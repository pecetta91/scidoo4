<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';

$IDstruttura = $_SESSION['IDstruttura'];

$arr_dati = (isset($_POST['arr_dati']) ? $_POST['arr_dati'] : []);
$data = $arr_dati['data'] ?? 0;
$IDsottotip = $arr_dati['IDsotto'];
$tipo = $arr_dati['tipo'] ?? 0;

if ($data == 0) {
	$time = (isset($_SESSION['tempo_benessere']) ? $_SESSION['tempo_benessere'] : time_struttura());
} else {
	$time = strtotime(convertiData($data));
}
$time0 = time0($time);
$_SESSION['tempo_benessere'] = $time;

$indice = (($_SESSION['visualizza_personale_benessere'] ?? 0) == 0 ? 'sale' : 'personale');

$time_fine = $time0 + 86400;
$servizi_presenti = [];
$servizi_sospesi['html'] = '';
$servizi_arrivo['html'] = '';

$prenotazioni_arrivo = get_prenotazioni(['0' => ['min_checkin' => $time0, 'max_checkin' => $time_fine]])['dati'];

$lista_servizi = get_servizi_presenti(['0' => ['time_inizio' => $time0, 'time_fine' => $time_fine, 'IDtipo' => 2]]);
//print_r($lista_servizi);
if (!empty($lista_servizi)) {
	foreach ($lista_servizi as $IDprenextra => $dati) {
		$modi = $dati['modi'];
		$sala = $dati['sala'];
		$time = $dati['time'];
		$durata = $dati['durata'];
		$IDpersonale = $dati['IDpersonale'];
		$IDpren = $dati['IDriferimento'];
		if ($modi == 1) {

			switch ($indice) {
			case 'sale':
				$IDriferimento = $sala;
				break;
			case 'personale':
				$IDriferimento = $IDpersonale;
				break;
			}

			$altezza = round(30 * ($durata / 15)) - 5;
			$servizi_presenti[$time][$IDriferimento] = '

			<div  class="div_servizio_inserito" style="background:' . (isset($lista_personale[$IDpersonale]) ? '#' . $lista_personale[$IDpersonale]['colore'] : '#D10073') . ';height:' . $altezza . 'px">
				<div >' . $dati['ospite'] . ' 	</div>
				<div class="testo_elissi_standard" > ' . ($dati['alloggio'] != '' ? $dati['alloggio'] : 'Senza Soggiorno') . ' -' . $dati['servizio'] . '</div>
			</div>';

		} else {
			$servizi_sospesi['html'] .= '	<li  ">
					<div class="c000 uk-text-bold" style="line-height: 15px;">
						<div>' . $dati['ospite'] . '  <div style="float:right;"><i class="fas fa-ellipsis-h"></i></div> </div>
						<div style="  font-size: 12px;  display: block; color: #777;  font-weight: 400;"> ' . ($dati['alloggio'] != '' ? $dati['alloggio'] : 'Senza Soggiorno') . '</div>
					</div>

					<div style="font-size: 11px;  max-width: 30ch; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;color:#303030; ">' . $dati['servizio'] . ' - <i class="fas fa-user"></i> ' . $dati['persone'] . '</div>
				</li>';
		}

		if (isset($prenotazioni_arrivo[$IDpren])) {

			$servizi_arrivo['html'] .= '
				<li>
					<div class="c000 uk-text-bold" style="line-height: 15px;">
						<div>' . $prenotazioni_arrivo[$IDpren]['nome_cliente'] . '  <div style="float:right;"><i class="fas fa-ellipsis-h"></i></div> </div>
						<div style="  font-size: 12px;  display: block; color: #777;  font-weight: 400;"> ' . $dati['alloggio'] . '</div>
					</div>

					<div style="font-size: 11px;  max-width: 30ch; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;color:#303030; ">' . $prenotazioni_arrivo[$IDpren]['persone'] . '	<i class="fas fa-user-alt"></i> ' . $prenotazioni_arrivo[$IDpren]['notti'] . '	<i class="fas fa-moon"></i></div>
				</li>';

			unset($prenotazioni_arrivo[$IDpren]);
		}

	}
}

