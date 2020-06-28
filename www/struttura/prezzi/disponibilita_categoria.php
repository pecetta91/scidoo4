<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';

$IDutente = $_SESSION['ID'];
$IDstruttura = $_SESSION['IDstruttura'];

$IDcategoria = $_POST['IDcategoria'] ?? 0;

$time = (isset($_SESSION['time_prezzi']) ? $_SESSION['time_prezzi'] : time_struttura());
$dataoggi = date('Y-m-d', $time);

$time_indietro = $time - (2 * 86400);
$time_fine = $time + (12 * 86400);

$categorie = get_categorie($IDstruttura, 0, 1);

$alloggi_disponibili = categorie_disponibili_per_giorno($IDstruttura, $time_indietro, $time_fine);

$testo_disponibilita = '';
if (!empty($alloggi_disponibili)) {
	foreach ($alloggi_disponibili as $tt => $dati) {

		$appartamenti_liberi = (isset($dati[$IDcategoria]) ? $dati[$IDcategoria] : 0);
		$testo_disponibilita .= '
			<div style="padding: 0 5px; text-align: center;   font-size: 15px; border-right: 1px solid #e1e1e1;' . ($time == $tt ? 'background: #0075ff; color: #fff;' : '') . '">
				<div style="">' . dataita11($tt) . ' </div>
				<div style="' . ($appartamenti_liberi == 0 ? 'color:#d53333;font-weight:600;' : '') . '">' . $appartamenti_liberi . '</div>
			</div> 	';

	}
}

$picker = '


<div class="nav navbar_picker_flex" >
 	<div onclick="chiudi_picker()"><i class="fas fa-times icona_chiudi" ></i></div>
</div>

<div class="content content_picker_nav"  id="dettagli_tab_oggetto" style="height:100%;padding: 0 5px;">
	<strong style="color:#' . $categorie[$IDcategoria]['colore'] . '">' . $categorie[$IDcategoria]['categoria'] . ' (' . $categorie[$IDcategoria]['numero_appartamenti'] . ') </strong>
	<div style="overflow: auto; white-space: nowrap;   display: flex;    padding: 5px 10px;    padding-bottom: 15px;">
	' . $testo_disponibilita . '

	</div>


</div>';

echo $picker;
