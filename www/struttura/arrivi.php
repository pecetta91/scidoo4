<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../config/connecti.php';
require_once __DIR__ . '/../../config/funzioni.php';

$IDstruttura = $_SESSION['IDstruttura'];

$arr_dati = (isset($_POST['arr_dati']) ? $_POST['arr_dati'] : []);
$data = $arr_dati['time'] ?? 0;

if ($data == 0) {
	$time = (isset($_SESSION['time_arrivi']) ? $_SESSION['time_arrivi'] : time_struttura());
	$dataoggi = date('Y-m-d', $time);
} else {
	$time = strtotime(convertiData($data));
	$dataoggi = date('Y-m-d', $time);
}

$time = time0($time);

$_SESSION['time_arrivi'] = $time;

$time_ieri = $time - 86400;
$time_domani = $time + 86400;

$lista_prenotazioni = [];
$query = "SELECT p.IDpren FROM prenextra AS p WHERE p.time>='$time_ieri' AND p.time<'$time_domani' AND p.IDstruttura='$IDstruttura' AND p.tipolim='4' GROUP BY p.IDpren ORDER BY p.time DESC";
$result = mysqli_query($link2, $query);
while ($row = mysqli_fetch_row($result)) {
	$lista_prenotazioni[] = $row['0'];
}

$alloggi = get_alloggi($IDstruttura);
$prenotazioni = get_prenotazioni(['0' => ['IDprenotazione' => $lista_prenotazioni]])['dati'];

$stato_arrivi = ['0' => ['html' => ' ', 'numero' => 0], '1' => ['html' => ' ', 'numero' => 0], '2' => ['html' => ' ', 'numero' => 0]]; //0 Arrivi, 1 Fermata, 2 Partenza
if (!empty($prenotazioni)) {
	foreach ($prenotazioni as $val) {
		$IDprenotazione = $val['ID'];

		$time0_arrivo = time0($val['checkin']);
		$time0_partenza = time0($val['checkout']);
		$stato = null;
		if ($time == $time0_arrivo) {$stato = 0;}
		if ($time == $time0_partenza) {$stato = 2;}
		if ($stato === null) {$stato = 1;}

		if (isset($val['alloggi'][$time])) {
			$IDalloggio = $val['alloggi'][$time];
		} else {
			$IDalloggio = $val['alloggi'][$time - 86400];
		}

		$stato_arrivi[$stato]['numero'] += 1;
		$stato_arrivi[$stato]['html'] .= '
		<li  style="position:relative" onclick="navigation(6,{IDprenotazione:' . $IDprenotazione . '},2,0);">
				<div class="c000 uk-text-bold" style="line-height: 15px;">
					<div> ' . $val['numero'] . ' ' . $val['nome_cliente'] . '	   <div style="float:right"><i class="fas fa-ellipsis-h"></i> </div></div>
					<div style="  font-size: 12px;  display: block; color: #777;  font-weight: 400;">
					' . ($val['checkin'] != '' ? dataita7($val['checkin']) : '') . ' -  ' . ($val['checkout'] != '' ? dataita7($val['checkout']) : '') . '</div>
				</div>

				<div class=" uk-text-bold c000" style="font-size:13px;line-height:15px;">
						' . $val['persone'] . '	<i class="fas fa-user-alt"></i> ' . $val['notti'] . '	<i class="fas fa-moon"></i>
						<div style="font-size: 14px;  font-weight: 400;   margin-top: 5px;  vertical-align: bottom; color: #303030; display: inline-block; max-width: 35ch;" class="testo_elissi_standard">' . $alloggi[$IDalloggio]['alloggio'] . ' </div>
				</div>
			</div>
		</li>';

	}
}

$testo = '
<ul class="no_before uk_tab_pulizie"  uk-tab="connect: #switcher; animation: uk-animation-fade" >
    <li class="uk-active" ><a href="#">Arrivi ' . ($stato_arrivi[0]['numero'] != 0 ? '<div class="numero_not_giorn">' . $stato_arrivi[0]['numero'] . '</div>' : '') . '</a></li>
    <li ><a href="#">Partenze ' . ($stato_arrivi[2]['numero'] != 0 ? '<div class="numero_not_giorn">' . $stato_arrivi[2]['numero'] . '</div>' : '') . ' </a></li>
    <li  ><a href="#">Permanenze ' . ($stato_arrivi[1]['numero'] != 0 ? '<div class="numero_not_giorn">' . $stato_arrivi[1]['numero'] . '</div>' : '') . ' </a></li>
</ul>

<ul class="uk-switcher uk-margin"  id="switcher">
    <li><ul class="uk-list lista_dati_default"  style="padding: 0">' . $stato_arrivi[0]['html'] . '</ul> </li>
    <li><ul class="uk-list lista_dati_default"  style="padding: 0"> ' . $stato_arrivi[2]['html'] . '</ul></li>
    <li><ul class="uk-list lista_dati_default"  style="padding: 0">' . $stato_arrivi[1]['html'] . ' </ul></li>
</ul>';

echo $testo;

?>
