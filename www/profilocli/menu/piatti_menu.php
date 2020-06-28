<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';
$IDprenotazione = $_SESSION['IDstrpren'];
$IDstruttura = get_IDstruttura_from_IDpren($IDprenotazione);
$IDaddebito = $_POST['IDaddebito'];
$lang = $_SESSION['lang'] ?: 0;

$dati_prenotazione = get_prenotazioni(['0' => ['IDprenotazione' => $IDprenotazione]], null, [], $IDstruttura);
$dettaglio_prenotazione = $dati_prenotazione['dati'][$IDprenotazione];

$dati_struttura = genera_sotto_strutture_lista($IDstruttura)[0];

$lista_servizi = get_dati_conto_riferimento($IDprenotazione, 0, [], $IDstruttura)['arr_servizi'];

$menu = array_filter($lista_servizi, function ($arg) use ($IDaddebito) {return $arg['IDaddebito'] == $IDaddebito;})[$IDaddebito];

$IDservizio = $menu['IDserv'];

$lista_menu = ristorante_genera_ordinazione($IDstruttura, $IDprenotazione, 0, $menu['IDsottotip'], $menu['time']);
$piatti = $lista_menu['piatti_menu'];
$piatti_txt = '';

if (!empty($piatti)) {
	$prodotti_riga = [];
	$righe = [];
	foreach ($piatti as $dati) {
		$righe[$dati['riga']] = 1;
	}

	if (!empty($righe)) {

		$lista_righe = implode(',', array_keys($righe));

		$query = "SELECT l.riga,s.IDsottotip
		FROM limiticatportate AS l
		LEFT JOIN servizi AS s ON (l.IDobj=s.ID)
		WHERE l.IDstr='$IDstruttura' AND l.IDserv=$IDservizio AND l.riga IN ($lista_righe) AND l.tipoobj=2";
		$result = mysqli_query($link2, $query);
		while ($row = $result->fetch_row()) {
			$prodotti_riga[$row[0]][$row[1]] = $row[1];

		}

	}

	$current_sottotip = null;
	foreach ($piatti as $dati) {
		$html = '';
		if ($current_sottotip != $dati['IDsottotip']) {
			$current_sottotip = $dati['IDsottotip'];
			$nome_portata = [];
			if (isset($prodotti_riga[$dati['riga']])) {
				foreach ($prodotti_riga[$dati['riga']] as $IDsotto) {
					$nome_portata[] = strtolower(traducis('', $IDsotto, 3, $lang));
				}
			}

			$html = '	<div class="div_uk_divider_list" style="margin-top:0px !important;">' . (!empty($nome_portata) ? implode(', ', $nome_portata) : traduci('Piatto ', $lang) . ' ' . $dati['IDsottotip']) . '</div>  ';
		}

		$piatti_txt .= $html . '


	    <div class=" uk_grid_div div_list_uk" uk-grid   data-parent="' . $dati['IDparent'] . '" data-riga="' . $dati['riga'] . '" data-menu="' . $dati['menu'] . '"
				data-collegato="' . (isset($dati['IDserv']) ? $dati['IDserv'] : 0) . '" onclick="visualizza_elenco_piatti_portate(this)">
		    <div class="uk-width-expand lista_grid_nome">' . (isset($dati['IDserv']) ? traducis('', $dati['IDserv'], 1, $lang) : traduci('Seleziona Piatto', $lang)) . '</div>
		    <div class="uk-width-auto uk-text-right lista_grid_right" > <i class="fas fa-chevron-right"></i>   </div>
		</div> ';
	}
}

$elenco_servizi = '';
$servizi = [];

$testo = '
<div class="nav navbar_picker_flex" >
 	<div onclick="chiudi_picker()"><i class="fas fa-times icona_chiudi" ></i></div>
 	<div style="margin-top:5px;padding-right:10px"> ' . traducis('', $IDservizio, 1, $lang) . '

 	</div>
</div>

<input type="hidden" value="' . $IDaddebito . '" id="IDaddebito_selezionato">
<input type="hidden" value="' . $menu['time'] . '" id="time_servizio">
<div class="content" style="margin-top:0;height: calc(100% - 50px);">
	<div  style="padding-top:5px;">
			' . $piatti_txt . '
	</div>
</div>

';

echo $testo;

?>


