var baseurl = /test.scidoo.com/.test(window.location.origin) ? 'https://test.scidoo.com/scidoo/' : 'https://www.scidoo.com/';
var versione='app_uikit';

$.ajaxSetup({
    xhrFields: {
        withCredentials: true
    }
});

var history_navigation=[];

let ristorante = {};


function carica_app(time) {

    $.ajax({
        url: baseurl+versione+'/config/controlloini.php',
        method: 'POST',
        dataType: 'text',
        timeout: 15000,
        cache: false,
        data: {},
        success: function(data) {
            var num = data.indexOf("error");
            if ((num == -1) && (!isNaN(data))) {
                data = parseInt(data);
                if (time == 0) {
                    carica_pagina_principale(data);

                } else {
                    visualizza_login();
                }
            } else {
                visualizza_login();
            }
        },
        error: function(data) {
            visualizza_login();

        }
    });

}

function carica_pagina_principale(tipo){
   loader(1);
    var url2='';
    if(tipo!=0){   //prenotazione
        url2='profilocli/profilo_cli.php';
        history_navigation.push('navigation_ospite(0,0)');
    }else{  //sruttura
        url2='struttura/profilo.php';
        history_navigation.push('navigation(4,0)');
    }
    $.ajax({
        url:  baseurl+versione+'/'+url2,
        method: 'POST',
        dataType: 'text',
        cache:false,
        timeout:5000,
        data: '',
        success: function (data) {
            loader();
            $('#pagina_principale').html(data);
            if(tipo!=0){
               aggiorna_menu_ospite(0,0);
               apri_controllo_privacy();
            }else{
               aggiorna_menu(4,0);
            }
     },
    error:function(data){
        loader();
    }
 });

}

function visualizza_login(){
    $.ajax({
            url:  baseurl+versione+'/login.php',
            method: 'POST',
            dataType: 'text',
            cache:false,
            timeout:5000,
            data: '',
            success: function (data) {
            $('#pagina_principale').html(data);
     },
    error:function(data){
        loader();
    }
 });
}


var openp=0;
var lastcalen_left=0;
var lastcalen_top=0;


 function navigation(id,arr,agg = 0,no_scorrimento = 0){

    id=parseInt(id);
    loader(1);
    var apriurl=[];

    apriurl[4]='struttura/dashboard.php';
    apriurl[5]='struttura/calendario.php';
    apriurl[6]='struttura/dettaglio_prenotazione.php';
    apriurl[7]='struttura/dettaglio_cliente.php';

    apriurl[9]='struttura/centro_benessere/centro_benessere.php';
    apriurl[10]='struttura/centro_benessere/centro_benessere_giorno.php';

    apriurl[12]='struttura/domotica.php';
    apriurl[13]='struttura/pulizie/pulizie_giorno.php';
    apriurl[14]='struttura/arrivi.php';
    apriurl[15]='struttura/elenco_clienti.php';
    apriurl[16]='struttura/elenco_prenotazioni.php';


    apriurl[18]='struttura/centro_benessere/trattamenti.php';
    apriurl[19]='struttura/sposta_prenotazione.php';
    apriurl[20]='struttura/metodi_pagamento_prenotazione.php';

    apriurl[21]='struttura/preventivi/preventivi.php';
    apriurl[22]='struttura/preventivi/nuovo_preventivo.php';

    apriurl[23]='struttura/negozio/vendite.php';
    apriurl[24]='struttura/negozio/nuova_vendita.php';
    apriurl[25]='struttura/prezzi/prezzi_giornalieri.php';

    apriurl[26]='struttura/spiaggia.php';

    apriurl[27]='struttura/ristorante/ristorante.php';
    apriurl[28]='struttura/ristorante/ordinazione.php';


    apriurl[29]='struttura/chat_messaggi/elenco_messaggi.php';
    apriurl[30]='struttura/prenotazioni_be_cm.php';

    var url=apriurl[id];


    $.ajax({
                url:  baseurl+versione+'/'+url,
                method: 'POST',
                dataType: 'text',
                cache:false,
                timeout:5000,
                data:{arr_dati:arr},
                success: function (data) {


                    if(arr==0){
                        arr={};
                    }
                     arr['indietro']=1;

                    var stringa_nav='navigation('+id+','+JSON.stringify(arr)+','+agg+','+no_scorrimento+');';
                    var last_elem=history_navigation.length-1;
                    var esegui_hash=0;
                    if(history_navigation[last_elem]){
                        if(history_navigation[last_elem].startsWith('navigation('+id)){

                            switch(id){
                                case 0:
                                case 4:
                                  esegui_hash=1;
                                break;
                                case 5:
                                case 26:
                                    history_navigation[last_elem]=stringa_nav;
                                break;
                            }

                        }else{
                            esegui_hash=1;
                        }
                    }else{
                        esegui_hash=1;
                    }

                    if(esegui_hash==1){
                          history_navigation.push(stringa_nav);
                        aggiungi_hash();
                    }




                    loader();
                    if($('#offcanvas').length>0){
                        UIkit.offcanvas('#offcanvas').hide();
                    }


                    $('.menu_nav_puls').removeClass('uk-active');
                    $('.m'+id).addClass('uk-active');

                    $('#container').html(data);
                    aggiorna_menu(id,arr);


                    if(no_scorrimento==0){
                      $('html, body').animate({scrollTop: '0px'});
                    }


                    if (typeof agg == "function") {
                        setTimeout(function(){agg()},100);
                    }

                    switch(agg){
                           case 1:

                            last_tab=0;

                            $('#calendar_div .body').scroll(function() {
                                $('#calendar_div .side').scrollTop($(this).scrollTop());
                                $('#calendar_div .header').scrollLeft($(this).scrollLeft());
                                lastcalen_left=Math.round($(this).scrollLeft());
                                lastcalen_top=Math.round( $(this).scrollTop());
                            });

                            var h=parseInt($('body').innerHeight() - 75);
                            $('#calendar_div').css('height',h+'px');

                             if($('.oggi').length>0){
                                    if((lastcalen_top!=0) || (lastcalen_left!=0)){
                                        $('.body').animate( { scrollLeft: '+='+lastcalen_left ,scrollTop: '+='+ lastcalen_top}, 1000 );
                                    }else{
                                        var offset_left=$('.oggi').offset().left-120;
                                        $('.body').animate( { scrollLeft: '+='+offset_left ,}, 1000);
                                    }
                             }else{

                                 if(openp==0){
                                      $('.body').animate( { scrollLeft: '0',scrollTop: '0'}, 1000 );
                                 }else{
                                    if((lastcalen_top!=0) || (lastcalen_left!=0)){
                                        $('.body').animate( { scrollLeft: '+='+lastcalen_left ,scrollTop: '+='+ lastcalen_top}, 1000 );
                                         openp=0;
                                    }
                                 }
                             }

                                $('.senza_soggiorno').on('click', function() {
                                    var time = $(this).attr('data-time');
                                        apri_giornaliere(time);
                                });

                                $('.prenotazione').on('click', function() {
                                    var ID = $(this).attr('IDpren');
                                    //console.log($(this).offset(),$(this).position());

                                   if(lastcalen_left < 0){lastcalen_left=lastcalen_left*-1 ;}
                                   if(lastcalen_top < 0){lastcalen_top=lastcalen_top *-1 ;}

                                    navigation(6,{'IDprenotazione':ID},2,0);
                                    openp = ID;
                                });

                                 $('.prenotazioni_annullate').on('click', function() {
                                    var time = $(this).attr('data-time');
                                     apri_annullate(time);
                                });

                              $('.note_esclusivi').on('click', function() {
                                    var time = $(this).attr('data-time');
                                    apri_esclusivi(time);
                                });


                                $('.new').on('click', function() {
                                    var notti=1;
                                    if($(this).hasClass('giornaliero')){
                                        notti=0;
                                    }
                                    if (openp == 0) {
                                        var time=  $(this).attr('data-time');
                                        navigation(22,{'IDpreventivo':0,'time':time,'notti':notti},()=>{modifica_anagrafica_nuovo_preventivo()});

                                    }
                                });

                           break;
                           case 4:
                                setTimeout(function() {
                                     UIkit.switcher('.uk-tab').show(last_tab);
                                     last_tab=0;
                                },300);
                           break;
                           case 8:
                                var prenotazione_aperta=0;
                                 $('#calendar_div .body').scroll(function() {
                                    $('#calendar_div .side').scrollTop($(this).scrollTop());
                                    $('#calendar_div .header').scrollLeft($(this).scrollLeft());
                                    lastcalen_left=Math.round($(this).scrollLeft());
                                    lastcalen_top=Math.round( $(this).scrollTop());
                                });

                                var h=parseInt($('body').innerHeight() - 75);
                                $('#calendar_div').css('height',h+'px');



                            if($('.oggi').length>0){
                                    if((lastcalen_top!=0) || (lastcalen_left!=0)){
                                        $('.body').animate( { scrollLeft: '+='+lastcalen_left ,scrollTop: '+='+ lastcalen_top}, 1000 );
                                    }else{
                                        var offset_left=$('.oggi').offset().left-120;
                                        $('.body').animate( { scrollLeft: '+='+offset_left ,}, 1000);
                                    }
                             }else{

                                 if(prenotazione_aperta==0){
                                      $('.body').animate( { scrollLeft: '0',scrollTop: '0'}, 1000 );
                                 }else{
                                    if((lastcalen_top!=0) || (lastcalen_left!=0)){
                                        $('.body').animate( { scrollLeft: '+='+lastcalen_left ,scrollTop: '+='+ lastcalen_top}, 1000 );
                                         prenotazione_aperta=0;
                                    }
                                 }
                            }



                                $('.apri_prenotazione').on('click', function() {
                                   var ID = $(this).data('idpren');
                                   if(lastcalen_left < 0){lastcalen_left=lastcalen_left*-1 ;}
                                   if(lastcalen_top < 0){lastcalen_top=lastcalen_top *-1 ;}
                                   prenotazione_aperta = ID;
                                    navigation(6,{'IDprenotazione':ID});
                                });




                                $('.collega_servizio').on('click', function() {
                                    if(prenotazione_aperta==0){
                                        var time=  $(this).data('time');
                                        var sala= $(this).data('sala');
                                        collega_prenotazioni({'time':time});
                                    }
                                });
                           break;
                    }

         },
        error:function(data){
            loader();
        }
     });
}



function apri_notifica(dati){
    var position='top-center';
    var status='primary';
    var time=2000;
    if(dati['position']){
        position=dati['position'];
    }
    if(dati['status']){
        status=dati['status'];
    }
    if(dati['timeout']){
        time=dati['timeout'];
    }
    UIkit.notification({  message: dati['messaggio'],   status: status, pos: position,   timeout: time  });
}


function accedi_str(){
    loader(1);
    var email = $('#email').val();
    var password = $('#pass').val();

    var url = baseurl + 'config/login.php';

    $.ajax({
        url: url,
        method: 'POST',
        dataType: 'text',
        timeout: 10000,
        cache: false,
        data: {  email: email,   password: password,   json: 1,resta:'on',},
        error: function(data) {
           loader();
        },
        success: function(data) {
            loader();
            var num = data.indexOf("error");
            if (num == -1) {
                carica_pagina_principale(0);
            } else {
                 UIkit.modal.dialog('<p class="uk-modal-body">I Dati immessi non sono corretti. Prego riprovare!</p>');
            }
        }
    });

}



function cambia_tab_prenotazione(IDprenotazione,tab){
    $('#dettaglio_prenotazione').html('');
    switch(tab){
        case 'informazioni':
        case 0:
            if($('.uk-tab').length>0){
             UIkit.tab('.uk-tab').show(0);
            }

           $.post('struttura/tab_prenotazione/informazioni_prenotazione.php', { IDprenotazione:IDprenotazione }, function(html) {
                $('#dettaglio_prenotazione').html(html);
            });
        break;
        case 'conto':
        case 2:
           if($('.uk-tab').length>0){
             UIkit.tab('.uk-tab').show(2);
            }
           $.post('struttura/tab_prenotazione/conto_prenotazione.php', { IDprenotazione:IDprenotazione }, function(html) {
                $('#dettaglio_prenotazione').html(html);
            });
        break;
        case 'orari':
        case 1:
            if($('.uk-tab').length>0){
             UIkit.tab('.uk-tab').show(1);
            }
             $.post('struttura/tab_prenotazione/orari_prenotazione.php', { IDprenotazione:IDprenotazione }, function(html) {
                $('#dettaglio_prenotazione').html(html);
            });

        break;

        case 'pagamenti':
        case 3:
              if($('.uk-tab').length>0){
             UIkit.tab('.uk-tab').show(3);
            }
             $.post('struttura/tab_prenotazione/pagamenti_prenotazione.php', { IDprenotazione:IDprenotazione }, function(html) {
                $('#dettaglio_prenotazione').html(html);
            });
        break;

    }

}

function esci() {

     UIkit.modal.confirm('Vuoi davvero uscire da Scidoo?').then(function () {
           loader(1);
            var query = { ID: '0' };
            setTimeout(function() {
                $.ajax({
                    url: baseurl + 'config/logout.php',
                    method: 'POST',
                    dataType: 'text',
                    cache: false,
                    timeout: 8000,
                    data: query,
                    success: function(data) {
                     loader();
                      visualizza_login();
                    },
                    error: function(data) {
                      loader();
                    }
                });
            });

       }, function () {   });
}



window.onhashchange = function() {
var numberhash=window.location.hash.substring(1);
//console.log(start_hash,numberhash);
    if(numberhash<start_hash){
       start_hash=numberhash-1;
        var key=history_navigation.length-2;
        var old_nav=history_navigation[key];
        //console.log(old_nav);
        eval(old_nav);
        history_navigation.splice(key);
    }
}

var start_hash=0;
if(start_hash==0){
    history.pushState("", document.title, window.location.pathname);
}
function aggiungi_hash(){
    start_hash++;
    window.location.hash ='#'+start_hash;

    if(start_hash!=0){
        $('#navigation_back').fadeIn();
    }else{
         $('#navigation_back').fadeOut();
    }
}



function goBack(){
    if(navigator.onLine){
        window.history.back();
    }
}

var last_tab=0;
function  tabdet_pren(tab){
    //UIkit.switcher('.uk-tab').show(tab);
    last_tab=tab;
}



function selezionainfo(ID,metodo,agg){
    var consta=1;
    var temp=$('#'+ID).html();
    var max=$('#'+ID).attr('max');
    var min=$('#'+ID).attr('min');
    temp=parseFloat(temp);

    switch(metodo){
        case 1:
            max=parseFloat(max);
            if(temp<max){temp=temp+consta;}
        break;
        case 2:
            min=parseFloat(min);
            if(temp>min){temp=temp-consta;}
        break;
    }

    $('#'+ID).html(temp);


    if (typeof agg == "function") {
            agg();
    }

    switch(agg){
        case 0:
            $('#'+ID).trigger('change');
        break;
    }
}



function modprenot(id, campo, tipo, val2, agg, notifica=1) {
   loader(1);

    switch (val2) {
        case 0:
            var val = $('#' + campo).val();
            //val=encodeURIComponent(val);
            break;
        case 1:
            var val = $('#' + campo).val();
            val = val.replace(',', '.');
            if (isNaN(val)) {
                //alertify.alert("E' necessario inserire un numero. Prego Riprovare");
                return false;
            }
            break;
        case 6:
            var val = $('#' + campo).val();
            val = val.replace(/\n/g, "<br/>"); //descrizione
            //val=encodeURIComponent(val);
            break;
        case 7:
            if (document.getElementById(campo).checked == true) { //si o no
                val = '1';
            } else {
                val = '0';
            }
            break;
        case 8:
            var val = $('#' + campo).html();
            break;
        case 9:
            var val = $('#' + campo).html();
            break;
        case 10:
            val = campo;
            break;
        case 11:
            val = $(campo).val();
            break;
        case 12:
            val = $(campo).val();
            id = id + '_' + $(campo).attr('alt');
            break;
        case 13:
            val = $(campo).html();
            break;
        default:
            var val = $('#' + campo).val();
            break;
    }


    var query = { val: val, tipo: tipo, ID: id, val2: val2 };

    $.ajax({
        url: baseurl + 'config/gestioneprenot.php',
        method: 'POST',
        dataType: 'text',
        cache: false,
        data: query,
        timeout: 5000,
        success: function(data) {
            //alert(data);




            if (notifica != 0) {
                  UIkit.notification("...", {
                    message:'Modifica effettuata con successo',
                    status: 'main_notifica',
                    pos:'bottom-center',
                    timeout:2000}
                 );
                /*
                if (agg == 2) {
                    myApp.addNotification({
                        message: 'Funzione eseguita con successo',
                        hold: 1200
                    });
                } else {
                    myApp.addNotification({
                        message: 'Modifica effettuata con successo',
                        hold: 1200
                    });
                }
                */
            }

            //console.log(data);
            loader();


            if (typeof agg == "function") {
                    agg();
            }

            switch (parseInt(agg)) {
                case 1:
                    var IDpren=$('#IDpren').val();
                    navigation(6,{'IDprenotazione':IDpren},2,0);
                break;
                case 2:
                     var IDinfop=$('#IDinfop').val();
                     var tipo_pers=$('#tipo_pers').val();
                     //navigation(7,[IDinfop,tipo_pers],3,0);
                break;
            }
        },
        error: function() {
           loader();
        }
    });
}


function loader(tipo){
    switch(tipo){
        case 1:
         $('#loader').show();
        break;
        default:
         $('#loader').hide();
        break;
    }
}


function crea_picker(onclose=null,parametri=null){
    var ID_picker=0;


    if($('.uk-picker').length>0){
        ID_picker=$('.uk-picker').length;
    }

    var stringa_id_picker='uk_picker_'+ID_picker;

    var stile_picker='';
    if(parametri!=null){
      var altezza_picker='height:'+(parametri['height'] !='' ? parametri['height']+';' : '');
      stile_picker='style="'+altezza_picker+'"';
    }

     $('body').append('<div class="uk-picker-overlay"  onclick="chiudi_picker()"> </div>');
     $('body').append('<div class="uk-picker stampa_contenuto_picker"  '+stile_picker+'  id="'+stringa_id_picker+'" > </div>');


     var height= $('#'+stringa_id_picker).height();
     $('.uk-picker-overlay').append('');


     $('#close_'+ID_picker+'.tasto_chiudi_picker').css('bottom',parseInt(height)+'px');

    $('#'+stringa_id_picker).on("remove", function () {
       //alert('chiudo');
        chiudi_picker();
        if(onclose){
            onclose();
        }
    });

    if(onclose){
        $('#'+stringa_id_picker).data('onclose', function( ){
            onclose();
        });
    }


    return stringa_id_picker;


}

