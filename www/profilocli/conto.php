<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../config/connecti.php';
require_once __DIR__ . '/../../config/funzioni.php';
$IDprenotazione = $_SESSION['IDstrpren'];
$IDstruttura = get_IDstruttura_from_IDpren($IDprenotazione);
$lang = $_SESSION['lang'] ?: 0;

$IDprenc = prenotcoll($IDprenotazione);

$dettaglio_prenotazione = get_prenotazioni(['0' => ['IDprenotazione' => $IDprenotazione]], [], [], $IDstruttura)['dati'][$IDprenotazione];

$time = $dettaglio_prenotazione['checkin'];
$checkout = $dettaglio_prenotazione['checkout'];
$stato = $dettaglio_prenotazione['stato'];
$gg = $dettaglio_prenotazione['notti'];
/*
$testo = genera_conto_ui_kit($IDprenotazione, 0, 'cambia_tab_prenotazione(' . $IDprenotazione . ',2)', $IDstruttura);

echo $testo;

return;
 */
$arrfor = array(" AND p2.datacar='0'", " AND p2.datacar='1' AND p.tipolim!='6'", "AND p2.datacar='1' AND p.tipolim='6' ");
$arrfor2 = array("", " AND p.modi='0'", "", "AND p.modi='1'");

$totgen = 0;
$ii = 0;

