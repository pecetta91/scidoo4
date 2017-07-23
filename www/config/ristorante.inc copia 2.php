<?php

if(!isset($inc)){
	header('Access-Control-Allow-Origin: *');
	include('../../config/connecti.php');
	include('../../config/funzioni.php');
	include('../../config/funzionilingua.php');
	
	$IDutente=intval($_SESSION['ID']);
	$IDstruttura=intval($_SESSION['IDstruttura']);
}

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
<input type="hidden" id="timeristo" value="'.$time.'">
<input type="hidden" id="ggristo" value="'.$gg.'">
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
						$querymain="SELECT ID,sottotipologia FROM sottotipologie WHERE IDmain='1' AND IDstr='$IDstruttura' ORDER BY ord";
						$resultmain=mysqli_query($link2,$querymain);
						if(mysqli_num_rows($resultmain)>0){
							while($rowm=mysqli_fetch_row($resultmain)){
								$arrsotto[$rowm['0']]=$rowm['1'];
								$IDsottotip=$rowm['0'];
								$IDprentot=array();
								$IDprentav=array();
											
								
								$query="SELECT p.IDpren,GROUP_CONCAT(p.ID SEPARATOR ','),FROM_UNIXTIME(time,'%d'),time FROM prenextra as p,prenextra2 as p2 WHERE 
					FROM_UNIXTIME(p.time,'%Y-%m-%d') BETWEEN '$dataini' AND '$datafin' AND p.IDstruttura='$IDstruttura' AND p.IDtipo='1' AND p.sottotip='$IDsottotip'  AND p.modi>='0' AND p2.IDprenextra=p.ID AND p2.qta>'0' GROUP BY p.IDpren,FROM_UNIXTIME(p.time,'%Y-%m-%d') ORDER BY p2.qta ";
					
					
								$result=mysqli_query($link2,$query);
								if(mysqli_num_rows($result)>0){
									while($row=mysqli_fetch_row($result)){
										
										$IDpren=$row['0'];
										$timeinto=$row['3'];
										$datainto=date('Y-m-d',$timeinto);
										
										if(!in_array($IDpren.'_'.$datainto,$IDprentav))	{
											
											
											$ggsett=$row['2'];
											
											//controllare il tavolo se sono state aggiunte persone
											
											$IDpreng=prenotstessotav($row['0']);
											$arr=explode(',',$IDpreng);
												
											foreach($arr as $dato){
												array_push($IDprentot,$dato);
												array_push($IDprentav,$dato.'_'.$datainto);
											}
											
											
											$query2="SELECT t.ID FROM tavoli as t,tavolipren as tp WHERE tp.IDpren='$IDpren'  AND t.IDsottotip='$IDsottotip' AND t.attivo>='1' AND tp.IDtav=t.ID AND FROM_UNIXTIME(t.timefor,'%Y-%m-%d')='$datainto' ";
											$result2=mysqli_query($link2,$query2);
											if(mysqli_num_rows($result2)>0){
												$row2=mysqli_fetch_row($result2);
												$IDtavolo=$row2['0'];
												
												$query2="SELECT GROUP_CONCAT(IDinfop SEPARATOR ','),COUNT(*)  FROM personetav WHERE IDtavolo='$IDtavolo' AND IDinfop!='0' AND attivo='1' GROUP BY IDtavolo ";
												$result2=mysqli_query($link2,$query2);
												$row2=mysqli_fetch_row($result2);
												$IDgroup=$row2['0'];
												$pers=$row2['1'];
											}else{
												
												$query2="SELECT GROUP_CONCAT(ID SEPARATOR ',') FROM prenextra WHERE 
										FROM_UNIXTIME(time,'%Y-%m-%d') ='$datainto' AND IDstruttura='$IDstruttura' AND IDtipo='1' AND sottotip='$IDsottotip'  AND modi>='0' AND IDpren IN($IDpreng) ";
												$result2=mysqli_query($link2,$query2);
												$row2=mysqli_fetch_row($result2);
												$IDprenextra=$row2['0'];
												
												$query2="SELECT GROUP_CONCAT(DISTINCT(IDinfop) SEPARATOR ','),COUNT(*) FROM prenextra2 WHERE IDprenextra IN ($IDprenextra) AND qta>'0' ORDER BY qta";
												$result2=mysqli_query($link2,$query2);
												$row2=mysqli_fetch_row($result2);
												$IDgroup=$row2['0'];
												$pers=$row2['1'];
											}
											
											
											
											if(isset($arrextra[$ggsett][$IDsottotip])){
												$arrextra[$ggsett][$IDsottotip][0].=','.$IDgroup;//IDpers
												$arrextra[$ggsett][$IDsottotip][1]+=$pers;//num
												$arrextra[$ggsett][$IDsottotip][2]++;//num
												
											}else{
												$arrextra[$ggsett][$IDsottotip][0]=$IDgroup;//IDpers
												$arrextra[$ggsett][$IDsottotip][1]=$pers;//num
												$arrextra[$ggsett][$IDsottotip][2]=1;//num
											}
										}
									}
								}
											
								
								
								$IDpreng=implode(',',$IDprentot);
								if(strlen($IDpreng)==0)$IDpreng='0';
								
								$query="SELECT GROUP_CONCAT(DISTINCT(IDtav) SEPARATOR ',') FROM tavolipren  WHERE IDpren IN($IDpreng)";
								$result=mysqli_query($link2,$query);
								if(mysqli_num_rows($result)>0){
									$row=mysqli_fetch_row($result);
									$IDgtav=$row['0'];
									if(strlen($IDgtav)==0)$IDgtav=0;
								}else{
									$IDgtav=0;
								}
								
								$query="SELECT FROM_UNIXTIME(timefor,'%d'),COUNT(*),GROUP_CONCAT(ID SEPARATOR ',') FROM tavoli WHERE FROM_UNIXTIME(timefor,'%Y-%m-%d') BETWEEN '$dataini' AND '$datafin'  AND IDstr='$IDstruttura' AND attivo>='1' AND IDsottotip='$IDsottotip'  AND ID NOT IN($IDgtav) GROUP BY FROM_UNIXTIME(timefor,'%d')";
								$result=mysqli_query($link2,$query);
								if(mysqli_num_rows($result)>0){
									while($row=mysqli_fetch_row($result)){
										
										$ggsett=$row['0'];
										$groupt=$row['2'];
										$query2="SELECT ID FROM personetav WHERE IDtavolo IN($groupt) AND attivo='1'";
										$result2=mysqli_query($link2,$query2);
										$pers=mysqli_num_rows($result2);
						
										if(isset($arrextra[$ggsett][$IDsottotip])){
											$arrextra[$ggsett][$IDsottotip][1]+=$pers;//num
											$arrextra[$ggsett][$IDsottotip][2]+=$row['1'];
											//$arrextra[$ggsett][1]+=$row['1'];//num
										}else{
											$arrextra[$ggsett][$IDsottotip][0]=0;//ID
											$arrextra[$ggsett][$IDsottotip][1]=$pers;//num
											$arrextra[$ggsett][$IDsottotip][2]=$row['1'];//num
										}
										//$arrextra[$ggsett][3]=$row['4'];//IDpren
									}
								}
							}
						}
						//inizia la stampa
									
						$testo.= '
		<div class="content-block-title"  style="margin-top:-18px; text-align:center; background:#e57511;color:#fff; line-height:30px; height:30px; border-radius:5px; padding:0px; overflow:hidden; position:relative; ">
		<input type="text" id="datacentro" style="position:absolute; top:0px; left:0px; opacity:0; width:100%; height:30px;">
		<table width="100%;" style="margin-top:-2px; margin-left:-2px;"><tr><td width="50%;" style="background:#d13b23;">'.dataita4($time).'</td><td>'.dataita4(($time+86400*$gg)).'</td></tr></table></div><br>
		

						
						
						
						
						<div class="timeline verticale" style="margin-top:-15px;">
						
						';
						
						
						
						for($i=0;$i<$gg;$i++){
							$timeextra=$time+$i*86400;	
							$ggs=date('d',$timeextra);
							$testo.= '
								<div class="timeline-item" style="color:#333;margin-top:-10px; " >
										 <div class="timeline-item-date"><b>'.$ggs.'</b><br><small>'.$giorniita3[date('N',$timeextra)].'</small></div>
										 <div class="timeline-item-divider"></div>
											 <div class="timeline-item-content" style="width:100%;padding:0px;">
							';
							
							foreach($arrsotto as $IDsotto =>$sotton){
								
								$testo.='<div class="timeline-item-time button buttcc" onclick="navigation(13,'."'".$timeextra.",".$IDsotto.",2'".',0)">'.$sotton.'</div>';
								if(isset($arrextra[$ggs][$IDsotto])){
									$nott='';
									$pers=$arrextra[$ggs][$IDsotto][1];
									$IDgroup=$arrextra[$ggs][$IDsotto][0];
									$num=$arrextra[$ggs][$IDsotto][2];
									
									if($arrextra[$ggs][$IDsotto]['0']!=0){
										$query2="SELECT GROUP_CONCAT(CONCAT('<b>',s.nome,' ',s.cognome,'</b>:',s.noteristo) SEPARATOR '<br>') FROM infopren as i,schedine as s WHERE i.ID IN($IDgroup) AND i.IDcliente=s.ID AND s.noteristo!=''";
										$result2=mysqli_query($link2,$query2);
										
										if(mysqli_num_rows($result2)>0){
											$row2=mysqli_fetch_row($result2);
											$notecli=$row2['0'];	
											if($notecli!=''){
												$nott='<span style="font-size:10px;color:#888;">'.mysqli_real_escape_string($link2,$notecli).'</span>';
											}
										}
									}
									
									
									$testo.='
									
									<div  onclick="navigation(13,'."'".$timeextra.",".$IDsotto.",2'".',0)">
										  <div class="timeline-item-title" style="font-size:13px; color:#a91926; font-weight:600; margin-left:10px;">'.$num.' Tavoli  ('.$pers.' <i class="material-icons" style="font-size:14px;">person</i>)<br>'.$nott.'</div>
																
									</div>
									<hr>
									';
									
								}
								
							
							}
							
							/*
							*/
							
						
							$testo.= '</div></div>';
						}
				
					$testo.='</div>';
					
					
	

				

			echo $testo.'<br><br><br><br><br>';	

?>