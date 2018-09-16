// Init App


blockPopstate=false;
var myApp = new Framework7({
    modalTitle: 'Scidoo',
	 animatePages:true,
	 cache:true,
	 material:false,//
	 fastClicks:false,
	 uniqueHistory:false,
	 pushState:false,
	 swipePanel: false,
	 preloadPreviousPage: true,
	 hideNavbarOnPageScroll: true,
	 animateNavBackIcon: true,
	 modalTitle: 'Scidoo',
	 //notificationTitle:'Scidoo',modificato
     notificationCloseOnClick: false,
     notificationCloseIcon: true,
     //notificationCloseButtonText: 'Close',
	 smartSelectBackOnSelect: true,
	 preroute: function (view, options) {
		 if(blockPopstate==true){
			if ($$('.modal-in').length > 0) { 
				myApp.closeModal();
				blockPopstate=false;
			}
			return false;
		}
    }
});


IDcode=window.localStorage.getItem("IDcode");

// Expose Internal DOM library
var $$ = Dom7;

var monthNames = ['Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto' , 'Settembre' , 'Ottobre', 'Novembre', 'Dicembre'];
var monthNamesShort= ['Gen', 'Feb', 'Mar', 'Apr', 'Mag', 'Giu', 'Lug', 'Ago', 'Set', 'Ott', 'Nov', 'Dic'];
var dayNames= ['Domenica', 'Lunedi', 'Martedi', 'Mercoledi', 'Giovedi', 'Venerdi', 'Sabato'];
var dayNamesShort= ['Dom', 'Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab'];
          

var mainView = myApp.addView('.view-main', {});

/*
$$(window).on('popstate', function(){
  myApp.closeModal('.popup.modal-in');
});
*/
var IDtabac='';
var reloadcal=0;
paginbef='';

var paginaora=0;

$$(document).on('page:init', function (e) {
	paginaora++;
	location.href='#'+paginaora;
});


var requestajax=0;
var stopexec=0;
//var timeoutback=0;
$$(document).on('page:back', function (e) {
	paginaora--;
	location.href='#'+paginaora;
	stopexec=0;
	
	var page=mainView.activePage.name;
	paginbef=page;
	
	requestajax.abort();
	
    setTimeout(function(){ 
   		var page=mainView.activePage.name;
		paginbef=page;
		
   		switch(page){
			
			case 'centrobenesseregiorno':
				if(reloadnav==1){
					var func=$$('#funccentro').val();
					eval(func);
				}
				
			break;
			case 'centrobenessere':
				
				myCalendar2.destroy();
				//calen2(13,'centrobenesserediv',16);
				
				
				//calennav(13,'centrobenesserediv',16);;
				/*if(reloadnav==1){
					var func=$$('#funccentro2').val();
					eval(func);
					reloadnav=0;
				}*/
			break;
			case 'ristorante':
				
				myCalendar2.destroy();

					//calen2(14,'ristorantediv',15);
					
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
				//alert('bbb');
				//if(reloadcal==1){
					var time=$$('#datacal').val();
					//navigation(2,time,0,1);
					//alert(time);
					navigationtxt(3,time,'calendariodiv',19);
					reloadcal=0;
					
				//}
			break;
			case 'nuovapren':
				
				
			break;
			case 'profilo':
				notifiche();
			break;
			case 'profilocli':
				navigationtxt(24,0,'contenutodiv',18);
			break;
			case 'promemoriaserv':
				navigationtxt2(10,0,'promemoriaservdiv',0);
			break;
			default:
				stopexec=1;
				setTimeout(function(){
					stopexec=0;
				},10000);
			break;
   		}
		
			   
  	}, 500);
});

function disableBack(){ window.history.forward() }


$$(window).on('popstate', function(e){
	if ($$('.modal-in').length > 0) { 
		myApp.closeModal();
	
		return false; 
	}else{
		var back=1;
		if ($$('.divsottoover').css('display')=='block') {
			back=0;
		}
		if(back==1){
			mainView.router.back();
		}
		
		//
	}
	
	/*else{ 
		
		var page=paginbef;//alert(page);
		switch(page){
			case 'nuovapren':
				//alert('block');
				//blockPopstate=true;
				return false; 
			break;
			case "profilo":
				//blockPopstate=true;
				return false; 
			break;
			case "profilocli":
				//blockPopstate=true;
				return false; 
			break;
			default:
				//blockPopstate=false;
				mainView.router.back();
			break;
		}
	}*/
});

//var baseurl='http://127.0.0.1/milliont/';
//var baseurl='http://192.168.1.106/milliont/';
//var baseurl='http://192.168.1.47/milliont/';
//

//var baseurl='http://188.11.58.195:108/milliont/';
var baseurl='https://www.scidoo.com/';


//var baseurl='http://188.11.58.195:108/milliont/';

var versione='v19';

//alert(baseurl);
function getUrlVars() {
	var vars = {};
	var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
	vars[key] = value;
	});
	return vars;
}


var guest=getUrlVars()["guest"];
if(typeof guest != 'undefined'){
	window.localStorage.setItem("IDcode", guest);
	onloadf(0);
}else{
	onloadf(0);
}