function chiudi_picker(){

    if($('.uk-picker').length>1){
       $('.uk-picker-overlay').last().remove();
       var close_fun=null;
      if($('.uk-picker').last().data('onclose')){
        // $('.uk-picker').last().data('onclose')();
         close_fun= $('.uk-picker').last().data('onclose');
      }
      $('.uk-picker').last().remove();
      if(close_fun){close_fun();}

    }else{
      $('.uk-picker-overlay').remove();
      if($('.uk-picker').data('onclose')){
         $('.uk-picker').data('onclose')();
      }
      $('.uk-picker').remove();
      $('body').removeClass('picker_imp');
    }

}

function close_all_picker(){
      $('.uk-picker-overlay').remove();
      if($('.uk-picker').data('onclose')){
         $('.uk-picker').data('onclose')();
      }
      $('.uk-picker').remove();
      $('body').removeClass('picker_imp');
}

var picker_id_div='';
function carica_content_picker(elem,onclose=null){
    if (typeof elem == "string") {
        elem=$('#'+elem);
    } else {
        elem=$(elem);
    }
    var dati = elem.html();
    var IDpicker=crea_picker(onclose);
    picker_id_div=elem;
     $('#'+IDpicker+'.stampa_contenuto_picker').html('<div class="content">'+dati+'</div>');

    if( $('#'+IDpicker+' .content .uk-icon').length==1){
        setTimeout(function(){
            var scroll2=parseInt(  $('#'+IDpicker+' .content .uk-icon').offset().top -  $('#'+IDpicker+' .content .uk-icon').offsetParent().offset().top ) - 25;
                 $('#'+IDpicker+' .content ').animate({scrollTop : scroll2},700);
        } , 500);
    }
}

function esegui_funzione_select(el){
    chiudi_picker();
    var valore=$(el).attr('value');
    var select=$(el).parent();

    var elemento_selezionato=$(select).find('span');
    $(select).find('span').remove();
    //console.log(elemento_selezionato);
    $(el).append(elemento_selezionato);

    copia_picker_html($(select));

    var html=$(el).html().replace(/<\/?[^>]+(>|$)/g, "");
    var funz = eval($(select).attr('onchange'));
    if (typeof funz == 'function') {
        funz(valore,html);
    }
}


function copia_picker_html(elem){
   var dati=$(elem).html();
   picker_id_div.find('ul').html(dati);
}

function picker_modal_action(btn,testo_extra=''){

    var content='<ul class="uk_picker_action " >'+btn+'</ul><div class="picker_action_elementi_extra">'+testo_extra+'</div>';
    var IDpicker=crea_picker();

    $('#'+IDpicker).addClass('picker_action');
    $('#'+IDpicker+'.stampa_contenuto_picker').html('<div class="content">'+content+'</div>');

    if(testo_extra){
      var altezza_contenuto_extra=$('#'+IDpicker+' .picker_action_elementi_extra').height();
      $('#'+IDpicker+' .uk_picker_action').css('padding-bottom', (parseInt(altezza_contenuto_extra))+'px');
    }

}


function aggiorna_menu(id,val){
    loader(1);

    $.ajax({
            url: baseurl+versione+'/config/aggiorna_menu.php',
            method: 'POST',
            dataType: 'text',
            cache:false,
            timeout:5000,
            data: {
                idstep:id,
                arr_dati:val
            },
             error: function (data) {
                loader();
            },
            success: function (data) {

                $('#menu_dinamic').html('');
                $('#menu_dinamic').html(data);

                loader();
            }
    });

}


function det_info_cli(IDcliente,IDinfo_prenonatizione,el){

    var btn=' ';
    var tel=$(el).data('telefono');
    var cell=$(el).data('cellulare');
    var mail=$(el).data('mail');

     var btn=btn+`<li  onclick="navigation(7,{IDcliente:`+IDcliente+`});chiudi_picker();">Apri Dettaglio </li>`;

     if(cell.length>2){
        btn=btn+`<li onclick="location.href ='tel:`+cell+`';chiudi_picker(); ">Chiama `+cell+`</li>`;
     }

    if(tel.length>2){
        btn=btn+` <li  onclick="location.href ='tel:`+tel+`';chiudi_picker(); ">Chiama `+tel+` </li>`;
     }

    if(mail.length>2){
        btn=btn+` <li  onclick="location.href ='mailto:`+mail+`';chiudi_picker(); ">Scrivi a `+mail+` </li>`;
     }
     picker_modal_action(btn)
}

function  crea_modal_action(group_btn1,group_btn2=0,group_btn3=0){//versione con i pulsanti
    var content='';

    content='<div class="group_btn">'+group_btn1+'</div>';

    if(group_btn2!=0){
         content=content+'<div class="group_btn">'+group_btn2+'</div>';
    }

    if(group_btn3!=0){
         content=content+'<div class="group_btn">'+group_btn3+'</div>';
    }

  var dialog=  UIkit.modal($(
     `<div class=" modal-action-sheet">
         <div class="uk-modal-dialog dialog-action-sheet">
            `+content+`
            <button class="uk-button uk-button-default uk-margin-small uk-width-1-1 chiudi " onclick="" >Chiudi</button>
         </div>
      </div>`));

dialog.show();

    setTimeout(function(){
    $('.uk-modal-page').on('click', function() {
        $('.modal-action-sheet').remove();
      });

    },200);


}

/*
function modservice(obj) {

    var multi = 0;
    var buttons = new Array();
    //var num=$$('#'+txtsend).attr('alt');
    var num = $(obj).attr('data-num');
    var modprezzo = $(obj).attr('data-prezzo');
    var posdelete = $(obj).attr('data-delete');
    var IDdati = $(obj).attr('data-id');
    var agg=parseInt($(obj).attr('data-agg'));

    var btn='';

    if (num > 1) {
     var res = IDdati.replace(/,/g, "/");
     //btn=btn+` <button class="uk-button uk-button-default uk-width-1-1 " onclick="navigation(8,['`+res+`'] , 0, 0); ">Apri Dettaglio</button>`;
     btn=btn+`<li  onclick="navigation(8,['`+res+`'] , 0, 0);chiudi_picker();">Apri Dettaglio </li>`;
    }
    //modprezzo=0;
    switch (parseInt(modprezzo)) {
        case 1:
        case 2:

        setTimeout(function(){
            var input='';
            UIkit.util.on('#mod_prezzo', 'click', function (e){
              UIkit.modal.prompt('Inscerisci il Prezzo:',input).then(function (input) {

                if(input){
                    if(!isNaN(input)){
                        input= parseFloat(input);
                        modprenextra(input,IDdati, 44, 9, agg);
                    } else{
                          UIkit.modal.dialog('<p class="uk-modal-body">Inserire un numero. Prego riprovare!</p>');
                    }
                }

               });

         });
        },200);

         //btn=btn+` <button class="uk-button uk-button-default uk-width-1-1" id="mod_prezzo" >Modifica Prezzo</button>`;
          btn=btn+`<li  onclick="chiudi_picker();" id="mod_prezzo" >Modifica Prezzo</li>`;
        break;


    }

    if (posdelete == 1) {
        btn=btn+`<li   onclick=" msgboxelimina(`+IDdati+`, 3, 0, 1, 1);chiudi_picker();" style="color:#d80404">Elimina</li>`;
        //btn=btn+` <button class="uk-button uk-button-default uk-width-1-1 "  onclick=" msgboxelimina(`+IDdati+`, 3, 0, 1, 1);">Elimina</button>`;
    }

    picker_modal_action(btn);

}

*/

function modprenextra(id, campo, tipo, val2, agg) {
    loader(1);

    var plus = "";

    switch (val2) {
        case 1:
            var val = $('#' + campo).val();
            val = val.replace(',', '.');
            if (isNaN(val)) {
                myApp.alert("E' necessario inserire un numero. Prego Riprovare");
                return false;
            }
            break;
        case 6:
            var val = $('#' + campo).val();
            val = val.replace(/\n/g, "<br/>"); //descrizione
            break;
        case 7:
            if (document.getElementById(campo).checked == true) { //si o no
                val = '1';
            } else {
                val = '0';
            }
            break;
        case 8:
            var classe = $('#' + campo).attr('class');
            var jj = 0;
            var kk = 0;
            if (classe.indexOf(plus + "1") != -1) { //1
                jj = 1;
                kk = 2;
            }
            if (classe.indexOf(plus + "2") != -1) { //2
                jj = 2;
                kk = 0;
            }
            if (jj == 0) {
                if (classe.indexOf("pag") != -1) { kk = 2; } else { kk = 1; }
            }
            val = kk;
            break;
        case 9:
            val = campo;
            break;
        case 10:
            var val = $('#' + campo).val();
            var plus = 'modi';
            val2 = 8;
            break;
        case 11:
            var val = $('#' + campo).val();
            var id2 = val;
            var val = $('#tr' + id).attr('lang');
            id = id2;
            break;
        case 12:
            var val = $(campo).val();
            break;
        default:
            var val = $('#' + campo).val();
            break;
    }


    var query = { val: val, tipo: tipo, ID: id, val2: val2 };
    $.ajax({
        url: baseurl + 'config/gestioneprenextra.php',
        method: 'POST',
        dataType: 'text',
        timeout: 7000,
        cache: false,
        data: query,
        success: function(data) {
            loader();

         apri_notifica({'messaggio':'Modifica effettuata con successo','status':'main_notifica','position':'bottom-center'});

           if (typeof agg == "function") {
                    agg();
            }

            switch (agg) {
                case 1:
                    var IDpren=$('#IDpren').val();
                    navigation(6,[IDpren],2,0);
                break;
                case 2:
                    var txtsend=$('#txtsend2').val();
                    navigation(8,[txtsend],0,0);
                break;
                case 3:
                     var old_nav=history_navigation[history_navigation.length-1];
                     eval(old_nav);
                break;
                case 4:
                 ricarica_addebito();
                break;
                case 5:
                    var IDserv=parseInt(data);
                    nascondi_addebito(IDserv);
                break;
            }

        },
        error: function(data) {
           loader();
        }
    });
}




function opensosp(IDsottotip,time){
    loader(1);

    $.ajax({

        url: baseurl+versione+'/struttura/sospesi.php',
        method: 'POST',
        dataType: 'text',
        timeout: 5000,
        cache: false,
        data:  { IDsottotip: IDsottotip, time: time },
        success: function(data) {
            loader();


            var IDpicker=crea_picker(()=>{},{'height':'75%'});
            $('#'+IDpicker+'.stampa_contenuto_picker').html(data);

        },
        error: function(data) {
            loader();
        }
    });

}

function settime1(val, tipo) {
    var ID = $('#IDprenextra').val();
    modprenextra(ID, val, 1, 9, 3);
}


function pulsdomotica(IDdom) {

var btn_el='';
var elimina = $('#iddomo' + IDdom).val();
var btn='<li onclick="setdom('+IDdom+', 1);chiudi_picker();">Manuale a Tempo</li>';
btn=btn+'<li onclick="setdom('+IDdom+',2);chiudi_picker();">Manuale ad Intervallo</li>';
if(elimina==1){
 btn=btn+'<li oonclick="modprenot('+IDdom+','+"'0_0'"+', 63, 10,3);chiudi_picker();" style="color:#c52525">Annulla programmi manuali</li> ';
}


picker_modal_action(btn);


}

function setdom(IDdom, manuale) {
    loader(1);

    var query = { IDdom: IDdom, manuale: manuale };
    $.ajax({
        url: baseurl+versione+'/struttura/set_domotica.php',
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
           var IDpicker=crea_picker(()=>{},{'height':'75%'});
           $('#'+IDpicker+'.stampa_contenuto_picker').html(data);



        }
    });
}


function pulsacc(IDdom,tipo) {
   var accendi='';
   var spegni='';
    if(tipo==2){
        accendi='accendidom('+IDdom+',1)';
        spegni='accendidom('+IDdom+',0)';
    }else{
        accendi='accendidom2('+IDdom+',1)';
        spegni='accendidom2('+IDdom+',0)';
    }

    var btn='<li onclick="'+accendi+';chiudi_picker();">Accendi</li>';
    var btn=btn+'<li onclick="'+spegni+';chiudi_picker();">Spegni</li>';


    picker_modal_action(btn);
}


function accendidom(ID, acc) {
    var giornic3 = '';
    var mioArray = $('#giornidom');
    var lun = mioArray.length; //individuo la lunghezza dell’array
    for (n = 0; n < lun; n++) { //scorro tutti i div del documento
        if (mioArray.item(n).checked == true) {
            var gg = mioArray.item(n).getAttribute('value');
            giornic3 = giornic3 + gg + ','
        }
    }
    var val = acc + '_2_' + $('#accendi').val() + '_' + $('#spegni').val() + '_' + giornic3;
    modprenot(ID, val, 63, 10,()=>{ navigation(12,0);});
}

function accendidom2(ID, acc) {
    var val = acc + '_1_' + $('#oreatt').val();
    modprenot(ID, val, 63, 10,()=>{ navigation(12,0);});
}

function pulsanti_pulizie(el) {


    var IDalloggio=$(el).attr('data-appartamento');
    var pulsanti=atob($('#pulsanti'+IDalloggio).val());


    picker_modal_action(pulsanti);

    $('.prenotazione_pulizia').on('click',function(){
        var IDpren=$(this).attr('data-prenotazione');
        chiudi_picker();
        apri_pren_pul(IDpren);
    });


    $('.stato_pulizia').on('click',function(){
        var stato=$(this).attr('data-tipo');
        chiudi_picker();
        modprenot(IDalloggio,stato,17,10,()=>{ aggiorna_pulizie();});
    });


}



function aggiorna_pulizie(){

    var tipo=$('#tipo_pulizia').val();
    var data=$('#navigation_data').val();

    if(tipo==0){
            navigation(13,{'time':data},0,0);
    }else{
        loader(1);
        $.ajax({
                url: baseurl+versione+'/struttura/pulizie/pulizie_settimana.php',
                method: 'POST',
                dataType: 'text',
                cache:false,
                timeout:5000,
                data: {data:data },
                 error: function (data) {
                    loader();
                },
                success: function (data) {

                    $('.pul').removeClass('active');
                    $('#pul_set').addClass('active');
                    loader();
                    $('#container').html(data);
                     aggiorna_menu(13,0);

/*
                        $('.div_btn_pulizie .pul').removeClass('active');
                        $('.div_btn_pulizie .pul[data-tipo="'+tipo+'"]').addClass('active');
                        */

                            $('#puliziediv .body').scroll(function() {

                                $('#puliziediv .side').scrollTop($(this).scrollTop());

                                $('#puliziediv .header').scrollLeft($(this).scrollLeft());
                            });

                            var h=parseInt($('body').innerHeight() - 75);

                            $('#puliziediv').css('height',h+'px');
                }
        });
    }
}

function cambia_tipo_pulizia(el){

    $('.div_btn_pulizie .pul').removeClass('active');
    var tipo_pulizia=$(el).attr('data-tipo');
    $(el).addClass('active');
    $('#tipo_pulizia').val(tipo_pulizia);
    aggiorna_pulizie();
}

function apri_pren_pul(IDpren) {
    loader(1);
    $.ajax({
            url:  baseurl+versione+'/struttura/pulizie/pulizie_dettaglio.php',
            method: 'POST',
            dataType: 'text',
            cache:false,
            timeout:5000,
            data: {
                IDpren:IDpren

            },
             error: function (data) {
                 loader();
            },
            success: function (data) {
                 loader();

                 var IDpicker=crea_picker(()=>{},{'height':'75%'});
                 $('#'+IDpicker+'.stampa_contenuto_picker').html(data);

            }

    });
}



function mostra_pren(tipo){

    $('.btn_tab_pren').removeClass('active');
    $('.tab_pren_pul').fadeOut();

    $('.btn_tab_pren.'+tipo).addClass('active');
    $('.tab_pren_pul.'+tipo).fadeIn();

    if(tipo==2){
        $('.btn_add').fadeIn();
    }else{
        $('.btn_add').fadeOut();
    }

}



function aumenta_serv(metodo,id,tipo){
    var consta=1;
    var idpren=$('.title-tab.selected').attr('alt');


    switch(metodo){
        case 1:
            var qta=$('#qta'+id).html();
            var max=$('#qta'+id).attr('max');
            var min=$('#qta'+id).attr('min');
        break;
        case 2:
            var qta=$('#mod'+id).html();
            var max=$('#mod'+id).attr('max');
            var min=$('#mod'+id).attr('min');
        break;
    }
    qta=parseFloat(qta);
    switch(tipo){
        case 1:
            max=parseFloat(max);
            if(qta<max){qta=qta+consta;}
        break;
        case 2:
            min=parseFloat(min);
            if(qta>min){qta=qta-consta;}
        break;
    }

    switch(metodo){

        case 1:
            $('#qta'+id).html(qta);
        break;
        case 2:
            $('#mod'+id).html(qta);
            var idprenextra=$('#mod'+id).attr('alt');
            modprenextra(idprenextra,id+'_'+tipo+'_'+qta,58,9,5);
        break;
    }


}


function inserisci_addebito_pulizie(){

    var IDprenotazione=$('#idpren_pul').val();

    var prodotti={};
    $('.serv').each(function() {
        var qta=parseInt($(this).html());
        if(qta>0){
            var id=$(this).attr('alt');
            prodotti[id]=qta;
            //
            //prod_addeb=prod_addeb+id+'_'+qta+',';
        }
    });

    modprenextra(IDprenotazione, prodotti, 57, 9, ()=>{ ricarica_addebito()});

}

function mostra_tab_addebita(tipo){

    if(tipo==0){
        $('.btn_add').fadeIn();
    }else{
        $('.btn_add').fadeOut();
    }

    UIkit.switcher('#tab_addebita').show(tipo);
}


function ricarica_addebito(){

    var idpren=$('#idpren_pul').val();

    $('.serv').each(function() {
        var qta=$(this).html('0');
    });

    loader(1);


    $.ajax({
            url: baseurl+versione+'/struttura/pulizie/pulizie_addebito.php',
            method: 'POST',
            dataType: 'text',
            cache:false,
            timeout:5000,
            data: {
                IDpren:idpren
            },
             error: function (data) {
                loader();
            },
            success: function (data) {

                loader();
                $('.tab_addebita.2').html(data);
                mostra_tab_addebita(1);

            }

    });

}