$testo = '<div style="padding:10px 5px">';
foreach ($arrfor as $qadd2) {
	$ii++;
	if ($ii == 1) {
		$scont = 'cini';
		$scontf = '3';

		$testo .= ' <div class="div_uk_divider_list">' . traduci('Prenotazione Iniziale', $lang, 1, 0) . '</div>
			<div id="infoprentab " class="p5 list-blocknew" >';

		$totale1 = 0;

	}

	if ($ii == 2) {
		$scontf = '2';
		$scont = 'cextra';
		/*
					$testo.='<div id="tab5" class="page-content tab ">
			          				<div class="content-block" style="padding:0px;">';
		*/
		$testo .= '<div class="div_uk_divider_list">' . traduci('Servizi Extra', $lang, 1, 0) . '</div>
			<div id="infoprentab  " class="p5 list-blocknew" >';
		$totale1 = 0;

	}
	if ($ii == 3) {
		$query = "SELECT p.ID FROM prenextra as p,prenextra2 as p2 WHERE p.IDtipo='10' AND p.IDpren IN ($IDprenc)AND p.ID=p2.IDprenextra AND p2.datacar='1'";
		$result = mysqli_query($link2, $query);
		if (mysqli_num_rows($result) > 0) {
			$testo .= '</div>';

			$testo .= '
			<div class="div_uk_divider_list">' . traduci('Prodotti Acquistati', $lang, 1, 0) . '</div>
			<div id="infoprentab " class="p5 list-blocknew" >';
		}
	}

	$query = "SELECT p.ID,p.time,p2.datacar,COUNT(DISTINCT(p2.IDprenextra)),GROUP_CONCAT(DISTINCT(p.ID) SEPARATOR ','),p.tipolim,p.extra,p.modi,SUM(p2.prezzo),GROUP_CONCAT(DISTINCT(p2.IDinfop) SEPARATOR ','),SUM(p2.qta),p.sala,p.IDtipo,p.sottotip FROM prenextra as p,prenextra2 as p2 WHERE p2.pacchetto<='0' AND p2.IDpren IN ($IDprenc) AND p.ID=p2.IDprenextra AND p2.paga>'0' $qadd2  GROUP BY p.extra ORDER BY p.time";
	$result = mysqli_query($link2, $query);

	$i = 0;
	while ($row = mysqli_fetch_row($result)) {

		$IDgroup = $row['4'];

		$tipolim = $row['5'];
		$query2 = "SELECT qta FROM prenextra2 WHERE IDprenextra IN ($IDgroup) AND pacchetto<='0' AND paga='1' GROUP BY IDprenextra";

		$result2 = mysqli_query($link2, $query2);
		if ((mysqli_num_rows($result2) > 0) || ($tipolim == '4')) {
			$modifica = "";
			$ID = $row['0'];
			$time = $row['1'];
			$datacar = $row['2'];
			$num2 = $row['3'];
			$num = $num2;
			$prezzo = $row['8'];
			$extra = $row['6'];
			$qta = $row['10'];
			$IDtipo = $row['12'];

			$servizio = traducis('', $extra, 1, $lang, 0, $qta);
			//getnomeserv($extra, $tipolim, $ID);

			if ($tipolim == '6') {
				$qtabutt = $qta;
			} else {
				$qtabutt = round($qta / $num);
			}

			if ($tipolim == '6') {
				$qtabutt = traduci('N.', $lang, 1) . ' ' . $qta . ' ' . traduci('Oggetti', $lang, 1, 0);
			} else {
				$nn = round($qta / $num);
				$persontxt = 'persone';
				if ($nn == 1) {$persontxt = 'persona';}
				$qtabutt = traduci('N.', $lang, 1) . ' ' . $nn . ' ' . txtpersone($nn, $lang, 0);
				//$qtabutt = 'N.' . $nn . ' ' . $persontxt;

			}

			$gruppo = $row['4'];
			$txtsend = '0';
			$pagato = 0;
			$numnon = 0;

			$num2 = '';
			$qta2 = '';

			if (($tipolim == '5') || ($tipolim == '7') || ($tipolim == '8')) {
				$groupinfoID = $row['9'];

				$qta2 = '<span class="fs12">' . traduci('Per', $lang, 1, 0) . ' ' . round($qta / $num) . ' ' . traduci('Persone', $lang, 1, 0) . '</span>';

				$arrg = explode(',', $IDgroup);
				//$prezzo=0;
				foreach ($arrg as $dato) {
					$pacchetto = $extra . '/' . $dato;
					$query2 = "SELECT SUM(prezzo) FROM prenextra2 WHERE pacchetto ='$pacchetto' AND IDpren IN ($IDprenc) AND IDinfop IN ($groupinfoID) AND IDinfop!='0' AND paga='1'";
					$result2 = mysqli_query($link2, $query2);
					if (mysqli_num_rows($result2) > 0) {
						$row2 = mysqli_fetch_row($result2);
						$prezzo += $row2['0'];
					}
					$query2 = "SELECT SUM(prezzo) FROM prenextra2 WHERE pacchetto ='$pacchetto' AND IDpren IN ($IDprenc) AND IDinfop ='0' AND paga='1'";
					$result2 = mysqli_query($link2, $query2);
					if (mysqli_num_rows($result2) > 0) {
						$row2 = mysqli_fetch_row($result2);
						$prezzo += $row2['0'];
					}
				}

			}

			$prezzotxt = "";
			$datatxt = date('d/m/Y', $time);
			$prezzotxt = round($prezzo, 2);

			if ($tipolim == '6') {
				$qta2 = ' <span class="fs12">N.' . $qta . '</span>';
			} else {
				if (($num == 1) && (($tipolim == '2') || ($tipolim == '1') || ($tipolim == '4'))) {
					//$num2=datafunc($time,$row['7'],$tipolim,'openmorph(2,'.$ID.','.$id.')',$ID);

					if (($row['12'] == 2) || ($row['12'] == 1)) {
						$sottot = $row['12'];
					} else {
						$sottot = $row['13'];
					}

					//$num2=datafunc2($time,$row['7'],$tipolim,'',$ID);
					//$num2='prova';
				}

			}
			$numtxt = $num . '+';

			$butt1 = ''; //prezzo
			$butt3 = ''; //sposta
			$butt4 = ''; //elimina
			$butt5 = ''; //orario

			$vis = ''; //visualizza

			$sost = 0;

			if ($num > 1) {
				$vis = $num . '+';
				if (($tipolim != '8') && ($tipolim != '7')) {
					$butt1 = $prezzotxt . '€';
				} else {
					$butt1 = $prezzotxt . '€';
				}

			} else {
				if (($tipolim == '5') || ($tipolim == '7') || ($tipolim == '8')) {
					$vis = $num . '+';
				} else {
					$vis = $num;
				}
				//prezzo
				if (($tipolim != '8') && ($tipolim != '7')) {

					if (($tipolim == '6') || ($tipolim == '1')) {
						$butt1 = $prezzotxt . '€';
					} else {
						$butt1 = $prezzotxt . '€';

					}
				} else {
					$butt1 = $prezzotxt . '€';

				}
			}
			$sala = '';

			if (($tipolim == '2') || ($tipolim == '1')) {
				$query3 = "SELECT nome FROM sale WHERE ID='" . $row['11'] . "' LIMIT 1";
				$result3 = mysqli_query($link2, $query3);
				if (mysqli_num_rows($result3) > 0) {
					$row3 = mysqli_fetch_row($result3);
					$sala = $row3['0'];
				}
			}

			if (in_array($tipolim, [7, 8])) {
				$servizio = getnomeserv($extra, $tipolim, $ID);
			}

			$butt3 = '';

			$testoinfo = $qtabutt . ' , ' . dataita2($time);

			if ($num > 1) {
				$testoinfo = $qtabutt;
			}

			$testo .= '

			<div uk-grid  class="uk_grid_div div_list_uk" >
				<div class="uk-width-auto lista_grid_numero" >' . $vis . '</div>
				    <div class="uk-width-expand lista_grid_nome uk-text-truncate"   >' . $servizio . '<br/>
						<span class="uk-text-muted uk-text-small" >' . $testoinfo . '</span></div>

			        <div class="uk-width-auto uk-text-right  lista_grid_right"  >' . $butt1 . '     </div>
				</div> ';

			$totale1 += $prezzo;
		}

	}

	if (($ii == 1) || ($ii == 3)) {

		//sconti modi = vecchio datacar

		$qadd3 = $arrfor2[$ii];
		$querysc = "SELECT p.ID,SUM(p.durata),s.servizio,p.modi,p.IDpren FROM prenextra as p,servizi as s WHERE p.IDpren IN($IDprenc) AND p.IDtipo='0' AND p.extra=s.ID $qadd3  GROUP BY p.IDpren,p.extra";
		$resultsc = mysqli_query($link2, $querysc);
		if (mysqli_num_rows($resultsc) > 0) {

			//$testo.='<tr ><td colspan="9"><div class="etich2" style=" color:#bc4504;">Abbuoni</div></td></tr>';
			while ($rowsc = mysqli_fetch_row($resultsc)) {

				$testo .= '
					<div uk-grid  class="uk_grid_div div_list_uk" >
				<div class="uk-width-auto lista_grid_numero" > </div>
				    <div class="uk-width-expand lista_grid_nome uk-text-truncate"   >' . $rowsc['2'] . '<br/>
						<span class="uk-text-muted uk-text-small" >(Pren: ' . estrainomeapp($rowsc['4']) . ')</span></div>

			        <div class="uk-width-auto uk-text-right  lista_grid_right"  >' . round($rowsc['1'], 2) . '   </div>
				</div> ';

				$totale1 += round($rowsc['1'], 2);
			}
		}

		$totgen += $totale1;
		$testo .= '</div>';

	}
}

