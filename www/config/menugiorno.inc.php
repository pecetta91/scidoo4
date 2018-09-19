<?php
$solotxtinto=0;
if(!isset($inc)){
	header('Access-Control-Allow-Origin: *');
	include('../../config/connecti.php');
	include('../../config/funzioni.php');
	include('../../config/funzionilingua.php');
	
	$IDutente=$_SESSION['ID'];
	$IDstruttura=$_SESSION['IDstruttura'];
}

$IDsottotip=$_SESSION['IDsottotip'];
$time=$_SESSION['timecal'];

$data=date('Y-m-d',$time);
$testo='
<div class="titleb">Elenco Piatti disponibili</div>
';

$portate=0;

$query="SELECT MAX(portata) FROM dispgiorno WHERE IDsottotip='$IDsottotip' AND  FROM_UNIXTIME(data,'%Y-%m-%d')= '$data' ";
$result=mysqli_query($link2,$query);
if(mysqli_num_rows($result)>0){
	$row=mysqli_fetch_row($result);
	$portate=$row['0'];
}
if($portate!=0){
	for($i=1;$i<=$portate;$i++){
	
	
		/*$query="SELECT portata FROM dispgiorno WHERE IDsottotip='$IDsottotip' AND  FROM_UNIXTIME(data,'%Y-%m-%d')= '$data' ";
		$result=mysqli_query($link2,$query);
		if(mysqli_num_rows($result)>0){
			$row=mysqli_fetch_row($result);
			$portate=$row['0'];
		}*/
	
		$IDportata=$i;
		$testo.='<div class="row rowlist no-gutter">
				<div class="col-60"><strong>Portata '.$i.'</strong></div>
				<div class="col-40">';
		if($i==$portate){
			$testo.='<a href="#"  onclick="modportate('."'".$time."_".$IDportata."_".$IDsottotip."'".',0,39,10,2)"  class="button color-black "  style="width:100px; font-size:11px; padding:1px; height:20px; line-height:17px; margin-left:10px;">Rimuovi Portata</a>';
		}
		
		$testo.='</div>
				';
		
		
		$query2="SELECT d.ID,s.servizio,d.portata FROM dispgiorno as d,servizi as s WHERE d.IDsottotip='$IDsottotip' AND  FROM_UNIXTIME(d.data,'%Y-%m-%d')= '$data' AND d.IDpiatto=s.ID AND d.portata='$IDportata' ORDER BY portata";
		$result2=mysqli_query($link2,$query2);
		if(mysqli_num_rows($result2)>0){
			while($row2=mysqli_fetch_row($result2)){
				$testo.='
				<div class="col-80" style="border-top:solid 1px #e1e1e1; padding:10px;">'.$row2['1'].'</div>
				<div class="col-20" style="border-top:solid 1px #e1e1e1; padding:10px;"><a href="#"  onclick="modportate('.$row2['0'].',0,38,10,2)"  class="button color-red " style="width:50px; font-size:10px; height:20px; padding:2px; line-height:17px;">Rimuovi</a></div>
				';
			}
		}
			

					
		$testo.='
			<div class="col-100" align="center"><br/><a href="#" onclick="nuovopiatto('.$i.')"  class="button button-fill " style="width:120px; font-size:11px; margin-left:10px;">Aggiungi Piatto</a></div>
			
			</div><br>';
		
		
		
	}
}else{
	$testo.='<br>
	<div style="color:#1f28a0;font-weight:600;">Aggiungi la portata ed i piatti disponibili</div>
	<br>
	';
}

$testo.='
<br>
<br>
<a href="#" class="button color-black " style="width:80%;line-height:38px; height:40px; margin:auto;"  onclick="modportate('.$time.','.$IDsottotip.',36,10,2)" >+ Aggiungi Portata</a><br>
</div>
';



echo $testo;	
				 
?>	

						  
						  
