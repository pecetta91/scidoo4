<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../config/connecti.php';
require_once __DIR__ . '/../../config/funzioni.php';
$IDprenotazione = $_SESSION['IDstrpren'];
$IDstruttura = get_IDstruttura_from_IDpren($IDprenotazione);
$lang = $_SESSION['lang'] ?: 0;

$informazioni_tipo_servizio = get_informazioni_tiposervizio();

$servizi = get_info_from_IDserv(null, null, $IDstruttura);
$tipologie_webapp = [];
if (!empty($servizi)) {
	foreach ($servizi as $dati) {
		if ($dati['web_app'] != 1) {continue;}
		$IDtipo = $dati['IDtipo'];
		$IDsottotipologia = $dati['IDsottotip'];

		$foto = getfoto($dati['ID'], 4);

		if (isset($tipologie_webapp[$IDtipo][$IDsottotipologia])) {
			if (($tipologie_webapp[$IDtipo][$IDsottotipologia] == '') || ($tipologie_webapp[$IDtipo][$IDsottotipologia] == 'camera.jpg')) {
				$tipologie_webapp[$IDtipo][$IDsottotipologia] = $foto;
			}
		} else {
			$tipologie_webapp[$IDtipo][$IDsottotipologia] = $foto;
		}

	}
}

$tipologie_txt = '';
foreach ($tipologie_webapp as $IDtipo => $dati) {

	$sottotipologie_txt = '';
	foreach ($dati as $IDsottotipologia => $foto) {

		if ($foto == 'camera.jpg') {
			$foto = ($informazioni_tipo_servizio[$IDtipo]['immagine'] != '' ? base_url() . '/img_template/' . $informazioni_tipo_servizio[$IDtipo]['immagine'] : '');
		} else {
			$foto = base_url() . '/immagini/big' . $foto;
		}

		$sottotipologie_txt .= '
		<div class="div_tipologia" onClick="navigation_ospite(8,{IDsottotipologia:' . $IDsottotipologia . '});">
			<div class="container_tipologia">
				<div class="div_immagine" style="background-image:url(' . $foto . ');position:relative">


					<div class="uk-text-truncate titolo_tipologia" >' . traducis('', $IDsottotipologia, 3, $lang) . '</div>
				</div>
			</div>
		</div> 	';

	}

	$tipologie_txt .= '
	<div class="titolo_paragrafo" style="margin-top:15px;"> ' . $informazioni_tipo_servizio[$IDtipo]['tipo'] . ' </div>

	<div style="padding:5px 10px;margin-bottom:10px">	    ' . $sottotipologie_txt . '</div>';
}

$testo = '<div>' . $tipologie_txt . '</div>';

echo $testo;

?>

<style>


.div_tipologia {margin: 10px 0; display: inline-flex; width: 49%;  place-content: space-evenly;}
.div_immagine{background-size: cover;background-repeat: no-repeat;background-position: center;width: 100%;height: 170px;border-radius:5px;}

.container_tipologia{width: 200px;  height: 170px;    border-radius: 5px;   place-content: center;   box-shadow: 0 0 10px 1px #efefef;   background: #fff;margin:0 5px;}
.titolo_tipologia{font-weight: 600;  font-size: 14px;  padding-left: 10px;   background: #1b1b1b8c; position: absolute;   width: 100%;  bottom: 0; color: #fff;border-bottom-right-radius: 5px;  border-bottom-left-radius: 5px;}

.pulsanti_funzione  .sotto_titolo{font-size: 13px;}

@media (min-width: 992px){
	.div_tipologia {width: 24%; }
}

</style>
