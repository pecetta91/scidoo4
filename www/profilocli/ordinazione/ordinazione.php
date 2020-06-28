<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';
$IDprenotazione = $_SESSION['IDstrpren'];
$IDstruttura = get_IDstruttura_from_IDpren($IDprenotazione);

$lang = $_SESSION['lang'] ?: 0;

$dati_prenotazione = get_prenotazioni(['0' => ['IDprenotazione' => $IDprenotazione]], null, [], $IDstruttura)['dati'][$IDprenotazione];

$gruppi_sale = elenco_gruppi_sale($IDstruttura);

$foto = getfoto($IDstruttura, 9);

$txt_sale = '';
if (!empty($gruppi_sale)) {
	foreach ($gruppi_sale as $dati) {
		$prima_sala = $dati['ID'];
		if ($dati['sale']) {
			$prima_sala = reset($dati['sale'])['ID'];
		}
		$txt_sale .= '
		<div class=" uk_grid_div div_list_uk" uk-grid   "  onclick="seleziona_sala_ordinazione_web_app(' . $prima_sala . ')">
					<div class="uk-width-1-4">
						<div style="height:60px;background-size:cover;background-image:url(' . base_url() . '/immagini/big' . $foto . ');background-repeat:no-repeat;background-position:center;"></div>
					</div>
		   			 <div class="uk-width-expand lista_grid_nome uk-text-truncate" style="padding-left: 10px; margin: 2px;">
					  	 	 <div>  ' . $dati['nome'] . '</div>
				    </div>
				    <div class="uk-width-auto uk-text-right lista_grid_right" >  <i class="fas fa-chevron-right"></i>   </div>
			</div>  	';

	}
}

$testo = '
<div class="div_uk_divider_list" style="margin-top:0px !important;">' . traduci('Dove Vuoi Riceverlo', $lang) . '</div>
	<div style="margin:0 10px;"   >' . $txt_sale . '</div> ';

echo $testo;

?>
