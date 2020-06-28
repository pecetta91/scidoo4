<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';
require_once __DIR__ . '/../../../config/preventivatore/funzioni/preventivatore.php';

$IDutente = $_SESSION['ID'];
$IDstruttura = $_SESSION['IDstruttura'];
$IDpreventivo = $_POST['IDpreventivo'];

$query = "SELECT  p.IDcliente,p.IDsequenziale FROM preventivo as p
LEFT JOIN schedine as s on s.ID=p.IDcliente
LEFT JOIN richieste AS r ON r.IDpreventivo=p.ID
WHERE p.IDstruttura='$IDstruttura' AND p.ID='$IDpreventivo'";

$result = mysqli_query($link2, $query);
$row = mysqli_fetch_row($result);
$IDcliente = $row['0'];
$ID_sequenziale = $row['1'];

$richieste_txt = '';
$testo = '';
$informazioni_categoria = genera_dati_categoria($IDstruttura);
$informazioni_alloggi = get_alloggi($IDstruttura);

$array_richieste = get_richieste(['0' => ['preventivo' => $IDpreventivo]], $IDstruttura, ['composizioni', 'sconti']);

if (!empty($array_richieste)) {
	foreach ($array_richieste as $IDrichiesta => $dati) {

		$IDcategoria = $dati['IDcategorie'][0] ?? 0;
		$IDalloggio = $dati['time_alloggi'][0]['IDalloggio'] ?? 0;

		$stato_richiesta = $dati['stato'];

		$arrivo = $dati['checkin'];
		$partenza = $dati['checkout'];
		$nottistr = $dati['notti'];

		$elenco_trattamenti = [];
		$prezzo_trattamenti = 0;
		if (!empty($dati['servizi'])) {
			foreach ($dati['servizi'] as $lista_rette) {
				if ($lista_rette['retta'] != 1) {continue;}

				$trattamento = $lista_rette['nome_servizio'];
				$prezzo_trattamenti += $lista_rette['prezzo'];

				$elenco_trattamenti[] = $trattamento;
			}
		}

		$nome_categoria = '';
		if ($nottistr > 0) {
			$nome_categoria = $informazioni_categoria[$IDcategoria]['nome'];
		} else {
			if (empty($elenco_trattamenti)) {
				$elenco_trattamenti[] = 'Personalizza';
			}
		}

		$numero_clienti = 0;
		foreach ($dati['clienti'] as $IDrestrizione => $numero) {
			$numero_clienti += $numero;
		}

		$richieste_txt .= '

		<div  class="richieste_carrello" onclick="dettagli_richiesta_preventivo(' . $IDrichiesta . ')">
				<div class="nome_retta_richiesta">' . implode(',', $elenco_trattamenti) . '</div>
				<div class="nome_categoria">' . $nome_categoria . ($IDalloggio ? ' - ' . $informazioni_alloggi[$IDalloggio]['alloggio'] : '') . '</div>
				<div>' . dataita7($arrivo) . ' - ' . dataita7($partenza) . ' - <span uk-icon="icon:users;ratio:0.7"></span>' . $numero_clienti . ' </div>

				<div>Retta : € ' . $dati['prezzo_rette'] . ' - Extra: € ' . $dati['prezzo_extra'] . '</div>

				<div class="totale_richiesta">Totale : € ' . $dati['prezzo_totale'] . '</div>

		</div> ';

	}

	$testo = '

	<div style="display:inline-flex;width:100%;height:100%;padding: 0 2px;">

		<div style="width:75%;margin:auto 0;border-right:#949494">
			<div class="container_richieste" style=" overflow-y: hidden;    overflow-x: auto;  white-space: nowrap; ">' . $richieste_txt . '</div>
		</div>

		<div style="width:25%;padding:0 8px;">
				<button class="pulsanti_preventivo_carrello" onclick="informazioni_preventivo()"><i class="fas fa-paper-plane"></i> </button>
				<button class="pulsanti_preventivo_carrello" onclick="elenco_richieste()"><i class="fas fa-plus"></i> </button>
		</div>
	</div>
  ';
}

echo $testo;

?>
