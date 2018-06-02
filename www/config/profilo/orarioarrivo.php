<?php 
	include('../../../config/connecti.php');
	include('../../../config/funzioni.php');
	include('../../../config/funzionilingua.php');



$IDpren=$_SESSION['IDstrpren'];



$query="SELECT gg,IDstruttura,time FROM prenotazioni WHERE IDv='$IDpren' LIMIT 1";

$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$gg=$row['0'];
$IDstr=$row['1'];
$time=$row['2'];

list($yy, $mm, $dd) = explode("-", date('Y-m-d',$time));
$time0=mktime(0, 0, 0, $mm, $dd, $yy);


$testo='<input type="hidden" value="'.$IDpren.'">
<input type="hidden" value="'.$time.'">';
if($gg>0){
	// checkin oraf
	$query="SELECT checkin,oraf FROM strutture WHERE ID='$IDstr' LIMIT 1";
	$result=mysqli_query($link2,$query);
	$row=mysqli_fetch_row($result);
	$checkin=$row['0'];
	$checkout=$row['1'];
}
else{
	//orai oraf
	$query="SELECT orai,oraf FROM strutture WHERE ID='$IDstr' LIMIT 1";
	$result=mysqli_query($link2,$query);
	$row=mysqli_fetch_row($result);
	$checkin=$row['0'];
	$checkout=$row['1'];
}


$oraini=$time0+$checkin;
$checkout=$time0+$checkout;

for($oraini;$oraini<$checkout;$oraini+=1800){
	$check='';
	if(date('H:i',$oraini)==date('H:i',$time)){
		$check=' checked="checked" ';
	}
	$testo.='
	<li onclick="modprofilo('.$IDpren.','."'ora".$oraini."'".',41,0,8);chiudimodal();">
      <label class="label-radio item-content" >
	  <input type="radio" id="ora'.$oraini.'" name="my-radio" '.$check.' value="'.$oraini.'" >
        <div class="item-inner">
          <div class="item-title">'.date('H:i',$oraini).'</div>
        </div>
      </label>
    </li>';
}





?>
<div class="picker-modal smart-select-picker" id="popoverord" >
      <div class="toolbar">
        <div class="toolbar-inner">
          <div class="left">Seleziona l'ora per il checkin</div>
          <div class="right"><a href="#" class="close-picker">Chiudi</a></div>
        </div>
      </div>
      <div class="picker-modal-inner">
		  <div class="page-content bcw" >
			<div class="list-block mt0" >
				<ul>
					<?php echo $testo; ?>
			   </ul>
		   </div>
		  </div>
      </div>
</div>
