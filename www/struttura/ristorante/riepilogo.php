<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/' . '../../../config/connecti.php';
require_once DIR_CONFIG . '/' . 'funzioni.php';
require_once DIR_CONFIG . '/' . 'ristorante/funzioni_ristorante.php';

$IDstruttura = $IDstruttura ?? $_SESSION['IDstruttura'];

$time_ricerca = $arr_dati['time'] ?? $_SESSION['ristorante']['time_ricerca'] ?? time_struttura();
if (!is_numeric($time_ricerca)) {$time_ricerca = strtotime(implode('-', array_reverse(explode('/', $time_ricerca))));}

// $IDsottotip_ricerca = $arr_dati['IDsottotip'] ?? $_SESSION['ristorante']['IDsottotip_ricerca'] ?? 0;
// $_SESSION['ristorante']['IDsottotip_ricerca'] = $IDsottotip_ricerca;
$_SESSION['ristorante']['time_ricerca'] = $time_ricerca;

echo ristorante_mostra_riepilogo($IDstruttura, $time_ricerca);
?>
<style>
.risto-riepilogo-sottotip {
	background: #f8f9fb;
	text-align: left;
	padding: 10px;
}

.risto-riepilogo-parent {
	padding-left: 10px;
	font-size: 17px;
	font-weight: 600;
	margin-top: 10px;
}

.risto-riepilogo-child {
	padding-left: 20px;
}
</style>
