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

$arrset=array();

$query="SELECT p.extra,p.time,p.IDpren,p.IDtipo,p.durata,p.tipolim,p.IDpers,p.sottotip,p.sala,s.servizio,p.modi,p.esclusivo,COUNT(p2.IDprenextra) FROM prenextra as p,servizi as s,prenextra2 as p2 WHERE p.ID='$ID' AND s.ID=p.extra AND p.ID=p2.IDprenextra  GROUP BY p.ID ";
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
				$persone=$row['12'];
				$esclusivo=$row['11'];

				$data=date('Y-m-d',$time);
				$IDpers=0;

				$IDluogo=0;
				if($tipolim==1){
					$IDluogo=$IDpersserv;
				}else{
					$IDluogo=$IDsalaserv;
				}

				if($modi>0){
					$timefinal=$time+$durata*60;
					for($i=$time;$i<$timefinal;$i+=900){
					
						$arrset[$IDluogo][$i]=$persone;
					}
				}



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
			
			
				$step=15;
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
                 <div class="left"  onclick="backexplode(2)">
						<i class="material-icons">chevron_left</i>
						</div>
				  <div class="center">'.$servizio.'
				  </div>
				  <div class="right"></div>
                 
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
				
	
				
				
				
				//$or=orari3(0,$data,$qta,$IDserv,$IDstruttura,0,$IDpers,3,$check,0,$ID,$checkout);

		
				$IDpersarr=array();
				$nomepers=array();
				$colorpers=array();
				$query="SELECT DISTINCT(p.ID),p.nome,p.color FROM mansioni as m,mansionipers as ms,personale as p WHERE ms.IDstruttura='$IDstruttura' AND ms.mansione=m.ID AND p.ID=ms.IDpers AND m.tipo='$IDtipo' AND p.ID!=''";
				$result=mysqli_query($link2,$query);
				if(mysqli_num_rows($result)>0){
					while($row=mysqli_fetch_row($result)){
						if(strlen($row['1'])>0){
							array_push($IDpersarr,$row['0']);
							$nomepers[$row['0']]=$row['1'];
							$colorpers[$row['0']]=$row['2'];
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
					//echo '<br><br><br><br><br>';
				//print_r($arrnondisp);
					
					
				/*
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

								}
								
							}
						}
					}
				}*/
				
				
				
				
				$claadd='class="modificas"';
				
				$first='';
				$txtinto='';
				//'.date('Y-m-d',$_GET['time']).'
				$testo.='<div class="content-block-title titleb">Scegli una data</div>
									<div class="list-block">
					  <ul>
						<li>
						  <a href="#" class="item-link smart-select" data-searchbar="false" data-open-in="picker" >
							<select id="datamod" onChange="cambiadatamod('.$ID.',1,0,'."'".$riagg."'".',1)">';

							 for($i=0;$i<=$notti;$i++){
												$tt=$check+86400*$i;
												$cla=''; 
												if(date('Y-m-d',$tt)==$data){
													$cla='selected';
												}
												$testo.='<option value="'.$tt.'" '.$cla.'>'.dataita($tt).' '.date('Y',$tt).'</option>';

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
					
					
					
					
					
					<div class="content-block-title titleb">Scegli Personale ed Orario<br><span style="font-size:12px; color:#666;">'."Clicca sull'orario di inizio per impostarlo".'</span></div>
					
					';
					
					
					$col='';
					$colmain='col-25';
					switch(count($nomepers)){
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
					
					
					
					$testo.='<div class="row no-gutter border  rowlist">
					<div class=" h20 '.$colmain.'"></div>
					
					';
					foreach($nomepers as $IDpers =>$nome){
						$testo.='<div class="h20 '.$col.'" onclick="set" style="text-align:center;color:#'.$colorpers[$IDpers].'">'.$nome.'</div>';
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
						foreach($nomepers as $IDpers =>$nome){
							$val=$timein.'_'.$IDpers;
							$cla='';
							if(isset($arrnondisp[$IDpers][$timein])){
								$cla='occup';
							}
							if(isset($arrset[$IDpers][$timein])){
								$cla=' serv';
								
							}
							
							
							$testo.='<div class="'.$col.' h20  '.$cla.'" onclick="settime1('."'".$val."'".');"></div>';
						}
					}
					$testo.='</div>';
					
					
				
				  
				$testo.='<input type="hidden" id="IDperssel" value="'.$IDpersactive.'">';
				
		
		
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
					$bef=-1800;
					$post=1800;
				$query="SELECT orarioi,orariof,ID FROM orarisotto WHERE IDsotto='$IDsotto' $qadd";
				$result=mysqli_query($link2,$query);
				if(mysqli_num_rows($result)>0){
					while($row=mysqli_fetch_row($result)){
						$orai=$time0+$row['0']+$bef;;
						$oraf=$time0+$row['1']+$post;;
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
      <a href="#" class="item-link smart-select" data-searchbar="false" data-open-in="picker" >
        <select id="datamod" onChange="cambiadatamod('.$ID.',1,0,'."'".$riagg."'".',1)">';
	
		 for($i=0;$i<=$notti;$i++){
							$tt=$check+86400*$i;
							$cla=''; 
							if(date('Y-m-d',$tt)==$data){
								$cla='selected';
							}
							$testo.='<option value="'.$tt.'" '.$cla.'>'.dataita($tt).' '.date('Y',$tt).'</option>';
							
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
	
	<li>
						  <a href="#" class="item-link smart-select" data-searchbar="false" data-open-in="picker" >
							<select id="datamod" onChange="">';

							


							$testo.='</select>
							<div class="item-content">
							  <div class="item-inner">
								<div class="item-title">Orario del Servizio</div>
								<div class="item-after"></div>
							  </div>
							</div>
						  </a>
						</li>
	
	
	<li>
						  <a href="#" class="item-link smart-select" data-searchbar="false" data-open-in="picker" >
							<select id="datamod" onChange="">';

							$testo.='</select>
							<div class="item-content">
							  <div class="item-inner">
								<div class="item-title">Sala di Svolgimento</div>
								<div class="item-after"></div>
							  </div>
							</div>
						  </a>
						</li>
	
	
	





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
					
					
					//
					
					
					//controllo esclusivi oggi
					
					
					
					$arrescl=array();
					$query="SELECT ID,time,sala,durata FROM prenextra WHERE esclusivo='1' AND IDstruttura='$IDstruttura' AND FROM_UNIXTIME(time,'%Y-%m-%d')='$data'";
					$result=mysqli_query($link2,$query);
					if(mysqli_num_rows($result)>0){
						while($row=mysqli_fetch_row($result)){
							$time=$row['1'];
							$timefine=$time+60*$durata;
							for($i=$time;$i<$timefine;$i+=900){
								if(!isset($arrescl[$row['2']][$i])){
									$arrescl[$row['2']][$i]=1;
								}
							}
						}
					}
					
					
					
					$arrstampa=array();
					
					if($IDtipo==1){
						//raggruppa tutto su una sala e non la modifica
						$IDsalamain=0;
						
						
						
						
						foreach($sale as $IDsala =>$nomesala){

							$okpers=0;

							$first=0;
							$IDsala=0;
							foreach ($orari as $times){
								$qta=0;
								if(isset($or[$IDsala][$times])){
									$qta=$or[$IDsala][$times];
								}

								if($eslusivo==1){
									if(!isset($arrstampa[$times][$IDsala])){
										$arrstampa[$times][$IDsala]=0;
									}
									$arrstampa[$times][$IDsala]+=$qta;
								}else{
									if(!isset($arrstampa[$times][$IDsalamain])){
										$arrstampa[$times][$IDsalamain]=0;
									}
									$arrstampa[$times][$IDsalamain]+=$qta;
								}
								
								

								
								
								
								
								
								/*
								//$clas='notdispo';
								$idinto='';
								$txtdispo='NON DISPONIBILE';


								$val=$IDsala.'_'.$times.'_0';
								$sel='';
								if(($times==$time)&&($IDsala==$IDsalaserv)){$sel='selected="selected"'; $okpers=1;}

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

								*/
								//$txtinto.='<option value="'.$val.'"  '.$sel.'>'.date('H:i',$times).' '.$txtp.'</option>';		

							}
						}
						
						if($esclusivo==0){
							$sale=array('Sala Ristorante');
						}
						
						
						
						
						
						
						
					}else{
						
						
						
					}
					
					/*
					
					
					$txtinto='';
					
						
					$IDpersactive='';
					foreach($sale as $IDsala =>$nomesala){

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
							
							
							$txtinto.='<option value="'.$val.'"  '.$sel.'>'.date('H:i',$times).' '.$txtp.'</option>';		
							
						}
						
						//$txtinto.='</div>';
						$classp='';
						if($okpers==1){
							$classp='<i class="f7-icons" style="color:#27dc80;">check</i>';
						}
						
						$testo.='
						
							<li>
						  <a href="#" class="item-link smart-select" data-open-in="picker">
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
					}*/
					
					
					
					
					$col='';
					$colmain='col-25';
					switch(count($sale)){
						case 1:
							$col='col-75';
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
					
					//echo '<br><br><br>';
					//print_r($arrstampa);
					
					$testo.='<div class="row no-gutter border rowlist">
					<div class=" h50 '.$colmain.'"></div>
					';
					
					foreach($sale as $IDsala =>$nome){
						$testo.='<div class="h50 '.$col.'" onclick="set" style="text-align:center;">'.$nome.'</div>';
					}
					
					$minstart=0;
					$minini=0;
					$first=0;
					foreach($orari as  $timein){
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
						
						
						$dato=array();
						if(isset($arrstampa[$timein])){
							$dato=$arrstampa[$timein];
						}
						
						
						
						
						$testo.='<div class="h20 '.$colmain.'">'.$ora.'</div>';
						
						foreach($sale as $IDsala =>$nome){
							$val=$IDsala.'_'.$timein.'_'.$IDsala;
							$cla='';
							$txtin='';
							$plus=0;
							
							if(isset($arrset[$IDsala][$timein])){
								$cla='serv';
								if(($first==1)&&($IDtipo==1)){$cla='';}
								$first=1;
								$plus=$arrset[$IDsala][$timein];
							}
							
							if(isset($dato[$IDsala])){
								$pers=$dato[$IDsala]+$plus;
								if($pers>0){
									$txtin=$pers.' '.txtpersone($pers);
								}
							}
							
						
							
							
							$testo.='<div class="'.$col.' h20 centercol '.$cla.'"  onclick="settime1('."'".$val."'".',2);">'.$txtin.'</div>';
						}
					}
					$testo.='</div>';
					
									
					
					$testo.='<input type="hidden" value="'.$IDpersactive.'" id="IDperssel">';
				
				}
				
				
				
				
			break;
		
		
		
	
			}
		
			
			
		}

	
	$testo.='
	
	
	
	
	</div><br/><br/><br/></div>
	';
	
	echo $testo;
?>