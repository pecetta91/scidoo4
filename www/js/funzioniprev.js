function gd(data1,data2,trasf){
	
	if(typeof(trasf) == "undefined") {
		trasf=1;
	}
	
	switch(trasf){
		case 1:
			anno1 = parseInt(data1.substr(6));
			mese1 = parseInt(data1.substr(3, 2));
			giorno1 = parseInt(data1.substr(0, 2));
			var dataok1=new Date(anno1, mese1-1, giorno1);
			anno2 = parseInt(data2.substr(6));
			mese2 = parseInt(data2.substr(3, 2));
			giorno2 = parseInt(data2.substr(0, 2));
			var dataok2=new Date(anno2, mese2-1, giorno2);
		break;
		case 2:
			giorno1 = parseInt(data1.substr(8, 2));
			mese1 = parseInt(data1.substr(5, 2));
			anno1 = parseInt(data1.substr(0,4));			
			var dataok1=new Date(anno1, mese1-1, giorno1);
			giorno2 = parseInt(data2.substr(8, 2));
			mese2 = parseInt(data2.substr(5, 2));
			anno2 = parseInt(data2.substr(0,4));	
			var dataok2=new Date(anno2, mese2-1, giorno2);
		break;
		case 0:
			dataok1=data1;
			dataok2=data2;
		break;
	}

	differenza = dataok2-dataok1;
	giorni_differenza = Math.round(new String(differenza/86400000));
	return giorni_differenza;
}



function modprezzoprev(id,tipo){
	
	myApp.prompt('Inserisci prezzo:', function (value) {
			if(!isNaN(value)){
				value=convertnumb(value,0);
				
				switch(tipo){
					case 1:
						modprenot(id,value,92,10,12);
					break;
					case 2:
						modprenot(id,value,92,10,12);
					break;
					case 3:
						modprenot(id,value,92,10,12);
					break;
					case 4:
						modprenot(id,value,91,10,12);
					break;
					case 5:
						modprenot(id,value,91,10,12)
					break;
					case 6:
						modprenot(id,value,91,10,12);
					break;
			 	}
				
				
				
			}else{
				myApp.alert('Devi inserire un numero. Prego riprovare');
			}
							
	});
	
	
}

function adddata(data1,notti,trasf,formato){
	
	if(typeof(trasf) == "undefined") {
		trasf=1;
	}
	if(typeof(formato) == "undefined") {
		formato=1;
	}
	
	switch(trasf){
		case 1:
			anno1 = parseInt(data1.substr(6));
			mese1 = parseInt(data1.substr(3, 2));
			giorno1 = parseInt(data1.substr(0, 2));
			var dataok1=new Date(anno1, mese1-1, giorno1);
			/*anno2 = parseInt(data2.substr(6));
			mese2 = parseInt(data2.substr(3, 2));
			giorno2 = parseInt(data2.substr(0, 2));
			var dataok2=new Date(anno2, mese2-1, giorno2);*/
		break;
		case 2:
			giorno1 = parseInt(data1.substr(8, 2));
			mese1 = parseInt(data1.substr(5, 2));
			anno1 = parseInt(data1.substr(0,4));		
			var dataok1=new Date(anno1, mese1-1, giorno1);
			/*giorno2 = parseInt(data2.substr(8, 2));
			mese2 = parseInt(data2.substr(5, 2));
			anno2 = parseInt(data2.substr(0,4));	
			var dataok2=new Date(anno2, mese2-1, giorno2);*/
		break;
		case 0:
			dataok1=data1;
			//dataok2=data2;
		break;
	}
	
	
	
	val=new Date(dataok1.getFullYear(),dataok1.getMonth(),parseInt(dataok1.getDate())+parseInt(notti));
	
	
	/*dataok2 = parseInt(dataok1)+parseInt(86400000*notti);
	alert(dataok2);
	var val=new Date(dataok2);*/
	
	

	var gg=val.getDate();
	if(gg<10)gg='0'+gg;
	var mm=val.getMonth();
	mm++;
	if(mm<10)mm='0'+mm;
	var yy=val.getFullYear();
					
	
	switch(formato){
		case 1:
			var data=gg+'/'+mm+'/'+yy;
		break;
		case 3:
			var data=yy+'-'+mm+'-'+gg;
			data=new Date(data);
		break;
		default:
			var data=yy+'-'+mm+'-'+gg;
		break;
	 }
	
	/*if(formato==1){
		var data=gg+'/'+mm+'/'+yy;
	}else{
		var data=yy+'-'+mm+'-'+gg;
	}*/



	return data;
}







