<?php
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';
$IDprenotazione = $_POST['IDpren'] ?? null;
$data = $_POST['data'] ?? null;
$tipo = $_POST['tipo'] ?? null;
$IDstruttura = $_SESSION['IDstruttura'] ?? null;

$IDinfopren = $_POST['IDinfop'] ?? null;
//scelta per ospite
$nome = null;
if ($IDinfopren) {
	if (!$IDinfopren or $tipo === null or !$data or !$IDprenotazione) {
		die("parametri richiesta mancanti");
	}
	$query = "SELECT MIN(p.time),MAX(p.time) FROM prenextra2 AS p2 JOIN prenextra AS p ON p.ID=p2.IDprenextra WHERE p.IDpren='$IDprenotazione' AND p2.IDinfop='$IDinfopren' AND tipolim=4 AND p.IDstruttura='$IDstruttura'";
	$result = $link2->query($query);
	$row = $result->fetch_row();
	$checkin = $row[0];
	$checkout = $row[1];

	$query = "SELECT CONCAT_WS(' ',s.nome,s.cognome) FROM infopren AS i JOIN schedine AS s ON s.ID=i.IDcliente WHERE i.IDstr='$IDstruttura' AND i.ID='$IDinfopren'";
	$result = $link2->query($query);
	$row = $result->fetch_row();
	$nome = $row[0];

	if (strpos($data, '/') !== false) {
		$data = implode('-', array_reverse(explode('/', $data)));
	}
	$data_from = date('Y-m-d', $tipo ? $checkout : $checkin);

	$aumento = (($tipo == 0 and $data_from > $data) or ($tipo == 1 and $data_from < $data));

	$lista_servizi = [];
	if (!$aumento) {
		$data_2 = $data_from;
		if ($tipo == 0) {
			$data_1 = date('Y-m-d', strtotime($data) - 86400);
		} else {
			$data_1 = $data;
			$data_2 = date('Y-m-d', strtotime($data_from) + 86400);
		}
		if ($data_1 > $data_2) {
			list($data_1, $data_2) = [$data_2, $data_1];
		}
		$query = "SELECT p2.IDp2,s.servizio,p2.prezzo,p.time,1 FROM prenextra2 AS p2 JOIN prenextra AS p ON p.ID=p2.IDprenextra JOIN servizi AS s ON s.ID=p.extra LEFT JOIN prenextra2_gruppi AS p2g ON p2g.IDprenextra2=p2.IDp2 WHERE p.IDpren='$IDprenotazione' AND p2.IDinfop='$IDinfopren' AND p.IDstruttura='$IDstruttura' AND FROM_UNIXTIME(p.time,'%Y-%m-%d') BETWEEN '$data_1' AND '$data_2' AND p2.pacchetto=0 AND p2g.IDgruppo IS NULL AND s.tipolim IN (4,5)";
		$result = $link2->query($query) or trigger_error($query);
		$lista_servizi = $result->fetch_all();
		$result->free();
	}

	$data_from = $tipo == 1 ? date('Y-m-d', strtotime($data_from) + 86400) : $data_from;
} else {
	//scelta per intera prenotazione

	$query = "SELECT MIN(p.time),MAX(p.time) FROM prenextra2 AS p2 JOIN prenextra AS p ON p.ID=p2.IDprenextra WHERE p.IDpren='$IDprenotazione' AND tipolim=4 AND p.IDstruttura='$IDstruttura'";
	$result = $link2->query($query);
	$row = $result->fetch_row();
	$checkin = $row[0];
	$checkout = $row[1] + 86400;

	if (strpos($data, '/') !== false) {
		$data = implode('-', array_reverse(explode('/', $data)));
	}
	$data_from = date('Y-m-d', $tipo ? $checkout : $checkin);

	$aumento = (($tipo == 0 and $data_from > $data) or ($tipo == 1 and $data_from < $data));

	$lista_servizi = [];
	if (!$aumento) {
		if ($tipo == 0) {
			$data_1 = date('Y-m-d', strtotime($data) - 86400);
		} else {
			$data_1 = $data;
		}
		$data_2 = $data_from;
		if ($data_1 > $data_2) {
			list($data_1, $data_2) = [$data_2, $data_1];
		}
		//conteggio gruppi
		$query = "SELECT p2g.IDgruppo,COUNT(p2.IDp2) FROM prenextra2 AS p2 JOIN prenextra AS p ON p.ID=p2.IDprenextra LEFT JOIN prenextra2_gruppi AS p2g ON p2g.IDprenextra2=p2.IDp2 WHERE p.IDpren='$IDprenotazione' AND p.IDstruttura='$IDstruttura' AND p2.pacchetto=0 GROUP BY p2g.IDgruppo";
		$result = $link2->query($query);
		$row = $result->fetch_all();
		$result->free();
		$gruppi = array_combine(array_column($row, 0), array_column($row, 1));

		$query = "SELECT GROUP_CONCAT(p2.IDp2 SEPARATOR ','),s.servizio,SUM(p2.prezzo),MIN(p.time),COUNT(DISTINCT p2.IDinfop),p2g.IDgruppo,COUNT(p2.IDp2) FROM prenextra2 AS p2 JOIN prenextra AS p ON p.ID=p2.IDprenextra JOIN servizi AS s ON s.ID=p.extra JOIN prenextra2_gruppi AS p2g ON p2g.IDprenextra2=p2.IDp2 WHERE p.IDpren='$IDprenotazione' AND p.IDstruttura='$IDstruttura' AND FROM_UNIXTIME(p.time,'%Y-%m-%d') BETWEEN '$data_1' AND '$data_2' AND p2.pacchetto=0 AND s.tipolim IN (4,5) GROUP BY p2g.IDgruppo";
		$result = $link2->query($query);
		while ($row = $result->fetch_row()) {
			if ($gruppi[$row[5]] == $row[6]) {
				$lista_servizi[] = $row;
			}
		}

		$query = "SELECT GROUP_CONCAT(p2.IDp2 SEPARATOR ','),s.servizio,SUM(p2.prezzo),p.time,COUNT(DISTINCT p2.IDinfop) FROM prenextra2 AS p2 JOIN prenextra AS p ON p.ID=p2.IDprenextra JOIN servizi AS s ON s.ID=p.extra LEFT JOIN prenextra2_gruppi AS p2g ON p2g.IDprenextra2=p2.IDp2 WHERE p.IDpren='$IDprenotazione' AND p.IDstruttura='$IDstruttura' AND FROM_UNIXTIME(p.time,'%Y-%m-%d') BETWEEN '$data_1' AND '$data_2' AND p2.pacchetto=0 AND p2g.IDgruppo IS NULL AND s.tipolim IN (4,5) GROUP BY p2g.IDgruppo,p.extra,p.time";
		$result = $link2->query($query) or trigger_error($query);
		$lista_servizi = array_merge($lista_servizi, $result->fetch_all());
		$result->free();

	}
	$scelte = '';
	if ($aumento) {

		$scelte .= '
		<div style="margin:5px 10px">
 		 		 <label><input class="uk-radio" checked type="radio"  data-val="0" name="metodo-ricalcolo">	Ricalcola Nuova Retta</label>
 		 		 <div style="font-size:12px">Il prezzo verrà ricalcolato solo per i nuovi giorni inseriti</div>
		</div> ';
	}
	$scelte .= '
		<div  style="margin:5px 10px">
 		 		 <label><input class="uk-radio" type="radio" data-val="1" name="metodo-ricalcolo">	Ricalcola Tutte le Rette</label>
 		 		 <div style="font-size:12px">Il prezzo verrà ricalcolato per tutti i giorni della prenotazione</div>
		</div>  ';
	if (!$aumento) {
		$scelte .= '
		<div  style="margin:5px 10px">
 		 		 <label><input checked class="uk-radio" type="radio"  data-val="2" name="metodo-ricalcolo">	Non Ricalcolare</label>
 		 		 <div style="font-size:12px">Il prezzo dei giorni rimanenti non verrà ricalcolato</div>
		</div>  ';
	}
	$scelte .= '
	<div  style="margin:5px 10px">
 		 		 <label><input class="uk-radio" type="radio"  data-val="3" name="metodo-ricalcolo">	Mantieni Stesso Prezzo</label>
 		 		 <div style="font-size:12px">Verrà mantenuto il prezzo attuale, ripartito nei giorni ancora presenti</div>
	</div> ';

}

