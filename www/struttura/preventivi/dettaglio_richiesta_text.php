<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';
require_once __DIR__ . '/../../../config/preventivatore/funzioni/preventivatore.php';

$IDutente = $_SESSION['ID'];
$IDstruttura = $_SESSION['IDstruttura'];

if (isset($_POST['IDpreventivo'])) {
	$IDpreventivo = $_POST['IDpreventivo'];
}

$dati_preventivo = get_preventivi(['0' => ['ID' => $IDpreventivo]], $IDstruttura)[$IDpreventivo];

$persone = $dati_preventivo['persone'] ?? [];

$checkin = dataita7($dati_preventivo['checkin']);

$notti = $dati_preventivo['notti'];
$checkout = '';
if ($notti > 0) {
	$checkout = ' - <span uk-icon="icon:calendar;ratio:0.7"></span> ' . (dataita7($dati_preventivo['checkout']) ?? ' -- ') . ' (' . $notti . ' ' . txtnotti($notti) . ') ';
}

foreach ($persone as $IDpren => $dati) {

	if ($dati['quantita'] > 0) {
		$txt_persone[] = 'N. ' . $dati['quantita'] . ' ' . $dati['etichetta'];
	}
}
$txt_persone = implode(', ', $txt_persone);

$dettaglio_richiesta = '<span uk-icon="icon:calendar;ratio:0.7"></span>' . $checkin . '  ' . $checkout . ' <br> <span uk-icon="icon:users;ratio:0.7"></span>' . $txt_persone;

echo '<div class="uk-text-normal">' . $dettaglio_richiesta . '</div>';

?>
