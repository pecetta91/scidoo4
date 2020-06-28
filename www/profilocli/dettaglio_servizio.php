<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../config/connecti.php';
require_once __DIR__ . '/../../config/funzioni.php';
$IDprenotazione = $_SESSION['IDstrpren'];
$IDstruttura = get_IDstruttura_from_IDpren($IDprenotazione);
$lang = $_SESSION['lang'] ?: 0;

$arr_dati = (isset($_POST['arr_dati']) ? $_POST['arr_dati'] : []);
$IDservizio = $arr_dati['IDservizio'] ?? 0;
$time_selezionato = $arr_dati['data_modifica'] ?? 0;

$dettaglio_prenotazione = get_prenotazioni(['0' => ['IDprenotazione' => $IDprenotazione]], [], [], $IDstruttura)['dati'][$IDprenotazione];

$informazioni_tipo_servizio = get_informazioni_tiposervizio();

$foto = getfoto($IDservizio, 4);

$dati_serv = get_info_from_IDserv($IDservizio, null, $IDstruttura);
$durata = $dati_serv['durata'];
$richiedi_orario = $dati_serv['richiedi_orario'];

if ($foto == 'camera.jpg') {
	$foto = ($informazioni_tipo_servizio[$dati_serv['IDtipo']]['immagine'] != '' ? base_url() . '/img_template/' . $informazioni_tipo_servizio[$dati_serv['IDtipo']]['immagine'] : '');
} else {
	$foto = base_url() . '/immagini/big' . $foto;
}

$lista_ospiti = [];
$persone = get_elenco_persone_prenotazione($IDprenotazione, $IDstruttura);
if (!empty($persone)) {
	foreach ($persone as $IDrestrizione) {
		if (isset($lista_ospiti[$IDrestrizione])) {
			$lista_ospiti[$IDrestrizione] += 1;
		} else {
			$lista_ospiti[$IDrestrizione] = 1;
		}
	}
}

$dettaglio_prezzo = visualizza_prezzo_servizio($IDservizio, $dettaglio_prenotazione['checkin'], $IDstruttura, $IDprenotazione, 0);
if (($dati_serv['IDtipo'] != 10) && (!isset($dati_serv['tipoadd']))) {
	$pulsante = '
	<div style="    width: 100%;  position: fixed;  bottom: 0;   left: 0;">
		<button style="    background: #2574ec!important;  border: none;  width: 100%; padding: 15px;   color: #fff;  font-size: 20px;  outline: none;" onclick="aggiungi_servizio_orari(' . $IDservizio . ')">' . traduci('Prenota Ora', $lang) . '</button>
	</div>';
} else {

	$pulsante = '
<div style="    width: 100%;  position: fixed;  bottom: 0;   left: 0;">
		<button style="    background: #2574ec!important;  border: none;  width: 100%; padding: 15px;   color: #fff;  font-size: 20px;  outline: none;" onclick="aggiungi_prodotto_ospite(' . $IDservizio . ')">' . traduci('Prenota Ora', $lang) . '</button>
</div>';
}

$lista_utilizzo = stato_utilizzo_servizio($IDstruttura, $dati_serv, $dettaglio_prenotazione['checkin'], $dettaglio_prenotazione['checkout'], $IDprenotazione, 0);

$time_servizio_fascia = get_fascie_orario_servizio($IDservizio, $IDstruttura);

for ($tt = time0($dettaglio_prenotazione['checkin']); $tt <= time0($dettaglio_prenotazione['checkout']); $tt += 86400) {
	if (time() > $tt) {continue;}
	if ($time_selezionato == 0) {
		$time_selezionato = $tt;
	}

	$presenza = 0;
	if (isset($lista_utilizzo[$tt])) {
		foreach ($lista_utilizzo[$tt] as $dati) {
			if (!empty($dati['persone'])) {
				foreach ($dati['persone'] as $valore) {
					if ($valore > 0) {
						$presenza = 1;
						break;
					}
				}
			}
			if ($presenza) {break;}
		}
	}

	if ($presenza) {continue;}

	$lista_giorni[$tt] = dataita($tt);
}

