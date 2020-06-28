<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';

$IDutente = $_SESSION['ID'];
$IDstruttura = $_SESSION['IDstruttura'];

$arr_dati = (isset($_POST['arr_dati']) ? $_POST['arr_dati'] : []);

$data = $arr_dati['time'] ?? 0;

if ($data == 0) {
	$time = (isset($_SESSION['time_prezzi']) ? $_SESSION['time_prezzi'] : time_struttura());
	$dataoggi = date('Y-m-d', $time);
} else {
	$time = strtotime(convertiData($data));
	$dataoggi = date('Y-m-d', $time);
}

$time = time0($time);

$_SESSION['time_prezzi'] = $time;
$alloggi_disponibili = categorie_disponibili_per_giorno($IDstruttura, $time);
$categorie = get_categorie($IDstruttura, 0, 1);

$lista_categoria_channel_room = [];
$query = "
SELECT ct.occupazione,cr.IDcat,ctr.tariffaint FROM
channeltariffe as ctr
JOIN channelroomt as ct ON ctr.ID=ct.IDtariffa
JOIN channelroom as cr ON ct.IDroom=cr.ID
WHERE ctr.IDstr='$IDstruttura'
GROUP BY ct.occupazione,cr.IDcat,ct.IDtariffa ";
$result = mysqli_query($link2, $query);
while ($row = mysqli_fetch_row($result)) {
	$IDcategoria = $row['1'];
	$IDtariffa = $row['2'];
	$occupazione = $row['0'];
	$lista_categoria_channel_room[$IDcategoria][$IDtariffa] = $occupazione;
}

$limiti_disponibilita_alloggi = [];
$query = "SELECT IDcat,valore,canale FROM limiti_disponibilita_alloggi WHERE  data='$dataoggi' AND IDstruttura='$IDstruttura'  ORDER BY ID";
$result = mysqli_query($link2, $query);
while ($row = mysqli_fetch_row($result)) {
	$IDcategoria = $row['0'];
	$alloggi_limitati = $row['1'];
	$tipo = $row['2']; // 0 be 1 channel
	$limiti_disponibilita_alloggi[$IDcategoria][$tipo] = $alloggi_limitati;
}

$testo_categorie = '';
if (!empty($categorie)) {

	foreach ($categorie as $IDcategoria => $dati) {
		$appartamenti_liberi = (isset($alloggi_disponibili[$time][$IDcategoria]) ? $alloggi_disponibili[$time][$IDcategoria] : 0);

		$prenotabili_channel_manager = '';
		if (isset($lista_categoria_channel_room[$IDcategoria])) {
			$prenotabili_channel_manager = '
			<div class="div_list_uk uk_grid_div " uk-grid>
			    <div class="uk-width-1-2 uk-text-truncate lista_grid_nome"  >Prenotabili CM</div>
				    <div class="uk-width-expand uk-text-right lista_grid_right ">
			   			<div class="stepper  stepper-init stepperrestr">
		    				<div class="stepper-button-minus" style="color:#0075ff;border:none"  onclick="selezionainfo(\'prenotabilecm' . $IDcategoria . '\',2,0)"  ><i class="fas fa-minus"></i></div>
						   <div class="stepper-value  inputrestr" min="0" id="prenotabilecm' . $IDcategoria . '"
						     onchange="modifica_prezzi(1,{IDcategoria:' . $IDcategoria . ',time:' . $time . ',tipo:1},\'prenotabilecm' . $IDcategoria . '\',\'html_id\');"
						   max="' . $dati['numero_appartamenti'] . '" style="border-bottom:1px solid #d6d6d6"> ' . (isset($limiti_disponibilita_alloggi[$IDcategoria][1]) ? $limiti_disponibilita_alloggi[$IDcategoria][1] : $dati['numero_appartamenti']) . ' </div>
						   <div class="stepper-button-plus" style="color:#0075ff;border:none" onclick="selezionainfo(\'prenotabilecm' . $IDcategoria . '\',1,0)"  ><i class="fas fa-plus"></i></div>
						 </div>
				    </div>
				</div>';
		}

		$testo_categorie .= '

		<div style="background:#fff;padding:10px;margin:10px;">

			<div><strong style="color:#' . $dati['colore'] . '">' . $dati['categoria'] . ' (' . $dati['numero_appartamenti'] . ') </strong>


				<button style="float:right;    border-radius: 3px;   border: none;    padding: 5px 10px;" class="uk-button-danger" onclick="modal_disponibilita(' . $IDcategoria . ')">Disponibilità</button>

			</div>

			<div class="div_list_uk uk_grid_div" uk-grid >
			    <div class="uk-width-1-2 lista_grid_nome uk-first-column">Disponibili</div>
			    <div class="uk-width-expand uk-text-right lista_grid_right"><strong style="color:#000">' . $appartamenti_liberi . '</strong> </div>
			</div>

				<div class="div_list_uk uk_grid_div " uk-grid>
				    <div class="uk-width-1-2 uk-text-truncate lista_grid_nome"  >Prenotabili BE</div>
				    <div class="uk-width-expand uk-text-right lista_grid_right ">
			   			<div class="stepper  stepper-init stepperrestr">
		    				<div class="stepper-button-minus" style="color:#0075ff;border:none"  onclick="selezionainfo(\'prenotabile' . $IDcategoria . '\',2,0)"  ><i class="fas fa-minus"></i></div>
						   <div class="stepper-value  inputrestr" min="0" id="prenotabile' . $IDcategoria . '"

						   onchange="modifica_prezzi(1,{IDcategoria:' . $IDcategoria . ',time:' . $time . ',tipo:0},\'prenotabile' . $IDcategoria . '\',\'html_id\');"

						   max="' . $dati['numero_appartamenti'] . '" style="border-bottom:1px solid #d6d6d6"> ' . (isset($limiti_disponibilita_alloggi[$IDcategoria][0]) ? $limiti_disponibilita_alloggi[$IDcategoria][0] : $dati['numero_appartamenti']) . ' </div>
						   <div class="stepper-button-plus" style="color:#0075ff;border:none" onclick="selezionainfo(\'prenotabile' . $IDcategoria . '\',1,0)"  ><i class="fas fa-plus"></i></div>
						 </div>
				    </div>
				</div>

				' . $prenotabili_channel_manager . '

				<div style="padding:15px 0;">
					<ul class="no_before uk_tab_pulizie tab_prezzi" uk-tab="active:3; animation: uk-animation-fade" style="background:#fff">
				        <li onclick="visualizza_dettagli_categoria_prezzo(' . $IDcategoria . ',\'prezzo\')"><a href="#" aria-expanded="true">Tariffe </a></li>
				        <li onclick="visualizza_dettagli_categoria_prezzo(' . $IDcategoria . ',\'minstay\',0)"><a href="#" aria-expanded="false">Soggiorno Minimo </a></li>
				        <li onclick="visualizza_dettagli_categoria_prezzo(' . $IDcategoria . ',\'minstay\',1)"><a href="#" aria-expanded="false">Soggiorno Minimo Avanzato </a></li>
				        <li onclick="visualizza_dettagli_categoria_prezzo(' . $IDcategoria . ',\'chiudi\')" class="nascondi_dettagli" style="visibility:hidden"><a href="#" aria-expanded="false">Chiudi </a></li>
				    </ul>
				 </div>

				<div class="div_tariffe" data-categoria="' . $IDcategoria . '" >  </div>
		</div> 	';

	}

}

$testo = '<div>' . $testo_categorie . '</div>';

echo $testo;
?>
