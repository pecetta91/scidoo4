<?php
//header('Access-Control-Allow-Origin: *');
require_once '../config/connecti.php';
require_once '../config/funzioni.php';
$rand = rand(1, 1000);
$nome_str = '';

$script = 'carica_app(0);';
if (isset($_SESSION['IDstruttura']) && (!isset($_SESSION['blocca_struttura']))) {

	$IDstruttura = $_SESSION['IDstruttura'];

	$query = "SELECT nome FROM strutture WHERE ID='$IDstruttura' LIMIT 1";
	$result = mysqli_query($link2, $query);
	$row = mysqli_fetch_row($result);
	$nome_str = $row['0'];

	$foto = 'immagini/big' . getfoto($IDstruttura, 12);

} else {

	if (isset($_GET['token'])) {
		$token = $_GET['token'];
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

	$landing_page = $_GET['landing_page'] ?? 0;

	if (!empty($landing_page)) {
		$script = 'history_navigation.push("navigation_ospite(0,0)");';
		switch ($landing_page) {
		case 'checkin':
			$script .= 'carica_pagina_principale(1);navigation_ospite(10,0)';
			break;
		case 'prenotazione':
			$script .= 'carica_pagina_principale(1);navigation_ospite(1,0,()=>{switch_tab_prenotazione_ospite(0)})';
			break;
		}
	}

}

$_SESSION['settore_gestionale'] = SETTORE_GEST;
$_SESSION['lang'] = 0;

?>
<html>
    <head>
        <title><?php echo $nome_str; ?></title>

        <meta name="apple-mobile-web-app-title" content="<?php echo $nome_str; ?>">
        <meta name="description" content="Applicazione Concierge - <?php echo $nome_str; ?>">

        <meta charset="utf-8">
        <meta name="viewport" content="target-densityDpi=device-dpi, width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no , viewport-fit=cover" />

        <link rel="icon" href="<?php echo base_url() . '/favicon.png'; ?>">



        <link rel="stylesheet" href="<?php echo base_url() . '/app_uikit/css/uikit.min.css'; ?>" />

        <link rel="stylesheet" href="<?php echo base_url() . '/app_uikit/css/main.css?rand=' . $rand; ?>" />


        <script  src="https://code.jquery.com/jquery-3.4.1.js"  integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="  crossorigin="anonymous"></script>


        <script src=" <?php echo base_url() . '/preventivo_picker/js/preventivo_main.js?r=' . $rand; ?> " data-infinito="0" data-unica="0" data-indietro="1" data-anni="1" data-giorni-passati="1" id="script_preventivo"></script>


         <link rel="stylesheet" href="<?php echo base_url() . '/preventivo_picker/css/preventivo_picker.css?r=' . $rand; ?> " />


         <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">


        <script src="<?php echo base_url() . '/config/dropzone/dropzone.js'; ?>"></script>
        <script src="<?php echo base_url() . '/app_uikit/js/uikit.js?r=' . $rand; ?>"></script>
        <script src="<?php echo base_url() . '/app_uikit/js/uikit-icons.min.js'; ?>"></script>
        <script src="<?php echo base_url() . '/app_uikit/js/main.js?r=' . $rand; ?>"></script>
        <script>

			$( document ).ready(function() {
				<?php echo $script; ?>
			});
		</script>

		<?php
if (isset($_SESSION['IDstruttura'])) {
	echo '
				<script>
					$(window).focus(function() {
					    idleCheck.movement = true;
					    controllo_login();

					    idleCheck.movement = true;
					});
				</script>';
}

if (!DEBUG) {
	echo "
		<script type='text/javascript'>
		  window.smartlook||(function(d) {
		    var o=smartlook=function(){ o.api.push(arguments)},h=d.getElementsByTagName('head')[0];
		    var c=d.createElement('script');o.api=new Array();c.async=true;c.type='text/javascript';
		    c.charset='utf-8';c.src='https://rec.smartlook.com/recorder.js';h.appendChild(c);
		    })(document);
		    smartlook('init', '81cc726e2e0e7145e002a27a58fc170d050bcafa');
		</script>
		";
}
?>


    </head>
    <body >

        <span uk-spinner="ratio:4.5" id="loader" style="display: none;position:fixed;  top: 50%;  left: 50%;z-index: 99;translate(-50%,-50%);
        color:#a2a2a2; border-radius: 10px;transform: translate(-50%,-50%);"></span>


        <div id="overlay_ricerca_preventivo" style="height:100%; position: fixed;  top: 0;
         bottom: 0; left: 0;    right: 0;  background: rgba(0,0,0,.5);  z-index: 1001;  transition: opacity .15s linear;display: none;">
        	<span uk-spinner="ratio:4.5"   style="position:fixed;  top: 50%;  left: 50%;z-index: 1002;color:#fff; border-radius: 10px;transform: translate(-50%,-50%);"></span>
    	</div>


    	<div id="pagina_principale" >


	   </div>

    </body>


</html>


