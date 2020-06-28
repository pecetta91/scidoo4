<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../config/connecti.php';
require_once __DIR__ . '/../../config/funzioni.php';

$IDutente = $_SESSION['ID'];
$IDstruttura = $_SESSION['IDstruttura'];

$dati = (isset($_POST['arr_dati']) ? $_POST['arr_dati'] : []);
$time = $dati['time'] ?? time_struttura();

$filtri_categorie = (isset($_SESSION['categorie_calendario']) ? $_SESSION['categorie_calendario'] : []);

$configurazioni_calendario = visualizza_configurazioni($IDstruttura, 11, $IDutente);

$altezza_riga = (($configurazioni_calendario['height_row_cal_app']['valore'] ?? 0) != 0 ? $configurazioni_calendario['height_row_cal_app']['valore'] : 50);
$grandezza_colonna = (($configurazioni_calendario['width_colonne_cal_app']['valore'] ?? 0) != 0 ? $configurazioni_calendario['width_colonne_cal_app']['valore'] : 54);
$nascondi_categoria = $configurazioni_calendario['nascondi_categoria']['valore'] ?? 0;

list($yy, $mm, $dd) = explode('-', date('Y-m-d'));
$timeoggi0 = mktime(0, 0, 0, $mm, 1, $yy);

$timeoggi = strtotime(date('Y-m-d'));

list($yy, $mm, $dd) = explode('-', date('Y-m-d', $time));
$timei = mktime(0, 0, 0, $mm, 1, $yy);

$timef = $timei + 86400 * 35;

$nota = [];
$query = "SELECT FROM_UNIXTIME(time,'%Y-%m-%d'),COUNT(*) FROM note WHERE time>='$timei' AND time<='$timef' AND IDstruttura='$IDstruttura' AND (titolo!='' OR descr!='') GROUP BY FROM_UNIXTIME(time,'%Y-%m-%d')";
$result = mysqli_query($link2, $query);
while ($row = mysqli_fetch_row($result)) {
	$time0_in = strtotime($row['0']);
	$nota[$time0_in] = $row['1'];
}

//esclusivi
$esc = [];
//$query = "SELECT  FROM_UNIXTIME(p.time,'%Y-%m-%d'),COUNT(*),p.IDpren,p.time FROM prenextra as p,servizi as s WHERE p.time>='$timei' AND p.time<='$timef' AND p.IDstruttura='$IDstruttura' AND p.extra=s.ID AND s.esclusivo='1' AND modi>='0' GROUP BY FROM_UNIXTIME(p.time,'%Y-%m-%d')";

$query2 = "SELECT FROM_UNIXTIME(p.time,'%Y-%m-%d') as data,COUNT(*),p.IDpren,p.time
FROM prenextra as p
WHERE p.time>=$timei AND p.time<=$timef AND p.IDstruttura=$IDstruttura AND p.esclusivo=1 AND modi>=0
GROUP BY data";
$result = mysqli_query($link2, $query);
while ($row = mysqli_fetch_row($result)) {
	$time0_in = strtotime($row['0']);
	$esc[$time0_in] = $row['1'];
}

$side = '';
$bodytxt = '';
$header = '';

$side = '<div class="appnew uk-text-middle " > <div class="nome  uk-text-truncate" style="    color: #BC3B3D;font-weight:600;">Note ed Esclusivi</div> </div>';
$rigatxt = '';
for ($tt = $timei; $tt < $timef; $tt += 86400) {

	$txt = '';
	$classn = '';

	if (isset($nota[$tt])) {
		$txt .= $nota[$tt] . ' <span>Note</span><hr style="margin: 2px;">';
		$classn = 'class="solonota note_esclusivi"';}
	if (isset($esc[$tt])) {
		$txt .= $esc[$tt] . ' <span>Esclus.</span>';
		$classn = 'class="noteesc note_esclusivi"';}

	$rigatxt .= '<div class="cont_body  ' . ($tt == $timeoggi ? 'oggi' : '') . '">' . ($txt != '' ? '<div ' . $classn . '  data-time="' . $tt . '" style="width:' . $grandezza_colonna . '">' . $txt . '</div>' : '') . '</div>';

}

