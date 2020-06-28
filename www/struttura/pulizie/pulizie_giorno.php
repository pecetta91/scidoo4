<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';

$IDutente = $_SESSION['ID'];
$IDstruttura = $_SESSION['IDstruttura'];

$dataoggi = date('Y-m-d', time_struttura());
$time = strtotime($dataoggi);

$arr_dati = (isset($_POST['arr_dati']) ? $_POST['arr_dati'] : []);

$data = $arr_dati['time'] ?? 0;

if ($data == 0) {
	$time = (isset($_SESSION['tempo_pul']) ? $_SESSION['tempo_pul'] : time_struttura());
	$dataoggi = date('Y-m-d', $time);
} else {
	$time = strtotime(convertiData($data));
	$dataoggi = date('Y-m-d', $time);
}

$_SESSION['tempo_pul'] = $time;
$_SESSION['pulizia_selezionata'] = 0;

$statocol = array('0bcd5e', 'f63535', 'f5d914');
$arrnome = array('Alloggi Pronti', 'Alloggi Occupati', 'Alloggi da Preparare');
$statoarr = array('Pronto', 'Occupato', 'Da Preparare');
$arricone = array('ion-android-happy', 'ion-android-lock', 'ion-paintbrush');
$arr_colori_checkin = array('', 'ab141b', '03980e');

$time_inizio = strtotime($dataoggi);
$time_fine = strtotime($dataoggi) + 86400;
$time_ieri = $time_inizio - 86400;

$lista_IDprenotazioni = [];
$query = "SELECT p.IDpren,p.sala,p.time,pr.IDpren
FROM prenextra as p
LEFT JOIN prenextra as pr ON  pr.time>='$time_ieri'  AND pr.time<'$time_inizio' AND pr.tipolim='4' AND pr.modi>='0' AND pr.IDstruttura='$IDstruttura' AND pr.IDpren=p.IDpren
WHERE p.tipolim='4'  AND p.time>='$time_inizio'  AND p.time<'$time_fine' AND p.modi>='0'  AND p.IDstruttura='$IDstruttura' AND
(pr.IDpren IS NULL OR pr.sala!=p.sala) GROUP BY p.IDpren";
$result = mysqli_query($link2, $query);
while ($row = mysqli_fetch_row($result)) {
	$lista_IDprenotazioni[] = $row['0'];
	$arr_pren_arrivo[$row['1']]['IDprenotazione'] = $row['0']; //IDpren
	$arr_pren_arrivo[$row['1']]['cambio'] = $row['2'] ?? '0'; //cambio alloggio
}

$implode_prenotazioni = '0';
if (!empty($lista_IDprenotazioni)) {
	$implode_prenotazioni = implode(',', $lista_IDprenotazioni);
}

$query = "SELECT pr.IDv,p.sala FROM prenextra as p
JOIN prenotazioni as pr ON pr.IDv=p.IDpren
WHERE p.IDstruttura='$IDstruttura' AND p.time>=$time_inizio AND p.time<$time_fine AND p.tipolim='4' AND p.modi>='0' AND p.sala!='0' AND p.IDpren NOT IN($implode_prenotazioni) GROUP BY pr.ID";
$result = mysqli_query($link2, $query);
while ($row = mysqli_fetch_row($result)) {
	$lista_IDprenotazioni[] = $row['0'];
	$array_pernottamento_oggi[$row['1']]['IDprenotazione'] = $row['0'];
}

$partenze = [];
$query = "SELECT pr.IDv,p.sala FROM prenextra as p
JOIN prenotazioni as pr ON pr.IDv=p.IDpren  AND pr.checkout>=$time_inizio AND pr.checkout<$time_fine
WHERE p.IDstruttura='$IDstruttura' AND p.time>='$time_ieri' AND p.time<'$time_inizio' AND p.modi>='0' AND p.tipolim='4' AND p.sala!='0'   GROUP BY pr.ID";

$result = mysqli_query($link2, $query);
while ($row = mysqli_fetch_row($result)) {
	$partenze[$row['1']]['IDprenotazione'] = $row['0'];
	$lista_IDprenotazioni[] = $row['0'];
}

$implode_prenotazioni = '0';
if (!empty($lista_IDprenotazioni)) {
	$implode_prenotazioni = implode(',', $lista_IDprenotazioni);
}