$elenco_penali = '';
$totale_penali = 0;
foreach ($lista_servizi as $servizio) {
	$totale_penali += $servizio[2];
	$elenco_penali .= '

 <div class=" uk_grid_div div_list_uk" uk-grid  >
        <div class="uk-width-auto lista_grid_nome">' . $servizio[1] . '<br><span style="font-size: 12px; color:#999">' . dataita11($servizio[3]) . ' ,' . $servizio[4] . ' ' . txtpersone($servizio[4]) . '</span></div>
        <div class="uk-width-expand uk-text-right lista_grid_right"  >  ' . $servizio[2] . '€ </div>
</div>
 ';
}
if ($data_from == $data) {
	exit("Errore calcolo data");
}

$div_metodo = '';

if ($scelte ?? false) {
	$div_metodo = '<div class="div_uk_divider_list" style="margin-top:0px !important;">Seleziona metodo di Ricalcolo</div> <div class="metodo-ricalcolo" >' . $scelte . '</div>';
}

if (count($lista_servizi)) {
	$div_metodo .= '
	<div class="penale">
			<div style="font-weight: 400; color:#333333; padding:10px; background-color: #ffffe9; border: 1px solid #bdbd8d; margin-bottom: 10px;">
			L ' . "'" . ' ospite non usufruirà di alcuni servizi.<br>
			</div>


	 <div class=" uk_grid_div div_list_uk" uk-grid  >
	        <div class="uk-width-expand lista_grid_nome" style="padding:0"><label style="display: flex; align-items: center;"><input id="penale__check" type="checkbox"/> Registra penale</label> </div>
	        <div class="uk-width-auto uk-text-right lista_grid_right"  > <input id="penale__totale"   style="width:100px" type="text" value="' . float_format($totale_penali) . '"> </div>
		</div>



		<div id="penale__toggle" style="margin:10px;text-decoration:underline ">Visualizza Servizi</div>
				<div style="  margin:auto; display: none;" class="penale__list">' . $elenco_penali . ' 	</div>

		</div>';
}

