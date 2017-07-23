<?php

if(!isset($inc)){
	header('Access-Control-Allow-Origin: *');
	include('../../config/connecti.php');
	include('../../config/funzioni.php');
	include('../../config/funzionilingua.php');
	
	$IDutente=intval($_SESSION['ID']);
	$IDstruttura=intval($_SESSION['IDstruttura']);
}

unset($_SESSION['timecal']);

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
	if((isset($_SESSION['timecal'])&&(is_numeric($_SESSION['timecal'])))){
		$time=$_SESSION['timecal'];
	}else{
		$time=time();
	}
}

if(isset($_GET['dato1'])){
	if($_GET['dato1']!='0'){
		$gg=$_GET['dato1']+1;
	}else{
		$gg=7;
	}
}else{
	$gg=7;
}



$_SESSION['timecal']=$time;
$mm=date('m',$time);
$aa=date('Y',$time);
$mmsucc=$mm+1;


$data=date('Y-m-d',$time);

$dataini=$data;
$datafin=date('Y-m-d',($time+86400*$gg));

unset($_SESSION['IDsottotip']);
$IDtipo=0;



//estrazione IDsottotip
/*
$ricrea=1;
if(isset($_SESSION['datecentro'])){
	if($_SESSION['datecentro'][0]==date('Y-m-d',$time)){
		$ricrea=0;
	}
}
if($ricrea==1){
	$_SESSION['datecentro']=array();
		for($j=0;$j<7;$j++){
			array_push($_SESSION['datecentro'],date('Y-m-d',$time+$j*86400));
	}
	
}
*/

