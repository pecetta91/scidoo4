<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';

$IDstruttura = $_SESSION['IDstruttura'];

$IDprenotazione = $_POST['IDprenotazione'];

$testo = ' <div class="div_uk_divider_list" style="margin-top:0px !important;">Dati pagamenti</div>';

echo $testo . '<br><br/>';

?>