function nascondi_addebito(IDserv){
    $('.pul_serv'+IDserv).remove();
}

function apri_giornaliere(time) {
     loader(1);

    var query = { time: time };
    $.ajax({
        url: baseurl+versione+'/struttura/prenotazioni_giornaliere.php',
        method: 'POST',
        dataType: 'text',
        timeout: 5000,
        cache: false,
        data: query,
        success: function(data) {
            loader();

            var IDpicker=crea_picker(()=>{},{'height':'75%'});
            $('#'+IDpicker+'.stampa_contenuto_picker').html(data);
        },
        error: function(data) {
            loader();
        }
    });
}


function apri_esclusivi(time) {


    var query = { time: time };
    $.ajax({
       url: baseurl+versione+'/struttura/prenotazioni_esclusive.php',
        method: 'POST',
        dataType: 'text',
        timeout: 5000,
        cache: false,
        data: query,
        success: function(data) {

             loader();

            var IDpicker=crea_picker(()=>{},{'height':'75%'});
            $('#'+IDpicker+'.stampa_contenuto_picker').html(data);

        },
        error: function(data) {
           loader();
        }
    });
}

function apri_annullate(time) {


    var query = { time: time };
    $.ajax({
        url: baseurl+versione+'/struttura/prenotazioni_annullate.php',
        method: 'POST',
        dataType: 'text',
        timeout: 5000,
        cache: false,
        data: query,
        success: function(data) {
          loader();
            var IDpicker=crea_picker(()=>{},{'height':'75%'});
            $('#'+IDpicker+'.stampa_contenuto_picker').html(data);
        },
        error: function(data) {
           loader();
        }
    });
}



function funzioni_det_pren(IDprenotazione){


     var btn=`<li onclick="navigation(19,{IDprenotazione:`+IDprenotazione+`});chiudi_picker();">Sposta Prenotazione</li>`;
     btn=btn+`<li onclick="msgboxelimina(`+IDprenotazione+`,1,0,2);chiudi_picker();openp=0" style="color:#d80404">Annulla Prenotazione</li>`;

     picker_modal_action(btn)

}



function msgboxelimina(id, tipo, altro, id2, url) {

    var arrtipiel = new Array("", "la prenotazione", "la scheda numero " + id, "il servizio", "l'album", "la foto", "il parametro", "l'orario", "8", "9", "la mansione", "il soggetto dal personale",
        "il messaggio Newsletter", "la fascia oraria", "il cliente dalla prenotazione", "la nota", "la Fattura/Ricevuta", "il prodotto dalla Fattura/Ricevuta",
         "l'acconto selezionato", "il Fornitore", "la Vendita", "il pagamento", "l'Agenzia", "la Ricevuta/Fattura", "i servizi selezionati", "l'abbuono", "la limitazione? Tutti le agenzie con la stessa limitazione subiranno lo stessa elimazione. Continuare", "il cofanetto regalo",
          "il voucher", "il servizio", "il documento", "la spedizione", "il servizio", "il prodotto dal tavolo", '34', 'il tavolo');

   UIkit.modal.confirm('Vuoi davvero eliminare '+arrtipiel[tipo]+'?').then(function () {

    //myApp.confirm('Vuoi davvero eliminare ' + arrtipiel[tipo] + '?<br><br>' + agg, function() {
        switch (tipo) {
            case 1:
            case 2:
            case 4:
            case 5:
            case 6:
            case 10:
            case 11:
            case 20:
            case 21:
            case 25:
                elimina(id, tipo, altro, id2, url);
                break;
            case 3:
                //id=$$('#tr'+id).attr('lang');
                elimina(id, tipo, altro, id2, url);
                break;
            case 32:
                modprofilo(id, 0, 5, 10, 4);
                break;
            case 33:
                //alert(altro);
                //alert(id);
                modprenextra(altro, id, 30, 9, 6);
                break;
            case 35:
                modprenextra(id, 0, 37, 9, 27);
                break;
        }
    }, function () {
           console.log('Rejected.')
   });
}



function elimina(id, tipo, altro, agg, url) {
    loader(1);

    var query = { ID: id, tipo: tipo, altro: altro };
    $.ajax({
        url: baseurl+'config/elimina.php',
        method: 'POST',
        dataType: 'text',
        cache: false,
        timeout: 5000,
        data: query,
        success: function(data) {

            loader();

             UIkit.notification("...", {
                    message:'Rimozione effettuata con successo',
                    status: 'main_notifica',
                    pos:'bottom-center',
                    timeout:1000}
                 );


            switch (agg) {


                case 1:
                    esegui_old_navigation();
                break;

                case 2:
                    goBack();
                break;
            /*
                case 1:
                    if (isNaN(altro)) {
                        var ff = 'riaggvis("' + altro + '")';
                        eval(ff);
                    } else {
                        switch (mainView.activePage.name) {
                            case 'explodeservice':
                                var txtsend2 = $$('#txtsend2').val();
                                navigation(22, txtsend2, 0, 1);
                                break;
                            default:
                                var IDpren = $$('#IDprenfunc').val();
                                navigationtxt(2, IDpren + ',1', 'contenutop', 1);
                                break;
                        }
                    }
                    break;
                case 2:
                    var time = $$('#IDprentime').val();
                    mainView.router.back();
                    navigationtxt(3, time, 'calendariodiv', 0);
                    var sea = $$('#searchp').val();
                    navigationtxt(19, sea, 'prenotazionidiv', 0);
                    break;
                case 4:
                    var IDpren = $$('#IDprenfunc').val();
                    navigationtxt(2, IDpren + ',3', 'contenutop', 1);
                    break;
            }
            */


        }
    },
        error: function(data) {
           loader();
        }
    });
}



function esegui_old_navigation(){
     var last_elem=history_navigation.length-1;
     var old_nav=history_navigation[last_elem];
     eval(old_nav);
}

/*
function modpagamenti(ID){

     var altbutton=parseInt($('#'+ID).attr('alt'));
     var btn='';

     switch(altbutton){

        case 1:
            //   navigation2(17,ID+',0',0,0);
            btn=btn+`<li onclick="chiudi_picker();">Apri Dettaglio</li>`;

            btn=btn+`<li   onclick=" msgboxelimina(`+ID+`, 21, 0, 1, 4);chiudi_picker();" style="color:#d80404">Elimina</li>`;
        break;

        case 2:
            var prezzo=$('#'+ID).attr('pr');
            prezzo=prezzo.slice(0,-2);

             btn=btn+`<li onclick="chiudi_picker();"  class="metodo_pagamento_btn" alt="2">Acconto</li>`;
             btn=btn+`<li onclick="chiudi_picker();" class="metodo_pagamento_btn"  alt="14">Caparra</li>`;

                    setTimeout(function(){
                    var input='';
                    UIkit.util.on('.metodo_pagamento_btn', 'click', function (e){
                        var metodo_pagamento=$(this).attr('alt');

                      UIkit.modal.prompt('Inscerisci il Prezzo:',input).then(function (input) {
                        if(input){
                            if(!isNaN(input)){
                                input= parseFloat(input);
                                //IDpren
                                 navigation(20,[ID,'1',metodo_pagamento,input],0,0);
                                //navigation2(17,ID+',1,+pag+,'+input,7,0);
                            } else{
                                  UIkit.modal.dialog('<p class="uk-modal-body">Inserire un numero. Prego riprovare!</p>');
                            }
                        }

                       });
                 });
                },200);



              btn=btn+`<li onclick="chiudi_picker();">Saldo</li>`;//navigation2(17,ID+',1,1,'+prezzo,7,0);



        break;


     }
     picker_modal_action(btn)
}
*/

function modifica_anagrafica_nuovo_preventivo(){
     var IDpreventivo=get_IDpreventivo();
        loader(1);

        var query = { IDpreventivo:IDpreventivo};
        $.ajax({
            url: baseurl+versione+'/struttura/preventivi/preventivo_anagrafica.php',
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

               var IDpicker=crea_picker(()=>{ricarica_anagrafica_preventivo();stampa_carrello_preventivo()},{'height':'92%'});

               $('#'+IDpicker+'.stampa_contenuto_picker').html(data);



            }
      });
}


function prev_set_prenotante(id,tipo){
    var IDpreventivo=get_IDpreventivo();
    gestione_preventivo(3, { id: IDpreventivo, campo: 'prenotante', value: [id, tipo] }, function(arg) {
          chiudi_picker();
          if(id==0){
            modifica_anagrafica_nuovo_preventivo();
          }
    });
}


function inscerisci_cliente_preventivo(IDcliente){
var IDpreventivo=get_IDpreventivo();
    gestione_preventivo(3, { id: IDpreventivo, campo: 'cliente', value:IDcliente }, function(arg) {
         chiudi_picker();
    });

}

function nuovo_cliente_preventivo(testo_cliente){
var IDpreventivo=get_IDpreventivo();
    gestione_preventivo(16, { id: IDpreventivo,cliente: testo_cliente }, function(arg) {
         chiudi_picker();modifica_anagrafica_nuovo_preventivo();
    });

}


function mod_preventivo(azione, id, campo, tipo_campo, callback = null) {
    loader(1);
    var val = null;
    switch (tipo_campo) {
        case 8:
        case 'html_id':
            val = $('#' + campo).html();
            break;
        case 9:
        case 'val_id':
            val = $('#' + campo).val();
            break;
        case 10:
        case 'var':
            val = campo;
            break;
        case 11:
        case 'val_dom':
            val = $(campo).val();
            break;
        default:
            break;
    }

    data = {};
    data.request = azione;
    data.id = id;
    data.value = val;

    $.post(baseurl+'config/preventivatore/gestione_preventivatore.php', data, function(result) {
        //console.log(result);
        loader(0);
        if (typeof callback === "function") {
            callback(result);
            return;
        }
        switch (callback) {

        }
    });

}

function modifica_dettagli_nuovo_preventivo(){
        var IDpreventivo=get_IDpreventivo();
        loader(1);

        var query = { IDpreventivo:IDpreventivo};
        $.ajax({
            url: baseurl+versione+'/struttura/preventivi/preventivo_dettagli.php',
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


               var IDpicker=crea_picker(()=>{ricarica_dettagli_preventivo();inizia_ricerca_preventivo()},{'height':'92%'});

               $('#'+IDpicker+'.stampa_contenuto_picker').html(data);

            }
      });
}

function modifica_data_preventivo(){
    var IDpreventivo=get_IDpreventivo();

    var d1 = $('#preventivo_arrivo').val().split('/').reverse().join('-');
    var d2 = $("#preventivo_partenza").val().split('/').reverse().join('-');

    if (!$(".div_partenza_prev").is(':visible')){
        d2 = d1;
        $("#preventivo_partenza").val($('#preventivo_arrivo').val());
    } else if (d1 >= d2) {
        var d = new Date(d1);
        d.setDate(d.getDate() + 1);
        d2 = d.toISOString().substring(0,10).split('-').reverse().join('/');
        $("#preventivo_partenza").val(d2);
    }

    var giorno_arr = $('#preventivo_arrivo').val();
    var giorno_parr = $('#preventivo_partenza').val();

    gestione_preventivo(3, { id: IDpreventivo, campo: 'data', value:  [giorno_arr, giorno_parr]}, function(arg) {
         ricarica_dettagli_preventivo()
    });


}




function mostra_giornaliero_preventivo(tipo){
    var IDpreventivo=get_IDpreventivo();
    switch(tipo){
        case 'giornaliero':
         $('.div_partenza_prev').hide();
        break;
        default:
            $('.div_partenza_prev').show();
        break;
    }


    gestione_preventivo(3, { id: IDpreventivo, campo: 'gruppo', value:0 }, function(arg) { });

    modifica_data_preventivo();
}



 function inizia_ricerca_preventivo(){
    var IDpreventivo=get_IDpreventivo();
    var visualizza_pacchetto=$('#tipo_ricerca_preventivo').val();
    $('#overlay_ricerca_preventivo').show();


        var query = { IDpreventivo:IDpreventivo,visualizza_pacchetto:visualizza_pacchetto};
        $.ajax({
            url: baseurl+versione+'/struttura/preventivi/ricerca_preventivo.php',
            method: 'POST',
            dataType: 'text',
            cache: false,
            timeout: 5000,
            data: query,
            error: function(data) {
               //loader();
               $('#overlay_ricerca_preventivo').hide();
            },

            success: function(data) {
               //loader();
               $('#overlay_ricerca_preventivo').hide();
                $('#richieste_preventivo').html(data);

            }
      });
 }


 function seleziona_retta(el){
    var IDretta=$(el).attr('data-id');
    $('.seleziona_retta').removeClass('retta_selezionata');
    $(el).addClass('retta_selezionata');

    $('.categorie_rette').removeClass('active');
    $('.categorie_rette[data-retta="'+IDretta+'"]').addClass('active');
 }

 function inserisci_richiesta_preventivo(el){
   var IDpreventivo=get_IDpreventivo();
    var IDservizio=$(el).attr('data-servizio');
    var IDcategoria=$(el).attr('data-categoria');
    var alloggi=$(el).attr('data-alloggi');


    var testo_sconti=atob($(el).attr('data-sconti'));
    var testo_supplementi=atob($(el).attr('data-supplementi'));


    //console.log(testo_sconti,testo_supplementi);

    let inserisci_retta=()=>{
        gestione_preventivo(5,{ id: IDpreventivo, pacc: IDservizio, alloggi: alloggi, categoria: IDcategoria},()=>{stampa_carrello_preventivo()});
     };

    if(alloggi){
        alloggi = alloggi ? alloggi.split(',') : null;

        var query = { alloggi:alloggi};
        $.ajax({
            url: baseurl+versione+'/struttura/preventivi/alloggi_preventivo.php',
            method: 'POST',
            dataType: 'text',
            cache: false,
            timeout: 5000,
            data: query,
            error: function(html) { },
            success: function(html) {
                 picker_modal_action(html,testo_sconti+testo_supplementi);
                $('.inserisci_richiesta').on('click',function(){
                       alloggi=$(this).attr('data-id');
                        inserisci_retta();
                        chiudi_picker();
                });
            }
      });
    }else{
        inserisci_retta();
    }

 }


 function ricarica_anagrafica_preventivo(){
        var IDpreventivo=get_IDpreventivo();
        loader(1);

        var query = { IDpreventivo:IDpreventivo};
        $.ajax({
            url: baseurl+versione+'/struttura/preventivi/dettaglio_anagrafica_text.php',
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

                $('#dettagli_anagrafica_preventivo').html(data);

            }
      });
 }

  function ricarica_dettagli_preventivo(){
       var IDpreventivo=get_IDpreventivo();

        loader(1);

        var query = { IDpreventivo:IDpreventivo};
        $.ajax({
            url: baseurl+versione+'/struttura/preventivi/dettaglio_richiesta_text.php',
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

                $('#dettagli_preventivo').html(data);

            }
      });
 }


 function stampa_carrello_preventivo(){
      var IDpreventivo=get_IDpreventivo();

        loader(1);

        var query = { IDpreventivo:IDpreventivo};
        $.ajax({
            url: baseurl+versione+'/struttura/preventivi/carrello_preventivo.php',
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

               if(data){
                $('#carrello_preventivo').addClass('mostra_carrello');
               }else{
                $('#carrello_preventivo').removeClass('mostra_carrello');
               }

                $('#carrello_preventivo').html(data);

            }
      });
 }


 function pulsanti_opzioni_preventivo(){

    var btn=$('#pulsanti_picker').html();
    picker_modal_action(btn);
    $('.pulsante_carrello_preventivo').on('click',function(){
        var tipo=$(this).attr('data-tipo');
         chiudi_picker();
        preventivatore_salva(tipo);

    });
 }



 function dettagli_richiesta_preventivo(IDrichiesta){
        var IDpreventivo=get_IDpreventivo();
        loader(1);

        var query = { IDpreventivo:IDpreventivo,IDrichiesta:IDrichiesta};
        $.ajax({
            url: baseurl+versione+'/struttura/preventivi/dettagli_richiesta.php',
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

               var IDpicker=crea_picker(()=>{stampa_carrello_preventivo()},{'height':'92%'});


               $('#'+IDpicker+'.stampa_contenuto_picker').html(data);
               switch_tab_dettagli_richiesta(1);


            }
      });

 }

 function elenco_richieste(){
     var IDpreventivo=get_IDpreventivo();
         loader(1);

        var query = { IDpreventivo:IDpreventivo};
        $.ajax({
            url: baseurl+versione+'/struttura/preventivi/elenco_richieste.php',
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

               var IDpicker=crea_picker(()=>{});
               $('#'+IDpicker+'.stampa_contenuto_picker').html(data);


            }
      });
 }

 function inserisci_prenotazione(el){
    let lista_richieste=[];

    if (typeof el == "number" || typeof el== "string") {
        lista_richieste.push(el);
    } else {
        $('.elenco_richieste').each(function(){
            var req=$(this).attr('data-request');
            if ($(this).find('input').is(':checked')) {
                lista_richieste.push(req);
            }

        });
    }



    if(lista_richieste){

        gestione_preventivo(10, {IDrequest: lista_richieste}, function(html){
            if (html.indexOf("error") == -1) {
                 chiudi_picker();
                   UIkit.notification("...", {
                    message:'Prenotazione Effettuta con successo.',
                    status: 'main_notifica',
                    pos:'bottom-center',
                    timeout:4000}
                 );

                let IDprenotazione = parseInt(html);
                goBack();
                navigation(6,[IDprenotazione]);
          }else{
             chiudi_picker();
               UIkit.notification("...", {
                    message:'La prenotazione non è stata inserita. La categoria di alloggio selezionata potrebbe non essere più disponibile.',
                    status: 'danger',
                    pos:'top-center',
                    timeout:4000}
                 );

          }
        });

    }

 }


 function gestione_preventivo(azione, data = null, callback = null){
    //funzione principale del preventivatore (simile a modproserv, ma non si occupa del recupero dati)
    loader(1);
    //se non è passato alcun id, prede in autimatico l'id del preventivo attualmente aperto
    if (!data) {
        data = {};
    }

    if (typeof data.id === "undefined") {
        let id = get_IDpreventivo();
        if (id) {
            data.id = id;
        }
    }

    data.request = azione;
    $.post(baseurl+'config/preventivatore/gestione_preventivatore.php', data, function(result) {
        //console.log(result);
        loader(0);
        if (typeof callback === "function") {
            callback(result);
        }
    });
 }

 function get_IDpreventivo(){
      var IDpreventivo=$('#IDpreventivo').val();
      return IDpreventivo;
 }


 function modal_cambia_valore_richiesta(IDregola,tipo){
        var input='';
          UIkit.modal.prompt('Inscerisci il Prezzo:',input).then(function (input) {

            if(input){
                if(!isNaN(input)){
                    input= parseFloat(input);
                    switch(tipo){
                        case 1://deposito
                          modprenot(IDregola,input,235,10,()=>{switch_tab_dettagli_richiesta(2)});
                        break;
                        case 2://cancellazione
                          modprenot(IDregola,input,287,10,()=>{switch_tab_dettagli_richiesta(2)});
                        break;
                    }
                } else{
                      UIkit.modal.dialog('<p class="uk-modal-body">Inserire un numero. Prego riprovare!</p>');
                }
            }

           });
 }

 function switch_tab_dettagli_richiesta(tab){

        var query = { tab_scelta:tab};
        $.ajax({
            url: baseurl+versione+'/struttura/preventivi/tab_dettagli_richiesta.php',
            method: 'POST',
            dataType: 'text',
            cache: false,
            timeout: 5000,
            data: query,
            error: function(data) {
               //loader();
            },

            success: function(data) {
              // loader();

                $('#dettagli_tab').html(data);

            }
      });

 }

 function modifica_conti(el,callback=null){
    var riga_padre=$(el).parent().closest('.lista_conti');
    var id=$(riga_padre).data('id');
    var lista_addebiti_collegati=$(riga_padre).data('lista_addebiti_collegati');
    var elimina=parseInt($(riga_padre).data('eliminabile'));
    var prezzo= parseFloat($(riga_padre).data('prezzo'));
    var tipo_riferimento=parseInt($(riga_padre).data('tipo_riferimento'));
    var IDriferimento=$(riga_padre).data('idriferimento');
    var modifica_prezzo=parseInt($(riga_padre).data('modifica_prezzo'));


    var btn='';

          //btn+=`<li  onclick=" chiudi_picker();">Apri Dettaglio </li>`;
        if(modifica_prezzo==1){
            btn+=`<li  onclick="chiudi_picker();" id="mod_prezzo" >Modifica Prezzo</li>`;
              setTimeout(function(){
                UIkit.util.on('#mod_prezzo', 'click', function (e){
                  UIkit.modal.prompt('Modifica il Prezzo:',prezzo).then(function (prezzo) {
                    if(prezzo){
                        if(!isNaN(prezzo)){
                            prezzo= parseFloat(prezzo);
                              mod_riferimento(2,[IDriferimento,tipo_riferimento,lista_addebiti_collegati], prezzo,10,()=>{callback});
                        } else{
                              UIkit.modal.dialog('<p class="uk-modal-body">Inserire un numero. Prego riprovare!</p>');
                        }
                    }

                   });
             });
            },200);
          }


        if($('#sposta_conto'+id).length>0){
            btn+=atob($('#sposta_conto'+id).val());
        }


        if (elimina == 1) {
            btn+=`<li   onclick="chiudi_picker();mod_riferimento(1,[`+IDriferimento+`,`+tipo_riferimento+`],'`+lista_addebiti_collegati+`',10,()=>{`+callback+`});" style="color:#d80404">Elimina</li>`;
        }



    picker_modal_action(btn);
}

