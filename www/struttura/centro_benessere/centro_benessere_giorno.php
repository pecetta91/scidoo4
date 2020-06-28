<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';
$IDstruttura = $_SESSION['IDstruttura'];
$testo = '';

$arr_dati = (isset($_POST['arr_dati']) ? $_POST['arr_dati'] : []);

$data = $arr_dati['data'] ?? 0;
$IDsottotip = $arr_dati['IDsotto'];
$IDsala = $arr_dati['IDsala'] ?? 0;

if ($data == 0) {
	$time = (isset($_SESSION['tempo_benessere']) ? $_SESSION['tempo_benessere'] : time_struttura());
} else {
	$time = strtotime(convertiData($data));
}

$time0 = time0($time);

$time_fine = $time0 + 86400;
$_SESSION['tempo_benessere'] = $time;

$orari = [];

$step_orario = (($_SESSION['step_30m_benessere'] ?? 0) == 0 ? 3600 : 1800);

$query = "SELECT orarioi,orariof,ID FROM orarisotto WHERE IDsotto='$IDsottotip' ORDER BY orarioi";
$result = mysqli_query($link2, $query);
while ($row = mysqli_fetch_row($result)) {
	$orai = $time0 + $row['0'];
	$oraf = $time0 + $row['1'];
	for ($orai; $orai <= $oraf; $orai += $step_orario) {
		$orari[] = $orai;
	}
}
$orari = array_unique($orari);

$persone_max = 0;
if ($IDsala) {
	$query = "SELECT maxp FROM sale WHERE ID='$IDsala' AND IDstr='$IDstruttura' LIMIT 1";
	$result = mysqli_query($link2, $query);
	$row = mysqli_fetch_row($result);
	$persone_max = $row['0'];
}

$prenotazioni_arrivo = get_prenotazioni(['0' => ['min_checkin' => $time0, 'max_checkin' => $time_fine]])['dati'];

$lista_servizi = get_servizi_presenti(['0' => ['time_inizio' => $time0, 'time_fine' => $time_fine, 'IDsottotip' => $IDsottotip, 'sala' => $IDsala]]);

$txt_confermati = '';
$txt_sospesi = '';
$txt_arrivi = '';

$lista_testo = ['confermati' => ['html' => '', 'totale' => 0], 'sospesi' => ['html' => '', 'totale' => 0], 'arrivi' => ['html' => '', 'totale' => 0]];

$lista_prenotazioni = [];
if (!empty($lista_servizi)) {
	foreach ($lista_servizi as $dati) {
		$IDaddebito = $dati['IDaddebito'];
		$IDprenotazione = $dati['IDriferimento'];
		if ($dati['modi'] == 0) {
			$lista_testo['sospesi']['html'] .= '
			<li onclick="disponibilita_servizio({IDaddebito:' . $IDaddebito . ',tipo_riferimento:0},0,()=>{ricarica_pagina_centro_benessere_giorno(' . $IDsala . ')})">
					<div class="c000 uk-text-bold" style="line-height: 15px;">
						<div>' . $dati['ospite'] . '  <div style="float:right;"><i class="fas fa-ellipsis-h"></i></div> </div>
						<div style="  font-size: 12px;  display: block; color: #777;  font-weight: 400;"> ' . ($dati['alloggio'] != '' ? $dati['alloggio'] : 'Senza Soggiorno') . '</div>
					</div>

					<div style="font-size: 11px;  max-width: 30ch; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;color:#303030; ">' . $dati['servizio'] . ' - <i class="fas fa-user"></i> ' . $dati['persone'] . '</div>
				</li> ';
			$lista_testo['sospesi']['totale']++;

		} else {

			$durata = $dati['durata'] * 60;

			$time_fine = $dati['time'] + $durata;

			$lista_orari = [];
			for ($time_ciclo = $dati['time']; $time_ciclo <= $time_fine; $time_ciclo += 1800) {

				if (isset($lista_prenotazioni[$time_ciclo])) {
					$lista_prenotazioni[$time_ciclo] += $dati['persone'];
				} else {
					$lista_prenotazioni[$time_ciclo] = $dati['persone'];
				}
				$lista_orari[] = $time_ciclo;
			}

			$lista_testo['confermati']['html'] .= '
				<li onclick="disponibilita_servizio({IDaddebito:' . $IDaddebito . ',tipo_riferimento:0},0,()=>{ricarica_pagina_centro_benessere_giorno(' . $IDsala . ')})
				" class="servizi_confermati" data-time="' . implode(',', $lista_orari) . '">
					<div class="c000 uk-text-bold" style="line-height: 15px;">
						<div>' . $dati['ospite'] . '  <div style="float:right;">' . date('H:i', $dati['time']) . ' - ' . date('H:i', $time_fine) . '</div> </div>
						<div style="  font-size: 12px;  display: block; color: #777;  font-weight: 400;"> ' . ($dati['alloggio'] != '' ? $dati['alloggio'] : 'Senza Soggiorno') . '</div>
					</div>

					<div style="font-size: 11px;  max-width: 30ch; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;color:#303030; ">' . $dati['servizio'] . ' - <i class="fas fa-user"></i> ' . $dati['persone'] . '</div>
				</li> ';
			$lista_testo['confermati']['totale']++;

		}

		if (isset($prenotazioni_arrivo[$IDprenotazione])) {

			$lista_testo['arrivi']['html'] .= '
				<li>
					<div class="c000 uk-text-bold" style="line-height: 15px;">
						<div>' . $prenotazioni_arrivo[$IDprenotazione]['nome_cliente'] . '  <div style="float:right;"><i class="fas fa-ellipsis-h"></i></div> </div>
						<div style="  font-size: 12px;  display: block; color: #777;  font-weight: 400;"> ' . $dati['alloggio'] . '</div>
					</div>

					<div style="font-size: 11px;  max-width: 30ch; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;color:#303030; ">' . $prenotazioni_arrivo[$IDprenotazione]['persone'] . '	<i class="fas fa-user-alt"></i> ' . $prenotazioni_arrivo[$IDprenotazione]['notti'] . '	<i class="fas fa-moon"></i></div>
					<div style="  font-size: 12px;  display: block; color: #777;  font-weight: 400;"> <div> ' . dataita5($prenotazioni_arrivo[$IDprenotazione]['checkin']) . ' ' .
			$prenotazioni_arrivo[$IDprenotazione]['orario_checkin'] . '   - ' . dataita5($prenotazioni_arrivo[$IDprenotazione]['checkout']) . '</div></div>
				</li>';
			$lista_testo['arrivi']['totale']++;

			unset($prenotazioni_arrivo[$IDprenotazione]);
		}

	}
}