switch ($tipo) {
case 0:

	$lista_personale = [];
	$query = "SELECT p.ID,p.nome,p.color
	FROM personale as p
	JOIN mansionipers as mp ON mp.IDpers=p.ID
	JOIN mansioni as m ON m.tipo='2' AND mp.mansione=m.ID
	WHERE p.IDstr='$IDstruttura' AND p.attivo='1' ORDER BY p.ID";
	$result = mysqli_query($link2, $query);
	while ($row = mysqli_fetch_row($result)) {
		$IDpersona = $row['0'];
		$lista_personale[$IDpersona] = ['nome' => $row['1'], 'colore' => $row['2']];
	}

	$lista_sale = [];
	$query = "SELECT s.ID,s.nome FROM sale as s
	JOIN  saleassoc as sc ON sc.ID=s.ID
	JOIN sottotipologie as st ON  st.ID=sc.IDsotto
	WHERE  s.IDstr='$IDstruttura' AND st.IDmain='2' GROUP BY s.ID";
	$result = mysqli_query($link2, $query);
	while ($row = mysqli_fetch_row($result)) {
		$lista_sale[$row['0']]['nome'] = $row['1'];
	}

	$riferimenti['sale'] = $lista_sale;
	$riferimenti['personale'] = $lista_personale;

	$query = "SELECT MIN(o.orarioi),MAX(o.orariof) FROM orarisotto as o
	JOIN sottotipologie as s  ON s.ID=o.IDsotto WHERE s.IDmain='2' AND s.IDstr='$IDstruttura'";
	$result = mysqli_query($link2, $query);
	$row = mysqli_fetch_row($result);
	$min = $row['0'];
	$max = $row['1'];
	$min = $min - ($min % 3600);
	$max = $max + ($max % 3600);

	$time_trattamenti_inizio = $time0 + $min;
	$time_trattamenti_fine = $time0 + $max;

	$bodytxt = '';
	$side = '';
	for ($time_centro = $time_trattamenti_inizio; $time_centro <= $time_trattamenti_fine; $time_centro += 900) {
		$side .= '<div class="fascia">  ' . date('H:i', $time_centro) . '  </div>';
		$riga_txt = '';
		foreach ($riferimenti[$indice] as $key => $val) {
			$riga_txt .= '<div class="cont_body ">' . (isset($servizi_presenti[$time_centro][$key]) ? $servizi_presenti[$time_centro][$key] : '') . '</div>';
		}
		$bodytxt .= '<div class="riga_txt" >' . $riga_txt . ' </div>';
	}

	$header = '';

	foreach ($riferimenti[$indice] as $key => $val) {
		$header .= '<div class="data_header"> <div>' . $val['nome'] . '</div>  </div>';

	}

	$testo = '


	<div id="ingressi_benessere" style="-webkit-overflow-scrolling:touch;overscroll-behavior: none;position:relative;">

	<div  class="header">
		<div  style="width: max-content;display:flex;height:100%"> ' . $header . ' </div>
	</div>

	<div  class="side">
		' . $side . '
	</div>


	<div  class="body" >
	' . $bodytxt . '
	</div>


</div>';

	break;

case 1:
	$testo = ' <ul class="uk-list lista_dati_default" style="padding: 0">' . $servizi_sospesi['html'] . '</ul>';
	break;
case 2:
	$testo = ' <ul class="uk-list lista_dati_default" style="padding: 0">' . $servizi_arrivo['html'] . '</ul>';
	break;

}

echo '<div style="margin-top:40px;">
	<input type="hidden" id="time" value="' . $time0 . '">
	<input type="hidden" id="IDsottotip" value="' . $IDsottotip . '">
	<input type="hidden" id="tipo" value="' . $tipo . '">

' . $testo . '</div>';

?>


<script>
$('#ingressi_benessere .body').scroll(function() {

$('#ingressi_benessere .side').scrollTop($(this).scrollTop());

$('#ingressi_benessere .header').scrollLeft($(this).scrollLeft());
});

var h=parseInt($('body').innerHeight() - 95);

$('#ingressi_benessere').css('height',h+'px');

</script>


<style>

/* griglia flex ingressi benessere*/
#ingressi_benessere .header {  grid-area: header;  overflow: hidden;}
#ingressi_benessere .body { grid-area: body;  overflow: auto;}



#ingressi_benessere {  width: 100%;  height: 86vh;  display: grid;  grid-template-rows: 40px auto;   grid-template-columns: 80px auto;  grid-template-areas: ". header"  "side body";}

#ingressi_benessere .header{background: #f9f8f9;border-right: 1px solid #e1e1e1 ;border-top:1px solid #e1e1e1}
#ingressi_benessere .header .data_header{    border-bottom: 1px solid #e1e1e1;   width: 130px;  background: #f9f8f9;
    border-right: 1px solid #e1e1e1;
    border-top: 1px solid #e1e1e1;}
#ingressi_benessere .header .data_header div{margin-top: 5px; text-align: center; font-size: 16px; color: #000; line-height: 15px; }
#ingressi_benessere .header .data_header.oggi{ }
#ingressi_benessere .header .data_header.oggi div{color: #30A947;}

#ingressi_benessere .side {  grid-area: side;overflow: hidden;border-top:1px solid #e1e1e1;border-bottom:1px solid #e1e1e1;}
#ingressi_benessere .side .fascia {  background: #fff;color:#333;padding: 0px 5px;height: 30px;
    border-bottom: 1px solid #e1e1e1;
    border-right: 1px solid #e1e1e1;
    position: relative;}


#ingressi_benessere .body  .riga_txt{display:flex;margin:0;padding: 0;   width: max-content;}
#ingressi_benessere .body .riga_txt .cont_body {border-right:1px solid #e1e1e1;border-bottom:1px solid #e1e1e1;height:30px;width:130px;position:relative;font-size: 12px;background: #fff}


.div_servizio_inserito{
    position: absolute;
    padding: 2px 5px;
    z-index: 2;
    margin: 2px 5px;
    width: 120px;
    border-radius: 3px;
    color: #fff;
}



</style>
