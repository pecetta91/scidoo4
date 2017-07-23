<?php 

if(!isset($inc)){
	header('Access-Control-Allow-Origin: *');
	include('../../config/connecti.php');
	include('../../config/funzioni.php');
	
	
	$IDutente=$_SESSION['ID'];
	$IDstruttura=$_SESSION['IDstruttura'];
	
	
}
	
	
$colornot=array();
$query="SELECT ID,colore FROM tiponotifica";
$result=mysqli_query($link2,$query);
while($row=mysqli_fetch_row($result)){
	$colornot[$row['0']]=$row['1'];
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
		
		$testo3='<div class="list-block media-list"><ul>';
		
		$query="SELECT ID,tipo,colore FROM tiponotifica";
		$result=mysqli_query($link2,$query);
		$arrt=array();
		$arrc=array();
		while($row=mysqli_fetch_row($result)){
			$arrt[$row['0']]=$row['1'];
			$arrc[$row['0']]=$row['2'];
		}
		$not='0';
		$query="SELECT ID,testo,time,titolo,tipo,letto FROM notifichetxt WHERE IDpers='$IDpers' AND time>='$time' ORDER BY letto,time DESC LIMIT 35";
		$result=mysqli_query($link2,$query);
		if(mysqli_num_rows($result)>0){
			while($row=mysqli_fetch_row($result)){
				$letto='';
				if($row['5']==0){
					$letto=' style="background:#e5eef6;"';
				}
				$testo3.='
		 <li '.$letto.' onclick="opennot('.$row['0'].')">
            <div class="item-content">
                <div class="item-inner">
                    <div class="item-title-row">
                        <div class="item-title" style="font-size:16px; line-height:25px;color:#'.$colornot[$row['4']].'">'.$row['3'].'</div>
                        <div class="item-after">'.dataita2($row['2']).'</div>
                    </div>
                    <div class="item-text" style="color:#53607b;font-size:14px;" id="notifica'.$row['0'].'">'.$row['1'].'</div>
                </div>
            </div>
        </li>';

				
			}
		}else{
			$testo3.='
				 <li>
				  <div class=" item-content">
					<div class="item-inner">
					  <div class="item-title-row">
						<div class="item-title">Non ci sono notifiche</div>
					  </div>
					</div>
				  </div>
				</li>
			
			';
		}
			
		$testo3.='</ul></div>
		<br><br><br><br><br>
		<input type="hidden" id="numnotif" value="'.$numnotifiche.'">
		
		';
		$query="UPDATE notifiche SET letto='1' WHERE IDdest='$IDpers'";
		$result=mysqli_query($link2,$query);
		$query="UPDATE notifichetxt SET letto='1' WHERE IDpers='$IDpers'";
		$result=mysqli_query($link2,$query);
		
		echo $testo3;	 
	
	


?>