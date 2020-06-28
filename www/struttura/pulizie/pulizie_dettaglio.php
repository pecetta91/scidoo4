<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';

$IDstruttura = $_SESSION['IDstruttura'];

$IDprenotazione = $_POST['IDpren'];

$testo = '';

$nota = '';

$query = "SELECT f.IDserv,s.servizio,f.ID FROM frigo_bar as f
JOIN servizi as s ON f.IDserv=s.ID WHERE f.IDstruttura='$IDstruttura' ORDER BY f.ordine";
$result = mysqli_query($link2, $query);
if (mysqli_num_rows($result) > 0) {
	$serv_addebita = '<div  style="padding-bottom:50px;">';
	while ($row = mysqli_fetch_row($result)) {
		$serv_addebita .= '

			<div class="uk-margin-small uk_grid_div uk-grid no_padding" uk-grid>
			    <div class="uk-width-3-5 uk-text-truncate lista_grid_nome uk-first-column">' . $row['1'] . '</div>
			    <div class="uk-width-expand uk-text-right lista_grid_right ">
			   			 <div class="stepper  stepper-init stepperrestr">

			    				<div class="stepper-button-minus " onclick="aumenta_serv(1,' . $row['0'] . ',2);" uk-icon="minus"></div>

							   <div class="stepper-value  serv" min="0" max="20"  alt="' . $row['0'] . '" id="qta' . $row['0'] . '">0</div>

							   <div class="stepper-button-plus " onclick="aumenta_serv(1,' . $row['0'] . ',1);" uk-icon="plus"></div>
						 </div>

			    </div>
			</div> ';
	}
	$serv_addebita .= '</div>';
}

$preparazione_pren = [];
$assenza_codice_letto = null;
$arr_letti = get_preparazione_prenotazione($IDprenotazione, $IDstruttura);
if (!empty($arr_letti)) {
	foreach ($arr_letti as $preparazione) {
		if ($preparazione['numero'] == 0) {continue;}
		if ($preparazione['codice'] == '') {$assenza_codice_letto = 1;}
	}

	foreach ($arr_letti as $preparazione) {
		if ($preparazione['numero'] == 0) {continue;}
		$stringa = ($assenza_codice_letto ? $preparazione['numero'] . ' ' . $preparazione['nome_letto'] : str_repeat($preparazione['codice'], $preparazione['numero']));
		$preparazione_pren[] = $stringa;
	}
}

$lista_restrizioni = get_restrizioni($IDstruttura)['lista_restrizioni'];

$restrizioni = [];
$query = "SELECT ID,IDrest FROM infopren  WHERE IDpren=$IDprenotazione AND pers=1";
$result = mysqli_query($link2, $query);
while ($row = mysqli_fetch_row($result)) {

	if (isset($restrizioni[$row[1]])) {
		$restrizioni[$row[1]] += 1;
	} else {
		$restrizioni[$row[1]] = 1;
	}
}

$lista_ospiti = [];
foreach ($restrizioni as $IDrestrizione => $numero) {
	$lista_ospiti[] = $numero . ' ' . ($numero > 1 ? $lista_restrizioni[$IDrestrizione]['restrizioni'] : $lista_restrizioni[$IDrestrizione]['restrizione']);

}

$query = "SELECT nota FROM note_interne WHERE IDobj='$IDprenotazione' AND tipoobj='0' AND tiponota='3'";
$result = mysqli_query($link2, $query);
if (mysqli_num_rows($result) > 0) {
	$row = mysqli_fetch_row($result);
	$nota = '

	<div class="uk-margin-bottom uk-margin-top">
		<div class="uk-heading-divider uk-margin"><strong>Note Pulizia</strong></div>

		<textarea class="uk-textarea	" readonly style="height:auto;font-size:13px;border-radius:3px;resize:none;" >' . $row['0'] . '</textarea>
	</div> ';
}

$lista_pulizie = [];
$lista_servizi = get_dati_conto_riferimento($IDprenotazione, 0, [], $IDstruttura)['arr_servizi'];

if (!empty($lista_servizi)) {
	foreach ($lista_servizi as $IDaddebito => $dati) {
		if (!in_array($dati['IDtipo'], [5])) {continue;}

		$qta = array_sum(array_column($dati['componenti'], 'qta'));

		$confermato = null;
		$query = "SELECT time FROM prenextra_confermati WHERE IDprenextra=$IDaddebito AND IDstruttura=$IDstruttura LIMIT 1";
		$result = mysqli_query($link2, $query);
		if (mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_row($result);
			$confermato = $row[0];
		}

		$pulsante = ' <button style="background:#da731a" class="btn_sconti_dettagli"  onclick="mod_riferimento(8,[' . $IDaddebito . ',0],0,10,()=>{chiudi_picker(); apri_pren_pul(' . $IDprenotazione . ');})">Conferma</button>';

		if ($confermato) {
			$pulsante = '<button style="background:#22bc51" class="btn_sconti_dettagli">Confermato!</button>';
		}

		$textserv = '
		<div class="uk_grid_div div_list_uk  "  uk-grid   data-id="' . $IDaddebito . '"  data-idtipo="' . $dati['IDtipo'] . '"   >
		    <div class="uk-width-expand lista_grid_nome uk-text-truncate" >' . $dati['nome_servizio'] . '   </div>
	        <div class="uk-width-auto  uk-text-right lista_grid_right c000">    ' . $pulsante . '  </div>
		</div> ';

		if (!isset($lista_pulizie[time0($dati['time'])])) {
			$lista_pulizie[time0($dati['time'])] = '';
		}

		$lista_pulizie[time0($dati['time'])] .= $textserv;

	}
}

