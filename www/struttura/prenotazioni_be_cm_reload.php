<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../config/connecti.php';
require_once __DIR__ . '/../../config/funzioni.php';
$IDutente = $_SESSION['ID'];
$IDstruttura = $_SESSION['IDstruttura'];

if (isset($_POST['parametri'])) {
	$parametri = array_map(function ($e) use ($link2) {if (is_array($e)) {return $e;}return $link2->escape_string(htmlspecialchars($e));}, $_POST['parametri']);
	$parametri = array_merge($_SESSION['filtri_be_cm'], $parametri);

} else {
	$parametri = $_SESSION['filtri_be_cm'];
}

if (isset($parametri['data_inizio'])) {
	$time_ini = strtotime(convertiData($parametri['data_inizio']));
	$parametri['time_inizio'] = $time_ini;
}

$_SESSION['filtri_be_cm'] = $parametri;

$query = "SELECT p.IDv,p.datapren,p.time,p.gg,p.settore_inserimento,p.checkout,COALESCE(inte.intestazione,CONCAT(s2.cognome,' ',s2.nome)),ag.color,GROUP_CONCAT(CONCAT(s.cognome,' ',s.nome) ORDER BY i.ID SEPARATOR '/'),COUNT(DISTINCT (i.ID))
FROM prenotazioni as p
JOIN infopren as i ON i.IDpren=p.IDv
LEFT JOIN schedine as s ON i.IDcliente=s.ID
LEFT JOIN agenziepren as a ON a.IDobj=p.IDv AND a.tipoobj='0'
LEFT JOIN agenzie as ag ON ag.ID=a.IDagenzia
LEFT JOIN intestazioni as inte ON inte.ID=ag.IDintestazione AND ag.tipointestazione='4'
LEFT JOIN schedine as s2 ON s2.ID=ag.IDintestazione AND ag.tipointestazione='0'
WHERE p.IDstruttura='$IDstruttura' AND p.settore_inserimento IN (2,4,6) AND p.time>$time_ini AND p.stato!='-1'
GROUP BY p.IDv
ORDER BY p.datapren DESC ";
//echo $query;
$result = mysqli_query($link2, $query);
while ($row = mysqli_fetch_row($result)) {

	$IDprenotazione = $row['0'];

	$datapren = $row['1'];
	$arrivo = $row['2'];

	$gg = $row['3'];
	$partenza = $row['5'];
	$numero_persone = $row['9'];
	$nome_ospite = explode('/', $row['8'])['0'] ?? '';

	$agenziatxt = '';

	if ($row['6']) {
		$color = '#000';
		if ($row['7']) {
			$color = $row['7'];
		}
		$agenziatxt = '<strong style="color:' . $color . '">' . $row['6'] . '</strong>';
	}

	switch ($row['4']) {
	case 2:
		$luogo = ' Prenotazione Online';
		break;
	case 4:
		$luogo = ' Channel Manager';
		break;
	case 6:
		$luogo = ' Preventivatore';
		break;
	}

	if ($agenziatxt != '') {
		$luogo = $agenziatxt . '<br/> <span style="font-size:10px;">' . $luogo . '</span>';
	}

	$txt .= '
		<li  style="position:relative" onclick="navigation(6,{IDprenotazione:' . $IDprenotazione . '});">
				<div class="c000 uk-text-bold" style="line-height: 15px;">
					<div>' . $nome_ospite . ' <span style="font-size:10px;">' . date('d/m/Y H:i', $datapren) . '</span><div style="float:right"><i class="fas fa-ellipsis-h"></i></div>  </div>
					<div style="  font-size: 12px;  display: block; color: #777;  font-weight: 400;">
					' . ($arrivo != '' ? dataita7($arrivo) : '') . ' -  ' . ($partenza != '' ? dataita7($partenza) : '') . '</div>
				</div>

				<div class=" uk-text-bold c000" style="font-size:13px;line-height:15px;">
					 	' . $numero_persone . ' <i class="fas fa-user-alt"></i> ' . $gg . '	<i class="fas fa-moon"></i>
						<div style="font-size: 14px;  font-weight: 400;   margin-top: 5px;  vertical-align: bottom; color: #303030; display: inline-block; max-width: 35ch;" class="testo_elissi_standard">' . $luogo . ' </div>
				</div>
			</div>
		</li>';
}

echo $txt;

?>