$informazioni_pulizia = [];
$prenotazioni = [];
if (!empty($lista_IDprenotazioni)) {
	$prenotazioni = get_prenotazioni(['0' => ['IDprenotazione' => $lista_IDprenotazioni]])['dati'];
	foreach ($prenotazioni as $val) {
		$IDprenotazione = $val['ID'];
		$preparazione_pren = [];
		$arr_letti = get_preparazione_prenotazione($IDprenotazione, $IDstruttura);
		if (!empty($arr_letti)) {

			foreach ($arr_letti as $IDletto => $prep) {
				if ($prep['numero'] == 0) {continue;}
				$preparazione_pren[] = str_repeat($prep['codice'], $prep['numero']);

			}
			$informazioni_pulizia[$IDprenotazione]['preparazione'] = ' ' . (isset($preparazione_pren) ? implode('', $preparazione_pren) : '');
		}
	}
}

$servizi_pulizia = [];
$query = "SELECT p.IDpren,p.extra,s.servizio FROM prenextra as p
JOIN prenextra2 as p2 ON p2.IDprenextra=p.ID AND p2.pacchetto='0' AND p2.qta>'0'
JOIN servizi as s ON s.ID=p.extra
WHERE p.IDstruttura='$IDstruttura' AND p.IDpren IN ($implode_prenotazioni) AND p.IDtipo='5' AND FROM_UNIXTIME(p.time,'%Y-%m-%d')='$dataoggi' GROUP BY p.time ORDER BY p.time";
$result = mysqli_query($link2, $query);
while ($row = mysqli_fetch_row($result)) {
	$IDprenotazione = $row['0'];
	$informazioni_pulizia[$IDprenotazione]['servizi'][] = $row['2'];
}

$query = "SELECT p.ID,pc.IDcat,c.nome,c.colore FROM personale as p
JOIN personale_categorie as pc ON pc.IDpers=p.ID
JOIN categorie as c ON c.ID=pc.IDcat
WHERE p.IDstr='$IDstruttura' AND p.IDuser='$IDutente' ";
$result = mysqli_query($link2, $query);
while ($row = mysqli_fetch_row($result)) {
	$array_categ[] = $row['1'];
}

$qadd = "";
if (!empty($array_categ)) {
	$catlist = implode(',', $array_categ);
	$qadd = " AND categoria IN ($catlist)";
}

$lista_appartamenti_txt = '';
$stampa_html = ['arrivi' => '', 'conta_arrivi' => 0, 'partenze' => '', 'conta_partenze' => 0, 'permanenze' => '', 'conta_permanenze' => 0, 'servizi' => '', 'conta_servizi' => 0];