$testo .= '</div>';

$query = "SELECT SUM(p2.prezzo) FROM prenextra2 as p2,prenextra as p WHERE p.IDpren IN($IDprenc) AND p.ID=p2.IDprenextra  AND p2.IDpren=p.IDpren  AND p2.paga='1'";
$result = mysqli_query($link2, $query);
$row = mysqli_fetch_row($result);
$prezzotot = round($row['0'], 2);

$testo .= '
<hr>


<div uk-grid  class="uk-margin-top uk_grid_div div_list_uk"    >
<div class="uk-width-auto lista_grid_numero"> </div>
    <div class="uk-width-expand lista_grid_nome "  ><strong>' . traduci('Totale Prenotazione', $lang, 1, 0) . '</strong>  </div>

    <div class="uk-width-auto uk-text-right  lista_grid_right">' . $prezzotot . ' €  </div>
</div>  ';

$testo .= '

<div class="content-block-title titlecontentnew"  style="color:#15a143;"><strong>' . traduci('Pagamenti', $lang, 1, 0) . '</strong></div>
			<div id="infoprentab  " class="p5 list-blocknew" >';

$acconto = 0;
$emettifattura = 0;
$queryacc = "SELECT IDscontr,SUM(valore),tipoobj,IDobj FROM scontriniobj WHERE IDobj IN($IDprenc) AND tipoobj IN(1,2,0,14) GROUP BY IDscontr";

