function navigationtxt2(id,str,campo,agg,loader){
	if(IDcode=='undefined'){
		onloadf(1);
	}
	//reloadnav=0;
	//alert(id);
	var url=baseurl+versione+"/config/";
	
	var apriurl=new Array('profilo/pren/prenotaservnuovostep1.php','profilo/pren/prenotaservnuovostep2.php','profilo/pren/prenotaservnuovostep3.php','profilo/pren/prenotaservnuovostep4.php','profilo/pren/prenotaservnuovostep5.php','profilo/pren/prenotaservnuovostep6.php','profilo/elencoserv.inc.php','profilo/serviziattivi.inc.php','profilo/check-in.inc.php','profilo/autoricercascript.php');//36
	var url=url+apriurl[id];
	//alert(id);
	//alert(url);
	//alert('TXT'+id);
	//alert(campo);
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
	$$.ajax({
            url: url,
                method: 'GET',
				dataType: 'text',
				cache:false,
				timeout:5000,
                data: query,
                success: function (data) {
					//alert(data);
					if(loader!=0){
						clearTimeout();
						myApp.hideIndicator();
					}
					
					
					$$('#'+campo).html(data);
					//alert(id);
				
					switch(agg){
						case 1:
							
						break;
							
							
					}
					$$('.page-content').scrollTop=0;
         },
		 error: function (data) {
					myApp.hideIndicator();
				}
     });	
}

function navigation2(id,str,afgg,rel){
	
	var url=baseurl+versione+"/";
	
	
	
	id=parseInt(id);	
	
	var apriurl=new Array('config/profilo/metodopag.php','config/profilo/galleria.php','config/profilo/fotoalbum.php','config/profilo/elencoluoghi.php','config/profilo/pren/prenotaservnuovo.php','config/profilo/elencoserv2.php','config/profilo/serviziattivi.php','config/profilo/infoutili.php','config/profilo/contatti.php','config/registrazione.php','config/profilo/check-in.php','config/profilo/privacypol.php');
	//last 34 
	
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
					
					//alert(agg);
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
					
					}
					
         },
		error:function(data){
			myApp.hideIndicator();
		}
     });
}

function metodopag(){
	
	var buttons=new Array();

	

			buttons.push(
					{
					text: 'Carta di Credito',
					onClick: function () {
						
						navigation2(0,1,0);
					}
				}); 
	
	 			var infop=$('#infop').val();
			    infop=atob(infop);
			    eval(infop);
	 
	        
	
	
	 var buttons3 = [
			{
				text: 'Chiudi',
				color:'black'
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
	}
	
}

          
var myApp2 = new Framework7(); 
 
var $$ = Dom7;



function foto(album,num){
	
	var photoarr=new Array();//	$$('#album'+album+' div')
	$$('#album'+album+' div.prendifoto ').each(function() {
			var id=parseInt($$(this).attr('idphoto'));		
			var src=$$(this).attr('alt');
			photoarr[id]=src;
	});
	
var myPhotoBrowser= myApp2.photoBrowser({
    photos : photoarr,
	navbarTemplate:'<div class="navbar navfoto">'+
    '<div class="navbar-inner">'+
        '<div class="left sliding">'+
            '<a href="#" class="link close-popup photo-browser-close-link {{#unless backLinkText}}icon-only{{/unless}} {{js "this.type === \'page\' ? \'back\' : \'\'"}}">'+
                '<i class="icon icon-back "></i>'+
                '{{#if backLinkText}}<span>{{backLinkText}}</span>{{/if}}'+
            '</a>'+
        '</div>'+
        '<div class="center sliding">'+
          '  <span class="photo-browser-current"></span> '+
           ' <span class="photo-browser-of">{{ofText}}</span> '+
            '<span class="photo-browser-total"></span>'+
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
	alert(val);
	if((titolo.length>5)&&(recens.length>10)){
		modprofilo(0,val,9,10,6);
	}else{
		myApp2.alert("E' obbligatorio inserire un titolo ed un testo alla recensione. Prego riprovare.");
	}
	
}


function modorarioospite(){
		myApp.showIndicator();

	var url=baseurl+versione+"/";
	var url=url+'config/profilo/orarioarrivo.php'; 
	$$.ajax({
                url: url,
                method: 'GET',
				dataType: 'text',
				cache:false,
				timeout:5000,
				data: {
					
                },
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
					myApp2.alert("Scegliere un giorno.");
				}	
		break;	
		case 2://funzione step 2
			salapren=valoresala();
			if(salapren.length>0){
					scorristep(1,IDserv);
				 	
				}else{
					myApp2.alert("Scegliere una sala.");
				}	
		break;
		case 3://funzione step 3
			orariopren=valoretime();
			if(orariopren.length>0){
					scorristep(1,IDserv);
				 	
				}else{
					myApp2.alert("Scegliere un orario.");
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
					alert(data);
				
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
				text: 'Chiudi',
				color:'black'
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
	}	
}

function prenconferma(idpren){
	modprofilo(idpren,0,40,0,7);
}

function backexplode2(tipo,dato0){
	mainView.router.back();
	setTimeout(function (){
			
		switch(tipo){
			case 1:
				var tipol=parseInt($$('#tipo').val());
				navigationtxt2(7,tipol,'servizidiv',0,1);
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
					data: {email:email,pass:pass,nome:nome,nomestr:nomestr,prefisso:prefisso,tel:tel,tipo:tipo,numc:numc,prezzom:prezzom
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
					var url2=url2+versione+'/indexexit.html';
					// alert(url2);
				
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


function autoricerca(tipo,idcliente){
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
					idcliente:idcliente
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

function autoscrivi(id,tipo){
	var nome='';
	var tipoprenot=0;
	var idcliente=$$('#idcliente').val();
	var campo='';
	switch(tipo){	
		case 1:
			campo='.cittadinanzaver';
			tipoprenot=30;
		break;
		
		case 2:
			campo='.luogonasver';
			tipoprenot=31;
		break;
			
		case 3:
			campo='.residenzaver';
			tipoprenot=29;
		break;
		
		case 4:
			campo='.luogorilver';
			tipoprenot=28;
		break;
		
		case 5:
			campo='.documentover';
			tipoprenot=32;
		break;	
	}
	nome=$$('#'+id).attr('alt').toLowerCase();	
	
	$$(campo).html(nome);
	
	
	
	modprofilo(idcliente,id,tipoprenot,10);
}


function apriacc(num)
{
	myApp.accordionToggle($$('#accordion'+num));
}