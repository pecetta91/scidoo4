<?php
//header('Access-Control-Allow-Origin: *');
require_once '../../config/connecti.php';
require_once '../../config/funzioni.php';

$IDutente = $_SESSION['ID'];
$IDstruttura = $_SESSION['IDstruttura'];

$time = strip_tags($_POST['time']);

$data = date('Y-m-d', $time);
$cont = '';

$query = "SELECT  p.IDpren FROM prenextra as p  WHERE FROM_UNIXTIME(p.time,'%Y-%m-%d')='$data' AND p.IDstruttura='$IDstruttura' AND p.modi>='0' AND  p.esclusivo='1' GROUP BY p.IDpren";
$result = mysqli_query($link2, $query);
if (mysqli_num_rows($result) > 0) {
	$cont .= '<div class="div_uk_divider_list"  style="  margin-left: 0 !important;margin-top:0px!important;   padding: 0;padding-bottom:15px;" >Prenotazioni con servizi esclusivi</div>';
	//$cont .= '<ul uk-accordion="multiple: true">';
	while ($row = mysqli_fetch_row($result)) {
		$IDprenotazione = $row['0'];

		$nome = estrainome($IDprenotazione);

		$cont .= ' <div class="uk_grid_div div_list_uk " uk-grid onclick="chiudi_picker();navigation(6,{IDprenotazione:' . $IDprenotazione . '});">
			    <div class="uk-width-2-3 lista_grid_nome uk-first-column" style="padding:0!Important;">	 ' . $nome . '   </div>
			    <div class="uk-width-expand uk-text-right lista_grid_right">     <span uk-icon="chevron-right"  > </span></div>
			</div>';

	}
	//$cont .= '</ul>';
}

$query2 = "SELECT titolo,descr,time FROM note WHERE FROM_UNIXTIME(time,'%Y-%m-%d')='$data' AND IDstruttura='$IDstruttura' AND (titolo!='' OR descr!='') ORDER BY time";
$result2 = mysqli_query($link2, $query2);
if (mysqli_num_rows($result2) > 0) {
	$cont .= '<div class="div_uk_divider_list"  style="  margin-left: 0 !important;margin-top:0px!important;   padding: 0;" >Note del Giorno</div>';
	while ($row = mysqli_fetch_row($result2)) {

		$cont .= '
			<div class="div_list_uk uk_grid_div" uk-grid   >
			    <div class="uk-width-expand lista_grid_nome " >' . $row['0'] . '</div>

			</div>

			<div class="uk-text-emphasis">' . stripslashes($row['1']) . '</div> ';
	}
}
/*
$query2="SELECT titolo,descr,time FROM note WHERE FROM_UNIXTIME(time,'%Y-%m-%d')='$data' AND IDstruttura='$IDstruttura' AND (titolo!='' OR descr!='') ORDER BY time";
$result2=mysqli_query($link2,$query2);
if(mysqli_num_rows($result2)>0){

$testo.='<div class="content-block-title titleb">Note del Giorno</div>';

while($row=mysqli_fetch_row($result2)){

$tit='';
$testonota='';
if($row['0']!=''){$tit='<b>'.$row['0'].'</b>';}
$testonota='<span style="font-size:13px;color:#555;">'.stripslashes($row['1']).'</span>';

$testo.='
<div class="row rowlist no-gutter"  >
<div class="col-100">'.$tit.'</div>

<div class="col-100">'.$testonota.'</div>

</div>';

}
//$testo.='</ul></div>';
}*/

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
