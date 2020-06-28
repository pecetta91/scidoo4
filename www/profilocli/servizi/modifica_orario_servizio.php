<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';
$IDprenotazione = $_SESSION['IDstrpren'];
$IDstruttura = get_IDstruttura_from_IDpren($IDprenotazione);
$lang = $_SESSION['lang'] ?: 0;

$dettaglio_prenotazione = get_prenotazioni(['0' => ['IDprenotazione' => $IDprenotazione]], null, [], $IDstruttura)['dati'][$IDprenotazione];

$IDaddebito = $_POST['IDaddebito'];

$data_modifica = (isset($_POST['data_modifica']) ? $_POST['data_modifica'] : 0);

$time0 = time_struttura();

$lista_servizi = get_dati_conto_riferimento($IDprenotazione, 0, [], $IDstruttura)['arr_servizi'];

$servizio = array_filter($lista_servizi, function ($arg) use ($IDaddebito) {return $arg['IDaddebito'] == $IDaddebito;});

$IDservizio = $servizio[$IDaddebito]['IDserv'];
$time_servizio = $servizio[$IDaddebito]['time'];

$modi = $servizio[$IDaddebito]['modi'];

$time_selezionato = time0($time_servizio);
if ($data_modifica) {
	$time_selezionato = time0($data_modifica);
	$time_servizio = 0;
}

$dati_serv = get_info_from_IDserv($IDservizio, null, $IDstruttura);

$nome_servizio = $dati_serv['nome_servizio'];

$lista_utilizzo = stato_utilizzo_servizio($IDstruttura, $dati_serv, $dettaglio_prenotazione['checkin'], $dettaglio_prenotazione['checkout'], $IDaddebito, 0);

$lista_giorni = [];
$lista_presenza = [];
if ($dettaglio_prenotazione['notti']) {

	for ($time_data = time0($dettaglio_prenotazione['checkin']); $time_data <= time0($dettaglio_prenotazione['checkout']); $time_data += 86400) {
		if ($time_data >= $time0) {
			$presenza = 0;
			if (isset($lista_utilizzo[$time_data])) {
				foreach ($lista_utilizzo[$time_data] as $dati) {
					if (!empty($dati['persone'])) {
						foreach ($dati['persone'] as $valore) {
							if ($valore > 0) {
								$presenza = 1;
								break;
							}
						}
					}
					if ($presenza) {break;}
				}
			}

			if ($presenza) {continue;}

			$lista_giorni[$time_data] = dataita($time_data);
		}
	}

	$riga_data = '
	<div id="giorni_servizio" style="display:none;"><ul class="uk-list uk-list-divider uk-picker-bot" style="padding:5px 20px;"
			onchange="(r)=>{modifica_orario_ospite( ' . $IDaddebito . ',r)}">' . genera_select_uikit($lista_giorni, $time_selezionato) . '</ul>
	</div>
	<div class="div_list_uk uk_grid_div uk-grid" uk-grid=""  onclick="carica_content_picker(' . "'giorni_servizio'" . ')" >
	    <div class="uk-width-1-3 lista_grid_nome uk-first-column">' . traduci('Data', $lang) . '</div>
	    <div class="uk-width-expand uk-text-right lista_grid_right"> ' . dataita($time_selezionato) . '   <i class="fas fa-chevron-right"></i>	 </div>
	</div>';

} else {
	$riga_data = '
	<div class="div_list_uk uk_grid_div uk-grid" uk-grid >
	    <div class="uk-width-1-3 lista_grid_nome uk-first-column">' . traduci('Data', $lang) . '</div>
	    <div class="uk-width-expand uk-text-right lista_grid_right"> ' . dataita($time_servizio) . '  </div>
	</div>';
}

$orari = personale_disponibile_servizio($IDstruttura, $IDservizio, $time_selezionato, $IDprenotazione, 0);

$time_option = '';
if (!empty($orari)) {
	foreach ($orari as $time_dispo => $dati) {

		$time_giorno = $time_selezionato + $time_dispo;

		$time_option .= '<li onclick="chiudi_picker();modifica_orario_IDaddebito_ospite(this)" value="' . $time_giorno . '" data-sala="' . $dati['IDsala'] . '"  data-personale="' . $dati['IDpersonale'] . '">' . date('H:i', $time_giorno) . ' </li>';
	}
}

$testo = '
<input type="hidden" id="IDaddebito" value="' . $IDaddebito . '">

<div class="div_uk_divider_list" style="margin-top:0px !important;" aria-expanded="true">' . $nome_servizio . '</div>

' . $riga_data . '


<div id="time_servizio" style="display:none;"><ul class="uk-list uk-list-divider uk-picker-bot" style="padding:5px 20px;" >' . $time_option . '</ul></div>



<div class="div_list_uk uk_grid_div " uk-grid   onclick="carica_content_picker(' . "'time_servizio'" . ')" >
    <div class="uk-width-1-3 lista_grid_nome uk-first-column">' . traduci('Ora', $lang) . '</div>
    <div class="uk-width-expand uk-text-right lista_grid_right"> ' . ($modi > 0 ? ($time_servizio ? date('H:i', $time_servizio) : '--') : '--') . ' <i class="fas fa-chevron-right"></i></div>
</div> ';

$picker = '
<div class="nav navbar_picker_flex" >
 	<div onclick="chiudi_picker()"><i class="fas fa-times icona_chiudi" ></i></div>
 	<div style="margin-top:5px;padding-right:10px"></div>
</div>



<div class="content" style="margin-top:0">
	<div id="dettagli_tab" style="padding-top:5px;">
		' . $testo . '

	</div>
</div>';

echo $picker;

?>

<script>

	function modifica_orario_IDaddebito_ospite(el){
		var IDaddebito=$('#IDaddebito').val()


		var time=$(el).attr('value');
		var sala=$(el).data('sala');
		var personale=$(el).data('personale')


		mod_ospite(21,IDaddebito,[sala,time,personale],10,()=>{chiudi_picker();modifica_orario_ospite(IDaddebito)});


	}

</script>
