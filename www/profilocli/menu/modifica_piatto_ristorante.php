<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';
$IDprenotazione = $_SESSION['IDstrpren'];
$IDstruttura = get_IDstruttura_from_IDpren($IDprenotazione);

$IDaddebito = $_POST['IDaddebito'];

$lang = $_SESSION['lang'] ?: 0;

$dati_prenotazione = get_prenotazioni(['0' => ['IDprenotazione' => $IDprenotazione]], null, [], $IDstruttura);
$dettaglio_prenotazione = $dati_prenotazione['dati'][$IDprenotazione];

$dati_struttura = genera_sotto_strutture_lista($IDstruttura)[0];

$lista_servizi = get_dati_conto_riferimento($IDprenotazione, 0, [], $IDstruttura)['arr_servizi'];

$menu = array_filter($lista_servizi, function ($arg) use ($IDaddebito) {return $arg['IDaddebito'] == $IDaddebito;})[$IDaddebito];

$IDservizio = $menu['IDserv'];
$IDsottotip = $menu['IDsottotip'];
$time = time0($menu['time']);

//print_html($menu);

$IDaddebito_collegato = $menu['componenti'][0]['IDaddebito_collegato'];

$query = "SELECT IDvariazione FROM limitivariazioni WHERE IDobj=$IDservizio AND tipoobj=2 AND IDstruttura=$IDstruttura";
$result = $link2->query($query);
$variazioni = array_column($result->fetch_all(), 0);

$_SESSION['variazioni_ristorante_be'] = [];
$variazioni_txt = ['positive' => '', 'negative' => ''];
if (!empty($variazioni)) {
	$query = "SELECT ID,prezzo,dato1 FROM  servizi WHERE ID IN (" . implode(',', $variazioni) . ")  AND IDstruttura=$IDstruttura ";
	$result = $link2->query($query);
	while ($row = $result->fetch_row()) {
		$IDvariazione = $row[0];
		$plus_variazione = $row['1'];
		$minus_variazione = $row['2'];

		if ($plus_variazione) {
			$variazioni_txt['positive'] .= '
			<div style="margin:5px 10px">
 		 		 <label><input class="uk-radio variazione" type="checkbox" data-modi="1" data-id="' . $IDvariazione . '" >  ' . traducis('', $IDvariazione, 1, $lang) . ' (  + ' . format_number($plus_variazione) . ' €)</label>
			</div> ';
		}

		if ($minus_variazione) {
			$variazioni_txt['negative'] .= '
			<div style="margin:5px 10px">
 		 		 <label><input class="uk-radio variazione" type="checkbox" data-modi="0" data-id="' . $IDvariazione . '" >  ' . traducis('', $IDvariazione, 1, $lang) . ' (  - ' . format_number($minus_variazione) . ' €)</label>
			</div> ';
		}

	}
}

$testo = '
<div class="nav navbar_picker_flex" >
 	<div onclick="chiudi_picker()"><i class="fas fa-times icona_chiudi" ></i></div>
 	<div style="margin-top:5px;padding-right:10px"> ' . traducis('', $IDservizio, 1, $lang) . '

 	</div>
</div>


<div class="content" style="margin-top:0;height: calc(100% - 50px);">
	<div  style="padding-top:5px;">

			<div style="padding:5px 10px;margin-top:10px" class="tab_content_tasto_menu">
				<div class="tasto_menu_default " onclick="switch_tab_menu(this)" data-tabid="1"> ' . traduci('Aggiungi', $lang) . '</div>
				<div class="tasto_menu_default " onclick="switch_tab_menu(this)" data-tabid="2"> ' . traduci('Togli', $lang) . '</div>
			</div>


			<div class="div_elenco_tab" style="margin-top:10px">
					<div data-tabid="1" class="content_tab_menu" style="display:none">' . $variazioni_txt['positive'] . '</div>
					<div data-tabid="2" class="content_tab_menu" style="display:none">' . $variazioni_txt['negative'] . '</div>
			</div>




	</div>

	<div style="    width: 100%;  position: fixed;  bottom: 0;   left: 0;">
		<button style="    background: #2574ec!important;  border: none;  width: 100%; padding: 15px;   color: #fff;  font-size: 20px;  outline: none;" onclick="salva_variazione_menu(' . $IDaddebito_collegato . ')">' . traduci('Aggiungi', $lang) . '</button>
	</div>


</div>';

echo $testo;
?>

<script>

	function salva_variazione_menu(IDaddebito){

		var variazioni={};
		$('.variazione').each(function(){
			if($(this).is(':checked')){
				var IDvariazione=$(this).data('id');
				var modi=$(this).data('modi');
				variazioni[IDvariazione]=modi;
			}
		});

		if(variazioni){
			var IDaddebito_selezionato=$('#IDaddebito_selezionato').val();
			mod_ospite(34,IDaddebito,variazioni,10,()=>{chiudi_picker();stampa_menu_addebito_web_app(IDaddebito_selezionato,1) });
		}
	}




	switch_tab_content_menu('.tab_content_tasto_menu .tasto_menu_default','.div_elenco_tab .content_tab_menu');
</script>