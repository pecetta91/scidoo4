<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';
$IDprenotazione = $_SESSION['IDstrpren'];
$IDstruttura = get_IDstruttura_from_IDpren($IDprenotazione);

$lang = $_SESSION['lang'] ?: 0;

$dati_prenotazione = get_prenotazioni(['0' => ['IDprenotazione' => $IDprenotazione]], null, [], $IDstruttura)['dati'][$IDprenotazione];

$lista_servizi = [];
$lista_sottotipologie = [];
$servizi = get_info_from_IDserv(null, null, $IDstruttura);
if (!empty($servizi)) {
	foreach ($servizi as $dati) {
		if (!in_array($dati['IDtipo'], [15, 16])) {continue;}
		if ($dati['web_app'] != 1) {continue;}

		$lista_servizi[] = $dati['ID'];
		$lista_sottotipologie[$dati['IDsottotip']][$dati['ID']] = $dati;

	}
}

$lista_ingredienti = get_ingredienti_servizio($lista_servizi, $IDstruttura);

$elenco_sottotip = '';
$foto = getfoto($IDstruttura, 9);
if (!empty($lista_sottotipologie)) {
	foreach ($lista_sottotipologie as $IDsottotip => $dati) {
		$testo_servizi = '';
		$foto_sottotip = '';
		foreach ($dati as $dati_serv) {

			$lista_tag = get_lista_tag($dati_serv['ID'], 3, $IDstruttura);
			$allergeni = [];
			if (!empty($lista_tag)) {
				foreach ($lista_tag as $tag) {
					if ($tag['IDtag_categoria'] != 12) {continue;}
					$nome_tag = traducis($tag['nome'], $tag['ID'], 25, $lang);
					$allergeni[] = $nome_tag;

				}
			}

			$ingredienti = [];
			if (!empty($lista_ingredienti[$dati_serv['ID']])) {
				foreach ($lista_ingredienti[$dati_serv['ID']] as $dati) {
					$ingredienti[] = traducis('', $dati['IDingrediente'], 1, $lang);
				}
			}

			$foto = getfoto($dati_serv['ID'], 4);

			$testo_servizi .= '

				<div class=" uk_grid_div div_list_uk" uk-grid   "  onclick="visualizza_servizio_ordinazione_web_app(' . $dati_serv['ID'] . ')">
				' . ($foto != 'camera.jpg' ?
				'<div class="uk-width-1-4">
						<div style="height:60px;background-size:cover;background-image:url(' . base_url() . '/immagini/big' . $foto . ');background-repeat:no-repeat;background-position:center;"></div>
					</div>'
				: '') . '


		   			 <div class="uk-width-expand lista_grid_nome " style="padding-left: 10px; margin: 2px;">
					  	 	 <div class="uk-text-truncate">  ' . traducis('', $dati_serv['ID'], 1, $lang) . ' </div>
		 			    ' . (!empty($ingredienti) ? '   <div style="font-size:11px">' . traduci('Ingredienti', $lang) . ' : ' . implode(', ', $ingredienti) . '</div>' : '') . '
					   ' . (!empty($allergeni) ? '   <div style="font-size:11px">' . traduci('Allergeni', $lang) . ' : ' . implode(', ', $allergeni) . '</div>' : '') . '
				    </div>
				    <div class="uk-width-auto uk-text-right lista_grid_right" >  <i class="fas fa-chevron-right"></i>   </div>
				</div>

				 	';

		}

		$elenco_sottotip .= '
		<div class="titolo_paragrafo" style="   margin-top: 0;   padding: 0;  padding-left: 5px; padding-top: 10px;">
	    	<div>' . traducis('', $IDsottotip, 3, $lang) . '</div>
	    </div>

			 ' . $testo_servizi . '


			 ';
	}
}

$testo = '
<div class="div_uk_divider_list" style="margin-top:0px !important;">' . traduci('Seleziona i Servizi', $lang) . '</div>
	<div style="margin:0 10px;"  >' . $elenco_sottotip . '</div>

';

echo $testo;

?>
