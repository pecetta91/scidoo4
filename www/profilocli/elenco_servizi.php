<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../config/connecti.php';
require_once __DIR__ . '/../../config/funzioni.php';
$IDprenotazione = $_SESSION['IDstrpren'];
$IDstruttura = get_IDstruttura_from_IDpren($IDprenotazione);
$lang = $_SESSION['lang'] ?: 0;
$arr_dati = (isset($_POST['arr_dati']) ? $_POST['arr_dati'] : []);

$IDsottotipologia = $arr_dati['IDsottotipologia'];

$dettaglio_prenotazione = get_prenotazioni(['0' => ['IDprenotazione' => $IDprenotazione]], null, [], $IDstruttura)['dati'][$IDprenotazione];

$informazioni_tipo_servizio = get_informazioni_tiposervizio();

$servizi = get_info_from_IDserv(null, null, $IDstruttura);
$div_servizi = '';
if (!empty($servizi)) {
	foreach ($servizi as $dati) {
		if ($dati['attivo'] != 1) {continue;}
		if ($dati['web_app'] != 1) {continue;}
		if ($dati['IDsottotip'] != $IDsottotipologia) {continue;}

		$foto = getfoto($dati['ID'], 4);
		$dettaglio_prezzo = visualizza_prezzo_servizio($dati['ID'], $dettaglio_prenotazione['checkin'], $IDstruttura, $IDprenotazione, 0);

		if ($foto == 'camera.jpg') {
			$foto = ($informazioni_tipo_servizio[$dati['IDtipo']]['immagine'] != '' ? base_url() . '/img_template/' . $informazioni_tipo_servizio[$dati['IDtipo']]['immagine'] : '');
		} else {
			$foto = base_url() . '/immagini/big' . $foto;
		}

		$div_servizi .= '
		<div class="div_tipologia" onClick="navigation_ospite(21,{IDservizio:' . $dati['ID'] . '})">
			<div class="container_tipologia">
				<div class="div_immagine" style="background-image:url(' . $foto . ');position:relative">

					<div class="uk-text-truncate titolo_tipologia" >' . traducis('', $dati['ID'], 1, $lang) . '</div>
				</div>
			</div>
		</div>  ';

	}
}
/*
<div style="margin:0px 15px;text-align:center; border-radius:5px;background:#fff;position:relative; box-shadow:0px 2px 15px #c2c1c1;margin-bottom:60px" onclick="navigation_ospite(21,{IDservizio:' . $dati['ID'] . '})">

<div style="background:url(' . base_url() . '/immagini/big' . $foto . ') center center / cover no-repeat;background-size:cover;height:250px;width:100%;border-top-left-radius:5px;border-top-right-radius:5px"> </div>

<div style="margin-top:15px;font-weight:600;text-transform:uppercase;color:#1a237e;font-size:13px;">' . traducis('', $dati['ID'], 1, $lang) . '</div>

<div style="    padding: 0 10px; padding-bottom:20px;  margin-top: 10px;  display: inline-flex;   width: 100%;  justify-content: space-between;">
' . ($dettaglio_prezzo['prezzo'] ? format_number($dettaglio_prezzo['prezzo']) . ' €  ' . $dettaglio_prezzo['tipo_calcolo'] : '') . '
</div>

</div>
 */

$testo = '<div style="padding:10px"> ' . $div_servizi . '</div>';

echo $testo;

?>


<style>


.div_tipologia {margin: 10px 0; display: inline-flex; width: 49%;  place-content: space-evenly;}
.div_immagine{background-size: cover;background-repeat: no-repeat;background-position: center;width: 100%;height: 170px;border-radius:5px;}

.container_tipologia{width: 200px;  height: 170px;    border-radius: 5px;   place-content: center;   box-shadow: 0 0 10px 1px #efefef;   background: #fff;margin:0 5px;}
.titolo_tipologia{font-weight: 600;  font-size: 14px;  padding-left: 10px;   background: #1b1b1b8c; position: absolute;   width: 100%;  bottom: 0; color: #fff;border-bottom-right-radius: 5px;  border-bottom-left-radius: 5px;}

.pulsanti_funzione  .sotto_titolo{font-size: 13px;}

@media (min-width: 992px){
	.div_tipologia {width: 24%; }
}

</style>
