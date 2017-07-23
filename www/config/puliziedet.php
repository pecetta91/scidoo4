<?php

if(!isset($inc)){
	header('Access-Control-Allow-Origin: *');
	include('../../config/connecti.php');
	include('../../config/funzioni.php');
	include('../../config/funzionilingua.php');
	
	$IDutente=intval($_SESSION['ID']);
	$IDstruttura=intval($_SESSION['IDstruttura']);
	
	$IDapp=intval($_GET['dato0']);
}


$gg=7;
if(isset($_GET['dato2'])){
	if(is_numeric($_GET['dato2'])){
		$gg=$_GET['dato2'];
	}
}

$data=date('Y-m-d',$time);

$query="SELECT nome FROM appartamenti WHERE ID='$IDapp' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$nomeapp=$row['0'];


$testo='
<div data-page="pulizie" class="page"> 

			 
			 <div class="navbar">
				<div class="navbar-inner">
					<div class="left">
					
					 <a href="#" class="link icon-only back" >
						<i class="material-icons" style="font-size:30px;">chevron_left</i>
					</a>
					
					</div>
					<div class="center titolonav">'.$nomeapp.'</div>
					<div class="right" ></div>
				</div>
			</div>
			 
			 
            <div class="page-content">
			
				
				
              <div class="content-block" id="puliziediv" style="padding:0px; width:100%;"> 
