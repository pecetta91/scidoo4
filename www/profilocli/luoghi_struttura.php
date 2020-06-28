<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../config/connecti.php';
require_once __DIR__ . '/../../config/funzioni.php';
$IDprenotazione = $_SESSION['IDstrpren'];
$IDstruttura = get_IDstruttura_from_IDpren($IDprenotazione);
$lang = $_SESSION['lang'] ?: 0;

$IDsotto_struttura = get_IDsotto_struttura_from_IDpren($IDprenotazione, $IDstruttura);
$dati_struttura = genera_sotto_strutture_lista($IDstruttura)[$IDsotto_struttura];

$lista_tipologie = '';
$luoghi = estrai_luoghi([], $IDstruttura);
if (!empty($luoghi)) {
	$tipologie = [];
	foreach ($luoghi as $dati) {
		if (!isset($tipologie[$dati['tipo_categoria']])) {
			$tipologie[$dati['tipo_categoria']] = ['categoria' => $dati['categoria'], 'lista' => []];
		}
		$tipologie[$dati['tipo_categoria']]['lista'][] = $dati;
	}

	foreach ($tipologie as $dati) {
		$luoghi = '';
		if (!empty($dati['lista'])) {
			foreach ($dati['lista'] as $val) {
				$foto = getfoto($val['ID'], 17);

				$riga_distanza = '';
				if (($val['latitudine'] != '') && ($val['longitudine'] != '')) {

					$distance_data = file_get_contents('https://maps.googleapis.com/maps/api/distancematrix/json?&language=IT&origins=' . $dati_struttura['latitudine'] . ',' . $dati_struttura['longitudine'] . '&destinations=' . $val['latitudine'] . ',' . $val['longitudine'] . '&key=AIzaSyAN6U_MYAHUznULQav0s897pKUx_Tt9EUo');
					$distance_arr = json_decode($distance_data);

					if ($distance_arr->status == 'OK') {
						$elements = $distance_arr->rows[0]->elements;
						if ($elements[0]->status != 'NOT_FOUND') {

							$distance = $elements[0]->distance->text;
							$duration = $elements[0]->duration->text;

							$riga_distanza = '<i class="fas fa-car" style="margin-right:5px;"></i> ' . $distance . ' - ' . $duration;
						}
					}
				}

				$luoghi .= '
			 	<li class="uk-width-3-4" onclick="navigation_ospite(18,{IDluogo:' . $val['ID'] . '})">
	                <div class="uk-card uk-card-default">
	                    <div class="uk-card-media-top" style="height:250px;background-image:url(' . base_url() . '/immagini/big' . $foto . ' );background-size:cover;background-position:center;background-repeat:no-repeat">
	                    </div>
	                    <div class="uk-card-body" style="padding: 20px 15px;">
	                        <h3 class="uk-card-title c000" style="font-size:13px;">' . $val['nome'] . '<div style="color:#80868b;font-size:11px;">' . $riga_distanza . '</div>

	                        </h3>
	                        <p style="font-size:12px;" class="uk-text-truncate">' . (isset($val['informazioni']['Descrizione']) ? strip_tags($val['informazioni']['Descrizione']['valore']) : '') . '</p>
	                    </div>
	                </div>
	        	 </li> ';
			}
		}

		$lista_tipologie .= '
		<div class="titolo_paragrafo"> 	<div>' . $dati['categoria'] . ' </div>  </div>
			<div class="uk-position-relative uk-visible-toggle uk-light" tabindex="-1" uk-slider="center: true;;autoplay:false;finite:true" style="padding:10px 0;margin-bottom:20px">
				    <ul class="uk-slider-items uk-grid">
					    ' . $luoghi . '
					</ul>
			</div>';

	}

}

$testo = $lista_tipologie;
echo $testo;
?>
