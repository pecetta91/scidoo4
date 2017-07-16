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


if(isset($_GET['dato2'])){
	if(is_numeric($_GET['dato2'])){
		$vis=$_GET['dato2'];
	}
}else{
	if(isset($_SESSION['vis'])){
		$vis=$_SESSION['vis'];
	}else{
		$vis=1;
	}
}

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

$_SESSION['IDsottotip']=$IDsottotip;
$query2="SELECT IDmain,sottotipologia FROM sottotipologie WHERE ID='$IDsottotip' LIMIT 1";
$result2=mysqli_query($link2,$query2);
$row=mysqli_fetch_row($result2);
$IDtipo=$row['0'];
$sottotipname=$row['1'];
//estrazione IDsottotip
$ricrea=1;
if(isset($_SESSION['datecentro'])){
	if($_SESSION['datecentro'][0]==date('Y-m-d',$time)){
		$ricrea=0;
	}
}
if($ricrea==1){
	$_SESSION['datecentro']=array();
	if($vis==1){
		for($j=0;$j<7;$j++){
			array_push($_SESSION['datecentro'],date('Y-m-d',$time+$j*86400));
		}
	}else{
		$_SESSION['datecentro'][0]=date('Y-m-d',$time);
	}
}


$testo='<input type="hidden" id="IDsottoristo" value="'.$IDsottotip.'">
<input type="hidden" id="timeristo" value="'.$time.'">
<input type="hidden" id="IDtipovis" value="'.$vis.'">
';

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
				
				switch($vis){
					case 1:
						
						$arrextra=array();
						$arrpren=array();
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
									
									
									
									if(isset($arrextra[$ggsett])){
										$arrextra[$ggsett][0].=','.$IDgroup;//IDpers
										$arrextra[$ggsett][1]+=$pers;//num
										$arrextra[$ggsett][2]++;//num
										
									}else{
										$arrextra[$ggsett][0]=$IDgroup;//IDpers
										$arrextra[$ggsett][1]=$pers;//num
										$arrextra[$ggsett][2]=1;//num
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
				
				
								if(isset($arrextra[$ggsett])){
									$arrextra[$ggsett][1]+=$pers;//num
									$arrextra[$ggsett][2]+=$row['1'];
									//$arrextra[$ggsett][1]+=$row['1'];//num
								}else{
									$arrextra[$ggsett][0]=0;//ID
									$arrextra[$ggsett][1]=$pers;//num
									$arrextra[$ggsett][2]=$row['1'];//num
								}
								//$arrextra[$ggsett][3]=$row['4'];//IDpren
							}
						}
						
									
						$testo.= '<div style="width:100%; text-align:left;  border-bottom:solid 1px #394baa; font-size:25px; margin-top:-25px; font-weight:100; color:#394baa;"> &nbsp;'.$sottotipname.'</div><br>
						
						
						<div class="timeline" style="margin-top:-15px; margin-left:-18px;">
						
						';
						
						for($i=0;$i<7;$i++){
							$timeextra=$time+$i*86400;	
							$ggs=date('d',$timeextra);
							$testo.= '
								<div class="timeline-item" style="color:#333;margin-top:-10px; " >
										 <div class="timeline-item-date">'.$ggs.' <small>'.$giorniita2[date('N',$timeextra)].'</small></div>
										 <div class="timeline-item-divider"></div>
											 <div class="timeline-item-content" style="width:100%;">
							';
							
							if(isset($arrextra[$ggs])){
								$nott='';
								$pers=$arrextra[$ggs][1];
								$IDgroup=$arrextra[$ggs][0];
								$num=$arrextra[$ggs][2];
								
								if($arrextra[$ggs]['0']!=0){
									$query2="SELECT GROUP_CONCAT(CONCAT('<b>',s.nome,' ',s.cognome,'</b>:',s.noteristo) SEPARATOR '<br>') FROM infopren as i,schedine as s WHERE i.ID IN($IDgroup) AND i.IDcliente=s.ID AND s.noteristo!=''";
									$result2=mysqli_query($link2,$query2);
									
									if(mysqli_num_rows($result2)>0){
										$row2=mysqli_fetch_row($result2);
										$notecli=$row2['0'];	
										if($notecli!=''){
											$nott='<span>'.mysqli_real_escape_string($link2,$notecli).'</span>';
										}
									}
								}
								
								
								$testo.='
								
								<div class="timeline-item-inner"  onclick="navigationtxt(14,'."'".$timeextra.",".$IDsottotip.",2'".','."'ristorantediv'".',6)">
									  <div class="timeline-item-title" style="font-size:14px; color:#1649b1; font-weight:600;">'.$num.' Tavoli - '.$pers.' persone </div>
									  <div class="timeline-item-text" style="font-size:9px;">'.$nott.'</div>						
								</div>
								
								';
								
							}else{
								$testo.='
								<div class="timeline-item-content" onclick="navigationtxt(14,'."'".$timeextra.",".$IDsottotip.",2'".','."'ristorantediv'".',6)">
									  <div class="timeline-item-title" style="font-size:13px;">Non ci sono prenotazioni</div>
								</div>';
							
							
							
							}
						
							$testo.= '</div></div>';
						}
				
					$testo.='</div>';
					
					
					
					
					
					
					
						
					
					
					break;
					case 2:

					
					
								
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
		
		
					
					$query="SELECT P.ID,P.time,P.note,P.IDpren,P.modi,S.servizio,GROUP_CONCAT(P.ID SEPARATOR ','),P.sala FROM prenextra as P,servizi AS S,prenextra2 as p2 WHERE P.time>'$time0' AND P.time<='$timef' AND P.IDstruttura='$IDstruttura' AND P.IDtipo='1' AND P.sottotip='$IDsottotip' AND P.extra=S.ID AND P.modi>='0' AND p2.IDprenextra=P.ID AND p2.qta>'0' GROUP BY P.sottotip,P.IDpren ORDER BY P.time DESC,p2.qta ";
		
		
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
			/*
									if($IDtavolo==0){
										$IDinfo=$IDprenextra*-1;
										$funct='modportate('.$row['3'].',0,16,10,5)';
										$funcinfo='';
									}else{
										if($nometavolo==0){
											$funct='nuovotavolo('.$IDtavolo.',0,1)';
										}else{
											$funct='openord('.$IDtavolo.')';
										}
										$funcinfo='nuovotavolo('.$IDtavolo.',0,1)';
										$IDinfo=$IDtavolo;
									}
									$tdadd='<td class="infoicon '.$cl.'" label="'.$IDinfo.'" style="width:30px;" onclick="'.$funcinfo.'"></td>';
									*/
			
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
									/*
									$txt[$k].='<table class="tavolor" cellspacing="0" >
									<tr>
									<td class="'.$class.'">'.$nometavolo.'</td>
									<td class="orat">'.date('H:i',$timeprenextra).'</td>
									<td align="left" onclick="'.$funct.'">'.$nomepren.'<br><span style="font-size:11px;">'.$nomeapp.' '.$servizio.'</span></td>
									<td class="IDperst" onclick="'.$funct.'">'.$person[$IDperson].'</td>
									<td class="IDperst" onclick="'.$funct.'"><span>'.$statop.'</span></td>
									<td class="pers" onclick="'.$funct.'">'.$npers.'</td>
									'.$tdadd.'
									</tr></table>';*/
									
									if(!isset($txtk[$IDsalap][$timeprenextra])){$txtk[$IDsalap][$timeprenextra]='';}
									
									$txtk[$IDsalap][$timeprenextra].='
										<li class="accordion-item" style="border-top:solid 1px #f1f1f1;">
											<a href="#" class="item-content item-link">

											<div class="item-media" >
												<div class="ntavolo '.$class.'">'.$nometavolo.'</div>
												</div>
											<div class="item-inner">
											 <div class="item-title" style="line-height:14px;" >'.$nomepren.'<br><span style="font-size:11px;color:#666; font-weight:400;">'.$nomeapp.' '.$servizio.'</span>'.$nott.'</div>
											 <div class="item-after" >
											 
											 <table><tr><td style="border-right:solid 1px #ccc; color:#1649b1; padding-right:5px;">
											 '.$npers.' <i class="material-icons" style="font-size:15px; color:#1649b1;">person</i>
											 </td>
											 <td>
											 '.date('H:i',$timeprenextra).'
											 </td></tr></table>
											 </div>
										  </div>
		</a>
      <div class="accordion-item-content" style="background:#f5f5f5;">
        <div class="content-block">';
		
		
		 $txtk[$IDsalap][$timeprenextra].='	 
		 <div class="list-block" >
      		<ul>
       
		 
		 ';
	
	
	
	$query2="SELECT p2.IDinfop,p.extra  FROM prenextra as p,prenextra2 as p2 WHERE FROM_UNIXTIME(p.time,'%Y-%m-%d')=FROM_UNIXTIME('$time','%Y-%m-%d')  AND p.IDstruttura='$IDstruttura' AND  p.sottotip='$IDsottotip' AND p2.IDprenextra=p.ID AND p.modi>='0' AND p.IDpren IN($IDprenunit)  ";
	$result2=mysqli_query($link2,$query2);
	if(mysqli_num_rows($result2)>0){
		while($row=mysqli_fetch_row($result2)){
			$IDinfop=$row['0'];
			$extra=$row['1'];
			$query3="SELECT servizio FROM servizi WHERE ID='$extra' LIMIT 1";
			$result3=mysqli_query($link2,$query3);
			$row3=mysqli_fetch_row($result3);
			$servizio=$row3['0'];
			
			$nome=estrainomecli($IDinfop);
			$tipocli=estraitipocli($IDinfop);
			
			$notecli='';
			$query3="SELECT s.noteristo FROM infopren as i,schedine as s WHERE i.ID ='$IDinfop' AND i.IDcliente=s.ID AND s.noteristo!='' LIMIT 1";
			$result3=mysqli_query($link2,$query3);
			if(mysqli_num_rows($result3)>0){
				$row3=mysqli_fetch_row($result3);
				$notecli='<br><b style="color:#bb2c1d;">'.$row3['0'].'</b>';
			}
			
			$txtk[$IDsalap][$timeprenextra].='
			 <li class="item-content">
			  <div class="item-inner">
				<div class="item-title" style="line-height:12px; font-size:12px;">'.$nome.'<br><span style="font-size:10px;font-weight:400; color:#999;">'.$tipocli.''.$notecli.'</span></div>
				<div class="item-after">'.$servizio.'</div>
			  </div>
			</li>
			';
			
		}
	}
	
