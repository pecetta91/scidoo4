<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/' . '../../../config/connecti.php';
require_once DIR_CONFIG . '/' . 'funzioni.php';
require_once DIR_CONFIG . '/' . 'ristorante/funzioni_ristorante.php';

$IDstruttura = $IDstruttura ?? $_SESSION['IDstruttura'];

$time_ricerca = $arr_dati['time'] ?? $_SESSION['ristorante']['time_ricerca'] ?? time_struttura();
if (!is_numeric($time_ricerca)) {$time_ricerca = strtotime(implode('-', array_reverse(explode('/', $time_ricerca))));}

$IDsottotip_ricerca = $arr_dati['IDsottotip'] ?? $_SESSION['ristorante']['IDsottotip_ricerca'] ?? 0;
$_SESSION['ristorante']['IDsottotip_ricerca'] = $IDsottotip_ricerca;
$_SESSION['ristorante']['time_ricerca'] = $time_ricerca;

$sottotipologie = get_sottotipologie(1, $IDstruttura);

$time_max = $time_ricerca + 86400;
$query = "SELECT dp.IDsottotip,dp.portata,s.servizio,s.ID FROM dispgiorno as dp
		JOIN servizi as s ON dp.IDpiatto=s.ID
		WHERE dp.data>='$time_ricerca' AND dp.data<'$time_max' ORDER BY dp.portata";
$result = $link2->query($query) or trigger_error($link2->error, E_USER_ERROR);

$menu = [];
while ($row = $result->fetch_row()) {
	$menu[$row[0]][$row[1]][] = $row;
}

$menu_html = '';
foreach ($sottotipologie as $IDsottotip => $nome_sottotip) {
	if (!isset($menu[$IDsottotip])) {continue;}
	$menu_html .= '<div style="background:#f8f9fb;text-align:left; padding:10px;"> <strong style="line-height:20px; font-size:20px;">' . ($nome_sottotip ?? '') . '</strong></div>';

	foreach ($menu[$IDsottotip] as $portata => $prodotti) {
		$menu_html .= '<div style="padding-left: 10px; font-size: 17px; font-weight: 600; margin-top: 10px;">Portata ' . $portata . '</div>';
		foreach ($prodotti as $info) {
			$menu_html .= '<div style="padding-left: 20px;">' . $info[2] . '</div>';
		}
	}
}
echo $menu_html;

?>
