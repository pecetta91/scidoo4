<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';
$IDprenotazione = $_SESSION['IDstrpren'];
$IDstruttura = get_IDstruttura_from_IDpren($IDprenotazione);
$IDaddebito = $_POST['IDaddebito'];

$lang = $_SESSION['lang'] ?: 0;

$lista_servizi = get_dati_conto_riferimento($IDprenotazione, 0, [], $IDstruttura)['arr_servizi'];

$menu = array_filter($lista_servizi, function ($arg) use ($IDaddebito) {return $arg['IDaddebito'] == $IDaddebito;})[$IDaddebito];

$IDservizio = $menu['IDserv'];
$IDsottotip = $menu['IDsottotip'];

$time = time0($menu['time']);

//$info_servizi = get_info_from_IDserv(null, null, $IDstruttura);

//$piatti_possibili = ristorante_get_piatti_menu($IDservizio, $time)[$IDservizio];

$servizi_inclusi = 0;
$servizi_pagamento = 0;
$lista_menu = ristorante_genera_ordinazione($IDstruttura, $IDprenotazione, 0, $IDsottotip, $time);
//print_html($lista_menu['piatti_menu']);
$current_sottotip = null;
foreach ($lista_menu['piatti_menu'] as $dati) {
	if (!empty($dati['IDserv'])) {
		$servizi_inclusi++;
	}
}

if (!empty($lista_menu['prodotti'])) {
	//print_html($lista_menu['prodotti']);
	foreach ($lista_menu['prodotti'] as $dati) {
		$prezzo = array_sum(array_column($dati['componenti'], 'prezzo'));
		$servizi_pagamento += $prezzo;
	}
}

$testo = '';
if (($servizi_inclusi > 0) || ($servizi_pagamento > 0)) {
	$testo = '
		<div class="div_list_uk uk_grid_div" uk-grid >
			    <div class="uk-width-expand lista_grid_nome uk-first-column" >
			    		<i class="fas fa-shopping-cart"></i> ' . $servizi_inclusi . ' ' . traduci('Inclusi', $lang) . '    ' . ($servizi_pagamento > 0 ? '+ ' . number_format($servizi_pagamento) . ' €' : '') . '
	  				</div>
	    		<div class="uk-width-auto  uk-text-right lista_grid_right c000 "  >  ' . traduci('Visualizza Ordine', $lang) . '  <i class="fas fa-chevron-right"></i></div>
			</div>

	  ';
}

echo $testo;
?>