function modifica_conto_totale(el){
    var prezzo=parseFloat($(el).data('prezzo'));
    var lista_addebiti_collegati=$(el).data('lista_addebiti_collegati');
    var tipo_riferimento=parseInt($(el).data('tipo_riferimento'));

      UIkit.modal.prompt('Modifica Totale:',prezzo).then(function (prezzo) {
    if(prezzo){
        if(!isNaN(prezzo)){
            prezzo= parseFloat(prezzo);
              mod_riferimento(4,[tipo_riferimento,lista_addebiti_collegati], prezzo,10,()=>{callback});
        } else{
              UIkit.modal.dialog('<p class="uk-modal-body">Inserire un numero. Prego riprovare!</p>');
        }
    }

   });
}

function mostra_addebiti(el){
 var id=$(el).data('contain') + '';
 var addebiti_visibili=0;
    if (id.indexOf(",")>0) {
        var lista=id.split(',');
        var count_addebiti=lista.length;
        $.each(lista, function( index, value ) {
            if($('.lista_conti[data-ref="'+value+'"]').is(':visible')){
                addebiti_visibili++;
            }
        });
        $.each(lista, function( index, value ) {
            if(addebiti_visibili==count_addebiti){
             $('.lista_conti[data-ref="'+value+'"]').fadeOut();
         }else{
             $('.lista_conti[data-ref="'+value+'"]').fadeIn();
         }
         creasessione(value,179);
        });
    }else{
        $('.lista_conti[data-ref="'+id+'"]').fadeToggle();
          creasessione(id,179);
    }
}


 function informazioni_preventivo(){
         var IDpreventivo=get_IDpreventivo();
        loader(1);

        var query = { IDpreventivo:IDpreventivo};
        $.ajax({
            url: baseurl+versione+'/struttura/preventivi/informazioni_preventivo.php',
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

               var IDpicker=crea_picker(()=>{},{'height':'92%'});
               $('#'+IDpicker+'.stampa_contenuto_picker').html(data);
               contenuto_informazioni_preventivo(1);
            }
      });

 }

  function contenuto_informazioni_preventivo(tab){
     var IDpreventivo=get_IDpreventivo();


        var query = {IDpreventivo:IDpreventivo,tab:tab};
        $.ajax({
            url: baseurl+versione+'/struttura/preventivi/contenuto_informazioni_preventivo.php',
            method: 'POST',
            dataType: 'text',
            cache: false,
            timeout: 5000,
            data: query,
            error: function(data) {
               //loader();
            },

            success: function(data) {
              // loader();
                $('#dettagli_tab').html(data);
            }
      });

 }



 function mod_impostazioni_prev(tipo, id, campo, val2, agg) {
    switch (val2) {
        case 0:
            var val = $('#' + campo).val();
            break;
        case 1:
            var val = $('#' + campo).val();
            val = val.replace(',', '.');

            if (isNaN(val)) {
                alertify.alert("E' necessario inserire un numero. Prego Riprovare");
                return false;
            }
            break;
        case 6:
            var val = $('#' + campo).val();
            val = val.replace(/\n/g, "<br/>"); //descrizione
            //val=encodeURIComponent(val);
            break;
        case 7:
            //var val=$('#'+campo).val();
            if (document.getElementById(campo).checked == true) { //si o no
                val = '1';
            } else {
                val = '0';
            }
            break;
        case 8:
            var val = $('#' + campo).html();

            break;
        case 9:
            var val = $('#' + campo).val();
            break;
        case 10:
            val = campo;
            break;
        case 11:
            var val = $(campo).val();
            break;
        case 12:
            var myRadio = $('input[name=' + campo + ']');
            val = myRadio.filter(':checked').val();
            break;
        case 13:
            if ($(campo).is(":checked")) { //si o no
                val = '1';
            } else {
                val = '0';
            }
            break;
        case 14: //replica per errore (Non cancellare)
            var val = $('#' + campo).summernote('code');
            break;

        default:
            var val = $('#' + campo).val();
            break;
    }
  loader(1);

    $.ajax({
        type: "POST",
        url: baseurl+'config/gestione_impostazioni_prev.php',
        data: { val: val, tipo: tipo, ID: id, val2: val2 },
        cache: false,
        timeout: 5000,
        error: function() {
              loader();
        },
        success: function(html) {
               loader();
            console.log(html);

            if (typeof agg == "function") {
                    agg();
            }
        }
    });
}

function preventivatore_salva(tipo){

    gestione_preventivo(9, { tipo: tipo }, function(arg) {
          chiudi_picker();
    });

}

function set_scadenza_preventivo(scadenza=null){
    var IDpreventivo=get_IDpreventivo();
    if(scadenza==null){
        var scadenza=$('#preventivo_partenza').val();
    }

    mod_impostazioni_prev(7,IDpreventivo,scadenza,10,()=>{contenuto_informazioni_preventivo(1)});
}



function modfica_prezzo_richiesta(el){
      var input='';
      var IDrichiesta=$('#richiesta_ID').val();
      var tipo=$(el).attr('data-tipo');
        UIkit.modal.prompt('Inscerisci il Prezzo:',input).then(function (input) {
          if(input){
              if(!isNaN(input)){
                  input= parseFloat(input);
                  mod_preventivo(23,IDrichiesta.split(','),[input,tipo,null],10,() => {switch_tab_dettagli_richiesta(4)});

              } else{
                    UIkit.modal.dialog('<p class="uk-modal-body">Inserire un numero. Prego riprovare!</p>');
              }
          }

         });
}


function prev_elimina_variazioni(IDrequest, variazioni = null) {
  gestione_preventivo(17, { id: IDrequest, value: variazioni }, function() {
    switch_tab_dettagli_richiesta(4);
  });
}

function modifica_variazione_richiesta(el){
    var tipo=$(el).attr('data-tipo');
    var IDrichiesta=$('#richiesta_ID').val();
    var testo=$(el).attr('data-testo');

    var value = '';

    var varia = null;

    var valore='';
    UIkit.modal.prompt(testo,valore).then(function (valore) {
      if(valore){
         // if(!isNaN(valore)){
              // valore=parseFloat(valore);

                switch (parseInt(tipo)) {
                  case 0:
                    value=parseFloat(valore);
                  break;
                  case 1:
                      value=parseFloat(valore);
                  break;
                  case 2:
                      value=parseFloat(valore);
                  break;
                  case 3:
                    var  tot_retta=$('#tot-retta').attr('data-totale');
                    value = parseFloat(tot_retta.replace(',','.'));
                    //varia = parseFloat($("#val-perc").val().replace(',','.'));
                    varia=parseFloat(valore.replace(',','.'));
                    value += (value * varia) / 100;

                  break;
                  case 4:
                    var  tot_retta=$('#tot-retta').attr('data-totale');
                    value = parseFloat(tot_retta.replace(',','.'));
                    // varia = parseFloat($("#val-varia").val().replace(',','.'));
                    varia=parseFloat(valore.replace(',','.'));
                    value += varia;
                  break;
                }
                mod_preventivo(23,IDrichiesta.split(','),[value,tipo,varia],10,() => {switch_tab_dettagli_richiesta(4)});
      }else{
          UIkit.notification("...", {
                    message:'Controllare che il valore sia inserito correttamente.',
                    status: 'danger',
                    pos:'top-center',
                    timeout:4000}
             );
      }

     });


}

function scadenza_opzione(IDrichiesta){
  var scadenza=$('#scadenza_opzione').val();
  mod_preventivo(45,IDrichiesta, {field: 'scadenza', value: scadenza +' 00:00' },'var');
}




function mod_messaggi(tipo, id, campo, val2, agg) {
  switch (val2) {
    case 0:
      var val = $('#' + campo).val();
      break;
    case 1:
      var val = $('#' + campo).val();
      val = val.replace(',', '.');

      if (isNaN(val)) {
        alertify.alert("E' necessario inserire un numero. Prego Riprovare");
        return false;
      }
      break;
    case 6:
      var val = $('#' + campo).val();
      val = val.replace(/\n/g, "<br/>"); //descrizione
      //val=encodeURIComponent(val);
      break;
    case 7:
      //var val=$('#'+campo).val();
      if (document.getElementById(campo).checked == true) { //si o no
        val = '1';
      } else {
        val = '0';
      }
      break;
    case 8:
      var val = $('#' + campo).html();

      break;
    case 9:
      var val = $('#' + campo).val();
      break;
    case 10:
      val = campo;
      break;
    case 11:
      var val = $(campo).val();
      break;
    case 12:
      var myRadio = $('input[name=' + campo + ']');
      val = myRadio.filter(':checked').val();
      break;
    case 13:
      if ($(campo).is(":checked")) { //si o no
        val = '1';
      } else {
        val = '0';
      }
      break;
    case 14: //replica per errore (Non cancellare)
      var val = $('#' + campo).summernote('code');
      break;

    default:
      var val = $('#' + campo).val();
      break;
  }
  loader(1);

  $.ajax({
    type: "POST",
    url: baseurl+'config/gestione_messaggi.php',
    data: { val: val, tipo: tipo, ID: id, val2: val2 },
    cache: false,
    timeout: 5000,
    error: function() {
      loader(0);
    },
    success: function(html) {
      loader(0);

        if (typeof agg == "function") {
              agg();
        }
    }
  });
}

function controlla_preventivo(){
      var IDpreventivo=get_IDpreventivo();
      mod_preventivo(1,IDpreventivo,true,10,()=>{goBack();});
}







function switch_tab_menu(obj){
  $(obj).parent().find( '.tasto_menu_default' ).removeClass('selected');
  $(obj).addClass('selected');
}

function switch_tab_content_menu(idbtn,contenitore_tab) {
  $(idbtn).on('click',function(){
    $this=$(this);
    var id_tab=$this.data('tabid');
    $(contenitore_tab).hide();
    $(contenitore_tab+'[data-tabid="'+id_tab+'"]').show();
  });
}


/*Messaggi / Chat*/
timer_messaggi_struttura=0;
function apri_chat_struttura(parametri){//picker contenitore chat
    loader(1);

    var query = { parametri:parametri};
    $.ajax({
        url: baseurl+versione+'/struttura/chat_messaggi/chat_picker.php',
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
           var IDpicker=crea_picker(()=>{clearInterval(timer_messaggi_struttura);},{'height':'75%'});
           $('#'+IDpicker+'.stampa_contenuto_picker').html(data);
        }
  });
}

function apri_preventivo_chat(){
    var IDpreventivo=get_IDpreventivo();
    apri_chat_struttura({IDobj:IDpreventivo,tipoobj:2});
}

function reload_messaggi_struttura(parametri){//elenco messaggi chat preventivo/prenotazione
        loader(1);

        var query = { parametri:parametri};
        $.ajax({
            url: baseurl+versione+'/struttura/chat_messaggi/reload_messaggi_struttura.php',
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
               $('#contenitore_chat').html(data);
               $('.scroll_chat_auto').stop().animate({
                  scrollTop: $('#contenitore_chat')[0].scrollHeight
                 }, 800);
            }
      });
}

function invia_messaggio(){
  var IDobj=$('#IDobj').val();
  var tipoobj=$('#tipoobj').val();

  var messaggio=$('.chat_input_mes').html();
  $('.chat_input_mes').html('');
  switch(parseInt(tipoobj)){
    case 1:
     mod_messaggi(1,IDobj+'_1',messaggio,10,()=>{reload_messaggi_struttura({IDprenotazione:IDobj});});
    break;
    case 2:
     mod_messaggi(2,IDobj+'_1',messaggio,10,()=>{reload_messaggi_struttura({IDpreventivo:IDobj});});
    break;
  }
}


/*Filtri Ricerca*/


function picker_filtro(tipo,onclose=null){
    loader(1);

    $.ajax({
        url: baseurl+versione+'/struttura/filtri_ricerca/'+tipo+'.php',
        method: 'POST',
        dataType: 'text',
        cache: false,
        timeout: 5000,
        data: {},
        error: function(data) {
           loader();
        },

        success: function(data) {
           loader();
           var IDpicker=crea_picker(()=>{onclose},{'height':'75%'});
           $('#'+IDpicker+'.stampa_contenuto_picker').html(data);
        }
  });

}



function ricerca_prenotazioni(){
    loader(1);
    var elem = $('.filtro_ricerca_prenotazione')
    var parametri = {};

    if (elem) {
        elem.each(function() {
            var $this = $(this);
            var v = null;
            if ($this.is(':checked')) {
                v = $this.is(':checked');
            } else {
                v = $this.val();
            }
             if(this.dataset.name=='testo'){
                 parametri[this.dataset.name] = v;
            }else{
                if (v) {
                    parametri[this.dataset.name] = v;
                }
            }

     });
}

    //console.log(parametri);
    $.post('struttura/elenco_prenotazioni_reload.php', { parametri: parametri}, function(html) {
        $('#container_txt_nav').html(html);
          loader();
    });
}

function filtri_ricerca_preventivo(){
      loader(1);
    var elem = $('.filtro_ricerca_preventivo')
    var parametri = {};

    if (elem) {
        elem.each(function() {
            var $this = $(this);
            var v = null;
            if ($this.is(':checked')) {
                v = $this.is(':checked');
            } else {
                v = $this.val();
            }

            if(this.dataset.name=='ricerca'){
                 parametri[this.dataset.name] = v;
            }else{
                if (v) {
                    parametri[this.dataset.name] = v;
                }
            }
        });
    }

    //console.log(parametri);
    $.post('struttura/preventivi/elenco_preventivi_reload.php', { parametri: parametri}, function(html) {
        $('#container_txt_nav').html(html);
        loader(0);
        aggiorna_navbar_preventivi();
    });
}

function aggiorna_navbar_preventivi(){
     var stato=$('[data-name="stato"]').val();
     $.post('struttura/preventivi/preventivi_menu.php', { stato: stato}, function(html) {
        $('#tab_filtri_preventivo').html(html);
        setTimeout(function(){$('.uk_tab_pulizie').animate( { scrollLeft:  $('.uk_tab_pulizie .uk-active').position().left}); },200);
    });
}


function cambia_stato_preventivo_ricerca(stato){
    if(stato==null){
        var stato=$('[data-name="stato"]').val();
    }

     UIkit.tab(".uk-tab").show(stato);
    $(".filtro_ricerca_preventivo").val(stato);
    filtri_ricerca_preventivo();
}

function filtri_ricerca_vendite(){
      loader(1);
    var elem = $('.filtro_ricerca_vendite')
    var parametri = {};

    if (elem) {
        elem.each(function() {
            var $this = $(this);
            var v = null;
            if ($this.is(':checked')) {
                v = $this.is(':checked');
            } else {
                v = $this.val();
            }

            if(this.dataset.name=='ricerca'){
                 parametri[this.dataset.name] = v;
            }else{
                if (v) {
                    parametri[this.dataset.name] = v;
                }
            }
        });
    }

    //console.log(parametri);
    $.post('struttura/negozio/elenco_vendite_reload.php', { parametri: parametri}, function(html) {
        $('#container_txt_nav').html(html);
       loader(0);
    });
}


