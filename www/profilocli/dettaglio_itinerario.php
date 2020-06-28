<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../config/connecti.php';
require_once __DIR__ . '/../../config/funzioni.php';
$IDprenotazione = $_SESSION['IDstrpren'];
$IDstruttura = get_IDstruttura_from_IDpren($IDprenotazione);

$lang = $_SESSION['lang'] ?: 0;

$dati = $_POST['arr_dati'] ?? [];
$IDitinerario = $dati['IDitinerario'] ?? 0;

$logo_struttura = getfoto($IDstruttura, 12);

$IDsotto_struttura = get_IDsotto_struttura_from_IDpren($IDprenotazione, $IDstruttura);
$dati_struttura = genera_sotto_strutture_lista($IDstruttura)[$IDsotto_struttura];
if ($IDsotto_struttura) {
	$logo_struttura = getfoto($IDsotto_struttura, 16);
}

$itinerario = estrai_itinerari_giornalieri([['IDitinerario' => $IDitinerario]], $IDstruttura)[$IDitinerario];
$stringamarker = '';
$stringapath = '';
$divfoto = '';

$div_elenco_luoghi = '';

$conta_luoghi = 0;
$lista_luoghi = estrai_luoghi([], $IDstruttura);

$last_latitudine = 0;
$last_longitudine = 0;

if (!empty($itinerario['luoghi'])) {
	foreach ($itinerario['luoghi'] as $luogo) {

		$foto = getfoto($luogo['ID'], 17);

		$divfoto .= '
			<li class="uk-width-4-5 ">
				  <a   href="' . base_url() . '/immagini/big' . $foto . '" >
					 <div class="uk-panel">
					 	<div style="width:100%;height:300px;background-size:cover;background-position:center;background-repeat:no-repeat;background-image:url(' . base_url() . '/immagini/big' . $foto . ')"></div>
					 </div>
				 </a>
			</li>';

		$riga_distanza = '';

		$distance_data = file_get_contents('https://maps.googleapis.com/maps/api/distancematrix/json?&language=IT&origins=' . $dati_struttura['latitudine'] . ',' . $dati_struttura['longitudine'] . '&destinations=' . $luogo['latitudine'] . ',' . $luogo['longitudine'] . '&key=AIzaSyAN6U_MYAHUznULQav0s897pKUx_Tt9EUo');

		if ($conta_luoghi > 0) {
			$distance_data = file_get_contents('https://maps.googleapis.com/maps/api/distancematrix/json?&language=IT&origins=' . $luogo['latitudine'] . ',' . $luogo['longitudine'] . '&destinations=' . $last_latitudine . ',' . $last_longitudine . '&key=AIzaSyAN6U_MYAHUznULQav0s897pKUx_Tt9EUo');
		}

		$distance_arr = json_decode($distance_data);

		if ($distance_arr->status == 'OK') {
			$elements = $distance_arr->rows[0]->elements;
			if ($elements[0]->status != 'NOT_FOUND') {
				$distance = $elements[0]->distance->text;
				$duration = $elements[0]->duration->text;

				$riga_distanza = '<i class="fas fa-car" style="margin-right:5px;"></i> ' . $distance . ' - ' . $duration;
			}
		}

		$last_latitudine = $luogo['latitudine'];
		$last_longitudine = $luogo['longitudine'];
		$div_elenco_luoghi .= '

			<div style="margin-bottom:20px;padding-left:20px;position:relative;    padding-bottom: 80px;">
					<div style="position: absolute; width: 50px; 	height: 50px; border-radius: 50%; 	font-size: 15px; border: 1px solid #2574ec ;text-align: center;
					 background-image:url(' . base_url() . '/immagini/big' . $foto . ');    top: 2px;    background-position: center;background-repeat:no-repeat; background-size: cover;"> </div>

					 <span style="    position: absolute;  background: #fff;  top: -40px;   color: #80868b;   font-size: 16px;  left: 38px;">' . $riga_distanza . '</span>

					<div style="padding-left:60px;padding-top:15px"  onclick="navigation_ospite(18,{IDluogo:' . $luogo['ID'] . '})">
							<div style="font-weight: 600; 	font-size: 16px; color:#2574ec ; margin-bottom: 20px;">' . $lista_luoghi[$luogo['ID']]['nome'] . '

								<div style="float:right;margin-right:15px;" > <i class="fas fa-arrow-right"></i></div>

							</div>

							<div style="margin: 0 5px;font-size:13px">

								' . tagliastringa2(strip_tags($lista_luoghi[$luogo['ID']]['informazioni']['Descrizione']['valore']), 100) . '

							</div>

					</div>

			</div> ';

		if (end($itinerario['luoghi']) == $luogo) {
			$stringamarker .= 'markers=color:red|label:A|' . $luogo['latitudine'] . ',' . $luogo['longitudine'];
			$stringapath .= $luogo['latitudine'] . ',' . $luogo['longitudine'];
		} else {
			$stringamarker .= 'markers=color:red|' . $luogo['latitudine'] . ',' . $luogo['longitudine'] . '&';
			$stringapath .= $luogo['latitudine'] . ',' . $luogo['longitudine'] . '|';
		}

		$conta_luoghi++;
	}
}

$link = 'https://maps.googleapis.com/maps/api/staticmap?markers=color:red|label:P|' . $dati_struttura['latitudine'] . ',' . $dati_struttura['longitudine'] . '&zoom=10&size=600x300&maptype=roadmap&' . $stringamarker . '&path=color:0x4285F4|weight:3|' . $dati_struttura['latitudine'] . ',' . $dati_struttura['longitudine'] . '|' . $stringapath . '&key=AIzaSyAN6U_MYAHUznULQav0s897pKUx_Tt9EUo';

$testo = '
<div class="uk-position-relative uk-visible-toggle uk-light" tabindex="-1" uk-slider="center: true;;autoplay:false;finite:true" style="padding:10px 0;margin-bottom:20px">
	    <ul class="uk-slider-items uk-grid">
	    	<li class="uk-width-4-5 ">
					 <div class="uk-panel">
					 	<div style="width:100%;height:300px;background-size:cover;background-position:center;background-repeat:no-repeat;background-image:url((\'' . $link . '\')"></div>
					 </div>
			</li>

		    ' . $divfoto . '
		</ul>
</div>


<div style="margin:10px 0;position:relative">


		<div style="position:relative;overflow:hidden;margin:10px 0">
			<div style="background-color: rgba(0, 0, 0, .12);  left: 45px; position: absolute;  width: 2px; height: 100vh; top: 10px;"> </div>


					<div style="margin-bottom:20px;padding-left:20px;position:relative;    padding-bottom: 80px;">
					<div style="position: absolute; width: 50px; 	height: 50px; border-radius: 50%; 	font-size: 15px; border: 1px solid #2574ec ;text-align: center; background:#fff;
					 background-image:url(' . base_url() . '/immagini/big' . $logo_struttura . ');  top: 2px;    background-position: center;background-repeat:no-repeat; background-size: cover;"> </div>


					<div style="padding-left:60px;padding-top:15px">
							<div style="font-weight: 600; 	font-size: 16px; color: #2574ec; margin-bottom: 20px;">' . $dati_struttura['nome'] . ' </div>

					</div>

			</div>


			' . $div_elenco_luoghi . '
		</div>



</div>

	<div class="div_uk_divider_list" style="margin-top:0px !important;">' . traduci('Descrizione', $lang) . '</div>
 		<div style="padding:5px;margin:10px 5px">' . $itinerario['descrizione'] . '</div>

';

echo $testo;
?>