function selnotti(notti,obj){
	$$('.slidenotti').removeClass('selected');
	$$(obj).addClass('selected');
	$('#notti').val(notti);
	controllodispo();
}



function selpacchettoprev(id,nump,tipopacc){
	
	ok=1;
	var numpers=0;
	var IDsog='';
		var mioArray=document.getElementsByClassName('checkpers');;
		var lun=mioArray.length; //individuo la lunghezza dell’array
		for (n=0;n<lun;n++) { //scorro tutti i div del documento
			if (mioArray[n].checked==true){
				IDsog= IDsog +mioArray.item(n).getAttribute('id')+',';
				numpers=numpers+1;
			}
		}
	
	if(nump!=0){
		if(numpers!=nump){
			var ok=0;
			alert("Per questo pacchetto e' necessario selezionare "+nump+" persona/e<br>Prego riprovare ");
		}
	}
	
	if(ok==1){
	
		if(IDsog!=0){
			
			myApp.showIndicator();
			//setTimeout(function(){ hidelo(); }, 5500);
			
			var url = baseurl+'config/preventivo/config/selperscat.php';
				//myApp.showIndicator(); setTimeout(function(){ hidelo(); }, 5500);	
				$$.ajax({
						url: url,
						method: 'POST',
						dataType: 'text',
						cache:false,
						data: {IDsog:IDsog,pacc:id,tipopacc:tipopacc},
						timeout:5000,
						error:function(data){
							myApp.hideIndicator();
						},
						success: function (data) {
							//alert(data);
							
							var tipo=$$('#tipopacchetto').val();
							//stepnew(0,0);
							tabservizi(tipo);
							myApp.hideIndicator();
							myApp.closeModal();
							calcolatot();
							
						}
				});
			
			
			
				
		}
	}
}


function eliminapaccprev(ID,tipo,agg){
	
	myApp.showIndicator();
			//setTimeout(function(){ hidelo(); }, 5500);
			
			var url = baseurl+'config/preventivo/config/eliminapacc.php';
				//myApp.showIndicator(); setTimeout(function(){ hidelo(); }, 5500);	
				$$.ajax({
						url: url,
						method: 'POST',
						dataType: 'text',
						cache:false,
						data: {ID:ID,tipo:tipo},
						timeout:5000,
						error:function(data){
							myApp.hideIndicator();
						},
						success: function (data) {
							
							//alert('bbb');
							//var ogg=$('#IDpaccselect');
							//if ($('#IDpaccselect') !== 'undefined') {
								
							if (agg==1) {	
								
								
								//alert('ccc');
								var id=$$('#IDpaccselect').val();
								var nump=$$('#nump').val();
								var tipopacc=$$('#tipopacchetto').val();
								selectpers(id,nump,tipopacc,1);
							}else{
								tabconto(0);
							}
							calcolatot();
							
							myApp.hideIndicator();
							
						}
				});
	
}



function cercavoucher(){
	myApp.prompt('Inserisci codice voucher:', function (value) {
		tabservizi(7,value);				
	});
}





function addrestriz(ID,add){
	var qta=$$('#restriz'+ID).html();
		
	if(add==1){
		qta++;
	}else{
		qta--;
	}
	$$('#restriz'+ID).html(qta);

}


