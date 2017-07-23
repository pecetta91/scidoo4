<?php

header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');
include('../../config/funzionilingua.php');

$IDutente=$_SESSION['ID'];
$IDstruttura=$_SESSION['IDstruttura'];

$ID=strip_tags($_GET['ID']);
$tipo=strip_tags($_GET['tipo']);
$riagg=strip_tags($_GET['riagg']);

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
				$IDpers=0;
				/*$min=date('i',$time);
				if(($min==45)||($min==15)){
					$time-=900;	
				}*/
				//echo date('d/m/Y H:i',$time);
	
	if(($modi==0)&&(isset($_SESSION['timecal']))){
		
	
		$_SESSION['timecal']=$time;
	//	}
		$data=date('Y-m-d',$time);
		
	}	
	
	
$timemod=$time;
if(isset($_GET['time'])){
	if(is_numeric($_GET['time'])&&($_GET['time']>0)){
		$timemod=$_GET['time'];
		$data=date('Y-m-d',$_GET['time']);
	}
}
	
	
	
$funz='';
switch($riagg){
	case 0:
		$funz='navigationtxt(2,'."'".$IDpren.",1'".','."'contenutop'".',1)';
	break;
	case 1:
		//$funz='riaggvis()';
		
		/*if($IDtipo==1){
			$funz='navigationtxt(20,'."'".$time.",".$IDsotto.",1'".','."'tabscentro'".',3)';
		}else{
			$funz='navigationtxt(21,'."'".$time.",".$IDsotto.",1'".','."'tabscentro'".',3)';
		}*/
	break;
	case 2:
		//$funz='navigationtxt(2,'."'".$IDpren.",4'".','."'contenutop'".',1)';
		$funz='modificaserv('.$ID.','.$tipo.',0,2,1)';
	break;
	default:
		
		//$funz='riaggvis('."'".$riagg."'".')';
		//$funz='navigationtxt(32,'."'".$IDpren.",4'".','."'tabscentro'".')';
		
	break;
}

echo '
<div data-page="orarioserv" class="page" > 
';
			
			
				$step=30;
				$steps=$step*60;
				
				$stepb=$durata/$step;
				
				$query="SELECT time,checkout,gg FROM prenotazioni WHERE IDv='$IDpren' LIMIT 1";
				$result=mysqli_query($link2,$query);
				$row=mysqli_fetch_row($result);
				$check=$row['0'];
				$checkout=$row['1'];
				$notti=$row['2'];			
				


$ggmini=$giorniita2[date('N',$timemod)];				

$testo='

<input type="hidden" id="IDmodserv" value="'.$ID.'">
<input type="hidden" id="tipomod" value="'.$tipo.'">
<input type="hidden" id="timemod" value="'.$timemod.'">
<input type="hidden" id="riaggmod" value="'.$riagg.'">

<div class="navbar navbar-fixed">
               <div class="navbar-inner">
                 <div class="left"> <a href="#" class="link" onclick="backexplode(2)">
						<i class="material-icons" style="font-size:40px;">chevron_left</i>
						</a></div>
				  <div class="center" style="line-height:12px;">ORARIO</b><br>
				  <b style="font-size:12px;">'.$servizio.'</b>
				  </div>
				  
                 
               </div>
	</div>
	<div class="page-content">
	
	<input type="hidden" id="funzioneriagg" value="'.$funz.'">
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
				
	
				
				
				
				$or=orari3(0,$data,$qta,$IDserv,$IDstruttura,0,$IDpers,3,$check,0,$ID,$checkout);



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
										//echo $IDpers.'-'.$tt.'<br>';
										break;
									}
								}
								if($ok==1){
									//echo $IDpers.'-'.date('H:i',$key2).'<br>';
									if(!isset($oraripers[$IDpers][$key2])){
										$oraripers[$IDpers][$key2]=$key;
									}
/*
									array_push($oraripers[$IDpers],$key2);
									
									if(!isset($sala[$key2][$IDpers])){ //la prima saletta disponibile per ogni persona
										$sala[$key2][$IDpers]=$key;
									}*/
								}
								
							}
						}
					}
				}
				
				
				
				
				$claadd='class="modificas"';
				
				$first='';
				$txtinto='';
				//'.date('Y-m-d',$_GET['time']).'
				$testo.='
				

		 <div class="content-block-title titleb">Scegli una data</div>
						<div class="list-block">
  <ul>
    <li>
      <a href="#" class="item-link smart-select" data-searchbar="false" data-open-in="popup" >
        <select id="datamod" onChange="cambiadatamod('.$ID.',1,0,'."'".$riagg."'".',1)">';
	
		 for($i=0;$i<=$notti;$i++){
							$tt=$check+86400*$i;
							$cla=''; 
							if(date('Y-m-d',$tt)==$data){
								$cla='selected';
							}
							$testo.='<option value="'.$tt.'" '.$cla.'>'.dataita($tt).' '.date('Y',$tt).'</option>';
							//$testo.='<a href="#"  alt="'.$i.'" onclick="modificaserv('.$ID.',1,'.$tt.','."'".$riagg."'".',1)" class="roundb3 '.$cla.'">'.$giorniita3[date('N',$tt)].'<br>'.date('d',$tt).'</a>';
						}
		
          
        $testo.='</select>
        <div class="item-content">
          <div class="item-inner">
            <div class="item-title">Data del Servizio</div>
            <div class="item-after"></div>
          </div>
        </div>
      </a>
    </li>
  </ul>