$bodytxt .= '<div class="riga_txt " style="overflow:hidden">' . $rigatxt . ' </div>';

$datai2 = date('Y-m-d', $timei);
$dataf2 = date('Y-m-d', $timef);

$query = "SELECT  data,IDalloggio FROM chiusuraalloggi WHERE IDstr='$IDstruttura' AND data BETWEEN '$datai2' AND '$dataf2' ";
$result = mysqli_query($link2, $query);
$arrclose = [];
while ($row = mysqli_fetch_row($result)) {
	$time0_in = strtotime($row['0']);
	$arrclose[$row['1']][$time0_in] = 1;
}

$arrpren = [];
$arrID = [];
$arrprenot = [];
$arrprenstati = [];
$arrpren_nomi = [];
$arrprenot2 = [];
$classpren = [];

$classpren = array();
$stati_prenotazione = get_stati_prenotazioni();

foreach ($stati_prenotazione as $IDstato => $dato) {
	$classpren[$IDstato] = $dato['classe'];
}

$IDprenmain = 0;
$salamain = 0;
$ttmain = 0;

$query = "SELECT FROM_UNIXTIME(pr.time,'%Y-%m-%d'),pr.IDpren,p.stato,pr.sala,p.gg,CONCAT(s.cognome,' ',s.nome),i.nome
FROM prenextra as pr
JOIN prenotazioni as p ON p.IDv=pr.IDpren
JOIN infopren as i ON pr.IDpren=i.IDpren
LEFT JOIN schedine as s ON s.ID=i.IDcliente
WHERE pr.time>=$timei AND pr.time<$timef  AND p.IDstruttura=$IDstruttura AND pr.IDtipo=8 AND p.stato>=0 AND pr.sala!=0  AND pr.modi>=0 AND p.gg>0
GROUP BY pr.time,pr.IDpren ORDER BY p.IDv,pr.time ";

$result = mysqli_query($link2, $query);

while ($row = mysqli_fetch_row($result)) {

	$tt = strtotime($row['0']);

	$IDpren = $row['1'];
	$sala = $row['3'];

	if ($IDprenmain != $IDpren) {
		if ($row['4'] == 0.5) {
			$arrprenot[$sala][$tt][$IDpren] = 0.5;
		} else {
			$arrprenot[$sala][$tt][$IDpren] = 1;
		}

		if ($row['5'] == ' ') {
			$arrpren_nomi[$IDpren] = $row['6'];
		} else {
			$arrpren_nomi[$IDpren] = $row['5'];
		}

		$IDprenmain = $IDpren;
		$salamain = $sala;
		$ttmain = $tt;
		//informazioni
		$arrprenstati[$row['1']] = $row['2'];
		$arrID[] = $row['2'];
	} else {
		if ($sala == $salamain) {

			if (!isset($_SESSION['spezzapren'][$tt][$IDpren])) {
				$arrprenot[$salamain][$ttmain][$IDprenmain]++;
			} else {
				$arrprenot[$sala][$tt][$IDpren] = 1;
				$IDprenmain = $IDpren;
				$salamain = $sala;
				$ttmain = $tt;
			}

		} else {
			$arrprenot[$sala][$tt][$IDpren] = 1;
			$IDprenmain = $IDpren;
			$salamain = $sala;
			$ttmain = $tt;
		}
	}

}

$arrcheck = array();

