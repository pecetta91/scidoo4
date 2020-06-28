<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../config/connecti.php';
require_once __DIR__ . '/../../config/funzioni.php';
$IDprenotazione = $_SESSION['IDstrpren'];
$IDstruttura = get_IDstruttura_from_IDpren($IDprenotazione);
$lang = $_SESSION['lang'] ?: 0;

$recensioni = get_lista_recensioni([], $IDstruttura);
$recensioni = ordina_array($recensioni, 'time', 'DESC');
$lista_clienti = [];
foreach ($recensioni as $dati) {
	$lista_clienti[] = $dati['IDutente'];
}

$ospiti = estrai_dati_ospiti([['IDcliente' => $lista_clienti]], [], $IDstruttura)['dati'];

if (!empty($recensioni)) {
	$testo = '  <ul class="uk-list lista_dati_default"   style="padding: 0">';
	foreach ($recensioni as $dati) {

		$nome = '';

		if (isset($ospiti[$dati['IDutente']])) {
			$nome = $ospiti[$dati['IDutente']]['nome'] . ' ' . $ospiti[$dati['IDutente']]['cognome'];
		}
		/*
			if ($dati['media']) {
				for ($i = 1; $i <= $dati['media']; $i++) {
					$txt_media .= '<i class="fas fa-star" style="color: #FFEB3B"></i>';
				}
			}
			 ' . $ospiti[$dati['IDutente']]['nome'] . ' ' . $ospiti[$dati['IDutente']]['cognome'] . '
		*/

		$testo .= '
		<li onclick="navigation_ospite(5,{IDrecensione:' . $dati['ID'] . '})">
			<div class="c000 uk-text-bold" style="line-height: 13px;">
				<div><div class="testo_elissi_standard" style="    max-width: 35ch;  display: inline-block;">' . $dati['titolo'] . '  </div>
				<div style="float:right;"><i class="fas fa-ellipsis-h"></i></div> </div>
				<div style="  font-size: 12px;  display: block; color: #777;  font-weight: 400;"> ' . dataita5($dati['time']) . '</div>

			</div>
			<div style="font-size: 11px;  max-width: 30ch; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;color:#303030; ">' . $nome . '</div>
			<div style="font-size:12px;"> ' . tagliastringa2($dati['recensione'], 50) . '</div>
		</li>';
	}

	$testo .= ' </ul>';

} else {
	$testo = '<div >Non ci sono Recensioni</div>';}

echo $testo;