function ricerca_clienti(){
    loader(1);
    var elem = $('.filtro_ricerca_clienti')
    var parametri = {};

    if (elem) {
        elem.each(function() {
            var $this = $(this);
            var v = null;
            if ($this.is(':checked')) {
                v = $this.is(':checked');
            } else {
                v = $this.val();
            }

             if(this.dataset.name=='testo'){
                 parametri[this.dataset.name] = v;
            }else{
                if (v) {
                    parametri[this.dataset.name] = v;
                }
            }

        });
    }

    //console.log(parametri);
    $.post('struttura/elenco_clienti_reload.php', { parametri: parametri}, function(html) {
        $('#container_txt_nav').html(html);
       loader(0);
    });
}


function ricerca_prenotazioni_be_cm(){
    loader(1);
    var elem = $('.filtro_ricerca_prenotazioni_be_cm')
    var parametri = {};

    if (elem) {
        elem.each(function() {
            var $this = $(this);
            var v = null;
            if ($this.is(':checked')) {
                v = $this.is(':checked');
            } else {
                v = $this.val();
            }

             if(this.dataset.name=='testo'){
                 parametri[this.dataset.name] = v;
            }else{
                if (v) {
                    parametri[this.dataset.name] = v;
                }
            }

        });
    }

    //console.log(parametri);
    $.post('struttura/prenotazioni_be_cm_reload.php', { parametri: parametri}, function(html) {
        $('#container_txt_nav').html(html);
       loader(0);
    });
}


function aggiorna_date_navigation(id_navigation){
    var data=$('#navigation_data').val();
     navigation(id_navigation,[data],0,0);
}


/* Centro benessere*/

function cambia_sala_centro(IDsala){
    var IDsottotip=$('#IDsottotip').val();

    navigation(10,{'IDsotto':IDsottotip,'IDsala':IDsala},()=>{

        setTimeout(function(){$('.uk_tab_pulizie').animate( { scrollLeft:  $('.uk_tab_pulizie .uk-active').position().left}); },200);

    },0);
}


function cambia_time_benessere(){
     var data=$('#navigation_data').val();
     var IDsottotip=$('#IDsottotip').val();
     var IDsala=$('#IDsala').val();
     navigation(10,{'data':data,'IDsotto':IDsottotip,'IDsala':IDsala},0,0);
}


function visualizza_centro_benessere(el){
    var time=$(el).data('time');
    if($(el).hasClass('selezionata')){
        $('.occupazione_colonna').removeClass('selezionata');
        $('.servizi_confermati').css('display','block');
    }else{
        $('.occupazione_colonna').removeClass('selezionata');
        $(el).addClass('selezionata');
        $('.servizi_confermati').css('display','none');
        $('.servizi_confermati[data-time*="'+time+'"]').css('display','block');
    }
    mostra_tab($('.pulsanti_tab[data-tab="3"]'));
}

function mostra_tab(el){
    $('.pulsanti_tab').removeClass('active')
    $('.container_tab div.tab').css('display','none');

    var tab=$(el).data('tab');
    $('.container_tab div.tab[data-tab="'+tab+'"]').css('display','block');
    $(el).addClass('active')
}

function ricarica_pagina_centro_benessere_giorno(IDsala){
    var IDsottotip=$('#IDsottotip').val();
    var tab_attiva=$('.pulsanti_tab.active');

    navigation(10,{'IDsotto':IDsottotip,'IDsala':IDsala},()=>{

        setTimeout(function(){
          $('.uk_tab_pulizie').animate( { scrollLeft:  $('.uk_tab_pulizie .uk-active').position().left});  },200);
          mostra_tab($(tab_attiva));

    },0);
}

function disponibilita_servizio(dati,data_modifica =0,onclose=null){

    loader(1);

    var query = {dati:dati,data_modifica:data_modifica};
    $.ajax({
        url: baseurl+versione+'/struttura/centro_benessere/orari_servizi.php',
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
            var IDpicker=crea_picker(()=>{if(onclose){onclose();}},{'height':'75%'});
            $('#'+IDpicker+'.stampa_contenuto_picker').html(data);
           }

        }
  });
}

function cambia_tipo_data(el){
     var tipo=$(el).data('ora');
     var IDsottotip=$('#IDsottotip').val();
     var IDsala=$('#IDsala').val();

     creasessione(tipo,173,()=>{navigation(10,{'IDsotto':IDsottotip,'tipo':tipo},0,0)});
}

function cambia_time_trattamenti(){
     var data=$('#navigation_data').val();
     var IDsottotip=$('#IDsottotip').val();
     var tipo=$('#tipo').val();
     navigation(18,{'data':data,'IDsotto':IDsottotip,'tipo':tipo},0,0);
}

function cambia_tipo_trattamenti(tipo){
     var data=$('#navigation_data').val();
     var IDsottotip=$('#IDsottotip').val();

     navigation(18,{'data':data,'IDsotto':IDsottotip,'tipo':tipo},0,0);
}

function visualizza_personale(el){
     var tipo_visualizzazione=$(el).data('personale');
     var IDsottotip=$('#IDsottotip').val();
     var tipo=$('#tipo').val();

     creasessione(tipo_visualizzazione,174,()=>{navigation(18,{'IDsotto':IDsottotip,'tipo':tipo},0,0)});
}


function creasessione(valore,tipo,callback=null) {


    var query = { valore: valore, tipo: tipo };
    $.ajax({
        url: baseurl + 'config/creasessione.php',
        method: 'POST',
        dataType: 'text',
        cache: false,
        timeout: 5000,
        data: query,
        success: function(data) {
           callback();
        },
        error: function(data) {
            myApp.hideIndicator();
        }
    });
}


/* configurazioni */

function configurazioni_calendario(){
    loader(1);


    $.ajax({
        url: baseurl+versione+'/struttura/configurazioni/calendario.php',
        method: 'POST',
        dataType: 'text',
        cache: false,
        timeout: 5000,
        data: {},
        error: function(data) {
           loader();
        },

        success: function(data) {
           loader();
            var time=$('#time_attuale').val();
            var IDpicker=crea_picker(()=>{navigation(5,[time],1,0)},{'height':'75%'});
            $('#'+IDpicker+'.stampa_contenuto_picker').html(data);
        }
  });
}




function mod_configurazioni(tipo,id,campo,val2,agg ) {
    switch (val2) {
        case 0:
            var val = $('#' + campo).val();
            break;
        case 1:
            var val = $('#' + campo).val();
            val = val.replace(',', '.');

            if (isNaN(val)) {
                alertify.alert("E' necessario inserire un numero. Prego Riprovare");
                return false;
            }
            break;
        case 6:
            var val = $('#' + campo).val();
            val = val.replace(/\n/g, "<br/>"); //descrizione
            //val=encodeURIComponent(val);
            break;
        case 7:
            //var val=$('#'+campo).val();
            if (document.getElementById(campo).checked == true) { //si o no
                val = '1';
            } else {
                val = '0';
            }
            break;
        case 8:
            var val = $('#' + campo).html();

            break;
        case 9:
            var val = $('#' + campo).val();
            break;
        case 10:
            val = campo;
            break;
        case 11:
            var val = $(campo).val();
            break;
        case 12:
            var myRadio = $('input[name=' + campo + ']');
            val = myRadio.filter(':checked').val();
            break;
        case 13:
            if ($(campo).is(":checked")) { //si o no
                val = '1';
            } else {
                val = '0';
            }
            break;
        case 14: //replica per errore (Non cancellare)
            var val = $('#' + campo).summernote('code');
            break;

        default:
            var val = $('#' + campo).val();
            break;
    }
    loader(1);

    $.ajax({
        type: "POST",
        url: baseurl+'config/gestione_configurazioni.php',
        data: { val: val, tipo: tipo, ID: id,val2: val2 },
        cache: false,
        timeout: 5000,
        error: function() {
            loader(0);
        },
        success: function(html) {
            loader(0);
            console.log(html);

            if (typeof agg == "function") {
                    agg();
            }

        }
    });
}

/**/

function nascondi_riga_calendario(el){

    var input=$(el).find('input');
    if($(input).is( ":checked" )){
      $(input).prop("checked", false);
    }else{
      $(input).prop("checked", true);
    }

    var IDcategoria=$(input).val();
    var time=$('#time_attuale').val();
    creasessione(IDcategoria,175,()=>{navigation(5,{'time':time},1,0)});
}

function filtri_categorie_calendario(){
    loader(1);


    $.ajax({
        url: baseurl+versione+'/struttura/configurazioni/calendario_filtri_categorie.php',
        method: 'POST',
        dataType: 'text',
        cache: false,
        timeout: 5000,
        data: {},
        error: function(data) {
           loader();
        },

        success: function(data) {
           loader();

            var IDpicker=crea_picker(()=>{},{'height':'75%'});
            $('#'+IDpicker+'.stampa_contenuto_picker').html(data);
        }
  });

}


/* applicazione ospite */
function navigation_ospite(id,arr,agg=0,no_scorrimento=0){

    id=parseInt(id);
    loader(1);
    var apriurl=[];
    apriurl[0]='profilocli/prenotazione.php';
    apriurl[1]='profilocli/info_pren.php';


    apriurl[4]='profilocli/recensioni.php';
    apriurl[5]='profilocli/dettaglio_recensione.php';
    apriurl[6]='profilocli/nuova_recensione.php';
    apriurl[7]='profilocli/elenco_sottotipologie.php';
    apriurl[8]='profilocli/elenco_servizi.php';

    apriurl[10]='profilocli/checkin_online.php';
    apriurl[11]='profilocli/conferma_prenotazione_pagamento.php';
    apriurl[12]='profilocli/orari_servizi.php';
    apriurl[13]='profilocli/numeri_utili.php';
    apriurl[14]='profilocli/dettaglio_informazione.php';
    apriurl[15]='profilocli/album_struttura.php';
    apriurl[16]='profilocli/luoghi_struttura.php';
    apriurl[17]='profilocli/temperatura.php';
    apriurl[18]='profilocli/dettaglio_luogo.php';
    apriurl[19]='profilocli/dettaglio_itinerario.php';
    apriurl[20]='profilocli/itinerari_struttura.php';
    apriurl[21]='profilocli/dettaglio_servizio.php';

    apriurl[22]='profilocli/menu/menu_ristorante.php';
    apriurl[23]='profilocli/ordinazione/ordinazione.php';
    apriurl[24]='profilocli/ordinazione/lista_servizi.php';

    var url=apriurl[id];

    $.ajax({
        url: baseurl+versione+'/'+url,
        method: 'POST',
        dataType: 'text',
        cache:false,
        timeout:5000,
        data:{arr_dati:arr},
        success: function (data) {
            loader();

                if(arr==0){
                    arr={};
                }
                 arr['indietro']=1;

            var stringa_nav='navigation_ospite('+id+','+JSON.stringify(arr)+','+agg+',1);';
            var last_elem=history_navigation.length-1;
            var esegui_hash=0;
            if(history_navigation[last_elem]){
                if(history_navigation[last_elem].startsWith('navigation_ospite('+id)){
                    switch(id){
                        case 0:
                          esegui_hash=1;
                        break;
                    }

                }else{
                    esegui_hash=1;
                }
            }else{
                esegui_hash=1;
            }

            if(esegui_hash==1){
                history_navigation.push(stringa_nav);
                aggiungi_hash();
            }


            $('#menu_dinamic').html('');
            $('#container').html(data);
            aggiorna_menu_ospite(id,arr);

            if (typeof agg == "function") {
                setTimeout(function(){agg()},100);
            }

            if(no_scorrimento==0){
              $('html, body').animate({scrollTop: '0px'});
            }

            switch(agg){}

         },
        error:function(data){
            loader();
        }
     });
}

function aggiorna_menu_ospite(id,val){
    loader(1);

    $.ajax({
            url: baseurl+versione+'/config/aggiorna_menu_ospite.php',
            method: 'POST',
            dataType: 'text',
            cache:false,
            timeout:5000,
            data: {  idstep:id,  arr_dati:val  },
            error: function (data) {
                loader();
            },
            success: function (data) {
                $('#menu_dinamic').html(data);
                loader();
            }
    });

}

function aggiorna_select_picker(el){
    chiudi_picker();

    var valore=$(el).attr('value');
    var select=$(el).parent();
    var html=$(el).html();

    var funz = eval($(select).attr('onchange'));
    if (typeof funz == 'function') {
        funz(valore);
    }
}

timer_messaggi_ospite=0;
function apri_chat_ospite(){//picker contenitore chat
    loader(1);

    $.ajax({
        url: baseurl+versione+'/profilocli/chat_picker.php',
        method: 'POST',
        dataType: 'text',
        cache: false,
        timeout: 5000,
        data: {},
        error: function(data) {
           loader();
        },

        success: function(data) {
           loader();
           var IDpicker=crea_picker(()=>{clearInterval(timer_messaggi_ospite);navigation_ospite(0,0,0,1)},{'height':'75%'});
           $('#'+IDpicker+'.stampa_contenuto_picker').html(data);
        }
  });
}


function reload_messaggi_ospite(){
    loader(1);

        $.ajax({
            url: baseurl+versione+'/profilocli/reload_messaggi_ospite.php',
            method: 'POST',
            dataType: 'text',
            cache: false,
            timeout: 5000,
            data: {},
            error: function(data) {
               loader();
            },
            success: function(data) {
               loader();
               $('#contenitore_chat').html(data);
               $('.scroll_chat_auto').stop().animate({
                  scrollTop: $('#contenitore_chat')[0].scrollHeight
                 }, 800);
            }
      });
}

function invia_messaggio_ospite(){
     var messaggio=$('.chat_input_mes').html();
     $('.chat_input_mes').html('');
     mod_ospite(19,0,messaggio,10,()=>{reload_messaggi_ospite()});

}



function carica_tab_vendite(IDvendita,tipo_tab){

    switch(tipo_tab){

        case 'carrello':

           $.post('struttura/negozio/carrello.php', { IDvendita:IDvendita }, function(html) {
                $('#container_vendita').html(html);
            });
        break;
        case 'pagamenti':

           $.post('struttura/negozio/pagamenti.php', { IDvendita:IDvendita }, function(html) {
                $('#container_vendita').html(html);
            });
        break;

    }

}

function cambia_valore_html_select(ID,valore,html){
    $('#'+ID).attr('data-select',valore);
    $('#'+ID).val(valore);
    $('#'+ID).html(html);
}


function mod_negozio(azione, id, campo, tipo_campo, callback = null) {
      loader(1);
    var val = null;
    switch (tipo_campo) {
        case 7:
            val = Number($(campo).is(":checked"));
        break;
        case 8:
        case 'html_id':
            val = $('#' + campo).html();
            break;
        case 9:
        case 'val_id':
            val = $('#' + campo).val();
            break;
        case 10:
        case 'var':
            val = campo;
            break;
        case 11:
        case 'val_dom':
            val = $(campo).val();
            break;
        default:
            break;
    }
    data = {};
    data.request = azione;
    data.id = id;
    data.value = val;

    $.post(baseurl+'config/gestione_negozio.php', data, function(result) {
        //console.log(result);
           loader(0);;
        if (typeof callback === "function") {
            callback(result);
            return;
        }
        switch (callback) {

        }
    });
}


function aggiungi_elemento_vendita(IDvendita,tipo_vendita){
   mod_negozio(4, IDvendita, tipo_vendita, 10,(result)=>{
          if(result>1){
            carica_tab_vendite(IDvendita,'carrello');
            modifica_oggetti_vendita({'IDvendita':IDvendita,'IDoggetto':result})
           // dettaglio_voucher({ID:result},()=>{dettaglio_vendita(6884,'carrello');})
        }else{
              carica_tab_vendite(IDvendita,'carrello');
        }

   });
}


function modifica_oggetti_vendita(dati){
    loader(1);

    $.ajax({
        url: baseurl+versione+'/struttura/negozio/oggetto_vendita.php',
        method: 'POST',
        dataType: 'text',
        cache: false,
        timeout: 5000,
        data: {dati:dati},
        error: function(data) {
           loader();
        },
        success: function(data) {
           loader();

           var IDpicker=crea_picker(()=>{carica_tab_vendite(dati['IDvendita'],'carrello');},{'height':'92%'});
           $('#'+IDpicker+'.stampa_contenuto_picker').html(data);
           carica_tab_oggetti('oggetto');


        }
    });
}

function carica_tab_oggetti(tab_oggetto){
    var IDvendita=$('#IDvendita').val();
    var IDoggetto=$('#IDoggetto').val();

    var dati={};
    dati['IDvendita']=IDvendita;
    dati['IDoggetto']=IDoggetto;
    dati['tab']=tab_oggetto;

    loader(1);

        $.ajax({
            url: baseurl+versione+'/struttura/negozio/oggetto_vendita_contenuto.php',
            method: 'POST',
            dataType: 'text',
            cache: false,
            timeout: 5000,
            data: {dati:dati},
            error: function(data) {
               loader();
            },
            success: function(data) {
               loader();

                $('#dettagli_tab_oggetto').html(data);


            }
        });
}

function modifica_oggetto_vendita_modal(el){
      var IDvendita=$('#IDvendita').val();
      var IDoggetto=$('#IDoggetto').val();
      var azione=$(el).attr('data-tipo');

      var stringa='Modifica il Prezzo';

      if(azione==17){
           stringa='Modifica Quantita';
      }

        var input='';
        UIkit.modal.prompt(stringa+' :',input).then(function (input) {
          if(input){
              if(!isNaN(input)){
                  input= parseFloat(input);
                  mod_negozio(azione, [IDoggetto,IDvendita],input, 10,()=>{carica_tab_oggetti('oggetto');});
              } else{
                    UIkit.modal.dialog('<p class="uk-modal-body">Inserire un numero. Prego riprovare!</p>');
              }
          }

         });
}
function modifica_prezzo_voucher_modal(el){

      var IDvoucher=$('#IDvoucher').val();

        var input='';
        UIkit.modal.prompt('Modifica il Prezzo :',input).then(function (input) {
          if(input){
              if(!isNaN(input)){
                  input= parseFloat(input);
                  mod_negozio(51,IDvoucher,input, 10,()=>{carica_tab_oggetti('valore_voucher');});
              } else{
                    UIkit.modal.dialog('<p class="uk-modal-body">Inserire un numero. Prego riprovare!</p>');
              }
          }

         });
}



