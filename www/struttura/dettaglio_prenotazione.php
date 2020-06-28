<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../config/connecti.php';
require_once __DIR__ . '/../../config/funzioni.php';
$IDstruttura = $_SESSION['IDstruttura'];

$dati = (isset($_POST['arr_dati']) ? $_POST['arr_dati'] : []);
$IDprenotazione = $dati['IDprenotazione'];

$telematico = get_telematico($IDstruttura);

$testo = '
<div class="div_container_principale">
	<input type="hidden" value="' . $IDprenotazione . '" id="IDprenotazione">
	<div id="dettaglio_prenotazione"></div>
</div>
<script>cambia_tab_prenotazione(' . $IDprenotazione . ',\'informazioni\')</script>
';
echo $testo;
return;
$query = "SELECT tempg,tempn,note,settore_inserimento FROM prenotazioni WHERE IDv='$IDprenotazione' LIMIT 1";
$result = mysqli_query($link2, $query);
$row = mysqli_fetch_row($result);
$tempg = $row['0'];
$tempn = $row['1'];
$note = stripslashes($row['2']);
$luogo_inserimento = $row['3'];

$lista_stati_alloggi = get_stati_alloggi();

$lista_alloggi = get_alloggi($IDstruttura);
$stati_prenotazione = get_stati_prenotazioni();
$stati_prenotazioni[-1] = ['classe' => '', 'stato' => 'Annullata', 'colore' => 'e54c5a'];

$dettaglio_prenotazione = get_prenotazioni(['0' => ['IDprenotazione' => $IDprenotazione]])['dati'][$IDprenotazione];

$checkin = $dettaglio_prenotazione['checkin'];
$checkout = $dettaglio_prenotazione['checkout'];
$datapren = $dettaglio_prenotazione['time_creazione'];
$stato_prenotazione = $dettaglio_prenotazione['stato'];
$lingua = $dettaglio_prenotazione['lingua'];
$IDappartamento = $dettaglio_prenotazione['lista_alloggi_occupati'][0];
$notti = $dettaglio_prenotazione['notti'];

$txt_stato = '';
if ($stato_prenotazione != -1) {

	$lista_stati = [];
	foreach ($stati_prenotazione as $stato => $val) {
		if ($stato == -1) {continue;}
		$lista_stati[$stato] = $val['stato'];
	}

	$txt_stato = '<div id="stati_pren" style="display:none;">
	<ul class="uk-list uk-list-divider uk-picker-bot" style="padding:5px 20px;" onchange="(r)=>{modprenot(' . $IDprenotazione . ',r,29,10,1);chiudi_picker();}">
	' . genera_select_uikit($lista_stati, $stato_prenotazione, []) . '</ul></div>';

}

$query = "SELECT stato FROM appartamenti WHERE ID='$IDappartamento' LIMIT 1";
$result = mysqli_query($link2, $query);
$row = mysqli_fetch_row($result);
$stato_alloggio = $row['0'];

$stati_alloggi = [];
foreach ($lista_stati_alloggi as $stato => $val) {
	$stati_alloggi[$stato] = $val['stato'];
}

//modprenot(' . $IDpren . ',' . "'" . $orario . "'" . ',' . $tipo . ',10,1);crea_picker(0);
$testo_pulizie = '
<div id="stato_pulizia_alloggio" style="display:none;">
<ul class="uk-list uk-list-divider uk-picker-bot" style="padding:5px 20px;" onchange="(r)=>{modprenot(' . $IDappartamento . ',r,17,10,1);chiudi_picker();}">
' . genera_select_uikit($stati_alloggi, $stato_alloggio, []) . '</ul></div>';

$IDprenc = prenotcoll($IDprenotazione);
ricalcolaagenziapren(0, $IDprenc);

$lista_persone = [];
$query = "SELECT i.ID,i.nome,i.IDcliente,i.IDrest,CONCAT_WS(' ',s.nome,s.cognome ),s.mail,s.tel,s.cell,t.restrizione
FROM infopren as i
LEFT JOIN schedine as s ON s.ID=i.IDcliente
JOIN tiporestr as t ON t.ID=i.IDrest
WHERE i.IDpren='$IDprenotazione' AND i.pers='1'";
$result = mysqli_query($link2, $query);
while ($row = mysqli_fetch_row($result)) {
	$IDinfopren = $row['0'];
	$nome = ($row['4'] ? $row['4'] : $row['1']);
	$lista_persone[$IDinfopren] = ['IDcliente' => $row['2'], 'nome' => $nome, 'mail' => $row['5'], 'telefono' => $row['6'], 'cellulare' => $row['7'], 'restrizione' => $row['8']];
}

$testo = '
<input type="hidden" value="' . $IDprenotazione . '" id="IDpren" >
<div class="div_container_principale">


	<ul class="uk-switcher uk-margin"  id="switcher" uk-switcher="swiping:false">
	    <li>

	    	<div class="div_uk_divider_list" style="margin-top:0px !important;">Dati prenotazione</div>



	    	<div id="time_checkin" style="display:none;">
				<ul class="uk-list uk-list-divider uk-picker-bot " style="padding:5px 20px;"  onchange="(r)=>{modprenot(' . $IDprenotazione . ',r,31,10,1);chiudi_picker();}">
					' . generaorario_uikit(date('H:i', $checkin), 1, 24, 60) . '
				</ul>
			</div>
		    <div class=" uk_grid_div div_list_uk" uk-grid onclick="carica_content_picker(' . "'time_checkin'" . ')" >
			    <div class="uk-width-2-3 lista_grid_nome">Arrivo  <br><span class="uk-text-muted uk-text-small" >' . dataita2($checkin) . '</span></div>
			    <div class="uk-width-expand uk-text-right lista_grid_right" >  ' . date('H:i', $checkin) . '  <span uk-icon="chevron-right" ></span></div>
			</div>




			<div id="time_checkout" style="display:none;">
				<ul class="uk-list uk-list-divider uk-picker-bot" style="padding:5px 20px;" onchange="(r)=>{modprenot(' . $IDprenotazione . ',r,149,10,1);chiudi_picker();}">
					' . generaorario_uikit(date('H:i', $checkout), 1, 24, 60) . '
				</ul>
			</div>
			<div class="div_list_uk uk_grid_div" uk-grid  onclick="carica_content_picker(' . "'time_checkout'" . ')">
			    <div class="uk-width-2-3 lista_grid_nome " >Partenza  <br><span class="uk-text-muted uk-text-small"  > ' . dataita2($checkout) . '</span></div>
			    <div class="uk-width-expand uk-text-right lista_grid_right"  >' . date('H:i', $checkout) . '  <span uk-icon="chevron-right" ></span></div>
			</div>

			' . $txt_stato . '
			<div class="div_list_uk uk_grid_div"  uk-grid  onclick="carica_content_picker(' . "'stati_pren'" . ')" >
			    <div class="uk-width-1-3 lista_grid_nome"    >Stato</div>
			    <div class="uk-width-expand uk-text-right lista_grid_right"> ' . $stati_prenotazione[$stato_prenotazione]['stato'] . '  <span uk-icon="chevron-right" ></span></div>
			</div>';

$testo_persone = '';
if (!empty($lista_persone)) {
	foreach ($lista_persone as $IDinfopren => $dati) {
//' . "'" . $dati['cellulare'] . "'" . ',' . "'" . $dati['telefono'] . "'" . ',' . "'" . $dati['mail'] . "'" . '
		$testo_persone .= '
			<div class="div_list_uk uk_grid_div" uk-grid  data-cellulare="' . $dati['cellulare'] . '" data-telefono="' . $dati['telefono'] . '" data-mail="' . $dati['mail'] . '"
			onclick="det_info_cli(' . $dati['IDcliente'] . ',' . $IDinfopren . ',this);">
				    <div class="uk-width-2-3 lista_grid_nome  uk-text-truncate" ><strong>' . $dati['nome'] . ' </strong><br><span class="uk-text-muted uk-text-small"  >' . $dati['mail'] . ' ' . $dati['cellulare'] . ' ' . $dati['telefono'] . '</span></div>
			        <div class="uk-width-expand uk-text-right lista_grid_right"> ' . $dati['restrizione'] . ' <span uk-icon="chevron-right"></span></div>
			</div>';
	}
}

$testo .= '<div class="div_uk_divider_list">Persone (N.' . count($lista_persone) . ')</div> ' . $testo_persone;

if ($notti > 0) {
	$testo .= '
		<div class="div_uk_divider_list">Alloggio</div>

			<div class="div_list_uk uk_grid_div" uk-grid>
			    <div class="uk-width-1-3 lista_grid_nome ">Alloggio</div>
			    <div class="uk-width-expand uk-text-right lista_grid_right"> ' . $lista_alloggi[$IDappartamento]['alloggio'] . '   </div>
			</div>  ';

	$testo .= $testo_pulizie . '
		<div class="div_list_uk uk_grid_div" uk-grid onclick="carica_content_picker(' . "'stato_pulizia_alloggio'" . ')">
		    <div class="uk-width-1-3 lista_grid_nome" >Pulizia</div>
		    <div class="uk-width-expand uk-text-right lista_grid_right"> ' . $lista_stati_alloggi[$stato_alloggio]['stato'] . '  <span uk-icon="chevron-right" ></span></div>
		</div> ';

	if (($_SESSION['contratto'] > 3) && ($notti != 0)) {

		$temperatura = genera_numeri_array(15, 30, 1, '°');

		$testo .= '
			<div id="temperatura_giorno" style="display:none;">
			<ul class="uk-list uk-list-divider uk-picker-bot" style="padding:5px 20px;" onchange="(r)=>{modprenot(' . $IDprenotazione . ',r,21,10,1);chiudi_picker();}">
			' . genera_select_uikit($temperatura, intval($tempg), []) . '</ul></div>

			<div class="div_list_uk uk_grid_div" uk-grid onclick="carica_content_picker(' . "'temperatura_giorno'" . ')">
			    <div class="uk-width-expand lista_grid_nome "  >T. Giorno (C&deg;)</div>
			    <div class="uk-width-expand uk-text-right lista_grid_right">' . $tempg . ' ° <span uk-icon="chevron-right" ></span></div>
			</div>


			<div id="temperatura_notte" style="display:none;">
			<ul class="uk-list uk-list-divider uk-picker-bot" style="padding:5px 20px;" onchange="(r)=>{modprenot(' . $IDprenotazione . ',r,22,10,1);chiudi_picker();}">
			' . genera_select_uikit($temperatura, intval($tempn), []) . '</ul></div>

			<div class="div_list_uk uk_grid_div" uk-grid onclick="carica_content_picker(' . "'temperatura_notte'" . ')">
			    <div class="uk-width-expand lista_grid_nome " >T. Notte (C&deg;)</div>
			    <div class="uk-width-expand uk-text-right lista_grid_right"> ' . $tempn . ' °  <span uk-icon="chevron-right" ></span></div>
			</div>  ';

	}

	$arr_letti = get_preparazione_prenotazione($IDprenotazione, $IDstruttura);

	$letti_txt = '';
	foreach ($arr_letti as $IDletto => $val) {
		$num = $val['numero'];
		$nome_letto = $val['nome_letto'];
		$dispozione_categoria_txt = '';
		if ($val['numero_massimo']) {
			$dispozione_categoria_txt = '<br/><span style="font-size:12px;color:#666">Max : <strong>' . $val['numero_massimo'] . '</strong></span> ';
		}

		$letti_txt .= '
				<div class="div_list_uk uk_grid_div " uk-grid>
					    <div class="uk-width-1-2 uk-text-truncate lista_grid_nome"  >' . $nome_letto . $dispozione_categoria_txt . '</div>
					    <div class="uk-width-expand uk-text-right lista_grid_right ">
					   			 <div class="stepper  stepper-init stepperrestr">

					    				<div class="stepper-button-minus"  onclick="selezionainfo(' . "'" . 'letto' . $IDletto . '' . "'" . ',2,4)" uk-icon="minus"></div>

									   <div class="stepper-value  inputrestr" min="0" max="20"  id="letto' . $IDletto . '" alt="' . $IDprenotazione . "_" . $IDletto . '" >' . $num . '</div>

									   <div class="stepper-button-plus" onclick="selezionainfo(' . "'" . 'letto' . $IDletto . '' . "'" . ',1,4)" uk-icon="plus"></div>
								 </div>

					    </div>
					</div> ';
	}

	if ($letti_txt) {
		$testo .= '	<div class="div_uk_divider_list"> Preparazione </div> ' . $letti_txt;
	}
}

$insda = '';
$query = "SELECT p2.nome FROM prenotazionins as p,personale as p2 WHERE p.IDpren='$IDprenotazione' AND p.IDpers=p2.ID LIMIT 1";
$result = mysqli_query($link2, $query);
if (mysqli_num_rows($result) > 0) {
	$row = mysqli_fetch_row($result);
	$insda = '- <b>Inserita da:</b> ' . $row['0'];
}

$insdove = '';

switch ($luogo_inserimento) {
case 1:
	$insdove = 'Inserita Direttamente da PC';
	break;
case 2:
	$insdove = 'Inserita in Prenotazione Online ';
	break;
case 3:
	$insdove = 'Inserita Direttamente da App Mobile';
	break;
case 4:
	$insdove = 'Inserita da Channel Manager';
	break;
case 5:
	$insdove = '- Inserita da Agenzia';
	break;
case 6:
	$insdove = ' - Inserita da Preventivatore';
	break;
}

$testo .= '
		<div class="div_uk_divider_list">Info prenotazione</div>
		<div class="div_list_uk uk_grid_div  uk_grid_div" uk-grid>
		    <div class="uk-width-expand " style="font-size: 17px;  color: #2542d9;"> ' . $insdove . ' <br/><span  class="uk-text-muted uk-text-small">Il ' . dataita4($datapren) . ' ' . date('H:i', $datapren) . '</span> </div>
		</div> ';

$query = "SELECT ID,attivo FROM autoconf WHERE IDstr='$IDstruttura' LIMIT 1";
$result = mysqli_query($link2, $query);
if (mysqli_num_rows($result) > 0) {
	$row = mysqli_fetch_row($result);
	$check = '';
	$IDauto = $row['0'];

	if ($row['1'] == 1) {
		$query2 = "SELECT ID FROM confermaplus WHERE IDstr='$IDstruttura' AND IDpren='$IDprenotazione' LIMIT 1";
		$result2 = mysqli_query($link2, $query2);
		if (mysqli_num_rows($result2) > 0) {
			$testo .= '
				<div class="titleb" style="color:#30b383; font-size:14px;">' . "La prenotazione non e' stata ancora confermata sull'APP Mobile" . '</div>';
		} else {
			$testo .= '
				<div class="titleb" style="color:#a42a2a; font-size:14px;"> ' . "(!) La prenotazione non e' stata ancora confermata sull'APP Mobile" . ' </div>';
		}
	}

}

if ((oraadesso($IDstruttura) - 300) < $datapren) {
	$query2 = "SELECT ID FROM stopconferma WHERE IDstr='$IDstruttura' AND IDpren='$IDprenotazione' AND reinvio='0' LIMIT 1";
	$result2 = mysqli_query($link2, $query2);
	if (mysqli_num_rows($result2) > 0) {
		$row2 = mysqli_fetch_row($result2);
		//controllo non inviare e rinviare
		$testo .= '
				 	<div class="uk-heading-divider uk-margin"><strong>' . "Invio notifica di inserimento ed<br>abilitazione all'APP stoppata" . '</strong></div>

				 	<div class="uk-margin-small" uk-grid onclick="modprenot(0,' . $row2['0'] . ',159,10,9)">
					    <div class="uk-width-expand"   style="font-size: 15px; color: #2542d9;">Invia Notifica</div>
					</div> ';

	} else {

		$testo .= '
				<div class="uk-heading-divider uk-margin"><strong>' . "Sara' inviata la notifica di inserimento ed abilitazione all'APP  tra pochi minuti.." . '</strong></div>

			 	<div class="uk-margin-small" uk-grid  onclick="modprenot(0,' . $IDprenotazione . ',158,10,9)">
				    <div class="uk-width-expand"   style="font-size: 15px; color: #2542d9;">Stop Notifica</div>
				</div> ';

	}
}

$testo .= '<br/><br/>

</li> <li>';

$serv_orari = [];
$query = "SELECT p.ID,p.time,p.modi,COUNT(DISTINCT(p2.IDprenextra)),GROUP_CONCAT(DISTINCT(p.ID) SEPARATOR ','),p.tipolim,p.extra,p.modi,SUM(p2.prezzo),GROUP_CONCAT(DISTINCT(p2.IDinfop) SEPARATOR ','),SUM(p2.qta),p.sala,p.IDtipo,p.sottotip,s.servizio FROM prenextra as p
	    JOIN prenextra2 as p2 ON p2.IDprenextra=p.ID
	    JOIN servizi as s ON s.ID=p.extra
	    WHERE  p2.IDpren IN ($IDprenc)   AND p2.paga>'0'  AND p.IDtipo NOT IN(8,9)  GROUP BY p.ID ORDER BY p.time";
$result = mysqli_query($link2, $query);
while ($row = mysqli_fetch_row($result)) {
	$IDprenextra = $row['0'];
	$time = $row['1'];
	$modi = $row['2'];
	$extra = $row['6'];
	$qta = $row['10'];
	$tipolim = $row['5'];
	$servizio = $row['14'];
	$time2 = time0($time);
	if ($tipolim != 6) {

		$textserv = '
					<div class="uk_grid_div div_list_uk  "  uk-grid  onclick="disponibilita_servizio(' . $IDprenextra . ')">
					    <div class="uk-width-expand lista_grid_nome uk-text-truncate" >' . $servizio . '<br/>
					  		<span class="uk-text-muted uk-text-small" > N.' . $qta . ' ' . ($qta == 1 ? 'persona' : 'persone') . '</span></div>
				        <div class="uk-width-auto  uk-text-right lista_grid_right"> ' . ($modi != 0 ? date('H:i', $time) : '--.--') . '  <span uk-icon="chevron-right" ></span></div>
					</div> ';

		if (isset($serv_orari[$time2])) {
			$serv_orari[$time2] .= $textserv;
		} else {
			$serv_orari[$time2] = $textserv;
		}
	}

}

if (!empty($serv_orari)) {
	foreach ($serv_orari as $time => $cont) {
		$testo .= '
		<div class="div_uk_divider_list"> ' . dataita($time) . ' ' . date('Y', $time) . ' </div>
		' . $cont;
	}
}

$testo .= ' </li>

	    <li>';

$sottogruppirag = [];
$totgen = 0;
$gruppi = array('Prenotazione Iniziale', 'Extra Ospite', "Prenotazione a Carico dell'Agenzia", "Extra a Carico dell'Agenzia");
$gruppiordine = array('0', '2', '3', '1');

$sottogruppi = [];
$sottogruppititolo = [];
$gruppisconti = [];

$sottogruppititolo[0][0] = '';
$sottogruppi[0][0] = '';

$sottogruppititolo[1][0] = '';
$sottogruppi[1][0] = "AND p.tipolim!='6' AND p.IDtipo!='19'";
$sottogruppititolo[1][1] = 'Prodotti';
$sottogruppi[1][1] = "AND p.tipolim='6' AND p.IDtipo NOT IN (15,16)";
$sottogruppititolo[1][2] = 'Ordini Ristorante';
$sottogruppi[1][2] = "AND p.tipolim='6' AND  p.IDtipo IN (15,16,17)";
$sottogruppititolo[1][3] = 'Elementi Rimossi';
$sottogruppi[1][3] = "AND p.IDtipo IN (19)";
$sottogruppirag[1][3] = "GROUP BY p.ID";

$sottogruppititolo[2][0] = '';
$sottogruppi[2][0] = '';
$sottogruppititolo[3][0] = '';
$sottogruppi[3][0] = '';

$gruppisconti[0] = " AND p.extra IN(1)";
$gruppisconti[1] = " AND p.extra IN(2,3)";
$gruppisconti[2] = " AND p.extra IN(5)";
$gruppisconti[3] = " AND p.extra IN(6)";

$txtgruppi = [];
$txttotgruppi = [];

$stampa = 0;
foreach ($gruppi as $datacar => $titolo) {
	$insert = 0;
	$totale1 = 0;

	if (isset($sottogruppi[$datacar])) {

		foreach ($sottogruppi[$datacar] as $key => $dato) {
			$qadd2 = "GROUP BY p.extra";
			$qadd = " AND p2.datacar='$datacar' " . $dato;

			if (isset($sottogruppirag[$datacar][$key])) {$qadd2 = $sottogruppirag[$datacar][$key];}

			$query = "SELECT p.ID,p.time,p2.datacar,COUNT(DISTINCT(p2.IDprenextra)),GROUP_CONCAT(DISTINCT(p.ID) SEPARATOR ','),p.tipolim,p.extra,p.modi,SUM(p2.prezzo),GROUP_CONCAT(DISTINCT(p2.IDinfop) SEPARATOR ','),SUM(p2.qta),p.sala,p.IDtipo,p.sottotip FROM prenextra as p,prenextra2 as p2 WHERE p2.pacchetto<='0' AND p2.IDpren IN ($IDprenc) AND p.ID=p2.IDprenextra AND p.IDtipo!='0' $qadd  $qadd2 ORDER BY p.IDtipo ";

			$result = mysqli_query($link2, $query);
			if (mysqli_num_rows($result) > 0) {

				//echo $query.'<br>';

				$insert = 1;
				if (isset($sottogruppititolo[$datacar][$key])) {
					if (strlen($sottogruppititolo[$datacar][$key]) > 0) {
						if (!isset($txtgruppi[$datacar])) {
							$txtgruppi[$datacar] = "";
						}
					}
				}

				$i = 0;
				while ($row = mysqli_fetch_row($result)) {
					$IDgroup = $row['4'];
					$tipolim = $row['5'];
					$testoinfo = '';

					//AND paga='1'
					$query2 = "SELECT qta FROM prenextra2 WHERE IDprenextra IN ($IDgroup) AND pacchetto<='0' GROUP BY IDprenextra";
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

						$servizio = getnomeserv($extra, $tipolim, $ID);

						$IDplus = str_replace(',', '', $row['9']);
						$IDplus = substr($IDplus, 0, 5);

						if (($tipolim == 9) || ($IDtipo == 20)) {
							$qtabutt = '';
						} else {

							if ($tipolim == '6') {
								$qtabutt = 'n. ' . $qta . ' oggetti';
							} else {
								$qtap = round($qta / $num);
								$qtabutt = 'n. ' . $qtap . ' ' . txtpersone($qtap);
							}
							// mettere solo funzione per modificare le persone e non testo
						}

						$gruppo = $row['4'];
						$txtsend = '0';
						$pagato = 0;
						$numnon = 0;

						$query2 = "SELECT GROUP_CONCAT(IDp2 SEPARATOR ',') FROM prenextra2 WHERE IDprenextra IN($gruppo) AND datacar='$datacar' AND pacchetto<='0' GROUP BY datacar";
						$result2 = mysqli_query($link2, $query2);
						if (mysqli_num_rows($result2) > 0) {
							$row2 = mysqli_fetch_row($result2);

							$txtsend = $row2['0'];
						}
						//	}

						$num2 = '';
						$qta2 = '';

						if (($tipolim == '5') || ($tipolim == '7') || ($tipolim == '8')) {
							$groupinfoID = $row['9'];
							$qta2 = ' <span style="font-size:12px;">per ' . round($qta / $num) . ' persone</span>';
							$arrg = explode(',', $IDgroup);
							//$prezzo=0;
							foreach ($arrg as $dato) {
								$pacchetto = $dato;
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
								//controllo non paga tutto

							}
						}
						$prezzotxt = "";
						//$datatxt = date('d/m/Y', $time);
						$prezzotxt = round($prezzo, 2);

						if ($tipolim == '6') {
							$qta2 = 'N.' . $qta;
							if ($num == 1) {
								//$num2 = datafunc($time, $row['7'], $tipolim, '', $ID);
								if ($row['7'] > 0) {
									$num2 = dataita3($time) . ' ' . date('H:i', $time);
								}
							}

						} else {
							if (($num == 1) && (($tipolim == '2') || ($tipolim == '1') || ($tipolim == '4') || ($tipolim == '9'))) {
								//$num2=datafunc($time,$row['7'],$tipolim,'openmorph(2,'.$ID.','.$id.')',$ID);

								if (($row['12'] == 2) || ($row['12'] == 1)) {
									$sottot = $row['12'];
								} else {
									$sottot = $row['13'];
								}
								if ($tipolim == 4) {
									$num2 = dataita3($time);
								} else {
									if (($row['7'] > 0)) {
										$num2 = dataita3($time) . ' ' . date('H:i', $time);
									}
								}

								//$num2 = datafunc($time, $row['7'], $tipolim, 'modserv2(' . $time . ',' . $sottot . ',' . $ID . ')', $ID);
							}

						}
						$numtxt = $num;

						$butt1 = ''; //prezzo
						$butt3 = ''; //sposta
						$butt4 = ''; //elimina
						$butt5 = ''; //orario

						$vis = ''; //visualizza

						$sost = 0;

						$funcexp = '';
						$txtsend2 = '';
						$modprezzo = 1;

						if ($num > 1) {
							//visualizza
							$txtsend2 = str_replace(',', '..', $txtsend);
							$funcexp = 'onclick="navigation(22,' . "'" . $txtsend2 . "'" . ',0,0)"';

							$vis = '<button class="shortcut mini10 info popover" onclick="vis2(-' . $ID . $IDplus . ',1,' . $num . ',1);">' . $num . '+<span>Visualizza Contenuto</span></button> ';

							//prezzo

							if (($tipolim != '8') && ($tipolim != '7')) {
								$butt1 = $prezzotxt;
								$modprezzo = 0;
							} else {
								$butt1 = $prezzotxt;
								$modprezzo = 1;
							}

						} else {

							//prezzo
							if (($tipolim != '8') && ($tipolim != '7')) {
								if (($tipolim == '6') || ($tipolim == '1')) {
									$butt1 = $prezzotxt;
									$modprezzo = 1;
								} else {
									$butt1 = $prezzotxt;
									$modprezzo = 1;
								}
							} else {
								$butt1 = $prezzotxt;
								$modprezzo = 0;
								if ($prezzotxt < 0) {
									$qtabutt = '';
								}
							}
						}

						$posdelete = 0;

						if ($tipolim != '8') {
							switch ($tipolim) {
							case 7:
								//controlla pagamento voucher
								$pagato = 0;

								$queryacc = "SELECT valore FROM scontriniobj WHERE IDobj='$extra' AND tipoobj ='7'";
								$resultacc = mysqli_query($link2, $queryacc);
								if (mysqli_num_rows($resultacc) == 0) {
									$butt6 = '<button class="shortcut  mini10  portacassa popover"><span>Voucher non pagato</span></button>';
								} else {
									$butt6 = '<button class="shortcut  mini10 success portacassaw popover"><span>Voucher pagato</span></button>';
								}

								break;
							default:

								if (($pagato != 0) && ($numnon != 0)) {
									//$butt6 = '<button class="shortcut  mini10 warning portacassaw popover" ><span>Servizio pagato in parte</span></button>';
									$posdelete = 1;
									$modprezzo = 0;
								}
								if ($pagato == 0) {
									$posdelete = 1;
									$modprezzo = 1;
									//$func = 'modifIDp(-' . $ID . $IDplus . ',this,10,' . $datacar . ',1,' . $num . ')';
									//$butt6 = '<button class="shortcut mini10  portacassa popover"><span>Servizio non pagato</span></button>';
								}
								if (($pagato != 0) && ($numnon == 0)) {
									//$butt6 = '<button class="shortcut  mini10 success portacassaw popover"><span>Servizio saldato completamente</span></button>';
									$posdelete = 0;
									$modprezzo = 0;
								}

								break;

							}
						}

						//sposta
						$butt3 = '';

						if ($tipolim == 8) {
							$servizio = '<input type="text" autocomplete="off" value="' . $servizio . '" id="cofanetto' . $ID . '" placeholder="Codice Cofanetto Agenzia" style="width:90%" onChange="modprenextra(' . $ID . ',' . "'cofanetto" . $ID . "'" . ',25,0)">';
						}

						if (!isset($txtgruppi[$datacar])) {
							$txtgruppi[$datacar] = "";
						}

						$testoinfo = $qtabutt . ' , ' . $num2;
						$txtgruppi[$datacar] .= '


									<div uk-grid  class="uk_grid_div div_list_uk" data-id="' . $txtsend . '" data-num="' . $num . '" data-delete="' . $posdelete . '" data-prezzo="' . $modprezzo . '"  onclick="modservice(this)" data-agg="1">
									<div class="uk-width-auto lista_grid_numero" >' . $num . '</div>
									    <div class="uk-width-expand lista_grid_nome uk-text-truncate"   >' . $servizio . '<br/>
											<span class="uk-text-muted uk-text-small" >' . $testoinfo . '</span></div>

								        <div class="uk-width-auto uk-text-right  lista_grid_right"  >' . $butt1 . ' €   <span uk-icon="chevron-right" ></span> </div>
									</div> 	';

						$totale1 += $prezzo;
					}
				}

			}

		}

	}

	if (isset($gruppisconti[$datacar])) {

		$qadd3 = $gruppisconti[$datacar];

		$querysc = "SELECT p.ID,SUM(p2.prezzo),s.servizio,p.modi,p.IDpren,p2.IDp2 FROM prenextra as p,servizi as s,prenextra2 as p2 WHERE p.IDpren IN($IDprenc) AND p.IDtipo='0' AND p.extra=s.ID AND p.ID=p2.IDprenextra $qadd3  GROUP BY p.IDpren,p.extra";
		$resultsc = mysqli_query($link2, $querysc);
		if (mysqli_num_rows($resultsc) > 0) {
			if (!isset($txtgruppi[$datacar])) {$txtgruppi[$datacar] = "";}
//$txtgruppi[$datacar] .= '<tr><td colspan="9"></td></tr>';

			while ($rowsc = mysqli_fetch_row($resultsc)) {

				$scontotxt = round($rowsc['1'], 2) . '€';

				$txtgruppi[$datacar] .= '

									<div uk-grid  class="uk_grid_div"  data-id="' . $rowsc['5'] . '" data-num="1" data-delete="1" data-prezzo="1"  onclick="modservice(this)"  data-agg="1">
									<div class="uk-width-auto lista_grid_numero"> </div>
									    <div class="uk-width-expand lista_grid_nome uk-text-truncate">' . $rowsc['2'] . '<br/>
											<span class="uk-text-muted uk-text-small" >' . $rowsc['2'] . '</span></div>

								        <div class="uk-width-auto uk-text-right  lista_grid_right">' . $scontotxt . '  <span uk-icon="chevron-right" ></span></div>
									</div>
					 ';

				$totale1 += round($rowsc['1'], 2);
			}
		}
	}
	$totgen += $totale1;

	if ($totale1 != 0) {
		$totale1 = round($totale1, 2);

		$txttotgruppi[$datacar] = '
				  <input type="hidden" value="' . $totale1 . '"  >

					<div uk-grid  class="uk-margin-top uk_grid_div div_list_uk"   onclick="contomodprezzo(' . $IDprenotazione . ',' . $datacar . ',0)"   >
					<div class="uk-width-auto lista_grid_numero"> </div>
					    <div class="uk-width-expand lista_grid_nome "  ><strong>Totale :</strong>  </div>

				        <div class="uk-width-auto uk-text-right  lista_grid_right">' . $totale1 . ' €   <span uk-icon="chevron-right" ></span></div>
					</div>
			 ';

	}

}

