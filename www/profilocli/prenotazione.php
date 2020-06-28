<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../config/connecti.php';
require_once __DIR__ . '/../../config/funzioni.php';
$IDprenotazione = $_SESSION['IDstrpren'];
$IDstruttura = get_IDstruttura_from_IDpren($IDprenotazione);

$dettaglio_prenotazione = get_prenotazioni(['0' => ['IDprenotazione' => $IDprenotazione]], null, [], $IDstruttura)['dati'][$IDprenotazione];

$checkin = $dettaglio_prenotazione['checkin'];
$checkout = $dettaglio_prenotazione['checkout'];

$stato = $dettaglio_prenotazione['stato'];
$lang = $dettaglio_prenotazione['lingua'];
$IDalloggio_principale = $dettaglio_prenotazione['IDalloggio_principale'];

$dati_struttura = get_dati_struttura($IDstruttura);
$tipo_servizi_possibili = $dati_struttura['tipo_servizi_possibili'] ?? [];

$_SESSION['lang'] = $lang;

$logo_struttura = getfoto($IDstruttura, 12);

$IDsotto_struttura = get_IDsotto_struttura_from_IDpren($IDprenotazione, $IDstruttura);
if ($IDsotto_struttura) {
	$logo_struttura = getfoto($IDsotto_struttura, 16);
}

$dati_struttura = genera_sotto_strutture_lista($IDstruttura)[$IDsotto_struttura];

$nome_struttura = $dati_struttura['nome'];

$timeadesso = time_struttura($IDstruttura);

if ($timeadesso < $checkin) {
	$datagg = date('Y-m-d', $checkin);
} else {
	$datagg = date('Y-m-d');
}

$time0 = strtotime($datagg);

$query = "SELECT GROUP_CONCAT(IDrest SEPARATOR ','),COUNT(IDrest) FROM infopren WHERE IDpren ='$IDprenotazione' AND pers='1'";
$result = mysqli_query($link2, $query);
$row = mysqli_fetch_row($result);
$IDrestrtxt = $row['0'] . ',';
$numero_persone = $row['1'];

$statopren = 2;
$query = "SELECT attivo FROM autoconf WHERE IDstr='$IDstruttura' LIMIT 1"; //se presente il pulsante compare
$result = mysqli_query($link2, $query);
if (mysqli_num_rows($result) > 0) {
	$row = mysqli_fetch_row($result);
	if ($row[0] == 1) {
		//autoconferma c'è
		switch ($stato) {
		case 0:
		case 1:
			$statopren = 1;
			break;
		case 2:
		case 5:
			$statopren = 2; //stato 2  confermata
			break;
		case 6: //stato 3 da confermare
			$statopren = 3;
			$query2 = "SELECT ID FROM confermaplus WHERE IDstr='$IDstruttura' AND IDpren='$IDprenotazione' LIMIT 1";
			$result2 = mysqli_query($link2, $query2);
			if (mysqli_num_rows($result2) > 0) {
				$statopren = 2;
			}

			break;
		default:
			$statopren = 2; //stato 2 confermato
			break;
		}
	} else {
		$statopren = 2;
	}
}

$deposito_segnalato = null;
$query = "SELECT IDdeposito,IDpagamento FROM deposito_prenotazione_segnalazioni WHERE IDprenotazione =$IDprenotazione AND IDstruttura=$IDstruttura LIMIT 1";
$result = mysqli_query($link2, $query);
if (mysqli_num_rows($result) > 0) {
	$row = mysqli_fetch_row($result);
	$deposito_segnalato = $row[0];
}

/*
$query = "SELECT pagamentoregis FROM prenotazionidati WHERE IDv='$IDprenotazione' LIMIT 1";
$result = mysqli_query($link2, $query);
if (mysqli_num_rows($result) > 0) {
$row = mysqli_fetch_row($result);
$pagamentoregis = ($row['0'] ? $row['0'] : 0);
}

 */
