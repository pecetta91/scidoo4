<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../config/connecti.php';
require_once __DIR__ . '/../../config/funzioni.php';
$IDstruttura = $_SESSION['IDstruttura'];

$IDutente = $_SESSION['ID'];
$parametri = $_POST['parametri'] ?? [];

$no_idpren = $parametri['no_idpren'] ?? 0;

$time_inizio = $parametri['time'];

$no_gruppi = $parametri['no_gruppi'] ?? '';
$no_tavoli = $parametri['no_tavoli'] ?? '';

$time_inizio = time0($time_inizio ?? time());

$time_fine = (isset($parametri['time_fine']) ? time0($parametri['time_fine']) : time0($time_inizio + 86400));

$lista_IDpren = [];
$query = "SELECT p.IDv FROM prenotazioni as p
JOIN prenextra as pr ON pr.IDpren=p.IDv AND pr.tipolim='4'
WHERE p.IDstruttura='$IDstruttura' AND pr.time>='$time_inizio' AND pr.time<='$time_fine' AND p.IDv!='$no_idpren' AND p.stato>='0' GROUP BY p.IDv";
$result = mysqli_query($link2, $query);
while ($row = mysqli_fetch_row($result)) {
	$IDpren = $row['0'];
	$lista_IDpren[] = $IDpren;
}

$lista = [];
$data_selection = '';
if ($lista_IDpren) {
	$filtri_prenotazioni = [['IDprenotazione' => $lista_IDpren]];

	if ($no_gruppi) {
		$filtri_prenotazioni[0]['gruppi'] = false;
		$data_selection .= 'data-no_gruppo="1"';
	}
	if ($no_idpren) {
		$data_selection .= 'data-no_idpren="' . $no_idpren . '"';
	}
	if ($no_tavoli) {
		$filtri_prenotazioni[0]['tavoli_collegati'] = false;
		$data_selection .= 'data-no_tavolo="1"';
	}

	$lista = get_prenotazioni($filtri_prenotazioni);
}
$lista_prenotazioni = $lista['dati'] ?? [];

$lista_prenotazioni_checkin = [];
if (!empty($lista_prenotazioni)) {
	foreach ($lista_prenotazioni as $key => $val) {

		$IDpren = $val['ID'];

		$checkin = time0($val['checkin']);

		$appartamento = '';

		$IDalloggio = reset($val['alloggi']);
		$appartamento = $alloggi[$IDalloggio]['alloggio'] ?? 'Senza Soggiorno';

		$nome_prenotazione = $val['nome_cliente'];

		$lista_prenotazioni_checkin[$checkin][] = ['IDpren' => $IDpren, 'nome' => $nome_prenotazione, 'appartamento' => $appartamento, 'numero' => $val['numero'], 'persone' => $val['persone']];

	}
}

