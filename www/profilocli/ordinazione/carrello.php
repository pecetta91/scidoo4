<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';
$IDprenotazione = $_SESSION['IDstrpren'];
$IDstruttura = get_IDstruttura_from_IDpren($IDprenotazione);

$lang = $_SESSION['lang'] ?: 0;

$dati_prenotazione = get_prenotazioni(['0' => ['IDprenotazione' => $IDprenotazione]], null, [], $IDstruttura)['dati'][$IDprenotazione];

$pulsante = '';
$testo_carrello = '';
if (!empty($_SESSION['ordinazione_webapp'][$IDprenotazione]['servizi'])) {
	$ordinazione = $_SESSION['ordinazione_webapp'][$IDprenotazione]['servizi'];
	foreach ($ordinazione as $dati) {

		$dettaglio_prezzo = visualizza_prezzo_servizio($dati['ID'], $dati_prenotazione['checkin'], $IDstruttura, $IDprenotazione, 0);
		$totale = $dati['quantita'] * $dettaglio_prezzo['prezzo'];

		$nome_variazione = [];
		if (!empty($dati['variazioni'])) {
			foreach ($dati['variazioni'] as $IDvariazione => $modi) {
				if (!empty($modi)) {
					$nome_variazione[] = strtolower(traducis('', $IDvariazione, 1, $lang));
				}
			}
		}

		$testo_carrello .= '
		<div class=" uk_grid_div div_list_uk" uk-grid   onclick="mostra_dettagli_servizio_ordianazione(' . $dati['ID'] . ')">
				<div class="uk-width-auto lista_grid_numero uk-first-column numero_servizi_conto" style="    margin: auto;">' . $dati['quantita'] . '</div>

			    <div class="uk-width-1-2 uk-text-truncate lista_grid_nome uk-first-column nome_servizi_conto">' . traducis('', $dati['ID'], 1, $lang) . '
			    ' . (!empty($nome_variazione) ? '<div style="font-size:11px">' . implode(', ', $nome_variazione) . '</div>' : '') . '
			    </div>
			     <div class="uk-width-expand uk-text-right lista_grid_right" > ' . ($totale ? format_number($totale) . ' €' : '') . '  <i class="fas fa-chevron-right"></i> </div>
			</div>  	';

	}

	$orario = (isset($_SESSION['ordinazione_webapp'][$IDprenotazione]['orario']) ? $_SESSION['ordinazione_webapp'][$IDprenotazione]['orario'] : 0);
	$testo_carrello .= '

	 <div id="orario" style="display:none;">
		<ul class="uk-list uk-list-divider uk-picker-bot" style="padding:5px 20px;" onchange="(r,s)=>{cambia_valore_html_select(\'select_orario\',r,s);mod_ospite(35,0,r,10) }">
		' . genera_select_uikit(genera_ora_uikit(date('G', time()), 23, 1800), $orario) . '
		</ul>
	</div>



   		<div class="uk-accordion-content" style="margin:0">
			<div class=" uk_grid_div div_list_uk" uk-grid  onclick="carica_content_picker($(\'#orario\'))" >
			    <div class="uk-width-auto lista_grid_nome">' . traduci('Orario', $lang) . '</div>
				<div class="uk-width-expand uk-text-right lista_grid_right c000 chevron_right_after"   data-select="' . $orario . '"  id="select_orario" > ' . ($orario != 0 ? gmdate('H:i', $orario) : '') . '  </div>
			</div>
   		</div>

	';

	$pulsante = '<div style="    width: 100%;  position: fixed;  bottom: 0;   left: 0;">
		<button style="    background: #2574ec!important;  border: none;  width: 100%; padding: 15px;   color: #fff;  font-size: 20px;  outline: none;"

		onclick="conferma_carrello()"
		 >' . traduci('Conferma', $lang) . ' </button>
	</div>';

} else {
	$testo_carrello = traduci('Nessun Servizio Selezionato', $lang);
}

$testo = '
<div class="nav navbar_picker_flex" >
 	<div onclick="chiudi_picker()"><i class="fas fa-times icona_chiudi" ></i></div>
 	<div style="margin-top:5px;padding-right:10px">  ' . traduci('Carrello', $lang) . '

 	</div>
</div>



<div class="content" style="margin-top:0;height: calc(100% - 50px);">
	<div  style="padding-top:5px;"> ' . $testo_carrello . ' </div>

' . $pulsante . '

</div>';

echo $testo;

?>


<script>

function mostra_dettagli_servizio_ordianazione(IDservizio){

    var btn='';

    btn+='<li onclick="chiudi_picker();visualizza_dettaglio_servizio_ordinazione('+IDservizio+')"> Dettagli  </li> ';
    btn+=`<li onclick="chiudi_picker();mod_ospite(30,`+IDservizio+`,0,10,()=>{chiudi_picker();apri_carrello_ordinazione_web_app()})" style="color:#d80404">Elimina</li>`;

  picker_modal_action(btn);
}

function conferma_carrello(){
	var time=$('#select_orario').data('select');

	if(time>0){

		mod_ospite(31,0,0,10,()=>{chiudi_picker();navigation_ospite(1,0,()=>{switch_tab_prenotazione_ospite(2) } )});
	}
}

function visualizza_dettaglio_servizio_ordinazione(IDservizio){

    $.ajax({
        url: baseurl+'app_uikit/profilocli/ordinazione/dettaglio_servizio_ordinazione.php',
        method: 'POST',
        dataType: 'text',
        cache: false,
        timeout: 5000,
        data: { IDservizio:IDservizio},
        error: function(html) {
           loader();
        },

        success: function(html) {
           loader();
           var IDpicker=crea_picker(()=>{modifica_carrello()},{'height':'80%'});
           $('#'+IDpicker+'.stampa_contenuto_picker').html(html);


        }
  });
}

function modifica_carrello(){
	  chiudi_picker();

	 	aggiorna_carrello_ordinazione_web_app();
	 	apri_carrello_ordinazione_web_app();


}
</script>