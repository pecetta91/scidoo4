<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../config/connecti.php';
require_once __DIR__ . '/../../config/funzioni.php';
$IDprenotazione = $_SESSION['IDstrpren'];
$IDstruttura = get_IDstruttura_from_IDpren($IDprenotazione);
$lang = $_SESSION['lang'];

$arr_dati = (isset($_POST['arr_dati']) ? $_POST['arr_dati'] : []);
$IDrecensione = $arr_dati['IDrecensione'] ?? 0;

$recensione = get_lista_recensioni([['IDrecensione' => $IDrecensione]], $IDstruttura)[$IDrecensione];

$ospiti = estrai_dati_ospiti([['IDcliente' => $recensione['IDutente']]], [], $IDstruttura)['dati'];
$nome = $ospiti[$recensione['IDutente']]['nome'] . ' ' . $ospiti[$recensione['IDutente']]['cognome'];
$parametri = get_parametri_recensione([], $IDstruttura);
$testo = '

<div style="padding:10px 5px">
<span style="font-size:11px;color:#999">' . traduci('Pubblicata', $lang, 1) . '  ' . dataita($recensione['time']) . ' ' . date('Y', $recensione['time']) . ' - ' . $nome . '</span>
<div> <strong style="font-size:15px;color:#000">' . $recensione['titolo'] . '</strong></div>

<div style="font-size:16px;margin-bottom:30px;">' . $recensione['recensione'] . '</div>';

if (!empty($recensione['parametri'])) {
	$testo .= '<div class="div_uk_divider_list" style="margin-top:0px !important;">' . traduci('Voti', $lang) . '</div>';
	foreach ($recensione['parametri'] as $dati) {

		$stringa = $dati['valore'];
		if ($parametri[$dati['IDparametro']]['tipologia'] == 0) {

			$valore_max = $parametri[$dati['IDparametro']]['valore_max'];

			$stelle = $valore_max - $dati['valore'];

			$stringa = str_repeat('<i class="fas fa-star" style="color: #FFEB3B"></i>', $dati['valore']);
			if ($stelle) {
				$stringa .= str_repeat('<i class="fas fa-star" ></i>', $stelle);
			}
		}

		$testo .= '  <div class="uk_grid_div div_list_uk"  uk-grid >
			<div class="uk-width-expand lista_grid_nome uk-text-truncate" >' . traducis('', $dati['IDparametro'], 40, $lang) . '</div>
			<div class="uk-width-auto  lista_grid_right"> ' . $stringa . '</i></div>
		</div>';
	}
}

$testo .= '</div>';

echo $testo;
?>
