
var tipo_tel=2;
$(document).ready(function(){
  var userAgent = navigator.userAgent || navigator.vendor || window.opera;
      // Windows Phone must come first because its UA also contains "Android"
    if (/windows phone/i.test(userAgent)) {
       console.log("Windows Phone");
       tipo_tel=1;
    }

    if (/android/i.test(userAgent)) {
      console.log("Android");
       tipo_tel=2;
    }

    // iOS detection from: http://stackoverflow.com/a/9039885/177710
    if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
      console.log("iOS");
       tipo_tel=3;
    } 
});



function navigationtxt2(id,str,campo,agg,loader){
	/*if(IDcode=='undefined'){
		onloadf(1);
	}*/
	//reloadnav=0;
	//alert(id);
	var url=baseurl+versione+"/config/";
	var apriurl=[];

	apriurl[0]='profilo/pren/prenotaservnuovostep1.php';
	apriurl[1]='profilo/pren/prenotaservnuovostep2.php';
	apriurl[2]='profilo/pren/prenotaservnuovostep3.php';
	apriurl[3]='profilo/pren/prenotaservnuovostep4.php';
	apriurl[4]='profilo/pren/prenotaservnuovostep5.php';
	apriurl[5]='profilo/pren/prenotaservnuovostep6.php';
	apriurl[6]='profilo/elencoserv.inc.php';
	apriurl[7]='profilo/serviziattivi.inc.php';
	apriurl[8]='profilo/check-in.inc.php';
	apriurl[9]='profilo/autoricercascript.php';
	apriurl[10]='profilo/promemoriaserv.inc.php';
	apriurl[11]='profilo/pren/nuovoserv.php';
	apriurl[12]='profilo/menurist.inc.php';
	apriurl[13]='preventivo/step4-agenzia.php';
	apriurl[14]='detristotav.inc.php';
	apriurl[15]='aggiungipiatti.inc.php';
	apriurl[16]='profilo/servizisosp.inc.php';
	apriurl[17]='profilo/pren/nuovoserv.inc.php';
	apriurl[18]='detaddpren.inc.php';
	apriurl[19]='profilo/modificaservospite.inc.php';
	apriurl[20]='profilo/messaggi_reload.php';
	apriurl[21]='chat_prenotazione.inc.php';
	apriurl[22]='messaggi_prenotazione_reload.php';
	apriurl[23]='pulizie_giorno.php';
	apriurl[24]='puliziev2.php';

	//var apriurl=new Array('profilo/pren/prenotaservnuovostep1.php','profilo/pren/prenotaservnuovostep2.php','profilo/pren/prenotaservnuovostep3.php','profilo/pren/prenotaservnuovostep4.php','profilo/pren/prenotaservnuovostep5.php','profilo/pren/prenotaservnuovostep6.php','profilo/elencoserv.inc.php','profilo/serviziattivi.inc.php','profilo/check-in.inc.php','profilo/autoricercascript.php','profilo/promemoriaserv.inc.php','profilo/pren/nuovoserv.php','profilo/menurist.php','preventivo/step4-agenzia.php','detristotav.inc.php','aggiungipiatti.inc.php','profilo/servizisosp.inc.php','profilo/pren/nuovoserv.inc.php','detaddpren.inc.php','profilo/modificaservospite.inc.php');//19
	var url=url+apriurl[id];
	//alert(id);
	 //alert(url);
	//alert('TXT'+id);
	//alert(campo);
	
	query=new Array();
	//query['IDcode']=IDcode;
	var str=new String(str);
	//alert(str);
	if(str.length>0){
		var vettore=str.split(',');
		if(vettore.length>0){
			for (prop in vettore) {
				query['dato'+prop]=vettore[prop];
				//alert(query['dato'+prop]);
			}
		}else{
			query['dato0']=str;
		}
	}	
	if(loader!=1){
		myApp.showIndicator();
	}
	
	$$.ajax({
            url: url,
                method: 'GET',
				dataType: 'text',
				cache:false,
				timeout:5000,
                data: query,
                success: function (data) {
					 //alert(data);

					myApp.hideIndicator();
					$$('#'+campo).html(data);
					//alert(id);
				
					switch(agg){
						case 1:
							/*
							setdatepcal('datanascita');
							setdatepcal('dataril');
							*/
						break;
						case 2:
							$$('.tl').removeClass('active');
							$$('#m'+query['dato1']).addClass('active');
							
			 
						break;
						case 3:
							$$('.rigasub').removeClass('activerigasub');
							$$('#btn'+query['dato0']).addClass('activerigasub');
						break;	
						case 4:
							$('.scroll_prenota_data').removeClass('data_scelta');
							$('#prenota_data'+query['dato1']).addClass('data_scelta');
							var alt=$('#prenota_data'+query['dato1']).attr('alt');
									
							if(alt=='no_prenota'){
							 
									$('#prenotaora').fadeOut(200);
							}else{
					 
								$('#prenotaora').fadeIn(200);
							}
							
			 			break;	
			 			case 5://messaggi

							var myMessages = myApp.messages('.messages', {
   								 autoLayout: true
							}); 

							myMessages.scrollMessages();
			 			break;
			 			case 6:
			 				var serv_prenotato=$('#serv_prenotato').val();

			 				if(parseInt(serv_prenotato)==0){
			 					$('#btn_menu').fadeIn(200);
			 				}else{
			 					$('#btn_menu').fadeOut(200);
			 				}
			 			break;	
			 			case 7:
			 				var tipo_schermo=$('#schermata_pul').val();


			 				if(tipo_schermo==0){
		 						$('.sub_tab').fadeIn();

			 						$('.notifiche_tab').each(function(){
			 							var alt=$(this).attr('alt');
			 							var val=$(this).val();

			 							if(val>0){
			 								$('#t'+alt).append('<div class="numero_not_giorn"> '+val+' </div>');
			 							}else{
			 								$('#t'+alt+' .numero_not_giorn').remove();
			 							}
			 						});

			 						var tabact=$('.button.tab-link.active').attr('alt');	

			 						cambia_tab_pul(tabact);
			 				}else{
			 					$('.sub_tab').css('display','none');
			 					$('#puliziediv .body').scroll(function() {
									$('#puliziediv .header').scrollLeft($(this).scrollLeft());
								});
								$('#puliziediv .body').scroll(function() {
									$('#puliziediv .side').scrollTop($(this).scrollTop());
								});
								
								var h=parseInt($('.page-content ').innerHeight() - 51);
								$('#puliziediv').css('height',h+'px');

								console.log(h);


			 				}
   
			 			break;
 
						
					}
					loadstaticios();
					$$('.page-content').scrollTop=0;			
         },
		 error: function (data) {
		 
					myApp.hideIndicator();
				}
     });	
}

var messaggi_reload=0;
var servizio_premuto=0;
var chat_profilo_reload=0;

