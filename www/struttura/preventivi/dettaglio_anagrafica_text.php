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

$preventivo = get_preventivi([['ID' => $IDpreventivo]], $IDstruttura)[$IDpreventivo];

$IDcliente = $preventivo['IDcliente'];

$stringa_nome = '';
$striga_numero = '';
if (!empty($IDcliente)) {
	$ospite = estrai_dati_ospiti([['IDcliente' => $IDcliente]], [], $IDstruttura)['dati'][$IDcliente];
	$stringa_nome = $ospite['cognome'] . '  ' . $ospite['nome'];
	$striga_numero = $ospite['prefisso_cell'] . '  ' . $ospite['cellulare'];

}

echo '
<div class="uk-text-normal">
	<span uk-icon="icon:users;ratio:0.7"></span> ' . $stringa_nome . '  - <span uk-icon="icon:receiver;ratio:0.7"></span> + ' . $striga_numero . '
</div>';

?>