$blocchi_giorni = '';
$lista_righe_prenotazioni = '';
//$primo_giorno = array_key_first($lista_prenotazioni_checkin);
$lista_date = [];
foreach ($lista_prenotazioni_checkin as $time_checkin => $val) {

	$lista_date[] = $time_checkin;

	$blocchi_giorni .= '
	<li>
		<div onclick="seleziona_giorno_prenotazioni(this)"   data-time="' . $time_checkin . '"
		 style=" width: 60px; height:60px;padding:5px;font-size: 11px; font-weight: 600; border-radius:5px;box-shadow: 0 0 5px 1px #e1e1e1;  text-align:center;align-items: center;margin:2px 5px;color:#000">
				<div class="giorno" style="width:100%">' . dataita15($time_checkin) . '<br><span>' . $mesiita2[date('n', $time_checkin)] . '</span></div>
		</div>
	</li>

	';

	/*
		<tr onclick="creasessione(' . $valore['IDpren'] . ', 169);" class="blocco_pren" data-nome="' . $valore['nome'] . '" data-alloggio="' . $valore['appartamento'] . '" data-numero="' . $valore['numero'] . '" data-pren="' . $valore['IDpren'] . '" style="cursor:pointer">
		<td>N. ' . $valore['numero'] . '</td><td>' . $valore['nome'] . '</td><td>' . $valore['appartamento'] . '</td><td>' . $valore['persone'] . ' <i class="fas fa-user"></i></td>
		</tr>
	*/
	$info_prenotazioni = '';
	if (!empty($val)) {
		foreach ($val as $valore) {
			$info_prenotazioni .= '

			<div uk-grid class="uk-margin-small uk_grid_div div_list_uk blocco_pren" data-nome="' . $valore['nome'] . '" data-alloggio="' . $valore['appartamento'] . '" data-numero="' . $valore['numero'] . '" data-pren="' . $valore['IDpren'] . '" data-time-check="' . $time_checkin . '" onclick="seleziona_prenotazione_collegamento(this)">
				<div class="uk-width-auto lista_grid_nome uk-first-column">	<input type="checkbox" style="width:20px;height:20px;"></div>
				<div class="uk-width-expand   c000">
						N. ' . $valore['numero'] . ' ' . $valore['nome'] . ' - ' . $valore['persone'] . ' <i class="fas fa-user"></i>
				</div>
			</div> ';
		}

		/*
			<div data-time-pren="' . $time_checkin . '" class="prenotazioni_nascoste">
						<div style="margin-bottom:10px;"><strong>' . dataita($time_checkin) . '</strong></div>

							<table style="width:100%;margin:0;table-layout:fixed;text-align:left;" class="tabmag3 td25 tabtrhover">
								<tr><th>Numero</th><th>Nome</th><th>Alloggio</th><th>Persone</th></tr>
								' . $info_prenotazioni . '
							</table>
					</div>
		*/

		$lista_righe_prenotazioni .= '
		<div data-time-pren="' . $time_checkin . '" class="prenotazioni_nascoste">
			' . $info_prenotazioni . '
		</div> ';
	}

}
/*
<div style="padding:5px;  overflow: auto;  white-space: nowrap;    width: 95%;  margin: auto;">

</div>
<div onclick="seleziona_giorno_prenotazioni(this)"   data-time="' . ($time_inizio + (86400 * $i)) . '"
style=" width: 60px; height:60px;padding:5px;font-size: 11px; font-weight: 600; border-radius:5px;box-shadow: 0 0 5px 1px #e1e1e1;  text-align:center;align-items: center;margin:2px 5px;color:#000">
<div class="giorno" style="width:100%">' . dataita15(($time_inizio + (86400 * $i))) . '<br></span></div>
</div>
 */

$testo = '

<div class="nav navbar_picker_flex" >
 	<div onclick="chiudi_picker()"><i class="fas fa-times icona_chiudi" ></i></div>
 	<div style="margin-top:5px;padding-right:10px">
 		<button onclick="aggiungi_servizio_tipologia()" style=" width: 100%;   background: #2574ec; border: none;  color: #fff;   border-radius: 5px;   padding: 5px 10px;   font-size: 16px;" >Salva</button>
 	</div>
</div>


<div class="content" style="margin-top:0;padding-top:5px;">

	' . genera_slider_date_uikit(['lista_date' => $lista_date]) . '



	<div style="margin-top:15px;">
		' . $lista_righe_prenotazioni . '
	</div>



</div>';

echo $testo;
?>
<script>
	$('.premi_data').on('click',function(){
		var time=$(this).data('time');
		 $('.premi_data').removeClass('data_selezionata');
   		 $('.premi_data[data-time="'+time+'"]').addClass('data_selezionata');
		seleziona_giorno_prenotazioni(time);
	});

</script>

<style>
	.prenotazioni_nascoste{display: none;margin: 0 5px;}
	.prenotazioni_nascoste.riga_visibile{display: block}
</style>
