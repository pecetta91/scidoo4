<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../config/connecti.php';
require_once __DIR__ . '/../../config/funzioni.php';
$IDutente = $_SESSION['ID'];
$IDstruttura = $_SESSION['IDstruttura'];

$query = "SELECT ID FROM personale WHERE  IDuser=$IDutente LIMIT 1";
$result = mysqli_query($link2, $query);
$row = mysqli_fetch_row($result);
$IDpersonale = $row['0'];

$timenow = oraadesso($IDstruttura);
$time = $timenow - 86400 * 1.5;

$query = "SELECT ID,tipo,colore FROM tiponotifica";
$result = mysqli_query($link2, $query);
$arrt = [];
$arrc = [];
while ($row = mysqli_fetch_row($result)) {
	$arrt[$row['0']] = $row['1'];
	$arrc[$row['0']] = $row['2'];
}

$txtarr = [];
$query = "SELECT GROUP_CONCAT(nt.ID SEPARATOR ','),nt.IDgroup,nt.tipogroup,nt.tipo,MAX(np.letto),nt.time FROM notifichetxt as nt,notifichepers as np WHERE np.IDpers=$IDpersonale AND nt.time>='$time' AND nt.ID=np.IDnotifica AND tipogroup!='0'  GROUP BY nt.IDgroup,nt.tipogroup,FROM_UNIXTIME(nt.time,'%Y-%m-%d %H')  ORDER BY nt.time DESC LIMIT 35";
$result = mysqli_query($link2, $query);
while ($row = mysqli_fetch_row($result)) {
	$titolo = '';
	$IDgroup = $row['0'];
	$time = $row['5'];

	switch ($row['2']) {
	case 1:
		$titolo = estrainome($row['1']) . '<br><span style="font-size:11px; color:#666; text-transform:capitalize; font-weight:400;">' . estrainomeapp($row['1']) . '</span>';
		break;
	}

	if (strlen($titolo) > 0) {
		$titolo = '<b style="font-size:13px; text-transform:uppercase; font-weight:600;  line-height:20px;">' . $titolo . '</b><br>';
	}

	$letto = $row['4'];

	if (!isset($txtarr[$time])) {$txtarr[$time] = '';}

	$lista_notifiche = '';
	$query2 = "SELECT ID,testo,time FROM notifichetxt WHERE ID IN($IDgroup) ORDER BY time DESC";
	$result2 = mysqli_query($link2, $query2);
	while ($row2 = mysqli_fetch_row($result2)) {
		$lista_notifiche .= '<div><strong>' . date('H:i', $row2['2']) . '</strong> &nbsp; ' . str_replace('/', '', $row2['1']) . '</div>';
	}

	$txtarr[$time] .= '
	<li  style="position:relative" class="apri_notifica"  style="' . ($letto == 0 ? 'background:#e5eef6;' : '') . ' "  >
			<div class="c000 uk-text-bold" style="line-height: 15px;">
				<div>  ' . $titolo . ' 	' . dataita7($time) . '  ' . date('H:i', $time) . '<div style="float:right"><i class="fas fa-ellipsis-h"></i></div>  </div>
				<div style="  font-size: 12px;  display: block; color: #777;  font-weight: 400;"> 	 </div>
			</div>

			<div class=" uk-text-bold c000" style="font-size:13px;line-height:15px;">
				<div style="font-size: 14px;  font-weight: 400;   margin-top: 5px;  vertical-align: bottom; color: #303030; display: inline-block; max-width: 35ch;" class="testo_elissi_standard">  </div>
			</div>
			<div class="uk-accordion-content" style="padding:0"> ' . $lista_notifiche . ' 	</div>
		</div>
	</li>';
}

//Raggruppa per alarm diversi da IDgroup
$query = "SELECT GROUP_CONCAT(nt.ID SEPARATOR ','),nt.IDgroup,nt.tipogroup,nt.tipo,MAX(np.letto),nt.time,nt.titolo FROM notifichetxt as nt,notifichepers as np WHERE np.IDpers=$IDpersonale AND nt.time>='$time' AND nt.ID=np.IDnotifica AND tipogroup='0' GROUP BY nt.tipo,FROM_UNIXTIME(nt.time,'%Y-%m-%d %H')  ORDER BY nt.time DESC LIMIT 35";
$result = mysqli_query($link2, $query);
while ($row = mysqli_fetch_row($result)) {
	$titolo = $row['6'];
	$IDgroup = $row['0'];

	$time = $row['5'];

	if (strlen($titolo) > 0) {
		$titolo = '<b style="font-size:13px; text-transform:uppercase; font-weight:600;  line-height:15px;">' . $titolo . '</b><br>';
	}

	$letto = $row['4'];
	if (!isset($txtarr[$time])) {$txtarr[$time] = '';}

	$lista_notifiche = '';
	$query2 = "SELECT ID,testo FROM notifichetxt WHERE ID IN($IDgroup)";
	$result2 = mysqli_query($link2, $query2);
	while ($row2 = mysqli_fetch_row($result2)) {
		$lista_notifiche .= '<div>' . str_replace('/', '', $row2['1']) . '</div>';
	}

	$txtarr[$time] .= '
	<li  style="position:relative"  style="' . ($letto == 0 ? 'background:#e5eef6;' : '') . ' " class="apri_notifica" >
			<div class="c000 uk-text-bold" style="line-height: 15px;">
				<div>  ' . $titolo . ' 	' . dataita7($time) . '  ' . date('H:i', $time) . '<div style="float:right"><i class="fas fa-ellipsis-h"></i></div>  </div>
				<div style="  font-size: 12px;  display: block; color: #777;  font-weight: 400;"> 	 </div>
			</div>

			<div class=" uk-text-bold c000" style="font-size:13px;line-height:15px;">
				<div style="font-size: 14px;  font-weight: 400;   margin-top: 5px;  vertical-align: bottom; color: #303030; display: inline-block; max-width: 35ch;" class="testo_elissi_standard">  </div>
			</div>
			<div class="uk-accordion-content" style="padding:0"> ' . $lista_notifiche . ' 	</div>
		</div>
	</li>';
}

krsort($txtarr);

$notifiche = '';
foreach ($txtarr as $dato) {
	$notifiche .= $dato;
}

$query = "UPDATE notifichepers SET letto='1' WHERE IDpers=$IDpersonale";
$result = mysqli_query($link2, $query);

$testo = '
<div class="nav navbar_picker_flex" >
 	<div onclick="chiudi_picker()"><i class="fas fa-times icona_chiudi" ></i></div>
 	<div style="margin-top:5px;padding-right:10px">
			<div>Notifiche dalla Struttura</div>
 	</div>

</div>

<div class="content" style="margin-top:0;height:calc(100% - 70px)">
	<div   style="padding:5px;">
        	 <ul class="uk-list lista_dati_default" uk-accordion="toggle:.apri_notifica" style="margin-top:0px;padding: 0">
        	 	' . $notifiche . '
        	 </ul>
        </div>
   </div>';

echo $testo;
?>