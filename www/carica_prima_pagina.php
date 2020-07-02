<?php
require_once __DIR__ . '/../config/connecti.php';
require_once __DIR__ . '/../config/funzioni.php';

$script = 'carica_app(0);';
if (isset($_SESSION['IDstruttura']) && (!isset($_SESSION['blocca_struttura']))) {

	$IDstruttura = $_SESSION['IDstruttura'];

	$query = "SELECT nome FROM strutture WHERE ID='$IDstruttura' LIMIT 1";
	$result = mysqli_query($link2, $query);
	$row = mysqli_fetch_row($result);
	$nome_str = $row['0'];

	$foto = 'immagini/big' . getfoto($IDstruttura, 12);

} else {

	if (isset($_POST['token'])) {
		$token = $_POST['token'];
		$query = "SELECT IDobj,IDcliente FROM add_to_home_token WHERE token='$token' AND tipoobj='1' LIMIT 1";
		$result = mysqli_query($link2, $query);
		$row = mysqli_fetch_row($result);

		$IDprenotazione = $row['0'];
		$IDcli = $row['1'];

		$_SESSION['IDstrpren'] = $IDprenotazione;
		$_SESSION['IDcliente'] = $IDcli;
		$_SESSION['tipocli'] = 1; //schedine

		if (!isset($_COOKIE['scidooguest'])) {

			$testo = '0_0_' . $IDprenotazione . '_' . $IDcli . '_1';
			$pw = $scidoo_config['encryption_key'];
			$testo = data_encrypt($testo, $pw);
			$contatore = 0;

			setcookie("scidooguest", $testo, time() + 2678400, '/', "scidoo.com"); //cookie che dura un mese
		}

	} else {

		$IDprenotazione = (isset($_SESSION['IDstrpren']) ? $_SESSION['IDstrpren'] : 0);
	}

	if ($IDprenotazione != 0) {
		$IDstruttura = get_IDstruttura_from_IDpren($IDprenotazione);
		$dati_struttura = genera_sotto_strutture_lista($IDstruttura)[0];
		$nome_str = $dati_struttura['nome'];
	}

	if (!isset($_SESSION['blocca_struttura'])) {
		$dispositivo = 1;
		registra_apertura($IDprenotazione, 0, $dispositivo, $IDstruttura);
	}

	$landing_page = $_POST['landing_page'] ?? 0;

	if (!empty($landing_page)) {
		$script = 'history_navigation.push("navigation_ospite(0,0)");';
		switch ($landing_page) {
		case 'checkin':
			$script .= 'carica_pagina_principale(1);navigation_ospite(10,0)';
			break;
		case 'prenotazione':
			$script .= 'carica_pagina_principale(1);navigation_ospite(1,0,()=>{switch_tab_prenotazione_ospite(0)})';
			break;
		case 'recensione':
			$script .= 'carica_pagina_principale(1);navigation_ospite(6,0)';
			break;
		case 'posizione':
			$script .= 'carica_pagina_principale(1);navigation_ospite(13,0,()=>{switch_tab_prenotazione_ospite(2)})';
			break;
		}
	}

}

$script_controllo_login = (isset($_SESSION['IDstruttura']) ?

	'	$(window).focus(function() {
		    idleCheck.movement = true;
		    controllo_login();

		    idleCheck.movement = true;
		});'

	: '');

$script_debug = (!DEBUG ?

	"
		  window.smartlook||(function(d) {
		    var o=smartlook=function(){ o.api.push(arguments)},h=d.getElementsByTagName('head')[0];
		    var c=d.createElement('script');o.api=new Array();c.async=true;c.type='text/javascript';
		    c.charset='utf-8';c.src='https://rec.smartlook.com/recorder.js';h.appendChild(c);
		    })(document);
		    smartlook('init', '81cc726e2e0e7145e002a27a58fc170d050bcafa'); "

	: '');

$_SESSION['settore_gestionale'] = SETTORE_GEST;
$_SESSION['lang'] = 0;

$testo = '  <script>

$( document ).ready(function() {' . $script . '});


' . $script_controllo_login . '

' . $script_debug . '
  </script>';

echo $testo;

?>