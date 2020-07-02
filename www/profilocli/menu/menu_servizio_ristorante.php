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

$testo = '
<div class="nav navbar_picker_flex" >
 	<div onclick="chiudi_picker()"><i class="fas fa-times icona_chiudi" ></i></div>
  <div>
   <ul uk-tab="connect: #switcher;animation: uk-animation-fade;swiping:false" class="no_before menu_uk_picker_icona uk-tab" style="border: none;">
    <li class="uk-active"  style="margin:0 10px;width:auto;" onclick="stampa_menu_addebito_web_app(' . $IDaddebito . ',0)"><div class="fs15" style="padding:5px 0">' . traduci('Prodotti e Menu', $lang) . '</div></li>

    <li  style="margin:0 10px;width:auto;" onclick="stampa_menu_addebito_web_app(' . $IDaddebito . ',1)"><div class="fs15" style="padding:5px 0">' . traduci('Menu', $lang) . '</div></li>
  </ul>
  </div>
</div>
<div>

</div>

<input type="hidden" value="' . $IDaddebito . '" id="IDaddebito_selezionato">
<input type="hidden" value="' . $time . '" id="time_servizio">
<input type="hidden" value="' . $IDservizio . '" id="IDmenu_servizio">


<div class="content" style="margin-top:0;height: calc(100% - 75px);">
	<div  style="padding-top:5px;padding-bottom: 70px;" id="stampa_menu"> </div>

  <div id="riepilogo_ordinazione"> </div>

</div>



  <script > stampa_menu_addebito_web_app(' . $IDaddebito . ',0);visualizza_riepilogo_inline(' . $IDaddebito . ')</script>';

echo $testo;

?>


<script>


function pulsanti_modifica_piatto(elem){
    var btn='';

    var IDaddebito_menu=$('#IDaddebito_selezionato').val();

    var idaddebito_collegato=$(elem).data('idaddebito_collegato');
    var IDaddebito=$(elem).data('idaddebito');

    var IDservizio=$(elem).data('idservizio');

    var variazioni=$(elem).data('variazioni');
    btn+='<li onclick="chiudi_picker(); visualizza_informazioni_servizio_menu('+IDservizio+')"> Informazioni</li> ';

    if(variazioni==1){
      btn+='<li onclick="chiudi_picker();modifica_piatto_ristorante('+IDaddebito+ ')"> Variazioni</li> ';

      if($('#variazioni'+IDaddebito).length>0){

         btn+=atob($('#variazioni'+IDaddebito).val());
      }
    }


  	btn+='<li onclick="chiudi_picker();mod_ospite(28,'+idaddebito_collegato+ ',0,10,()=>{stampa_menu_addebito_web_app('+IDaddebito_menu+',1)});" style="color:#d80404">Elimina</li>';


	  picker_modal_action(btn);
	$('.sostituisci').on('click',function(){

		chiudi_picker();visualizza_elenco_piatti_portate($(elem));
	});

}


function modifica_piatto_ristorante(IDaddebito){
    $.ajax({
            url: baseurl+'app_uikit/profilocli/menu/modifica_piatto_ristorante.php',
            method: 'POST',
            dataType: 'text',
            cache: false,
            timeout: 5000,
            data: { IDaddebito:IDaddebito},
            error: function(html) {
               loader();
            },

            success: function(html) {
               loader();
               var IDpicker=crea_picker(()=>{ },{'height':'80%'});
               $('#'+IDpicker+'.stampa_contenuto_picker').html(html);


            }
      });

}


function visualizza_informazioni_servizio_menu(IDservizio,riga){
    var IDaddebito=$('#IDaddebito_selezionato').val();
    $.ajax({
        url: baseurl+'app_uikit/profilocli/menu/informazioni_servizio_menu.php',
        method: 'POST',
        dataType: 'text',
        cache: false,
        timeout: 5000,
        data: { IDservizio:IDservizio,riga:riga,IDaddebito:IDaddebito},
        error: function(html) {
           loader();
        },

        success: function(html) {
           loader();
          var IDpicker=crea_picker(()=>{stampa_menu_addebito_web_app(IDaddebito,0);visualizza_riepilogo_inline(IDaddebito)},{'height':'45%'});
           $('#'+IDpicker+'.stampa_contenuto_picker').html(html);
        }
      });
}


function modifica_piatto_menu(tipo_aggiunta){
  /*

  0 rimozione
  1 aggiunta
  */

    var IDservizio=$('#IDpiatto').val();

    var time=$('#time_servizio').val();

    var menu=$('#IDmenu_servizio').val();

    var IDaddebito=$('#IDaddebito_selezionato').val();

    var riga=$('#riga_menu').val();


    mod_ospite(27,[IDservizio,tipo_aggiunta],[IDaddebito,menu,time,riga],10,()=>{});
}



</script>

