<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';
$IDutente = $_SESSION['ID'];
$IDstruttura = $_SESSION['IDstruttura'];

$picker = '

<div class="nav navbar_picker_flex" >
 	<div onclick="chiudi_picker()"><i class="fas fa-times icona_chiudi" ></i></div>
 		<div class="uk-inline"  style="margin-top: 5px;  padding-right: 10px;">
		        <span class="uk-form-icon"><i class="fas fa-search"></i></span>
		        <input class="uk-input filtro_ricerca_preventivo" type="text"  style="height:unset" placeholder="ricerca servizio" onkeyup="ricerca_servizio(this.value)">
		    </div>

</div>

<div class="content content_picker_nav"  id="dettagli_lista_servizi" style="padding-bottom:20px">


</div>';

echo $picker;

?>
