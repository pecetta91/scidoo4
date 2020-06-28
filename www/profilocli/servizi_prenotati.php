<?php
require_once '../../config/connecti.php';
require_once '../../config/funzioni.php';
$lang = $_SESSION['lang'];

//header('Access-Control-Allow-Origin: *');

$IDpren = $_SESSION['IDstrpren'];

$timeoggi = time_struttura();

$query = "SELECT time,IDstruttura,checkout FROM prenotazioni WHERE IDv='$IDpren' LIMIT 1";
$result = mysqli_query($link2, $query);
$row = mysqli_fetch_row($result);
$time = $row['0'];
$IDstr = $row['1'];
$checkout = $row['2'];
$IDprenc = prenotcoll($IDpren);

$datacheckin = date('Y-m-d', $time);
$datacheckout = date('Y-m-d', $checkout);

$array_servizi_prenotati = array();
$array_servizi_da_impostare = array();

$query = "SELECT p.ID,FROM_UNIXTIME(p.time,'%Y-%m-%d'),p.extra,SUM(p2.qta),p.IDtipo,p.sottotip,p.tipolim,s.servizio,p.modi,p.time,s.descrizione FROM prenextra as p JOIN prenextra2 as p2 ON p.ID=p2.IDprenextra JOIN servizi as s ON p.extra=s.ID WHERE p.IDstruttura='$IDstr' AND   p2.IDpren IN ($IDprenc)  AND p2.paga>'0'  AND p.tipolim IN(1,2,3) AND FROM_UNIXTIME(p.time,'%Y-%m-%d')>='$datacheckin' AND FROM_UNIXTIME(p.time,'%Y-%m-%d')<='$datacheckout' GROUP BY p.ID ORDER BY p.time";
$result = mysqli_query($link2, $query);
if (mysqli_num_rows($result) > 0) {
	while ($row = mysqli_fetch_row($result)) {

		$ID = $row['0'];

		$time0_in = strtotime($row['1']);
		$time0 = $time0_in;

		$extra = $row['2'];
		$qta = $row['3'];

		$IDtipo = $row['4'];
		$IDsotto = $row['5'];
		$tipolim = $row['6'];

		$servizio = traducis('', $extra, 1, $lang, 0, $qta);
		$modi = $row['8'];
		$timeserv = $row['9'];
		$descrizione = traducis('', $extra, 2, $lang, 0, $qta);

		if ($IDtipo == 1) {
			$query4 = "SELECT GROUP_CONCAT(s.servizio SEPARATOR ' , '),dp.portata FROM dispgiorno as dp,servizi as s,sottotipologie as st WHERE dp.IDsottotip='$IDsotto' AND  FROM_UNIXTIME(dp.data,'%Y-%m-%d')=FROM_UNIXTIME('$time0','%Y-%m-%d') AND dp.IDpiatto=s.ID AND s.IDsottotip=st.ID  GROUP BY dp.portata ORDER BY dp.portata LIMIT 1";
			$result4 = mysqli_query($link2, $query4);
			if (mysqli_num_rows($result4) > 0) {
				$array_servizi_prenotati[$time0][$ID][9] = '
								<div class="row no-gutter col-100" style="color:#1124ad;margin-top: 10px;">
									<div class="col-80 fs16">
											<div>' . traduci('Visualizza menu', $lang, 1, 0) . '</div>
									 </div>
								 	<div class="col-20" style="text-align:center"><i class="ion-information-circled fs16"></i></div>
								 </div>  ';
			}
		}

		$array_servizi_prenotati[$time0][$ID][0] = $IDtipo;
		$array_servizi_prenotati[$time0][$ID][1] = $IDsotto;
		$array_servizi_prenotati[$time0][$ID][2] = $tipolim;
		$array_servizi_prenotati[$time0][$ID][3] = $modi;
		$array_servizi_prenotati[$time0][$ID][4] = $timeserv;
		$array_servizi_prenotati[$time0][$ID][5] = $servizio;
		$array_servizi_prenotati[$time0][$ID][6] = $extra;
		$array_servizi_prenotati[$time0][$ID][7] = $qta;
		$array_servizi_prenotati[$time0][$ID][8] = $descrizione;

	}
}

foreach ($array_servizi_prenotati as $time0 => $time) {

	$data2 = dataita($time0);
	$testo .= '<div class="time time' . $time0 . '" >
	<div style="width:100%;height:30px;margin-bottom:10px;border-bottom:1px solid #e1e1e1"  class="selezionadate"  ><div style="padding-left:10px;line-height:30px;font-size:13px" ><span >' . $data2 . '</span></div></div>';

	foreach ($time as $ID => $dato) {

		if ($dato[3] == 0) {
			$testoora = '--.--';
			$modi = 0;
		} else {
			$testoora = date('H:i', $dato[4]);
			$modi = 1;
		}

		$persone = traduci('Per', $lang, 1) . ' ' . $dato[7] . ' ' . txtpersone($dato[7], $lang, 0);

		$menu = '';
		if (isset($dato[9])) {
			$menu = $dato[9];
		}

		$txt = '<div class="servsosp tipolim' . $dato[0] . '" id="riga' . $ID . '"  onclick="navigation(3,' . $ID . ',0,0)"  >

			<div uk-grid>
 				 <div class="uk-width-auto">
 				 	 <span class="fw600 fs13" style="color:#1124ad">' . $testoora . '</span>
 				 </div>
					<div class="uk-width-auto"  style="padding:0px 5px">

							<div style="font-size:16px;color:#202020;padding-right:15px;font-weight:600">' . $dato[5] . '</div>

							<div class="uk-text-truncate uk-text-muted">' . $dato[8] . '</div>

							<div  style="margin-top:5px"><div style="color: #1124ad; font-size: 14px;">' . $persone . '</div>

							' . $menu . '


					</div>

				</div>
		</div> ';

		if ($modi == 0) {

			if (!empty($array_servizi_da_impostare)) {
				$array_servizi_da_impostare[$modi] .= $txt;
			} else {
				$array_servizi_da_impostare[$modi] = $txt;
			}

		} else {
			$testo .= $txt;
		}

	}

}

$testo2 = '';

if (!empty($array_servizi_da_impostare)) {

	$testo2 = '<div style="margin-bottom:25px" id="sosp" class="selezionadate" alt="0" >
	<div class="uk-heading-divider" ><strong>' . traduci('Servizi da Impostare', $lang, 1, 0) . '</strong> </div>

	';
	foreach ($array_servizi_da_impostare as $serv) {
		$testo2 .= $serv;
	}
	$testo2 .= '</div>';
}

$testo2 .= '
	<div class="uk-heading-divider" style="margin-top:10px;" ><strong>' . traduci('Servizi Prenotati', $lang, 1, 0) . '</strong></div>
	<div style="margin-top:5px;" id="servizi"> ' . $testo . '</div>';

echo $testo2;
?>
