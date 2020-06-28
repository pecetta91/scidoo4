<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';
require_once __DIR__ . '/../../../config/servizi/aggiunta/funzioni_aggiunta_servizi.php';

$IDutente = $_SESSION['ID'];
$IDstruttura = $_SESSION['IDstruttura'];

array_escape($_POST);
$IDriferimento = $_POST['IDriferimento'] ?? null;
$tipo_riferimento = $_POST['tipo_riferimento'] ?? null;
$IDservizio = $_POST['IDservizio'];
$opzioni = $_POST['opzioni'] ?? [];

if (!$IDriferimento || $tipo_riferimento === null) {
	exit("Nessun riferimento selezionato");
}

$aggiunta = Servizi\Aggiunta\AggiuntaServizio::nuovo($IDstruttura, $IDriferimento, $tipo_riferimento);
$riferimento = $aggiunta->riferimento;
$date = $riferimento->get_date();

if ($IDservizio) {
	$servizio = get_info_from_IDserv($IDservizio, null, $IDstruttura);
	$info_date = $aggiunta->get_info_date($IDservizio) ?: [];
} else {
	$servizio = ['tipolim' => 0];
	$info_date = [];
}
$intervallo = [];
foreach ($date as $info) {
	$intervallo[0] = min($intervallo[0] ?? $info[0], $info[0]);
	$intervallo[1] = max($intervallo[1] ?? $info[1], $info[1]);
}
if ($intervallo[0] > $intervallo[1]) {
	die("Il gruppo selezionato non è compatibile.");
}
$date = $intervallo;

$has_date_inserimento = array_reduce($info_date, function ($tot, $current) {return $tot or $current['inserimento'];}, false);

if (isset($opzioni['date']) and in_array(0, $opzioni['date'])) {
	$date_selezionate = [0 => true];
} else {
	$date_selezionate = array_fill_keys(range($date[0], $date[1], 86400), false);
	if ($opzioni['date'] ?? false) {
		foreach ($opzioni['date'] as $d) {
			$date_selezionate[$d] = true;
		}
	} else if ($has_date_inserimento) {
		foreach ($info_date as $d => $info) {
			$date_selezionate[$d] = $info['inserimento'] ?? false;
		}
	}
}

$opzioni['date'] = array_keys(array_filter($date_selezionate));

$selected = ($date_selezionate[0] ?? false) ? 'selected' : '';
$box_date = '<div class="date ' . $selected . '" data-date="0"><span>Nessuna</span></div>';

$date_range = in_array($servizio['tipolim'], [11, 12]);

$lista_date = [];
for ($i = $date[0]; $i <= $date[1]; $i += 86400) {

	$lista_date[] = $i;

	//$selected = ($date_selezionate[$i] ?? false) ? 'selected' : '';
	//$box_date .= '<div class="date ' . $selected . '" data-date="' . $i . '"><span>' . date('j', $i) . ' ' . $giorniita3[date('N', $i)] . '</span></div>';
}

if (count(array_filter($date_selezionate))) {
	$testo_servizio = $aggiunta->get_setup_servizio_uikit($IDservizio, $opzioni);
} else {
	$testo_servizio = '<div class="vflex" style="justify-content: center; align-items: center; font-size:15px; height: 100%;">Selezionare almeno una data per continuare</div>';
}

$testo = '


<input type="hidden" value="' . $IDservizio . '" id="IDservizio_scelto">
<input type="hidden" value="' . ($date_range ? 1 : 0) . '" id="range_attivo">

	' . genera_slider_date_uikit(['lista_date' => $lista_date, 'date_selezionate' => $date_selezionate]) . '




<div style="margin: 20px 0;"> ' . $testo_servizio . ' </div>';
echo $testo;
?>

<script>
var range=$('#range_attivo').val();

var data_inizio=0;
var data_fine=0;


if(range==1){
	if($('.premi_data.data_selezionata[data-time]').length>0){
		data_inizio=$('.premi_data.data_selezionata[data-time]:first').data('time');
		data_fine=$('.premi_data.data_selezionata[data-time]:last').data('time');
	}



	$('.premi_data').on('click',function(){
		var time=parseInt($(this).data('time'));

		if(data_inizio==0){
			data_inizio=time;
			$('.premi_data[data-time="'+time+'"]').addClass('data_selezionata');
		}else{

			if(data_fine==0){
				if(time>data_inizio){
					data_fine=time;
					seleziona_date_multiple();
				}else{
					$('.premi_data').removeClass('data_selezionata');
					$('.premi_data[data-time="'+time+'"]').addClass('data_selezionata');
					data_inizio=time;
				}

			}else{
				data_fine=0;
				data_inizio=time;
				$('.premi_data').removeClass('data_selezionata');
				$('.premi_data[data-time="'+time+'"]').addClass('data_selezionata');
			}
		}
	});

}else{
	$('.premi_data').on('click',function(){
		var time=parseInt($(this).data('time'));
		if($(this).hasClass(('data_selezionata'))){
			$(this).removeClass('data_selezionata');
		}else{
			$(this).addClass('data_selezionata');
		}
		ricarica_pagina();
		//seleziona_date_multiple();
	});
}


function seleziona_date_multiple(){
	$('.premi_data').removeClass('data_selezionata');
	for(i=data_inizio;i<=data_fine;i+=86400){
		$('.premi_data[data-time="'+i+'"]').addClass('data_selezionata');
	}

	ricarica_pagina();

}


function ricarica_pagina(){

	var opzioni={};
	opzioni['date']=[]
	var IDservizio=$('#IDservizio_scelto').val();
    var lista_riferimenti=$('#IDriferimento').val().split(",");
    var tipo_riferimento=$('#tipo_riferimento').val();

    $('.premi_data.data_selezionata[data-time]').each(function(){
    	var time=$(this).data('time');
    	opzioni['date'].push(time);
    });

	ricarica_setup_servizio(lista_riferimenti,tipo_riferimento,IDservizio,opzioni);
}


function aggiungi_servizio_setup(list){
	$( "#aggiungi-servizio").unbind( "click" );
	 event.stopImmediatePropagation();

	var IDservizio=$('#IDservizio_scelto').val();

	if( $('.premi_data.data_selezionata[data-time]').length==0){
		apri_notifica({'messaggio':'nessuna data specificata','status':'danger'});
		return;
	}


	if (!list || Object.keys(list).length == 0) { return; }
	let args = {
		IDservizio: IDservizio,
		tipo: 1,
		list: list
	};


	mod_add_serv(4, IDservizio, args, (res) => {
		if (/1$/.test(res)) {
			 apri_notifica({'messaggio':'Servizio inserito con successo','status':'succes'});
			 chiudi_picker();
		}

	});

}




</script>