function carica_modal_lista_servizi(){
    loader(1);

    $.ajax({
        url: baseurl+versione+'/struttura/negozio/modal_lista_servizi.php',
        method: 'POST',
        dataType: 'text',
        cache: false,
        timeout: 5000,
        data: {},
        error: function(data) {
           loader();
        },
        success: function(data) {
           loader();

           var IDpicker=crea_picker(()=>{carica_tab_oggetti('valore_voucher');},{'height':'85%'});
           $('#'+IDpicker+'.stampa_contenuto_picker').html(data);
           ricerca_servizio();
        }
    });
}
function ricerca_servizio(stringa){
    var url = baseurl+versione+'/struttura/negozio/cerca_servizio.php';
    var query = {cerca_servizio:stringa };
    $.ajax({   url: url,  method: 'POST',  dataType: 'text',   cache: false,   timeout: 5000,  data: query,
        error: function(data) {  loader();   },
        success: function(data) {  loader();
             $('#dettagli_lista_servizi').html(data);
        }
    });
}

function aggiungi_componente_voucher(ID){
    var IDvoucher=$('#IDvoucher').val();
    mod_negozio(13, IDvoucher,[ID,1,1], 10,()=>{chiudi_picker();});
}


function visualizza_dettagli_categoria_prezzo(IDcategoria,tipologia,minstay=0){


    switch(tipologia){
        case 'prezzo':
        $('.nascondi_dettagli').css('visibility','visible');
           var url = baseurl+versione+'/struttura/prezzi/tab_prezzi.php';
            var query = {IDcategoria:IDcategoria };
            $.ajax({   url: url,  method: 'POST',  dataType: 'text',   cache: false,   timeout: 5000,  data: query,
                error: function(data) {  loader();   },
                success: function(data) {  loader();
                     $('.div_tariffe[data-categoria="'+IDcategoria+'"]').html(data);
                }
            });
        break;
        case 'minstay':
        $('.nascondi_dettagli').css('visibility','visible');
           var url = baseurl+versione+'/struttura/prezzi/tab_minstay_avanzato.php';
            var query = {IDcategoria:IDcategoria,minstay:minstay };
            $.ajax({   url: url,  method: 'POST',  dataType: 'text',   cache: false,   timeout: 5000,  data: query,
                error: function(data) {  loader();   },
                success: function(data) {  loader();
                     $('.div_tariffe[data-categoria="'+IDcategoria+'"]').html(data);
                }
            });
        break;
        case 'chiudi':
        $('.nascondi_dettagli').css('visibility','hidden');
         $('.div_tariffe[data-categoria="'+IDcategoria+'"]').html('');
        break;
    }


}



function modifica_prezzi(azione, id, campo, tipo_campo, callback = null) {
    loader(1);
    var val = null;
    switch (tipo_campo) {
        case 7:
            val = Number($(campo).is(":checked"));
        break;
        case 8:
        case 'html_id':
            val = $('#' + campo).html();
            break;
        case 9:
        case 'val_id':
            val = $('#' + campo).val();
            break;
        case 10:
        case 'var':
            val = campo;
            break;
        case 11:
        case 'val_dom':
            val = $(campo).val();
            break;
        default:
            break;
    }

    data = {};
    data.request = azione;
    data.id = id;
    data.value = val;
    console.log(data);
    $.post(baseurl+'config/gestione_modifica_prezzi.php', data, function(result) {
        //console.log(result);
        loader(0);
        if (typeof callback === "function") {
            callback(result);
            return;
        }
        switch (callback) {

        }
    });

}

function modal_disponibilita(IDcategoria){
    loader(1);

    if($('.disponibilita_modal').length>0){
        $('.disponibilita_modal').remove();
    }

    var url =baseurl+versione+'/struttura/prezzi/disponibilita_categoria.php';
    var query = {IDcategoria:IDcategoria };
    $.ajax({ url: url,  method: 'POST',   dataType: 'text', cache: false,  timeout: 5000,   data: query,
        error: function(data) {
           loader();
        },
        success: function(data) {
           loader();

            $('body').append('<div class="uk-picker stampa_contenuto_picker disponibilita_modal" style="height:24%">'+data+' </div>');

        }
    });

}


(function($) {
    $.fn.searchBox = function (options = {}) {
        class SearchBox {
            /**
             * @param  DomElement   elem    input da trasformare
             * @param  Object       options opzioni
             *  url: indirizzo per cui eseguire la richiesta
             *  args: parametri da passare alla richiesta
             *  onclick: funzione che prende gli attributi data- come parametri
             *  prepend: risultati da aggiungere alla query
             *   data: elenco di attributi data-
             *   text: testo del risultato
             *   onclick: funzione onclick personalizzata
             *  delay: (bool) ritarda l'esecuzione della funzione dopo la pressione di un tasto
             *  minChars: numero minimo di caratteri per avviare la funzione
             *  autoSelect: (bool) seleziona automaticamente se rimane solo uno
             * @return lista jquery
             */
            constructor(elem, options) {
                let defaults = {
                    url: $(elem).data('url') || '',
                    args: {},
                    delay: true,
                    minChars: 3,
                    autoSelect: false
                };
                options = $.extend(defaults, options);
                this.$input = $(elem);
                this.options = options;
                this.lastKeyTime = false;



                this.start();
            }

            start() {
                let searchArea = this.$searchArea = $('<div></div>');
                let searchBox = this;
                searchArea.addClass('suggsched searchbox-result ric');
                searchArea.css({
                    "position": 'absolute',
                    "z-index": '999',
                    "margin-top": '0px',
                    "display": 'none'
                });
                searchArea.data('index', -1);
                searchArea.insertAfter(this.$input);

                let opts = this.options;
                if (opts.prepend) {
                    if (!Array.isArray(opts.prepend)) {
                        opts.prepend = [opts.prepend];
                    }
                    opts.prepend.reverse();
                }
                this.$input.on('keyup', function(evt) {
                    let search = false;
                    switch (evt.key) {
                        case "ArrowDown":
                            searchArea.data('index', Math.min(searchArea.data('index') + 1, searchArea.children('.searchbox-result__item').length));
                            break;
                        case "ArrowUp":
                            searchArea.data('index', Math.max(searchArea.data('index') - 1, 0));
                            break;
                        case "Enter":
                            let target = searchArea.find('.searchbox-result__item--highlight').get(0);
                            if (target && typeof (opts.onclick) == "function") {
                                opts.onclick.call(target, Object.assign({},target.dataset));
                            }
                        case "Escape":
                        case "Tab":
                            $('.suggsched').fadeOut('fast');
                            break;
                        default:
                            search = true;
                        break;
                    }
                    let timestamp = Date.now();
                    let delay = 400;
                    if (searchBox.lastKeyTime) {
                        delay = Math.min(400, 2 * Math.max(600, timestamp - searchBox.lastKeyTime));
                        searchBox.lastKeyTime = timestamp;
                    }

                    let elems = searchArea.children('.searchbox-result__item');
                    let idx = searchArea.data('index');
                    elems.removeClass('searchbox-result__item--highlight');
                    if (idx >= 0) {$(elems[idx]).addClass('searchbox-result__item--highlight');}
                    if (!search) return;
                    let args = opts.args;
                    args.queryString = this.value;
                    if (opts.minChars > this.value.length) {
                        searchArea.hide();
                        return;
                    }

                    $.each(this.attributes, function ( index, attribute ) {
                       args[attribute.name] = attribute.value;
                    });

                    searchBox.showWait();
                    if (opts.delay) {
                        delay_call(() => {
                            searchBox.search(args);
                        }, delay, searchBox);
                    } else {
                        searchBox.search(args);
                    }

                });
                this.$input.on('click', function(evt) {
                    this.select();
                    if (searchArea.html()) {
                        searchArea.show();
                    }
                });
                this.$searchArea.on('click', function(evt) {
                    let target = evt.target;
                    if (!$(target).hasClass('searchbox-result__item')) {
                        target = $(target).closest('.searchbox-result__item').get(0) || null;
                    }

                    if (target) {
                        if (typeof (opts.onclick) == "function") {
                            opts.onclick.call(target, Object.assign({},target.dataset));
                        }
                        $(this).fadeOut();
                    }
                });
            }

            showWait() {
                if (this.$searchArea.find('.searchbox-searching').length) { return; }
                let load = $('<div>Ricerca in corso<span>.</span><span>.</span><span>.</span></div>');
                load.addClass('searchbox-searching');
                this.$searchArea.html('');
                this.$searchArea.append(load);
                this.$searchArea.show();
            }

            search(args) {
                let searchArea = this.$searchArea;
                let opts = this.options;
                $.post(opts.url, args, function(result) {
                    searchArea.data('index', -1);
                    searchArea.html(result);
                    if (opts.autoSelect) {
                        let elements = searchArea.find('.searchbox-result__item');
                        if (elements.length == 1) {
                            elements.click();
                            return;
                        }
                    }
                    if (opts.prepend) {
                        for (let opt of opts.prepend) {
                            let pData = opt.data || '';
                            let pText = opt.text || '';
                            let pOnclick = opt.onclick || null;
                            let item = $('<span></span>');
                            item.addClass('searchbox-result__item');
                            //aggiunge data-<nome> se specificato
                            if (pData) {
                                for (let data in pData) {item.attr('data-' + data, pData[data]); }
                            }
                            if (pOnclick) {
                                item.on('click', function(event) {
                                    event.stopPropagation();
                                    pOnclick.call(this,args.queryString);
                                })
                            };
                            item.html(pText + args.queryString);
                            searchArea.prepend(item);
                        }
                    }
                    if (searchArea.html()) {
                        searchArea.show();
                    } else {
                        searchArea.hide();
                    }
                });
            }
        }

        return this.each(function() {
            let data = $(this).data('searchBox');

            if (!data) {
                //init
                let data = new SearchBox(this, options);
                $(this).data('searchBox', data);
            }
        });
    };
})(jQuery);

function delay_call(funzione, delay = 0, sender = 0) {
    if (delay_call.list === undefined) {
        delay_call.list = new Map();
    }
    let list = delay_call.list;

    if (list.has(sender)) {
        let id = list.get(sender);
        clearTimeout(id);
    }
    let timeout = setTimeout(() => {
        list.delete(sender);
        funzione();
    }, delay);

    list.set(sender, timeout);
}

function delay_cancel(sender = null) {
    if (delay_call.list === undefined) {
        return;
    }
    let list = delay_call.list;
    if (sender === null) {
        for (let [k, id] of list) {
            clearTimeout(id);
        }
        list.clear();
    } else if (list.has(sender)) {
        let id = list.get(sender);
        clearTimeout(id);
        list.delete(sender);
    }
}



$(document).click(function(event) {
    if (!$(event.target).closest('.suggsched').length) {
        if ($('.suggsched').is(":visible")) {
            if (!$(event.target).is(":focus")) {
                $('.suggsched').fadeOut();
            }
        }
    }
});


/*Collega Prenotazioni*/

function collega_prenotazioni(parametri){
    //collega_prenotazioni.php
    loader(1);
    var url = baseurl;
    var url = 'struttura/collega_prenotazioni.php';
    var query = { parametri:parametri };
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

               var IDpicker=crea_picker(()=>{},{'height':'92%'});
               $('#'+IDpicker+'.stampa_contenuto_picker').html(data);
            }
      });
}

function seleziona_giorno_prenotazioni(time){

    $('.prenotazioni_nascoste').removeClass('riga_visibile');
    $('.prenotazioni_nascoste[data-time-pren="'+time+'"]').addClass('riga_visibile');
}

function seleziona_prenotazione_collegamento(el){
    var IDprenotazione=$(el).data('pren');
   // creasessione(IDprenotazione, 169);
    var checkbox=$(el).find('[type="checkbox"]');

    if($(checkbox).is(':checked')){
        $(checkbox).prop('checked', false );
    }else{
        $(checkbox).prop('checked', true );
    }

    var time_selezionato=$(el).data('time-check');

    var count=0;
    $('[data-time-check="'+time_selezionato+'"]').each(function(){

        var checkbox= $(this).find('[type="checkbox"]');

        if($(checkbox).is(':checked')){
            count++
        }

    });

    $('.premi_data[data-time="'+time_selezionato+'"] .notifica_giorno').html('');
    if(count>0){
        $('.premi_data[data-time="'+time_selezionato+'"] .notifica_giorno').html(count);
    }
}


function aggiungi_servizio_tipologia(){
    var opzioni={};
    var dati={};

    opzioni['solo_tipologie']=[];
    if($('#tipologia').length>0){
        opzioni['solo_tipologie'].push($('#tipologia').val());
    }

    dati['tipo_riferimento']=0;
    dati['IDriferimento']=[];
    $('.blocco_pren').each(function(){
        var checkbox=$(this).find('[type="checkbox"]');
        console.log(checkbox);
        var IDprenotazione=$(this).data('pren')
        if($(checkbox).is(':checked')){
              dati['IDriferimento'].push(IDprenotazione);
        }
    });

    aggiunta_servizio(dati,opzioni);
    chiudi_picker();
}

/*Aggiungi servizio*/

 function aggiunta_servizio(dati,opzioni,onclose=null){

        loader(1);

        $.ajax({
            url: baseurl+versione+'/struttura/servizi/popup_aggiungi_servizio.php',
            method: 'POST',
            dataType: 'text',
            cache: false,
            timeout: 5000,
            data: { dati:dati,opzioni:opzioni },
            error: function(data) {
               loader();
            },

            success: function(data) {
               loader();

               var IDpicker=crea_picker(()=>{onclose()},{'height':'92%'});
               $('#'+IDpicker+'.stampa_contenuto_picker').html(data);
            }
      });

 }

 function popup_ricerca_servizio(){
        loader(1);

        $.ajax({
            url: baseurl+versione+'/struttura/servizi/popup_ricerca_servizio.php',
            method: 'POST',
            dataType: 'text',
            cache: false,
            timeout: 5000,
            data: {},
            error: function(data) {
               loader();
            },

            success: function(data) {
               loader();

               var IDpicker=crea_picker(()=>{},{'height':'70%'});
               $('#'+IDpicker+'.stampa_contenuto_picker').html(data);
               ricerca_servizio_standard();
            }
      });

 }

 function ricerca_servizio_standard(stringa){
    var url =  baseurl+versione+'/struttura/servizi/ricerca_servizio.php';
    var query = {cerca_servizio:stringa };
    $.ajax({   url: url,  method: 'POST',  dataType: 'text',   cache: false,   timeout: 5000,  data: query,
        error: function(data) {  loader();   },
        success: function(data) {  loader();
             $('#lista_servizi').html(data);
        }
    });
}


function scegli_servizio_da_aggiungere(IDservizio){

    var lista_riferimenti=$('#IDriferimento').val().split(",");
    var tipo_riferimento=$('#tipo_riferimento').val();

    //aggiorna_servizio_selezionato(IDservizio);
    var nome_servizio=$('.premi_servizio[data-id="'+IDservizio+'"]').html();
    $('#nome_servizio_selezionato').html(nome_servizio);
    chiudi_picker();
    ricarica_setup_servizio(lista_riferimenti,tipo_riferimento,IDservizio);

 }

 function ricarica_setup_servizio(IDriferimento, tipo_riferimento, IDservizio, opzioni = {}) {

     $.ajax({
            url: baseurl+versione+'/struttura/servizi/setup_servizio.php',
            method: 'POST',
            dataType: 'text',
            cache: false,
            timeout: 5000,
            data: {IDriferimento: IDriferimento, tipo_riferimento: tipo_riferimento, IDservizio: IDservizio, opzioni: opzioni},
            error: function(data) {
               loader();
            },

            success: function(data) {
               loader();

             $('#date_servizio').html(data);

            }
      });


}


function mod_add_serv(tipo, servizio, data, callback) {

    //var IDservizio=$('#IDservizio_scelto').val();
    var lista_riferimenti=$('#IDriferimento').val().split(",");
    var tipo_riferimento=$('#tipo_riferimento').val();


    if (!lista_riferimenti) {
        apri_notifica({'messaggio':'Selezionare almeno una riserva','status':'danger'});

        return;
    }
    loader(1);
    $.post(baseurl+'/config/servizi/aggiunta/gestione_aggiunta_servizi.php', { tipo: tipo, IDservizio: servizio, IDriferimento: lista_riferimenti, tipo_riferimento: tipo_riferimento, args: data })
        .done(function(r) {
            if (callback) {
                callback(r);
            }
        }).always(()=>{loader(0);});
}





function mod_riferimento(azione, id, campo, tipo_campo, callback = null) {
    loader(1);
    var val = null;
    switch (tipo_campo) {
        case 8:
        case 'html_id':
            val = $('#' + campo).html();
            break;
        case 9:
        case 'val_id':
            val = $('#' + campo).val();
            break;
        case 10:
        case 'var':
            val = campo;
            break;
        case 11:
        case 'val_dom':
            val = $(campo).val();
            break;
        default:
            break;
    }
    data = {};
    data.request = azione;
    data.id = id;
    data.value = val;

    $.post(baseurl+'config/gestione_riferimento.php', data, function(result) {

        loader(0);
        if (typeof callback === "function") {
            callback(result);
            return;
        }
        switch (callback) {  }
    });
}


function mod_ospite(azione, id, campo, tipo_campo, callback = null,notifica=null) {
    loader(1);
    var val = null;
    switch (tipo_campo) {
        case 7:
            val = Number($(campo).is(":checked"));
        break;
        case 8:
        case 'html_id':
            val = $('#' + campo).html();
            break;
        case 9:
        case 'val_id':
            val = $('#' + campo).val();
            break;
        case 10:
        case 'var':
            val = campo;
            break;
        case 11:
        case 'val_dom':
            val = $(campo).val();
            break;
        default:
            break;
    }

    data = {};
    data.request = azione;
    data.id = id;
    data.value = val;


    $.post(baseurl+versione+'/config/gestione_ospite.php', data, function(result) {
        loader(0);
        console.log(result);
        if (notifica){
            if(result!=-1){
                 apri_notifica({'messaggio':"Modifica Effettuata",'status':'success'});
            }else{
                 apri_notifica({'messaggio':"La modifica non è stata effettuata , prego riprovare.",'status':'danger'});
            }

        }

        if (typeof callback === "function") {
            callback(result);
            return;
        }
        switch (callback) {  }
    });

}


/* PARTE OSPITE */

function pulsanti_pagamento_webapp(IDdeposito){
    var pulsanti=atob($('#pulsanti_pagamento').val());
    picker_modal_action(pulsanti);

    $('.pagamento').on('click',function(){

        chiudi_picker();
        navigation_ospite(11,{'IDdeposito':IDdeposito,'IDpagamento':$(this).data('id'),'tipo_pagamento':$(this).data('tipo')})
    });

}


