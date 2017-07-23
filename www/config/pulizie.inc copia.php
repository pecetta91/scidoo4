<?php

if(!isset($inc)){
	header('Access-Control-Allow-Origin: *');
	include('../../config/connecti.php');
	include('../../config/funzioni.php');
	include('../../config/funzionilingua.php');
	
	$IDutente=intval($_SESSION['ID']);
	$IDstruttura=intval($_SESSION['IDstruttura']);
	if(isset($_GET['dato0'])){
		if($_GET['dato0']!='0'){
			$time=$_GET['dato0'];
		}else{
			if(isset($_SESSION['timecal'])){
				$time=$_SESSION['timecal'];
			}else{
				$time=time();
			}
		}
	}else{
		if(isset($_SESSION['timecal'])){
			$time=$_SESSION['timecal'];
		}else{
			$time=time();
		}
	}
	$_SESSION['timecal']=$time;
	$mm=date('m',$time);
	$aa=date('Y',$time);
	$mmsucc=$mm+1;
}


if(isset($_GET['dato1'])){
	if(is_numeric($_GET['dato1'])){
		$vis=$_GET['dato1'];
	}
}else{
	if(isset($_SESSION['vis'])){
		$vis=$_SESSION['vis'];
	}else{
		$vis=1;
	}
}


$gg=7;
if(isset($_GET['dato2'])){
	if(is_numeric($_GET['dato2'])){
		$gg=$_GET['dato2'];
	}
}

$data=date('Y-m-d',$time);

$testo='
<input type="hidden" id="timeristo" value="'.$time.'">
<input type="hidden" id="IDtipovis" value="'.$vis.'">
<input type="hidden" id="ggpulizie" value="'.$gg.'">
';

				list($yy, $mm, $dd) = explode("-", $data);
				$time0=mktime(0, 0, 0, $mm, $dd, $yy);
				$timef=$time0+86400;
			$statoarr=array('Pronto','Occupato','Da Preparare');	
			$statocol=array('1dbb68','bb2c1d','d8bf18');		
			
			
//elenco arrivi
			
