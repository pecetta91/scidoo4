// Init App
var myApp = new Framework7({
    modalTitle: 'Scidoo',
	 animatePages:true,
	 cache:true,
	 fastClicks:true,
	 uniqueHistory:true,
	 pushState:true,
	 preloadPreviousPage: false,
	 hideNavbarOnPageScroll: false,
	 modalTitle: 'Scidoo',
	 notificationTitle:'Scidoo',
     notificationCloseOnClick: false,
     notificationCloseIcon: true,
     notificationCloseButtonText: 'Close',
	 smartSelectBackOnSelect: true,
	 cache: true	 
});
//	  swipePanel: 'left'

IDcode=window.localStorage.getItem("IDcode");

// Expose Internal DOM library
var $$ = Dom7;

// Add main view

var mainView = myApp.addView('.view-main', {});
// Add another view, which is in right panel

/*
$$(window).on('popstate', function(){
  myApp.closeModal('.popup.modal-in');
});
*/

$$(window).on('popstate', function(){
 	if ($$('.modal-in').length > 0) { 
		myApp.closeModal();
		blockPopstate=true;
		return false; 
	}else{ 
		//controllo che non stiamo sulla pagina profilo
		switch(mainView.activePage.name){
			case "profilo":
				blockPopstate=true;
				return false; 
			break;
			case "profilocli":
				blockPopstate=true;
				return false; 
			break;
			default:
				blockPopstate=false;
				mainView.router.back();
			break;
		}
	} 
});


var baseurl='http://127.0.0.1/milliont/';
//var baseurl='http://192.168.1.106/milliont/';
//var baseurl='http://192.168.1.100/milliont/';
//var baseurl='https://www.scidoo.com/';

function getUrlVars() {
	var vars = {};
	var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
	vars[key] = value;
	});
	return vars;
}

var guest=getUrlVars()["guest"];
//alert(guest);
if(typeof guest != 'undefined'){
	
	window.localStorage.setItem("IDcode", guest);
	onloadf(0);
	
	//navigation(1,'',7);
}else{
	onloadf(0);
}



var IDutente=0;
	 function sendform(){
        var email = $$('input[name="email"]').val();
        var password = $$('input[name="pass"]').val();
			
		setTimeout(function(){ hidelo(); }, 5500);		
			
		var url = baseurl+'config/login.php';
		//alert(url);
		myApp.showIndicator();setTimeout(function(){ hidelo(); }, 5500);	
		$$.ajax({
                url: url,
                method: 'POST',
				dataType: 'text',
				cache:false,
				data: {
                    email:email,
					password:password,
					json:1,
					callback:'?'
                },
                beforeSend: function (data) {
					
				},
				 error: function (data) {
					//alert(data);
					//console.log(data);
				},
				statusCode: {
					404: function() {
					  alert( "page not found" );
					  myApp.hideIndicator();
					}
				  },
				  
                success: function (data) {
                    //Find matched items
					//alert(data);
					clearTimeout();
					 myApp.hideIndicator();  	 //alert(data);
					var num=data.indexOf("error");  	 //alert(data);
					if(num==-1){		
						window.localStorage.setItem("IDcode", data);
						//alert(data);
						IDcode=data;
						//var query = {IDcode:data};
						navigation(0,'',0);
					}else{
						myApp.addNotification({
							message: "I Dati immessi non sono corretti. Prego riprovare!",
							hold:1200
						});
					}
					 
				}
            })
	}
	
		function sendform2(){
        var email = $$('input[name="mailcli"]').val();
        var data = $$('input[id="kscal"]').val();
		var url = baseurl+'config/logincli.php';
		myApp.showIndicator();
		setTimeout(function(){ hidelo(); }, 5500);	
		//alert(url);
		$$.ajax({
                url: url,
                method: 'POST',
				dataType: 'text',
				cache:false,
				data: {
                    email:email,
					data:data,
					json:1
                },
                beforeSend: function (data) {
					//alert(email);
				},
				 error: function (data) {
					//alert(data);
					//console.log(data);
				},
				statusCode: {
					404: function() {
					  alert( "Problema applicativo. Contattare la struttura" );
					  myApp.hideIndicator();
					}
				  },
				  
                success: function (data) {
                    // Find matched items
                 	 //alert(data);
					 clearTimeout();
					myApp.hideIndicator();
                    //alert(data);
					var num=data.indexOf("error");
					if(num==-1){
						window.localStorage.setItem("IDcode", data);
						IDcode=data;
						//var query = {};
						navigation(1,'',7);

					}else{
						
						
						myApp.addNotification({
							message: "I Dati immessi non sono corretti. Prego riprovare!",
							hold:1200
						});
						
					}
				}
            })	
	
	}

myApp.onPageInit('profilo', function (page) {	
	myApp.initPageSwiper('#tabmain3');
});
myApp.onPageInit('indice', function (page) {	
	/*var calendarDefault = myApp.calendar({
		 input: '#kscal',
		dateFormat: 'dd/mm/yyyy'
	 });*/
	if(IDcode=='undefined'){
		onloadf(1);
	}
});

var p=0;
var scrollcal=0;
function scrollrig(){
		var lef=$$('.table-fixed-right').offset().left*-1+parseInt(172);
		scrollcal=lef;
		if(p==1){
			$$('#tabdate').css('left',lef+'px');
		}
	}	
	
var indipren=0;
function addprenot(time,app){
	
	indipren=1;
	//IDcode=window.localStorage.getItem("IDcode");
		var url=baseurl+"mobile/config/nuovaprenotazione.php";
		//id=parseInt(id);
		//var apriurl=new Array('config/profilo.php','config/profilocli.php','config/calendario.inc.php','config/detpren.php');
		//var url=url+apriurl[id];
		//alert(IDcode);
		
		//var popupHTML = '<div class="popup" style="padding:0px;" id="contprenot"></div>';
		//myApp.popup(popupHTML);
		
		var query={time:time,app:app};			
		myApp.showIndicator();setTimeout(function(){ hidelo(); }, 5500);	
		$$.ajax({
				url: url,
					method: 'GET',
					dataType: 'text',
					cache:false,
					data: query,
					success: function (data) {
						clearTimeout();
						myApp.hideIndicator();
						//$$('#contprenot').html(data);
						
						
						mainView.router.load({
							content: data,
						  animatePages: true
						});
				
						navigationtxt(5,0,'step0',0);
						statostep=0;
						/*if((time!=0)&&(app!=0)){
							stepnew(1,'1,1')	;						
						}
						if((time!=0)&&(app==0)){
							stepnew(1,'0,1')	;
						}*/
						if(time!=0){
							statostep=1;
							stepnew(1,0);
							if(app!=0){
								piunotti=1;
							}else{
								piunotti=0;
							}
						}
						myApp.initPageSwiper('#tabmain4');
			 }
		 });	
	};

var IDtabac='';
var reloadcal=0;
$$(document).on('page:back', function (e) {
   setTimeout(function(){ 
   
   		switch(mainView.activePage.name){
			case 'centrobenesseregiorno':
				if(reloadnav==1){
					var func=$$('#funccentro').val();
					eval(func);
				}
			break;
			case 'centrobenessere':
				/*if(reloadnav==1){
					var func=$$('#funccentro2').val();
					eval(func);
					reloadnav=0;
				}*/
			break;
			case 'ristorante':
				if(reloadnav==1){
					var func=$$('#funccentro4').val();
					//alert(func);
					eval(func);
					reloadnav=0;
				}
			break;
			case 'ristorantegiorno':
				if(reloadnav==1){
					var func=$$('#funccentro3').val();
			 		eval(func);
				}
			break;
			case 'dettavolo':
				//var IDtab=$$('.detta .active').attr('id');
				if(reloadnav==1){
					var func=$$('#funccentro5').val();
					//alert(func);
					eval(func);
				}
			break;
			case 'calendario':
			
				if(reloadcal==1){
					var time=$$('#datacal').val();
					//navigation(2,time,0,1);
					//alert(time);
					navigationtxt(3,time,'calendariodiv',0);
					reloadcal=0;
				}
			break;
			
   		}	   
  	}, 500);


});



myApp.onPageInit('calendario', function (page) {	
	//var p=0;
	
							$$('.nosoggcal').on('click', function () {
								var time=$$(this).attr('alt');
								if(!isNaN(time)){
									var time=parseInt($$('#datacal').val())+parseInt(((time-1)*86400));
									opennosogg(time);
								}
							});
							$$('.ppp').on('click', function () {
								var ID=$$(this).attr('label');
								navigation(3,ID);
								openp=ID;
							});
							
							$$('.new').on('click', function () {
								if(openp==0){
									var id=$$(this).attr('id');
									var arr=id.split('_');
									var time=parseInt($$('#datacal').val())+parseInt(((arr['0']-1)*86400));
									var app=arr['1'];
									addprenot(time,app);
								}
							});
							$$('.noteesc').on('click', function () {
								var time=$$(this).attr('alt');
								if(!isNaN(time)){
									var time=parseInt($$('#datacal').val())+parseInt(((time-1)*86400));
									openesclu(time);
								}
							});
							
							$$('.annullata').on('click', function () {
								var time=$$(this).attr('alt');
								if(!isNaN(time)){
									var time=parseInt($$('#datacal').val())+parseInt(((time-1)*86400));
									openann(time);
								}
							});
							
							if ($$(".ogg").html() != undefined) {
								var offset = $$("#tabcalmain").offset().left;
								var offset2 = $$(".ogg").offset().left;
								var left=offset2-offset-50;
								document.getElementById('tabcalmain').scrollLeft=left;
							}
						
							
							
		
	var ps=0;	
	var container2 = $$('.page-content');
	$$(container2).scroll(function() {
		//var offset = ;

		var off=parseInt($$("#tabappart").offset().top)+parseInt(55);
		if(off<105){
			if(ps==0){
				ps=1;
			}
		}else{
			if(ps==1){
				ps=0;
			}
		}
		
		if(off<104){
				 //var offset2=;
				var lef=$$('.table-fixed-right').offset().left*-1+parseInt(172);
				//alert(lef);
				scrollcal=lef*-1;
				 var off2=$$('.table-fixed-right').offset().top;
				 
				 //$('#valore').html(off2);
				 //document.getElementById('valore').innerHTML=off2;

				 if(off2<0){
					 var off2=(off2*-1);
					 //off2=parseInt(off2)+parseInt(2);
				 }else{
					 off2=parseInt(2)-off2;
				}
				
				off2=parseInt(off2)+parseInt(50);
				

				//$$('#tabdate').css('top',off2+'px');
				
				if(p!=1){ 
					$$('#tabdate').css('position','fixed');
					//$$('#tabdate').css('z-index','99999');
					$$('#tabdate').css('top','50px');
					$$('#tabdate').css('left',lef+'px');
					$$('#tabbody').css('margin-top','49px');
					p=1;
				}
				
		}else{
			if(p==1){
				$$('#tabdate').css('position','absolute');
				$$('#tabdate').css('top','auto');
				$$('#tabdate').css('margin-top','0px');
				$$('#tabdate').css('left','0px');
				
				p=0;
			}
		}
	});
	
});
	


