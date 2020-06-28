<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';

$IDstruttura = $_SESSION['IDstruttura'];

$dati = $_POST['dati'] ?? [];

$IDaddebito = $dati['IDaddebito'];
$tipo_riferimento = $dati['tipo_riferimento'] ?? 0;

$data_modifica = (isset($_POST['data_modifica']) ? $_POST['data_modifica'] : 0);

$time0 = time_struttura();
$arrset = [];

switch ($tipo_riferimento) {
case 0:
	$query = "SELECT p.ID,p.time,p.modi,p.IDpers,p.sala,p.extra,p.IDpren FROM prenextra as p
	JOIN prenextra2 as p2 ON  p2.IDprenextra=p.ID WHERE p.IDstruttura=$IDstruttura AND p.ID=$IDaddebito GROUP BY p.ID";

	$result = $link2->query($query);
	$row = mysqli_fetch_row($result);
	$time_servizio = $row[1];
	$modi = $row[2];
	$IDpersonale = $row[3];
	$IDsala = $row[4];
	$IDservizio = $row[5];
	$IDriferimento = $row[6];

	$prenotazione = get_prenotazioni(['0' => ['IDprenotazione' => $IDriferimento]])['dati'][$IDriferimento];
	$notti = $prenotazione['notti'];
	$checkin = $prenotazione['checkin'];
	$checkout = $prenotazione['checkout'];

	break;

case 1:

	$query = "SELECT o.ID,o.time,o.modi,o.IDpers,o.IDsala,o.IDserv FROM oraripren AS o
		JOIN oraripren2 AS o2 ON o2.IDoraripren=o.ID WHERE o.IDstruttura=$IDstruttura AND o.ID= $IDaddebito   GROUP BY o.ID";
	$result = $link2->query($query);
	$row = mysqli_fetch_row($result);
	$time_servizio = $row[1];
	$modi = $row[2];
	$IDpersonale = $row[3];
	$IDsala = $row[4];
	$IDservizio = $row[5];
	$IDriferimento = $row[6];

	$richiesta = get_richieste(['0' => ['ID' => $IDriferimento]], $IDstruttura, [])[$IDriferimento];
	$notti = $richiesta['notti'];
	$checkin = $richiesta['checkin'];
	$checkout = $richiesta['checkout'];

	break;
}

$time_selezionato = time0($time_servizio);
if ($data_modifica) {
	$time_selezionato = time0($data_modifica);
	$time_servizio = 0;
}

$dati_serv = get_info_from_IDserv($IDservizio, null, $IDstruttura);

$nome_servizio = $dati_serv['nome_servizio'];

$lista_utilizzo = stato_utilizzo_servizio($IDstruttura, $dati_serv, $checkin, $checkout, $IDaddebito, $tipo_riferimento);

$lista_giorni = [];
$lista_presenza = [];
if ($notti) {
	$checkin0 = time0($checkin);
	$checkout0 = time0($checkout);
	for ($time_data = $checkin0; $time_data <= $checkout0; $time_data += 86400) {
		if ($time_data >= $time0) {
			$presenza = 0;
			if (isset($lista_utilizzo[$time_data])) {
				foreach ($lista_utilizzo[$time_data] as $dati) {
					if (!empty($dati['persone'])) {
						foreach ($dati['persone'] as $valore) {
							if ($valore > 0) {
								$presenza = 1;
								break;
							}
						}
					}
					if ($presenza) {break;}
				}
			}

			if ($presenza) {
				$lista_presenza[$time_data] = 1;
			}

			$lista_giorni[$time_data] = dataita($time_data);
		}
	}

	$riga_data = '
	<div id="giorni_servizio" style="display:none;"><ul class="uk-list uk-list-divider uk-picker-bot" style="padding:5px 20px;"
			onchange="(r)=>{disponibilita_servizio({IDaddebito:' . $IDaddebito . ',tipo_riferimento:' . $tipo_riferimento . '},r)}">' . genera_select_uikit($lista_giorni, $time_selezionato) . '</ul>
	</div>
	<div class="div_list_uk uk_grid_div uk-grid" uk-grid=""  onclick="carica_content_picker(' . "'giorni_servizio'" . ')" >
	    <div class="uk-width-1-3 lista_grid_nome uk-first-column">Data</div>
	    <div class="uk-width-expand uk-text-right lista_grid_right"> ' . dataita($time_selezionato) . '  <span uk-icon="chevron-right" class="uk-icon"></span> </div>
	</div>';

} else {
	$riga_data = '
	<div class="div_list_uk uk_grid_div uk-grid" uk-grid="" >
	    <div class="uk-width-1-3 lista_grid_nome uk-first-column">Data</div>
	    <div class="uk-width-expand uk-text-right lista_grid_right"> ' . dataita($time_servizio) . '  </div>
	</div>';
}

