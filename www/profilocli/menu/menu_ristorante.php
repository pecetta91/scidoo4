<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';
$IDprenotazione = $_SESSION['IDstrpren'];
$IDstruttura = get_IDstruttura_from_IDpren($IDprenotazione);
$lang = $_SESSION['lang'] ?: 0;

$dati_prenotazione = get_prenotazioni(['0' => ['IDprenotazione' => $IDprenotazione]], null, [], $IDstruttura);
$dettaglio_prenotazione = $dati_prenotazione['dati'][$IDprenotazione];

$dati_struttura = genera_sotto_strutture_lista($IDstruttura)[0];

$lista_servizi = get_dati_conto_riferimento($IDprenotazione, 0, [], $IDstruttura)['arr_servizi'];

$info_servizi = get_info_from_IDserv(null, null, $IDstruttura);

$serv_orari = [];
if (!empty($lista_servizi)) {

	$collegamenti = [];
	foreach ($lista_servizi as $id => $value) {
		foreach ($value['componenti'] as $componente) {
			$c = $componente['IDaddebito_collegato_riferimento'];
			if ($c) {
				$componente['parent'] = $value;
				$collegamenti[$c][$componente['IDaddebito_collegato']] = $componente;
			}
		}
	}

	foreach ($lista_servizi as $IDaddebito => $dati) {

		if (!in_array($dati['IDtipo'], [1])) {continue;}
		//print_html($dati['componenti']);

		$time = time0($dati['time']);
		$IDsottotip = $dati['IDsottotip'];
		$da_compilare = null;
		$qta = array_sum(array_column($dati['componenti'], 'qta'));

		$lista_menu = ristorante_genera_ordinazione($IDstruttura, $IDprenotazione, 0, $dati['IDsottotip'], $dati['time']);

		$IDservizio = $dati['IDserv'];

		if (!empty($lista_menu['piatti_menu'])) {
			foreach ($lista_menu['piatti_menu'] as $dati_piatti) {
				if (!isset($dati_piatti['IDserv'])) {
					$da_compilare = 1;
					break;
				}
			}
		}

		$textserv = '

			<div class="uk_grid_div div_list_uk  toggle_accordion"  uk-grid   data-id="' . $IDaddebito . '"    onclick="visualizza_menu_addebito_webapp(' . $IDaddebito . ',()=>{navigation_ospite(22,0)})">
			    <div class="uk-width-expand lista_grid_nome uk-text-truncate" >' . $dati['nome_servizio'] . '<br/>
			  		<span class="uk-text-muted uk-text-small" > N.' . $qta . ' ' . txtpersone($qta, $lang) . ' '
		. ($da_compilare ? '-  <span style="color:#ce0000">' . traduci('Da Completare', $lang) . '  </span>' : '') . '</span>  </div>
		        <div class="uk-width-auto  uk-text-right lista_grid_right c000"> ' . traduci('Vedi Menu', $lang) . ' <i class="fas fa-chevron-right"></i> </div>
			</div>   ';

		if (!isset($serv_orari[time0($dati['time'])])) {
			$serv_orari[time0($dati['time'])] = ' ';
		}

		$serv_orari[time0($dati['time'])] .= $textserv;

	}
}

if (!empty($serv_orari)) {
	foreach ($serv_orari as $time => $cont) {
		$testo .= ' <div class="div_uk_divider_list"> ' . dataita($time) . ' ' . date('Y', $time) . ' </div> ' . $cont;
	}
}

echo $testo;

?>