$testo = '
<div class="nav navbar_picker_flex" >
 	<div onclick="chiudi_picker()"><i class="fas fa-times icona_chiudi" ></i></div>
 	<div style="margin-top:5px;padding-right:10px">  ' . ($tipo ? 'Checkout' : 'Checkin') . ' ' . dataita11(strtotime($data_from)) . ' <i class="fas fa-arrow-right"></i> ' . dataita11(strtotime($data)) . '  </div>
</div>

<div class="content" style="margin-top:0;height:calc(100% - 50px);">
	<div  id="scelta-ricalcolo">
			<div id="scelta1"> ' . $div_metodo . '
				<div style="margin:10px;text-align:center"><button id="scelta-ricalcolo__done"  class="button_salva_preventivo" style=" width:100%">CONFERMA</button></div>
			</div>
	</div>
</div>';

echo $testo;
?>


<script>
$(function () {
	$("#prosegui").click(function() {
		$("#scelta1").hide();
		$("#scelta2").show();
	});
	$("#penale__toggle").click(function() {

		$(".penale__list").toggle();
	});
	$('[name="metodo-ricalcolo"]').change(function () {

		if ($(this).is(':checked')) {
			if ($(this).data('val') == '3') {
				$('#scelta-ricalcolo .penale').hide();
				$('#penale__check').prop('checked', false);
				return;
			}
		}
		$('#scelta-ricalcolo .penale').show();
	});
});
</script>