function navigation2(id,str,agg,rel){
	
	var url=baseurl+versione+"/";
	
	
	
	id=parseInt(id);	
	
	var apriurl=[];
	apriurl[0] = 'config/profilo/metodopag.php';
	apriurl[1] = 'config/profilo/galleria.php';
	apriurl[2] = 'config/profilo/fotoalbum.php';
	apriurl[3] = 'config/profilo/elencoluoghi.php';
	apriurl[4] = 'config/profilo/pren/prenotaservnuovo.php';
	apriurl[5] = 'config/profilo/elencoserv2.php';
	apriurl[6] = 'config/profilo/serviziattivi.php';
	apriurl[7] = 'config/profilo/infoutili.php';
	apriurl[8] = 'config/profilo/contatti.php';
	apriurl[9] = 'config/registrazione.php';
	apriurl[10] = 'config/profilo/check-in.php';
	apriurl[11] = 'config/profilo/privacypol.php';
	apriurl[12] = 'config/profilo/promemoriaserv.php';
	apriurl[13] = 'config/profilo/pren/nuovoserv.php';
	apriurl[14] = 'config/profilo/menurist.php';
	apriurl[15] = 'config/profilo/servizisospesi.php';
	apriurl[16] = 'config/cambiadatapren.php';
	apriurl[17] = 'config/metodipag.php';
	apriurl[18] = 'config/detristotav.php';
	apriurl[19] = 'config/profilo/infoprenotazione.php';
	apriurl[20] = 'config/profilo/servizisosp.php';
	apriurl[21] = 'config/profilo/modificaservospite.php';
	apriurl[22] = 'config/detaddpren.php';
	apriurl[23] = 'config/profilo/luoghi.php';
	apriurl[24] = 'config/profilo/itinerario.php';
	apriurl[25] = 'config/profilo/elenco_luoghi.php';
	apriurl[26] = 'config/profilo/elenco_itinerari.php';
	apriurl[27] = 'config/profilo/elenco_serv_new.php';
	apriurl[28] = 'config/profilo/elenco_serv_dettaglio.php';
	apriurl[29] = 'config/profilo/check-in_new.php';
	apriurl[30] = 'config/profilo/messaggi.php';
	apriurl[31] = 'config/chat_prenotazione.php';
	apriurl[32]='config/messaggi_prenotazione.php';
	//var apriurl=new Array('config/profilo/metodopag.php','config/profilo/galleria.php','config/profilo/fotoalbum.php','config/profilo/elencoluoghi.php','config/profilo/pren/prenotaservnuovo.php','config/profilo/elencoserv2.php','config/profilo/serviziattivi.php','config/profilo/infoutili.php','config/profilo/contatti.php','config/registrazione.php','config/profilo/check-in.php','config/profilo/privacypol.php','config/profilo/promemoriaserv.php','config/profilo/pren/nuovoserv.php' ,'config/profilo/menurist.php','config/profilo/servizisospesi.php','config/cambiadatapren.php','config/metodipag.php','config/detristotav.php','config/profilo/infoprenotazione.php','config/profilo/servizisosp.php','config/profilo/modificaservospite.php','config/detaddpren.php');
	//last 22
	
	var url=url+apriurl[id];
	//alert(url);
	
	
	
	/*if(IDcode=='undefined'){
		onloadf(1);
	}*/
	//alert(str);
	//alert('NAV'+id);
	var query=new Array();
	//query['IDcode']=IDcode;
	//alert(IDcode);
	var str=new String(str);
	

	
	
	if(str.length>0){
		var vettore=str.split(',');
		if(vettore.length>0){
			
			for (prop in vettore) {
				query['dato'+prop]=vettore[prop];
			}
		}else{
			query['dato0']=str;
			
		}
	}
	
	myApp.showIndicator();//setTimeout(function(){ hidelo(); }, 5500);	
	//alert(url);
	
	$$.ajax({
            url: url,
                method: 'GET',
				dataType: 'text',
				cache:false,
				timeout:5000,
                data: query,
                success: function (data) {
					
					//alert(data);	
					 //console.log(data);
					
					myApp.hideIndicator();
					clearTimeout();
					switch(rel){
						case 1:
							//alert(data);
							mainView.router.load({
							  content: data,
							  reload:true
							});
							//alert('ok');
						break;
						case 2:
							
							//alert(data);
							setTimeout(function(){ 
								mainView.router.load({
								  content: data,
								  reload:true
								});
							}, 250);
							
						break;
						default:
							//pageprev=mainView.activePage.name;
						//	alert(pageprev);
							mainView.router.load({
							  content: data
							});
						break;
					}
					
					
					//mainView.router.loadContent({content:data,force:true});
					switch(agg){
						case 1: 
							var mySwiper5 = myApp.swiper('.swiper-5', {
							  pagination:'.swiper-5 .swiper-pagination',
							  spaceBetween: 10,
							  slidesPerView: 3});
						break;
						case 2:
								statostep=0;
								scorristep(0,str);
						break;
						case 3:
							var mySwiper=myApp.swiper(' .sw1', {
								 spaceBetween: 10,
									 slidesPerView: 1});
						
							var sslide=$$('.giornosel').attr('padre');
							mySwiper.slideTo(sslide,400);

							var serv_prenotato=$('#sotto'+query['dato0']).attr('alt');

			 				if(parseInt(serv_prenotato)==0){
			 					$('#btn_menu').fadeIn(200);
			 				}else{
			 					$('#btn_menu').fadeOut(200);
			 				}
 	
						
						break;
						case 4:

						/*
							timeout=0;
							if(rel==2){
								timeout=500;
							}
							setTimeout(function(){
								var mySwiper=myApp.swiper(' .sw1', {
									 spaceBetween: 10,
  									 slidesPerView: 1});
							
								var sslide=$$('.giornoselserv').attr('padre');
								mySwiper.slideTo(sslide,400);
							},timeout);
							*/
						break;	
						case 5: //load checkin
							/*
							setdatepcal('datanascita');
							setdatepcal('dataril');
							*/
						break;
						case 6:
							//setTimeout(function(){
								
								var myCalendar5 = myApp.calendar({
									input: '#cambiadatapren',
									weekHeader: true,
									header: false,
									footer: false,
									closeOnSelect:true,
									dateFormat: 'yyyy-mm-dd',
									rangePicker: false,
									onChange:function (p, values, displayValues){
										
										var val=new Date(values);
											
										var gg=val.getDate();
										if(gg<10)gg='0'+gg;
										var mm=val.getMonth();
										mm++;
										if(mm<10)mm='0'+mm;
										var yy=val.getFullYear();
										var data=yy+'-'+mm+'-'+gg;
										var data2=val.getTime()/1000;
										
										var datai=$$('#calsposta').html();
										if(data!=datai){
											$$('#calsposta').html(data);
											$$('#calsposta2').html(data2);
											trasftesto3(data,'cambiadatapren');

											navigationtxt(38,data,'cambiadatatxt',0);
										}
										
										
										
									}
								});
								setTimeout(function(){
									
									var data=$$('#calsposta').html();
									var dataf3=new Date(data);
									myCalendar5.value=[dataf3];
									
								},500);
								
							//},500);	
						break;
						case 7:
							var calendararrivo = myApp.calendar({
									input: '#startcal',
									weekHeader: true,
									header: false,
									footer: false,
									closeOnSelect:true,
									dateFormat: 'yyyy-mm-dd',
									monthNames: monthNames,
									monthNamesShort: monthNamesShort,
									dayNames: dayNames,
									dayNamesShort: dayNamesShort,
									onChange:function (p, values, displayValues){
											var val=new Date(values);
											var gg=val.getDate();
											if(gg<10)gg='0'+gg;
											var mm=val.getMonth();
											mm++;
											if(mm<10)mm='0'+mm;
											var yy=val.getFullYear();
											var data=yy+'-'+mm+'-'+gg;
											var data2=val.getTime()/1000;

											var datai=$$('#datains').html();
											if(data!=datai){
												$$('#datains').html(data);
												$$('#timeins').html(data2);
												trasftesto3(data,'startcal');

											}
										
										calendararrivo.close();
									}
							});
										
							
						break;
						
						case 8://paypal script
							
								var paypal=$('#pulsantepaypal').val();
								paypal=atob(paypal);
								eval(paypal);	
						break;	
						case 9:
								if(filtro_servizio!=0){//filtro tipologie
									filtrogenerale(filtro_servizio,1);
								}
							
								if(exist!=0){//filtro giorni 
									var giorno=exist;
									exist=0;
									scrollbox(1,giorno);
									$('#scroller').animate({scrollLeft: $('#scrollbox'+giorno).offset().left - 120}, 800);
								}else{
									if(sosp==1){//filtro servizi sospesi
										if($('#sosp').length==0){
											scrollbox(0);
										}else{
											sosp=0;
											scrollbox(0);
										}	
									}
								}
							
								if(servizio_premuto!=0){//scorro al servizio premuto
									$('#paginascroll').animate({scrollTop: $('#riga'+servizio_premuto).offset().top - 120 }, 800);
									servizio_premuto=0;
								}
						break;
						case 10:
								servizio_premuto=query['dato0'];
						break;
						case 11:
							exist=0;
							servizio_premuto=0;
							filtro_servizio=0;
						break;	
						case 12:
								var alt=$('#serv'+query['dato0']).attr('alt'); 
								if(alt=='no_prenota'){
									$('#prenotaora').fadeOut(200);
								 
								}else{
									$('#prenotaora').fadeIn(200);
									 
								}
						break;	
						case 13:

							$('#luoghi_page').scroll(function() {
								var off2=Math.round($('#navbar_sotto').offset().top);
								if(off2<=0){
										$('#navbar_sopra').removeClass('copri_scritta');
										$('#navbar_sotto').css('visibility','hidden');

								}else{
										$('#navbar_sopra').addClass('copri_scritta');
										$('#navbar_sotto').css('visibility','visible');
							 
								}
							});
						break;
						case 14:	
				$('.dropdown_ajax').each(function() {
									var idcliente=$(this).attr('data-cliente');
							        var tipo=$(this).attr('data-tipo');
									var autocompleteDropdownAjax = myApp.autocomplete({
								    input: $(this),
								    openIn: 'dropdown',
								    preloader: true, //enable preloader
								    valueProperty: 'id', //object's "value" property name
								    textProperty: 'name', //object's "text" property name
								    limit: 20, //limit to 20 results
								   	dropdownPlaceholderText: 'Ricerca..',
								    expandInput: true, // expand input
								   onChange: function (autocomplete, value) {
								     	//console.log('aa');
								       
								        
								       	salva_autoricerca(value.id,tipo,idcliente);
								       	$('#'+tipo+'_'+idcliente).val(value.name);
								        
								    },
								    source: function (autocomplete, query, render) {
								        var results = [];
								        if (query.length === 0) {
								            render(results);
								            return;
								        }
								        // Show Preloader
								        autocomplete.showPreloader();
								        // Do Ajax request to Autocomplete data
								        
								        $.ajax({
								            url:baseurl+versione+'/config/profilo/alloggiati_dropdown.php',
								            method: 'GET',
								            dataType: 'json',
								            //send "query" to server. Useful in case you generate response dynamically
								            data: {
								                query: query,
								                tipo:tipo
								            },
								            success: function (data) {
								                // Find matched items
								                for (var i = 0; i < data.length; i++) {

								                    if (data[i].name.toLowerCase().indexOf(query.toLowerCase()) >= 0){
								                    	results.push(data[i]);
								                    }
								                }
								                autocomplete.hidePreloader();

								                render(results);
								            }
								        });
								    }/*,onChange: function (autocomplete, value) {
							    		console.log(value);
								        // Add item text value to item-after
								        //$$('#autocomplete-standalone').find('.item-after').text(value[0]);
								       // $$('#autocomplete-standalone').find('input').val(value[0]);
								    },onClose:function(autocomplete,value){
								    	console.log(value);
								    
								    }*/
								     
									    
								});
							});
						
						break;

						case 15://chat messaggi Profilocli
							messaggi_reload=setInterval('reload_mess()',10000); 

							var conversationStarted = false;

							var myMessagebar = myApp.messagebar('.messagebar', {
	    						maxHeight: 200
							}); 

							var myMessages = myApp.messages('.messages', {
   								 autoLayout: true
							});   


							myMessages.scrollMessages();

							$('.messagebar .link').on('click', function () {
								  // Message text
								  var messageText = myMessagebar.value().trim();
								  // Exit if empy message
								  if (messageText.length === 0) return;
								 
								  // Empty messagebar
								  myMessagebar.clear();


								 /*
								  // Add message
								  myMessages.addMessage({
								    // Message text
								    text: messageText,
								    // Random message type
								    type: 'sent'
								    // Avatar and name:
								 	/*
									    day: !conversationStarted ? 'Today' : false,
									    time: !conversationStarted ? (new Date()).getHours() + ':' + (new Date()).getMinutes() : false
								   
								  })
								  */
								  modprofilo(0,messageText,46,10,14);
								 
								  // Update conversation flag
								  conversationStarted = true;

								});   

						break;
						case 16://chat messaggi Profilo

							var IDpren=$('#IDpren').val();

							chat_profilo_reload=setInterval('reload_chat_profilo()',10000); 

							var conversationStarted = false;

							var myMessagebar = myApp.messagebar('.messagebar', {
	    						maxHeight: 200
							}); 

							var myMessages = myApp.messages('.messages', {
   								 autoLayout: true
							});   


							myMessages.scrollMessages();

							$('.messagebar .link').on('click', function () {
								  // Message text
								  var messageText = myMessagebar.value().trim();
								  // Exit if empy message
								  if (messageText.length === 0) return;
								 
								  // Empty messagebar
								  myMessagebar.clear();


								 /*
								  // Add message
								  myMessages.addMessage({
								    // Message text
								    text: messageText,
								    // Random message type
								    type: 'sent'
								    // Avatar and name:
								  
									    day: !conversationStarted ? 'Today' : false,
									    time: !conversationStarted ? (new Date()).getHours() + ':' + (new Date()).getMinutes() : false
								     
								  })
								  */

								  modprenot(IDpren,messageText,272,10,15);
								 
								 
								  // Update conversation flag
								  conversationStarted = true;
								});  


						break;
					 
						 
							
					}
					loadstaticios();
         },
		error:function(data){
			myApp.hideIndicator();
		}
     });
}

function reload_mess(){//profilocli
	navigationtxt2(20,0,'messaggi',5,1);
}

function reload_chat_profilo(){//profilo
	var IDpren=$('#IDpren').val();
	navigationtxt2(22,IDpren,'messaggi',5,1);
}

function salva_autoricerca(idmod,tipo,idcliente){
	//console.log(idmod,tipo,idcliente);
	var campo='';
	var tipoprenot=0;//modprenot
	switch(parseInt(tipo)){	
		case 1:
			campo='.cittadinanzaver';
			tipoprenot='48';
		break;
		
		case 2:
			campo='.luogonasver';
 			tipoprenot='49';
		break;
			
		case 3:
			campo='.residenzaver';
			tipoprenot='47';
		break;
		
		case 4:
			campo='.luogorilver';
			tipoprenot='46';
		break;
		
		case 5:
			campo='.documentover';
			tipoprenot='50';
		break;	
	}

	modprenot(idcliente,idmod,tipoprenot,10);
	 
}


function paypalload(ID,acconto,dato1,dato2){
	
	
		 	paypal.Button.render({

	
            env: 'sandbox', // sandbox | production
		
			style: {
				label: 'buynow',
				//fundingicons: true, // optional
				branding: true, // optional
				size:  'large', // small | medium | large | responsive
				shape: 'pill',   // pill | rect
				color: 'blue'   // gold | blue | silve | black
			},



            client: {
            sandbox:    'AUU5djpGmLVeRO6nT-njYN95WsURRkoq7v4FbypEZRVraWheDgL00tw4_C_1U1s_-13DR_RCXDm7F3ZD',
            production: dato1
            },
    
            payment: function(data, actions) {
                return actions.payment.create({
                    payment: {
                        transactions: [
                            {
                                amount: { total: acconto, currency: 'EUR' }
                            }
                        ]
                    }
                });
            },

            // onAuthorize() is called when the buyer approves the payment
            onAuthorize: function(data, actions) {

                // Set up a url on your server to execute the payment
               // var EXECUTE_URL = 'https://www.scidoo.com/config/negozioonline/generavoucheronline.php';
				
				var EXECUTE_URL =baseurl+'config/salvapagamentopaypal.php';
				//alert(EXECUTE_URL);
                var data = {
                    paymentID: data.paymentID,
                    payerID: data.payerID,
					IDpren: ID,
					valore:acconto
                };
 
					
				return actions.payment.execute().then(function() {
					$.post(EXECUTE_URL,data,function(html){
						//window.alert(html);
						 alert(html);
					});	
				});
				
			
				/*
                	return paypal.request.post(EXECUTE_URL, data).then(function (res) {
						//alert('aa');
						//window.alert(res);
						 alert(res);
                    });*/
            }

        }, '#paypal-button-container');
	}


