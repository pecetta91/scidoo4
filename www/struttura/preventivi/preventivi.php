<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';
$IDstruttura = $_SESSION['IDstruttura'];

$dati = (isset($_POST['arr_dati']) ? $_POST['arr_dati'] : []);

$stato = 0;

if (isset($dati['indietro'])) {
	$stato = $_SESSION['filtri_preventivo']['stato'];
} else {

	$timedata = time0(time_struttura());
	$time_inizio = $timedata - (86400 * 14);
	$time_fine = $timedata + (86400 * 7);

	$_SESSION['filtri_preventivo'] = ['time_inizio' => $time_inizio, 'time_fine' => $time_fine, 'stato' => $stato];
}

$testo = '
<input type="hidden" value="' . $stato . '"    class="filtro_ricerca_preventivo" data-name="stato">
 <ul class="uk-list lista_dati_default" id="container_txt_nav" style="margin-top:50px;padding: 0">';
echo $testo;

include 'elenco_preventivi_reload.php';

echo '</ul>';
