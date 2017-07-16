<?php
$solotxtinto=0;
if(!isset($inc)){
	header('Access-Control-Allow-Origin: *');
	include('../../config/connecti.php');
	include('../../config/funzioni.php');
	include('../../config/funzionilingua.php');
	
	$IDutente=$_SESSION['ID'];
	$IDstruttura=$_SESSION['IDstruttura'];
		
		
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
	
	
	$data=date('Y-m-d',$time);
	
	$dataini=$data;
	$datafin=date('Y-m-d',($time+86400*7));
	$IDtipo=0;
	if(isset($_GET['dato1'])){
		$IDsottotip=$_GET['dato1'];
	}else{
		if(isset($_SESSION['IDsottotip'])){
			$IDsottotip=$_GET['dato1'];
		}else{
			$query2="SELECT ID FROM sottotipologie WHERE IDstr='$IDstruttura' AND IDmain ='1' ORDER BY ord LIMIT 1";
			$result2=mysqli_query($link2,$query2);
			$row=mysqli_fetch_row($result2);
			$IDsottotip=$row['0'];
		}
	}

	if(isset($_GET['dato2'])){
		if($_GET['dato2']==1){
			$solotxtinto=1;
		}
	}


	$_SESSION['IDsottotip']=$IDsottotip;
	$query2="SELECT IDmain,sottotipologia FROM sottotipologie WHERE ID='$IDsottotip' LIMIT 1";
	$result2=mysqli_query($link2,$query2);
	$row=mysqli_fetch_row($result2);
	$IDtipo=$row['0'];
	$sottotipname=$row['1'];
	$inc=1;
}
$_SESSION['visristo']=1;
unset($_SESSION['orario']);
//estrazione IDsottotip
$ricrea=1;
if(isset($_SESSION['datecentro'])){
	if($_SESSION['datecentro'][0]==date('Y-m-d',$time)){
		$ricrea=0;
	}
}
if($ricrea==1){
	$_SESSION['datecentro'][0]=date('Y-m-d',$time);
}

$testo='<input type="hidden" id="IDsottoristogiorno" value="'.$IDsottotip.'">
<input type="hidden" id="timeristogiorno" value="'.$time.'">
';

