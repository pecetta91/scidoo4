<?php

header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');
include('../../config/funzionilingua.php');

$IDutente=$_SESSION['ID'];
$IDstruttura=$_SESSION['IDstruttura'];


$ID=strip_tags($_POST['ID']);
$tipo=strip_tags($_POST['tipo']);
$riagg=strip_tags($_POST['riagg']);

$query="SELECT extra,time,IDpren,IDtipo,durata,tipolim,IDpers,sottotip FROM prenextra WHERE ID='$ID' LIMIT 1";
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
				$data=date('Y-m-d',$time);
				$IDpers=0;

if($riagg==0){
	$funz='navigationtxt(2,'."'".$IDpren.",1'".','."'contenutop'".',1)';
}else{
	$funz='riaggvis('."'".$riagg."'".')';
}
echo '<input type="hidden" id="funzioneriagg" value="'.$funz.'">';
				
				
				$step=15;
				$steps=$step*60;
				
				$stepb=$durata/15;
				
				
				$query="SELECT time,checkout,gg FROM prenotazioni WHERE IDv='$IDpren' LIMIT 1";
				$result=mysqli_query($link2,$query);
				$row=mysqli_fetch_row($result);
				$check=$row['0'];
				$checkout=$row['1'];
				$notti=$row['2'];
$timemod=$time;
if(isset($_POST['time'])){
	if(is_numeric($_POST['time'])&&($_POST['time']>0)){
		$timemod=$_POST['time'];
		$data=date('Y-m-d',$_POST['time']);
	}
}
				

$testo='

<input type="hidden" id="IDmodserv" value="'.$ID.'">
<input type="hidden" id="tipomod" value="'.$tipo.'">
<input type="hidden" id="timemod" value="'.$timemod.'">
<input type="hidden" id="riaggmod" value="'.$riagg.'">


