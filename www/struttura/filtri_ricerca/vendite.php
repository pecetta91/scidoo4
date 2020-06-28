<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';
$IDstruttura = $_SESSION['IDstruttura'];

//print_r($_SESSION['filtri_prenotazione']);

$parametri = $_SESSION['filtri_vendite'] ?? [];
$select_filtri = $parametri['oggetto_ricerca'] ?? 0;

$datainizio = $parametri['data_inizio']; //d/m/Y
$datafine = $parametri['data_fine'];

$lista_ricerca_stati = [0 => 'Nome Cliente', 1 => 'Note', 2 => 'Nome Voucher'];
$testo = '
<div class="nav navbar_picker_flex" >
 	<div onclick="chiudi_picker()"><i class="fas fa-times icona_chiudi" ></i></div>
 	<div style="margin-top:5px;margin-right:10px;"><button class="btn_avanti_preventivo" onclick="filtri_ricerca_vendite();chiudi_picker()">Ricerca</button></div>
</div>


<div class="content scroll_chat_auto" style="height:75vh">


 <div id="filtra_ricerca" style="display:none;">
	<ul class="uk-list uk-list-divider uk-picker-bot" style="padding:5px 20px;" onchange="(r,s)=>{cambia_valore_html_select(\'filtro_ricerca\',r,s);}">
		' . genera_select_uikit($lista_ricerca_stati, [$select_filtri]) . '
	</ul>
</div>


<div class="div_list_uk uk_grid_div  " uk-grid >
		    <div class="uk-width-1-3 lista_grid_nome uk-first-column">Filtra Su  </div>
			<div class="uk-width-expand uk-text-right lista_grid_right filtro_ricerca_vendite" data-name="oggetto_ricerca" value="' . $select_filtri . '" data-select="' . $select_filtri . '" onclick="carica_content_picker(' . "'filtra_ricerca'" . ')" id="filtro_ricerca"  style="text-decoration:underline">' . $lista_ricerca_stati[$select_filtri] . '</div>
</div>



<div class="div_list_uk uk_grid_div  " uk-grid >
		    <div class="uk-width-1-3 lista_grid_nome uk-first-column">Data inizio   </div>


		 <div class="uk-width-expand uk-text-right lista_grid_right">

		      <input class="uk-input input_cli  uk-form-small uk-text-right filtro_ricerca_vendite" data-name="datain" style="border: 1px solid#e1e1e1;  border-radius: 3px;"
		      type="text" data-testo="Data inizio Ricerca" onclick="apri_modal(this,1);"  onchange=""  value="' . $datainizio . '"  data-noformat="' . ($datainizio ? convertiData($datainizio, 'NO') : '') . '"  readonly>
			</div>

</div>



<div class="div_list_uk uk_grid_div  " uk-grid >
	    <div class="uk-width-1-3 lista_grid_nome uk-first-column">Data fine   </div>

		 <div class="uk-width-expand uk-text-right lista_grid_right">

		      <input class="uk-input input_cli  uk-form-small uk-text-right filtro_ricerca_vendite" data-name="datafin" style="border: 1px solid#e1e1e1;  border-radius: 3px;"
		      type="text" data-testo="Data Fine Ricerca" onclick="apri_modal(this,1);"  onchange=""  value="' . $datafine . '"  data-noformat="' . ($datafine ? convertiData($datafine, 'NO') : '') . '"  readonly>
			</div>

</div>


</div> ';

echo $testo;

?>


<script>

</script>