var piunotti=0;

	
	var calcolodispo=0;
	
	function dispo2(){
		
		myApp.showIndicator();
		//setTimeout(function(){ hidelo(); }, 5500);
		
		calcolodispo=0;
		
		/*
		var nottipern=1;
		if(piunotti==1){		
			var prenotveloce=$$('#prenotveloce').html();
			if(prenotveloce==0){
				var datai=$$('#prenotvelocetime').val();	
				var notti=$$('#notti').val();	
			}else{
				var data=document.getElementById('data').value;
				if(data.length>10){
					var vettore=data.split(' - ');
					var datai=vettore['0'];
					var dataf=vettore['1'];
					var notti=gd(datai,dataf,2);
				}else{
					myApp.alert('Date selezionate in modo scorretto. Si prega di modificarle per continuare.');
					myApp.showTab('#step1');
					return false;
				}
			}
		}else{
			var prenotveloce=$$('#prenotveloce').html();
			if(prenotveloce==0){
				var datai=$$('#prenotvelocetime').val();
				
				var notti=0;
			}else{
				var data=document.getElementById('data').value;
				if(data.length>5){
					var datai=data;
					notti=0;
					nottipern=0;
				}else{
					myApp.alert('Date selezionate in modo scorretto. Si prega di modificarle per continuare.');
					myApp.showTab('#step1');
					return false;
				}
			}
		}
		*/
		
		var datai=$$('#dataarr').val();
		var notti=$$('#notti').html();
		var orario=document.getElementById('orario').value;
		var voucher=0;
		var cofanetto=0;
		var query = {datai:datai,notti:notti,voucher:voucher,cofanetto:cofanetto,orario:orario};
		
		var IDcont=document.getElementsByClassName('inputrestr');
		var lung=IDcont.length;
		//alert('a');
		
		var somma=0;
		for(i=0;i<lung;i++){
			var ID=IDcont[i].id;
			var val=$$('#'+ID).html();
			//alert(val);
			//if(val=="undefined")val=0;
			var ss=$$('#'+ID).attr('lang');
			var ID=$$('#'+ID).attr('alt');
			//var ID = ID.replace(/[^0-9]/g,'');
			//var ID=parseInt(ID);
			if(ss==1){
				somma=parseInt(somma)+parseInt(val);
			}
			query[ID]=val;
		}
		
		var ok=1;
		if((notti==0)&&(piunotti==1)){
			ok=0;
		}
		
		if(notti>0){
			if(document.getElementById('alloggio').length==0){
				ok=0;
			}
		}
		//alert(ok+'-'+somma);
		if(ok==1){
			if(somma>0){	
				var url = baseurl+'config/preventivo/config/dispo1.php';
				//myApp.showIndicator(); setTimeout(function(){ hidelo(); }, 5500);	
				$$.ajax({
						url: url,
						method: 'POST',
						dataType: 'text',
						cache:false,
						data: query,
						timeout:5000,
						error: function(data){
							myApp.hideIndicator();
						},
						success: function (data) {
							//Find matched items
							//alert(data);
							 calcolodispo=1;
							 myApp.hideIndicator();
							
							
							 if(data==1){
								stepnew(1,0)
							 }
							 
						}
					});
			}else{
				 myApp.hideIndicator();
			}
		}else{
			
			myApp.addNotification({
						message: 'Ci sono dei dati obbligatori mancanti.<br/>Prego riprovare',
						hold:1200
					});
			
			myApp.hideIndicator();
		}
	}
	
	
