<?php
//header('Access-Control-Allow-Origin: *');
//tipi ospiti
$query = "SELECT t.ID,t.restrizione,t.codice FROM tiporestr AS t WHERE t.IDstr='$IDstruttura' AND t.personale=1 AND t.limite=0 ORDER BY t.ordine";
$result = $link2->query($query);
$restrizioni = [];
while ($row = $result->fetch_row()) {
	$restrizioni[$row[0]] = ['nome' => $row[1], 'codice' => $row[2]];
}

$alloggi = get_alloggi($IDstruttura);
$categorie = get_categorie($IDstruttura);

//info opzione
$informazioni = $riferimento->get_info();

$date = $riferimento->get_date();
$conteggio_attuale = $aggiunta->get_conteggio_servizio($IDservizio);

$attuale = $aggiunta->quantita_attuale($IDservizio);

//restrizioni per ogni opzione
$persone = $riferimento->get_restrizioni();
$prezzi = $aggiunta->get_prezzi_servizio($IDservizio, $persone);

$date_lookup = array_flip($opzioni['date'] ?? []);
$scelta_persone = '';

$nome_restrizioni = '<ul id="lista_restrizioni" style="display:none">';
foreach ($restrizioni as $IDrestr => $restrizione) {
	$nome_restrizioni .= '<li value="' . $IDrestr . '">' . ($restrizione['nome']) . '</li>';
}
$nome_restrizioni .= '</ul>';

$somma_prezzi = 0;
foreach ($persone as $ref => $_) {
	$righe = '';
	$intervallo = $date[$ref];
	$showdate = true;
	if (isset($date_lookup[0])) {
		$intervallo = [$intervallo[0], $intervallo[0]];
		$date_lookup[$intervallo[0]] = true;
		$showdate = false;
	}

	$carrello = '';

	for ($i = $intervallo[0]; $i <= $intervallo[1]; $i += 86400) {
		if (!isset($date_lookup[$i])) {continue;}
		$utilizzo_totale = $conteggio_attuale[$i];
		$info_utilizzo = $attuale[$ref][$i] ?? [];
		$info_utilizzo = array_sum(array_map(function ($arg) {return count($arg['persone']);}, $info_utilizzo));

		$persone = ' ';

		$count_persone = 0;
		foreach ($restrizioni as $id => $_) {
			$prezzo = reset($prezzi[$ref])[$id] ?? [];
			$count_persone += (count($prezzo) ?: '0');
			$persone .= '<input type="hidden" class="restrizioni" data-prezzo="' . ($prezzo ? implode(',', $prezzo) : '0') . '" data-id="' . $id . '" value="' . (count($prezzo) ?: '0') . '" >';
			//$righe .= '<td><input class="restr" data-prezzo="' . implode(',', $prezzo) . '" data-id="' . $id . '" type="number" value="' . (count($prezzo) ?: '0') . '"></td>';
		}

		$carrello .= '
		<div data-id="' . $ref . '" data-time="' . $i . '" data-totale="' . float_format(reset($prezzi[$ref])[0]) . '" class="richieste_carrello servizio_da_aggiungere" onclick="crea_popup_script(this)">
		' . $persone . '
				<div>' . ($showdate ? date('d/m/Y', $i) : '--') . ' </div>
		<div> € <span class="totale">' . float_format(reset($prezzi[$ref])[0]) . '</span> </div>
		<div><i class="fas fa-user"></i><span class="n_persone"> ' . $count_persone . '</span> </div>

		</div>';
		$somma_prezzi += reset($prezzi[$ref])[0];
	}

	$alloggio = $alloggi[$informazioni[$ref]['id_alloggio']]['alloggio'] ?? '';
	$categoria = $categorie[$informazioni[$ref]['id_categoria']]['categoria'] ?? '';

	$riga_informazioni = $informazioni[$ref]['nome_cliente'] . ' - ' . $alloggio;

	$scelta_persone .= '
	<div style="margin-bottom:10px">
		<div class="div_uk_divider_list" style="margin-top:5px !important;    margin-left: 0 !important;  margin-bottom: 10px !important;">' . $riga_informazioni . ' </div>

		<div style=" overflow-y: hidden;    overflow-x: auto;  white-space: nowrap; "> ' . $carrello . '</div>
	</div>';

}

$somma_prezzi = '(' . format_number($somma_prezzi) . '€)';

?>


  <?=$nome_restrizioni?>
  <?=$scelta_persone?>



<script>