function controllo_pagamento(){
    var pagamento_ok=0;
    var dati=[];
    var IDpagamento=$('#IDpagamento').val();
    var tipo_pagamento=parseInt($('#tipo_pagamento').val());
    var IDdeposito=$('#IDdeposito').val();

    if(tipo_pagamento==1){
            var numero_carta=$('#numero_carta').val();
            var anno=$('#select_anno').attr('data-select');
            var mese=$('#select_mese').attr('data-select');
            var intestatario=$('#intestatario').val();
            var IDprenotazione=$('#idpren').val();

        $.ajax({
              url: baseurl+'config/preventivoonlinev2/config/controllocarta.php',
            method: 'POST',
            dataType: 'text',
            cache: false,
            timeout: 5000,
            data: {number:numero_carta,meses:mese,annos:anno,intes:intestatario,IDpagamento:IDpagamento,tipo_pagamento:tipo_pagamento,IDprenotazione:IDprenotazione},
            error: function(data) {
               loader();
            },

            success: function(data) {
               loader();
                var num=data.indexOf("error");
                if(num==-1){
                  pagamento_ok=1;
                   dati=[numero_carta,anno,mese,intestatario];
                   mod_ospite(18,[IDpagamento,IDdeposito],dati,10,()=>{goBack(); },1);
                }else{
                  apri_notifica({'messaggio':data.replace('error',''),'status':'danger'});
                }
            }

            });

    }else{
         mod_ospite(18,[IDpagamento,IDdeposito],0,10,()=>{goBack(); },1);
    }



}


function apri_informazioni_struttura(){
        loader(1);

        $.ajax({
           url: baseurl+versione+'/profilocli/informazioni_struttura.php',
            method: 'POST',
            dataType: 'text',
            cache: false,
            timeout: 5000,
            data: {},
            error: function(data) {
               loader();
            },

            success: function(data) {
               loader();

               var IDpicker=crea_picker(()=>{},{'height':'70%'});
               $('#'+IDpicker+'.stampa_contenuto_picker').html(data);

            }
      });
}


function switch_tab_prenotazione_ospite(tab){
    $('.tab_info_pren').fadeOut();
    setTimeout(function(){
        UIkit.tab('.uk-tab').show(tab)
        $('.tab_info_pren[data-tab="'+tab+'"]').fadeIn();
    },200);

}

ristorante.operazione = function(sel, arg, callback = false) {
    $.post(
        baseurl + 'config/ristorante/operazioni.php',
        { sel:sel, arg:arg },
        function(result) {
            if (callback) {
                callback(result);
            }
        }
    );
};


ristorante.carica_prodotti = function(callback = null) {
    if (!!ristorante.servizi) {
        if (typeof callback === "function") {
            callback();
        }
        return;
    }
    $.post(baseurl + 'config/ristorante/get_prodotti.php',{},function(data) {
        ristorante.servizi = data;
        if (typeof callback === "function") {
            callback();
        }
    }, "json");
};

ristorante.mostra_prodotti = async function(tipo, id_selezionato, container = null) {
    await new Promise((resolve, reject) => {
        ristorante.carica_prodotti(() => {
            resolve();
        });
    });

    if (!ristorante.servizi) {
        console.log("errore");
        return;
    }

    let soggetto = null;
    let elementi = null;
    let lista = null;
    let class_default = null;
    let color_default = null;
    let click = null;
    let target_div = container || document.getElementById("selectprod");
    let search = '';
    let lista_qta = null;

    if (tipo === "search") {
        if (filter) {
            search = filter;
            tipo = 1;
        } else {
            tipo = 0;
        }
        filter = null;
    }

    target_div.html('');

    switch (tipo) {
    case 0:
        //sottotipologie
        soggetto = ristorante.servizi.sottotipologie;
        lista = soggetto.lista;
        elementi = soggetto.ordine;
        class_default = soggetto.classe;
        let _target = $(target_div).next();
        click = function(arg) {ristorante.mostra_prodotti(1, arg, _target);};
        soggetto.navigazione = [{
            nome: '<i class="far fa-list-alt"></i> MENU',
            classe: 'ordinazione-btn-back',
            colore: '#ff9800',
            click: () => {
                let sottotip = $('#ristorante-info-ordinazione').data('sottotip')
               ristorante.mostra_prodotti(3, sottotip, _target);
            }
        }];

        break;
    case 1:
        //prodotti
        soggetto = ristorante.servizi.prodotti;
        lista = soggetto.lista;
        if (id_selezionato) {
            elementi = ristorante.servizi.sottotipologie.lista[id_selezionato].lista;
            color_default = ristorante.servizi.sottotipologie.lista[id_selezionato].colore;
        } else {
            elementi = soggetto.ordine;
        }
        class_default = soggetto.classe;
        click = function(arg) {
            ristorante.seleziona_prodotto(arg,'prodotto', 1);
        };
        soggetto.navigazione = [];
        // [{
        //     nome: '<i class="fas fa-arrow-left"></i> INDIETRO',
        //     classe: 'ordinazione-btn-back',
        //     colore: '#4B81DD',
        //     click: () => {ristorante.mostra_prodotti(0, 0, target_div);}
        // }];
        break;
    case 3:
        //menu
        let sottotipo = id_selezionato;
        soggetto = ristorante.servizi.menu;
        if (sottotipo) {
            lista = soggetto.lista[sottotipo];
            elementi = soggetto.ordine[sottotipo];
        } else {
            lista = {};
            for (let k of Object.keys(soggetto.lista)) {
                lista = Object.assign(lista, soggetto.lista[k]);
            }
            elementi = [].concat.apply([], Object.values(soggetto.ordine));
        }
        class_default = soggetto.classe;
        click = function(arg) {
            ristorante.seleziona_prodotto(arg, 'menu', 1);
        };
        soggetto.navigazione = [];
        // [{
        //     nome: '<i class="fas fa-arrow-left"></i> INDIETRO',
        //     classe: 'ordinazione-btn-back',
        //     colore: '#4B81DD',
        //     click: () => {ristorante.mostra_prodotti(0, 0, target_div);}
        // }];
        break;
    case 4:
        //variazioni
        soggetto = ristorante.servizi.variazioni;
        lista = ristorante.servizi.variazioni.lista;
        elementi = soggetto.ordine;
        class_default = soggetto.classe;
        click = function(arg) {
            ristorante.seleziona_prodotto.call(id_selezionato, arg, 'variazione', 1);
        };
        soggetto.navigazione = [{
            nome: '<i class="fas fa-arrow-left"></i> INDIETRO',
            classe: 'ordinazione-btn-back',
            colore: '#4B81DD',
            click: () => {ristorante.mostra_prodotti(0, 0, target_div);}
        }];

        break;
    default:
        console.log("missing case");
        return
        break;
    }
    //contenitore temporaneo
    var contenuto = $('<div></div>');
    contenuto.addClass('ordinazione-btn-container');

    var btn;
    if (soggetto.navigazione) {
        for (let elem of soggetto.navigazione) {
            btn = $('<button></button').addClass("ordinazione-btn naviga").html(elem.nome);
            if (elem.classe) {
                btn.addClass(elem.classe);
            }
            if (elem.colore) {
                btn.css("background-color", elem.colore);
            }
            if (elem.click) {
                btn.on('click', elem.click);
            }
            target_div.append(btn);
        }
    }

    for (var elem of elementi) {
        var elem_id = elem;
        elem = lista[elem];
        if (elem === undefined) {
            continue;
        }
        btn = $('<button></button').addClass("ordinazione-btn " + class_default).html(elem.nome);
        btn.attr('data-id', elem_id);
        if (elem.colore) {
            btn.css("background-color", "#"+elem.colore);
        } else if (color_default) {
            btn.css("background-color", "#"+color_default);
        }
        if (!!click) {
            let bind = ((item,arg) => {return () => {click.call(item,arg);}})(btn,elem_id);
            btn.on('click', bind);
        }
        if (elem.extra) {
            var extra = $('<span></span>').addClass('tag-prezzo').html(elem.extra);
            btn.append(extra);
        }
        target_div.append(btn);
    }
};

ristorante.reload_ordinazione = function() {
    let info = $('#ristorante-info-ordinazione');
    var IDtav = info.data('tavolo');
    var IDpren = info.data('prenotazione');
    var IDsottotip = info.data('sottotip');
    var portata = info.data('portata');
    var numero_tavolo = info.data('numero');
    let args = {tavolo: IDtav, pren: IDpren, sottotip: IDsottotip, num_tavolo: numero_tavolo, portata: portata, refresh: 1};
    $.post('struttura/ristorante/ordinazione.php', {arr_dati: args}, (data) => {
        $(`#ristorante-elenco-prodotti .ordinazione-portata[data-portata="${portata}"]`).html(data);
    });
    // navigation(28, args);
}

ristorante.selezione_piatti = function(tipo, id, container) {
    let info = $('#ristorante-info-ordinazione');

    container = $(container);
    let actions = $('<div></div>');
    let confirm = $('<button>CONFERMA</button>');
    let cancel = $('<button>CHIUDI</button>');
    let mode = $('<div></div>').addClass('ordinazione-select-mode');
    if (tipo == 4) {
        let b = $('<div><i class="fas fa-minus"></i></div>').attr('value', 0);
        b.addClass('selected');
        mode.append(b);
        b = $('<div><i class="fas fa-plus"></i></div>').attr('value', 1);
        mode.append(b);
        mode.on('click', function(event) {
            if ($(event.target).attr('value') !== undefined) {
                mode.find('div').removeClass('selected');
                $(event.target).addClass('selected');
            }
        });
    }

    // container.addClass('ordinazione-pannello-prodotti');
    container.html('');
    // if (tipo == 4) {container.append(mode);}

    // confirm.on('click', function() {
    //     ristorante.conferma_prodotti.call(id, () => {
    //         chiudi_picker();
    //         ristorante.reload_ordinazione();
    //     })
    // });
    ristorante.mostra_prodotti(tipo, id, container);
}

ristorante.popup_sottotip = function() {
    return new Promise((resolve, reject) => {
        common.popup(baseurl + 'config/ristorante/popup_sottotip.php', null, {
            ondone: (args) => {resolve(args); },
            oncancel: () => {resolve(0);}
        });
    });
};

ristorante.crea_tavolo = async function(arg, coperti = false, sottotip = false) {
    const fnc = function (args) {
        loader(1);
        ristorante.operazione(6, args, function(html) {
            // risto.popup_clear();
            loader(0);
            if (html.indexOf('error') == -1) {
                navigation(28, {tavolo: html});
            }
            else {
                alertify.alert("Errore inserimento tavolo");
            }
        });
    };

    // if (sottotip) {
    //     let s = await risto.popup_sottotip();
    //     arg[1] = s;
    // }
    // if (coperti) {
    //     risto.popup_coperti(function(res) {
    //         fnc(arg.concat(["",1,]).concat([res]));
    //     });
    // } else {
        fnc(arg);
    // }
}

ristorante.seleziona_prodotto = function(IDprod, tipo, quantita = false) {
    var amount = 1;
    if (quantita) {
        amount = quantita;
    }
    let info = $('#ristorante-info-ordinazione');
    var IDtav = info.data('tavolo');
    var portata = $('#ordinazione-selettore-portate .uk-active').data('index');
    var mode = $('.ordinazione-select-mode .selected').attr('value'); //per variazioni

    let arg;
    if (tipo == 'prodotto') {
        arg = IDprod+'_'+portata+'_'+amount;
        modordinazione(7, IDtav, arg, 10, () => {ristorante.reload_ordinazione();});
    } else if (tipo == 'menu') {
        arg = [IDprod, amount];
        modordinazione(10, IDtav, arg, 10, () => {ristorante.reload_ordinazione();});
    } else if (tipo == 'variazione') {
        //this viene passato con .call ed è un id
        arg = [IDprod, this, mode, amount];
        console.log(arg);
        modordinazione(8, IDtav, arg, 10, () => {ristorante.reload_ordinazione();});
    }
};

ristorante.conferma_prodotti = async function(callback = null) {
    let info = $('#ristorante-info-ordinazione');
    var IDtav = info.data('tavolo');
    var portata = $('#ordinazione-selettore-portate .uk-active').data('index');
    if (portata === undefined) { portata = info.data('portata'); }
    let list = info.data('lista_prodotti');
    if (!list) {
        return;
    }

    let sublist = list['prodotto'] || {};
    let arg = [];
    for (let k of Object.keys(sublist)) {
        let qta = sublist[k].quantity || 1;
        arg.push(k+'_'+portata+'_'+qta);
    }
    if (arg.length) {
        await new Promise((resolve, reject) => {modordinazione(7, IDtav, arg, 10, resolve); });
    }
    sublist = list['menu'] || {};
    arg = [];
    for (let k of Object.keys(sublist)) {
        let qta = sublist[k].quantity || 1;
        arg.push([k, qta]);
    }
    if (arg.length) {
        await new Promise((resolve, reject) => {modordinazione(10, IDtav, arg, 10, resolve); });
    }

    sublist = list['variazione'] || {};
    arg = [];
    for (let k of Object.keys(sublist)) {
        let qta = sublist[k].quantity || 1;
        let mode = sublist[k].mode;
        //this viene passato con .call ed è un id
        let id = parseInt(this) || null;
        arg.push([k, this, mode, qta]);
    }
    if (arg.length) {
        await new Promise((resolve, reject) => {modordinazione(8, IDtav, arg, 10, resolve); });
    }
    // modordinazione(IDtav, IDmenu, 10, 10, 5); menu
    if (typeof callback == "function") {callback();}
}

function modordinazione(tipo, id, campo, val2, agg) {
    switch (val2) {
        case 0:
        var val = $('#' + campo).val();
            //val=encodeURIComponent(val);
            break;
            case 1:
            var val = $('#' + campo).val();
            val = val.replace(',', '.');
            if (isNaN(val)) {
                alertify.alert("E' necessario inserire un numero. Prego Riprovare");
                return false;
            }
            break;
            case 6:
            var val = $('#' + campo).val();
            val = val.replace(/\n/g, "<br/>"); //descrizione
            //val=encodeURIComponent(val);
            break;
            case 7:
            if (document.getElementById(campo).checked == true) { //si o no
                val = '1';
            } else {
                val = '0';
            }
            break;
            case 8:
            var val = $('#' + campo).html();
            break;
            case 9:
            var val = $('#' + campo).html();
            break;
            case 10:
            val = campo;
            break;
            case 11:
            val = $(campo).val();
            break;
            case 12:
            val = $(campo).val();
            id = id + '_' + $(campo).attr('alt');
            break;
            default:
            var val = $('#' + campo).val();
            break;
        }

        var url = baseurl + 'config/ristorante/gestioneordinazioni.php';
        var query = { val: val, tipo: tipo, ID: id, val2: val2 };
    //alert(url);
    loader(1);
    $.ajax({
        url: url,
        method: 'POST',
        dataType: 'text',
        cache: false,
        data: query,
        timeout: 7000,
        error: function(data) {

        },
        success: function(data) {
            console.log(data);
            if (typeof agg == "function") {
                agg(data);
            }
            loader(0);
        }
    });
}

ristorante.apri_tavolo_prenotazione = function(args = {}) {
    $.post('struttura/ristorante/tavoli_attivi.php', args, (data) => {
        let picker = crea_picker();
        picker = $('#' + picker);
        picker.css('overflow-y', 'auto');
        picker.html(data);
    });
}




function salva_recensione(){
    var recensione={}

    var titolo=$('#titolo').val();
    var descrizione=$('#descrizione').val();

    recensione['titolo']=titolo;
    recensione['descrizione']=descrizione;
    recensione['parametri']={};

    $('.parametri').each(function(){
        var ID=$(this).data('id');
        var tipo=parseInt($(this).data('tipo'));


        if(tipo==0){
            console.log($('#valore_'+ID).val());
            var valore=$('#valore_'+ID).val();
        }else{
            var valore=$('#valore_'+ID).val();
        }
        recensione['parametri'][ID]=valore;

    });

    mod_ospite(24,0,recensione,10,()=>{goBack()},1);

}


function mod_anagrafiche(azione,id,campo,tipo_campo,callback = null,notifica=null) {
    loader(1);
    var val = null;
    switch (tipo_campo) {
        case 8:
        case 'html_id':
            val = $('#' + campo).html();
            break;
        case 9:
        case 'val_id':
            val = $('#' + campo).val();
            break;
        case 10:
        case 'var':
            val = campo;
            break;
        case 11:
        case 'val_dom':
            val = $(campo).val();
            break;
        default:
            break;
    }


    $.ajax({
        url: baseurl+"config/gestione_anagrafiche.php",
        type: "POST",
        data: { val: val, tipo: azione, ID: id, val2: tipo_campo },
        timeout: 8000,
        error: function(html) {
            loader(0);

        },
        success: function(html) {

            loader(0);
            if (notifica){
                if(result!=-1){
                     apri_notifica({'messaggio':"Modifica Effettuata",'status':'success'});
                }else{
                     apri_notifica({'messaggio':"La modifica non è stata effettuata , prego riprovare.",'status':'danger'});
                }

            }

            if (typeof callback == "function") {
                callback(html);
            }

            switch (callback) {   }
        }
    });
}


function aggiungi_allegato(IDoggetto,tipo_oggetto,callback=null){

    $.ajax({
        url: baseurl+versione+'/config/aggiungi_allegato.php',
        method: 'POST',
        dataType: 'text',
        cache: false,
        timeout: 5000,
        data: {IDoggetto: IDoggetto, tipo_oggetto: tipo_oggetto  },
        error: function(data) {
           loader();
        },

        success: function(data) {
           loader();
           var IDpicker=crea_picker(()=>{if(callback){callback();}},{'height':'75%'});
           $('#'+IDpicker+'.stampa_contenuto_picker').html(data);
        }
  });

}


function aggiungi_foto(IDoggetto,tipo_oggetto,callback=null){

    $.ajax({
        url: baseurl+versione+'/config/aggiungi_foto.php',
        method: 'POST',
        dataType: 'text',
        cache: false,
        timeout: 5000,
        data: {IDoggetto: IDoggetto, tipo_oggetto: tipo_oggetto  },
        error: function(data) {
           loader();
        },

        success: function(data) {
           loader();
           var IDpicker=crea_picker(()=>{if(callback){callback();}},{'height':'75%'});
           $('#'+IDpicker+'.stampa_contenuto_picker').html(data);
        }
  });

}




