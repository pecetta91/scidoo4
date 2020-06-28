<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';

$IDstruttura = $_SESSION['IDstruttura'];

$IDprenotazione = $_POST['IDprenotazione'];

$telematico = get_telematico($IDstruttura);

$query = "SELECT tempg,tempn,note,settore_inserimento FROM prenotazioni WHERE IDv='$IDprenotazione' LIMIT 1";
$result = mysqli_query($link2, $query);
$row = mysqli_fetch_row($result);
$tempg = $row['0'];
$tempn = $row['1'];
$note = stripslashes($row['2']);
$luogo_inserimento = $row['3'];

$lista_alloggi = get_alloggi($IDstruttura);
$stati_prenotazione = get_stati_prenotazioni();
$stati_prenotazioni[-1] = ['classe' => '', 'stato' => 'Annullata', 'colore' => 'e54c5a'];

$dettaglio_prenotazione = get_prenotazioni(['0' => ['IDprenotazione' => $IDprenotazione]])['dati'][$IDprenotazione];

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
	<ul class="uk-list uk-list-divider uk-picker-bot" style="padding:5px 20px;" onchange="(r)=>{modprenot(' . $IDprenotazione . ',r,29,10,()=>{
 		cambia_tab_prenotazione(' . $IDprenotazione . ',\'informazioni\')});chiudi_picker();}">
	' . genera_select_uikit($lista_stati, $stato_prenotazione, []) . '</ul></div>';

}

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

list($hh, $ii) = explode(":", $dettaglio_prenotazione['orario_checkin']);
$time_checkin = (floor((int) $hh * 3600)) + floor((int) $ii * 60);

list($hh, $ii) = explode(":", $dettaglio_prenotazione['orario_checkout']);
$time_checkout = (floor((int) $hh * 3600)) + floor((int) $ii * 60);

