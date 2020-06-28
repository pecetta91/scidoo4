<?php
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';
$IDutente = $_SESSION['ID'];
$IDstruttura = $_SESSION['IDstruttura'];

if (isset($_POST['IDpren'])) {
	$IDprenotazione = $_POST['IDpren'];
}

$servizi_frigo_bar = [];
$query = "SELECT IDserv FROM frigo_bar WHERE IDstruttura=$IDstruttura";
$result = mysqli_query($link2, $query);
while ($row = mysqli_fetch_row($result)) {
	$servizi_frigo_bar[] = $row[0];
}

$lista_servizi = get_dati_conto_riferimento($IDprenotazione, 0, [], $IDstruttura)['arr_servizi'];
$servizi = [];
if (!empty($lista_servizi)) {
	foreach ($lista_servizi as $IDaddebito => $dati) {
		if (!in_array($dati['IDserv'], $servizi_frigo_bar)) {continue;}
		$IDservizio = $dati['IDserv'];

		$qta_serv = 0;
		if ($dati['tipolim'] == 6) {
			$qta_serv = array_sum(array_column($dati['componenti'], 'qta'));
		}

		$prezzo = array_sum(array_column($dati['componenti'], 'prezzo'));
		$time0 = time0($dati['time']);

		if (isset($servizi[$time0][$IDservizio])) {
			$servizi[$time0][$IDservizio]['numero'] += 1;
			$servizi[$time0][$IDservizio]['prezzo'] += $prezzo;
		} else {
			$servizi[$time0][$IDservizio]['numero'] = 1;
			$servizi[$time0][$IDservizio]['servizio'] = $dati['nome_servizio'];
			$servizi[$time0][$IDservizio]['prezzo'] = $prezzo;
			$servizi[$time0][$IDservizio]['qta'] = $qta_serv;
		}

	}
}
$serv_addebitati = '';
if (!empty($servizi)) {
	$serv_addebitati = '<div class="uk-margin">';
	foreach ($servizi as $time => $dati) {
		$lista_addebiti = '';
		foreach ($dati as $dati_serv) {

			$lista_addebiti .= '
			<div class="uk_grid_div div_list_uk  "  uk-grid  >

				<div class="uk-width-auto lista_grid_numero uk-first-column numero_servizi_conto">' . ($dati['qta'] != 0 ? $dati['qta'] : $dati['numero']) . '</div>

			    <div class="uk-width-1-2 uk-text-truncate lista_grid_nome uk-first-column nome_servizi_conto">' . $dati_serv['servizio'] . '	</div>

			    <div class="uk-width-expand uk-text-right lista_grid_right c000 fs16 prezzo_servizi_conto">  </div>

			</div>  ';

		} //onclick="aumenta_serv(2,' . $row4['0'] . ',2);"  onclick="aumenta_serv(2,' . $row4['0'] . ',1);"

		$serv_addebitati .= '<div class="uk-heading-divider uk-margin"><strong>' . dataita6($time) . '</strong></div> ' . $lista_addebiti;

	}

	$serv_addebitati .= '</div>';
}

/*
<div class="uk-margin-small uk_grid_div uk-grid pul_serv " uk-grid="" style="    margin-left: -30px;">
<div class="uk-width-3-5 uk-text-truncate lista_grid_nome uk-first-column">' . $dati_serv['servizio'] . '</div>
<div class="uk-width-expand uk-text-right lista_grid_right ">
<div class="stepper  stepper-init stepperrestr">

<div class="stepper-button-minus "  uk-icon="minus"></div>

<div class="stepper-value  serv" min="0" max="20"  >' . ($dati_serv['qta'] != 0 ? $dati_serv['qta'] : $dati_serv['numero']) . '</div>

<div class="stepper-button-plus" uk-icon="plus"></div>
</div>

</div>
</div>

$serv_addebitati = '';
$timeold = 0;
$query4 = "SELECT p.extra,SUM(p2.qta),FROM_UNIXTIME(p.time,'%Y-%m-%d'),s.servizio,GROUP_CONCAT(DISTINCT p.ID)  FROM prenextra as p
JOIN frigo_bar as f ON f.IDserv=p.extra AND f.IDstruttura='$IDstruttura'
LEFT JOIN prenextra2 as p2 ON p2.IDprenextra=p.ID
JOIN servizi as s ON s.ID=p.extra
WHERE p.IDstruttura='$IDstruttura' AND p.IDpren='$IDpren' AND p2.paga='1'  GROUP BY p.extra,UNIX_TIMESTAMP(FROM_UNIXTIME(p.time,'%Y-%m-%d')) ORDER BY p.time";
$result4 = mysqli_query($link2, $query4);
if (mysqli_num_rows($result4) > 0) {
$serv_addebitati .= '<div class="uk-margin">';
while ($row4 = mysqli_fetch_row($result4)) {
//$time_s=$row4['2'];

$time0_in = strtotime($row4['2']);
if ($timeold != $time0_in) {
$timeold = $time0_in;
$serv_addebitati .= '<div class="uk-heading-divider uk-margin"><strong>' . dataita6($time0_in) . '</strong></div> ';
}
$serv_addebitati .= '

<div class="uk-margin-small uk_grid_div uk-grid pul_serv' . $row4['0'] . '" uk-grid="" style="    margin-left: -30px;">
<div class="uk-width-3-5 uk-text-truncate lista_grid_nome uk-first-column">' . $row4['3'] . '</div>
<div class="uk-width-expand uk-text-right lista_grid_right ">
<div class="stepper  stepper-init stepperrestr">

<div class="stepper-button-minus " onclick="aumenta_serv(2,' . $row4['0'] . ',2);" uk-icon="minus"></div>

<div class="stepper-value  serv" min="0" max="20"  alt="' . $row4['4'] . '" id="mod' . $row4['0'] . '">' . $row4['1'] . '</div>

<div class="stepper-button-plus " onclick="aumenta_serv(2,' . $row4['0'] . ',1);" uk-icon="plus"></div>
</div>

</div>
</div>  ';
}

$serv_addebitati .= '</div>';
}*/

echo $serv_addebitati;

?>
