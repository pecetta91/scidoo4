<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';

$IDutente = $_SESSION['ID'];
$IDstruttura = $_SESSION['IDstruttura'];

$IDcategoria = $_POST['IDcategoria'] ?? 0;
$mostra_avanzato = $_POST['minstay'] ?? 1;

$time = (isset($_SESSION['time_prezzi']) ? $_SESSION['time_prezzi'] : time_struttura());
$dataoggi = date('Y-m-d', $time);

$lista_tariffe = get_servizi_minstay();

$limiti_disponibilita_alloggi = [];
$query = "SELECT IDcat,valore,canale FROM limiti_disponibilita_alloggi WHERE  data='$dataoggi' AND IDstruttura='$IDstruttura' AND IDcat='$IDcategoria'  ORDER BY ID";
$result = mysqli_query($link2, $query);
while ($row = mysqli_fetch_row($result)) {
	$IDcategoria = $row['0'];
	$alloggi_limitati = $row['1'];
	$tipo = $row['2']; // 0 be 1 channel
	$limiti_disponibilita_alloggi[$IDcategoria][$tipo] = $alloggi_limitati;
}

$lista_minstay = [];
$query = "SELECT minstay,cta,ctd,IDcat,tipo,IDtariffa FROM minstay WHERE IDstr='$IDstruttura' AND data='$dataoggi' AND IDcat='$IDcategoria'   ORDER BY IDtariffa,IDcat";
$result = mysqli_query($link2, $query);
while ($row = mysqli_fetch_row($result)) {
	$minstay = $row['0'];
	$cta = $row['1'];
	$ctd = $row['2'];
	$IDcategoria = $row['3'];
	$tipo_tariffa = $row['4'];
	$IDtariffa = $row['5'];

	$lista_minstay[$tipo_tariffa][$IDcategoria][$IDtariffa]['minstay'] = $minstay;
	$lista_minstay[$tipo_tariffa][$IDcategoria][$IDtariffa]['cta'] = $cta;
	$lista_minstay[$tipo_tariffa][$IDcategoria][$IDtariffa]['ctd'] = $ctd;

}

$lista_chiusura = [];
$query = "SELECT IDtariffa,tipo,IDcat FROM chiusura_rette WHERE IDstr='$IDstruttura'  AND data='$dataoggi' ";
$result = mysqli_query($link2, $query);
while ($row = mysqli_fetch_row($result)) {
	$IDtariffa = $row['0'];
	$tipo_tariffa = $row['1'];
	$IDcategoria = $row['2'];
	$lista_chiusura[$tipo_tariffa][$IDcategoria][$IDtariffa] = 1;
}

$testo_tariffe = '';

$alloggi_disponibili = categorie_disponibili_per_giorno($IDstruttura, $time);
$appartamenti_liberi = (isset($alloggi_disponibili[$time][$IDcategoria]) ? $alloggi_disponibili[$time][$IDcategoria] : 0);

