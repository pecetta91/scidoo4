<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';
$IDstruttura = $_SESSION['IDstruttura'];

if (isset($_POST['parametri'])) {
	$parametri = array_map(function ($e) use ($link2) {if (is_array($e)) {return $e;}return $link2->escape_string(htmlspecialchars($e));}, $_POST['parametri']);
	$parametri = array_merge($_SESSION['filtri_preventivo'], $parametri);
	//$parametri['IDprenotazione'] = [];
} else {
	$parametri = $_SESSION['filtri_preventivo'];
}

$ricerca = $parametri['ricerca'] ?? '';
if (isset($parametri['data_inizio'])) {
	$time_ini = strtotime(convertiData($parametri['data_inizio']));
	$parametri['time_inizio'] = $time_ini;
}

if (isset($parametri['data_fine'])) {
	$time_fine = strtotime(convertiData($parametri['data_fine']));
	$parametri['time_fine'] = $time_fine;
}

$_SESSION['filtri_preventivo'] = $parametri;

$filtro_preventivo = $parametri;

$IDclienti_trovati = [];
if (strlen($ricerca) > 3) {
	$IDclienti_trovati = ricercacliente($ricerca, $IDstruttura);
}
if (is_numeric($ricerca)) {
	$filtro_preventivo['IDsequenziale'] = $ricerca;
}

if (!empty($IDclienti_trovati)) {
	$filtro_preventivo['cliente'] = $IDclienti_trovati;
}

switch ($filtro_preventivo['stato']) {
case 5: //non aperti
	$filtro_preventivo['aperture'] = '0';
	$filtro_preventivo['stato'] = [3, 4];
	break;
case 6: //scaduti
	$filtro_preventivo['max_scadenza'] = time_struttura();
	$filtro_preventivo['scadenza'] = true;
	unset($filtro_preventivo['stato']);
	break;
case 7: //preventivi richiesta di prenotazione
	$filtro_preventivo['stato'] = 6;
	break;
}

$lista_preventivi = get_preventivi([$filtro_preventivo]);

$txt_preventivi = '';
if (!empty($lista_preventivi)) {
	foreach ($lista_preventivi as $key => $val) {

		$IDpreventivo = $val['ID'];
		$nome_cliente = $val['nome_cliente'];
		$data_checkin = $val['checkin'];

		$ricevuto = '0';
		$scadenza = (($val['scadenza']) ?? 0) ?: '--';
		$creato_il = $val['time_creazione'] ?? '';
		$personale = $val['personale'] ?? 'Ospite';

		$aperto = '--';
		$info_open = '';

		if (isset($val['aperture'])) {
			$aperto = count($val['aperture']);
			$aperto .= ' ' . ($aperto != 1 ? 'volte' : 'volta');
		}

		if ($scadenza != '--') {
			$scadenza = dataita5($scadenza);
		}

		$txt_preventivi .= '
		<li onclick="navigation(22,{IDpreventivo:' . $IDpreventivo . ',time:0},()=>{stampa_carrello_preventivo()},0);">

			<div class="c000 uk-text-bold" style="line-height: 15px;">
				<div>' . $nome_cliente . '  <div style="float:right;">' . $aperto . '</div> </div>
				<div style="  font-size: 12px;  display: block; color: #777;  font-weight: 400;"> ' . dataita5($data_checkin) . '</div>


			</div>


			<div style="font-size: 11px;  max-width: 30ch; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;color:#303030; ">' . $personale . ' ' . date('d/m/Y H:i', $creato_il) . '</div>
		</li> ';
	}
}

echo $txt_preventivi;
