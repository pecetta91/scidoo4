<?php
//header('Access-Control-Allow-Origin: *');
// $IDstruttura;
// $IDutente;
// $arr_dati; Dati passati dalla richiesta

// $extra_men; html prima del menu
// $left_men; html sinistra del menu
// $center_men; html centro del menu
// $right_men; html destra del menu
// $componenti_interni; html interno al menu

$left_men = '<button class="uk-button uk-button-default" onclick="goBack()" id="navigation_back" style="display:block"> <i class="fas fa-chevron-left"></i>   Indietro</button>';

$sezione = $arr_dati['sezione'] ?? 0;

if ($sezione == 1 or $sezione == 2 or $sezione == 3) {
	$time_ricerca = $arr_dati['time'] ?? $_SESSION['ristorante']['time_ricerca'] ?? time_struttura();
	if (!is_numeric($time_ricerca)) {$time_ricerca = strtotime(implode('-', array_reverse(explode('/', $time_ricerca))));}
	$right_men = '
	<div   class="div_cambia_data_nav" data-testo="Data" onclick="apri_modal(this,1);" value="' . date('d/m/Y', $time_ricerca) . '" data-noformat="' . date('d-m-Y', $time_ricerca) . '" id="navigation_data" onchange="navigation(27,{sezione: ' . $sezione . ', time: this.value});" > <i class="fas fa-chevron-down" style="margin-right: 2px;vertical-align: middle;"></i> ' . dataita6($time_ricerca) . '  </div>';

}

$componenti_interni = '<div class="tab_scelta_sottomenu">
	<ul uk-tab="connect: #switcher; animation: uk-animation-fade;swiping:false" class="uk-tab uk_tab_pulizie">
		<li class="' . ($sezione == 0 ? 'uk-active' : '') . '" onclick="navigation(27,{sezione: 0});"><a href="#">Tavoli</a></li>
		<li class="' . ($sezione == 1 ? 'uk-active' : '') . '" onclick="navigation(27,{sezione: 1});"><a href="#">Prenotazioni</a></li>
		<li class="' . ($sezione == 2 ? 'uk-active' : '') . '" onclick="navigation(27,{sezione: 2});"><a href="#">Menu</a></li>
		<li class="' . ($sezione == 3 ? 'uk-active' : '') . '" onclick="navigation(27,{sezione: 3});"><a href="#">Riepilogo</a></li>
		<li class="' . ($sezione == 4 ? 'uk-active' : '') . '" onclick="navigation(27,{sezione: 4});"><a href="#">Asporto</a></li>
	</ul>
	</div>';
