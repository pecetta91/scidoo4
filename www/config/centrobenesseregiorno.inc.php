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


$IDprensosp=getprenotazioni($data,0,$IDstruttura,1,1);
switch($IDtipo){
	case 4:
	$query2="
	SELECT COUNT(*) FROM prenextra WHERE IDpren IN ($IDprensosp) AND  modi='0' AND sottotip='$IDsottotip' AND IDstruttura='$IDstruttura'";
	break;
	case 2:
	$query2="SELECT COUNT(*) FROM prenextra WHERE IDpren IN ($IDprensosp) AND  modi='0' AND IDtipo='2' AND IDstruttura='$IDstruttura'";
	break;

}
//<input type="hidden" value="" id="sospesi">

$result2=mysqli_query($link2,$query2);
if(mysqli_num_rows($result2)>0){
	$row2=mysqli_fetch_row($result2);
	$sospgiorn=$row2['0'];
}




list($yy, $mm, $dd) = explode("-", $data);
$time0=mktime(0, 0, 0, $mm, $dd, $yy);

$testo='<input type="hidden" id="IDsottocentrogiorno" value="'.$IDsottotip.'">
<input type="hidden" id="IDtipocentrogiorno" value="'.$IDtipo.'">
<input type="hidden" id="timecentrogiorno" value="'.$time0.'">
<input type="hidden" id="datacentrogiorno" value="'.date('Y-m-d',$time0).'">
<input type="hidden" id="idstr" value="'.$IDstruttura.'">
<input type="hidden" id="numsosp" value="'.$sospgiorn.'">
';

$_SESSION['datecentro']=array();
$_SESSION['datecentro'][0]=date('Y-m-d',$time);

$arrserv=array();
$arrpers=array();

if($IDtipo==4){
	$query2="SELECT  p.ID,p.time,p.durata,p.IDpren,SUM(p2.qta),p.esclusivo,s.servizio,p.sala FROM prenextra as p,prenextra2 as p2,servizi as s WHERE p.sottotip='$IDsottotip' AND p2.IDprenextra=p.ID AND FROM_UNIXTIME(p.time,'%Y-%m-%d')='$data' AND p.modi>'0' AND p.sala!='0' AND s.ID=p.extra  GROUP BY p.ID";
}else{
	$query2="SELECT  p.ID,p.time,p.durata,p.IDpren,1,p.esclusivo,s.servizio,p.IDpers,p.sala FROM prenextra as p,prenextra2 as p2,servizi as s WHERE p.IDtipo='2' AND p2.IDprenextra=p.ID AND FROM_UNIXTIME(p.time,'%Y-%m-%d')='$data'  AND p.modi>'0' AND p.sala!='0' AND p.extra=s.ID  GROUP BY p.ID ";
}

