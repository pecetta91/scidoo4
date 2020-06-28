<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';
$IDutente = $_SESSION['ID'];
$IDstruttura = $_SESSION['IDstruttura'];

$dati = $_POST['dati'] ?? [];

$IDvendita = $dati['IDvendita'];
$IDoggetto = $dati['IDoggetto'];
$tab = $dati['tab'] ?? 'oggetto';

$testo = '';
$dati_vendita = reset(get_vendite([['IDvendita' => $IDvendita]])['dati']);

$oggetto = $dati_vendita['oggetti'][$IDoggetto];

switch ($tab) {
case 'oggetto':

	if ($oggetto['IDriferimento'] == 0) {

		$nome_oggetto = '<input class="uk-input ricerca_prenotante_preventivo" id="ricerca_servizio" type="text" autocomplete="off_ric" placeholder="Ricerca Prodotto Negozio" data-url="' . base_url() . '/config/searchbox/prodotti_negozio.php">

			<script>
				$("#ricerca_servizio").searchBox({
					onclick:function (args){
						mod_negozio(16, ' . $IDvendita . ', [args.id,' . $IDoggetto . '], 10,()=>{carica_tab_oggetti(\'oggetto\');})
					}
				});
			</script>';
	} else {
		$nome_oggetto = $oggetto['nome_oggetto'];
	}

	$info_prodotto = '';
	if ($oggetto['tipo_riferimento'] == 13) {

		$info_prodotto = '
		<div class="div_list_uk uk_grid_div" uk-grid   onclick="modifica_oggetto_vendita_modal(this)" data-tipo="17">
			    <div class="uk-width-1-2 lista_grid_nome uk-first-column">Quantita</div>
			    <div class="uk-width-expand uk-text-right lista_grid_right"> ' . $oggetto['quantita'] . ' <i class="fas fa-chevron-right"></i></div>
			</div>

			<div class="div_list_uk uk_grid_div" uk-grid   onclick="modifica_oggetto_vendita_modal(this)" data-tipo="18">
			    <div class="uk-width-1-2 lista_grid_nome uk-first-column">Prezzo unitario</div>
			    <div class="uk-width-expand uk-text-right lista_grid_right"> € ' . format_number($oggetto['prezzo_unitario']) . '  <i class="fas fa-chevron-right"></i></div>
			</div>';

	}

	$stati = genera_stati_voucher();

	$testo = '

	<div class="div_uk_divider_list" style="margin-top:5px !important;    margin-left: 0 !important;  margin-bottom: 10px !important;"> Oggetto </div>

			<div class="div_list_uk uk_grid_div  " uk-grid  >
			    <div class="uk-width-1-2 lista_grid_nome uk-first-column">Oggetto</div>
			    <div class="uk-width-expand uk-text-right lista_grid_right">' . $nome_oggetto . '</div>
			</div>

			' . $info_prodotto . '

			' . ($oggetto['tipo_riferimento'] == 7 ? '

				<div id="stato_voucher" style="display:none;">
				<ul class="uk-list uk-list-divider uk-picker-bot" style="padding:5px 20px;" onchange="(r)=>{mod_negozio(20, [' . $oggetto['IDvoucher'] . ',' . $IDvendita . '],r, 10,()=>{carica_tab_oggetti(\'oggetto\');});}">
					' . genera_select_uikit($stati, $oggetto['stato']) . '</ul>
				</div>

				<div class="div_list_uk uk_grid_div" uk-grid   onclick="carica_content_picker(' . "'stato_voucher'" . ')">
				    <div class="uk-width-1-2 lista_grid_nome uk-first-column">Stato</div>
				    <div class="uk-width-expand uk-text-right lista_grid_right"> ' . $stati[$oggetto['stato']] . '  <i class="fas fa-chevron-right"></i></div>
				</div>	' : '') . '

			<div class="div_list_uk uk_grid_div  " uk-grid  onclick="modifica_oggetto_vendita_modal(this)" data-tipo="19">
			    <div class="uk-width-1-2 lista_grid_nome uk-first-column">Totale</div>
			    <div class="uk-width-expand uk-text-right lista_grid_right"> € ' . format_number($oggetto['totale']) . '  <i class="fas fa-chevron-right"></i></div>
			</div> ';

	if ($oggetto['tipo_riferimento'] == 7) {
		$IDvoucher = $oggetto['IDvoucher'];
		$dati_voucher = reset(get_voucher([['IDvoucher' => $IDvoucher]])['dati']);
		$data = ($dati_voucher['time_attivazione'] ? date('d/m/Y', $dati_voucher['time_attivazione']) : '');
		$data_noformat = ($dati_voucher['time_attivazione'] ? date('d-m-Y', $dati_voucher['time_attivazione']) : '');

		$testo .= '	<div class="div_uk_divider_list" style="margin-top:5px !important;    margin-left: 0 !important;  margin-bottom: 10px !important;"> Dettagli Voucher </div>


			<div class="div_list_uk uk_grid_div  " uk-grid  >
			    <div class="uk-width-1-2 lista_grid_nome uk-first-column">Titolo</div>
			    <div class="uk-width-expand uk-text-right lista_grid_right"> <input type="text" class="uk-input input_cli  uk-form-small uk-text-right" onchange="mod_negozio(11, ' . $IDvoucher . ',this, 11);" value="' . $dati_voucher['titolo'] . '"> </div>
			</div>


			<div class="div_list_uk uk_grid_div  " uk-grid  >
			    <div class="uk-width-1-2 lista_grid_nome uk-first-column">Data Attivazione</div>
			    <div class="uk-width-expand uk-text-right lista_grid_right">

			      <input class="uk-input input_cli  uk-form-small uk-text-right" id="preventivo_partenza" style="border: 1px solid#e1e1e1;  border-radius: 3px;"
			   	   type="text" data-testo="Scadenza" onclick="apri_modal(this,1);" onchange=" mod_negozio(12,' . $IDvoucher . ',this.value, 10,()=>{carica_tab_oggetti(\'oggetto\');})"   value="' . $data . '"  data-noformat="' . $data_noformat . '"  readonly> </div>
			</div>

			<div class="div_list_uk uk_grid_div  " uk-grid  >
			    <div class="uk-width-1-2 lista_grid_nome uk-first-column">Scadenza</div>
			    <div class="uk-width-expand uk-text-right lista_grid_right" style="color: #000; font-weight: 600; font-size: 14px;"> ' . date('d/m/Y', $dati_voucher['time_scadenza']) . ' </div>
			</div>


			<div class="div_list_uk uk_grid_div  " uk-grid  >
			    <div class="uk-width-1-2 lista_grid_nome uk-first-column">Cliente Mittente</div>
			    <div class="uk-width-expand uk-text-right lista_grid_right"> <input type="text" class="uk-input input_cli  uk-form-small uk-text-right"  value="' . $dati_voucher['cliente_mittente'] . '" onchange="mod_negozio(6, ' . $IDvoucher . ',this, 11);"> </div>
			</div>

			<div class="div_list_uk uk_grid_div  " uk-grid  >
			    <div class="uk-width-1-2 lista_grid_nome uk-first-column">Cliente Destinatario</div>
			    <div class="uk-width-expand uk-text-right lista_grid_right"> <input type="text" class="uk-input input_cli  uk-form-small uk-text-right" value="' . $dati_voucher['cliente_destinatario'] . '" onchange="mod_negozio(7, ' . $IDvoucher . ',this, 11);"> </div>
			</div>



			<div class="div_list_uk uk_grid_div  " uk-grid  >
			    <div class="uk-width-1-2 lista_grid_nome uk-first-column">Email Destinatario</div>
			    <div class="uk-width-expand uk-text-right lista_grid_right"> <input type="text" class="uk-input input_cli  uk-form-small uk-text-right" value="' . $dati_voucher['email_cliente_destinatario'] . '" onchange="mod_negozio(24, ' . $IDvoucher . ',this, 11);"> </div>
			</div>


			<div class="div_list_uk uk_grid_div  " uk-grid  >
			    <div class="uk-width-expand lista_grid_nome uk-first-column">
			    <input  type="checkbox" ' . ($dati_voucher['conto_vendita'] == 1 ? 'checked="checked"' : '') . ' onchange="mod_negozio(9, ' . $IDvoucher . ',this, 7);" > Conto Vendita</div>
			</div> ';
	}

	break;
case 'valore_voucher':

	$IDvoucher = $oggetto['IDvoucher'];

	$dati_voucher = reset(get_voucher([['IDvoucher' => $IDvoucher]])['dati']);
	$testo .= '
	<input type="hidden" value="' . $IDvoucher . '" id="IDvoucher">
	<div class="div_uk_divider_list" style="margin-top:5px !important;    margin-left: 0 !important;  margin-bottom: 10px !important;"> Valore </div>
				<div class="div_list_uk uk_grid_div  " uk-grid  >
				    <div class="uk-width-1-2 lista_grid_nome uk-first-column">Valore</div>
				    <div class="uk-width-expand uk-text-right lista_grid_right">€ ' . format_number($dati_voucher['prezzo']) . ' </div>
				</div>

			<div class="div_list_uk uk_grid_div  " uk-grid  onclick="modifica_oggetto_vendita_modal(this)" data-tipo="19">
			    <div class="uk-width-1-2 lista_grid_nome uk-first-column">Prezzo</div>
			    <div class="uk-width-expand uk-text-right lista_grid_right"> € ' . format_number($dati_voucher['prezzo_vendita']) . '  <i class="fas fa-chevron-right"></i></div>
			</div>



				<div style="padding:5px 10px;margin-top:10px" class="tab_content_tasto_menu">
						<div class="tasto_menu_default ' . ($dati_voucher['tipo_voucher'] == 0 ? 'selected' : '') . ' " onclick="
						mod_negozio(50, [' . $IDvoucher . ',' . $dati_voucher['IDvendita'] . '],0, 10,()=>{carica_tab_oggetti(\'valore_voucher\')});"   style="padding:5px;"> Voucher Servizio</div>
						<div class="tasto_menu_default ' . ($dati_voucher['tipo_voucher'] == 1 ? 'selected' : '') . '" onclick="
							mod_negozio(50, [' . $IDvoucher . ',' . $dati_voucher['IDvendita'] . '],1,10,()=>{carica_tab_oggetti(\'valore_voucher\')});"   style="padding:5px;"> Voucher Monetario </div>
				</div>

				<div style="margin-top:20px">';

	switch ($dati_voucher['tipo_voucher']) {
	case 0:

		$testo .= '

			<div class="div_uk_divider_list" style="margin-top:5px !important;    margin-left: 0 !important;  margin-bottom: 10px !important;">Componenti
					<button style=" float:right ;border: none;  border-radius: 3px;   background: #2a6eff;
					padding: 5px 10px;  color: #fff; font-size: 14px;  font-weight: 600;     text-transform: uppercase;;"  onclick="carica_modal_lista_servizi()">Aggiungi</button>
			</div>	 ';

		foreach ($dati_voucher['componenti'] as $dato) {

			$funzione_elimina = 'mod_negozio(14, ' . $IDvoucher . ',' . $dato['ID'] . ',10,()=>{ carica_tab_oggetti(\'valore_voucher\');});';

			$testo .= ' <div uk-grid  class="uk_grid_div div_list_uk"  onclick="' . $funzione_elimina . '">
						<div class="uk-width-auto lista_grid_numero" >' . $dato['quantita'] . '</div>
						    <div class="uk-width-expand lista_grid_nome uk-text-truncate" >' . $dato['servizio'] . '<br><span>' . ($dato['persone'] > 0 ? 'Per ' . $dato['persone'] . ' ' . txtpersone($dato['persone']) : '') . '</span></div>
					        <div class="uk-width-auto uk-text-right  lista_grid_right" style="color:#d93535" >  <i class="fas fa-times"></i>   </div>
						</div> 	 ';
		}

		break;
	case 1:

		$testo .= '
			<div class="div_list_uk uk_grid_div  " uk-grid  onclick="modifica_prezzo_voucher_modal()"  >
			    <div class="uk-width-1-2 lista_grid_nome uk-first-column">Totale</div>
			    <div class="uk-width-expand uk-text-right lista_grid_right"> € ' . format_number($dati_voucher['prezzo']) . '  <i class="fas fa-chevron-right"></i></div>
			</div>';
		break;
	}
	$testo .= '</div>';

	break;

case 'spedizione':
	break;
}

echo $testo;
?>