switch($vis){
	case 1:			
		
		$testo.='
		
		<div class="content-block-title"   style="margin-top:-18px; text-align:center; background:#e57511;color:#fff; line-height:30px; height:30px; border-radius:5px; padding:0px; overflow:hidden; position:relative; ">
		<input type="text" id="datacentro" style="position:absolute; top:0px; left:0px; opacity:0; width:100%; height:30px;">
		<table width="100%;" style="margin-top:-2px; margin-left:-2px;"><tr><td width="50%;" style="background:#d13b23;">'.dataita4($time).'</td><td>'.dataita4(($time+86400*$gg)).'</td></tr></table></div><br>
		
		
		';
		
		$numarr=0;
		
		for($i=0;$i<7;$i++){
			
			$timeini=$time0+$i*86400;
			$timefin=$timeini+86400;
				
			$query="SELECT ID,IDv,app,time FROM prenotazioni WHERE time>='$timeini' AND time<'$timefin' AND gg>'0' AND IDstruttura='$IDstruttura' AND stato>='0'"; 
			$result=mysqli_query($link2,$query);
	
			
			if(mysqli_num_rows($result)>0){
				$numarr++;
				$testo.='
					<div class="content-block-title titleb" style="margin-top:-10px; color:#455ba2; ">'.dataita($timeini).'</div>
					<div class="list-block accordion-list"  style="margin-bottom:10px;">
					<ul>
				
				
			';
				
				while($row=mysqli_fetch_row($result)){
					$id=$row['1'];
					$IDapp=$row['2'];
				
					$query2="SELECT nome,attivo,stato FROM appartamenti WHERE ID='$IDapp'  AND IDstruttura='$IDstruttura'"; 
					$result2=mysqli_query($link2,$query2);
					$row2=mysqli_fetch_row($result2);
					$nome=$row2['0'];
					
					$testo.='
						
						<li class="accordion-item" style="border-left:solid 3px #'.$statocol[$row2['2']].';"><a href="#" class="item-content item-link" >
							<div class="item-inner">
							  <div class="item-title">'.$nome.'</div>
							  <div class="item-after" style="font-size:13px;line-height:12px;text-align:right;font-weight:100;"><div style="border-right:solid 1px #ccc;padding-right:5px;margin-right:5px;">'.date('H:i',$row['3']).'</div><div style="color:#'.$statocol[$row2['2']].';font-weight:400;">'.$statoarr[$row2['2']].'</div></div>
							</div></a>
						  <div class="accordion-item-content">
							<div class="content-block" style="background:#f4f4f4; padding:0px;">
							
							<div class="list-block">
								  <ul>
								  
								  	<li class="item-content">
										<div class="item-title" style="width:100%; padding:0px;">
										<p class="buttons-row" style="width:100%; padding:0px;">
  
									';
										
										
										
										$q8="SELECT stato FROM appartamenti WHERE ID='$IDapp' LIMIT 1";
										$r8=mysqli_query($link2,$q8);
										$row8=mysqli_fetch_row($r8);
										$proapp=$row8['0'];
									
										
										foreach ($statoarr as $key=>$dato){
											$testo.='<a href="#" onclick="modprenot('.$IDapp.','.$key.',17,10,3)" class="button button12 button-raised';
											if($key==$proapp){$testo.=' button-fill ';
												switch($key){
													case 0:
														$testo.='color-green';
													break;
													case 1:
														$testo.='color-red';
													break;
													case 2:
														$testo.='color-orange';
													break;
												}
											
											}
											$testo.='" style="font-size:12px;">'.$dato.'</a>';
										}
										
										
										
										$testo.='</p></div>
									</li>
									
									
								  
								  
								  
								  
									<li class="item-content">
									  <div class="item-inner">
										<div class="item-title">Persone</div>
										<div class="item-after">';
										
										$query2="SELECT GROUP_CONCAT(IDrest SEPARATOR ',') FROM infopren WHERE IDpren='$id' AND pers='1' GROUP BY IDstr";
										$result2=mysqli_query($link2,$query2);
										if(mysqli_num_rows($result2)>0){
											$row2=mysqli_fetch_row($result2);
											$group=$row2['0'].',';
											$testo.=txtrestr($group,0);
										}
										
										
										
										$testo.='</div>
									  </div>
									</li>
									
									
									
									<li class="item-content">
									  <div class="item-inner">
										<div class="item-title">Letti</div>
										<div class="item-after">';
										
										 $query2="SELECT infopren.nome,tiporestr.restrizione  FROM infopren,tiporestr WHERE infopren.IDpren='$id' AND infopren.IDstr='$IDstruttura' AND infopren.pers='0' AND infopren.IDrest=tiporestr.ID";
										$result2=mysqli_query($link2,$query2);
										$num2=mysqli_num_rows($result2);
										if($num2>0){
												$j=1;
												while($row=mysqli_fetch_row($result2)){
													if($row['0']!=0){
													$testo.='N.'.$row['0'].' '.$row['1'];
													if($j<$num2){$testo.=', ';if(($j%2)==0)$testo.='<br>';}
												}
											$j++;
											}	
										}else{
											$testo.='Nessuna disposizione';
										}
										
										$testo.='</div>
									  </div>
									</li>
									
									
									
									<li class="item-content" style="height:80px;">
									  <div class="item-inner" style="height:70px;">
										<div class="item-title">Note All.</div>
										<div class="item-after">
										<textarea style="width:100%; height:65px; font-size:11px; line-height:11px; padding:2px; border-radius:3px; margin-top:-15px; border:solid 1px #ccc;" placeholder="Note Alloggio"></textarea>
										';
										
										$testo.='</div>
									  </div>
									</li>
							';
							
								
						
								
						
								
								
							$testo.='
							 
							 </ul></div>
							 
							 
							</div>
						  </div>
						</li>
						';
					
				}
				$testo.='</ul></div>';
			}			
		}
		
		if($numarr==0){
			$testo.='<span style="font-size:16px;">Non ci sono arrivi questa settimana</span>';
		}
	break;
	case 2:
		
		$testo.='
			<div class="content-block-title">Alloggi Struttura</div>
				<div class="list-block accordion-list">
				  <ul>
			';
		
		$query="SELECT nome,attivo,stato,ID FROM appartamenti WHERE IDstruttura='$IDstruttura' AND attivo='1'"; 
		$result=mysqli_query($link2,$query);
		while($row=mysqli_fetch_row($result)){
			$IDapp=$row['3'];
			$proapp=$row['2'];
			$testo.='
					<li class="accordion-item" style="border-left:solid 3px #'.$statocol[$row['2']].'"><a href="#" class="item-content item-link">
						<div class="item-inner">
						  <div class="item-title">'.$row['0'].'</div>
						  <div class="item-after" style="color:#'.$statocol[$row['2']].';font-weight:400;">'.$statoarr[$row['2']].'</div>
						</div></a>
					  <div class="accordion-item-content">
						<div class="content-block" style="background:#f4f4f4;">
						
						<div class="list-block">
								  <ul>
								 <li class="item-content">
										<div class="item-title" style="width:100%; padding:0px;">
										<p class="buttons-row" style="width:100%; padding:0px;">
  
									';
										
										
										
										
									
										
										foreach ($statoarr as $key=>$dato){
											$testo.='<a href="#" onclick="modprenot('.$IDapp.','.$key.',17,10,3)" class="button button12 button-raised';
											if($key==$proapp){$testo.=' button-fill ';
												switch($key){
													case 0:
														$testo.='color-green';
													break;
													case 1:
														$testo.='color-red';
													break;
													case 2:
														$testo.='color-orange';
													break;
												}
											
											}
											$testo.='" style="font-size:12px;">'.$dato.'</a>';
										}
										
										
										
										$testo.='</p></div>
									</li>
									
									
									<li class="item-content" style="height:80px;">
									  <div class="item-inner" style="height:70px;">
										<div class="item-title">Note All.</div>
										<div class="item-after">
										<textarea style="width:100%; height:65px; font-size:11px; line-height:11px; padding:2px; border-radius:3px; margin-top:-15px; border:solid 1px #ccc;" placeholder="Note Alloggio"></textarea>
										';
										
										$testo.='</div>
									  </div>
									</li>
									
									</ul></div>
								 
								  
						
						 
						</div>
					  </div>
					</li>';
			
			
		
		}
		
		$testo.='<ul></div>';
		
	
	
	break;
}
					
					$modb='
					<li><a href="#" class="list-button item-link" onclick="navigationtxt(15,'."'".$time.",1'".','."'puliziediv'".',7)">Vista Settimanale</a></li>
					<li><a href="#" class="list-button item-link" onclick="navigationtxt(15,'."'".$time.",2'".','."'puliziediv'".',7)" >Vista Per Alloggio</a></li>
					';
					
					
					$testo.='<div id="menu10" style="display:none;" >'.base64_encode($modb).'</div>
					
					<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
		
		';
		
		/*
					
     <div class="toolbar tabbar toolbar-bottom " id="bottomprofilo" style="height:52px;  transform:translateZ(0); webkit-transform:translateZ(0);background:#1c275e; z-index:99999; padding:0px; position:fixed; bottom:0px;">
			<div class="toolbar-inner">
				<a href="#" onclick="aprimod(10,this)" class="tab-link " style="height:52px;">
					<i class="icon" style="line-height:10px;"><div style="position:absolute;  margin-top:22px; margin-left:-21px; width:100%; text-align:center; font-size:8px;color:#fff;  ">Settimana / Alloggi</div>
						<i class="material-icons" style="font-size:25px; color:#fff; margin-top:-5px; margin-left:5px;">view_list</i>
					</i>
				</a>
				<a href="#" class="tab-link" style="height:52px;">
					
				</a>
				
				
			</div>
		</div>
		
		
		*/


			echo $testo;	

?>