$time_select = '';
if (!empty($time_servizio_fascia)) {
	$time_option = '<li onclick="esegui_funzione_select(this);" value="' . $time_selezionato . '"> -- <span class="uk-align-right" uk-icon="check" style="color:#2641da"></span></li>';

	$servizi_presenti = get_orari_servizi_presenti($IDservizio, $tt, $IDstruttura);

	$time_disponibili = personale_disponibile_servizio($IDstruttura, $IDservizio, $time_selezionato, $IDprenotazione, 0);
	if (!empty($time_disponibili)) {
		$numero_time = count($time_disponibili);
		foreach ($time_disponibili as $time_dispo => $dati) {
			$selezionato = '';
			$time_giorno = $time_selezionato + $time_dispo;

			if (!empty($time_servizio_fascia)) {

				if (!isset($time_servizio_fascia[$time_dispo])) {continue;}

				if ($time_servizio_fascia[$time_dispo] == 1) {
					if (!empty($servizi_presenti[$time_giorno])) {continue;}
				}

			} else {
				$minuti = date('i', $time_giorno);
				if (($minuti == 15) || ($minuti == 45)) {continue;}
				if ($numero_time > 10) {if ($minuti == 30) {continue;}}
			}

			$time_option .= '<li onclick="esegui_funzione_select(this);" value="' . $time_giorno . '">' . date('H:i', $time_giorno) . ' - ' . date('H:i', ($time_giorno + ($durata * 60))) . ' </li>';
			// data-sala="' . $dati['IDsala'] . '"  data-personale="' . $dati['IDpersonale'] . '"
		}
	}

	$time_select = '<div id="time_servizio" style="display:none;">
<ul class="uk-list uk-list-divider uk-picker-bot" style="padding:5px 20px;" onchange="(r,s)=>{cambia_valore_html_select(\'time_serv\',r,s);}">' . $time_option . '</ul></div>

<div class="div_list_uk uk_grid_div " uk-grid onclick="carica_content_picker($(\'#time_servizio\'))" >
<div class="uk-width-1-3 lista_grid_nome uk-first-column">' . traduci('Orario', $lang) . '</div>
<div class="uk-width-expand uk-text-right lista_grid_right chevron_right_after" data-name="oggetto_ricerca" value="' . $time_selezionato . '" data-select="' . $time_selezionato . '"  id="time_serv"   >   --   	 </div>
</div>';
}

$informazioni_prenota = '


	<input type="hidden" value="' . $time_selezionato . '" id="giorno_selezionato">
	<div id="giorni_servizio" style="display:none;"><ul class="uk-list uk-list-divider uk-picker-bot" style="padding:5px 20px;"
			onchange="(r)=>{navigation_ospite(21,{IDservizio:' . $IDservizio . ',data_modifica:r})}">' . genera_select_uikit($lista_giorni, $time_selezionato) . '</ul>
	</div>
	<div class="div_list_uk uk_grid_div " uk-grid  onclick="carica_content_picker(' . "'giorni_servizio'" . ')" >
	    <div class="uk-width-1-3 lista_grid_nome uk-first-column">' . traduci('Data', $lang) . '</div>
	    <div class="uk-width-expand uk-text-right lista_grid_right"> ' . dataita($time_selezionato) . '   <i class="fas fa-chevron-right"></i>	 </div>
	</div>';
$txt_restrizione = '';
if ($dati_serv['IDtipo'] != 10) {

	$lista_restrizioni = get_restrizioni($IDstruttura)['lista_restrizioni'];
	foreach ($lista_restrizioni as $IDrestrizione => $val) {
		$txt_restrizione .= '<input type="hidden"  class="restrizioni_input" data-id="' . $IDrestrizione . '"  data-nome="' . $val['restrizione'] . '" value="' . (isset($lista_ospiti[$IDrestrizione]) ? $lista_ospiti[$IDrestrizione] : 0) . '">';
	}

	$informazioni_prenota .= '
		<div class="div_list_uk uk_grid_div" uk-grid  onclick="crea_popup_script()" >
		    <div class="uk-width-1-3 lista_grid_nome uk-first-column">' . traduci('Persone', $lang) . '</div>
		    <div class="uk-width-expand uk-text-right lista_grid_right"> <span id="numero_persone"> ' . count($persone) . '</span> <i class="fas fa-users"></i>	 </div>
		</div>';
} else {
	$informazioni_prenota .= '
	<div class="div_list_uk uk_grid_div " uk-grid>
		<div class="uk-width-1-2 uk-text-truncate lista_grid_nome"  >' . traduci('Quantita', $lang) . '</div>
				<div class="uk-width-expand uk-text-right lista_grid_right ">
				<div class="stepper  stepper-init stepperrestr">
					<div class="stepper-button-minus" style="color:#0075ff;border:none"  onclick="selezionainfo(\'quantita_serv\',2,0)"  ><i class="fas fa-minus"></i></div>
					<div class="stepper-value  inputrestr" min="1" id="quantita_serv"   max="50" style="border-bottom:1px solid #d6d6d6"> 1 </div>
					<div class="stepper-button-plus" style="color:#0075ff;border:none" onclick="selezionainfo(\'quantita_serv\',1,0)"  ><i class="fas fa-plus"></i></div>
				</div>
			</div>
	</div>';
}

$informazioni_prenota .= $time_select;

$testo = $txt_restrizione . '


