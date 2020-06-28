<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';

$IDutente = $_SESSION['ID'];
$IDstruttura = $_SESSION['IDstruttura'];
$data = (isset($_POST['data']) ? $_POST['data'] : 0);

if ($data == 0) {
	$time = (isset($_SESSION['tempo_pul']) ? $_SESSION['tempo_pul'] : time_struttura());
	$dataoggi = date('Y-m-d', $time);
} else {
	$time = strtotime(convertiData($data));
	$dataoggi = date('Y-m-d', $time);
}

$time = time0($time);
$_SESSION['tempo_pul'] = $time;
$_SESSION['pulizia_selezionata'] = 1;

$statocol = array('0bcd5e', 'f63535', 'f5d914');

$statoarr = array('Pronto', 'Occupato', 'Da Preparare');

$arr_colori_checkin = array('', 'ab141b', '03980e');
$array_servizi = [];

$array_note = [];
$arr_IDpren = [];

$timeieri = $time - 86400;
$dataieri = date('Y-m-d', $timeieri);
$timef = $time + (86400 * 7);
$dataf = date('Y-m-d', $timef);
$dataf2 = date('Y-m-d', $timef + 86400);
$time_ieri0 = strtotime($dataieri);
$timefine0 = strtotime($dataf);
$timefine2 = strtotime($dataf2);

$IDprenmain = 0;
$salamain = 0;
$ttmain = 0;

$bodytxt = '';
$side = '';

$header = '';

$configurazioni_calendario = visualizza_configurazioni($IDstruttura, 11, $IDutente);
$altezza_riga = (($configurazioni_calendario['height_row_cal_app']['valore'] ?? 0) != 0 ? $configurazioni_calendario['height_row_cal_app']['valore'] : 50);
$grandezza_colonna = (($configurazioni_calendario['width_colonne_cal_app']['valore'] ?? 0) != 0 ? $configurazioni_calendario['width_colonne_cal_app']['valore'] : 54);

$dopo_checkin = 0;
$prima_checkin = 0;

$cont_serv_tot = 0;
$cont_note_tot = 0;

