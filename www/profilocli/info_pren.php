<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../config/connecti.php';
require_once __DIR__ . '/../../config/funzioni.php';
$IDprenotazione = $_SESSION['IDstrpren'];
$IDstruttura = get_IDstruttura_from_IDpren($IDprenotazione);
$lang = $_SESSION['lang'] ?: 0;

$dati_prenotazione = get_prenotazioni(['0' => ['IDprenotazione' => $IDprenotazione]], null, [], $IDstruttura);

$dettaglio_prenotazione = $dati_prenotazione['dati'][$IDprenotazione];

$ospite_princiapale = reset(estrai_dati_ospiti([['IDprenotazione' => $IDprenotazione]], [], $IDstruttura)['dati']);

$dati_struttura = genera_sotto_strutture_lista($IDstruttura)[0];

$saldo = $dettaglio_prenotazione['asaldo_ospite'];
$saldo = array_reduce($dati_prenotazione['dati'], function ($saldo, $element) {return $saldo + $element['asaldo_ospite'] ?? 0;});
$totale_prenotazione_ospite = array_reduce($dati_prenotazione['dati'], function ($totale_prenotazione_ospite, $element) {return $totale_prenotazione_ospite + $element['totale_prenotazione_ospite'] ?? 0;});

$time_line[time0($dettaglio_prenotazione['checkin'])][] = ['tipo' => 'checkin'];
$time_line[time0($dettaglio_prenotazione['checkout'])][] = ['tipo' => 'checkout'];
$time_line[time0($dettaglio_prenotazione['time_creazione'])][] = ['tipo' => 'creazione'];

if (!empty($dettaglio_prenotazione['depositi'])) {
	foreach ($dettaglio_prenotazione['depositi'] as $dati) {
		$time_line[time0($dati['scadenza'])][] = ['tipo' => 'deposito', 'dati' => $dati];
	}
}

if (!empty($dettaglio_prenotazione['pagamenti_ospite'])) {
	foreach ($dettaglio_prenotazione['pagamenti_ospite'] as $dati) {
		$time_line[time0($dati['time'])][] = ['tipo' => 'pagamenti', 'dati' => $dati];
	}
}

$depositi_segnalati = [];
$query = "SELECT IDdeposito,time FROM deposito_prenotazione_segnalazioni WHERE IDprenotazione =$IDprenotazione AND IDstruttura=$IDstruttura";
$result = mysqli_query($link2, $query);
while ($row = mysqli_fetch_row($result)) {
	$depositi_segnalati[$row[0]] = $row[1];
}

$lista_restrizioni = get_restrizioni($IDstruttura)['lista_restrizioni'];

$lista_ospiti = [];
$query = "SELECT IDrest  FROM infopren  WHERE IDstr=$IDstruttura AND IDpren=$IDprenotazione AND pers='1'";
$result = mysqli_query($link2, $query);
while ($row = mysqli_fetch_row($result)) {
	if (isset($lista_ospiti[$row[0]])) {
		$lista_ospiti[$row[0]] += 1;
	} else {
		$lista_ospiti[$row[0]] = 1;
	}
}

$clienti = [];
foreach ($lista_ospiti as $IDrestrizione => $numero) {
	$restrizione_txt = ($numero > 1 ? $lista_restrizioni[$IDrestrizione]['restrizioni'] : $lista_restrizioni[$IDrestrizione]['restrizione']);
	$clienti[$IDrestrizione] = $numero . ' ' . traduci($restrizione_txt, $lang, $numero);
}