<input type="hidden" id="richiedi_orario" value="' . $richiedi_orario . '">
<div style="margin:10px 5px;padding-bottom:60px">



	<div  style="height:200px;background-image:url(' . $foto . ');background-size:cover;background-position:center;background-repeat:no-repeat"></div>


	<div class="div_uk_divider_list" style="margin-top:0px !important;">' . traduci('Prenota Subito', $lang) . '
		<div style="float:right">' . ($dettaglio_prezzo['prezzo'] ? format_number($dettaglio_prezzo['prezzo']) . ' €  ' . $dettaglio_prezzo['tipo_calcolo'] : '') . '</div>
	</div>
	 ' . $informazioni_prenota . '


	 <div class="div_uk_divider_list" style="margin-top:45px !important;">' . traduci('Informazioni Servizio', $lang) . '</div>
	 <div style="margin:5px">' . strip_tags(traducis('', $IDservizio, 2, $lang)) . '</div>
</div>

 ' . $pulsante;

echo $testo;

/*

<div style="margin-top:10px">' . strip_tags(traducis('', $IDservizio, 2, $lang)) . '</div>
 */
?>

<script>


function aggiungi_servizio_orari(IDservizio){

	var richiedi_orario=parseInt($('#richiedi_orario').val());
	var lista={};

	lista['persone']={};
	if($('.restrizioni_input').length>0){
		$('.restrizioni_input').each(function(){

			var IDrestrizione=$(this).data('id');
			var numero=$(this).val();

			if(numero>0){
				lista['persone'][IDrestrizione]=numero;
			}

		});
	}

	var giorno=$('#giorno_selezionato').val();
	lista['giorno']=giorno;
	var time_specifico=0;
	if($('#time_serv').length>0){
		time_specifico=$('#time_serv').val()
		lista['time_specifico']=time_specifico;
	}

	if(lista['persone'].length==0){
		 apri_notifica({'messaggio':"E' Necessario selezionare le persone per prenotare il servizio.",'status':'danger'});
	}else{
		history_navigation.splice(history_navigation.length-1)
		mod_ospite(25,IDservizio,lista,10,()=>{navigation_ospite(12,0)},1);
	}



}


function aggiungi_prodotto_ospite(IDservizio){
	history_navigation.splice(history_navigation.length-1)
	var lista={};
	var giorno=$('#giorno_selezionato').val();
	lista['giorno']=giorno;

	var time_specifico=0;

	if($('#time_serv').length>0){
		time_specifico=$('#time_serv').val()
		lista['time_specifico']=time_specifico;
	}

	var quantita=parseInt($('#quantita_serv').html());

	lista['quantita']=quantita;

	mod_ospite(25,IDservizio,lista,10,()=>{navigation_ospite(1,0,()=>{switch_tab_prenotazione_ospite(2)})},1);
}

function crea_popup_script(el){

	var restrizioni='';
	$('.restrizioni_input').each(function(){
		var nome=$(this).data('nome');
		var IDrestrizione=$(this).data('id');
		var valore=$(this).val();
		restrizioni+=`
		<div class="div_list_uk uk_grid_div" uk-grid>
		    <div class="uk-width-1-2 lista_grid_nome uk-first-column"> `+nome+`</div>
    		<div class="uk-width-expand uk-text-right lista_grid_right ">
	   			<div class="stepper  stepper-init stepperrestr">
    				<div class="stepper-button-minus" style="color:#0075ff;border:none"  onclick="selezionainfo(\'restr`+IDrestrizione+`\',2,0)"  ><i class="fas fa-minus"></i></div>
				   <div class="stepper-value  restrizione" min="0"  data-id="`+IDrestrizione+`"   id="restr`+IDrestrizione+`"    max="99" style="border-bottom:1px solid #d6d6d6" >`+valore+`     </div>
				   <div class="stepper-button-plus" style="color:#0075ff;border:none" onclick="selezionainfo(\'restr`+IDrestrizione+`\',1,0)"  ><i class="fas fa-plus"></i></div>
				 </div>
		    </div>
		</div>`;
	});

		var html=`

		<div class="nav navbar_picker_flex" >
		 	<div onclick="chiudi_picker()"><i class="fas fa-times icona_chiudi" ></i></div>
		 	<div style="margin-top:5px;padding-right:10px"> </div>
		</div>


		<div class="content picker" style="margin-top:0;padding-top:5px;">
				`+restrizioni+`

		</div>`;


	    var IDpicker=crea_picker(()=>{},{'height':'50%'});
        $('#'+IDpicker+'.stampa_contenuto_picker').html(html);

	$('.restrizione').on('change',function() {
		let numero_persone=$(this).html();
		let IDrestrizione=$(this).data('id');

		$('.restrizioni_input[data-id="'+IDrestrizione+'"]').val(numero_persone);


		modifica_restrizione();
	});


}

function modifica_restrizione(){
	var numero_persone=0;
	$('.restrizioni_input').each(function(){
		numero_persone+=parseInt($(this).val());
	})

	$('#numero_persone').html(numero_persone);
}

</script>
