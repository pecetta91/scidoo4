<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';
$IDstruttura = $_SESSION['IDstruttura'];

if (isset($_POST['parametri'])) {
	$parametri = array_map(function ($e) use ($link2) {if (is_array($e)) {return $e;}return $link2->escape_string(htmlspecialchars($e));}, $_POST['parametri']);
	$parametri = array_merge($_SESSION['filtri_vendite'], $parametri);
} else {
	$parametri = $_SESSION['filtri_vendite'];
}

if (isset($parametri['data_inizio'])) {
	$parametri['time_inizio'] = strtotime(convertiData($parametri['data_inizio']));
}

if (isset($parametri['data_fine'])) {
	$parametri['time_fine'] = strtotime(convertiData($parametri['data_fine']));
}

if ($parametri['ricerca'] ?? 0) {
	unset($parametri['time_fine']);
	unset($parametri['time_inizio']);

	switch ($parametri['oggetto_ricerca']) {
	case 0:
		$IDtrovati = ricercacliente($parametri['ricerca']);
		$parametri['IDcliente'] = empty($IDtrovati) ? [0] : $IDtrovati;
		break;
	case 1:
		$parametri['note'] = $parametri['ricerca'];
		break;
	case 2:
		$parametri['codice_voucher'] = $parametri['ricerca'];
		break;
	}
}

$_SESSION['filtri_vendite'] = $parametri;

$lista_vendite = get_vendite([$parametri])['dati'] ?? [];

$vendite_txt = '';
if (!empty($lista_vendite)) {
	foreach ($lista_vendite as $dati) {

		$elem_carrello = array_slice($dati['oggetti'], 0, 2);
		$oggetti_vendite = 0;
		foreach ($elem_carrello as $dati_oggetto) {
			$oggetti_vendite = ($dati_oggetto['tipo_riferimento'] == 7 ? +1 : +$dati_oggetto['quantita']);
		}

		$vendite_txt .= '
		<li onclick="navigation(24,{IDvendita:' . $dati['ID'] . '},()=>{carica_tab_vendite(' . $dati['ID'] . ',\'carrello\')},0);">

			<div class="c000 uk-text-bold" style="line-height: 15px;">
				<div>  ' . $dati['nome_cliente'] . '  <div style="float:right;">' . $dati['stato_testuale'] . '</div> </div>

				<div style="  font-size: 12px;  display: block; color: #777;  font-weight: 400;"> <div> ' . $dati['ID'] . ' - ' . dataita5($dati['time']) . '</div></div>
			</div>
			<div style="font-size: 11px;  max-width: 30ch; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;color:#303030; ">' . ($oggetti_vendite > 0 ? 'componenti : ' . $oggetti_vendite : '') . ' </div>

		</li> ';
	}
}

echo $vendite_txt;
