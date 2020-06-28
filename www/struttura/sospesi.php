<?php
//header('Access-Control-Allow-Origin: *');
require_once '../../config/connecti.php';
require_once '../../config/funzioni.php';

$IDutente = $_SESSION['ID'];
$IDstruttura = $_SESSION['IDstruttura'];

$IDsottotip = ($_POST['IDsottotip']);
$time = strip_tags($_POST['time']);

$cont = '';
$groupid = getprenot($time, $IDstruttura);

$query = "SELECT  p.IDpren FROM prenextra as p,prenextra2 as p2,servizi as s,prenotazioni as pr  WHERE p.sottotip='$IDsottotip'  AND p.IDpren IN($groupid) AND (p.modi='0' OR p.sala='0') AND p.ID=p2.IDprenextra AND s.ID=p.extra  AND pr.IDv=p.IDpren  GROUP BY p.IDpren ORDER BY pr.gg";
$qadd = "SUM(p2.qta)";
$q2add = "p.sottotip='$IDsottotip'";

if ($IDsottotip == 2) {
	$query = "SELECT  p.IDpren FROM prenextra as p,prenextra2 as p2,servizi as s,prenotazioni as pr WHERE p.IDtipo='2'  AND p.IDpren IN($groupid) AND (p.modi='0' OR p.sala='0') AND p.ID=p2.IDprenextra AND s.ID=p.extra AND pr.IDv=p.IDpren  GROUP BY p.IDpren ORDER BY pr.gg";

	$qadd = "1";
	$q2add = "p.IDtipo='2'";

}

$result = mysqli_query($link2, $query);
if (mysqli_num_rows($result) > 0) {
	$cont .= '<ul uk-accordion="multiple: true">';
	while ($row = mysqli_fetch_row($result)) {
		$IDpren = $row['0'];
		$query3 = "SELECT ID,app FROM prenotazion WHERE  IDstruttura='$IDstruttura' AND  IDv='$IDpren' ";
		$result3 = mysqli_query($link2, $query3);
		if (mysqli_num_rows($result) > 0) {

		}

		$cont .= '

   		 <li>
   		 	<div class="uk-accordion-title" style="color:#2542d9;font-size:17px;font-weight:600;">' . estrainome($IDpren) . '<br><span style="font-size: 11px;" class="uk-text-lighter uk-text-muted">' . estrainomeapp($IDpren) . '</span></div>
   		 		<div class="uk-accordion-content"> ';

		$query2 = "SELECT p.ID,p.time,s.servizio,$qadd,p.esclusivo FROM prenextra as p,prenextra2 as p2,servizi as s WHERE $q2add  AND p.IDpren='$IDpren' AND (p.modi='0' OR p.sala='0')  AND p.ID=p2.IDprenextra AND s.ID=p.extra GROUP BY p.ID ORDER BY p.IDpren";

		$result2 = mysqli_query($link2, $query2);
		if (mysqli_num_rows($result2) > 0) {
			//modificaserv(' . $row2['0'] . ',1,0,1,0);
			while ($row2 = mysqli_fetch_row($result2)) {
				$cont .= '

					<div class="uk_grid_div" uk-grid onclick="navigation(11,[' . $row2['0'] . ',1,0],0,0);crea_picker();">
					  <div class="uk-width-expand uk-text-truncate lista_grid_nome">' . $row2['2'] . '</div>
					  <div class="uk-width-auto uk-text-right lista_grid_right">' . $row2['3'] . ' <span uk-icon="icon:user;ratio:0.7" >  </div>
					  <div class="uk-width-auto uk-text-right lista_grid_right"><span uk-icon="chevron-right"  >  </div>
					</div> ';
			}

		}
		$cont .= '</div></li>';
	}

	$cont .= '</ul>';
} else {
	$cont .= '<div class="uk-heading-divider uk-margin"><strong>Non ci sono Prenotazioni  Sospese</strong>  </div>';
}

$testo = '

<div class="nav navbar_picker_flex" >
 	<div onclick="chiudi_picker()"><i class="fas fa-times icona_chiudi" ></i></div>
</div>

<div class="content" style="margin-top:0;height:calc(100% - 70px)">
	<div   style="padding:5px;">
        	' . $cont . '
        </div>
   </div>

';

echo $testo;
