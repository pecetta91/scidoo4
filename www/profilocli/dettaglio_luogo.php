<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../config/connecti.php';
require_once __DIR__ . '/../../config/funzioni.php';
$IDprenotazione = $_SESSION['IDstrpren'];
$IDstruttura = get_IDstruttura_from_IDpren($IDprenotazione);
$lang = $_SESSION['lang'] ?: 0;

$dati = $_POST['arr_dati'] ?? [];
$IDluogo = $dati['IDluogo'] ?? 0;

$IDsotto_struttura = get_IDsotto_struttura_from_IDpren($IDprenotazione, $IDstruttura);
$dati_struttura = genera_sotto_strutture_lista($IDstruttura)[$IDsotto_struttura];

$luoghi = estrai_luoghi([['IDluogo' => $IDluogo]], $IDstruttura)[$IDluogo];

$lista_immagini = estrai_immagini([['IDobj' => $IDluogo, 'tipoobj' => 17]]);

$foto = '';
if (!empty($lista_immagini)) {
	foreach ($lista_immagini as $dati) {
		$foto .= '
		<li  class="uk-width-4-5 ">
				  <a   href="' . base_url() . '/immagini/big' . $dati['foto'] . '" >
					 <div class="uk-panel">
					 	<div style="width:100%;height:300px;background-size:cover;background-position:center;background-repeat:no-repeat;background-image:url(' . base_url() . '/immagini/big' . $dati['foto'] . ')"></div>

					 </div>
				 </a>
			</li> ';
	}
}

$testo = '
<div class="uk-position-relative uk-visible-toggle uk-light" tabindex="-1" uk-slider="center: true;;autoplay:false;finite:true" style="padding:10px 0;margin-bottom:20px">
	    <ul class="uk-slider-items uk-grid">
		    ' . $foto . '
		</ul>
</div> ';

$informazioni_txt = '';
foreach ($luoghi['informazioni'] as $info => $dati) {

//	if ($info == 'Descrizione') {

	$informazioni_txt .= '
		<div class="div_uk_divider_list" style="margin-top:0px !important;">' . $info . '</div>
 		<div style="padding:5px;margin:10px 5px">' . $dati['valore'] . '</div> ';
/*
} else {
$informazioni_txt .= '
<div class=" uk_grid_div div_list_uk" uk-grid  >
<div class="uk-width-auto lista_grid_nome">' . $info . '</div>
<div class="uk-width-expand uk-text-right lista_grid_right" >  ' . $dati['valore'] . '    </div>
</div> ';
}*/

}

$testo .= '<div style="margin-top:10px">' . $informazioni_txt . '</div>';

echo $testo;
?>
