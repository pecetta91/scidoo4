<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';

$IDutente = $_SESSION['ID'];
$IDstruttura = $_SESSION['IDstruttura'];

$IDcategoria = $_POST['IDcategoria'] ?? 0;

$time = (isset($_SESSION['time_prezzi']) ? $_SESSION['time_prezzi'] : time_struttura());
$dataoggi = date('Y-m-d', $time);

$lista_tariffe = get_servizi_minstay();

$IDrestrmain = getrestrmain($IDstruttura);

$lista_categoria_channel_room = [];
$query = "
SELECT ct.occupazione,cr.IDcat,ctr.tariffaint FROM
channeltariffe as ctr
JOIN channelroomt as ct ON ctr.ID=ct.IDtariffa
JOIN channelroom as cr ON ct.IDroom=cr.ID
WHERE ctr.IDstr='$IDstruttura' AND cr.IDcat='$IDcategoria'
GROUP BY ct.occupazione,cr.IDcat,ct.IDtariffa ";
$result = mysqli_query($link2, $query);
while ($row = mysqli_fetch_row($result)) {

	$IDtariffa = $row['2'];
	$occupazione = $row['0'];
	$lista_categoria_channel_room[$IDtariffa] = $occupazione;
}

$lista_persone = 0;
$query = "SELECT c.ID,MAX(c.capienza+c.persone_add) as somma, MAX((CASE WHEN a.cap<=0 THEN c.capienza ELSE a.cap END)+(CASE WHEN a.maxcap<0 THEN 0 ELSE a.maxcap END))
FROM categorie as c
LEFT JOIN appartamenti as a ON a.categoria=c.ID
WHERE c.IDstr='$IDstruttura' AND c.ID='$IDcategoria' AND c.tipo='0' GROUP BY c.ID ORDER BY c.ordine";
$result = mysqli_query($link2, $query);
while ($row = mysqli_fetch_row($result)) {

	$capienza = $row['1'];
	$capienza_max = $row['2'];
	if ($capienza_max > $capienza) {$capienza = $capienza_max;}
	$lista_persone = $capienza;
}

$IDstag_calcolo = [];
$query = "SELECT IDclas,data FROM prezzi WHERE  data='$dataoggi' AND IDstr='$IDstruttura'  AND IDcat='$IDcategoria'  ORDER BY IDserv";
$result = mysqli_query($link2, $query);
while ($row = mysqli_fetch_row($result)) {

	$IDstag_calcolo[] = $row['0'];
}

$GLOBALS['g_max_notti_calcolo_prezzitable'] = 1;
$checkout = $time + 86400;
$lista_prezzi = [];
foreach ($lista_tariffe[1] as $IDservizio => $info) {

	for ($occupazione = 1; $occupazione <= $lista_persone; $occupazione += 1) {
		$flag_giornalieri = 1;
		$arrrestr = array();
		for ($c = 0; $c < $occupazione; $c++) {
			$arrrestr[] = $IDrestrmain;
		}
		$no_sconti = 1;
		$IDrestrtxt = implode(',', $arrrestr);
		$plus_supplementi = 1;
		$solo_supplementi_giornalieri = 1;
		$GLOBALS['cache']['prezzo_supplementi'] = 0;

		$arr_prezzo = calcola_prezzo_booking_engine($IDservizio, $IDrestrtxt, $IDcategoria, 1, $IDstruttura, $time, $checkout, $flag_giornalieri, $no_sconti, $plus_supplementi, $solo_supplementi_giornalieri);

		foreach ($GLOBALS['g_arr_prezzo'] as $time => $dato) {
			$prezzo = array_sum($dato);
			$lista_prezzi[$occupazione][$IDservizio]['prezzo'] = $prezzo;
			$lista_prezzi[$occupazione][$IDservizio]['modificato'] = 0;
			//$prezzi[$IDcat][$occupazione][$time] = [$prezzo, 0, 1];
		}
		unset($GLOBALS['g_arr_prezzo']);
	}
}

$query = "SELECT prezzo,IDcat,occupazione,IDserv FROM prezzi_servizio_giornalieri WHERE IDstr='$IDstruttura'   AND IDcat='$IDcategoria'   AND data='$dataoggi' ";
$result = $link2->query($query);
while ($row = $result->fetch_row()) {
	$prezzo = $row['0'];

	$occupazione = $row['2'];
	$IDservizio = $row['3'];
	$lista_prezzi[$occupazione][$IDservizio]['prezzo'] = $prezzo;
	$lista_prezzi[$occupazione][$IDservizio]['modificato'] = 1;
}