$txtk[$IDsalap][$timeprenextra].='</ul></div>

	<p class="buttons-row">
	  <a href="#" class="button button-raised button-fill color-orange" style="font-size:10px;color:#fff;" onclick="modificaserv('.$IDprenextra.',1,0,2)">Modifica Orario/Sala</a>
	  <a href="#" class="button button-raised button-fill color-pink" style="font-size:10px;color:#fff;">Tavolo Arrivato</a>
	</p>
';
		 
	$txtk[$IDsalap][$timeprenextra].='</div>
      </div>
    </li>';
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
				/*
				$txt[$k].='<table class="tavolor" cellspacing="0" >
				<tr>
				<td class="'.$class.'" >'.$row['1'].'</td>
				<td class="orat" >'.date('H:i',$row['6']).'</td>
				<td class="nome" align="left" onclick="'.$funct.'" >'.$note.'</td>
				<td class="IDperst" onclick="'.$funct.'">'.$person[$IDperson].'</td>
				<td class="IDperst" onclick="'.$funct.'"><span>'.$statop.'</span></td>
				<td class="pers"onclick="'.$funct.'">'.$numerop.'</td>
				'.$tdadd.'
				</tr></table>';*/
				
				
				if(!isset($txtk[$IDsalamain][$timeprenextra])){$txtk[$IDsalamain][$timeprenextra]='';}								
									$txtk[$IDsalamain][$timeprenextra].='
									
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
					
					<div style="width:100%; text-align:right; border-bottom:solid 1px #394baa; font-size:25px; margin-top:-25px; font-weight:100; color:#394baa;">
					<a href="#" class="button  color-indigo" onclick="navigationtxt(14,'."'".$time.",".$IDsottotip.",1'".','."'ristorantediv'".',6)" style="width:100px; font-weight:400; font-size:13px;padding-top:3px; height:35px; line-height:30px; margin:1px; float:left; display:inline-block">< Settimana</a>
					
					<div style="display:inline-block; margin-right:10px;">'.$sottotipname.'</div></div>				
					<br>
					
					
					<div  class="navbar " id="tabbardet" style="margin:auto;margin-top:-10px;margin-bottom:-50px; width:99%;background:transparent; padding:0px;">
							<div  style="height:32px; padding:0px;background:transparent;box-shadow:none;width:98%; ">
							  <div class="buttons-row" style=" box-shadow:none;  width:100%; padding-left:5px;">
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
							$badge='<div class="badge bg-red" style="right:4px; font-size:13px; font-weight:bold; height:17px; width:17px; position:absolute; top:-10px; line-height:100%;;">'.count($txtk[$IDsala]).'</div>';
						}
							
						$testo.='<a href="#IDsalaristo'.$IDsala.'" class="tab-link   '.$active.' button button-raised" style="font-size:10px; padding:0px; height:40px; overflow:visible;"><div style="width:100%;height:100%;  overflow:hidden;line-height:13px; padding-top:7px;">'.wordwrap($nomesala,10,'<br>').'</div>
						'.$badge.'
						</a>';
								
						$txtinto.='<div id="IDsalaristo'.$IDsala.'" class="tab   '.$active.'" style="overflow-y:visible; padding-top:-10px; margin-top:10px; color:#333;" align="left">
						
						';
						
						
						if(isset($txtk[$IDsala])){
							$txtinto.='<div class="list-block accordion-list" id="infoprentab" style="padding:0px;">
      							<ul>';
							
							sort($txtk[$IDsala]);
							foreach ($txtk[$IDsala] as $times =>$dato){
								$txtinto.=$dato;
							}
							$txtinto.='<ul></div>';
						}else{
						
						}
	
						
						
						$txtinto.='
						
						</div>';
						
					}
						
						
	
					$testo.='
					
					</div>
						<div class="tabs-animated-wrap" style="height:auto; min-height:1000px; margin-top:-10px;padding:0px; width:100%;">
						<div class="tabs" style="height:600px; padding:0px; width:100%;" align="center" valign="top" >
						
							'.$txtinto.'
							
							</div>	
						  </div>
						 
						</div>
						 </div>
						  ';
						  
		
					break;
					
					
					
				}




				
					$modb='';
					
					$modb.='
					<li><a href="#" class="list-button item-link" onclick="navigationtxt(14,'."'".$time.",".$IDsottotip.",2'".','."'ristorantediv'".',6)">Vista Giornaliera</a></li>
					<li><a href="#" class="list-button item-link" onclick="navigationtxt(14,'."'".$time.",".$IDsottotip.",1'".','."'ristorantediv'".',6)" >Vista Settimanale</a></li>
					
					';
					
					
					$modb2='';
					
				  	$testo.='<div id="menu10" style="display:none;" >'.base64_encode($modb).'</div>';
					
					$num=0;
					$query="SELECT ID,sottotipologia FROM sottotipologie WHERE IDmain='$IDtipo' AND IDstr='$IDstruttura' ORDER BY ord";
					$result=mysqli_query($link2,$query);
					if(mysqli_num_rows($result)){
						while($row=mysqli_fetch_row($result)){
							$num++;
							
							$tav='';
							if($vis==1){
								$query2="SELECT ID FROM prenextra WHERE 
			FROM_UNIXTIME(time,'%Y-%m-%d') BETWEEN '$dataini' AND '$datafin' AND IDstruttura='$IDstruttura' AND IDtipo='1' AND sottotip='".$row['0']."'  AND modi>='0' LIMIT 1 ";
							}else{
								$query2="SELECT ID FROM prenextra WHERE 
			FROM_UNIXTIME(time,'%Y-%m-%d') = '$data' AND IDstruttura='$IDstruttura' AND IDtipo='1' AND sottotip='".$row['0']."'  AND modi>='0' LIMIT 1 ";
							}
							$result2=mysqli_query($link2,$query2);
							$num2=mysqli_num_rows($result2);	
							if($num2>0){
								$tav='<b style="font-size:11px; float:right; color:#167cb1;">N.'.$num2.' Tav.</b>';
							}
							
							
							
							
							$modb2.='<li><a href="#" onclick="navigationtxt(14,'."'".$time.",".$row['0'].",".$vis."'".','."'ristorantediv'".',6)" class="list-button item-link"  >'.$row['1'].' '.$tav.'</a></li>';
						}
					}
				
					$testo.='
					
					<br><br><br><br><br>
					<div id="menu11" style="display:none;" >'.base64_encode($modb2).'</div>
					
     <div class="toolbar tabbar toolbar-bottom " id="bottomprofilo" style="height:52px;  transform:translateZ(0); webkit-transform:translateZ(0);background:#1c275e; z-index:99999; padding:0px; position:fixed; bottom:0px;">
			<div class="toolbar-inner">
				<a href="#" onclick="aprimod(10,this)" class="tab-link " style="height:52px;">
					<i class="icon" style="line-height:10px;"><div style="position:absolute;  margin-top:22px; margin-left:-21px; width:100%; text-align:center; font-size:8px;color:#fff;  ">Settimana / Giorno</div>
						<i class="material-icons" style="font-size:25px; color:#fff; margin-top:-5px; margin-left:5px;">view_list</i>
					</i>
				</a>
				<a href="#" class="tab-link" style="height:52px;">
					<input type="text" style="border:solid 1px #fff; width:80%; height:35px; margin:auto; background:transparent; font-size:16px; color:#fff; border-radius:4px; text-align:center;" id="datacentro" placeholder="Data" value="'.date('d/m/Y',$time).'">
				</a>
				<a href="#" class="tab-link" style="height:52px;" onclick="aprimod(11,this)">
						<i class="icon " style="line-height:10px;"><div style="position:absolute; margin-top:22px; margin-left:-5px; width:100%; text-align:center; font-size:8px;color:#fff;">Categorie</div>
						<i class="material-icons" style="color:#fff; font-size:25px; margin-top:-5px; margin-left:5px;">dehaze</i>
						
						<span class="badge bg-green"  style="margin-left:-4px; margin-top:-3px;">'.$num.'</span>
					</i>
				</a>
				
			</div>
		</div>
		';


			echo $testo;	

?>