$query = "SELECT A.ID,A.nome,A.attivo,A.temp,A.categoria,C.colore,A.stato,A.statod,C.nome
FROM appartamenti as A,categorie AS C
WHERE A.IDstruttura=$IDstruttura  AND A.categoria=C.ID ORDER BY A.ordine";
$result = mysqli_query($link2, $query);
while ($row = mysqli_fetch_row($result)) {
	//$row contine la query degli appartamenti
	$IDapp = $row['0'];
	$IDcategoria = $row['4'];
	$rigatxt = '';

	$nascondi_riga = '';
	if (!empty($filtri_categorie)) {
		if (!isset($filtri_categorie[$IDcategoria])) {
			$nascondi_riga = 'display:none;';
		}
	}

	if ($row['2'] != '2') {

		$side .= '<div class="appnew uk-text-middle  " id="' . $row['0'] . '" style="' . $nascondi_riga . '"> <div class="nome  uk-text-truncate">' . $row['1'] . '<br/>
			<span ' . ($nascondi_categoria == 1 ? 'style="display:none"' : '') . '>' . $row['8'] . '</span>
		</div> </div>';

		for ($tt = $timei; $tt < $timef; $tt += 86400) {

			$class = '';
			if (isset($arrclose[$IDapp][$tt])) {
				$class = 'close';
			}

			$class2 = '';
			if ($tt == $timeoggi) {
				$class2 = 'oggi';
			}

			$rigatxt .= '<div class="cont_body new ' . $class . $class2 . ' " data-alloggio="' . $IDapp . '"  data-time="' . $tt . '"  >';

			if (isset($arrprenot[$IDapp][$tt])) {
				foreach ($arrprenot[$IDapp][$tt] as $IDpren => $dato) {
					$rigatxt .= '
					  <div class="divcal prenotazione ' . $classpren[$arrprenstati[$IDpren]] . '" IDpren="' . $IDpren . '" data-start="' . $tt . '"   style="width:' . ($dato * ($grandezza_colonna)) . 'px;">

								<div>' . $arrpren_nomi[$IDpren] . '</div>

							</div>';
				}
			}

			$rigatxt .= '</div>';

		}

	} else {

		$side .= '<div class="appnew uk-text-middle " style="' . $nascondi_riga . '"  id="' . $row['0'] . '"> <div class="nome  uk-text-truncate" style="color:#fb7f05;font-weight:600;">' . $row['1'] . '</div> </div>';

		$arrpernot2 = [];
		$arrID2 = [];
		$query2 = "SELECT COUNT(*),FROM_UNIXTIME(time,'%Y-%m-%d'),IDv FROM prenotazioni WHERE gg='0' AND IDstruttura='$IDstruttura' AND  time>='$timei' AND time<'$timef' AND stato>='0' GROUP BY FROM_UNIXTIME(time,'%Y-%m-%d') ";
		$result2 = mysqli_query($link2, $query2);
		if (mysqli_num_rows($result2) > 0) {
			while ($row2 = mysqli_fetch_row($result2)) {

				$time0_in = strtotime($row2['1']);
				$arrprenot2[$time0_in] = $row2['0'];
				$arrID2[$time0_in] = $row2['2'];
			}
		}

		for ($tt = $timei; $tt < $timef; $tt += 86400) {

			$class = '';
			if ($tt == $timeoggi) {
				$class = 'oggi';
			}

			$rigatxt .= '<div class="cont_body  new giornaliero ' . $class . '"  >';

			if (isset($arrprenot2[$tt])) {
				$rigatxt .= '<div class="senzasdiv senza_soggiorno" data-time="' . $tt . '">' . $arrprenot2[$tt] . '</div>';
			} else {
				$rigatxt .= '<div class="new"   data-alloggio="' . $IDapp . '"  data-time="' . $tt . '"  ></div>';
			}

			$rigatxt .= '</div>';
		}

	}

	$bodytxt .= '<div class="riga_txt " style="overflow:hidden;' . $nascondi_riga . '" data-categoria="' . $IDcategoria . '">' . $rigatxt . ' </div>';

}

$arrpernot2 = [];
$arrID2 = [];
$query = "SELECT COUNT(*),FROM_UNIXTIME(time,'%Y-%m-%d'),IDv
FROM prenotazioni WHERE stato<=-1 AND IDstruttura=$IDstruttura AND  time>=$timei AND time<$timef
GROUP BY FROM_UNIXTIME(time,'%Y-%m-%d') ";
$result = mysqli_query($link2, $query);
while ($row = mysqli_fetch_row($result)) {
	$time0_in = strtotime($row['1']);
	$arrprenot2[$time0_in] = $row['0'];
	$arrID2[$time0_in] = $row['2'];
}

