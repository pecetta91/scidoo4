<?php
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';

//require_once __DIR__ . '/' . '../funzioni/disponibilita.php';
array_escape($_POST);

$IDstruttura = $_SESSION['IDstruttura'];

$IDprenotazione = $_POST['IDprenotazione'];
$data = $_POST['data'];
$data = implode('-', array_reverse(explode('/', $data)));
$time = strtotime($data);

$query = "SELECT COUNT(DISTINCT time),sala,MAX(time) FROM prenextra WHERE IDpren='$IDprenotazione' AND tipolim=4 AND IDstruttura='$IDstruttura'";
$result = $link2->query($query);
$row = $result->fetch_row();
$notti = $row[0];
$sala = $row[1];
$time_end = $row[2];

$alloggi_disponibili = alloggi_disponibili_per_giorno($IDstruttura, [], $time, $time + (86400 * $notti), $IDprenotazione);
$alloggi_disponibili = count($alloggi_disponibili) > 1 ? array_intersect(...$alloggi_disponibili) : reset($alloggi_disponibili);

$query = "SELECT ID,nome FROM appartamenti WHERE IDstruttura='$IDstruttura' AND attivo IN(0,1)";
$result = $link2->query($query);
$alloggi = $result->fetch_all();
$alloggi = array_combine(array_column($alloggi, 0), array_column($alloggi, 1));

$opzioni = implode(array_map(function ($arg) use ($alloggi, $sala) {return '<option ' . ($sala == $arg ? 'selected' : '') . ' value="' . $arg . '">' . $alloggi[$arg] . '</option>';}, $alloggi_disponibili));

$testo = '
<div class="nav navbar_picker_flex" >
 	<div onclick="chiudi_picker()"><i class="fas fa-times icona_chiudi" ></i></div>
 	<div style="margin-top:5px;padding-right:10px"> Cambio data

 	</div>
</div>



<div class="content" style="margin-top:0">
	<div id="cambio-data" style="padding-top:5px;">
 				<div class="page1">

 					<div style="margin:10px ;text-align:center">
 						<button id="cambio-data-next" class="button_salva_preventivo"  >SPOSTA ARRIVO</button>
 						' . ($time < $time_end ? ' <button id="data-durata" class="button_salva_preventivo"  >CAMBIA DURATA SOGGIORNO</button> ' : '') . '
 					</div>
				</div>
				<div class="page2" style="display:none;">
				' . ($opzioni ? '
						 <div id="filtra_alloggio" style="display:none;">
							<ul class="uk-list uk-list-divider uk-picker-bot" style="padding:5px 20px;" onchange="(r,s)=>{cambia_valore_html_select(\'alloggio\',r,s);}">
								' . genera_select_uikit($alloggi, []) . '
							</ul>
						</div>

					     <div class=" uk_grid_div div_list_uk" uk-grid  onclick="carica_content_picker($(\'#filtra_alloggio\'))">
					        <div class="uk-width-1-3 lista_grid_nome">Alloggio </div>
					        <div class="uk-width-expand uk-text-right lista_grid_right c000"  data-appsrc="' . $sala . '"  id="alloggio" data-select="' . $sala . '"   > ' . $alloggi[$sala] . '  </div>
					    </div> ' : 'Nessun alloggio disponibile') . '



							<div style="margin:10px;text-align:center"><button id="data-sposta"  class="button_salva_preventivo" style=" width:100%">CONFERMA</button></div>


				</div>

	</div>
</div>
';

$script = "<script>
		$('#cambio-data-next').click(function() {
			$('#cambio-data .page1').hide();
			$('#cambio-data .page2').show();
		});
";
if (!($time < $time_end)) {
	$script .= "
	setTimeout(function() {
		$('#cambio-data-next').click();
	});";
}
$script .= "</script>";

echo $testo . $script;

?>