$stampa = 0;
foreach ($gruppiordine as $key) {
	if (isset($txtgruppi[$key])) {
		//foreach($txtgruppi[$IDgr] as $key =>$dato){
		$dato = $txtgruppi[$key];
		$testo .= '
			<div class="div_uk_divider_list"> ' . $gruppi[$key] . ' </div>
				' . $dato;
		if (isset($txttotgruppi[$key])) {
			$testo .= $txttotgruppi[$key];
		}

	}
}

$testo .= '	<div class=" div_uk_divider_list">Totale</div>

			<div uk-grid onclick="contomodprezzo(' . $IDprenotazione . ',-1,0)" class="uk-margin-top uk_grid_div div_list_uk" >
				<div class="uk-width-auto lista_grid_numero"> </div>
			    <div class="uk-width-expand lista_grid_nome"   > <strong>Totale Prenotazione: </strong></div>

		        <div class="uk-width-auto uk-text-right  lista_grid_right">' . round($totgen, 2) . ' €   <span uk-icon="chevron-right" ></span></div>
			</div>';

$testo .= '</li>
	    <li>';

$txtcarta = '';
$queryc = "SELECT ID FROM carte WHERE IDpren IN($IDprenc)";
$resultc = mysqli_query($link2, $queryc);
if (mysqli_num_rows($resultc) > 0) {
	$rowc = mysqli_fetch_row($resultc);
	$testo .= '<div style="float:right; margin-right:20px; text-align:right;font-size:14px; color:#d42aa6;"><strong>PRENOTAZIONE GARANTITA<br></strong> con Carta di Credito</b><br><span style="font-size:10px;">Clicca su Carta di Credito (da PC) per visualizzare</span></div><br><br><br><hr>';
}

