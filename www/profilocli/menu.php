<?php
//header('Access-Control-Allow-Origin: *');
require_once '../../config/connecti.php';
require_once '../../config/funzioni.php';
$lang = $_SESSION['lang'] ?: 0;

$testo = '
	<ul class="uk-nav uk-nav-default ">
	 <li class="uk-nav-header">' . traduci('Prenotazione', $lang) . '</li>
	 <li class="menu_nav_puls m0  uk-active"><a onclick="navigation_ospite(0,0,0,0);" >Home</a></li>
	 <li class="menu_nav_puls m1"><a  onclick="navigation_ospite(1,0,()=>{switch_tab_prenotazione_ospite(0)});">' . traduci('Prenotazione', $lang) . '</a></li>
	 <li class="menu_nav_puls m2"><a onclick="navigation_ospite(12,0,0,0);" >' . traduci('I tuoi Servizi', $lang) . '</a></li>



	 <li class="menu_nav_puls  "><a onclick="navigation_ospite(7,0)" >' . traduci('Personalizza', $lang) . '</a></li>
	 <li class="menu_nav_puls  "><a  onclick="apri_informazioni_struttura()">' . traduci('Informazioni', $lang) . ' </a></li>
	 <li class="menu_nav_puls  "><a  onclick="navigation_ospite(4,0)">' . traduci('Recensioni', $lang) . ' </a></li>
</ul>

 <ul class="uk-nav uk-nav-default   uk-margin-top">
 	<li class="uk-nav-header">' . traduci('Informazioni', $lang) . '</li>
 	<li class="menu_nav_puls  "><a  onclick="navigation_ospite(16,0)">' . traduci('Luoghi', $lang) . ' </a></li>
 	<li class="menu_nav_puls  "><a  onclick="navigation_ospite(20,0)">' . traduci('Itinerari', $lang) . '</a></li>
 	<li class="menu_nav_puls  "><a  onclick="navigation_ospite(15,0)" >' . traduci('Galleria', $lang) . '</a></li>
 </ul>

 <button class="uk-button uk-button-danger" style="border-radius:5px;margin-top:10px" onclick="esci()">Logout</button>';

echo $testo;

?>
