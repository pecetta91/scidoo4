<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';
$IDutente = $_SESSION['ID'];
$IDstruttura = $_SESSION['IDstruttura'];

$IDvendita = $_POST['IDvendita'] ?? 0;
$dati_vendita = reset(get_vendite([['IDvendita' => $IDvendita]])['dati']);

if (!$dati_vendita['IDcliente']) {
	$html_ospite = '
	<div style="margin-bottom:20px;">

		<div class="div_uk_divider_list" style="margin-top:5px !important"> Ospite</div>

	    <div class="uk-inline" style="width:100%;">
	        <span class="uk-form-icon" uk-icon="icon: search;ratio:0.8"></span>
	       <input class="uk-input ricerca_prenotante_preventivo" id="ricerca_intestazione" type="text" autocomplete="off" placeholder="Ricerca Cliente o Inserisci Nuovo" data-url="' . base_url() . '/config/searchbox/prenotante.php">
    	</div>

	</div>


		<script>
			$("#ricerca_intestazione").searchBox({

				onclick:function (args){
					mod_negozio(1, ' . $IDvendita . ', [args.id,args.tipo], 10,()=>{carica_tab_vendite(' . $IDvendita . ',\'carrello\');})
				},

			});
		</script>';
/*

prepend: [
{
text: "<u>Nuova Azienda:</u> ",
onclick: function(txt) {

intestazione({IDintestazione:0,IDobj:' . $IDvendita . ',tipo:13,nome_azienda:txt},()=>{
dettaglio_vendita(' . $IDvendita . ',\'carrello\')
},()=>{
rimuovi_azienda_popup();
mod_negozio(1, ' . $IDvendita . ', [0,0], 10,()=>{carica_tab_vendite(' . $IDvendita . ',\'carrello\');});
});
}
},
{
text: "<u>Nuova Scheda Ospite:</u> ",
onclick: function(txt) {
dettaglio_cliente({IDcliente:0,IDobj:' . $IDvendita . ',tipo:11,nome_cliente:txt},
()=>{dettaglio_vendita(' . $IDvendita . ',\'carrello\');},
()=>{
rimuovi_cliente_popup();
mod_negozio(1, ' . $IDvendita . ', [0,0], 10,()=>{carica_tab_vendite(' . $IDvendita . ',\'carrello\');});
}
);
}
}
]
 */
} else {

	$html_ospite = '

	<div class="div_uk_divider_list" style="margin-top:5px !important">Dati Ospite

	<button style=" float:right ;background: #e4e4e4; border: none;  color: #333;   border-radius: 3px;   padding: 5px 10px;   font-size: 15px;"
	onclick="mod_negozio(1, ' . $IDvendita . ', [0,0], 10,()=>{carica_tab_vendite(' . $IDvendita . ',\'carrello\');})">Cambia Ospite</button>

</div>

	<div uk-grid class="uk-margin-small uk_grid_div div_list_uk">
		<div class="uk-width-expand lista_grid_nome uk-first-column">Nome</div>
		<div class="uk-width-expand uk-text-right lista_grid_right">
			 ' . $dati_vendita['nome_cliente'] . '
		</div>
	</div>




	<div uk-grid class="uk-margin-small uk_grid_div div_list_uk">
		<div class="uk-width-expand lista_grid_nome uk-first-column">Email</div>
		<div class="uk-width-expand uk-text-right lista_grid_right">  ' . $dati_vendita['email'] . '</div>
	</div>

	<div uk-grid class="uk-margin-small uk_grid_div div_list_uk">
		<div class="uk-width-expand lista_grid_nome uk-first-column">Cellulare</div>
		<div class="uk-width-expand uk-text-right lista_grid_right">
			   ' . $dati_vendita['telefono'] . '
		</div>
	</div>  ';
}

$elementi_carrello = '';
foreach ($dati_vendita['oggetti'] as $IDoggetto => $info) {

	if ($info['IDriferimento'] == 0) {
		$testo_oggetto = ($info['tipo_riferimento'] == 7 ? 'Voucher' : 'Prodotto');
	} else {
		$testo_oggetto = $info['nome_oggetto'];
	}
	if ($testo_oggetto == '') {
		$testo_oggetto = ($info['tipo_riferimento'] == 7 ? 'Voucher' : 'Prodotto');
	}

	$elementi_carrello .= '
		<div class="div_list_uk uk_grid_div  " uk-grid onclick="modifica_oggetti_vendita({IDvendita:' . $IDvendita . ',IDoggetto:' . $IDoggetto . '})">
		    <div class="uk-width-1-3 lista_grid_nome uk-first-column">' . $testo_oggetto . '</div>
		    <div class="uk-width-expand uk-text-right lista_grid_right">' . format_number($info['totale']) . ' € <span uk-icon="chevron-right" class="uk-icon"></span></div>
		</div>

	';
}

$testo = $html_ospite . '


	<div id="tipo_vendita" style="display:none;">
	<ul class="uk-list uk-list-divider uk-picker-bot" style="padding:5px 20px;" onchange="(r)=>{aggiungi_elemento_vendita(' . $IDvendita . ',r);chiudi_picker();}">
		' . genera_select_uikit(['7' => 'Voucher', '13' => 'Prodotto'], []) . '</ul>
	</div>



	<div id="stato_vendita" style="display:none;" data-titolo="stato">
		<ul class="uk-list uk-list-divider uk-picker-bot " style="padding:5px 20px;" onchange="(r)=>{mod_negozio(21, ' . $IDvendita . ', r, 10);  carica_tab_vendite(' . $IDvendita . ',\'carrello\');}">
		' . genera_select_uikit(genera_stati_vendita(), $dati_vendita['stato']) . '
		</ul>
	</div>

	<div class="div_list_uk uk_grid_div  " style="margin: 10px 0 !important;" uk-grid onclick="carica_content_picker(' . "'stato_vendita'" . ')">
	    <div class="uk-width-1-3 lista_grid_nome uk-first-column">Stato</div>
	    <div class="uk-width-expand uk-text-right lista_grid_right">' . $dati_vendita['stato_testuale'] . ' <span uk-icon="chevron-right" class="uk-icon"></span></div>
	</div>




	<div class="div_uk_divider_list" style="margin-top:5px !important"> Carrello

	<button style="float:right;border: none;  border-radius: 3px;  background: #2a6eff;   padding: 5px 10px;     color: #fff;   font-size: 14px; font-weight: 600;   text-transform: uppercase;" onclick="carica_content_picker(' . "'tipo_vendita'" . ')"><i class="fas fa-plus"></i> Aggiungi</button>
	</div>

	' . $elementi_carrello . '

';

echo $testo;
?>
