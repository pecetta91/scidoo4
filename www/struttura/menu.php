<?php
// //header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../config/connecti.php';
require_once __DIR__ . '/../../config/funzioni.php';

$IDutente = $_SESSION['ID'];
$IDstruttura = $_SESSION['IDstruttura'];

$cambia_struttura = '';
$query = "SELECT DISTINCT(s.ID),s.nome,s.tipologia FROM strutture as s,personale as p WHERE (s.IDcliente='$IDutente') OR (p.IDuser='$IDutente' AND p.IDstr=s.ID)";
$result = mysqli_query($link2, $query);
while ($row = mysqli_fetch_row($result)) {
	$cambia_struttura .= '<li onclick="chiudi_picker();modcambio(' . $row['0'] . ',1)">' . $row['1'] . ' </li>';
}

$testo = '
<input type="hidden" value="' . base64_encode($cambia_struttura) . '" id="cambia_struttura">
	<ul class="uk-nav uk-nav-default ">
	 <li class="uk-nav-header">Prenotazione</li>
	 <li class="menu_nav_puls"><a onclick="navigation(15,0,0,0)">Clienti</a></li>
	 <li class="menu_nav_puls"><a onclick="navigation(12,0,0,0)">Domotica</a></li>
	 <li class="menu_nav_puls"><a onclick="navigation(16,0,0,0)">Prenotazioni</a></li>
     <li class="menu_nav_puls"><a onclick="navigation(21,0,()=>{aggiorna_navbar_preventivi();})">Preventivi</a></li>
     <li class="menu_nav_puls"><a onclick="cambia_struttura()">Cambia Struttura</a></li>

</ul>


<button class="uk-button uk-button-danger" style="border-radius:5px;margin-top:10px" onclick="esci()">Logout</button>
  ';

return $testo;

?>
