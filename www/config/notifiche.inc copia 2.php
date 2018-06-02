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
	
	$tempooggi=time();

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



$query="SELECT GROUP_CONCAT(nt.ID SEPARATOR ','),nt.IDgroup,nt.tipogroup,nt.tipo,MAX(np.letto),nt.time FROM notifichetxt as nt,notifichepers as np WHERE np.IDpers='$IDpers' AND nt.time>='0' AND nt.ID=np.IDnotifica AND tipogroup!='0'  GROUP BY IDgroup,tipogroup  ORDER BY nt.time DESC LIMIT 35";
		
		$result=mysqli_query($link2,$query);
		if(mysqli_num_rows($result)>0){
			while($row=mysqli_fetch_row($result)){
				$titolo='';
				
				$IDgroup=$row['0'];
				$IDpren=$row['1'];
				
				$time=$row['5'];
				$nome=estrainome($row['1']);
				
				
				$query4="SELECT a.nome FROM appartamenti as a,prenotazioni as p WHERE p.IDv IN($IDpren) AND p.IDstruttura='$IDstruttura' AND p.app=a.ID";
				$result4=mysqli_query($link2,$query4);
						$nomeapp='';
						if(mysqli_num_rows($result4)>0){
							while($row4=mysqli_fetch_row($result4)){
								$nomeapp.=''.$row4['0'].' , ';
							}
								$nomeapp=substr($nomeapp, 0, strlen($nomeapp)-3); 
							}else{
							 $nomeapp="";
							}
				//$titolo=$row['3'];
				/*$titolo=estrainome($row['1']).'<br><span style="font-size:11px; color:#666; text-transform:capitalize; font-weight:400;">'.estrainomeapp($row['1']).'</span>';*/
				switch($row['2']){
					case 1:
						$titolo='<div class="item-title" style="font-weight:100; color:#000;line-height:20px;"><strong>'.estrainome($row['1']).'</strong> di '.$nomeapp.' 
						';
					break;
				}
				
				$letto='';
				$stato=1;
				if($row['4']==0){
					$letto=' style="background:#e5eef6;"';
					$stato=0;
				}
				
				if(!isset($txtarr[$time])){
					$txtarr[$time]='';
				}
				
			
				$txtarr[$time].='
				<div '.$letto.' class="notific" onclick="opennot('."'".$IDgroup."'".','."'".$arrc[$row['3']]."'".','."'".$nome."'".')">
				<li  class="item-link item-content"  style="height:100%">
		  <div class="item-media" style="color:#'.$arrc[$row['3']].';"><i class="f7-icons">bell</i></div>
		  <div class="item-inner" style="height:100%">
		   <div class="item-title-row">'.$titolo.' - 
		  ';
				
				
				$query3="SELECT COUNT(ID) FROM notifichetxt WHERE ID IN($IDgroup)";
				$result3=mysqli_query($link2,$query3);
				$row3=mysqli_fetch_row($result3);
				$countid=$row3['0'];
			
				
				$query2="SELECT ID,testo,time FROM notifichetxt WHERE ID IN($IDgroup) ORDER BY time DESC";
				$result2=mysqli_query($link2,$query2);
				if(mysqli_num_rows($result2)>0){
					
					
					
					if($countid==1)
					{
						$row2=mysqli_fetch_row($result2);
						$timenot=$row2['2'];
						$temporim=calcolatime($tempooggi,$timenot);
						$testonot=str_replace('/','',$row2['1']);
						
						$txtarr[$time].='<input type="hidden" value="'.base64_encode($testonot).'" id="testonot'.$row2['0'].'">
						<input type="hidden" value="'.base64_encode($temporim).'" id="timenot'.$row2['0'].'">';
						
						
						
						if(strlen($testonot) > 50){$testonot=substr($testonot,0,50)."...";}
						    
						 $txtarr[$time].='<strong>'.ucfirst($testonot).'</strong>
						 <br/>
							<div style="font-size:11px;color:#777;margin-top:5px; text-transform:none; font-weight:100;"><i class="far fa-clock" style="font-size:11px"></i> &nbsp;'.$temporim.'</div>
							
							';
						
					}
					
					else
					{
						while($row2=mysqli_fetch_row($result2))
						 {
							 //<li><strong>'.date('H:i',$row2['2']).'</strong> &nbsp; '.str_replace('/','',$row2['1']).'</li>
							 
							 $timenot=$row2['2'];
							 $temporim=calcolatime($tempooggi,$timenot);
							 $testonot=str_replace('/','',$row2['1']);
							 
							$txtarr[$time].='
							 <input type="hidden" value="'.base64_encode($testonot).'" id="testonot'.$row2['0'].'">
							 <input type="hidden" value="'.base64_encode($temporim).'" id="timenot'.$row2['0'].'">';
						    
						 }
						$txtarr[$time].=' <strong>Ha '.$countid.' notifiche</strong><br/>
						
						
						<div style="font-size:11px;color:#777;margin-top:5px; text-transform:none; font-weight:100;"><i class="far fa-clock" style="font-size:11px"></i> &nbsp;'.$temporim.'</div>';
					}
					
					$txtarr[$time].='</div></div></div> </li>     </div><div class="crealinea"></div>';
				}
				/*</td>
				<td style="font-weight:600; text-align:center; font-size:13px; color:#12ba81;">'.date('H:i',$time).'</td>
				</tr>
				$txtarr[$time].='
				<div  style="font-weight:600; text-align:center; font-size:13px; color:#12ba81;">'.date('H:i',$time).'</div>';
				*/
			}
		}
		
		
		//Raggruppa per alarm diversi da IDgroup
		
		
		$query="SELECT GROUP_CONCAT(nt.ID SEPARATOR ','),nt.IDgroup,nt.tipogroup,nt.tipo,MAX(np.letto),nt.time,nt.titolo FROM notifichetxt as nt,notifichepers as np WHERE np.IDpers='$IDpers' AND nt.time>='$time' AND nt.ID=np.IDnotifica AND tipogroup='0'  GROUP BY nt.tipo  ORDER BY nt.time DESC LIMIT 35";
		
		$result=mysqli_query($link2,$query);
		if(mysqli_num_rows($result)>0){
			while($row=mysqli_fetch_row($result)){
				$titolo=$row['6'];
				
				$IDgroup=$row['0'];
				
				$timenot2=$row['5'];
				//$titolo=$row['3'];
				
				
				
				if(strlen($titolo)>0){
					$titolo='<b style="font-size:13px; text-transform:uppercase; font-weight:600;  line-height:15px;">'.$titolo.'</b><br>';
				}
				$letto='';
				$stato=1;
				if($row['4']==0){
					$letto=' style="background:#e5eef6;"';
					$stato=0;
				}
				
				if(!isset($txtarr[$time])){
					$txtarr[$time]='';
				}

				/*<tr '.$letto.' >
				<td align="center" style="font-size:14px;height:50px;font-weight:600;">
				<div class="cercmini2" style="background-color:#'.$arrc[$row['3']].';">'.date('d',$time).'<br>
				<span>'.$mesiita2[date('n',$time)].'</span>
				</div>
				</td>
				<td>'.$titolo.'<br>*/
				
				$temporimasto=calcolatime($tempooggi,$timenot2);
				$txtarr[$time].='
				<div '.$letto.' class="notific" onclick="opennot('."'".$IDgroup."'".','."'".$arrc[$row['3']]."'".','."'".$titolo."'".')">
				<li  class="item-content"  style="height:100%">
		  		<div class="item-media" style="color:#'.$arrc[$row['3']].';"></div>
		  	<div class="item-inner" style="height:100%">
				<div class="item-title-row">
				 <div class="item-title">'.$titolo.'<br/>
				 <div style="font-size:11px;color:#777;margin-top:5px; text-transform:none; font-weight:100;"><i class="far fa-clock" style="font-size:11px"></i> &nbsp;'.$temporimasto.'</div>
				 </div>
				
			    </div>
		   ';
				
				$query3="SELECT COUNT(ID) FROM notifichetxt WHERE ID IN($IDgroup)";
				$result3=mysqli_query($link2,$query3);
				$row3=mysqli_fetch_row($result3);
				$countid=$row3['0'];
				
				$query2="SELECT ID,testo FROM notifichetxt WHERE ID IN($IDgroup)";
				$result2=mysqli_query($link2,$query2);
				
				if(mysqli_num_rows($result2)>0){
					$txtarr[$time].='<div class="item-text">';
					while($row2=mysqli_fetch_row($result2)){
						
						$testonotifica=str_replace('/','',$row2['1']);
						$txtarr[$time].='
						<input type="hidden" value="'.base64_encode($testonotifica).'" id="testonot'.$row2['0'].'">
						<span style="font-size:13px">'.$testonotifica.'</span>';
						
					}
					//$txtarr[$time].='</ul>';
				}
				/*</td>
				<td style="font-weight:600; text-align:center; font-size:13px; color:#12ba81;">'.date('H:i',$time).'</td>
				</tr>*/
				//<div  style="font-weight:600; text-align:center; font-size:13px; color:#12ba81;">'.date('H:i',$time).'</div>
				$txtarr[$time].='</div></div></li></div><div class="crealinea"></div>
				';			
			}
		}

	krsort($txtarr);
		
		foreach($txtarr as $dato){
			$testo3.=$dato;
		}












