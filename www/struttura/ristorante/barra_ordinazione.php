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

$IDprenotazione = $arr_dati['pren'] ?? null;
$IDsottotip = $arr_dati['sottotip'] ?? 0;
$numero_tavolo = $arr_dati['num_tavolo'] ?? 0;

if ($IDprenotazione and $numero_tavolo) {
	$sottotipologie = get_sottotipologie(1, $IDstruttura);
	$sottotipologie = array_replace(['Bar'], $sottotipologie);
	$center_men = '<div style="display: none;"><ul class="uk-list uk-list-divider uk-picker-bot" onchange="(r)=>{navigation(28,' . "{pren: $IDprenotazione, sottotip: r, num_tavolo: $numero_tavolo}" . ');}">' . genera_select_uikit($sottotipologie) . '</ul></div>';
	$center_men .= '<div style="color:#fff;" onclick="carica_content_picker($(this).prev());">' . ($sottotipologie[$IDsottotip] ?? 'Bar') . ' <i class="fas fa-chevron-down"></i></div>';
}

$left_men = '<button class="uk-button uk-button-default" onclick="goBack()" id="navigation_back" style="display:block"> <i class="fas fa-chevron-left"></i> Indietro</button>';

// $right_men = '<div class="uk-navbar-item" onclick="ristorante.selezione_piatti(0,0)"><i class="fas fa-plus"></i></div>';
