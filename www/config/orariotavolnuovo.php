<?php

header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');

$IDstruttura=$_SESSION['IDstruttura'];
$IDsottotip=$_POST['idsottotip'];
$time=$_POST['time'];

list($yy, $mm, $dd) = explode("-",date('Y-m-d',$time));
$time0=mktime(0, 0, 0, $mm, $dd, $yy);

$testo='
<input type="hidden" value="'.$IDsottotip.'" id="idosottotip">
<input type="hidden" value="'.$time.'" id="time">
';


	$query2="SELECT MIN(orarioi),MAX(orariof) FROM orarisotto WHERE IDsotto='$IDsottotip' AND IDstr='$IDstruttura' ";  
	$result2=mysqli_query($link2,$query2);
    $row2=mysqli_fetch_row($result2);
	$orarioin=$row2['0'];
    $orariofin=$row2['1'];


$oraini=$time0+$orarioin;
$orariofin=$time0+$orariofin;
$i=0;
for($oraini;$oraini<$orariofin;$oraini+=900){
	$orariodef=date('H:i',$oraini);
	$sel='';
	if($i==0){
		$i++;
		$sel='selected="selected"';
	}
	$testo.='
	
	<li class="item-link item-content" onclick="cambiaorariopren('."'".$orariodef."'".','.$oraini.');chiudimodal();" >
          <div class="item-inner">
					<div class="item-title" value="'.$orarioin.'" '.$sel.'>'.date('H:i',$oraini).'</div>
		  </div>
        </li>';
}

?>
<div class="picker-modal smart-select-picker" id="popoverord" >
      <div class="toolbar">
        <div class="toolbar-inner">
          <div class="left"></div>
          <div class="right"><a href="#" class="close-picker">Chiudi</a></div>
        </div>
      </div>
      <div class="picker-modal-inner">
		  <div class="page-content" style="background-color: white">
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

