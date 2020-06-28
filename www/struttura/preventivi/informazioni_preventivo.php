<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';
require_once __DIR__ . '/../../../config/preventivatore/funzioni/preventivatore.php';

$IDutente = $_SESSION['ID'];
$IDstruttura = $_SESSION['IDstruttura'];
$IDpreventivo = $_POST['IDpreventivo'];

$div_pulsanti_picker = '';
$query = "SELECT  p.IDcliente,s.mail,s.cell,s.tel,COUNT(r.ID),p.stato FROM preventivo as p
LEFT JOIN schedine as s on s.ID=p.IDcliente
LEFT JOIN richieste AS r ON r.IDpreventivo=p.ID
WHERE p.IDstruttura='$IDstruttura' AND p.ID='$IDpreventivo'";
$result = mysqli_query($link2, $query);
if (mysqli_num_rows($result) > 0) {
	$row = mysqli_fetch_row($result);
	$stato_prevenitivo = $row['5'];
	if ($row[0] and $row[4]) {
		if ($stato_prevenitivo > 2) {
			$div_pulsanti_picker .= '<li  class="pulsante_carrello_preventivo" data-tipo="0">Reinvia </li> ';
		} else {
			$no_cont = 2;
			if ($row['1']) {
				$div_pulsanti_picker .= '<li  class="pulsante_carrello_preventivo" data-tipo="1">Invia Email </li> ';
				$no_cont--;
			}

			if ($row['2']) {
				$div_pulsanti_picker .= '<li  class="pulsante_carrello_preventivo" data-tipo="2">Invia Sms </li> ';
				$no_cont--;
			}

			if (($row['1']) && ($row['2'])) {
				$div_pulsanti_picker .= '<li  class="pulsante_carrello_preventivo" data-tipo="3"> Invia Sms + Email </li> ';
			}

			if ($no_cont == 2) {
				$div_pulsanti_picker = '<strong>Inserire Email o Cellulare</strong>';
			}
		}

	} elseif ($row[4] == 0) {
		$div_pulsanti_picker = '<strong>Nessuna proposta creata</strong>';
	} else {
		$div_pulsanti_picker = '<strong>Selezionare un Cliente</strong>';
	}
} else {
	$div_pulsanti_picker = '<strong>Selezionare un Cliente</strong>';
}

$testo = '
<div id="pulsanti_picker" style="display:none">' . $div_pulsanti_picker . '</div>

<div class="nav navbar_picker_flex" >
 	<div onclick="chiudi_picker()"><i class="fas fa-times icona_chiudi" ></i></div>
 	<div style="margin-top:5px;padding-right:10px">
 		<button class="button_salva_preventivo" onclick="preventivatore_salva(4)">Salva</button>
 		<button onclick="pulsanti_opzioni_preventivo()" class="button_salva_preventivo">Invia</button>
 	</div>
</div>


<div>
	<ul  uk-tab="connect: #switcher;animation: uk-animation-fade;swiping:false" class="no_before menu_uk_picker_icona"  >
			<li class="uk-active" onclick="contenuto_informazioni_preventivo(1)"><div><i class="fas fa-cog"></i></div></li>
	        <li onclick="contenuto_informazioni_preventivo(2)"><div><i class="fas fa-images"></i></div></li>
	        <li class="" onclick="contenuto_informazioni_preventivo(3)" ><div><i class="fas fa-edit"></i></div></li>
	        <li class="" onclick="contenuto_informazioni_preventivo(4)"><div><i class="fas fa-info"></i></div></li>
	        <li class="" onclick="apri_preventivo_chat()"><div><i class="fas fa-comments"></i></div></li>
        </ul>
</div>

<div class="content" style="margin-top:0">
	<div id="dettagli_tab" style="padding-top:5px;">
	</div>
</div>';

echo $testo;
?>
