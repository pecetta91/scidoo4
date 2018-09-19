<?php

if(!isset($inc)){
	header('Access-Control-Allow-Origin: *');
	include('../../config/connecti.php');
	include('../../config/funzioni.php');
	include('../../config/funzionilingua.php');

	
	
	$testo='';
	$ID=$_SESSION['IDprensposta'];
	
	$query="SELECT time,gg,checkout,IDstruttura FROM prenotazioni WHERE IDv='$ID' LIMIT 1";
	$result=mysqli_query($link2,$query);
	$row=mysqli_fetch_row($result);
	$time=$row['0'];
	$notti=$row['1'];
	$checkout=$row['2'];
	$IDutente=$_SESSION['ID'];
	$IDstruttura=$_SESSION['IDstruttura'];
	
	
	$data=$_GET['dato0'];
	list($yy, $mm, $dd) = explode("-",$data);
	$time = mktime(0, 0, 0, $mm, $dd, $yy);
	$time+=7200;
	$checkout=$time+86400*$notti;
	
	
	
}

$qadd='';
if($notti==0){
	$qadd="AND attivo='2'";
	$app='0';
}else{
	$qadd="AND attivo='1'";
	$app=getdisponibilita($time,$checkout,$IDstruttura,0,0,0,0);
}




$query="SELECT ID,nome FROM appartamenti WHERE IDstruttura='$IDstruttura' $qadd AND ID NOT IN($app)";
$result=mysqli_query($link2,$query);
   $testo.='<div class="list-block"><ul>';

if(mysqli_num_rows($result)>0){

	while($row=mysqli_fetch_row($result)){
		/*$testo.='
		<li>
		  <label class="label-radio item-content">
			<input type="radio" name="alloggio" value="'.$row['0'].'">
			<div class="item-inner">
			  <div class="item-title">'.$row['1'].'</div>
			</div>
		  </label>
		</li>
		
		';*/
		$testo.='
		<li>
		  <label class="label-radio item-content">
			<div class="item-inner">
			  <div class="item-title" style="font-size:17px;"><strong>'.$row['1'].'</strong></div>
			  <div class="item-after"><a href="#" class="button button-round button-fill color-green " onclick="spostapren('.$row['0'].')">Sposta</a></div>
			</div>
		  </label>
		</li>
		
		';
		
		
		
	}

}else{
	$testo.='
		<li>
		  <label class="label-radio item-content">
			
			<div class="item-inner">
			  <div class="item-title">Nessun Alloggio Disponibile</div>
			</div>
		  </label>
		</li>
		
		';
	
}
$testo.='</ul></div>';







if(!isset($inc)){
	echo $testo;
}

?>


