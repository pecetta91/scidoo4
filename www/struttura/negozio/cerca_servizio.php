<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';
$IDutente = $_SESSION['ID'];
$IDstruttura = $_SESSION['IDstruttura'];

$cerca_servizio = (isset($_POST['cerca_servizio']) ? $link2->real_escape_string($_POST['cerca_servizio'] ?? '') : '');

$ricerca = '%' . $cerca_servizio . '%';
$lista_ID = [];
$query = "SELECT ID FROM servizi WHERE IDstruttura='$IDstruttura' AND  servizio LIKE '$ricerca'  ";
$result = mysqli_query($link2, $query);
$row = mysqli_fetch_all($result);
$lista_ID = array_column($row, 0);

$lista_servizi = get_elenco_servizi([['IDservizio' => $lista_ID]], $IDstruttura);

$lista_servizi_txt = '';

foreach ($lista_servizi as $dati) {

	$lista_servizi_txt .= ' <li   value="' . $dati['ID'] . '" onclick="aggiungi_componente_voucher(' . $dati['ID'] . ')" >' . $dati['servizio'] . '</li>';

}

echo ' <ul class="uk-list uk-list-divider uk-picker-bot" style="padding:5px 20px;">' . $lista_servizi_txt . '</ul>';

?>