$testo = ' <div class="div_uk_divider_list" style="margin-top:0px !important;">Dati prenotazione</div>



	    	<div id="time_checkin" style="display:none;">
				<ul class="uk-list uk-list-divider uk-picker-bot " style="padding:5px 20px;"  onchange="(r)=>{modprenot(' . $IDprenotazione . ',r,307,10,()=>{
 		cambia_tab_prenotazione(' . $IDprenotazione . ',\'informazioni\')});chiudi_picker();}">
 					' . genera_select_uikit(genera_ora_uikit(6, 23), $time_checkin) . '

				</ul>
			</div>
		    <div class=" uk_grid_div div_list_uk" uk-grid onclick="carica_content_picker(' . "'time_checkin'" . ')" >
			    <div class="uk-width-2-3 lista_grid_nome">Arrivo  <br><span class="uk-text-muted uk-text-small" >' . dataita2($dettaglio_prenotazione['checkin']) . '</span></div>
			    <div class="uk-width-expand uk-text-right lista_grid_right" >  ' . $dettaglio_prenotazione['orario_checkin'] . '  <span uk-icon="chevron-right" ></span></div>
			</div>

			' . ($dettaglio_prenotazione['notti'] != 0 ? '
					<div id="time_checkout" style="display:none;">
					<ul class="uk-list uk-list-divider uk-picker-bot" style="padding:5px 20px;" onchange="(r)=>{modprenot(' . $IDprenotazione . ',r,308,10,()=>{
 		cambia_tab_prenotazione(' . $IDprenotazione . ',\'informazioni\')});chiudi_picker();}">
							' . genera_select_uikit(genera_ora_uikit(1, 23), $time_checkout) . '
					</ul>
				</div>
				<div class="div_list_uk uk_grid_div" uk-grid  onclick="carica_content_picker(' . "'time_checkout'" . ')">
				    <div class="uk-width-2-3 lista_grid_nome " >Partenza  <br><span class="uk-text-muted uk-text-small"  > ' . dataita2($dettaglio_prenotazione['checkout']) . '</span></div>
				    <div class="uk-width-expand uk-text-right lista_grid_right"  >' . $dettaglio_prenotazione['orario_checkout'] . '  <span uk-icon="chevron-right" ></span></div>
				</div>
			' : '') . '


			' . $txt_stato . '
			<div class="div_list_uk uk_grid_div"  uk-grid  onclick="carica_content_picker(' . "'stati_pren'" . ')" >
			    <div class="uk-width-1-3 lista_grid_nome"    >Stato</div>
			    <div class="uk-width-expand uk-text-right lista_grid_right"> ' . $stati_prenotazione[$stato_prenotazione]['stato'] . '  <span uk-icon="chevron-right" ></span></div>
			</div>';

$testo_persone = '';
if (!empty($lista_persone)) {
	foreach ($lista_persone as $IDinfopren => $dati) {
		$testo_persone .= '
			<div class="div_list_uk uk_grid_div" uk-grid  data-cellulare="' . $dati['cellulare'] . '" data-telefono="' . $dati['telefono'] . '" data-mail="' . $dati['mail'] . '"
			onclick="det_info_cli(' . $dati['IDcliente'] . ',' . $IDinfopren . ',this);">
				    <div class="uk-width-2-3 lista_grid_nome  uk-text-truncate" ><strong>' . $dati['nome'] . ' </strong><br><span class="uk-text-muted uk-text-small"  >' . $dati['mail'] . ' ' . $dati['cellulare'] . ' ' . $dati['telefono'] . '</span></div>
			        <div class="uk-width-expand uk-text-right lista_grid_right"> ' . $dati['restrizione'] . ' <span uk-icon="chevron-right"></span></div>
			</div>';
	}
}

$testo .= '<div class="div_uk_divider_list">Persone (N.' . count($lista_persone) . ')</div> ' . $testo_persone;

if ($dettaglio_prenotazione['notti'] > 0 && ($IDappartamento)) {
	$lista_stati_alloggi = get_stati_alloggi();

	$query = "SELECT stato FROM appartamenti WHERE ID='$IDappartamento' LIMIT 1";

	$result = mysqli_query($link2, $query);
	$row = mysqli_fetch_row($result);
	$stato_alloggio = $row['0'];

	$stati_alloggi = [];
	foreach ($lista_stati_alloggi as $stato => $val) {
		$stati_alloggi[$stato] = $val['stato'];
	}

	$testo .= '
		<div class="div_uk_divider_list">Alloggio</div>

			<div class="div_list_uk uk_grid_div" uk-grid>
			    <div class="uk-width-1-3 lista_grid_nome ">Alloggio</div>
			    <div class="uk-width-expand uk-text-right lista_grid_right"> ' . $lista_alloggi[$IDappartamento]['alloggio'] . '   </div>
			</div>


		<div id="stato_pulizia_alloggio" style="display:none;">
				<ul class="uk-list uk-list-divider uk-picker-bot" style="padding:5px 20px;" onchange="(r)=>{modprenot(' . $IDappartamento . ',r,17,10,()=>{
 		cambia_tab_prenotazione(' . $IDprenotazione . ',\'informazioni\')});chiudi_picker();}">
				' . genera_select_uikit($stati_alloggi, $stato_alloggio, []) . '</ul>
		</div>

		<div class="div_list_uk uk_grid_div" uk-grid onclick="carica_content_picker(' . "'stato_pulizia_alloggio'" . ')">
		    <div class="uk-width-1-3 lista_grid_nome" >Pulizia</div>
		    <div class="uk-width-expand uk-text-right lista_grid_right"> ' . $lista_stati_alloggi[$stato_alloggio]['stato'] . '  <span uk-icon="chevron-right" ></span></div>
		</div> ';

	if (($_SESSION['contratto'] > 3) && ($notti != 0)) {

		$temperatura = genera_numeri_array(15, 30, 1, '°');

		$testo .= '
			<div id="temperatura_giorno" style="display:none;">
			<ul class="uk-list uk-list-divider uk-picker-bot" style="padding:5px 20px;" onchange="(r)=>{modprenot(' . $IDprenotazione . ',r,21,10,()=>{
 		cambia_tab_prenotazione(' . $IDprenotazione . ',\'informazioni\')});chiudi_picker();}">
			' . genera_select_uikit($temperatura, intval($tempg), []) . '</ul></div>

			<div class="div_list_uk uk_grid_div" uk-grid onclick="carica_content_picker(' . "'temperatura_giorno'" . ')">
			    <div class="uk-width-expand lista_grid_nome "  >T. Giorno (C&deg;)</div>
			    <div class="uk-width-expand uk-text-right lista_grid_right">' . $tempg . ' ° <span uk-icon="chevron-right" ></span></div>
			</div>


			<div id="temperatura_notte" style="display:none;">
			<ul class="uk-list uk-list-divider uk-picker-bot" style="padding:5px 20px;" onchange="(r)=>{modprenot(' . $IDprenotazione . ',r,22,10,()=>{
 		cambia_tab_prenotazione(' . $IDprenotazione . ',\'informazioni\')});chiudi_picker();}">
			' . genera_select_uikit($temperatura, intval($tempn), []) . '</ul></div>

			<div class="div_list_uk uk_grid_div" uk-grid onclick="carica_content_picker(\'temperatura_notte\')">
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

					    				<div class="stepper-button-minus"  style="color:#0075ff;border:none" onclick="selezionainfo(\'letto' . $IDletto . '\',2,0)"  > <i class="fas fa-minus"></i></div>

									   <div class="stepper-value  inputrestr" min="0" max="20"  id="letto' . $IDletto . '" alt="' . $IDprenotazione . "_" . $IDletto . '"
									   onchange="  modprenot(\'' . $IDprenotazione . '_' . $IDletto . '\',\'letto' . $IDletto . '\',280,8);"
									   >' . $num . '</div>

									   <div class="stepper-button-plus" style="color:#0075ff;border:none" onclick="selezionainfo(\'letto' . $IDletto . '\',1,0)"  > <i class="fas fa-plus"></i></div>
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
	$insdove = '- Inserita da Preventivatore';
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

				 	<div class="uk-margin-small" uk-grid onclick="modprenot(0,' . $row2['0'] . ',159,10,()=>{
 		cambia_tab_prenotazione(' . $IDprenotazione . ',\'informazioni\')})">
					    <div class="uk-width-expand"   style="font-size: 15px; color: #2542d9;">Invia Notifica</div>
					</div> ';

	} else {

		$testo .= '
				<div class="uk-heading-divider uk-margin"><strong>' . "Sara' inviata la notifica di inserimento ed abilitazione all'APP  tra pochi minuti.." . '</strong></div>

			 	<div class="uk-margin-small" uk-grid  onclick="modprenot(0,' . $IDprenotazione . ',158,10,()=>{
 		cambia_tab_prenotazione(' . $IDprenotazione . ',\'informazioni\')})">
				    <div class="uk-width-expand"   style="font-size: 15px; color: #2542d9;">Stop Notifica</div>
				</div> ';

	}
}

echo $testo;

?>
