<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../config/connecti.php';
require_once __DIR__ . '/../../config/funzioni.php';
$IDprenotazione = $_SESSION['IDstrpren'];
$IDstruttura = get_IDstruttura_from_IDpren($IDprenotazione);
$lang = $_SESSION['lang'] ?: 0;

$IDsotto_struttura = get_IDsotto_struttura_from_IDpren($IDprenotazione, $IDstruttura);
$dati_struttura = genera_sotto_strutture_lista($IDstruttura)[$IDsotto_struttura];
$testo = '';
$itinerari = estrai_itinerari_giornalieri([], $IDstruttura);
if (!empty($itinerari)) {
	$itinerari_txt = '';
	foreach ($itinerari as $dati) {
		$divfoto = '';
		$stringamarker = '';
		$stringapath = '';
		if (!empty($dati['luoghi'])) {
			$numero_luoghi = 0;

			foreach ($dati['luoghi'] as $luogo) {
				if ($numero_luoghi < 2) {
					$foto = getfoto($luogo['ID'], 17);
					$style = 'height:125px;';
					if ($numero_luoghi == 0) {
						$style = 'height:123px;margin-bottom:2px; ';
					}
					$divfoto .= '<div style="background:url(' . base_url() . '/immagini/big' . $foto . ') center center / cover no-repeat;background-size:cover;' . $style . '"></div>';
				}

				if (end($dati['luoghi']) == $luogo) {
					$stringamarker .= 'markers=color:red|label:A|' . $luogo['latitudine'] . ',' . $luogo['longitudine'];
					$stringapath .= $luogo['latitudine'] . ',' . $luogo['longitudine'];
				} else {
					$stringamarker .= 'markers=color:red|' . $luogo['latitudine'] . ',' . $luogo['longitudine'] . '&';
					$stringapath .= $luogo['latitudine'] . ',' . $luogo['longitudine'] . '|';
				}

				$numero_luoghi++;
			}
		}

		$link = 'https://maps.googleapis.com/maps/api/staticmap?markers=color:red|label:P|' . $dati_struttura['latitudine'] . ',' . $dati_struttura['longitudine'] . '&zoom=10&size=600x300&maptype=roadmap&' . $stringamarker . '&path=color:0x4285F4|weight:3|' . $dati_struttura['latitudine'] . ',' . $dati_struttura['longitudine'] . '|' . $stringapath . '&key=AIzaSyAN6U_MYAHUznULQav0s897pKUx_Tt9EUo';

		$mappa = '
		<div style="height:250px;background-size:cover;background-position:center center;background-repeat:no-repeat;background-image:url(\'' . $link . '\')" ></div> ';

		$itinerari_txt .= '
			<li class="uk-width-3-4 " onclick="navigation_ospite(19,{IDitinerario:' . $dati['ID'] . '})">
                <div class="uk-card uk-card-default">

                    <div class="uk-card-media-top" style="height:250px; display: flex;">
                    	<div style="width:40%;margin-right:1px;">' . $divfoto . '</div>
						<div style="width:60%">' . $mappa . '</div>

                    </div>
                    <div class="uk-card-body" style="padding: 20px 15px;position:relative">
                    	 <div style="    position: absolute;  right: 10px;  font-size: 13px; top: 5px;  color: #333;">    ' . (!empty($dati['luoghi']) ? '<i class="fas fa-map-marker-alt" style="margin-right:5px"></i>' . count($dati['luoghi']) . ' ' . (count($dati['luoghi']) > 1 ? traduci('Luoghi ', $lang) : traduci('Luogo', $lang)) : '') . '</div>
                        <div class=" c000" style="font-size: 20px; font-weight: 600;" >' . $dati['nome'] . ' </div>
                        <p style="font-size:12px;" class="uk-text-truncate">' . strip_tags($dati['descrizione']) . '</p>
                    </div>
                </div>
         </li>  ';

	}

	$testo = '

	    <div class="uk-position-relative uk-visible-toggle uk-light" tabindex="-1" uk-slider="center: true;autoplay:false;finite:true" style="padding:10px 0;margin-bottom:20px">
		    <ul class="uk-slider-items uk-grid remove_first_padding">
			    ' . $itinerari_txt . '
			</ul>
		</div>';

}

echo $testo;
?>