function spostapren(vettore,vettoremain){
	//alert('bbb');
	//var datain=$$('#calsposta2').html();
	//var IDpren=$$('#IDprensposta').html();
	//var app=$$('input[name="alloggio"]:checked').val(); 
	//alert('aaa');
	//if((datain!='')&&(IDpren!='')&&(app!='')){
		
	var dataString = "vettore=" + vettore + "&vettoremain=" + vettoremain;	
	//alert(dataString);	
	   var url=baseurl+'config/spostamento/crea.php';
		//alert(url);
		$$.ajax({
				url: url,
				method: 'POST',
				dataType: 'text',
				timeout:5000,
				cache:false,
				data: {vettore:vettore,vettoremain:vettoremain},
				success: function (html) {
					//alert(html);
					var num=html.indexOf("error");
					if(num!=-1){
						myApp.addNotification({
							message: "Non e' stato possibile spostare la prenotazione. Prego Riprovare",
							custom:'<div class="item-title">Non e'+"' stato possibile spostare la prenotazione. Prego Riprovare"+'</div>',
							hold:1000
						});
					}else{
						myApp.confirm('Vuoi davvero spostare la prenotazione?', function () {
							spostapren2();
						});
					}

				}
		});
	/*}else{
		myApp.addNotification({
			message: 'Compila tutti e campi e Riprova!',
			hold:1000
		});
	}*/
	
	

}



function spostapren2(){

	var url=baseurl+'config/spostamento/sposta.php';
	myApp.showIndicator();
	$$.ajax({
			url: url,
			method: 'POST',
			dataType: 'text',
			timeout:8000,
			cache:false,
			data: {},
			success: function (data) {
				//alert(data);
				myApp.hideIndicator();
				var num=data.indexOf("ok");
				
				if(num!=-1){
					myApp.addNotification({
						message: 'Prenotazione Spostata con successo',
						custom:'<div class="item-title">Prenotazione Spostata con successo</div>',
						hold:1000
					});
					mainView.router.back();
					backexplode(12,0);
				}

			},
			error:function(data){
				myApp.hideIndicator();
			}
	});

}

/*
var timeoutdate=null;


function setdatepcal(id){

	setTimeout(function(){
			var data1=$('#'+id).val();
			//alert(data1);
			var today=adddata(data1,0,2,3);
			//alert(today);
		
		
							//var today = new Date();

							var pickerInline = myApp.picker({
								
								container: '#'+id+'-picker',
								toolbar: false,
								rotateEffect: true,

								value: [today.getMonth(), today.getDate(), today.getFullYear(), today.getHours(), (today.getMinutes() < 10 ? '0' + today.getMinutes() : today.getMinutes())],

								onChange: function (picker, values, displayValues) {
									//alert(picker.value[2]);
									//alert(picker.value[0]);
									//alert(picker.value[1]);
									var dd=picker.value[2].split(',');
									var datain=picker.value[2]+'-'+convert((parseInt(picker.value[0])+parseInt(1)))+'-'+convert(picker.value[1]);
									//alert(datain);
									var daysInMonth = new Date(datain);
									//alert(daysInMonth);
									var data2=adddata(daysInMonth,0,0,2);
									//alert(data2);
									$('#'+id).val(data2);
									
									clearTimeout(timeoutdate);

									// Make a new timeout set to go off in 800ms
									timeoutdate = setTimeout(function () {
										$('#'+id).trigger('change');
										clearTimeout(timeoutdate);
									}, 3500);
									
									
									
									var data2=adddata(daysInMonth,0,0,1);
									
									$('#'+id+'txt').html(data2);
									/*if (values[1] > daysInMonth) {
										picker.cols[1].setValue(daysInMonth);
									} 
								},
								formatValue: function (p, values, displayValues) {
									return displayValues[0] + ' ' + values[1] + ', ' + values[2] + ' ' + values[3] + ':' + values[4];
								},
								cols: [
									// Months
									{
										values: ('0 1 2 3 4 5 6 7 8 9 10 11').split(' '),
										displayValues: ('January February March April May June July August September October November December').split(' '),
										textAlign: 'left'
									},
									// Days
									{
										values: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31],
									},
									// Years
									{
										values: (function () {
											var arr = [];
											for (var i = 1900; i <= 2030; i++) { arr.push(i); }
											return arr;
										})(),
									}
								]
							});                

							},500);
	
	
	
	
	
	
}
*/

function convert(num){
	if(num<10){
		num='0'+num;
	}
	return num;
}


function metodopag(){
	
	var buttons=new Array();		
	
	var infop=$('#infop').val();
	infop=atob(infop);
	eval(infop);
	
	 var buttons3 = [
			{
				text: '<div class="lastbutton-modal">Chiudi</div>'
			}
		];
		
		 var groups = [buttons,buttons3];
  	     myApp.actions(groups);
}

function controllocarta2(){
	var number=$$('#ncarta').val();
	var annos=$$('#annos').val();
	var meses=$$('#meses').val();
	var intes=$$('#intes').val();
	//alert('aaa');
	if((number.length>1)&&(intes.length>4)){		
		var url=baseurl;
		//alert(url);
		var url=url+'config/preventivoonline/config/controllocarta.php';
		$$.ajax({
				url: url,
				method: 'POST',
				dataType: 'text',
				timeout:5000,
				cache:false,
				data: {number:number,meses:meses,annos:annos},
				success: function (data) {
					//alert(number+'-'+data);
					myApp.hideIndicator();
					var num=data.indexOf("error");
					if(num==-1){
						var txt=number+'_'+annos+'_'+meses+'_'+intes;
						var IDpren=$$('#IDprenfunc').val();
						modprofilo(IDpren,txt,3,10,9);
					}else{
						myApp.alert("Potrebbe esserci stato un errore nell'inserimento della carta di credito. Prego ricontrollare i numeri o contattare la struttura.");
					}
				},
				 error: function (data) {
					 myApp.hideIndicator();
				}
		});
	}else{
		myApp.addNotification({
			message: "E' necessario compilare tutti i campi obbligatori.",
			custom:'<div class="item-title">E'+"' necessario compilare tutti i campi obbligatori."+'</div>',
			hold:1200
		});
		
	}
	
}

          
//var myApp2 = new Framework7(); 
 
//var $$ = Dom7;



function foto(album,num){
	
	var photoarr=new Array();//	$$('#album'+album+' div')
	$$('#album'+album+' div.foto').each(function() {
			var id=parseInt($$(this).attr('idphoto'));		
			var src=$$(this).attr('alt');
			photoarr[id]=src;
	});
	
var myPhotoBrowser= myApp.photoBrowser({
    photos : photoarr,
	 theme: 'dark',
	ofText: '/',
	navbarTemplate:'<div class="navbar navfoto">'+
    '<div class="navbar-inner">'+
        '<div class="left sliding">'+
            '<a href="#" class="link close-popup photo-browser-close-link {{#unless backLinkText}}icon-only{{/unless}} {{js "this.type === \'page\' ? \'back\' : \'\'"}}">'+
                '<i class="icon icon-back "></i>'+
                '{{#if backLinkText}}<span>{{backLinkText}}</span>{{/if}}'+
            '</a>'+
        '</div>'+
        '<div class="center sliding " style="color: #fff;">'+
          '  <span class="photo-browser-current fs14"></span> '+
           ' <span class="photo-browser-of fs14">{{ofText}}</span> '+
            '<span class="photo-browser-total fs14"></span>'+
        '</div>'+
       '<div class="right"></div>'+
    '</div>'+
'</div>  ',
toolbarTemplate:'<div class="toolbar tabbar navfoto"><div class="toolbar-inner"><a href="#" class="link photo-browser-prev"><i class="f7-icons  biancotool">chevron_left</i></a><a href="#" class="link photo-browser-next"><i class="f7-icons biancotool">chevron_right</i></a></div></div>'
});
	myPhotoBrowser.open(num);
	
	
}

function salvarecensione2(){
	var titolo=$$('#titolo').val();
	var recens=$$('#recensione').val();
	
	
	var voti='';
	
	var mioArray=document.getElementsByClassName('param');
	var lun=mioArray.length; //individuo la lunghezza dell’array 
	for (n=0;n<lun;n++) { //scorro tutti i div del documento
		var val=mioArray.item(n).value
		if(val>0){
			var id=mioArray.item(n).getAttribute('alt');
			voti=voti+id+','+val+'-';
		}
	}
	
	var val=titolo+'/////'+recens+'/////'+voti;
	//alert(val);
	if((titolo.length>5)&&(recens.length>10)){
		modprofilo(0,val,9,10,6);
	}else{
		myApp.alert("E' obbligatorio inserire un titolo ed un testo alla recensione. Prego riprovare.");
	}
	
}


function modorarioospite(IDpren){
 
myApp.showIndicator();
var url=baseurl+versione+"/";
var url=url+'config/profilo/orarioarrivo.php'; 
$$.ajax({
			url: url,
			method: 'GET',
			dataType: 'text',
			cache:false,
			timeout:5000,
			data: {},
			error: function (data) {
				myApp.hideIndicator();
			},
			success: function (data) {
				myApp.hideIndicator();
				myApp.pickerModal(data);
				popoverord();
			}
});

}

function modificatemp(metodo,pren,tipo){
	var consta=0.5;
	switch(tipo)
		{
			case 1:
				var temp=$$('#tempgiorn').html();
				var max=$$('#tempgiorn').attr('max');
				var min=$$('#tempgiorn').attr('min');
			break;
			case 2:
				var temp=$$('#tempnotte').html();
				var max=$$('#tempnotte').attr('max');
				var min=$$('#tempnotte').attr('min');
			break;	
		}
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
	if(tipo==1){
		$$('#tempgiorn').html(temp);
	}else{
		$$('#tempnotte').html(temp);
	}
	//modprofilo(pren,temp,tipo,10,0);
}

function modtemper(pren){
	var tempg=$$('#tempgiorn').html();
	var tempn=$$('#tempnotte').html();
	
	//1 giorno
	modprofilo(pren,tempg,1,10,0);
	
	//2 notte
	modprofilo(pren,tempn,2,10,0);
}



