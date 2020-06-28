<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../config/connecti.php';
require_once __DIR__ . '/../../config/funzioni.php';

$IDstruttura = $_SESSION['IDstruttura'];

$time = strip_tags($_POST['time']);

$time0 = time0($time);
$time0fine = $time0 + 86400;

$stati_prenotazione = get_stati_prenotazioni();
$stati_prenotazioni[-1] = ['classe' => '', 'stato' => 'Annullata', 'colore' => 'e54c5a'];

//$lista_prenotazioni=get_prenotazioni([['min_']],null,[],$IDstruttura)['dati'];

$prenotazioni_giornaliere = '';
$query = "SELECT p.ID,p.IDv,p.time,COUNT(i.ID),p.stato
FROM prenotazioni as p
JOIN statopren as s ON s.IDstato=p.stato
JOIN infopren as i ON i.IDpren=p.IDv AND i.pers='1'
WHERE p.IDstruttura='$IDstruttura' AND p.gg='0' AND p.time>='$time0' AND p.time<'$time0fine' AND p.stato=s.IDstato
GROUP BY p.IDv ORDER BY p.time ";
$result = mysqli_query($link2, $query);
while ($row = mysqli_fetch_row($result)) {

	$nome = estrainome($row[1]);
	$n_persone = $row[3];

	$prenotazioni_giornaliere .= '
		<div class="uk_grid_div div_list_uk " uk-grid onclick="chiudi_picker();navigation(6,{IDprenotazione:' . $row[1] . '});">
		    <div class="uk-width-2-3 lista_grid_nome uk-first-column" style="padding:0!Important;">
		    <div class=" pren_giornaliere_notifica" style="background:#' . $stati_prenotazione[$row[4]]['colore'] . '"></div> ' . $nome . '  <br><span class="uk-text-muted uk-text-small">' . $n_persone . ' ' . txtpersone($n_persone) . '</span></div>
		    <div class="uk-width-expand uk-text-right lista_grid_right"> ' . date('H:i', $row[2]) . '   <span uk-icon="chevron-right"  > </span></div>
		</div>


';
}

/*

<button onclick="nosog(' . $row['2'] . ')" class="shortcut large ' . $row['4'] . '" style="line-height:24px; font-weight:100;" >

<span style="font-size:13px;">ID.' . $row['0'] . '</span><br>
<span style="font-size:27px;">' . date('H:i', $row['3']) . '</span><hr>
<span style="font-weight:400;line-height:16px;">' . $nome . '</span>
<br><span style="font-size:13px;">' . $num . ' Persone</span></button>
 */

/*
$cont = ' ';
$query = "SELECT p.ID,p.stato,p.IDv,p.time,COUNT(DISTINCT i.ID) FROM prenotazioni as p JOIN infopren as i ON i.IDpren=p.IDv AND i.pers='1' WHERE p.IDstruttura='$IDstruttura' AND p.app='$IDapp' AND FROM_UNIXTIME(p.time,'%Y-%m-%d')= '$data'";
$result = mysqli_query($link2, $query);
while ($row = mysqli_fetch_row($result)) {
$stato = $row['1'];
switch ($stato) {
case 0:
$stato = 'incerto';
break;
case 1:
$stato = 'base';
break;
case 2:
$stato = 'acconto';
break;
case 3:
$stato = 'arrivato';
break;
case 4:
$stato = 'saldo';
break;
case 5:
$stato = 'credit';
break;
case 6:
$stato = 'manuale';
break;
}

$IDprenotazione = $row['2'];
$nome = estrainome($row['2']);
$num = $row['4'];

$cont .= '<div class="uk_grid_div div_list_uk " uk-grid onclick="chiudi_picker();navigation(6,{IDprenotazione:' . $IDprenotazione . '},2,0);">
<div class="uk-width-2-3 lista_grid_nome uk-first-column" style="padding:0!Important;">	<div class="' . $stato . ' pren_giornaliere_notifica"></div> ' . estrainome($IDprenotazione) . '  <br><span class="uk-text-muted uk-text-small">' . $num . ' ' . txtpersone($num) . '</span></div>
<div class="uk-width-expand uk-text-right lista_grid_right"> ' . date('H:i', $row['3']) . '   <span uk-icon="chevron-right"  > </span></div>
</div> ';

}
 */
$testo = '

<div class="nav navbar_picker_flex" >
 	<div onclick="chiudi_picker()"><i class="fas fa-times icona_chiudi" ></i></div>
</div>

<div class="content" style="margin-top:0;height:calc(100% - 70px)">
	<div   style="padding:5px;">
        	' . $prenotazioni_giornaliere . '
        </div>
   </div> ';

echo $testo;
