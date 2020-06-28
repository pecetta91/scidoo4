<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';
$IDstruttura = $_SESSION['IDstruttura'];

/*
if (isset($_GET['parametri'])) {
$parametri = array_map(function ($e) use ($link2) {if (is_array($e)) {return $e;}return $link2->escape_string(htmlspecialchars($e));}, $_GET['parametri']);
$parametri = array_merge($_SESSION['filtri_messaggi'], $parametri);

} else {
$parametri = $_SESSION['filtri_messaggi'];
}

 */

$filtro = [];
$filtroprev = [];
$filtropren = [];

$time = time_struttura();
$time_mese = $time - 30 * 86400;
$query = "
SELECT * FROM ( SELECT IDobj,tipoobj FROM messaggi
 WHERE ricevuto=1 AND IDstr=$IDstruttura AND data>=$time_mese
 GROUP BY IDobj,tipoobj
 ORDER BY data DESC
 ) as messaggi

LIMIT 100";

$result = mysqli_query($link2, $query);
while ($row = mysqli_fetch_row($result)) {
	switch ($row['1']) {
	case 0:
		$filtropren['IDprenotazione'][] = $row['0'];
		break;
	case 1:
		$filtroprev['IDpreventivo'][] = $row['0'];
		break;
	}
}
$filtro1 = $filtro + $filtroprev;
$filtro2 = $filtro + $filtropren;

$messaggi = estrai_messaggi([$filtro1, $filtro2], 'per_oggetto');

$txt = '';

foreach ($messaggi as $IDmessaggio => $val) {

	if (isset($val['tipoobj'])) {

		$tipoobj = $val['tipoobj'];
		$IDobj = $val['IDobj'];

		$da_leggere = '';
		if ($val['numero_messaggi_da_leggere'] > 0) {
			$da_leggere = '
			<span style=" font-size: 12px;
		    position: absolute;   width: 20px;   height: 20px;  background: #5a5aff;  color: #fff;   text-align: center;  border-radius: 50%;  line-height: 20px;right:6px;top:5px;">' . $val['numero_messaggi_da_leggere'] . '</span> ';

		}

		$txt .= '
			<li  style="position:relative"   onclick=" apri_chat_struttura({IDobj:' . $IDobj . ',tipoobj:' . $tipoobj . '});">
				<div class="c000 uk-text-bold" style="line-height: 15px;">
					<div> ' . $val['nome_oggetto'] . ' ' . $val['nome_cliente'] . '	 ' . $val['nome_tipoobj'] . ' <div style="float:right"><i class="fas fa-ellipsis-h"></i></div>  </div>

					<div style="  font-size: 12px;  display: block; color: #777;  font-weight: 400;">
					 ' . ($val['checkin_oggetto'] != '' ? dataita7($val['checkin_oggetto']) : '') . '</div>
				</div>

				<div class=" uk-text-bold c000" style="font-size:13px;line-height:15px;">
					 		' . date('d/m/Y H:i', $val['time_messaggio']) . '
						<div style="font-size: 14px;  font-weight: 400;   margin-top: 5px;  vertical-align: bottom; color: #303030; display: inline-block; max-width: 35ch;" class="testo_elissi_standard">
						 ' . Tagliastringa2($val['messaggio'], 50) . ' </div>
				</div>
			</div>' . $da_leggere . '
		</li> ';
	}
}

echo $txt;

?>