$butt_stato_pren = ($statopren == 2 ? 0 : 1);

$riga_prenotazione = '<br><span class="sotto_titolo" style="color:#22bc51">' . traduci('Confermata', $lang) . ' </span>';
if ($butt_stato_pren == 1) {
	if (($statopren == 1) && ($deposito_segnalato)) {
		$riga_prenotazione = '<br><span class="sotto_titolo" style="color:#4a92fe">' . traduci('In attesa di conferma ', $lang) . ' </span> ';
	} else {
		$riga_prenotazione = '<br> <span class="sotto_titolo" style="color:#CB0003">' . traduci('Da confermare ', $lang) . ' </span>';
	}

}

$testo_deposito = '';

if (isset($dettaglio_prenotazione['depositi'])) {
	if ($dettaglio_prenotazione['depositi'][0]['IDscontr'] == 0) {
		$testo_deposito = '<br> <span class="sotto_titolo" style="color:#CB0003">' . traduci('Deposito da Pagare ', $lang) . ' </span>';
	}
}

$foto_cat = getfoto($IDalloggio_principale, 2);

$testo = '
<div style="height:235px;background-image:url(' . base_url() . '/immagini/big' . $foto_cat . ');background-size:cover;background-repeat:no-repeat; width:100%;border-bottom-left-radius: 30px;position:relative;margin-bottom:70px">
	<img src="' . base_url() . '/immagini/big' . $logo_struttura . '" style=" max-width: 100px;  max-height: 100px;margin:10px">

	<div style="    width: 95%;  background: #fff; border-bottom-left-radius: 25px;  border-top-left-radius: 25px;   box-shadow: 0 0 5px 1px #a5a5a5;  position: absolute;
	    bottom: 0; right: 0;   padding: 10px;  transform: translateY(50%);">

	    <div style="    display: inline-flex;   place-content: space-between;  width: 100%;">
			<div>
				<div style="font-size: 16px;  color: #007aff;  font-weight: 600;   text-transform: uppercase;">' . $nome_struttura . '</div>

				<div style="font-size:12px">' . traduci('Prenotazione N.', $lang) . ' ' . $dettaglio_prenotazione['numero'] . ' ' . traduci('Di', $lang) . ' ' . $dettaglio_prenotazione['nome_cliente'] . ' </div>
				<div style="display:inline-flex">

				<div >
					<strong style="font-size:13px;">Check-in</strong>
					<span style="font-size:13px;font-weight:600;color:#000">' . dataita5($checkin) . ' </span>
				</div>


				<div style="  margin-left:5px">
					<strong style="font-size:13px;">Check-out</strong>
					<span style="font-size:13px;font-weight:600;color:#000">' . dataita5($checkout) . ' </span>
				</div>

				<div style="  margin-left:5px">
					<strong style="font-size:13px;">' . traduci('Ospiti', $lang) . '</strong>
					<span style="font-size:13px;font-weight:600;color:#000"> ' . $dettaglio_prenotazione['persone'] . '</span>
				</div>

				</div>
			</div>

			<div>

			</div>
		</div>
	</div>
</div>';

$funzione_checkin = '
<div class="pulsanti_funzione">
		<div class="container_funz"  onclick="navigation_ospite(10,0)">
			<div class="div_icona" style="color: #3652AF;background:#3652af1a"  >
				<div style=""><i class="fas fa-users" ></i></div>
			</div>
			<div class="testo"> ' . ($dettaglio_prenotazione['notti'] != 0 ? 'Check-in Online <br><span class="sotto_titolo" style="color:#22bc51">' . traduci('Effettuato', $lang) . '</span>' : traduci('Condividi App', $lang)) . '
			</div>
		</div>
	</div>';