var myCalendar='';
var reloadnav=0;
var reloadnavadd=0;

var myPhoto=new Array();

function navigation(id,str,agg,rel){
	var url=baseurl+"mobile/";
	id=parseInt(id);
	

	//var apriurl=new Array('profilo/temp.php','calendario.inc.php','detpren2.php','calendario2.inc.php','preventivo/step1.php','preventivo/step0.php','preventivo/step2.php','preventivo/step3.php','preventivo/step4.php','preventivo/step5.php','notifiche.inc.php','promemoria.php','appunti.inc.php','centrobenessere.inc.php','ristorante.inc.php','pulizie.inc.php','domotica.inc.php','arrivi.inc.php','clienti.inc.php','prenotazioni.inc.php','ristorantegiorno.inc.php','centrobenesseregiorno.inc.php','preventivo/step4cerca.php','profilo/servizi.php','profilo/prenotazione.php','profilo/temperatura.php','profilo/menuristorante.php','/profilo/ilconto.php','profilo/elencoservizi.php','profilo/elencoluoghi.php','ricercaclidet.php','ricercaserv.php','centrobenesseregiorno.inc.php');
	
	
	var apriurl=new Array('config/profilo.php','config/profilocli.php','config/calendario.inc.php','config/detpren.php','config/centrobenessere.php','config/ristorante.php','config/pulizie.php','config/arrivi.php','config/prenotazioni.php','config/clienti.php','config/domotica.php','config/notifiche.php','config/appunti.php','config/ristorantegiorno.php','config/centrobenesseregiorno.php','config/dettavolo.php','config/profilo/servizi.php','config/profilo/temperatura.php','config/profilo/menuristorante.php','config/profilo/elencoservizi.php','config/profilo/ilconto.php','config/profilo/elencoluoghi.php','config/explodeservice.php','config/puliziedet.php','config/clientidet.php','config/profilo/detserv.php','config/profilo/detservizio.php','config/profilo/addserv.php','config/profilo/suggerimenti.php','config/profilo/galleria.php','config/profilo/recensioni.php','config/profilo/detrecensione.php','config/profilo/nuovarecensione.php');
	//last 32
	var url=url+apriurl[id];
	//alert(url);
	if(IDcode=='undefined'){
		onloadf(1);
	}
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
	myApp.showIndicator();setTimeout(function(){ hidelo(); }, 5500);	
	$$.ajax({
            url: url,
                method: 'GET',
				dataType: 'text',
				cache:false,
                data: query,
                success: function (data) {
					myApp.hideIndicator();
					//alert(data);
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
							mainView.router.load({
							  content: data,
							   animatePages: true
							});
						break;
					}
					
					//alert(agg);
					//mainView.router.loadContent({content:data,force:true});
					switch(agg){
						case 1:
							//
						
						break;
						case 2:

						
						break;
						case 3:
						
							
						break;
						case 4://agg button giorni
							//alert(query['dato0']);
							var left = $$('#'+query['dato0']).offset().left;
							left=left-150;
							document.getElementById('infinitemain').scrollLeft=left;
						break;
						case 5:
							 myCalendar = myApp.calendar({
								input: '#dataarrivi',
								weekHeader: true,
								dateFormat: 'dd/mm/yyyy',
								header: false,
								footer: false,
								rangePicker: true,
								onChange:function (p, values, displayValues){
									var string=new String(values);
									var vett=string.split(',');
									var t1=vett['0']/1000;
									if(string.length>20){
										var t2=vett['1']/1000;
										var diff=((t2-t1)/86400);
										var send=t1+','+diff;
										navigationtxt(17,send,'arrividiv',8)
										myCalendar.close()
									}
								}
							});
						break;
						case 6:
							var sosp=$$('#sospesi').val();
							$$('#badgecentro').html(sosp);
							if(IDtabac2.length>0){
								myApp.showTab('#'+IDtabac2);
							}
						break;
						case 7:
							menuprofilo();
						break;
						case 8:
							if(IDtabac.length>0){
								myApp.showTab('#'+IDtabac);
							}
						break;
						case 9:
							var evals=$$('#evals').val();
							eval(evals);
						break;
					}
					myApp.closeModal();
         }
     });
}



function aventistep1(){
	if(okstep1==1){
		stepnew(1,0);
	}else{
		myApp.addNotification({
			message: "E' necessario inserire una data. Prego riprovare",
			hold:1200
		});
	}
}

function avantistep2(){
	if ($$('input[name=pacchetto]:checked').length > 0) {
		statostep=3;stepnew(1,0);
	}else{
		myApp.addNotification({
			message: "E' necessario selezionare una soluzione. Prego riprovare",
			hold:1200
		});
	}
}



function avanti2(dato0){
	switch(statostep){
		case 0:
			stepnew(1,dato0);
		break;
		case 1:
			aventistep1();
		break;
		case 2:
			if(calcolodispo==0){
				dispo1();
			}else{
				stepnew(1,0);
			}	
		break;
		case 3:
			//controllo
			
			var mioArray=document.getElementsByClassName('tablist selected');
			var lun=mioArray.length;
			
			if(lun>0){
				stepnew(1,0);
			}else{
				//notification
				
				myApp.addNotification({
							message: "Obbligatorio selezionare un'opzione",
							hold:1200
						});
				
			}
		break;
		case 4:
			stepnew(1,0);
		break;
		case 5:
			stepnew(1,0);
		break;	
		case 6:
			confermapren();
		break;
	}
	
	
	
}




var statostep=0;

function stepnew(step,str){
	$$('.popup').scrollTo(0,0);
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
	$$('#buttonadd').css('visibility','hidden');
	var step=parseInt($$('#step'+statostep).attr('alt'));
	$$('.tabpren').attr('disabled','disabled');
	for(i=0;i<=statostep;i++){
		$$('#buttstep'+i).removeAttr('disabled');
	}
	$$('.avanti').html('Avanti');
	
	
	if(step==2){
		if(indipren==1){
			$$('#indietrobutt').css('display','none');
		}else{
			$$('#indietrobutt').css('display','block');
		}
	}else{
		$$('.avanti').css('display','block');
		$$('#indietrobutt').css('display','block');
	}
	if(step==0){
		$$('.avanti').css('display','none');
		$$('#indietrobutt').css('display','none');
	}else{
		$$('.avanti').css('display','block');
	}
	
	
	if(show!=0){myApp.showTab('#step'+statostep);}
	
	switch(step){
		case 0:
			indipren=0;
			
		break;
		case 1:
			indipren=0;
			if(agg==1){navigationtxt(4,str,'step'+statostep,2);}
			//if(show!=0){myApp.showTab('#step'+statostep);}
			$$('#titolodivmain').html('Check In - Check Out');
			
		break;
		case 2:
		
			
			
			
			if(agg==1){
				navigationtxt(4,"0,2",'step'+statostep,11);
			}
			$$('#titolodivmain').html('Richiesta');
			
			if(show!=0){myApp.showTab('#step'+statostep);}
		break;
		case 3:
			if((agg==1)&&(calcolodispo==0)){
				//alert(statostep);
				navigationtxt(6,1,'step'+statostep,0);
				//calcolatot();
			}
			$$('#titolodivmain').html('Trattamento');
			
			//if(show!=0){myApp.showTab('#step'+statostep);}
		break;
		case 4:
		
			$$('#buttonadd').css('visibility','visible');
			if((agg==1)&&(calextra==0)){
				navigationtxt(7,str,'step'+statostep,0);
				calcolatot();
			}else{
				calextra=0;
			}
			$$('#titolodivmain').html('Elenco Servizi');
			//if(show!=0){myApp.showTab('#step'+statostep);}
		break;
		case 5:
			if(agg==1){navigationtxt(8,str,'step'+statostep,0);}
			//if(show!=0){myApp.showTab('#step'+statostep);}
			$$('#titolodivmain').html('Dati Cliente');
		break;
		case 6:
			$$('.avanti').html('<i class="f7-icons " style="color:#fff; font-size:30px; margin-top:2px; ">check</i>');
			$$('#titolodivmain').html('Conferma Prenotazione');
			$$('.avanti').html('Conferma');
			if(agg==1){navigationtxt(9,str,'step'+statostep,0);}
			//if(show!=0){myApp.showTab('#step'+statostep);}
		break;
	}
	
	if(statostep==0){
		$$('.divmainmenu').css('visibility','hidden');
	}else{
		$$('.divmainmenu').css('visibility','visible');
		
	}
	
}

var okstep1=0;
var openp=0;

