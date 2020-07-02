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

$IDservizio_menu = $menu['IDserv'];
$IDsottotip = $menu['IDsottotip'];
$time = time0($menu['time']);

$info_servizi = get_info_from_IDserv(null, null, $IDstruttura);

$piatti_possibili = ristorante_get_piatti_menu($IDservizio_menu, $time)[$IDservizio_menu];

switch ($tipo) {
case 0:

	$lista_menu = ristorante_genera_ordinazione($IDstruttura, $IDprenotazione, 0, $IDsottotip, $time);
	$ordinazione_riga_completata = [];
	$servizi_ordinati = [];
	foreach ($lista_menu['piatti_menu'] as $dati) {
		$riga = $dati['riga'];
		if (!isset($ordinazione_riga_completata[$riga])) {
			$ordinazione_riga_completata[$riga] = 0;
		}

		if (empty($dati['IDserv'])) {
			$ordinazione_riga_completata[$riga] += 1;
		}

		if (!empty($dati['IDserv'])) {

			if (!isset($servizi_ordinati[$dati['IDserv']]['inclusi'])) {
				$servizi_ordinati[$dati['IDserv']]['inclusi'] = 0;
			}
			$servizi_ordinati[$dati['IDserv']]['inclusi'] += 1;
		}

	}

	if (!empty($lista_menu['prodotti'])) {
		foreach ($lista_menu['prodotti'] as $dati) {
			if (!empty($dati['IDserv'])) {
				if (!isset($servizi_ordinati[$dati['IDserv']]['pagamento'])) {
					$servizi_ordinati[$dati['IDserv']]['pagamento'] = 0;
				}
				$servizi_ordinati[$dati['IDserv']]['pagamento'] += 1;
				$servizi_ordinati[$dati['IDserv']]['inclusi'] += 1;
			}
		}
	}

	$elenco_portate = [];
	if (!empty($piatti_possibili)) {

		foreach ($piatti_possibili as $riga => $dati) {
			$lista_servizi = [];
			if (isset($dati['menu_del_giorno'])) {
				foreach ($dati['menu_del_giorno'] as $dati_portata) {
					$lista_servizi[$dati_portata['IDservizio']] = $dati_portata;
				}
			}

			if (isset($dati['prodotti'])) {
				foreach ($dati['prodotti'] as $dati_portata) {
					$lista_servizi[$dati_portata['IDservizio']] = $dati_portata;
				}
			}

			$persone = (isset($dati_portata['persone']) ? $dati_portata['persone'] : []);

			$elenco_portate[$riga] = ['servizi' => $lista_servizi, 'persone' => $persone, 'portata' => $dati['portata']];
		}
	}

	if (!empty($elenco_portate)) {

		foreach ($elenco_portate as $riga => $dati) {
			$sottitip = [];
			$piatti_txt = '';

			$lista_ingredienti = get_ingredienti_servizio(array_keys($dati['servizi']), $IDstruttura);
			foreach ($dati['servizi'] as $dati_dispo) {
				$IDpiatto = $dati_dispo['IDservizio'];

				if (!in_array($info_servizi[$IDpiatto]['IDsottotip'], $sottitip)) {
					$sottitip[] = $info_servizi[$IDpiatto]['IDsottotip'];
				}

				$ingredienti = [];
				if (!empty($lista_ingredienti[$IDpiatto])) {
					foreach ($lista_ingredienti[$IDpiatto] as $dati_ingredienti) {
						$ingredienti[] = traducis('', $dati_ingredienti['IDingrediente'], 1, $lang);
					}
				}

				$quantita_piatti_inclusi = (!empty($servizi_ordinati[$IDpiatto]['inclusi']) ? ($servizi_ordinati[$IDpiatto]['inclusi'] > 0
					? '<div style="position:absolute ; top: -1px;   left: 8px;  width: 17px;  font-size: 14px;  text-align: center;
					height: 17px; line-height: 17px;border-radius:50%;background:#3652AF;color:#fff">' . $servizi_ordinati[$IDpiatto]['inclusi'] . '</div>' : ''
				) : '');

				$servizi_da_pagare = '';
				if (!empty($servizi_ordinati[$IDpiatto]['pagamento'])) {
					if ($servizi_ordinati[$IDpiatto]['pagamento'] > 0) {
						$prezzo_da_pagare = $servizi_ordinati[$IDpiatto]['pagamento'] * $dati_dispo['prezzo'];
						$servizi_da_pagare = ($prezzo_da_pagare == 0 ? 'Gratis' : number_format($prezzo_da_pagare) . ' €');
					}
				}

				//<br><span>' . ($ordinazione_riga_completata[$riga] == 0 ? ($dati_dispo['prezzo'] == 0 ? 'Gratis' : number_format($dati_dispo['prezzo']) . ' €') : traduci('Incluso nel menu', $lang)) . '

/*

<div class="uk-width-auto  uk-text-right lista_grid_right c000 ">

<span style="text-decoration:underline;padding:1px 5px;color:#0075ff;font-size:12px">' . traduci('Aggiungi', $lang) . '</span>
</div>
 */

				$piatti_txt .= '
				<div class="div_list_uk uk_grid_div" uk-grid style="min-height:55px;">
				    <div class="uk-width-expand lista_grid_nome uk-first-column uk-position-relative" onclick="visualizza_informazioni_servizio_menu(' . $IDpiatto . ',' . $riga . ')">
					    ' . $quantita_piatti_inclusi . '
	 					' . traducis('', $IDpiatto, 1, $lang) . ' <span style="font-size:12px">' . ($servizi_da_pagare != '' ? '+ ' . $servizi_da_pagare : '') . '</span><br><span>' . implode(', ', $ingredienti) . '</span>

	 					<div  style="text-decoration:underline;padding:1px 5px;color:#0075ff;font-size:12px">' . traduci('Seleziona', $lang) . '</div>

 					</div>


				</div>';
			}
			// onclick="collega_piatto_menu(' . $IDpiatto . ',' . $riga . ')"
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
case 2:
	$lista_menu = ristorante_genera_ordinazione($IDstruttura, $IDprenotazione, 0, $IDsottotip, $time);
	//print_html($lista_menu['piatti_menu']);
	$current_sottotip = null;
	foreach ($lista_menu['piatti_menu'] as $dati) {

		if (!empty($dati['IDserv'])) {
			if ($current_sottotip != $dati['IDsottotip']) {
				$current_sottotip = $dati['IDsottotip'];
				$testo .= '<div class="div_uk_divider_list uk-text-uppercase" style="margin-top:0px !important;">' . traduci('Portata', $lang) . ' ' . $dati['IDsottotip'] . '</div>';
			}
			/*

				data-idaddebito="' . $IDaddebito_principale . '"
			*/
			$testo .= '
			<div class="div_list_uk uk_grid_div" uk-grid onclick="pulsanti_modifica_piatto(this)"
				data-parent="' . $dati['IDparent'] . '"
				data-riga="' . $dati['riga'] . '"
				data-menu="' . $dati['menu'] . '"
				data-idaddebito_collegato="' . $dati['IDaddebito_collegato'] . '"
				data-idservizio="' . $dati['IDserv'] . '">

			    <div class="uk-width-expand lista_grid_nome uk-first-column" >' . traducis('', $dati['IDserv'], 1, $lang) . '</div>
	    		<div class="uk-width-auto  uk-text-right lista_grid_right c000 "> <i class="fas fa-chevron-right"></i> </div>
			</div>';
		}
	}

	$prodotti = '';
	$variazioni = [];
	if (!empty($lista_menu['variazioni'])) {
		//print_html($lista_menu['variazioni']);
		foreach ($lista_menu['variazioni'] as $dati) {
			$IDaddebito_collegato_riferimento = reset($dati['componenti'])['IDaddebito_collegato_riferimento'];
			$variazioni[$IDaddebito_collegato_riferimento][] = $dati;
		}
	}

	if (!empty($lista_menu['prodotti'])) {
		//print_html($lista_menu['prodotti']);
		foreach ($lista_menu['prodotti'] as $dati) {
			$prezzo = array_sum(array_column($dati['componenti'], 'prezzo'));
			$IDaddebito_collegato = reset($dati['componenti'])['IDaddebito_collegato'];
			$nome_variazione = [];
			$elenco_variazioni = '';
			if (isset($variazioni[$IDaddebito_collegato])) {
				foreach ($variazioni[$IDaddebito_collegato] as $dati_variazioni) {
					$IDaddebito_collegato_variazione = reset($dati_variazioni['componenti'])['IDaddebito_collegato'];

					$quantita = reset($dati_variazioni['componenti'])['qta'];
					$prezzo = reset($dati_variazioni['componenti'])['prezzo'];

					$variazione = strtolower(traducis('', $dati_variazioni['IDserv'], 1, $lang));

					$nome_variazione[] = '<span ' . ($quantita < 0 ? 'style="color:#d80404"' : '') . ' > ' . ($quantita < 0 ? '-' : '') . ' ' . $variazione . ' ' . ($prezzo != 0 ? number_format($prezzo) . ' €' : '') . '</span>';

					$elenco_variazioni .= '
					<li style="color:#d80404" onclick="chiudi_picker();mod_ospite(28,' . $IDaddebito_collegato_variazione . ',0,10,()=>{stampa_menu_addebito_web_app(' . $IDaddebito . ',1)});">' . traduci('Elimina', $lang) . ' ' . $variazione . ' </li>';
				}
			}

			$prodotti .= '
				<input type="hidden" value="' . base64_encode($elenco_variazioni) . '" id="variazioni' . $dati['IDaddebito'] . '">
				<div class="div_list_uk uk_grid_div" uk-grid
				data-idaddebito="' . $dati['IDaddebito'] . '"
				data-idaddebito_collegato="' . $IDaddebito_collegato . '"
				data-idservizio="' . $dati['IDserv'] . '"
				data-variazioni="1"
				onclick="pulsanti_modifica_piatto(this)">
				    <div class="uk-width-expand lista_grid_nome uk-first-column" >' . traducis('', $dati['IDserv'], 1, $lang) . '
				    	' . (!empty($nome_variazione) ? '<br>' . implode(', ', $nome_variazione) : '') . '</div>
		    		<div class="uk-width-auto  uk-text-right lista_grid_right c000 "  >  ' . ($prezzo == 0 ? 'Gratis' : number_format($prezzo) . ' €') . '  <i class="fas fa-chevron-right"></i></div>
				</div>';
		}
		$testo .= '<div class="div_uk_divider_list uk-text-uppercase" style="margin-top:0px !important;">' . traduci('A pagamento', $lang) . '</div>' . $prodotti;
	}

	break;
case 1:

	$elenco_portate = [];
	if (!empty($piatti_possibili)) {

		foreach ($piatti_possibili as $riga => $dati) {
			$lista_servizi = [];
			if (isset($dati['menu_del_giorno'])) {
				foreach ($dati['menu_del_giorno'] as $dati_portata) {
					$lista_servizi[$dati_portata['IDservizio']] = $dati_portata;
				}
			}

			if (isset($dati['prodotti'])) {
				foreach ($dati['prodotti'] as $dati_portata) {
					$lista_servizi[$dati_portata['IDservizio']] = $dati_portata;
				}
			}

			$persone = (isset($dati_portata['persone']) ? $dati_portata['persone'] : []);

			$elenco_portate[$riga] = ['servizi' => $lista_servizi, 'persone' => $persone, 'portata' => $dati['portata']];
		}
	}

	$lista_restrizioni = get_restrizioni($IDstruttura)['lista_restrizioni'];
	$lista_menu = ristorante_genera_ordinazione($IDstruttura, $IDprenotazione, 0, $IDsottotip, $time);

	$menu_persone = [];
	foreach ($lista_menu['menu'] as $dati) {
		foreach ($dati['componenti'] as $dati_componenti) {
			$IDrestrizione = $dati_componenti['IDrestr'];
			$IDaddebito_collegato = $dati_componenti['IDaddebito_collegato'];
			$menu_persone[$IDaddebito_collegato] = ['IDaddebito_collegato' => $IDaddebito_collegato, 'IDrestrizione' => $IDrestrizione, 'portate' => []];
		}
	}

	foreach ($lista_menu['piatti_menu'] as $dati) {
		$IDparent = $dati['IDparent'];
		$menu_persone[$IDparent]['portate'][] = $dati;
	}

	$elenco_persone = '';
	foreach ($menu_persone as $dati) {
		$current_sottotip = null;
		$dettaglio_persona = '';
		$IDaddebito_collegato = $dati['IDaddebito_collegato'];
		$lista_servizi = ['inclusi' => 0, 'da_selezionare' => 0];

		if (!empty($dati['portate'])) {
			foreach ($dati['portate'] as $dati_portate) {
				$riga = $dati_portate['riga'];
				$sottotipologie = [];
				if (!empty($dati_portate['IDserv'])) {
					$servizio_selezionato = $dati_portate['IDserv'];
					$lista_servizi['inclusi']++;
				} else {
					$lista_servizi['da_selezionare']++;
				}

				//$dati_portate['IDaddebito_collegato']

				$lista_servizi_da_selezionare = '';

				if (isset($elenco_portate[$riga]['servizi'])) {
					foreach ($elenco_portate[$riga]['servizi'] as $dati_servizio) {
						$IDservizio = $dati_servizio['IDservizio'];

						if (!in_array($info_servizi[$IDservizio]['IDsottotip'], $sottotipologie)) {
							$sottotipologie[] = $info_servizi[$IDservizio]['IDsottotip'];
						}

						$lista_servizi_da_selezionare .= '
						<div class="div_list_uk uk_grid_div" uk-grid>
							<div class="uk-width-auto " style="margin:auto">
							<input class="uk-radio"
							onchange="mod_ospite(39,[' . $IDservizio . ',' . $IDservizio_menu . ',' . $IDsottotip . '],[' . $time . ',' . $IDaddebito_collegato . ',' . $riga . '],10)"
							type="radio" name="menu_' . $IDaddebito_collegato . '_' . $riga . '"
							id="' . $IDservizio . '_' . $IDaddebito_collegato . '_' . $riga . '" ' . ($servizio_selezionato == $IDservizio ? 'checked' : '') . '> </div>

							<div class="uk-width-expand lista_grid_nome uk-first-column" >
							<label for="' . $IDservizio . '_' . $IDaddebito_collegato . '_' . $riga . '">' . traducis('', $IDservizio, 1, $lang) . '</label></div>

						</div>';
					}
				}

				if ($current_sottotip != $riga) {
					$current_sottotip = $riga;
					$nome_portata = [];
					if (!empty($sottotipologie)) {
						foreach ($sottotipologie as $IDsotto) {
							$nome_portata[] = traducis('', $IDsotto, 3, $lang);
						}
					}

					$dettaglio_persona .= '<div class="div_uk_divider_list uk-text-uppercase" style="margin:0px !important;">
					' . (!empty($nome_portata) ? implode(', ', $nome_portata) : traduci('Portata', $lang) . ' ' . $dati_portate['IDsottotip']) . ' </div>';
				}

				$dettaglio_persona .= $lista_servizi_da_selezionare;
			}
		}

		$elenco_persone .= '
		<li>
			<a class="uk-accordion-title no_before" href="#" style="    border-bottom: 1px solid #2574ec; color: #2574ec;"> ' . traduci('Menu ', $lang) . '
		 	' . $lista_restrizioni[$dati['IDrestrizione']]['restrizione'] . '
		 		<span style="font-size:14px">' . ($lista_servizi['inclusi'] > 0 ? $lista_servizi['inclusi'] . ' ' . traduci('Inclusi', $lang) : '') . '</span>
		 		<span style="font-size:14px;color:#d80404">' . ($lista_servizi['da_selezionare'] > 0 ? $lista_servizi['da_selezionare'] . ' ' . traduci('Da Aggiungere', $lang) : '') . '
		 		</span>
			</a>
		 <div class="uk-accordion-content" style="margin:0"> ' . $dettaglio_persona . '	</div>
		</li>';

	}

	$testo = '<ul style="margin:0;padding: 5px 10px;" uk-accordion="multiple: true;" >' . $elenco_persone . ' </ul>';

	break;

}

/*

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

if (time() > $time) {
$onclick = '';

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

 */

echo $testo;

?>

