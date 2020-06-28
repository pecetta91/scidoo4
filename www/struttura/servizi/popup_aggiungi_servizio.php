<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';

$IDutente = $_SESSION['ID'];
$IDstruttura = $_SESSION['IDstruttura'];
$dati = $_POST['dati'] ?? [];
$opzioni = $_POST['opzioni'] ?? [];

$IDriferimento = $dati['IDriferimento'] ?? null;
$tipo_riferimento = $dati['tipo_riferimento'] ?? 0;

//opzioni
$seleziona_tutto = $opzioni['seleziona_tutto'] ?? null;
$tipologie = $opzioni['solo_tipologie'] ?? null;
$sala = $opzioni['sala'] ?? null;
$time_selected = $opzioni['time'] ?? time0(time_struttura($IDstruttura));

$_SESSION['filtro_tipologie'] = $tipologie;

$testo = '

<div class="nav navbar_picker_flex" >
 	<div onclick="chiudi_picker()"><i class="fas fa-times icona_chiudi" ></i></div>
 	<div style="margin-top:5px;padding-right:10px">
 			Aggiungi Servizio
 	</div>
</div>


<div class="content" style="margin-top:0 ;    height: calc(100% - 120px);padding-bottom:20px">
<input type="hidden" id="IDriferimento" value="' . (is_array($IDriferimento) ? implode(',', $IDriferimento) : $IDriferimento) . '">
<input type="hidden" id="tipo_riferimento" value="' . $tipo_riferimento . '">
	<div id="dettagli_tab" style="padding-top:5px;">

			<div id="seleziona_servizio" class="div_dettagli_preventivatore uk-margin-bottom" onclick="popup_ricerca_servizio()">
					Servizio
					<div id="nome_servizio_selezionato"></div>
			</div>


			<div id="date_servizio">

			</div>


			<div style="position:fixed;bottom:0px;width:100%;border-top:1px solid #e1e1e1;background:#eee ">
				<div style="width:95%;margin:0 10px; " class="uk-inline conversation-compose">
			 	  		<button style=" width: 100%;   background: #2574ec; border: none;  color: #fff;   border-radius: 5px;   padding: 5px 10px;   font-size: 16px;" id="aggiungi-servizio" > Conferma
			 	  		<span id="numero_servizi"></span> <span id="totale_vendita"></span></button>
			      </div>
			</div>


	</div>
</div>';

/*<div  class="div_dettagli_preventivatore uk-margin-bottom">
Date
</div>*/

echo $testo;
?>