if (($timeadesso < $checkin) && ($dettaglio_prenotazione['notti'] != 0)) {
	if ($stato != 3) {
		//$arr = [];

		if ($dettaglio_prenotazione['checkin_online'] == 0) {

			//if (controllocheckin_online($IDprenotazione, $IDstruttura, 0, $arr, 1, null, 0) == 0) {
			$funzione_checkin = '
			<div class="pulsanti_funzione">
					<div class="container_funz" onclick="navigation_ospite(10,0,0,0)">
						<div class="div_icona" style="color: #3652AF;background:#3652af1a" >
							<div style=""><i class="fas fa-users" ></i></div>
						</div>
						<div class="testo">Check-in Online
							<br><span class="sotto_titolo" style="color:#CB0003">' . traduci('Da Effettuare', $lang) . '</span>
						 </div>
					</div>
				</div>';
		}
	}

} else {
	$funzione_checkin = '';
}

$messaggi = estrai_messaggi([['IDprenotazione' => $IDprenotazione]], '', $IDstruttura);

$lista_servizi = get_dati_conto_riferimento($IDprenotazione, 0, [], $IDstruttura)['arr_servizi'];
$servizi_da_impostare = array_filter($lista_servizi, function ($arg) {
	if (!in_array($arg['tipolim'], [4, 5, 6, 7, 8, 10])) {
		return $arg['modi'] == 0;
	}
});

$_SESSION['ordinazione_webapp'][$IDprenotazione] = [];
$messaggi_da_leggere = array_sum(array_column($messaggi, 'numero_messaggi_da_leggere_ospite'));

$testo .= '<div  style="margin-top:25px;padding:5px 10px;">
					' . $funzione_checkin . '
				 	 <div class="pulsanti_funzione">
						<div class="container_funz" onclick="apri_chat_ospite()">
							<div class="div_icona" style="color: #3652AF;background:#3652af1a" >
								<div style=""><i class="fas fa-envelope" ></i></div>
							</div>
							<div class="testo">Chat
							<br>
							<span class="sotto_titolo">' . (!empty($messaggi) ? count($messaggi) . ' ' . (count($messaggi) > 1 ? traduci('Messaggi', $lang) : traduci('Messaggio', $lang)) : '') . '
							' . ($messaggi_da_leggere > 0 ? '<br><span style="color:#CB0003">' . $messaggi_da_leggere . ' ' . traduci('Da leggere', $lang) . '</span>' : '') . '</span>
							</div>
						</div>
					</div>


					 <div class="pulsanti_funzione">
						<div class="container_funz" onclick="navigation_ospite(1,0,()=>{switch_tab_prenotazione_ospite(0)})">
							<div class="div_icona" style="color: #3652AF;background:#3652af1a" >
								<div style=""><i class="fas fa-info" ></i></div>
							</div>
							<div class="testo">' . traduci('Prenotazione', $lang) . ' ' . $riga_prenotazione . $testo_deposito . '</div>
						</div>
					</div>';

