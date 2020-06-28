<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../config/connecti.php';
require_once __DIR__ . '/../../config/funzioni.php';
$IDprenotazione = $_SESSION['IDstrpren'];
$IDstruttura = get_IDstruttura_from_IDpren($IDprenotazione);
$lang = $_SESSION['lang'];

$dettaglio_prenotazione = get_prenotazioni(['0' => ['IDprenotazione' => $IDprenotazione]], [], [], $IDstruttura)['dati'][$IDprenotazione];
$testo = '

<div style="padding:10px;padding-bottom:25px">
	<div style="margin-bottom:20px;">

		<div>' . traduci('Titolo', $lang) . '</div>

	    <div class="uk-inline" style="width:100%;">

	        <input class="uk-input   " type="text" placeholder="' . traduci('Titolo', $lang) . '"  id="titolo" />
    	</div>
	</div>

	<div style="margin-bottom:20px;">

		<div>' . traduci('Recensione', $lang) . '</div>

	    <div class="uk-inline" style="width:100%;">

	        <textarea class="uk-textarea   " style="height:150px;resize:none" type="text" placeholder="' . traduci('Recensione', $lang) . '"  id="descrizione" />
    	</div>
	</div> ';

$parametri = get_parametri_recensione([], $IDstruttura);

$parametri_txt = '';
foreach ($parametri as $dati) {

	/*
		if (isset($dati['quando'])) {
			if (in_array(2, $dati['quando'])) {
				if (time() < $dettaglio_prenotazione['checkout']) {continue;}
			}

		}
	*/

	if ($dati['tipologia'] == 1) {
		$parametri_txt .= '
			<div class="div_list_uk uk_grid_div  parametri" uk-grid   data-id="' . $dati['ID'] . '" data-tipologia="' . $dati['tipologia'] . '">
			    <div class="uk-width-expand lista_grid_nome uk-first-column">' . traducis('', $dati['ID'], 40, $lang) . '</div>
			    <div class="uk-width-auto uk-text-right lista_grid_right  ">
			   			   <input class="uk-input input_cli  uk-form-small valore_parametro" type="text" id="valore_' . $dati['ID'] . '"   placeholder="' . traduci('Inserire Valore ', $lang) . '">
			     </div>
			</div> 	';

	} else {

		$voti = genera_numeri_array($dati['valore_min'], $dati['valore_max'], 1, ' ');
		$parametri_txt .= '
			<div id="voto' . $dati['ID'] . '" style="display:none;">
				<ul class="uk-list uk-list-divider uk-picker-bot " style="padding:5px 20px;"  onchange="(r)=>{chiudi_picker();cambia_valore(' . $dati['ID'] . ',r)}">
				' . genera_select_uikit($voti, []) . '
				</ul>
			</div>

			<div class="div_list_uk uk_grid_div parametri" uk-grid onclick="carica_content_picker($(\'#voto' . $dati['ID'] . '\'))" data-id="' . $dati['ID'] . '" data-tipologia="' . $dati['tipologia'] . '">
			    <div class="uk-width-expand lista_grid_nome uk-first-column">' . traducis('', $dati['ID'], 40, $lang) . '</div>

			    <div class="uk-width-auto uk-text-right lista_grid_right chevron_right_after"> <input style="    border: none; text-align: right;" id="valore_' . $dati['ID'] . '" type="number" readonly value=""  class="valore_parametro">  </div>
			</div>
		';
	}

}

$testo .= $parametri_txt . '</div>


	<div style="    width: 100%;  position: fixed;  bottom: 0;   left: 0;">
		<button style="    background: #2574ec!important;  border: none;  width: 100%; padding: 15px;   color: #fff;  font-size: 20px;  outline: none;" onclick="salva_recensione()">' . traduci('Nuova Recensione', $lang) . '</button>
	</div>';

echo $testo;
?>

<script>

	function cambia_valore(IDparametro,valore){

    $('#valore_'+IDparametro).val(valore);

}
</script>