<input type="hidden" id="timeristo" value="'.$time.'">
<input type="hidden" id="IDtipovis" value="'.$vis.'">
<input type="hidden" id="ggpulizie" value="'.$gg.'">
';

				list($yy, $mm, $dd) = explode("-", $data);
				$time0=mktime(0, 0, 0, $mm, $dd, $yy);
				$timef=$time0+86400;
			$statoarr=array('Pronto','Occupato','Da Preparare');	
			$statocol=array('1dbb68','bb2c1d','d8bf18');		
			
	
		
		$time=oraadesso($IDstruttura);
		
		$data0=date('Y-m-d',$time);
		$datai=date('Y-m-d',($time-86400));
		$dataf=date('Y-m-d',$time+7*86400);
		
		list($yy, $mm, $dd) = explode("-", $data0);
		$time0=mktime(0, 0, 0, $mm, $dd, $yy);
		
		$prenapp=array(array());
		$checkin=array(array());
		$checkout=array(array());
		
		$query="SELECT p.IDv,p.app,p.time,p.gg,p.checkout FROM prenotazioni as p,prenextra as pr WHERE pr.IDstruttura='$IDstruttura' AND pr.IDpren=p.IDv AND FROM_UNIXTIME(pr.time,'%Y-%m-%d') BETWEEN '$datai' AND '$dataf' AND pr.IDtipo='8' AND p.app='$IDapp' ";
		$result=mysqli_query($link2,$query);
		if(mysqli_num_rows($result)>0){
			while($row=mysqli_fetch_row($result)){
				$IDpren=$row['0'];
				$IDapp2=$row['1'];
				$times=$row['2'];
					
				$gg=floor(($times-$time0)/86400);
				$part=floor(($row['4']-$time0)/86400);
				
				$giorni=$row['3'];
				for($kk=0;$kk<$giorni;$kk++){
					$prenapp[$IDapp2][$kk+$gg]=$IDpren;
				}
				if($times>$time0){
					$checkin[$IDapp2][$gg]=$times;
				}
				//echo $gg;
				$checkout[$IDapp2][$part]=$row['4'];
				//$checkin=array(array());
				
				
				
			}
		}
		
		
		
		$ggstart=date('N',($time-86400));
		
		$query="SELECT nome,attivo,stato,ID FROM appartamenti WHERE IDstruttura='$IDstruttura' AND attivo='1' AND ID='$IDapp' LIMIT 1"; 
		$result=mysqli_query($link2,$query);
		while($row=mysqli_fetch_row($result)){
			$IDapp=$row['3'];
			$proapp=$row['2'];
			$class='';
			switch($row['2']){
				case 0:
					$class='tav4';
				break;
				case 1:
					$class='tav1';
				break;
				case 2:
					$class='tav3';
				break;
			}
			
			$IDpren=0;
			$prox='<table class="dispon">';
			
			$ggstart2=$ggstart;
			$line1='';
			$line2='';
			
			for($i=-1;$i<8;$i++){
				$class='';
				$into='';
				$mex='';
				if($i==0){$into='X ';}
				if(isset($prenapp[$IDapp][$i])){
					if($IDpren==0)$IDpren=$prenapp[$IDapp][$i];
					$red=0;
					if(isset($prenapp[$IDapp][$i-1])){
						if($prenapp[$IDapp][$i-1]!=$prenapp[$IDapp][$i]){
							$red=1;
						}
					}
					if($red==0){
						//$prox.='<td class="red"></td>';
						$class.='red';
						$mex='Occupato';
					}else{
						//$prox.='<td class="orange"></td>';	
						$class.='orange';
						$mex='<b>Da pulire</b>';
						//$mex='<div style="color:#bb3b3c;line-height:10px; margin:0px;">Da pulire<br>urgente</div>';
					}
						
				}else{
					$class.='green';
					$mex='Libero';
					//$prox.='<td class="green"></td>';
				}
				$classoggi='';
				if($i==1){
					$classoggi='style="border:solid 2px #36905c;"';
					//$mex.='<br>Oggi';
				}
				$ins=0;
				if(isset($checkout[$IDapp][$i])){
					$ins=1;
					$mex.='<hr style="margin:1px;">Lib: '.date('H:i',$checkout[$IDapp][$i]);
				}
				
				if(isset($checkin[$IDapp][$i])){
					if($ins==0){$mex.='<hr style="margin:1px;">';}else{$mex.='<br>';}
					$mex.='Arr: '.date('H:i',$checkin[$IDapp][$i]);
				}
				
				
				$line1.='<td '.$classoggi.'>
				<div style="width:35px; height:35px; border-radius:50%; font-size:15px; line-height:17px; padding:5px; border:solid 1px #ccc;" class="'.$class.'">'.date('d',($time+$i*86400)).'<br>'.$giorniita2[$ggstart2].'</div>
				
				</td>';
				$line2.='<td style="border:none;background:transparent;" valign="top"><span style="font-size:9px;">'.$mex.'</span></td>';
				
				$ggstart2++;
				if($ggstart2==8){$ggstart2=1;}
				
				
			}
			$prox.='<tr>'.$line1.'</tr><tr>'.$line2.'</tr></table>';
			
			
			
			//controllo se oggi e' presente qualcuno
			/*
			$testo.='<div style="padding:0px 10px 0px 10px;">Oggi: '.dataita4(time()).' '.date('H:i').'</div>';
			
			$query="SELECT p.IDv,p.checkout FROM prenotazioni as p,prenextra as pr WHERE pr.IDstruttura='$IDstruttura' AND pr.IDpren=p.IDv AND FROM_UNIXTIME(pr.time,'%Y-%m-%d') ='$data0' AND pr.IDtipo='8' AND p.app='$IDapp' ";
			$result=mysqli_query($link2,$query);
			if(mysqli_num_rows($result)>0){
				//controllo partenza prossima
				$row=mysqli_fetch_row($result);
				$checkout=$row['1'];
				$testo.='<div style="color:#bd2d11; padding:0px 10px 0px 10px;">I signori che soggiornano in questo alloggio lo lasceranno il giorno <b>'.dataita4($checkout).'</b> alle ore <b>'.date('H:i',$checkout).'</b></div>';
				
				
				
			}else{
				//controlla il giorno di ieri
				$dataieri=date('Y-m-d',($time0-86400));
				$query="SELECT p.IDv,p.checkout FROM prenotazioni as p,prenextra as pr WHERE pr.IDstruttura='$IDstruttura' AND pr.IDpren=p.IDv AND FROM_UNIXTIME(pr.time,'%Y-%m-%d') ='$dataieri' AND pr.IDtipo='8' AND p.app='$IDapp' ";
				$result=mysqli_query($link2,$query);
				if(mysqli_num_rows($result)>0){
					$row=mysqli_fetch_row($result);
					$checkout=$row['1'];
					$testo.='<div style="color:#e87d15;padding:0px 10px 0px 10px;">I signori che soggiornano questo alloggio lo lasceranno oggi alle ore '.date('H:i',$checkout).' </div>';
				}else{
					//se non c'e' dice che ora e' libero
					$testo.='<div style="color:#3bae5e;padding:0px 10px 0px 10px;"><b>Alloggio libero</b></div>';
				}
			}
			
			*/
			$testo.='<div style="width:100%; overflow-x:scroll; height:110px; overflow-y:hidden;"><div style="width:500px;">'.$prox.'</div></div>';
			
			$testo.='
				<div class="content-block-title titleb">Stato Pulizia</div>
				<div class="list-block" >
				  <ul>
					<li>
					  <a href="#" class="item-link smart-select">
						<select name="fruits" onchange="modprenot('.$IDapp.',this.value,17,10,0)" >';
						  
						  foreach ($statoarr as $key=>$dato){
							  $testo.='<option value="'.$key.'" ';
							  if($key==$proapp){$testo.=' selected="selected" ';}
							   $testo.='>'.$dato.'</option>';
							}

					  
						$testo.='</select>
						<div class="item-content">
						  <div class="item-inner">
							<div class="item-title">Stato Alloggio</div>
							<div class="item-after"></div>
						  </div>
						</div>
					  </a>
					</li>
				</ul>
				</div>   ';
			
			
			
				//prossime 2 prenotazioni
				
				
				
					
				
				
			
			
			
			
					
						
									
									
									
									$query3="SELECT IDv,time,gg,checkout FROM prenotazioni WHERE app='$IDapp' AND IDstruttura='$IDstruttura' AND time>='$time0' ORDER BY time LIMIT 2";
									$result3=mysqli_query($link2,$query3);
									
									if(mysqli_num_rows($result3)>0){
										
										$lastcheckout=0;
										
										while($row3=mysqli_fetch_row($result3)){
											
											/*if($lastcheckout!=0){
												$testo.='
											<div class="content-block-title titleb" style="color:#bd2d11;margin-top:-20px">Checkout: '.date('H:i',$lastcheckout).'<br>';
											$diff=$row3['1']-$lastcheckout;
											if($diff>86400){
												$testo.='<span style="color:#42416b">Tempo per pulire alloggio:</span>'.round($diff/86400).' giorni';
											}else{
												$testo.='<span style="color:#42416b">Tempo per pulire alloggio:</span>'.round($diff/3600).' ore';
											}
											
											
											$testo.='</div>';
											}*/
											
											
											//- <span style="text-transform:capitalize;color:#f1aa38;">'.$row3['2'].' '.txtnotti($row3['2']).'</span>
											
											
											if($lastcheckout==0){
												$testo.='
											<div class="content-block-title titleb"><span style="color:#349670;font-weight:400;">Prossimo Arrivo</span><br> '.dataita4($row3['1']).'  '.date('H:i',$row3['1']).' </div>';
											}else{
												$testo.='
											<div class="content-block-title titleb"><span style="color:#349670;font-weight:400;">Arrivo successivo</span><br> '.dataita4($row3['1']).'  '.date('H:i',$row3['1']).' </div>';
											}
											
											$lastcheckout=$row3['3'];
											
											$testo.='
											<div class="list-block"><ul>';
											
												
											$IDpren=$row3['0'];
											$testo.='<li class="item-content">
										  <div class="item-inner">
											<div class="item-title">Persone</div>
											<div class="item-after">';
											
											$query2="SELECT GROUP_CONCAT(IDrest SEPARATOR ',') FROM infopren WHERE IDpren='$IDpren' AND pers='1' GROUP BY IDstr";
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
											
											 $query2="SELECT infopren.nome,tiporestr.restrizione  FROM infopren,tiporestr WHERE infopren.IDpren='$IDpren' AND infopren.IDstr='$IDstruttura' AND infopren.pers='0' AND infopren.IDrest=tiporestr.ID";
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
										</ul></div>
										';
										
										}
									}
									
									
									$testo.='
									</ul></div>';
			
			
		
		}
		
		$testo.='<ul></div>
		';
		
	$testo.='</div></div>';
	



			echo $testo;	

?>