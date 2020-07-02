<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../config/connecti.php';
require_once __DIR__ . '/../../config/funzioni.php';
$IDprenotazione = $_SESSION['IDstrpren'];
$IDstruttura = get_IDstruttura_from_IDpren($IDprenotazione);
$lang = $_SESSION['lang'] ?: 0;

$lista_servizi = get_dati_conto_riferimento($IDprenotazione, 0, [], $IDstruttura)['arr_servizi'];

$serv_orari = [];
if (!empty($lista_servizi)) {
	foreach ($lista_servizi as $IDaddebito => $dati) {
		if (in_array($dati['tipolim'], [4, 5, 6, 7, 8, 10, 14])) {continue;}

		$qta = array_sum(array_column($dati['componenti'], 'qta'));

		$textserv = '
		<div class="uk_grid_div div_list_uk  "  uk-grid   data-id="' . $IDaddebito . '"  data-idtipo="' . $dati['IDtipo'] . '"   onclick="modifica_servizi(this)">
		    <div class="uk-width-expand lista_grid_nome uk-text-truncate" >' . $dati['nome_servizio'] . '<br/>
		  		<span class="uk-text-muted uk-text-small" > N.' . $qta . ' ' . ($qta == 1 ? 'persona' : 'persone') . '</span> ' . ($dati['modi'] == 0 ? '<span style="color:#CB0003;font-size: 15px;font-weight:600">' . traduci('Da Impostare', $lang) . '</span>' : '') . '</div>
	        <div class="uk-width-auto  uk-text-right lista_grid_right c000"> ' . ($dati['modi'] != 0 ? date('H:i', $dati['time']) : '--.--') . '  <i class="fas fa-chevron-right"></i> </div>
		</div> ';

		if (!isset($serv_orari[time0($dati['time'])])) {
			$serv_orari[time0($dati['time'])] = '';
		}

		$serv_orari[time0($dati['time'])] .= $textserv;
	}
}

if (!empty($serv_orari)) {
	foreach ($serv_orari as $time => $cont) {
		$testo .= ' <div class="div_uk_divider_list"> ' . dataita($time) . ' ' . date('Y', $time) . ' </div>
		' . $cont;
	}
}

echo $testo . '<br/><br/>';

?>

<script>
 function modifica_servizi(el){

 	var IDaddebito=$(el).data('id');
 	var tipo_riferimento=$(el).data('tipo_riferimento');
  var IDtipo=$(el).data('idtipo');
  var btn='';


    btn+=`<li  onclick="chiudi_picker();modifica_orario_ospite(`+IDaddebito+`,0)" >Imposta Orario</li>`;

    if(IDtipo==1){

    btn+=`<li  onclick="chiudi_picker();visualizza_menu_addebito_webapp(`+IDaddebito+`)" >Visualizza Menu</li>`;
    }


    picker_modal_action(btn);
}



function modifica_orario_ospite(IDaddebito,data_modifica =0){

    loader(1);

    var url = 'profilocli/servizi/modifica_orario_servizio.php';
    var query = {IDaddebito:IDaddebito,data_modifica:data_modifica};

    $.ajax({
        url: url,
        method: 'POST',
        dataType: 'text',
        cache: false,
        timeout: 5000,
        data: query,
        error: function(data) {
           loader();
        },

        success: function(data) {
           loader();
           if($('.uk-picker.stampa_contenuto_picker').length>0){
             $('.uk-picker.stampa_contenuto_picker').last().html(data);
           }else{
            var IDpicker=crea_picker(()=>{navigation_ospite(12,0)},{'height':'75%'});
            $('#'+IDpicker+'.stampa_contenuto_picker').html(data);
           }

        }
  });
}
</script>