function modifica_allegato(elem){
    var IDallegato=$(elem).data('id');
    var elimina=$(elem).data('elimina');
    var download=$(elem).data('download');


    var btn='';
    if(download){
        btn+='<li  onclick="chiudi_picker();"><a href="'+download+'" download> Scarica Allegato  <i class="fas fa-link"></i></a> </li> ';
    }


    if (elimina == 1) {
        btn+=`<li   onclick="chiudi_picker();" style="color:#d80404">Elimina</li>`;
    }

  picker_modal_action(btn);
}


function cambia_struttura(){

    var pulsanti=atob($('#cambia_struttura').val());

    picker_modal_action(pulsanti);
}

function modcambio(ID, tipo) {
    $.post(baseurl+'config/gestionecambio.php', { ID: ID, tipo: tipo }, function(html) {
        switch (tipo) {
            case 1:


                reload_info_struttura(parseInt(html))
                .finally(() => {
                    location.reload();
                });

                break;
            case 2:
                location.reload();
                break;
        }
    });
}


function reload_info_struttura(IDstruttura) {
    return new Promise((resolve, reject) => {
        let info = get_info_struttura();
        if (!info || !info[IDstruttura]) {
            reject();
            return;
        }
        $.post(baseurl+'config/gestione/info_struttura.php', {info: info[IDstruttura]})
        .done(resolve)
        .fail(reject);
    });
}

function get_info_struttura() {
    let info = localStorage.getItem('info_struttura');
    if (!info) {
        return null;
    }
    info = JSON.parse(info);
    return info;
}


function controllo_check(IDprenotazione, data, tipo, solo_spostamento = false, callback = null) {
     var refresh =  function() { chiudi_picker();};


    let alloggio = null;
    if (tipo !== null && typeof tipo === "object") {
        alloggio = tipo.alloggio;
        tipo = tipo.tipo;
    }

    var durata = function() {

        $.ajax({
            url: baseurl+versione+'/struttura/sposta_prenotazione/scelta_ricalcolo.php',
            method: 'POST',
            dataType: 'text',
            cache: false,
            timeout: 5000,
            data: {IDpren: IDprenotazione, data: data, tipo: tipo },
            error: function(html) {
               loader();
            },

            success: function(html) {
               loader();
               var IDpicker=crea_picker(()=>{},{'height':'80%'});
               $('#'+IDpicker+'.stampa_contenuto_picker').html(html);

                    $('#scelta-ricalcolo__done').click(function() {
                        let target = $('.metodo-ricalcolo input:checked').get(0);
                        let modo = $(target).data('val');
                        let content = { IDpren: IDprenotazione, data: data, tipo: tipo, modo: modo, alloggio: alloggio };
                        if ($('#penale__check').is(':checked')) {
                            content.penale = $('#penale__totale').val();
                        }
                        $.post(baseurl+'config/spostamento/controllo_check.php', content, function(html) {
                            if (!/^[1-4]$/.test(html)) {
                                  apri_notifica({'messaggio':'Impossibile spostare','status':'danger'});

                            }

                        }).always(() => {
                            close_all_picker();
                            goBack();
                        });
                    });
            }
      });
    }



    if (tipo == 0 && !solo_spostamento) {


        $.ajax({
            url: baseurl+versione+'/struttura/sposta_prenotazione/scelta_sposta.php',
            method: 'POST',
            dataType: 'text',
            cache: false,
            timeout: 5000,
            data: {IDprenotazione: IDprenotazione, data: data, tipo: tipo },
            error: function(html) {
               loader();
            },

            success: function(html) {
               loader();
               var IDpicker=crea_picker(()=>{if(callback){callback();}},{'height':'30%'});
               $('#'+IDpicker+'.stampa_contenuto_picker').html(html);


                   $('#data-sposta').click(function() {
                    var IDalloggio=$('#alloggio').data('select');
                    if(IDalloggio==0){
                        return false;
                    }

                    var sala_old=$('#alloggio').data('appsrc');
                    var vdest = [data, IDalloggio, 1].join("_");
                    var vsrc = ['', sala_old, 1, IDprenotazione].join("_");

                    sposta2(vdest, vsrc, null, 0, refresh, refresh);
                });
                $('#data-durata').click(durata);
            }
      });

    } else {
        durata();
    }
}

var spostasubito = 0;
function sposta2(vettore, vettoremain, durata = null, spezzato = 0, onsuccess = false, onfailure = false) {
    //if ((datasposta != '0') && (appsposta != '0')) {
    var dataString = "vettore=" + vettore + "&vettoremain=" + vettoremain;
    $.ajax({
        type: "POST",
        url: baseurl+"config/spostamento/crea.php",
        data: {
            vettore: vettore,
            vettoremain: vettoremain,
            durata: durata,
            spezzato
        },
        cache: false,
        success: function(html) {



            var errore = /error:\s*?(.*)/.exec(html);
            if (!errore) {

                UIkit.modal.confirm('Vuoi davvero spostare la prenotazione?').then(function () {  sposta3(); }, function () {   });

                if (typeof onsuccess === "function") {
                    onsuccess();
                }
            } else {
                   apri_notifica({'messaggio':errore[1],'status':'danger'});
                if (typeof obj !== "undefined" && obj != 0) {
                    $(objmain).css('top', '0');
                    $(objmain).css('left', '0');
                }
                if (typeof onfailure === "function") {
                    onfailure();
                }
            }
        }
    });


}


function sposta3() {
    $.post(baseurl+"config/spostamento/sposta.php", {}, function(data) {
        var num = data.indexOf("ok");

        var num2 = data.indexOf("error");
        if (num2 >= 0) {
            apri_notifica({'messaggio':'Non è possibile spostare prenotazione senza soggiorno su alloggi e viceversa.<br>Prego Riprovare','status':'primary'});

        } else {

            if (num >= 0) {

                 apri_notifica({'messaggio':'Prenotazione spostata con successo!','status':'succes'});

               goBack();

            } else {

              apri_notifica({'messaggio':'Errore! Prego Riprovare','status':'danger'});
                goBack();
            }
        }

    });
}


function set_notti_prenotazione(IDpren, elem, fine = 1) {
   // var notti = elem.value;
   var notti=$(elem).html();
    var patt = /[0-9]*/;
    if ($(elem).is('input') && (!patt.test(notti) || parseInt(notti) < 0)) {
          apri_notifica({'messaggio':'Inserito un valore non valido. Riprovare.','status':'danger'});

        return;
    }
    var diff_notti = notti - elem.dataset.notti;
    if (diff_notti == 0) {
        return;
    }
    var data = new Date(elem.dataset.data);
    if (fine == 1) {
        var offset = 0;
        if (elem.dataset.giorni) {
            offset = parseInt(elem.dataset.giorni);
        }
        data.setDate(data.getDate() + diff_notti + offset);
    } else {
        data.setDate(data.getDate() - diff_notti);
    }

     UIkit.modal.confirm('Vuoi Modificare il numero di notti?').then(function () {

          data = data.toISOString().substring(0, 10);
            controllo_check(IDpren, data, fine);

       }, function () { esegui_old_navigation(); });

}

function sposta_date_prenotazione(IDpren, time, giorni, src_app, dest_app) {
    if(dest_app!=0){
        var dest = [time, dest_app, 1].join('_');
        var src = [time, src_app, 1, IDpren].join('_');
        sposta2(dest, src, giorni);
    }
}



function visualizza_piatti_menu(IDaddebito,callback=null){
        $.ajax({
            url: baseurl+versione+'/profilocli/menu/piatti_menu.php',
            method: 'POST',
            dataType: 'text',
            cache: false,
            timeout: 5000,
            data: {IDaddebito: IDaddebito },
            error: function(html) {
               loader();
            },

            success: function(html) {
               loader();
               var IDpicker=crea_picker(()=>{if(callback){callback();}},{'height':'70%'});
               $('#'+IDpicker+'.stampa_contenuto_picker').html(html);


            }
      });
}

function visualizza_menu_giorno(IDaddebito,callback=null){
        $.ajax({
            url: baseurl+versione+'/profilocli/menu/menu_giornaliero.php',
            method: 'POST',
            dataType: 'text',
            cache: false,
            timeout: 5000,
            data: {IDaddebito: IDaddebito },
            error: function(html) {
               loader();
            },

            success: function(html) {
               loader();
               var IDpicker=crea_picker(()=>{if(callback){callback();}},{'height':'70%'});
               $('#'+IDpicker+'.stampa_contenuto_picker').html(html);


            }
      });
}


function visualizza_elenco_piatti_portate(elem,callback=null){
    var parent=$(elem).data('parent');
    var riga=$(elem).data('riga');
    var menu=$(elem).data('menu');

    $.ajax({
        url: baseurl+versione+'/profilocli/menu/elenco_piatti_portate.php',
        method: 'POST',
        dataType: 'text',
        cache: false,
        timeout: 5000,
        data: {parent: parent,riga:riga,menu:menu },
        error: function(html) {
           loader();
        },

        success: function(html) {
           loader();
           var IDpicker=crea_picker(()=>{if(callback){callback();}},{'height':'85%'});
           $('#'+IDpicker+'.stampa_contenuto_picker').html(html);


        }
  });
}

function collega_piatto_menu(IDservizio){
    var parent=$('#parent').val();
    var time=$('#time_servizio').val();
    var menu=$('#IDmenu_servizio').val();
    var IDaddebito=$('#IDaddebito_selezionato').val();


    mod_ospite(27,IDservizio,[parent,menu,time],10,()=>{chiudi_picker();stampa_menu_addebito_web_app(IDaddebito,1)});
}


function aggiorna_carrello_ordinazione_web_app(){
     $.ajax({
            url: baseurl+versione+'/profilocli/ordinazione/aggiorna_carrello_quantita.php',
            method: 'POST',
            dataType: 'text',
            cache: false,
            timeout: 5000,
            data: { },
            error: function(html) {
               loader();
            },
            success: function(html) {
               loader();
               var quantita=parseInt(html);
               $('.notifica_carrello').html('');
               if(quantita!=0){
                $('.notifica_carrello').html(quantita);
               }

            }
    });
}


function apri_carrello_ordinazione_web_app(){
     $.ajax({
            url: baseurl+versione+'/profilocli/ordinazione/carrello.php',
            method: 'POST',
            dataType: 'text',
            cache: false,
            timeout: 5000,
            data: { },
            error: function(html) {
               loader();
            },

            success: function(html) {
               loader();
               var IDpicker=crea_picker(()=>{},{'height':'80%'});
               $('#'+IDpicker+'.stampa_contenuto_picker').html(html);


            }
      });

}


function seleziona_sala_ordinazione_web_app(IDsala){


    mod_ospite(33,IDsala,0,10,()=>{navigation_ospite(24,0,()=>{aggiorna_carrello_ordinazione_web_app();});})

}


function visualizza_servizio_ordinazione_web_app(IDservizio,callback=null){

        $.ajax({
            url: baseurl+versione+'/profilocli/ordinazione/dettaglio_servizio.php',
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
               var IDpicker=crea_picker(()=>{if(callback){callback();}},{'height':'80%'});
               $('#'+IDpicker+'.stampa_contenuto_picker').html(html);


            }
      });

}



function visualizza_menu_addebito_webapp(IDaddebito,onclose=null){

    $.ajax({
        url: baseurl+versione+'/profilocli/menu/menu_servizio_ristorante.php',
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
           var IDpicker=crea_picker(()=>{if(onclose){onclose()}},{'height':'85%'});
           $('#'+IDpicker+'.stampa_contenuto_picker').html(html);


        }
      });
}

function stampa_menu_addebito_web_app(IDaddebito,tipo){
    $.ajax({
        url: baseurl+versione+'/profilocli/menu/stampa_menu_servizio.php',
        method: 'POST',
        dataType: 'text',
        cache: false,
        timeout: 5000,
        data: { IDaddebito:IDaddebito,tipo:tipo},
        error: function(html) {
           loader();
        },

        success: function(html) {
           loader();
            $('#stampa_menu').html(html);
        }
      });
}


function aggiungi_al_carrello_ordine(IDservizio){

    var quantita=parseFloat($('#quantita').attr('data-select'));

    var variazioni={};
    var note=$('#note_servizio').val();
    $('.variazione').each(function(){
        if($(this).is(':checked')){
            var IDvariazione=$(this).data('id');
            var modi=$(this).data('modi');
            variazioni[IDvariazione]=[];
            variazioni[IDvariazione][modi]=1;

        }
    });


    if(quantita!=0){
        mod_ospite(29,IDservizio,[quantita,variazioni,note],10,()=>{chiudi_picker();apri_carrello_ordinazione_web_app();aggiorna_carrello_ordinazione_web_app()});
    }else{

     apri_notifica({'messaggio':'inserire una quantità , prego riprovare.','status':'danger'});
    }

}


function richiedi_demo_scidoo(){
    var nome=$('#nome').val();
    var email=$('#email').val();
    var telefono=$('#telefono').val();
    var nome_struttura=$('#nome_struttura').val();
    var sito_struttura=$('#sito_struttura').val();


    if((nome_struttura=='') || (nome=='') || (telefono=='') || (email=='') || sito_struttura==''){
        apri_notifica({'messaggio':'è necessario compilare tutti i campi obbligatori.Prego Riprovare','status':'danger'});
        return false;
    }

    if(!$('#privacy_policy').is(':checked')){
                apri_notifica({'messaggio':"è necessario accettare l'informativa sulla privacy.Prego Riprovare",'status':'danger'});
        return false;
    }


    loader(1);
    $.ajax({
            url: baseurl+versione+'/richiesta_demo.php',
            method: 'POST',
            dataType: 'text',
            cache:false,
            timeout:5000,
            data: {nome:nome,email:email,telefono:telefono,nome_struttura:nome_struttura,sito_struttura:sito_struttura },
            error: function (data) {
                loader();

            },
            success: function (data) {
                loader();
            switch(parseInt(data)){
                case -1:
                apri_notifica({'messaggio':"Si prega di inserire un' email valida. Prego riprovare",'status':'danger'});
                break;
                case 0:
                apri_notifica({'messaggio':"E' necessario compilare tutti i campi obbligatori. Prego riprovare",'status':'danger'});
                break;
                case 1:
                apri_notifica({'messaggio':"La richiesta è stata inviata con successo, sarai ricontattato al più presto per provare il prodotto!",'status':'succes'});
                break;
                }


            }
    });
}


function apri_notifiche(onclose=null){

    $.ajax({
        url: baseurl+versione+'/struttura/notifiche.php',
        method: 'POST',
        dataType: 'text',
        cache: false,
        timeout: 5000,
        data: { },
        error: function(html) {
           loader();
        },

        success: function(html) {
           loader();
           var IDpicker=crea_picker(()=>{if(onclose){onclose()}},{'height':'85%'});
           $('#'+IDpicker+'.stampa_contenuto_picker').html(html);
        }

      });
}




function apri_controllo_privacy(){

    $.ajax({
        url: baseurl+versione+'/profilocli/controllo_privacy.php',
        method: 'POST',
        dataType: 'text',
        cache: false,
        timeout: 5000,
        data: { },
        error: function(html) {
           loader();
        },

        success: function(html) {
           loader();
          if(parseInt(html)!=1){

            var IDpicker=crea_picker(()=>{},{'height':'85%'});
            $('.uk-picker-overlay').attr('onclick','');
            $('#'+IDpicker+'.stampa_contenuto_picker').html(html);


          }
        }

      });
}



 document.addEventListener("touchstart", function(event) {
    //alert($(obj).html());
    //$(obj).trigger('click');
    event.stopPropagation();
    event.preventDefault();
});

var idleCheck = {};

idleCheck.movement = false;
idleCheck.cooldown = false;

function controllo_login() {
    if (idleCheck.movement && !idleCheck.cooldown) {
        $.post(baseurl+'/config/controllo_login.php', function(html) {
            if (!html) {
                return;
            }
            console.log("User session: ", html);
            let result = new RegExp('^(\\w+):?\\s?(.*)', 'i').exec(html);
            if (!result) return;
            let status = result[1];
            let message = result[2];
            if (status == "logout") {
                location.reload();
            } /*else if (html == 'user') {
                user_prompt_utente();
            }*/
        });
        idleCheck.cooldown = true;
        setTimeout(() => { idleCheck.cooldown = false; }, 1000);
    }
    idleCheck.movement = false;
}



function registra_nuova_struttura(){
    var nome=$('#nome').val();
    var email=$('#email').val();
    var telefono=$('#telefono').val();

    var nome_struttura=$('#nome_struttura').val();

    var sito_struttura=$('#sito_struttura').val();
    var password=$('#password').val();
    var prezzo=$('#prezzo').val();

    var numero_appartamenti=$('#appartamenti').attr('data-select');
    var tipo_struttura=$('#tipo_struttura').attr('data-select');

    var prefisso=$('#prefisso').attr('data-select');

    if((nome_struttura=='') || (nome=='') || (telefono=='') || (email=='') || sito_struttura==''){
        apri_notifica({'messaggio':'è necessario compilare tutti i campi obbligatori.Prego Riprovare','status':'danger'});
        return false;
    }

    if(!$('#privacy_policy').is(':checked')){
        apri_notifica({'messaggio':"è necessario accettare l'informativa sulla privacy.Prego Riprovare",'status':'danger'});
        return false;
    }


    loader(1);
    $.ajax({
            url: baseurl+'/registrazione/registratip.php',
            method: 'POST',
            dataType: 'text',
            cache:false,
            timeout:5000,

            data: {  email:email,pass:password,nome:nome,nomestr:nome_struttura,prefisso:prefisso,tel:telefono,prezzom:prezzo,numc:numero_appartamenti,tipo:tipo_struttura,dove:1},
            error: function (data) {
                loader();

            },
            success: function (data) {
              loader();
                var error=0;

                if(data.indexOf("error")!=-1){
                    error=1;
                }

                switch(error){
                    case 0:
                     apri_notifica({'messaggio':"Benvenuto su Scidoo! Il primo gestionale in Italia per la Struttura e per l'Ospite! Vi auguriamo un'ottima esperienza ",'status':'success'});

                         setTimeout(function(){
                             location.href='index.php';
                         },3000);
                    break;
                    case 1:
                       apri_notifica({'messaggio':"La registrazione non è stata completata! Potrebbe essere errata la password di un account esistente o potrebbe essere un errore di connessione. Prego riprovare o contattare l'assistenza",'status':'danger'});
                    break;
                }
            }
    });
}