$query = "SELECT ID,nome,stato,categoria,IDpiano FROM appartamenti WHERE IDstruttura='$IDstruttura' AND attivo='1' $qadd ORDER BY ordine";
$result = mysqli_query($link2, $query);
while ($row = mysqli_fetch_row($result)) {
	$IDappartamento = $row['0'];
	$nome_alloggio = $row['1'];
	$stato = $row['2'];
	$piano = $row['4'];
	$funzione_pulsanti_action = '';
	foreach ($statoarr as $key => $dato) {
		if ($key != $stato) {
			if ($key != 1) {
				$funzione_pulsanti_action .= '<li  class="stato_pulizia" data-tipo="' . $key . '" style="color:#' . $statocol[$key] . '">' . $dato . '</li>';
			}
		}
	}

	$prenotazioni_appartamento = '';

	$info_appartamento = '
	<div class="uk-width-1-4  nome_pul_gior"  style="background:#' . $statocol[$stato] . ';"  >
			<div class="nome_alloggio_gior">' . $nome_alloggio . '</div>
	</div>';
	if (isset($arr_pren_arrivo[$IDappartamento])) {
		$stampa_html['conta_arrivi']++;

		$IDprenotazione = $arr_pren_arrivo[$IDappartamento]['IDprenotazione'];
		$funzione_pulsanti_action .= '<li  class="prenotazione_pulizia" data-prenotazione="' . $IDprenotazione . '"  >Prenotazione Arrivo</li>';

		$checkin_txt = $prenotazioni[$IDprenotazione]['orario_checkin'];

		$preparazione_arrivo = (isset($informazioni_pulizia[$IDprenotazione]['preparazione']) ? ' <i class="fas fa-bed"></i>' . $informazioni_pulizia[$IDprenotazione]['preparazione'] : '');

		$stampa_html['arrivi'] .= '
			<div uk-grid class="row_pul_gior statocamere statocam' . $stato . ' piano' . $piano . '"  onclick="pulsanti_pulizie(this)" data-appartamento="' . $IDappartamento . '" >
					' . $info_appartamento . '
				<div class="uk-width-expand riepilogo_pulizie_div" style="padding:0 5px;">

					<div class="c000" style="font-weight:600;font-size:14px;">' . $prenotazioni[$IDprenotazione]['nome_cliente'] . ' <div style="float:right;font-weight:400"><i class="fas fa-ellipsis-h"></i></div>
					</div>

					<div style="line-height:18px">
					<span class="lista_icone_pul">	<i class="fas fa-moon"></i>	' . $prenotazioni[$IDprenotazione]['notti'] . '  <i class="fas fa-user"></i> ' . $prenotazioni[$IDprenotazione]['persone'] . '
						 ' . $preparazione_arrivo . '  <i class="far fa-clock"></i> ' . $checkin_txt . '</span>
							<div style="float:right;font-size:13px;color:#333">' . (isset($informazioni_pulizia[$IDprenotazione]['servizi']) ? implode(',', $informazioni_pulizia[$IDprenotazione]['servizi']) : '') . '</div>
					</div>

				</div>
			</div>';

		$prenotazioni_appartamento = '<td style="width:50%;vertical-align:top;">
			<div class="c000 testo_elissi_standard" style="font-weight:600;font-size:14px;max-width:10ch"  >' . $prenotazioni[$IDprenotazione]['nome_cliente'] . ' </div>
			<div style="line-height:18px"><span class="lista_icone_pul">
			<i class="fas fa-moon"></i> ' . $prenotazioni[$IDprenotazione]['notti'] . '  <i class="fas fa-user"></i> ' . $prenotazioni[$IDprenotazione]['persone'] . '
			' . ($informazioni_pulizia[$IDprenotazione]['preparazione'] ? ' <i class="fas fa-bed"></i>' . $informazioni_pulizia[$IDprenotazione]['preparazione'] : '') . ' </span></div>
			</td>';

		if (!empty($prenotazioni[$IDprenotazione]['servizi'])) {
			$stampa_html['conta_servizi']++;
			$stampa_html['servizi'] .= '
			<div uk-grid class="row_pul_gior statocamere statocam' . $stato . ' piano' . $piano . '"  onclick="pulsanti_pulizie(this)" data-appartamento="' . $IDappartamento . '" >
					' . $info_appartamento . '
				<div class="uk-width-expand riepilogo_pulizie_div" style="padding:0 5px;">
					<div class="c000" style="font-weight:600;font-size:14px;">' . $prenotazioni[$IDprenotazione]['nome_cliente'] . ' <div style="float:right;font-weight:400"><i class="fas fa-ellipsis-h"></i></div>
					</div>

					<div style="line-height:18px">
						<span class="lista_icone_pul"><i class="fas fa-moon"></i>	' . $prenotazioni[$IDprenotazione]['notti'] . '  <i class="fas fa-user"></i> ' . $prenotazioni[$IDprenotazione]['persone'] . '
						  ' . $preparazione_arrivo . ' </span>
							<div style="float:right;font-size:13px;color:#333">' . (isset($informazioni_pulizia[$IDprenotazione]['servizi']) ? implode(',', $informazioni_pulizia[$IDprenotazione]['servizi']) : '') . '</div>
					</div>
				</div>
			</div> ';

		}

	}

	if (isset($array_pernottamento_oggi[$IDappartamento])) {
		$stampa_html['conta_permanenze']++;
		$IDprenotazione = $array_pernottamento_oggi[$IDappartamento]['IDprenotazione'];

		$funzione_pulsanti_action .= '<li  class="prenotazione_pulizia" data-prenotazione="' . $IDprenotazione . '"  >Prenotazione Permanenza</li>';

		$preparazione_pernottamento = (isset($informazioni_pulizia[$IDprenotazione]['preparazione']) ? ' <i class="fas fa-bed"></i>' . $informazioni_pulizia[$IDprenotazione]['preparazione'] : '');
		$stampa_html['permanenze'] .= '
			<div uk-grid class="row_pul_gior statocamere statocam' . $stato . ' piano' . $piano . '"  onclick="pulsanti_pulizie(this)" data-appartamento="' . $IDappartamento . '" >
				' . $info_appartamento . '
				<div class="uk-width-expand riepilogo_pulizie_div" style="padding:0 5px;">

						<div class="c000" style="font-weight:600;font-size:14px;">' . $prenotazioni[$IDprenotazione]['nome_cliente'] . ' <div style="float:right;font-weight:400"><i class="fas fa-ellipsis-h"></i></div>
				 		</div>

						<div style="line-height:18px">
							<span class="lista_icone_pul"><i class="fas fa-moon"></i>	' . $prenotazioni[$IDprenotazione]['notti'] . '  <i class="fas fa-user"></i> ' . $prenotazioni[$IDprenotazione]['persone'] . '
							  ' . $preparazione_pernottamento . ' </span>
								<div style="float:right;font-size:13px;color:#333">' . (isset($informazioni_pulizia[$IDprenotazione]['servizi']) ? implode(',', $informazioni_pulizia[$IDprenotazione]['servizi']) : '') . '</div>
						</div>

					</div>
			</div>';

		$prenotazioni_appartamento = '<td style="width:100%;vertical-align:top;">
			<div class="c000" style="font-weight:600;font-size:14px;">' . $prenotazioni[$IDprenotazione]['nome_cliente'] . ' </div>
			<div style="line-height:18px"><span class="lista_icone_pul">
	    	<i class="fas fa-moon"></i> 	' . $prenotazioni[$IDprenotazione]['notti'] . '  <i class="fas fa-user"></i> ' . $prenotazioni[$IDprenotazione]['persone'] . '   ' . ($informazioni_pulizia[$IDprenotazione]['preparazione'] ? ' <i class="fas fa-bed"></i>' . $informazioni_pulizia[$IDprenotazione]['preparazione'] : '') . ' </span></div>
			 </td>';

		if (!empty($prenotazioni[$IDprenotazione]['servizi'])) {
			$stampa_html['conta_servizi']++;
			$stampa_html['servizi'] .= '
			<div uk-grid class="row_pul_gior statocamere statocam' . $stato . ' piano' . $piano . '"  onclick="pulsanti_pulizie(this)" data-appartamento="' . $IDappartamento . '" >
					' . $info_appartamento . '
				<div class="uk-width-expand riepilogo_pulizie_div" style="padding:0 5px;">
					<div class="c000" style="font-weight:600;font-size:14px;">' . $prenotazioni[$IDprenotazione]['nome_cliente'] . ' <div style="float:right;font-weight:400"><i class="fas fa-ellipsis-h"></i></div>
					</div>

					<div style="line-height:18px">
						<span class="lista_icone_pul">	<i class="fas fa-moon"></i>	' . $prenotazioni[$IDprenotazione]['notti'] . '  <i class="fas fa-user"></i> ' . $prenotazioni[$IDprenotazione]['persone'] . '
					 ' . $preparazione_pernottamento . '  </span>
							<div style="float:right;font-size:13px;color:#333">' . (isset($informazioni_pulizia[$IDprenotazione]['servizi']) ? implode(',', $informazioni_pulizia[$IDprenotazione]['servizi']) : '') . '</div>
					</div>
				</div>
			</div> ';
		}
	}

	if (isset($partenze[$IDappartamento])) {
		$IDprenotazione = $partenze[$IDappartamento]['IDprenotazione'];
		$stampa_html['conta_partenze']++;

		$funzione_pulsanti_action .= '<li  class="prenotazione_pulizia" data-prenotazione="' . $IDprenotazione . '"  >Prenotazione Partenza</li>';

		$preparazione_partenza = (isset($informazioni_pulizia[$IDprenotazione]['preparazione']) ? ' <i class="fas fa-bed"></i>' . $informazioni_pulizia[$IDprenotazione]['preparazione'] : '');

		$checkout_txt = $prenotazioni[$IDprenotazione]['orario_checkout'];

		$stampa_html['partenze'] .= '
		<div uk-grid class="row_pul_gior statocamere statocam' . $stato . ' piano' . $piano . '"  onclick="pulsanti_pulizie(this)" data-appartamento="' . $IDappartamento . '" >
					' . $info_appartamento . '
				<div class="uk-width-expand riepilogo_pulizie_div" style="padding:0 5px;">
					<div class="c000" style="font-weight:600;font-size:14px;">' . $prenotazioni[$IDprenotazione]['nome_cliente'] . ' <div style="float:right;font-weight:400"><i class="fas fa-ellipsis-h"></i></div>
					</div>

					<div style="line-height:18px">
					<span class="lista_icone_pul">	<i class="fas fa-moon"></i>	' . $prenotazioni[$IDprenotazione]['notti'] . '  <i class="fas fa-user"></i> ' . $prenotazioni[$IDprenotazione]['persone'] . '
					 ' . $preparazione_partenza . '   <i class="far fa-clock"></i> ' . $checkout_txt . '</span>
							<div style="float:right;font-size:13px;color:#333">' . (isset($informazioni_pulizia[$IDprenotazione]['servizi']) ? implode(',', $informazioni_pulizia[$IDprenotazione]['servizi']) : '') . '</div>
					</div>
				</div>
			</div> ';

		$prenotazioni_appartamento .= '<td style="width:50%;vertical-align:top;">
			<div class="c000 testo_elissi_standard" style="font-weight:600;font-size:14px;max-width:10ch"  >' . $prenotazioni[$IDprenotazione]['nome_cliente'] . ' </div>
			<div style="line-height:18px">
			<span class="lista_icone_pul">
			<i class="fas fa-moon"></i>  ' . $prenotazioni[$IDprenotazione]['notti'] . '  <i class="fas fa-user"></i> ' . $prenotazioni[$IDprenotazione]['persone'] . '  ' . ($informazioni_pulizia[$IDprenotazione]['preparazione'] ? ' <i class="fas fa-bed"></i>' . $informazioni_pulizia[$IDprenotazione]['preparazione'] : '') . ' </span></div>
		 </td>';

		if (!empty($prenotazioni[$IDprenotazione]['servizi'])) {
			$stampa_html['conta_servizi']++;
			$stampa_html['servizi'] .= '
			<div uk-grid class="row_pul_gior statocamere statocam' . $stato . ' piano' . $piano . '"  onclick="pulsanti_pulizie(this)" data-appartamento="' . $IDappartamento . '" >
					' . $info_appartamento . '
				<div class="uk-width-expand riepilogo_pulizie_div" style="padding:0 5px;">
					<div class="c000" style="font-weight:600;font-size:14px;">' . $prenotazioni[$IDprenotazione]['nome_cliente'] . ' <div style="float:right;font-weight:400"><i class="fas fa-ellipsis-h"></i></div>
					</div>

					<div style="line-height:18px">
						<span class="lista_icone_pul"> <i class="fas fa-moon"></i>	' . $prenotazioni[$IDprenotazione]['notti'] . '  <i class="fas fa-user"></i> ' . $prenotazioni[$IDprenotazione]['persone'] . '
					 ' . $preparazione_partenza . ' </span>
							<div style="float:right;font-size:13px;color:#333">' . (isset($informazioni_pulizia[$IDprenotazione]['servizi']) ? implode(',', $informazioni_pulizia[$IDprenotazione]['servizi']) : '') . '</div>
					</div>
				</div>
			</div> ';

		}
	}

	$lista_appartamenti_txt .= '
	<input type="hidden" value="' . base64_encode($funzione_pulsanti_action) . '" id="pulsanti' . $IDappartamento . '">
	<div uk-grid class="row_pul_gior statocamere statocam' . $stato . ' piano' . $piano . '"  onclick="pulsanti_pulizie(this)" data-appartamento="' . $IDappartamento . '"  >
			' . $info_appartamento . '
			<div class="uk-width-expand riepilogo_pulizie_div" style="padding:0 5px;">
				<table style="width:100%"><tr>	' . $prenotazioni_appartamento . '</tr></table>
			</div>
	</div>';

}

