<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';

$IDstruttura = $_SESSION['IDstruttura'];

$parametri = $_POST['parametri'] ?? [];

$last_time = 0;

$filtro = [];
if (isset($parametri['IDpreventivo'])) {
	$filtro['IDpreventivo'] = [$parametri['IDpreventivo']];
}

if (isset($parametri['IDprenotazione'])) {
	$filtro['IDprenotazione'] = [$parametri['IDprenotazione']];
}

$arr_messaggi = estrai_messaggi(['0' => $filtro]);

$div_messaggi = '';
if (!empty($arr_messaggi)) {
	$arr_messaggi = ordina_array($arr_messaggi, 'time_messaggio', 'ASC');
	foreach ($arr_messaggi as $val) {

		if (($val['letto'] == 0) && ($val['ricevuto'] == 1)) {
			$messaggi_da_leggere[] = $val['IDmessaggio'];
		}

		$messaggio = $val['messaggio'];
		$time_messaggio = $val['time_messaggio'];
		$ricevuto = $val['ricevuto'];
		$letto = $val['letto'];
		$visualizzato = $val['visualizzato'];
		$stato = $val['stato'];

		$tipoobj = $val['tipoobj'];
		$IDobj = $val['IDobj'];
		$oggetto = $val['oggetto'];

		$check_in = $val['checkin_oggetto'];
		$check_out = $val['checkout_oggetto'];
		$numero_sequenziale = $val['numero_oggetto'];

		$time_txt = date('H:i', $time_messaggio);

		$time_messaggio0 = strtotime(date('Y-m-d', $time_messaggio));

		if ($last_time != $time_messaggio0) {
			$last_time = $time_messaggio0;
			$last_inviato = -1;
			$div_messaggi .= '<div class="messages-date">' . dataita($time_messaggio) . '</div>';
		}

		if ($ricevuto == 1) {
			$div_messaggi .= '
					<div class="message received">
         						' . strip_tags($messaggio, '<br>') . '
                  	<span class="metadata"><span class="time">' . $time_txt . '</span></span>
                </div>';

		} else {

			$icon = '';
			if ($letto > 0) {
				$icon = 'uk-icon="icon: check; ratio:1"';
			}

			$div_messaggi .= '
					<div class="message sent">
         				' . strip_tags($messaggio, '<br>') . '
		                  <span class="metadata">
		                     	 <span class="time">' . $time_txt . '</span><span class="tick"  ' . $icon . '> </span>
		                  </span>
	                </div> ';

		}

	}

}

if (!empty($messaggi_da_leggere)) {
	update_messaggi_letti($messaggi_da_leggere, 'struttura', $IDstruttura);
}

echo $div_messaggi;
?>