var statostep=0;
function scorristep(step,str){
	$$('.page-content').scrollTo(0,0);
	var show=1;
	var agg=1;
	if(step==-1)agg=0;
	
	switch(step){
		case 0:
			indipren=0;
			show=0;
		break;
		case 1:
			statostep++;
		break;
		default:
			statostep--;
		break;
	}
	
	
	var step=parseInt($$('#step'+statostep).attr('alt'));
	$$('.tabpren').attr('disabled','disabled');
	for(i=0;i<=statostep;i++){
		$$('#buttstep'+i).removeAttr('disabled');
	}

	if(step==2){
		if(indipren==1){
			$$('#indietro').css('display','none');
		}else{
			$$('#indietro').css('display','block');
		}
	}else{
		$$('.avanti').css('display','block');
		$$('#indietro').css('display','block');
	}
	if(step==0){
		//$$('.avanti').css('display','none');
		$$('#indietro').css('display','none');
	}else{
		$$('.avanti').css('display','block');
	}
	
							 	
	if(show!=0){myApp.showTab('#step'+statostep);}
	//alert('STEP:'+statostep+'-'+agg);
	$$('#avantitxt').html('Avanti');
	$$('.bottoneprezzo').css('background','#4cd964');
	switch(step){
		case 0:
			if(agg==1){
				//scegliere data per servizio
				navigationtxt2(0,str,'step'+statostep,0);
			}
		break;
		case 1:
			if(agg==1){
				//scegliere sala per servizio
				navigationtxt2(1,str,'step'+statostep,0);
			}
		break;
		case 2:
			if(agg==1){
				//scegliere ora per servizio
				navigationtxt2(2,str+','+datapren,'step'+statostep,0);
			}
		break;
		case 3:
			if(agg==1){
				// conferma prenotoazione di servizi normali(tipolim!=6)
				var stringadati=orariopren+','+salapren+','+datapren;
				$$('#avantitxt').html('Conferma Prenotazione');
				$$('.bottoneprezzo').css('background','#203a93');
				navigationtxt2(3,str+','+stringadati,'step'+statostep,0);
			}
		break;
		case 4:
		if(agg==1){
			//conferma prenotoazione di servizi con regole(tipolim==6)
			$$('#avantitxt').html('Conferma Prenotazione');
			$$('.bottoneprezzo').css('background','#203a93');
			$$('#indietro').css('display','none');
			navigationtxt2(4,str,'step'+statostep,0);
		}
		break;
		case 5:
		if(agg==1){
			//conferma prenotoazione di servizi senza regole(tipolim==6)
			$$('#avantitxt').html('Conferma Prenotazione');
			$$('.bottoneprezzo').css('background','#203a93');
			navigationtxt2(5,str+','+datapren,'step'+statostep,0);
		}
		break;
	
	}
	
}

var datapren='';
var salapren='';
var orariopren='';

function stepdopo(IDserv){
	//alert('DATO'+data0);
	var arraystep=$$('#statostep').val();
	var statoarr=arraystep.split(',');
	var scorri=parseInt(statoarr[statostep]);
	
	
	switch(scorri){
		case 1://funzione step 1
			datapren=valoredata();
			if(datapren.length>0){
					scorristep(1,IDserv);
				}else{
					myApp.alert("Scegliere un giorno.");
				}	
		break;	
		case 2://funzione step 2
			salapren=valoresala();
			if(salapren.length>0){
					scorristep(1,IDserv);
				 	
				}else{
					myApp.alert("Scegliere una sala.");
				}	
		break;
		case 3://funzione step 3
			orariopren=valoretime();
			if(orariopren.length>0){
					scorristep(1,IDserv);
				 	
				}else{
					myApp.alert("Scegliere un orario.");
				}	
		break;
		case 4://calcolo il prezzo su una nuova pagina e ritorno il valore
			//invio la data o ora, servizio e persone
			//calcolaprezzo();
			prenotaoramik();
			//alert('invio dati per la prenotazione');
		break;
	

	}
}
/*
function prendidata(num){
	var iddata='data'+num;
	var id='';
	var tipo=parseInt($$('#tipofun').val());
	switch(tipo)
		{
			case 1://checkbox
				$$('.sceglidataserv').each(function() {
					id=$$(this).attr('id');
					if (id==iddata) 
					{
						if($$(this).hasClass('dataattivata')){
							$$(this).removeClass('dataattivata');
						}else{
							$$(this).addClass('dataattivata');
						}	
					}			
	 		});
			break;
			
			case 2://radiobox
				$$('.sceglidataserv').each(function() {
		 id=$$(this).attr('id');
			if (id==iddata) 
					{
						$$(this).addClass('dataattivata');
	 				}else{
						$$(this).removeClass('dataattivata');
					}
					
			});
				
			break;	
		}
}*/



function valoredata(){
	var tipo=parseInt($$('#tipofun').val());
    var tempo='';
	var temp='';
	switch(tipo)
		{
			case 1://checkbox
					$$('.sceglidataserv').each(function(){
						if ($$(this).hasClass('dataattiva2')){
							temp=$$(this).attr('alt');
							tempo=tempo+temp+',';
			
						}
					});	
			break;
			
			case 2://radiobox  
					$$('.sceglidataserv').each(function(){
						if ($$(this).hasClass('dataattiva2')){
							tempo=$$(this).attr('alt');
						}
					});
			break;	
		}
	return tempo;
}


function prendisala(num){
	
	var iddata='data'+num;
	var id='';
	$$('.prendisala').each(function() {
		 id=$$(this).attr('id');
			if (id==iddata) 
					{
						$$(this).addClass('dataattivata');
	 				}else{
						$$(this).removeClass('dataattivata');
					}
					
			});
	
}

function valoresala(){
    var sala='';
	$$('.prendisala').each(function(){
		if ($$(this).hasClass('dataattivata')){
			sala=$$(this).attr('alt');
		}
	});
	return sala;
}



/*
function prenditime(num){
	
	var idtime='tempo'+num;
	var id='';

	$$('.time').each(function() {
		 id=$$(this).attr('id');
			if (id==idtime) 
					{
						$$(this).addClass('dataattivata');
	 				}else{
						$$(this).removeClass('dataattivata');
					}
					
			});
	
}*/

function valoretime(){
    var time='';
	$$('.time').each(function(){
		if ($$(this).hasClass('dataattiva2')){
			time=$$(this).attr('alt');
		}
	});
	return time;
}


function prendidatav2(){//richiamo fun per prendere il valore delle date selezionate
	
	var tempo=valoredata();
	$$('#timepren').val(tempo);
	
}

function cambiaicona(num){//prendo la/e date per il servizio
	var iddata='data'+num;
	var id='';
	var tipo=parseInt($$('#tipofun').val());
	switch(tipo)
		{
			case 1://checkbox
				$$('.sceglidataserv').each(function() {
					id=$$(this).attr('id');
					if (id==iddata) 
					{
						if($$(this).hasClass('dataattiva2')){
							$$(this).removeClass('dataattiva2');
							$$(this).find(".f7-icons").html('circle');
						}else{
							$$(this).addClass('dataattiva2');
							$$(this).find(".f7-icons").html('check');
						}						
					}			
	 		});
			break;
			
			case 2://radiobox
				$$('.sceglidataserv').each(function() {
		 id=$$(this).attr('id');
			if (id==iddata) 
					{
						
						$$(this).addClass('dataattiva2');
						$$(this).find(".f7-icons").html('check');
						
	 				}else{
						$$(this).find(".f7-icons").html('circle');
						$$(this).removeClass('dataattiva2');
					}
					
			});
				
			break;	
		}
	
}

function cambiaiconatime(num){//prendo l'orario per il servizio
	var iddata='tempo'+num;
	var id='';

				$$('.time').each(function() {
		 id=$$(this).attr('id');
			if (id==iddata) 
					{
						$$(this).addClass('dataattiva2');
						$$(this).find(".f7-icons").html('check');
						
	 				}else{
						$$(this).find(".f7-icons").html('circle');
						$$(this).removeClass('dataattiva2');
					}
					
			});	
}

function controllacheck(){
	var temp='';
	var tempo='';
	$$('.soggetti').each(function(){
					if ($$(this).is(':checked')){
						temp=$$(this).val();
						tempo=tempo+temp+',';
					}
				});
	$$('#idpers').val(tempo);
}

function calcolaprezzo(){
	myApp.showIndicator();
	
	var idserv=$$('#idserv').val();
	var idpers=$$('#idpers').val();
	var timepren=$$('#timepren').val();
	var idsala=$$('#idsala').val();
	
	var url=baseurl+versione+"/";
	var url=url+'config/profilo/pren/calcolaprezzo.php'; 
	$$.ajax({
                url: url,
                method: 'GET',
				dataType: 'text',
				cache:false,
				timeout:5000,
				data: {
					idserv:idserv,
					idpers:idpers,
					timepren:timepren,
					sala:idsala
                },
				 error: function (data) {
					myApp.hideIndicator();
				},
                success: function (data) {
					//alert(data);
				
		}
		
	});
	
}

function tipologiaserv(num){
	
	var buttons=new Array();

	 			var infop=$('#servizi'+num).val();
			    infop=atob(infop);
			    eval(infop);

	
	 var buttons3 = [
			{
				text: '<div class="lastbutton-modal">Chiudi</div>'
			}
		];
		
		 var groups = [buttons,buttons3];
  	     myApp.actions(groups);
}

function cambiaactive(num){
	var id='';
	var idprem='btn'+num;
	
	$$('.rigasub').each(function() {
		 id=$$(this).attr('id');
			if (id==idprem) 
					{
						$$(this).addClass('activerigasub');
	 				}else{
						$$(this).removeClass('activerigasub');
					}
					
			});
	
}

function prenotaoramik(){

	var val1=$$('#timepren').val();
	var idpren=$$('#idpren').val();
	var val2='';
	$$('.soggetti').each(function(i, obj) {
    	if($$(obj).is(':checked')){
			val2=val2+$$(obj).val()+',';
		}
	});
	var val3=$$('#idserv').val();
	var val4=$$('#idsala').val();
	ok=1;
	
	if((ok==1)&&(val2.length==0)){
		myApp.alert("E' necessario selezionare almeno una persona");
		ok=0;
	}
	
	if(ok==1){
		var val=val1+'///'+val2+'///'+val3+'///'+val4;
		modprofilo(idpren,val,4,10,3);	
		//aggiungere sala al modprofilo
		
	}	
}

function prenconferma(idpren){
	modprofilo(idpren,0,40,0,7);
}

function backexplode2(tipo){
	mainView.router.back();
	setTimeout(function (){
			
		switch(tipo){
			case 1:
				var tipol=parseInt($$('#tipo').val());
				navigationtxt2(7,tipol,'servizidiv',0,1);
			break;
			case 2://ristorante				
				
				var viscontenuto=$$('#contenutodiv').val();//ristorante schermata
				navigation(5,'0,'+viscontenuto,3,2);
			break;
			case 3://centro ben
				navigation(4,0,2,2);
			break;	
			case 4:// chat prenotazione
				clearInterval(chat_profilo_reload);
				navigation2(31,0,0,2);
			break;
	

		}
		
		

	},300);
}
function selezionacheck(num){
	var idcheck='check'+num;
	var id='';
		$$('.checkl').each(function() {
			id=$$(this).attr('id');
			if (id==idcheck) 
			{
				if($$(this).hasClass('cehckplus')){
					$$(this).removeClass('cehckplus');
					$$(this).find(".f7-icons").html('circle');
				}else{
					$$(this).addClass('cehckplus');
					$$(this).find(".f7-icons").html('check');
				}						
			}	
	
});
						   }

