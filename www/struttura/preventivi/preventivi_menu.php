<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';
$IDstruttura = $_SESSION['IDstruttura'];

$stato_selezionato = $_POST['stato'] ?? 0;

$prev_notifica = [];
if (isset($_SESSION['filtri_preventivo'])) {
	$parametri = $_SESSION['filtri_preventivo'];
	$ricerca = $parametri['ricerca'] ?? '';
	if (isset($parametri['data_inizio'])) {
		$time_ini = strtotime(convertiData($parametri['data_inizio']));
		$parametri['time_inizio'] = $time_ini;
	}

	if (isset($parametri['data_fine'])) {
		$time_fine = strtotime(convertiData($parametri['data_fine']));
		$parametri['time_fine'] = $time_fine;
	}

	$filtro_preventivo = $parametri;

	$IDclienti_trovati = [];
	if (strlen($ricerca) > 3) {
		$IDclienti_trovati = ricercacliente($ricerca, $IDstruttura);
	}
	if (is_numeric($ricerca)) {
		$filtro_preventivo['IDsequenziale'] = $ricerca;
	}

	if (!empty($IDclienti_trovati)) {
		$filtro_preventivo['cliente'] = $IDclienti_trovati;
	}

	unset($filtro_preventivo['stato']);
	$lista_preventivi = get_preventivi([$filtro_preventivo]);

	$conteggio_stati = array_count_values(array_column($lista_preventivi, 'stato'));

	$prev_notifica['online'] = $conteggio_stati[1] ?? null;
	$prev_notifica['salvati'] = $conteggio_stati[2] ?? null;
	$prev_notifica['attesa'] = $conteggio_stati[3] ?? null;
	$prev_notifica['inviati'] = $conteggio_stati[4] ?? null;
	$prev_notifica['confermati'] = $conteggio_stati[5] ?? null;
	$prev_notifica['accettati'] = $conteggio_stati[6] ?? null;
	$prev_notifica['booking_engine'] = $conteggio_stati[7] ?? null;

	$prev_notifica['scaduti'] = count(array_filter($lista_preventivi, function ($arg) {return $arg['scadenza'] ? $arg['scadenza'] < time_struttura() : false;}));

	$prev_notifica['non-aperti'] = count(array_filter($lista_preventivi, function ($arg) {return !isset($arg['aperture']) and in_array($arg['stato'], [3, 4]);}));

	array_walk($prev_notifica, function (&$arg) {$arg = $arg ? '<div class="numero_not_giorn">' . $arg . '</div>' : '';});

}

$testo = '<ul  uk-tab="animation: uk-animation-fade;swiping:false"  class="uk_tab_pulizie no_before" style="padding: 5px 0;  ">
	 		<li class="' . ($stato_selezionato == 0 ? 'uk-active' : '') . '"><a onclick="cambia_stato_preventivo_ricerca(0)">Tutti</a></li>
		 	<li class="' . ($stato_selezionato == 1 ? 'uk-active' : '') . '"><a onclick="cambia_stato_preventivo_ricerca(1)">Da Leggere' . $prev_notifica['online'] . '</a></li>
		 	<li class="' . ($stato_selezionato == 2 ? 'uk-active' : '') . '"><a onclick="cambia_stato_preventivo_ricerca(2)">Da Gestire' . $prev_notifica['salvati'] . '</a></li>
		 	<li class="' . ($stato_selezionato == 3 ? 'uk-active' : '') . '"><a onclick="cambia_stato_preventivo_ricerca(3)">In Spedizione ' . $prev_notifica['attesa'] . '</a></li>
		 	<li class="' . ($stato_selezionato == 4 ? 'uk-active' : '') . '"><a onclick="cambia_stato_preventivo_ricerca(4)">Inviati' . $prev_notifica['inviati'] . '</a></li>
		 	<li class="' . ($stato_selezionato == 5 ? 'uk-active' : '') . '"><a onclick="cambia_stato_preventivo_ricerca(5)">Non Aperti' . $prev_notifica['non-aperti'] . '</a></li>
		 	<li class="' . ($stato_selezionato == 6 ? 'uk-active' : '') . '"><a onclick="cambia_stato_preventivo_ricerca(6)">Scaduti' . $prev_notifica['scaduti'] . '</a></li>
		 	<li class="' . ($stato_selezionato == 7 ? 'uk-active' : '') . '"><a onclick="cambia_stato_preventivo_ricerca(7)">Accettati (In Attesa) ' . $prev_notifica['accettati'] . '</a></li>
</ul>';

echo $testo;