$side .= '<div class="appnew uk-text-middle" > <div class="nome  uk-text-truncate" style="color:#BC3B3D;font-weight:600;">Annullate</div> </div>';
$rigatxt = '';
for ($tt = $timei; $tt < $timef; $tt += 86400) {
	$clas = '';
	if ($tt == $timeoggi) {
		$clas = 'oggi';
	}

	$rigatxt .= '<div  class="cont_body ' . $clas . '">';

	if (isset($arrprenot2[$tt])) {
		$rigatxt .= '<div class="prenotazioni_annullate divcal" data-time="' . $tt . '">' . $arrprenot2[$tt] . '</div> ';
	}
	$rigatxt .= '</div>';
}
$bodytxt .= '<div class="riga_txt " style="overflow:hidden">' . $rigatxt . ' </div>';

for ($tt = $timei; $tt < $timef; $tt += 86400) {
	$clas = '';
	if ($tt == $timeoggi) {
		$clas = 'oggi';
	}

	$gg = date('w', $tt);
	$mes = date('n', $tt);
	$header .= '<div class="data_header ' . $clas . '" >
		<div  ><strong style="font-size:17px;">' . date('d', $tt) . '</strong>   <br> ' . $giorniita2[$gg] . '</div></div>';
}

$cambio_mesi = '';
$cambio_mesi = '<div id="cambio_mesi" style="display:none;">
<ul class="uk-list uk-list-divider uk-picker-bot" style="padding:5px 20px;"> ';
//$sel = '<span class="uk-align-right" uk-icon="check" style="color:#2641da"></span>';

if ($timeoggi0 != $timei) {
	$cambio_mesi .= '<li  onclick="navigation(5, [' . $timeoggi0 . '], 1, 0);chiudi_picker();"><strong> Vai ad Oggi</strong> </li>';
}

$timei = mktime(0, 0, 0, $mm, 1, $yy);
for ($i = -5; $i < 6; $i++) {
	$prossimi_anni = mktime(0, 0, 0, $mm + $i, 1, $yy);

	$aa = date('Y', $prossimi_anni);
	$numeromese = date('n', $prossimi_anni);
	$cambio_mesi .= ' <li  onclick="navigation(5, [' . $prossimi_anni . '], 1, 0);chiudi_picker();">' . $mesiita[$numeromese] . ' ' . $aa . ' </li>';
}

$cambio_mesi .= '</ul></div>';

$mese = '
<div   class="div_grid_angolo" onclick="carica_content_picker(' . "'cambio_mesi'" . ')">
	<div class="testo_mese"> ' . $mesiita[date('n', $timei)] . '  	<i class="fas fa-chevron-down"></i> 	<br/> <span style="font-size:10px;">' . date('Y', $timei) . '</span>
	</div>

</div>';

$testo = $cambio_mesi . '
			<input type="hidden" id="time_attuale" value="' . $timei . '" >
			' . $mese . '
		<div id="calendar_div" style="-webkit-overflow-scrolling:touch;overscroll-behavior: none;position:relative">

			<div   class="header">
				<div  style="width: max-content;display:flex;height:100%"> ' . $header . ' </div>
			</div>

			<div  class="side">
				' . $side . '
			</div>

			<div  class="body">
				' . $bodytxt . '
			</div>
	</div>';

echo $testo;
?>

<style>
#calendar_div  .cont_body { width:<?php echo $grandezza_colonna; ?>px;height: <?php echo $altezza_riga; ?>px;}
#calendar_div  .data_header{  width: <?php echo $grandezza_colonna; ?>px;}
#calendar_div  .appnew{height: <?php echo $altezza_riga; ?>px;}
</style>
