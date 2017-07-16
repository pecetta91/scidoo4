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
		$numnotifiche=0;
		
		$testo3='
		<div class="list-block" style="min-height:500px;margin-top:-30px;">
		  <ul>
		
		';
		
		$query="SELECT ID,tipo,colore FROM tiponotifica";
		$result=mysqli_query($link2,$query);
		$arrt=array();
		$arrc=array();
		while($row=mysqli_fetch_row($result)){
			$arrt[$row['0']]=$row['1'];
			$arrc[$row['0']]=$row['2'];
		}
		$not='0';
		$query="SELECT ID,IDobj,alarm,time,letto,risolto,altro FROM notifiche WHERE IDdest='$IDpers' AND view='1' AND time>='$time' AND IDstr='$IDstruttura' ORDER BY time DESC,letto LIMIT 35";
				
		$result=mysqli_query($link2,$query);
		if(mysqli_num_rows($result)>0){
			while($row=mysqli_fetch_row($result)){
				
				$not.=','.$row['0'];
				$txt1='';
				$txt2='';
				switch($row['2']){
					case 1:
					case 12:
						$txt1.='<b>'.estrainome($row['1']).'</b><br><span style="font-size:11px;">'.estrainomeapp($row['1'],1).'</span><br>';
					break;
					case 2: 
					case 3:
					case 10:
						$query2="SELECT etichetta FROM domotica WHERE ID='".$row['1']."' LIMIT 1";
						$result2=mysqli_query($link2,$query2);
						$row2=mysqli_fetch_row($result2);
						$txt1.='<b>'.$row2['0'].'</b><br>';
						
						$check='';
						if($row['5']==1){
							$check=' checked';
						}
						
						
						$txt2.='
						<div class="swipeout-actions-left">
							<a href="#">Risolto</a>
						  </div>
						';
						
					break;
					case 4:
						
						
					break;
					case 5:
					case 7: //elimina servizio
					
						$IDprenextra=$row['1'];
						$arr=explode('_',$row['6']);
						$nump=$arr['0'];
						$IDpren=$arr['2'];
						$IDserv=$arr['1'];
						$time=$arr['3'];
						if($row['2']==5){
							$query2="SELECT IDinfop FROM prenextra2 WHERE IDprenextra='$IDprenextra'";
							$result2=mysqli_query($link2,$query2);
							$nump=mysqli_num_rows($result2);
						}
									
						$query2="SELECT servizio FROM servizi WHERE ID='$IDserv' LIMIT 1";
						$result2=mysqli_query($link2,$query2);
						$row2=mysqli_fetch_row($result2);
						$servizio=$row2['0'];
			
						$txt1.='<b>'.$servizio.' per '.$nump.' persona/e</b><br><span style="font-size:10px;">'.estrainomeapp($IDpren,1).'</span><br>';
			
					
					
					
					break;
					case 11: //annulla prenotazione
						$IDpren=$row['1'];
						$txt1.='<b>'.estrainome($IDpren).'</b><br><span style="font-size:10px;">Prenotazioni annullate</span><br>';
					break;
				}
				$txt1.='<b style="font-size:11px; color:#999;">'.$arrt[$row['2']].'</b>';
				$class='';
				if($row['4']==0){
					$class="nonletto";
					$numnotifiche++;
				}
				
				
				$icon=array();
				$icon[1]='add';
				$icon[2]='bolt_fill';
				$icon[3]='bolt_fill';
				$icon[6]='bolt_fill';
				$icon[10]='bolt_fill';
				$icon[11]='bolt_fill';
				$icon[8]='persons';
				$icon[9]='persons';
				$icon[7]='close';
				$icon[12]='reload';
				$icon[5]='add';
				
				
				$testo3.='
				 <li class="swipeout '.$class.'" >
				  <div class="swipeout-content" >
					<div class="item-content">
					  <div class="item-media"><div class="notifica'.$row['2'].' notifdiv"><i class="icon f7-icons" style="color:#fff;  font-size:15px;">'.$icon[$row['2']].'</i></div></div>
					  <div class="item-inner notif" >
					  '.$txt1.'
						  </div>
					  <div class="item-after notif" >'.date('H:i',$row['3']).'<br><span style="font-size:9px;">'.date('d/m/y',$row['3']).'</span></div>
					  
					  
					  
					</div>
				  </div>
				  '.$txt2.'
				  
				  
				</li>
				';
			}
		}else{
			$testo3.='
			
				<li class="swipeout" '.$class.'>
				  <div class="swipeout-content">
					<div class="item-content">
					  <div class="item-media"></div>
					  <div class="item-inner">Non ci sono notifiche </div>
					 </div>
				  </div>
				</li>';
		}
			
		$testo3.='</ul></div>
		<br><br><br><br><br>
		
		<input type="hidden" id="numnotif" value="'.$numnotifiche.'">
		
		';
		$query="UPDATE notifiche SET letto='1' WHERE IDdest='$IDpers'";
		$result=mysqli_query($link2,$query);
		
		
	
		echo $testo3;	 
	
	


?>