function selelim(pacc,tipopacc,obj){
	//alert('');
	if (!($(obj).is(':checked'))) {
		//alert(pacc+'---'+tipopacc);
		if(pacc!=0){
			eliminapaccprev(pacc,tipopacc,1);
		}		
	}
	
	
}



	function selectpers(id,nump,tipopacc,relo){
		
		var url = baseurl+versione+'/config/preventivo/selectpers.php';
		
		myApp.showIndicator(); 
		//setTimeout(function(){hidelo();}, 5500);	
		
		$$.ajax({
				url: url,
						method: 'POST',
						dataType: 'text',
						cache:false,
						timeout:5000,
						data: {IDpacc:id,nump:nump,tipopacc:tipopacc,relo:relo},
						success: function (data){
							myApp.hideIndicator(); 
							if(relo==1){
								$$('#popup').html(data);
							}else{
								myApp.popup(data);
							}
						},
						error:function (){
							myApp.hideIndicator(); 
						}
				});
		
		
	}
	
	
	
	 function dispo1(){
		 //alert('aa');
		 //alert('dispo1');
		var nottipern=1;
		/*if(piunotti==1){		
			var prenotveloce=$$('#prenotveloce').html();
			if(prenotveloce==0){
				var datai=$$('#prenotvelocetime').val();	
				var notti=$$('#notti').val();	
			}else{
				var data=document.getElementById('data').value;
				if(data.length>10){
					var vettore=data.split(' - ');
					var datai=vettore['0'];
					var dataf=vettore['1'];
					var notti=gd(datai,dataf,2);
				}else{
					myApp.alert('Date selezionate in modo scorretto. Si prega di modificarle per continuare.');
					myApp.showTab('#step1');
					return false;
				}
			}
		}else{
			var prenotveloce=$$('#prenotveloce').val();
			if(prenotveloce==0){
				var datai=$$('#prenotvelocetime').val();
				nottipern=0;
				var notti=0;
			}else{
				var data=document.getElementById('data').value;
				if(data.length>5){
					var datai=data;
					notti=0;
					nottipern=0;
				}else{
					myApp.alert('Date selezionate in modo scorretto. Si prega di modificarle per continuare.');
					myApp.showTab('#step1');
					return false;
				}
			}
			
		}*/
		
		var datai=document.getElementById('dataform').value;
		var notti=$$('#notti').val();	
		var orario=document.getElementById('orario').value;
		var voucher=0;
		var cofanetto=0;
				
		
		var query = {datai:datai,notti:notti,voucher:voucher,cofanetto:cofanetto,orario:orario};
		
		var IDcont=document.getElementsByClassName('inputrestr');
		var lung=IDcont.length;
		//alert('a');
		var somma=0;
		for(i=0;i<lung;i++){
			var ID=IDcont[i].id;
			var val=$$('#'+ID).val();
			//if(val=="undefined")val=0;
			var ss=$$('#'+ID).attr('lang');
			var ID=$$('#'+ID).attr('alt');
			//var ID = ID.replace(/[^0-9]/g,'');
			//var ID=parseInt(ID);
			if(ss==1){
				somma=parseInt(somma)+parseInt(val);
			}
			query[ID]=val;
		}
		//alert('aa');
		var ok=1;
		/*if((notti==0)&&(nottipern==1)){
			ok=0;
		}*/
		if(ok==1){
			if(somma>0){	
				var url = baseurl+'config/preventivo/config/dispo1.php';
		
				myApp.showIndicator();
				
				
				$$.ajax({
						url: url,
						method: 'POST',
						dataType: 'text',
						cache:false,
						data: query,
						timeout:5000,
						error: function(data){
							myApp.hideIndicator();
						},
						success: function (data) {
							//Find matched items
							//alert(data);
							 myApp.hideIndicator();
							 //statostep=2;  	 //alert(data);
							 stepnew(1,0);
						}
					});
			}else{
				myApp.alert('Devi inserire tutti i campi. Prego Riprovare');
			}
		}else{
			myApp.alert('Il soggiorno deve avere una durata minima di una notte.');
		}	
		
	}

