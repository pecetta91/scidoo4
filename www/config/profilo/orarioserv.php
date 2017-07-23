<?php

header('Access-Control-Allow-Origin: *');
include('../../../config/connecti.php');
include('../../../config/funzioni.php');
//include('../../../config/funzionilingua.php');



$IDpren=$_SESSION['IDstrpren'];

$query="SELECT time,checkout,IDstruttura,gg FROM prenotazioni WHERE IDv='$IDpren' LIMIT 1";

$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$check=$row['0'];
$checkout=$row['1'];
$IDstruttura=$row['2'];
$notti=$row['3'];

$ID=strip_tags($_GET['ID']);
$tipo=strip_tags($_GET['tipo']);

$online=0;


$query="SELECT p.extra,p.time,p.IDpren,p.IDtipo,p.durata,p.tipolim,p.IDpers,p.sottotip,p.sala,s.servizio,p.modi FROM prenextra as p,servizi as s WHERE p.ID='$ID' AND s.ID=p.extra LIMIT 1";
				$result=mysqli_query($link2,$query);
				$row=mysqli_fetch_row($result);
				$IDserv=$row['0'];
				$time=$row['1'];
				$IDpren=$row['2'];
				$IDtipo=$row['3'];
				$durata=$row['4'];
				$tipolim=$row['5'];
				$IDpersserv=$row['6'];
				$IDsotto=$row['7'];
				$IDsalaserv=$row['8'];
				$servizio=$row['9'];
				$modi=$row['10'];
				$data=date('Y-m-d',$time);
				$datamain=$data;
				$IDpers=0;
				$min=date('i',$time);
				if(($min==45)||($min==15)){
					$time-=900;	
				}
	
	$orarioutile=array();
	if($modi>0){
		$timeappo=$time;
		for($dd=0;$dd<=$durata;$dd+=15){
			array_push($orarioutile,($time+$dd*60));
		}
	}
	
	
	if(($modi==0)&&(isset($_SESSION['timecal']))){
		$time=$_SESSION['timecal'];
		$data=date('Y-m-d',$time);
	}		
	
	
	if($data!=$datamain){
		$online=1;
	}
			

				$step=30;
				$steps=$step*60;
				
				$stepb=$durata/$step;
				
				
				
				
				
$timemod=$time;
if(isset($_GET['time'])){
	if(is_numeric($_GET['time'])&&($_GET['time']>0)){
		$timemod=$_GET['time'];
		$data=date('Y-m-d',$_GET['time']);
	}
}
$ggmini=$giorniita2[date('N',$timemod)];				

$testo='

<input type="hidden" id="IDmodserv" value="'.$ID.'">
<input type="hidden" id="tipomod" value="'.$tipo.'">
<input type="hidden" id="timemod" value="'.$timemod.'">

<div class="navbar" style="background:#32ae6c;color:#fff; height:50px;">
               <div class="navbar-inner" style="height:45px;">
                  <div class="center" style="line-height:12px; height:45px; padding-top:15px;">			  
				  <span style="font-size:15px;">Modifica</span><br>
				  <span style="font-size:15px; font-weight:100;">'.$servizio.'</span>
				  
				  </div>
                  <div class="right" style="padding-right:15px;">
						<a href="#" style="width:40px;height:100%;" class="close-popup"><i class="icon f7-icons" style="color:#fff;  font-size:35px; ">check</i></a>
				
				  
				  </div>
               </div>
            </div>


