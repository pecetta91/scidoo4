<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';
require_once __DIR__ . '/../../../config/preventivatore/funzioni/preventivatore.php';
$IDutente = $_SESSION['ID'];
$IDstruttura = $_SESSION['IDstruttura'];

$dati = (isset($_POST['arr_dati']) ? $_POST['arr_dati'] : []);
$IDpreventivo = $dati['IDpreventivo'] ?? 0;
$time = $dati['time'] ?? time();

if ($IDpreventivo == 0) {
	$argomenti = [];
	$argomenti['stato'] = 0;
	$argomenti['checkin'] = $time;

	if (isset($dati['notti'])) {
		$argomenti['notti'] = $dati['notti'];
	}

	$IDpreventivo = prev_nuovo_preventivo($argomenti, ['sequenziale' => false]);
}

$testo = '
<input type="hidden" value="' . $IDpreventivo . '" id="IDpreventivo">
<input type="hidden" value="1" id="tipo_ricerca_preventivo">
<div class="div_container_principale" style="padding-top:20px;padding-bottom:15px;">

	<div class="div_dettagli_preventivatore uk-margin-bottom" onclick="modifica_anagrafica_nuovo_preventivo()" >
		Dati Anagrafici

		<div id="dettagli_anagrafica_preventivo">';
echo $testo;
include "dettaglio_anagrafica_text.php";
echo '
		</div>


	</div>

	<div class="div_dettagli_preventivatore" onclick="modifica_dettagli_nuovo_preventivo()">
		Dettaglio Richiesta

		<div id="dettagli_preventivo">';
include "dettaglio_richiesta_text.php";

echo '</div>

	</div>

	<div id="richieste_preventivo" style="min-height:800px;margin-top: 10px;padding-bottom:150px"> </div>


	<div id="carrello_preventivo" class="carrello_preventivo "> </div>


</div>';

?>