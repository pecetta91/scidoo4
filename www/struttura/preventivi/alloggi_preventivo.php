<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';
require_once __DIR__ . '/../../../config/preventivatore/funzioni/preventivatore.php';

$IDutente = $_SESSION['ID'];
$IDstruttura = $_SESSION['IDstruttura'];

$spezzo_possibile = true;

$alloggi = $_POST['alloggi'] ?? null;
$info_alloggi = get_alloggi($IDstruttura);

$lista_alloggi = '';
if ($alloggi) {
	$lista_alloggi .= '
	<li  data-id="' . implode(',', $alloggi) . '" style="color: #4267b2" class="inserisci_richiesta">Continua Senza Specificare</li> ';
	foreach ($alloggi as $IDalloggio) {
		$lista_alloggi .= '<li class="inserisci_richiesta"  data-id="' . $IDalloggio . '" >' . $info_alloggi[$IDalloggio]['alloggio'] . '</li>';
	}

}

echo $lista_alloggi;

?>