';
		
		
		
		
		if($tipo==1){
		
			switch($tipolim){
				
				case 1:
		
				list($yy, $mm, $dd) = explode("-", $data);
				$time0=mktime(0, 0, 0, $mm, $dd, $yy);
				
				$qadd="";
				$query="SELECT GROUP_CONCAT(IDorarios SEPARATOR ',') FROM assocorario WHERE IDserv='$IDserv' GROUP BY IDserv";
				$result=mysqli_query($link2,$query);
				if(mysqli_num_rows($result)>0){
					$row=mysqli_fetch_row($result);
					$grora=$row['0'];
					$qadd=" AND ID IN ($grora)";	
				}
				
				$orari=array();
				
				$query="SELECT orarioi,orariof,ID FROM orarisotto WHERE IDsotto='$IDsotto' $qadd";
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
				
				
				$qta=1;
				
	
				$or=orari3(0,$data,$qta,$IDserv,$IDstruttura,0,$IDpers,2,$check,0,$ID,$checkout,$online);





				/*
				foreach($or as $key =>$dato){
					foreach($dato as $key2 =>$dato2){
						if($key2<$check)unset($or[$key][$key2]);
						//echo $key.'-'.date('H:i',$key2).'<br>';
					}
				}*/
				
				//estrarre tutto il personale
				
				$IDpersarr=array();
				$nomepers=array();
				
				$query="SELECT DISTINCT(p.ID),p.nome FROM mansioni as m,mansionipers as ms,personale as p WHERE ms.IDstruttura='$IDstruttura' AND ms.mansione=m.ID AND p.ID=ms.IDpers AND m.tipo='$IDtipo' AND p.ID!=''";
				$result=mysqli_query($link2,$query);
				if(mysqli_num_rows($result)>0){
					while($row=mysqli_fetch_row($result)){
						if(strlen($row['1'])>0){
							array_push($IDpersarr,$row['0']);
							$nomepers[$row['0']]=$row['1'];
						}
					}
				}
				
				
				
				
				
				
				foreach($IDpersarr as $IDpers){
					$arrnondisp[$IDpers]=array();
					
					$query="SELECT p.time,p.durata FROM prenextra as p WHERE p.IDpers='$IDpers' AND FROM_UNIXTIME(p.time,'%Y-%m-%d') ='$data'   AND p.ID!='$ID' ";
					$result=mysqli_query($link2,$query);
					if(mysqli_num_rows($result)>0){
						while($row=mysqli_fetch_row($result)){
							//$finet=$row['0']+($row['1']/$step)*60-60;
							$stepb2=$row['1']/$step;
							for($i=0;$i<$stepb2;$i++){
								$tt2=$row['0']+(($i*$step)*60);
								//array_push($arrnondisp[$IDpers],$i);
								$arrnondisp[$IDpers][$tt2]=0;
							}						
						}
					}
					
				}
					//print_r($arrnondisp);
					
					
					
				foreach($or as $key =>$dato){
					foreach($dato as $key2 =>$dato2){
						if($dato2!=0){
							foreach($IDpersarr as $IDpers){
								$ok=1;
								for($i=0;$i<$stepb;++$i){
									$tt=$key2+(60*($i*$step));
									if(isset($arrnondisp[$IDpers][$tt])){
										$ok=0;
										break;
									}
								}
								if($ok==1){
									if(!isset($oraripers[$IDpers])){$oraripers[$IDpers]=array();}
									array_push($oraripers[$IDpers],$key2);
									$sala[$key2]=$key;
								}
							}
						}
					}
				}
				
				
				
				$claadd='class="modificas"';
				
				$first='';
				$txtinto='';
				
				
				
				$testo.='
				<div class="list-block" style="margin-top:10px;margin-bottom:0px;">
				  <ul>
					<li class="item-content">
						<div class="item-after"><select style="font-size:17px;" onchange="modificaserv('.$ID.',1,this.value,'."'".$riagg."'".')">';
						for($i=0;$i<=$notti;$i++){
							$tt=$check+86400*$i;
							$testo.='<option value="'.$tt.'"';
							if(date('Y-m-d',$tt)==$data){
								$testo.=' selected="selected" ';
							}
							$testo.='>'.dataita($tt).'</option>';
							
						}
					
						$testo.='</select></div>
					</li>
					</ul></div>
				
				';
				
				
				
				$testo.= ' 
				<div  class="navbar " id="tabbardet" style="margin:auto;margin-top:8px;margin-bottom:-50px;  width:98%;background:transparent; padding:0px;">
						<div  style="height:32px; padding:0px;background:transparent;box-shadow:none;width:100%; ">
						  <div class="buttons-row" style=" box-shadow:none;  width:100%; ">
							';
				
				
					
				foreach ($nomepers as  $IDpers =>$nome){
					$active='';
					$active2='';
					
					if($IDpersserv>0){
						if($IDpersserv==$IDpers){$active=' tabac ';}
					}else{
						if($first==''){$active='tabac active button-fill'; $active2='active ';$first='1';}
					}
					$testo.='<a href="#IDpers'.$IDpers.'" class="tab-link   '.$active.' button button-raised">'.$nome.'</a>';
	
					
					$txtinto.='<div id="IDpers'.$IDpers.'" class="tab  '.$active2.'" style="overflow-y:visible; padding-top:20px;padding-bottom:50px; " align="left">';
						//<div class="list-block" style="margin-top:-5px;"><ul>
					
					foreach ($orari as $times){
						
						//if(isset($oraripers[$IDpers][$times])){
							
						$checked='';
						$func='';
						$dis='disabled';
						$clas='color-red';
						$idinto='';
						if(in_array($times,$oraripers[$IDpers])){
							$val="'".$sala[$times].'_'.$times.'_'.$IDpers."'";
							$clas='';
							if(($times==$time)&&($IDpers==$IDpersserv)){$clas='button-fill';}
								
							$func=' onclick="modprenextra('.$ID.','.$val.',1,9,2)" ';
							$dis='';
							$idinto='id="'.$times.$sala[$times].$IDpers.'"';						
						}
						if(date('i',$times)=='00'){$txtinto.='<br>';}
						$txtinto.='<a href="#" '.$func.' '.$idinto.' '.$dis.' class="button button-raised bor bor2 '.$clas.'">'.date('H:i',$times).'</a>';
					
					}	
					
					$txtinto.='</div>';			
							
	
				}
				
				$testo.='</div></div></div>
				
				<div class="tabs-animated-wrap" style="height:600px;">
  			 <div class="tabs" style="height:600px; margin-left:21px; margin-right:-21px;" align="center">
					'.$txtinto.'
					</div>	
				  </div>
				';
		
		
			break;
			case 2:
				
				
				list($yy, $mm, $dd) = explode("-", $data);
				$time0=mktime(0, 0, 0, $mm, $dd, $yy);
				
				$qadd="";
				$query="SELECT GROUP_CONCAT(IDorarios SEPARATOR ',') FROM assocorario WHERE IDserv='$IDserv' GROUP BY IDserv";
				$result=mysqli_query($link2,$query);
				if(mysqli_num_rows($result)>0){
					$row=mysqli_fetch_row($result);
					$grora=$row['0'];
					$qadd=" AND ID IN ($grora)";	
				}
				$orari=array();
				$query="SELECT orarioi,orariof,ID FROM orarisotto WHERE IDsotto='$IDsotto' $qadd";
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
	
					$query="SELECT SUM(qta),GROUP_CONCAT(IDinfop SEPARATOR ',') FROM prenextra2 WHERE IDprenextra='$ID' GROUP BY IDprenextra";
				$result=mysqli_query($link2,$query);
				$row=mysqli_fetch_row($result);
				$qta=$row['0'];
				$groupid=$row['1'];
				
				
				$dis1=0;
				
				if($IDtipo==1){
					$query2="SELECT p.ID FROM prenextra as p,prenextra2 as p2 WHERE FROM_UNIXTIME(p.time,'%Y-%m-%d')=FROM_UNIXTIME('$timemod','%Y-%m-%d') AND p.IDstruttura='$IDstruttura' AND  p.IDpren='$IDpren' AND p.sottotip='$IDsotto' AND p.modi>'0' AND p.ID!='$ID' AND p.ID=p2.IDprenextra AND p2.IDinfop IN($groupid)";
					
					$result2=mysqli_query($link2,$query2);
					if(mysqli_num_rows($result2)>0){
						$dis1=1;
					}
				}
				
				
				//estrarre tutto il personale
				$maxp=array();
				$sale=array();
				$query="SELECT s.ID,s.nome,s.maxp FROM sale as s,saleassoc as sc WHERE sc.IDsotto='$IDsotto' AND sc.ID=s.ID ORDER BY sc.priorita";
				$result=mysqli_query($link2,$query);
				if(mysqli_num_rows($result)>0){
					while($row = mysqli_fetch_row($result)){
						$sale[$row['0']]=$row['1'];
						$maxp[$row['0']]=$row['2'];
					}
				}
				
				$wid=80*($notti+2);
				$testo.='<br><div style="width:100%;text-align:center;  font-size:15px; margin-bottom:10px; font-weight:600;">DATA</div>
<div style="height:70px; width:99%; border:solid 1px #f1f1f1;  overflow-x:scroll;" id="datamain">
			<div style="width:'.$wid.'px;">
			
			
	';

//$finecheck=$check0+($notti+1)*86400;

for($i=0;$i<=$notti;$i++){

	$tt=$check+$i*86400;
	
	
	//prenotaora('.$IDserv.',this.value,1)
	$sele='';
	if(date('Y-m-d',$tt)==$data){$sele='selected';	
		echo '<input type="hidden" id="funcreload" value="modificaorario('.$ID.',1,'.$tt.',1)">';
	}
		/*
		$testo.='<div class="buttdate '.$sele.'" onclick="modificaorario('.$ID.',1,'.$tt.',1)" >'.date('d',$tt).'<br>
		<span style="font-size:12px;">'.$mesiita2[date('n',$tt)].' '.date('Y',$tt).'</span></div>';
		*/
		
		$testo.='<a href="#" alt="'.$i.'" onclick="modificaorario('.$ID.',1,'.$tt.',1)" class="roundb3 '.$sele.'">'.$giorniita2[date('N',$tt)].'<br>'.date('d',$tt).'</a>';
		
}



$testo.= '
       
        </div>
      </div><br><div style="  width:100%;text-align:center;  font-size:15px; margin-bottom:0px; font-weight:600;">ORARI DISPONIBILI</div>
    <div style="width:100;" align="center">

';
				
					
				$orari=array_unique($orari);
				
				if($dis1==1){
					$testo.='<div style="margin:5px; font-size:15px;  color:#a43c32;font-weight:100;">Questo servizio non pu&ograve; essere ricevuto due volte lo stesso giorno.</div>';
				}else{
	
					$or=orari3(0,$data,$qta,$IDserv,$IDstruttura,0,$IDpers,2,$check,0,0,$checkout,$online);
					
					//print_r($or);
					
					$orari2=array();
					//$numptime=array();
					
					
					
					//filtra l'array orari appena generato
					
					
					foreach ($or as $IDsala =>$dato){
						foreach ($dato as $timeor =>$nump){
							if(!isset($orari2[$timeor])){
								if(in_array($timeor,$orari)){
									
									if(($nump>0)||(in_array($timeor,$orarioutile))){
										$orari2[$timeor]=$IDsala;
									}
									
								}
								//$numptime[$timeor]=$nump;
							}
						}
					}
					
					$step=$durata/30;
					
						
					foreach ($orari2 as $timeor =>$dato){
						$ok=1;
						for($i=0;$i<$step;$i++){
							$time2=$timeor+$i*1800;
							if(!isset($orari2[$time2])){
								$ok=0;
								break;
							}
						}
						if($ok==0){
							unset($orari2[$timeor]);
						}
						
						
					}
									
	
	
	
	
	
	
	
						$first=0;
						foreach ($orari2 as $times =>$IDsala){
							/*
							if((date('i',$times)=='00')){
								$testo.= '<br>';
							}
								
							$val="'".$IDsala.'_'.$times.'_'."0'";
							
							
							$clas='';
							$active='';
							
							if(($times==$time)&&($IDsala==$IDsalaserv)){$active='button-fill';}
									
							$func=' onclick="modprofilo('.$ID.','.$val.',6,10,5)" ';
							$dis='';
							$txtp='';
							
							
							$qta=0;
							
							
							$testo.='<a href="#" '.$func.' id="'.$times.$IDsala.'0"  class="button button-raised bor '.$active.'">'.date('H:i',$times).' '.$txtp.'</a>';
							*/
							
							$checked='';
							$func='';
							//$dis='disabled';
							//$clas='notdispo';
							$sel='';
							$idinto='';
							
							$clas='avail';
							$txtdispo='DISPONIBILE';
							$val="'".$IDsala.'_'.$times.'_'."0'";
							$func=' onclick="modprofilo('.$ID.','.$val.',6,10,5)" ';
							$dis='';
							$idinto='id="'.$times.$IDsala.'0"';
							
							
							if(($times==$time)&&($IDpers==$IDpersserv)){$sel='selected';}
									
								//if(date('i',$times)=='00'){$txtinto.='<br>';}
							
							
							$testo.='<div class="tablist '.$sel.'" '.$func.' '.$idinto.' >
							<table><tr><td>'.date('H:i',$times).'</td><td class="'.$clas.'">'.$txtdispo.'</td></table>
							
							</div>';
							
							
						}
						
					
					}
					
	
				$testo.='</div>';
				
				
			break;
		
		
	
			}
		
			
			
		}

	
	$testo.='
	<br><div style="width:90%; margin:auto; text-align:center; font-size:12px;">
	(*) Si consiglia sempre di prenotare il proprio orario al fine di migliorare il servizio.<br>
	</div>
	<br>';
	
	echo $testo;
?>