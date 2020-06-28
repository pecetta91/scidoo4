<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';

$IDstruttura = $_SESSION['IDstruttura'];

$IDprenotazione = $_POST['IDprenotazione'];

$testo = '';

$lista_servizi = get_dati_conto_riferimento($IDprenotazione, 0, [])['arr_servizi'];

$serv_orari = [];
if (!empty($lista_servizi)) {
	foreach ($lista_servizi as $IDaddebito => $dati) {
		if (in_array($dati['tipolim'], [4, 5, 6, 7, 8])) {continue;}

		$qta = array_sum(array_column($dati['componenti'], 'qta'));

		$onclick = 'disponibilita_servizio({IDaddebito:' . $IDaddebito . ',tipo_riferimento:0},0,()=>{cambia_tab_prenotazione(' . $IDprenotazione . ',1)})';

		switch ($dati['IDtipo']) {
		case 1:
		case 2:
		case 12:
		case 22:
		case 10:
		case 13:
		case 22:
		case 23:
		case 28:
		case 29:

			break;
		case 4:

			break;
		default:
			$onclick = '';
			break;
		}

		$textserv = '
		<div class="uk_grid_div div_list_uk  "  uk-grid  onclick="' . $onclick . '">
					    <div class="uk-width-expand lista_grid_nome uk-text-truncate" >' . $dati['nome_servizio'] . '<br/>
					  		<span class="uk-text-muted uk-text-small" > N.' . $qta . ' ' . ($qta == 1 ? 'persona' : 'persone') . '</span></div>
				        <div class="uk-width-auto  uk-text-right lista_grid_right c000"> ' . ($dati['modi'] != 0 ? date('H:i', $dati['time']) : '--.--') . ' ' . ($onclick != '' ? '<i class="fas fa-chevron-right"></i>' : '') . '  </div>
					</div> ';

		if (isset($serv_orari[time0($dati['time'])])) {
			$serv_orari[time0($dati['time'])] .= $textserv;
		} else {
			$serv_orari[time0($dati['time'])] = $textserv;
		}

	}
}

if (!empty($serv_orari)) {
	foreach ($serv_orari as $time => $cont) {
		$testo .= ' <div class="div_uk_divider_list"> ' . dataita($time) . ' ' . date('Y', $time) . ' </div>
		' . $cont;
	}
}

echo $testo . '<br/><br/>';

?>
