<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../config/connecti.php';
require_once __DIR__ . '/../../config/funzioni.php';

$IDprenotazione = $_SESSION['IDstrpren'];

$post = array_escape($_POST);
$request = $post['request'] ?? null;
$ID = $post['id'] ?? null;
$value = $post['value'] ?? null;
$IDstruttura = get_IDstruttura_from_IDpren($IDprenotazione);

switch ($request) {
case 1:
	set_schedina_nome($ID, $value, $IDstruttura);
	break;
case 2:
	set_schedina_cognome($ID, $value, $IDstruttura);
	break;
case 3:
	set_schedina_email($ID, $value, $IDstruttura);
	break;
case 4:
	set_schedina_cellulare($ID, $value, $IDstruttura);
	break;
case 5:
	set_schedina_telefono($ID, $value, $IDstruttura);
	break;
case 6:
	set_schedina_cittadinanza($ID, $value, $IDstruttura);
	break;
case 7:
	set_schedina_luogo_nascita($ID, $value, $IDstruttura);
	break;
case 8:
	set_schedina_residenza($ID, $value, $IDstruttura);
	break;
case 9:
	set_schedina_indirizzo($ID, $value, $IDstruttura);
	break;
case 10:
	set_schedina_documento($ID, $value, $IDstruttura);
	break;
case 11:
	set_schedina_numero_documento($ID, $value, $IDstruttura);
	break;
case 12:
	set_schedina_luogo_rilascio($ID, $value, $IDstruttura);
	break;
case 13:
	set_schedina_data_nascita($ID, $value, $IDstruttura);
	break;
case 14:
	set_schedina_data_rilascio($ID, $value, $IDstruttura);
	break;
case 15:
	set_schedina_prefisso_cellulare($ID, $value, $IDstruttura);
	break;
case 16:
	set_schedina_prefisso_telefono($ID, $value, $IDstruttura);
	break;
case 17:
	set_schedina_sesso($ID, $value, $IDstruttura);
	break;
case 18:

	$IDpagamento = $ID[0];
	$IDdeposito = $ID[1];
	$pagamento = get_dati_pagamenti($IDstruttura, [['IDpagamento' => $IDpagamento]])[$IDpagamento];
	$tipo_pagamento = $pagamento['tipo_pagamento'];
	$nome_pagamento = $pagamento['nome_pagamento'];

	switch ($tipo_pagamento) {

	case 1:
		$inscarte = 0;
		if (!empty($value)) {
			$numero = mysqli_real_escape_string($link2, $value[0]);
			$anno = mysqli_real_escape_string($link2, $value[1]);
			$mese = mysqli_real_escape_string($link2, $value[2]);
			$intestatario = mysqli_real_escape_string($link2, $value[3]);

			$IDcartadicredito = inserisci_carta($intestatario, $mese, $anno, $numero, $IDstruttura, $IDprenotazione, 0);
			$inscarte = 1;
		}

		break;

	default:
		break;

	}

	$dati_struttura = genera_sotto_strutture_lista($IDstruttura)[0];

	$nome_struttura = $dati_struttura['nome'];
	$mail_struttura = $dati_struttura['email'];

	$time = time();
	$dettaglio_prenotazione = get_prenotazioni(['0' => ['IDprenotazione' => $IDprenotazione]], null, [], $IDstruttura)['dati'][$IDprenotazione];

	$prezzo_deposito = 0;
	foreach ($dettaglio_prenotazione['depositi'] as $dati) {
		if ($dati['ID'] != $IDdeposito) {continue;}

		$prezzo_deposito = $dati['prezzo'];
	}

	$cliente = reset(estrai_dati_ospiti([['IDprenotazione' => $IDprenotazione]], [], $IDstruttura)['dati']);

	$testo_mail = 'Salve,<br/>
		con la presente email si  conferma  la segnalazione del  pagamento di  ' . format_number($prezzo_deposito) . '   mediante ' . $nome_pagamento . '
		<br/><br>
		<br/><br/>

		<strong>Dati Prenotazione:</strong><br/><br/>
			ID: ' . $dettaglio_prenotazione['numero'] . '<br/>
			Nome: ' . $dettaglio_prenotazione['nome_cliente'] . '<br/>
			Check-in: ' . dataita($dettaglio_prenotazione['checkin']) . '<br/>
			'
		. ($dettaglio_prenotazione['notti'] > 0 ? '

			Check-out: ' . dataita($dettaglio_prenotazione['checkout']) . '<br>
			Notti: ' . $dettaglio_prenotazione['notti'] . ' <br/>' : '');

	$testo_mail .= '
	    Ospite: <strong>' . $cliente['nome'] . ' ' . $cliente['cognome'] . '</strong>
		Cellulare: <strong>' . $cliente['cellulare'] . '</strong><br/>
		Telefono: <strong>' . $cliente['telefono'] . '</strong><br/>
		Indirizzo: <strong>' . $cliente['indirizzo'] . '</strong><br/>
		E-mail: <strong>' . $cliente['email'] . '</strong> <br/> <br/> ';

	$oggetto = $nome_struttura . ' - Prenotazione N.' . $dettaglio_prenotazione['numero'] . ' di ' . $dettaglio_prenotazione['nome_cliente'] . ' -  Richiesta conferma di avvenuto pagamento tramite ' . $nome_pagamento;

	$query = "INSERT INTO deposito_prenotazione_segnalazioni(IDstruttura,IDdeposito,IDprenotazione,IDpagamento,time) VALUES ('$IDstruttura','$IDdeposito','$IDprenotazione','$IDpagamento','$time')";
	$result = mysqli_query($link2, $query);
	$IDsegnalazione = mysqli_insert_id($link2);
	echo $IDsegnalazione;

	$response = inviamail($mail_struttura, $testo_mail, 0, $oggetto, $cliente['email'], [], $dettaglio_prenotazione['nome_cliente'], $cliente['email']);

	break;
case 19:

	$timeora = time_struttura();
	$lingua = $_SESSION['lang'] ?: 0;

	$query = "SELECT CONCAT_WS(' ',s.nome,s.cognome) FROM infopren as i
	LEFT JOIN schedine as s  ON s.ID=i.IDcliente
	WHERE i.IDpren='$IDprenotazione' AND i.IDstr='$IDstruttura' AND i.pers='1' LIMIT 1";
	$result = mysqli_query($link2, $query);
	$row = mysqli_fetch_row($result);
	$nomeospite = $row['0'];

	$oggetto = 'Nuovo Messaggio da ' . $nomeospite;

	$arr_impostazioni = ['IDobj' => $IDprenotazione, 'tipoobj' => 0, 'oggetto' => $oggetto, 'IDstruttura' => $IDstruttura, 'lingua' => $lingua, 'messaggio' => $value, 'ricevuto' => 1];

	crea_messaggi($arr_impostazioni);
	break;
case 20:

	set_ora_checkin_prenotazione($IDprenotazione, $value, 1, $IDstruttura);

	break;
case 21:

	$IDsala = $value[0];
	$time = $value[1];
	$personale = $value[2];

	set_orario_addebito($ID, $IDsala, $time, $IDpersonale, $IDstruttura, 0);

	break;
case 22:
	set_prenotazione_temperatura_giorno($IDprenotazione, $value, $IDstruttura);

	break;
case 23:
	set_prenotazione_temperatura_notte($IDprenotazione, $value, $IDstruttura);
	break;

case 24:

	if (isset($value)) {

		$titolo = $value['titolo'];
		$descrizione = $value['descrizione'];
		$parametri = [];

		if (!empty($value['parametri'])) {
			foreach ($value['parametri'] as $IDparametro => $valore) {
				if ($valore == '') {continue;}
				$parametri[$IDparametro] = $valore;
			}
		}

		inserisci_nuova_recensione(['titolo' => $titolo, 'descrizione' => $descrizione, 'parametri' => $parametri], ['IDoggetto' => $IDprenotazione, 'tipo_oggetto' => 0, 'IDcliente' => $_SESSION['IDcliente']], $IDstruttura);

	}

	break;
case 25:

	$time = $value['giorno'];

	if (isset($value['time_specifico'])) {
		$time = $value['time_specifico'];
	}

	$persone = [];
	if (!empty($value['persone'])) {
		foreach ($value['persone'] as $IDrestrizione => $numero) {
			for ($i = 0; $i < $numero; $i++) {
				$persone[] = $IDrestrizione;
			}
		}
	}

	if (isset($value['quantita'])) {
		$persone = $value['quantita'];
	}

	$lista_time[$time] = ['soggetti' => $persone];
	$parametro = ['IDservizio' => $ID, 'tipo' => 1, 'postazione' => '', 'list' => $lista_time];

	inserisci_servizi_riferimento($parametro, $IDprenotazione, 0, $IDstruttura);
	break;
case 26:
	$lingua = $_SESSION['lang'] ?: 0;
	$lista_ospiti = estrai_dati_ospiti([['IDprenotazione' => $IDprenotazione]], [], $IDstruttura)['dati'];

	$dati_struttura = get_dati_struttura($IDstruttura);

	$oggetto = 'Web App  ' . $dati_struttura['nome_struttura'];

	if (!empty($lista_ospiti)) {
		foreach ($lista_ospiti as $dati) {

			if (isset($_SESSION['IDcliente'])) {
				if ($_SESSION['IDcliente'] == $dati['ID']) {continue;}
			}

			$email = $dati['email'];

			$button_link = '
		<a href=" " style="   width: auto;  border-radius: 5px;    padding: 10px;  font-weight: 600;
	    text-transform: uppercase;     font-size: 15px;  background-color: #f5f5f5;   color: #539800;    border: solid 1px #488203;   text-decoration: none;" >Scarica App</a>';

			$content = '
				<html><body>
				Gentile cliente,<br/>
				per recuperare la password relativa allo username ' . $email . ' è sufficiente cliccare sul seguente pulsante ed impostare la nuova password.
				<br/><br/><br/><br/>' . $button_link;

			$content .= '<br/><br/><hr/>' . traduci('Per ulteriori informazioni contattare la struttura. Questa è una email automatica. Non Rispondere a questo messaggio.', $lingua) . '
				<br/>
				<hr/><br/><br/>
				<strong>' . $dati_struttura['nome_struttura'] . '</strong><br/><br>
				<i>' . $dati_struttura['indirizzo'] . '</i><br/>
				<i>Tel. ' . $dati_struttura['telefono'] . ' - ' . $dati_struttura['email'] . '</i><br/>
				<i>Tel. ' . $dati_struttura['sitoweb'] . '</i><br/>
				<br/>
				<a href="https://www.scidoo.com/preventivov2/privacy_policy.php?cod=' . $IDstruttura . '">Privacy Policy</a><br/><br/>
				Powered by SCIDOO - HOTEL BOOKING MANAGER<br/><br/> ';

			$content .= ' &copy; Copyright ' . date('Y') . ' <br><br/>
		' . traduci('Ricevi questa email perché ti sei registrato sul nostro sito e hai dato il consenso a ricevere comunicazioni email da parte nostra.', $lingua) . '<br>
		' . traduci('Se non vuoi più ricevere email di questo tipo clicca su ', $lingua) . ' <a href="https://www.scidoo.com/unsubscribe">' . traduci('Cancella iscrizione', $lingua) . '</a>

		</body>
		</html>';

			$response = inviamail($email, $content, 0, $oggetto, $dati_struttura['email'], [], $dati_struttura['nome_struttura'], $dati_struttura['email']);

		}
	}

	break;
case 27:

	$parent = $value[0];
	$IDmenu_servizio = $value[1];
	$time = $value[2];

	$IDsottotip = get_info_from_IDserv($IDmenu_servizio, 'IDsottotip', $IDstruttura);

	$IDservizio = $ID;

	ristorante_inserisci_prodotto($IDstruttura, ['ID' => $IDprenotazione, 'tipo' => 0], ['id' => $IDprenotazione, 'tipo' => 0, 'IDservizio' => $IDservizio, 'time' => $time, 'idsottotip' => $IDsottotip, 'IDaddebito_riferimento' => $parent, 'target' => 0]);
	break;
case 28:
	elimina_addebiti_collegati($IDstruttura, $IDprenotazione, 0, $ID);
	break;
case 29:
	$IDservizio = $ID;
	$quantita = $value[0];
	$variazioni = $value[1];
	$note = $value[2] ?? '';
	if (!isset($_SESSION['ordinazione_webapp'][$IDprenotazione]['servizi'][$IDservizio])) {

		$dati = ['ID' => $IDservizio, 'quantita' => $quantita, 'variazioni' => $variazioni, 'note' => $note];

		$_SESSION['ordinazione_webapp'][$IDprenotazione]['servizi'][$IDservizio] = $dati;
	}

	//$_SESSION['ordinazione'][$IDprenotazione]

	break;
case 30:
	unset($_SESSION['ordinazione_webapp'][$IDprenotazione]['servizi'][$ID]);
	break;
case 31:

	$time_ora = time();

	$secondi = $_SESSION['ordinazione_webapp'][$IDprenotazione]['orario'];

	$sala = $_SESSION['ordinazione_webapp'][$IDprenotazione]['sala'];
	$time = time0($time_ora) + $secondi;
	foreach ($_SESSION['ordinazione_webapp'][$IDprenotazione]['servizi'] as $dati) {

		$lista_time[$time] = ['soggetti' => $dati['quantita']];

		$parametro = ['IDservizio' => $dati['ID'], 'tipo' => 1, 'postazione' => '', 'list' => $lista_time, 'note' => $dati['note'], 'sala' => $sala];

		$IDaddebito = inserisci_servizi_riferimento($parametro, $IDprenotazione, 0, $IDstruttura)[0];

		if (!empty($dati['variazioni'])) {
			$IDsottotip = get_info_from_IDserv($dati['ID'], 'IDsottotip', $IDstruttura);

			foreach ($dati['variazioni'] as $IDvariazione => $modi) {
				if (!empty($modi)) {
					foreach ($modi as $modo => $val) {
						if ($val) {
							ristorante_inserisci_prodotto($IDstruttura, ['ID' => $IDprenotazione, 'tipo' => 0],
								['IDservizio' => $IDvariazione, 'time' => $time_ora, 'idsottotip' => $IDsottotip, 'IDtarget' => $IDaddebito, 'modi' => $modo]);
						}

					}
				}

			}
		}

	}

	$_SESSION['ordinazione_webapp'][$IDprenotazione] = [];
	break;

case 32:
	$IDservizio = $ID[0];
	$modifica = $ID[1];

	switch ($modifica) {
	case 'quantita':
		$_SESSION['ordinazione_webapp'][$IDprenotazione]['servizi'][$IDservizio]['quantita'] = $value;
		break;
	case 'variazioni':
		$IDvariazione = $value[0];
		$modi = $value[1];

		if (isset($_SESSION['ordinazione_webapp'][$IDprenotazione]['servizi'][$IDservizio]['variazioni'][$IDvariazione])) {
			$valore_var = $_SESSION['ordinazione_webapp'][$IDprenotazione]['servizi'][$IDservizio]['variazioni'][$IDvariazione];

			if (isset($valore_var[$modi])) {

				unset($_SESSION['ordinazione_webapp'][$IDprenotazione]['servizi'][$IDservizio]['variazioni'][$IDvariazione]);
			} else {
				$_SESSION['ordinazione_webapp'][$IDprenotazione]['servizi'][$IDservizio]['variazioni'][$IDvariazione][$modi] = 1;
			}
		} else {
			$_SESSION['ordinazione_webapp'][$IDprenotazione]['servizi'][$IDservizio]['variazioni'][$IDvariazione][$modi] = 1;
		}
		break;
	case 'note':
		$_SESSION['ordinazione_webapp'][$IDprenotazione]['servizi'][$IDservizio]['note'] = $value;
		break;

	}

	break;
case 33:
	$_SESSION['ordinazione_webapp'][$IDprenotazione]['sala'] = $ID;
	break;
case 34:
	//variazioni menu

	$IDaddebito = $ID;
	$time_ora = time();
	$lista_servizi = get_dati_conto_riferimento($IDprenotazione, 0, [], $IDstruttura)['arr_servizi'];

	$menu = array_filter($lista_servizi, function ($arg) use ($IDaddebito) {return $arg['IDaddebito'] == $IDaddebito;})[$IDaddebito];

	$IDsottotip = get_info_from_IDserv($menu['IDserv'], 'IDsottotip', $IDstruttura);

	if (!empty($value)) {
		foreach ($value as $IDvariazione => $modi) {
			ristorante_inserisci_prodotto($IDstruttura, ['ID' => $IDprenotazione, 'tipo' => 0],
				['IDservizio' => $IDvariazione, 'time' => $time_ora, 'idsottotip' => $IDsottotip, 'IDtarget' => $IDaddebito, 'modi' => $modi]);

		}
	}

	break;
case 35:
	$_SESSION['ordinazione_webapp'][$IDprenotazione]['orario'] = $value;
	break;
case 36:
	rimuovi_immagine($ID);
	break;
case 37:
	set_schedina_autorizzazione_privacy($ID, $value, $IDstruttura);
	break;
}
