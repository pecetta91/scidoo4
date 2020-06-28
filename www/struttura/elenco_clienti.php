<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../config/connecti.php';
require_once __DIR__ . '/../../config/funzioni.php';
$IDutente = $_SESSION['ID'];
$IDstruttura = $_SESSION['IDstruttura'];

$dati = (isset($_POST['arr_dati']) ? $_POST['arr_dati'] : []);

if (!isset($dati['indietro'])) {
	$_SESSION['filtri_clienti'] = [];
}

$testo = '<ul class="uk-list lista_dati_default" id="container_txt_nav" style="margin-top:50px;padding: 0">';
echo $testo;

include 'elenco_clienti_reload.php';

echo '</ul>';