function datiregistrati(){
	//myApp.showIndicator();
	myApp.showPreloader('Stiamo configurando il gestionale su misura per lei!');
	//var serv2=new Array();
	//var serv=new Array();
	var email=$$('#email').val();
	var pass=$$('#pass').val();
	var nome=$$('#nome').val();
	var nomestr=$$('#nomestr').val();
	var prefisso=$$('#prefisso').val();
	var tel=$$('#tel').val();
	var tipo=$$('#tipo').html();
	var numc=$$('#numc').val();
	var prezzom=$$('#prezzom').val();
	/*
	$$('.checkl').each(function() {
		var id2=$$(this).attr('id2');
		if($$(this).hasClass('cehckplus')){
			serv2[id2]='1';
			serv.push({name: $$(this).attr('name'), value:'1'});
		}else{
			serv.push({name: $$(this).attr('name'), value:'0'});
			serv2[id2]='0';
		}						
	});*/
	
	//serv=$.param(serv);
	var emailReg = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	
	if((email=='')||(pass=='')||(nome=='')||(nomestr=='')||(tel=='')||(numc=='')||(prezzom=='')){
		myApp.alert('Completa tutti i campi e riprova!');
		myApp.hidePreloader();
		
		return false;
	}else{
			if(!emailReg.test(email)) {
            myApp.alert('Email non valida. Prego riprovare.');
				 myApp.hidePreloader();
				return false;
			}else{
				var url=baseurl+'registrazione/registratip.php'; 
				//alert(url);
				$$.ajax({
					url: url,
					method: 'POST',
					dataType: 'text',
					cache:false,
					timeout:20000,
					data: {email:email,pass:pass,nome:nome,nomestr:nomestr,prefisso:prefisso,tel:tel,tipo:tipo,numc:numc,prezzom:prezzom,dove:1
					},
					error: function (data) {
						 myApp.hidePreloader();
						
						 myApp.alert("Potrebbe esserci stato un problema di connessione ad internet. Prego riprovare!", 'Scidoo');
						
					},
					success: function (data) {
						//alert(data);
						 myApp.hidePreloader();
						 myApp.alert("Benvenuto su Scidoo! Il primo gestionale in Italia per la Struttura e per l'Ospite! Vi auguriamo un'ottima esperienza ", 'Scidoo', function () {
							sendform(3);
						});
						
						
						
						

					}
				});
			}
		}
}

function indietroindex(){
	var url2=baseurl;
				
					var url2=url2+versione+'/indexmobile.html';
				
					mainView.router.load({
					 	url: url2,
						animatePages: true,
						reload:true,
						force:true
					});
	
				setTimeout(function (){
						vislogin();
						//mainView.history = [];
						//disableBack();
						azzerastoria();
					},600);
}


function autoricerca(tipo,idcliente,agg){
	myApp.showIndicator();

	var url=baseurl+versione+"/";
	var url=url+'config/profilo/autoricerca.php'; 
	$$.ajax({
                url: url,
                method: 'GET',
				dataType: 'text',
				cache:false,
				timeout:5000,
				data: {
					tipologia:tipo,
					idcliente:idcliente,
					aggfunz:agg
                },
				 error: function (data) {
					//rimuovioverlay();
					 myApp.hideIndicator();
				},
                success: function (data) {
					myApp.hideIndicator();
					myApp.popup(data);
					//myApp.pickerModal(data);
					popoverord();
					
		}
		
	});
}

function autoscrivi(id,tipo,funzagg){
	var nome='';
	var tipoprenot=0;//modprenot
	var tipomodprofilo=0;//modprofilo
	var idcliente=$$('#idcliente').val();
	var campo='';
	
	switch(tipo){	
		case 1:
			campo='.cittadinanzaver';
			tipomodprofilo=30;
			tipoprenot=48;
		break;
		
		case 2:
			campo='.luogonasver';
			tipomodprofilo=31;
			tipoprenot=49;
		break;
			
		case 3:
			campo='.residenzaver';
			tipomodprofilo=29;
			tipoprenot=47;
		break;
		
		case 4:
			campo='.luogorilver';
			tipomodprofilo=28;
			tipoprenot=46;
		break;
		
		case 5:
			campo='.documentover';
			tipomodprofilo=32;
			tipoprenot=50;
		break;	
	}
	
	nome=$$('#'+id).attr('alt').toLowerCase();	
	$$(campo).html(nome);
	
	modprenot(idcliente,id,tipoprenot,10,funzagg);
	
	/*
	switch(funzagg){
		case 0:
			 modprofilo(idcliente,id,tipomodprofilo,10);
		break;
		
		case 1:
			 alert(tipoprenot);
			 modprenot(idcliente,id,tipoprenot,10);
		break;	
	}*/
	
}



function apriacc(num){

	//myApp.accordionOpen($$('#accordion'+num));
	myApp.accordionToggle($$('#accordion'+num));
}

var lastscroll=0;

function effettoscroll(animatingScroll){
	if(animatingScroll == 1){
		return false;
	}
		var scrollleft=$$('#provascroll').scrollLeft();
	
		var dimensionebox=$$('.paginaslider').width();//dimensione base post
		var dimensione=$$('.paginaslider').outerWidth(true);//dimensione del post
			
		//7var passosucc=Math.abs((dimensione/2)+50);
		//var numero=passosucc+lastscroll;
	
		var diff=scrollleft-lastscroll;	
	//alert(diff);
	//$$('#provascroll').animate( { scrollLeft: lastscroll-dimensione }, 1000);
		if(diff<-100){
		//alert(scrollleft);
	    $$('#provascroll').scrollLeft(lastscroll-dimensione);
			var numero=
		lastscroll=$$('#provascroll').scrollLeft();
		  $('#provascroll').stop().animate({scrollLeft:numero}, 1000);
			animatingScroll=1;
			diff=0;
			return false;
	}
	
	if(diff>100){
	   // $$('#provascroll').scrollLeft(parseInt(lastscroll)+parseInt(dimensione)));
		//alert(diff);
		var numero=parseInt(lastscroll)+parseInt(dimensione);
	   $('#provascroll').stop().animate({scrollLeft:numero}, 1000);
		lastscroll=$$('#provascroll').scrollLeft();
			animatingScroll = 1;
			diff=0;
			return false;
   	}
	


	
	/*else{
	   //scorro a sinistra
		$$('#provascroll').scrollLeft(-scrollleft-dimensione);
		lastscroll=$$('#provascroll').scrollLeft();
	}*/
	
	// se l'elemento in vista va sotto i -220 allora passo all'elemento successivo
}

function scorridata(idd){
	
	$$('.sceglig').removeClass('giornosel');
	$$('#td'+idd).addClass('giornosel');
	var tipo=parseInt(($$('#swiper3').val()));
	if(tipo==1){
	$$('.sceglig2').removeClass('giornosel2');
	$$('#ttd'+idd).addClass('giornosel2');
	}
	
	/*
	
	
	$$('.sceglig').each(function() {

					var id=$$(this).attr('id');
					
					if (id==iddata) 
					{
						if($$(this).hasClass(classe)){
							$$(this).addClass(classe+'sel');
							alert(id);
						}
						
					}else{
						$$(this).removeClass(classe+'sel');
					}
	 		});*/
}

/*
function scorridataserv(idd){
	$$('.scegligserv').removeClass('giornoselserv');
	$$('#td'+idd).addClass('giornoselserv');
}
*/

function scorrioraserv(idd){
	$$('.oraservizio').removeClass('oraserviziosel');
	$$('#tempo'+idd).addClass('oraserviziosel');
}

function sceglitiporist(funz){
	
	var tipo=parseInt($$('#contenutodiv').val());
	var tempo=parseInt($$('#timeristo').val());
	if(funz==0){
				switch(tipo){
						

					case 0://tavoli
						$$('#pulscontenuto').html('Tavoli');
						navigationtxt(14,tempo+',1','ristorantediv',0);

					break;

					case 1://menù
						$$('#pulscontenuto').html('Menú');
						navigationtxt(14,tempo+',0','ristorantediv',0);

					break;	
				}
		
	}else{
			navigationtxt(14,funz+','+tipo,'ristorantediv',0);
	}

}

function sceglitiporistgiorno(funz){
	
	var tipo2=parseInt($$('#contenutodivgiorno').val());
	var tempo2=parseInt($$('#timeristogiorno').val());
	var IDtipo=parseInt($$('#IDsottoristogiorno').val());
	if(funz==0){
				switch(tipo2){
					case 0://elenco tavoli
						$$('#pulscontenuto2').html('Elenco Tavoli');					
						navigationtxt(20,tempo2+','+IDtipo+',1','ristorantegiornodiv',0)
					break;

					case 1://sale
						$$('#pulscontenuto2').html('Sale');
						navigationtxt(20,tempo2+','+IDtipo+',0','ristorantegiornodiv',0)

					break;	
				}
		
	}else{
			navigationtxt(20,funz+','+IDtipo+','+tipo2,'ristorantegiornodiv',0);
	}

}



function sceglitipobenessgiorno(funz){

	var IDtipo=parseInt($$('#IDsottocentrogiorno').val());	
	navigationtxt(21,funz+','+IDtipo,'centrobenesseregiornodiv',16);	
}



function cambiadatapren(prenotazione){
	myApp.showIndicator();

	var url=baseurl+versione+"/";
	var url=url+'config/cambiadatapren.php'; 
	$$.ajax({
                url: url,
                method: 'GET',
				dataType: 'text',
				cache:false,
				timeout:5000,
				data: {
					IDpren:prenotazione
					
                },
				 error: function (data) {
					//rimuovioverlay();
					 myApp.hideIndicator();
				},
                success: function (data) {
					myApp.hideIndicator();
					myApp.popup(data);
					//myApp.pickerModal(data);
					popoverord();
					
		}
		
	});
}


function funzionidetpren(idpren){
	
		var buttons=new Array();	

			buttons.push(
					{
					text: '<div>Sposta Prenotazione</div>',
					onClick: function () {
						navigation2(16,idpren,6,0);
					}
				}); 
	
			buttons.push(
					{
					text: '<div class="colorered">Annulla Prenotazione</div>',
					onClick: function () {
					 msgboxelimina(idpren,1,0,2);
					}
				}); 
		/*	
			buttons.push(
					{
					text: '<div class="colorered">modal</div>',
					onClick: function () {
					modalfirstentry();
					}
				});*/


	        
	
	
	 var buttons3 = [
			{
				text: '<div class="lastbutton-modal">Chiudi</div>'
			}
		];
		
		 var groups = [buttons,buttons3];
  	     myApp.actions(groups);
	
	
}

function sceglidisposizione(idpren){
	
	myApp.showIndicator();

	var url=baseurl+versione+"/";
	var url=url+'config/sceglidisposizione.php'; 
	$$.ajax({
                url: url,
                method: 'GET',
				dataType: 'text',
				cache:false,
				timeout:5000,
				data: {
					IDpren:idpren
				},
				 error: function (data) {
					//rimuovioverlay();
					 myApp.hideIndicator();
				},
                success: function (data) {
					myApp.hideIndicator();
					myApp.popup(data);
					//myApp.pickerModal(data);
					popoverord();
					
		}
		
	});
	
	
}


function modalfirstentry(){
	
	myApp.showIndicator();
	var url=baseurl+versione+"/";
	var url=url+'config/firstentry.php'; 
	$$.ajax({
                url: url,
                method: 'GET',
				dataType: 'text',
				cache:false,
				timeout:5000,
				data: {},
				 error: function (data) {
					//rimuovioverlay();
					 myApp.hideIndicator();
				},
                success: function (data) {
					myApp.hideIndicator();
					if(data!=1){
							myApp.popup(data);
							var mySwiper6 = myApp.swiper('.swiper-6', {
								pagination:'.swiper-6 .swiper-pagination',
								spaceBetween: 0,
								slidesPerView: 1,
								allowSlidePrev:false,
								nextButton: '.swiper-avanti'});	
						
							mySwiper6.on('reachEnd', function () {
								$$('#avanti').removeClass('swiper-button-disabled');
								$$('#avanti').attr('onclick','myApp.closeModal();');
								$$('#avanti').children().html('Chiudi');
							});
					}
		}
	});
}