$servizio = '';
if (!empty($lista_pulizie)) {
	foreach ($lista_pulizie as $time => $contenuto) {
		$servizio .= ' <div class="div_uk_divider_list"> ' . dataita($time) . ' ' . date('Y', $time) . ' </div>
' . $contenuto;
	}
}

$prenotazione = get_prenotazioni(['0' => ['IDprenotazione' => $IDprenotazione]])['dati'][$IDprenotazione];

$info_check = ' <div uk-grid class="uk-margin-small">
	<div class="uk-width-1-3 lista_grid_nome  ">Check-in</div>
	<div class="uk-width-expand  uk-text-small lista_grid_right uk-text-right">' . dataita6($prenotazione['checkin']) . ' - ' . $prenotazione['orario_checkin'] . '</div>
</div>

<hr style="margin: 0;">

<div uk-grid class="uk-margin-small">
	<div class="uk-width-1-3 lista_grid_nome  ">Check-out</div>
	<div class="uk-width-expand  uk-text-small lista_grid_right uk-text-right">' . dataita6($prenotazione['checkout']) . ' - ' . $prenotazione['orario_checkout'] . '</div>
</div>

<hr style="margin: 0;"> ';

$testo = '


<div class="nav navbar_picker_flex" >
 	<div onclick="chiudi_picker()"><i class="fas fa-times icona_chiudi" ></i></div>
 		<div class="uk-button-group div_btn_pulizie " style="    margin-right: 10px;">
		<button onclick="mostra_pren(1)" class="uk-button uk-text-capitalize  btn_tab_pren 1 active" style="padding:0 5px;">Prenotazione</button>
		<button onclick="mostra_pren(2)" class="uk-button uk-text-capitalize  btn_tab_pren 2" style="padding:0 5px">Addebita</button>
	</div>
</div>


<div class="content" style="margin-top:0;padding:0 10px;height: calc(100% - 70px);">
	<div id="dettagli_tab" style="padding-top:10px;">

<div class="div_uk_divider_list" style="    margin: 0px !important;  padding: 5px 0;  border: none;"  >N.' . $prenotazione['numero'] . ' ' . $prenotazione['nome_cliente'] . '</div>

		<input type="hidden" value="' . $IDprenotazione . '" id="idpren_pul">

		<div  class="button_bottom_div">
         	<button  onclick="inserisci_addebito_pulizie()" class="btn_add" style="display:none">Addebita</button>
        </div>




					<div class="tab_pren_pul 1">

					' . (!empty($preparazione_pren) ? '
						<div uk-grid class="uk-margin-small" >
							<div class="uk-width-1-3 lista_grid_nome "> Disposizione</div>
							<div class=" uk-text-small lista_grid_right uk-width-expand uk-text-right">' . implode(', ', $preparazione_pren) . '</div>
						</div>

					' : '') . '



					<hr style="margin:0;">


					<div uk-grid class="uk-margin-small" >
						<div class="uk-width-1-3 lista_grid_nome  ">Ospiti</div>
						<div class="uk-width-expand  uk-text-small lista_grid_right uk-text-right">' . implode(', ', $lista_ospiti) . '</div>
					</div>

					<hr style="margin:0;">
					' . $info_check . ' ' . $nota . $servizio . '
					</div>
					<div class="tab_pren_pul 2" style="display:none;">
 					    <ul  uk-tab="connect: #switcher_pren; animation: uk-animation-fade" id="tab_addebita">
					        <li class="uk-active" onclick="mostra_tab_addebita(0)"><a >Addebita</a></li>
					        <li onclick="mostra_tab_addebita(1)"><a >Addebitato</a></li>
					    </ul>
					 	<ul class="uk-switcher uk-margin" uk-switcher="swiping:false" id="switcher_pren">
					 <li>
						<div class="tab_addebita 1" > ' . $serv_addebita . ' </div>
					</li>
					<li>
					<div class="tab_addebita 2">  ';
$inc = 1;
echo $testo;
include 'pulizie_addebito.php';

echo '</div>

	</li></ul>
</div>



		</div>
</div>

';

?>