$_SESSION['IDagenziaprenfatt'] = array();
$arroggetti = array(array());
$codobj = 0;

//0 oggetto
//1 quota sospeso
//2 quota da pagare
//3 informazioni
//4 pulsanti
//5 fattura

$query = "SELECT SUM(p2.prezzo) FROM prenextra2 as p2,prenextra as p WHERE p.IDpren IN($IDprenc) AND p.ID=p2.IDprenextra AND p2.datacar='0' AND p2.IDpren=p.IDpren  AND p2.paga='1'  AND p.tipolim NOT IN(7,8)";
$result = mysqli_query($link2, $query);
$row = mysqli_fetch_row($result);
$prezzoini = round($row['0'], 2);

$query = "SELECT SUM(p2.prezzo) FROM prenextra2 as p2,prenextra as p WHERE p.IDpren IN($IDprenc) AND p.ID=p2.IDprenextra AND p2.datacar='1' AND p2.IDpren=p.IDpren AND p2.paga='1' AND p.tipolim NOT IN(7,8)";
$result = mysqli_query($link2, $query);
$row = mysqli_fetch_row($result);
$prezzoextra = round($row['0'], 2);

$query = "SELECT SUM(durata) FROM prenextra  WHERE IDpren IN($IDprenc) AND IDtipo='0'";
$result = mysqli_query($link2, $query);
$row = mysqli_fetch_row($result);
$sconti = round($row['0'], 2);

