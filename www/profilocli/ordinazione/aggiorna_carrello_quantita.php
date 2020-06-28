<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';
$IDprenotazione = $_SESSION['IDstrpren'];
$IDstruttura = get_IDstruttura_from_IDpren($IDprenotazione);

$quantita = 0;
if (!empty($_SESSION['ordinazione_webapp'][$IDprenotazione]['servizi'])) {
	$ordinazione = $_SESSION['ordinazione_webapp'][$IDprenotazione]['servizi'];
	foreach ($ordinazione as $dati) {

		$quantita += $dati['quantita'];

	}

}

echo $quantita;
?>
