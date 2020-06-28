<?php
//header('Access-Control-Allow-Origin: *');

$alloggi = get_alloggi($IDstruttura);
$categorie = get_categorie($IDstruttura);

$prezzo = $aggiunta->get_prezzi_servizio($IDservizio, 1);
$prezzo = reset($prezzo);
$prezzo = reset($prezzo)[0];
$totale = $prezzo * count($riferimento->IDriferimento);
$prezzo = float_format($prezzo, true);
$totale = float_format($totale, true);

$date = $riferimento->get_date();
$date_lookup = array_flip($opzioni['date'] ?? []);

$d = [min(array_column($date, 0)), max(array_column($date, 1))];
$disponibilita = disponibilita_servizio($IDservizio, $d[0], $d[1], ['IDobj' => $riferimento->IDriferimento, 'tipoobj' => $riferimento->tipo_riferimento]);
$disponibilita = $disponibilita['disponibilita'];

$quantita_attuale = $aggiunta->quantita_attuale($IDservizio);
$quantita_esistente = $aggiunta->quantita_esistente($IDservizio);
$informazioni = $riferimento->get_info();

$lista_servizi = '';
$prezzo_totale = 0;
foreach ($riferimento->IDriferimento as $ID) {
	$alloggio = $alloggi[$informazioni[$ID]['id_alloggio']]['alloggio'] ?? '';

	$intervallo = $date[$ID];
	$showdate = true;
	if (isset($date_lookup[0])) {
		$intervallo = [$intervallo[0], $intervallo[0]];
		$date_lookup[$intervallo[0]] = true;
		$showdate = false;
	}
	$carrello = '';

	for ($i = $intervallo[0]; $i <= $intervallo[1]; $i += 86400) {
		if (!isset($date_lookup[$i])) {continue;}
		$dispo = $disponibilita[$i][''][''] ?? [];
		$attuale = array_sum(array_map(function ($arg) {return count($arg['persone']);}, $quantita_attuale[$ID][$i] ?? []));
		$esistente = array_sum(array_map(function ($arg) {return count($arg['persone']);}, $quantita_esistente[$i] ?? []));
		$stato = ($dispo ? ($esistente + $attuale . ' di ' . ($dispo[1] != PHP_INT_MAX ? $dispo[1] : '&infin;')) : '');

		$carrello .= '
		<div data-id="' . $ID . '" data-time="' . $i . '" data-qta="1" data-prezzo="' . floatval($prezzo) . '" class="richieste_carrello servizio_da_aggiungere" onclick="crea_popup_script(this)">
	  		<div>' . ($showdate ? date('d/m/Y', $i) : '--') . ' </div>
			<div> Uni: € <span class="unitario">' . $prezzo . '</span> </div>

			<div>N. <span class="n_persone"> 1</span> </div>
			<div> € <span class="totale">' . $prezzo . '</span> </div>
		</div>';
		$prezzo_totale += floatval($prezzo);
	}

	$riga_informazioni = $informazioni[$ID]['nome_cliente'] . ' - ' . $alloggio;

	$lista_servizi .= '
	<div style="margin-bottom:10px">
		<div class="div_uk_divider_list" style="margin-top:5px !important;    margin-left: 0 !important;  margin-bottom: 10px !important;">' . $riga_informazioni . ' </div>

		<div style=" overflow-y: hidden;    overflow-x: auto;  white-space: nowrap; "> ' . $carrello . '</div>
	</div>';

}
?>

<input type="hidden" value=" <?=$prezzo_totale?>" class="totale_tipolim">

   <?=$lista_servizi?>



