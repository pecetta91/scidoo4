<?php
require_once '../../config/connecti.php';
require_once '../../config/funzioni.php';
$lang = $_SESSION['lang'];

////header('Access-Control-Allow-Origin: *');

$IDpren = $_SESSION['IDstrpren'];
$route = $_SESSION['route'];

$IDprenextra = $_POST['dato0'];

if (isset($_POST['dato1'])) {
	if ($_POST['dato1'] != '0') {
		$tempo_navigation = $_POST['dato1'];
	} else {
		$tempo_navigation = 0;
	}
} else {
	$tempo_navigation = 0;
}

$timeoggi = time_struttura();

$query = "SELECT time,IDstruttura,gg FROM prenotazioni WHERE IDv='$IDpren' LIMIT 1";
$result = mysqli_query($link2, $query);
$row = mysqli_fetch_row($result);
$checkin = $row['0'];
$IDstruttura = $row['1'];
$notti = $row['2'];
$IDprenc = prenotcoll($IDpren);

if ($timeoggi >= $checkin) {
	$checkin = $timeoggi;
}

list($yy, $mm, $dd) = explode("-", date('Y-m-d', $checkin));
$check0 = mktime(0, 0, 0, $mm, $dd, $yy);

$query = "SELECT p.extra,p.time,p.IDtipo,p.durata,p.tipolim,p.sottotip,p.modi,SUM(p2.qta),GROUP_CONCAT(p2.IDinfop SEPARATOR ',') FROM prenextra as p,servizi as s,prenextra2 as p2 WHERE p.ID='$IDprenextra' AND s.ID=p.extra AND p.ID=p2.IDprenextra  GROUP BY p.ID ";
$result = mysqli_query($link2, $query);
$row = mysqli_fetch_row($result);
$IDserv = $row['0'];
$time_servizio = $row['1'];
$IDtipo = $row['2'];
$durata = $row['3'];
$tipolim = $row['4'];
$IDsotto = $row['5'];
$modi = $row['6'];
$qta = $row['7'];
$groupid = $row['8'];
$servizio_presente = 0;

$orari = array();
$orari2 = array();
$oraridisponibili = array();
$graph = array();
$sale = array();

$query = "SELECT s.ID,s.nome,s.maxp FROM sale as s,saleex as sc WHERE s.IDstr='$IDstruttura' AND sc.IDserv='$IDserv' AND sc.IDsala=s.ID";
$result = mysqli_query($link2, $query);
if (mysqli_num_rows($result) > 0) {
	while ($row = mysqli_fetch_row($result)) {
		$IDsala = $row['0'];
		$nome = $row['1'];
		$maxp = $row['2'];
		$sale[$IDsala][0] = $nome;
		$sale[$IDsala][1] = $maxp;
	}
} else {
	$query = "SELECT s.ID,s.nome,s.maxp FROM sale as s,saleassoc as sc WHERE  s.IDstr='$IDstruttura' AND sc.IDsotto='$IDsotto' AND sc.ID=s.ID";
	$result = mysqli_query($link2, $query);
	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_row($result)) {
			$IDsala = $row['0'];
			$nome = $row['1'];
			$maxp = $row['2'];
			$sale[$IDsala][0] = $nome;
			$sale[$IDsala][1] = $maxp;

			//$sale[$row[0]] = ['nome' => $row[1], 'maxp' => $row[2]];
		}
	}
}

$maxpers = array_sum(array_column($sale, 1));

$testo .= '<div class=" uk-text-center" style="margin-bottom:10px"><strong>' . traducis('', $IDserv, 1, $lang, 0, 1) . '</strong></div>';

