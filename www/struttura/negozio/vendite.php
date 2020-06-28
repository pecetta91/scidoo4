<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';

$IDutente = $_SESSION['ID'];
$IDstruttura = $_SESSION['IDstruttura'];

$dati = (isset($_POST['arr_dati']) ? $_POST['arr_dati'] : []);

if (!isset($dati['indietro'])) {

	$timedata = time0(time_struttura());
	$time_inizio = $timedata - (86400 * 14);
	$time_fine = $timedata + (86400 * 7);

	$_SESSION['filtri_vendite'] = ['data_inizio' => date('d/m/Y', $time_inizio), 'data_fine' => date('d/m/Y', $time_fine), 'oggetto_ricerca' => 0];
}

$testo = ' <ul class="uk-list lista_dati_default" id="container_txt_nav" style="margin-top:50px;padding: 0">';
echo $testo;

include 'elenco_vendite_reload.php';

echo '</ul>';

?>

