<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';
$IDstruttura = $_SESSION['IDstruttura'];
$parametri = $_SESSION['filtri_preventivo'] ?? [];

$datainizio = date('d/m/Y', $parametri['time_inizio']); //d/m/Y
$datafine = date('d/m/Y', $parametri['time_fine']);
$ricerca = $parametri['ricerca'] ?? '';

$testo = '


<div class="nav navbar_picker_flex" >
 	<div onclick="chiudi_picker()"><i class="fas fa-times icona_chiudi" ></i></div>
 	 	<div style="margin-top:5px;margin-right:10px;"><button class="btn_avanti_preventivo" onclick="filtri_ricerca_preventivo();chiudi_picker()">Ricerca</button></div>
</div>


<div class="content scroll_chat_auto" style="height:75vh">



<div class="div_list_uk uk_grid_div  " uk-grid >
		    <div class="uk-width-1-3 lista_grid_nome uk-first-column">Cerca  </div>

		 <div class="uk-width-expand uk-text-right lista_grid_right">

		      	<input class="uk-input filtro_ricerca_preventivo uk-form-small"  type="text" value="' . $ricerca . '" placeholder="Cerca Preventivo" data-name="ricerca"  />
			</div>

</div>



<div class="div_list_uk uk_grid_div  " uk-grid >
		    <div class="uk-width-1-3 lista_grid_nome uk-first-column">Data inizio   </div>

		 <div class="uk-width-expand uk-text-right lista_grid_right">

		      <input class="uk-input input_cli  uk-form-small uk-text-right filtro_ricerca_preventivo" data-name="data_inizio" style="border: 1px solid#e1e1e1;  border-radius: 3px;"
		      type="text" data-testo="Data inizio Ricerca" onclick="apri_modal(this,1);"  onchange=""  value="' . $datainizio . '"  data-noformat="' . ($datainizio ? convertiData($datainizio, 'NO') : '') . '"  readonly>
			</div>

</div>



<div class="div_list_uk uk_grid_div  " uk-grid >
	    <div class="uk-width-1-3 lista_grid_nome uk-first-column">Data fine   </div>

		 <div class="uk-width-expand uk-text-right lista_grid_right">

		      <input class="uk-input input_cli  uk-form-small uk-text-right filtro_ricerca_preventivo" data-name="data_fine" style="border: 1px solid#e1e1e1;  border-radius: 3px;"
		      type="text" data-testo="Data Fine Ricerca" onclick="apri_modal(this,1);"  onchange=""  value="' . $datafine . '"  data-noformat="' . ($datafine ? convertiData($datafine, 'NO') : '') . '"  readonly>
			</div>

</div>


</div> ';

echo $testo;

?>


<script>


</script>
