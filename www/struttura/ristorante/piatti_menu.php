<?php
require_once __DIR__ . '/' . '../../../config/connecti.php';
require_once DIR_CONFIG . '/' . 'funzioni.php';
require_once DIR_CONFIG . '/' . 'ristorante/funzioni_ristorante.php';
require_once DIR_CONFIG . '/' . 'ristorante/funzioni_ristorante.php';
$IDstruttura = $IDstruttura ?? $_SESSION['IDstruttura'];

array_escape($_POST);
$menu = $_POST['menu'];
$riga = $_POST['riga'];

$query = "SELECT IDservizio FROM prodottiport WHERE IDstruttura='$IDstruttura' AND ID='$menu'";
$result = $link2->query($query) or trigger_error($link2->error, E_USER_ERROR);
if (!$result->num_rows) {
	exit();
}
$IDservizio = $result->fetch_row()[0];

$prodotti = ristorante_get_piatti_menu($IDservizio, time0(), $riga);
$prodotti = $prodotti[$IDservizio][$riga];
$prodotti = array_replace($prodotti['prodotti'] ?? [], $prodotti['menu_del_giorno'] ?? []);
array_walk($prodotti, function (&$arg) {$arg['nome'] = $arg['nome_servizio'];unset($arg['nome_servizio']);});
echo json_encode($prodotti);
