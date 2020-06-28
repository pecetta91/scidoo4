<?php
//header('Access-Control-Allow-Origin: *');
include '../config/connecti.php';
include '../config/funzioni.php';

$time = time();

$nome = $_POST['nome'];
$nome_struttura = $_POST['nome_struttura'];
$mail = $_POST['email'];
$telefono = $_POST['telefono'];

$controlla_email = checkEmail($mail);

if ($controlla_email == 0) {
	echo '-1';
	return false;
}
if (($nome == '') || ($nome_struttura == '') || ($mail == '') || ($telefono == '')) {
	echo '0';
	return false;
}

$oggetto = 'Richiesta Informazioni - Scidoo  ';

$testo_mail = '
Richiesta di informazioni  da: <strong>' . $nome . '   </strong> <br/><br/>
Per la Struttura:  <strong>' . $nome_struttura . '   </strong><br/><br/>


<br/> Telefono: ' . $telefono . '
<br/> Email: ' . $mail . '
<br/>
<br/> ';

$response = inviamail('info@scidoo.com', $testo_mail, 0, $oggetto, $mail, [], $nome, $mail);
echo $response;

if ($response) {
	$messaggio_ospite = "
Gent.mo $nome  ,<br/>
con la seguente email si conferma la corretta ricezione della vostra email.<br/><br/>
Le risponederemo entro le prossime 24/48hr.<br/><br/><br/>

-- Richiesta Ricevuta --
<br/><br/><br/>" . $testo_mail . "<br/>";

	$oggetto = 'Richiesta Informazioni - Scidoo';

	$response = inviamail($mail, $messaggio_ospite, 0, $oggetto, 'info@scidoo.com', [], 'SCIDOO', 'info@scidoo.com');
}

/*

crea sessione prova scidoo
 */