ksort($time_line);
$time_line_txt = '';
foreach ($time_line as $time => $val) {

	$testo_time = '';
	foreach ($val as $dati) {
		$descrizione = '';
		switch ($dati['tipo']) {
		case 'creazione':
			$descrizione = traduci('Prenotazione inserita  ', $lang) . ' ' . traduci('alle ore ', $lang) . ' ' . date('H:i', $dettaglio_prenotazione['time_creazione']);
			break;
		case 'deposito':

			$testo_deposito = '';
			if (isset($depositi_segnalati[$dati['dati']['ID']])) {
				$testo_deposito = '<strong style="color:#da731a">' . traduci('In Attesa di conferma da parte della Struttura', $lang) . ' <br> ' . traduci('Segnalato ', $lang) . ' ' . dataita($depositi_segnalati[$dati['dati']['ID']]) . '  ' . traduci('Alle Ore ', $lang) . ' ' . date('H:i', $depositi_segnalati[$dati['dati']['ID']]) . '</strong>';
			} else {
				$testo_deposito = ($dati['dati']['IDscontr'] ? '' : traduci('da Saldare', $lang) . ' ' . format_number($dati['dati']['prezzo']) . ' €' . '
					<button style="margin-top:5px;display:block; ' . (time_struttura($IDstruttura) < $dati['dati']['scadenza'] ? 'background:#da731a;' : '') . '"

					class="btn_sconti_dettagli" onclick="pulsanti_pagamento_webapp(' . $dati['dati']['ID'] . ')">' . traduci('Esegui', $lang) . ' <i class="fas fa-arrow-right"></i></button>');
			}

			$descrizione = traduci('Politiche di deposito', $lang) . ' ' . $testo_deposito;

			break;
		case 'checkin':
			if ($dettaglio_prenotazione['orario_confermato'] == 0) {
				if ($dettaglio_prenotazione['orario_checkin'] == '00:00') {
					$testo_checkin = '<span style="text-decoration:underline">  Checkin Default: ' . date('H:i', $dati_struttura['check_in']) . '</span>';
					$time_check = $dati_struttura['check_in'];
				} else {
					$testo_checkin = '<span style="text-decoration:underline"> Checkin Default:   ' . $dettaglio_prenotazione['orario_checkin'] . '</span>';

					list($hh, $ii) = explode(":", $dettaglio_prenotazione['orario_checkin']);
					$time_check = (floor((int) $hh * 3600)) + floor((int) $ii * 60);
				}

			} else {
				$testo_checkin = '<span style="color:#22bc51;text-decoration:underline""> Checkin ' . traduci('Confermato', $lang) . ':   ' . $dettaglio_prenotazione['orario_checkin'] . '</span>';

				list($hh, $ii) = explode(":", $dettaglio_prenotazione['orario_checkin']);
				$time_check = (floor((int) $hh * 3600)) + floor((int) $ii * 60);
			}

			$descrizione = '
				<div id="time_checkin" style="display:none;">
					<ul class="uk-list uk-list-divider uk-picker-bot " style="padding:5px 20px;"  onchange="(r)=>{mod_ospite(20, 0, r, 10,()=>{navigation_ospite(1,0)})};">
						' . genera_select_uikit(genera_ora_uikit(6, 23), $time_check) . '
					</ul>
				</div>

				<div onclick="carica_content_picker($(\'#time_checkin\'));" style="    font-size: 16px;font-weight:600" >' . $testo_checkin . '</div>';

			$descrizione .= '<button class="btn_sconti_dettagli" style="margin-top:5px;display: block;  background: #2574ec;"  onclick="location.href=' . "' https://maps.google.com/?q=" . $dati_struttura['indirizzo'] . "'" . '">' . traduci('Come Arrivare ', $lang) . '</button>';

			break;
		case 'checkout':
			$descrizione = '<span style="font-weight:600;    font-size: 16px;"> Checkout ' . traduci('ore', $lang) . '  ' . $dettaglio_prenotazione['orario_checkout'] . ' </span>
			<br>' . ($saldo ? traduci('A Saldo', $lang) . '  ' . format_number($saldo) . ' € ' . traduci('Di', $lang) . ' ' . format_number($totale_prenotazione_ospite) . ' €' : '');
			break;
		case 'pagamenti':

			$descrizione = '<div style="margin-bottom:10px">  <span style="font-weight:600;    font-size: 16px;">' . traduci('Pagamento di', $lang) . '   ' . format_number($dati['dati']['valore']) . ' €</span> <br>
			' . $dati['dati']['oggetto'] . '  ' . traduci('Eseguito Alle Ore ', $lang) . ' : ' . date('H:i', $dati['dati']['time']) . '   </div>';

			break;
		}

		$testo_time .= '
		<div style="background: #fff; font-size: 15px; min-height: 70px; padding: 0 5px;  border-radius: 2px;margin-bottom:15px;color:#000">
	   			 <div style="margin-top:5px">' . $descrizione . '</div>
		</div>';
	}

	$time_line_txt .= '

	<div style="margin-bottom:20px;padding-left:20px;position:relative">
			<div style="position: absolute; width: 20px; 	height: 20px; border-radius: 50%; 	font-size: 15px; border: 1px solid #2574ec ;text-align: center;  background: #fff;    top: 2px;"> </div>

			<div style="padding-left:40px">
					<div style="font-weight: 600; 	font-size: 16px; color: #0f0f0f; margin-bottom: 20px;">' . dataita($time) . '</div>

					<div style="margin: 0 5px;"> 	' . $testo_time . ' </div>

			</div>

	</div>';
}

$infop = '';
$pagamenti = get_pagamenti_webapp($IDstruttura);
if (!empty($pagamenti)) {
	foreach ($pagamenti as $IDpagamento => $dati) {
		$nome_pagamento = $dati['nome_pagamento'];
		$tipo_pagamento = $dati['tipo_pagamento'];
		$infop .= '<li class="pagamento" data-id="' . $IDpagamento . '" data-tipo="' . $tipo_pagamento . '"  >' . $nome_pagamento . '</li>';
	}
}
$info_pagamento = '<input type="hidden" value="' . base64_encode($infop) . '" id="pulsanti_pagamento" >';

$query = "SELECT IDdeposito,IDcancellazione FROM prenotazioni WHERE IDv =$IDprenotazione AND IDstruttura=$IDstruttura LIMIT 1";
$result = mysqli_query($link2, $query);
$row = mysqli_fetch_row($result);
$IDdeposito = $row[0] ?? 0;
$IDcancellazione = $row[1] ?? 0;

$stringa_descrizione_deposito = '';
if ($IDdeposito) {
	$stringa_descrizione_deposito = '<p style="color:#646464;  margin: 5px 0;  font-size: 13px;">' . traducis('', $IDdeposito, 45, $lang) . '</p>';
}

$stringa_descrizione_cancellazione = '';
if ($IDcancellazione) {
	$stringa_descrizione_cancellazione = '<p style="color:#646464;    margin: 5px 0;  font-size: 13px;">' . traducis('', $IDcancellazione, 42, $lang) . '</p>';
}

$testo = $info_pagamento . '
<div class="div_container_principale">


<div class="tab_info_pren" data-tab="0"   style="display:none" >
		<div style="position:relative;overflow:hidden;margin:10px 0">
			<div style="background-color: rgba(0, 0, 0, .12);  left: 29px; position: absolute;  width: 2px; height: 100vh; top: 10px;"> </div>
		' . $time_line_txt . '
		</div>

</div>


<div  class="tab_info_pren" data-tab="1" style="display:none" >
	<div class="div_uk_divider_list" style="margin-top:0px !important;">' . traduci('Dati prenotazione', $lang) . '</div>


		    <div class=" uk_grid_div div_list_uk" uk-grid  >
			    <div class="uk-width-2-3 lista_grid_nome">' . traduci('Numero prenotazione', $lang) . '</div>
			    <div class="uk-width-expand uk-text-right lista_grid_right" >  ' . $dettaglio_prenotazione['numero'] . '   </div>
			</div>


			 <div class=" uk_grid_div div_list_uk" uk-grid  >
			    <div class="uk-width-auto lista_grid_nome">Check-In </div>
			    <div class="uk-width-expand uk-text-right lista_grid_right" >' . dataita2($dettaglio_prenotazione['checkin']) . '  </div>
			</div>


 			<div class=" uk_grid_div div_list_uk" uk-grid  >
			    <div class="uk-width-auto lista_grid_nome">Check-Out</div>
			    <div class="uk-width-expand uk-text-right lista_grid_right" >  ' . dataita2($dettaglio_prenotazione['checkout']) . '    </div>
			</div>

 			<div class=" uk_grid_div div_list_uk" uk-grid  >
			    <div class="uk-width-2-3 lista_grid_nome">' . traduci('Ospiti', $lang) . '</div>
			    <div class="uk-width-expand uk-text-right lista_grid_right" > ' . implode(', ', $clienti) . '     </div>
			</div>





			<div class="uk-margin"></div>
				<div class="div_uk_divider_list" style="margin-top:0px !important;">' . traduci('Dati Ospite', $lang) . '</div>




			 <div class=" uk_grid_div div_list_uk" uk-grid  >
			    <div class="uk-width-auto lista_grid_nome">' . traduci('Nominativo', $lang) . '</div>
			    <div class="uk-width-expand uk-text-right lista_grid_right" > ' . $ospite_princiapale['nome'] . ' ' . $ospite_princiapale['cognome'] . ' </div>
			</div>


 			<div class=" uk_grid_div div_list_uk" uk-grid  >
			    <div class="uk-width-auto lista_grid_nome">' . traduci('Cellulare', $lang) . '</div>
			    <div class="uk-width-expand uk-text-right lista_grid_right" > ' . $ospite_princiapale['cellulare'] . '    </div>
			</div>

 			<div class=" uk_grid_div div_list_uk" uk-grid  >
			    <div class="uk-width-auto lista_grid_nome">Email</div>
			    <div class="uk-width-expand uk-text-right lista_grid_right" >  ' . $ospite_princiapale['email'] . '   </div>
			</div>


			<div class="uk-margin"></div>

				<div class="div_uk_divider_list" style="margin-top:0px !important;">' . traduci('Dati Struttura', $lang) . '</div>

				 <div class=" uk_grid_div div_list_uk" uk-grid   >
				    <div class="uk-width-auto lista_grid_nome">' . $dati_struttura['nome'] . '   </div>
				    <div class="uk-width-expand uk-text-right lista_grid_right" >' . $dati_struttura['tipologia'] . '   </div>
				</div>


				 <div class=" uk_grid_div div_list_uk" uk-grid   onclick="location.href=' . "'tel:" . $dati_struttura['telefono'] . "'" . '">
				    <div class="uk-width-auto lista_grid_nome">' . traduci('Telefono', $lang) . '</div>
				    <div class="uk-width-expand uk-text-right lista_grid_right" > ' . $dati_struttura['telefono'] . ' </div>
				</div>



	 			<div class=" uk_grid_div div_list_uk" uk-grid  onclick="location.href=' . "'mailto:" . $dati_struttura['email'] . "'" . '" >
				    <div class="uk-width-auto lista_grid_nome">Email</div>
				    <div class="uk-width-expand uk-text-right lista_grid_right" > ' . $dati_struttura['email'] . '   </div>
				</div>

				<div class=" uk_grid_div div_list_uk" uk-grid   onclick="location.href=' . "'https://" . $dati_struttura['sito'] . "'" . '" >
				    <div class="uk-width-auto lista_grid_nome">' . traduci('Sito Web', $lang) . '</div>
				    <div class="uk-width-expand uk-text-right lista_grid_right" > ' . $dati_struttura['sito'] . '   </div>
				</div>

	 			<div class=" uk_grid_div div_list_uk" uk-grid    onclick="location.href=' . "' https://maps.google.com/?q=" . $dati_struttura['indirizzo'] . "'" . '">
				    <div class="uk-width-auto lista_grid_nome">' . traduci('Indirizzo', $lang) . '</div>
				    <div class="uk-width-expand uk-text-right lista_grid_right" >  ' . $dati_struttura['indirizzo'] . '   </div>
				</div>


			<div class="uk-margin"></div>
			<div class="div_uk_divider_list" style="margin-top:0px !important;">' . traduci('Itinerario Viaggio', $lang) . '</div>


	 			<div class=" uk_grid_div div_list_uk" uk-grid   onclick="location.href=' . "' https://maps.google.com/?q=" . $dati_struttura['indirizzo'] . "'" . '">
				    <div class="uk-width-auto lista_grid_nome">' . traduci('Posizione', $lang, 1, 0) . '</div>
				    <div class="uk-width-expand uk-text-right lista_grid_right" > Google maps   </div>
				</div>

				<div class=" uk_grid_div div_list_uk" uk-grid   onclick="location.href=' . "' https://maps.google.com/?q=" . $dati_struttura['indirizzo'] . "'" . '" >
				    <div class="uk-width-auto lista_grid_nome">' . traduci('Latitudine', $lang) . '</div>
				    <div class="uk-width-expand uk-text-right lista_grid_right" > ' . $dati_struttura['latitudine'] . '   </div>
				</div>

	 			<div class=" uk_grid_div div_list_uk" uk-grid  onclick="location.href=' . "' https://maps.google.com/?q=" . $dati_struttura['indirizzo'] . "'" . '">
				    <div class="uk-width-auto lista_grid_nome">' . traduci('Longitudine', $lang) . '</div>
				    <div class="uk-width-expand uk-text-right lista_grid_right" >  ' . $dati_struttura['longitudine'] . '   </div>
				</div>



				<div class="uk-margin"></div>
				<div class="div_uk_divider_list" style="margin-top:0px !important;">' . traduci('Politiche di Cancellazione', $lang) . '</div>

				<div style="padding:5px 0px;margin:0 10px">' . get_cancellazione_prenotazione($IDprenotazione) . '
					' . $stringa_descrizione_cancellazione . '
				</div>

				<div class="uk-margin"></div>
				<div class="div_uk_divider_list" style="margin-top:0px !important;">' . traduci('Politiche di Deposito', $lang) . '</div>
				<div style="padding:5px 0px;margin:0 10px">' . get_deposito_prenotazione($IDprenotazione) . '
				' . $stringa_descrizione_deposito . '
				</div>
</div>
';

$lista_servizi = get_dati_conto_riferimento($IDprenotazione, 0, [], $IDstruttura)['arr_servizi'];
$elenco_servizi = '';
$servizi = [];
if (!empty($lista_servizi)) {

	foreach ($lista_servizi as $IDaddebito => $dati) {
		$IDservizio = $dati['IDserv'];

		$qta = 0;
		if (in_array($dati['tipolim'], [2, 3, 4, 5, 10])) {
			$qta = array_sum(array_column($dati['componenti'], 'qta'));
		}
		$qta_serv = 0;
		if ($dati['tipolim'] == 6) {
			$qta_serv = array_sum(array_column($dati['componenti'], 'qta'));
		}

		$prezzo = array_sum(array_column($dati['componenti'], 'prezzo'));

		if (isset($servizi[$IDservizio])) {
			$servizi[$IDservizio]['numero'] += 1;
			$servizi[$IDservizio]['prezzo'] += $prezzo;
		} else {
			$servizi[$IDservizio]['numero'] = 1;
			$servizi[$IDservizio]['persone'] = $qta;
			$servizi[$IDservizio]['prezzo'] = $prezzo;
			$servizi[$IDservizio]['qta'] = $qta_serv;
		}

	}

	if (!empty($servizi)) {
		foreach ($servizi as $IDservizio => $dati) {

			$txt_aggiuntivi = ($dati['persone'] > 0 ? traduci('Per', $lang) . ' ' . $dati['persone'] . ' ' . ($dati['persone'] > 1 ? traduci('Persone', $lang) : traduci('Persona', $lang)) : '');
			$elenco_servizi .= '
			<div class="uk_grid_div div_list_uk  "  uk-grid  >

				<div class="uk-width-auto lista_grid_numero uk-first-column numero_servizi_conto">' . ($dati['qta'] != 0 ? $dati['qta'] : $dati['numero']) . '</div>

			    <div class="uk-width-1-2 uk-text-truncate lista_grid_nome uk-first-column nome_servizi_conto">' . traducis('', $IDservizio, 1, $lang) . '<br>
    			<span>' . $txt_aggiuntivi . '</span>

    			</div>

			    <div class="uk-width-expand uk-text-right lista_grid_right c000 fs16 prezzo_servizi_conto"> ' . ($dati['prezzo'] != 0 ? format_number($dati['prezzo']) . ' €' : traduci('Incluso', $lang)) . '</div>



			</div> ';
		}
	}
}

$testo .= '

<div  class="tab_info_pren" data-tab="2"  style="display:none">
	<div class="div_uk_divider_list" style="margin-top:0px !important;">' . traduci('Servizi e Trattamenti', $lang) . '</div>

	' . $elenco_servizi . '
</div>



 <div style="padding:5px 0px;margin:10px ">' . traduci('Per ulteriori informazioni contattare la struttura indicando il numero di prenotazione', $lang) . '.  <br>
  &copy; Copyright ' . date('Y') . ' By Scidoo.com </div> <br/><br/>
 </div>';

echo $testo;

?>
<style>
	ul{margin-bottom: 5px;}

</style>