$result2=mysqli_query($link2,$query2);
if(mysqli_num_rows($result2)>0){
	$i=0;
	while($row2=mysqli_fetch_row($result2)){
		$IDp=$row2['0'];
		$timep=$row2['1'];
		$duratap=$row2['2'];
		$IDprenp=$row2['3'];
		$pers=$row2['4'];
		$escl=$row2['5'];
		$servizio=$row2['6'];
		$IDsalapers=$row2['7'];
		
		if($IDtipo==4){
			$arrserv[$timep][$i][0]=$IDp;
			$arrserv[$timep][$i][1]=$duratap;
			$arrserv[$timep][$i][2]=$IDprenp;
			$arrserv[$timep][$i][3]=$pers;
			$arrserv[$timep][$i][4]=$escl;
			$arrserv[$timep][$i][5]=$servizio;
			$arrserv[$timep][$i][6]=$IDsalapers;
			$timefine=$timep+$duratap*60;
			for($j=$timep;$j<$timefine;$j+=900){
				if(!isset($arrpers[$j])){$arrpers[$j]=0;}
				$arrpers[$j]+=$pers;
			}
		}else{
			$IDsala=$row2['8'];
			$j=0;
			if(isset($arrserv[$IDsala][$timep])){$j=count($arrserv[$IDsala][$timep]);}
			
			$arrserv[$IDsala][$timep][$j][0]=$IDp;
			$arrserv[$IDsala][$timep][$j][1]=$duratap;
			$arrserv[$IDsala][$timep][$j][2]=$IDprenp;
			$arrserv[$IDsala][$timep][$j][3]=$servizio;
			$arrserv[$IDsala][$timep][$j][4]=$IDsalapers;
			
		}
		$i++;
		
	
	}
}

				
					


				
				$orari=array();
				
				if($IDtipo==2){
					$steps=900;
					$query="SELECT GROUP_CONCAT(ID SEPARATOR ',') FROM sottotipologie WHERE IDmain='$IDtipo' GROUP BY IDstr";
					$result=mysqli_query($link2,$query);
					if(mysqli_num_rows($result)>0){
						$row=mysqli_fetch_row($result);
						$IDsottogroup=$row['0'];
						$query="SELECT MIN(orarioi),MAX(orariof) FROM orarisotto WHERE IDsotto IN($IDsottogroup)";
						$result=mysqli_query($link2,$query);
						if(mysqli_num_rows($result)>0){
							$row=mysqli_fetch_row($result);
							$orai=$time0+$row['0'];
							$oraf=$time0+$row['1'];
							for($orai;$orai<=$oraf;$orai+=$steps){
								array_push($orari,$orai);
							}
						}
						
						
						$maxp=array();
						$sale=array();

						$query="SELECT s.ID,s.nome,s.maxp FROM sale as s,saleassoc as sc WHERE sc.IDsotto IN($IDsottogroup) AND sc.ID=s.ID   GROUP BY s.ID ORDER BY sc.priorita";
						$result=mysqli_query($link2,$query);
						if(mysqli_num_rows($result)>0){
							while($row = mysqli_fetch_row($result)){
								$sale[$row['0']]=$row['1'];
								$maxp[$row['0']]=$row['2'];
							}
						}
						
						
					}else{
						
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
					}
					
					$perscolor=array();
					$perscolor[0]='D10073';
					$perscolor[-1]='D10073';
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
					$steps=1800;
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
					
					
					$query2="SELECT sottotipologia FROM sottotipologie WHERE ID='$IDsottotip' LIMIT 1";
					$result2=mysqli_query($link2,$query2);
					$row=mysqli_fetch_row($result2);
					$sottotipologia=$row['0'];

						
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
				
				




	if($IDtipo==2){
		
		
		
		
		
					$col='';
					$colmain='col-25';
					switch(count($sale)){
						case 1:
							$col='col-80';
						break;
						case 2:
							$colmain='col-20';
							$col='col-40';
						break;
						case 3:
							$col='col-25';
						break;
						case 4:
							$colmain='col-20';
							$col='col-20';
						break;
						case 5:
							$colmain='col-25';
							$col='col-15';
						break;
					}
					
					
					
					$testo.='
					<div class="content-block-title titleb">Elenco Servizi Prenotati<br><span style="font-size:12px; color:#666;">'."Clicca su un orario disponibile per aggiungere".'</span></div>
					
					<div class="row no-gutter border  rowlist">
					<div class="h50 '.$colmain.' "></div>
					
					';
					foreach($sale as $nome){
						$testo.='<div class="h50 '.$col.'" style="text-align:center;font-weight:600;">'.$nome.'</div>';
					}
					
					$minstart=0;
					$minini=0;
					foreach($orari as $timein){
						$ora='';
						if($minstart==0){
							$minstart=date('i',$timein);
							if($minstart!=0){
								$minini=4-(60/$minstart);
							}
							$ora=date('H:i',$timein);
							$minstart=1;
						}
						
						if($minini==4){
							$minini=0;
							$ora=date('H:i',$timein);
						}
						$minini++;
						
						
						
						
						$testo.='<div class="h20 '.$colmain.'">'.$ora.'</div>';
						foreach($sale as $IDsala =>$nome){
							$val=$IDsala.'_'.$timein.'_0';
							
							$testo.='<div class="'.$col.' h20  '.$cla.'" style="position:relative;">';
							
							if(isset($arrserv[$IDsala][$timein])){
								
								
								$num=count($arrserv[$IDsala][$timein]);
								$wid=ceil(76/$num)-1;
								$margin[0]='margin-left:-6px; ';
								for($k=1;$k<$num;$k++){
									$margin[$k]='margin-left:'.($k*$wid+6).'%;';
								}
								
								
								
								foreach ($arrserv[$IDsala][$timein] as $j =>$dato){
									/*$IDpersin=$dato['1'];
									$IDprenextrain=$dato['0'];
									$durata=$dato['2'];
									$servizioin='';*/
								
									$IDp=$dato[0];
									$durata=$dato[1];
									$IDpren=$dato[2];
									$servizio=$dato[3];
									$IDpers=$dato[4];
									
									
										$testo.='<div onclick="modificaserv('.$IDp.',1,0,1)" class="divorari" style="width:'.$wid.'%; '.$margin[$j].' height:'.(($durata/15)*20).'px; border:solid; background:#'.$perscolor[$IDpers].'">
										<strong>'.estrainome($IDpren).'</strong><br/>
										<span>'.$servizio.'<br>
										<i>'.estrainomeapp($IDpren,0).'</i></span>
										</div>';	
									
								}
								
								
								
								
								/*
								
								$IDp=$arrserv[$IDpers][$timein][0];
								$durata=$arrserv[$IDpers][$timein][1];
								$IDpren=$arrserv[$IDpers][$timein][2];
								$servizio=$arrserv[$IDpers][$timein][3];
								
								
								$testo.='<div onclick="modificaserv('.$IDp.',1,0,1)" style="width:85%; font-size:12px; overflow:hidden; color:#fff; height:'.(($durata/15)*19).'px; position:absolute; border-radius:3px; z-index:999; background:#'.$perscolor[$IDpers].'">
								
								<strong>'.estrainome($IDpren).'</strong><br/>
								<span style="font-size:10px;">'.$servizio.'<br>
								<i>'.estrainomeapp($IDpren,0).'</i></span>
								
								
								</div>';
								*/
								
								
							}
							
							
							
							$testo.='</div>';
						}
					}
					$testo.='</div>';
					
						
		
		
		
	}else{
		//echo '<br><br><br><br>';
		//print_r($arrpers);
		
		
		$col='';
					$colmain='col-15';
					$colmain2='col-10';
					$col='col-75';
					/*switch(count($persnome)){
						case 1:
							$col='col-70';
							$col='col-70';
						break;
						case 2:
							//$colmain='col-20';
							$col='col-35';
						break;
						case 3:
							$colmain='col-20';
							$colmain2='20';
							$col='col-20';
						break;
					}*/
					
					$testo.='
					<div class="content-block-title titleb">Elenco Servizi Prenotati<br><span style="font-size:12px; color:#666;">'."Clicca su un orario disponibile per aggiungere".'</span></div>
					
					<div class="row no-gutter border2 rowlist">
					<div class=" h20 '.$colmain.' bornone"></div>
					<div class=" h20 '.$colmain2.' bornone"> <i class="f7-icons" style="font-size:15px;">person</i></div>
					<div class="h20 '.$col.' centercol bornone" style="text-transform:uppercase; font-weight:600;">'.$sottotipologia.'</div>
					';
					/*foreach($sale as $IDsala =>$nome){
						$testo.='<div class="h20 '.$col.'" style="text-align:center;font-weight:600;">'.$nome.'</div>';
					}*/
					
					$orari=array_unique($orari);
		
					$minstart=0;
					$minini=0;
					foreach($orari as $timein){
						$ora='';
						if($minstart==0){
							$minstart=date('i',$timein);
							if($minstart!=0){
								$minini=2-(60/$minstart);
							}
							$ora=date('H:i',$timein);
							$minstart=1;
						}
						
						if($minini==2){
							$minini=0;
							$ora=date('H:i',$timein);
						}
						$minini++;
						
						
						$pers='';
						if(isset($arrpers[$timein])){
							$pers=$arrpers[$timein];
						}
						$hhbase=55;
						
						/*
						if(isset($arrserv[$timein])){
							$num=$hhbase*count($arrserv[$timein]);
						}else{
							$num=23;
						}*/
						
						
						$testo.='<div class="h20 '.$colmain.' f12"><strong>'.$ora.'</strong></div>
						<div class="h20 '.$colmain2.' f12" >'.$pers.'</div>
						';
						//foreach($sale as $IDsala =>$nome){
							
						$testo.='<div class="'.$col.' h20 " >
						
						';
							
							if(isset($arrserv[$timein])){
								
								foreach($arrserv[$timein] as $dato){
									
									
									
									$IDp=$dato[0];
									$duratap=$dato[1];
									$IDprenp=$dato[2];
									$persone=$dato[3];
									$escl=$dato[4];
									$servizio=$dato[5];
									$IDsalapers=$dato[6];
									
									
									$testo.='
									<div class="row no-gutter" onclick="modificaserv('.$IDp.',1,0,1)" style="padding:0px; margin-bottom:3px; border:none; border-radius:3px; background:#ec95e3; color:#fff; height:'.$hhbase.';">
									
									<div class="col-70 f12">'.estrainome($IDprenp).'</div>
									<div class="col-15 f11 centercol"><i class="f7-icons">person</i> '.$persone.'</div>
									<div class="col-15 f11"><i class="f7-icons">time</i> '.$duratap.'</div>
									<div class="col-60 f10">'.$servizio.'</div>
									<div class="col-40 rightcol f10">'.estrainomeapp($IDprenp,0).'</div>
									</div>

								';
									
									
									
									
									
								}
							}
						$testo.='</div>';
					}
					
					$testo.='</div>';
		
		
		
		
		
		
		
		
	}


/*

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
					
				//<input type="hidden" value="" id="sospesi">
				$result2=mysqli_query($link2,$query2);
				$num=mysqli_num_rows($result2);
				$testo='
				
				<a href="#" onclick="opensosp()"  class="button button-round button-fill" style="width:100px;position:fixed;bottom:0;right:0;margin-right:5px;margin-bottom:5px;z-index:9999"><span id="sospesi">'.$num.'</span>&nbsp; Sospesi</a>
				<div style="width:100%; height:5px;"></div>
				'.$testo;
*/



			echo $testo;







return false;
/*


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

*/