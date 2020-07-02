var baseurl = /test.scidoo.com/.test(window.location.origin) ? 'https://test.scidoo.com/scidoo/' : 'https://www.scidoo.com/';
var versione='app_uikit';

 var landing_page=stampa_parametro_landing('landing_page');
 var token=stampa_parametro_landing('token');
$.ajax({
	type: "POST",
    url: baseurl+versione+'/config/get_script_scidoo.php',
	data: { },
	cache: false,
	success: function(html) {
 		$('head').append(html);
 		carica_prima_pagina();
	}
});


function stampa_parametro_landing(parametro){
    var pagina_url = window.location.search.substring(1);
    var variabile = pagina_url.split('&');
    for (var i = 0; i < variabile.length; i++){
        var parametro_url = variabile[i].split('=');
        if (parametro_url[0] == parametro){
            return parametro_url[1];
        }
    }
}



function carica_prima_pagina(){

	$.ajax({
		type: "POST",
	    url: baseurl+versione+'/carica_prima_pagina.php',
		data: {landing_page:landing_page,token:token },
		cache: false,
		success: function(html) {
	 		$('head').append(html); 	
	 	}
	});
}

 
