<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../config/connecti.php';
require_once __DIR__ . '/../../config/funzioni.php';

$IDutente = $_SESSION['ID'];
$IDstruttura = $_SESSION['IDstruttura'];

$dati = (isset($_POST['arr_dati']) ? $_POST['arr_dati'] : []);

if (!isset($dati['indietro'])) {
	$time0 = time_struttura();
	$timein = date('d/m/Y', $time0 - (86400 * 7));
	$timefin = date('d/m/Y', $time0 + (86400 * 14));

	$_SESSION['filtri_prenotazione'] = ['datain' => $timein, 'datafin' => $timefin, 'tipodata' => 1, 'where' => 1];
}

$testo = '<ul class="uk-list lista_dati_default" id="container_txt_nav" style="margin-top:50px;padding: 0">';
echo $testo;

include 'elenco_prenotazioni_reload.php';

echo '</ul>';