var calextra=0;

 function selpacc(IDapp,IDpacc,obj){
	 calextra=0;
	 $$('.tablist').removeClass('selected');
	 $$(obj).addClass('selected');
	 myApp.showIndicator();
	//   setTimeout(function(){ hidelo(); }, 5500);	
		var query = {IDapp:IDapp,IDpacc:IDpacc};
		var url = baseurl+versione+'/config/preventivo/config/selpacc.php';
				$$.ajax({
						url: url,
						method: 'POST',
						dataType: 'text',
						cache:false,
						data: query,
						timeout:5000,
						success: function (data) {
							 //clearTimeout();
							 calcolatot();
							 navigationtxt(6,0,'step2',0,0);
							 myApp.hideIndicator();
							 //myApp.hideIndicator();  	 //alert(data);
							 calextra=1;
						},
						error: function (){
							myApp.hideIndicator();
						}
					})
	}	
	
function addservprev(rel){
	var url=baseurl;
	var url=url+versione+'/config/preventivo/config/serviziadd.php';
	var query = {relo:rel};
	$$.get(url,query, function(data){
		//alert(data);
		//$$('#popup').html(data);
		//myApp.popup(data);
		blockPopstate=false;
		mainView.router.load({
			content: data,
			animatePages: true
		});
		//alert(data);
		
		//$$('#contpopup2').html(data);
		
	});
}	

function selezorario(obj){
	//$$('.roundb6').removeClass('selected');
	//$$(obj).addClass('selected');
	
}

function aggiungis(){
	myApp.showIndicator(); 
	//setTimeout(function(){ hidelo(); }, 5500);
	
	//nuovoservprev('.$IDins.',this.value)
	
	
	
	var val1=$$('#orarioadd').val();
	
	
	var val2='';
	$$('.soggetti').each(function(i, obj) {
    	if($$(obj).is(':checked')){
			val2=val2+$$(obj).val()+',';
		}
	});
	
	/*
	var val3=$$('#IDservadd').val();
	var val4=$$('#IDsaladef').val();
	var val=val1+'_'+val4+'_'+val2+'_'+val3;*/
	
	if((val1!='0')&&(val1!=undefined)&&(val2.length>0)){
		//gestioneric(0,val,2,10,2);
		val1=$$('#orarioadd').val();
		nuovoservprev(0,val1,1);
		
		
	}else{
		myApp.hideIndicator(); 
		alert("E' obbligatorio indirare un orario ed almeno una persona.");
	}
}


function nuovoservprev(ID,val,agg){
	
	var IDserv=$('#IDservaggiungi').val();

	var IDsog='';
	
	var mioArray=document.getElementsByClassName('soggetti');;
		var lun=mioArray.length; //individuo la lunghezza dell’array
		for (n=0;n<lun;n++) { //scorro tutti i div del documento
			if (mioArray[n].checked==true){
				IDsog= IDsog +mioArray.item(n).value+',';
			}
		}
	
	
	var val=val+'_'+IDsog+'_'+IDserv;
	if(agg==1){
		modprenextra(ID,val,23,9,25);
	}else{
		modprenextra(ID,val,23,9);
	}
	
	
}


function addservprev2(IDins,IDserv,time,rel){
//	alert(time);
	myApp.showIndicator();  
	var query = {IDserv:IDserv,IDins:IDins,time:time};
	var url = baseurl+versione+'/config/preventivo/config/addserv.php';
	$$.ajax({
						url: url,
						method: 'POST',
						dataType: 'text',
						cache:false,
						data: query,
						timeout:5000,
						success: function (data) {
							//alert(data);
							//alert(rel);
							switch(rel){
								case 0:
									blockPopstate=false;
									
									mainView.router.load({
									  content: data,
									   animatePages: true
									});
									//alert(data);
									//myApp.popup(data);
									/*if(relo==1){
										$$('#popup').html(data);
									}else{
										myApp.popup(data);
									}*/
									
									//var popupHTML = '<div class="popup popupadd" id="contpopup2" style="padding:0px;">'+data+'</div>';
									//myApp.popup(popupHTML);
								break;
								case 1:
									/*mainView.router.load({
									  content: data,
									   reload:true
									});*/
									
									$$('#add2').html(data);
									$$('.tabindietro').css('display','block');
									$$('#add2').html(data);
									//$$('#buttonaddprev').css('display','block');
									$$('#contbuttonagg').css('display','block');
									
									myApp.showTab('#add2');
						
									var tot=$$('#totalecalcolato').val();
									$$('#totaleserv').html(tot+' Euro');
									
								break;
								case 2:
									//$$('#contpopup2').html(data);
									mainView.router.load({
									  content: data,
									   reload:true
									});
									
									$$('#tab5').trigger('click');
								break;
							}
						
							
							 myApp.hideIndicator();  	 

							 //var left=parseInt($$(".roundb3.selected").attr('alt'));
							 //var left=left*30-10;
							// document.getElementById('dataadd').scrollLeft=left;
							
							 var relo=$$('#reload').val();
							 //alert(relo);
							 if(relo==1){
								var time=$$('#dataaddserv').val();
								timesucc=parseInt(time)+parseInt(86400);
								var IDserv=$$('#IDservaggiungi').val();
								addservprev2(IDins,IDserv,timesucc,1);
							}
								
						},
						error: function (){
							myApp.hideIndicator();  
						}
					})
}	


