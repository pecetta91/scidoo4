<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';
$IDprenotazione = $_SESSION['IDstrpren'];
$IDstruttura = get_IDstruttura_from_IDpren($IDprenotazione);
$IDservizio = $_POST['IDservizio'];

$lang = $_SESSION['lang'] ?: 0;

$dati_prenotazione = get_prenotazioni(['0' => ['IDprenotazione' => $IDprenotazione]], null, [], $IDstruttura)['dati'][$IDprenotazione];

$dati_serv = get_info_from_IDserv($IDservizio, null, $IDstruttura);

$foto = getfoto($IDservizio, 4);

$lista_tag = get_lista_tag($IDservizio, 3, $IDstruttura);
$lista_allergeni = '';
if (!empty($lista_tag)) {
	foreach ($lista_tag as $tag) {
		if ($tag['IDtag_categoria'] != 12) {continue;}
		$nome_tag = traducis($tag['nome'], $tag['ID'], 25, $lang);
		$allergeni[] = $nome_tag;

	}
}

$numeri = genera_numeri_array(1, 20, 1);

$query = "SELECT u.ID,u.sigla FROM  servizi as s
JOIN unita_misura as u  ON u.ID=s.dato1
WHERE s.ID=$IDservizio AND s.IDstruttura=$IDstruttura LIMIT 1";
$result = mysqli_query($link2, $query);
if (mysqli_num_rows($result) > 0) {
	$row = mysqli_fetch_row($result);
	$sigla = $row['1'];
	switch ($row[0]) {
	case 0:
	case 3:
	case 1:
		if ($row[0] == 3) {$sigla = '';}
		$numeri = [];
		$numeri = genera_numeri_array(1, 20, 1, $sigla);

		break;

	case 2:
		$numeri = [];
		for ($i = 0.1; $i <= 2; $i += 0.1) {
			$numeri[strval($i)] = $i . ' ' . $sigla;
		}

		break;
	}

}

$query = "SELECT IDvariazione FROM limitivariazioni WHERE IDobj=$IDservizio AND tipoobj=2 AND IDstruttura=$IDstruttura";
$result = $link2->query($query);
$variazioni = array_column($result->fetch_all(), 0);

$_SESSION['variazioni_ristorante_be'] = [];
$variazioni_txt = ['positive' => '', 'negative' => ''];
if (!empty($variazioni)) {
	$query = "SELECT ID,prezzo,dato1 FROM  servizi WHERE ID IN (" . implode(',', $variazioni) . ")  AND IDstruttura=$IDstruttura ";
	$result = $link2->query($query);
	while ($row = $result->fetch_row()) {
		$IDvariazione = $row[0];
		$plus_variazione = $row['1'];
		$minus_variazione = $row['2'];

		if ($plus_variazione) {
			$variazioni_txt['positive'] .= '
			<div style="margin:5px 10px">
 		 		 <label><input class="uk-radio variazione" type="checkbox" data-modi="1" data-id="' . $IDvariazione . '" >  ' . traducis('', $IDvariazione, 1, $lang) . ' (  + ' . format_number($plus_variazione) . ' €)</label>
			</div> ';
		}

		if ($minus_variazione) {
			$variazioni_txt['negative'] .= '
			<div style="margin:5px 10px">
 		 		 <label><input class="uk-radio variazione" type="checkbox" data-modi="0" data-id="' . $IDvariazione . '" >  ' . traducis('', $IDvariazione, 1, $lang) . ' (  - ' . format_number($minus_variazione) . ' €)</label>
			</div> ';
		}

	}
}

$lista_ingredienti = [];
$ingredienti = get_ingredienti_servizio([$IDservizio], $IDstruttura);
if (!empty($ingredienti)) {
	foreach ($ingredienti[$IDservizio] as $dati) {
		$lista_ingredienti[] = traducis('', $dati['IDingrediente'], 1, $lang);
	}
}

$descrizione = strip_tags(traducis('', $IDservizio, 2, $lang));

$foto = getfoto($IDservizio, 4);

$testo = '
<div class="nav navbar_picker_flex" >
 	<div onclick="chiudi_picker()"><i class="fas fa-times icona_chiudi" ></i></div>
 	<div style="margin-top:5px;padding-right:10px">  ' . traducis('', $IDservizio, 1, $lang) . '

 	</div>
</div>




<div class="content" style="margin-top:0;height: calc(100% - 110px);">
	<div>

	' . ($foto != 'camera.jpg' ? '<div  style="height:150px;background-image:url(' . base_url() . '/immagini/big' . $foto . ');background-size:cover;background-position:center;background-repeat:no-repeat"></div>' : '') . '




	' . ($descrizione != '' ?
	'
		<div class="titolo_paragrafo" style="   margin-top: 0;   padding: 0;  padding-left: 5px; ">
	    	<div>' . traduci('Descrizione', $lang) . ' </div>
	    </div>
	 	<div style="padding:0 10px;">' . $descrizione . '</div>'

	: '') . '


	' . (!empty($lista_ingredienti) ?
	'<div class="titolo_paragrafo" style="   margin-top: 0;   padding: 0;  padding-left: 5px; padding-top: 10px;">
	    	<div>' . traduci('Ingredienti', $lang) . ' </div>
	    </div>
	 	<div style="padding:0 10px;">' . implode(', ', $lista_ingredienti) . '</div>'

	: '') . '


	' . (!empty($allergeni) ? '<div class="titolo_paragrafo" style="   margin-top: 0;   padding: 0;  padding-left: 5px;  padding-top: 10px;">
	    	<div>' . traduci('Allergeni', $lang) . ' </div>
	    </div>
	 	<div style="padding:0 10px;">' . implode(', ', $allergeni) . '</div>' : '') . '





	</div>

	<div style="    width: 100%;  position: fixed;  bottom: 0;   left: 0;">
		<button style="    background: #2574ec!important;  border: none;  width: 100%; padding: 15px;   color: #fff;  font-size: 20px;  outline: none;" onclick="
		chiudi_picker();collega_piatto_menu(' . $IDservizio . ')">' . traduci('Seleziona', $lang) . '</button>
	</div>

</div>';

echo $testo;

//switch_tab_content_menu('.tab_content_tasto_menu .tasto_menu_default','.div_elenco_tab .content_tab_menu');
?>