function modalfirstentryospiti(){
	
	myApp.showIndicator();
	var url=baseurl+versione+"/";
	var url=url+'config/firstentryospiti.php'; 
	$.ajax({
                url: url,
                method: 'GET',
				dataType: 'text',
				cache:false,
				timeout:5000,
				data: {},
				 error: function (data) {
					//rimuovioverlay();
					 myApp.hideIndicator();
				},
                success: function (data) {
					myApp.hideIndicator();
					if(data!=1){
							myApp.pickerModal(data);

							$('.salta-swiper').on('click', function () {
								setTimeout(function() {
									aprimodal_share(tipo_tel,0);
								}, 500);
							}); 

							//myApp.popup(data);
							var mySwiper6 = myApp.swiper('.swiper-6', {
								pagination:'.swiper-6 .swiper-pagination',
								spaceBetween: 0,
								slidesPerView: 1,
								allowSlidePrev:false,
								nextButton: '.swiper-avanti'});	

							mySwiper6.on('reachEnd', function () {
								$('#avanti_swiper').removeClass('swiper-button-disabled');
								$('#avanti_swiper').addClass('salta-swiper');
								$('#avanti_swiper').attr('onclick','myApp.closeModal();');
								$('#avanti_swiper').children().html('Chiudi');

							});
						 
					}else{

						setTimeout(function() {
							aprimodal_share(tipo_tel,1);
						}, 500);
					}


		}
	});
		/*	
		window.addEventListener('appinstalled', (event) => {
				 alert( 'appinstalled', event);
		}); */
 
}

function pulsantimenu(){
 
	var buttons=new Array();	

	 			var infomenu=$('#infomenu').val();
			    infomenu=atob(infomenu);
			    eval(infomenu);

	
	 var buttons3 = [
			{
				text: '<div class="lastbutton-modal">Chiudi</div>'
			}
		];
		
		 var groups = [buttons,buttons3];
  	     myApp.actions(groups);
	
}

function contomodprezzo(id,datacar,tipo){
	myApp.prompt('Inserisci prezzo:', function (value) {
	if(!isNaN(value)){
		value=convertnumb(value,0);
		//alert(IDunique);
		//modprenot(id,value,tipo,10,7);
		if(tipo==0){
			
			var ID=id+'_'+datacar;
			alert(ID);
			modprenot(ID,value,3,10,7);
		}
		
	}else{
		myApp.alert('Devi inserire un numero. Prego riprovare');
	}
							
	});
}

function modpagamenti(ID){
		var altbutton=parseInt($$('#'+ID).attr('alt'));
		var buttons=new Array();
		
		switch(altbutton){
			case 1:
				
				buttons.push(
					{
					text: '<div>Apri Dettaglio</div>',
					onClick: function () {
						navigation2(17,ID+',0',0,0);
					}
				}); 
			buttons.push(
					{
					text: '<div>Elimina</div>',
					color:'red',
					onClick: function () {
						msgboxelimina(ID,21,0,4);
					}
				});					
			break;
				
			case 2:
				var prezzo=$$('#'+ID).attr('pr');
				prezzo=prezzo.slice(0,-2);
				
				
				buttons.push(
					{
					text: '<div>Acconto</div>',
					onClick: function () {
						
						myApp.prompt('Inserisci prezzo:', function (value) {
							if(!isNaN(value)){
								value=convertnumb(value,0);
								//alert(IDunique);
								navigation2(17,ID+',1,2,'+value,7,0);
								//listapag(ID,value);
							}else{
								myApp.alert('Devi inserire un numero. Prego riprovare');
							}
							
						});
						
					}
				}); 
				buttons.push(
					{
					text: '<div>Caparra</div>',
					 onClick: function () {
						 myApp.prompt('Inserisci prezzo:', function (value) {
							if(!isNaN(value)){
								value=convertnumb(value,0);
								//alert(IDunique);'."'ID,1,".$IDpag.",value'".'
								navigation2(17,ID+',1,14,'+value,7,0);
								//listapag(ID,value);
							}else{
								myApp.alert('Devi inserire un numero. Prego riprovare');
							}
							
						});
						 	
					}
				}); 
				
				buttons.push(
					{
					text: '<div><strong>Saldo</strong></div>',
					onClick: function () {
						//listapag(ID,value);
						navigation2(17,ID+',1,1,'+prezzo,7,0);
					}
				}); 
				
			break;		
		}
	
		 var buttons3 = [
			{
				text: '<div class="lastbutton-modal">Chiudi</div>'
			}
		];
		
		 var groups = [buttons, buttons3];
  	     myApp.actions(groups);
}


function listapag(ID,value){
	var buttons=new Array();
	var metodipag=$('#metodipag').val();
	metodipag=atob(metodipag);
	eval(metodipag);
	
	 var buttons3 = [
			{
				text: '<div class="lastbutton-modal">Chiudi</div>'
			}
		];
		
		 var groups = [buttons, buttons3];
  	     myApp.actions(groups);
}

function salvascontrino(IDpren,tipo,IDinfop,IDextra,metodo,agg,totale,sconto){
	myApp.showIndicator();
		var url=baseurl;
		var url=url+'config/salvascontrino.php';
		//alert(val);
		var query = {IDpren:IDpren,tipo:tipo,IDinfop:IDinfop,IDextra:IDextra,metodo:metodo,totale:totale,sconto:sconto};
		//alert(url);
		$$.ajax({
			url: url,
			method: 'POST',
			dataType: 'text',
			cache:false,
			data: query,
			timeout:5000,
			success: function (data) {
				myApp.hideIndicator();
				switch(agg) {
					case 1:
						myApp.addNotification({
						message: 'Pagamento registrato',
						custom:'<div class="item-title">Pagamento registrato</div>',
							hold:2000
						});
						backexplode(13);
						
					break;
					
				}
			},
			error: function (){
				myApp.hideIndicator();
			}
	});
}

function datimodpagamento(ID,tipo,totale){
	var giornoins=parseInt($$('#timeins').val());
	
	var metodopag=$('#metodpag option:selected').val();	
	var personale=$('#personale option:selected').val();
	
	salvascontrino(ID,tipo,0,totale,metodopag,1,totale,0);
}

//var stringapiatti='';
var numprod=0;

function selezionainfo(ID,metodo,agg){
	var consta=1;
	var temp=$$('#'+ID).html();
	var max=$$('#'+ID).attr('max');
	var min=$$('#'+ID).attr('min');
	temp=parseFloat(temp);

	switch(metodo){
			case 1:
				max=parseFloat(max);
				if(temp<max){temp=temp+consta;numprod=numprod+consta;}			 	
			break;
			case 2:
				min=parseFloat(min);
				if(temp>min){temp=temp-consta;numprod=numprod-consta;}
			break;	
		}

	$$('#'+ID).html(temp);
		
	switch(agg){
		case 1://temperatura
			var IDpuls=$$('#'+ID).attr('alt');
			modprenot(IDpuls,temp,4,10);
		break;	
		
		case 2://portate
			var IDcont=document.getElementsByClassName('inputpiatti');
			var lung=IDcont.length;
			var stringapiatti='';
			for(i=0;i<lung;i++){
				var idp=IDcont[i].id;
				var val=$$('#'+idp).html();
				var idp=$$('#'+idp).attr('alt');
				stringapiatti=stringapiatti +idp+'_'+val+'/////';		
			}
			var cat=$$('#'+ID).attr('cat');
			$$('#IDsotto'+cat).val(stringapiatti);
			
			$$('#numeroprod').html(numprod);
			
		break;	
		
		case 3://add-remove portate
			var IDprod=$$('#'+ID).attr('alt');
			//var portata=$$('#'+ID).attr('portata');
			var IDtavolo=$$('#IDtavolo').val();
			var agg=5;
			
			if(metodo==2){
				agg=6;
			}
			
			modportatetav(IDtavolo,IDprod,agg,10,1);
			
		break;	
	}
		
	//modprofilo(pren,temp,tipo,10,0);
}


function passadati(campo,totale){
	//alert(campo);
	
	navigationtxt2(13,campo+','+totale,'aggiornagenzie',0,1);
		
}

function modprezzotavolo(){
	myApp.prompt('Inserisci prezzo:', function (value) {
							if(!isNaN(value)){
								value=convertnumb(value,0);
							
							}else{
								myApp.alert('Devi inserire un numero. Prego riprovare');
							}
						});
}


function nuovotavoloord(app){
	var buttons=new Array();
	var time=0;
	var IDsottotip=0;
	if ($$("#timeristogiorno").html() != undefined) {
		time=$$('#timeristogiorno').val();
	}else{
		time=$$('#timeristo').val();
	}
				
			buttons.push(
				{
				text: '<div>Prenotazione Presente</div>',
				onClick: function () {
					
					if ($$("#IDsottotip").html() != undefined) {
						IDsottotip=$$('#IDsottotip').val();
					}
					
					nuovotavolo(time,IDsottotip);
				

				}
			}); 
			buttons.push(
					{
					text: '<div>Nuova Prenotazione</div>',
					onClick: function () {

						addprenot(time,app,0);
					}
				});
	
	var buttons3 = [
			{
				text: '<div class="lastbutton-modal">Chiudi</div>'
			}
		];
	
	 var groups = [buttons, buttons3];
	 myApp.actions(groups);

}

var prodserv='';
function salvatavportata(portata){
	var IDtavolo=$$('#IDtavolo').val();

	prodserv='';
	var mioArray=document.getElementsByClassName('valpiatti');
	var lun=mioArray.length; //individuo la lunghezza dell’array 
	for (n=0;n<lun;n++) { //scorro tutti i div del documento
		//var ID=mioArray.item(n).getAttribute('alt');
		//var qta=mioArray.item(n).innerHTML;
		//alert(ID);
		prodserv=prodserv+mioArray.item(n).value;		
	}
	if(prodserv.length>0){
			//alert(prodserv);
		modportatetav(IDtavolo+'_'+portata,prodserv,2,10,1);
		
	}else{
		myApp.addNotification({
			message: 'Non è stato selezionato nessun prodotto',
			custom:'<div class="item-title">Non è stato selezionato nessun prodotto</div>',
			hold:1700
		});
	}
	

}


function modportatetav(id,campo,tipo,val2,agg){
		myApp.showIndicator();//setTimeout(function(){ hidelo(); }, 4500);	
		switch(val2) {
			case 0:
				var val=$$('#'+campo).val();
				//val=encodeURIComponent(val);
				break;
			case 1:
				var val=$$('#'+campo).val();
				val=val.replace(',','.');
				if(isNaN(val)){
					alertify.alert("E' necessario inserire un numero. Prego Riprovare");
					return false;
				}
				break;
			case 6:
				var val=$$('#'+campo).val();
				val=val.replace(/\n/g, "<br/>"); //descrizione
				//val=encodeURIComponent(val);
				break;
			case 7:
				if(document.getElementById(campo).checked==true){ //si o no
					val='1';
				}else{
					val='0';
				}
				break;
			case 8:
				var val=$$('#'+campo).html();
				break;
			case 9:
				var val=$$('#'+campo).html();
				break;
			case 10:
				val=campo;
			break;
			case 11:
				val=$$(campo).val();
			break;
			case 12:
				val=$$(campo).val();
				id=id+'_'+$$(campo).attr('alt');
			break;
			default:
				var val=$$('#'+campo).val();
			break;
	}
	
	var url=baseurl;
		var url=url+'config/ristorante/gestioneordinazioni.php';
		var query = {val:val,tipo:tipo,ID:id,val2:val2};
		//alert(url);
		$$.ajax({
			url: url,
			method: 'POST',
			dataType: 'text',
			cache:false,
			data: query,
			timeout:7000,
			error: function (data) {
				//rimuovioverlay();
				myApp.hideIndicator();
			},
			success: function (data) {
				//alert(data);
				myApp.hideIndicator();
				reloadnav=1;
				//alert(agg);
				switch(agg){
					case 1:
						myApp.closeModal();
						var IDtav=$$('#IDtavolo').val();
						navigationtxt2(14,IDtav+',0','contdettav',2);
					break;
					case 2:
						myApp.closeModal();
						var IDtav=$$('#IDtavolo').val();
						navigationtxt2(14,IDtav+',1','contdettav',2);
					break;	
					
				}
			}
		});
}

