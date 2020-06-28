<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';
$IDutente = $_SESSION['ID'];
$IDstruttura = $_SESSION['IDstruttura'];

$dati = $_POST['arr_dati'] ?? [];

$IDvendita = $dati['IDvendita'] ?? 0;

if ($IDvendita == 0) {
	$IDvendita = negozio_nuova_vendita();
}

$testo = '
<input type="hidden" value="' . $IDvendita . '" id="IDvendita">

<div class="div_container_principale" id="container_vendita" style="padding-bottom:50px">



</div>';

echo $testo;
?>
