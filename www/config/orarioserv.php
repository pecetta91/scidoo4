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

				/*$IDluogo=0;
				if($tipolim==1){
					$IDluogo=$IDpersserv;
				}else{
					$IDluogo=$IDsalaserv;
				}*/

				$IDluogo=$IDsalaserv;
				//if($modi>0){
					if($IDtipo==2){
						$arrset[$IDluogo][$time]=$IDpersserv;
					}else{
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
                 <div class="left navbarleftsize260"  onclick="backexplode(2)">
						<i class="material-icons">chevron_left</i>
						<strong class="stiletitolopagine lh15">'.$servizio.'</strong>
						</div>
						
				  <div class="center">
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
				
				$query="SELECT MIN(orarioi),MAX(orariof) FROM orarisotto WHERE IDsotto='$IDsotto' $qadd";
				$result=mysqli_query($link2,$query);
				if(mysqli_num_rows($result)>0){
					$row=mysqli_fetch_row($result);
						
					$orai=$time0+$row['0'];
					$imin=date('H:i',$orai);
					$sec=secondi($imin);
					$diff=$sec%3600;
					$orai=$orai-$diff;
					$oraf=$time0+$row['1'];
					
					for($orai;$orai<=$oraf;$orai+=$steps){
						array_push($orari,$orai);
					}
				}
					
				$maxpers=0;
				$query="SELECT s.ID,s.nome,s.maxp FROM sale as s,saleassoc as sc WHERE sc.IDsotto='$IDsotto' AND sc.ID=s.ID ORDER BY sc.priorita";
				$result=mysqli_query($link2,$query);
				if(mysqli_num_rows($result)>0){
					while($row = mysqli_fetch_row($result)){
						if($IDsalamain==0){$IDsalamain=$row['0'];}
						$sale[$row['0']]=$row['1'];
						$maxp[$row['0']]=$row['2'];
						$maxpers+=$row['2'];
					}
				}
					
					
				
				
				$qta=1;

				//$or=orari3(0,$data,$qta,$IDserv,$IDstruttura,0,$IDpers,3,$check,0,$ID,$checkout);

		
				$IDpersarr=array();
				$nomepers=array();
				$colorpers=array();
				$colorpers[0]='D10073';
				$colorpers[-1]='D10073';
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
				

				
				foreach($sale as $IDsala =>$nome){
					$arrnondisp[$IDsala]=array();
					
					$query="SELECT p.time,p.durata,p.IDpers,p.ID,p.durata FROM prenextra as p WHERE p.sala='$IDsala' AND FROM_UNIXTIME(p.time,'%Y-%m-%d') ='$data'  AND p.modi>'0' ";
					$result=mysqli_query($link2,$query);
					if(mysqli_num_rows($result)>0){
						while($row=mysqli_fetch_row($result)){
							//$finet=$row['0']+($row['1']/$step)*60-60;
						//	$stepb2=$row['1']/$step;
							//for($i=0;$i<$stepb2;$i++){
								$tt2=$row['0'];
								//array_push($arrnondisp[$IDpers],$i);
								
								$j=0;
								if(isset($arrnondisp[$IDsala][$tt2])){$j=count($arrnondisp[$IDsala][$tt2]);}
								
								$arrnondisp[$IDsala][$tt2][$j][0]=$row['3'];
								$arrnondisp[$IDsala][$tt2][$j][1]=$row['2'];
								$arrnondisp[$IDsala][$tt2][$j][2]=$row['4'];
								
							//}						
						}
					}
					
				}
					
				//echo '<br><br><br><br><br>';
				//print_r($arrnondisp);
					
				
				$claadd='class="modificas"';
				
				$first='';
				$txtinto='';
				//'.date('Y-m-d',$_GET['time']).'
				$testo.='<div class="content-block-title titleb">Scegli una data</div>
									<div class="list-block">
					  <ul>
						<li>
						  <a href="#" class="item-link smart-select" data-searchbar="false" data-open-in="picker" pickerHeight="400px" >
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
									
					<div class="row no-gutter border  rowlist" onclick="settime1('."'0_".$time.'_0'."'".');">
					<div class=" h50 col-40" style="border:none;">Servizio in Sospeso<br><span style="font-size:10px; color:#666;">Imposta il servizio più tardi</span></div>
					<div class=" h50 col-55" style="border:dotted 1px #ccc;; position:relative;">';
					
					if(isset($arrset[0])){
					//	$testo.= 'aaa';
						$testo.='<div class="divorari" style="width:80%; height:40px; background:#'.$colorpers[0].'">
										<strong>'.estrainome($IDpren).'</strong><br/>
										<span>'.$servizio.'<br>
										<i>'.estrainomeapp($IDpren,0).'</i></span>
										</div>';
					}else{
						$testo.='<span style="font-size:11px; color:#999;">Clicca qui per Sospeso</span>';
					}
					
					
					
					$testo.='</div>
					<div class="col-5" style="border:none;"></div>
					</div>
					
					
					
					<div class="content-block-title titleb">Scegli Sala ed Orario<br><span style="font-size:12px; color:#666;">'."Clicca sull'orario di inizio per impostarlo".'</span></div>
					
					';
					
					
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
					
					
					
					$testo.='<div class="row no-gutter border  rowlist">
					<div class=" h50 '.$colmain.'"></div>
					
					';
					foreach($sale as $nome){
						$testo.='<div class="h50 '.$col.' centercol">'.$nome.'</div>';
					}
					
					$minstart=0;
					$minini=0;
					foreach($orari as $timein){
						$ora='';
						$classin='nobb';
						if($minstart==0){
							$minstart=date('i',$timein);
							if($minstart!=0){
								$minini=4-(60/$minstart);
							}
							$ora=date('H:i',$timein);
							$minstart=1;
							$classin='';
						}
						
						if($minini==4){
							$minini=0;
							$ora=date('H:i',$timein);
							$classin='';
						}
						$minini++;
						
						$testo.='<div class="h20 '.$classin.' '.$colmain.'" >'.$ora.'</div>';
						foreach($sale as $IDsala =>$nome){
							
							$val=$IDsala.'_'.$timein.'_0';
							//$val='';
							$textadd='';
							if(isset($arrnondisp[$IDsala][$timein])){
								
								$num=count($arrnondisp[$IDsala][$timein]);
								$wid=ceil(76/$num)-1;
								$margin[0]='margin-left:-6px; ';
								for($k=1;$k<$num;$k++){
									$margin[$k]='margin-left:'.($k*$wid+6).'%;';
								}
								
								foreach ($arrnondisp[$IDsala][$timein] as $j =>$dato){
									$IDpersin=$dato['1'];
									$IDprenextrain=$dato['0'];
									$durata=$dato['2'];
									$servizioin='';
								
									if(isset($arrset[$IDsala][$timein])){
										$textadd.='<div class="divorari" style="width:'.$wid.'%; '.$margin[$j].' height:'.(($durata/15)*19).'px; background:#'.$colorpers[$IDpersin].'">
										<strong>'.estrainome($IDpren).'</strong><br/>
										<span>'.$servizio.'<br>
										<i>'.estrainomeapp($IDpren,0).'</i></span>
										</div>';	
										unset($arrset[$IDsala][$timein]);
									}else{
										$textadd.='<div class="divorari" style="opacity:0.4; width:'.$wid.'%; '.$margin[$j].'  height:'.(($durata/15)*19).'px;  background:#'.$colorpers[$IDpersin].'">
										<span>'.$servizioin.'
										</div>';
									}
								}
							}
							
							
							
							
							
							$testo.='<div class="'.$col.' h20  '.$cla.'" style="position:relative;" onclick="settime1('."'".$val."'".');">'.$textadd.'</div>';
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
				$orari2=array();
					$bef=-1800;
					$post=1800;
				$steps=1800;
				$step2=1800;
					$orai=-1;
					$oraf=-1;
				$query="SELECT orarioi,orariof FROM orarisotto WHERE IDsotto='$IDsotto' $qadd";
				$result=mysqli_query($link2,$query);
				if(mysqli_num_rows($result)>0){
					while($row=mysqli_fetch_row($result)){
						if($orai<0){$orai=$row['0'];}
						if($oraf<0){$oraf=$row['1'];}
						
						$appo1=$row['0'];
						$appo2=$row['1'];
						if($appo2<$appo1){
							$appo2=86400+$appo2;
						}
						if($orai>$appo1){
							$orai=$appo1;
						}
						if($oraf<$appo2){
							$oraf=$appo2;
						}						
					}
				}
					//echo '<br><br><br>'.$orai.'---'.$oraf.'---'.$time0;
					
					$orai=$time0+$orai+$bef;;
					$oraf=$time0+$oraf+$post;
						for($jj=$orai;$jj<=$oraf;$jj+=$steps){
							array_push($orari,$jj);
						}
						if(count($orari)>10){$step2=3600;}
						for($orai;$orai<=$oraf;$orai+=$step2){
							array_push($orari2,$orai);
						}	
					
	
				//echo '<br><br><br><br><br>'.$orai;print_r($orari2);
				
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
					
				if(isset($_SESSION['orario'][$IDserv][$data])){
					$or=$_SESSION['orario'][$IDserv][$data];
				}else{
					$or=orari3(0,$data,$qta,$IDserv,$IDstruttura,0,$IDpers,2,$check,0,0,$checkout);
					$_SESSION['orario'][$IDserv][$data]=$or;
				}	
					
					
				$maxpers=0;
				$query="SELECT s.ID,s.nome,s.maxp FROM sale as s,saleassoc as sc WHERE sc.IDsotto='$IDsotto' AND sc.ID=s.ID ORDER BY sc.priorita";
				$result=mysqli_query($link2,$query);
				if(mysqli_num_rows($result)>0){
					while($row = mysqli_fetch_row($result)){
						if($IDsalamain==0){$IDsalamain=$row['0'];}
						$sale[$row['0']]=$row['1'];
						$maxp[$row['0']]=$row['2'];
						$maxpers+=$row['2'];
					}
				}
				
				
				
							
				$testo.='
				
				 <div class="content-block-title titleb">Scegli Data, Orario e Luogo</div>
						<div class="list-block">
  <ul>
    <li>
      <a href="#" class="item-link smart-select" data-searchbar="false" data-open-in="picker" pickerHeight="400px" >
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
    </li>';
					
				if($dis1==1){
					$testo.='<div style="margin:5px; font-size:15px;  color:#a43c32;font-weight:100;">Questo servizio non pu&ograve; essere ricevuto due volte lo stesso giorno.</div>';	
				}else{
					
					if($IDtipo==1){
						$zero='0';
						$funcor='modprenextra('.$ID.',this,42,12)';
						$funcsa='modprenextra('.$ID.',this,43,12)';
					}else{
						$funcor='modprenextra('.$ID.',this,1,12,2)';
						$funcsa='modprenextra('.$ID.',this,43,12,2)';
						$zero='0_0_0';
						if($IDsalaserv==0){
							$IDsalaserv2=$IDsalamain;
						}else{
							$IDsalaserv2=$IDsalaserv;
						}
						
					}
					
					
					
					$orari=array_unique($orari);
					$testo.='<li>
						  <a href="#" class="item-link smart-select" data-searchbar="false" data-open-in="picker" pickerHeight="400px">
							<select id="datamod" onChange="'.$funcor.'">
								<option value="'.$zero.'">--.--</option>';
								foreach($orari as  $timein){
									if($IDtipo==1){
										$val=$timein;
									}else{
										$val=$IDsalaserv2.'_'.$timein.'_0';
									}
									
									$testo.='<option value="'.$val.'"';
									if($timein==$time){
										$testo.=' selected="selected"';
									}
									$testo.='>'.date('H:i',$timein).'</div>';
								}
					
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
						  <a href="#" class="item-link smart-select" data-searchbar="false" data-open-in="picker" pickerHeight="400px">
							<select id="datamod" onChange="'.$funcsa.'">
							';
					
							//if($IDtipo==1){
								$testo.='<option value="0">...</option>';
							//}

								foreach($sale as $IDsala =>$nome){
									$testo.='<option value="'.$IDsala.'"';
									if($IDsala==$IDsalaserv){
										$testo.=' selected="selected"';
									}
									$testo.='>'.$nome.'</div>';
								}
					
					
							$testo.='</select>
							<div class="item-content">
							  <div class="item-inner">
								<div class="item-title">Sala di Svolgimento</div>
								<div class="item-after"></div>
							  </div>
							</div>
						  </a>
						</li>';
				}

					
	
					
					
					
					
					$txtesclusivi='';
					
					
					
					$arrescl=array();
					$query="SELECT ID,time,sala,durata FROM prenextra WHERE esclusivo='1' AND IDstruttura='$IDstruttura' AND FROM_UNIXTIME(time,'%Y-%m-%d')='$data' AND sottotip='$IDsotto'";
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
					
					$graph=array();
					
					foreach($sale as $IDsala =>$nomesala){
							foreach ($orari2 as $times){
								$qta=0;
								if(isset($or[$IDsala][$times])){
									$qta=$maxp[$IDsala]-$or[$IDsala][$times];
								}
								if(isset($graph[$times])){
									$graph[$times]+=$qta;
								}else{
									$graph[$times]=$qta;
								}
							}
					}
					
					/*
					if(($modi==1)&&(date('Y-m-d',$time)==$data)){
						
						if($step2==3600){
							if(date('i',$time)==30){
								$time-=1800;
							}
						}
						
						$timefine=$time+60*$durata;
						for($i=$time;$i<$timefine;$i+=$step2){
							if(isset($graph[$i])){
								$graph[$i]+=$persone;
							}
						}
					}*/
					
					
					
								
					
					
					
									
					
					$testo.='
					
					
					  </ul>
</div>   


<div class="content-block-title titleb" style="color:#666;">Stato Servizio</div>

<div class="graph" style="background:#fff; border-top:solid 1px #e1e1e1; border-bottom:solid 1px #e1e1e1;">
';
	$wid=floor(100/(count($graph)+1));
	foreach($graph as $times =>$qta){
		if($step2==3600){
			$ora=date('H',$times);
		}else{
			$ora=date('H:i',$times);
		}
		$testo.='<div style="width:'.$wid.'%;"><span style="height:'.(((80*$qta)/$maxpers)+5).'%"></span>'.$ora.'<br/><i>'.$qta.'</i></div>';
	}


    
$testo.='
</div>



					
					<input type="hidden" value="'.$IDpersactive.'" id="IDperssel">';
				
			
					
				
				
				
				
			break;
		
		
		
	
			}
		
			
			
		}

	
	$testo.='
	
	
	
	
	</div><br/><br/><br/></div>
	';
	
	echo $testo;
?>