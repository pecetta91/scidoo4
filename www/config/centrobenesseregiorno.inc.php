<?php
if(!isset($inc)){
	//echo 'aaa';
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
			$time=time();
		}
	}else{
		$time=time();
	}
	$_SESSION['timecal']=$time;
	$IDtipo=0;
	if(isset($_GET['dato1'])){
		if($_GET['dato1']==2){
			$IDtipo=2;
			$IDsottotip=2;
		}else{
			$IDsottotip=$_GET['dato1'];
		}
	}else{
		if(isset($_SESSION['IDsottotip'])){
			$IDsottotip=$_GET['dato1'];
		}else{
			$query2="SELECT ID FROM sottotipologie WHERE IDstr='$IDstruttura' AND IDmain ='4' ORDER BY ord LIMIT 1";
			$result2=mysqli_query($link2,$query2);
			$row=mysqli_fetch_row($result2);
			$IDsottotip=$row['0'];
		}
	}


	
	$_SESSION['IDsottotip']=$IDsottotip;
}
unset($_SESSION['orario']);

	$_SESSION['timecal']=$time;
	$mm=date('m',$time);
	$aa=date('Y',$time);
	$mmsucc=$mm+1;

$data=date('Y-m-d',$time);

if($IDtipo==0){
	$query2="SELECT IDmain FROM sottotipologie WHERE ID='$IDsottotip' LIMIT 1";
	$result2=mysqli_query($link2,$query2);
	$row=mysqli_fetch_row($result2);
	$IDtipo=$row['0'];
}
//estrazione IDsottotip

list($yy, $mm, $dd) = explode("-", $data);
$time0=mktime(0, 0, 0, $mm, $dd, $yy);

$testo='<input type="hidden" id="IDsottocentrogiorno" value="'.$IDsottotip.'">
<input type="hidden" id="timecentrogiorno" value="'.$time0.'">
';

