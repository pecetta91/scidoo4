<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/' . '../../../config/connecti.php';
require_once DIR_CONFIG . '/' . 'funzioni.php';
require_once DIR_CONFIG . '/' . 'ristorante/funzioni_ristorante.php';

array_escape($_REQUEST);
$arr_dati = $_REQUEST['arr_dati'];
// $IDstruttura;
// $IDutente;
// $arr_dati; Dati passati dalla richiesta
echo '<div style="margin-bottom: 50px;"></div>';
switch ($arr_dati['sezione'] ?? 0) {
case 0:
default:
	require __DIR__ . '/tavoli_attivi.php';
	break;
case 1:
	require __DIR__ . '/prenotazioni.php';
	break;
case 2:
	require __DIR__ . '/menu_giornaliero.php';
	break;
case 3:
	require __DIR__ . '/riepilogo.php';
	break;
}