</div>

 <div class="content-block-title titleb">Scegli operatore e Orario</div>

				<div class="list-block">
  <ul>';
$IDpersactive='';				
					
				foreach ($nomepers as  $IDpers =>$nome){
					
					
					
					// style="overflow-y:visible; margin-top:8px; padding-bottom:50px; " align="center"
					
						//<div class="list-block" style="margin-top:-5px;"><ul>
					
					//echo '<br><br><br>'.$IDpersserv.'<br>';;
					//print_r($orari);
					$okpers=0;
					
					$txtinto='<option value="0">--</option>';
					foreach ($orari as $times){
						
						$clas='disabled="disabled"';
						$val='';
						//if(in_array($times,$oraripers[$IDpers])){
						if(isset($oraripers[$IDpers][$times])){
							$saletta=$oraripers[$IDpers][$times];
							$val=$saletta.'_'.$times.'_'.$IDpers;
							$clas='';
							//$func=' onclick="modprenextra('.$ID.','.$val.',1,9,2)" ';
							$dis='';
						}
						
						$sel='';
						if(($times==$time)&&($IDpers==$IDpersserv)){$sel='selected="selected"';$okpers=1;}
							
						$txtinto.='<option value="'.$val.'" '.$clas.' '.$sel.'>'.date('H:i',$times).'</option>';		
					
					}	
					
					
					//$classp='';
					if($okpers==1){
						//$classp='<i class="f7-icons" style="color:#27dc80;">check</i>';
						$nome='<b style="color:#28a164;"><i class="f7-icons" style="color:#28a164; font-size:15px;">check</i> '.$nome.'</b>';
					}
					
					/*
					<div class="item-media">
							 '.$classp.'
							 </div>*/
					
					$testo.='
					
						<li>
					  <a href="#" class="item-link smart-select" data-open-in="popup">
						<select name="fruits" onchange="modprenextra('.$ID.',this.value,1,9,2)">
						  '.$txtinto.'
						</select>
						
						<div class="item-content">
							 
						  <div class="item-inner">
							<div class="item-title">'.$nome.'</div>
							<div class="item-after">--</div>
						  </div>
						</div>
					  </a>
					</li>
					';
					
					
					
						
							
				}
				
				
				$testo.='</ul></div>
				  
				  <input type="hidden" id="IDperssel" value="'.$IDpersactive.'">
				';
				
		
		
			break;
			case 2:
				
				
				
				$first='';
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
				$IDsalamain=0;
				$query="SELECT s.ID,s.nome,s.maxp FROM sale as s,saleassoc as sc WHERE sc.IDsotto='$IDsotto' AND sc.ID=s.ID ORDER BY sc.priorita";
				$result=mysqli_query($link2,$query);
				if(mysqli_num_rows($result)>0){
					while($row = mysqli_fetch_row($result)){
						if($IDsalamain==0){$IDsalamain=$row['0'];}
						$sale[$row['0']]=$row['1'];
						$maxp[$row['0']]=$row['2'];
					}
				}
				
				
				if($IDsalaserv==0){$IDsalaserv=$IDsalamain;}
				
							
				$testo.='
				
				 <div class="content-block-title titleb">Scegli una data</div>
						<div class="list-block">
  <ul>
    <li>
      <a href="#" class="item-link smart-select" data-searchbar="false" data-open-in="popup" >
        <select id="datamod" onChange="cambiadatamod('.$ID.',1,0,'."'".$riagg."'".',1)">';
	
		 for($i=0;$i<=$notti;$i++){
							$tt=$check+86400*$i;
							$cla=''; 
							if(date('Y-m-d',$tt)==$data){
								$cla='selected';
							}
							$testo.='<option value="'.$tt.'" '.$cla.'>'.dataita($tt).' '.date('Y',$tt).'</option>';
							//$testo.='<a href="#"  alt="'.$i.'" onclick="modificaserv('.$ID.',1,'.$tt.','."'".$riagg."'".',1)" class="roundb3 '.$cla.'">'.$giorniita3[date('N',$tt)].'<br>'.date('d',$tt).'</a>';
						}
		
          
        $testo.='</select>
        <div class="item-content">
          <div class="item-inner">
            <div class="item-title">Data del Servizio</div>
            <div class="item-after"></div>
          </div>
        </div>
      </a>
    </li>
  </ul>
