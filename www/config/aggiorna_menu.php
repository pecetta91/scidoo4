<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../config/connecti.php';
require_once __DIR__ . '/../../config/funzioni.php';
$IDutente = $_SESSION['ID'];
$IDstruttura = $_SESSION['IDstruttura'];

$tipo_pagina = $_POST['idstep'];

$val = '';

array_escape($_REQUEST);
$arr_dati = $_REQUEST['arr_dati'] ?? [];

$telematico = get_telematico($IDstruttura);

$testomen = '';
$left_men = '';
$right_men = '';
$center_men = '';
$extra_men = '';
$componenti_interni = '';

switch ($tipo_pagina) {

case 4: //home struttura

	$dati_struttura = get_dati_struttura($IDstruttura);

	$left_men = '
	<a href="#offcanvas" uk-toggle="" class="uk-navbar-toggle   scidoo_hidden_medium" style="color: #2542d9">
		<i class="fas fa-bars" style="margin-right:5px;"></i></a>
	<div class="testo_sinistra" style="font-family:nova_regular,sans-serif !important"> ' . $dati_struttura['nome_struttura'] . '</div> ';

	$right_men = '<img data-src="' . base_url() . '/sito_scidoo/logo_scritta_white.png" width="70" alt="SCIDOO" uk-img style="margin-right:10px"> ';

	$center_men = '<button class="uk-button uk-button-default " onclick="goBack()" id="navigation_back">  <i class="fas fa-chevron-left"></i>  Indietro</button>';

	break;

case 5: //calendario

	$categorie_sel = (isset($_SESSION['categorie_calendario']) ? $_SESSION['categorie_calendario'] : []);
//onclick="nascondi_riga_calendario('.$IDcategoria.');"
	$categorie = get_categorie_multistruttura($IDstruttura, -1);
	$testo_categorie = '';
	if (!empty($categorie)) {
		foreach ($categorie as $IDsotto_struttura => $dati) {
			foreach ($dati['categorie'] as $IDcategoria => $nome_cat) {
				$testo_categorie .= ' <li  onclick="nascondi_riga_calendario(this);"> <input  style="width:17px;height:17px;pointer-events:none" class="uk-radio"
				type="checkbox" value="' . $IDcategoria . '"  ' . (in_array($IDcategoria, $categorie_sel) ? 'checked="checked"' : '') . '> ' . $nome_cat . '  </li> ';
			}
		}
	}

	$time = (isset($arr_dati['time']) ? $arr_dati['time'] : time_struttura());
	list($yy, $mm, $dd) = explode('-', date('Y-m-d', $time));
	$time_avanti = mktime(0, 0, 0, $mm + 1, 1, $yy);
	$time_indietro = mktime(0, 0, 0, $mm - 1, 1, $yy);

	$left_men = '<button class="uk-button uk-button-default " onclick="goBack()" id="navigation_back" style="display:block">  <i class="fas fa-chevron-left"></i>  Calendario </button>';

	$right_men = '
	<div id="filtro_categorie" style="display:none;"> <ul class="uk-list uk-list-divider uk-picker-bot" style="padding:5px 20px;"  >' . $testo_categorie . '</ul></div>
		<span class="  uk-navbar-item"  onclick="navigation(22,0,()=>{ modifica_anagrafica_nuovo_preventivo()})"  style="padding:0 10px"> <i class="fas fa-plus"></i></span>
		<span class="uk-navbar-item" id="indietro_cal"  onClick="navigation(5, {time:' . $time_indietro . '}, 1, 0);"  style="padding:0 10px"> <i class="fas fa-chevron-left"></i></span>
		<span class=" uk-navbar-item"  id="avanti_cal"  onClick="navigation(5, {time:' . $time_avanti . '}, 1, 0);"  style="padding:0 10px"> <i class="fas fa-chevron-right"></i></span>

		<span class=" uk-navbar-item" onclick="carica_content_picker(' . "'filtro_categorie'" . ')" style="padding:0 10px"> <i class="fas fa-filter"></i></span>
	    <span class=" uk-navbar-item" onclick="configurazioni_calendario()" style="padding:0 10px"> <i class="fas fa-cog"></i></span>';

	break;

case 6: //detpren

	$IDprenotazione = $arr_dati['IDprenotazione'] ?? 0;

	$messaggi = estrai_messaggi([['IDprenotazione' => $IDprenotazione]], '', $IDstruttura);

	$messaggi_da_leggere = array_sum(array_column($messaggi, 'numero_messaggi_da_leggere'));

	$nome = estrainome($IDprenotazione) ?? 'Indietro';

	$left_men = '<button class="uk-button uk-button-default " onclick="goBack()" id="navigation_back" style="display:block">  <i class="fas fa-chevron-left"></i>  ' . $nome . '</button>';

	$right_men = '
	<span class="uk-margin-small-right uk-navbar-item"   onclick=" apri_chat_struttura({IDobj:' . $IDprenotazione . ',tipoobj:0});" style="position:relative">

	' . ($messaggi_da_leggere > 0 ? '<div class="numero_not_giorn" style="    right: 0px;  top: 10px;  line-height: 12px;">' . $messaggi_da_leggere . '</div>' : '') . '
	 <i class="fas fas fa-comments"></i></span>


	<span class="uk-margin-small-right uk-navbar-item"  onclick="aggiunta_servizio({IDriferimento:' . $IDprenotazione . ',tipo_riferimento:0},[],()=>{cambia_tab_prenotazione(' . $IDprenotazione . ',\'conto\')})" > <i class="fas fa-plus"></i></span>

	<span class="uk-margin-small-right uk-navbar-item"   onclick="funzioni_det_pren(' . $IDprenotazione . ')"> <i class="fas fa-cog"></i></span>

	';

	$extra_men .= '
		<div class="tab_scelta_sottomenu">
			<ul  uk-tab="connect: #switcher; animation: uk-animation-fade;swiping:false" style="margin-bottom:0;    place-content: center;">
		        <li class="uk-active" onclick=" cambia_tab_prenotazione(' . $IDprenotazione . ',\'informazioni\')"><a href="#">Dettagli</a></li>
		        <li onclick="cambia_tab_prenotazione(' . $IDprenotazione . ',\'orari\')"><a href="#">Orari</a></li>
		        <li onclick="cambia_tab_prenotazione(' . $IDprenotazione . ',\'conto\')"><a href="#">Conto</a></li>';
	if ($telematico == 0) {$extra_men .= '<li onclick="cambia_tab_prenotazione(' . $IDprenotazione . ',\'pagamenti\')"><a href="#">Pagamenti</a></li>';}

	$extra_men .= ' </ul> </div>';

	break;
case 7:

	$IDinfo_prenonatizione = $arr_dati['IDinfopren'] ?? 0;
	$IDcliente = $arr_dati['IDcliente'] ?? 0;

	if ($IDcliente) {
		$query = "SELECT CONCAT_WS(' ',nome,cognome) FROM schedine WHERE IDstr='$IDstruttura' AND ID='$IDcliente'";
		$result = mysqli_query($link2, $query);
		$row = mysqli_fetch_row($result);
		$nome = $row['0'];
	} else {
		$query = "SELECT CONCAT_WS(' ',s.nome,s.cognome) FROM infopren as i LEFT JOIN schedine as s ON s.ID=i.IDcliente WHERE i.IDstr='$IDstruttura' AND i.ID='$IDcliente' AND i.pers='1' ";
		$result = mysqli_query($link2, $query);
		$row = mysqli_fetch_row($result);
		$nome = 'Nuovo Ospite';
		if ($row['0']) {
			$nome = $row['0'];
		}
		$right_men = ' <span class="uk-margin-small-right uk-navbar-item"><i class="fas fa-search"></i></span> ';
	}

	$left_men = '<button class="uk-button uk-button-default " onclick="goBack()" id="navigation_back" style="display:block">  <i class="fas fa-chevron-left"></i>  ' . $nome . '</button>';

	break;

case 8:

	$left_men = '<button class="uk-button uk-button-default " onclick="goBack()" id="navigation_back" style="display:block">  <i class="fas fa-chevron-left"></i>  Dettaglio Servizio</button>';

	break;

case 9: //centro benessere

	$time_navigation = (isset($_SESSION['tempo_benessere']) ? $_SESSION['tempo_benessere'] : time_struttura());

	$left_men = '<button class="uk-button uk-button-default " onclick="goBack()" id="navigation_back" style="display:block">  <i class="fas fa-chevron-left"></i>  Centro Benessere</button>';

	$right_men = '
	<div  class="div_cambia_data_nav" data-testo="Data" onclick="apri_modal(this,1);" value="' . date('d/m/Y', $time_navigation) . '" data-noformat="' . date('d-m-Y', $time_navigation) . '" id="navigation_data" onchange="navigation(9,{time:this.value},0)" >	 <i class="fas fa-chevron-down" style="margin-right: 2px;vertical-align: middle;"></i> ' . dataita6($time_navigation) . '  </div>';

	break;

case 10: //centro benessere giorno

	$time = (isset($_SESSION['tempo_benessere']) ? $_SESSION['tempo_benessere'] : time_struttura());

	$IDsottotip = $arr_dati['IDsotto'];
	$IDsala_selezionata = $arr_dati['IDsala'] ?? 0;

	$query = "SELECT sottotipologia FROM sottotipologie WHERE IDmain='4' AND ID='$IDsottotip' AND IDstr='$IDstruttura'";
	$result = mysqli_query($link2, $query);
	$row = mysqli_fetch_row($result);
	$nome = $row['0'];

	$elenco_sale = '';
	$cont = 0;
	$query = "SELECT s.ID,s.maxp,s.nome FROM sale as s
	JOIN saleassoc as sc  ON sc.ID=s.ID WHERE sc.IDsotto='$IDsottotip' ORDER BY s.ordine";
	$result = mysqli_query($link2, $query);
	while ($row = mysqli_fetch_row($result)) {
		$elenco_sale .= ' <li class="' . ($IDsala_selezionata == $row['0'] ? 'uk-active' : '') . '" onclick="cambia_sala_centro(' . $row['0'] . ')"><a >' . $row['2'] . '</a></li>';
	}

	$left_men = '<button class="uk-button uk-button-default " onclick="goBack()" id="navigation_back" style="display:block">  <i class="fas fa-chevron-left"></i> ' . $nome . '</button>';

	$ora_benessere = ($_SESSION['step_30m_benessere'] ?? 0);

	$right_men = '

	<span  style="color: #fff;border-radius:3px; margin-right: 20px; ' . ($ora_benessere == 1 ? '  font-weight: 600;  padding: 0 2px;background:#fff;color:#2574ec; ' : '') . '" onclick="cambia_tipo_data(this)" data-ora="' . ($ora_benessere == 1 ? 0 : 1) . '">30M</span>

	<div  class="div_cambia_data_nav" data-testo="Data" onclick="apri_modal(this,1);" value="' . date('d/m/Y', $time) . '" data-noformat="' . date('d-m-Y', $time) . '" id="navigation_data" onchange="cambia_time_benessere()" >	 <i class="fas fa-chevron-down" style="margin-right: 2px;vertical-align: middle;"></i> ' . dataita6($time) . '  </div>';

	$extra_men .= '
		<div class="tab_scelta_sottomenu">
			<ul  uk-tab="connect: #switch_sale; animation: uk-animation-fade;swiping:false"  class="uk_tab_pulizie no_before" style="padding: 5px 0;  ">
		       ' . $elenco_sale . '
		     </ul> </div>';

	break;

case 11:

	break;
case 12:

	$left_men = '<button class="uk-button uk-button-default " onclick="goBack()" id="navigation_back" style="display:block">  <i class="fas fa-chevron-left"></i> Domotica</button>';
	$extra_men .= '
		<div class="tab_scelta_sottomenu">
			<ul  uk-tab="connect: #switcher; animation: uk-animation-fade;swiping:false" style="margin-bottom:0;    place-content: center;" class="no_before">
		        <li class="uk-active" ><a href="#">Strumenti</a></li>
		        <li ><a href="#">Alloggi</a></li>
			</ul> </div>';

	break;

case 13: //pulizie

	$time_navigation = (isset($_SESSION['tempo_pul']) ? $_SESSION['tempo_pul'] : time_struttura());

	$tipo_pulizia = (isset($_SESSION['pulizia_selezionata']) ? $_SESSION['pulizia_selezionata'] : 0);

	$left_men = '<button class="uk-button uk-button-default " onclick="goBack()" id="navigation_back" style="display:block">  <i class="fas fa-chevron-left"></i> Indietro </button>';

	$center_men = '
	<input type="hidden" id="tipo_pulizia" value="' . $tipo_pulizia . '">
	<div class="uk-button-group div_btn_pulizie" >
		<button onclick="cambia_tipo_pulizia(this);" data-tipo="0"  class="uk-button  pul ' . ($tipo_pulizia == 0 ? 'active' : '') . '">Day</button>
		<button onclick="cambia_tipo_pulizia(this);" data-tipo="1"  class="uk-button  pul ' . ($tipo_pulizia == 1 ? 'active' : '') . '">Week</button>
	</div> ';

	$right_men = '
	<div   class="div_cambia_data_nav" data-testo="Data" onclick="apri_modal(this,1);" value="' . date('d/m/Y', $time_navigation) . '" data-noformat="' . date('d-m-Y', $time_navigation) . '" id="navigation_data" onchange="aggiorna_pulizie()" >	 <i class="fas fa-chevron-down" style="margin-right: 2px;vertical-align: middle;"></i> ' . dataita6($time_navigation) . '  </div>';

	break;

case 14:

	$time_navigation = (isset($_SESSION['time_arrivi']) ? $_SESSION['time_arrivi'] : time_struttura());

	$left_men = '<button class="uk-button uk-button-default " onclick="goBack()" id="navigation_back" style="display:block">  <i class="fas fa-chevron-left"></i>  Arrivi</button>';

	$right_men = '

		<div  class="div_cambia_data_nav" data-testo="Data" onclick="apri_modal(this,1);" value="' . date('d/m/Y', $time_navigation) . '" data-noformat="' . date('d-m-Y', $time_navigation) . '" id="navigation_data" onchange="navigation(14,{time:this.value},0)" " >	 <i class="fas fa-chevron-down" style="margin-right: 2px;vertical-align: middle;"></i> ' . dataita6($time_navigation) . '  </div>';

	break;

case 15:

	$left_men = '<button class="uk-button uk-button-default " onclick="goBack()" id="navigation_back" style="display:block">  <i class="fas fa-chevron-left"></i>  Elenco Clienti</button>';

	$componenti_interni = '
	<div class="ricerca_bar_fixed">
	    <div class="uk-inline"  >
	        <span class="uk-form-icon"><i class="fas fa-search"></i></span>
	        <input class="uk-input filtro_ricerca_clienti" data-name="testo" value="' .
		(isset($_SESSION['filtri_clienti']['testo']) ? $_SESSION['filtri_clienti']['testo'] : '') . '"  type="text" placeholder="Cerca Cliente" onKeyUp="ricerca_clienti();" />
	    </div>
	</div>';

	break;
case 16: //prenotazioni
	$left_men = '<button class="uk-button uk-button-default " onclick="goBack()" id="navigation_back" style="display:block">  <i class="fas fa-chevron-left"></i> Elenco Prenotazioni</button>';

	$right_men = ' <span class="uk-margin-small-right uk-navbar-item"  onclick="navigation(22,0,7,0)"><i class="fas fa-plus"></i></span>
		<span class="uk-margin-small-right uk-navbar-item"   onclick="picker_filtro(\'prenotazione\')"><i class="fas fa-filter"></i></span>  ';

	$componenti_interni = '
	<div class="ricerca_bar_fixed">
	    <div class="uk-inline"  >
	        <span class="uk-form-icon" ><i class="fas fa-search"></i></span>
	        <input class="uk-input filtro_ricerca_prenotazione" data-name="testo"  value="' .
		(isset($_SESSION['filtri_prenotazione']['testo']) ? $_SESSION['filtri_prenotazione']['testo'] : '') . '" type="text" placeholder="Cerca Prenotazione" onKeyUp="ricerca_prenotazioni();" />
	    </div>
	</div>';

	break;

case 17:

	break;

case 18:
	$time = (isset($_SESSION['tempo_benessere']) ? $_SESSION['tempo_benessere'] : time_struttura());
	$IDsottotip = $arr_dati['IDsotto'];
	$tipo = $arr_dati['tipo'] ?? 1;

	$personale_trattamenti = ($_SESSION['visualizza_personale_benessere'] ?? 0);

	$left_men = '<button class="uk-button uk-button-default " onclick="goBack()" id="navigation_back" style="display:block">  <i class="fas fa-chevron-left"></i> Trattamenti</button>';

	$right_men = ' <span  style="' . ($personale_trattamenti == 1 ? '  border-radius: 3px; background:  #fff;  padding:  2px 5px; ' : '') . ' margin-right:10px;"
		onclick="visualizza_personale(this)"  data-personale="' . ($personale_trattamenti == 1 ? 0 : 1) . '"><i class="fas fa-user"
		 style="' . ($personale_trattamenti == 1 ? 'color:#2574ec !important' : '') . '"></i></span>


	<div  class="div_cambia_data_nav" data-testo="Data" onclick="apri_modal(this,1);" value="' . date('d/m/Y', $time) . '" data-noformat="' . date('d-m-Y', $time) . '" id="navigation_data" onchange="cambia_time_trattamenti()" >	 <i class="fas fa-chevron-down" style="margin-right: 2px;vertical-align: middle;"></i> ' . dataita6($time) . '  </div>';

	$extra_men .= '
		<div style="width: 100%;  background: #f9f8f9;   position: fixed;   top: 50px;  z-index: 11;">
			<ul  uk-tab="connect: #switch_sale; animation: uk-animation-fade;swiping:false"  class="uk_tab_pulizie no_before" style="padding: 5px 0; ">
		     <li class="' . ($tipo == 0 ? 'uk-active' : '') . '" onclick="cambia_tipo_trattamenti(0)"><a >Planning</a></li>
		     <li class="' . ($tipo == 1 ? 'uk-active' : '') . '" onclick="cambia_tipo_trattamenti(1)"><a >Sospesi</a></li>
		     <li class="' . ($tipo == 2 ? 'uk-active' : '') . '" onclick="cambia_tipo_trattamenti(2)"><a >Arrivi</a></li>

		     </ul> </div>';

	break;
case 19: //sposta prenotazione
	$left_men = '<button class="uk-button uk-button-default " onclick="goBack()" id="navigation_back" style="display:block">  <i class="fas fa-chevron-left"></i> Sposta Prenotazione</button>';

	break;
case 20:

	$IDtipo = $arr_dati['0'];
	$tipo = $arr_dati['1'];
	$metodo_inserimento = $arr_dati['2'];
	$totale = $arr_dati['3'];

	switch ($metodo_inserimento) {
	case 1:
		$tipopag = 'Saldo Finale';
		break;
	case 2:
		$tipopag = 'Acconto';
		break;
	case 14:
		$tipopag = 'Caparra';
		break;
	}

	$left_men = '<button class="uk-button uk-button-default " onclick="goBack()" id="navigation_back" style="display:block">  <i class="fas fa-chevron-left"></i> Pagamento ' . $tipopag . '</button>';

	break;
case 21:

	$left_men = '<button class="uk-button uk-button-default " onclick="goBack()" id="navigation_back" style="display:block">  <i class="fas fa-chevron-left"></i> Preventivi</button>';

	$right_men = ' <span class="uk-margin-small-right uk-navbar-item" onclick="navigation(22,0,()=>{modifica_anagrafica_nuovo_preventivo()})" > <i class="fas fa-plus"></i></span>

	<span class="uk-margin-small-right uk-navbar-item"   onclick="picker_filtro(\'preventivo\')"><i class="fas fa-filter"></i></span>';

	$extra_men .= ' <div class="tab_scelta_sottomenu" id="tab_filtri_preventivo">  </div> ';

	break;

case 22:
	//' . (($_SESSION['preventivo']['mostra_tutto'] ?? 0) == 1 ? '0' : '1') . '
	$left_men = '<button class="uk-button uk-button-default " onclick="controlla_preventivo();" id="navigation_back" style="display:block">  <i class="fas fa-chevron-left"></i>  Preventivo</button>';

	$right_men = '<button class="uk-button uk-button-default button_nav_uk" onclick="inizia_ricerca_preventivo()"> <i class="fas fa-sync-alt"></i></button>

	 <label style="color:#fff"><input class="apple-switch" type="checkbox" ' . (($_SESSION['preventivo']['mostra_tutto'] ?? 0) == 1 ? 'checked="checked"' : '') . '
	 onchange="gestione_preventivo(3, { campo: \'mostra_tutto\',value:this.value},()=>{inizia_ricerca_preventivo()})"> Mostra Tutto</label>';

	break;

case 23: //vendite

	$left_men = '<button class="uk-button uk-button-default " onclick="goBack()" id="navigation_back" style="display:block"> <i class="fas fa-chevron-left"></i> Vendite</button>';

	$right_men = ' <span class="uk-margin-small-right uk-navbar-item" onclick="
	mod_negozio(2, 0, 0, 10,(result)=>{ navigation(24,{IDvendita:result},()=>{carica_tab_vendite(result,\'carrello\')},0);}); "  ><i class="fas fa-plus"></i></span>

	<span class="uk-margin-small-right uk-navbar-item"   onclick="picker_filtro(\'vendite\')"><i class="fas fa-filter"></i></span>';

	$componenti_interni = '

		<div   class="ricerca_bar_fixed">
		    <div class="uk-inline" >
		        <span class="uk-form-icon"><i class="fas fa-search"></i></span>
		        <input class="uk-input filtro_ricerca_vendite"  type="text" placeholder="Cerca " data-name="ricerca" value="' .
		(isset($_SESSION['filtri_vendite']['ricerca']) ? $_SESSION['filtri_vendite']['ricerca'] : '') . '"  onKeyUp="filtri_ricerca_vendite()"/>
		    </div>
		</div>';

	break;
case 24: // dettaglio vendita

	$IDvendita = $arr_dati['IDvendita'] ?? 0;

	$vendita = get_vendite([['IDvendita' => $IDvendita]])['dati'][$IDvendita];
	$nome_cliente_vendita = ($vendita['nome_cliente'] ? $vendita['nome_cliente'] : 'Indietro');

	$left_men = '<button class="uk-button uk-button-default " onclick="goBack()" id="navigation_back" style="display:block"><i class="fas fa-chevron-left"></i>  ' . $nome_cliente_vendita . '</button>';
	$right_men = ($vendita['eliminabile'] == 1 ? '<span class="uk-margin-small-right uk-navbar-item"  style="color:#CB0003 !important" onclick="
		mod_negozio(3, ' . $IDvendita . ', [0,0], 10,()=>{goBack();})" ><i class="fas fa-minus-circle"></i></span>' : '');

	$extra_men .= '
		<div class="tab_scelta_sottomenu">
			<ul  uk-tab="connect: #switcher; animation: uk-animation-fade;swiping:false" style="margin-bottom:0;    place-content: center;">
		        <li class="uk-active" onclick="carica_tab_vendite(' . $IDvendita . ',\'carrello\')"><a href="#">Carrello</a></li>
		        <li onclick="carica_tab_vendite(' . $IDvendita . ',\'pagamenti\')"><a href="#">Pagamenti</a></li>
		        <li  onclick="apri_chat_struttura({IDobj:' . $IDvendita . ',tipoobj:0});"><a href="#">Chat</a></li>
		      </ul>
		</div>';

	break;

case 25: //prezzi giornalieri
	$left_men = '<button class="uk-button uk-button-default " onclick="$(' . "'.disponibilita_modal'" . ').remove();goBack()" id="navigation_back" style="display:block"> <i class="fas fa-chevron-left"></i> Prezzi Giornalieri</button>';

	$time_navigation = (isset($_SESSION['time_prezzi']) ? $_SESSION['time_prezzi'] : time_struttura());

	$right_men = '
	<div  class="div_cambia_data_nav" data-testo="Data" onclick="apri_modal(this,1);" value="' . date('d/m/Y', $time_navigation) . '" data-noformat="' . date('d-m-Y', $time_navigation) . '" id="navigation_data" onchange="navigation(25,{time:this.value},()=>{ $(\'disponibilita_modal\').remove();},0)" >	 <i class="fas fa-chevron-down" style="margin-right: 2px;vertical-align: middle;"></i> ' . dataita6($time_navigation) . '  </div>';

	break;
case 26: //Spiaggia / Garage

	$time = (isset($arr_dati['time']) ? $arr_dati['time'] : time_struttura());
	$tipologia = $arr_dati['tipologia'];

	list($yy, $mm, $dd) = explode('-', date('Y-m-d', $time));

	$time_avanti = mktime(0, 0, 0, $mm + 1, 1, $yy);
	$time_indietro = mktime(0, 0, 0, $mm - 1, 1, $yy);

	$nome = 'Indietro';
	switch ($tipologia) {
	case 28:
		$nome = 'Garage';
		break;
	case 29:
		$nome = 'Spiaggia';
		break;
	}

	$left_men = '<button class="uk-button uk-button-default " onclick="goBack()" id="navigation_back" style="display:block">  <i class="fas fa-chevron-left"></i>  ' . $nome . ' </button>';

	$right_men = '
		<span class="  uk-navbar-item"  onclick=" "  style="padding:0 10px"> <i class="fas fa-plus"></i></span>

		<span class="uk-navbar-item" id="indietro_cal"  onClick="navigation(26, {tipologia:' . $tipologia . ',time:' . $time_indietro . '}, 8, 0);"  style="padding:0 10px"> <i class="fas fa-chevron-left"></i></span>
		<span class="uk-navbar-item" id="avanti_cal"  onClick="navigation(26, {tipologia:' . $tipologia . ',time:' . $time_avanti . '}, 8, 0);"  style="padding:0 10px"> <i class="fas fa-chevron-right"></i></span> ';

	break;
case 27:
	require __DIR__ . '/../struttura/ristorante/barra_menu.php';
	break;
case 28:
	require __DIR__ . '/../struttura/ristorante/barra_ordinazione.php';
	break;

case 29:
	$left_men = '<button class="uk-button uk-button-default " onclick="goBack()" id="navigation_back" style="display:block">  <i class="fas fa-chevron-left"></i> Chat Struttura</button>';

	break;
case 30:
	$left_men = '<button class="uk-button uk-button-default " onclick="goBack()" id="navigation_back" style="display:block">  <i class="fas fa-chevron-left"></i> Prenotazioni BE/CM</button>';
	$right_men = '
		<span class="uk-margin-small-right uk-navbar-item"   onclick="picker_filtro(\'prenotazioni_be_cm\')"><i class="fas fa-filter"></i></span>  ';

	break;
default:

	$left_men = '<button class="uk-button uk-button-default " onclick="goBack()" id="navigation_back" style="display:block"> <i class="fas fa-chevron-left"></i>   Indietro</button>';

	break;
}

$testomen = $extra_men . '
	<div uk-sticky="sel-target: .uk-navbar-container; cls-active: uk-navbar-sticky"    class="menu_dinamic uk_menu_nav">
		<nav class="uk-navbar-container sfondo_navbar" uk-navbar  >
			<div class="uk-navbar-left uk-navbar-item">
				' . $left_men . '
			</div>

			<div class="uk-navbar-center">
				' . $center_men . '

			</div>

			<div class="uk-navbar-right">
				' . $right_men . '
			</div>

			' . $componenti_interni . '
		</nav>
	</div>

';

echo $testomen;
