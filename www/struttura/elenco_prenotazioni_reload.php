<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../config/connecti.php';
require_once __DIR__ . '/../../config/funzioni.php';
$IDstruttura = $_SESSION['IDstruttura'];

if (isset($_POST['parametri'])) {
	$parametri = array_map(function ($e) use ($link2) {if (is_array($e)) {return $e;}return $link2->escape_string(htmlspecialchars($e));}, $_POST['parametri']);
	$parametri = array_merge($_SESSION['filtri_prenotazione'], $parametri);
	$parametri['IDprenotazione'] = [];
} else {
	$parametri = $_SESSION['filtri_prenotazione'];
}

$ricerca = $parametri['testo'] ?? '';

$IDclienti_trovati = [];
if (strlen($ricerca) > 3) {
	$IDclienti_trovati = ricercacliente($ricerca, $IDstruttura);
	if (!empty($IDclienti_trovati)) {
		$IDclienti_trovati = implode(',', $IDclienti_trovati);
		$lista_id_clienti = [];
		$query = "SELECT IDpren FROM infopren WHERE IDcliente IN ($IDclienti_trovati) ORDER BY POSITION(IDcliente IN '($IDclienti_trovati)') ";
		$result = mysqli_query($link2, $query);
		while ($row = mysqli_fetch_row($result)) {
			$lista_id_clienti[] = $row[0];
		}

		if (!empty($lista_id_clienti)) {
			$parametri['IDprenotazione'] = $lista_id_clienti;
		}

	}
} else {
	$parametri['IDprenotazione'] = [];
}

$parametri['attiva'] = !($parametri['where'] == 2 && !$parametri['tipodata']);

$_SESSION['filtri_prenotazione'] = $parametri;

$filtri_prenotazione = prepara_parametri_prenotazioni($parametri);

$appartamenti = get_alloggi($IDstruttura);
$prenotazioni = get_prenotazioni(['0' => $filtri_prenotazione]) ?? [];

$txt = '';
if (!empty($prenotazioni['dati'])) {
	$prenotazioni = $prenotazioni['dati'];
	foreach ($prenotazioni as $val) {
		$IDprenotazione = $val['ID'];

		$alloggio = [];
		foreach ($val['alloggi'] as $IDalloggio) {
			$alloggio[] = $appartamenti[$IDalloggio]['alloggio'] ?? '';
		}
		$alloggio = array_keys(array_count_values($alloggio));
		$lista_alloggi = implode(', ', $alloggio);

		$txt .= '
			<li  style="position:relative" onclick="navigation(6,{IDprenotazione:' . $IDprenotazione . '});">
				<div class="c000 uk-text-bold" style="line-height: 15px;">
					<div> ' . $val['numero'] . ' ' . $val['nome_cliente'] . '	 <div style="float:right"><i class="fas fa-ellipsis-h"></i></div>  </div>
					<div style="  font-size: 12px;  display: block; color: #777;  font-weight: 400;">
					' . ($val['checkin'] != '' ? dataita7($val['checkin']) : '') . ' -  ' . ($val['checkout'] != '' ? dataita7($val['checkout']) : '') . '</div>
				</div>

				<div class=" uk-text-bold c000" style="font-size:13px;line-height:15px;">
						' . $val['persone'] . '	<i class="fas fa-user-alt"></i> ' . $val['notti'] . '	<i class="fas fa-moon"></i>
						<div style="font-size: 14px;  font-weight: 400;   margin-top: 5px;  vertical-align: bottom; color: #303030; display: inline-block; max-width: 35ch;" class="testo_elissi_standard">' . $lista_alloggi . ' </div>
				</div>
			</div>
		</li>';

	}

}

echo $txt;

?>