function navigationtxt(id,str,campo,agg,loader){
	if(IDcode=='undefined'){
		onloadf(1);
	}
	//reloadnav=0;
	//alert(id);
	var url=baseurl+"mobile/config/";
	var apriurl=new Array('profilo/temp.php','calendario.inc.php','detpren2.php','calendario2.inc.php','preventivo/step1.php','preventivo/step0.php','preventivo/step2.php','preventivo/step3.php','preventivo/step4.php','preventivo/step5.php','notifiche.inc.php','promemoria.php','appunti.inc.php','centrobenessere.inc.php','ristorante.inc.php','pulizie.inc.php','domotica.inc.php','arrivi.inc.php','clienti.inc.php','prenotazioni.inc.php','ristorantegiorno.inc.php','centrobenesseregiorno.inc.php','preventivo/step4cerca.php','profilo/servizi.php','profilo/prenotazione.php','profilo/temperatura.php','profilo/menuristorante.php','/profilo/ilconto.php','profilo/elencoservizi.php','profilo/elencoluoghi.php','ricercaclidet.php','ricercaserv.php','centrobenesseregiorno.inc.php');
	var url=url+apriurl[id];
	
	//alert('TXT'+id);
	if(loader!=0){
		myApp.showIndicator();
		setTimeout(function(){ hidelo(); }, 5500);	
	}
	query=new Array();
	query['IDcode']=IDcode;
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
	$$.ajax({
            url: url,
                method: 'GET',
				dataType: 'text',
				cache:false,
                data: query,
                success: function (data) {
					//alert(data);
					if(loader!=0){
						clearTimeout();
						myApp.hideIndicator();
					}
					$$('#'+campo).html(data);
					//alert(id);
					if(id==3){
						
							//alert(scrollcal);
							
							if(scrollcal!=0){
								document.getElementById('tabcalmain').scrollLeft=parseInt(scrollcal*-1)+parseInt(85);
							}else{
								if ($$(".ogg").html() != undefined) {
									var offset = $$(".ogg").offset();
									var left=(parseInt(offset.left)-parseInt(100));
									document.getElementById('tabcalmain').scrollLeft=left;
								}
							}
							
							$$('.nosoggcal').on('click', function () {
								var time=$$(this).attr('alt');
								if(!isNaN(time)){
									var time=parseInt($$('#datacal').val())+parseInt(((time-1)*86400));
									opennosogg(time);
								}
							});
							
							$$('.ppp').on('click', function () {
								var ID=$$(this).attr('label');
								navigation(3,ID);
								openp=ID;
							});
						
							
							$$('.new').on('click', function () {
								if(openp==0){
									var id=$$(this).attr('id');
									var arr=id.split('_');
									var time=parseInt($$('#datacal').val())+parseInt(((arr['0']-1)*86400));
									
									var app=arr['1'];
									addprenot(time,app);
								}
							});
							$$('.noteesc').on('click', function () {
								var time=$$(this).attr('alt');
								if(!isNaN(time)){
									var time=parseInt($$('#datacal').val())+parseInt(((time-1)*86400));
									openesclu(time);
								}
							});
							
							$$('.annullata').on('click', function () {
								var time=$$(this).attr('alt');
								if(!isNaN(time)){
									var time=parseInt($$('#datacal').val())+parseInt(((time-1)*86400));
									openann(time);
								}
							});
					}
					switch(agg){
						case 1:
							//$$('.tmenupren').removeClass('active');
							//$$('#tabm'+query['dato1']).addClass('active');
							//myApp.closeModal('.popover-menu');
							// myApp.materialTabbarSetHighlight('.subnavbar');
							/*switch(query['dato1']){
								case "0":
								 	myApp.initPageSwiper('#tabmain');
								break
								case "1":
									myApp.initPageSwiper('#tabmain2');
								break;
							}*/
							
							
							
							$$('.tl').removeClass('active');
							$$('#m'+query['dato1']).addClass('active');
							
							
						break;
						case 2:
						
							var range=true;
							piunotti=1;
							if(query['dato0']==0){
								piunotti=0;
								range=false;
							}
						
						
							 var calendarInline = myApp.calendar({
								container: '#ks-calendar-inline-container',
								input: '#data',
								weekHeader: true,
								dateFormat: 'yyyy-mm-dd',
								rangePicker: range,
								header: false,
								footer: false,
								onChange:function (p, values, displayValues){
									var str=new String(values);
									var vettore=str.split(',');
									//var data=document.getElementById('data').value;
									statostep=1;
									okstep1=0;
									if((piunotti==1)&&(vettore.length==2)){
										//stepnew(1,0);
										okstep1=1;
									}
									
									if((piunotti==0)&&(vettore.length==1)){
										okstep1=1;
										//stepnew(1,0);
									}
									
									
								},
								
								toolbarTemplate:
									'<div class="toolbar calendar-custom-toolbar">' +
										'<div class="toolbar-inner">' +
											'<div class="left">' +
												'<a href="#" class="link icon-only"><i class="icon icon-back"></i></a>' +
											'</div>' +
											'<div class="center"></div>' +
											'<div class="right">' +
												'<a href="#" class="link icon-only"><i class="icon icon-forward"></i></a>' +
											'</div>' +
										'</div>' +
									'</div>',
								onOpen: function (p) {
									$$('.calendar-custom-toolbar .center').text(monthNames[p.currentMonth] +', ' + p.currentYear);
									$$('.calendar-custom-toolbar .left .link').on('click', function () {
										calendarInline.prevMonth();
									});
									$$('.calendar-custom-toolbar .right .link').on('click', function () {
										calendarInline.nextMonth();
									});
								},
								onMonthYearChangeStart: function (p) {
									$$('.calendar-custom-toolbar .center').text(monthNames[p.currentMonth] +', ' + p.currentYear);
								}
							});
						     
						break;
						case 3:
							//da specificare
							//alert(data);
							
						
						break;
						case 4:
							var offset = $$(".ogg").offset();
							var left=parseInt(offset.left)+parseInt(70);
							$$('#tabdate').css('left',lef+'px');
							//$$('#tabdate').animate({'left': left});
						break;
						case 5:
							myCalendar = myApp.calendar({
								input: '#datacentro',
								weekHeader: true,
								dateFormat: 'dd/mm/yyyy',
								header: false,
								footer: false,
								rangePicker: true,
								onChange:function (p, values, displayValues){
									var string=new String(values);
									var vett=string.split(',');
									var t1=vett['0']/1000;
									if(string.length>20){
										var t2=vett['1']/1000;
										var diff=((t2-t1)/86400);
										var send=t1+','+diff;
										navigationtxt(13,send,'centrobenesserediv',5)
										myCalendar.close()
									}
								}
							});
						break;
						case 6:
							
							$$('.buttdate').removeClass('selected');
							$$('#'+query['dato0']).addClass('selected');
							
						break;
						case 7:
						
							
						break;
						case 8:
							var myCalendar = myApp.calendar({
								input: '#dataarrivi',
								weekHeader: true,
								dateFormat: 'dd/mm/yyyy',
								header: false,
								footer: false,
								rangePicker: true,
								onChange:function (p, values, displayValues){
									var string=new String(values);
									var vett=string.split(',');
									var t1=vett['0']/1000;
									if(string.length>20){
										var t2=vett['1']/1000;
										var diff=((t2-t1)/86400);
										var send=t1+','+diff;
										navigationtxt(17,send,'arrividiv',8)
										myCalendar.close()
									}
								}
							});
						break;
						case 9:
							var sosp=$$('#sospesi').val();
							$$('#badgecentro').html(sosp);
						break;
						case 10:
							//myApp.initImagesLazyLoad('contenutodiv')
							
							var icon = {
								url: "https://www.scidoo.com/mobile/img/homepoi.svg", // url
								scaledSize: new google.maps.Size(30, 30)
							};
							
							
							var lat=parseFloat($$('#latstr').val());
							var lon=parseFloat($$('#lonstr').val());
							var nomestr=$$('#nomestr').val();
							
							
							 var struttura = {lat: lat, lng: lon};
							//alert(lat);
							var map = new google.maps.Map(document.getElementById('map'), {
center: struttura,
zoom: 10,mapTypeId: 'roadmap'
});

							
							
						

							 var infowindow = new google.maps.InfoWindow;
							infowindow.setContent(nomestr);
					
							var marker = new google.maps.Marker({
								map: map, 
								position: struttura,
								icon:icon});
							
							// infowindow.open(map, marker);
							marker.addListener('click', function() {
							  infowindow.open(map, marker);
							});
											
							//$$('img.lazy').trigger('lazy');
						break;
						case 11:
							
							
							
						break;
						case 12:
						
							myApp.accordionOpen('#IDinfop'+query['dato2'])
							//$$('#IDinfop'+query['dato2']).trigger('click');;
						break;
						case 13:
							var left=parseInt($$(".tempgiorno.selt").attr('alt'));
							var left=left*40;
							//var left=$$(".tempgiorno.selt").offset().left;
							
							document.getElementById('tempergiorno').scrollLeft=left;
							var left=parseInt($$(".tempnotte.selt").attr('alt'));
				
							var left=left*40;
							document.getElementById('tempernotte').scrollLeft=left;
						
							
						break;
					}
					
					document.getElementByClass('.page-content').scrollTop=0;
					
					
					
					
         }
     });	
}





function onloadf(time){
	
	//alert('aa');
	//alert();
	IDcode=window.localStorage.getItem("IDcode");
	
	var h = window.innerHeight;
	creasessione(h,86);
	
	if(IDcode.length>10){
		//alert(IDcode);
		//var url=baseurl+"mobile/";
		
		var url=baseurl+'mobile/config/controlloini.php';
	
		
		$$.ajax({
            url: url,
                  method: 'POST',
				dataType: 'text',
				cache:false,
                data: {IDcode:IDcode},
                success: function (data){
					//alert(data);
					var num=data.indexOf("error");
					if((num==-1)&&(!isNaN(data))){
						data=parseInt(data);
						//var arr=new Array();
						
						if(time==0){
							agg=0;
							if(data==1)agg=7;
							navigation(data,'',agg)
						}
					}
    				//myApp.hideIndicator();
					//mainView.router.loadContent(data);
       			}
    	 });
	}
	
	
}