<div class="navbar" style="background:#32ae6c;color:#fff;">
               <div class="navbar-inner">
                  <div class="left" align="center">
				  </div>
                  <div class="center">Modifica</div>
                  <div class="right" >
						<a href="#" onclick="myApp.closePanel();"><i class="icon f7-icons" style="color:#fff;  font-size:30px; ">check</i></a>
				
				  
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
				
	
				
				
				$or=orari3(0,$data,$qta,$IDserv,$IDstruttura,0,$IDpers,2,$check,0,$ID,$checkout);


				foreach($or as $key =>$dato){
					foreach($dato as $key2 =>$dato2){
						if($key2<$check)unset($or[$key][$key2]);
						//echo $key.'-'.date('H:i',$key2).'<br>';
					}
				}
				
				//estrarre tutto il personale
				
				$IDpersarr=array();
				$nomepers=array();
				
				$query="SELECT DISTINCT(p.ID),p.nome FROM mansioni as m,mansionipers as ms,personale as p WHERE ms.IDstruttura='$IDstruttura' AND ms.mansione=m.ID AND p.ID=ms.IDpers AND m.tipo='$IDtipo' AND p.ID!=''";
				$result=mysqli_query($link2,$query);
				if(mysqli_num_rows($result)>0){
					while($row=mysqli_fetch_row($result)){
						array_push($IDpersarr,$row['0']);
						$nomepers[$row['0']]=$row['1'];

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
				
				
				
				$claadd='class="modificas"';
				
				
				$first='';
				$txtinto='';
				
				$testo.='
				<div class="list-block" style="margin-top:10px;margin-bottom:0px;">
				  <ul>
					<li class="item-content">
					  <div class="item-inner">
						<div class="item-title">Data</div>
						<div class="item-after"><select style="font-size:13px;" onchange="modificaserv('.$ID.',1,this.value,'.$riagg.')">';
						
						for($i=0;$i<=$notti;$i++){
							$tt=$check+86400*$i;
							$testo.='<option value="'.$tt.'"';
							if(date('Y-m-d',$tt)==$data){
								$testo.=' selected="selected" ';
							}
							$testo.='>'.dataita($tt).'</option>';
							
						}
					
						$testo.='</select></div>
					  </div>
					</li>
					</ul></div>
				
				';
				
				
				
				$testo.= ' <div class="buttons-row" style="width:90%; margin:auto; margin-top:10px;">';
				
				
					
				foreach ($nomepers as  $IDpers =>$nome){
					$active='';
					$active2='';
					
					if($IDpersserv>0){
						if($IDpersserv==$IDpers){$active=' tabac';}
					}else{
						if($first==''){$active=' active';$active2='active';$first='1';}
					}
					$testo.='<a href="#IDpers'.$IDpers.'" class="tab-link   '.$active.' button">'.$nome.'</a>';
	
					
					$txtinto.='<div id="IDpers'.$IDpers.'" class="tab  '.$active2.'" style="overflow-y:visible; padding-top:20px;padding-bottom:50px; " align="left">
							
							';
						//<div class="list-block" style="margin-top:-5px;"><ul>
					
					foreach ($orari as $times){
						
						//if(isset($oraripers[$IDpers][$times])){
							
						$checked='';
						$func='';
						$dis='disabled';
						$clas='color-gray';
						if(in_array($times,$oraripers[$IDpers])){
							$val="'".$sala[$times].'_'.$times.'_'.$IDpers."'";
							$clas='';
							if(($times==$time)&&($IDpers==$IDpersserv)){$clas='active';}
								
							$func=' onclick="modprenextra('.$ID.','.$val.',1,9,2)" ';
							$dis='';
														
						}
						
						if(date('i',$times)=='00'){$txtinto.='<br>';}
						$txtinto.='<a href="#" '.$func.' class="button '.$clas.'" style="width:20%; padding:0px;  display:inline-block; margin:1px;">'.date('H:i',$times).'</a>';
						
						
						
						
						
					}	
					
					$txtinto.='</div>';			
							
	
				}
				
				$testo.='</div>
				
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
				
				
				$qta=1;
				
				
				
				$or=orari3(0,$data,$qta,$IDserv,$IDstruttura,0,$IDpers,2,$check,0,$ID,$checkout);


				//print_r($or);
				
				//estrarre tutto il personale
				
				$sale=array();
				$query="SELECT s.ID,s.nome FROM sale as s,saleex as sc WHERE sc.IDserv='$IDserv' AND sc.IDsala=s.ID LIMIT 1";
				$result=mysqli_query($link2,$query);
				
				if(mysqli_num_rows($result)>0){
					while($row = mysqli_fetch_row($result)){
						$sale[$row['0']]=$row['1'];
					}
					
				}else{
					$query="SELECT s.ID,s.nome FROM sale as s,saleassoc as sc WHERE sc.IDsotto='$IDsotto' AND sc.ID=s.ID ORDER BY sc.priorita LIMIT 1";
					$result=mysqli_query($link2,$query);
					if(mysqli_num_rows($result)>0){
						while($row = mysqli_fetch_row($result)){
							$sale[$row['0']]=$row['1'];
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
					  <div class="item-inner">
						<div class="item-title">Data</div>
						<div class="item-after"><select style="font-size:13px;" onchange="modificaserv('.$ID.',1,this.value,'.$riagg.')">';
						
						for($i=0;$i<=$notti;$i++){
							$tt=$check+86400*$i;
							$testo.='<option value="'.$tt.'"';
							if(date('Y-m-d',$tt)==$data){
								$testo.=' selected="selected" ';
							}
							$testo.='>'.dataita($tt).'</option>';
						}
					
						$testo.='</select></div>
					  </div>
					</li>
					</ul></div>
				
				';
				
				
				
				
				
				
				$testo.= ' <div class="buttons-row" style="width:90%; margin:auto; margin-top:10px;">';
				
				$mattino=array($time0,$time0+3600*14,$time0+3600*20);
				$mattinotxt=array('Mattino <br><span style="font-size:10px;">(Fino alle 14:00)</span>','Pomeriggio<br><span style="font-size:10px;">(Dalle 14:00 alle 20:00)</span>','Sera <br><span style="font-size:10px;">(Dalle 20:00 in Poi)</span>');
				
				
				foreach($sale as $IDsala =>$nomesala){
					$testo.='<a href="#IDsala'.$IDsala.'" class="tab-link  button">'.$nomesala.'</a>';
					
					$txtinto.='<div id="IDsala'.$IDsala.'" class="tab " style="overflow-y:visible;   " align="left">
					
					<div class="list-block accordion-list" style=" background:#ccc;" >
			 						<ul style="margin-top:-450px;">
					
					
					';
					
					
					
					$arrmat=array();
					
					
					$start=0;$j=-1;
					foreach($or[$IDsala] as $times =>$nump){
						if(date('i',$times)=='00'){$txtinto.='<br>';}
						
						
						
						$ma=-1;
						foreach ($mattino as $timet){
							if($times>$timet){
								$ma++;
							}else{
								break;
							}
						}
						if(!isset($arrmat[$ma])){$arrmat[$ma]='';}
						
						$arrmat[$ma].='<a href="#" onclick=""  class="button" style="width:20%; padding:0px;  display:inline-block; margin:1px;">'.date('H:i',$times).'</a>';
						
					}
					
					
					foreach ($arrmat as $key =>$cont){
						$txtinto.='
									<li class="accordion-item " style="padding:0px;">
									
									  <a href="#" class="item-link item-content" >
										
										<div class="item-inner">
										  <div class="item-title">
											'.$mattinotxt[$key].'
										  </div>
										</div>
									  </a>
									  
									  <div class="accordion-item-content">
											<div class="content-block" style="margin-left:20px; margin-right:-20px;">
											'.$cont.'
											
											
									
									</div>
					  			</div></li>
									
									
									';
						
						
					}
					
					
					$txtinto.='
							
							
							</ul></div>';
					
					$txtinto.='</div>';	
				}
				
				
				/*
				
					
				foreach ($nomepers as  $IDpers =>$nome){
					$active='';
					$active2='';
					
					if($IDpersserv>0){
						if($IDpersserv==$IDpers){$active=' tabac';}
					}else{
						if($first==''){$active=' active';$active2='active';$first='1';}
					}
					$testo.='<a href="#IDpers'.$IDpers.'" class="tab-link   '.$active.' button">'.$nome.'</a>';
	
					
					$txtinto.='<div id="IDpers'.$IDpers.'" class="tab  '.$active2.'" style="overflow-y:visible; padding-top:20px;padding-bottom:50px; " align="left">
							
							';
						//<div class="list-block" style="margin-top:-5px;"><ul>
					
					foreach ($orari as $times){
						
						//if(isset($oraripers[$IDpers][$times])){
							
						$checked='';
						$func='';
						$dis='disabled';
						$clas='color-gray';
						if(in_array($times,$oraripers[$IDpers])){
							$val="'".$sala[$times].'_'.$times.'_'.$IDpers."'";
							$clas='';
							if(($times==$time)&&($IDpers==$IDpersserv)){$clas='active';}
								
							$func=' onclick="modprenextra('.$ID.','.$val.',1,9,2)" ';
							$dis='';
						}
						
						if(date('i',$times)=='00'){$txtinto.='<br>';}
						$txtinto.='<a href="#" '.$func.' class="button '.$clas.'" style="width:20%; padding:0px;  display:inline-block; margin:1px;">'.date('H:i',$times).'</a>';
						
					}	
					
					$txtinto.='</div>';			
				}*/
				
				
				$testo.='</div>
				
				<div class="tabs-animated-wrap" style="height:2000px; margin-top:20px;">
  			 <div class="tabs" style="height:600px;" align="center" valign="top">
					'.$txtinto.'
					</div>	
				  </div>
				';
		
				
				
				
				
				
				
				
			break;
		
		
		
	
			}
		
			
			
		}

	
	$testo.='
	
	<br><br><br><br><br>';
	
	echo $testo;
?>