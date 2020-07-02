<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../config/connecti.php';
require_once __DIR__ . '/../../config/funzioni.php';
$IDprenotazione = $_SESSION['IDstrpren'];
$IDstruttura = get_IDstruttura_from_IDpren($IDprenotazione);
$lang = $_SESSION['lang'] ?: 0;

$dati = $_POST['arr_dati'] ?? [];
$IDpagamento = $dati['IDpagamento'] ?? 0;
$tipo_pagamento = $dati['tipo_pagamento'] ?? 0;
$IDdeposito = $dati['IDdeposito'] ?? 0;

$dettaglio_prenotazione = get_prenotazioni(['0' => ['IDprenotazione' => $IDprenotazione]], null, [], $IDstruttura)['dati'][$IDprenotazione];

$prezzo_deposito = 0;
foreach ($dettaglio_prenotazione['depositi'] as $dati) {
	if ($dati['ID'] != $IDdeposito) {continue;}

	$prezzo_deposito = $dati['prezzo'];
}

$prezzo_paypal = number_format($prezzo_deposito, 2, '.', '');
$carta = '

<div id="anno" style="display:none;">
	<ul class="uk-list uk-list-divider uk-picker-bot" style="padding:5px 20px;" onchange="(r,s)=>{cambia_valore_html_select(\'select_anno\',r,s);}">
		' . genera_select_uikit(genera_anni_uikit(10), []) . ' </ul>
</div>

<div id="mese" style="display:none;">
	<ul class="uk-list uk-list-divider uk-picker-bot" style="padding:5px 20px;" onchange="(r,s)=>{cambia_valore_html_select(\'select_mese\',r,s);}">
		' . genera_select_uikit(genera_mesi_uikit(), []) . ' </ul>
</div>



<div uk-grid class="uk-margin-small uk_grid_div div_list_uk">
	<div class="uk-width-expand   lista_grid_nome">' . traduci('Numero di Carta', $lang) . '</div>
	<div class="uk-width-expand uk-text-right lista_grid_right">
   <input class="uk-input input_cli  uk-form-small" type="text"  value="" id="numero_carta"   onchange=""  placeholder="' . traduci('Numero di Carta', $lang) . '">
	</div>
</div>


<div uk-grid class="uk-margin-small uk_grid_div div_list_uk">
	<div class="uk-width-expand   lista_grid_nome">' . traduci('Anno', $lang) . '</div>
	<div class="uk-width-expand uk-text-right lista_grid_right filtro_ricerca_vendite" data-name="oggetto_ricerca" value="0" data-select="0" onclick="carica_content_picker(\'anno\')" id="select_anno"  style="text-decoration:underline">' . traduci('Seleziona', $lang) . '</div>
</div>



<div uk-grid class="uk-margin-small uk_grid_div div_list_uk">
	<div class="uk-width-expand   lista_grid_nome">' . traduci('Mese', $lang) . '</div>
 	<div class="uk-width-expand uk-text-right lista_grid_right filtro_ricerca_vendite" data-name="oggetto_ricerca" value="0" data-select="0" onclick="carica_content_picker(\'mese\')" id="select_mese"  style="text-decoration:underline">' . traduci('Seleziona', $lang) . '</div>
</div>



<div uk-grid class="uk-margin-small uk_grid_div div_list_uk">
	<div class="uk-width-expand   lista_grid_nome">' . traduci('Intestatario', $lang) . '</div>
	<div class="uk-width-expand uk-text-right lista_grid_right">

   <input class="uk-input input_cli  uk-form-small" type="text"  value="" id="intestatario"   onchange=""  placeholder="' . traduci('Intestatario', $lang) . '">
	</div>
</div>';