function modprofilo(id,campo,tipo,val2,agg){
		myApp.showIndicator();setTimeout(function(){ hidelo(); }, 4500);	
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
		var url=url+'config/gestioneprofilo.php';
		var query = {val:val,tipo:tipo,ID:id,val2:val2};
		//alert(url);
		$$.ajax({
			url: url,
			method: 'POST',
			dataType: 'text',
			cache:false,
			data: query,
			success: function (data) {
				//alert(data);
				clearTimeout();
				if(agg==3){
					myApp.addNotification({
						message: 'Servizio prenotato con successo',
						hold:1200
					});
				}else{
					/*myApp.addNotification({
					message: 'Modifica effettuata con successo',
						hold:1200
					});*/
				}	
					
				myApp.hideIndicator();
				switch(agg) {
					case 1:
						navigationtxt(25,0,'contenutodiv',13);
					break;
					case 2:
						navigationtxt(24,0,'contenutodiv',0);
						
						if(tipo==7){
							myApp.addNotification({
							message: 'Ha appena ricevuto una copia della mail inviata alla struttura.',
								hold:2000
							});
						}
						
					break;
					case 3:
						mainView.router.back();
						//var IDsotto=$$('#IDsottotipsel').val();
						//navigationtxt(24,IDsotto,'contenutodiv',0);
					break;
					case 4:
						navigationtxt(23,0,'contenutodiv',0);
					break;
					case 5:
						navigationtxt(23,0,'contenutodiv',0);
						var func=$$('#funcreload').val();
						eval(func);
					break;
					case 6:
					
						 myApp.alert("Grazie del tempo da lei dedicato  alla recensione.<br/>Sara' utile alla nostro struttura ed ai nostri ospiti!", 'Grazie della Recensione', function () {
							mainView.router.back();
							setTimeout(function (){
								navigation(30,0,0,1);
								
							},500);
							
						});
					
					
						
						
						
						
						
					break;
					
					
				}
			}
	});
}

idprec=0;
function vis2(id,tipo,multi,num){
	//alert();
	
	
	if(idprec!=id){
		var campo='into'+tipo+'-'+id;
		$$('.modificas').html('--');
		var val=$$('#tr'+id).attr('lang');
		
		idprec=id;
		//alert(val);
		var url=baseurl;
		var url=url+'mobile/config/detserv2.php';
		
		var query = {ID:val,tipo:tipo,multi:multi};
		//alert(url);
		$$.ajax({
				url: url,
					  method: 'GET',
					dataType: 'text',
					cache:false,
					data: query,
					success: function (data) {
						//myApp.hideIndicator();
						document.getElementById(campo).innerHTML=data;
						
						var scriptinto=$$('#scriptinto').html();
						if(scriptinto!="undefined"){
							eval(scriptinto);
						}
						
						
						//mainView.router.loadContent(data);
					}
			 });
	}else{
		idprec=0;
	}
}


function modrestr(id,tipo,multi,num,popup){
	

		var val=$$('#tr'+id).attr('lang');
		
		idprec=id;
		//alert(val);
		var url=baseurl;
		var url=url+'mobile/config/modrestr.php';
		
		var query = {ID:val,tipo:tipo,multi:multi};
		//alert(url);
		$$.ajax({
				url: url,
					  method: 'GET',
					dataType: 'text',
					cache:false,
					data: query,
					success: function (data) {
						//myApp.hideIndicator();
						
						
						clearTimeout();
				
						if(popup==1){
							$$('#contpopup').html(data);
						}else{
							var popupHTML = '<div class="popup" id="contpopup" style="padding:0px;">'+data+'</div>';
							myApp.popup(popupHTML);
						}
				
						
						//mainView.router.loadContent(data);
					}
			 });
	
}

function backcentro(tipo){
	switch(tipo){
		case 1:
			var time=$$('#timecentro').val();
			//var gg=$$('#ggcentro').val();
			//var txt=time+','+gg;
			navigationtxt(13,time,'centrobenesserediv',0)
		break;
		case 2:
			var time=$$('#timeristo').val();
			//var gg=$$('#ggristo').val();
			//var txt=time+','+gg;
			navigationtxt(14,time,'ristorantediv',0)
		break;
		
	}
}


function riaggvis(txtsend){
	//alert(mainView.activePage.name);
	//controllo dove siamo
	//
	
	/*
	var time=$$('#timecentrogiorno').val();
	var sottotip=$$('#IDsottocentrogiorno').val();
	var txt=time+','+sottotip;
	
	
	navigation(14,txt,6,1);
	*/
	
	switch(mainView.activePage.name){
		case "detpren":
			var campo='into1-'+$$('#'+txtsend).val();
			//alert(campo);
			var url=baseurl;
				var url=url+'mobile/config/detserv2.php';
				var query = {ID:txtsend,tipo:1,multi:0};
				//alert(url);
				$$.ajax({
						url: url,
							  method: 'GET',
							dataType: 'text',
							cache:false,
							data: query,
							success: function (data) {
								document.getElementById(campo).innerHTML=data;
								var scriptinto=$$('#scriptinto').html();
								if(scriptinto!="undefined"){
									eval(scriptinto);
								}
							}
					 });
		break;
		case "centrobenesseregiorno":
			//alert('aa');
			var time=$$('#timecentrogiorno').val();
			var sottotip=$$('#IDsottocentrogiorno').val();
			var txt=time+','+sottotip;
			navigation(14,txt,6,1);
			
			//navigationtxt(21,txt,'centrobenesseregiornodiv',9);
			
		break;
		case "ristorantegiorno":
			var time=$$('#timeristogiorno').val();
			var send= time+','+$$('#IDsottoristogiorno').val();
			navigationtxt(20,send,'ristorantegiornodiv',0)
		break;
	}
}


function modprenextra(id,campo,tipo,val2,agg){
		myApp.showIndicator();
		setTimeout(function(){ hidelo(); }, 5500);	
		var plus="";
		switch(val2) {
			case 1:
				var val=$$('#'+campo).val();
				val=val.replace(',','.');
				if(isNaN(val)){
					myApp.alert("E' necessario inserire un numero. Prego Riprovare");
					return false;
				}
				break;
			case 6:
				var val=$$('#'+campo).val();
				val=val.replace(/\n/g, "<br/>"); //descrizione
				break;
			case 7:
				if(document.getElementById(campo).checked==true){ //si o no
					val='1';
				}else{
					val='0';
				}
				break;
			case 8:
				var classe=$$('#'+campo).attr('class');
				var jj=0;
				var kk=0;
				if(classe.indexOf(plus+"1")!=-1){ //1
					jj=1;
					kk=2;
				}
				if(classe.indexOf(plus+"2")!=-1){ //2
					jj=2;
					kk=0;
				}
				if(jj==0){
					if(classe.indexOf("pag")!=-1){kk=2;}else{kk=1;}
				}
				val=kk;	
				break;
			case 9:
				val=campo;
			break;
			case 10:
				var val=$$('#'+campo).val();
				var plus='modi';
				val2=8;
				break;
			case 11:
				var val=$$('#'+campo).val();
				var id2=val;
				var val=$$('#tr'+id).attr('lang');
				id=id2;
				break;
			default:
				var val=$$('#'+campo).val();
			break;
		}
		
		var url=baseurl;
		var url=url+'config/gestioneprenextra.php';
		reloadnav=1;
		
		var query = {val:val,tipo:tipo,ID:id,val2:val2};
		//alert(url);
		$$.ajax({
				url: url,
					  method: 'POST',
					dataType: 'text',
					cache:false,
					data: query,
					success: function (data) {
						
						//alert(data);
						clearTimeout();
						myApp.addNotification({
							message: 'Modifica effettuata con successo',
							hold:1700
						});
						
						switch(agg) {
							case 1:
								var arr=data.split('_');
								addservprev2(arr['0'],arr['1'],arr['2']);
							break;
							case 2: //modifica serv
								/*var ID=$$('#IDmodserv').val();
								var tipo=$$('#tipomod').val();
								var time=$$('#timemod').val();
								var riagg=$$('#riaggmod').val();
								modificaserv(ID,tipo,time,riagg);*/
								
								var arr=val.split('_');
								var sala=arr['0'];
								var time=arr['1'];
								var IDpers=arr['2'];
								$$('.tablist').removeClass('selected');
								$$('#'+time+sala+IDpers).addClass('selected');
								
								var funz=$$('#funzioneriagg').val();
								//alert(funz);
								eval(funz);
								
							break;
							case 3:
								//aggintoristo(id,0);
								//navigation(15,IDprenextra,8,1);
								
							break;
							case 4:

								switch(mainView.activePage.name){
									case 'explodeservice':
										var txtsend2=$$('#txtsend2').val();
										navigation(22,txtsend2,0,1);
									break;
									default:
										var IDpren=$$('#IDprenfunc').val();
										navigationtxt(2,IDpren+',1','contenutop',1);
									break;
								}
							
							break;
							case 5:
								mainView.router.back();
							break;
							case 6:
								navigation(15,id,8,1);
							break;
							
						}
						
						myApp.hideIndicator();
						
						
					}
			 });

}


function aggintoristo(IDprenextra,tipo){
	/*var url=baseurl;
	var url=url+'mobile/config/ristointo.php';
	var query = {IDprenextra:IDprenextra,tipo:tipo};
	$$.get(url,query, function(data){
		myApp.closeModal();
		$$('#into'+IDprenextra).html(data);
		//alert(data);
	});*/
	reloadnav=0;
	setTimeout(function(){
		
	 }, 300);	
	
	
	
}

