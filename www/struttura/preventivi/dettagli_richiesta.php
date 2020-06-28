<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';

$IDutente = $_SESSION['ID'];
$IDstruttura = $_SESSION['IDstruttura'];
$IDpreventivo = $_POST['IDpreventivo'];
$IDrichiesta = $_POST['IDrichiesta'];

$_SESSION['IDrequest'] = $IDrichiesta;

$picker = '

<input type="hidden" id="IDrichiesta" value="' . $IDrichiesta . '">

<div class="nav navbar_picker_flex" >
 	<div onclick="chiudi_picker()"><i class="fas fa-times icona_chiudi" ></i></div>

 	<div style="margin-top: 5px;">

	<button class="button_salva_preventivo" onclick="aggiunta_servizio({IDriferimento:' . $IDrichiesta . ',tipo_riferimento:1},{seleziona_tutto:0},()=>{ switch_tab_dettagli_richiesta(1); })"  >Aggiungi Servizio</button>

 	<div onclick="gestione_preventivo(12,{ id:' . $IDpreventivo . ', value: ' . $IDrichiesta . '},()=>{chiudi_picker();stampa_carrello_preventivo()});" style="  display: inline-block; padding-right: 10px;"><button class="button_salva_preventivo" style="background:#d80404">Elimina</button> </div>
 	</div>
</div>


<div>
	<ul  uk-tab="connect: #switcher;animation: uk-animation-fade;swiping:false" class="no_before menu_uk_picker_icona"  >
			<li class="uk-active" onclick="switch_tab_dettagli_richiesta(1)" style="margin:0 10px;width:auto;"><div class="fs15">Servizi</div></li>
	        <li onclick="switch_tab_dettagli_richiesta(2)"  style="margin:0 10px;width:auto;"><div class="fs15">Depositi</div></li>
	        <li class="" onclick="switch_tab_dettagli_richiesta(3)"  style="margin:0 10px;width:auto;"><div class="fs15">Impostazioni</div></li>
	        <li class="" onclick="switch_tab_dettagli_richiesta(4)"  style="margin:0 10px;width:auto;"><div class="fs15">Sconti</div></li>
    </ul>
</div>

<div class="content" style="padding:10px 5px;height:calc(100% - 125px)">

	 <div id="dettagli_tab">


 	</div>

</div>';

echo $picker;

?>