$testo = '
<div id="content_pulizie">

    <ul class="no_before uk_tab_pulizie"  uk-tab="connect: #switcher; animation: uk-animation-fade"    >
        <li class="uk-active" onclick=""><a href="#">Arrivi ' . ($stampa_html['conta_arrivi'] > 0 ? '<div class="numero_not_giorn">' . $stampa_html['conta_arrivi'] . '</div>' : '') . '</a></li>
        <li onclick=""><a href="#">Partenze ' . ($stampa_html['conta_partenze'] > 0 ? '<div class="numero_not_giorn">' . $stampa_html['conta_partenze'] . '</div>' : '') . '</a></li>
        <li onclick=""><a href="#">Permanenze ' . ($stampa_html['conta_permanenze'] > 0 ? '<div class="numero_not_giorn">' . $stampa_html['conta_permanenze'] . '</div>' : '') . '</a></li>
        <li onclick=""><a href="#">Servizi ' . ($stampa_html['conta_servizi'] > 0 ? '<div class="numero_not_giorn">' . $stampa_html['conta_servizi'] . '</div>' : '') . '</a></li>
        <li onclick=""><a href="#">Stanze </a></li>
    </ul>
 	<ul class="uk-switcher uk-margin"  id="switcher">
	    <li>' . $stampa_html['arrivi'] . '</li>
	    <li>' . $stampa_html['partenze'] . '</li>
	    <li>' . $stampa_html['permanenze'] . '</li>
	    <li>' . $stampa_html['servizi'] . '</li>
	    <li>' . $lista_appartamenti_txt . '</li>
 	</ul>
</div> ';

echo $testo;

?>


<style>
.uk-switcher .uk-grid{margin-left: 0 !important}

</style>