/*
function addservprevent(IDserv){
	//myApp.closeModal('.popover-menu');
	alert('aa');
	myApp.showIndicator();  setTimeout(function(){ hidelo(); }, 5500);	
	var url = baseurl+'mobile/config/preventivo/config/addserv.php';
	$$.ajax({
						url: url,
						method: 'POST',
						dataType: 'text',
						cache:false,
						data: query,
						success: function (data) {
							alert('aa');
							//$$('#pannellodx').html(data);
							 myApp.hideIndicator();  	 //alert(data);
							 mainView.router.back();
							 //myApp.closePanel('right');
							
							
							
							 
							 
						}
					})
}	*/


function ricercaservizio(val){
	navigationtxt(31,val,'servizitrovati');
}
	

function gestioneric(id,campo,tipo,val2,agg){
	myApp.showIndicator(); 
	//setTimeout(function(){ hidelo(); }, 5500);	 
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
			default:
				var val=$$('#'+campo).val();
			break;
	}
	
	//alert(val);
	var query={val:val,tipo:tipo,ID:id,val2:val2};
	
	var url = baseurl+'config/preventivoonline/config/gestionerichiesta.php';
	$$.ajax({
						url: url,
						method: 'POST',
						dataType: 'text',
						cache:false,
						data: query,
						timeout:5000,
						success: function (data) {
							//alert(data);
							myApp.hideIndicator(); 
							switch(agg) {
								case 1:
									var arr=data.split('_');
									addservprev2(arr['0'],arr['1'],arr['2'],1);
								break;
								case 2:
									//myApp.closePanel('right');
									
									navigationtxt(6,0,'step'+statostep,0);
									calcolatot();
									mainView.router.back();
									blockPopstate=true;
									
								break;
								case 3:
									/*myApp.addNotification({
										message: 'Modifica effettuata con successo',
										hold:1700
									});*/
						
								
									var arr=data.split('_');
									$$('.roundb6').removeClass('selected');
									$$('#'+arr['2']).addClass('selected');
								break;
							}
							
						},
						error: function (){
							myApp.hideIndicator(); 
						}
					})
		
}


function ricarcolaadd(){
	var totale=0;
	var num=0;
	$$('.soggetti').each(function(i, obj) {
		//alert($$(obj).attr('checked'));
    	if($$(obj).is(':checked')){
			var prezzo=parseFloat($$(obj).attr('alt'));
			totale=parseFloat(prezzo)+parseFloat(totale);
			num++;
		}
	});
	$$('#totaleserv').html(totale+' Euro');
	if(num==1){
		$$('#numpers').html(num +' Persona');
	}else{
		$$('#numpers').html(num +' Persone');
	}
	if(num>0){
		$$('#confbutton').removeAttr('disabled');
	}else{
		$$('#confbutton').Attr('disabled','disabled');
	}
}

