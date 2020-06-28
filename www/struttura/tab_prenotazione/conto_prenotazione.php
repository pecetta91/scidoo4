<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';

$IDstruttura = $_SESSION['IDstruttura'];

$IDprenotazione = $_POST['IDprenotazione'];

$testo = genera_conto_ui_kit($IDprenotazione, 0, 'cambia_tab_prenotazione(' . $IDprenotazione . ',2)');

echo $testo;

?>
