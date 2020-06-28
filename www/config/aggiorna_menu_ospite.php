<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../config/connecti.php';
require_once __DIR__ . '/../../config/funzioni.php';
$IDprenotazione = $_SESSION['IDstrpren'];
$IDstruttura = get_IDstruttura_from_IDpren($IDprenotazione);

$tipo_pagina = $_POST['idstep'];
$lang = $_SESSION['lang'] ?? 0;

$arr_dati = (isset($_POST['arr_dati']) ? $_POST['arr_dati'] : []);

$testo_menu = '';
$no_menu = 0;
$extra_men = '';
switch ($tipo_pagina) {

case 0:
	$no_menu = 1;
	break;

case 1: //info prenotazione
	$left_men = '<button class="uk-button uk-button-default " onclick="goBack()" id="navigation_back" style="display:block"> <i class="fas fa-chevron-left"></i> ' . traduci('Prenotazione', $lang) . '</button>';

	$extra_men .= '
		<div class="tab_scelta_sottomenu">
			<ul  uk-tab="connect: #switcher; animation: uk-animation-fade;swiping:false" style="margin-bottom:0;    place-content: center;background:#fff">
		        <li class="uk-active" onclick="switch_tab_prenotazione_ospite(0)" ><a href="#" style="color:#2574ec" >Timeline</a></li>
		        <li  onclick="switch_tab_prenotazione_ospite(1)" ><a href="#" style="color:#2574ec">' . traduci('Informazioni', $lang) . '</a></li>
		        <li  onclick="switch_tab_prenotazione_ospite(2)"  ><a href="#" style="color:#2574ec">' . traduci('Servizi Prenotati', $lang) . '</a></li>
		      </ul>
		</div>';

	break;
case 4: // recensioni

	$recensioni = get_lista_recensioni([['IDoggetto' => $IDprenotazione, 'tipo_oggetto' => 0]], $IDstruttura);

	$left_men = '<button class="uk-button uk-button-default " onclick="goBack()" id="navigation_back" style="display:block"> <i class="fas fa-chevron-left"></i> ' . traduci('Recensioni', $lang) . '</button>';
	if (empty($recensioni)) {
		$right_men = ' <span class="uk-navbar-item" onclick="navigation_ospite(6,0,0,0)"  > <i class="fas fa-plus"></i></span> ';
	}
	break;

case 5: //dettaglio recensione

	$left_men = '<button class="uk-button uk-button-default " onclick="goBack()" id="navigation_back" style="display:block"> <i class="fas fa-chevron-left"></i> ' . traduci('Dettaglio Recensione', $lang) . '</button>';

	break;

case 6: //nuova recensione

	$left_men = '<button class="uk-button uk-button-default " onclick="goBack()" id="navigation_back" style="display:block"> <i class="fas fa-chevron-left"></i> ' . traduci('Nuova Recensione', $lang) . '</button>';

	break;
case 7: //servizi
	$left_men = '<button class="uk-button uk-button-default " onclick="goBack()" id="navigation_back" style="display:block"> <i class="fas fa-chevron-left"></i> ' . traduci('Elenco Servizi', $lang) . '</button>';
	break;
case 8: //servizi nella sottotipologia

	$IDsottotip = $arr_dati['IDsottotipologia'] ?? 0;
	$left_men = '<button class="uk-button uk-button-default " onclick="goBack()" id="navigation_back" style="display:block"> <i class="fas fa-chevron-left"></i> ' . traducis('', $IDsottotip, 3, $lang) . '</button>';
	break;
case 9:
	//$left_men = '<button class="uk-button uk-button-default " onclick="goBack()" id="navigation_back" style="display:block"> <i class="fas fa-chevron-left"></i> Il conto</button>';
	break;
case 10:
	$dati = get_prenotazioni(['0' => ['IDprenotazione' => $IDprenotazione]], null, [], $IDstruttura)['dati'][$IDprenotazione];

	$left_men = '<button class="uk-button uk-button-default " onclick="goBack()" id="navigation_back" style="display:block"> <i class="fas fa-chevron-left"></i> ' . ($dati['notti'] != 0 ? 'Check-in Online' : traduci('Condividi App', $lang)) . '</button>';
	break;
case 11:

	$IDpagamento = $arr_dati['IDpagamento'] ?? 0;
	$titolo_pagamento = traducis('', $IDpagamento, 28, $lang);

	$left_men = '<button class="uk-button uk-button-default " onclick="goBack()" id="navigation_back" style="display:block"> <i class="fas fa-chevron-left"></i> ' . $titolo_pagamento . '</button>';
	break;
case 12:
	$left_men = '<button class="uk-button uk-button-default " onclick="goBack()" id="navigation_back" style="display:block"> <i class="fas fa-chevron-left"></i> ' . traduci('I tuoi Servizi', $lang) . '</button>';
	break;
case 13:
	$left_men = '<button class="uk-button uk-button-default " onclick="goBack()" id="navigation_back" style="display:block"> <i class="fas fa-chevron-left"></i> ' . traduci('Numeri Utili', $lang) . '</button>';
	$extra_men .= '
		<div class="tab_scelta_sottomenu">
			<ul  uk-tab="connect: #switcher; animation: uk-animation-fade;swiping:false" style="margin-bottom:0;    place-content: center;background:#fff">
		        <li class="uk-active" onclick="switch_tab_prenotazione_ospite(0)" ><a href="#" style="color:#2574ec" >Numeri Utili</a></li>
		        <li  onclick="switch_tab_prenotazione_ospite(1)" ><a href="#" style="color:#2574ec">' . traduci('Contatti', $lang) . '</a></li>
		        <li  onclick="switch_tab_prenotazione_ospite(2)"  ><a href="#" style="color:#2574ec">' . traduci('Come Arrivare', $lang) . '</a></li>
		      </ul>
		</div>';

	break;
case 14:
	$IDinformazione = $arr_dati['IDinformazione'] ?? 0;
	$titolo_informazione = traducis('', $IDinformazione, 16, $lang);
	$left_men = '<button class="uk-button uk-button-default " onclick="goBack()" id="navigation_back" style="display:block"> <i class="fas fa-chevron-left"></i> ' . $titolo_informazione . '</button>';
	break;
case 15:
	$left_men = '<button class="uk-button uk-button-default " onclick="goBack()" id="navigation_back" style="display:block"> <i class="fas fa-chevron-left"></i> ' . traduci('Galleria', $lang) . '</button>';
	break;
case 16:
	$left_men = '<button class="uk-button uk-button-default " onclick="goBack()" id="navigation_back" style="display:block"> <i class="fas fa-chevron-left"></i> ' . traduci('Luoghi ed Eventi', $lang) . '</button>';
	break;
case 17:
	$left_men = '<button class="uk-button uk-button-default " onclick="goBack()" id="navigation_back" style="display:block"> <i class="fas fa-chevron-left"></i> ' . traduci('Temperatura Alloggio', $lang) . '</button>';
	break;
case 18:
	$IDluogo = $arr_dati['IDluogo'] ?? 0;

	$luoghi = estrai_luoghi([['IDluogo' => $IDluogo]], $IDstruttura)[$IDluogo];

	$left_men = '<button class="uk-button uk-button-default " onclick="goBack()" id="navigation_back" style="display:block"> <i class="fas fa-chevron-left"></i> ' . $luoghi['nome'] . '</button>';

	$right_men = '<span class="uk-margin-small-right uk-navbar-item"   onclick="location.href=' . "' https://maps.google.com/?q=" . $luoghi['latitudine'] . "," . $luoghi['longitudine'] . "  '" . '"> <i class="fas fa-map-marker-alt"></i></span> ';

	break;
case 19:

	$IDitinerario = $arr_dati['IDitinerario'] ?? 0;

	$itinerario = estrai_itinerari_giornalieri([['IDitinerario' => $IDitinerario]], $IDstruttura)[$IDitinerario];

	$left_men = '<button class="uk-button uk-button-default " onclick="goBack()" id="navigation_back" style="display:block"> <i class="fas fa-chevron-left"></i> ' . $itinerario['nome'] . '</button>';

	break;
case 20:
	$left_men = '<button class="uk-button uk-button-default " onclick="goBack()" id="navigation_back" style="display:block"> <i class="fas fa-chevron-left"></i> ' . traduci('Itinerari', $lang) . '</button>';
	break;
case 21:
	$IDservizio = $arr_dati['IDservizio'] ?? 0;
	$left_men = '<button class="uk-button uk-button-default " onclick="goBack()" id="navigation_back" style="display:block;"> <i class="fas fa-chevron-left"></i> <span style="text-transform:capitalize;font-size:13px">' . traducis('', $IDservizio, 1, $lang) . '</span></button>';
	break;
case 22:
	$left_men = '<button class="uk-button uk-button-default " onclick="goBack()" id="navigation_back" style="display:block;"> <i class="fas fa-chevron-left"></i>
		 ' . traduci('Menù', $lang) . ' </button>';
	break;
case 23:
	$left_men = '<button class="uk-button uk-button-default " onclick="goBack()" id="navigation_back" style="display:block;"> <i class="fas fa-chevron-left"></i>
		 ' . traduci('Seleziona Sala', $lang) . ' </button>';

	break;
case 24:
	$left_men = '<button class="uk-button uk-button-default " onclick="goBack()" id="navigation_back" style="display:block;"> <i class="fas fa-chevron-left"></i>
		 ' . traduci('Ordinazione', $lang) . ' </button>';

	$right_men = '<span class="uk-margin-small-right uk-navbar-item uk-position-relative"  onclick="apri_carrello_ordinazione_web_app()" > <div class="notifica_carrello"></div><i class="fas fa-shopping-cart"></i></span> ';
	break;
default:
	$left_men = '<button class="uk-button uk-button-default " onclick="goBack()" id="navigation_back" style="display:block"> <i class="fas fa-chevron-left"></i> ' . traduci('Indietro', $lang) . '</button>';
	break;
}

$testo_menu = $extra_men . '
	<div uk-sticky="sel-target: .uk-navbar-container; cls-active: uk-navbar-sticky"    class="menu_dinamic uk_menu_nav">
		<nav class="uk-navbar-container navbar_ospite" uk-navbar  >
			<div class="uk-navbar-left uk-navbar-item">
					' . (isset($left_men) ? $left_men : '') . '
			</div>

			<div class="uk-navbar-center">
				' . (isset($center_men) ? $center_men : '') . '

			</div>

			<div class="uk-navbar-right">
					' . (isset($right_men) ? $right_men : '') . '
			</div>

			' . (isset($componenti_interni) ? $componenti_interni : '') . '
		</nav>
	</div>';

$stampa_menu = ($no_menu == 0 ? $testo_menu : '');

echo $stampa_menu;
