<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../config/connecti.php';
require_once __DIR__ . '/../../config/funzioni.php';
$IDstruttura = $_SESSION['IDstruttura'];
$IDutente = $_SESSION['ID'];

$dati = (isset($_POST['arr_dati']) ? $_POST['arr_dati'] : []);
$time = $dati['time'] ?? time_struttura();
$tipologia = $dati['tipologia'];

list($yy, $mm, $dd) = explode('-', date('Y-m-d'));
$timeoggi0 = mktime(0, 0, 0, $mm, 1, $yy);
$timeoggi = strtotime(date('Y-m-d'));

list($yy, $mm, $dd) = explode('-', date('Y-m-d', $time));
$time_inizio = mktime(0, 0, 0, $mm, 1, $yy);

$time_fine = $time_inizio + 86400 * 35;

$configurazioni_calendario = visualizza_configurazioni($IDstruttura, 11, $IDutente);

$altezza_riga = (($configurazioni_calendario['height_row_cal_app']['valore'] ?? 0) != 0 ? $configurazioni_calendario['height_row_cal_app']['valore'] : 50);
$grandezza_colonna = (($configurazioni_calendario['width_colonne_cal_app']['valore'] ?? 0) != 0 ? $configurazioni_calendario['width_colonne_cal_app']['valore'] : 54);

$cambio_mesi = '<div id="cambio_mesi" style="display:none;">
<ul class="uk-list uk-list-divider uk-picker-bot" style="padding:5px 20px;"> ';

if ($timeoggi0 != $time_inizio) {
	$cambio_mesi .= '<li  onclick="navigation(26, {tipologia:' . $tipologia . ',time:' . $timeoggi0 . '},8);chiudi_picker();"><strong> Vai ad Oggi</strong> </li>';
}

$time_inizio = mktime(0, 0, 0, $mm, 1, $yy);
for ($i = -5; $i < 6; $i++) {
	$prossimi_anni = mktime(0, 0, 0, $mm + $i, 1, $yy);
	$cambio_mesi .= ' <li  onclick="navigation(26, {tipologia:' . $tipologia . ',time:' . $prossimi_anni . '},8);chiudi_picker();">' . $mesiita[date('n', $prossimi_anni)] . ' ' . date('Y', $prossimi_anni) . ' </li>';
}

$cambio_mesi .= '</ul></div>';

$mese = '
<div   class="div_grid_angolo" onclick="carica_content_picker(' . "'cambio_mesi'" . ')">
	<div class="testo_mese"> ' . $mesiita[date('n', $time_inizio)] . '  	<i class="fas fa-chevron-down"></i> 	<br/> <span style="font-size:10px;">' . date('Y', $time_inizio) . '</span>
	</div>
</div>';

$lista_time = \Calendario\celle_servizio($IDstruttura, $tipologia, $time_inizio, $time_fine);
//print_html($lista_time);

$header = '';
for ($time_giorno = $time_inizio; $time_giorno < $time_fine; $time_giorno += 86400) {
	$gg = date('w', $time_giorno);
	$mes = date('n', $time_giorno);
	$header .= '<div class="data_header ' . ($time_giorno == $timeoggi ? 'oggi' : '') . '" > <div><strong style="font-size:17px;">' . date('d', $time_giorno) . '</strong>   <br> ' . $giorniita2[$gg] . '</div></div>';
}

$side = '';
$bodytxt = '';

$query = "SELECT sl.ID,sl.nome,sg.nome
FROM servizi AS s
JOIN saleassoc AS sa ON sa.IDsotto=s.IDsottotip
JOIN sale AS sl ON sl.ID=sa.ID
JOIN sale_gruppi AS sg ON sg.ID=sl.IDgruppo
WHERE s.IDtipo=$tipologia AND s.IDstruttura='$IDstruttura' GROUP BY sl.ID";
$sale_struttura = $link2->query($query)->fetch_all();
if (!empty($sale_struttura)) {
	foreach ($sale_struttura as $sala) {
		$IDsala = $sala[0];
		$side .= '<div class="appnew uk-text-middle "   id="' . $IDsala . '">
			<div class="nome  uk-text-truncate" >' . $sala[1] . '<br/> <span>' . $sala[2] . '</span></div> </div>';

		$rigatxt = '';
		for ($time_giorno = $time_inizio; $time_giorno < $time_fine; $time_giorno += 86400) {

			$rigatxt .= '<div class="cont_body collega_servizio' . ($time_giorno == $timeoggi ? 'oggi' : '') . '"  data-sala="' . $sala[0] . '"  data-time="' . $time_giorno . '" >';

			if (isset($lista_time['celle'][$IDsala][$time_giorno]) && (is_array($lista_time['celle'][$IDsala][$time_giorno]))) {
				$dati_tipologia = $lista_time['celle'][$IDsala][$time_giorno];
				$nome_cliente = $dati_tipologia['nome_cliente'];
				$giorni = $dati_tipologia['giorni'];

				$rigatxt .= '<div class="divcal apri_prenotazione" style="background-color:#' . $dati_tipologia['colore'] . ';width:' . ($giorni * ($grandezza_colonna)) . 'px;"  data-idpren="' . $dati_tipologia['IDprenotazione'] . '">
					<div>' . $nome_cliente . '</div>
				</div>';
			}

			$rigatxt .= '</div>';
		}

		$bodytxt .= '<div class="riga_txt " style="overflow:hidden;" >' . $rigatxt . ' </div>';
	}
}

$testo = $cambio_mesi . '
<input type="hidden" id="tipologia" value="' . $tipologia . '">
<input type="hidden" id="time_attuale" value="' . $time_inizio . '" >
			' . $mese . '
		<div id="calendar_div" style="-webkit-overflow-scrolling:touch;overscroll-behavior: none;position:relative">

			<div   class="header">
				<div  style="width: max-content;display:flex;height:100%"> ' . $header . ' </div>
			</div>

			<div  class="side">
				' . $side . '
			</div>

			<div  class="body">
				' . $bodytxt . '
			</div>
	</div>';

echo $testo;
?>

<style>



#calendar_div  .cont_body { width:<?php echo $grandezza_colonna; ?>px;height: <?php echo $altezza_riga; ?>px;}

#calendar_div  .data_header{  width: <?php echo $grandezza_colonna; ?>px;}

#calendar_div  .appnew{height: <?php echo $altezza_riga; ?>px;}

</style>
