<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';
$IDstruttura = $_SESSION['IDstruttura'];

//print_r($_SESSION['filtri_prenotazione']);

$parametri = $_SESSION['filtri_prenotazione'] ?? [];
$select_filtri = $parametri['tipodata'] ?? 0;

$datainizio = $parametri['datain']; //d/m/Y
$datafine = $parametri['datafin'];

/*
<input type="hidden" data-name="tipodata" class="filtro_ricerca_prenotazione" value="' . $select_filtri . '">
$lista_filtri = [1 => 'Data checkin', 2 => 'Data checkout', 3 => 'Data inserimento', 4 => 'Data permanenza', 6 => 'Opzionate', 5 => 'Intervallo date', 0 => 'Tutte le prenotazioni'];

$div_filtri_select = '';
$filtro_selezionato_txt = '';
foreach ($lista_filtri as $key => $value) {
if ($key == $select_filtri) {$filtro_selezionato_txt = $value;}
$div_filtri_select .= '<li onclick="cambia_tipodata(' . $key . ');chiudi_picker();"> ' . $value . ($select_filtri == $key ? '<span class="uk-align-right" uk-icon="check" style="color:#2641da"></span>' : '') . '</li>';
}

<div id="filtro_tipodata" style="display:none;">
<ul class="uk-list uk-list-divider uk-picker-bot " style="padding:5px 20px;">
' . $div_filtri_select . '
</ul>
</div>

' . $filtro_selezionato_txt . '  <i class="fas fa-chevron-right"></i>
onclick="carica_content_picker(' . "'deposito_regola'" . ')"
 */

$testo = '


<input type="hidden" value="1" data-name="attiva" class="filtro_ricerca_prenotazione">

<div class="nav navbar_picker_flex" >
 	<div onclick="chiudi_picker()"><i class="fas fa-times icona_chiudi" ></i></div>
 	<div style="margin-top:5px;margin-right:10px;"><button class="btn_avanti_preventivo" onclick="ricerca_prenotazioni();chiudi_picker()">Ricerca</button></div>
</div>


<div class="content scroll_chat_auto" style="height:75vh">




	<div class="div_uk_divider_list" style="margin-top:0px !important;"  >Filtra su
		<div style="float:right;color:#333">
			<select  data-name="tipodata" class="filtro_ricerca_prenotazione" style="width:160px;">
					<option value="1" ' . ($select_filtri == 1 ? 'selected' : '') . '>Data checkin</option>
					<option value="2" ' . ($select_filtri == 2 ? 'selected' : '') . '>Data checkout</option>
					<option value="3" ' . ($select_filtri == 3 ? 'selected' : '') . '>Data inserimento</option>
					<option value="4" ' . ($select_filtri == 4 ? 'selected' : '') . '>Data permanenza</option>
					<option value="6" ' . ($select_filtri == 6 ? 'selected' : '') . '>Opzionate</option>
					<option value="5" ' . ($select_filtri == 5 ? 'selected' : '') . '>Intervallo date</option>
					<option value="0" ' . ($select_filtri == 0 ? 'selected' : '') . '>Tutte le prenotazioni</option>
			</select>
		</div>
	</div>


<div class="div_list_uk uk_grid_div  " uk-grid >
		    <div class="uk-width-1-3 lista_grid_nome uk-first-column">Data inizio   </div>


		 <div class="uk-width-expand uk-text-right lista_grid_right">

		      <input class="uk-input input_cli  uk-form-small uk-text-right filtro_ricerca_prenotazione" data-name="datain" style="border: 1px solid#e1e1e1;  border-radius: 3px;"
		      type="text" data-testo="Data inizio Ricerca" onclick="apri_modal(this,1);"  onchange=""  value="' . $datainizio . '"  data-noformat="' . ($datainizio ? convertiData($datainizio, 'NO') : '') . '"  readonly>
			</div>

</div>



<div class="div_list_uk uk_grid_div  " uk-grid >
	    <div class="uk-width-1-3 lista_grid_nome uk-first-column">Data fine   </div>

		 <div class="uk-width-expand uk-text-right lista_grid_right">

		      <input class="uk-input input_cli  uk-form-small uk-text-right filtro_ricerca_prenotazione" data-name="datafin" style="border: 1px solid#e1e1e1;  border-radius: 3px;"
		      type="text" data-testo="Data Fine Ricerca" onclick="apri_modal(this,1);"  onchange=""  value="' . $datafine . '"  data-noformat="' . ($datafine ? convertiData($datafine, 'NO') : '') . '"  readonly>
			</div>

</div>


</div> ';

echo $testo;

?>


<script>

</script>