$testo='
<input type="hidden" id="timecentro" value="'.$time.'">
<input type="hidden" id="ggcentro" value="'.$gg.'">
';

				list($yy, $mm, $dd) = explode("-", $data);
				$time0=mktime(0, 0, 0, $mm, $dd, $yy);
				$timef=$time0+86400;
				
				
				$orari=array();
				$steps=3600;
			
						
							
	
				$firstmain=0;
			
						$arrextra=array();
						$arrpren=array();
						
						$arrsotto=array();
						$querymain="SELECT ID,sottotipologia FROM sottotipologie WHERE IDmain='4' AND IDstr='$IDstruttura'";
						$resultmain=mysqli_query($link2,$querymain);
						if(mysqli_num_rows($resultmain)>0){
							while($rowm=mysqli_fetch_row($resultmain)){
								$arrsotto[$rowm['0']]=$rowm['1'];
								$IDsottotip=$rowm['0'];
								
								$query="SELECT p.IDpren,GROUP_CONCAT(p.ID SEPARATOR ','),FROM_UNIXTIME(time,'%d'),time,SUM(p2.qta) FROM prenextra as p,prenextra2 as p2 WHERE 
					FROM_UNIXTIME(p.time,'%Y-%m-%d') BETWEEN '$dataini' AND '$datafin' AND p.IDstruttura='$IDstruttura' AND p.sottotip='$IDsottotip'  AND p.modi>='0' AND p2.IDprenextra=p.ID AND p2.qta>'0' GROUP BY p.IDpren,FROM_UNIXTIME(p.time,'%Y-%m-%d') ORDER BY p2.qta ";
					
					
								$result=mysqli_query($link2,$query);
								if(mysqli_num_rows($result)>0){
									while($row=mysqli_fetch_row($result)){
										
										$IDpren=$row['0'];
										$timeinto=$row['3'];
										$pers=$row['4'];
										$datainto=date('Y-m-d',$timeinto);
										
											
											$ggsett=$row['2'];
											
											
												
											$IDgroup='';
											if(isset($arrextra[$ggsett][$IDsottotip])){
												$arrextra[$ggsett][$IDsottotip][1]+=$pers;//num
												$arrextra[$ggsett][$IDsottotip][0]++;//num
											}else{
												$arrextra[$ggsett][$IDsottotip][1]=$pers;//num
												$arrextra[$ggsett][$IDsottotip][0]=1;//num
											}
										}
									}
							
							}
						}
						//inizia la stampa
						
						
						
						
						
						
						$arrsotto[2]='Trattamenti';
								$IDsottotip=2;
								
								$query="SELECT p.IDpren,GROUP_CONCAT(p.ID SEPARATOR ','),FROM_UNIXTIME(time,'%d'),time,SUM(p2.qta) FROM prenextra as p,prenextra2 as p2 WHERE 
					FROM_UNIXTIME(p.time,'%Y-%m-%d') BETWEEN '$dataini' AND '$datafin' AND p.IDstruttura='$IDstruttura' AND p.IDtipo='$IDsottotip'  AND p.modi>'0' AND p2.IDprenextra=p.ID AND p2.qta>'0' GROUP BY p.ID,FROM_UNIXTIME(p.time,'%Y-%m-%d') ORDER BY p2.qta ";
					
					
								$result=mysqli_query($link2,$query);
								if(mysqli_num_rows($result)>0){
									while($row=mysqli_fetch_row($result)){
										
										$IDpren=$row['0'];
										$timeinto=$row['3'];
										$pers=$row['4'];
										$datainto=date('Y-m-d',$timeinto);
										
											
											$ggsett=$row['2'];
											
											//controllare il tavolo se sono state aggiunte persone
											
											if(isset($arrextra[$ggsett][$IDsottotip])){
												$arrextra[$ggsett][$IDsottotip][1]+=$pers;//num
												$arrextra[$ggsett][$IDsottotip][0]++;//num
											}else{
												$arrextra[$ggsett][$IDsottotip][1]=$pers;//num
												$arrextra[$ggsett][$IDsottotip][0]=1;//num
											}
										}
									}
						
						
						
						
									
						$testo.= '
		<div class="content-block-title" style="margin-top:-18px; text-align:center; background:#e57511;color:#fff; line-height:30px; height:30px; border-radius:5px; padding:0px; overflow:hidden; position:relative; ">
		<input type="text" id="datacentro" style="position:absolute; top:0px; left:0px; opacity:0; width:100%; height:30px;">
		
		<table width="100%;" style="margin-top:-2px; margin-left:-2px;"><tr><td width="50%;" style="background:#d13b23;">'.dataita4($time).'</td><td>'.dataita4(($time+86400*$gg)).'</td></tr></table></div><br>
		

						
						
						
						
						<div class="timeline verticale" style="margin-top:-15px;">
						
						';
						
						for($i=0;$i<$gg;$i++){
							$timeextra=$time+$i*86400;	
							
							$groupid=getprenot($timeextra,$IDstruttura);
							
							$ggs=date('d',$timeextra);
							$testo.= '
								<div class="timeline-item" style="color:#333;margin-top:-10px; " >
										 <div class="timeline-item-date">'.$ggs.'<br><small>'.$giorniita2[date('N',$timeextra)].'</small></div>
										 <div class="timeline-item-divider"></div>
											 <div class="timeline-item-content" style="width:100%;">
							';
							
							foreach($arrsotto as $IDsotto =>$sotton){
								$testo.='<div class="timeline-item-time button buttcc"  onclick="navigation(14,'."'".$timeextra.",".$IDsotto."'".',6)">'.$sotton.'</div>';
								
								$sospesi='';
								
								
								
								switch($IDsotto){
									case 2:
									$query2="SELECT   COUNT(DISTINCT(p.ID)),SUM(p2.qta) FROM prenextra as p,prenextra2 as p2,servizi as s WHERE p.IDtipo='2'  AND p.IDpren IN($groupid) AND p.modi='0' AND p.ID=p2.IDprenextra AND s.ID=p.extra";
									break;
									default:
									$query2="SELECT  COUNT(DISTINCT(p.ID)),SUM(p2.qta) FROM prenextra as p,prenextra2 as p2,servizi as s WHERE p.sottotip='$IDsotto'  AND p.IDpren IN($groupid) AND p.modi='0' AND p.ID=p2.IDprenextra AND s.ID=p.extra";
									break;
								}
								
								$result2=mysqli_query($link2,$query2);
								$numsosp=mysqli_num_rows($result2);
								$row2=mysqli_fetch_row($result2);
								$numsosp=0;
								if($row2['0']>0){
									$numsosp=$row2['0'];
									$sospesi='<td style="border-left:solid 1px #ccc;padding-left:5px; color:#d13b23;">'.$row2['0'].' Sospesi ('.$row2['1'].'<i class="material-icons" style="font-size:14px;">person</i>)</td>';
								}
									
								if(isset($arrextra[$ggs][$IDsotto])){
									$nott='';
									$pers=$arrextra[$ggs][$IDsotto][1];
									$num=$arrextra[$ggs][$IDsotto][0];
									$testo.='									
									<div onclick="navigation(14,'."'".$timeextra.",".$IDsotto."'".',6)">
										  <div class="timeline-item-title" style="font-size:13px; color:#29b981; font-weight:600; margin-left:10px;"><table><tr><td>'.$num.' Confermati ('.$pers.'<i class="material-icons" style="font-size:14px;">person</i>)<td>'.$sospesi.'</tr></table><br></div>
									</div>
									';								
								}else{
									if($numsosp>0){
										$testo.='									
										<div  onclick="navigation(14,'."'".$timeextra.",".$IDsotto."'".',6)">
											  <div class="timeline-item-title" style="font-size:13px; color:#1649b1; font-weight:600; margin-left:10px;"><table><tr>'.$sospesi.'</tr></table><br></div>
										</div>
										';	
									}
									
									
								}
							}
							$testo.= '</div></div>';
						}
				
					$testo.='</div>';
					
					
	

				

			echo $testo.'<br><br><br><br><br>';	

?>