$lista_prezzi_tariffe = [];
$query = "SELECT prezzo,IDcat,occupazione,man,IDtariffa FROM prezzitariffe WHERE IDstr='$IDstruttura' AND IDcat='$IDcategoria'  AND data='$dataoggi'";
$result = $link2->query($query);
while ($row = $result->fetch_row()) {
	$prezzo = $row['0'];

	$occupazione = $row['2'];
	$man = $row['3'];
	$IDtariffa = $row['4'];

	$lista_prezzi_tariffe[$occupazione][$IDtariffa]['prezzo'] = $prezzo;
	//$lista_prezzi_tariffe[$occupazione][$IDtariffa]['manuale'] = $man;
	//$prezzi[$row[3]][$row[4]][$time0_in][$IDtariffa] = [$row[2], $row[0], $row[5]];
}

$mostra_avanzato = 1;
$div_tab_prezzi = '';
if (!empty($lista_tariffe)) {

	foreach ($lista_tariffe as $ordine => $value) {
		foreach ($value as $IDtariffa => $dati_tariffa) {
			if (($mostra_avanzato == 0) && ($IDtariffa != 0)) {continue;}
			$tipo_servizio = $dati_tariffa['tiposerv'];

			$prezzo_div = '';
			if (($lista_persone) && ($IDtariffa != 0)) {
				$prezzo_persone = '';

				if ($tipo_servizio == 1) {

					for ($persona = 1; $persona <= $lista_persone; $persona += 1) {

						$prezzo = (isset($lista_prezzi[$persona][$IDtariffa]['prezzo']) ? $lista_prezzi[$persona][$IDtariffa]['prezzo'] : 0);

						$prezzo_persone .= ' <div class="div_list_uk uk_grid_div" uk-grid >
									    <div class="uk-width-1-2 lista_grid_nome uk-first-column"><i class="fas fa-user"></i> x ' . $persona . '</div>
									    <div class="uk-width-expand uk-text-right lista_grid_right">
									    <input class="uk-input input_cli  uk-form-small uk-text-right"
									     style="border: 1px solid#e1e1e1;  border-radius: 3px;' . ($lista_prezzi[$persona][$IDtariffa]['modificato'] == 1 ? 'background:#fbefa4' : '') . ' " type="text" value="' . $prezzo . '"  onchange="
								     modifica_prezzi(6,{IDtariffa:' . $IDtariffa . ',IDcategoria:' . $IDcategoria . ',time:' . $time . ',tipo_tariffa:' . $tipo_servizio . ',occupazione:' . $persona . '},this,11);">  </div>
									</div>  ';
					}
				} else {

					if (isset($lista_categoria_channel_room[$IDtariffa])) {

						$occupazione = $lista_categoria_channel_room[$IDtariffa];
						$prezzo = (isset($lista_prezzi_tariffe[$occupazione][$IDtariffa]['prezzo']) ? $lista_prezzi_tariffe[$occupazione][$IDtariffa]['prezzo'] : 0);
						$prezzo_persone .= '
								<div class="div_list_uk uk_grid_div" uk-grid >
								    <div class="uk-width-1-2 lista_grid_nome uk-first-column"><i class="fas fa-user"></i> x ' . $occupazione . '</div>
								    <div class="uk-width-expand uk-text-right lista_grid_right">
								       <input class="uk-input input_cli  uk-form-small uk-text-right"
									     style="border: 1px solid#e1e1e1;  border-radius: 3px;" type="text" value="' . $prezzo . '"  onchange="
								     modifica_prezzi(6,{IDtariffa:' . $IDtariffa . ',IDcategoria:' . $IDcategoria . ',time:' . $time . ',tipo_tariffa:' . $tipo_servizio . ',occupazione:' . $occupazione . '},this,11);">
								    </div>
								</div>  ';
					}

				}

				$div_tab_prezzi .= '
						<div style="margin-bottom:10px;border-bottom:1px solid #e1e1e1;"
						class="div_componente_prezzo_giorno" data-categoria="' . $IDcategoria . '" data-tariffa="' . $IDtariffa . '" data-tipo="prezzo">
							<strong style="color:#000">' . $dati_tariffa['nome'] . '</strong>

									' . $prezzo_persone . '
						</div>';

			}

		}
	}
}

//prezzi
echo $div_tab_prezzi;

?>