$sala_occupazione = '';
foreach ($orari as $time_fascie) {

	$colonna = '';
	if (isset($lista_prenotazioni[$time_fascie])) {
		$colonna = '
		<div class="occupazione_colonna" data-time="' . $time_fascie . '" onclick="visualizza_centro_benessere(this)" style="height:' . (round($lista_prenotazioni[$time_fascie] / $persone_max, 2) * 100) . 'px;">
				' . $lista_prenotazioni[$time_fascie] . '
		</div>';
	}

	$sala_occupazione .= '
	 <div class="statistica_benessere" onclick="">

 		<div class="colonna">  ' . $colonna . ' 	</div>

	 	<div>' . date('H:i', $time_fascie) . '</div>
	 </div>';

}

$testo = '
<input type="hidden" id="time" value="' . $time0 . '">
<input type="hidden" id="IDsottotip" value="' . $IDsottotip . '">
<input type="hidden" id="IDsala" value="' . $IDsala . '">

<div style="border-bottom:1px solid #e1e1e1;background:#fff;padding:5px 0; display: flex; white-space: nowrap;flex-wrap: unset;  overflow: auto;    margin-top: 40px;">
' . $sala_occupazione . '
</div>



<div  class="div_tab_benessere">

	<div  data-tab="1" class="pulsanti_tab  active" onclick="mostra_tab(this)">Arrivi ' . ($lista_testo['arrivi']['totale'] ? '
		<div class="numero_not_giorn">' . $lista_testo['arrivi']['totale'] . '</div>' : '') . '</div>
	<div   data-tab="2" class="pulsanti_tab"  onclick="mostra_tab(this)">Sospesi ' . ($lista_testo['sospesi']['totale'] ? '
		<div class="numero_not_giorn">' . $lista_testo['sospesi']['totale'] . '</div>' : '') . '</div>
	<div   data-tab="3" onclick="mostra_tab(this)" class="pulsanti_tab" >Presenti ' . ($lista_testo['confermati']['totale'] ? '
		<div class="numero_not_giorn">' . $lista_testo['confermati']['totale'] . '</div>' : '') . '</div>

</div>


<div style="margin-top:10px;" class="container_tab">

	<div class="tab" data-tab="1" style="display:block">' . (isset($lista_testo['arrivi']['html']) ? '
	 <ul class="uk-list lista_dati_default" style="padding: 0">' . $lista_testo['arrivi']['html'] . '</ul>' : '') . '
	</div>


	<div class="tab" data-tab="2">' . (isset($lista_testo['sospesi']['html']) ? '
	 <ul class="uk-list lista_dati_default" style="padding: 0">' . $lista_testo['sospesi']['html'] . '</ul>' : '') . '
	</div>


	<div  class="tab" data-tab="3">' . (isset($lista_testo['confermati']['html']) ? '
	  <ul class="uk-list lista_dati_default" style="padding: 0">' . $lista_testo['confermati']['html'] . '</ul>' : '') . '
	 </div>

</div>



<div id="contenuto_benessere">
</div>

';

echo $testo;

?>
<style>
	.statistica_benessere{background: #fff;   padding: 0 3px;font-size: 11px}
	.statistica_benessere .colonna{height: 100px;position: relative;}
	.statistica_benessere .occupazione_colonna{position: absolute;left: 0;bottom: 0;background: #6d83d0;    z-index: 1;   width: -webkit-fill-available;text-align: center;color:#fff;    max-height: 100px;}
	.container_tab .tab{display:none;}
	.pulsanti_tab.active {   color: #1e87f0;}
	.div_tab_benessere{color: #000; background: #fff;  text-align: center;  display: flex;  font-weight: 600; border-bottom: 1px solid #e1e1e1;}
	.div_tab_benessere .pulsanti_tab{width:33%;padding:5px 0;border-right:1px solid #e1e1e1;position: relative;}
	.div_tab_benessere .pulsanti_tab:last-child{border-right:none;}

	.occupazione_colonna.selezionata{    box-shadow: 0 0 4px 1px #3bff53;}

	.servizi_confermati.selezionata{border: 1px solid #3bff53;}
</style>