<script>
function crea_popup_script(el){

	var prezzo=parseFloat($(el).data('prezzo'));
	console.log($(el).data());

	var time=$(el).data('time');
	var id=$(el).data('id');
	var qta=parseInt($(el).data('qta'));

	var totale=qta*prezzo;
	var html=`

		<div class="nav navbar_picker_flex" >
		 	<div onclick="chiudi_picker()"><i class="fas fa-times icona_chiudi" ></i></div>
		 	<div style="margin-top:5px;padding-right:10px"> </div>
		</div>


		<div class="content picker" style="margin-top:0;padding-top:5px;">
			 	<div class="div_list_uk uk_grid_div" uk-grid>
				    <div class="uk-width-1-2 lista_grid_nome uk-first-column"> Quantita</div>
		    		<div class="uk-width-expand uk-text-right lista_grid_right ">
			   			<div class="stepper  stepper-init stepperrestr">
		    				<div class="stepper-button-minus" style="color:#0075ff;border:none"  onclick="selezionainfo(\'qta\',2,0)"  ><i class="fas fa-minus"></i></div>
						   <div class="stepper-value  restrizione" min="1"  data-id="qta"   id="qta"    max="99" style="border-bottom:1px solid #d6d6d6" >`+qta+`     </div>
						   <div class="stepper-button-plus" style="color:#0075ff;border:none" onclick="selezionainfo(\'qta\',1,0)"  ><i class="fas fa-plus"></i></div>
						 </div>
				    </div>
				</div>

				<div class="div_list_uk uk_grid_div unitario_pulsante" uk-grid>
				    <div class="uk-width-1-2 lista_grid_nome uk-first-column">Unitario</div>
				    <div class="uk-width-expand uk-text-right lista_grid_right c000"> € <span id="unitario">`+prezzo+`</span> <i class="fas fa-chevron-right"></i>  </div>
				</div>

				<div class="div_list_uk uk_grid_div variazione_pulsante" uk-grid>
				    <div class="uk-width-1-2 lista_grid_nome uk-first-column">Variazione</div>
				    <div class="uk-width-expand uk-text-right lista_grid_right c000"> % <i class="fas fa-chevron-right"></i>   </div>
				</div>
					<div class="div_list_uk uk_grid_div " uk-grid>
				    <div class="uk-width-1-2 lista_grid_nome uk-first-column">Totale</div>
				    <div class="uk-width-expand uk-text-right lista_grid_right c000"> € <span id="totale">`+totale+`</span></div>
				</div>

		</div>`;


	    var IDpicker=crea_picker(()=>{aggiorna_totale($(el))},{'height':'50%'});
        $('#'+IDpicker+'.stampa_contenuto_picker').html(html);
        valore_popup($(el),$('.picker'));

    	abilita_modal_unitario($(el));
    	abilita_modal_variazione($(el));
}


function abilita_modal_unitario(el){

    UIkit.util.on('.unitario_pulsante', 'click', function (e){

	var prezzo=parseFloat($(el).data('prezzo'));

      UIkit.modal.prompt('Modifica Prezzo:',prezzo).then(function (prezzo) {
        if(prezzo){
            if(!isNaN(prezzo)){

                prezzo= parseFloat(prezzo);
            	$('#unitario').html(prezzo);
            	$(el).removeData('prezzo');

				$(el).data('prezzo',prezzo);
				$(el).attr('data-prezzo',prezzo);

				aggiorna_totale_popup($(el));
            } else{
                  UIkit.modal.dialog('<p class="uk-modal-body">Inserire un numero. Prego riprovare!</p>');
            }
        }

       });
 });
}

function abilita_modal_variazione(el){

	var variazione='';
    UIkit.util.on('.variazione_pulsante', 'click', function (e){
	var prezzo=parseFloat($(el).data('prezzo'));

      UIkit.modal.prompt('Inserire Variazione:',variazione).then(function (variazione) {

        if(variazione){
            if(!isNaN(variazione)){
                variazione= parseFloat(variazione);

				prezzo += parseFloat(prezzo * variazione / 100);
				prezzo  = prezzo.toFixed(2);

            	$('#unitario').html(prezzo);
            	$(el).removeData('prezzo');
				$(el).data('prezzo',prezzo);
				$(el).attr('data-prezzo',prezzo);


				aggiorna_totale_popup($(el));

            } else{
                  UIkit.modal.dialog('<p class="uk-modal-body">Inserire un numero. Prego riprovare!</p>');
            }
        }

       });
 });
}

function valore_popup(el,popup){


	$(popup).find('#qta').on('change',function() {
		var n_servizio=parseInt($(this).html());
		$(el).data('qta',n_servizio);
		$(el).find('.n_persone').html(n_servizio);
		aggiorna_totale_popup($(el));
	});

}

function aggiorna_totale_popup(el){
	var qta=parseInt($('#qta').html());
	var prezzo=parseFloat($(el).data('prezzo'));
	$('#totale').html(qta*prezzo);
}

carica_totale_pulsante();

function aggiorna_totale(el){

	$(el).find('.unitario').html($(el).data('prezzo'));

	var totale=parseFloat($(el).data('prezzo')* parseInt($(el).data('qta')) );
	$(el).find('.totale').html(totale);

	aggiorna_totale_elemento();
}

function aggiorna_totale_elemento(){
	let totale=0;
	$('.servizio_da_aggiungere').each(function (){

		totale += parseFloat($(this).data('prezzo') * $(this).data('qta'));
	});

	$('.totale_tipolim').val(totale);
	carica_totale_pulsante();
}


function carica_totale_pulsante(){
	var value=$('.totale_tipolim').val();
	$('#totale_vendita').html('€ '+value);
}


$('#aggiungi-servizio').off("click").on('click', function() {
	var list = {};

	$('.servizio_da_aggiungere').each(function() {
		let ref = $(this).data('id');
		let time = $(this).data('time');
		let qta =$(this).data('qta');
		let unitario = parseFloat($(this).data('prezzo'));

		if (!list[ref]) { list[ref] = {}; }
		list[ref][time] = {
			soggetti: qta,
			prezzo: unitario
		};
	});


	aggiungi_servizio_setup(list);
 });

</script>
