<?php
//header('Access-Control-Allow-Origin: *');
include '../../config/connecti.php';
include '../../config/funzioni.php';

$IDutente = $_SESSION['ID'];
$IDstruttura = $_SESSION['IDstruttura'];

$arr = implode(',', $_GET['arr_dati']);
list($txtsend2) = explode(',', $arr);

$txtsend = str_replace('/', ',', $txtsend2);

$multi = 2;
$txt = '';

$query = "SELECT p.tipolim,p.ID,p.extra,p.time,p.modi,p.sala,p.IDtipo,p.sottotip,GROUP_CONCAT(p2.IDinfop) FROM prenextra as p,prenextra2 as p2 WHERE p2.IDp2 IN($txtsend) AND p2.IDprenextra=p.ID GROUP BY p.IDstruttura ";
$result = mysqli_query($link2, $query);
$row = mysqli_fetch_row($result);
$tipolim = $row['0'];
$IDprenextra = $row['1'];
$extra = $row['2'];
$IDinfop = $row['3'];

//if ($multi > 1) {

switch ($tipolim) {
case 3:
case 1:

	$query = "SELECT p.ID,p.extra,p.time,p.modi,p.tipolim,p.sala,p.IDtipo,p.sottotip,GROUP_CONCAT(p2.IDinfop SEPARATOR ','),GROUP_CONCAT(p2.IDp2 SEPARATOR ','),0,COUNT(p2.IDp2),SUM(p2.prezzo),p2.IDp2,p2.datacar FROM prenextra as p,prenextra2 as p2 WHERE p2.IDp2 IN($txtsend) AND p2.IDprenextra=p.ID GROUP BY p.ID";
	$result = mysqli_query($link2, $query);
	while ($row = mysqli_fetch_row($result)) {
		$IDprenextra = $row['0'];
		$time = $row['2'];
		$modi = $row['3'];
		$IDsala = $row['5'];
		$IDsottotip = $row['7'];
		$IDgroup = $row['9'];
		$tipolim = $row['4'];

		$pagato = $row['10'];
		$numnon = $row['11'];
		$prezzo = round($row['12'], 2);
		$keyin = $row['13'];
		$datacar = $row['14'];

		$testoinfo = '';
		$servizio = getnomeserv($row['1'], $tipolim, $IDprenextra);

		$nomesala = '';
		$query3 = "SELECT nome FROM sale WHERE ID='$IDsala' LIMIT 1";
		$result3 = mysqli_query($link2, $query3);
		if (mysqli_num_rows($result3) > 0) {
			$row3 = mysqli_fetch_row($result3);
			$nomesala = $row3['0'];
		}

		$butt6 = '';
		$modprezzo = 1;

		if (($pagato != 0) && ($numnon != 0)) { //pagato in parte
		}
		if ($pagato == 0) { //non pagato
		}
		if (($pagato != 0) && ($numnon == 0)) {
			//pagato
			$modprezzo = 0;
		}

		$orario = '';
		if ($modi > 0) {
			$orario = dataita3($time) . ' ' . date('H:i', $time);
		}

		$testoinfo = $nomesala . ' ' . $orario;

		$txt .= '

				<div uk-grid  class="uk_grid_div"  data-id="' . $IDgroup . '" data-num="1" data-delete="1" data-prezzo="' . $modprezzo . '"  onclick="modservice(this)"  data-agg="2">
				<div class="uk-width-auto lista_grid_numero">1 </div>
				    <div class="uk-width-expand lista_grid_nome uk-text-truncate">' . $servizio . '<br/>
						<span class="uk-text-muted uk-text-small" >' . $testoinfo . '</span></div>
			        <div class="uk-width-auto uk-text-right  lista_grid_right">' . $prezzo . ' €  <span uk-icon="chevron-right" ></span></div>
				</div> ';

		/*
						<a class="item-link" data-id="' . $IDgroup . '" data-num="1" data-delete="1" data-prezzo="' . $modprezzo . '"  onclick="modservice(this)">
					 <div class="item-contentnew mt10">

					 <div class="item-innernew item-innernew2">
						 <div class="item-media mediaright pr15"><div class="roundfunc">1</div>
						  </div>
					  <div class="item-title">' . $servizio . '<br><span>' . $testoinfo . '</span></div>
					  <div class="item-after fs14 fw600">
						' . $prezzo . ' €
					  </div>

					</div>
					</div>
					</a>

				*/
	}

	break;
case 2:
case 4:

	$query = "SELECT p.ID,p.extra,p.time,p.modi,p.tipolim,p.sala,p.IDtipo,p.sottotip,GROUP_CONCAT(p2.IDinfop SEPARATOR ','),GROUP_CONCAT(p2.IDp2 SEPARATOR ','),0,COUNT(p2.IDp2),SUM(p2.prezzo),p2.IDp2,p2.datacar FROM prenextra as p,prenextra2 as p2 WHERE p2.IDp2 IN($txtsend) AND p2.IDprenextra=p.ID GROUP BY p.ID";
	$result = mysqli_query($link2, $query);
	while ($row = mysqli_fetch_row($result)) {
		$IDprenextra = $row['0'];
		$time = $row['2'];
		$modi = $row['3'];
		$IDsala = $row['5'];
		$IDsottotip = $row['7'];
		$IDgroup = $row['9'];
		$tipolim = $row['4'];

		$pagato = $row['10'];
		$numnon = $row['11'];
		$prezzo = round($row['12'], 2);
		$keyin = $row['13'];
		$datacar = $row['14'];

		$testoinfo = '';
		$servizio = getnomeserv($row['1'], $tipolim, $IDprenextra);

		$nomesala = '';
		$query3 = "SELECT nome FROM sale WHERE ID='$IDsala' LIMIT 1";
		$result3 = mysqli_query($link2, $query3);
		if (mysqli_num_rows($result3) > 0) {
			$row3 = mysqli_fetch_row($result3);
			$nomesala = $row3['0'];
		}

		$butt6 = '';
		$modprezzo = 1;

		if (($pagato != 0) && ($numnon != 0)) { //pagato in parte
		}
		if ($pagato == 0) { //non pagato
		}
		if (($pagato != 0) && ($numnon == 0)) {
			//pagato
			$modprezzo = 0;
		}

		$orario = '';
		if ($modi > 0) {
			$orario = dataita3($time) . ' ' . date('H:i', $time);
		}

		$testoinfo = $nomesala . ' ' . $orario;

		$txt .= '
				<div uk-grid  class="uk_grid_div"  data-id="' . $IDgroup . '" data-num="1" data-delete="1" data-prezzo="' . $modprezzo . '"  onclick="modservice(this)"  data-agg="2">
					<div class="uk-width-auto lista_grid_numero">1 </div>
				    <div class="uk-width-expand lista_grid_nome uk-text-truncate">' . $servizio . '<br/>
						<span class="uk-text-muted uk-text-small" >' . $testoinfo . '</span></div>
			        <div class="uk-width-auto uk-text-right  lista_grid_right">' . $prezzo . ' €  <span uk-icon="chevron-right" ></span></div>
				</div>  ';

		/*<a class="item-link" data-id="' . $IDgroup . '" data-num="1" data-delete="1" data-prezzo="' . $modprezzo . '"  onclick="modservice(this)">
					 <div class="item-contentnew mt10">

					 <div class="item-innernew item-innernew2">
						 <div class="item-media mediaright pr15"><div class="roundfunc">1</div>
						  </div>
					  <div class="item-title">' . $servizio . '<br><span>' . $testoinfo . '</span></div>
					  <div class="item-after fs14 fw600">
						' . $prezzo . ' €
					  </div>

					</div>
					</div>
					</a>*/
	}

	break;
case 5:

	$query = "SELECT p.ID,p.extra,p.time,p.modi,p.tipolim,p.sala,p.IDtipo,p.sottotip,GROUP_CONCAT(p2.IDinfop SEPARATOR ','),GROUP_CONCAT(p2.IDp2 SEPARATOR ','),0,COUNT(p2.IDp2),SUM(p2.prezzo),p2.IDp2,p2.datacar FROM prenextra as p,prenextra2 as p2 WHERE p2.IDp2 IN($txtsend) AND p2.IDprenextra=p.ID GROUP BY p.ID";
	$result = mysqli_query($link2, $query);
	while ($row = mysqli_fetch_row($result)) {
		$IDprenextra = $row['0'];
		$time = $row['2'];
		$modi = $row['3'];
		$IDsala = $row['5'];
		$IDsottotip = $row['7'];
		$IDgroup = $row['9'];
		$tipolim = $row['4'];

		$pagato = $row['10'];
		$numnon = $row['11'];
		$prezzo = round($row['12'], 2);
		$keyin = $row['13'];
		$datacar = $row['14'];

		$testoinfo = '';
		$servizio = getnomeserv($row['1'], $tipolim, $IDprenextra);

		$nomesala = '';
		$query3 = "SELECT nome FROM sale WHERE ID='$IDsala' LIMIT 1";
		$result3 = mysqli_query($link2, $query3);
		if (mysqli_num_rows($result3) > 0) {
			$row3 = mysqli_fetch_row($result3);
			$nomesala = $row3['0'];
		}

		$butt6 = '';
		$modprezzo = 1;

		if (($pagato != 0) && ($numnon != 0)) { //pagato in parte
		}
		if ($pagato == 0) { //non pagato
		}
		if (($pagato != 0) && ($numnon == 0)) {
			//pagato
			$modprezzo = 0;
		}

		$orario = '';
		if ($modi > 0) {
			$orario = dataita3($time) . ' ' . date('H:i', $time);
		}

		$testoinfo = $nomesala . ' ' . $orario;

		$txt .= '
				<div uk-grid  class="uk_grid_div"  data-id="' . $IDgroup . '" data-num="1" data-delete="1" data-prezzo="' . $modprezzo . '"  onclick="modservice(this)"  data-agg="2">
					<div class="uk-width-auto lista_grid_numero">1 </div>
				    <div class="uk-width-expand lista_grid_nome uk-text-truncate">' . $servizio . '<br/>
						<span class="uk-text-muted uk-text-small" >' . $testoinfo . '</span></div>
			        <div class="uk-width-auto uk-text-right  lista_grid_right">' . $prezzo . ' €  <span uk-icon="chevron-right" ></span></div>
				</div>   ';

		/*
				<a class="item-link" data-id="' . $IDgroup . '" data-num="1" data-delete="1" data-prezzo="' . $modprezzo . '"  onclick="modservice(this)">
					 <div class="item-contentnew mt10">

					 <div class="item-innernew item-innernew2">
						 <div class="item-media mediaright pr15"><div class="roundfunc">1</div>
						  </div>
					  <div class="item-title">' . $servizio . '<br><span>' . $testoinfo . '</span></div>
					  <div class="item-after fs14 fw600">
						' . $prezzo . ' €
					  </div>

					</div>
					</div>
					</a>
				*/
	}

	break;
case 6:
case 9:

	$query = "SELECT p.ID,p.extra,p.time,p.modi,p.tipolim,p.sala,p.IDtipo,p.sottotip,GROUP_CONCAT(p2.IDinfop SEPARATOR ','),GROUP_CONCAT(p2.IDp2 SEPARATOR ','),0,COUNT(p2.IDp2),SUM(p2.prezzo),p2.IDp2,p2.datacar FROM prenextra as p,prenextra2 as p2 WHERE p2.IDp2 IN($txtsend) AND p2.IDprenextra=p.ID GROUP BY p.ID";
	$result = mysqli_query($link2, $query);
	while ($row = mysqli_fetch_row($result)) {
		$IDprenextra = $row['0'];
		$time = $row['2'];
		$modi = $row['3'];
		$IDsala = $row['5'];
		$IDsottotip = $row['7'];
		$IDgroup = $row['9'];
		$tipolim = $row['4'];

		$pagato = $row['10'];
		$numnon = $row['11'];
		$prezzo = round($row['12'], 2);
		$keyin = $row['13'];
		$datacar = $row['14'];

		$testoinfo = '';
		$servizio = getnomeserv($row['1'], $tipolim, $IDprenextra);

		$nomesala = '';
		$query3 = "SELECT nome FROM sale WHERE ID='$IDsala' LIMIT 1";
		$result3 = mysqli_query($link2, $query3);
		if (mysqli_num_rows($result3) > 0) {
			$row3 = mysqli_fetch_row($result3);
			$nomesala = $row3['0'];
		}

		$butt6 = '';
		$modprezzo = 1;

		if (($pagato != 0) && ($numnon != 0)) { //pagato in parte
		}
		if ($pagato == 0) { //non pagato
		}
		if (($pagato != 0) && ($numnon == 0)) {
			//pagato
			$modprezzo = 0;
		}

		$orario = '';
		if ($modi > 0) {
			$orario = dataita3($time) . ' ' . date('H:i', $time);
		}

		$testoinfo = $nomesala . ' ' . $orario;

		$txt .= '
				<div uk-grid  class="uk_grid_div"  data-id="' . $IDgroup . '" data-num="1" data-delete="1" data-prezzo="' . $modprezzo . '"  onclick="modservice(this)"  data-agg="2">
					<div class="uk-width-auto lista_grid_numero">1 </div>
				    <div class="uk-width-expand lista_grid_nome uk-text-truncate">' . $servizio . '<br/>
						<span class="uk-text-muted uk-text-small" >' . $testoinfo . '</span></div>
			        <div class="uk-width-auto uk-text-right  lista_grid_right">' . $prezzo . ' €  <span uk-icon="chevron-right" ></span></div>
				</div>

			';

		/*
					<a class="item-link" data-id="' . $IDgroup . '" data-num="1" data-delete="1" data-prezzo="' . $modprezzo . '"  onclick="modservice(this)">
					 <div class="item-contentnew mt10">

					 <div class="item-innernew item-innernew2">
						 <div class="item-media mediaright pr15"><div class="roundfunc">' . $numnon . '</div>
						  </div>
					  <div class="item-title">' . $servizio . '<br><span>' . $testoinfo . '</span></div>
					  <div class="item-after fs14 fw600">
						' . $prezzo . ' €
					  </div>

					</div>
					</div>
					</a>
			*/
	}

	break;
}
/*} else {
switch ($tipolim) {
case 5:
$pacchetto = $extra . '/' . $IDprenextra;

$query = "SELECT p.ID,p.extra,p.time,p.modi,p.tipolim,p.sala,p.IDtipo,p.sottotip,GROUP_CONCAT(p2.IDinfop SEPARATOR ','),GROUP_CONCAT(p2.IDp2 SEPARATOR ','),p2.pagato,COUNT(p2.IDp2),SUM(p2.prezzo),p2.IDp2,p2.datacar FROM prenextra as p,prenextra2 as p2 WHERE p2.pacchetto='$pacchetto' AND p2.IDinfop IN($IDinfop) AND p2.IDprenextra=p.ID GROUP BY p.ID";
$result = mysqli_query($link2, $query);
while ($row = mysqli_fetch_row($result)) {
$IDprenextra = $row['0'];
$time = $row['2'];
$modi = $row['3'];
$IDsala = $row['5'];
$IDsottotip = $row['7'];
$IDgroup = $row['9'];
$tipolim = $row['4'];

$pagato = $row['10'];
$numnon = $row['11'];
$prezzo = round($row['12'], 2);
$keyin = $row['13'];
$datacar = $row['14'];

$testoinfo = '';
$servizio = getnomeserv($row['1'], $tipolim, $IDprenextra);

$nomesala = '';
$query3 = "SELECT nome FROM sale WHERE ID='$IDsala' LIMIT 1";
$result3 = mysqli_query($link2, $query3);
if (mysqli_num_rows($result3) > 0) {
$row3 = mysqli_fetch_row($result3);
$nomesala = $row3['0'];
}

$butt6 = '';
$modprezzo = 1;

if (($pagato != 0) && ($numnon != 0)) { //pagato in parte
}
if ($pagato == 0) { //non pagato
}
if (($pagato != 0) && ($numnon == 0)) {
//pagato
$modprezzo = 0;
}

$orario = '';
if ($modi > 0) {
$orario = dataita3($time) . ' ' . date('H:i', $time);
}

$testoinfo = $nomesala . ' ' . $orario;

$txt .= '<a class="item-link" data-id="' . $IDgroup . '" data-num="1" data-delete="0" data-prezzo="' . $modprezzo . '"  onclick="modservice(this)">
<div class="item-contentnew mt10">

<div class="item-innernew item-innernew2">
<div class="item-media mediaright pr15"><div class="roundfunc">' . $numnon . '</div>
</div>
<div class="item-title">' . $servizio . '<br><span>' . $testoinfo . '</span></div>
<div class="item-after fs14 fw600">
' . $prezzo . ' €
</div>
</div>
</div>
</a>';
}

break;
}

}*/

$testo = ' <input type="hidden" id="txtsend2" value="' . $txtsend2 . '">
	<div class="uk-text-lead	uk-text-capitalize	uk-text-bold	uk-text-small uk-margin-bottom	" style="color:#2641da;">Dettaglio Servizio</div>
 ' . $txt;

echo $testo;