$resultacc = mysqli_query($link2, $queryacc);
if (mysqli_num_rows($resultacc) > 0) {
	while ($rowacc = mysqli_fetch_row($resultacc)) {

		$tipopag = '';
		$colorpag = '';
		$buttadd = '';
		$pagatok = 0;

		$effettuato = 0;
		$sospeso = 0;
		$metodopag = '';

		switch ($rowacc['2']) {
		case 0:
			$tipopag = traduci('Pagamento Singoli Servizi', $lang, 1, 0);
			$colorpag = 'info';

			break;
		case 1:
			$tipopag = traduci('Saldo Finale', $lang, 1, 0);
			$colorpag = 'success';

			break;
		case 2:
			$tipopag = traduci('Acconto', $lang, 1, 0);
			$colorpag = 'warning';

			break;
		case 14:
			$tipopag = traduci('Caparra', $lang, 1, 0);
			$colorpag = 'warning';

			break;
		}

		$query3 = "SELECT metodo,valore,sospeso,time FROM scontrinimetodopag WHERE IDscontr='" . $rowacc['0'] . "' ";
		$result3 = mysqli_query($link2, $query3);
		if (mysqli_num_rows($result3) > 0) {
			while ($row3 = mysqli_fetch_row($result3)) {
				$metodo = $row3['0'];
				$timepag = $row3['3'];
				$txtsosp = '';
				if ($row3['2'] != 0) {
					$txtsosp = '<strong>*' . traduci('Sospeso', $lang, 1, 0) . '</strong>';
					$sospeso += $row3['1'];

					$query4 = "SELECT DISTINCT(IDscontr),valore FROM scontriniobj WHERE IDobj='$IDscontr' AND tipoobj='19'";
					$result4 = mysqli_query($link2, $query4);
					if (mysqli_num_rows($result4) > 0) {
						while ($row4 = mysqli_fetch_row($result4)) {
							$IDscontrin = $row4['0'];
							$sospeso -= $row4['1'];
						}
					}

				} else {
					$totale += $row3['1'];
				}

				$tipo = '';
				$query = "SELECT pagamento FROM tipopag WHERE ID='$metodo' LIMIT 1";
				$result = mysqli_query($link2, $query);
				if (mysqli_num_rows($result) > 0) {
					$row = mysqli_fetch_row($result);
					$tipo = $row['0'];
				}

				$metodopag .= '<span class="fs10"> (' . $tipo . ') </span>' . $txtsosp;
			}
		}

		$pulsanti = '';
		$effettuato = round($rowacc['1'], 2);

		if ($metodopag) {
			$txtoggetto = $tipopag . ' <br><span class="conto2">' . traduci('Pagamento Effettuato', $lang, 1, 0) . ' ' . $metodopag . '</span>';
		} else {
			$txtoggetto = $tipopag . '<br><span class="conto1">' . $metodopag . '</span>';
		}

		$testo .= '

			<div uk-grid  class="uk_grid_div div_list_uk" >
				<div class="uk-width-auto lista_grid_numero" > </div>
				    <div class="uk-width-expand lista_grid_nome uk-text-truncate"   >' . $txtoggetto . ' </div>

			        <div class="uk-width-auto uk-text-right  lista_grid_right"  >' . $effettuato . '    </div>
				</div>  ';

		$acconto += $rowacc['1'];
	}
}

$queryacc = "SELECT totale,data,IDagenzia,corrispettivo,contratto,ID FROM agenziepren WHERE IDobj IN($IDprenc) AND tipoobj='0'";
$resultacc = mysqli_query($link2, $queryacc);
if (mysqli_num_rows($resultacc) > 0) {
	$rowag = mysqli_fetch_row($resultacc);
	$agetot = $rowag['0'];
	$IDagenziapren = $rowag['5'];

	$query6 = "SELECT nome FROM agenzie WHERE ID='" . $rowag['2'] . "' LIMIT 1";
	$result6 = mysqli_query($link2, $query6);
	$row6 = mysqli_fetch_row($result6);
	$nomeag = $row6['0'];

	switch ($rowag['4']) {
	case 0: //paga all'agenzia

		//$quotaagenzia+=$agetot;

		$testo .= '<div class="item-contentnew" >
								 <div class="item-innernew item-innernew2">
									 <div class="item-media mediaright pr15"><div  class="roundfunc"></div>
									  </div>
								  <div class="item-title">' . traduci('Pagamento Presso', $lang, 1, 0) . ' ' . $nomeag . '</div>
								  <div class="item-after fs14 fw600 c000" >' . $agetot . ' €</div>
							</div>
						</div>';

		break;

	}
}

$testo .= '</div></div></div>';

echo $testo;
?>
