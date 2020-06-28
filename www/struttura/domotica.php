<?php
//header('Access-Control-Allow-Origin: *');
include '../../config/connecti.php';
include '../../config/funzioni.php';

$IDutente = $_SESSION['ID'];
$IDstruttura = $_SESSION['IDstruttura'];

$timeora = oraadesso($IDstruttura);
$data = date('Y-m-d', $timeora);
$ora = $timeora - 7200;
$before = $timeora + 7200;
$adesso = $timeora;

$testo = '
<div class="div_container_principale">
 <ul class="uk-switcher uk-margin"  id="switcher"><li>';

$accman = -1;
$query = "SELECT ID,stato,etichetta,tipo,temp FROM domotica WHERE IDstr='$IDstruttura' AND tipo IN(1,2,3)";
$result = mysqli_query($link2, $query);
if (mysqli_num_rows($result) > 0) {
	while ($row = mysqli_fetch_array($result)) {
		$IDdom = $row['0'];
		$acc = $row['1'];
		$title = "Spento<br>Auto";
		if ($acc == 0) {$classdom = "tav01";} else {
			$classdom = "tav3";
			$title = " Accesso<br>Auto";}
		$mess = '';

		$temp = '';

		if ($row['3'] == 1) {
			$temp = $row['4'] . '&deg;';
		}

		$man = 0;
		$trov = 0;
		for ($j = 0; $j < 2; $j++) {
			if ($trov == 0) {
				if ($j == 1) {
					$query2 = "SELECT acceso,timei,timef FROM pianificazione WHERE IDdom='$IDdom' ORDER BY timei LIMIT 1";
					$result2 = mysqli_query($link2, $query2);
					if (mysqli_num_rows($result2) > 0) {
						$man = 1;
						$row2 = mysqli_fetch_row($result2);
						$accman = $row2['0'];
						$timei = $row2['1'];
						$timef = $row2['2'];
						$attivato = '';
						$title = "Manuale";

						if ($accman == 1) {$attivato = 'Si accender&agrave;';} else { $attivato = 'Si spegner&agrave;';}

						$mess = $attivato . ' dalle: ' . date('H:i', $timei) . '/' . date('H:i', $timef) . '';
						$trov = 1;
					}
				} else {
					$query2 = "SELECT acceso,timei,timef FROM pianificazione WHERE IDdom='$IDdom' AND '$before'>=timei AND '$adesso'<timef LIMIT 1";
					$result2 = mysqli_query($link2, $query2);
					if (mysqli_num_rows($result2) > 0) {
						$man = 1;
						$row2 = mysqli_fetch_row($result2);
						$accman = $row2['0'];
						$timei = $row2['1'];
						$timef = $row2['2'];
						$attivato = '';
						$attivato = '';
						$title = "Manuale";
						if ($accman == 1) {
							$classdom = "tav4";
							$attivato = 'Acceso';} else {
							$classdom = "tav0";
							$attivato = 'Spento';}

						$mess = $attivato . ' dalle: ' . date('H:i', $timei) . '/' . date('H:i', $timef) . '';
						$trov = 1;
					} else {
						/*
										$mess='Si attiver&agrave; alle:<br>
										<span style="font-size:16px;">10:30</span>';*/

						$queryd = "SELECT P.time-(T.dato3*60) FROM tempserv AS T,prenextra AS P,domotica AS D WHERE (P.time-(T.dato3*60))>'$ora'  AND FROM_UNIXTIME(P.time,'%Y-%m-%d')= FROM_UNIXTIME('$ora','%Y-%m-%d')  AND D.ID=T.IDdom AND D.ID='$IDdom' AND P.sottotip=T.ID AND P.modi>'0' ORDER BY P.time LIMIT 1";

						$resultd = mysqli_query($link2, $queryd);
						if (mysqli_num_rows($resultd) > 0) {
							$man = 0;
							$rowd = mysqli_fetch_row($resultd);
							$timeacc = $rowd['0'];
							$mess = 'Si attiver&agrave; alle: ' . date('H:i', $timeacc);
							$trov = 1;
						} else {
							$mess = 'Oggi non attivo';
							$trov = 0;
						}
					}
				}
			}

		}

		//$funcdom='opensubmenu(8,'."'".$IDdom."_".$acc."_".$man."'".')';

		//$funcdom2="modprenot(".$IDdom.",0,68,10,16)";
		$exit = '<img src="images/power.svg" style="width:20px; height:20px;">';

		if ($row['3'] == 1) {
			$exit = '<div style="font-size:17px;margin-top:3px; font-weight:400; color:#fff;">' . $row['4'] . '&deg;</div >';
		}

		if (($man == 1) && ($acc != $accman)) {
			$exit = '<img src="images/hold.svg" style="width:20px; height:20px;">';
			$acc = $accman;
		}

		//onmousedown="settain(0)" onmouseup="settaout5(this)" alt="'.$funcdom.'" lang="'.$funcdom2.'"
		$query3 = "SELECT timei,timef,acceso FROM pianificazione WHERE IDdom='$IDdom' ";
		$result3 = mysqli_query($link2, $query3);
		$elim = 0;
		if (mysqli_num_rows($result3) > 0) {$elim = 1;}

		$testo .= '<input type="hidden" value="' . $elim . '" id="iddomo' . $IDdom . '">
					<div uk-grid class=" uk_grid_div" onclick="pulsdomotica(' . $IDdom . ')">
						<div class="uk-width-expand lista_grid_nome uk-text-truncate">
							<div class="ntavolo ' . $classdom . '" style="display:inline-block;margin-left:5px;margin-right:5px;"></div>
								<div style="display:inline-block">
								 ' . $row['2'] . '  <br> <span class="uk-text-muted uk-text-small">' . $mess . '</span>
								 </div>
						</div>


						<div class="uk-width-auto uk-text-right">
								<table ><tr>
									<td class="bordotempdom" style="    border-right: solid 1px #ccc;  color: #1649b1;  padding-right: 5px;">
									' . $temp . '
								 </td>
								 <td class="modalitadom" style="    font-size: 10px;  width: 50px;  text-align: center;">
								 ' . $title . '
								 </td>
								 <td><span uk-icon="chevron-right" ></span></td>

								 </tr></table>
						</div>

					</div>

					<hr style="margin:4px 0;"> ';

	}
}

