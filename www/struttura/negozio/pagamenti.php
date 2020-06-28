<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';
$IDutente = $_SESSION['ID'];
$IDstruttura = $_SESSION['IDstruttura'];

$IDvendita = $_POST['IDvendita'] ?? 0;
$dati_vendita = reset(get_vendite([['IDvendita' => $IDvendita]])['dati']);

$totale_pagato = 0;
$totale_fatturato = 0;

$tipoobj = [3, 4, 5, 6];
$tipoobj_txt = implode(',', $tipoobj);

$query = "SELECT f.IDfattura
FROM scontriniobj as s
JOIN fattureIDscontr as f ON f.IDscontr=s.IDscontr
WHERE s.IDobj IN($IDvendita) AND s.tipoobj IN($tipoobj_txt)
GROUP BY f.IDfattura";
$result = mysqli_query($link2, $query);
$lista_fattura = array_column($result->fetch_all(), 0);

//pagamenti senza fiscalità
//(f.IDscontr IS NULL OR s.IDfattura_utilizzo='0')
$query = "SELECT s.IDscontr,sc.oggetto,(s.valore-s.rivalsa),s.IDfattura_utilizzo
FROM scontriniobj as s
JOIN scontrini_oggetti as sc ON sc.ID=s.tipoobj
LEFT JOIN fattureIDscontr as f ON f.IDscontr=s.IDscontr
WHERE s.IDobj IN($IDvendita) AND s.tipoobj IN($tipoobj_txt) AND (f.IDscontr IS NULL )
GROUP BY s.IDscontr";
$result = mysqli_query($link2, $query);
while ($row = mysqli_fetch_row($result)) {

	$dati_pagamento = get_dati_pagamento($row['0']);
	$pulsanti = '';
	if ($row['3'] == 0) {
		$pulsanti .= '<button class="shortcut mini16 popover del3icon danger" onclick="msgboxelimina(' . $row['0'] . ',21,0,4)"><span>Elimina Pagamento</span></button>';
	} else {
		$totale_fatturato += $row['2'];
	}
	$totale_pagato += $row['2'];

	$testo_pagamenti .= ' <div class="uk_grid_div div_list_uk" uk-grid  >
					<div class="uk-width-expand lista_grid_nome uk-text-truncate"  > ' . $row['1'] . ' <br> <span class="uk-text-muted uk-text-small" style="font-size:12px;">
					' . $row['2'] . '</span></div>
					<div class="uk-width-auto lista_grid_right" ></div>

				</div> ';
}

$parametri_ricerca = [];
if ($lista_fattura) {
	$parametri_ricerca[0] = ['IDdocumenti' => ($lista_fattura ?? [])];
}

$array = get_documenti_fiscali($IDstruttura, null, $parametri_ricerca);
$parametri_ricerca = [];
$parametri_ricerca[0] = ['IDobj' => $IDvendita, 'tipoobj' => $tipoobj];
//$array = estrai_documenti($array, $parametri_ricerca);

$documento_di_checkout = 0;

foreach ($array as $key => $dati_documento) {
	$IDdocumento = $dati_documento['IDdocumento'];
	$pulsanti = '<button class="shortcut  mini16 info popover" onclick="modifichep(' . $IDdocumento . ',this,0,24,0,0)"><i class="fas fa-folder-open"></i><span>Stampa/Visualizza Documenti</span></button>';
	if ($dati_documento['eliminabile'] == 1) {
		$pulsanti .= '<button class="shortcut mini16 danger del3icon popover " onclick="msg_continua(()=>{modprenot(' . $IDdocumento . ', 0, 100, 10, ()=>{dettaglio_vendita(' . $IDvendita . ',\'pagamenti\');});});"><span>Elimina Documento</span></button>';
	}

	if ($dati_documento['tipoobj_principale'] == 5) {
		$documento_di_checkout = 1;
	}

	$dettaglio_pagamenti = get_dati_pagamento_documento($dati_documento['dettaglio_pagamenti']);

	$pagato = $dettaglio_pagamenti['pagato'];
	$sospeso = $dettaglio_pagamenti['sospeso'];

	$testo .= '

		<div class="uk_grid_div div_list_uk" uk-grid  >
			<div class="uk-width-expand lista_grid_nome uk-text-truncate"  >' . $dati_documento['oggetto'] . ' <br> <span class="uk-text-muted uk-text-small" style="font-size:12px;">
			' . $dati_documento['nome_formattato'] . '</span></div>
			<div class="uk-width-auto lista_grid_right" >' . format_number($pagato) . ' - ' . format_number($sospeso) . '</div>

		</div> ';

	$pagamenti_documento = '';

	$dettaglio_corrispettivi_pagati = get_dati_corrispettivo_documento_pagati($dati_documento, $IDdocumento);

	if (!empty($dettaglio_corrispettivi_pagati)) {

		foreach ($dettaglio_corrispettivi_pagati as $IDdocumento_interno => $dato) {

			$dati_documento_interno = get_dati_fattura($IDdocumento_interno);

			$testo_pagamenti .= '

				<div class="uk_grid_div div_list_uk" uk-grid  >
					<div class="uk-width-expand lista_grid_nome uk-text-truncate"  > Pagamento del Sospeso <br> <span class="uk-text-muted uk-text-small" style="font-size:12px;">
					' . $dati_documento_interno['nome_formattato'] . '</span></div>
					<div class="uk-width-auto lista_grid_right" >' . $dato['pagato'] . '</div>

				</div> ';

		}

	}

	$totale_pagato += $pagato + $sospeso;
	$totale_fatturato += $pagato + $sospeso;

}

$testo_pagamenti = '';
$pagamenti_da_effettuare = [];
$totale_vendita = $dati_vendita['totale_vendita'];

if (($totale_vendita != $totale_fatturato) || ($documento_di_checkout == 0)) {
	$dati_pagamento = [];
	$a_saldo = $totale_vendita - $totale_pagato;
	$dati_pagamento['titolo'] = 'Quota a Saldo';
	$dati_pagamento['a_saldo'] = format_number($a_saldo);

	if ($a_saldo == 0) {
		//openscontr(' . $IDvendita . ',1,0,' . $a_saldo . ',0,1);
		$dati_pagamento['pulsante'] = '
			<button class="shortcut   recta8  info popover" onclick="modifichep(' . $IDvendita . ',this,2,88,0,5)" >Check-Out<span>Registra Check-out</span></button>';
	} else {
		$dati_pagamento['pulsante'] = '<button class="shortcut recta8  success popover"  onclick="modifichep(' . $IDvendita . ',this,2,86,0,' . $a_saldo . ')">Registra<span>Registra Movimento</span></button>';
	}
	$pagamenti_da_effettuare[] = $dati_pagamento;
}

if (!empty($pagamenti_da_effettuare)) {
/*' . $dati['pulsante'] . '*/
	foreach ($pagamenti_da_effettuare as $dati) {
		$testo_pagamenti .= '

		<div class="uk_grid_div div_list_uk" uk-grid  >
				<div class="uk-width-expand lista_grid_nome uk-text-truncate"  >' . $dati['titolo'] . ' </div>
				<div class="uk-width-auto lista_grid_right" >€ ' . format_number($dati['a_saldo']) . ' <span uk-icon="chevron-right" ></span></div>

			</div> ';
	}

} else {
	$testo_pagamenti .= '<div>Nessun Pagamento da Effettuare</div>';
}

$testo = '<div class="div_uk_divider_list" style="margin-top:5px !important">Dati Pagamenti </div>' . $testo_pagamenti;

echo $testo;

?>