$orari = personale_disponibile_servizio($IDstruttura, $IDservizio, $time_selezionato, $IDriferimento, $tipo_riferimento);

$time_option = '';
if (!empty($orari)) {
	foreach ($orari as $time_dispo => $dati) {

		$time_giorno = $time_selezionato + $time_dispo;

		$time_option .= '<li onclick="chiudi_picker();modifica_orario(this)" value="' . $time_giorno . '" data-sala="' . $dati['IDsala'] . '"  data-personale="' . $dati['IDpersonale'] . '">' . date('H:i', $time_giorno) . ' </li>';
	}
}

$testo = '
<input type="hidden" id="IDaddebito" value="' . $IDaddebito . '">
<input type="hidden" id="tipo_riferimento" value="' . $tipo_riferimento . '">

<div class="div_uk_divider_list" style="margin-top:0px !important;" aria-expanded="true">' . $nome_servizio . '</div>

' . $riga_data . '



' . (isset($lista_presenza[$time_selezionato]) ? '<div style="    margin: 10px; color: #c42424;">Servizio presente in questo giorno </div>' : '') . '

<div id="time_servizio" style="display:none;"><ul class="uk-list uk-list-divider uk-picker-bot" style="padding:5px 20px;" >
' . $time_option . '</ul>

</div>
<div class="div_list_uk uk_grid_div " uk-grid   onclick="carica_content_picker(' . "'time_servizio'" . ')" >
    <div class="uk-width-1-3 lista_grid_nome uk-first-column">Ora</div>
    <div class="uk-width-expand uk-text-right lista_grid_right"> ' . ($time_servizio ? date('H:i', $time_servizio) : '--') . ' <span uk-icon="chevron-right" class="uk-icon"></span></div>
</div>


';

/*

<div class="div_list_uk uk_grid_div uk-grid" uk-grid="" >
<div class="uk-width-1-3 lista_grid_nome uk-first-column">Sala</div>
<div class="uk-width-expand uk-text-right lista_grid_right">   <span uk-icon="chevron-right" class="uk-icon"></span></div>
</div>
 */

$picker = '
<div class="nav navbar_picker_flex" >
 	<div onclick="chiudi_picker()"><i class="fas fa-times icona_chiudi" ></i></div>
 	<div style="margin-top:5px;padding-right:10px">

 	</div>
</div>



<div class="content" style="margin-top:0">
	<div id="dettagli_tab" style="padding-top:5px;">
		' . $testo . '

	</div>
</div>';