if (!empty($lista_tariffe)) {
	foreach ($lista_tariffe as $ordine => $value) {
		foreach ($value as $IDtariffa => $dati_tariffa) {
			if (($mostra_avanzato == 0) && ($IDtariffa != 0)) {continue;}
			$tipo_servizio = $dati_tariffa['tiposerv'];
			$chiusura = 0;
			$minstay = 0;
			$cta = 0;
			$ctd = 0;

			if (isset($lista_minstay[0][$IDcategoria][0])) {
				$minstay = $lista_minstay[0][$IDcategoria][0]['minstay'];
				$cta = $lista_minstay[0][$IDcategoria][0]['cta'];
				$ctd = $lista_minstay[0][$IDcategoria][0]['ctd'];
			}

			if (isset($lista_chiusura[0][$IDcategoria][0])) {
				$chiusura = $lista_chiusura[0][$IDcategoria][0];
				$minstay = 0;
				$cta = 0;
				$ctd = 0;
			}

			if (isset($lista_minstay[$tipo_servizio][$IDcategoria][$IDtariffa])) {
				$chiusura = 0;
				$minstay = $lista_minstay[$tipo_servizio][$IDcategoria][$IDtariffa]['minstay'];
				$cta = $lista_minstay[$tipo_servizio][$IDcategoria][$IDtariffa]['cta'];
				$ctd = $lista_minstay[$tipo_servizio][$IDcategoria][$IDtariffa]['ctd'];
			}

			if (isset($lista_chiusura[$tipo_servizio][$IDcategoria][$IDtariffa])) {
				$chiusura = $lista_chiusura[$tipo_servizio][$IDcategoria][$IDtariffa];
				$minstay = 0;
				$cta = 0;
				$ctd = 0;
			}

/*
$chiusura = (isset($lista_chiusura[$tipo_servizio][$IDcategoria][$IDtariffa]) ? $lista_chiusura[$tipo_servizio][$IDcategoria][$IDtariffa] :
(isset($lista_chiusura[0][$IDcategoria][0]) ? $lista_chiusura[0][$IDcategoria][0] : 0)
);

$minstay = (isset($lista_minstay[$tipo_servizio][$IDcategoria][$IDtariffa]['minstay']) ? $lista_minstay[$tipo_servizio][$IDcategoria][$IDtariffa]['minstay'] :
(isset($lista_minstay[0][$IDcategoria][0]['minstay']) ? $lista_minstay[0][$IDcategoria][0]['minstay'] : 0)
);

$cta = (isset($lista_minstay[$tipo_servizio][$IDcategoria][$IDtariffa]['cta']) ? $lista_minstay[$tipo_servizio][$IDcategoria][$IDtariffa]['cta'] :
(isset($lista_minstay[0][$IDcategoria][0]['cta']) ? $lista_minstay[0][$IDcategoria][0]['cta'] : 0)
);

$ctd = (isset($lista_minstay[$tipo_servizio][$IDcategoria][$IDtariffa]['ctd']) ? $lista_minstay[$tipo_servizio][$IDcategoria][$IDtariffa]['ctd'] :
(isset($lista_minstay[0][$IDcategoria][0]['ctd']) ? $lista_minstay[0][$IDcategoria][0]['ctd'] : 0)
);
 */

			$testo_tariffe .= '
					<div class="div_componente_prezzo_giorno"  style="margin-bottom:10px;border-bottom:1px solid #e1e1e1;"  data-categoria="' . $IDcategoria . '" data-tariffa="' . $IDtariffa . '">
						<strong style="color:#000">' . $dati_tariffa['nome'] . '</strong>

					<div class="div_list_uk uk_grid_div" uk-grid >
					    <div class="uk-width-1-2 lista_grid_nome uk-first-column">Chiuso</div>
					    <div class="uk-width-expand uk-text-right lista_grid_right">
					    <input style="width:22px;height:22px;" type="checkbox" ' . ($chiusura == 1 ? 'checked="checked"' : '') . '
					    onchange="modifica_prezzi(2,{IDtariffa:' . $IDtariffa . ',IDcategoria:' . $IDcategoria . ',time:' . $time . ',tipo_tariffa:' . $tipo_servizio . '},this,7,()=>{

					    	visualizza_dettagli_categoria_prezzo(' . $IDcategoria . ',\'minstay\',' . $mostra_avanzato . ');
					    	})" > </div>
					</div>
					<div class="div_list_uk uk_grid_div" uk-grid >
					    <div class="uk-width-1-2 lista_grid_nome uk-first-column">Notti minime</div>
					     <div class="uk-width-expand uk-text-right lista_grid_right ">
				   			<div class="stepper  stepper-init stepperrestr">
			    				<div class="stepper-button-minus" style="color:#0075ff;border:none"  onclick="selezionainfo(\'minstay' . $IDcategoria . '_' . $IDtariffa . '\',2,0)"  ><i class="fas fa-minus"></i></div>
							   <div class="stepper-value  inputrestr"

							     onchange="modifica_prezzi(3,{IDtariffa:' . $IDtariffa . ',IDcategoria:' . $IDcategoria . ',time:' . $time . ',tipo_tariffa:' . $tipo_servizio . '},\'minstay' . $IDcategoria . '_' . $IDtariffa . '\',\'html_id\');"


							   min="1" id="minstay' . $IDcategoria . '_' . $IDtariffa . '" max="20" style="border-bottom:1px solid #d6d6d6"> ' . (isset($minstay) ? $minstay : '1') . ' </div>
							   <div class="stepper-button-plus" style="color:#0075ff;border:none" onclick="selezionainfo(\'minstay' . $IDcategoria . '_' . $IDtariffa . '\',1,0)"  ><i class="fas fa-plus"></i></div>
							 </div>
					    </div>


						</div>
						<div class="div_list_uk uk_grid_div" uk-grid >
						    <div class="uk-width-1-2 lista_grid_nome uk-first-column">Chiusura Arrivo</div>
						    <div class="uk-width-expand uk-text-right lista_grid_right"><input
						       onchange="modifica_prezzi(4,{IDtariffa:' . $IDtariffa . ',IDcategoria:' . $IDcategoria . ',time:' . $time . ',tipo_tariffa:' . $tipo_servizio . '},this,7);"
						    style="width:22px;height:22px;" type="checkbox" ' . ($cta == 1 ? 'checked="checked"' : '') . ' > </div>
						</div>
						<div class="div_list_uk uk_grid_div" uk-grid >
						    <div class="uk-width-1-2 lista_grid_nome uk-first-column">Chiusura Partenza</div>
						    <div class="uk-width-expand uk-text-right lista_grid_right"> <input
						      onchange="modifica_prezzi(5,{IDtariffa:' . $IDtariffa . ',IDcategoria:' . $IDcategoria . ',time:' . $time . ',tipo_tariffa:' . $tipo_servizio . '},this,7);"
						    type="checkbox" style="width:22px;height:22px;" ' . ($ctd == 1 ? 'checked="checked"' : '') . ' ></div>
						</div>
					</div>

					';

		}
	}
}

echo $testo_tariffe;

?>