$numtsala=array();


				list($yy, $mm, $dd) = explode("-", $data);
				$time0=mktime(0, 0, 0, $mm, $dd, $yy);
				$timef=$time0+86400;
				
				
				$orari=array();
				$steps=3600;
				
					$maxp=array();
					$sale=array();
					$IDsalamain=0;
					$query="SELECT s.ID,s.nome,s.maxp FROM sale as s,saleassoc as sc WHERE sc.IDsotto='$IDsottotip' AND sc.ID=s.ID ORDER BY sc.priorita";
					$result=mysqli_query($link2,$query);
					if(mysqli_num_rows($result)>0){
						while($row = mysqli_fetch_row($result)){
							if($IDsalamain==0)$IDsalamain=$row['0'];
							$sale[$row['0']]=$row['1'];
							$maxp[$row['0']]=$row['2'];
						}
					}
						
	
				$firstmain=0;
	
					$query="SELECT GROUP_CONCAT(IDpren SEPARATOR ','),COUNT(*) FROM prenextra WHERE FROM_UNIXTIME(time,'%Y-%m-%d')='$data' AND IDstruttura='$IDstruttura' AND IDtipo='1' AND sottotip='$IDsottotip'  GROUP BY sottotip";
					$result=mysqli_query($link2,$query);
					if(mysqli_num_rows($result)>0){
						$row=mysqli_fetch_row($result);
						$IDprennot=$row['0'];
						$num=$row['1'];
					}else{
						$IDprennot=0;
						$num=0;
					}
					
					$group='';
					$query="SELECT GROUP_CONCAT(t.ID SEPARATOR ',') FROM tavoli as t,tavolipren as tp WHERE FROM_UNIXTIME(timefor,'%Y-%m-%d')='$data'  AND t.IDsottotip='$IDsottotip' AND t.ID=tp.IDtav AND tp.IDpren IN($IDprennot) GROUP BY t.IDsottotip";
					$result=mysqli_query($link2,$query);
					if(mysqli_num_rows($result)>0){
						$row=mysqli_fetch_row($result);
						$group=$row['0']; //tavoli con un servizio prenextra
					}
					if(strlen($group)==0)$group=0;
			
					$query="SELECT ID FROM tavoli WHERE FROM_UNIXTIME(timefor,'%Y-%m-%d')='$data'  AND IDstr='$IDstruttura' AND attivo>='1' AND IDsottotip='$IDsottotip'  AND ID NOT IN($group)";
					$result=mysqli_query($link2,$query);
					$num+=mysqli_num_rows($result);
					
					
					$k=0;
					$meta=ceil($num/2);
					$i=0;	
					
					$IDpreng=array();
		
					$txtk=array(array());
		
		
					
					$query="SELECT P.ID,P.time,P.note,P.IDpren,P.modi,S.servizio,GROUP_CONCAT(P.ID SEPARATOR ','),P.sala FROM prenextra as P,servizi AS S,prenextra2 as p2 WHERE P.time>'$time0' AND P.time<='$timef' AND P.IDstruttura='$IDstruttura' AND P.IDtipo='1' AND P.sottotip='$IDsottotip' AND P.extra=S.ID AND P.modi>='0' AND p2.IDprenextra=P.ID AND p2.qta>'0' GROUP BY P.sottotip,P.IDpren ORDER BY P.time,P.sala DESC,p2.qta ";
		
					$result=mysqli_query($link2,$query);
					
					if(mysqli_num_rows($result)>0){
						while($row=mysqli_fetch_row($result)){
							$IDpren=$row['3'];
								if(!in_array($IDpren,$IDpreng)){	
								
									
								
								$IDprenunit=prenotstessotav($IDpren,$IDpreng);
								$IDprenunitmain=$IDprenunit;
								
								$IDprenextra=$row['0'];
								$timeprenextra=$row['1'];
								$note=$row['2'];
								$servizio=$row['5'];
								$IDsalap=$row['7'];
									
								$query2="SELECT t.ID,t.num,t.attivo,t.IDpersonale FROM tavoli as t,tavolipren as tp WHERE tp.IDpren='$IDpren'  AND t.IDsottotip='$IDsottotip' AND t.attivo>='1' AND tp.IDtav=t.ID AND t.timefor>'$time0' AND t.timefor<='$timef' ";
								
								$result2=mysqli_query($link2,$query2);
								if(mysqli_num_rows($result2)>0){
									$row2=mysqli_fetch_row($result2);
									$nometavolo=''.$row2['1'];
									$IDtavolo=$row2['0'];	
									$attivo=$row2['2'];
									$IDperson=$row2['3'];
								}else{
									$nometavolo='-';
									$IDtavolo=0;
									$attivo=0;
									$IDperson=0;
								}
								$colort="";$colort2="fff";
								if($row['4']<='1'){$colort="info";}
								if($row['4']=='2'){$colort="success";}
								
								$nomepren='';
								$nomeapp='';
						
								if($IDtavolo==0){
									
									$query2="SELECT GROUP_CONCAT(P.IDpren SEPARATOR ',') FROM prenextra as P WHERE P.time>'$time0' AND P.time<='$timef' AND P.IDstruttura='$IDstruttura' AND P.IDtipo='1' AND P.sottotip='$IDsottotip' AND P.modi>='0' AND P.IDpren IN($IDprenunit) GROUP BY P.IDstruttura";
									$result2=mysqli_query($link2,$query2);
									$row2=mysqli_fetch_row($result2);
									$IDprenunit=$row2['0'];
									
									
									$arr=explode(',',$IDprenunit);
									foreach ($arr as $dato){
										array_push($IDpreng,$dato);
									}
										
									
									
								}else{
									//controlla in base a persone interne //IDinfop
									
									
									$query2="SELECT GROUP_CONCAT(IDinfop SEPARATOR ','),COUNT(*),attivo  FROM personetav WHERE IDtavolo='$IDtavolo' AND IDinfop!='0'  GROUP BY IDtavolo ";
									$result2=mysqli_query($link2,$query2);
									if(mysqli_num_rows($result2)>0){
										$row2=mysqli_fetch_row($result2);
										$IDgroup=$row2['0'];
										$npers=$row2['1'];
									}else{
										$IDgroup=0;
									}
									if(strlen($IDgroup)==0){$IDgroup=0;}
			
									$query2="SELECT GROUP_CONCAT(DISTINCT(IDpren) SEPARATOR ',') FROM infopren WHERE ID IN($IDgroup) GROUP BY IDstr";
									$result2=mysqli_query($link2,$query2);
									$row2=mysqli_fetch_row($result2);
									
									$IDprenunit=$row2['0'];
									
									$arr=explode(',',$IDprenunit);
									foreach ($arr as $dato){
										array_push($IDpreng,$dato);
									}
								}
			
			
									
								
								$query2="SELECT a.nome,p.IDv FROM appartamenti as a,prenotazioni as p WHERE p.IDv IN($IDprenunit) AND p.IDstruttura='$IDstruttura' AND p.app=a.ID";
								$result2=mysqli_query($link2,$query2);
								$nomeapp='(';
								if(mysqli_num_rows($result2)>0){
									while($row2=mysqli_fetch_row($result2)){
										if($IDtavolo!=0){
											$query3="SELECT IDpren FROM tavolipren WHERE IDtav='$IDtavolo' AND IDpren='".$row2['1']."' LIMIT 1";
											$result3=mysqli_query($link2,$query3);
											if(mysqli_num_rows($result3)>0){
												$nomepren.='<b>'.estrainome($row2['1']).'</b><br>';
												$nomeapp.=''.$row2['0'].' , ';
											}else{
												$nomepren.='<b style="color:#dc2b00;">'.estrainome($row2['1']).'</b><br>';
												$nomeapp.=''.$row2['0'].' , ';
											}
										}else{
											$nomepren.='<b>'.estrainome($row2['1']).'</b><br>';
											$nomeapp.=''.$row2['0'].' , ';
										}
									}
									$nomepren=substr($nomepren, 0, strlen($nomepren)-5).'<br>'; 
									$nomeapp=substr($nomeapp, 0, strlen($nomeapp)-3); 
			
									$nomeapp.=')';
								}else{
									$nomeapp="";
									$nomepren="";
								}
								
								
								if($IDtavolo==0){				
									$query2="SELECT GROUP_CONCAT(p2.IDinfop SEPARATOR ','),COUNT(*)  FROM prenextra as p,prenextra2 as p2 WHERE p.time>'$time0' AND p.time<='$timef' AND p.IDstruttura='$IDstruttura' AND  p.sottotip='$IDsottotip' AND p2.IDprenextra=p.ID AND p.modi>='0' AND p2.qta>'0' AND p.IDpren IN($IDprenunit) GROUP BY p.IDstruttura ";
									$result2=mysqli_query($link2,$query2);
									$row2=mysqli_fetch_row($result2);
									$IDgroup=$row2['0'];
									$npers=$row2['1'];
					
								
								}else{
									$query2="SELECT GROUP_CONCAT(IDinfop SEPARATOR ','),COUNT(*)  FROM personetav WHERE IDtavolo='$IDtavolo' AND IDinfop!='0' AND attivo='1' GROUP BY IDtavolo ";
									$result2=mysqli_query($link2,$query2);
									$row2=mysqli_fetch_row($result2);
									$IDgroup=$row2['0'];
									$npers=$row2['1'];
								}
	
								
								
								$notecli='';
								$query2="SELECT i.ID FROM infopren as i,schedine as s WHERE i.ID IN($IDgroup) AND i.IDcliente=s.ID AND s.noteristo!='' LIMIT 1";
								$result2=mysqli_query($link2,$query2);
								$nott='';
								if(mysqli_num_rows($result2)>0){
									$nott='<br><b style="color:#bb2c1d;font-size:10px;">Ci sono delle note</b>';
								}
	
								
								$funct='nuovotavolo('.$IDtavolo.','.$row['3'].')';
								if($nott==1){
									$cl='infcol';
								}else{
									$cl='setcol';
								}
		
									$statop='Prenotato';
									switch($attivo){
										case 1:
											$statop='In Sospeso';
											$class='tav1';
										break;
										case 2:
											$statop='Stampato';
											$class='tav2';
										break;
										case 3:
											$statop='Prenotato';
											$class='tav4';
										break;
										default:
											$statop='Prenotato';
											$class='tav3';
										break;
									}
									
									//$azz='';
									
									if($IDsalap==0){$IDsalap=$IDsalamain;}
									
									if(!isset($txtk[$IDsalap])){
										$txtk[$IDsalap]='';
										$numtsala[$IDsalap]=0;
										//$azz='aaa';
									}
									$numtsala[$IDsalap]++;
	
								$txtk[$IDsalap].='
								
									
									<li>
									  <a href="#" onclick="navigation(15,'.$IDprenextra.',0,0)" class="item-link item-content">
										<div class="item-media"><div class="ntavolo '.$class.'">'.$nometavolo.'</div></div>
										<div class="item-inner">
										  <div class="item-title">'.$nomepren.'<br><span style="font-size:11px;color:#666; font-weight:400;">'.$nomeapp.' '.$servizio.'</span>'.$nott.'</div>
										  <div class="item-after"><table><tr><td style="border-right:solid 1px #ccc; color:#1649b1;">
											 '.$npers.' <i class="material-icons" style="font-size:15px; color:#1649b1;">person</i>
											 </td>
											 <td>
											 '.date('H:i',$timeprenextra).'
											 </td></tr></table></div>
										</div>
									  </a>
									</li>
									
								
								
								
								
								';
	
	
	
	
	
								}
						}
					}
					
		
				
		$query="SELECT ID,num,note,menu,attivo,IDpersonale,timefor FROM tavoli WHERE timefor>'$time0' AND timefor<='$timef' AND IDstr='$IDstruttura' AND attivo>='1' AND IDsottotip='$IDsottotip' AND ID NOT IN($group) ORDER BY time DESC ";
		$result=mysqli_query($link2,$query);
		if(mysqli_num_rows($result)>0){
			while($row=mysqli_fetch_row($result)){
				$IDperson=$row['5'];
				$IDtav=$row['0'];				
				$group2='0';		
				$query2="SELECT GROUP_CONCAT(IDinfop SEPARATOR ',') FROM personetav WHERE IDtavolo='$IDtav' AND IDinfop!='0' AND attivo='1' GROUP BY IDtavolo ";
				$result2=mysqli_query($link2,$query2);
				if(mysqli_num_rows($result2)>0){
					$row2=mysqli_fetch_row($result2);
					$IDgroup=$row2['0'];
					if(strlen($IDgroup)>0){
						$query2="SELECT GROUP_CONCAT(DISTINCT(IDpren) SEPARATOR ',') FROM infopren WHERE ID IN($IDgroup) GROUP BY IDstr";
						$result2=mysqli_query($link2,$query2);
						$row2=mysqli_fetch_row($result2);
						$group2=$row2['0'];	
					}else{
						$query2="SELECT GROUP_CONCAT(IDpren) FROM tavolipren WHERE IDtav='$IDtav' GROUP BY IDtav";
						$result2=mysqli_query($link2,$query2);
						
						if(mysqli_num_rows($result2)>0){
							$row2=mysqli_fetch_row($result2);
							$group2=$row2['0'];
							if(strlen($group2)==0)$group2='0';
						}	
					}
					
				}else{
					$query2="SELECT GROUP_CONCAT(IDpren) FROM tavolipren WHERE IDtav='$IDtav' GROUP BY IDtav";
					$result2=mysqli_query($link2,$query2);
					$group2='0';
					if(mysqli_num_rows($result2)>0){
						$row2=mysqli_fetch_row($result2);
						$group2=$row2['0'];
						if(strlen($group2)==0)$group2='0';
					}				
				}
				
				if($group2==0){
					$note=$row['2'].'<br><span style="font-size:11px;">'.$menus[$row['3']].'</span>';
				}else{
					$note=estrainome($group2).'<br><span style="font-size:11px;">('.estrainomeapp($group2,0).')</span>';
				}
				
				
				$funct='openord('.$row['0'].')';
				
				$tdadd='<td class="infoicon setcol" label="'.$row['0'].'" style="width:30px;" onclick="nuovotavolo('.$row['0'].',0,1)"></td>';
				
				//$tdadd='<td class="settingmod3" style="width:30px;background-color:#ccc;" onclick="nuovotavolo('.$row['0'].',0,1)"></td>';
				
				$servizio=$menus[$row['3']];
				
				$query2="SELECT ID FROM personetav WHERE IDtavolo='$IDtav' AND attivo='1'";
				$result2=mysqli_query($link2,$query2);
				$numerop=mysqli_num_rows($result2);
				
				
			
				$statop='Prenotato';
				switch($row['4']){
					case 1:
						$statop='In Sospeso';
						$class='tav1';
					break;
					case 2:
						$statop='Stampato';
						$class='tav2';
					break;
					case 3:
						$statop='Prenotato';
						$class='tav4';
					break;
					default:
						$statop='Prenotato';
						$class='tav3';
					break;
				}
		
				
				
				if(!isset($txtk[$IDsalamain])){$txtk[$IDsalamain]='';}								
									$txtk[$IDsalamain].='
									
										<li class="accordion-item">
										 <a href="#" class="item-content item-link">
										  <div class="item-inner">
										  	<div class="item-media">'.$row['1'].'</div>
											<div class="item-title" >'.$note.'<br><span style="font-size:11px;">'.$person[$IDperson].'</span></div>
											<div class="item-after">'.date('H:i',$row['6']).'</div>
										  </div>
										  </a>
										
										 <div class="accordion-item-content">
											<div class="content-block">
											</div>
										  </div>
										</li>
									
									';
				
			}
		}
				
				
						$testo.= ' 
					<div class="subnavbar">
							  <div class="buttons-row" >
								';
	
					$txtinto='';
					
					foreach($sale as $IDsala =>$nomesala){
							$active='';
						if($firstmain==0){
							$active='active';
							$firstmain++;
						}	
						$badge='';	
						if(isset($txtk[$IDsala])){
							$badge='<div class="badge bg-red bagristo">'.$numtsala[$IDsala].'</div>';
						}
							
						$testo.='<a href="#IDsalaristo'.$IDsala.'"  onclick="IDtabac2='."'IDsalaristo".$IDsala."';".'" class="tab-link button   '.$active.' " style="overflow:visible;">'.$nomesala.'
						'.$badge.'
						</a>';
								
						$txtinto.='<div id="IDsalaristo'.$IDsala.'" class="tab   '.$active.'" >
						
						';
						
						
						if(isset($txtk[$IDsala])){
							$txtinto.='<br/><div class="list-block">
      							<ul>'.$txtk[$IDsala].'<ul></div>';
							
							/*sort($txtk[$IDsala]);
							foreach ($txtk[$IDsala] as $times =>$dato){
								$txtinto.=$dato;
							}*/
							//$txtinto.='<ul></div>';
						}else{
						
						}
	
						
						
						$txtinto.='</div>';
						
					}
						
					$testo.='
					</div>
					</div>
					</div>
		       	 <div class="page-content">
		     	 <div class="content-block" id="ristorantegiornodiv" style="padding:0px; width:100%;">
					
						<div class="tabs-swipeable-wrap" >
						<div class="tabs"  valign="top" id="tabscentro">
							'.$txtinto.'
							</div>	
						  </div>
						 
						</div>
						 </div>
						  ';
						  
		if($solotxtinto==0){
			echo $testo;	
		}else{
			echo $txtinto;
		}

?>