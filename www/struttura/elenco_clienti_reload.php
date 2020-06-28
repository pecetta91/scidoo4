<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../config/connecti.php';
require_once __DIR__ . '/../../config/funzioni.php';
$IDstruttura = $_SESSION['IDstruttura'];

if (isset($_POST['parametri'])) {
	$parametri = array_map(function ($e) use ($link2) {if (is_array($e)) {return $e;}return $link2->escape_string(htmlspecialchars($e));}, $_POST['parametri']);
	$parametri = array_merge($_SESSION['filtri_clienti'], $parametri);
	//$parametri['IDprenotazione'] = [];
} else {
	$parametri = $_SESSION['filtri_clienti'];
}

$ricerca = $parametri['testo'] ?? '';

$_SESSION['filtri_clienti'] = $parametri;

$testo = '';

$filtro = [];
if (strlen($ricerca) > 2) {
	$lista_id_clienti = ricercacliente($ricerca, $IDstruttura);
	$filtro['IDcliente'] = (!empty($lista_id_clienti) ? $lista_id_clienti : [0]);
}

$lista_ospiti = estrai_dati_ospiti([$filtro])['dati'];
if (!empty($lista_ospiti)) {
	foreach ($lista_ospiti as $key => $val) {
		$IDschedina = $val['ID'];

		$testo .= '
		<li onclick="navigation(7,{IDcliente:' . $IDschedina . '},3,0);">

			<div class="c000 uk-text-bold" style="line-height: 15px;">
				<div>' . $val['nome'] . ' ' . $val['cognome'] . ' <div style="float:right;"><i class="fas fa-ellipsis-h"></i></div> </div>
				<div style="  font-size: 12px;  display: block; color: #777;  font-weight: 400;">' . $val['cellulare'] . '   </div>
			</div>


			<div style="font-size: 11px;  max-width: 30ch; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;color:#303030; ">' . $val['email'] . '</div>
		</li> ';

	}
}

echo $testo;
