<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';

$IDutente = $_SESSION['ID'];
$IDstruttura = $_SESSION['IDstruttura'];

$arr_dati = (isset($_POST['arr_dati']) ? $_POST['arr_dati'] : []);
$data = $arr_dati['time'] ?? 0;

if ($data == 0) {
	$time = (isset($_SESSION['tempo_benessere']) ? $_SESSION['tempo_benessere'] : time_struttura());
	$dataoggi = date('Y-m-d', $time);
} else {
	$time = strtotime(convertiData($data));
	$dataoggi = date('Y-m-d', $time);
}

$time = time0($time);
$_SESSION['tempo_benessere'] = $time;

$ggs = date('d', $time);
$dataini = date('Y-m-d', $time);
$datafin = date('Y-m-d', ($time + 86400));

$time_fine = $time + 86400;

$lista_sottotip = [];
$lista_sospesi = [];
$lista_confermati = [];

$lista_sottotip[2] = 'Trattamenti';
$query = "SELECT ID,sottotipologia FROM sottotipologie WHERE IDmain='4' AND IDstr='$IDstruttura'";
$result = mysqli_query($link2, $query);
while ($row = mysqli_fetch_row($result)) {
	$IDsottotipologia = $row['0'];
	$lista_sottotip[$IDsottotipologia] = $row['1'];
}

$IDprensosp = getprenotazioni($time, 0, $IDstruttura, 1);
$lista_sospesi = get_servizi_sospesi(['0' => ['IDprenotazione' => [$IDprensosp], 'IDtipo' => [2, 4], 'time_inizio' => $time, 'time_fine' => $time_fine]], $IDstruttura);
$lista_IDsottotip_sospesi = [];
if (!empty($lista_sospesi)) {
	foreach ($lista_sospesi as $dati) {
		if (!empty($dati['servizi'])) {
			foreach ($dati['servizi'] as $val) {
				$IDsottotip = $val['IDsottotip'];
				$IDtipo = $val['IDtipo'];
				$persone = $val['npers'];
				if ($IDtipo == 2) {
					$IDsottotip = 2;
				}
				if (isset($lista_IDsottotip_sospesi[$IDsottotip]['persone'])) {
					$lista_IDsottotip_sospesi[$IDsottotip]['persone'] += $persone;
				} else {
					$lista_IDsottotip_sospesi[$IDsottotip]['persone'] = $persone;
				}

				if (isset($lista_IDsottotip_sospesi[$IDsottotip]['servizi'])) {
					$lista_IDsottotip_sospesi[$IDsottotip]['servizi'] += 1;
				} else {
					$lista_IDsottotip_sospesi[$IDsottotip]['servizi'] = 1;
				}

			}
		}

	}
}

$lista_sale = [];
$query = "SELECT s.ID,st.IDmain,st.ID FROM sale as s
JOIN saleassoc as sc ON  sc.ID=s.ID
JOIN sottotipologie as st ON st.ID=sc.IDsotto WHERE st.IDmain IN (2,4)  AND s.IDstr='$IDstruttura'  ORDER BY s.ordine";
$result = mysqli_query($link2, $query);
while ($row = mysqli_fetch_row($result)) {
	$IDtipo = $row['1'];
	$IDsottotip = $row['2'];
	$IDsala = $row['0'];
	if ($IDtipo == 2) {
		$IDsottotip = 2;
	}
	if (!isset($lista_sale[$IDsottotip])) {
		$lista_sale[$IDsottotip] = $IDsala;
	}

}

$lista_IDsottotip_confermati = [];
$query = "SELECT p.ID,p.sottotip,p.IDtipo,p.time,p.durata,p.IDpren,SUM(p2.qta),p.esclusivo,p.sala
FROM prenextra as p
JOIN prenextra2 as p2 ON p2.IDprenextra=p.ID
WHERE p.IDstruttura='$IDstruttura' AND p.IDtipo IN (2,4) AND p.time>=$time AND p.time<$time_fine AND modi>0 GROUP BY p.ID ORDER BY p.time";
$result = mysqli_query($link2, $query);
while ($row = mysqli_fetch_row($result)) {
	$IDsottotipologia = $row['1'];
	if ($row['2'] == 2) {
		$IDsottotipologia = 2;
	}

	if (isset($lista_IDsottotip_confermati[$IDsottotipologia]['servizi'])) {
		$lista_IDsottotip_confermati[$IDsottotipologia]['servizi'] += 1;
	} else {
		$lista_IDsottotip_confermati[$IDsottotipologia]['servizi'] = 1;
	}

}

$testo = '<div style="display: flex; justify-content: center; flex-wrap: wrap;  justify-content: space-between;padding:10px ">';
foreach ($lista_sottotip as $IDsotto => $nome_sottotip) {

	$riga_sospesi = '';
	if (isset($lista_IDsottotip_sospesi[$IDsotto])) {
		//( <i class="fas fa-user"></i> ' . $lista_IDsottotip_sospesi[$IDsotto]['persone'] . ' )
		$riga_sospesi = '<div>' . $lista_IDsottotip_sospesi[$IDsotto]['servizi'] . ' S  </div>';
	}
	$riga_confermati = '';

	if (isset($lista_IDsottotip_confermati[$IDsotto])) {
		$riga_confermati = '<div>' . $lista_IDsottotip_confermati[$IDsotto]['servizi'] . ' C  </div>';
	}

	$riga_informazioni = $riga_sospesi . '' . $riga_confermati;

	$sfondo = 'background:#f5a149;color:#fff';
	if ($riga_informazioni == '') {
		$riga_informazioni = '<div><span style="font-size: 13px; ">Nessuna Prenotazione</span></div>';
		$sfondo = '';
	}

	$IDsala = $lista_sale[$IDsotto] ?? 0;

	$IDnavigation = 10;
	if ($IDsotto == 2) {
		$IDnavigation = 18;
		$IDsala = 0;
	}

	$testo .= '
	<div class="tile_centro_benessere" onclick="navigation(' . $IDnavigation . ',{IDsotto:' . $IDsotto . ',IDsala:' . $IDsala . '},0,0)" style="' . $sfondo . '">
			<div style="font-weight:600;" class=" testo_elissi_standard">' . $nome_sottotip . '</div>

			<div style="display:inline-flex;    width: 100%;  place-content: space-between;">

				' . $riga_informazioni . '
			</div>

	</div>';
}

$testo .= '</div>';
echo $testo;
?>


<style>

.tile_centro_benessere{width:46%;border:1px solid #e1e1e1;border-radius:5px;padding: 10px;height:80px;
    margin: 10px 5px;  background: #fff;  box-shadow: 0 0 5px 1px #e1e1e1;color: #000}
</style>