$prezzotot = $prezzoextra + $prezzoini + $sconti;

$txtpag = '';

$query = "SELECT GROUP_CONCAT(extra SEPARATOR ',') FROM prenextra WHERE IDpren IN($IDprenc) AND IDstruttura='$IDstruttura' AND tipolim='7' AND modi>='0'";
$result = mysqli_query($link2, $query);
$row = mysqli_fetch_row($result);
$idee = $row['0'];
$arridee = explode(',', $idee);

//pagamenti ad idee

//pagamento a prenotazione

//pagamenti a servizi

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

		switch ($rowacc['2']) {
		case 0:
			$tipopag = 'Pagamento Singoli Servizi';
			$colorpag = 'info';

			break;
		case 1:
			$tipopag = 'Saldo Finale';
			$colorpag = 'success';

			break;
		case 2:
			$tipopag = 'Acconto';
			$colorpag = 'warning';

			break;
		case 14:
			$tipopag = 'Caparra';
			$colorpag = 'warning';

			break;
		}

		$quotasospesa = 0;
		$quotapagata = 0;
		$IDscontrpagsospeso = array();
		$timepag = 0;

		$metodopag = getmetodopag($rowacc['0'], $quotasospesa, $quotapagata, $IDscontrpagsospeso, $timepag);
		$metodopag = '<span style="font-size:9px"><br>' . strip_tags($metodopag) . '</span>';
		$arroggetti[$codobj][6] = 0;
		$txtoggetto = '';
		$pulsanti = '';

		$arroggetti[$codobj][0] = $tipopag;
		$arroggetti[$codobj][1] = $quotasospesa; //sospeso
		$arroggetti[$codobj][2] = $quotapagata; //effettuato
		$arroggetti[$codobj][3] = '<span><b>Metodo Pagamento:</b> ' . $metodopag . '</span>'; //info  <span>'.dataita4($timepag).'<br> MOD
		$arroggetti[$codobj][4] = $pulsanti; //pulsanti

		if ($pagatok == 0) {
			$arroggetti[$codobj][4] .= '';
		}
		$arroggetti[$codobj][5] = '';
		$arroggetti[$codobj][5] = $quotapagata;

		$codobj++;

		$acconto += $rowacc['1'];
	}
}

