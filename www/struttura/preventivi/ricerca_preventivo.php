<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';
require_once __DIR__ . '/../../../config/preventivatore/funzioni/preventivatore.php';

$IDutente = $_SESSION['ID'];
$IDstruttura = $_SESSION['IDstruttura'];

$IDpreventivo = $_POST['IDpreventivo'];

$dati_preventivo = get_preventivi(['0' => ['ID' => $IDpreventivo]], $IDstruttura, ['richieste'])[$IDpreventivo];

$persone_preventivo = $dati_preventivo['persone'];
$notti_preventivo = $dati_preventivo['notti'];

$persone = [];
$quantita_persone = 0;
foreach ($persone_preventivo as $IDrestrizione => $val) {
	$quantita_persone += $val['quantita'];
	if ($val['quantita']) {
		$persone[$IDrestrizione] = $val['quantita'];
	}
}

$elenco_categorie_prenotabili_online = [];
$appartamenti_prenotabili = get_alloggi($IDstruttura);
foreach ($appartamenti_prenotabili as $key => $val) {
	if (in_array($val['attivo'], [1, 2])) {
		if (!in_array($val['IDcategoria'], $elenco_categorie_prenotabili_online)) {
			$elenco_categorie_prenotabili_online[] = $val['IDcategoria'];
		}
	}
}

$elenco_rette_prenotabili_online = [];
$rette_prenotabili = get_elenco_servizi(['0' => ['tipolim' => [4, 5], 'extraonline' => 1]], $IDstruttura);
foreach ($rette_prenotabili as $key => $val) {
	$elenco_rette_prenotabili_online[] = $val['ID'];
}

$array_ricerca = prev_disponibilita_rette($IDpreventivo,
	['IDstruttura' => $IDstruttura, 'lista_rette_prenotabili' => $elenco_rette_prenotabili_online, 'lista_categorie_prenotabili' => $elenco_categorie_prenotabili_online]);

$servizi_composizioni = [];
$array_rette = [];
$array_categorie = [];

foreach ($array_ricerca as $IDretta => $categorie) {
	$nome_servizio = get_info_from_IDserv($IDretta)['nome_servizio'];
	$array_rette[$IDretta] = [];
	$array_rette[$IDretta]['nome_servizio'] = $nome_servizio;
	$servizi_composizioni[] = $IDretta;
	foreach ($categorie as $IDcategoria => $info_cella) {

		$array_categorie[$IDcategoria]['pacchetti'][$IDretta] = [];

		$array_rette[$IDretta]['categorie'][$IDcategoria] = [];
	}
}

$nomi_sconti = get_info_sconti($IDstruttura);

$informazioni_categorie = genera_dati_categoria($IDstruttura);

$contenuto_ricerca = '';

if (isset($array_ricerca) && ($notti_preventivo == 0)) {
	$contenuto_ricerca = stampa_ricerca_pacchetti($array_ricerca, $array_rette, $IDstruttura);
}

if (isset($array_ricerca) && ($notti_preventivo != 0)) {
	$contenuto_ricerca = stampa_ricerca_categorie($array_ricerca, $array_categorie, $IDstruttura);

}

$testo = '';
if (empty($array_ricerca)) {
	$testo = '

	<div style="padding:10px 5px;text-align:center">
			<p>Nessun risultato disponibile.<br/>
			Provare date e/o alloggi differenti<br/>
			oppure</p>
			<button class="button_salva_preventivo" style="margin-bottom:10px;" onclick="gestione_preventivo(3, { campo:\'mostra_tutto\',value:1},()=>{inizia_ricerca_preventivo()})">Mostra Tutto</button>
	 </div>
	';
}

$testo .= $contenuto_ricerca;

echo $testo;
?>