$_SESSION['datecentro']=array();
$_SESSION['datecentro'][0]=date('Y-m-d',$time);

				
				$orari=array();
				$steps=3600;
				if($IDtipo==2){
					$query="SELECT orai,oraf FROM strutture WHERE ID='$IDstruttura' LIMIT 1";
					$result=mysqli_query($link2,$query);
					if(mysqli_num_rows($result)>0){
						$row=mysqli_fetch_row($result);
							$orai=$time0+$row['0'];
							$oraf=$time0+$row['1'];
							for($orai;$orai<=$oraf;$orai+=$steps){
								array_push($orari,$orai);
						}
					}
					
					$query="SELECT s.ID,s.nome,s.maxp FROM sale as s,saleassoc as sc,sottotipologie as st WHERE st.IDmain='2' AND st.ID=sc.IDsotto AND sc.ID=s.ID AND s.IDstr='$IDstruttura' GROUP BY s.ID";
					$result=mysqli_query($link2,$query);
					if(mysqli_num_rows($result)>0){
						while($row=mysqli_fetch_row($result)){
							$sale[$row['0']]=$row['1'];
							$maxp[$row['0']]=$row['2'];
						}
					}
					
					$perscolor=array();
					$persnome=array();
					$IDpersarr=array();
					$query2="SELECT personale.ID,personale.nome,personale.color FROM personale,mansioni,mansionipers WHERE mansionipers.IDstruttura='$IDstruttura' AND mansionipers.mansione=mansioni.ID AND mansioni.tipo ='2' AND personale.attivo='1' AND mansionipers.IDpers=personale.ID ORDER BY personale.ID";
					$result2=mysqli_query($link2,$query2);
					$num2=mysqli_num_rows($result2);
					if($num2>0){
						while($row2=mysqli_fetch_row($result2)){
							$IDpers=$row2['0'];
							array_push($IDpersarr,$IDpers);
							$perscolor[$IDpers]=$row2['2'];
							$persnome[$IDpers]=$row2['1'];
							$testo.= '<input type="hidden" value="'.$row2['2'].'" id="IDpers'.$IDpers.'">';
						}
					}
										
					
				}else{
					
					$query="SELECT orarioi,orariof,ID FROM orarisotto WHERE IDsotto='$IDsottotip' ORDER BY orarioi";
					$result=mysqli_query($link2,$query);
					if(mysqli_num_rows($result)>0){
						while($row=mysqli_fetch_row($result)){
							$orai=$time0+$row['0'];
							$oraf=$time0+$row['1'];
							for($orai;$orai<=$oraf;$orai+=$steps){
								array_push($orari,$orai);
							}
						}
					}
					
					$maxp=array();
					$sale=array();
					
					$query="SELECT s.ID,s.nome,s.maxp FROM sale as s,saleassoc as sc WHERE sc.IDsotto='$IDsottotip' AND sc.ID=s.ID ORDER BY sc.priorita LIMIT 1";
					$result=mysqli_query($link2,$query);
					if(mysqli_num_rows($result)>0){
						while($row = mysqli_fetch_row($result)){
							$sale[$row['0']]=$row['1'];
							$maxp[$row['0']]=$row['2'];
						}
					}
						
				}
				
				
				$mattino[0]=array('0','1','2','3','4','5','6','7','8','9','10','11','12');
				$mattino[1]=array('13','14','15','16','17','18','19');
				$mattino[2]=array('20','21','22','23','24');
				$mattinotxt=array('Mattino','Pomeriggio','Sera');
				
			
				
				$arrsum=array();
				
				
				
				
				$firstmain=0;
				
				switch($IDtipo){
					case 2:
					case 4:
					
					$testo.= '  <div class="subnavbar" style="margin-top:5px;">
							  <div class="buttons-row" >
								';
	
					$txtinto='';
					foreach($sale as $IDsala =>$nomesala){
						$active='';
						if($firstmain==0){
							$active='active';
							$firstmain++;
						}	
						
						//estrai servizi in questa sala				
						$arrserv=array();
					if($IDtipo==4){
						$query2="SELECT  p.ID,p.time,p.durata,p.IDpren,SUM(p2.qta),p.esclusivo,s.servizio FROM prenextra as p,prenextra2 as p2,servizi as s WHERE p.sottotip='$IDsottotip' AND p2.IDprenextra=p.ID AND FROM_UNIXTIME(p.time,'%Y-%m-%d')='$data' AND p.sala='$IDsala' AND modi>'0' AND s.ID=p.extra  GROUP BY p.ID ORDER BY p.time";
					}else{
						$query2="SELECT  p.ID,p.time,p.durata,p.IDpren,1,p.IDpers,s.servizio FROM prenextra as p,prenextra2 as p2,servizi as s WHERE p.IDtipo='2' AND p2.IDprenextra=p.ID AND FROM_UNIXTIME(p.time,'%Y-%m-%d')='$data' AND p.sala='$IDsala' AND modi>'0' AND p.extra=s.ID  GROUP BY p.ID ORDER BY p.time";
					}
					
					$result2=mysqli_query($link2,$query2);
					$nn=mysqli_num_rows($result2);
					
					$badge='';	
					if($nn>0){
						$badge='<div class="badge bg-red bagristo">'.$nn.'</div>';
					}
					
					$testo.='<a href="#IDsalacentro'.$IDsala.'" class="tab-link   '.$active.' button " onclick="IDtabac2='."'IDsalacentro".$IDsala."';".'"  style="overflow:visible;">'.$nomesala.$badge.'</a>';
								
					$txtinto.='<div id="IDsalacentro'.$IDsala.'" class="tab '.$active.'">';
					
					
					
					if($nn>0){
						//if(!isset($arrsum[$IDsala])){$arrsum[$IDsala]=0;}
						//$arrsum[$IDsala]+$nn;
						while($row2=mysqli_fetch_row($result2)){
	
							$timees=$row2['1'];
							$durata=$row2['2'];
							$IDpren=$row2['3'];
							$servizio=$row2['6'];
							
							
											
							if($durata<30)$durata=30;
							$esclusivo='';
							$back='';
							$back2='';
							$ntav='';
							$subs='';
							
							if($IDtipo==4){
								if($row2['5']=='1'){
									$back='style="background:#aa1919; color:#fff;"';
								//	$back2='style="background:#777; color:#fff; max-width:120px; border-radius:5px;"';
									$esclusivo='<br><b>Esclusivo</b>';
									$ntav='E';
								}
							}else{
								//$perscolor[$IDpers]=$row2['2'];
								//$persnome[$IDpers]=$row2['1'];
								
								$back='style="background:#'.$perscolor[$IDpers].'; color:#fff;"';
								//$back2='style="background:#fff; margin:auto; float:right;  text-align:center;; color:#333; padding:1px; border-radius:5px;"';
								//$esclusivo='<br><b style=" font-size:12px">'.$persnome[$row2['5']].'</b>';
								$ntav=substr($persnome[$row2['5']],0,1);
								$subs='<b style="font-size:8px;">'.$persnome[$row2['5']].'</b>';
							}
							
							$nome=estrainome($IDpren);
							
							$query3="SELECT a.nome FROM prenotazioni as p,appartamenti as a WHERE p.IDv='$IDpren' AND p.app=a.ID LIMIT 1";
							$result3=mysqli_query($link2,$query3);
							$row3=mysqli_fetch_row($result3);
							$nomeapp=$row3['0'];
							
						
							
							$tt='
							<li onclick="modificaserv('.$row2['0'].',1,0,1)">
										 <div class="item-content" >
										 
										 <div id="ntavdiv" >
												<div class="ntavolo" '.$back.'>'.$ntav.'</div>'.$subs.'
											</div>
										 
										  <div class="item-inner">
											<div class="item-title">'.$nome.'<br><span style="font-size:11px;color:#666;">'.$servizio.' '.$esclusivo.'<br>'.$nomeapp.'</span>
											</div>
												<div class="item-after">
											<table style="margin-top:-10px;"><tr><td style="border-right:solid 1px #ccc;color:#d13b23;padding-right:5px;margin-right:5px;">'.date('H:i',$timees).'<br><span style="color:#777;">'.mintoore($durata).'<span></td>
											<td><b>'.$row2['4'].'</b><i class="material-icons">person</i></td></tr></table>
											
											
											</div>
											
										  </div>
										  </a>
										  </li>
							
							
							
							
							';
							
							$orar=date('G',$row2['1']);
							foreach($mattino as $key =>$dato){
								if(in_array($orar,$dato)){
									if(!isset($arrserv[$key]))$arrserv[$key]='';
									$arrserv[$key].=$tt;
									break;
								}
							}
						
						}
					}
				
					foreach ($arrserv as  $key=>$dato){
						
						$txtinto.='
						<div class="content-block-title titleb">'.$mattinotxt[$key].'</div>
								<div class="list-block"><ul>'.$dato.'</ul></div>';
						
					}
					
					$txtinto.='</div>';
						
					}
	
					$testo.='</div></div>
					</div>
			
				  <div class="page-content">
	              <div class="content-block" id="centrobenesseregiornodiv">
					
					
					
					
						<div class="tabs-animated-wrap" >
						<div class="tabs"  id="tabscentro">
							'.$txtinto.'
							</div>	
						  </div>
						</div>  
					
						  
						  ';
						  
		
					break;
					
				}


			$datagg=date('Y-m-d',$time0);
			$IDprensosp=getprenotazioni($datagg,0,$IDstruttura,1,1);
				switch($IDtipo){
					case 4:
					$query2="
					SELECT ID FROM prenextra WHERE IDpren IN ($IDprensosp) AND  modi='0' AND sottotip='$IDsottotip' AND IDstruttura='$IDstruttura'";
					break;
					case 2:
					$query2="SELECT ID FROM prenextra WHERE IDpren IN ($IDprensosp) AND  modi='0' AND IDtipo='2' AND IDstruttura='$IDstruttura'";
					break;
				}
					
				$result2=mysqli_query($link2,$query2);
				$num=mysqli_num_rows($result2);
				$testo.='<input type="hidden" value="'.$num.'" id="sospesi">';
					
			echo $testo;	
	
?>