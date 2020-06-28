<?php
//header('Access-Control-Allow-Origin: *');

$alloggi = get_alloggi($IDstruttura);
$categorie = get_categorie($IDstruttura);

$prezzo = $aggiunta->get_prezzi_servizio($IDservizio, 1);
$prezzo = reset($prezzo);
$prezzo = reset($prezzo)[0];
$totale = $prezzo * count($riferimento->IDriferimento);
//$prezzo = float_format($prezzo, true);
$totale = float_format($totale, true);

$sale = sale_servizio($IDservizio);
array_walk($sale, function (&$arg) {$arg = $arg['nome'];});
$attuale = $aggiunta->quantita_attuale($IDservizio);
$informazioni = $riferimento->get_info();

$opzioni_sala = array_replace(['--'], $sale);

$nome_servizio = get_info_from_IDserv($IDservizio, 'nome_servizio', $IDstruttura);
/*
pinco pallino
sangiovese dal al

aggiungi quantiti lista griglia

dove sta riga c'è info

ombrellone prima fila
dal 2 al 5

conferma 5 servizi con totale

info pren + piccolo

totale prenotazione al posto di aggiungi !!
 */

$prima_data_servizio = dataita11($opzioni['date'][0]);
$ultima_data_servizio = dataita11(end($opzioni['date']));

$riga_prenotazioni = '';
foreach ($riferimento->IDriferimento as $ID) {
	$alloggio = $alloggi[$informazioni[$ID]['id_alloggio']]['alloggio'] ?? '';

	$nome_cliente = $informazioni[$ID]['nome_cliente'];

	$riga_prenotazioni .= '
		<div class="div_uk_divider_list" style="margin-top:5px !important;    margin-left: 0 !important;  margin-bottom: 10px !important;    font-size: 13px;  color: #333;  line-height: 1;">' . $nome_cliente . '- <span>' . $alloggio . ' - dal al </span>

		 </div>

	 	  <div uk-grid class="uk_grid_div div_list_uk   copia_servizio"    data-id="' . $ID . '"   >
		   		 <div class="uk-width-expand lista_grid_nome uk-text-truncate"><i class="fas fa-plus"></i> Aggiungi Quantita</span> </div>
		 </div>

		<div uk-grid class="uk_grid_div div_list_uk  lista_servizio primo"  onclick="crea_popup_script(this)" data-id="' . $ID . '" data-prezzo="' . $prezzo . '" data-sala="0" >
		    <div class="uk-width-expand lista_grid_nome uk-text-truncate">' . $nome_servizio . ' <br> <span>dal ' . $prima_data_servizio . ' al ' . $ultima_data_servizio . '</span> </div>
	        <div class="uk-width-auto uk-text-right  lista_grid_right c000"  ><span class="stringa_prezzo">' . $prezzo . '</span>  <i class="fas fa-chevron-right"></i>   </div>
		</div>

		 ';
}

?>

 <div id="sale_select" style="display:none;">
	<ul class="uk-list uk-list-divider uk-picker-bot seleziona_sala" style="padding:5px 20px;" onchange="(r,s)=>{cambia_sala_popup_select(r,s);}">
			<?=genera_select_uikit($opzioni_sala, [])?>
	</ul>
</div>






	<?=$riga_prenotazioni?>

	<input type="hidden" value="<?=$totale?>" class="totale_tipolim">



<script>



$('.copia_servizio').on('click',function() {
	let ID=$(this).data('id');
	let servizio=$('.lista_servizio[data-id="'+ID+'"]');
	let clone_servizio=servizio.clone();
	clone_servizio.removeClass('primo');
	console.log($(clone_servizio).data());
	clone_servizio.insertAfter(servizio);
	aggiorna_totale_elemento();
});


carica_totale_pulsante();


