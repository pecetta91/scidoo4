<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';

$IDutente = $_SESSION['ID'];
$IDstruttura = $_SESSION['IDstruttura'];

$IDpreventivo = $_POST['IDpreventivo'];

$preventivo = get_preventivi([['ID' => $IDpreventivo]], $IDstruttura)[$IDpreventivo];

$html_ospite = '';
$html_prenotante = '';

if (!$preventivo['IDcliente']) {

	$html_ospite = '
	<div style="margin-bottom:20px;">

		<div>Ospite</div>

	    <div class="uk-inline" style="width:100%;">
	        <span class="uk-form-icon" uk-icon="icon: search;ratio:0.8"></span>
	        <input class="uk-input  ricerca_cliente_preventivo" id="ricerca_cliente" type="text" placeholder="Ricerca Cliente"   type="text" autocomplete="off" data-url="' . base_url() . '/config/searchbox/schedine.php" />
    	</div>

	</div>

	<script>
			$("#ricerca_cliente").searchBox({
				onclick:function (args){
					 inscerisci_cliente_preventivo(args.id);
				 },

	 			prepend:
	 			[ 		{
						text: "Nuova Cliente: ",
							onclick: function(txt) { nuovo_cliente_preventivo(txt); }
						}
				]
			});
			</script>';

} else {

	$IDcliente = $preventivo['IDcliente'];
	$ospite = estrai_dati_ospiti([['IDcliente' => $IDcliente]], [], $IDstruttura)['dati'][$IDcliente];

	$html_ospite = '

	<div class="div_uk_divider_list" style="margin-top:5px !important">Dati Ospite

	<button style=" float:right ;background: #e4e4e4; border: none;  color: #333;   border-radius: 3px;   padding: 5px 10px;   font-size: 15px;" onclick="inscerisci_cliente_preventivo(0)">Cambia Ospite</button>

</div>

	<div uk-grid class="uk-margin-small uk_grid_div div_list_uk">
		<div class="uk-width-expand lista_grid_nome uk-first-column">Nome</div>
		<div class="uk-width-expand uk-text-right lista_grid_right">
			   <input class="uk-input input_cli  " type="text" value="' . $ospite['nome'] . '"  onchange="mod_anagrafiche(67,' . $IDcliente . ',this,11); "   placeholder="Nome">
		</div>
	</div>


	<div uk-grid class="uk-margin-small uk_grid_div div_list_uk">
		<div class="uk-width-expand lista_grid_nome uk-first-column">Cognome</div>
		<div class="uk-width-expand uk-text-right lista_grid_right">
			   <input class="uk-input input_cli  " type="text" value="' . $ospite['cognome'] . '"  onchange="mod_anagrafiche(68,' . $IDcliente . ',this,11)"  placeholder="Cognome">
		</div>
	</div>


	<div uk-grid class="uk-margin-small uk_grid_div div_list_uk">
		<div class="uk-width-expand lista_grid_nome uk-first-column">Email</div>
		<div class="uk-width-expand uk-text-right lista_grid_right">
			   <input class="uk-input input_cli  " type="text" value="' . $ospite['email'] . '"  onchange="mod_anagrafiche(69,' . $IDcliente . ',this,11)"  placeholder="Email">
		</div>
	</div>


	<div id="prefisso_cellulare" style="display:none;">
		<ul class="uk-list uk-list-divider uk-picker-bot" style="padding:5px 20px;" onchange="(r)=>{
			mod_anagrafiche(70,' . $IDcliente . ',r,10,()=>{chiudi_picker();modifica_anagrafica_nuovo_preventivo(' . $IDpreventivo . ')}) ;chiudi_picker()}">
				' . genera_select_uikit(generaprefisso_uikit(), $ospite['prefisso_cell']) . '
		</ul>
	</div>

	<div uk-grid class="uk-margin-small uk_grid_div div_list_uk">
		<div class="uk-width-1-3   lista_grid_nome  ">Cellulare</div>
		<div class="uk-width-1-4"  onclick="carica_content_picker(' . "'prefisso_cellulare'" . ')">
			<div class="uk-inline">
					<span class="uk-form-icon uk-form-icon-flip uk-icon uk_picker-select" uk-icon="icon:triangle-down;ratio:.7"></span>
				<div class="uk-input input_cli   uk_picker_select"> + ' . $ospite['prefisso_cell'] . '</div>
			</div>
		</div>
		<div class="uk-width-expand uk-text-right lista_grid_right">
			   <input class="uk-input input_cli  " type="number"   value="' . $ospite['cellulare'] . '"  onchange="	mod_anagrafiche(71,' . $IDcliente . ',this,11)" placeholder="Cellulare" >
		</div>
	</div> ';
}

$prenotante = $link2->query("SELECT a.IDintestazione,a.tipointestazione FROM agenziepren AS ap JOIN agenzie AS a ON a.ID=ap.IDagenzia WHERE ap.IDobj='$IDpreventivo' AND ap.tipoobj=1 LIMIT 1")->fetch_row();
if (!$prenotante) {
	$html_prenotante = '
	<div style="margin-top:20px;">

		<div>Prenotante</div>

	    <div class="uk-inline" style="width:100%;">
	        <span class="uk-form-icon" uk-icon="icon: search;ratio:0.8"></span>
	        <input class="uk-input  ricerca_prenotante_preventivo" id="ricerca_prenotate"  placeholder="Ricerca Cliente / Intestazione" onKeyUp=""
	         type="text" autocomplete="off" data-url="' . base_url() . '/config/searchbox/prenotante.php"/>
    	</div>

	</div>

		<script>
			$("#ricerca_prenotate").searchBox({
				onclick:function (args){
					 prev_set_prenotante(args.id,args.tipo);
				 },

			});
			</script>
	';
} else {
	$html_prenotante = '

		<div class="div_uk_divider_list" style="margin-top:35px !important">Dati Prenotante

			<button style=" float:right ;background: #e4e4e4; border: none;  color: #333;   border-radius: 3px;   padding: 5px 10px;   font-size: 15px;" onclick="prev_set_prenotante(0,0)">Cambia Prenotante</button>
		</div>


			<div uk-grid class="uk-margin-small uk_grid_div div_list_uk">
		<div class="uk-width-expand lista_grid_nome uk-first-column">Nome</div>
		<div class="uk-width-expand uk-text-right lista_grid_right">
			   <input class="uk-input input_cli  " type="text" value="' . getcliente(...$prenotante) . '"   readonly  placeholder="Nome">
		</div>
	</div> ';
}

$picker = '
<div class="nav navbar_picker_flex" >
 	<div onclick="chiudi_picker()"><i class="fas fa-times icona_chiudi" ></i></div>
 	<div style="margin-top:5px;margin-right:10px;"><button class="btn_avanti_preventivo" onclick="chiudi_picker();modifica_dettagli_nuovo_preventivo(' . $IDpreventivo . ')">Avanti</button></div>
</div>
<div class="content" style="padding:10px 5px;">
		' . $html_ospite . '

		' . $html_prenotante . '
</div>

';

echo $picker;
