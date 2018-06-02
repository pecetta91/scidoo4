<?php

header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');
include('../../config/funzionilingua.php');

$IDstruttura=$_SESSION['IDstruttura'];
$IDprenextra=$_POST['IDprenextra'];
$agg=$_POST['agg'];


$querym="SELECT sottotip,extra,time FROM prenextra WHERE IDstruttura='$IDstruttura' AND ID='$IDprenextra' ";

$resultm=mysqli_query($link2,$querym);
$rowm=mysqli_fetch_row($resultm);
		$IDsottotip=$rowm['0'];
        $IDserv=$rowm['1'];
        $timeprenextra=$rowm['2'];

list($yy, $mm, $dd) = explode("-",date('Y-m-d',$timeprenextra));
$time0=mktime(0, 0, 0, $mm, $dd, $yy);



$testo='<input type="hidden" value="'.$timeprenextra.'" id="time">
<input type="hidden" value="'.$IDsottotip.'" id="idosottotip">
<input type="hidden" value="'.$agg.'" id="agg">';

	$qadd='';
	$query="SELECT GROUP_CONCAT(IDorarios SEPARATOR ',') FROM assocorario WHERE IDserv='$IDserv' GROUP BY IDserv";
	$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
		$row = mysqli_fetch_row($result);
		$grora=$row['0'];
		$qadd=" AND ID IN ($grora)";	
	}

	$query2="SELECT MIN(orarioi),MAX(orariof) FROM orarisotto WHERE IDsotto='$IDsottotip'  $qadd LIMIT 1";
	$result2=mysqli_query($link2,$query2);
    $row2=mysqli_fetch_row($result2);
	$orarioin=$row2['0'];
    $orariofin=$row2['1'];


$oraini=$time0+$orarioin;
$orariofin=$time0+$orariofin;

for($oraini;$oraini<$orariofin;$oraini+=900){
	$check='';
	if(date('H:i',$oraini)==date('H:i',$timeprenextra)){
		$check=' checked="checked" ';
	}
	//'.$oraini.'       '.date('H:i',$oraini).'
	$testo.='
	<li onclick="modprenextra('.$IDprenextra.','."'".$oraini."'".',13,9,21);">
      <label class="label-radio item-content" >
	  <input type="radio" name="my-radio" '.$check.' value="'.$orarioin.'" >
        <div class="item-inner">
          <div class="item-title">'.date('H:i',$oraini).'</div>
        </div>
      </label>
    </li>';
}

?>
<div class="picker-modal smart-select-picker ">
      <div class="toolbar">
        <div class="toolbar-inner">
          <div class="left"></div>
          <div class="right"><a href="#" class="close-picker">Chiudi</a></div>
        </div>
      </div>
      <div class="picker-modal-inner">
		  <div class="page-content">
			<div class="list-block" style="margin-top:0px;">
				<ul>
					<?php 
						echo $testo;
					?>
			   </ul>
		   </div>
		  </div>
      </div>
</div>