function modprenot(id,campo,tipo,val2,agg){
		myApp.showIndicator();setTimeout(function(){ hidelo(); }, 4500);	
		switch(val2) {
			case 0:
				var val=$$('#'+campo).val();
				//val=encodeURIComponent(val);
				break;
			case 1:
				var val=$$('#'+campo).val();
				val=val.replace(',','.');
				if(isNaN(val)){
					//alertify.alert("E' necessario inserire un numero. Prego Riprovare");
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
		var url=url+'config/gestioneprenot.php';
		//alert(val);
		var query = {val:val,tipo:tipo,ID:id,val2:val2};
		//alert(url);
		$$.ajax({
			url: url,
			method: 'POST',
			dataType: 'text',
			cache:false,
			data: query,
			success: function (data) {
				//alert(data);
				if(agg==2){
					myApp.addNotification({
							message: 'Funzione eseguita con successo',
							hold:1200
						});
				}else{
					myApp.addNotification({
							message: 'Modifica effettuata con successo',
							hold:1200
						});
				}
				clearTimeout();
				myApp.hideIndicator();
				switch(agg) {
					case 1:
						var vis=$$('#visdom').val();
						navigationtxt(16,vis,'domoticadiv',0);
						myApp.closeModal();
					break;
					case 2:
						navigationtxt(12,0,'appuntidiv',0);
						//myApp.closeModal();
					break;
					case 3:
						var send= $$('#timeristo').val()+','+$$('#IDtipovis').val()+','+$$('#ggpulizie').val();
						navigationtxt(15,send,'puliziediv',7)				
					break;
					case 4:
						navigationtxt(22,'','contencli');
					break;
					case 5:
						var txt=$$('#IDprenfunc').val()+',2';
						navigationtxt(2,txt,'contenutop',1);
						
						var txt2=$$('#IDinfopdet').val();
						navigation(24,txt2,0,1);
						
					break;
					case 6:
						var txt=$$('#IDprenfunc').val()+',2,'+val;
						navigationtxt(2,txt,'contenutop',12);
						
						var txt2=$$('#IDinfopdet').val();
						navigation(24,txt2,0,1);
						
					break;
					case 7:
						switch(mainView.activePage.name){
							case 'explodeservice':
								var txtsend2=$$('#txtsend2').val();
								navigation(22,txtsend2,0,1);
							break;
							default:
								var IDpren=$$('#IDprenfunc').val();
								navigationtxt(2,IDpren+',1','contenutop',1);
							break;
						}
						
					break;
					case 8:
						var tot=$$('#totalevacanza').val();
						$$('#totaleprev').html(tot+' €');
					break;
					case 9:
						var txt=$$('#IDprenfunc').val()+',0';
						navigationtxt(2,txt,'contenutop',1);
					break;
					case 10:
						backexplode(3);
					break;
					
				}
			}
	});
}


function  opennot(ID){
	var notifica=$$('#notifica'+ID).html();
	// var popupHTML = '<div class="popup"><div class="navbar"><div class="navbar-inner"><div class="center titolonav">Notifica</div><div class="right close-popup" ><a href="#"  ><i class="icon f7-icons" >close</i></a></div></div></div><div class="content-block notificatxt">'+notifica+'</div></div>';
  //myApp.popup(popupHTML);
	myApp.alert(notifica);
	
}


function backexplode(tipo,dato0){
	mainView.router.back();
	setTimeout(function (){
		
		switch(tipo){
			case 1:
				var IDpren=$$('#IDprenfunc').val();
				navigationtxt(2,IDpren+',1','contenutop',1);
			break;
			case 2:
				var IDpren=$$('#IDprenfunc').val();
				navigationtxt(2,IDpren+',4','contenutop',1);
			break;
			case 3:
				navigationtxt(12,0,'appuntidiv',0);
			break;
			case 4: //centro benessere
				if(reloadnav==1){
					navigation(4,dato0,4,1)
					//navigationtxt(13,dato0,'centrobenesserediv',6);
				}
			break;
			case 5://ristorante
				if(reloadnav==1){
					navigation(5,dato0,4,1)
				}
			break;
			case 6: //ospiti
				var txt=$$('#IDprenfunc').val()+',2';
				navigationtxt(2,txt,'contenutop',1);
			break;
			case 7:
				navigation(16,0,0,1);
			break;
		}
		
		
		
	},300);
}


function ricercaclidet(nav,val,ID,campo){
	navigationtxt(30,val+','+ID,campo);
}


function accendidom(ID,acc){
	var giornic3='';
	var mioArray=document.getElementsByName('giornidom');
	var lun=mioArray.length; //individuo la lunghezza dell’array 
	for (n=0;n<lun;n++) { //scorro tutti i div del documento
		if(mioArray.item(n).checked==true){
			var gg=mioArray.item(n).getAttribute('value');
			giornic3=giornic3+gg+','	
		}
	}
	
	var val=acc+'_2_'+$$('#accendi').val()+'_'+$$('#spegni').val()+'_'+giornic3;
	modprenot(ID,val,63,10,1);
}

function accendidom2(ID,acc){
	var val=acc+'_1_'+$$('#oreatt').val();
	modprenot(ID,val,63,10,1);
}



var inter3=setInterval("notifiche()",300000);

function notifiche(){
	var app='';
	var not='';
	
	var url=baseurl;
	var url=url+'mobile/config/reloadnot.php';
	var query = {};
	$$.ajax({
			url: url,
			method: 'GET',
			dataType: 'text',
			cache:false,
			data: query,
			success: function (data) {
				//alert(data);
				//var data=new String(data);
				var arr=data.split(',');
				app=parseInt(arr['1']);
				not=parseInt(arr['0']);
				$$('#numnotifiche2').html(not);
				//$$('#numappunti2').html(app);
				//alert();
				
				
			}
	});
	
	
	
	//navigationtxt(11,0,'promemoria',0);
}



function openesclu(time){
	var url=baseurl;
	var url=url+'mobile/config/esclusivi.php';
	var query = {time:time};
	$$.ajax({
			url: url,
			method: 'GET',
			dataType: 'text',
			cache:false,
			data: query,
			success: function (data) {
				var popupHTML = '<div class="popup" id="contpopup" style="padding:0px;">'+data+'</div>';
				myApp.popup(popupHTML);
				
				//myApp.pickerModal(data);
			}
	});
}

function opennosogg(time){
	var url=baseurl;
	var url=url+'mobile/config/nosoggiorno.php';
	var query = {time:time};
	$$.ajax({
			url: url,
			method: 'GET',
			dataType: 'text',
			cache:false,
			data: query,
			success: function (data) {
				var popupHTML = '<div class="popup" id="contpopup" style="padding:0px;">'+data+'</div>';
				myApp.popup(popupHTML);
				//myApp.pickerModal(data);
			}
	});
}


function opensosp(){
	var IDsotto=$$('#IDsottocentrogiorno').val();;
	var time=$$('#timecentrogiorno').val();;
	//alert('aa');
	
	var url=baseurl;
	var url=url+'mobile/config/sospesi.php';
	var query = {IDsotto:IDsotto,time:time};
	$$.ajax({
			url: url,
			method: 'GET',
			dataType: 'text',
			cache:false,
			data: query,
			success: function (data) {
				//alert(data);
				mainView.router.load({
					content: data,
					  animatePages: true
				});
				
			}
	});
}


function openann(time){
	var url=baseurl;
	var url=url+'mobile/config/annullate.php';
	var query = {time:time};
	$$.ajax({
			url: url,
			method: 'GET',
			dataType: 'text',
			cache:false,
			data: query,
			success: function (data) {
				
				
				
				var popupHTML = '<div class="popup" id="contpopup" style="padding:0px;">'+data+'</div>';
				myApp.popup(popupHTML);
				//myApp.pickerModal(data);
			}
	});
}

function cambiadatamod(ID,tipo,time,riagg){
	var time=$$('#datamod').val();
	modificaserv(ID,tipo,time,riagg,1);
}

function modificaserv(ID,tipo,time,riagg,popup){
	myApp.showIndicator();setTimeout(function(){ hidelo(); }, 5500);	
	var url=baseurl;
	//alert(time);
	var url=url+'mobile/config/orarioserv.php';
	var query = {ID:ID,tipo:tipo,time:time,riagg:riagg};
	$$.ajax({
			url: url,
			method: 'GET',
			dataType: 'text',
			cache:false,
			data: query,
			success: function (data) {
				
				//myApp.closeModal('.popover-menu');
				
				if(popup==1){
					setTimeout(function (){
						mainView.router.load({
							  content: data,
							  reload:true
						});
					},300);
				}else{
					//pageprev=mainView.activePage.name;
					
					mainView.router.load({
						content: data,
						animatePages: true
					});
				}
				 myApp.hideIndicator();
				 	var ID=$$('#IDperssel').val();
					if(ID.length>0){
						 myApp.showTab('#'+ID);
					}
			}
	});
}


function aprimenu(IDpren,obj){
	//alert(IDpren);
	var data=$$('#menu'+IDpren).html();
	$$('#menupop').html(data);
	myApp.popover($$('.popover-menu'),obj);
}

function aprimod(ID,obj){
	var data=$$('#menu'+ID).html();
	data=atob(data);
	$$('#menupop').html(data);
	myApp.popover($$('.popover-menu'),obj);

}

var durata11='';
var tipolim11='';
var IDtipo11='';
var serviziriep='';
var extra11='';

function backselect(){
	$$('.tabindietro').css('display','none');
	$$('#buttadddiv').css('display','none');
}

function backselect2(){
	$$('.tabindietro').css('display','none');
	$$('#buttonaddprev').css('display','none');
}
function selectservice(ID,tipolim,IDtipo,durata,agg,time){
	
	var url=baseurl;
	var url=url+'mobile/config/step2add.php';
	extra11=ID;
	durata11=durata;
	tipolim11=tipolim;
	IDtipo11=IDtipo;
	$$('.tabindietro').css('display','block');
	
	arrservice=new Array();
	
	var query = {ID:ID,tipolim:tipolim,time:time};
	$$.ajax({
			url: url,
			method: 'GET',
			dataType: 'text',
			cache:false,
			data: query,
			success: function (data) {
				$$('#add2').html(data);
				document.getElementById('pannellodx').scrollTop=0;
				$$('#buttadddiv').css('display','block');
				//alert(serviziriep);
				if(isNaN(agg)){
					myApp.showTab('#add2');
				}
						
			}
	});	

}



function creasessione(valore,tipo){
	var url=baseurl;
	var url=url+'config/creasessione.php';
	
	var query = {valore:valore,tipo:tipo};
	$$.ajax({
			url: url,
			method: 'POST',
			dataType: 'text',
			cache:false,
			data: query,
			success: function (data) {
				switch (data) {
					case "successaddserv":
						 selectservice(extra11,tipolim11,IDtipo11,durata11,1);
					
					break;
				}
				
						
			}
	});	
	
}




function addservice2(agg,tipoadd){
	myApp.showIndicator();setTimeout(function(){ hidelo(); }, 5500);	
	var note="";
	var prezzo="";//da vedere
	var opzione = 0;
	var tipolim=tipolim11;		
	var IDtipo=IDtipo11;	
	var durata=durata11;
	var orario11='00:00';
	var personale11='';
	
	if(tipolim=='6'){
		serviziriep='';
		var mioArray=document.getElementsByClassName('cent');
		var lun=mioArray.length; //individuo la lunghezza dell’array 
		for (n=0;n<lun;n++) { //scorro tutti i div del documento
			var serv=mioArray.item(n).getAttribute('alt');
			var time=mioArray.item(n).getAttribute('dir');
			var qta=mioArray.item(n).innerHTML;
			serviziriep =serviziriep +serv+'_'+time+'_'+qta+'_0/////';		
		}	
	}
	nump=1;
	if((tipolim=='8')||(tipolim=='7')){
		var arr=serviziriep.split('/////');
		var num=arr.length-1;
		if(num>=limitp){
			nump=1;
		}else{
			nump=0;
		}
	}
	
	reloadnav=1;
	//alert(serviziriep);
	
if((serviziriep.length>0)&&(nump==1)){
	dataString='arrins='+serviziriep+"&orario=" + orario11  +"&note=" + note+"&IDtipo=" + IDtipo+"&personale=" + personale11 +"&prezzo=" + prezzo +"&durata=" + durata+"&tipolim=" + tipolim+"&tipoadd=" + tipoadd
	
	//alert(dataString);
	var url=baseurl;
	var url=url+'config/addservice2.php';
	
	$$.ajax({
			url: url,
			method: 'POST',
			dataType: 'text',
			cache:false,
			data: dataString,
			success: function (data) {
				clearTimeout();
				myApp.addNotification({
							message: 'Servizio inserito con successo',
							hold:1200
						});
						//alert(agg);
				switch(agg){
					case 1:
						var pagdetpren=$$('#pagdetpren').val();
						var ricarica=1;		
						if(pagdetpren==1){
							if((tipolim11==2)||(tipolim11==1)){
								ricarica=2;
							}
						}
						mainView.router.back();
						var IDpren=$$('#IDprenfunc').val();
						setTimeout(function (){
							if(ricarica==1){
								navigationtxt(2,IDpren+',1','contenutop',1);
							}else{
								navigationtxt(2,IDpren+',4','contenutop',1);
							}
						},300);
						
						
					break;
					case 2:
						var IDp=parseInt(data);
						modificaserv(IDp,1,0,2,1);
						setTimeout(function(){ riaggvis(0); }, 500);					
					break;
				}
				myApp.hideIndicator();
						
			}
	});	

}else{
	myApp.hideIndicator();
	myApp.addNotification({
		message: 'Devi selezionare almeno una persona o aggiungere un oggetto',
		hold:1200
	});
	
	
}
}

function hidelo(){
	myApp.hideIndicator();
}




var arrservice=new Array();

function selectbutt(obj){
	var key=$$(obj).attr('lang');
	var campo=$$(obj).attr('align');
	var prezzo=$$(obj).attr('alt');
	
	totale=0;
	var ele=new Array();
	
	
	var type=document.getElementsByName('soggetti').item(0).getAttribute('type');
	
	
	
	var mioArray=document.getElementsByName('soggetti');
	var lun=mioArray.length; //individuo la lunghezza dell’array 
	for (n=0;n<lun;n++) { //scorro tutti i div del documento
		if(mioArray.item(n).checked==true){
			var time=mioArray.item(n).getAttribute('align');
			var prezzo=mioArray.item(n).getAttribute('alt');
			totale=parseFloat(totale)+parseFloat(prezzo);
			if (typeof ele[time] == 'undefined') {
				ele[time]=1;
			}else{
				ele[time]=parseInt(ele[time])+parseInt(1);
			}			
		}
	}
	
	/*$$('.eletxt').html('0 Elementi selezionati');
	for (var key2 in ele) {
		var txt=ele[key2]+' Elementi selezionati';
		 $$('#ele'+key2).html(txt);	 
	}*/
	$$('#totaleadd').html(totale);
	
	if(type=='radio'){
		 serviziriep=key;
	}else{
		if (typeof arrservice[key] == 'undefined') {
		//	alert('add');
			arrservice[key]=key;
		}else{
			//alert('remove');
			delete arrservice[key];
		}
		serviziriep='';
		for (var key in arrservice) {
			 serviziriep =serviziriep +key+'/////';	 
		}
	}
	
	

	
}


function cercaservizio(val){
	var url=baseurl;
	var url=url+'mobile/config/cercaservizi.php';
	var query = {val:val};
	$$.ajax({
			url: url,
			method: 'GET',
			dataType: 'text',
			cache:false,
			data: query,
			success: function (data) {
				$$('#listaservizi').html(data);
				 myApp.hideIndicator();
			}
	});
}

function addservice(IDpren,popup){
	serviziriep='';
	myApp.showIndicator();setTimeout(function(){ hidelo(); }, 5500);	
	var url=baseurl;
	var url=url+'mobile/config/addserv.php';
	var query = {IDpren:IDpren};
	$$.ajax({
			url: url,
			method: 'GET',
			dataType: 'text',
			cache:false,
			data: query,
			success: function (data) {
				//alert(data);
				clearTimeout();
				
				if(popup==1){
					mainView.router.load({
						  content: data,
						  reload:true
					});
				}else{
					mainView.router.load({
						content: data,
						  animatePages: true
						});
				}
				
				/*
				if(popup==1){
					$$('#contpopup').html(data);
				}else{
					var popupHTML = '<div class="popup" id="contpopup" style="padding:0px;">'+data+'</div>';
					myApp.popup(popupHTML);
				}*/
				
				 myApp.hideIndicator();
				
						
			}
	});

}	


function selprenot(popup){
	
	switch(mainView.activePage.name){
		
		case "centrobenesseregiorno":
			var time=$$('#timecentrogiorno').val();
			var IDsotto=$$('#IDsottocentrogiorno').val();
		break;
		case "ristorantegiorno":
			var time=$$('#timeristogiorno').val();
			var IDsotto=$$('#IDsottoristogiorno').val();
		break;
	}
	
	myApp.showIndicator();setTimeout(function(){ hidelo(); }, 5500);	
	var url=baseurl;
	var url=url+'mobile/config/selprenot.php';
	var query = {time:time,IDsotto:IDsotto};
	$$.ajax({
			url: url,
			method: 'GET',
			dataType: 'text',
			cache:false,
			data: query,
			success: function (data) {
				//alert(data);
				
				if(popup==1){
					
					mainView.router.load({
						content: data,
						  reload: true
						});
					
					
				}else{
					
					mainView.router.load({
						content: data,
						  animatePages: true
						});
					
					
				}
				
				 myApp.hideIndicator();
				
						
			}
	});

}	

function cercaprenot(val){
	var url=baseurl;
	var url=url+'mobile/config/cercaprenot.php';
	var query = {val:val};
	$$.ajax({
			url: url,
			method: 'GET',
			dataType: 'text',
			cache:false,
			data: query,
			success: function (data) {
				$$('#listaprenot').html(data);
				 myApp.hideIndicator();
			}
	});
}



function setdom(IDdom,popup){
	
	myApp.showIndicator();setTimeout(function(){ hidelo(); }, 5500);	
	var url=baseurl;
	var url=url+'mobile/config/setdomotica.php';
	var query = {IDdom:IDdom};
	$$.ajax({
			url: url,
			method: 'GET',
			dataType: 'text',
			cache:false,
			data: query,
			success: function (data) {
				//alert(data);
				if(popup==1){
					$$('#contpopup').html(data);
				}else{
					var popupHTML = '<div class="popup" id="contpopup" style="padding:0px;">'+data+'</div>';
					myApp.popup(popupHTML);
				}
				
				 myApp.hideIndicator();
				
						
			}
	});

}	



function addprodotto(IDpren,popup){
	
	myApp.showIndicator();setTimeout(function(){ hidelo(); }, 5500);	
	var url=baseurl;
	var url=url+'mobile/config/addprodotto.php';
	var query = {IDpren:IDpren};
	$$.ajax({
			url: url,
			method: 'GET',
			dataType: 'text',
			cache:false,
			data: query,
			success: function (data) {
				//alert(data);
				clearTimeout();
				
				if(popup==1){
					//$$('#contpopup').html(data);
					mainView.router.load({
					 	content: data,
						reload: true
					});
					
				}else{
					
					mainView.router.load({
					 	content: data,
						animatePages: true
					});
					
				}
				 myApp.hideIndicator();						
			}
	});

}	

function addprod(ID,add,prezzo){
	
	var num=$$('#p'+ID).html();
	var nprod=$$('#nprod').html();
	var euro=$$('#euro').html();
	
	if(add==0){
		num=num-1;
		nprod=nprod-1;
		euro=euro-prezzo;
	}else{
		num=parseInt(num)+parseInt(1);
		nprod=parseInt(nprod)+parseInt(1);
		euro=parseInt(euro)+parseInt(prezzo);
	}
	$$('#p'+ID).html(num);
	$$('#nprod').html(nprod);
	$$('#euro').html(euro);
	
	if(num==0){
		$$('#p'+ID).removeClass('selected');
	}else{
		$$('#p'+ID).addClass('selected');
	}
}




function addprod2(IDprenextra){
	
	servizi='';
	var mioArray=document.getElementsByClassName('roundb selected');
	var lun=mioArray.length; //individuo la lunghezza dell’array 
	for (n=0;n<lun;n++) { //scorro tutti i div del documento
		var ID=mioArray.item(n).getAttribute('alt');
		var qta=mioArray.item(n).innerHTML;
		servizi =servizi +ID+'_'+qta+'/////';		
	}
	modprenextra(IDprenextra,servizi,29,9,5);	
}



function msgboxelimina(id,tipo,altro,id2,url){
	var cosa;
	var agg="";
	myApp.closeModal('.popover-menu');
	var arrtipiel=new Array("","la prenotazione","la scheda numero "+id,"il servizio","l'album","la foto","il parametro","l'orario","8","9","la mansione","il soggetto dal personale","il messaggio Newsletter","la fascia oraria","il cliente dalla prenotazione","la nota","la Fattura/Ricevuta","il prodotto dalla Fattura/Ricevuta","l'acconto selezionato","il Fornitore","la Vendita","il pagamento","l'Agenzia","la Ricevuta/Fattura","i servizi selezionati","l'abbuono","la limitazione? Tutti le agenzie con la stessa limitazione subiranno lo stessa elimazione. Continuare","il cofanetto regalo","il voucher","il servizio","il documento","la spedizione","il servizio","il prodotto dal tavolo");
	
	
		myApp.confirm('Vuoi davvero eliminare '+ arrtipiel[tipo] +'?<br><br>'+agg, function () {
			
			
			switch(tipo) {
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
					elimina(id,tipo,altro,id2,url);
				break;
				case 3:
					id=$$('#tr'+id).attr('lang');
					elimina(id,tipo,altro,id2,url);
				break;
				case 32:	
					modprofilo(id,0,5,10,4);
				break;
				case 33:
					modprenextra(altro,id,30,9,6);
				break;
				
			
			
			}
			
		});
	
	
	
	
}



function elimina(id,tipo,altro,agg,url){

	myApp.showIndicator();setTimeout(function(){ hidelo(); }, 5500);	
	var url=baseurl;
	var url=url+'config/elimina.php';
	var query = {ID:id,tipo:tipo,altro:altro};
	
	$$.ajax({
			url: url,
			method: 'POST',
			dataType: 'text',
			cache:false,
			data: query,
			success: function (data) {
				
				myApp.addNotification({
					message: 'Rimozione effettuata con successo',
					hold:1000
				});
				
				myApp.hideIndicator();
				switch(agg){
					case 1:
						
						if(isNaN(altro)){
							var ff='riaggvis("'+altro+'")';
							eval(ff);
						}else{
							switch(mainView.activePage.name){
								case 'explodeservice':
									var txtsend2=$$('#txtsend2').val();
									navigation(22,txtsend2,0,1);
								break;
								default:
									var IDpren=$$('#IDprenfunc').val();
									navigationtxt(2,IDpren+',1','contenutop',1);
								break;
							}
						}
					break;
					case 2:
						var time=$$('#IDprentime').val();
						mainView.router.back();
						navigationtxt(3,time,'calendariodiv',0);
						var sea=$$('#searchp').val();
						navigationtxt(19,sea,'prenotazionidiv',0);
					break;
				}
						
			}
	});
}


function detcli(ID){
	//alert(ID);
	myApp.showIndicator();setTimeout(function(){ hidelo(); }, 5500);	
	var url=baseurl;
	var url=url+'mobile/config/detcli.php';
	var query = {ID:ID};
	$$.ajax({
			url: url,
			method: 'GET',
			dataType: 'text',
			cache:false,
			data: query,
			success: function (data) {
				myApp.closeModal('.popover-menu');
				$$('#pannellodx').html(data);
				myApp.openPanel('right');
				myApp.hideIndicator();		
			}
	});
}

function detappunto(ID){
	//alert(ID);
	myApp.showIndicator();setTimeout(function(){ hidelo(); }, 5500);	
	var url=baseurl;
	var url=url+'mobile/config/detappunto.php';
	var query = {ID:ID};
	$$.ajax({
			url: url,
			method: 'GET',
			dataType: 'text',
			cache:false,
			data: query,
			success: function (data) {
				
				
				mainView.router.load({
							  content: data,
							   animatePages: true
							});
				/*
				var popupHTML = '<div class="popup" style="padding:0px;">'+data+'</div>';
				myApp.popup(popupHTML);*/
				
				
				myApp.hideIndicator();		
			}
	});
}

function salvaappunto(){
			var appunto='';
			var note=$$('#noteappunto').val();
			
			//var dests=$('#dests').val();
			
			var dests='';
			/*
			var mioArray=document.getElementsByName('dests');
			var lun=mioArray.length; //individuo la lunghezza dell’array 
			for (n=0;n<lun;n++) { //scorro tutti i div del documento
				if(mioArray.item(n).checked==true){
					var val=mioArray.item(n).value;
					dests=dests+val+',';		
				}
			}*/
			
			var dests=$$('#destinatari').val()+',';
			
			var argrec=$$('#argrec').val();
			var argnew=$$('#argnew').val();
			arg='';
			if((argrec!='undefined')&&(argrec!='0')&&(argrec!='')){
				arg=argrec;
			}else{
				arg=argnew;
			}
			
			var val=appunto+'////'+note+'////'+dests+'////'+arg;
			if((note.length>0)&&(dests.length>0)&&(arg.length>0)){
				modprenot(0,val,141,10,10);
				
			}else{
				myApp.addNotification({
					message: "Devi inserire almeno un destinatario,un appunto ed un argomento.",
					hold:1200
				});
				
				
			}
			
			
}

function esci(){
	
	var url=baseurl;
	var url=url+'config/logout.php';
	var query = {ID:'0'};
	$$.ajax({
			url: url,
			method: 'GET',
			dataType: 'text',
			cache:false,
			data: query,
			success: function (data) {
				mainView.router.back();
				window.localStorage.setItem("IDcode", '0');
				/*
				var calendarDefault = myApp.calendar({
					input: '#kscal',
					dateFormat: 'dd/mm/yyyy'
				});
				*/
				//window.localStorage.getItem("IDcode")='0';
				
			}
	});
}



//profilo



function menuprofilo(){
	
	var h = window.innerHeight;
	myApp.showIndicator();setTimeout(function(){ hidelo(); }, 5500);	
	var url=baseurl;
	var url=url+'mobile/config/profilo/menu.php';
	var query = {h:h};
	$$.ajax({
			url: url,
			method: 'GET',
			dataType: 'text',
			cache:false,
			data: query,
			success: function (data) {
					
				$$('#pannellosx').html(data);
				myApp.hideIndicator();
			}
	});
}
function aprimenuprofilo(){
	myApp.openPanel('left');
}

function openmenuoggi(ID){
	var data='<div class="picker-modal"><div class="toolbar"><div class="toolbar-inner"><div class="left" style="margin-left:20px;">Menu</div><div class="right" style="padding-right:15px;"><a href="#" class="close-picker" onclick="myApp.closeModal();" style="width:50px;"><i class="icon f7-icons" style="color:#fff;  font-size:30px; ">close</i></a></div></div></div><div class="picker-modal-inner" style="height:100%; overflow-y:scroll;"><div class="content-block" style="padding:0px;">'+$$('#menu'+ID).html()+'</div><br></div>';
	
	myApp.pickerModal(data);
}

function titolomenu(titolo){
	//document.getElementsByClassName('page').scrollTop=0;
	myApp.accordionClose(".accordion-item");
	$$('#titolopage').html($$(titolo).attr('alt'));
	myApp.closePanel('left');
	
}


function setlocation(lat2,lon2,str){
	
	//myApp.showIndicator();setTimeout(function(){ hidelo(); }, 5500);	
	var lat=parseFloat($$('#latstr').val());
	var lon=parseFloat($$('#lonstr').val());
	var nomestr=$$('#nomestr').val();
	var arr=lat+','+lon+'//'+lat2+','+lon2;
	var struttura = {lat: lat2, lng: lon2};
				
	var map = new google.maps.Map(document.getElementById('map'), {
center: struttura,
zoom: 10, mapTypeId: 'roadmap'
});


								var arr=arr.split('//');
							
								var icon = {
								url: "https://www.scidoo.com/mobile/img/homepoi.svg", // url
								scaledSize: new google.maps.Size(30, 30)
							};
							
							
							for (var i = 0; i < arr.length; ++i) {
								
									var arr2=arr[i].split(',');
									
									if(i==0){
									
										  var marker = new google.maps.Marker({
											position: {
											  lat: parseFloat(arr2['0']),
											  lng: parseFloat(arr2['1'])
											},
											map: map,
											//label:'Struttura',
											icon: icon
										  });
										  var infowindow = new google.maps.InfoWindow({
											  content: nomestr,
											});
									}else{
									 	var marker = new google.maps.Marker({
											position: {
											  lat: parseFloat(arr2['0']),
											  lng: parseFloat(arr2['1'])
											},
											map: map,
										  });
										   var infowindow = new google.maps.InfoWindow({
											  content: str,
											});
									}
								  
								  
									
									//infowindow.open(marker.get('map'), marker);
									marker.addListener('click', function() {
									  infowindow.open(marker.get('map'), marker);
									});
															  
								  
								}
							
	
}



function  infopoi(ID){
	var url=baseurl;
	var url=url+'mobile/config/detluogo.php';
	var query = {ID:ID};
	$$.ajax({
			url: url,
			method: 'GET',
			dataType: 'text',
			cache:false,
			data: query,
			success: function (data) {
				clearTimeout();
				myApp.hideIndicator();
				
				var popupHTML = '<div class="popup" style="padding:0px;">'+data+'</div>';
				myApp.popup(popupHTML);
				
				
			}
	});
}

function controllocarta(){
	myApp.showIndicator();setTimeout(function(){ hidelo(); }, 5500);	
	var number=$$('#ncarta').val();
	var annos=$$('#annos').val();
	var meses=$$('#meses').val();
	var intes=$$('#intes').val();
	if((number.length>1)&&(intes.length>4)){		
		var url=baseurl;
		var url=url+'config/preventivoonline/config/controllocarta.php';
		$$.ajax({
				url: url,
				method: 'POST',
				dataType: 'text',
				cache:false,
				data: {number:number,meses:meses,annos:annos},
				success: function (data) {
					clearTimeout();
					myApp.hideIndicator();
					var num=data.indexOf("error");
					if(num==-1){
						var txt=number+'_'+annos+'_'+meses+'_'+intes;
						var IDpren=$$('#IDprenfunc').val();
						modprofilo(IDpren,txt,3,10,2);
					}
				}
		});
	}	
}


function prenotaora(IDserv,time,popup){
	myApp.showIndicator();setTimeout(function(){ hidelo(); }, 5500);	
	
	navigation(27,IDserv+','+time,0,1);
	/*
	var url=baseurl;
	var url=url+'mobile/config/profilo/addserv.php';
	query={IDserv:IDserv,time:time};			
		myApp.showIndicator();setTimeout(function(){ hidelo(); }, 5500);	
		$$.ajax({
				url: url,
					method: 'GET',
					dataType: 'text',
					cache:false,
					data: query,
					success: function (data) {
						clearTimeout();
						myApp.hideIndicator();
						///mainView.router.loadContent(data);
						if(popup==1){
							$$('#contentprenot').html(data);
						}else{
							var popupHTML = '<div class="popup" id="contentprenot" style="padding:0px;">'+data+'</div>';
							myApp.popup(popupHTML);
						}
			 }
		 });	*/	
}

function prenotaora2(){

	var val1=$$('#orariadd').val();
	
	var val2='';
	$$('.soggetti').each(function(i, obj) {
    	if($$(obj).is(':checked')){
			val2=val2+$$(obj).val()+',';
		}
	});
	var val3=$$('#IDservadd').val();
	
	ok=1;
	if(val1=='undefined'){
		myApp.addNotification({
			message: "E' necessario selezionare l'orario del servizio.",
			hold:1200
		});
		ok=0;
	}	
	if((ok==1)&&(val2.length==0)){
		myApp.addNotification({
			message: "E' necessario selezionare almeno una persona",
			hold:1200
		});
		ok=0;
	}
	if(ok==1){
		var val=val1+'///'+val2+'///'+val3;
		modprofilo(0,val,4,10,3);	
	}	
}


function selorario(obj){
	$$('.buttore').removeClass('oresel');
	$$(obj).addClass('oresel');
}

function modificaorario(ID,tipo,time,popup){
	myApp.showIndicator();setTimeout(function(){ hidelo(); }, 5500);	
	navigation(25,ID+','+time,0,1);
}


function mipiace(ID,tipoobj,agg){
	
	//var val=ID+'_'+tipoobj;
	
	myApp.showIndicator();setTimeout(function(){ hidelo(); }, 5500);	
	var url=baseurl;
	var url=url+'config/gestioneprofilo.php';
	var query = {ID:ID,val:tipoobj,tipo:8,val2:0};
	$$.ajax({
			url: url,
			method: 'POST',
			dataType: 'text',
			cache:false,
			data: query,
			success: function (data) {
				//alert(data);

				myApp.hideIndicator();
				clearTimeout();
				
				switch(agg){
					case 1:
						var num=parseInt(data);
						if(num>1){
							$$('#numl'+ID).html(num);	
						}else{
							$$('#numl'+ID).html('');
						}
						var classe=$$('#icon'+ID).attr('class');					
						var num=classe.indexOf("mipiace");  	 //alert(data);
						if(num==-1){	
							$$('#icon'+ID).addClass('mipiace');
						}else{
							$$('#icon'+ID).removeClass('mipiace');
						}
					
					break;
					case 2:
						var num=parseInt(data);
						if(num>1){
							$$('#numl'+ID).html(num);	
						}else{
							$$('#numl'+ID).html('Mi Piace');
						}
						var classe=$$('#numl'+ID).attr('class');					
						var num=classe.indexOf("mipiace");  	 //alert(data);
						if(num==-1){	
							$$('#numl'+ID).addClass('mipiace');
						}else{
							$$('#numl'+ID).removeClass('mipiace');
						}
					
					break;
					
					
				}
				
						
			}
	});
}

function selristo (obj){
	$$('.buttsxristo').removeClass('selected');
	$$(obj).addClass('selected');
}


function onlynumb(obj,inte){
	if(inte==1){
		var numb=parseInt($(obj).val());
	}else{
		var numb=parseFloat($(obj).val());
	}
	if(isNaN(numb)){
		numb=0;
	}
	$(obj).val(numb);
}

function convertnumb(numb2,inte){
	if(inte==1){
		var numb=parseInt(numb2);
	}else{
		var numb=parseFloat(numb2);
	}
	if(isNaN(numb)){
		numb=0;
	}
	return numb;
}


function modservice(ID){
		var txtsend=$$('#tr'+ID).attr('lang');
		var modprezzo=parseInt($$('#tr'+ID).attr('alt'));
		var IDunique=$$('#tr'+ID).attr('title');
		var txtsend2=new String($$('#tr'+ID).attr('dir'));
		var multi=0;
		var buttons=new Array();
		
		var num=$$('#'+txtsend).attr('alt');
		
		
		if(num>1){
			buttons.push(
					{
					text: 'Apri Dettaglio',
					onClick: function () {
						navigation(22,txtsend2,0,0);
					}
				}); 	
		}
		
		
		//modprezzo=0;
		//alert(modprezzo);
		switch(modprezzo){
			
			case 1:
				 buttons.push(
					{
					text: 'Modifica Prezzo',
					onClick: function () {

						myApp.prompt('Inserisci prezzo:', function (value) {
							if(!isNaN(value)){
								value=convertnumb(value,0);
								//alert(IDunique);
								modprenot(IDunique,value,98,10,7)
							}else{
								myApp.alert('Devi inserire un numero. Prego riprovare');
							}
							
						});
					}
				}); 
			
			break;
			case 2:
				 buttons.push(
					{
					text: 'Modifica Prezzo',
					onClick: function () {
						myApp.prompt('Inserisci prezzo:', function (value) {
							if(!isNaN(value)){
								value=convertnumb(value,0);
								modprenextra(value,txtsend,18,9,4);
							}else{
								myApp.alert('Devi inserire un numero. Prego riprovare');
							}
						});
					}
				}); 
			break;
		}
		
		if(modprezzo>0){
			
			var del=$$('#'+txtsend).attr('lang');
			if(del==1){
			buttons.push(
					{
					text: 'Elimina',
					color:'red',
					onClick: function () {
						msgboxelimina(ID,3,0,1,1);
					}
				}); 
			}
		}
		 var buttons3 = [
			{
				text: 'Chiudi',
				color:'black'
			}
		];
		
		 var groups = [buttons, buttons3];
  	     myApp.actions(groups);
}

var relinf=0;
function infinitedx(){
	var wid=$$('#divintoinfinite').css('width');
	var wid2=(parseInt(wid)-500)*-1;
	var scroll2=$$('.buttdatesup').offset().left;
	//alert(scroll2+'/'+wid2);
	
	if((scroll2<=wid2)&&(relinf==0)){
		//alert(scroll2);
	
		//var wid=$$('#divintoinfinite').css('width');
		relinf=1;
		var url=baseurl;
		var url=url+'mobile/config/infinitedx.php';
		var query = {};
		$$.ajax({
				url: url,
				method: 'GET',
				dataType: 'text',
				cache:false,
				data: query,
				success: function (data) {
					//alert(data);
					//relinf=1;
					setTimeout(function (){
						relinf=0;	
					},300);
					wid=parseInt(wid)+parseInt(450);
					$$('#divintoinfinite').css('width',wid+'px');
					$$('#divintoinfinite').append(data);
					
				}
		});
	}
}



function detinfop(ID,tel,cell,email){
		
		var buttons=new Array();
	
			buttons.push(
					{
					text: 'Apri Dettaglio',
					onClick: function () {
						navigation(24,ID,0,0);
					}
				}); 	
		
		
		if(tel.length>2){
			buttons.push(
					{
					text: 'Chiama '+tel,
					onClick: function () {
						location.href="tel:"+tel;
					}
				}); 
		}
		
		if(cell.length>2){
			buttons.push(
					{
					text: 'Chiama '+cell,
					onClick: function () {
						location.href="tel:"+cell;
					}
				}); 
		}
		
		if(email.length>2){
			buttons.push(
					{
					text: 'Scrivi a '+email,
					onClick: function () {
						location.href="mailto:"+email;
					}
				}); 
		}
		
		
		 var buttons3 = [
			{
				text: 'Chiudi',
				color:'black'
			}
		];
		
		 var groups = [buttons, buttons3];
  	     myApp.actions(groups);

}
var IDstrex=new Array();
function cambiastruttura(txt){
		
		var buttons=new Array();
	//alert(txt);
	
		var evalstr=$$('#evalcambia').val();
		
		evalstr=atob(evalstr);
		//evalstr='buttons.push({text: "1",onClick: function () {modcambio(2,3);}});';
		eval(evalstr);
	/*
		var arr1= txt.split(',');
		var i=0;
		for (prop in arr1) {
			if(arr1[prop]!=''){
			var arr2= arr1[prop].split('-');
		  	i++;
		 	
			var IDstr=arr2['0'];
			var str=arr2['1'];
			
		  	buttons.push({
					text: str,
					onClick: function () {
						modcambio(IDstr,3);
					}
				}); 	
			}
		} */
	
		 var buttons3 = [
			{
				text: 'Chiudi',
				color:'black'
			}
		];
		
		 var groups = [buttons, buttons3];
  	     myApp.actions(this,groups);

}


var strarr=new Array();
function modcambio(ID,tipo){
	var url=baseurl;
	var url=url+'config/gestionecambio.php';
	$$.post(url,{ID:ID,tipo:tipo},function(html){
		if(html!='error'){
			window.localStorage.setItem("IDcode", html);
			navigation(0,'',0,1);
		}
	});
}

var loadinf=true;
function infscroll(obj){
	
	if (obj.scrollTop + obj.clientHeight >= obj.scrollHeight) {
   		//esegue funzione
  	}

}


function salvarecensione(){
	var titolo=$$('#titolo').val();
	var recens=$$('#titolo').val();
	
	
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
	modprofilo(0,val,9,10,6);
	
	
	
}