function sceglicategoria(IDcat){
	var prodsel=$$('#IDsotto'+IDcat).val();
	navigationtxt2(15,IDcat+','+prodsel,'aggiungipiatti',3);
}
function gestioneport(portata,tipo){
	var IDtavolo=$$('#IDtavolo').val();
	
	switch(tipo){
		
		case 1://aggiungi portata tav
			modportatetav(IDtavolo,portata,1,10,1);
		break;
		
		case 2://rmuovi portata tav
			modportatetav(IDtavolo,portata,3,10,1);
		break;	
			
	}
}


function servtavprezzo(ID){
	
	myApp.prompt('Inserisci prezzo:', function (value) {
					if(!isNaN(value)){
						value=convertnumb(value,0);
						//alert(value+'-'+ID);
						modprenextra(value,ID,18,9,29);
					}else{
						myApp.alert('Devi inserire un numero. Prego riprovare');
					}
		});
}

function aprinoteristo(IDsotto){
	
	$$('#accordion'+IDsotto).toggleClass('accordionhauto');
	
}

function filtrososp(ID){
	ID=ID.toString();
	var filtroval='tipolim'+ID;

	if(ID!=0){
 		$('.servsosp').fadeIn().filter(':not(.'+filtroval+')').fadeOut(200);	 
	}else{
		$('.servsosp').fadeIn(200);
 
	}	
	
	if($('.fabdiv').hasClass('fabdiv-opened')){
		$('.fabdiv').removeClass('fabdiv-opened');	
		$('.overlay-speed-dial').removeClass('overlay-speed-visible');
	}
	
}

 
 
function overlayfab(){
	
	if($('.fabdiv').hasClass('fabdiv-opened')){
		$('.fabdiv').removeClass('fabdiv-opened');	
		$('.overlay-speed-dial').removeClass('overlay-speed-visible');
	}else{
		$('.fabdiv').addClass('fabdiv-opened');	
		$('.overlay-speed-dial').addClass('overlay-speed-visible');
	}
}

function fab2(tipo){
	switch(tipo){
		case 1:
			var classe='.fb1';
			var classe2='.fb2 , .fb3';
		break;
		case 2:
			var classe='.fb2';
			var classe2='.fb1 , .fb3';
		break;
		case 3:
			var classe='.fb3';
			var classe2='.fb1 , .fb2';
		break;	
		case 0:
			$('.fb1 , .fb2 , .fb3').removeClass('fabdiv-opened');	
			$('.fb2 , .fb1 , .fb3').removeClass('fab_noview');
			$('.overlay-speed-dial').removeClass('overlay-speed-visible');
			return;
		break;	
	}
	
	if($(classe).hasClass('fabdiv-opened')){
		$(classe).removeClass('fabdiv-opened');	
		$(classe2).removeClass('fab_noview');
		$('.overlay-speed-dial').removeClass('overlay-speed-visible');
	}else{
		$(classe).addClass('fabdiv-opened');	
		$(classe2).addClass('fab_noview');
		$('.overlay-speed-dial').addClass('overlay-speed-visible');
	}
}

function mostraserv(tipo){
	switch(tipo){
			
		case 1:
			$('#date').fadeIn(500);
		break;
		
		case 2:
			$('#orario').fadeIn(500);
		break;
	
		case 3:
			$('#persone').fadeIn(500);
		break;	
	}
}


function seldatamodal(idpick){
	
	myApp.showIndicator();

	var url=baseurl+versione+"/";
	var url=url+'config/seldatamodal.php'; 
	$$.ajax({
                url: url,
                method: 'GET',
				dataType: 'text',
				cache:false,
				timeout:5000,
				data:{
					idpick:idpick
				},
				error: function (data) {
					//rimuovioverlay();
					 myApp.hideIndicator();
				},
                success: function (data) {
					myApp.hideIndicator();
					
			 		setTimeout(function(){
						setdatamodalpicker(idpick);	
					},100);
					
					//myApp.popup(data);
					myApp.pickerModal(data);
		}
		
	});
	
}

 

function setdatamodalpicker(idpicker){
	
			var data1=$('#'+idpicker).val();
			var data1=$('#'+idpicker).attr('alt');


			//alert(data1);
			var today=adddata(data1,0,2,3);
			//alert(today);
		
		
							//var today = new Date();

							var pickerInline = myApp.picker({
								
								container: '#carica-datepicker',
								toolbar: false,
								rotateEffect: true,

								value: [today.getMonth(), today.getDate(), today.getFullYear(), today.getHours(), (today.getMinutes() < 10 ? '0' + today.getMinutes() : today.getMinutes())],

								onChange: function (picker, values, displayValues) {
									//alert(picker.value[2]);
									//alert(picker.value[0]);
									//alert(picker.value[1]);
									var dd=picker.value[2].split(',');
									var datain=picker.value[2]+'-'+convert((parseInt(picker.value[0])+parseInt(1)))+'-'+convert(picker.value[1]);
									//alert(datain);
									var daysInMonth = new Date(datain);
									//alert(daysInMonth);
									var data2=adddata(daysInMonth,0,0,2);
									//alert(data2);
						 
									$('#datamodalpicker').val(data2);
							 
									var data2=adddata(daysInMonth,0,0,1);
									
									$('#datamodalpickerformat').val(data2);
								 
								},
								formatValue: function (p, values, displayValues) {
									return displayValues[0] + ' ' + values[1] + ', ' + values[2] + ' ' + values[3] + ':' + values[4];
								},
								cols: [
									// Months
									{
										values: ('0 1 2 3 4 5 6 7 8 9 10 11').split(' '),
										displayValues: ('January February March April May June July August September October November December').split(' '),
										textAlign: 'left'
									},
									// Days
									{
										values: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31],
									},
									// Years
									{
										values: (function () {
											var arr = [];
											for (var i = 1900; i <= 2030; i++) { arr.push(i); }
											return arr;
										})(),
									}
								]
							});                						
}

function formattdatamodalpicker(idpicker){
	var data=$('#datamodalpicker').val();
	var dataform=$('#datamodalpickerformat').val();
	
	myApp.closeModal();
	$('#'+idpicker).val(data);
	$('#'+idpicker).attr('alt',data);
	//$('#'+idpicker+'txt').html(dataform);
	$('#'+idpicker).trigger('change');
	$('#'+idpicker).val(dataform);
	
}


function filtrogenerale(ID,tipo,reset){
	ID=ID.toString();
	var filtroval='';
	var classe='';
	
	switch(tipo){
		case 1:
			filtroval='tipolim'+ID;
			classe='.servsosp';
 
		break;
		
		case 2:
			filtroval='statocam'+ID;
			classe='.statocamere';
		break;
		case 3:
			filtroval='piano'+ID;
			classe='.statocamere';
		break;
		case 4:
			filtroval='time'+ID;
			classe='.time';
		break;	
		case 5:
			classe='.giorni_pul';
			filtroval=ID;
		break;
		case 6:
			filtroval='categoria'+ID;
			classe='.statocamere';
		break;	
		case 7:
			filtroval='msg_leg';
			classe='.messaggi_chat';
		break;
	}	
 
 

		if(reset!=0){
			 $(classe).fadeOut(200).filter('.'+filtroval).fadeIn(200);
		}else{
			$(classe).fadeIn(200);
		}	
	 
	if($('.fabdiv').hasClass('fabdiv-opened')){
		$('.fabdiv').removeClass('fabdiv-opened');	
		$('.overlay-speed-dial').removeClass('overlay-speed-visible');
	}
	
}

 

function vistapulize( ){
	var buttons=new Array();
	var tipo=parseInt($$('#vista').val());
	var time=$$('#time').val();
 
 		switch(tipo){
			
			case 0:
				
				buttons.push(
					{
					text: '<div>Vista Settimanale</div>',
					onClick: function () {
 						navigationtxt(15,time+',1','puliziediv',0);
					}
				});
				
			break;	
			case 1:
				
					buttons.push(
						{
						text: '<div>Vista Giornaliera</div>',
						onClick: function () {
							navigationtxt(15,time+',0','puliziediv',0);
						}
					}); 
			break;			
		}
		
	
			
	
	var buttons3 = [
			{
				text: '<div class="lastbutton-modal">Chiudi</div>'
			}
		];
	
	 var groups = [buttons, buttons3];
	 myApp.actions(groups);
}
 
function datapul(time){
	var tipo=parseInt($$('#vista').val());
	navigationtxt(15,time+','+tipo,'puliziediv',0);
	
}

function scriviorario(val){
	$('#orariotxt').html(val);
}

 
function contattaci( ){
	var buttons=new Array();		
	
	var contatti=$('#btn-info').val();
	contatti=atob(contatti);
	eval(contatti);
	
	/*
 		buttons.push(
					{
					text: '<div>Scrivici</div>',
					onClick: function () {
					 location.href='mailto:'+mail;
					}
				});
	
		buttons.push(
					{
					text: '<div>Chiamaci</div>',
					onClick: function () {
 						location.href='tel:'+tel;
					}
				});
	
	*/
	
	 var buttons3 = [
			{
				text: '<div class="lastbutton-modal">Chiudi</div>'
			}
		];
		
		 var groups = [buttons,buttons3];
  	     myApp.actions(groups);
}


var exist=0;
var sosp=0;
function scrollbox(tipo,giorno){
	switch(tipo){
			
		case 1:
			if(sosp!=0){
				$('#scrollboxsosp').removeClass('activescrollbox');
				$('#sosp').fadeOut(200);
				$('#servizi').fadeIn(200);
				sosp=0;
			}
			if(giorno!=0){
				$('.scrollboxserv').removeClass('activescrollbox');
				$('#scrollbox'+giorno).addClass('activescrollbox');
				
				if(exist==0){
					exist=giorno;
					$('.scrollboxserv').removeClass('activescrollbox');
					$('#scrollbox'+giorno).addClass('activescrollbox');
					filtrogenerale(giorno,4);	
					$('#scrollboxfiltro').fadeIn(200);
				}else{
					
					if(exist==giorno){
						$('.scrollboxserv').removeClass('activescrollbox');
						exist=0;
						filtrogenerale(0,4,0);	
						$('#scrollboxfiltro').fadeOut(200);
					}else{
						exist=giorno;
						$('.scrollboxserv').removeClass('activescrollbox');
						$('#scrollbox'+giorno).addClass('activescrollbox');
						filtrogenerale(giorno,4);	
						$('#scrollboxfiltro').fadeIn(200);
					}	
				}
				
 
			}else{	
				filtrogenerale(0,4,0);	
				$('#scrollboxfiltro').fadeOut(200);
				$('.scrollboxserv').removeClass('activescrollbox');
				 exist=0;
			}
		break;

		case 0:
			if(sosp==0){
				$('.scrollboxserv').not('#scrollboxsosp').removeClass('activescrollbox');
				$('#scrollboxsosp').addClass('activescrollbox');
				$('#servizi').fadeOut(200);
				$('#sosp').fadeIn(200);
				sosp=1;
			}else{
				$('#scrollboxsosp').removeClass('activescrollbox');
				$('#servizi').fadeIn(200);
				$('#sosp').fadeOut(200);
				$('#scrollbox'+exist).addClass('activescrollbox');
				sosp=0;
			}
			
			

		break;	
	}
}

function reloaddata(pren,gg){
	navigationtxt2(19,pren+','+gg,'modificaserv',0);
}

