<?php 

if(!isset($inc)){
header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');


	$IDutente=$_SESSION['ID'];
	$IDstruttura=$_SESSION['IDstruttura'];
}
	$query="SELECT contratto FROM contratti WHERE IDstruttura='$IDstruttura' LIMIT 1";
	$result=mysqli_query($link2,$query);
	$row=mysqli_fetch_row($result);
	$contratto=$row['0'];
	$_SESSION['contratto']=$contratto;
	
	$query="SELECT IDcliente,nome FROM strutture WHERE ID='$IDstruttura' LIMIT 1";
	$result=mysqli_query($link2,$query);
	$row=mysqli_fetch_row($result);
	$IDmainuser=$row['0'];
	$nomestr=$row['1'];
	$IDpos=1;

	$query2="SELECT ID FROM personale WHERE  IDuser='$IDutente' LIMIT 1";
	$result2=mysqli_query($link2,$query2);
	$row2=mysqli_fetch_row($result2);
	$IDpers=$row2['0'];


	$time=time()-86400*3;
	$numappunti=0;
		
		$testo4='
		<div class="list-block  media-list" >
      <ul>
		
		';
	
		$qadd='';
		/*if(strlen($_SESSION['filtraappunti'])>0){
			$qadd=' AND UPPER("'.$_SESSION['filtraappunti'].'")=UPPER(a.argomento) ';
		}*/
		
	
		$not='0';
		$query="(SELECT a.ID,a.testo,a.note,a.time,a.fatto,a.IDcliente,a.argomento FROM appunti as a,appuntidest as ad WHERE a.IDstr='$IDstruttura' AND a.ID=ad.IDappunto AND ad.IDdest='$IDutente' AND a.fatto='0' $qadd) UNION (SELECT ID,testo,note,time,fatto,IDcliente,argomento FROM appunti  WHERE IDstr='$IDstruttura' AND IDcliente='$IDutente'  AND fatto='0' )  ORDER BY time DESC LIMIT 14 ";
		$result=mysqli_query($link2,$query);
		if(mysqli_num_rows($result)>0){
			while($row=mysqli_fetch_row($result)){
				$check='';
				if($row['4']==1)$check='checked';
				$arg='';
				if(strlen($row['6'])>0){
					$arg='<b style="font-size:9px; color:#00b254;">'.$row['6'].'</b>';
				}
				$nomecli='';
				
				$query2="SELECT nome FROM clienti WHERE ID ='".$row['5']."' LIMIT 1";
				$result2=mysqli_query($link2,$query2);
				if(mysqli_num_rows($result2)>0){
					$row2=mysqli_fetch_row($result2);
					$nomecli=$row2['0'];				
				}
				
				$txtnota=$row['1'].' '.$row['2'];
				
				$noteapp='';
				if(strlen($row['2'])>0){
					$noteapp='<br><span style="font-size:9px; color:#999; width:70%;">'.str_replace('\n','<br>',$row['2']).'</span>';
				}
				
				
				
				$testo4.='
			
					<li class="swipeout">
					  <div class="swipeout-content">
					  <label class="label-checkbox item-content" style="height:100%">
					  	<input type="checkbox" name="my-checkbox"  id="riso'.$row['0'].'" value="0" onchange="modprenot('.$row['0'].','."'riso".$row['0']."'".',142,7)" '.$check.'>
						<div class="item-media mediaright">
						  <i class="icon icon-form-checkbox"></i>
						</div>
						
						
						<div class="item-inner" style="height:100%">
						  <div class="item-title-row">
						   <div class="item-text" style="color:#333;font-weight:600; height:30px;line-height:15px;">'.$txtnota.'  </div>
							
							<div class="item-right" style="width:100px; font-size:12px; text-align:right;">'.dataita3($row['3']).' '.date('H:i',$row['3']).'<br/>
							'.$arg.'
							</div>
						  </div>
						 <div class="item-title"></div>
						</div>
						</label>
					  </div>
					 	
					</li>
				';
		
				
			}
		}else{
			$testo4.='
			
			<li class="swipeout">
					  <div class="swipeout-content">
						<div class="item-content">
						  
						  <div class="item-inner notif" >Non ci sono appunti
							  </div>
						  
						  
						</div>
					  </div>

					</li>
				';
		}
			
		$testo4.='
		</ul></div>
	
		
	
		
		<input type="hidden" id="numappunt" value="'.$numappunti.'">
		
		';
	
		echo $testo4;	 
	


?>