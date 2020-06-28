<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../config/connecti.php';
require_once __DIR__ . '/../../config/funzioni.php';
$IDprenotazione = $_SESSION['IDstrpren'];
$IDstruttura = get_IDstruttura_from_IDpren($IDprenotazione);
$lang = $_SESSION['lang'] ?: 0;

$informazioni_struttura = estrai_informazioni_struttura([], $IDstruttura);
$div_informazione = '';
if (!empty($informazioni_struttura)) {
	foreach ($informazioni_struttura as $dati) {
		$descrizione = strip_tags(traducis('', $dati['ID'], 17, $lang));
		$div_informazione .= '

			<div class="pulsanti_funzione">
					<div class="container_funz" onclick="chiudi_picker();navigation_ospite(14,{IDinformazione:' . $dati['ID'] . '},0,0)" >
						<div class="div_icona" style="color: #d80073;background:#d800731a">
							<div style=""><i class="fas fa-info"></i></div>
						</div>
						<div class="testo">' . traducis('', $dati['ID'], 16, $lang) . ' </div>
					</div>
			</div> ';
	}

}

$testo = '

<div class="nav navbar_picker_flex" >
 	<div onclick="chiudi_picker()"><i class="fas fa-times icona_chiudi" ></i></div>
 	<div style="margin-top:5px;padding-right:10px"> ' . traduci('Informazioni', $lang) . '</div>
</div>


<div class="content" style="margin-top:0 ;    height: calc(100% - 60px);">

	 ' . $div_informazione . '



</div>';

echo $testo;

?>
