<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../config/connecti.php';
require_once __DIR__ . '/../../config/funzioni.php';
$IDprenotazione = $_SESSION['IDstrpren'];
$IDstruttura = get_IDstruttura_from_IDpren($IDprenotazione);
$lang = $_SESSION['lang'] ?: 0;

$lista_album = '';
$album = estrai_album($IDstruttura);
if (!empty($album)) {
	$count_album = 0;
	foreach ($album as $dati) {

		$lista_foto = '';

		if (!empty($dati['foto'])) {
			foreach ($dati['foto'] as $immagine) {
				if ($immagine['elemento'] != 'immagine') {continue;}

				$lista_foto .= '
				<li  class="uk-width-4-5 ">
					  <a class="uk-inline" href="' . base_url() . '/immagini/big' . $immagine['foto'] . '" data-caption="' . $dati['nome'] . '">
						 <div class="uk-panel">
						 	<div class="foto_galleria_slider" style=" background-image:url(' . base_url() . '/immagini/big' . $immagine['foto'] . ')"></div>

						 </div>
					 </a>
				</li> ';

			}
		}
		//	<div style="color: #1a73e8" onclick=""> Vedi Album</div>
		$lista_album .= '
		<div class="titolo_paragrafo"  style="' . ($count_album == 0 ? 'margin:0;' : 'margin-top:45px') . ';">
			<div>' . $dati['nome'] . '
				<div style="color:#70757a;font-size:12px;font-weight:400;">' . $dati['descrizione'] . '</div>
			</div>

		</div>
		 <div class="uk-position-relative uk-visible-toggle uk-light" tabindex="-1" uk-slider="autoplay:false;finite:false;" style="padding:10px 0;    margin: 0 10px;margin-bottom:20px">
		    <ul class="uk-slider-items  uk-grid-match uk-grid" uk-lightbox="animation: slide" >
				' . $lista_foto . '
			</ul>
		</div> ';
		$count_album++;

	}

}
$testo = $lista_album;
echo $testo;
?>