function cambiadestprev(restr,IDins,IDserv){
	var val=restr+'_'+IDserv;
	modprenextra(IDins,val,24,9,1);
	
}


function chiudiprev(){
    myApp.confirm('Sei sicuro di voler uscire da Nuova Prenotazione?', 
      function () {
		  	blockPopstate=false;
			mainView.router.back();
      }
    );

}
function eliminaextraprev(ID,back){
	
	
		myApp.confirm('Sei sicuro di voler eliminare il servizio dal preventivo?', 
		  function () {
			myApp.showIndicator(); 
			//setTimeout(function(){ hidelo(); }, 5500);	 
			var url=baseurl;
			var url=url+'config/preventivo/config/eliminaextra.php';
			var query = {ID:ID};
			//alert(url);
			$$.ajax({
				url: url,
				method: 'POST',
				dataType: 'text',
				cache:false,
				data: query,
				timeout:5000,
				error: function(data){
					myApp.hideIndicator();
				},
				success: function (data) {
					myApp.hideIndicator();
					
					navigationtxt(6,0,'step'+statostep,0);
					calcolatot();
					if(back==1){
						mainView.router.back();
					}
					
					
					/*myApp.addNotification({
						message: 'Servizio rimosso con successo',
						hold:1500,
					});*/
					
				}
			});
			
			
			
		  }
		);
	
	
	
	

}


function addservprevent(IDserv){
	myApp.showIndicator(); 
	//setTimeout(function(){ hidelo(); }, 5500);	 
	var url=baseurl;
	var url=url+'config/preventivo/config/addextra.php';
	var query = {IDserv:IDserv};
	//alert(url);
	$$.ajax({
		url: url,
		method: 'POST',
		dataType: 'text',
		cache:false,
		data: query,
		timeout:5000,
		error: function(data){
							myApp.hideIndicator();
						},
		success: function (data) {
			/*
			myApp.addNotification({
							message: 'Servizio aggiunto con successo',
							hold:2500,
							 button: {text: '<i class="material-icons">close</i>'}
						});
			*/
			
			navigationtxt(6,0,'step'+statostep,0);
			calcolatot();
			myApp.hideIndicator();
			mainView.router.back();
			
		}
	});	
}

function calcolatot(){
	var url=baseurl;
	var url=url+versione+'/config/preventivo/config/calcolatot.php';
	var query = {};
	//alert(url);
	$$.ajax({
		url: url,
		method: 'POST',
		dataType: 'html',
		cache:false,
		data: query,
		success: function (data) {
			$$('#totaleprev').html(data);
			
		}
	});	
}

function confermapren(){
	
	myApp.showIndicator(); 
	//setTimeout(function(){ hidelo(); }, 5500);	 
	var url=baseurl;
	var url=url+'config/preventivo/conferma.php';
	var noteag=$$('#noteag').val();
	var query = {noteag:noteag};
	$$.ajax({
		url: url,
		method: 'POST',
		dataType: 'text',
		cache:false,
		data: query,
		timeout:10000,
		success: function (data) {
			myApp.addNotification({
				message: 'Prenotazione inserita con successo',
				hold:1200,
			});
			myApp.hideIndicator();  
			reloadcal=1;
			blockPopstate=false;
			data=parseInt(data);
			var IDp=new String(data);
			navigation(3,IDp,0,1);
		},
		error: function (){
			myApp.hideIndicator();  
			blockPopstate=false;
		}
	});	
	
	
	
}



function modificaprezzoprev(){
		myApp.prompt('Inserisci prezzo totale:', function (value) {
			if(!isNaN(value)){
				modprenot(0,value,94,10,11);
			}else{
				myApp.alert('Devi inserire un numero. Prego riprovare');
			}
		});
}

function modificaaccontoprev(){
	
		myApp.prompt('Inserisci Acconto:', function (value) {
							if(!isNaN(value)){
								modprenot(0,value,112,10,11);
							}else{
								myApp.alert('Devi inserire un numero. Prego riprovare');
							}
						});
}

