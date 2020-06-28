<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';
require_once __DIR__ . '/../../../config/preventivatore/funzioni/preventivatore.php';

$IDutente = $_SESSION['ID'];
$IDstruttura = $_SESSION['IDstruttura'];
$IDpreventivo = $_POST['IDpreventivo'];

$query = "SELECT gruppo,IDcliente FROM preventivo WHERE IDstruttura='$IDstruttura' AND ID='$IDpreventivo'";
$result = $link2->query($query);
list($gruppo, $IDcliente) = $result->fetch_row();

if (!$gruppo) {
	$query = "SELECT r.ID FROM richieste AS r WHERE r.IDstr='$IDstruttura' AND r.IDpreventivo='$IDpreventivo' ORDER BY r.ID";
	$result = $link2->query($query);
	$proposte = array_column($result->fetch_all(), 0);
} else {
	$query = "SELECT pgr.gruppo,pgr.IDrequest FROM preventivo AS p JOIN preventivo_gruppo_richieste AS pgr ON pgr.IDpreventivo=p.ID WHERE p.IDstruttura='$IDstruttura' AND p.ID='$IDpreventivo'";
	$result = $link2->query($query);
	$proposte = [];
	while ($row = $result->fetch_row()) {
		$proposte[$row[0]][] = $row[1];
	}
	array_walk($proposte, function (&$arg) {$arg = json_encode($arg);});
}

$contenuto = '	<div class="content" style="padding:2px 5px">';

if (!$IDcliente) {
	$contenuto = 'Nessun Cliente Selezionato';
} elseif (count($proposte) == 0) {
	$contenuto .= 'Nessuna proposta presente';
} elseif (count($proposte) == 1) {
	$contenuto .= '<script>inserisci_prenotazione(' . key($proposte) . ');chiudi_picker();</script>';

} else {
	$numero = 1;
	foreach ($proposte as $g => $proposta) {

		$contenuto .= '
		<div style="margin:5px 0" class="elenco_richieste" data-request="' . $g . '">
			<label   style="align-items: center;" ><input type="checkbox" style="width:20px;height:20px;"> <span  style="vertical-align:super">PROPOSTA ' . $numero++ . '</span></label>
		</div>';
	}
	$contenuto .= '</div>

		<div style="position:absolute;width:100%;bottom:10px;text-align:center;">
         	<button style=" width: 95%;   background: #2542d9; border: none;  color: #fff;   border-radius: 5px;   padding: 5px 10px;   font-size: 16px;"  onclick="inserisci_prenotazione(this)">Conferma</button>
        </div> ';
}

$testo = '
<div class="nav navbar_picker_flex" >
 	<div onclick="chiudi_picker()"><i class="fas fa-times icona_chiudi" ></i></div>
</div>
 ' . $contenuto . '
';

echo $testo;

?>
