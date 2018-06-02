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
	if((is_numeric($_GET['dato0']))&&($_GET['dato0']!='0')){
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

$gg=1;

$_SESSION['timecal']=$time;
$mm=date('m',$time);
$aa=date('Y',$time);
$mmsucc=$mm+1;

$giorni= date('N',$time);

$data=date('Y-m-d',$time);

$dataini=$data;
$datafin=date('Y-m-d',($time+86400*$gg));

unset($_SESSION['IDsottotip']);
$IDtipo=0;
//<input type="hidden" id="funccentro2" value="">//navigationtxt(13,'.$time.','."'centrobenesserediv'".',6)<input type="hidden" id="funccentro2" value="navigation(4,'.$time.',0,1)">



//onclick="myCalendar2.open();"

/*<div class="dataoggi">
				<div class="buttdateog calenddiv">'.$giorniita2[$giorni].
			    ' '.date('d',$time).'  '.$mesiita[date('n',$time)].'</div></div>*/

$testo='


<a href="#"  class="button button-round button-fill pulscentroben" id="prova"><i class="f7-icons fs13" >today</i> &nbsp;&nbsp;'.dataita($time).'</a>	


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
						
						
						
						
						
						
						
						
						
						$timeextra=$time;
						$ggs=date('d',$timeextra);
						
					$datagg=date('Y-m-d',$timeextra);
							//$groupid=getprenot($timeextra,$IDstruttura);
							
							$IDprensosp=getprenotazioni($datagg,0,$IDstruttura,1,1);
							
							$arrs=array();
							$arrp=array();
							
							
							
							$query2="SELECT COUNT(DISTINCT(p.ID)),p.sottotip,p.IDtipo,p.modi,SUM(p2.qta) FROM prenextra as p,prenextra2 as p2 WHERE ((p.IDpren IN ($IDprensosp) AND  p.modi='0') OR (FROM_UNIXTIME(p.time,'%Y-%m-%d')='$datagg' AND p.modi>'0')) AND p.IDtipo IN(2,4) AND p.IDstruttura='$IDstruttura' AND p.ID=p2.IDprenextra  GROUP BY p.sottotip,p.modi";
						//	echo $query2;
							
							
							$result2=mysqli_query($link2,$query2);
							$tot=mysqli_num_rows($result2);
							while($row2=mysqli_fetch_row($result2)){
								$modi=0;
								if($row2['3']!=0){
									$modi=1;
								}
								if($row2['2']==2){
									$arrs[$row2['2']][$modi]=$row2['0'];
									$arrp[$row2['2']][$modi]=$row2['4'];
									
								}else{
									$arrs[$row2['1']][$modi]=$row2['0'];
									$arrp[$row2['1']][$modi]=$row2['4'];
								}
								//$script.= '$("#sottotipo'.$IDinto.'").prepend('."'".'<div class="notific">'.$row2['0'].'</div>'."');";
							}
							
							
							
							//ksort($arrsotto);
					
							foreach($arrsotto as $IDsotto =>$sotton){
								$testo.='
								<div class="content-block-title titleb">'.strtoupper($sotton).'</div>

   ';
								$sospesi=0;
								$conf=0;
								$sospesitxt='';
								$conftxt='';
								if(isset($arrs[$IDsotto][0])){
									$sospesi=$arrs[$IDsotto][0];
								}	
								if(isset($arrs[$IDsotto][1])){
									$conf=$arrs[$IDsotto][1];	
								}
								
								if($sospesi>0){
									
									$sosptxt='Sospesi';
									if($sospesi==1){$sosptxt='Sospeso';}
									
									$sospesitxt='<strong><u>'.$sospesi.' '.$sosptxt.'</u></strong> <br/><span class="fs12" >'.$arrp[$IDsotto][0].' '.txtpersone($arrp[$IDsotto][0]).'</span>';
								}
								
								if($conf>0){
									$sosptxt='Confermati';
									if($conf==1){$sosptxt='Confermato';}
									$conftxt='<strong><u>'.$conf.' '.$sosptxt.'</u> </strong><br/><span class="fs12">'.$arrp[$IDsotto][1].' '.txtpersone($arrp[$IDsotto][1]).'</span>';
								}
								
								
								if(($sospesi+$conf)>0){
								
									$testo.='
									
									<div class="row rowlist no-gubber sospesoconf"  onclick="navigation(14,'."'".$timeextra.",".$IDsotto."'".',6)">
							
									<div class="col-50 centercol h40">
										'.$conftxt.'
									</div>
									
									<div class="col-50 centercol h40" >
										'.$sospesitxt.'
									</div>
									</div>
									
									
									';
									/*
									
									$testo.='
									
									
									
									
									
									
									<li >
									  <div  style="background:#f5a149; border-radius:3px; color:#fff;" href="#" class="item-content" onclick="navigation(14,'."'".$timeextra.",".$IDsotto."'".',6)">
										<div class="item-inner">
										  <div class="item-title">'.$conftxt.$sospesitxt.'</div>
										</div>
									  </div>
									</li>
									
									';*/
																
								}else{
									
										
									
									$testo.='
									
									<div class="row rowlist no-gubber prenotazionenorm"   onclick="navigation(14,'."'".$timeextra.",".$IDsotto.",2'".',0)">
							
									<div class="col-50 centercol c777">
										Nessuna Prenotazione
									</div>
									
									<div class="col-50 centercol">
										0 <i class="material-icons fs14" >person</i>
									</div>
									</div>
									
									
									
									';
									
									/*
								$testo.='
									
									
									<li>
									  <div href="#" class="item-content" onclick="navigation(14,'."'".$timeextra.",".$IDsotto.",2'".',0)">
										<div class="item-inner">
										  <div class="item-title">Nessuna prenotazione</div>
										  <div class="item-after">0 <i class="material-icons" style="font-size:14px;">person</i></div>
										</div>
									  </div>
									</li>
									
									';*/
									
									
								}
							
							}
							
					
	

				

			echo $testo.'<br><br><br><br><br>';	

?>