function crea_popup_script(el){

	var totale=parseFloat($(el).data('totale'));
	var time=$(el).data('time');
	var id=$(el).data('id');


	var restrizioni='';
	$(el).find('.restrizioni').each(function(){

		var idrestrizione=$(this).data('id');
		var numero=$(this).val();
		var nome_restrizioni=$('#lista_restrizioni li[value="'+idrestrizione+'"]').html();
		restrizioni+=`
		<div class="div_list_uk uk_grid_div" uk-grid>
		    <div class="uk-width-1-2 lista_grid_nome uk-first-column"> `+nome_restrizioni+`</div>
    		<div class="uk-width-expand uk-text-right lista_grid_right ">
	   			<div class="stepper  stepper-init stepperrestr">
    				<div class="stepper-button-minus" style="color:#0075ff;border:none"  onclick="selezionainfo(\'restr`+idrestrizione+`\',2,0)"  ><i class="fas fa-minus"></i></div>
				   <div class="stepper-value  restrizione" min="0"  data-id="`+idrestrizione+`"   id="restr`+idrestrizione+`"    max="99" style="border-bottom:1px solid #d6d6d6" >`+numero+`     </div>
				   <div class="stepper-button-plus" style="color:#0075ff;border:none" onclick="selezionainfo(\'restr`+idrestrizione+`\',1,0)"  ><i class="fas fa-plus"></i></div>
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

				<div class="div_list_uk uk_grid_div totale_pulsante" uk-grid>
				    <div class="uk-width-1-2 lista_grid_nome uk-first-column">Totale</div>
				    <div class="uk-width-expand uk-text-right lista_grid_right c000"> € <span id="totale">`+totale+`</span> <i class="fas fa-chevron-right"></i> </div>
				</div>
		</div>`;


	    var IDpicker=crea_picker(()=>{aggiorna_valori($(el));carica_totale_pulsante()},{'height':'50%'});
        $('#'+IDpicker+'.stampa_contenuto_picker').html(html);
        valore_popup($(el),$('.picker'));

        abilita_modal_unitario($(el));


}

function abilita_modal_unitario(elem){
    UIkit.util.on('.totale_pulsante', 'click', function (e){
	var prezzo=parseFloat($(elem).data('totale'));
      UIkit.modal.prompt('Modifica Prezzo:',prezzo).then(function (prezzo) {

        if(prezzo){
            if(!isNaN(prezzo)){
                prezzo= parseFloat(prezzo);
            	$('#totale').html(prezzo);
				$(elem).data('totale',prezzo);
				$(elem).attr('data-totale',prezzo);
				$(elem).find('.totale').html(prezzo);


				let id = $(elem).data('id');
				let time = $(elem).data('time');

				let lista_restr = {};
				if (!lista_restr[id]) lista_restr[id] = [];
				$(elem).find('.restrizioni').each(function(){
					let idrestr = $(this).data('id');
					let count = $(this).data('prezzo');
					lista_restr[id].push([idrestr, count]);
				});
				var IDservizio=$('#IDservizio_scelto').val();
				mod_add_serv(3,IDservizio, {restr: lista_restr, prezzo: price, time: time}, function(r) {
					let results = JSON.parse(r);
					ricarica_prezzi(results);
				});


            } else{
                  UIkit.modal.dialog('<p class="uk-modal-body">Inserire un numero. Prego riprovare!</p>');
            }
        }

       });
 });
}



function valore_popup(elem,popup){
	$(popup).find('.restrizione').on('change',function() {
		let numero_persone=$(this).html();
		let IDrestrizione=$(this).data('id');

		$(elem).find('.restrizioni[data-id="'+IDrestrizione+'"]').val(numero_persone);


		modifica_restrizione($(elem));
	});



}

function modifica_restrizione(elem){
	let lista_restr = {};
	let id = $(elem).data('id');
	let time = $(elem).data('time');
	if (!lista_restr[id]) lista_restr[id] = [];

	$(elem).find('.restrizioni').each(function(){

		let idrestr = $(this).data('id');
		let count = parseInt($(this).val());

		lista_restr[id].push({id: idrestr, qta: count});
	});


 	var IDservizio=$('#IDservizio_scelto').val();
	mod_add_serv(1,IDservizio, {persone: lista_restr, time: time}, function(r) {
		let results = JSON.parse(r);
		ricarica_prezzi(results);
	});

}

ricarica_prezzi([]);
function aggiorna_valori(elem){

	var numero_persone=0;
	var prezzo=0;

	$(elem).find('.restrizioni').each(function(){
		numero_persone+=parseInt($(this).val());
		prezzo_persona=parseInt($(this).val())* parseFloat($(this).data('prezzo'));
		prezzo+=prezzo_persona;

	});

	$(elem).find('.n_persone').html(' '+numero_persone);

}

function ricarica_prezzi(valori){
	totale=0;
	for (let r in valori) {
		$('.servizio_da_aggiungere[data-id="'+r+'"]').each(function() {
			let time = this.dataset.time;
			if(valori[r][time][0]){
				totale=valori[r][time][0];
				$(this).data('totale',totale);
				$(this).find('.totale').html(totale);
				$('#totale').val(totale);
			}

			$(this).find('input').each(function() {
				let restr = this.dataset.id;
				if (valori[r][time] === undefined) { return; }
				if ($(this).hasClass('restrizioni') && restr && valori[r][time][restr]) {
					$(this).attr('data-prezzo',valori[r][time][restr].join(',') );
				}
			});
		});
	}



}


$('#aggiungi-servizio').off("click").on('click', function() {
 let list = {};


 $('.servizio_da_aggiungere[data-id]').each(function(){
  	let id = $(this).data('id');
	let time = $(this).data('time');


	if (!id || !time) return;
	if (!list[id]) { list[id] = {}; }
	if (!list[id][time]) { list[id][time] = {soggetti: [], prezzi: []}; }

	$(this).find('.restrizioni').each(function() {

			let restr = $(this).data('id');
			let qta = parseInt($(this).val());

			if (!qta) return;

			let prezzo =  $(this).data('prezzo').split(',');
			let s = Array(qta).fill(restr);
			list[id][time].soggetti = list[id][time].soggetti.concat(s);
			list[id][time].prezzi = list[id][time].prezzi.concat(prezzo);
		});
	});

	 aggiungi_servizio_setup(list);
});

function carica_totale_pulsante(){
	var value=0;
	$('.servizio_da_aggiungere[data-id]').each(function(){
		value+=parseFloat($(this).data('totale'));
	});

	$('#totale_vendita').html('€ '+value);
}



</script>
