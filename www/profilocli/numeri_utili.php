<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../config/connecti.php';
require_once __DIR__ . '/../../config/funzioni.php';
$IDprenotazione = $_SESSION['IDstrpren'];
$IDstruttura = get_IDstruttura_from_IDpren($IDprenotazione);
$lang = $_SESSION['lang'] ?: 0;

$IDsotto_struttura = get_IDsotto_struttura_from_IDpren($IDprenotazione, $IDstruttura);
$dati_struttura = genera_sotto_strutture_lista($IDstruttura)[$IDsotto_struttura];

$numeri = get_numeri_utili($IDstruttura);
$lista_numeri = '';
if (!empty($numeri)) {
	foreach ($numeri as $dati) {

		$lista_numeri .= '
		<div class="uk_grid_div div_list_uk  "  uk-grid  >
			    <div class="uk-width-expand lista_grid_nome uk-text-truncate" >' . $dati['testo'] . '<br/>
			  		<span class="uk-text-muted uk-text-small" >' . $dati['descrizione'] . '</span></div>
		        <div class="uk-width-auto  uk-text-right lista_grid_right c000"> ' . $dati['numero'] . '  </div>
		</div>';

	}
}

$testo = '

<div class="div_container_principale">


	<div class="tab_info_pren" data-tab="0" >
		 ' . $lista_numeri . '
	</div>


	<div class="tab_info_pren" data-tab="1"  style="display:none">

	<div class="div_uk_divider_list" style="margin-top:0px !important;">' . traduci('Contatti', $lang) . '</div>


			 <div class=" uk_grid_div div_list_uk" uk-grid   onclick="location.href=' . "'tel:" . $dati_struttura['telefono'] . "'" . '">
			    <div class="uk-width-auto lista_grid_nome">' . traduci('Telefono', $lang) . '</div>
			    <div class="uk-width-expand uk-text-right lista_grid_right" > ' . $dati_struttura['telefono'] . ' <i class="fas fa-chevron-right"></i> </div>
			</div>


 			<div class=" uk_grid_div div_list_uk" uk-grid  onclick="location.href=' . "'mailto:" . $dati_struttura['email'] . "'" . '" >
			    <div class="uk-width-auto lista_grid_nome">Email</div>
			    <div class="uk-width-expand uk-text-right lista_grid_right" > ' . $dati_struttura['email'] . '  <i class="fas fa-chevron-right"></i>  </div>
			</div>

			<div class=" uk_grid_div div_list_uk" uk-grid   onclick="location.href=' . "'https://" . $dati_struttura['sito'] . "'" . '" >
			    <div class="uk-width-auto lista_grid_nome">' . traduci('Sito Web', $lang) . '</div>
			    <div class="uk-width-expand uk-text-right lista_grid_right" > ' . $dati_struttura['sito'] . '   <i class="fas fa-chevron-right"></i> </div>
			</div>

	</div>


	<div class="tab_info_pren" data-tab="2"  style="display:none">
		<div class="div_uk_divider_list" style="margin-top:0px !important;">' . traduci('Come Arrivare', $lang) . '</div>

 			<div class=" uk_grid_div div_list_uk" uk-grid    onclick="location.href=' . "' https://maps.google.com/?q=" . $dati_struttura['indirizzo'] . "'" . '">
			    <div class="uk-width-auto lista_grid_nome">' . traduci('Indirizzo', $lang) . '</div>
			    <div class="uk-width-expand uk-text-right lista_grid_right" >  ' . $dati_struttura['indirizzo'] . '    <i class="fas fa-chevron-right"></i></div>
			</div>



			<div class=" uk_grid_div div_list_uk" uk-grid   onclick="location.href=' . "' https://maps.google.com/?q=" . $dati_struttura['indirizzo'] . "'" . '" >
			    <div class="uk-width-auto lista_grid_nome">' . traduci('Latitudine', $lang) . '</div>
			    <div class="uk-width-expand uk-text-right lista_grid_right" > ' . $dati_struttura['latitudine'] . '    <i class="fas fa-chevron-right"></i></div>
			</div>

 			<div class=" uk_grid_div div_list_uk" uk-grid  onclick="location.href=' . "' https://maps.google.com/?q=" . $dati_struttura['indirizzo'] . "'" . '">
			    <div class="uk-width-auto lista_grid_nome">' . traduci('Longitudine', $lang) . '</div>
			    <div class="uk-width-expand uk-text-right lista_grid_right" >  ' . $dati_struttura['longitudine'] . '   <i class="fas fa-chevron-right"></i></div>
			</div>




	</div>


</div>


';

echo $testo;

?>