if (in_array(1, $tipo_servizi_possibili)) {

	$testo .= ($_COOKIE['debug'] ?
		'<div class="pulsanti_funzione">
								<div class="container_funz" onclick="navigation_ospite(23,0)">
									<div class="div_icona" style="color:#f44336;background:#f443361a" >
										<div style=""><i class="fas fa-utensils" ></i></div>
									</div>
									<div class="testo">' . traduci('Ordinazione ', $lang) . '  </div>
								</div>
							</div>
		' : '') . '


							 <div class="pulsanti_funzione">
								<div class="container_funz" onclick="navigation_ospite(22,0)">
									<div class="div_icona" style="color: #af3695;background:#af36951a" >
										<div style=""><i class="fas fa-bars" ></i></div>
									</div>
									<div class="testo">' . traduci('Menù e servizi Ristorante', $lang) . '  </div>
								</div>
							</div>';

}

$servizi = get_info_from_IDserv(null, null, $IDstruttura);
$servizi_presenti_prenotabili = null;
if (!empty($servizi)) {
	foreach ($servizi as $dati) {
		if ($dati['web_app'] != 1) {continue;}
		$servizi_presenti_prenotabili = 1;
		break;
	}
}

$testo .= ($servizi_presenti_prenotabili ? '
	<div class="pulsanti_funzione">
		<div class="container_funz" onclick="navigation_ospite(7,0)">
			<div class="div_icona" style="color: #30d2ec;background:#30d2ec1a" >
				<div style=""><i class="fas fa-cart-plus" ></i></div>
			</div>
			<div class="testo">' . traduci('Prenota un Servizio', $lang) . ' 	<br><span class="sotto_titolo" >' . traduci('Personalizza Il tuo Soggiorno', $lang) . '</span></div>
		</div>
	</div> ' : '') . '




			<div class="pulsanti_funzione">
				<div class="container_funz"  onclick="navigation_ospite(12,0,0,0)">
					<div class="div_icona" style="color: #007aff;background:#007aff1a">
						<div style=""><i class="fas fa-spa" ></i></div>
					</div>
					<div class="testo">' . traduci('I tuoi Servizi', $lang) . ' <br><span  class="sotto_titolo" style="color:#CB0003">' . (count($servizi_da_impostare) > 0 ? count($servizi_da_impostare) . ' ' . (count($servizi_da_impostare) > 1 ? traduci('Servizio', $lang) : traduci('Servizi', $lang)) . ' ' . traduci('Da impostare', $lang) : '') . '</span></div>
				</div>
			</div> ';

$query = "SELECT temp,domotraff,domotrisc FROM appartamenti WHERE ID=$IDalloggio_principale LIMIT 1";
$result = mysqli_query($link2, $query);
if (mysqli_num_rows($result) > 0) {
	$row = mysqli_fetch_row($result);
	if ((($row['1'] != 0) || ($row['2'] != 0)) && ($row['0'] != 0)) {
		$query = "SELECT tempn,tempg FROM prenotazioni WHERE IDv=$IDprenotazione AND IDstruttura=$IDstruttura";
		$result = mysqli_query($link2, $query);
		$row = mysqli_fetch_row($result);
		$temperatura_notte = $row['0'];
		$temperatura_giorno = $row['1'];

		$testo .= '
			<div class="pulsanti_funzione">
					<div class="container_funz"  onclick="navigation_ospite(17,0)">
						<div class="div_icona" style="color: #a42727;background:#a427271a">
							<div style=""><i class="fas fa-thermometer-half" ></i></div>
						</div>
						<div class="testo">' . traduci('Temperatura Alloggio', $lang) . '
						<div class="sotto_titolo" style="color:#d94b1a;    display: flex;  width: 100%;    place-content: space-evenly;">
								<span> <i class="fas fa-sun"></i> ' . $temperatura_giorno . ' °</span>
								<span style="color:#2574ec"><i class="fas fa-moon"></i> ' . $temperatura_notte . ' ° </span></div>
						</div>
					</div>
			</div>  ';

	}
}

$informazioni_struttura = estrai_informazioni_struttura([], $IDstruttura);

$recensioni = get_lista_recensioni([], $IDstruttura);
$testo .= '
			<div class="pulsanti_funzione">
					<div class="container_funz" onclick="navigation_ospite(13,0)">
						<div class="div_icona" style="color: #007aff;background:#007aff1a" >
							<div style=""><i class="fas fa-phone" ></i></div>
						</div>
						<div class="testo">' . traduci('Numeri Utili', $lang) . '</div>
					</div>
			</div>


			' . (!empty($recensioni) ? '
			<div class="pulsanti_funzione">
					<div class="container_funz"  onclick="navigation_ospite(4,0)">
						<div class="div_icona" style="color: #f49112;background:#f491121a">
							<div style=""><i class="fas fa-star" ></i></div>
						</div>
						<div class="testo">' . traduci('Recensioni', $lang) . ' <br>
								<span class="sotto_titolo" >' . (!empty($recensioni) ? count($recensioni) . ' ' . (count($recensioni) > 1 ? traduci('Recensioni', $lang) : traduci('Recensione', $lang)) : '') . '</span>
						</div>
					</div>
			</div>
			' : '') . '


			' . (!empty($informazioni_struttura) ? '
				<div class="pulsanti_funzione">
					<div class="container_funz"  onclick="apri_informazioni_struttura()">
						<div class="div_icona" style="color: #d80073;background:#d800731a">
							<div style=""><i class="fas fa-info" ></i></div>
						</div>
						<div class="testo">' . traduci('Info sulla Struttura', $lang) . '
						<div class="sotto_titolo">' . (!empty($informazioni_struttura) ? count($informazioni_struttura) . ' ' . (count($informazioni_struttura) > 1 ? traduci('Informazioni', $lang) : traduci('Informazione', $lang)) : '') . ' 	</div>
						</div>
					</div>
				</div>
				' : '') . '



</div> ';

$informazioni_tipo_servizio = get_informazioni_tiposervizio();
$servizi_web_app = [];
$query = "SELECT IDserv FROM web_app_servizi WHERE IDstruttura=$IDstruttura";
$result = mysqli_query($link2, $query);
while ($row = mysqli_fetch_row($result)) {
	$servizi_web_app[] = $row[0];
}

$servizi_consigliati = '';
if (!empty($servizi_web_app)) {
	foreach ($servizi_web_app as $IDservizio) {

		$dettaglio_prezzo = visualizza_prezzo_servizio($IDservizio, $checkin, $IDstruttura, $IDprenotazione, 0);
		$foto = getfoto($IDservizio, 4);
		$IDtipo = $servizi[$IDservizio]['IDtipo'];

		if ($foto == 'camera.jpg') {
			$foto = ($informazioni_tipo_servizio[$IDtipo]['immagine'] != '' ? base_url() . '/img_template/' . $informazioni_tipo_servizio[$IDtipo]['immagine'] : '');
		} else {
			$foto = base_url() . '/immagini/big' . $foto;
		}

		$descrizione = strip_tags(traducis('', $IDservizio, 2, $lang));
		$servizi_consigliati .= '
		<li class="uk-width-3-4" onclick="navigation_ospite(21,{IDservizio:' . $IDservizio . '})">
                <div class="uk-card uk-card-default">
                    <div class="uk-card-media-top" style="height:250px;background-image:url(' . $foto . ');background-size:cover;background-position:center;background-repeat:no-repeat">
                    </div>
                    <div class="uk-card-body" style="padding: 20px 15px;">
                        <div class=" c000" style="font-size:15px;">' . traducis('', $IDservizio, 1, $lang) . '</div>
                        <p style="font-size:13px;font-weight:600;margin-top:10px" class=" c000">' . ($dettaglio_prezzo['prezzo'] ? format_number($dettaglio_prezzo['prezzo']) . ' €  ' . $dettaglio_prezzo['tipo_calcolo'] : '') . '</p>
                    </div>
                </div>
         </li> ';
	}

	$testo .= '
	<div class="titolo_paragrafo">
	    	<div>' . traduci('Servizi Consigliati', $lang, 1, 0) . '
	    		<div style="color:#70757a;font-size:12px;font-weight:400;">' . traduci('Scopri i servizi proposti dalla struttura', $lang) . '</div>
	    	</div>
	    	<div style="color: #1a73e8;"  onclick="navigation_ospite(7,0)" > ' . traduci('Mostra Tutti', $lang, 1, 0) . '</div>
	</div>
	<div  uk-slider="center: true;autoplay:false;finite:true;draggable:true" >
	   <div class="uk-position-relative">

			<div class="uk-position-relative   uk-slider-container" tabindex="0" style="padding:10px 0;margin-bottom:20px">
				    <ul class="uk-slider-items uk-grid remove_first_padding">
					    ' . $servizi_consigliati . '
					</ul>
			</div>


			<ul class="uk-slider-nav uk-dotnav uk-flex-center"></ul>
		</div>
	</div>';

}

$luoghi = estrai_luoghi([['preferiti' => 1]], $IDstruttura);
if (!empty($luoghi)) {

	$luoghi_consigliati = '';
	foreach ($luoghi as $dati) {
		$riga_distanza = '';
		if (($dati['latitudine'] != '') && ($dati['longitudine'] != '')) {

			$distance_data = file_get_contents('https://maps.googleapis.com/maps/api/distancematrix/json?&language=IT&origins=' . $dati_struttura['latitudine'] . ',' . $dati_struttura['longitudine'] . '&destinations=' . $dati['latitudine'] . ',' . $dati['longitudine'] . '&key=AIzaSyAN6U_MYAHUznULQav0s897pKUx_Tt9EUo');
			$distance_arr = json_decode($distance_data);

			if ($distance_arr->status == 'OK') {
				$elements = $distance_arr->rows[0]->elements;
				if ($elements[0]->status != 'NOT_FOUND') {

					$distance = $elements[0]->distance->text;
					$duration = $elements[0]->duration->text;

					$riga_distanza = '<i class="fas fa-car" style="margin-right:5px;"></i> ' . $distance . ' - ' . $duration;
				}
			}
		}

		$foto = getfoto($dati['ID'], 17);
		$luoghi_consigliati .= '
			<li class="uk-width-3-4 " onclick="navigation_ospite(18,{IDluogo:' . $dati['ID'] . '})" >
                <div class="uk-card uk-card-default">
                    <div class="uk-card-media-top" style="height:250px;background-image:url(' . base_url() . '/immagini/big' . $foto . ');background-size:cover;background-position:center;background-repeat:no-repeat">
                    </div>
                    <div class="uk-card-body" style="padding: 20px 15px;position:relative">
                        <div style="    position: absolute;  right: 10px;  font-size: 13px; top: 5px;  color: #333;">' . $riga_distanza . '</div>
                        <div class=" c000" style="font-size: 20px; font-weight: 600;line-height:1">' . $dati['nome'] . '<div style="color:#80868b;font-size:11px;">' . $dati['categoria'] . '</div>

                        </div>
                        <p style="font-size:12px;" class="uk-text-truncate">' . (isset($dati['informazioni']['Descrizione']) ? strip_tags($dati['informazioni']['Descrizione']['valore']) : '') . '</p>
                    </div>
                </div>
        	 </li>  ';

	}

	$testo .= '
	<div class="titolo_paragrafo">
    	<div>' . traduci('Luoghi Consigliati', $lang, 1, 0) . '
    		<div style="color:#70757a;font-size:12px;font-weight:400;">' . traduci('Scopri i luoghi che ti circondano', $lang) . '</div>
    	</div>
    	<div style="color: #1a73e8;"  onclick="navigation_ospite(16,0)" > ' . traduci('Mostra Tutti', $lang, 1, 0) . '</div>
    </div>
	<div class="uk-position-relative uk-visible-toggle uk-light" tabindex="-1" uk-slider="center: true;;autoplay:false;finite:true" style="padding:10px 0;margin-bottom:20px">
		    <ul class="uk-slider-items uk-grid remove_first_padding">
			    ' . $luoghi_consigliati . '
			</ul>
	</div>';
}

$itinerari = estrai_itinerari_giornalieri([], $IDstruttura);
if (!empty($itinerari)) {
	$itinerari_txt = '';
	foreach ($itinerari as $dati) {
		$divfoto = '';
		$stringamarker = '';
		$stringapath = '';
		if (!empty($dati['luoghi'])) {
			$numero_luoghi = 0;

			foreach ($dati['luoghi'] as $luogo) {
				if ($numero_luoghi < 2) {
					$foto = getfoto($luogo['ID'], 17);
					$style = 'height:125px;';
					if ($numero_luoghi == 0) {
						$style = 'height:123px;margin-bottom:2px; ';
					}
					$divfoto .= '<div style="background:url(' . base_url() . '/immagini/big' . $foto . ') center center / cover no-repeat;background-size:cover;' . $style . '"></div>';
				}

				if (end($dati['luoghi']) == $luogo) {
					$stringamarker .= 'markers=color:red|label:A|' . $luogo['latitudine'] . ',' . $luogo['longitudine'];
					$stringapath .= $luogo['latitudine'] . ',' . $luogo['longitudine'];
				} else {
					$stringamarker .= 'markers=color:red|' . $luogo['latitudine'] . ',' . $luogo['longitudine'] . '&';
					$stringapath .= $luogo['latitudine'] . ',' . $luogo['longitudine'] . '|';
				}

				$numero_luoghi++;
			}
		}

		$link = 'https://maps.googleapis.com/maps/api/staticmap?markers=color:red|label:P|' . $dati_struttura['latitudine'] . ',' . $dati_struttura['longitudine'] . '&zoom=10&size=600x300&maptype=roadmap&' . $stringamarker . '&path=color:0x4285F4|weight:3|' . $dati_struttura['latitudine'] . ',' . $dati_struttura['longitudine'] . '|' . $stringapath . '&key=AIzaSyAN6U_MYAHUznULQav0s897pKUx_Tt9EUo';

		$mappa = '
		<div style="height:250px;background-size:cover;background-position:center center;background-repeat:no-repeat;background-image:url(\'' . $link . '\')" ></div> ';

		$itinerari_txt .= '
			<li class="uk-width-3-4 " onclick="navigation_ospite(19,{IDitinerario:' . $dati['ID'] . '})">
                <div class="uk-card uk-card-default">

                    <div class="uk-card-media-top" style="height:250px; display: flex;">
                    	<div style="width:40%;margin-right:1px;">' . $divfoto . '</div>
						<div style="width:60%">' . $mappa . '</div>

                    </div>
                    <div class="uk-card-body" style="padding: 20px 15px;position:relative">
                    	 <div style="    position: absolute;  right: 10px;  font-size: 13px; top: 5px;  color: #333;">    ' . (!empty($dati['luoghi']) ? '<i class="fas fa-map-marker-alt" style="margin-right:5px"></i>' . count($dati['luoghi']) . ' ' . (count($dati['luoghi']) > 1 ? traduci('Luoghi ', $lang) : traduci('Luogo', $lang)) : '') . '</div>
                        <div class=" c000" style="font-size: 20px; font-weight: 600;" >' . $dati['nome'] . ' </div>
                        <p style="font-size:12px;" class="uk-text-truncate">' . strip_tags($dati['descrizione']) . '</p>
                    </div>
                </div>
         </li>  ';

	}

	$testo .= '
		<div class="titolo_paragrafo">' . traduci('Itinerari giornalieri', $lang, 1, 0) . '
	    	<div style="color: #1a73e8" onclick="navigation_ospite(20,0)"> ' . traduci('Mostra Tutti', $lang, 1, 0) . '</div>
	    </div>
	    <div class="uk-position-relative uk-visible-toggle uk-light" tabindex="-1" uk-slider="center: true;autoplay:false;finite:true;" style="padding:10px 0;margin-bottom:20px">
		    <ul class="uk-slider-items uk-grid remove_first_padding">
			    ' . $itinerari_txt . '
			</ul>
		</div>';

}
/*
if (next($luoghi)) {
$stringamarker .= 'markers=color:red|' . $latitudine . ',' . $longitudine . '&';
$stringapath .= $latitudine . ',' . $longitudine . '|';
} else {
$stringamarker .= 'markers=color:red|label:A|' . $latitudine . ',' . $longitudine;
$stringapath .= $latitudine . ',' . $longitudine;
}
$i++;

}

$mappa = '
<div style="height:120px;background-size:cover;background-position:center center;background-repeat:no-repeat;  border-top-right-radius: 10px;background-image:url(' . "'" . 'https://maps.googleapis.com/maps/api/staticmap?markers=color:red|label:P|' . $lat . ',' . $long . '&zoom=10&size=600x300&maptype=roadmap&' . $stringamarker . '&path=color:0x4285F4|weight:3|' . $lat . ',' . $long . '|' . $stringapath . ' &key=AIzaSyAN6U_MYAHUznULQav0s897pKUx_Tt9EUo' . "'" . ')" ></div> ';
$n_luoghi = count($luoghi) ?? 0;
 */

$album = estrai_album($IDstruttura);
if (!empty($album)) {
	$count_foto = 0;
	$lista_foto = '';
	foreach ($album as $dati) {
		if (!empty($dati['foto'])) {
			foreach ($dati['foto'] as $immagine) {
				if ($immagine['elemento'] != 'immagine') {continue;}

				$lista_foto .= '
				<li  class="uk-width-4-5 ">
					  <a class="uk-inline" href="' . base_url() . '/immagini/big' . $immagine['foto'] . '" data-caption="' . $dati['nome'] . '">
						 <div class="uk-panel">
						 	<div  class="foto_galleria_slider" style="background-image:url(' . base_url() . '/immagini/big' . $immagine['foto'] . ')"></div>

						 </div>
					 </a>
				</li> ';

				$count_foto++;
			}
		}
	}

	$testo .= '
	<div class="titolo_paragrafo" style="margin-top:45px; ">
		<div>' . traduci('Galleria', $lang) . '
			<div style="color:#70757a;font-size:12px;font-weight:400;">' . $count_foto . ' ' . traduci('Foto', $lang) . '</div>
		</div>
		<div style="color: #1a73e8" onclick="navigation_ospite(15,0)">' . traduci('Vedi Album', $lang) . '</div>
	</div>
	 <div class="uk-position-relative   "   uk-slider="center: true;autoplay:false;finite:true;" style="padding:10px 0;margin-bottom:20px">
	    <ul class="uk-slider-items uk-grid-match  uk-grid remove_first_padding" uk-lightbox="animation: slide" data-uk-lightbox="toggle: li " >
			' . $lista_foto . '
		</ul>
	</div> ';

}

$testo .= '

<div class="pulsanti_funzione">
		<div class="container_funz"  onclick="navigation_ospite(13,0,()=>{switch_tab_prenotazione_ospite(1)})">
			<div class="div_icona" style="color: #007aff;background:#007aff1a"  >
				<div style=""><i class="fas fa-phone" ></i></div>
			</div>
			<div class="testo">' . traduci('Contatti', $lang) . ' </div>
		</div>
	</div>

<div class="pulsanti_funzione">
		<div class="container_funz"  onclick="navigation_ospite(13,0,()=>{switch_tab_prenotazione_ospite(2)})">
			<div class="div_icona" style="color:8df00a;background:#8df00a1a"  >
				<div style=""><i class="fas fa-map-marker-alt" ></i></div>
			</div>
			<div class="testo">' . traduci('Come Arrivare', $lang) . ' </div>
		</div>
	</div>


<div onclick="esci()">Logout</div>';

echo $testo;

?>

<style>

.pulsanti_funzione {margin: 10px 0; display: inline-flex; width: 49%;  place-content: space-evenly;}
.pulsanti_funzione .div_icona{ text-align: center; background: #f3f3f3;   border-radius: 50%;  font-size: 25px;margin: 10px auto;  width: 55px;  height:55px;position:relative}
.pulsanti_funzione .div_icona i{ position: absolute;  top: 50%;    left: 50%;  transform: translate(-50%, -50%);}
.pulsanti_funzione .testo{color: #333; font-weight: 600; font-size: 16px;text-align:center;;margin-top:5px;}
.pulsanti_funzione .container_funz{width: 170px;
    height: 145px;
    border-radius: 10px;
    place-content: center;
    box-shadow: 0 0 10px 1px #efefef;
    padding: 5px;
    background: #fff;}

  .pulsanti_funzione  .sotto_titolo{font-size: 13px;}

@media (min-width: 992px){
	.pulsanti_funzione {width: 24%; }
}

</style>