switch ($tipolim) {
case 1:

	$dataextra = dataita($time_servizio);

	$testo .= '<div class="uk-heading-divider" style="margin-top:10px;" ><strong>' . traduci('Data e Ora del Servizio', $lang, 1) . '</strong></div>
			<div class="list-blocknew">
			<div class="item-contentnew">
			  <div class="item-innernew">
				<div class="item-title c999 fw600">' . traduci('Data', $lang, 1) . '</div>
				<div class="item-after fw600 c333">' . $dataextra . '</div>
			  </div>
			</div>';

	if ($modi == 0) {
		$tempo = '--.--';
	} else {
		$tempo = date('H:i', $time_servizio);
	}

	$testo .= '<div class="item-contentnew">
			  <div class="item-innernew">
				<div class="item-title c999 fw600">' . traduci('Ora', $lang, 1) . '</div>
				<div class="item-after fw600 c333">' . $tempo . '</div>
			  </div>
			</div>
		</div>';

	$testo .= '<div style="padding:2px 10px">' . "<span>" . traduci("Per modificare l'orario si prega di contattare  il personale addetto", $lang, 1) . ".<br/>" . traduci("Grazie", $lang, 1) . "</span></div>";

	break;

case 2:
case 3:
	$testo_modifica = '';

	if ($tempo_navigation != 0) {
		$timeextra = $tempo_navigation;
		$dataextra = date('Y-m-d', $tempo_navigation);
		$modi = 0;
	} else {
		$timeextra = $time_servizio;
		$dataextra = date('Y-m-d', $time_servizio);
	}

	list($yy, $mm, $dd) = explode("-", $dataextra);
	$time0 = mktime(0, 0, 0, $mm, $dd, $yy);

	$testo .= '<input type="hidden" id="giorno_cambiato" value="' . $time0 . '">';

	if ($IDtipo == 1) {
		$query2 = "SELECT p.ID FROM prenextra as p,prenextra2 as p2 WHERE FROM_UNIXTIME(p.time,'%Y-%m-%d')=FROM_UNIXTIME('$time0','%Y-%m-%d') AND p.IDstruttura='$IDstruttura' AND  p.IDpren='$IDpren' AND p.sottotip='$IDsotto' AND p.modi>'0' AND p.ID!='$IDprenextra' AND p.ID=p2.IDprenextra AND p2.IDinfop IN($groupid)";
		$result2 = mysqli_query($link2, $query2);
		if (mysqli_num_rows($result2) > 0) {
			$servizio_presente = 1;
		}
	}

	$qadd = "";
	$query = "SELECT GROUP_CONCAT(IDorarios SEPARATOR ',') FROM assocorario WHERE IDserv='$IDserv' GROUP BY IDserv";
	$result = mysqli_query($link2, $query);
	if (mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_row($result);
		$grora = $row['0'];
		$qadd = " AND ID IN ($grora)";
	}

	$timef0 = $time0 + 86400;
	$min = 15;
	$step = $min * 60;
	$step2 = 3600;

	$query = "SELECT orarioi,orariof FROM orarisotto WHERE IDsotto='$IDsotto' $qadd";
	$result = mysqli_query($link2, $query);
	if (mysqli_num_rows($result) > 0) {
		while ($row = mysqli_fetch_row($result)) {
			$orai = $row['0'] + $time0;
			$oraf = $row['1'] + $time0;
			if ($oraf <= $orai) {
				$oraf += 86400;
			}
			for ($i = $orai; $i < $oraf; $i += $step) {
				$orari[$i] = $i;
			}

			for ($i = $orai; $i < $oraf; $i += $step2) {
				$orari2[$i] = $i;
			}
		}

	}

	$testo .= '<div class="uk-heading-divider" style="margin-top:10px;" ><strong>' . traduci('Scegli Data e Orario', $lang, 1) . '</strong></div>


		<div uk-grid>


			<div class="uk-width-auto">
				' . traduci('Data', $lang, 1) . '
			</div>

			<div  class="uk-width-expand uk-text-right">
        <select id="datamod" onChange="reloaddata(' . $IDprenextra . ',this.value);">';
	for ($i = 0; $i <= $notti; $i++) {
		$tt = $check0 + (86400 * $i);
		$cla = '';
		if ($tt == $time0) {
			$cla = 'selected';
		}
		$testo .= '<option value="' . $tt . '" ' . $cla . '>' . dataita($tt) . '</option>';
	}

	$testo .= '</select>
	</div>
	</div>';

	if ($servizio_presente == 0) {

		if ($modi == 0) {
			$testo_tempo = '--.--';
			$testo_modifica = '
				<div style="padding:2px 10px">
						<span>' . traduci('Per cambiare la data di svolgimento del servizio, si prega di selezionare un orario', $lang, 1) . '.<br/>' . traduci('Grazie', $lang, 1) . '</span>
				</div>';

		} else {
			$testo_tempo = date('H:i', $time_servizio);
		}

		$oraridisponibili = orari5($time0, $qta, $IDserv, $IDstruttura, $IDprenextra, 1);
		$orari = array_unique($orari);

		$testo .= '

		<div uk-grid style="margin-top:20px;">
			<div class="uk-width-auto">
				' . traduci('Orario', $lang, 1) . '
			</div>

			<div class="uk-width-expand uk-text-right">

		<select id="datamod" onchange="modprenextra(' . $IDprenextra . ',this.value,1,9,30)">';
		if ($modi == 0) {
			$testo .= '<option selected>--.--</option>';
		}

		foreach ($orari as $times => $lista_time) {
			$sel = '';
			$disab = '';
			$txtdisab = '';
			$value = '';

			if (isset($oraridisponibili[$times][0])) {
				if ($oraridisponibili[$times][0] > $qta) {
					$value = $oraridisponibili[$times][1] . '_' . $times . '_0';
				}
			}

			if ($modi != 0) {
				if ($times == $time_servizio) {
					$sel = 'selected';
				}
			}

			if ($value == '') {
				$disab = ' disabled';
				$txtdisab = traduci('Non Disponibile', $lang, 1);
			}

			$testo .= '<option value="' . $value . '"  ' . $sel . $disab . '>' . date('H:i', $times) . $txtdisab;

		}

		$testo .= '</select>
			</div>
		</div> ';

	} else {

		$testo_modifica = '<div style="margin:5px; font-size:15px;  color:#a43c32;font-weight:100;">
		' . traduci('Questo servizio non può essere ricevuto due volte lo stesso giorno', $lang, 1) . '.</div>';
	}

	$testo .= '</div>';

	if ($testo_modifica != '') {
		$testo .= $testo_modifica;
	}

	if ($IDtipo != 1) {

		foreach ($orari2 as $times => $lista_time) {
			if (isset($oraridisponibili[$times])) {
				if ($maxpers == 0) {
					$qta = $oraridisponibili[$times][0];
				} else {
					$qta = $maxpers - $oraridisponibili[$times][0];
				}
				$graph[$times] = $qta;
			}

		}

		$testo .= '<div class="uk-heading-divider" style="margin-top:10px;" ><strong>' . traduci('Stato Servizio', $lang, 1) . '</strong></div>
					<div class="graph" style="background:#fff; border-top:solid 1px #e1e1e1; border-bottom:solid 1px #e1e1e1;">';
		$wid = floor(100 / (count($graph) + 1));
		foreach ($graph as $times => $qta) {

			$ora = date('H', $times);
			if ($maxpers == 0) {
				$hh = ((1 * $qta));
			} else {
				$hh = ((80 * $qta) / $maxpers) + 5;
			}

			$testo .= '<div style="width:' . $wid . '%;">
						<div>' . $ora . '<br/><i>' . $qta . '</i></div><span style="height:' . $hh . '%"></span>
					</div>';
		}
		$testo .= '</div>';

	} else {

		$query4 = "SELECT GROUP_CONCAT(s.servizio SEPARATOR '<br/>'),dp.portata FROM dispgiorno as dp,servizi as s,sottotipologie as st WHERE dp.IDsottotip='$IDsotto' AND  FROM_UNIXTIME(dp.data,'%Y-%m-%d')='$dataextra' AND dp.IDpiatto=s.ID AND s.IDsottotip=st.ID  GROUP BY dp.portata ORDER BY dp.portata";
		$result4 = mysqli_query($link2, $query4);
		if (mysqli_num_rows($result4) > 0) {
			$txtmenu = '';
			$contmenu = 0;
			while ($row4 = mysqli_fetch_row($result4)) {

				$txtmenu .= ' <div class="row rowlistt no-gutter" style="border-bottom:1px solid #e1e1e1;padding:8px 0px">
									<div class="col-100">
										<div style="color:#969696; font-size:12px;">' . traduci('Portata', $lang, 1) . ' ' . $row4['1'] . '</div>
									</div>
									<div class="col-100"><div style="margin-top:5px; font-size:16px;color:#333;margin-left:5px">' . $row4['0'] . '</div></div>
								</div> ';
			}

			if (strlen($txtmenu) > 0) {
				$testo .= ' <div style="padding:2px 10px;" onclick="navigation2(14,' . "'" . $IDsotto . "," . $timeextra . "'" . ',3,0)">
								<div class="content-block-title titlecontentnew titlecontentnew2" style="line-height:1;margin:5px 0px;padding-bottom:0px"><strong style="font-size:15px;color:#000;">' . traduci('Menu Giornaliero', $lang, 1) . '</strong>  </div>
						 		<div  style="margin-top:5px">' . $txtmenu . '</div>
							</div>';
			}
		}
	}

	break;
}

if (!isset($inc)) {
	echo $testo;
}

?>

