<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../config/connecti.php';
require_once __DIR__ . '/../../config/funzioni.php';
$IDprenotazione = $_SESSION['IDstrpren'];
$IDstruttura = get_IDstruttura_from_IDpren($IDprenotazione);
$lang = $_SESSION['lang'];

$testo = '
<div class="nav navbar_picker_flex" >
 	<div onclick="chiudi_picker()"><i class="fas fa-times icona_chiudi" ></i></div>
</div>


<div class="content scroll_chat_auto" style="margin-top:0;height:calc(100% - 115px)">
	<div id="contenitore_chat" style="padding:0 10px" class="conversation-container">



	</div>
</div>




<div style="position:fixed;bottom:0px;width:100%;border-top:1px solid #e1e1e1;background:#eee ">
	<div style="width:95%;margin:0 10px; " class="uk-inline conversation-compose">

	 <div class="editable chat_input_mes" contenteditable="true" placeholder="Scrivi un Messaggio"></div>

 	  <button class="send" onclick="invia_messaggio_ospite();">
        <div class="circle">
          <i class="fas fa-paper-plane" style="font-size:18px;margin-left:-3px"></i>
        </div>
      </button>
      </div>
</div>

';

echo $testo;

?>


<script>

reload_messaggi_ospite();
timer_messaggi_ospite=setInterval('reload_messaggi_ospite()',10000);


</script>

