<?php 
	include('../../../config/connecti.php');
	include('../../../config/funzioni.php');
	include('../../../config/funzionilingua.php');
	header('Access-Control-Allow-Origin: *');
	$testo='';


$IDpren=$_SESSION['IDstrpren'];
$route=$_SESSION['route'];

$ID=$_GET['dato0'];



$query="SELECT app,gg,time,tempg,tempn,checkout,IDstruttura FROM prenotazioni WHERE IDv='$IDpren' LIMIT 1";

$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDapp=$row['0'];
$notti=$row['1'];
$check=$row['2'];
$tempg=$row['3'];
$tempn=$row['4'];
$checkout=$row['5'];
$IDstr=$row['6'];

$nomepren=estrainome($IDpren);
$timeora=oraadesso($IDstr);

$serviziarr=array();
$prodottiarr=array();

$query="SELECT p.IDtipo,p.tipolim,s.servizio,p.time,p.modi,p.extra,p.sottotip,p.durata,p.IDpers,p.sala,GROUP_CONCAT(p2.IDinfop SEPARATOR ',') FROM prenextra as p,servizi as s,prenextra2 as p2 WHERE p.ID='$ID' AND p.extra=s.ID AND p.ID=p2.IDprenextra GROUP BY p.ID";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDtipo=$row['0'];
$tipolim=$row['1'];
$servizio=$row['2'];
$time=$row['3'];
$modi=$row['4'];
$IDserv=$row['5'];
$IDsotto=$row['6'];
$durata=$row['7'];
$IDpersserv=$row['8'];
$IDsalaserv=$row['9'];
$IDinfoparr=explode(',',$row['10']);

if($time==0){
	if($timeora>$check){
		if($timeora<$checkout){
			$time=$timeora;
		}else{
			$time=$check;
		}
	}else{
		$time=$timeora;
	}
}


$timesel=0;
$timeselfin=0;
if($modi>0){
	$timesel=$time;
	$timeselfin=$timesel+$durata*60;
}



$foto='immagini/big'.getfoto($IDserv,4);


echo '<input type="hidden" id="funcreload" value="navigation(25,'.$ID.',0,1)">';


$testo='<div data-page="detserv" class="page" > 
		 	<div class="navbar" id="navcal" >
				<div class="navbar-inner">
					<div class="left">
					
					  <a href="#" class="link icon-only back"  >
						<i class="material-icons fs40" >chevron_left</i>
					</a>
					</div>
					<div class="center titolonav">'.$servizio.'</div>
					<div class="right"></div>
				</div>
			</div>
		 <div class="page-content">
              <div class="content-block" id="detserv"> 
	
			  ';
			  

/*<div class="dettaglioserviziodiv" style="background:url('.$route.$foto.') no-repeat center center;">
			  	<div class="dettagliservoverlay"></div>
			</div>*/

			  
	
	$_SESSION['timecal']=$time;
	$data=date('Y-m-d',$time);
	
	$timemod=$time;
if(isset($_GET['dato1'])){
	if(is_numeric($_GET['dato1'])&&($_GET['dato1']>0)){
		$timemod=$_GET['dato1'];
		$data=date('Y-m-d',$timemod);
	}
}
	
	$step=30;
				$steps=$step*60;
				
				$stepb=$durata/$step;
				
		if($tipolim==2){
			  
			  
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
					$query2="SELECT p.ID FROM prenextra as p,prenextra2 as p2 WHERE FROM_UNIXTIME(p.time,'%Y-%m-%d')=FROM_UNIXTIME('$timemod','%Y-%m-%d') AND p.IDstruttura='$IDstr' AND  p.IDpren='$IDpren' AND p.sottotip='$IDsotto' AND p.modi>'0' AND p.ID!='$ID' AND p.ID=p2.IDprenextra AND p2.IDinfop IN($groupid)";
					
					$result2=mysqli_query($link2,$query2);
					if(mysqli_num_rows($result2)>0){
						$dis1=1;
					}
				}
				
				
				//estrarre tutto il personale
				$maxp=array();
				$sale=array();
				$IDsalamain=0;
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
			
			
			
				
				
				if($IDsalaserv==0){$IDsalaserv=$IDsalamain;}
							
					$testo.='<div class="content-block-title titleb">Data<br/>
					
					
					</div>
						<div class="list-block">
  <ul>
    <li>
      <a href="#" class="item-link smart-select" data-searchbar="false" data-open-in="picker" data-open-in="picker" pickerHeight="400px" >
        <select id="datamod" onChange="modificaorario('.$ID.',1,this.value,1)">';
	
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
            <div class="item-after" style="width:100%; max-width:100%; text-align:center; font-size:17px;"></div>
          </div>
        </div>
      </a>
    </li>
  