function scegli_pers_nuovo_serv(IDpers){
	var str='';
	
	if($('#'+IDpers).hasClass('pers_scelta')){
		$('#'+IDpers).removeClass('pers_scelta');
	}else{
		$('#'+IDpers).addClass('pers_scelta');
	}
	
	$('.scegli_pers.pers_scelta').each(function() {
	 	var id=$(this).attr('id');
		str=str+id+',';
	});
 
	$('#persone_presenti').val(str);
	
}
 
function scegli_orario_nuovo_serv(time){
	$('.scegli_orario').removeClass('ora_scelta');
	$('#ora'+time).addClass('ora_scelta');
	
	var val=$('#ora'+time).attr('value');
	
	$('#time_scelto').val(val);	
}

function completapren(IDserv,IDpren){
	var id_persone='';
	var orario='';
	var note='';
	
	var giorno=$('#giorno_nav').val();
	
	if($('#persone_presenti').length!=0){
		id_persone=$('#persone_presenti').val();
	}
	
	if($('#time_scelto').length!=0){
		orario=$('#time_scelto').val();
	}
	
	if(orario==''){
		orario='0_'+giorno+'_0';
	}

	note=$('#note').val();
 
 
	
	var stringa=IDserv+'///'+id_persone+'///'+note+'///'+orario;
	
	
	modprofilo(IDpren,stringa,44,10,12);
 
}

function apri_pren_pul(IDpren ) {

 
	myApp.showIndicator();
	var url=baseurl+versione+"/";
	var url=url+'config/pulizie_dettaglio.php'; 
	$.ajax({
			url: url,
			method: 'GET',
			dataType: 'text',
			cache:false,
			timeout:5000,
			data: {
				IDpren:IDpren
				 
			},
			 error: function (data) {
				 myApp.hideIndicator();
			},
			success: function (data) {
				myApp.hideIndicator();
				//myApp.popup(data);
				myApp.pickerModal(data);
				popoverord();		
			}
		
	});
}


function filtro_pulizie_stati(classe){
  	$('.statocamere ').fadeIn(200);
	var IDapp=[];
	$('.riga_txt').each(function() {
		 if($(this).hasClass(classe)){
		 	var id=$(this).attr('alt');
		 	IDapp.push(id);
		 } 
	});

	$('.statocamere ').fadeOut(200);
 
	for (var i = 0; i < IDapp.length; ++i) {
		$('#'+IDapp[i]).fadeIn(200);
		$('#riga'+IDapp[i]).fadeIn(200);
	}
 
 
	if($('.fabdiv').hasClass('fabdiv-opened')){
		$('.fabdiv').removeClass('fabdiv-opened');	
		$('.overlay-speed-dial').removeClass('overlay-speed-visible');
	}
}

function mostra_pren(tipo){

	switch(tipo){
			case 1:
				$('.addebiti').addClass('hiddenaddebita');
				$('.btn_add').addClass('nobtn');

				$('.tab').addClass('selected');	
				$('.tab_addebita').addClass('selected');
			break;
			case 2:
				$('.tab').removeClass('selected');	
				$('.tab_addebita').removeClass('selected');
				$('.addebiti').removeClass('hiddenaddebita');
				$('.addebiti #add2').removeClass('selected');
				$('.addebiti  #add1').addClass('selected');
				$('.addebiti .tab_addebita.1').addClass('selected');
				$('.btn_add').removeClass('nobtn');
			break;
	}
 	
}
 

function mostra_tab_addebita(tab){
	/*
	var pren_sel=$('.title-tab.selected').attr('alt');
	*/
	$('.tab_addebita').removeClass('selected');	
	$('.title_tab_addebita').removeClass('selected');
	
	$('.addebiti  #add'+tab).addClass('selected');
	$('.addebiti  .tab_addebita.'+tab).addClass('selected');
	
	switch(tab){
		case 1:
			$('.btn_add').removeClass('nobtn');
		break;
		case 2:
			$('.btn_add').addClass('nobtn');
		break;	
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
			modprenextra(idprenextra,id+'_'+tipo+'_'+qta,58,9,32);
		break;	
	}
	
	
}

function inserisci_add(){
	//var time=$('#timesel').val();
	var idpren=$('#idpren_pul').val();
	//var idpren=$('.title-tab.selected').attr('alt');
	var prod_addeb='';

	$('.serv').each(function() {
		var qta=parseInt($(this).html());
		if(qta>0){
			var id=$(this).attr('alt');
			prod_addeb=prod_addeb+id+'_'+qta+',';
		}
	});
	
	modprenextra(idpren,prod_addeb,57,9,31);
}

function apri_legenda(){
	$('#legenda').fadeToggle(100);
}

function ricarica_addebito(){
	
	var idpren=$('#idpren_pul').val();
	
	$('.serv').each(function() {
		var qta=$(this).html('0');
	});
 
	myApp.showIndicator();
	var url=baseurl+versione+"/";
	var url=url+'config/pulizie_addebito.php'; 
	$.ajax({
			url: url,
			method: 'GET',
			dataType: 'text',
			cache:false,
			timeout:5000,
			data: {
				IDpren:idpren
			},
			 error: function (data) {
				 myApp.hideIndicator();
			},
			success: function (data) {
				//console.log(data);
				myApp.hideIndicator();
				$('.tab_addebita.2').html(data);
				mostra_tab_addebita(2);
				//myApp.pickerModal(data);		
			}
		
	});
	
}

function nascondi_addebito(IDserv){
	$('.item-contentnew.serv'+IDserv).remove();
}

function contatta_luoghi( ){
	var buttons=new Array();		
	
	var contatti=$('#luoghi_info').val();
	contatti=atob(contatti);
	eval(contatti);
	
	/*
 		buttons.push(
					{
					text: '<div>Scrivici</div>',
					onClick: function () {
					 location.href='mailto:'+mail;
					}
				});
	
		buttons.push(
					{
					text: '<div>Chiamaci</div>',
					onClick: function () {
 						location.href='tel:'+tel;
					}
				});
	
	*/
	
	 var buttons3 = [
			{
				text: '<div class="lastbutton-modal">Chiudi</div>'
			}
		];
		
		 var groups = [buttons,buttons3];
  	     myApp.actions(groups);
}

function aprimodal_info(ID){

	myApp.showIndicator();
	var url=baseurl+versione+"/";
	var url=url+'config/profilo/modal_info.php'; 
	$.ajax({
			url: url,
			method: 'GET',
			dataType: 'text',
			cache:false,
			timeout:5000,
			data: {
				IDinfo:ID
			},
			 error: function (data) {
				 myApp.hideIndicator();
			},
			success: function (data) {
				//console.log(data);
				myApp.hideIndicator();
				//myApp.popup(data);
				myApp.pickerModal(data);
				popoverord();		
			}
		
	});

}

 
function aprimodal_share(OS,tipo){

	//myApp.showIndicator();
	var url=baseurl+versione+"/";
	var url=url+'config/profilo/modal_addtohome.php'; 
	$.ajax({
			url: url,
			method: 'GET',
			dataType: 'text',
			cache:false,
			timeout:5000,
			data: {
				operative_sys:OS,start:tipo
			},
			 error: function (data) {
				 myApp.hideIndicator();
			},
			success: function (data) {
			 
				 if(data!='1'){
			 		myApp.popup(data,true);

					$('.modal-overlay').attr('onclick','elimina_popup()');
				 }
				 	
			}
		
	});
}

function reload_notifiche_mess(){
 
	var url=baseurl+versione+"/";
	var url=url+'config/profilo/conta_notifiche.php'; 
	$.ajax({
			url: url,
			method: 'GET',
			dataType: 'text',
			cache:false,
			timeout:5000,
			data: { 
				ID:1
			},
			 error: function (data) {
				 myApp.hideIndicator();
			},
			success: function (data) {
				 if(data!='0'){
			 		 $('.notifiche_chat').html(data);
			 		 $('.notifiche_chat').addClass('back_red');
				 }
			}
	});
}

 

function foto_luoghi(num){

	var photoarr=[]; 
	$('.luogo_foto').each(function() {
			var alt=$(this).attr('alt');
			var num2=$(this).attr('num');
			photoarr[num2]=alt;
	});

 
	var myPhotoBrowser= myApp.photoBrowser({
	    photos : photoarr,
		theme: 'dark',
		ofText: '/',
		navbarTemplate:'<div class="navbar navfoto">'+
	    '<div class="navbar-inner">'+
	        '<div class="left sliding">'+
	            '<a href="#" class="link close-popup photo-browser-close-link {{#unless backLinkText}}icon-only{{/unless}} {{js "this.type === \'page\' ? \'back\' : \'\'"}}">'+
	                '<i class="icon icon-back "></i>'+
	                '{{#if backLinkText}}<span>{{backLinkText}}</span>{{/if}}'+
	            '</a>'+
	        '</div>'+
	        '<div class="center sliding " style="color: #fff;">'+
	          '  <span class="photo-browser-current fs14"></span> '+
	           ' <span class="photo-browser-of fs14">{{ofText}}</span> '+
	            '<span class="photo-browser-total fs14"></span>'+
	        '</div>'+
	       '<div class="right"></div>'+
	    '</div>'+
	'</div>  ',
	toolbarTemplate:'<div class="toolbar tabbar navfoto"><div class="toolbar-inner"><a href="#" class="link photo-browser-prev"><i class="f7-icons  biancotool">chevron_left</i></a><a href="#" class="link photo-browser-next"><i class="f7-icons biancotool">chevron_right</i></a></div></div>'
	});


	myPhotoBrowser.open(num);

}


function prova_notifica(){
	myApp.addNotification({
		message:'Modifica Salvata',
		custom:'<div class="item-title">Non e'+"' stato possibile spostare la prenotazione. Prego Riprovare"+'</div>',
		hold: 12000
	});
}

function prenota_rapido(){
	var tempo=$('#tempo').val();
	var IDsotto=$('#IDsotto').val();

	apriprenotaora(IDsotto,tempo);
}

function elimina_popup() {
	myApp.closeModal();
	$('.popover').remove();
}

function scorri_elem(el){
	$('#'+el).animate( { scrollLeft: '+=70' }, 400);
}

function schermata_pul(tipo,el){
	$('.schermata_pul').removeClass('active');
	$(el).addClass('active');

	$('#schermata_pul').val(tipo);
	switch(tipo){
		case 0://giorno	 
			navigationtxt2(23,'','pulizie_div_cont',7);	
		break;

		case 1://settimana
			navigationtxt2(24,'','pulizie_div_cont',7);
		break;
	}
}

function cambia_tab_pul(tipo){

	$('.tab-link').removeClass('active');
	$('#t'+tipo).addClass('active');

	$('.tab').removeClass('active');
	$('#tab'+tipo).addClass('active');
}

 
 /*
//let deferredPrompt;
const divInstall = document.getElementById('installContainer');
const butInstall = document.getElementById('butInstall');
window.addEventListener('beforeinstallprompt', (event) => {
  console.log('beforeinstallprompt', event);
  // Stash the event so it can be triggered later.
  window.deferredPrompt = event;
  // Remove the 'hidden' class from the install button container
  divInstall.classList.toggle('hidden', false);
});


butInstall.addEventListener('click', () => {
  console.log('butInstall-clicked');
  const promptEvent = window.deferredPrompt
  if (!promptEvent) {
    // The deferred prompt isn't available.
    return;
  }
  // Show the install prompt.
  promptEvent.prompt();
  // Log the result
  promptEvent.userChoice.then((result) => {
    // Reset the deferred prompt variable, since 
    // prompt() can only be called once.
    window.deferredPrompt = null;
    // Hide the install button.
    divInstall.classList.toggle('hidden', true);
  });
});
*/
 