$quotaagenzia = 0;
$txtdapag = '';
$txteffet = '';
$txtdaeffet = '';
$txtagenzia = '';
$IDagenziapren = 0;
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

		$quotaagenzia += $agetot;
		//controllo pagamento acconto

		$txtagenzia .= '<tr><td class="tdtit">' . $nomeag . '<div class="shortcut mini17 infoicon info popover"><span>
			 Contratto: ' . $rowag['1'] . ' &euro;  sono stati pagati alla Agenzia</button><br>

			</td><td><b>' . $agetot . ' &euro;</b></td><td>' . date('d/m/Y', $rowag['1']) . '</td><td><i>Agenzia</i><br><b>' . $rowag['3'] . '€</b></td><td><i>Struttura</i><br><b>' . round($agetot - $rowag['3'], 2) . '€</b></td></tr>';

		if ($agetot > 0) {

			//controllo pagamanto

			$quotaversata = 0;

			$query3 = "SELECT valore,IDscontr FROM scontriniobj WHERE IDobj='$IDagenziapren' AND tipoobj='10'";
			$result3 = mysqli_query($link2, $query3);
			if (mysqli_num_rows($result3) > 0) {
				while ($row3 = mysqli_fetch_row($result3)) {
					$query4 = "SELECT timepag,metodopag FROM scontrini WHERE ID='" . $row3['1'] . "' LIMIT 1";
					$result4 = mysqli_query($link2, $query4);
					$row4 = mysqli_fetch_row($result4);
					$timepag = $row4['0'];

					$quotaversata += $row3['0'];

					$arroggetti[$codobj][0] = 'Quota Struttura [Ricevuta]<br><span>' . metodopag($row4['1']) . '</span>';
					$arroggetti[$codobj][1] = 0; //sospeso
					$arroggetti[$codobj][2] = round($row3['0'], 2); //effettuato
					$arroggetti[$codobj][3] = '<span>Metodo Pagamento: ' . metodopag($row4['1']) . '<br>Effettuato il ' . dataita4($timepag) . '</span>'; //info

					$arroggetti[$codobj][4] = '';
					$arroggetti[$codobj][5] = '';

					$codobj++;

				}

			}

			if (($agetot - $rowag['3'] - $quotaversata) > 0) {

				$qq = round($agetot - $quotaversata - $rowag['3'], 2);
				$arroggetti[$codobj][0] = 'Quota Struttura [Da Ricevere]';
				$arroggetti[$codobj][1] = $qq . ' € di ' . ($agetot - $rowag['3']); //sospeso
				$arroggetti[$codobj][2] = 0; //effettuato
				$arroggetti[$codobj][3] = '<span>Quota Struttura<br>Da ricevere</span>'; //info
				$arroggetti[$codobj][4] = ''; //pulsanti

				$codobj++;

			}
		}

		break;
	case 1: //paga alla struttura

		$txtagenzia .= '<tr><td class="tdtit">' . $nomeag . '<div class="shortcut mini17 infoicon info popover"><span>
			 Contratto: ' . $agetot . ' &euro; vanno pagati alla Struttura</button></td><td><b>' . $agetot . ' &euro;</b></td><td>' . date('d/m/Y', $rowag['1']) . '</td><td><i>Agenzia</i><br><b>' . round($rowag['3'], 2) . '€</b></td><td><i>Struttura</i><br><b>' . round($agetot - $rowag['3'], 2) . '€</b></td></tr>
				';

		$quotaversata = 0;

		if ($agetot > 0) {

			//controllo pagamanto

			$query3 = "SELECT valore,IDscontr FROM scontriniobj WHERE IDobj='$IDagenziapren' AND tipoobj='10'";
			$result3 = mysqli_query($link2, $query3);
			if (mysqli_num_rows($result3) > 0) {
				while ($row3 = mysqli_fetch_row($result3)) {

					$query4 = "SELECT timepag,metodopag FROM scontrini WHERE ID='" . $row3['1'] . "' LIMIT 1";
					$result4 = mysqli_query($link2, $query4);
					$row4 = mysqli_fetch_row($result4);
					$timepag = $row4['0'];

					$quotaversata += $row3['0'];

					$arroggetti[$codobj][0] = 'Quota agenzia [Versamento]<br><span>' . metodopag($row4['1']) . '</span>';
					$arroggetti[$codobj][1] = 0; //sospeso
					$arroggetti[$codobj][2] = round($row3['0'], 2); //effettuato
					$arroggetti[$codobj][3] = '<span>Metodo Pagamento: ' . metodopag($row4['1']) . '<br>Effettuato il ' . dataita4($timepag) . '</span>'; //info

					$arroggetti[$codobj][4] = '';

					$arroggetti[$codobj][5] = '>';
					$codobj++;
				}

			}

			if (($rowag['3'] + $quotaversata) > 0) {

				$qq = round($rowag['3'], 2) + $quotaversata;

				$arroggetti[$codobj][0] = 'Quota Agenzia [Da Versare]';
				$arroggetti[$codobj][1] = '-' . $qq; //sospeso
				$arroggetti[$codobj][2] = 0; //effettuato
				$arroggetti[$codobj][3] = '<span>Quota Agenzia<br>Da Versare</span>'; //info

				$arroggetti[$codobj][4] = '';
				//pulsanti
				$codobj++;
				//<button class="shortcut recta15 warning" style="width:97px;"  onclick="openscontr(0,10,'.$IDagenziapren.',0,1)">Versa Acconto</button>
				//<button class="shortcut recta15 success"   style="width:98px;" onclick="openscontr(0,10,'.$IDagenziapren.','.$qq.')">Versa Totale</button>

			}
		}

		break;
	case 2: //pagamento automatico

		$txtagenzia .= '<tr><td class="tdtit"><div class="shortcut mini17 infoicon info popover" ><span>
			 Contratto: ' . $agetot . ' &euro; vanno pagati alla Struttura</div>

				' . $nomeag . '

				</td><td><b>' . $agetot . ' &euro;</b></td><td>' . date('d/m/Y', $rowag['1']) . '</td><td><b>' . round($rowag['3'], 2) . '€</b></td><td><b>' . round($agetot - $rowag['3'], 2) . '€</b></td></tr>
				';

		$quotaversata = 0;

		if ($agetot > 0) {

			//controllo pagamanto

			$query3 = "SELECT valore,IDscontr FROM scontriniobj WHERE IDobj='$IDagenziapren' AND tipoobj='10' LIMIT 1";
			$result3 = mysqli_query($link2, $query3);
			if (mysqli_num_rows($result3) > 0) {
				$row3 = mysqli_fetch_row($result3);
				$query4 = "SELECT timepag,metodopag FROM scontrini WHERE ID='" . $row3['1'] . "' LIMIT 1";
				$result4 = mysqli_query($link2, $query4);
				$row4 = mysqli_fetch_row($result4);
				$timepag = $row4['0'];

				$quotaversata += $row3['0'];

				$arroggetti[$codobj][0] = 'Quota agenzia [Prelievo Effettuato]<br><span>' . metodopag($row4['1']) . '</span>';
				$arroggetti[$codobj][1] = 0; //sospeso
				$arroggetti[$codobj][2] = round($row3['0'], 2); //effettuato
				$arroggetti[$codobj][3] = '<span>Commissione Agenzia<br>Prelievo automatico</span>'; //info

				$arroggetti[$codobj][4] = '';

				$arroggetti[$codobj][5] = ''; //pulsanti
				$codobj++;

			}

			if (($rowag['3'] + $quotaversata) > 0) {

				$qq = round($rowag['3'], 2) + $quotaversata;

				$arroggetti[$codobj][0] = 'Quota Agenzia [Prelievo Auto]';
				$arroggetti[$codobj][1] = '-' . $qq; //sospeso
				$arroggetti[$codobj][2] = 0; //effettuato
				$arroggetti[$codobj][3] = '<span>Commissione Agenzia<br>Prelievo automatico</span>'; //info

				$arroggetti[$codobj][4] = '';

				$codobj++;

			}

		}

		break;
	}
}