/*

		$query="SELECT ID,testo,time,titolo,tipo,notifichepers.letto FROM notifichetxt,notifichepers WHERE notifichepers.IDpers='$IDpers' AND notifichepers.IDnotifica=notifichetxt.id  AND time>='$time' ORDER BY letto,time DESC LIMIT 35";

		$result=mysqli_query($link2,$query);

		if(mysqli_num_rows($result)>0){
			while($row=mysqli_fetch_row($result)){
				$letto='';
				if($row['5']==0){
					$letto=' background:#e5eef6';
				}*/
				/*<div class="item-content">
                <div class="item-inner">
                    <div class="item-title-row">
                        <div class="item-title" style="font-size:16px; line-height:25px;color:#'.$colornot[$row['4']].'">'.$row['3'].'</div>
                        <div class="item-after">'.dataita2($row['2']).'</div>
                    </div>
                    <div class="item-text" style="color:#53607b;font-size:14px;" id="notifica'.$row['0'].'">'.$row['1'].'</div>
                </div>
            </div>
			'.date('d',$row['2']).'  '.$mesiita[date('n',$row['2'])].'*/
				
        /*  $row['1']=str_replace('///','<br>',$row['1']);
				
				$testo3.=' 
            <input type="hidden" value="'.base64_encode($row['1']).'" id="testonot'.$row['0'].'">
            ';
				
				if(strlen($row['1']) > 40)
				{
					$row['1']=substr($row['1'],0,40)."...";
				}
				
				
				
				$testo3.='
		 <li style="'.$letto.';height:100%" class="item-content"  onclick="opennot('.$row['0'].')">
		  <div class="item-media" ><i style="color:#'.$arrc[$row['4']].'" class="'.$arrayicon[$row['4']].'"></i></div>
		    <div class="item-inner "style="height:100%">
          <div class="item-title" style="color:#c90909;height:100%;font-size:12px;">'.$row['3'].'</div>
          
		  <div class="item-title" style="font-size:11px;">'.$row['1'].'</div>
        </div>
        </li>';

				/*
				<div class="item-media">'.dataita2($row['2']).'</div>
        <div class="item-inner">
          <div class="item-title">'.$row['3'].'</div>
          <div class="item-after">'.dataita2($row['2']).'</div>
		  <div class="item-title">'.$row['1'].'</div>
        </div>

				
				*/
	/*		}
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
		}*/
			
		$testo3.='</ul></div>
		<br><br><br><br><br>
		<input type="hidden" id="numnotif" value="'.$numnotifiche.'">
		
		';
		$query="UPDATE notifiche SET letto='1' WHERE IDdest='$IDpers'";
		$result=mysqli_query($link2,$query);
		$query="UPDATE notifichetxt SET letto='1' WHERE IDpers='$IDpers'";
		$result=mysqli_query($link2,$query);
        $query="UPDATE notifichepers SET letto='1' WHERE IDpers='$IDpers'";
		$result=mysqli_query($link2,$query);
		
		echo $testo3;	 
	
	


?>