$testo_pagamento = '';
switch ($tipo_pagamento) {
case 2:
	$pagamenti_proprieta = get_dati_pagamenti($IDstruttura, [['IDpagamento' => $IDpagamento]]);
	$codice_cliente = $pagamenti_proprieta[$IDpagamento]['proprieta']['CODICE_CLIENTE']['valore'] ?? '';
//codice paypal
	$testo_pagamento = '
<div style="text-align:center">
<span style="font-size:15px;">Paga con Paypal o Carta di Credito/Bancomat</span>
<div id="paypal-button-container" style="z-index:2;position:relative"></div>
</div>';
	$testo_pagamento .= "
<script>
		paypal.Button.render({
		env: 'production', // sandbox | production
			style: {
			label: 'buynow',
			fundingicons: true, // optional
			branding: true, // optional
			size:  'large', // small | medium | large | responsive
			shape: 'rect',   // pill | rect
			color: 'gold'   // gold | blue | silve | black
		},

		client: {
			sandbox:    'AQxQqDiaa1zCbQ2sM6VTbaTYdXrCNPNv26xgtTiN3Q5Y7pMu9UWp0MKdH6YJ2sld7LsXG84CADOlqocD',
			production: '" . $codice_cliente . "'
		},

		payment: function(data, actions) {
			return actions.payment.create({
				payment: {
					transactions: [ { amount: { total: '" . $prezzo_paypal . "', currency: 'EUR' } } ]
				}
			});
		},

		onAuthorize: function(data, actions) {


		return actions.payment.execute().then(function() {
			controllo_pagamento();

		});

		}

		}, '#paypal-button-container');
</script>";

	break;

default:

	$campi_informazione = '';
	$descrizione_pagamento = '';
	$informazioni_pagamenti = get_info_pagamenti($IDpagamento, $tipo_pagamento, $IDstruttura);
	foreach ($informazioni_pagamenti as $IDproprieta => $valore) {

		if ($valore['IDpagamento_modello']) {
			$informazione_proprieta = strip_tags(stripslashes(traducis($valore['info'] ?? '', $valore['IDpagamento_modello'], 29, $lang)));
			if ($valore['tipo_input'] != 0) {
				$descrizione_pagamento .= '<div style="margin:10px ">' . $informazione_proprieta . '</div> ';
			} else {
				$campi_informazione .= '

					<div uk-grid class="uk-margin-small uk_grid_div div_list_uk">
						<div class="uk-width-auto   lista_grid_nome"><strong >' . $valore['nome'] . ' </strong> </div>
						<div class="uk-width-expand uk-text-right lista_grid_right">
						' . $informazione_proprieta . '
						</div>
					</div>  ';
			}
		}
	}
	/*
		$txt_info .= '<div class="content-block-title titlecontentnew" ><strong>' . traduci('Casuale', $lang, 1) . '</strong></div>

			<div class="row rowlist no-gutter impriga infobon">
				<div class="fw400 fs15" style="line-height:23px">' . traduci('Prenotazione N.', $lang, 1) . ' ' . $IDprentxt . ' di ' . estrainome($IDpren) . ' (' . traduci('Arrivo', $lang, 1) . ': ' . dataita($time) . ' ' . date('Y', $time) . ')</div>
				</div>
			<br/> ';
	*/

	$causale = ($tipo_pagamento == 3 ? '<div class="div_uk_divider_list" style="margin-top:0px !important;">' . traduci('Causale', $lang) . '</div>

		<div style="margin:10px 0;padding:0 5px;">' . traduci('Prenotazione N.', $lang, 1) . ' ' . $dettaglio_prenotazione['numero'] . ' di ' . $dettaglio_prenotazione['nome_cliente'] . '
		(' . traduci('Arrivo', $lang, 1) . ': ' . dataita($dettaglio_prenotazione['checkin']) . ' ' . date('Y', $dettaglio_prenotazione['checkin']) . ')</div>

		' : '');

	$testo_pagamento = ($tipo_pagamento == 1 ? $carta : '') . '<div class="info_pag"> ' . $campi_informazione . $descrizione_pagamento . ' </div>' . $causale . '

	<div style="    width: 100%;  position: fixed;  bottom: 0;   left: 0;">
		<button style="    background: #2574ec!important;  border: none;  width: 100%; padding: 15px;   color: #fff;  font-size: 25px;  outline: none;" onclick="
		controllo_pagamento()">' . traduci('Segnala Pagamento', $lang) . '</button>
	</div> 	';

	break;
}

$testo = '
<input type="hidden" id="IDpagamento" value="' . $IDpagamento . '">
<input type="hidden" id="tipo_pagamento" value="' . $tipo_pagamento . '">
<input type="hidden" id="IDdeposito" value="' . $IDdeposito . '">



<div class="div_uk_divider_list" style="margin-top:0px !important;">' . traduci('Conferma Pagamento', $lang) . '</div>


			 <div class=" uk_grid_div div_list_uk" uk-grid  >
			    <div class="uk-width-auto lista_grid_nome">' . traduci('Deposito', $lang) . '</div>
			    <div class="uk-width-expand uk-text-right lista_grid_right" > ' . format_number($prezzo_deposito) . ' € </div>
			</div>


' . $testo_pagamento . '<div style="padding-bottom:150px"></div>';

echo $testo;

?>