$queryacc = "SELECT ID,extra,time FROM prenextra WHERE IDpren IN($IDprenc) AND tipolim='8'"; //controllo cofanetti
$resultacc = mysqli_query($link2, $queryacc);
if (mysqli_num_rows($resultacc) > 0) {

	while ($rowag = mysqli_fetch_row($resultacc)) {
		$IDprenextra = $rowag['0'];
		$IDcof = $rowag['1'];

		$query2 = "SELECT codice,IDagenzia,ID FROM cofanettivend WHERE IDprenextra='$IDprenextra'";
		$result2 = mysqli_query($link2, $query2);
		$row2 = mysqli_fetch_row($result2);
		$codice = $row2['0'];
		$IDagenzia = $row2['1'];
		$IDcofanetto = $row2['2'];

		$query2 = "SELECT prezzo,persone,cofanetto FROM cofanetti WHERE ID='$IDcof' LIMIT 1";
		$result2 = mysqli_query($link2, $query2);
		$row2 = mysqli_fetch_row($result2);
		$agetot = $row2['0'];
		$persone = $row2['1'];
		$cof = $row2['2'];

		$query2 = "SELECT nome FROM agenzie WHERE ID='$IDagenzia' LIMIT 1";
		$result2 = mysqli_query($link2, $query2);
		$row2 = mysqli_fetch_row($result2);
		$agenzia = $row2['0'];
		//suddividere da pagare cofanetto - non mischiare con acconto

		$acconto += $agetot;

		$txtagenzia .= '<tr><td class="tdtit">' . $agenzia . '<br>

			<div style="font-weight:300; font-size:10px; text-transform:none;">Confanetto : ' . $cof . '</div>
			</td><td><b>' . round($agetot, 2) . ' &euro;</b></td><td>' . date('d/m/Y', $rowag['2']) . '</td><td></td><td><b>' . round($agetot, 2) . ' &euro;</b></td></tr>';

		//in caso di confatti paga la quota agenzia rispettiva al cofanetto

		$quotacofanetto = 0;

		$query3 = "SELECT valore,IDscontr FROM scontriniobj WHERE IDobj='$IDcofanetto' AND tipoobj='11' LIMIT 1";
		$result3 = mysqli_query($link2, $query3);
		if (mysqli_num_rows($result3) > 0) {
			$row3 = mysqli_fetch_row($result3);
			$query4 = "SELECT timepag,metodopag FROM scontrini WHERE ID='" . $row3['1'] . "' LIMIT 1";
			$result4 = mysqli_query($link2, $query4);
			$row4 = mysqli_fetch_row($result4);
			$timepag = $row4['0'];

			$arroggetti[$codobj][0] = 'Cofanetto [Versamento ricevuto]<br><span>' . metodopag($row4['1']) . '</span>';
			$arroggetti[$codobj][1] = 0; //sospeso
			$arroggetti[$codobj][2] = round($row3['0'], 2); //effettuato
			$arroggetti[$codobj][3] = '<span>Quota Cofanetto<br>Versamento ricevuto</span>'; //info

			$arroggetti[$codobj][4] = '';

			$arroggetti[$codobj][5] = '';

			$quotacofanetto += $row3['0'];

			$codobj++;

		}

		$qq = $agetot - $quotacofanetto;
		if ($qq > 0) {
			$arroggetti[$codobj][0] = 'Valore Cofanetto [Da ricevere]';
			$arroggetti[$codobj][1] = round($qq, 2); //sospeso
			$arroggetti[$codobj][2] = 0; //effettuato
			$arroggetti[$codobj][3] = '<span>Quota Agenzia<br>Agenzia paga il pacchetto alla struttura </span>'; //info
			$arroggetti[$codobj][4] = ''; //pulsanti
			$codobj++;
		}

	}
}

$accontoreg = 0;
$prezzoidee = 0;
$prezzodasaldreg = 0;

//$queryacc="SELECT extra,ID FROM prenextra WHERE tipolim='7' AND IDpren IN($IDprenc)";

$queryacc = "SELECT p.extra,p.ID,SUM(prezzo) FROM prenextra as p,prenextra2 as p2  WHERE p.IDpren IN($IDprenc) AND p.tipolim='7' AND p.ID=p2.IDprenextra GROUP BY p.ID";

