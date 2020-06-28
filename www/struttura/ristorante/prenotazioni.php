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

$query = "SELECT ID,ord FROM sottotipologie WHERE IDmain=1 AND IDstr='$IDstruttura'";
$result = $link2->query($query) or trigger_error($link2->error, E_USER_ERROR);
$ordine_sottotip = array_column($result->fetch_all(), 1, 0);

$prenotazioni = risto_prenotazioni($IDstruttura, $time_ricerca);
// print_html($prenotazioni);
// $prenotazioni = array_filter($prenotazioni, function ($arg) use ($IDsottotip_ricerca) {
// 	return ($IDsottotip_ricerca ? ($arg['IDsottotip'] == $IDsottotip_ricerca) : $arg['IDsottotip']);
// });
usort($prenotazioni, function ($a, $b) use ($ordine_sottotip) {return (($ordine_sottotip[$a['IDsottotip']] ?? 0) - ($ordine_sottotip[$b['IDsottotip']] ?? 0)) ?: ($a['time_prenextra'] - $b['time_prenextra']);});

$sottotipologie = get_sottotipologie(1, $IDstruttura);
$select_sottotip = array_intersect_key($sottotipologie, array_fill_keys(array_unique(array_column($prenotazioni, 'IDsottotip')), 1));
$hidden_select = '<div id="ristorante-pren-sottotip-select" style="display:none;"><ul class="uk-list uk-list-divider uk-picker-bot" style="padding:5px 20px;" onchange="(r)=>{navigation(27,{sezione: 1, IDsottotip: r});}">' . genera_select_uikit($select_sottotip, $IDsottotip_ricerca) . '</ul></div>';

$current_time = null;
$current_sottotip = null;
$prenotazioni_html = $hidden_select;
foreach ($prenotazioni as $tavolo) {
	if ($current_sottotip != $tavolo['IDsottotip']) {
		if ($current_sottotip !== null) {
			$prenotazioni_html .= '</div>';
		}
		if (!$IDsottotip_ricerca) {$IDsottotip_ricerca = $tavolo['IDsottotip'];}
		$current_sottotip = $tavolo['IDsottotip'];
		$visibile = $IDsottotip_ricerca != $current_sottotip ? 'display: none;' : '';
		$prenotazioni_html .= '<div class="ristorante-prenotazione-sottotip" style="background:#f8f9fb;text-align:left; padding:10px;' . $visibile . '" data-sottotip="' . $current_sottotip . '"><strong style="line-height:20px; font-size:20px;" onclick="carica_content_picker($(`#ristorante-pren-sottotip-select`));">' . ($sottotipologie[$tavolo['IDsottotip']] ?? '') . ' <i class="fas fa-chevron-down"></i></strong>';
	}
	if ($current_time != $tavolo['time_prenextra']) {
		$current_time = $tavolo['time_prenextra'];
		$prenotazioni_html .= '<div style="padding-left: 10px; font-size: 17px; font-weight: 600; margin-top: 10px;">' . date('H:i', $tavolo['time_prenextra']) . '</div>';
	}
	$onclick = 'ristorante.apri_tavolo_prenotazione({pren: ' . $tavolo['IDpren'] . ', sottotip: ' . $tavolo['IDsottotip'] . '});';
	// $onclick = 'risto.azione(1, 7,[' . $tavolo['IDpren'] . ',' . $tavolo['ID'] . ', ' . $tavolo['IDsottotip'] . '])';
	// $stylealloc = 'style="width:70px;height:50px; font-size:14px;border:2px solid #c88d00; color:#c88d00; background:none;outline:none;font-weight:600; background:#f1f1f1"';
	$numero_tavolo = $tavolo['nome'];

	if ($tavolo['tipo'] == Tavolo::collegato || $tavolo['tipo'] == Tavolo::normale) {
		$onclick = sprintf('navigation(28,{pren: %s, sottotip: %s, num_tavolo: %s});', $tavolo['IDpren'], $tavolo['IDsottotip'], $tavolo['numero']);
	} else if ($tavolo['tipo'] == Tavolo::asporto || $tavolo['tipo'] == Tavolo::normale || !$tavolo['aperto']) {
		$onclick = sprintf('navigation(28,{tavolo: %s});', $tavolo['ID']);
	}
	$prenotazioni_html .= '<div class="risto-prenotazione" onclick="' . $onclick . '">
	<div class="risto-prenotazione-left">
		Tavolo<br><span style="font-size:15px; font-weight: 600;">' . $numero_tavolo . '</span>
		<div style="text-align: center;"><i class="fas fa-user"></i> ' . $tavolo['num_persone'] . '</div>
	</div>
	<div style="flex: 1;">
		<div style="font-weight: 600;">' . $tavolo['nome_pren'] . '</div>
		<div style="font-weight: 600; color: #da7913;">' . $tavolo['nome_app'] . '</div>
		<div>' . $tavolo['servizi'] . '</div>
	</div>
	' . ($tavolo['note'] ? '<div style="position: absolute; right: 13px;" uk-tooltip="' . $tavolo['note'] . '" class="risto-prenotazione-notice" onclick="event.stopPropagation();"><i class="fas fa-info"></i></div>' : '') . '
	</div>';
}
$prenotazioni_html .= '</div>';

echo $prenotazioni_html;
?>
<style>
.risto-prenotazione {
	display: flex;
	font-size: 14px;
	width: 97%;
	border: 1px solid #e1e1e1;
	border-radius: 5px;
	margin: 10px 5px;
	background: #fff;
	box-shadow: 0 0 5px 1px #e1e1e1;
	color: #000;
	height: 100px;
	padding: 5px;
	position: relative;
}
.risto-prenotazione-left {
	text-align: center;
	border-right: 1px solid #ccc;
	margin-right: 5px;
	width: 80px;
}

.risto-prenotazione-notice {
	background: #eb0e0e;
	color: #fff;
	border-radius: 50%;
	text-align: center;
	display: flex;
	justify-content: center;
	align-items: center;
	width: 25px;
	height: 25px;
}
</style>