$testo .= '</li> <li>';

$colorapp = array('333', 'ae3232', '324cae');
$query = "SELECT nome,temp,stato,ID,domotrisc,domotraff,risc,statod FROM appartamenti WHERE IDstruttura='$IDstruttura' AND attivo='1' ";
$result = mysqli_query($link2, $query);
while ($row = mysqli_fetch_row($result)) {
	$IDapp = $row['2'];
	$domotrisc = $row['4'];
	$domotraff = $row['5'];

	if (($domotrisc != 0) || ($domotraff != 0)) {
		$domot = 0;
		$txtraff = '';

		if ($row['6'] == 1) {
			$domot = $domotrisc;
			$txtraff = 'Riscaldamento';
		} else {
			$domot = $domotraff;
			$txtraff = 'Raffrescamento';
		}

		$temp = $row['1'] . '  &deg;';
		$IDdom = 0;

		$query2 = "SELECT ID FROM domotica WHERE IDstr='$IDstruttura' AND ID='$domot'";
		$result2 = mysqli_query($link2, $query2);
		if (mysqli_num_rows($result2) > 0) {
			$row2 = mysqli_fetch_array($result2);
			$IDdom = $row2['0'];
		} else {
			$temp = 'Non collegato';
		}

		$acc = 0;

		$mex = '';
		if ($row['7'] == 1) {
			$acc = 1;
			$mex = '<b>' . $txtraff . ' Auto</b> - Acceso';
		} else {
			$mex = '<b>' . $txtraff . ' Auto</b> - Spento';
		}

		//controllo pianificazione

		$query2 = "SELECT acceso,timei,timef FROM pianificazione WHERE IDdom='$IDdom' AND '$before'>=timei AND '$adesso'<timef LIMIT 1";
		$result2 = mysqli_query($link2, $query2);
		if (mysqli_num_rows($result2) > 0) {
			$man = 1;
			$row2 = mysqli_fetch_row($result2);
			$accman = $row2['0'];
			$timei = $row2['1'];
			$timef = $row2['2'];
			if ($accman == 0) {
				$mex = '<b>' . $txtraff . ' Manuale</b> - Si spegner&agrave; alle ' . date('H:i', $timei);
			} else {
				$mex = '<b>' . $txtraff . ' Manuale</b> - Si accender&agrave; alle ' . date('H:i', $timei);
			}
		} else {

			$query2 = "SELECT acceso,timei,timef FROM pianificazione WHERE IDdom='$IDdom' AND '$adesso'>=timei AND '$adesso'<timef LIMIT 1";
			$result2 = mysqli_query($link2, $query2);
			if (mysqli_num_rows($result2) > 0) {
				$man = 1;
				$row2 = mysqli_fetch_row($result2);
				$accman = $row2['0'];
				$timei = $row2['1'];
				$timef = $row2['2'];
				if ($accman == 0) {
					$mex = '<b>' . $txtraff . ' Manuale</b> - Spento dalle ' . date('H:i', $timei) . ' alle  ' . date('H:i', $timef);
				} else {
					$acc = 1;
					$mex = '<b>' . $txtraff . ' Manuale</b> - Acceso dalle ' . date('H:i', $timei) . ' alle  ' . date('H:i', $timef);
				}
			}
		}
		$classdom = '';
		switch ($acc) {
		case 0:
			$classdom = 'tav01';
			break;
		case 1:
			$classdom = 'tav1';
			break;
		}

		$query3 = "SELECT timei,timef,acceso FROM pianificazione WHERE IDdom='$IDdom' ";
		$result3 = mysqli_query($link2, $query3);
		$elim = 0;
		if (mysqli_num_rows($result3) > 0) {$elim = 1;}

		$testo .= '<input type="hidden" value="' . $elim . '" id="iddomo' . $IDdom . '">


					<div uk-grid class=" uk_grid_div" onclick="pulsdomotica(' . $IDdom . ')">

						<div class="uk-width-expand lista_grid_nome uk-text-truncate">
							<div class="ntavolo ' . $classdom . '" style="display:inline-block;margin-left:5px;margin-right:5px;"></div>
								<div style="display:inline-block">
								 ' . $row['0'] . ' <br> <span class="uk-text-muted uk-text-small" style="color:#' . $colorapp[$acc] . ';">' . $mex . '</span>
								 </div>
						</div>


						<div class="uk-width-auto uk-text-right">

							<span style="color:#' . $colorapp[$acc] . ';">' . $temp . '</span>

								 <span uk-icon="chevron-right"></span>
						</div>

					</div>

					<hr style="margin:4px 0;"> ';

		/*		<li onclick="pulsdomotica('.$IDdom.')"><div class="item-content h100" >
							<div class="item-media" >
												<div class="ntavolo '.$classdom.'"></div>
												</div>

							<div class="item-inner" >
							  <div class="item-title coloredomalloggi" >'.$row['0'].'<br>
							  <div  class="fs10 lh11" style="color:#'.$colorapp[$acc].';">'.wordwrap($mex,35,'<br>').'</div>
							  </div>
							  <div class="item-after" style="color:#'.$colorapp[$acc].';">'.$temp.'</div>
							</div>
							</div>

						</li>*/

	}
}

$testo .= '</li> </ul></div>';

echo $testo

?>