$resultacc = mysqli_query($link2, $queryacc);
if (mysqli_num_rows($resultacc) > 0) {
	while ($rowacc = mysqli_fetch_row($resultacc)) {
		$IDreg = $rowacc['0'];
		$prezzoreg2 = $rowacc['2'];
		$pacchetto = $rowacc['0'] . '/' . $rowacc['1'];

		$query3 = "SELECT SUM(prezzo) FROM prenextra2 WHERE pacchetto='$pacchetto'";
		$result3 = mysqli_query($link2, $query3);
		$row3 = mysqli_fetch_row($result3);
		$prezzoreg2 += $row3['0'];

		$query3 = "SELECT v.tipocliente,v.IDcliente,v.ID FROM vendite as v,venditeoggetti as vo WHERE vo.IDfinale='$IDreg' AND vo.tipoobj='7' AND vo.IDvendita=v.ID LIMIT 1";
		$result3 = mysqli_query($link2, $query3);
		$row3 = mysqli_fetch_row($result3);
		$tipocli = $row3['0'];
		$IDcli = $row3['1'];
		$IDvendita = $row3['2'];
		$insasacc = 0;

		//controllo pagamento voucher

		$pagvoucher = 0;

		if ($prezzoreg2 < 0) {
			$acconto -= $prezzoreg2;
			$prezzoreg2 *= -1;
		}

		$prezzoidee += $prezzoreg2;

		//echo 'aa'.$prezzoidee.'<br>';
		//echo $prezzoreg2;

		if ($tipocli == '5') {

			$query4 = "SELECT corrispettivo,perc,totale,ID,contratto,IDagenzia FROM agenziepren WHERE IDobj='$IDvendita' AND tipoobj='1' LIMIT 1";
			$result4 = mysqli_query($link2, $query4);
			$row4 = mysqli_fetch_row($result4);
			$corr = $row4['0'];
			$parc = $row4['1'];
			$tot = $row4['2'];
			$IDagenziaprenvend = $row4['3'];

			if (!in_array($IDagenziaprenvend, $_SESSION['IDagenziaprenfatt'])) {
				$_SESSION['IDagenziaprenfatt'][] = $IDagenziaprenvend;
				//echo $IDagenziapren.'<br>';
				$contratto = $row4['4'];
				$IDagenzia = $row4['5'];

				$query4 = "SELECT SUM(prezzo) FROM indirizzisped WHERE IDvend='$IDvendita'";
				$result4 = mysqli_query($link2, $query4);
				$row4 = mysqli_fetch_row($result4);
				$spedizione = $row4['0'];

				$query4 = "SELECT SUM(totale) FROM venditeoggetti WHERE IDvendita='$IDvendita'";
				$result4 = mysqli_query($link2, $query4);
				$row4 = mysqli_fetch_row($result4);
				$tot1 = $row4['0'];

				switch ($contratto) {
				case 0:
					$asaldo = $tot - $corr;
					$quotastr = 0;
					break;
				case 1:
				case 2:
					$asaldo = -$corr;
					$quotastr = $tot;
					break;
				}

				$pagatagenzia = 0;

				$query3 = "SELECT valore,IDscontr FROM scontriniobj WHERE IDobj='$IDagenziaprenvend' AND tipoobj='10'";

				$result3 = mysqli_query($link2, $query3);
				if (mysqli_num_rows($result3) > 0) {
					while ($row3 = mysqli_fetch_row($result3)) {

						$query4 = "SELECT timepag,metodopag FROM scontrini WHERE ID='" . $row3['1'] . "' LIMIT 1";
						$result4 = mysqli_query($link2, $query4);
						$row4 = mysqli_fetch_row($result4);
						$timepag = $row4['0'];

						$pagatagenzia += $row3['0'];

						if ($row3['0'] > 0) {
							$arroggetti[$codobj][0] = 'Quota agenzia [Pagamento]<br><span>' . metodopag($row4['1']) . '</span>';
						} else {
							$arroggetti[$codobj][0] = 'Quota agenzia [Versamento]<br><span>' . metodopag($row4['1']) . '</span>';
						}

						$arroggetti[$codobj][1] = 0; //sospeso
						$arroggetti[$codobj][2] = round($row3['0'], 2); //effettuato
						$arroggetti[$codobj][3] = '<span>Metodo Pagamento: ' . metodopag($row4['1']) . '<br>Effettuato il ' . dataita4($timepag) . '</span>'; //info

						$arroggetti[$codobj][4] = '';

						$arroggetti[$codobj][5] = '';
						$codobj++;
					}
				}

				$prezzosaldagenzia = $asaldo - $pagatagenzia;

				$query4 = "SELECT nome FROM agenzie WHERE ID='$IDcli' LIMIT 1";
				$result4 = mysqli_query($link2, $query4);
				$row4 = mysqli_fetch_row($result4);
				$nagenzia = $row4['0'];

				$rimborso = '';

				if ($prezzosaldagenzia != 0) {

					if ($prezzosaldagenzia < 0) {
						$arroggetti[$codobj][0] = 'Quota da Versare [Agenzia]';

					} else {
						$arroggetti[$codobj][0] = 'Quota da Ricevere [Agenzia]';

					}
					$arroggetti[$codobj][1] = $prezzosaldagenzia; //sospeso
					$arroggetti[$codobj][2] = 0; //effettuato
					$arroggetti[$codobj][3] = '<span>Corrispettivo: ' . $corr . '€<br>(' . $parc . '% di ' . $tot1 . '€)<br>' . $rimborso . '</span>'; //info

					//<button class="shortcut recta15 warning" style="width:97px;" onclick="fatturaagenzia('.$IDvend.',5,1)">Acconto</button>
					//<button class="shortcut recta15 success" style="width:98px;" onclick="openscontr(0,10,'.$IDagenziapren.','.$prezzosald.')">Segnala Saldo</button>

					$arroggetti[$codobj][4] = '';
					$codobj++;

				}

			}

			if ($quotastr != 0) {

				$group = '0';
				$tot1 = 0;
				$query2 = "SELECT SUM(totale),GROUP_CONCAT(CONCAT('///',IDfinale,'_',tipoobj,'///') SEPARATOR ',') FROM venditeoggetti WHERE IDvendita='$IDvendita' AND tipoobj='7'";
				$result2 = mysqli_query($link2, $query2);
				$row2 = mysqli_fetch_row($result2);
				$tot1 += $row2['0'];
				$groupIDobj = $row2['1'];
				if (strlen($groupIDobj) > 0) {
					$group .= ',' . str_replace('///', "'", $groupIDobj);
				}

				if (strlen($group) > 2) {
					$group = substr($group, 2);
				}
				$totalevoucher = round($tot1 + $spedizione, 2);

				$pagvoucher = 0;

				$codicevoucher = getnomeserv($IDreg, 7, 0);

				$txtpag = '';
				$query3 = "SELECT valore,IDscontr,tipoobj FROM scontriniobj WHERE  IDobj='$IDreg' AND tipoobj='7' ";
				$result3 = mysqli_query($link2, $query3);
				if (mysqli_num_rows($result3) > 0) {
					while ($row3 = mysqli_fetch_row($result3)) {
						$pagvoucher += $row3['0'];

						$tipopag = 'Pagamento Voucher [' . $codicevoucher . ']';

						$query4 = "SELECT timepag,metodopag FROM scontrini WHERE ID='" . $row3['1'] . "' LIMIT 1";
						$result4 = mysqli_query($link2, $query4);
						$row4 = mysqli_fetch_row($result4);
						$timepag = $row4['0'];
						$metodopag = $row4['1'];

						$arroggetti[$codobj][0] = $tipopag . '<br><span>' . metodopag($metodopag) . '</span>';
						$arroggetti[$codobj][1] = 0; //sospeso
						$arroggetti[$codobj][2] = round($row3['0'], 2); //effettuato
						$arroggetti[$codobj][3] = '<span>Metodo Pagamento: ' . metodopag($metodopag) . '<br>Effettuato il ' . dataita4($timepag) . '</span>'; //info
						$arroggetti[$codobj][4] = '';

						$arroggetti[$codobj][5] = '';
						$codobj++;

					}
				}

				$prezzosaldvoucher = $prezzoreg2 - $pagvoucher;
				if ($prezzosaldvoucher != 0) {
					$arroggetti[$codobj][0] = 'A Saldo Voucher [' . $codicevoucher . ']';
					$arroggetti[$codobj][1] = $prezzosaldvoucher; //sospeso
					$arroggetti[$codobj][2] = 0; //effettuato
					$arroggetti[$codobj][3] = '<span>Registra il pagamento di  Acconto o del Saldo</span>'; //info
					$arroggetti[$codobj][4] = '';

					//<button class="shortcut recta200" onclick="fatturaagenzia('.$IDvendita.',5,1)">Pagamento Voucher</button>
					$codobj++;

				}

			}

		} else {

			$codicevoucher = getnomeserv($IDreg, 7, 0);
			$query3 = "SELECT valore,IDscontr FROM scontriniobj WHERE IDobj='$IDreg' AND tipoobj='7'";
			$result3 = mysqli_query($link2, $query3);
			if (mysqli_num_rows($result3) > 0) {
				while ($row3 = mysqli_fetch_row($result3)) {

					$query4 = "SELECT timepag,metodopag FROM scontrini WHERE ID='" . $row3['1'] . "' LIMIT 1";
					$result4 = mysqli_query($link2, $query4);
					$row4 = mysqli_fetch_row($result4);
					$timepag = $row4['0'];
					$metodopag = $row4['1'];

					$delpag = '';
					$txtfattvou = '';
					/*$txtfattvou=getstampfattura($row3['1'],0,0);
							if(!$txtfattvou){
								$delpag='<button class="shortcut mini16 popover del3icon danger" onclick="msgboxelimina('.$row3['1'].',21,0,4)"><span>Elimina Pagamento</span></button>';
								$txtfattvou='Da emettere su "Voucher"';
							}*/

					if ($metodopag == 0) {
						$pagvoucher += $row3['0'];
						$arroggetti[$codobj][0] = 'Documento Fiscale Emesso Voucher [' . $codicevoucher . ']</span>';
						$arroggetti[$codobj][1] = round($row3['0'], 2); //sospeso
						$arroggetti[$codobj][2] = 0; //effettuato
						$arroggetti[$codobj][3] = '<span>Recarsi su Voucher per registrare il pagamento</span>'; //info

						//$arroggetti[$codobj][4]='';

						$arroggetti[$codobj][4] = '';

						$codobj++;
					} else {

						$pagvoucher += $row3['0'];
						$arroggetti[$codobj][0] = 'A Saldo Voucher [' . $codicevoucher . ']';
						$arroggetti[$codobj][1] = 0; //sospeso
						$arroggetti[$codobj][2] = round($row3['0'], 2); //effettuato
						$arroggetti[$codobj][3] = '<span>Metodo Pagamento: ' . metodopag($metodopag) . '<br>Effettuato il ' . dataita4($timepag) . '</span>'; //info

						$arroggetti[$codobj][4] = $delpag;

						$arroggetti[$codobj][5] = '<strong class="daemettere">' . $txtfattvou . '</strong>';

						$codobj++;
					}

				}

			}

			$prezzodasaldreg = $prezzoreg2 - $pagvoucher;

			if ($prezzodasaldreg != 0) {
				$arroggetti[$codobj][0] = 'Voucher da Saldare';
				$arroggetti[$codobj][1] = round($prezzodasaldreg, 2); //sospeso
				$arroggetti[$codobj][2] = 0; //effettuato
				$arroggetti[$codobj][3] = '<b>Voucher Non Pagato</b><br>Recarsi nella sezione "VOUCHER"</span>'; //info
				$arroggetti[$codobj][4] = ''; //pulsanti
				//<button class="shortcut recta15 success"  onclick="openscontr('.$IDreg.',7,'.$IDreg.','.$prezzodasaldreg.')">Saldo</button>
				$codobj++;

			}
		}

	}
}

