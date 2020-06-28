<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../config/connecti.php';
require_once __DIR__ . '/../../config/funzioni.php';
$IDprenotazione = $_SESSION['IDstrpren'];
$IDstruttura = get_IDstruttura_from_IDpren($IDprenotazione);
$lang = $_SESSION['lang'] ?: 0;

$dettaglio_prenotazione = get_prenotazioni(['0' => ['IDprenotazione' => $IDprenotazione]], [], [], $IDstruttura)['dati'][$IDprenotazione];

$IDalloggio_principale = $dettaglio_prenotazione['IDalloggio_principale'];

/*
$query = "SELECT rangen,rangep FROM tempdef WHERE IDstruttura='$IDstruttura' LIMIT 1";
$result = mysqli_query($link2, $query);
$row = mysqli_fetch_row($result);
$rangen = round($row['0']);
$rangep = round($row['1']);*/

$query = "SELECT tempn,tempg FROM prenotazioni WHERE IDv=$IDprenotazione AND IDstruttura=$IDstruttura";
$result = mysqli_query($link2, $query);
$row = mysqli_fetch_row($result);
$temperatura_notte = $row['0'];
$temperatura_giorno = $row['1'];

/*
$query = "SELECT nome,temp,statod,risc,tempg,tempn FROM appartamenti WHERE ID=$IDalloggio_principale LIMIT 1";
$result = mysqli_query($link2, $query);
$row = mysqli_fetch_row($result);
$nome = $row['0'];
$temp = $row['1'];
$statod = $row['2'];
$risc = $row['3'];
$tempgdef = round($row['4'], 1);

$tempndef = round($row['5'], 1);

if (($risc = '1') && ($statod == 1)) {
$statodom = traduci('In Riscaldamento', $lang, 1);
$color = 'c11010';
}
if (($risc = '0') && ($statod == 1)) {
$statodom = traduci('In Raffreddamento', $lang, 1);
$color = '1759c6';
}

/*
$alloggio = traduci('Alloggio', $lang, 1) . ' : ' . $nome . '<br>';

if (($timeora > $time) && ($timeora < $checkout)) {
$testo .= '

<div style="width:100%; text-align:center; font-size:70px; color:#' . $color . '; line-height:45px;">
<span style="font-size:14px; color:#888">Temperatura Attuale</span><br><br>
' . $temp . '&deg;<br>
<div style="margin-top:0px;;font-size:15px;  font-weight:100; ">' . $statodom . '</div>
</div>';
} else {
$testo .= '

<div style="width:100%; text-align:center; font-size:70px; color:#' . $color . '; line-height:15px;">
<span style="font-size:14px; color:#888">' . traduci('La Temperatura Istantanea sarà', $lang, 1) . ';<br> ' . traduci('visualizzata qui da momento del tuo check-in', $lang, 1) . '</span><br>
</div>';
}
 */
$temperatura = genera_numeri_array(15, 30, 1, '°');

$testo = '


<div class="div_list_uk uk_grid_div " uk-grid>
	<div class="uk-width-1-2 uk-text-truncate lista_grid_nome"  >' . traduci('Temperatura Giorno', $lang) . ' (C °)</div>
			<div class="uk-width-expand uk-text-right lista_grid_right ">
			<div class="stepper  stepper-init stepperrestr">
				<div class="stepper-button-minus" style="color:#0075ff;border:none"  onclick="selezionainfo(\'giorno\',2,0)"  ><i class="fas fa-minus"></i></div>
				<div class="stepper-value  inputrestr" min="15" id="giorno"  onchange="mod_ospite(22,0,\'giorno\',\'html_id\');"  max="30" style="border-bottom:1px solid #d6d6d6"> ' . $temperatura_giorno . '</div>
				<div class="stepper-button-plus" style="color:#0075ff;border:none" onclick="selezionainfo(\'giorno\',1,0)"  ><i class="fas fa-plus"></i></div>
			</div>
		</div>
</div>



<div class="div_list_uk uk_grid_div " uk-grid>
	<div class="uk-width-1-2 uk-text-truncate lista_grid_nome"  >' . traduci('Temperatura Notte', $lang) . ' (C °)</div>
			<div class="uk-width-expand uk-text-right lista_grid_right ">
			<div class="stepper  stepper-init stepperrestr">
				<div class="stepper-button-minus" style="color:#0075ff;border:none"  onclick="selezionainfo(\'notte\',2,0)"  ><i class="fas fa-minus"></i></div>
				<div class="stepper-value  inputrestr" min="15" id="notte" onchange=" mod_ospite(23,0,\'notte\',\'html_id\');
				"   max="30" style="border-bottom:1px solid #d6d6d6"> ' . $temperatura_notte . ' </div>
				<div class="stepper-button-plus" style="color:#0075ff;border:none" onclick="selezionainfo(\'notte\',1,0)"  ><i class="fas fa-plus"></i></div>
			</div>
		</div>
</div>




  ';

echo $testo;
/*
<div id="temperatura_giorno" style="display:none;">
<ul class="uk-list uk-list-divider uk-picker-bot" style="padding:5px 20px;" onchange="(r)=>{mod_ospite(22,0,r,10,()=>{navigation_ospite(17,0)});chiudi_picker();}">
' . genera_select_uikit($temperatura, intval($temperatura_giorno), []) . '</ul></div>

<div class="div_list_uk uk_grid_div" uk-grid onclick="carica_content_picker($(\'#temperatura_giorno\'))">
<div class="uk-width-expand lista_grid_nome "  >T. Giorno (C °)</div>
<div class="uk-width-expand uk-text-right lista_grid_right">' . $temperatura_giorno . ' ° <i class="fas fa-chevron-right"></i></div>
</div>

<div id="temperatura_notte" style="display:none;">
<ul class="uk-list uk-list-divider uk-picker-bot" style="padding:5px 20px;" onchange="(r)=>{mod_ospite(23,0,r,10,()=>{navigation_ospite(17,0)}) ;chiudi_picker();}">
' . genera_select_uikit($temperatura, intval($temperatura_notte), []) . '</ul></div>

<div class="div_list_uk uk_grid_div" uk-grid onclick="carica_content_picker($(\'#temperatura_notte\'))">
<div class="uk-width-expand lista_grid_nome " >T. Notte (C °)</div>
<div class="uk-width-expand uk-text-right lista_grid_right"> ' . $temperatura_notte . ' ° <i class="fas fa-chevron-right"></i> </div>
</div>
 */

?>