$query = "SELECT FROM_UNIXTIME(pr.time,'%Y-%m-%d'),pr.IDpren,p.stato,pr.sala,p.gg,CONCAT(s.cognome,' ',s.nome),i.nome FROM prenextra as pr JOIN prenotazioni as p ON p.IDv=pr.IDpren JOIN infopren as i ON pr.IDpren=i.IDpren LEFT JOIN schedine as s ON s.ID=i.IDcliente WHERE pr.time>='$time_ieri0' AND pr.time<'$timefine2'  AND p.IDstruttura='$IDstruttura' AND pr.IDtipo='8' AND p.stato>='0' AND pr.sala!='0'  AND pr.modi>='0' AND p.gg>'0' GROUP BY pr.time,pr.IDpren ORDER BY p.IDv,pr.time ";
$result = mysqli_query($link2, $query);
if (mysqli_num_rows($result) > 0) {
	while ($row = mysqli_fetch_row($result)) {

		$tt = strtotime($row['0']);

		$IDpren = $row['1'];
		$sala = $row['3'];

		if (!in_array($IDpren, $arr_IDpren)) {
			$arr_IDpren[] = $IDpren;
		}

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
}

$info_struttura = genera_sotto_strutture_lista($IDstruttura)[0];
$checkin_struttura = $info_struttura['check_in'];
//echo $checkin_struttura;
/*
$query = "SELECT checkin FROM strutture WHERE ID='$IDstruttura'";
$result = mysqli_query($link2, $query);
if (mysqli_num_rows($result) > 0) {
$row = mysqli_fetch_row($result);
$checkin_str = $row['0'];
}*/

$implode_prenotazioni = '0';
if (!empty($arr_IDpren)) {
	$implode_prenotazioni = implode(',', $arr_IDpren);
}

$query = "SELECT p.IDv,GROUP_CONCAT(np.nota SEPARATOR ',') FROM prenotazioni as p
LEFT JOIN note_interne as np ON np.IDobj=p.IDv AND np.tipoobj='0' AND np.tiponota='3'
WHERE p.IDv IN($implode_prenotazioni) GROUP BY p.IDv ";
$result = mysqli_query($link2, $query);
while ($row = mysqli_fetch_row($result)) {
	$IDprenotazione = $row['0'];
	if ($row['1']) {
		$array_note[$IDprenotazione][] = $row['1'];
	}

}

$prenotazioni = get_prenotazioni(['0' => ['IDprenotazione' => $arr_IDpren]])['dati'];

//$IDpren_group = implode(',', $arr_IDpren_control);

$query = "SELECT p.extra,FROM_UNIXTIME(p.time,'%Y-%m-%d'),p.IDpren FROM prenextra as p
JOIN prenextra2 as p2 ON p2.IDprenextra=p.ID AND p2.pacchetto='0' AND p2.qta>'0'
WHERE p.IDstruttura='$IDstruttura' AND p.IDpren IN($implode_prenotazioni) AND p.IDtipo='5'  GROUP BY p.extra,p.time ";
$result = mysqli_query($link2, $query);
while ($row = mysqli_fetch_row($result)) {
	$time0_in = strtotime($row['1']);
	$IDv = $row['2'];
	$array_servizi[$IDv][$time0_in] = $row['0'];
}

$lista_categorie = [];
$query = "SELECT p.ID,pc.IDcat,c.nome,c.colore FROM personale as p
JOIN personale_categorie as pc ON pc.IDpers=p.ID
JOIN categorie as c ON c.ID=pc.IDcat
WHERE p.IDstr='$IDstruttura' AND p.IDuser='$IDutente' ";
$result = mysqli_query($link2, $query);
while ($row = mysqli_fetch_row($result)) {
	$lista_categorie[] = $row['1'];
}

$alloggi = get_alloggi($IDstruttura);

if (!empty($alloggi)) {
	foreach ($alloggi as $dati) {
		if ($dati['attivo'] != 1) {continue;}
		$IDalloggio = $dati['ID'];
		$IDpiano = $dati['IDpiano'];
		$IDcategoria = $dati['IDcategoria'];
		$statoapp = $dati['stato'];
		$nomeapp = $dati['alloggio'];

		if (!empty($lista_categorie)) {
			if (!in_array($IDcategoria, $lista_categorie)) {continue;}
		}

		$valorebtn = '';

		//$cont_checkin_dopo = 0;
		//$cont_checkin_prima = 0;
		//$cont_serv = 0;
		//$cont_note = 0;

		foreach ($statoarr as $key => $dato) {
			if ($key == 1) {continue;}
			if ($key == $statoapp) {continue;}
			$valorebtn .= '<li  class="stato_pulizia" data-tipo="' . $key . '" style="color:#' . $statocol[$key] . '">' . $dato . '</li>';
		}

		$side .= '<div class="appnew statocamere statocam' . $statoapp . ' piano' . $IDpiano . '  categoria' . $IDcategoria . '" onclick="pulsanti_pulizie(this)" data-appartamento="' . $IDalloggio . '"
		style="background:#' . $statocol[$statoapp] . '" id="' . $IDalloggio . '">

					<div class="nome">' . $nomeapp . '</div>

					<input type="hidden" value="' . base64_encode($valorebtn) . '" id="pulsanti' . $IDalloggio . '">
				</div>';

		$rigatxt = '';
		for ($tt = $timeieri; $tt <= $timef; $tt += 86400) {

			$lista_prenotazioni = '';
			if (isset($arrprenot[$IDalloggio][$tt])) {
				foreach ($arrprenot[$IDalloggio][$tt] as $IDpren => $dato) {
					$checkin = $prenotazioni[$IDpren]['orario_checkin_prenotazione'];
					$serv = '';
					if (isset($array_servizi[$IDpren])) {
						$time_serv = array_keys($array_servizi[$IDpren]);
						foreach ($time_serv as $key => $val) {

							if (($val >= $timeieri) && ($val < $timef)) {
								$tf = ($val - $tt) / 86400 + 1;

								$left = $tf * $grandezza_colonna;
								$serv .= '<div class="notifiche_pul" alt="' . $IDpren . '" style="background:#cb00ce;top:0px;left:' . $left . 'px;"> S </div>';
								//$cont_serv++;
							}
						}
					}
					$notapres = '';
					if (isset($array_note[$IDpren])) {
						$notapres = '<div class="notifiche_pul" style="background:#2abac9;line-height:12px;top:0px;left:35px;">N</div>';
						//$cont_note++;
					}

					/*
						$query3 = "SELECT FROM_UNIXTIME(time,'%Y-%m-%d') FROM prenextra WHERE IDpren='$IDpren' AND tipolim='4' AND sala='$IDapp' AND modi >='0' ORDER BY time LIMIT 1";
						$result3 = mysqli_query($link2, $query3);
						if (mysqli_num_rows($result3) > 0) {
							$row3 = mysqli_fetch_row($result3);

							$new_check = strtotime($row3['0']);
							if ($new_check < $tt) {
								$checkin_txt = 'Perm';
								$style_check = '';
							}
					*/
/*
$style_check = '';
if ($tt != $checkin) {
$checkin_txt = 'Perm';
} else {
 */$style_check = '';
					$time_arrivo = time0($checkin) + $checkin_struttura;
					if ($time_arrivo != $checkin) {
						//posticipato 1
						//Anticipato 2
						$tipo_check = ($checkin > $time_arrivo ? 1 : 2);

						$style_check = 'background:#' . ($tipo_check == 1 ? 'ab141b' : '03980e') . ';color:#fff';
					}

					$lista_prenotazioni .= '
						<div style="position:relative" >' . $serv . $notapres . '
							<div  class="info_pul"  style="width:' . ($dato * ($grandezza_colonna) - 7) . 'px;height:' . ($altezza_riga - 5) . 'px;"  onclick="apri_pren_pul(' . $IDpren . ');">

							<div style="position:relative;height:100%;">
									<div style="position:absolute;left:0;top:0;line-height:13px;">
											 <div style="font-size:12px;' . $style_check . ';padding:0 5px;margin-top:2px">' . $prenotazioni[$IDpren]['orario_checkin'] . '</div>
											 <div style="white-space:nowrap;font-size:10px;font-weight:600;margin-top:3px"> ' . $prenotazioni[$IDpren]['persone'] . ' <i class="fas fa-user"></i></div>
									 </div>
									<div style="position:absolute;right:3px;bottom:0px;font-size:12px;font-weight:600">  ' . $prenotazioni[$IDpren]['orario_checkout'] . '</div>
							</div>

							</div>
						</div>';
				}
			}

			$rigatxt .= '<div class="cont_body  ' . ($tt == $time ? 'oggi' : '') . '"  > ' . $lista_prenotazioni . ' </div>';
		}

/*
$classe_filtro = '';

if ($cont_checkin_dopo > 0) {
$dopo_checkin++;
$classe_filtro .= ' check_dopo';
}
if ($cont_checkin_prima > 0) {
$prima_checkin++;
$classe_filtro .= 'check_prima';
}

if ($cont_serv > 0) {
$cont_serv_tot++;
$classe_filtro .= ' pulizia_serv';
}

if ($cont_note > 0) {
$cont_note_tot++;
$classe_filtro .= ' note_pulizia';
}
 */

		$bodytxt .= '<div class="riga_txt statocamere statocam' . $statoapp . ' piano' . $IDpiano . '  categoria' . $IDcategoria . '  "   alt="' . $IDalloggio . '" id="riga' . $IDalloggio . '" style="overflow:hidden">' . $rigatxt . ' </div>';
	}
}

$header = '';

for ($tt = $timeieri; $tt <= $timef; $tt += 86400) {
	$gg = date('w', $tt);
	$header .= '<div class="data_header ' . ($tt == $time ? 'oggi' : '') . '" > <div>
	<strong style="font-size:17px;">' . date('d', $tt) . '</strong>   <br> ' . $giorniita2[$gg] . '</div></div>';
}

$testo = '

			<div id="puliziediv" style="-webkit-overflow-scrolling:touch;overscroll-behavior: none;position:relative" class="container_no_mobile uk-container uk-container-small uk-position-relative">
				<div   class="div_grid_angolo"  style="height:40px;    left: 0px;"> <div class="testo_mese">  </div> 	</div>

				<div   class="header">
					<div  style="width: max-content;display:flex;height:100%"> ' . $header . ' </div>
				</div>

				<div  class="side">
					' . $side . '
				</div>

				<div  class="body" >
					' . $bodytxt . '
				</div>
		</div>  ';

echo $testo;

?>

<style>

#puliziediv {  width: 100%;  height: 86vh;  display: grid; grid-template-columns: 95px auto; grid-template-rows: 40px auto;  grid-template-areas: ". header"  "side body";}

#puliziediv  .cont_body { width:<?php echo $grandezza_colonna; ?>px;height: <?php echo $altezza_riga; ?>px;}

#puliziediv  .data_header{  width: <?php echo $grandezza_colonna; ?>px;}

#puliziediv  .appnew{height: <?php echo $altezza_riga; ?>px;}



#puliziediv .header{ grid-area: header; overflow: hidden;border-right: 1px solid #e1e1e1;background:#f9f8f9;}
#puliziediv .header .data_header{text-align:center; border-bottom:1px solid #e1e1e1;}
#puliziediv .header .data_header div{       margin-top: 5px; text-align: center; font-size: 11px; color: #000; line-height: 15px; }
#puliziediv .header .data_header.oggi{ }
#puliziediv .header .data_header.oggi div{color: #30A947;}


#puliziediv .body { grid-area: body;  overflow: auto;}
#puliziediv .body .riga_txt{display:flex;margin:0;padding: 0;   width: max-content;}
#puliziediv .body .riga_txt .cont_body {border-right:1px solid #e1e1e1;border-bottom:1px solid #e1e1e1;position:relative;font-size: 12px;background: #fff}





#puliziediv .side {  grid-area: side;overflow: hidden;border-top:1px solid #e1e1e1;border-bottom:1px solid #e1e1e1;}
#puliziediv .side .appnew{color:#fff; padding: 0px 5px;   border-bottom: 1px solid #e1e1e1;border-right: 1px solid #e1e1e1; position: relative;}
#puliziediv .side .appnew .nome{padding-top:10px;text-overflow: ellipsis;white-space:nowrap;overflow: hidden;text-transform:uppercase;font-size:11px;text-align: center;   font-weight: 600;}





</style>