function crea_popup_script(el){
	var prezzo=parseFloat($(el).data('prezzo'));
	var id=$(el).data('id');
	var sala=$(el).data('sala');

console.log($(el).data());

	var testo=$('#sale_select li[value="'+sala+'"]').html();

	var elimina='';
	if(!$(el).hasClass('primo')){
		elimina='	 <button class="button_salva_preventivo elimina_servizio" style="background:#d80404" >Elimina</button>';
	}
	var html=`

		<div class="nav navbar_picker_flex" >
		 	<div onclick="chiudi_picker()"><i class="fas fa-times icona_chiudi" ></i></div>
		 	<div style="margin-top:5px;padding-right:10px"> `+elimina+`

		 	</div>
		</div>


		<div class="content picker" style="margin-top:0;padding-top:5px;">

				<div class="div_list_uk uk_grid_div unitario_pulsante" uk-grid  >
				    <div class="uk-width-1-2 lista_grid_nome uk-first-column">Prezzo</div>
				    <div class="uk-width-expand uk-text-right lista_grid_right c000"> € <span id="unitario">`+prezzo+`</span>  <i class="fas fa-chevron-right"></i> </div>
				</div>


				<div class="div_list_uk uk_grid_div variazione_pulsante" uk-grid>
				    <div class="uk-width-1-2 lista_grid_nome uk-first-column">Variazione</div>
				    <div class="uk-width-expand uk-text-right lista_grid_right c000"> % <i class="fas fa-chevron-right"></i></div>
				</div>


			<div class="div_list_uk uk_grid_div " uk-grid >
					    <div class="uk-width-1-2 lista_grid_nome uk-first-column">Sala </div>
						<div class="uk-width-expand uk-text-right lista_grid_right c000" id="sala" value="`+sala+`"   onclick="carica_content_picker('sale_select')"  style="text-decoration:underline">`+testo+`</div>
			</div>

		</div>`;


	    var IDpicker=crea_picker(()=>{controlla_sala($(el));aggiorna_totale_elemento()},{'height':'45%'});
        $('#'+IDpicker+'.stampa_contenuto_picker').html(html);
        //valore_popup($(el),$('.picker'));

    	$('.elimina_servizio').on('click',function(){
			chiudi_picker();
			$(el).remove();
			aggiorna_totale_elemento();
		});

    	abilita_modal_unitario(el);
    	abilita_modal_variazione(el);

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
				$(el).find('.stringa_prezzo').html(prezzo);
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

				$(el).find('.stringa_prezzo').html(prezzo);

            } else{
                  UIkit.modal.dialog('<p class="uk-modal-body">Inserire un numero. Prego riprovare!</p>');
            }
        }

       });
 });
}


function aggiorna_totale_elemento(){
	let totale=0;
	$('.lista_servizio').each(function (){
		totale += parseFloat($(this).data('prezzo'));
	});

	$('.totale_tipolim').val(totale);
	carica_totale_pulsante();
}


function carica_totale_pulsante(){
	var value=$('.totale_tipolim').val();
	$('#totale_vendita').html('€ '+value);
}


$('#aggiungi-servizio').off("click").on('click', function() {
	let data = [];


   $('.premi_data.data_selezionata[data-time]').each(function(){
    	var time=$(this).data('time');
    	data.push(time);
    });


	let list = {};
	$('.lista_servizio').each(function() {
		let ref = this.dataset.id;
		let sala = $(this).data('sala');

		let unitario = parseFloat($(this).data('prezzo'));
		for (let d of data) {
			if (!list[ref]) { list[ref] = []; }
			list[ref].push({
				time: d,
				soggetti: 1,
				prezzo: unitario,
				sala: sala
			});
		}
	});

	aggiungi_servizio_setup(list);
 });



function cambia_sala_popup_select(valore,html){
	$('#sala').attr('value',valore);
	$('#sala').html(html);
}

function controlla_sala(el){
	var sala=$('#sala').attr('value');
 	$(el).data('sala',parseInt(sala));

}



</script>