$prezzosald = round($prezzotot - $acconto - $quotaagenzia, 2);

$prezzosald2 = round($prezzosald, 2);

if ($prezzosald2 != 0) {

	$arroggetti[$codobj][0] = 'Quota a Saldo';
	$arroggetti[$codobj][1] = $prezzosald2; //sospeso
	$arroggetti[$codobj][2] = 0; //effettuato
	$arroggetti[$codobj][3] = '<span>Effettua il pagamento con Acconto , Saldo o selezionando i servizi singolarmente</span>'; //info
	$arroggetti[$codobj][4] = ''; //pulsanti

	$codobj++;

} else {

}

$queryf = "SELECT SUM(totale) FROM fatture WHERE IDobj IN($IDprenc) AND tipo='0' AND tipoobj='0'";
$resultf = mysqli_query($link2, $queryf);
$rowf = mysqli_fetch_row($resultf);
$fatturaf = $rowf['0'];
$queryf = "SELECT SUM(totale) FROM fatture WHERE IDobj IN($IDprenc) AND tipo='1' AND tipoobj='0'";
$resultf = mysqli_query($link2, $queryf);
$rowf = mysqli_fetch_row($resultf);
$ricevutaf = $rowf['0'];

$dapag = '';
$sospeso = '';
$pagat2 = '';

if (empty($arroggetti[0])) {
	$testo .= '<div style="padding:15px;color:#b01f3e;"><strong>Questa prenotazione non ha servizi a pagamento</strong></div></br></br>';
} else {

	foreach ($arroggetti as $dato) {
		$tipostamp = 0;
		$sosp = '';
		if ($dato['1'] != 0) {
			$sosp = $dato['1'] . ' €';
			$tipostamp = 1;}
		$pagat = $dato['2'];
		if (is_numeric($dato['2'])) {
			if ($dato['2'] == 0) {
				$pagat = '';
			} else {
				$pagat = $dato['2'] . ' €';
			}
		}

		switch ($tipostamp) {
		case 1;

			$pagat2 .= '
					<div class="uk_grid_div div_list_uk"  uk-grid onclick="modpagamenti(' . $IDprenc . ');" id="' . $IDprenc . '" alt="2" pr="' . $sosp . '">
						<div class="uk-width-expand lista_grid_nome uk-text-truncate" >' . $dato['0'] . ' <br> <span class="uk-text-muted uk-text-small" >' . $dato['3'] . '</span></div>
						<div class="uk-width-auto  lista_grid_right">' . $sosp . '  <span uk-icon="chevron-right" ></span></div>

					</div>';
			/*
					<a class="item-link" onclick="modpagamenti(' . $IDprenc . ');" id="' . $IDprenc . '" alt="2" pr="' . $sosp . '">
						<div class="item-contentnew" >
							<div class="item-innernew item-innernew2 h60" >
								<div class="item-title">' . $dato['0'] . ' <br> <div class="desc">' . $dato['3'] . '</div></div>
								<div class="item-after fw600 c333">' . $sosp . '</div>
							</div>
					   </div>
					</a>*/
			break;
		case 0:

			$dapag .= '
					<div class="uk_grid_div div_list_uk" uk-grid onclick="modpagamenti(' . $dato['5'] . ')"  id="' . $dato['5'] . '"  alt="1" pr="' . $pagat . '">
						<div class="uk-width-expand lista_grid_nome uk-text-truncate"  >' . $dato['0'] . ' <br> <span class="uk-text-muted uk-text-small" style="font-size:12px;">' . $dato['3'] . '</span></div>
						<div class="uk-width-auto lista_grid_right" >' . $pagat . '	 <span uk-icon="chevron-right" ></span></div>

					</div>';
			/*
						<a class="item-link" onclick="modpagamenti(' . $dato['5'] . ')"  id="' . $dato['5'] . '"  alt="1" pr="' . $pagat . '">
							<div class="item-contentnew">
								<div class="item-innernew item-innernew2 h60" >
										<div class="item-title">' . $dato['0'] . ' <br> <div style="line-height:1;font-size:14px;">' . $dato['3'] . '</div></div>
										<div class="item-after fw600 c333">' . $pagat . '</div>
								</div>
						   </div>
						</a>';*/

			break;
		}

	}

	if (strlen($dapag) > 0) {
		$testo .= '	<div class="div_uk_divider_list">Pagamenti eseguiti</div>

			 ' . $dapag;

	}

	if (strlen($pagat2) > 0) {
		$testo .= '<div class="div_uk_divider_list">Pagamenti da Eseguire</div>
			' . $pagat2;
	}

	$testo .= '<br/><br/>

		<div class=" div_uk_divider_list"  >Totale</div>

		<div class="uk_grid_div div_list_uk" uk-grid onclick="contomodprezzo(' . $IDprenotazione . ',-1,0)" >

		    <div class="uk-width-expand lista_grid_nome"   > <strong>Totale Prenotazione: </strong></div>

	        <div class="uk-width-auto uk-text-right lista_grid_right">' . $prezzotot . ' €</div>
		</div> ';

}

$testo .= '</li>
	</ul> </div> ';

echo $testo;

?>