echo $picker;
/*
return;

$query = "SELECT pr.extra,pr.time,pr.IDpren,pr.IDtipo,pr.sottotip,pr.modi,pr.esclusivo, pr.durata,pr.tipolim,pr.IDpers,pr.sala,s.servizio,COUNT(p2.IDprenextra) FROM prenextra as pr
JOIN servizi as s ON s.ID=pr.extra
JOIN prenextra2 as p2 ON p2.IDprenextra=pr.ID
WHERE pr.ID='$IDprenextra'   GROUP BY pr.ID";
$result = mysqli_query($link2, $query);
$row = mysqli_fetch_row($result);
$IDservizio = $row['0'];
$time_servizio = $row['1'];
$IDprenotazione = $row['2'];
$IDtipo = $row['3'];
$IDsottotipologia = $row['4'];
$modi = $row['5'];
$esclusivo = $row['6'];
$durata = $row['7'];
$tipolim = $row['8'];
$IDpersonale = $row['9'];
$IDsala = $row['10'];
$nome_servizio = $row['11'];
$persone = $row['12'];

$orari_disponibili = orari5($time0, $persone, $IDservizio, $IDstruttura, $IDprenextra, 1);

$prenotazione = get_prenotazioni(['0' => ['IDprenotazione' => $IDprenotazione]])['dati'][$IDprenotazione];
$notti = $prenotazione['notti'];
$checkin = $prenotazione['checkin'];
$checkout = $prenotazione['checkout'];

$time_selezionato = time0($time_servizio);
if ($data_modifica) {
$time_selezionato = time0($data_modifica);
$time_servizio = 0;
}

//print_r($orari_disponibili);
$lista_giorni = [];
if ($notti) {
$checkin0 = time0($checkin);
$checkout0 = time0($checkout);
for ($time_data = $checkin0; $time_data <= $checkout0; $time_data += 86400) {
if ($time_data >= $time0) {
$lista_giorni[$time_data] = dataita($time_data);
}
}

$riga_data = '

<div id="giorni_servizio" style="display:none;"><ul class="uk-list uk-list-divider uk-picker-bot" style="padding:5px 20px;" onchange="(r)=>{disponibilita_servizio(' . $IDprenextra . ',r)}">' . genera_select_uikit($lista_giorni, $time_selezionato, []) . '</ul></div>
<div class="div_list_uk uk_grid_div uk-grid" uk-grid=""  onclick="carica_content_picker(' . "'giorni_servizio'" . ')" >
<div class="uk-width-1-3 lista_grid_nome uk-first-column">Data</div>
<div class="uk-width-expand uk-text-right lista_grid_right"> ' . dataita($time_selezionato) . '  <span uk-icon="chevron-right" class="uk-icon"></span> </div>
</div>';

} else {
$riga_data = '
<div class="div_list_uk uk_grid_div uk-grid" uk-grid="" >
<div class="uk-width-1-3 lista_grid_nome uk-first-column">Data</div>
<div class="uk-width-expand uk-text-right lista_grid_right"> ' . dataita($time_servizio) . '  </div>
</div>';
}

$step_orario = 1800;
$orari = [];
$query = "SELECT orarioi,orariof,ID FROM orarisotto WHERE IDsotto='$IDsottotipologia' ORDER BY orarioi";
$result = mysqli_query($link2, $query);
while ($row = mysqli_fetch_row($result)) {
$orai = $time_selezionato + $row['0'];
$oraf = $time_selezionato + $row['1'];
for ($orai; $orai <= $oraf; $orai += $step_orario) {
if (!isset($orari[$orai])) {
$orari[$orai] = date('H:i', $orai);
}
}
}

$query = "SELECT os.orarioi,os.orariof FROM assocorario as a
JOIN orarisotto as os ON os.ID=a.IDorarios
WHERE a.IDserv='$IDservizio' AND os.IDstr='$IDstruttura'";
$result = mysqli_query($link2, $query);
while ($row = mysqli_fetch_row($result)) {
$orai = $time_selezionato + $row['0'];
$oraf = $time_selezionato + $row['1'];
for ($orai; $orai <= $oraf; $orai += $step_orario) {

if (!isset($orari[$orai])) {
$orari[$orai] = date('H:i', $orai);
}

}
}

//print_r($orari);
$testo = '
<input type="hidden" id="IDprenextra" value="' . $IDprenextra . '">
<div class="div_uk_divider_list" style="margin-top:0px !important;" aria-expanded="true">' . $nome_servizio . '</div>

' . $riga_data . '

<div id="time_servizio" style="display:none;"><ul class="uk-list uk-list-divider uk-picker-bot" style="padding:5px 20px;" onchange="(r)=>{console.log(r)}">' . genera_select_uikit($orari, $time_servizio, []) . '</ul></div>
<div class="div_list_uk uk_grid_div " uk-grid   onclick="carica_content_picker(' . "'time_servizio'" . ')" >
<div class="uk-width-1-3 lista_grid_nome uk-first-column">Ora</div>
<div class="uk-width-expand uk-text-right lista_grid_right"> ' . ($time_servizio ? date('H:i', $time_servizio) : '--') . ' <span uk-icon="chevron-right" class="uk-icon"></span></div>
</div>

<div class="div_list_uk uk_grid_div uk-grid" uk-grid="" >
<div class="uk-width-1-3 lista_grid_nome uk-first-column">Sala</div>
<div class="uk-width-expand uk-text-right lista_grid_right">   <span uk-icon="chevron-right" class="uk-icon"></span></div>
</div>

';

$picker = '
<div class="nav navbar_picker_flex" >
<div onclick="chiudi_picker()"><i class="fas fa-times icona_chiudi" ></i></div>
<div style="margin-top:5px;padding-right:10px">

</div>
</div>

<div class="content" style="margin-top:0">
<div id="dettagli_tab" style="padding-top:5px;">
' . $testo . '

</div>
</div>';

echo $picker;*/
?>

<script>

	function modifica_orario(el){
		var IDaddebito=$('#IDaddebito').val()
		var tipo_riferimento=$('#tipo_riferimento').val();

		var time=$(el).attr('value');
		var sala=$(el).data('sala');
		var personale=$(el).data('personale')


		mod_riferimento(3,[IDaddebito,tipo_riferimento],sala+'_'+time+'_'+personale,10,()=>{

			chiudi_picker();disponibilita_servizio({'IDaddebito':IDaddebito,'tipo_riferimento':tipo_riferimento})
		});
	}
</script>