var IDutente=0;
	 function sendform(tipo){
		var tipo=parseInt(tipo);
		switch(tipo){
			case 2:
				var email = $$('input[name="email2"]').val();
        		var password = $$('input[name="pass2"]').val();
			break;
			case 3:
				var email=$$('#email').val();
				var password=$$('#pass').val();
				
			break;
			default:
				var email = $$('input[name="email"]').val();
        		var password = $$('input[name="pass"]').val();
			break;
			
				
		}
    	//setTimeout(function(){ hidelo(); }, 5500);		
		
		var url = baseurl+'config/login.php';
		//alert(url);
		myApp.showIndicator();//setTimeout(function(){ hidelo(); }, 5500);	
		$$.ajax({
                url: url,
                method: 'POST',
				dataType: 'text',
				timeout:10000,
				cache:false,
				data: {
                    email:email,
					password:password,
					json:1,
					callback:'?'
                },
				error: function (data) {
					 myApp.hideIndicator();
					//alert(data);
					//console.log(data);
				},
				statusCode: {
					404: function() {
					  alert( "Pagina non trovata!" );
					  myApp.hideIndicator();
					}
				  },
                success: function (data) {
                    //Find matched items
					//alert(data);
					//clearTimeout();
					 myApp.hideIndicator();  	 //alert(data);
					var num=data.indexOf("error");  	 //alert(data);
					if(num==-1){		
						window.localStorage.setItem("IDcode", data);
						//alert(data);
						IDcode=data;
						//var query = {IDcode:data};
						//$$('#logged').html('<a href="javascript:void(0)" onclick="navigation('+"0,'',0"+')" class=" -big button-fill" style=" width:40%; margin:auto; background:#ff9c00;">Entra su Scidoo</a>');
						
						navigation(0,'',12,1); //
						setTimeout(function (){
							azzerastoria();
						},1000);

					}else{
						myApp.addNotification({
							message: "I Dati immessi non sono corretti. Prego riprovare!",
							hold:1200
						});
					}
					 
				}
            })
	}
	
	
	function azzerastoria(){
			//alert('aa');
				$$('.navbar-on-left').remove(); 
                    $$('.page-on-left').remove(); 
                    //var index   = mainView.history[0];
                    //var actual  = mainView.activePage.url;
					//alert(index);
                    mainView.history = [];
                   // mainView.history.push(index);
                   // mainView.history.push( actual );
                    //mainView.router.back();
	}
	
	
	
		function sendform2(){
        var email = $$('input[name="mailcli"]').val();
        var data = $$('input[id="kscal"]').val();
		var url = baseurl+'config/logincli.php';
		myApp.showIndicator();
		//setTimeout(function(){ hidelo(); }, 5500);	
		//alert(url);
		$$.ajax({
                url: url,
                method: 'POST',
				dataType: 'text',
				cache:false,
				timeout:5000,
				data: {
                    email:email,
					data:data,
					json:1
                },
                beforeSend: function (data) {
					//alert(email);
				},
				 error: function (data) {
					 myApp.hideIndicator();
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
					 //clearTimeout();
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

var posadd='';

myApp.onPageInit('profilo', function (page) {	
	/*myApp.initPageSwiper('#tabmain3');*/
	//alert($('#posadd').val());
	posadd=$('#posadd').val();
	
	var mySwiper3 = myApp.swiper('.swiper-3', {
	  pagination:'.swiper-3 .swiper-pagination',
	  spaceBetween: 10,
	  slidesPerView: 3,
	  centeredSlides: false
	
	});
	
	
});
myApp.onPageInit('indice', function (page) {	
	
	if(IDcode=='undefined'){
		onloadf(1);
	}
	
});

function offsetfunc(elt) {
	var rect = elt.getBoundingClientRect(), bodyElt = document.body;
	
	return {
	  top: rect.top + bodyElt .scrollTop,
	  left: rect.left + bodyElt .scrollLeft
	}
}


var p=0;
var scrollcal=0;
var mesescrollato=0;
function scrollrig(){
		//var lef=document.getElementById('tabcalmain');
		//lef=lef.offsetLeft*-1+parseInt(172);
		//alert(lef);
		//var offsetElt = offsetfunc(document.getElementById('tabcalmain'));
		//lef=offsetElt.left*-1+parseInt(172);
		/*alert(lef);
		
		alert(offsetElt.left);
	*/
	//alert(scrollriginib);
	
	//alert('bb');
	
	
	
	
	/*var nomemeseattuale=$$('#dataattuale').html();
	var nomemeseprox=$$('#dataprox').html();
	var offsetfin= $$(".giornofin").offset().left;
	
	if(offsetfin<110){
		if(mesescrollato==0){
			$('#datameseattuale').hide().html(nomemeseprox).fadeIn(400);
			mesescrollato=1;
		}
	}else{
		if(mesescrollato==1){
			$('#datameseattuale').hide().html(nomemeseattuale).fadeIn(400);
			mesescrollato=0;
		}
	}*/
	
	
	if(scrollriginib==1){
		offsettopcalendario=$$('.table-fixed-right').offset().top;
		//offsetleftcalendario=parseInt($$(('.table-fixed-right')).offset().left);
		
		var offset=$$("#tabbody").offset().left;
		//alert(offset);
		offsetleftcalendario=offset;
		
		//$('#datameseattuale').html(offset);
	
			//alert(offset);
			//document.getElementById("tabdate").style.left=
			var lef=offset;
	
			scrollcal=lef;
			
			$('#tabdate').css({'left':lef+'px'});
			
			//document.getElementById("tabdate").style.left=lef+'px';
		
		
	}
	scrollriginib=1;
		

 			
			//$$('#tabdate').css('left',lef+'px');
		
	}	
	
var indipren=0;
function addprenot(time,app,notti){
	
	if(notti==-1){
		var buttons=new Array();
	
			buttons.push(
					{
					text: '<div>Con Pernottamento</div>',
					onClick: function () {
						addprenot2(0,0,1);
						//navigation(24,ID,0,0);
					}
				}); 	
			var appsenza=$$('#IDsenzas').val();	
				
			buttons.push(
					{
					text: '<div>Giornaliera</div>',
					onClick: function () {
						addprenot2(0,appsenza,0);
						//navigation(24,ID,0,0);
					}
				});
		
		
		
		
		 var buttons3 = [
			{
				text: '<div class="lastbutton-modal">Chiudi</div>'
			}
		];
		
		 var groups = [buttons, buttons3];
  	     myApp.actions(groups);
	}else{
		
		addprenot2(time,app,notti);
	}
	
	
}



function addprenot2(time,app,notti){
	
	indipren=1;
	//IDcode=window.localStorage.getItem("IDcode");
		var url=baseurl+versione+"/config/nuovaprenotazione.php";
		//id=parseInt(id);
		//var apriurl=new Array('config/profilo.php','config/profilocli.php','config/calendario.inc.php','config/detpren.php');
		//var url=url+apriurl[id];
		//alert(IDcode);
		
		//var popupHTML = '<div class="popup" style="padding:0px;" id="contprenot"></div>';
		//myApp.popup(popupHTML);
		
		var query={time:time,app:app};			
		myApp.showIndicator();//setTimeout(function(){ hidelo(); }, 5500);	
		$$.ajax({
				url: url,
					method: 'GET',
					dataType: 'text',
					cache:false,
					timeout:5000,
					data: query,
					success: function (data) {
						
						myApp.hideIndicator();
						//$$('#contprenot').html(data);
						
						
						mainView.router.load({
							content: data,
						   animatePages: true
						});
						
						blockPopstate=true;
						
						//navigationtxt(5,0,'step0',0);
						/*if((time!=0)&&(app!=0)){
							stepnew(1,'1,1')	;						
						}
						if((time!=0)&&(app==0)){
							stepnew(1,'0,1')	;
						}*/
						
						
						//if(time!=0){
							statostep=0;
							stepnew(0,0);
							//alert('ff');
							
							if(notti!=0){
								piunotti=1;
							}else{
								piunotti=0;
							}
						//}
						myApp.initPageSwiper('#tabmain4');
			 },
				 error: function (data) {
					 myApp.hideIndicator();
				}
		 });	


}



var offsetleftcalendario=0;
var offsettopcalendario=92;
var scrollriginib=1;


myApp.onPageInit('calendario',function (page) {
	
	
	var timeprecedente=parseInt($$('#datadietro').val());
	var timeprossimo=parseInt($$('#dataavanti').val());
	var dataattuale=$$('#dataattuale').html();
	var meseattuale=$$('#meseattuale').val();
	
	$$('#mesescorso').attr('onclick','navigation(2,'+timeprecedente+',1,1);offsetleftcalendario=0');
	$$('#meseprox').attr('onclick','navigation(2,'+timeprossimo+',1,1);offsetleftcalendario=0');
	$$('#datameseattuale').attr('onclick','cambiomesi('+meseattuale+');');
	$$('#datameseattuale').html(dataattuale);
	//$$('#tabdate').stop();
	//$$('#tabdate').css('position','fixed');
	//$$('#tabdate').css('top','50px');
	//$$('#tabdate').css('left','86px');
	//$$('#tabbody').css('margin-top','53px');
	//var p=0;
	
							$$('.nosoggcal').on('click', function () {
								var time=$$(this).attr('alt');
								if(!isNaN(time)){
									var time=parseInt($$('#meseattuale').val())+parseInt(((time-1)*86400));
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
									var time=parseInt($$('#meseattuale').val())+parseInt(((arr['0']-1)*86400));
									//alert(time);
									var app=arr['1'];
									addprenot(time,app,arr['2']);
								}
							});
							$$('.notaes').on('click', function () {
								var time=$$(this).attr('alt');
								if(!isNaN(time)){
									var time=parseInt($$('#meseattuale').val())+parseInt(((time-1)*86400));
									openesclu(time);
								}
							});
							
							$$('.annullata').on('click', function () {
								var time=$$(this).attr('alt');
								if(!isNaN(time)){
									var time=parseInt($$('#meseattuale').val())+parseInt(((time-1)*86400));
									openann(time);
								}
							});
								
							
	
									if(offsetleftcalendario==0){
										if ($$(".ogg").html() != undefined) {
											scrollriginib=0;
											
											var offset = $$("#tabcalmain").offset().left;
											var offset2 = $$(".ogg").offset().left;	
											//alert(offset2);
											var left=offset2-offset;
												
											
											document.getElementById('tabcalmain').scrollLeft=parseInt(left)+parseInt(111);
											
											//alert(left);
											//offsetleftcalendario=left;
											//alert(left);
											
											var max=parseInt($$("#tabbody").css('width'))-parseInt($$("#tabcalmain").css('width'))-111;
											
											//alert(max);
											if(left>max){
												left=max;
											}
											left=left*-1;
											document.getElementById("tabdate").style.left=left+'px';
											
											
											setTimeout(function(){
												offsettopcalendario=parseInt($$(('#tabcalmain')).offset().top);
												
												var offset=$$("#tabbody").offset().left;
												offsetleftcalendario=offset;
												
											},1000);
											
											
										}else{
											document.getElementById("tabdate").style.left='111px';
										}
									}else{
										if(offsettopcalendario<0){
											var offsettopcalendario2=parseInt(-1*offsettopcalendario)+parseInt(49);
										}else{
											var offsettopcalendario2=49-offsettopcalendario;
										}
										
										
										$$('.page-content').scrollTop(offsettopcalendario2);
										
										//alert(offsetleftcalendario);
										
										
										
										if(offsetleftcalendario>=0){
											var res=(parseInt(offsetleftcalendario)-86);
											if(res<0){res=res*-1;}
											//alert('PLU:'+res);
											document.getElementById('tabcalmain').scrollLeft=res;
										}else{
											var res=parseInt(offsetleftcalendario)*-1+parseInt(86);
											//alert(res);
											document.getElementById('tabcalmain').scrollLeft=res;
										}
										scrollriginib=0;
										
										
										
										//var left2=parseInt(offsetleftcalendario)-86;
										//alert(offsetleftcalendario+'//'+left2);
										document.getElementById("tabdate").style.left=offsetleftcalendario+'px';
										//document.getElementById("tabdate").style.left='86px';
											
									}
							
						
							
							
	var befscroll=0;
	var ps=0;	
	var container2 = $$('.page-content');
	$$(container2).scroll(function() {
		offsettopcalendario=parseInt($$(('#tabcalmain')).offset().top);
	});
	
	
});
	


var myCalendar='';
var reloadnav=0;
var reloadnavadd=0;

var myPhoto=new Array();

function navigation(id,str,agg,rel){
	
	var url=baseurl+versione+"/";
	
	
	
	id=parseInt(id);
	

	//var apriurl=new Array('profilo/temp.php','calendario.inc.php','detpren2.php','calendario2.inc.php','preventivo/step1.php','preventivo/step0.php','preventivo/step2.php','preventivo/step3.php','preventivo/step4.php','preventivo/step5.php','notifiche.inc.php','promemoria.php','appunti.inc.php','centrobenessere.inc.php','ristorante.inc.php','pulizie.inc.php','domotica.inc.php','arrivi.inc.php','clienti.inc.php','prenotazioni.inc.php','ristorantegiorno.inc.php','centrobenesseregiorno.inc.php','preventivo/step4cerca.php','profilo/servizi.php','profilo/prenotazione.php','profilo/temperatura.php','profilo/menuristorante.php','/profilo/ilconto.php','profilo/elencoservizi.php','profilo/elencoluoghi.php','ricercaclidet.php','ricercaserv.php','centrobenesseregiorno.inc.php');
	
	
	var apriurl=new Array('config/profilo.php','config/profilocli.php','config/calendario.inc.php','config/detpren.php','config/centrobenessere.php','config/ristorante.php','config/pulizie.php','config/arrivi.php','config/prenotazioni.php','config/clienti.php','config/domotica.php','config/notifiche.php','config/appunti.php','config/ristorantegiorno.php','config/centrobenesseregiorno.php','config/dettavolo.php','config/profilo/servizi.php','config/profilo/temperatura.php','config/profilo/menuristorante.php','config/profilo/elencoservizi.php','config/profilo/ilconto.php','config/profilo/elencoluoghi.php','config/explodeservice.php','config/puliziedet.php','config/clientidet.php','config/profilo/detserv.php','config/profilo/detservizio.php','config/profilo/addserv.php','config/profilo/suggerimenti.php','config/profilo/galleria.php','config/profilo/recensioni.php','config/profilo/detrecensione.php','config/profilo/nuovarecensione.php','config/detluogo.php','config/nuovotavolo.php','config/menugiorno.php','config/impostazionialloggi.php');
	//last 36
	
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
	
	myApp.showIndicator();//setTimeout(function(){ hidelo(); }, 5500);	
	//alert(url);
	
	requestajax=$$.ajax({
            url: url,
                method: 'GET',
				dataType: 'text',
				cache:false,
				timeout:5000,
                data: query,
                success: function (data) {
					
					//myApp.alert(data);
					
					
					myApp.hideIndicator();
					//clearTimeout();
					switch(rel){
						case 1:
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
					
					//alert(agg);
					//mainView.router.loadContent({content:data,force:true});
					switch(agg){
						case 1:
							var nomemeseattuale=$$('#dataattuale').html();
							$('#datameseattuale').html(nomemeseattuale);
							
							$('#tabcalmain').scroll(function(){
								scrollrig();
							});
							
							
							
						break;
						case 2:
                            //calen2(13,'centrobenesserediv',16);
								timeout=0;
							if(rel==2){
								timeout=500;
							}
											
						
							setTimeout(function(){
								
								var mySwiper=myApp.swiper(' .sw2', {
									 spaceBetween: 10,
  									 slidesPerView: 1});
								var sslide=$$('.giornosel').attr('padre');
								mySwiper.slideTo(sslide,400);	
								calen2(4,2,2);
							},timeout);	

						break;
						case 3:
							//alert('aa');
							
							timeout=0;
							if(rel==2){
								timeout=500;
							}
							
							
							
							setTimeout(function(){
								
								var mySwiper=myApp.swiper(' .sw2', {
									 spaceBetween: 10,
  									 slidesPerView: 1});
								var sslide=$$('.giornosel').attr('padre');
								mySwiper.slideTo(sslide,400);	
								calen2(5,3,2);
							},timeout);
							
							
						break;
						case 4://agg button giorni
							//alert(query['dato0']);
							/* if(typeof($$('#'+query['dato0']).length) != 'undefined'){
								var left = $$('#'+query['dato0']).offset().left;
								left=left-150;
								document.getElementById('infinitemain').scrollLeft=left;
							 }*/
							
						break;
						case 5:
							 //calen2(17,'arrividiv',17);
							
							timeout=0;
							if(rel==2){
								timeout=500;
							}
							setTimeout(function(){
								
								var mySwiper=myApp.swiper(' .sw2', {
									 spaceBetween: 10,
  									 slidesPerView: 1});
								var sslide=$$('.giornosel').attr('padre');
								mySwiper.slideTo(sslide,400);	
							},timeout);
							
						break;
						case 6:
							var sosp=parseInt($$('#numsosp').val());
							$$('#sospesi').html(sosp);
							
							var mySwiper2=myApp.swiper(' .sw3', {
									 spaceBetween: 10,
  									 slidesPerView: 1});
							
								var sslide=$$('.giornosel2').attr('padre2');
								mySwiper2.slideTo(sslide,400);
						
						break;
						case 7:
							
							/*
							var mySwiper3 = myApp.swiper('.swiper-3', {
							  pagination:'.swiper-3 .swiper-pagination',
							  spaceBetween: 0,
							  slidesPerView: 3
							});
							var mySwiper4 = myApp.swiper('.swiper-4', {
							  pagination:'.swiper-4 .swiper-pagination',
							  spaceBetween: 10,
							  slidesPerView: 3
							});
							
							*/
							
							modalfirstentryospiti();
							//menuprofilo();
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
						
						case 10:
								var mySwiper2=myApp.swiper(' .sw3', {
									 spaceBetween: 10,
  									 slidesPerView: 1});
							
								var sslide=$$('.giornosel2').attr('padre2');
								mySwiper2.slideTo(sslide,400);
						
						break;
						case 11:
							//calen2(14,'ristorantediv',15);
							//mySwiper.slideTo(0,400);
							//alert('prova');
						break;
						case 12:
							modalfirstentry();
							
						break;	
					
					}
					
         },
				 error: function (data) {
					 myApp.hideIndicator();
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
		statostep=1;stepnew(1,0);
	}else{
		myApp.addNotification({
			message: "E' necessario selezionare una soluzione. Prego riprovare",
			hold:1200
		});
	}
}





function  tabconto(tipo){
		var url=baseurl+versione+"/config/preventivo/ilconto.php";
		var query={tipo:tipo};	
		myApp.showIndicator();//setTimeout(function(){ hidelo(); }, 5500);	
		$$.ajax({
			url: url,
			method: 'GET',
			dataType: 'text',
			timeout:5000,
			cache:false,
			data: query,
			success: function (data) {
				myApp.hideIndicator();
				$$('.conto1').removeClass('active');
				$$('#conto'+tipo).addClass('active');
				$$('#contenutoconto').html(data);
						
			},
				 error: function (data) {
					 myApp.hideIndicator();
				}
		});
}





function  tabservizi(tipo,cerca){
		var url=baseurl+versione+"/config/preventivo/elencoserv.php";
		var query={tipo:tipo,cerca:cerca};	
		myApp.showIndicator();//setTimeout(function(){ hidelo(); }, 5500);	
		$$.ajax({
			url: url,
			method: 'GET',
			dataType: 'text',
			timeout:5000,
			cache:false,
			data: query,
			success: function (data) {
				myApp.hideIndicator();
				$$('.step1').removeClass('active');
				$$('#step1'+tipo).addClass('active');
				$$('#contenutoservizi').html(data);
						
			},
				 error: function (data) {
					 myApp.hideIndicator();
				}
		});
}



function avanti2(dato0){
	//alert('DATO'+data0);
	//alert(statostep);
	switch(statostep){
		case 0:
			
			dispo2();
				
		break;
		case 1:
			//controllo
			
			stepnew(1,0);
			/*var mioArray=document.getElementsByClassName('tablist selected');
			var lun=mioArray.length;
			
			if(lun>0){
				stepnew(1,0);
			}else{
				//notification
				
				myApp.addNotification({
					message: "Obbligatorio selezionare un'opzione",
					hold:1200
				});
				
			}*/
		break;
		case 2:
			stepnew(1,0);
		break;
		case 3:
			stepnew(1,0);
		break;	
		case 4:
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
	
	$('#avantitxt').html('Avanti');
	switch(step){
		case 0:
					
			if(agg==1){
				navigationtxt(4,"0,2",'step'+statostep,11);
			}
			$$('#titolodivmain').html('Richiesta');
			
			
		break;
		case 1:
		
			//dispo2();
			
			if(agg==1){
				//alert(statostep);
				navigationtxt(5,1,'step'+statostep,0);
				$$('#titolodivmain').html('Trattamento');
				calcolatot();
				//calcolatot();
			}
								
			
			
			//if(show!=0){myApp.showTab('#step'+statostep);}
		break;
		case 2:
		
			$$('#buttonadd').css('visibility','visible');
			if((agg==1)&&(calextra==0)){
				navigationtxt(6,str,'step'+statostep,0);
				calcolatot();
			}else{
				calextra=0;
			}
			$$('#titolodivmain').html('Elenco Servizi');
			//if(show!=0){myApp.showTab('#step'+statostep);}
		break;
		case 3:
			if(agg==1){navigationtxt(7,str,'step'+statostep,0);}
			//if(show!=0){myApp.showTab('#step'+statostep);}
			$$('#titolodivmain').html('Dati Cliente');
		break;
		case 4:
			//$$('.avanti').html('<i class="f7-icons " style="color:#fff; font-size:30px; margin-top:2px; ">check</i>');
			$$('#titolodivmain').html('Conferma Prenotazione');
			$('#avantitxt').html('Conferma');
			if(agg==1){navigationtxt(8,str,'step'+statostep,0);}
			//if(show!=0){myApp.showTab('#step'+statostep);}
		break;
	}
	
	if(statostep==0){
		$$('.divmainmenu').css('visibility','hidden');
	}else{
		$$('.divmainmenu').css('visibility','visible');
		
	}
	
}


function controllodispo(){
	var url=baseurl+versione+"/config/preventivo/config/controllodispo.php";
	
	myApp.showIndicator();
	query=new Array();
	query['datai']=$$('#dataarr').val();
	query['notti']=$$('#notti').html();
		$$.ajax({
            url: url,
                method: 'GET',
				dataType: 'text',
				cache:false,
				timeout:10000,
                data: query,
				success: function (data) {
				
					myApp.hideIndicator();
					//alert(data);
					if(data.length==0){
						$$('#txtalloggio').html('No Disponibilità');
						$$('#txtalloggio').addClass('txtnoavail');
					}else{
						$$('#alloggio').html(data);
						$$('#txtalloggio').removeClass('txtnoavail');
						var txtalloggio= $('#alloggio').find("option:selected").text();
						$$('#txtalloggio').html(txtalloggio);
					}
					
				},
				 error: function (data) {
					 myApp.hideIndicator();
				}
		});
	
	
}

var okstep1=0;
var openp=0;

function navigationtxt(id,str,campo,agg,loader){
	if(IDcode=='undefined'){
		onloadf(1);
	}
	//reloadnav=0;
	//alert(id);
	var url=baseurl+versione+"/config/";
	
	var apriurl=new Array('profilo/temp.php','calendario.inc.php','detpren2.php','calendario2.inc.php','preventivo/step0.php','preventivo/step1.php','preventivo/step2.php','preventivo/step3.php','preventivo/step4.php','preventivo/step5.php','notifiche.inc.php','promemoria.php','appunti.inc.php','centrobenessere.inc.php','ristorante.inc.php','pulizie.inc.php','domotica.inc.php','arrivi.inc.php','clienti.inc.php','prenotazioni.inc.php','ristorantegiorno.inc.php','centrobenesseregiorno.inc.php','preventivo/step4cerca.php','profilo/servizi.php','profilo/prenotazione.php','profilo/temperatura.php','profilo/menuristorante.php','/profilo/ilconto.php','profilo/elencoservizi.php','profilo/elencoluoghi.php','ricercaclidet.php','ricercaserv.php','centrobenesseregiorno.inc.php','aggiungipiatti2.php','tavoloordinazione.php','nuovotavolo.php','ricercapersscript.php','menugiorno.inc.php','cambiadatapren.inc.php','impostazionialloggi.inc.php');//39
	var url=url+apriurl[id];
	//alert(id);
	//alert(url);
	//alert('TXT'+id);
	if(loader!=0){
		myApp.showIndicator();
		//setTimeout(function(){ hidelo(); }, 5500);	
	}
	query=new Array();
	query['IDcode']=IDcode;
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
	requestajax=$$.ajax({
            url: url,
                method: 'GET',
				dataType: 'text',
				cache:false,
				timeout:7000,
                data: query,
                success: function (data) {
					//alert(data);
					
					if(stopexec==1){
						return false;
					}
					
					if(loader!=0){
						//clearTimeout();
						myApp.hideIndicator();
					}
					
					
					$$('#'+campo).html(data);
					//alert(id);
					if(id==3){
						
							//alert(scrollcal);
							
							if(scrollcal!=0){
								document.getElementById('tabcalmain').scrollLeft=parseInt(scrollcal*-1)+parseInt(110);
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
									var time=parseInt($$('#meseattuale').val())+parseInt(((time-1)*86400));
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
									var time=parseInt($$('#meseattuale').val())+parseInt(((arr['0']-1)*86400));
									
									var app=arr['1'];
									addprenot(time,app,arr['2']);
								}
							});
							$$('.noteesc').on('click', function () {
								var time=$$(this).attr('alt');
								if(!isNaN(time)){
									var time=parseInt($$('#meseattuale').val())+parseInt(((time-1)*86400));
									openesclu(time);
								}
							});
							
							$$('.annullata').on('click', function () {
								var time=$$(this).attr('alt');
								if(!isNaN(time)){
									var time=parseInt($$('#meseattuale').val())+parseInt(((time-1)*86400));
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
								monthNames: monthNames,
								monthNamesShort: monthNamesShort,
								dayNames: dayNames,
								dayNamesShort: dayNamesShort,
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
								monthNames: monthNames,
								monthNamesShort: monthNamesShort,
								dayNames: dayNames,
								dayNamesShort: dayNamesShort,
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
								url: "https://www.scidoo.com/"+versione+"/img/homepoi.svg", // url
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
							//rangePicker: range,

							
							
							
							if(piunotti==1){
								
								
									var mySwiper4 = myApp.swiper('.swiper-4', {
									  pagination:'.swiper-4 .swiper-pagination',
									  spaceBetween: 5,
									  slidesPerView: 7
									});
				
				

								
	
								var calendararrivo = myApp.calendar({
									input: '#dataform',
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
											var datap=$$('#dataarr').val();
											
											
											var gg=val.getDate();
											if(gg<10)gg='0'+gg;
											var mm=val.getMonth();
											mm++;
											if(mm<10)mm='0'+mm;
											var yy=val.getFullYear();
											var data=yy+'-'+mm+'-'+gg;
										
											
											
										
											if(data!=datap){
												$$('#dataarr').val(data);
												trasftesto2(data,'dataform');
												
												

												//controllodispo();

												//controllo notti eventuali e modifica data 



													var datai=data;
													var dataf=$$('#datapar').val();

													var notti=gd(datai,dataf,2);
													if(notti<1){

														 dataf2=adddata(datai,1,2,2);
														 trasftesto2(dataf2,'dataform2');
														 var dataf3=new Date(dataf2);

														 calendarpartenza.value=[dataf3];
														var dataf3=new Date(datai);
														 calendarpartenza.params.minDate=[dataf3];

														 $$('#notti').html('1');
													}else{
														$$('#notti').html(notti);
														if(notti>1){
															$$('#txtnotti').html('Notti');
														}else{
															$$('#txtnotti').html('Notte');
														}

														var dataf3=new Date(data);
														calendarpartenza.params.minDate=[dataf3]; 
													}

													controllodispo();										


												
											}
										
											
									}
								}); 
								
								
							
								var calendarpartenza = myApp.calendar({
									input: '#dataform2',
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
	
										//alert('ccc');
										
											var val=new Date(values);
											var gg=val.getDate();
											if(gg<10)gg='0'+gg;
											var mm=val.getMonth();
											mm++;
											if(mm<10)mm='0'+mm;
											var yy=val.getFullYear();
											var data=yy+'-'+mm+'-'+gg;
											
											var datap=$('#datapar').val();
											if(data!=datap){
												var txtdata=trasftesto2(data,'dataform2');
											
												//controllo notti eventuali e modifica data 

												var dataf=data;
												var datai=$$('#dataarr').val();
												var notti=gd(datai,dataf,2);
												$$('#notti').html(notti);

												if(notti>1){
													$$('#txtnotti').html('Notti');
												}else{
													$$('#txtnotti').html('Notte');
												}

												controllodispo();
											}
										
										
											
											
									}
								});  
								
								var datai=$$('#dataarr').val();
								var dataf=$$('#datapar').val();

								var dataf3=new Date(datai);
								//alert(dataf3);
								calendararrivo.value=[dataf3];
								
								calendarpartenza.params.minDate=[dataf3]; 
								var dataf3=new Date(dataf);
								calendarpartenza.value=[dataf3];
							}else{
																
								var calendararrivo = myApp.calendar({
									input: '#dataform',
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
										//alert('dddd');
											var val=new Date(values);
											var gg=val.getDate();
											if(gg<10)gg='0'+gg;
											var mm=val.getMonth();
											mm++;
											if(mm<10)mm='0'+mm;
											var yy=val.getFullYear();
											var data=yy+'-'+mm+'-'+gg;
											$$('#dataarr').val(data);
											testotimemick(data,'dataform');
											//trasftesto2(data,'dataform');
											
									}
								}); 
								
								
								
							}
							
							
							
							
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
							
							
						case 14:
							var myCalendar = myApp.calendar({
								input: '#buttdata',
								weekHeader: true,
								dateFormat: 'dd/mm/yyyy',
								header: false,
								footer: false,
								rangePicker: false,
								onChange:function (p, values, displayValues){
									
									var vis=$('#vispulizie').val();
									var datapulizie=$('#datapulizie').val();
									var data = Date.parse(values);
									var tempo= data/1000;
									if(tempo!=datapulizie){
										var val=tempo+','+vis;
										navigationtxt(15,val,'puliziediv',14);
									}
								 	myCalendar.close();
										
									
									
									
								}
							});
						break;
						case 15:
							
					//		calen2(14,'ristorantediv',15);
						break;
						case 16:
								var sosp=parseInt($$('#numsosp').val());
								$$('#sospesi').html(sosp);
							//calen2(13,'centrobenesserediv',16);
							//calennavbar(13,'centrobenesserediv',16);
						break;
						case 17:
							//calen2(17,'arrividiv',17);
						break;
						case 18:
							//ricarca slider
							setTimeout(function(){
								var swiperserv = myApp.swiper('.swiperserv',{
									pagination:'.swiper-pagination',
									spaceBetween: 10,
									centeredSlides: true,
									loop: true,
									slidesPerView: 'auto'
								});
							},200);
							
							
						break;
						case 19:
							//calendario
							var nomemeseattuale=$$('#dataattuale').html();
							$('#datameseattuale').html(nomemeseattuale);
							
							$('#tabcalmain').scroll(function(){
								scrollrig();
							});
							
						break;
					}
					
         },
				 error: function (data) {
					 myApp.hideIndicator();
				}
     });	
}

function trasftesto(data,campo){
	var url=baseurl;
	var url=url+'config/testodata.php';
	//alert(url);
	$$.post(url,{data:data},function(html){
		//alert(html);
		$$('#'+campo).val(html);
		$$('#'+campo).attr('alt',data);
	});
}

function trasftesto2(data,campo){
	var url=baseurl;
	//var url=url+'config/testodata2.php';
	var url=url+versione+'/config/testodatamick.php';
	//alert(url);
	$$.post(url,{data:data},function(html){
		//alert(html);
		var arr=html.split('//');
		//alert('bb'+arr['3']);
		$$('#'+campo+'-1').html(arr['0']);
		$$('#'+campo+'-2').html(arr['1']);
		$$('#'+campo+'-3').html(arr['2']);
		$$('#'+campo+'-4').html(arr['3']);
		//$$('#'+campo).attr('alt',data);
	});
}

function trasftesto3(data,campo){
	var url=baseurl;
	var url=url+versione+'/config/testodata3.php';
	$$.post(url,{data:data},function(html){
		$$('#'+campo).val(html);
	});
}


function testotimemick(data,campo){
	var url=baseurl;
	//var url=url+'config/testodata2.php';
	var url=url+versione+'/config/testodatamick.php';
	//alert(url);
	$$.post(url,{data:data},function(html){
		//alert(html);
		var arr=html.split('//');
		
		$$('#'+campo+'-1').html(arr['0']);
		$$('#'+campo+'-2').html(arr['1']);
		$$('#'+campo+'-3').html(arr['2']);
		$$('#'+campo+'-4').html(arr['3']);
		//$$('#'+campo).attr('alt',data);
	});
}



function generanotti(data,campo,nottisel){
	var url=baseurl;
	var url=url+'config/generanotti.php';
	$$.post(url,{data:data,nottisel:nottisel},function(html){
		var arr=html.split('///');
		
		$$('#'+campo).html(arr['0']);
		$$('#nottiafter').html(arr['1']);
	});
}

/*
mainView.history = []; 
document.addEventListener('backbutton', function (e) { e.preventDefault();});

	// Check for go back in history / 
	var view = myApp.getCurrentView(); 
	if (!view) return; 
	if (view.history.length > 1) { view.router.back(); return; } 
*/

function onloadf(time){
	
	myApp.showIndicator();
	//setTimeout(function(){ hidelo(); }, 5000);	
	IDcode=window.localStorage.getItem("IDcode");
	//alert(IDcode);
	IDcode2=new String(IDcode);
	
	//var h = window.innerHeight;
	//creasessione(h,86);
	//alert(IDcode2);
	if(IDcode2.length>10){
	
		var url=baseurl+versione+'/config/controlloini.php';
		//var IDnotpush=$$('#IDnotpush').val();
		$$.ajax({
            url: url,
                method: 'POST',
				dataType: 'text',
				timeout:10000,
				cache:false,
                data: {IDcode:IDcode},
                success: function (data){
					//alert(data);
					myApp.hideIndicator();
					var num=data.indexOf("error");
					if((num==-1)&&(!isNaN(data))){
						data=parseInt(data);
						if(time==0){
							agg=0;

							if(data==1)agg=7;
							
							azzerastoria();

							navigation(data,'',agg,1);
							notifpush(1);
						}else{
							vislogin();
						}
					}else{
						vislogin();
					}
				
				
    				//myApp.hideIndicator();
					//mainView.router.loadContent(data);
       			},
				 error: function (data) {
					 myApp.hideIndicator();
				}
    	 });
	}else{
		//alert('cc');
		var IDnotpush=$$('#IDnotpush').val();
		vislogin();
		myApp.hideIndicator();	
	}
	
}

function notifpush(tipo){
	
		var url=baseurl+versione+'/config/notifichepush.php';
		var IDnotpush=$$('#IDnotpush').val();
	
		$$.ajax({
            url: url,
                method: 'POST',
				dataType: 'text',
				timeout:10000,
				cache:false,
                data: {IDnotpush:IDnotpush,tipo:tipo},
                success: function (data){
					
       			}
    	 });
	
	
	
	
}


function vislogin(){
	
	$$( ".app" ).animate({
		top: "-30"
	});
	$$('#logindiv').animate({
		opacity: "1"
	});
	//$$('#logged').html('');

}


function modprofilo(id,campo,tipo,val2,agg){
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
		var url=url+'config/gestioneprofilo.php';
		var query = {val:val,tipo:tipo,ID:id,val2:val2};
		//alert(url);
		$$.ajax({
			url: url,
			method: 'POST',
			timeout:5000,
			dataType: 'text',
			cache:false,
			data: query,
			success: function (data) {
				//alert(data);
				//clearTimeout();
				if(agg==3){
					myApp.addNotification({
						message: 'Servizio prenotato con successo',
						hold:1200
					});
				}else{
					myApp.addNotification({
					message: 'Modifica Salvata',
						hold:1200
					});
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
						
					case 7: 
							myApp.alert("Prenotazione Confermata con successo!'", function () {
								navigation(1,0,0,2);
							});
					break;
					case 8:
							// Convert timestamp to milliseconds
 							var datacheckin = new Date(val*1000);
							var ore= datacheckin.getHours();
 							var min= "0" + datacheckin.getMinutes();
							var stringa=ore+':'+min.substr(-2);
							$$('#oracheckin').html(stringa);
							myApp.addNotification({
							message: 'Orario di arrivo modificato con successo!',
								hold:2000
							});
					break;
					case 9:
						//backexplode(11);
						mainView.router.back();
						myApp.addNotification({
							message: 'Prenotazione confermata con successo!',
								hold:2000
							});
					break;
					
					
				}
			},
				 error: function (data) {
					 myApp.hideIndicator();
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
		var url=url+versione+'/config/detserv2.php';
		
		var query = {ID:val,tipo:tipo,multi:multi};
		//alert(url);
		$$.ajax({
					url: url,
				  	method: 'GET',
					dataType: 'text',
					timeout:5000,
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
					},
				 error: function (data) {
					 myApp.hideIndicator();
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
		var url=url+versione+'/config/modrestr.php';
		
		var query = {ID:val,tipo:tipo,multi:multi};
		//alert(url);
		$$.ajax({
				url: url,
					  method: 'GET',
					dataType: 'text',
					cache:false,
					timeout:5000,
					data: query,
					success: function (data) {
						//myApp.hideIndicator();
						
						
						//clearTimeout();
				
						if(popup==1){
							$$('#contpopup').html(data);
						}else{
							var popupHTML = '<div class="popup" id="contpopup" style="padding:0px;">'+data+'</div>';
							myApp.popup(popupHTML);
						}
				
						
						//mainView.router.loadContent(data);
					},
				 error: function (data) {
					 myApp.hideIndicator();
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
				var url=url+versione+'/config/detserv2.php';
				var query = {ID:txtsend,tipo:1,multi:0};
				//alert(url);
				$$.ajax({
						url: url,
							  method: 'GET',
							dataType: 'text',
							timeout:5000,
							cache:false,
							data: query,
							success: function (data) {
								document.getElementById(campo).innerHTML=data;
								var scriptinto=$$('#scriptinto').html();
								if(scriptinto!="undefined"){
									eval(scriptinto);
								}
							},
				 error: function (data) {
					 myApp.hideIndicator();
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
		//setTimeout(function(){ hidelo(); }, 5500);	
		var plus="";
	//alert(val2);
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
			case 12:
				var val=$$(campo).val();
			break;
			default:
				var val=$$('#'+campo).val();
			break;
		}
		
		var url=baseurl;
		var url=url+'config/gestioneprenextra.php';
		reloadnav=1;
		//alert(val);
		var query = {val:val,tipo:tipo,ID:id,val2:val2};
		$$.ajax({
				url: url,
					  method: 'POST',
					dataType: 'text',
					timeout:5000,
					cache:false,
					data: query,
					success: function (data) {
						
						//alert(data);
						//clearTimeout();
						myApp.addNotification({
							message: 'Modifica effettuata con successo',
							hold:1700
						});
						//alert(agg);
						switch(agg) {
							case 1:
								var arr=data.split('_');
								addservprev2(arr['0'],arr['1'],arr['2']);
								ricarcolaadd();
							break;
							case 2: //modifica serv
								/*var ID=$$('#IDmodserv').val();
								var tipo=$$('#tipomod').val();
								var time=$$('#timemod').val();
								var riagg=$$('#riaggmod').val();
								modificaserv(ID,tipo,time,riagg);*/
								
								/*var arr=val.split('_');
								var sala=arr['0'];
								var time=arr['1'];
								var IDpers=arr['2'];
								$$('.tablist').removeClass('selected');
								$$('#'+time+sala+IDpers).addClass('selected');
								
								var funz=$$('#funzioneriagg').val();
								eval(funz);*/
								
								var funz=$$('#datamod').attr('onchange');
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
								myApp.closeModal();
								setTimeout(function(){
								tavoloordinazione(id);
								}, 300);
								
								/*mainView.router.back();*/
							break;
							case 6:
								/*myApp.closeModal();
								navigation(15,id,8,1);*/
								var IDprenextrapopover=id+',1';
								navigationtxt(34,IDprenextrapopover,'ordinazione',0);
								
							break;
							case 21:
								chiudimodal();
		                        var stringad=$$('#time').val()+','+$$('#idosottotip').val()+','+$$('#agg').val();
							  	navigationtxt(20,stringad,'ristorantegiornodiv',0);
							break;	
							case 22:
								chiudimodal();
							    var stringad=$$('#timeristogiorno').val()+','+$$('#IDsottoristogiorno').val()+',1';
							  	navigationtxt(20,stringad,'ristorantegiornodiv',0);
								
							break;	
							case 23:
								chiudimodal();
							    var stringad=$$('#timeristogiorno').val()+','+$$('#IDsottoristogiorno').val()+',0';
							  	navigationtxt(20,stringad,'ristorantegiornodiv',0);
								
							break;	
							case 24://alloca tavolo
								chiudimodal();
							    var stringad=$$('#time').val()+','+$$('#idosottotip').val()+','+$$('#agg').val();
							  	navigationtxt(20,stringad,'ristorantegiornodiv',0);
							break;	
							case 25:
								navigationtxt(6,0,'step'+statostep,0);
								calcolatot();
								mainView.router.back();
								blockPopstate=true;
							break;
							case 26:
								var IDprenextra=$$('#IDprenextradet').val();
								dettagliotavolo(IDprenextra,1);
								
							break;
							case 27:
								navigationtxt(20,0,'ristorantegiornodiv',0);
							break;
							
						}
						
						myApp.hideIndicator();
						
						
					},
				 error: function (data) {
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
		myApp.showIndicator();
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
			timeout:5000,
			success: function (data) {
				//alert(data);
				/*if(agg==2){
					myApp.addNotification({
							message: 'Funzione eseguita con successo',
							hold:1200
						});
				}else{
					myApp.addNotification({
							message: 'Modifica effettuata con successo',
							hold:1200
						});
				}*/
				
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
					case 11:
						stepnew(0,0);
					break;
					case 12:
						tabconto(0);
						calcolatot();
					break;
					
				}
			},
			error: function (){
				myApp.hideIndicator();
			}
	});
}


function  opennot(ID,color,nome){
	
	var codice='';
	var myarray=ID.split(',');
	
	for(var i=0;i<myarray.length;i++)
	{
		var contenuto=$$('#testonot'+myarray[i]).val();
		var tempo=$$('#timenot'+myarray[i]).val();
		var testo='<li class="item-content" style="height:100%"><div class="item-media"><i class="fas fa-circle"></i></div><div class="item-inner" style="height:100%"><div class="item-title">'+atob(contenuto)+'<br/><div style="font-size:10px; margin-top:7px;color:#888;">'+atob(tempo)+'</div></div></div></li>';
		//var testo='<li style="font-size:13px;line-height:20px;">'+atob(contenuto)+' <strong>'+atob(tempo)+'</strong></li>';
		 codice=codice+testo;
	}
	

    var data='<div class="picker-modal" style="height:400px"><div class="toolbar"><div class="toolbar-inner"><div class="left" style="text-transform:uppercase;">'+nome+'</div><div class="right" style="margin-right:15px;"><a href="#" class="close-picker" onclick="myApp.closeModal();"><i class="f7-icons">check</i> </a></div></div></div><div class="picker-modal-inner" style="height:100%;background-color:white; overflow-y:scroll;"><div class="page-content"><div class="list-block"><ul>'+codice+'</ul></div></div><br></div>';	
	myApp.pickerModal(data);
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
					reloadnav=0;
					navigation(4,dato0,2,1);
					//navigationtxt(13,dato0,'centrobenesserediv',6);
				}
			break;
			case 5://ristorante
				if(reloadnav==1){
					reloadnav=0;
					navigation(5,dato0,3,1);
					}
			break;
			case 6: //ospiti
				var txt=$$('#IDprenfunc').val()+',0';
				navigationtxt(2,txt,'contenutop',1);
			break;
			case 7:
				navigation(16,0,0,1);
			break;
			case 8:
				navigationtxt(20,'','ristorantegiornodiv',0);
			break;
			case 9:
				setTimeout(function(){
					var page=mainView.activePage.name;
					switch(page){
						case 'profilo':
							navigation(2,0,0,1);
						break;	
						case 'arrivi':
							navigationtxt(17,0,'arrividiv',17);
						break;
						case 'calendario':
							navigation(2,0,1,1);
						break;
					}
				},500);
			break;
			case 10:
				//ritorno da calendario
				/*setTimeout(function(){
					var page=mainView.activePage.name;
					switch(page){
						case 'profilo':
							notifiche();
						break;	
					}
				},500);*/
			break;
			case 11:
				navigationtxt(24,0,'contenutodiv',0);
			break;
			case 12:
				var IDpren=$$('#IDprenfunc').val();
				navigationtxt(2,IDpren+',0','contenutop',1);
			break;
			
		}
		
		
		
	},300);
}




function ricercaclidetdiv(ID){
	//alert(ID);
		myApp.showIndicator();

	var url=baseurl+versione+"/";
	var url=url+'config/ricercaclidetdiv.php'; 
	//alert(url);
	$$.ajax({
                url: url,
                method: 'POST',
				dataType: 'text',
				cache:false,
				timeout:5000,	
				data: {
					IDinfop:ID
                },
				 error: function (data) {
					//rimuovioverlay();
					 myApp.hideIndicator();
				},

                success: function (data) {
					//alert(data);
					myApp.hideIndicator();
					myApp.pickerModal(data);
					popoverord();
		}
		
	});
	
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



//var inter3=setInterval("notifiche()",300000);

function notifiche(){
	var app='';
	var not='';
	
	var url=baseurl;
	var url=url+versione+'/config/reloadnot.php';
	var query = {};
	$$.ajax({
			url: url,
			method: 'GET',
			timeout:5000,
			dataType: 'text',
			cache:false,
			data: query,
			success: function (data) {
				//alert(data);
				//var data=new String(data);
				var arr=data.split(',');
				app=parseInt(arr['1']);
				not=parseInt(arr['0']);
				if(not>0){
					$$('#badgenot').css('display','block');
				}else{
					
					$$('#badgenot').css('display','none');
				}
				
				if(app>0){
					$$('#badgeapp').css('display','block');
				}else{
					$$('#badgeapp').css('display','none');
				}
				
				$$('#badgenot').html(not);
				$$('#badgeapp').html(app);
				//alert();
				
				
			},
				 error: function (data) {
					 myApp.hideIndicator();
				}
	});
	
	
	
	//navigationtxt(11,0,'promemoria',0);
}



function openesclu(time){
	var url=baseurl;
	var url=url+versione+'/config/esclusivi.php';
	var query = {time:time};
	$$.ajax({
			url: url,
			method: 'GET',
			dataType: 'text',
			timeout:5000,
			cache:false,
			data: query,
			success: function (data) {
				var popupHTML = '<div class="popup" id="contpopup" style="padding:0px;">'+data+'</div>';
				myApp.popup(popupHTML);
				
				//myApp.pickerModal(data);
			},
				 error: function (data) {
					 myApp.hideIndicator();
				}
	});
}

function opennosogg(time){
	var url=baseurl;
	var url=url+versione+'/config/nosoggiorno.php';
	var query = {time:time};
	$$.ajax({
			url: url,
			method: 'GET',
			dataType: 'text',
			timeout:5000,
			cache:false,
			data: query,
			success: function (data) {
				var popupHTML = '<div class="popup" id="contpopup" style="padding:0px;">'+data+'</div>';
				myApp.popup(popupHTML);
				//myApp.pickerModal(data);
			},
				 error: function (data) {
					 myApp.hideIndicator();
				}
	});
}


function opensosp(){
	var IDsotto=$$('#IDsottocentrogiorno').val();;
	var time=$$('#timecentrogiorno').val();;
	//alert('aa');
	
	var url=baseurl;
	var url=url+versione+'/config/sospesi.php';
	var query = {IDsotto:IDsotto,time:time};
	$$.ajax({
			url: url,
			method: 'GET',
			dataType: 'text',
			timeout:5000,
			cache:false,
			data: query,
			success: function (data) {
				//alert(data);
				
				myApp.pickerModal(data);
				popoverord();
				
				/*
				mainView.router.load({
					content: data,
					  animatePages: true
				});*/
				
			},
				 error: function (data) {
					 myApp.hideIndicator();
				}
	});
}


function openann(time){
	var url=baseurl;
	var url=url+versione+'/config/annullate.php';
	var query = {time:time};
	$$.ajax({
			url: url,
			method: 'GET',
			dataType: 'text',
			timeout:5000,
			cache:false,
			data: query,
			success: function (data) {
				
				
				
				var popupHTML = '<div class="popup" id="contpopup" style="padding:0px;">'+data+'</div>';
				myApp.popup(popupHTML);
				//myApp.pickerModal(data);
			},
				 error: function (data) {
					 myApp.hideIndicator();
				}
	});
}

function cambiadatamod(ID,tipo,time,riagg){
	var time=$$('#datamod').val();
	modificaserv(ID,tipo,time,riagg,1);
}

function settime1(val,tipo){
	var ID=$$('#IDmodserv').val();
	/*switch(tipo){
		case 2:*/
			modprenextra(ID,val,1,9,2);
		/*break;
		default:
			modprenextra(ID,val,41,9,2);
		break;
	}*/
	
}


function modificaserv(ID,tipo,time,riagg,popup){
	myApp.showIndicator();//setTimeout(function(){ hidelo(); }, 5500);	
	var url=baseurl;
	//alert(time);
	var url=url+versione+'/config/orarioserv.php';
	var query = {ID:ID,tipo:tipo,time:time,riagg:riagg};
	$$.ajax({
			url: url,
			method: 'GET',
			dataType: 'text',
			cache:false,
			timeout:5000,
			data: query,
			success: function (data) {
				//alert(data);
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
			},
				 error: function (data) {
					 myApp.hideIndicator();
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



function backselect(){
	$$('.tabindietro').css('display','none');
	$$('#buttadddiv').css('display','none');
}

function backselect2(){
	$$('.tabindietro').css('display','none');
	$$('#contbuttonagg').css('display','none');
}




function addservnoteprec(){
	var buttons=new Array();
	buttons.push({
		text: '<div>Aggiungi ad Iniziale</div>',
		onClick: function () {
			addservnote(0)
		}
	});
	buttons.push({
		text: '<div>Aggiungi ad Extra</div>',
		onClick: function () {
			addservnote(1)
		}
	});
	
	 var buttons3 = [{
		text: '<div class="lastbutton-modal">Chiudi</div>'

	}];
		
	var groups = [buttons,buttons3];
  	myApp.actions(groups);
}


function addservnote(tipoadd){
	
	var val=$('#notaserv').val();
	var prezzo=$('#notaprezzo').val();
	//alert(val+'-'prezzo)
	if((val.length>2)&&(!isNaN(prezzo))){
		var url=baseurl+'/config/addservnote.php';
		myApp.showIndicator();
		$$.ajax({
			url: url,
			method: 'POST',
			dataType: 'text',
			timeout:5000,
			cache:false,
			data: {val:val,prezzo:prezzo,tipoadd:tipoadd},
			success: function (data) {
				myApp.hideIndicator();
				myApp.addNotification({
					message: 'Servizio inserito con successo',
					hold:1500
				});
				$('#notaserv').val('');
				$('#notaprezzo').val('');
			},
				 error: function (data) {
					 myApp.hideIndicator();
				}
		});	
	}else{
		alertify.log("E' obbligatorio inserire un testo con un prezzo.<br>Prego Riprovare");
	}
}





function selcof(val){
	
	var arr = val.split('_');

	servizio11=arr['0'];
	limitp=arr['1'];
	tipolim11='8';
	dato=arr['2'];
	//$('#pass22').html('Cofanetto');
	//$('#pass23').html(dato);
	//$('.pass').removeClass('active');
	//$('#pass4').addClass('active');
	
	
	
	
	var url=baseurl;
	var url=url+versione+'/config/step2add.php';
	
	
	//var serviziotxt=$('#nome'+ID).html();
	//$('#ricercaserv').val(serviziotxt);
	$$('.tabindietro').css('display','block');
	
	arrservice=new Array();
	myApp.showIndicator();
	var query = {ID:servizio11,tipolim:tipolim11};
	$$.ajax({
			url: url,
			method: 'GET',
			dataType: 'text',
			timeout:5000,
			cache:false,
			data: query,
			success: function (data) {
				myApp.hideIndicator();
				//alert(data);
				$$('.tabindietro').css('display','block');
				$$('#add2').html(data);
				$$('#buttadddiv').css('display','block');
				myApp.showTab('#add2');
				
						
			},
				 error: function (data) {
					 myApp.hideIndicator();
				}
	});	
	
}




function selaccontoreg(obj){
	var buttons=new Array();
	buttons.push({
		text: '<div>Aggiungi come Acconto</div>',
		onClick: function () {
			var ID=$(obj).attr('id');
			//alert(ID);
			sellreg(ID);
		}
	});
	buttons.push({
		text: '<div>Aggiungi come Servizi</div>',
		onClick: function () {
			selreg(obj);
		}
	});
	
	 var buttons3 = [{
		text: '<div class="lastbutton-modal">Chiudi</div>'
	}];
		
	var groups = [buttons,buttons3];
  	myApp.actions(groups);
}

var limitp=0;
function selreg(obj){
	IDtipo11=$(obj).attr('dir');
	servizio11=$(obj).attr('id');
	var dato =$(obj).attr('lang');
	limitp=$(obj).attr('alt');
	
	tipolim11='7';
	
	
	var url=baseurl;
	var url=url+versione+'/config/step2add.php';
	
	myApp.showIndicator();
	var query = {ID:servizio11,tipolim:tipolim11};
	$$.ajax({
			url: url,
			method: 'GET',
			dataType: 'text',
			cache:false,
			timeout:5000,
			data: query,
			success: function (data) {
				myApp.hideIndicator();
				$$('#add2').html(data);
				$$('.tabindietro').css('display','block');
				$$('#buttadddiv').css('display','block');
				myApp.showTab('#add2');
				
						
			},
				 error: function (data) {
					 myApp.hideIndicator();
				}
	});	
	
}

function sellreg(ID){
	serviziriep='00';
	IDtipo11=0;
	personale11=ID;
	tipolim11='77';
	addservice2(1,0);
//	$('#ricercaserv').val('');
//	$('#listaservizi').html('');
}




function selectservice(ID,tipolim,IDtipo,durata,agg,time){
	var url=baseurl;
	var url=url+versione+'/config/step2add.php';
	extra11=ID;
	durata11=durata;
	tipolim11=tipolim;
	IDtipo11=IDtipo;
	
	var serviziotxt=$('#nome'+ID).html();
	$('#ricercaserv').val(serviziotxt);
	$$('.tabindietro').css('display','block');
	
	arrservice=new Array();
	myApp.showIndicator();
	var query = {ID:ID,tipolim:tipolim,time:time};
	$$.ajax({
			url: url,
			method: 'GET',
			dataType: 'text',
			cache:false,
			timeout:5000,
			data: query,
			success: function (data) {
				myApp.hideIndicator();
				$$('#add2').html(data);
				$$('#buttadddiv').css('display','block');
				
				if(tipolim11==6){
					var totale=0;
					var mioArray=document.getElementsByClassName('cent');
					var lun=mioArray.length; //individuo la lunghezza dell’array 
					for (n=0;n<lun;n++) { //scorro tutti i div del documento
						var prezzo=mioArray.item(n).getAttribute('title');
						var qta=mioArray.item(n).innerHTML;
						var totale=parseFloat(totale)+parseFloat(prezzo*qta);
					}
					
					$$('#totaleadd').html(totale);
				}
				
				
				if(isNaN(agg)){
					myApp.showTab('#add2');
				}
						
			},
				 error: function (data) {
					 myApp.hideIndicator();
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
			timeout:5000,
			data: query,
			success: function (data) {
				switch (data) {
					case "successaddserv":
						 selectservice(extra11,tipolim11,IDtipo11,durata11,1);
					break;
				}
				
						
			},
				 error: function (data) {
					 myApp.hideIndicator();
				}
	});	
}



var durata11='';
var tipolim11='';
var IDtipo11='';
var serviziriep='';
var extra11='';
var personale11='';
var orario11='00:00';

function addservice2(agg,tipoadd){

	/*var note="";
	var prezzo="";//da vedere
	var opzione = 0;
	var tipolim=parseInt(tipolim11);		
	var IDtipo=IDtipo11;	
	var durata=durata11;
	var orario11='00:00';
	var personale11='';*/
	
	
	var note="";
	var prezzo="";//da vedere
	var opzione = 0;
	var tipolim=parseInt(tipolim11);		
	var IDtipo=IDtipo11;	
	//IDserv=servizio11;	
	var durata=durata11;
	
	
	nump=1;
	
	reloadnav=1;
	
	switch(tipolim){
		case 6:
			serviziriep='';
			var mioArray=document.getElementsByClassName('cent');
			var lun=mioArray.length; //individuo la lunghezza dell’array 
			for (n=0;n<lun;n++) { //scorro tutti i div del documento
				var serv=mioArray.item(n).getAttribute('alt');
				var time=mioArray.item(n).getAttribute('dir');
				var qta=mioArray.item(n).innerHTML;
				serviziriep =serviziriep +serv+'_'+time+'_'+qta+'_0/////';		
			}	
		break;
		case 7:
		case 8:
			var arr=serviziriep.split('/////');
			var num=arr.length-1;
			if(num>=limitp){
				nump=1;
			}else{
				nump=0;
			}
		break;
		case 77:
			tipolim=7;
			nump=1;
		break;	 
	}
	
	
if((serviziriep.length>0)&&(nump==1)){
	dataString='arrins='+serviziriep+"&orario=" + orario11  +"&note=" + note+"&IDtipo=" + IDtipo+"&personale=" + personale11 +"&prezzo=" + prezzo +"&durata=" + durata+"&tipolim=" + tipolim+"&tipoadd=" + tipoadd
	
	//alert(dataString);
	
	var url=baseurl;
	var url=url+'config/addservice2.php';
	myApp.showIndicator();
	$$.ajax({
			url: url,
			method: 'POST',
			dataType: 'text',
			timeout:5000,
			cache:false,
			data: dataString,
			success: function (data) {
				//alert(data);
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
						
			},
				 error: function (data) {
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


function cercaservizio(val,tipo){
	var url=baseurl;
	var url=url+versione+'/config/cercaservizi.php';
	var query = {val:val,tipo:tipo};
	$$.ajax({
			url: url,
			method: 'GET',
			dataType: 'text',
			cache:false,
			timeout:5000,
			data: query,
			success: function (data) {
				$$('#listaservizi').html(data);
				myApp.hideIndicator();
			},
				 error: function (data) {
					 myApp.hideIndicator();
				}
	});
}


function addserv(IDpren,popup){
	//alert(IDpren);
	var buttons=new Array();
	//alert(posadd);
	var functxt = ["","<div>Servizio/Prodotto</div>", "<div>Appunto</div>", "<div>Voucher</div>","<div>Cofanetto</div>"];
	
	var arrp=posadd.split(',');
	arrp.sort();
	var lun=arrp.length;
	
	if(lun==1){
		functxt[2]='<div>Servizio</div>';
	}
	
	
	
	for(i=0;i<lun;i++){
		var j=parseInt(arrp[i]);
		switch(j){
			case 1:
				buttons.push({
					text: functxt[1],
					onClick: function () {
						addservice(IDpren,popup,1);
					}
				});
			break;
			case 2:
				buttons.push({
					text: functxt[2],
					onClick: function () {
						addservice(IDpren,popup,2);
					}
				});
			break;
			case 3:
				buttons.push({
					text: functxt[3],
					onClick: function () {
						addservice(IDpren,popup,3);
					}
				});
			break;
			case 4:
				buttons.push({
					text: functxt[4],
					onClick: function () {
						addservice(IDpren,popup,4);
					}
				});
			break;
		}
	}
	
	 var buttons3 = [{
		text: '<div class="lastbutton-modal">Chiudi</div>'
	}];
		
	var groups = [buttons,buttons3];
  	myApp.actions(groups);
	
}



function addservice(IDpren,popup,tipo){
	serviziriep='';
	myApp.showIndicator();//setTimeout(function(){ hidelo(); }, 5500);	
	var url=baseurl;
	var url=url+versione+'/config/addserv.php';
	var query = {IDpren:IDpren,tipo:tipo};
	$$.ajax({
			url: url,
			method: 'GET',
			dataType: 'text',
			cache:false,
			timeout:5000,
			data: query,
			success: function (data) {
				//alert(data);
				//clearTimeout();
				
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
				
				
				 myApp.hideIndicator();
				
						
			},
				 error: function (data) {
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
	
	myApp.showIndicator();//setTimeout(function(){ hidelo(); }, 5500);	
	var url=baseurl;
	var url=url+versione+'/config/selprenot.php';
	var query = {time:time,IDsotto:IDsotto};
	$$.ajax({
			url: url,
			method: 'GET',
			dataType: 'text',
			cache:false,
			timeout:5000,
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
				
						
			},
				 error: function (data) {
					 myApp.hideIndicator();
				}
		
	});

}	

function cercaprenot(val){
	var url=baseurl;
	var url=url+versione+'/config/cercaprenot.php';
	var query = {val:val};
	$$.ajax({
			url: url,
			method: 'GET',
			dataType: 'text',
			cache:false,
			timeout:5000,
			data: query,
			success: function (data) {
				$$('#listaprenot').html(data);
				 myApp.hideIndicator();
			},
				 error: function (data) {
					 myApp.hideIndicator();
				}
		
	});
}



function setdom(IDdom,manuale,popup){
	
	myApp.showIndicator();//setTimeout(function(){ hidelo(); }, 5500);	
	var url=baseurl;
	var url=url+versione+'/config/setdomotica.php';
	var query = {IDdom:IDdom,manuale:manuale};
	$$.ajax({
			url: url,
			method: 'GET',
			dataType: 'text',
			cache:false,
		    timeout:5000,
			data: query,
		    error: function (data) {
					//rimuovioverlay();
					 myApp.hideIndicator();
				},
			success: function (data) {
				myApp.hideIndicator();
				myApp.pickerModal(data);
				popoverord();
			}
	});

}	



function addprodotto(IDpren,popup){
	
	myApp.showIndicator();//setTimeout(function(){ hidelo(); }, 5500);	
	var url=baseurl;
	var url=url+versione+'/config/addprodotto.php';
	var query = {IDpren:IDpren};
	$$.ajax({
			url: url,
			method: 'GET',
			dataType: 'text',
			cache:false,
			timeout:5000,
			data: query,
			success: function (data) {
				//alert(data);
				//clearTimeout();
				
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
			},
				 error: function (data) {
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
		if(num<0){num=0;}
		
		nprod=nprod-1;
		if(nprod<0){nprod=0;}
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
	
	//alert('aaa');
	var servizi='';
	
	var mioArray=document.getElementsByClassName('buttaddin2 selected');
	var lun=mioArray.length; //individuo la lunghezza dell’array 
	for (n=0;n<lun;n++) { //scorro tutti i div del documento
		var ID=mioArray.item(n).getAttribute('alt');
		var qta=mioArray.item(n).innerHTML;
		servizi =servizi +ID+'_'+qta+'/////';		
	}
	//alert(servizi);
	var IDsotto=$$('#IDsottoatt').val();
	$$('#IDsotto'+IDsotto).val(servizi);
	
	
	
}




function addprod2(IDprenextra){
	
	servizi='';
	var mioArray=document.getElementsByClassName('inputsottosel');
	var lun=mioArray.length; //individuo la lunghezza dell’array 
	for (n=0;n<lun;n++) { //scorro tutti i div del documento
		//var ID=mioArray.item(n).getAttribute('alt');
		//var qta=mioArray.item(n).innerHTML;
		servizi =servizi +mioArray.item(n).value;		
	}
	//alert(servizi);
	if(servizi.length>0){
		modprenextra(IDprenextra,servizi,29,9,5);
	}else{
		myApp.closeModal();
		myApp.addNotification({
			message: 'Non è stato selezionato nessun prodotto',
			hold:1700
		});
	}
		
}



function msgboxelimina(id,tipo,altro,id2,url){
	var cosa;
	var agg="";
	myApp.closeModal('.popover-menu');
	var arrtipiel=new Array("","la prenotazione","la scheda numero "+id,"il servizio","l'album","la foto","il parametro","l'orario","8","9","la mansione","il soggetto dal personale","il messaggio Newsletter","la fascia oraria","il cliente dalla prenotazione","la nota","la Fattura/Ricevuta","il prodotto dalla Fattura/Ricevuta","l'acconto selezionato","il Fornitore","la Vendita","il pagamento","l'Agenzia","la Ricevuta/Fattura","i servizi selezionati","l'abbuono","la limitazione? Tutti le agenzie con la stessa limitazione subiranno lo stessa elimazione. Continuare","il cofanetto regalo","il voucher","il servizio","il documento","la spedizione","il servizio","il prodotto dal tavolo",'34','il tavolo');
	
	
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
					//alert(altro);
					//alert(id);
					modprenextra(altro,id,30,9,6);
				break;
				case 35:
					modprenextra(id,0,37,9,27);
				break;
			
			
			}
			
		});
	
	
	
	
}



function elimina(id,tipo,altro,agg,url){

	myApp.showIndicator();//setTimeout(function(){ hidelo(); }, 5500);	
	var url=baseurl;
	var url=url+'config/elimina.php';
	var query = {ID:id,tipo:tipo,altro:altro};
	
	$$.ajax({
			url: url,
			method: 'POST',
			dataType: 'text',
			cache:false,
			timeout:5000,
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
						
			},
				 error: function (data) {
					 myApp.hideIndicator();
				}
	});
}


function detcli(ID){
	//alert(ID);
	myApp.showIndicator();//setTimeout(function(){ hidelo(); }, 5500);	
	var url=baseurl;
	var url=url+versione+'/config/detcli.php';
	var query = {ID:ID};
	$$.ajax({
			url: url,
			method: 'GET',
			dataType: 'text',
			cache:false,
			timeout:5000,
			data: query,
			success: function (data) {
				//myApp.closeModal('.popover-menu');
				myApp.hideIndicator();	
				$$('#pannellodx').html(data);
				myApp.openPanel('right');
					
			},
			error: function (data) {
				myApp.hideIndicator();
			}
	});
}

function detappunto(ID){
	//alert(ID);
	myApp.showIndicator();//setTimeout(function(){ hidelo(); }, 5500);	
	var url=baseurl;
	var url=url+versione+'/config/detappunto.php';
	var query = {ID:ID};
	$$.ajax({
			url: url,
			method: 'GET',
			dataType: 'text',
			cache:false,
			timeout:5000,
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
			},
				 error: function (data) {
					 myApp.hideIndicator();
				}
	});
}

function salvaappunto(){
			var appunto='';
			var note=$$('#noteappunto').val();
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
	myApp.confirm('Vuoi davvero uscire da Scidoo', function () {
		notifpush(-1);
		 
		var url=baseurl;
		var url=url+'config/logout.php';
		var query = {ID:'0'};
		
		myApp.showIndicator();
		setTimeout(function(){
			
			$$.ajax({
				url: url,
				method: 'GET',
				dataType: 'text',
				cache:false,
				timeout:8000,
				data: query,
				success: function (data) {
					 myApp.hideIndicator();
					var url2=baseurl;
					//var url2=url2+versione+'/indexexit.html';
					// alert(url2);
					var url2=url2+versione+'/indexmobile.html';
				
					mainView.router.load({
					 	url: url2,
						animatePages: true,
						reload:true,
						force:true
					});
					
					
					//mainView.router.loadPage(url2)
				
					
					
					//mainView.history = []; 
					
					window.localStorage.setItem("IDcode", '0');
					setTimeout(function (){
						vislogin();
						//mainView.history = [];
						//disableBack();
						azzerastoria();
					},600);
				},
				 error: function (data) {
					 myApp.hideIndicator();
				}
			});
			
			
			
		});
		
	});
}



//profilo



function menuprofilo(){
	
	var h = window.innerHeight;
	myApp.showIndicator();//setTimeout(function(){ hidelo(); }, 5500);	
	var url=baseurl;
	var url=url+versione+'/config/profilo/menu.php';
	var query = {h:h};
	$$.ajax({
			url: url,
			method: 'GET',
			dataType: 'text',
			cache:false,
			timeout:5000,
			data: query,
			success: function (data) {
					
				$$('#pannellosx').html(data);
				myApp.hideIndicator();
			},
				 error: function (data) {
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
								url: "https://www.scidoo.com/"+versione+"/img/homepoi.svg", // url
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
	var url=url+versione+'/config/detluogo.php';
	var query = {ID:ID};
	$$.ajax({
			url: url,
			method: 'GET',
			dataType: 'text',
			cache:false,
			timeout:5000,
			data: query,
			success: function (data) {
				//clearTimeout();
				myApp.hideIndicator();
				
				var popupHTML = '<div class="popup" style="padding:0px;">'+data+'</div>';
				myApp.popup(popupHTML);
				
				
			},
				 error: function (data) {
					 myApp.hideIndicator();
				}
	});
}

function controllocarta(){
	myApp.showIndicator();//setTimeout(function(){ hidelo(); }, 5500);	
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
				timeout:5000,
				cache:false,
				data: {number:number,meses:meses,annos:annos},
				success: function (data) {
				//	clearTimeout();
					myApp.hideIndicator();
					var num=data.indexOf("error");
					if(num==-1){
						var txt=number+'_'+annos+'_'+meses+'_'+intes;
						var IDpren=$$('#IDprenfunc').val();
						modprofilo(IDpren,txt,3,10,2);
					}
				},
				 error: function (data) {
					 myApp.hideIndicator();
				}
		});
	}	
}


function prenotaora(IDserv,time,popup){
	myApp.showIndicator();//setTimeout(function(){ hidelo(); }, 5500);	
	
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
					timeout:5000,
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
			 },
				 error: function (data) {
					 myApp.hideIndicator();
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
	myApp.showIndicator();//setTimeout(function(){ hidelo(); }, 5500);	
	navigation(25,ID+','+time,0,1);
}


function mipiace(ID,tipoobj,agg){
	
	//var val=ID+'_'+tipoobj;
	
	myApp.showIndicator();//setTimeout(function(){ hidelo(); }, 5500);	
	var url=baseurl;
	var url=url+'config/gestioneprofilo.php';
	var query = {ID:ID,val:tipoobj,tipo:8,val2:0};
	$$.ajax({
			url: url,
			method: 'POST',
			dataType: 'text',
			cache:false,
			timeout:5000,
			data: query,
			success: function (data) {
				//alert(data);

				myApp.hideIndicator();
				//clearTimeout();
				
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
				
						
			},
				 error: function (data) {
					 myApp.hideIndicator();
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
					text: '<div>Apri Dettaglio</div>',
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
					text: '<div>Modifica Prezzo</div>',
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
					text: '<div>Modifica Prezzo</div>',
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
					text: '<div>Elimina</div>',
					color:'red',
					onClick: function () {
						msgboxelimina(ID,3,0,1,1);
					}
				}); 
			}
		}
		 var buttons3 = [
			{
				text: '<div class="lastbutton-modal">Chiudi</div>'
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
		var url=url+versione+'/config/infinitedx.php';
		var query = {};
		$$.ajax({
				url: url,
				method: 'GET',
				dataType: 'text',
				cache:false,
				timeout:5000,
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
					
				},
				 error: function (data) {
					 myApp.hideIndicator();
				}
		});
	}
}



function detinfop(ID,tel,cell,email){
		
		var buttons=new Array();
	
			buttons.push(
					{
					text: '<div>Apri Dettaglio</div>',
					onClick: function () {
						navigation(24,ID,0,0);
					}
				}); 	
		
		
		if(tel.length>2){
			buttons.push(
					{
					text: '<div>Chiama '+tel+'</div>',
					onClick: function () {
						location.href="tel:"+tel;
					}
				}); 
		}
		
		if(cell.length>2){
			buttons.push(
					{
					text: '<div >Chiama '+cell+'</div>',
					onClick: function () {
						location.href="tel:"+cell;
					}
				}); 
		}
		
		if(email.length>2){
			buttons.push(
					{
					text: '<div>Scrivi a '+email+'</div>',
					onClick: function () {
						location.href="mailto:"+email;
					}
				}); 
		}
		
		
		 var buttons3 = [
			{
				text: '<div class="lastbutton-modal">Chiudi</div>'
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
				text: '<div class="lastbutton-modal">Chiudi</div>'
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
			navigation(0,'',0,1);//
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
	if((titolo.length>5)&&(recens.length>10)){
		modprofilo(0,val,9,10,6);
	}else{
		myApp.alert("E' obbligatorio inserire un titolo ed un testo alla recensione. Prego riprovare.");
	}
	
}
function azionevideo(id,stato){
		
/*var statopulizia = $$("#stati").val();
var arraystato = statopulizia.split(',');
var lun= arraystato.length;*/ 
	
//perche' scorri due volte questo array se lo hai gia' fatto in PHP?	
	
var buttons=new Array();
/*for(var i=0; i<lun;i++){
	
	var arraystato2  = arraystato[i].split('_');
	
	
	 if(stato!=arraystato2['1'])
		 {*/
		    var valorebtn=$$('#valorebtn'+id).val(); //come facevi prima ad identifica un valorebtn senza id? Ne avevi uno per ogni appartamento! sei stato fortunato che ne e' attivo uno - stampava due volte perche' lun e' uguale a 2
			    valorebtn=atob(valorebtn);
			    eval(valorebtn);
			    
/*	}
	
}*/

	
	
	var buttons2 = [
					{
					text: '<div>Apri Dettaglio</div>',
					
					onClick: function () {
				    navigation(23,id,0,0)
					}
				}];
		
		
		 var buttons3 = [
			{
				text: '<div class="lastbutton-modal">Chiudi</div>'
				
			}
		];
		
		 var groups = [buttons,buttons2,buttons3];
  	     myApp.actions(groups);

}

function notificaprova(){
	myApp.addNotification({
		message: "notifica ok,notifica ok,notifica ok",
		hold:222200
	});
}



var myCalendar2;
function calen2(id,agg,rel){
	//    
	myCalendar2 = myApp.calendar({
	header: false,	
    footer: false,
	closeByOutsideClick:false,
	input: '#datacalen',
    dateFormat: 'dd/mm/yyyy',		
	onChange:function (p, values, displayValues){
		var tempotime = $$('#tempotime').val();
		var data = Date.parse(values);
		var tempo= data/1000;
		var datistr='';
		
					if(id==5){//ristorante schermata
						var viscontenuto=$$('#contenutodiv').val();
						datistr=tempo+','+viscontenuto;
					}else{
						datistr=tempo;
					}
		
		if(tempotime==0){
			navigation(id,datistr,agg,rel);
			myCalendar2.close();
			$$('#tempotime').val(tempo);
		}else{
			if(tempotime!=tempo){
				navigation(id,datistr,agg,rel);
				myCalendar2.close();
				$$('#tempotime').val(tempo);
			}
		}
		//myCalendar2.destroy();
		}
	});

}

function chiudimodal(){
	myApp.closeModal();
	myApp.closePanel();
	rimuovioverlay();
	
}

function pulsservizio(agg){
	
	var buttons=new Array();
	

			buttons.push(
					{
					text: '<div>Aggiungi ad Iniziale</div>',
					onClick: function () {
						addservice2(agg,0);
						
					}
				}); 
	 
	         buttons.push(
					{
					text: '<div>Aggiungi Extra</div>',
					onClick: function () {
						addservice2(agg,1);
						
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
 var mySwiper = myApp.swiper('.swiper-container', {
    pagination:'.swiper-pagination',
    spaceBetween: 100 // 100px between slides
  });


function vistasalelib(time,IDsottotip,tavolo){
	
	var buttons=new Array();
	

			buttons.push(
					{
					text: '<div>Alloca prenotazione</div>',
					onClick: function () {
						var agg=1;
						stampapren(time,IDsottotip,tavolo,agg);
					}
				}); 
	 
	         buttons.push(
					{
					text: '<div>Nuova prenotazione</div>',
					onClick: function () {
						var stringa=time+','+IDsottotip+','+tavolo;
						navigation(34,stringa,'nuovotavolo',0);
						//nuovotavolo(time,IDsottotip);
						//notificaprova();
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
function tavoloprenotato(IDprenextra){
	
	var buttons=new Array();
	var buttons2=new Array();
	
			buttons.push(
					{
					text: '<div>Orario</div>',
					onClick: function () {
						var agg=1;
						orariotavolo(IDprenextra,agg);
					}
				}); 	
	
			buttons2.push(
					{
					text: '<div>Aggiungi Piatti</div>',
						onClick: function () {
						aggiungipiatti(IDprenextra);
						
					}
				}); 
	
	
	
			buttons2.push(
					{
					text: '<div>Ordinazione</div>',
					onClick: function () {
						tavoloordinazione(IDprenextra);
					}
				}); 
	
	
			 buttons.push(
					{
					text: '<div >Dettaglio Tavolo</div>',
					onClick: function () {
					dettagliotavolo(IDprenextra);
					}
				}); 
	 
	         buttons.push(
					{
					text: '<div>Libera</div>',
					onClick: function () {
					var agg=22;
			        liberatavolo(IDprenextra,agg);
					}
				}); 
	
	
	 var buttons3 = [
			{
				text: '<div class="lastbutton-modal">Chiudi</div>'
			}
		];
		
		 var groups = [buttons2,buttons, buttons3];
  	     myApp.actions(groups);

}

function elencotavoli(IDprenextra,elimina,IDprenextra2){
	var buttons=new Array();
	var buttons2=new Array();
	var buttons4=new Array();
//alert(IDprenextra);
			 buttons4.push(
					{
					text: '<div>Alloca Tavolo</div>',
					onClick: function () {
						var agg=0;
						popovertavoli(IDprenextra,agg);
					}
						
				}); 
	 
	         buttons4.push(
					{
					text: '<div>Orario</div>',
					onClick: function () {
						orariotavolo(IDprenextra);
					}
				}); 
	
			 buttons.push(
					{
					text: '<div>Aggiungi Piatti</div>',
					onClick: function () {
						aggiungipiatti(IDprenextra);
						
					}
				}); 
	 
	         buttons.push(
					{
					text: '<div>Ordinazione</div>',
					onClick: function () {
						tavoloordinazione(IDprenextra);
					}
				}); 
	
			 buttons4.push(
					{
					text: '<div>Dettaglio Tavolo</div>',
					onClick: function () {
						dettagliotavolo(IDprenextra);
					}
				}); 
	
	//if(elimina==1){
	//alert(IDprenextra2);
		buttons2.push({
			text: '<div style="color:red">Annulla Tavolo</div>',
			onClick: function () {
				msgboxelimina(IDprenextra2,35,0,0,0);
						
			}
		}); 
	//}
	
	 var buttons3 = [{
		text: '<div class="lastbutton-modal">Chiudi</div>'
	}];
		
	var groups = [buttons,buttons4,buttons2,buttons3];
  	myApp.actions(groups);
	
}


function presenzaospiti(ID,modprezzo){
	var buttons=new Array();
	var buttons2=new Array();

			 buttons.push(
					{
					text: '<div>Presente e Paga</div>',
					/*color:'green',*/
					onClick: function () {
						modprenextra(1,ID,15,9,26);
					}
						
				}); 
	 
	         buttons.push(
					{
					text: '<div>Assente e Paga</div>',
					/*color:'orange',*/
					onClick: function () {
						modprenextra(0,ID,15,9,26);
					}
				}); 
	
			 buttons.push(
					{
					text: '<div>Assente e Non Paga</div>',
					/*color:'red',*/
					onClick: function () {
						modprenextra(-1,ID,15,9,26);
					}
				}); 
	        
	if(modprezzo==1){
		buttons2.push({
			text: '<div>Modifica Prezzo</div>',
			onClick: function () {
				
				myApp.prompt('Inserisci prezzo:', function (value) {
					if(!isNaN(value)){
						value=convertnumb(value,0);
						//alert(value+'-'+ID);
						modprenextra(value,ID,18,9,26);
					}else{
						myApp.alert('Devi inserire un numero. Prego riprovare');
					}
				});
						
			}
		}); 
	}
	
	 var buttons3 = [{
		text: '<div class="lastbutton-modal">Chiudi</div>'
	}];
		
	var groups = [buttons,buttons2,buttons3];
  	myApp.actions(groups);
	
}


function popovertavoli(IDprenextra,agg){
		myApp.showIndicator();

	var url=baseurl+versione+"/";
	var url=url+'config/allocatavolo.php'; 
	$$.ajax({
                url: url,
                method: 'POST',
				dataType: 'text',
				cache:false,
				timeout:5000,
				data: {
					IDprenextra:IDprenextra,
					agg:agg
                },

				 error: function (data) {
					//rimuovioverlay();
					 myApp.hideIndicator();
				},

                success: function (data) {
				
	myApp.hideIndicator();
					myApp.pickerModal(data);
		}
		
	});
	
}


function orariotavolo(IDprenextra,agg){
		myApp.showIndicator();

	var url=baseurl+versione+"/";
	var url=url+'config/orariotavolo.php'; 
	$$.ajax({
                url: url,
                method: 'POST',
				dataType: 'text',
				cache:false,
				timeout:5000,
				data: {
					IDprenextra:IDprenextra,
					agg:agg
			
                },

				 error: function (data) {
					//rimuovioverlay();
					 myApp.hideIndicator();
				},

                success: function (data) {
	myApp.hideIndicator();
					myApp.pickerModal(data);
		}
		
	});
	
}

function aggiungipiatti(IDprenextra,popup){
		myApp.showIndicator();

	var url=baseurl+versione+"/";
	var url=url+'config/aggiungipiatti.php'; 
	$$.ajax({
                url: url,
                method: 'POST',
				dataType: 'text',
				cache:false,
				timeout:5000,
				data: {
					IDprenextra:IDprenextra,
					json:1,
					callback:'?'
                },

				 error: function (data) {
					//rimuovioverlay();
					 myApp.hideIndicator();
				},

                success: function (data) {
				//alert(data);
				//clearTimeout();
				
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

function tavoloordinazione(IDprenextra){
		myApp.showIndicator();

	var url=baseurl+versione+"/";
	var url=url+'config/tavoloordinazione.php'; 
	$$.ajax({
                url: url,
                method: 'GET',
				dataType: 'text',
				cache:false,
				timeout:5000,
				data: {
					dato0:IDprenextra
                },

				 error: function (data) {
					//rimuovioverlay();
					 myApp.hideIndicator();
				},

                success: function (data) {
	
	myApp.hideIndicator();
					myApp.pickerModal(data);
					popoverord();
		}
		
	});
}

function dettagliotavolo(IDprenextra,vis){
	myApp.showIndicator();
	var url=baseurl+versione+"/";
	var url=url+'config/dettagliotavolo.php'; 
	$$.ajax({
                url: url,
                method: 'POST',
				dataType: 'text',
				cache:false,
				timeout:5000,
				data: {
					IDprenextra:IDprenextra,
					vis:vis
                },

				 error: function (data) {
					//rimuovioverlay();
					 myApp.hideIndicator();
				},
                success: function (data) {
			
					myApp.hideIndicator();
					//alert(vis);
					if(vis==1){
						reloadnav=1;
						$$('#txtpersdet').html(data);
						navigationtxt(20,0,'ristorantegiornodiv',0);
						
					}else{
						myApp.pickerModal(data);
					}
					
		}
		
	});
}


function aggiungibott(){
	var buttons=new Array();

		    var buttoncat=$$('#button').val();
			    buttoncat=atob(buttoncat);
			    eval(buttoncat);

	  var buttons3 = [
			{
				text: '<div class="lastbutton-modal">Chiudi</div>',
			}
		];
		
		 var groups = [buttons, buttons3];
  	     myApp.actions(groups);
	
}

function cambiavalore(nome){
	
	var simplybutton=$$('#simplybutton');
	 simplybutton.html(nome);
}


function popoverord(){
	
	var alt=$(window).outerHeight();
	var popoverord=$$('#popoverord');
	var altf=alt -((alt *20)/100);
	popoverord.css("height", altf+"px");
	
}

function buttonristoact(bottprem){
	var button1=$$('#tavoli');
	var button2=$$('#sale');
	switch(bottprem)
	{
		case 1: 
				button1.addClass("active");
				button2.removeClass("active");
		break;
		case 2:
				button1.removeClass("active");
				button2.addClass("active");
		break;
			
	}
}

function tavolisala(IDprenextra){
	var buttons=new Array();
	
	     buttons.push(
					{
					text: '<div>Modifica orario</div>',
					onClick: function () {
						var agg=0;
						orariotavolo(IDprenextra,agg);
					}
				}); 
	
			 buttons.push(
					{
					text: '<div>Aggiungi piatti</div>',
					onClick: function () {
						aggiungipiatti(IDprenextra);
						
					}
				}); 
	 
	         buttons.push(
					{
					text: '<div>Ordinazione</div>',
					onClick: function () {
						tavoloordinazione(IDprenextra);
					}
				}); 
	
			 buttons.push(
					{
					text: '<div>Dettaglio tavolo</div>',
					onClick: function () {
						dettagliotavolo(IDprenextra);
					}
				}); 
	
	          buttons.push(
			 {
				text: '<div>Libera</div>',
				onClick: function (){
					var agg=23;
					liberatavolo(IDprenextra,agg);
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


function liberatavolo(IDprenextra,agg){
	myApp.closeModal('.popover-menu');
	myApp.confirm('Vuoi davvero liberare il tavolo ?<br><br>', function () 
	  {
		 modprenextra(IDprenextra,-1,36,9,agg);
	 }); 
	
}


function stampapren(time,IDsottotip,tavolo,agg){
	//tempo e idsottotip
		myApp.showIndicator();

	var url=baseurl+versione+"/";
	var url=url+'config/prenotazionilinea.php'; 
	$$.ajax({
                url: url,
                method: 'POST',
				dataType: 'text',
				cache:false,
				timeout:5000,	
				data: {
					time:time,
					IDsottotip:IDsottotip,
					tavolo:tavolo,
					agg:agg
                },

				 error: function (data) {
					//rimuovioverlay();
					 myApp.hideIndicator();
				},

                success: function (data) {
	myApp.hideIndicator();
					myApp.pickerModal(data);
					popoverord();
		}
		
	});
	
}


function nuovotavolo(time,IDsottotip,popup){
	//tempo e idsottotip
		myApp.showIndicator();

	var url=baseurl+versione+"/";
	var url=url+'config/nuovotavolo.php'; 
	$$.ajax({
                url: url,
                method: 'POST',
				dataType: 'text',
				cache:false,
				timeout:5000,
				data: {
					time:time,
					IDsottotip:IDsottotip
                },
				 error: function (data) {
					//rimuovioverlay();
					 myApp.hideIndicator();
				},
                success: function (data) {
					//clearTimeout();
				
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







function ricercacli(){
		myApp.showIndicator();

	var url=baseurl+versione+"/";
	var url=url+'config/ricercacli.php'; 
	$$.ajax({
                url: url,
                method: 'POST',
				dataType: 'text',
				cache:false,
				timeout:5000,
				data: {
				
                },

				 error: function (data) {
					//rimuovioverlay();
					 myApp.hideIndicator();
				},
                success: function (data) {
	myApp.hideIndicator();
					myApp.pickerModal(data);
					var alt=$(window).outerHeight();
					var popoverord=$$('#popoverord');
					var altf=alt -((alt *10)/100);
					popoverord.css("height", altf+"px");
		}
		
	});
}

function nuovtavtasti(time,IDsottotip){
	var buttons=new Array();
	var simplybutton=$$('#tipologia');

			buttons.push(
					{
					text: '<div>Ricerca prenotazioni</div>',
					onClick: function () {
	                    simplybutton.html('Ricerca Prenotazione');
						ricercaprenotazione(time,IDsottotip);
					}
				}); 
	 
	         buttons.push(
					{
					text: '<div>Ricerca cliente</div>',
					onClick: function () {
						simplybutton.html('Ricerca Cliente');
						ricercacli();
					}
				}); 
		
			buttons.push(
					{
					text: '<div>Nuovo cliente</div>',
					onClick: function () {
					simplybutton.html('Nuovo Cliente');
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


function ricercaprenotazione(time,IDsottotip){
		myApp.showIndicator();

	var url=baseurl+versione+"/";
	var url=url+'config/ricercaprenotazionetav.php'; 
	$$.ajax({
                url: url,
                method: 'POST',
				dataType: 'text',
				timeout:5000,
				cache:false,
				data: {
					time:time,
					IDsottotip:IDsottotip
					
                },
				 error: function (data) {
					//rimuovioverlay();
					 myApp.hideIndicator();
				},
                success: function (data) {
					//alert(data);	
					myApp.hideIndicator();
					myApp.pickerModal(data);
					popoverord();
					
		}
		
	});
	
}


function selezionaserv(IDsottotip,time){
		myApp.showIndicator();

	var url=baseurl+versione+"/";
	var url=url+'config/selezionaserv.php'; 
	$$.ajax({
                url: url,
                method: 'POST',
				dataType: 'text',
				timeout:5000,
				cache:false,
				data: {
					IDsottotip:IDsottotip,
					time:time
                },
				 error: function (data) {
					//rimuovioverlay();
					 myApp.hideIndicator();
				},
				
                success: function (data) {
						
					myApp.hideIndicator();
					myApp.pickerModal(data);
					popoverord();
					
		}
		
	});
	
}


function prenotaztavolointo(){
	var idclienti='';

	var mioArray=$('.ricercacheckbox');
	var lun=mioArray.length; //individuo la lunghezza dellâ€™array 
	for (n=0;n<lun;n++) { //scorro tutti i div del documento
		if (mioArray[n].checked){
			idclienti=idclienti + mioArray[n].getAttribute('alt')+',';
		}
	}
	if(idclienti.length>0){
		prenotaztavolo(1,idclienti);
	}
	
	
}


function prenotaztavolo(tipo,ID){
	
	switch(tipo){
		case 1:
			/*$$('#personemax').val(0);
			$$('#euro').html(0);
			var id="";
	        var id2="";
			var npers=0;
	        var idclienti="";
	        var nopers=0;
			var mioArray=$$('.ricercacheckbox');
			var lun=mioArray.length; //individuo la lunghezza dellâ€™array 
			for (n=0;n<lun;n++) { //scorro tutti i div del documento
				if (mioArray[n].checked==true){
					id2=$$(mioArray[n]).attr('id');
					id=id + $$(mioArray[n]).attr('id')+',';
					npers=(parseInt($$('#npers'+id2).val()) + npers);
					idclienti=idclienti + $$('#client'+id2).val()+',';
				}else{
				   nopers++;
				}
			}
			if(nopers==lun){//se nulla e' selezionato fai nuovo cliente
				tipo=3;
			}*/
			var idclienti="";
			npers=0;
			vedicheckbox(0);
			
		break;
		case 2:
			$$('#personemax').val(0);
			$$('#euro').html(0);
			//caso premo ricerca
		break;
		case 3:
			$$('#personemax').val(0);
			$$('#euro').html(0);
			tipo=tipo;
		break;
		
	}

	
	$$('#personemax').val(npers);
	myApp.showIndicator();
	var url=baseurl+versione+"/";
	var url=url+'config/prenottavolo.php'; 
	$$.ajax({
                url: url,
                method: 'POST',
				dataType: 'text',
				cache:false,
		        timeout:5000,
				data: {
					ID:ID,
					npers:npers,
					tipo:tipo,
					IDclienti:idclienti
					
                },
				 error: function (data) {
					//rimuovioverlay();
					 myApp.hideIndicator();
				},
			
                success: function (data) {
					//alert(data);		
					myApp.hideIndicator();
					$$('#dettagliocliente').html(data);
				}
		
	});

	
}



function salvatavolo(){
	myApp.showIndicator();
	
	var IDcliente=$('#IDnuovotav').val();
	var tipopersnuovo=$('#tipopersnuovo').val();
	var ok=1;
	
	//alert(tipopersnuovo);
	prezzirestr='';
	var dati='';
	if(IDcliente==0){
		var nome=$('#nome').val()+' '+$('#cognome').val();
		var prefisso=$('#prefisso').val();
		var tel=$('#telefono').val();
		var email=$('#email').val();
		if(nome.length==0){
			ok=0;
		}else{
			dati=nome+'_'+prefisso+'_'+tel+'_'+email;
		}
	}
	var IDrestr="";
	var prezzirestr='';
	
	if(tipopersnuovo==1){
		
		//estrae le persone selezionate
		
		var IDcliente=$('#IDclientipren').val()+',';;
		
		//alert(IDrestr);
		
		if(IDcliente.length<2){
			ok=0;
		}
		
		//alert(IDrestr);
	}else{
		var IDcont=document.getElementsByClassName('inputrestr');
		var lung=IDcont.length;
				
		var IDrestr="";
		for(i=0;i<lung;i++){
			var ID=IDcont[i].id;
			var val=$('#'+ID).val();
			if(val>0){
				var ID=$('#'+ID).attr('alt');
				
				IDrestr = IDrestr +ID+'_'+val+',';
				var prezzointo=$('#prezzoschermo'+ID).html();
				if(prezzointo!='undefined'){
					prezzirestr = prezzirestr +ID+'_'+prezzointo+'//';
				}
			}
		}
		
		
		if(IDrestr.length==0){
			ok=0;
		}
		//alert(IDrestr);
	}
	
	
	var serv=$('#idserv').val();
	var data=$('#data').val();
	var orario=$('#timeserv').val();
	var prezzo=$('#euro').html();
	var note=$('#note').val();
	
	//alert(prezzo);
	
	if((serv==0)||(data.length==0)){
		ok=0;
	}

		
	if(ok==1){
		var url=baseurl+'config/nuovotavoloins.php'; 
	
		
		$$.ajax({
                url: url,
                method: 'POST',
				dataType: 'text',
				cache:false,
		        timeout:5000,
				data:{IDcliente:IDcliente,tipopersnuovo:tipopersnuovo,IDrestr:IDrestr,serv:serv,data:data,orario:orario,prezzo:prezzo,note:note,prezzirestr:prezzirestr,dati:dati},
				 error: function (data) {
					//rimuovioverlay();
					 myApp.hideIndicator();
				},

                success: function (data) {
					//alert(data);
					reloadnav=1;
					backexplode(8,0);
					myApp.hideIndicator();
				}
		});
		
		
		
	
		
	
	}else{
		myApp.hideIndicator();
		alert('Devi compilare tutti i campi obbligatori.Prego riprovare');
	}
	
	
	
	
	
}


function visionepersone(ID,IDclienti){
		myApp.showIndicator();

	
	var url=baseurl+versione+"/";
	var url=url+'config/visionepersone.php'; 
	$$.ajax({
                url: url,
                method: 'POST',
				dataType: 'text',
				cache:false,
		        timeout:5000,
				data: {
					ID:ID,
					IDclienti:IDclienti
					
                },
				 error: function (data) {
					//rimuovioverlay();
					 myApp.hideIndicator();
				},

                success: function (data) {
					//alert(data);
					myApp.hideIndicator();
					myApp.pickerModal(data);
					popoverord();
					
		}
		
	});
	
}


function vedicheckbox(tipo){
	var serv=$('#idserv').val();
	var data=$('#data').val();
	var IDclienti='';
	switch(tipo){
		case 0:
			var mioArray=document.getElementsByClassName('clienticheck');
			var lun=mioArray.length; //individuo la lunghezza dell’array 
			for (n=0;n<lun;n++) { //scorro tutti i div del documento
				var IDinto=mioArray.item(n).getAttribute('value');
				if(IDinto.length>0){
					IDclienti=IDclienti+IDinto+',';
				}
			}
			
			
		break;
		case 1:
			var mioArray=document.getElementsByClassName('checkboxpersona');
			var lun=mioArray.length; //individuo la lunghezza dell’array 
			//alert(lun);
			for (n=0;n<lun;n++) { //scorro tutti i div del documento
				if(mioArray.item(n).checked==true){
					var IDinto=mioArray.item(n).getAttribute('id');
					IDclienti=IDclienti+IDinto+',';
				}
			}
			
			prenotaztavolo(1,IDclienti);
		break;
		case 2:
			var IDclienti=$('#IDclientipren').val();
		break;
		
	}

	if(IDclienti.length>1){
		myApp.showIndicator();
		var url=baseurl+'config/calcoloprezzoristorantemobile.php'; 
		$$.ajax({
					url: url,
					method: 'POST',
					dataType: 'text',
					cache:false,
					timeout:5000,
					data: {serv:serv,data:data,pers:IDclienti},
					error: function (data) {
						//rimuovioverlay();
					 myApp.hideIndicator();
					},

					success: function (data) {
						myApp.hideIndicator();
						$$('#euro').html(data);
					}
		});

	}
	//rifare chiamata ajax a visionepersone ed eliminare o aggiungire nel record id clienti
	
}



function cambiaservizio(nome,idserv,prezzo){
	var servizio=$$('#nomeserv');
	servizio.html(nome);
	$$('#idserv').val(idserv);
	//impostare prezzi per categoria bambino ragazzo adulto
	//chiamata pagina php per query prezzi
	
	var tipopersnuovo=parseInt($('#tipopersnuovo').val());

	switch(tipopersnuovo){
		case 1:
			vedicheckbox(2);
		break;
		default:
			inserisciprezzirist(prezzo);
			calcolaprezzotav();
			
		break;
	}
	chiudimodal();
	

}


function inserisciprezzirist(prezzo){
	$$('#prezzo').val(prezzo);
	var prezzoschermo=$$('.prezzopersone');
	var lung=prezzoschermo.length;
	for(i=0;i<lung;i++){
		var prezzoid=prezzoschermo[i].id;
	    $$('#'+prezzoid).html(prezzo);//metto il prezzo in ogni span 
	}
}

function tastiricerca(time,IDsottotip){
	
	var buttons=new Array();
	
	buttons.push(
					{
					text: '<div>Ricerca Prenotazioni</div>',
					onClick: function () {
						ricercaprenotazione(time,IDsottotip);
					}
				}); 
	 
	         buttons.push(
					{
					text: '<div>Ricerca Cliente</div>',
					onClick: function () {
						ricercacli();
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


function rimuovioverlay(){
	 $$('.modal-overlay').fadeOut();
	 $$('.preloader-indicator-overlay').remove();
	 $$('.preloader-indicator-modal').remove();
	 myApp.hideIndicator();
}

function orariotavolonuovo(idsottotip,time){
	myApp.showIndicator();
	var url=baseurl+versione+"/";
	var url=url+'config/orariotavolnuovo.php'; 
	$$.ajax({
                url: url,
                method: 'POST',
				dataType: 'text',
				timeout:5000,
				cache:false,
				data: {
					idsottotip:idsottotip,
					time:time
                },
				 error: function (data) {
					//rimuovioverlay();
					 myApp.hideIndicator();
					
				},
                success: function (data) {
					//alert(data);
					myApp.pickerModal(data);
					myApp.hideIndicator();
					popoverord();
					
		}
		
	});
	
}

function cambiaorariopren(datetime,time){
	var orario=$$('#orarioserv');
	var timeserv=$$('#timeserv');
	timeserv.val(time)
	orario.html(datetime);
}


function calcolaprezzotav(){

	var prezzo=$$('#prezzo').val();
	var npers=$$('#personemax').val();
	var prezzofin=prezzo*npers;
	$$('#euro').html(prezzofin);
}

function contapers(){
	var idselectnum=$$('.inputrestr');
	var lung=idselectnum.length;
	var cont=0;
	for(i=0;i<lung;i++){
		var idselectnumero=idselectnum[i].id;//prendo id del numero di persone per ogni categoria dal select
		var numero=$$('#'+idselectnumero).val();//prendo i numeri dei value 
		numero=parseInt(numero);
		cont=cont+numero;
		}
	$$('#personemax').val(cont);
	calcolaprezzotav();
}



function argomentonuov(){
	var value=$$('#argrec').val();
	if(value==0){
		myApp.confirm('<input type="text" placeholder="argomento nuovo" id="nuovoinp"> ', function () 
	  {
			var val=$$('#nuovoinp').val();
		$$('#argnew').val(val);
		$$('#newval').html(val);
	 });
		
	}
	else{
		$$('#argnew').val(value);
	}
	//var testo='<input type="text" placeholder="nuova categoria" id="catnuov" >';
	//myApp.popup(testo);		
}




function pulsdomotica(IDdom){

	var buttons=new Array();
	var elimina=$$('#iddomo'+IDdom).val();

			buttons.push(
					{
					text: '<div>Manuale a Tempo</div>',
					onClick: function () {
						setdom(IDdom,1);
						
					}
				}); 
	 
	         buttons.push(
					{
					text: '<div>Manuale ad Intervallo</div>',
					onClick: function () {
					setdom(IDdom,2);
						
					}
				}); 

	
	 var buttons3 = [
			{
				text: '<div class="lastbutton-modal">Chiudi</div>'
			
			}
		];
		var groups = [buttons,buttons3];
	
		if(elimina==1){
					var buttons2= [
						{
						text: '<div style="color:red">Annulla programmi manuali</div>',
						onClick: function () {
						modprenot(IDdom,"0_0",63,10,1);

						}
					}];
					
				var groups = [buttons,buttons2,buttons3];
				}
		
	
		 
	
  	     myApp.actions(groups);

}

function pulizienav(num){
	var b1=$$('#all');
	var b2=$$('#prog');
	var b3=$$('#pul');
	
	switch(num){
			
		case 1:
				b1.addClass("active");
				b2.removeClass("active");
				b3.removeClass("active");
		break;
		case 2:
				b1.removeClass("active");
				b2.addClass("active");
			    b3.removeClass("active");
		break;
		case 3:
				b1.removeClass("active");
				b2.removeClass("active");
				b3.addClass("active");
		break;
			
	}
	
}

function pulsacc(IDdom){
	
	var buttons=new Array();
	var accendi=$$('#acdom').val();
	accendi=atob(accendi);
	accendi=eval(accendi);
	

			buttons.push(
					{
					text: '<div>Accendi</div>',
					onClick: function () {
						accendi(IDdom,1);
					}
				}); 
	 
	         buttons.push(
					{
					text: '<div>Spegni</div>',
					onClick: function () {
					 accendi(IDdom,0);
					}
				}); 
	
	
	 var buttons3 = [
			{
				text: '<div class="lastbutton-modal">Chiudi</div>'
			}
		];
		
		 var groups = [buttons,buttons3];
  	     myApp.actions(groups);

}



function profiloclienti(tipo,popup){
	
	myApp.showIndicator();
    var url=baseurl+versione+"/";
	var url=url+'config/profilocliscopri.php'; 
 	$$.ajax({
                url: url,
                method: 'POST',
				dataType: 'text',
				cache:false,
		        timeout:5000,
				data: {
					tipo:tipo
                },
				 error: function (data) {
					//rimuovioverlay();
					 myApp.hideIndicator();
				},
			
                success: function (data) {
					//clearTimeout();
				
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



function modportate(id,campo,tipo,val2,agg){
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
		var url=url+'config/gestioneportate.php';
		var query = {val:val,tipo:tipo,ID:id,val2:val2};
		//alert(url);
		$$.ajax({
			url: url,
			method: 'POST',
			dataType: 'text',
			cache:false,
			data: query,
			timeout:5000,
			error: function (data) {
				//rimuovioverlay();
					 myApp.hideIndicator();
			},
			success: function (data) {
				myApp.hideIndicator();
				reloadnav=1;
				//alert(agg);
				switch(agg){
					case 1:
						myApp.closeModal();
						navigationtxt(37,0,'menugiorno',0);
					break;
					case 2:
						navigationtxt(37,0,'menugiorno',0);
					break;
				}
			}
		});
}


function nuovopiatto(portata){
	myApp.showIndicator();

	var url=baseurl+versione+"/";
	var url=url+'config/nuovopiatto.php'; 
	$$.ajax({
                url: url,
                method: 'GET',
				dataType: 'text',
				cache:false,
				timeout:5000,
				data: {
					dato0:portata
                },
				 error: function (data) {
					//rimuovioverlay();
					 myApp.hideIndicator();
				},
                success: function (data) {
					
					myApp.hideIndicator();
					myApp.pickerModal(data);
					popoverord();
					navigationtxt(37,0,'menugiorno',0);
		}
		
	});
}

function accordionsottotip(){
	
	var buttons=new Array();
	
	var infop=$('#infosottotip').val();
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


function ricaricapagserv(tipo,dato0){
	
	var IDserv=parseInt($$('#IDservadd').val());
	
	switch(tipo){
	
		case 1:  			
			navigation2(13,IDserv+','+dato0,4,2);
			//passo id serv alla pagina con navigation
		break;	
		
		case 2:
			//navigationtxt(11);
		
		break;	
			
	}
}

function verificapersonenuovoserv(stringaid){
	myApp.showIndicator();
	var url=baseurl+versione+"/";
	var url=url+'config/profilo/pren/sceglipersone.php'; 
	$$.ajax({
                url: url,
                method: 'GET',
				dataType: 'text',
				cache:false,
				timeout:5000,
				data: {
					stringaid:stringaid
                },
				 error: function (data) {
					//rimuovioverlay();
					 myApp.hideIndicator();
				},
                success: function (data) {
					myApp.hideIndicator();
					myApp.pickerModal(data);
					
					
		}
		
	});
	
}
function contapersnuovoserv(tipo){
	
	var restrizione='';
	var mioArray=$('.scegliperscheckbox');
	var nump=0;
	var idpers='';
	var lun=mioArray.length; //individuo la lunghezza dellâ€™array 
	for (n=0;n<lun;n++) { //scorro tutti i div del documento
		if (mioArray[n].checked){
			restrizione=restrizione + mioArray[n].getAttribute('alt')+',';
			idpers=idpers + mioArray[n].getAttribute('id')+',';
			nump++;
		}
	}
	$$('#sceglipers').attr('onclick','verificapersonenuovoserv("'+idpers+'");');
	$$('#idpersonepres').val(idpers.replace(/,\s*$/, ""));
	calcolaprezzofin(restrizione,nump);
	
}

function calcolaprezzofin(restr,nump){
	myApp.showIndicator();
	var IDserv=parseInt($$('#IDservadd').val());
	var Giorno=parseInt($$('#time').val());
	
	var url=baseurl+versione+"/";
	var url=url+'config/profilo/pren/calcolaprezzofin.php'; 
	$$.ajax({
                url: url,
                method: 'GET',
				dataType: 'text',
				cache:false,
				timeout:5000,
				data: {
					restr:restr,
					IDserv:IDserv,
					Giorno:Giorno
                },
				 error: function (data) {
					//rimuovioverlay();
					 myApp.hideIndicator();
				},
                success: function (data) {
					myApp.hideIndicator();
					if(nump==1){
						var stringaper=nump+' Persona';
					}else{
						var stringaper=nump+' Persone';
					}
					$$('#numospiti').html(stringaper);
					$$('#prezzofin').html(data);
					//alert(data);
					
					//ricaricapagserv(tipo);
		}
		
	});
	
}
function selezionasaleserv(IDserv){
	myApp.showIndicator();
	var url=baseurl+versione+"/";
	var url=url+'config/profilo/pren/selezionasaleserv.php'; 
	$$.ajax({
                url: url,
                method: 'GET',
				dataType: 'text',
				cache:false,
				timeout:5000,
				data: {
					IDserv:IDserv
                },
				 error: function (data) {
					//rimuovioverlay();
					 myApp.hideIndicator();
				},
                success: function (data) {
					myApp.hideIndicator();
					myApp.pickerModal(data);
					
		}
		
	});	
}

function cambiasala(IDsala,nomesala,){
	$$('#salaid').val(IDsala);
	$$('#salaserv').html(nomesala);
}

function completapren(IDtipo,IDserv,regola){
	
	var giorno=parseInt($$('#time').val());
	var note=$$('#note').val();
	var orariogiorno='';
	var IDpersone=$$('#idpersonepres').val()
	
	if(IDtipo==1){
		var sala=parseInt($$('#salaid').val());
		if(sala==''){
			myApp.alert("Scegliere una sala.");
			return false;
		}
	}
	
	if(regola==1){
			$$('.oraservizio').each(function() {
				if($$(this).hasClass('oraserviziosel')){
					orariogiorno=$$(this).attr('alt');
				}						
			});
	}

}


function apriprenotaora(IDsotto,tempo){
	myApp.showIndicator();
	var url=baseurl+versione+"/";
	var url=url+'config/profilo/prenservpopup.php'; 
	$$.ajax({
                url: url,
                method: 'GET',
				dataType: 'text',
				cache:false,
				timeout:5000,
				data: {
					IDsotto:IDsotto,
					tempo:tempo
                },
				 error: function (data) {
					//rimuovioverlay();
					 myApp.hideIndicator();
				},
                success: function (data) {
					
					myApp.hideIndicator();
					myApp.pickerModal(data);
				
					var mySwiper=myApp.swiper('.sw2', {
					 spaceBetween: 10,
					 slidesPerView: 'auto',
					 speed:400,
					 pagination:'.swiper-pagination',
					 centeredslides:'true',
				     loop:'true'
					});
			
				setTimeout(function(){	
					mySwiper.update();
				},500);
				
					
					
		}
		
	});
	
}

function cambiomesi(mese){
	myApp.showIndicator();
	var url=baseurl+versione+"/";
	var url=url+'config/cambiomesecal.php'; 
	$$.ajax({
                url: url,
                method: 'GET',
				dataType: 'text',
				cache:false,
				timeout:5000,
				data: {
					mese:mese
                },
				 error: function (data) {
					//rimuovioverlay();
					 myApp.hideIndicator();
				},
                success: function (data) {
					myApp.hideIndicator();
					myApp.pickerModal(data);
					
					
		}
		
	});
}

function aprimenusx(){
	
	myApp.showIndicator();
	var url=baseurl+versione+"/"+'config/menusx.php'; 
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
					
					$('#contentpanelsx').html(data);
					myApp.openPanel('left');
					
					
		}
		
	});
}






function modimpo(id,campo,tipo,val2,agg){
		myApp.showIndicator();
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
		var url=url+'config/gestioneimpo.php';
		//alert(val);
		var query = {val:val,tipo:tipo,ID:id,val2:val2};
		//alert(url);
		$$.ajax({
			url: url,
			method: 'POST',
			dataType: 'text',
			cache:false,
			data: query,
			timeout:5000,
			success: function (data) {
				//alert(data);
					myApp.addNotification({
						message: 'Funzione eseguita con successo',
						hold:1200
					});
				
				
				myApp.hideIndicator();
				switch(agg) {
					case 1:
						 navigationtxt(39,0,'alloggi',0,0);
					break;
					
				}
			},
			error: function (){
				myApp.hideIndicator();
			}
	});
}









function modificaalloggio(IDapp,IDcat){
	myApp.prompt('Inserisci Nome Alloggio:', function (value) {
		if(value.length>0){
			
			var ID=IDapp+'_'+IDcat;
			
			modimpo(ID,value,1,10,1);
		}else{
			myApp.alert("E' necessario inserire un valore. Prego riprovare");
		}

	});


}