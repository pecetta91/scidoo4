<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';
$IDutente = $_SESSION['ID'];
$IDstruttura = $_SESSION['IDstruttura'];

$dati = $_POST['dati'] ?? [];
$IDvendita = $dati['IDvendita'] ?? 0;
$IDoggetto = $dati['IDoggetto'] ?? 0;

$dati_vendita = reset(get_vendite([['IDvendita' => $IDvendita]])['dati']);

$oggetto = $dati_vendita['oggetti'][$IDoggetto];

$picker = '

<input type="hidden" value="' . $IDoggetto . '" id="IDoggetto">
<div class="nav navbar_picker_flex" >
 	<div onclick="chiudi_picker()"><i class="fas fa-times icona_chiudi" ></i></div>
 	<div  style="margin-top: 5px;  padding-right: 10px;"><button class="button_salva_preventivo" style="background:#d80404" onclick="
 	mod_negozio(5, ' . $IDvendita . ', ' . $IDoggetto . ', 10,()=>{chiudi_picker();carica_tab_vendite(' . $IDvendita . ',\'carrello\');})">Elimina</button> </div>
</div>


<div>
	<ul  uk-tab="connect: #switcher;animation: uk-animation-fade;swiping:false" class="no_before menu_uk_picker_icona"  >
			<li class="uk-active" onclick="carica_tab_oggetti(\'oggetto\')">
				<div style="padding:5px 0;"><i class="fas fa-spa"></i> <div style="font-size:12px">Dettagli</div> </div>
			</li>
	      ' . ($oggetto['tipo_riferimento'] == 7 ? '
	      	 <li onclick="carica_tab_oggetti(\'valore_voucher\')">
	      		 <div style="padding:5px 0;"><i class="fas fa-gift"></i> 	 <div style="font-size:12px">Componenti</div>  </div>
	      	 </li>
	      	' : '') . '

    </ul>
</div>

<div class="content content_picker_nav"  id="dettagli_tab_oggetto" style="padding-bottom:20px">


</div>';

echo $picker;
//  <li class="" onclick="carica_tab_oggetti(\'spedizione\')" ><div><i class="fas fa-truck-moving"></i></div></li>
?>
