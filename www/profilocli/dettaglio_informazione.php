<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../config/connecti.php';
require_once __DIR__ . '/../../config/funzioni.php';
$IDprenotazione = $_SESSION['IDstrpren'];
$IDstruttura = get_IDstruttura_from_IDpren($IDprenotazione);
$lang = $_SESSION['lang'] ?: 0;

$dati = $_POST['arr_dati'] ?? [];

$IDinformazione = $dati['IDinformazione'];

/*
$informazioni_struttura = estrai_informazioni_struttura([['IDinformazione'=>$IDinformazione]], $IDstruttura)[$IDinformazione];
 */

$titolo = traducis('', $IDinformazione, 16, $lang);
$descrizione = traducis('', $IDinformazione, 17, $lang);

$testo = '



<div class="uk-text-break" style="font-size:13px;">' . $descrizione . '</div>

';

echo $testo;

?>

