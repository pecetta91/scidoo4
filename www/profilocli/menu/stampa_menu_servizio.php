<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';
$IDprenotazione = $_SESSION['IDstrpren'];
$IDstruttura = get_IDstruttura_from_IDpren($IDprenotazione);
$IDaddebito = $_POST['IDaddebito'];
$tipo = $_POST['tipo'];
$lang = $_SESSION['lang'] ?: 0;

$dati_prenotazione = get_prenotazioni(['0' => ['IDprenotazione' => $IDprenotazione]], null, [], $IDstruttura);
$dettaglio_prenotazione = $dati_prenotazione['dati'][$IDprenotazione];

$dati_struttura = genera_sotto_strutture_lista($IDstruttura)[0];

$lista_servizi = get_dati_conto_riferimento($IDprenotazione, 0, [], $IDstruttura)['arr_servizi'];

$menu = array_filter($lista_servizi, function ($arg) use ($IDaddebito) {return $arg['IDaddebito'] == $IDaddebito;})[$IDaddebito];

$IDservizio = $menu['IDserv'];
$IDsottotip = $menu['IDsottotip'];
$time = time0($menu['time']);
$time_fine = $time + 86400;
$info_servizi = get_info_from_IDserv(null, null, $IDstruttura);

$testo = '';
switch ($tipo) {
case 0:

	$portate = [];
	$query = "SELECT portata,IDpiatto FROM dispgiorno  WHERE IDsottotip=$IDsottotip  AND data>$time AND data<$time_fine     ";
	$result = mysqli_query($link2, $query);
	while ($row = mysqli_fetch_row($result)) {
		$portate[$row[0]][] = $row[1];
	}

	if (!empty($portate)) {
		foreach ($portate as $numero_portata => $dati) {
			$sottitip = [];
			$piatti_txt = '';
			foreach ($dati as $IDpiatto) {

				if (!in_array($info_servizi[$IDpiatto]['IDsottotip'], $sottitip)) {
					$sottitip[] = $info_servizi[$IDpiatto]['IDsottotip'];
				}

				$piatti_txt .= '
			<div class=" uk_grid_div div_list_uk" uk-grid  >
				<div class="uk-width-expand lista_grid_nome">' . traducis('', $IDpiatto, 1, $lang) . '</div>
				<div class="uk-width-auto uk-text-right lista_grid_right" >    </div>
			</div> ';
			}
			$nome_sottotip = [];
			if (!empty($sottitip)) {
				foreach ($sottitip as $IDsotto) {
					$nome_sottotip[] = traducis('', $IDsotto, 3, $lang);
				}
			}

			$testo .= '<div class="div_uk_divider_list uk-text-uppercase" style="margin-top:0px !important;">' . implode(', ', $nome_sottotip) . '</div>  ' . $piatti_txt;

		}
	}
	break;
case 1:

	$lista_variazioni = [];
	foreach ($lista_servizi as $id => $value) {
		//if ($value['IDaddebito'] == 1211) {print_html($value);}
		foreach ($value['componenti'] as $componente) {
			$c = $componente['IDaddebito_collegato_riferimento'];
			if ($c) {
				$componente['parent'] = $value;
				$collegamenti[$c][$componente['IDaddebito_collegato']] = $componente;

			}
		}
	}

	$lista_menu = ristorante_genera_ordinazione($IDstruttura, $IDprenotazione, 0, $IDsottotip, $time);

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
						$nome_portata[] = traducis('', $IDsotto, 3, $lang);
					}
				}

				$html = '	<div class="div_uk_divider_list uk-text-uppercase" style="margin-top:0px !important;">' . (!empty($nome_portata) ? implode(', ', $nome_portata) : traduci('Piatto ', $lang) . ' ' . $dati['IDsottotip']) . '</div>  ';
			}

			$IDaddebito_principale = 0;

			$IDaddebito_collegati = (isset($dati['IDaddebito_collegato']) ? $dati['IDaddebito_collegato'] : 0);

			if (isset($collegamenti[$dati['IDparent']][$IDaddebito_collegati])) {
				$IDaddebito_principale = $collegamenti[$dati['IDparent']][$IDaddebito_collegati]['parent']['IDaddebito'];

			}

			$elenco_variazioni = '';
			$nome_variazione = [];
			if (isset($collegamenti[$IDaddebito_collegati])) {
				foreach ($collegamenti[$IDaddebito_collegati] as $dati_addebiti) {
					$variazione = strtolower(traducis('', $dati_addebiti['parent']['IDserv'], 1, $lang));
					$nome_variazione[] = $variazione;
					$elenco_variazioni .= '
					<li style="color:#d80404" onclick="chiudi_picker();mod_ospite(28,' . $dati_addebiti['IDaddebito_collegato'] . ',0,10,()=>{stampa_menu_addebito_web_app(' . $IDaddebito . ',1)});">' . traduci('Elimina', $lang) . ' ' . $variazione . ' </li>';
				}
			}

			$onclick = (isset($dati['IDserv']) ? 'pulsanti_modifica_piatto(this)' : 'visualizza_elenco_piatti_portate(this)');
			$nome_servizio = (isset($dati['IDserv']) ? traducis('', $dati['IDserv'], 1, $lang) . ' ' . (!empty($nome_variazione) ? '<span><br>' . implode(', ', $nome_variazione) : '')

				: '<div style="color:#CB0003">' . traduci('Seleziona Piatto', $lang) . '</div>');

			/*
				if (time() > $time) {
					$onclick = '';
			*/

			$testo .= $html . '

			<input type="hidden" value="' . base64_encode($elenco_variazioni) . '" id="variazioni' . $IDaddebito_principale . '">
	    	<div class=" uk_grid_div div_list_uk" uk-grid
	            data-parent="' . $dati['IDparent'] . '"
	   		    data-riga="' . $dati['riga'] . '"
	    		data-menu="' . $dati['menu'] . '"
				data-collegato="' . (isset($dati['IDserv']) ? $dati['IDserv'] : 0) . '"
				data-IDaddebito-menu="' . $IDaddebito . '"
				data-idaddebito_collegati="' . $IDaddebito_collegati . '"
				data-idaddebito="' . $IDaddebito_principale . '" onclick="' . $onclick . '">
		    <div class="uk-width-expand lista_grid_nome">' . $nome_servizio . '</div>
		    <div class="uk-width-auto uk-text-right lista_grid_right" > <i class="fas fa-chevron-right"></i>   </div>
		</div> ';
		}
	}

	break;
}

echo $testo;

?>