</div>   




				';
				
				
				
				$orari=array_unique($orari);
				
	
				if($dis1==1){
					$testo.='<div style="margin:5px; font-size:15px;  color:#a43c32;font-weight:100;">Questo servizio non pu&ograve; essere ricevuto due volte lo stesso giorno.</div>';
				}else{
					$testo.='
					 <div class="content-block-title titleb">Scegli Sala e Orario</div>
						<div class="list-block">
  <ul>

					';
	
	
	
					if(isset($_SESSION['orario'][$IDserv][$data])){
						$or=$_SESSION['orario'][$IDserv][$data];
					}else{
						$or=orari3(0,$data,$qta,$IDserv,$IDstruttura,0,$IDpers,2,$check,0,0,$checkout);
						
						$_SESSION['orario'][$IDserv][$data]=$or;
					}
		
	//$or=orari3(0,$data,$qta,$IDserv,$IDstruttura,0,$IDpers,2,$check,0,0,$checkout);
					
					$txtinto='';
					
					//$testo.= '<div class="buttons-row">';
					
					
					//$active='';
					//$active2='';
					$IDpersactive='';
					foreach($sale as $IDsala =>$nomesala){
						
						//$testo.='<a href="#IDsalamod'.$IDsala.'" class="tab-link '.$active.' button ">'.$nomesala.'</a>';
							
						$okpers=0;
						
						$first=0;
						$txtinto='<option value="0" >--</option>';
						foreach ($orari as $times){
							
							
							//$clas='notdispo';
							$idinto='';
							$txtdispo='NON DISPONIBILE';
							
								
							$val=$IDsala.'_'.$times.'_0';
							$sel='';
							if(($times==$time)&&($IDsala==$IDsalaserv)){$sel='selected="selected"'; $okpers=1;}
									
							//$func=' onclick="modprenextra('.$ID.','.$val.',1,9,2);" ';
							$dis='';
							$txtp='';
							
							if(isset($or[$IDsala][$times])){
								//$txtdispo='DISPONIBILE';
								//$clas='avail';
								$qta=$or[$IDsala][$times];
								
								if($qta==0){
									$txtp='- '.$maxp[$IDsala].'+ persone';
								}else{
									$tt3=$maxp[$IDsala]-$qta;
									if($tt3!=0){
										$txtp='- '.$tt3.' persone';
									}									
									
								}
							}
							
							
							
							$qta=0;
							
							
							/*$txtinto.='<a href="#" '.$func.' id="'.$times.$IDsala.'0"  class="button button-raised bor '.$active.'">'.date('H:i',$times).' '.$txtp.'</a>';*/
							
							
							
							$txtinto.='<option value="'.$val.'"  '.$sel.'>'.date('H:i',$times).' '.$txtp.'</option>';		
							
							/*$txtinto.='<div class="tablist '.$sel.'" '.$func.' id="'.$times.$IDsala.'0" >
							<table><tr><td>'.date('H:i',$times).'</td><td class="numpl">'.$txtp.'</td><td class="'.$clas.'">'.$txtdispo.'</td></table>
							
							</div>';*/
						}
						
						//$txtinto.='</div>';
						$classp='';
						if($okpers==1){
							$classp='<i class="f7-icons" style="color:#27dc80;">check</i>';
						}
						
						$testo.='
						
							<li>
						  <a href="#" class="item-link smart-select" data-open-in="popup">
							<select name="fruits" onchange="modprenextra('.$ID.',this.value,1,9,2)">
							  '.$txtinto.'
							</select>
							
							<div class="item-content">
								 <div class="item-media">
								 '.$classp.'
								 </div>
							  <div class="item-inner">
								<div class="item-title">'.$nomesala.'</div>
								<div class="item-after">--</div>
							  </div>
							</div>
						  </a>
						</li>';
						
						
							
					}
					
					
			
					
					
					
					
					
					$testo.='
					<input type="hidden" value="'.$IDpersactive.'" id="IDperssel">
					';
				
				}
				
				
				
				
			break;
		
		
		
	
			}
		
			
			
		}

	
	$testo.='
	
	
	
	
	</div></div>
	';
	
	echo $testo;
?>