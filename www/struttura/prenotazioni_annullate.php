<?php
//header('Access-Control-Allow-Origin: *');
require_once '../../config/connecti.php';
require_once '../../config/funzioni.php';

$IDutente = $_SESSION['ID'];
$IDstruttura = $_SESSION['IDstruttura'];

$time = strip_tags($_POST['time']);

$data = date('Y-m-d', $time);
$cont = '';

$query = "SELECT p.ID,p.IDv,p.time,p.gg,COUNT(DISTINCT i.ID) FROM prenotazioni as p
JOIN infopren as i ON i.IDpren=p.IDv AND i.pers='1'
WHERE p.IDstruttura='$IDstruttura' AND p.app='0' AND p.stato='-1' AND FROM_UNIXTIME(p.time,'%Y-%m-%d')= '$data'";

$result = mysqli_query($link2, $query);
while ($row = mysqli_fetch_row($result)) {

	$IDprenotazione = $row['1'];
	$gg = $row['3'];
	$time = $row['2'];
	$num = $row['4'];

	$cont .= '
		<div class="div_list_uk uk_grid_div" uk-grid  onclick="chiudi_picker();navigation(6,{IDprenotazione:' . $IDprenotazione . '});">
			    <div class="uk-width-2-3 lista_grid_nome " style="padding-right:0;" > ' . estrainome($IDprenotazione) . '<br><span class="uk-text-muted uk-text-small"  >N.' . $gg . ' Notti - ' . $num . ' Persone</span></div>
			    <div class="uk-width-expand uk-text-right lista_grid_right"  >' . date('H:i', $time) . '  <span uk-icon="chevron-right" ></span></div>
			</div>';
}

$testo = '<div class="nav navbar_picker_flex" >
 	<div onclick="chiudi_picker()"><i class="fas fa-times icona_chiudi" ></i></div>
</div>

<div class="content" style="margin-top:0;height:calc(100% - 70px)">
	<div   style="padding:5px;">
        	' . $cont . '
        </div>
   </div>';

echo $testo;

?>
