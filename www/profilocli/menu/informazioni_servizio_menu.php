<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';
$IDprenotazione = $_SESSION['IDstrpren'];
$lang = $_SESSION['lang'] ?: 0;
$IDstruttura = get_IDstruttura_from_IDpren($IDprenotazione);

$IDservizio = $_POST['IDservizio'];
$IDaddebito = $_POST['IDaddebito'];
$riga = $_POST['riga'];

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

$lista_ingredienti = [];
$ingredienti = get_ingredienti_servizio([$IDservizio], $IDstruttura);
if (!empty($ingredienti)) {
	foreach ($ingredienti[$IDservizio] as $dati) {
		$lista_ingredienti[] = traducis('', $dati['IDingrediente'], 1, $lang);
	}
}

$descrizione = strip_tags(traducis('', $IDservizio, 2, $lang));
$foto = getfoto($IDservizio, 4);
$prezzo = $dati_serv['prezzo'];

$lista_servizi = get_dati_conto_riferimento($IDprenotazione, 0, [], $IDstruttura)['arr_servizi'];
$menu = array_filter($lista_servizi, function ($arg) use ($IDaddebito) {return $arg['IDaddebito'] == $IDaddebito;})[$IDaddebito];

//$IDservizio_menu = $menu['IDserv'];
$IDsottotip = $menu['IDsottotip'];
$time = time0($menu['time']);

$lista_menu = ristorante_genera_ordinazione($IDstruttura, $IDprenotazione, 0, $IDsottotip, $time);
$servizi_ordinati = ['inclusi' => 0, 'pagamento' => 0];
foreach ($lista_menu['piatti_menu'] as $dati) {
	if ($dati['IDserv'] != $IDservizio) {continue;}
	$servizi_ordinati['inclusi'] += 1;
}

if (!empty($lista_menu['prodotti'])) {
	foreach ($lista_menu['prodotti'] as $dati) {
		if ($dati['IDserv'] != $IDservizio) {continue;}
		$servizi_ordinati['pagamento'] += 1;
		$servizi_ordinati['inclusi'] += 1;
	}
}

/*
' . ($foto != 'camera.jpg' ? '<div  style="height:150px;background-image:url(' . base_url() . '/immagini/big' . $foto . ');background-size:cover;background-position:center;background-repeat:no-repeat"></div>' : '') . '

' . ($descrizione != '' ?
'
<div class="titolo_paragrafo" style="   margin-top: 0;   padding: 0;  padding-left: 5px; ">
<div>' . traduci('Descrizione', $lang) . ' </div>
</div>
<div style="padding:0 10px;">' . $descrizione . '</div>'

: '') . '

' . (!empty($allergeni) ? '<div class="titolo_paragrafo" style="   margin-top: 0;   padding: 0;  padding-left: 5px;  padding-top: 10px;">
<div>' . traduci('Allergeni', $lang) . ' </div>
</div>
<div style="padding:0 10px;">' . implode(', ', $allergeni) . '</div>' : '') . '

 */

$testo = '
<div class="nav navbar_picker_flex" >
 	<div onclick="chiudi_picker();"><i class="fas fa-times icona_chiudi" ></i></div>
 	<div style="margin-top:5px;padding-right:10px">   </div>
</div>



<input type="hidden" value="' . $IDservizio . '" id="IDpiatto">
<input type="hidden" value="' . $riga . '" id="riga_menu">
<div class="content" style="margin-top:0;height: calc(100% - 110px);">



	<div style="text-align:center;margin-top:10px">


		<div style="font-weight:600;font-size:17px;">' . traducis('', $IDservizio, 1, $lang) . ' <br> ' . ($prezzo != 0 ? format_number($prezzo) . ' €' : 'Gratis') . '</div>

		<div style="padding:0 10px;font-size:13px;">' . implode(', ', $lista_ingredienti) . '</div>


			 	<div style="margin-top:20px">
				 	<div class="stepper  stepper-init stepperrestr" style="width:100%;font-size:27px;    place-content: center;">
				 		 <div class="stepper-button-minus" style="color:#0075ff;border:none;width:33%;height:50px" onclick="
				 		 selezionainfo(\'numero_serv\',2,0);modifica_piatto_menu(0)"><i class="fas fa-minus"></i></div>

				 		 	<div class="stepper-value   " min="0" data-id="" id="numero_serv" max="99" style="border-bottom:1px solid #d6d6d6;width:60px;height:50px" >' . $servizi_ordinati['inclusi'] . '</div>

				 		 <div class="stepper-button-plus" style="color:#0075ff;border:none;width:33%;height:50px" onclick="selezionainfo(\'numero_serv\',1,0);modifica_piatto_menu(1)"><i class="fas fa-plus"></i></div>
				 	</div>
				</div>


				<div  style="margin-top:40px;color:#d80404"
					onclick="mod_ospite(38,' . $IDservizio . ',' . $IDaddebito . ',10,()=>{ 	chiudi_picker();   })" >' . ($servizi_ordinati['inclusi'] > 0 ? traduci('Rimuovi tutti i prodotti', $lang) : '') . '</div>

		</div>


		<div style="    width: 100%;  position: fixed;  bottom: 0;   left: 0;">
<button style="    background: #2574ec!important;  border: none;  width: 100%; padding: 15px;   color: #fff;  font-size: 20px;  outline: none;" onclick="chiudi_picker();">' . traduci('Continua', $lang) . '</button>
</div>

</div>';

/*	*/
echo $testo;

?>

