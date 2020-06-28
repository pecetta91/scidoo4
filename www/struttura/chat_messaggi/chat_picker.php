<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';

$IDstruttura = $_SESSION['IDstruttura'];
$parametri = $_POST['parametri'] ?? [];
$IDobj = $parametri['IDobj'];
$tipoobj = $parametri['tipoobj'];

$testo = '
<input type="hidden" id="IDobj" value="' . $IDobj . '" />
<input type="hidden" id="tipoobj" value="' . $tipoobj . '" />
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

 	  <button class="send" onclick="invia_messaggio();">
        <div class="circle">
          <i class="fas fa-paper-plane" style="font-size:18px;margin-left:-3px"></i>
        </div>
      </button>
      </div>
</div>


<script>
switch(' . $tipoobj . '){
	case 0:
		reload_messaggi_struttura({IDprenotazione:' . $IDobj . '});
		timer_messaggi_struttura=setInterval(\'reload_messaggi_struttura({IDprenotazione:' . $IDobj . '})\',10000);
	break;
	case 1:
		reload_messaggi_struttura({IDpreventivo:' . $IDobj . '});
		timer_messaggi_struttura=setInterval(\'reload_messaggi_struttura({IDpreventivo:' . $IDobj . '})\',10000);
	break;
}
</script> ';

echo $testo;

?>