';
				
			
				
				
				$orari=array_unique($orari);
				
				
				$valarr=array();
	
	
				if($dis1==1){
					$testo.='<div class="txtservprenot">Questo servizio non pu&ograve; essere ricevuto due volte lo stesso giorno.</div>';
				}else{
				
					/*if(isset($_SESSION['orario'][$IDserv][$data])){
						$or=$_SESSION['orario'][$IDserv][$data];
					}else{
						$or=orari3(0,$data,$qta,$IDserv,$IDstruttura,0,$IDpers,3,$check,0,$ID,$checkout);
						$_SESSION['orario'][$IDserv][$data]=$or;
					}*/
					
					$IDinfoporario=array();
					$or=orari4(0,$data,$qta,$IDserv,$IDstruttura,$IDinfoporario,$IDpers,1,$check,$ID,$checkout);
					
					
					/*
					foreach ($or as $IDsala =>$dato){
						foreach($dato as $time =>$dato2){
							$testo.=date('H:i',$time).'<br>';
						}
					}*/
					
					
					$txtinto='';
					
					//$testo.= '<div class="buttons-row">';
					
					
					//$active='';
					//$active2='';
					$IDpersactive='';
					
					/*
					foreach($sale as $IDsala =>$nomesala){
						
						//$testo.='<a href="#IDsalamod'.$IDsala.'" class="tab-link '.$active.' button ">'.$nomesala.'</a>';
							
						$okpers=0;
						
						$first=0;
						
						
						
						foreach ($orari as $times){
							
							
							
							//$clas='notdispo';
							$idinto='';
							//$txtdispo='NON DISPONIBILE';
							
							
							
							
							
							$val=$IDsala.'_'.$times.'_0';
							
							if(!isset($valarr[$times])){
								$valarr[$times]=$IDsala.'_'.$times.'_0';
							}		
									
							//$func=' onclick="modprenextra('.$ID.','.$val.',1,9,2);" ';
							$dis='';
							
							$qta=0;
							
							
							
							
						}
					}
					*/
					
					
					$testo.='</ul></div>';
					
					
					//$IDinfoporario=array();
					
					$occup=array();
					$IDsalat=array();
					foreach ($orari as $times){
							
							if(!isset($valarr[$times])){

								foreach($sale as $IDsala =>$nomesala){
									if(isset($or[$IDsala][$times])){
										
										if(arraycerca($IDinfoparr,$IDinfoporario[$IDsala][$times])){ //controlla se c'e' qualcuno di loro in quell'ora
											
										
											$val=$IDsala.'_'.$times.'_0';
											$occup[$times]=$or[$IDsala][$times];
											$IDsalat[$times]=$IDsala;

											//$testo.=date('H:i',$times).'--//'.$occup[$times].'//'.$IDinfoporario[$IDsala][$times][0].'<br>';
											
											$valarr[$times]=$IDsala.'_'.$times.'_0';
										}else{
											//rimuove quelli prima secondo durata
											
											for($k=$times-$durata*60;$k<=$times;$k+=$steps){
												//$testo.='CANC'.date('H:i',$k).'<br>';
												if(isset($valarr[$k])){
													unset($valarr[$k]);
												}
											}
											
											
											
										}
										//}


									}
								}
							}
						}
					
					
					//$testo.=date('H:i',$timesel);
					
					
					$txtinto='
					
					<li>
					  <label class="label-radio item-content" onclick="modprofilo('.$ID.','."'0_".$time0."_0'".',6,10)">
						<input type="radio" name="orario">
						<div class="item-inner">
						  <div class="item-title" style="font-size:18px; padding-left:15px; color:#2542d9;">--.--</div>
						<div class="item-after">Sceglilo più tardi &nbsp;&nbsp;</div>
						  
						</div>
					  </label>
					</li>
					
					
					';
					
					
					
					
					
					foreach ($orari as $times){
							$sel='';
						
							if(!isset($valarr[$times])){

								
								if($times==$timesel){$sel='checked="checked"'; $okpers=1;}
								
								$txtinto.='
								<li>
								  <label class="label-radio item-content">
									<input type="radio" disabled="disabled" name="orario" '.$sel.'>
									<div class="item-inner">
									  <div class="item-title" style="font-size:18px; padding-left:15px; color:#666;">'.date('H:i',$times).'</div>
									</div>
								  </label>
								</li>

								';

								
								
								
								
								
							}else{
								
								$val=$valarr[$times];
								
								$txtoccup='';
								$disab='';
								$numpsala=$IDsalat[$times];
								$perc=($occup[$times]/$numpsala)*100;
								$colorin='31aa21';
								if($perc>50){
									//$txtoccup='Molto Tranquillo';
									$txtoccup='Disponibile';
								}else{
									$colorin='e89300';
									if($occup[$times]<=0){
										
										if(($times>=$timesel)&&($times<=$timeselfin)){
											//$txtoccup='Posti Liberi: '.$qta;
											$txtoccup='Disponibile';
										}else{
											$colorin='b20036';
											$txtoccup='Non Disponibile';
											$disab='disabled="disabled"';
										}
									}else{
										if($qta<$occup[$times]){
											$colorin='b20036';
											$txtoccup='Non Disponibile';
											$disab='disabled="disabled"';
										}else{
											//$txtoccup='Posti Liberi: '.$occup[$times];	
											$txtoccup='Disponibile';
										}
										
									}
								}
								
								if($times==$timesel){$sel='checked="checked"'; $okpers=1;}
								
								if(($times>=$check)||(($timesel-3600)<$check)){
									$txtinto.='
									<li onclick="modprofilo('.$ID.','."'".$val."'".',6,10)" '.$disab.'>
									  <label class="label-radio item-content">
										<input type="radio" name="orario" '.$sel.'>
										<div class="item-inner">
										  <div class="item-title" style="font-size:18px; padding-left:15px; color:#2542d9;">'.date('H:i',$times).'</div>
										  <div class="item-after" style="color:#'.$colorin.'">'.$txtoccup.' &nbsp;&nbsp;</div>
										</div>
									  </label>
									</li>

									';
								}
								
								

								
								
								
							}
						
							
							
					}
					
					
					
				
					
					$testo.='
					<div class="content-block-title titleb">Orario<br/>
					<span>'."Clicca sull'orario per prenotare l'orario di inizio servizio<br/>
					Ogni orario puo' essere modificato in qualsiasi momento.<br/>
					
					".'</span>
					
					</div>
					
					
					
					<div class="list-block">
  <ul>
					'.$txtinto.'
					
					<input type="hidden" value="'.$IDpersactive.'" id="IDperssel">
					';
			  
			  
			  
				}
			  
			  $testo.='</ul></div>';
			  
			  
			  
			  
			  
			  
		}else{
			if($tipolim=='1'){
				$testo.='<p class="p10" >'."<b>Per modificare l'orario si prega di contattare  il personale addetto. <br>Grazie</b></p>";
			}
		}
			  
			  
			  $testo.='<br><br><br><br><br><br><hr class="separa">
			  <div class="p20 textcenter" ><b>Il Servizio</b><br><br>'.traducis('',$IDserv,2,0,1).' </div>
			  <hr class="separa">
			  
			 ';
			   
			   
			  
			
			  
			  
			  if(($tipolim==2)||($tipolim==1)){
					
				 $testo.=' 
				 	 <div class="p20 textcenter"">
					   <b>Gli Orari</b><br>
					  '.orariservizio($IDserv).'
					  
					  </div>
					   <hr class="separa">
				 
				 
				  ';	
					
					
					
				$querylim="SELECT s.ID,s.nome,s.maxp FROM sale as s,saleassoc as sa WHERE s.IDstr='$IDstr' AND s.ID=sa.ID AND sa.IDsotto='$IDsotto'";
				$resultlim=mysqli_query($link2,$querylim);
				if(mysqli_num_rows($resultlim)>0){
					$testo.='<div class="p20 textcenter">
					   <b>Le Sale</b><br>';
					 
					while($rowlim=mysqli_fetch_row($resultlim)){
						$testo.=$rowlim['1'].'<br>';
					}	
					
					$testo.='</div>
					 <hr class="separa">';
				}
				
				
			}
			  
			  
			  
			  
			  
			  
			  
			  
			  //operatori
			  

$testo.='<br><br><div class="infoservattivi"><span class="infoservattivitxt">&Egrave; possibile modificare gli orari fino a 4h prima del suo inizio.<br>Per qualsiasi altre informazioni o modifica contrattare la struttura.</span></div>



</div></div></div>


';




if(!isset($inc)){
echo $testo;
}




?>