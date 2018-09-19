<?php 
header('Access-Control-Allow-Origin: *');
include('../../../../config/connecti.php');
include('../../../../config/funzioni.php');
include('../../../../config/preventivoonline/config/funzioniprev.php');
$txt='';

$IDpren=$_SESSION['IDstrpren'];

$query="SELECT app,gg,time,IDstruttura,checkout FROM prenotazioni WHERE IDv='$IDpren' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDapp=$row['0'];
$notti=$row['1'];
$time=$row['2'];
$IDstruttura=$row['3'];
$checkout=$row['4'];

$IDserv=$_GET['dato0'];


$query="SELECT servizio,IDtipo,IDsottotip,prezzo,durata,descrizione FROM servizi WHERE ID='$IDserv' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$servizio=$row['0'];
$IDtipo=$row['1'];
$IDsottotip=$row['2'];
$prezzo=$row['3'];
$durata=$row['4'].' Minuti';
$desc=$row['5'];


if(isset($_GET['dato1'])){	
	if($_GET['dato1']!='0'){
			$timeoggi=$_GET['dato1'];		
	}else{
	 $timeoggi=time();
	}
}

$vartempo=$_GET['dato2'];
$vedoswiper=0;
if($vartempo==2){
	$vedoswiper=1;
}

$data=date('Y-m-d',$time);
$dataoggi=date('Y-m-d',$timeoggi);
$finepren=date('Y-m-d',$checkout);

list($yy, $mm, $dd) = explode("-", $data);
$timearr0=mktime(0, 0,0, $mm, $dd, $yy);

list($yy, $mm, $dd) = explode("-", $dataoggi);
$timeoggi0=mktime(0, 0,0, $mm, $dd, $yy);

$sel=$timearr0;
$giornosel=date('N',$time);	
$giornocheckout=date('N',$checkout);	



$temporeale=time();
$temporeale=date('Y-m-d',$temporeale);
list($yy, $mm, $dd) = explode("-", $temporeale);
$temporeale0=mktime(0, 0,0, $mm, $dd, $yy);


if(($timeoggi>$time)&&($timeoggi<$checkout)){
	$sel=$timeoggi0;
}

$giornooggi=date('N',$sel);
if($giornooggi>=$giornosel){
	$start=floor((($sel-$timearr0)/86400)/7);
}else{
	$start=1+floor((($sel-$timearr0)/86400)/7);	
}

if($giornocheckout>=$giornosel){
	$sett=floor((($checkout-$timearr0)/86400)/7);
}else{
	$sett=1+floor((($checkout-$timearr0)/86400)/7);
	
}

$arr=array();
$start2=0;
$giornoocc=array();

$timestart0=$timearr0-86400*($giornosel-1);

for($j=0;$j<=$sett;$j++){
	for($i=1;$i<=7;$i++){
		
		$tt=$timestart0+(86400*($i-1))+(86400*7*$j);
		
		if($tt==$sel){
			//selezionato
		}

		if(($tt>=$timearr0)&&($tt<=$checkout)){
			$arr[$j][$i]='funzione';
			
				if($tt<$temporeale0){
					$arr[$j][$i]='';
				}
		}else{
			$arr[$j][$i]='';
		}
	}
}







$arrserv=array();

$txt.='
<div data-page="addserv" class="page" > 
<input type="hidden" id="IDservadd" value="'.$IDserv.'">
<input type="hidden" id="time" value="'.$timeoggi.'">

            <div class="navbar" >
               <div class="navbar-inner">
			   		<div class="left navbarleftsize170">
						<a href="#" class="link icon-only back"   >
							<i class="material-icons">chevron_left</i>
							<strong class="stiletitolopagine">Prenota Servizio</strong>
						</a>
					</div>
					<div class="center" ></div>
					<div class="right"></div>
			   </div>
			</div>';

							$stampapers=0;
							$prezzotot=0;
							$IDrestrmain=getrestrmain($IDstruttura);
							$query="SELECT t.tipolimite FROM tiposervizio as t,servizi as s WHERE s.ID='$IDserv' AND s.IDtipo=t.ID LIMIT 1";
							$result=mysqli_query($link2,$query);
							$row=mysqli_fetch_row($result);
							$tipolim=$row['0'];
		

							$regola=0;
							$cli=0;//numero di ospiti per pren
							$query="SELECT GROUP_CONCAT(IDrest SEPARATOR ','),COUNT(*),GROUP_CONCAT(ID SEPARATOR ',') FROM infopren WHERE IDpren ='$IDpren' AND pers='1'";
							$result=mysqli_query($link2,$query);
							$row=mysqli_fetch_row($result);
							$IDrestrtxt=$row['0'].',';
							$cli=$row['1'];
							$IDchecked=$row['2'];
							$txt.='<input type="hidden" id="idpersonepres" value="'.$IDchecked.'"> ';	
								if($cli==1){
									$stringacli=$cli.' Persona';
								}else{
									$stringacli=$cli.' Persone';
								}

							switch($tipolim){
									case 1:
										$stampapers=1;
										$prezzotot=calcolaprezzoserv($IDserv,$dataoggi,$IDrestrmain.',',$IDstruttura,0,$IDpren);
									break;
									case 6:
										$prezzotot=calcolaprezzoserv($IDserv,$dataoggi,1,$IDstruttura,0,$IDpren);
										$query="SELECT ID FROM regolaserv WHERE  IDserv='$IDserv' AND IDstr='$IDstruttura' LIMIT 1";
										$result=mysqli_query($link2,$query);
										if(mysqli_num_rows($result)>0){
											$regola=1;
											$IDregola=$row['0'];
										}
									
									
									break;
									case 2:
										$stampapers=1;
										$prezzotot=calcolaprezzoserv($IDserv,$dataoggi,$IDrestrtxt,$IDstruttura,0,$IDpren);
									break;

							}
			
	//#749bfa
		$txt.='
				<div class="bottombarpren" style="background:#203a93;z-index:999;height:45px;padding:5px 10px" align="center">
					<table style="width:100%;" cellpadding="0" cellspacing="0"><tr>
				<td style="width:30%" ><span style="color:#fff;font-size:20px;margin-left:20px">€ <span id="prezzofin">'.$prezzotot.'</span></span><br><span style="font-size: 13px;margin-left:20px;color:#dfe4ec;" id="sconto" > </span></td>
				    <td style="width:30%"></td>
				<td style="width:40%;padding-right:5px"><button class="button" style="height:40px;background:#fff;color:#203a93;border-radius:5px;font-size:13px;width:92%;border:none;font-weight:600;box-shadow: 0 3px 10px 0 rgba(26, 38, 255, 0.1), 0 10px 10px -8px rgba(26, 38, 255, 0.1);padding:5px;" onclick="completapren('.$IDtipo.','.$IDserv.','.$regola.');"><span id="avantitxt">Prenota Ora</span></button></td>
				</tr></table>
			</div>
			
            
			<div class="page-content">
				<div class="content-block" id="prenotanuovoservizio">
						
						<div class="content-block-title titleb" style="margin-top:10px;color:#878787;text-transform:none">Servizio</div>
						<div class="row no-gutter rowlist" style="margin:0px 15px; box-shadow: 0 3px 10px 0 rgba(26, 38, 255, 0.1), 0 10px 10px -8px rgba(26, 38, 255, 0.1);border: solid 1px #dddddd;border-left: 3px solid #203a93;border-radius: 5px" >
							<div class="col-60" style="color:#203a93;font-size:15px;font-weight:600">'.$servizio.'</div>
							<div class="col-40" style="position:relative"><div style="position: absolute;line-height: 30px;top: 0;margin-top: 1px;border-radius: 7px;right: 0;margin-right: 25px;height: 30px;padding-left:7px;padding-right:7px;color:#fff;background: #749bfa;">€ '.$prezzo.'</div></div>';	
							if(($IDtipo==1) || ($IDtipo==10)){
								$desc= preg_replace("#(<br\s*/?>\s*)+#"," ,",$desc);
								if(strlen($desc)>25){
									$desc=stripslashes(substr($desc,0,205));
								 }
								$durata=$desc;
							}
								$txt.='<div class="col-80" style="font-size:12px;font-weight:400;color:#5c5c5c;padding-right:12px">'.$durata.'</div>';		
							
						$txt.='</div>';



if($vedoswiper==0){

				$txt.='<div id="dataservizionuovo">';

				$txt.='<div class="content-block-title titleb" style="color:#878787;text-transform:none;margin-top:10px">Giorno</div>
				<div class="swiper-container sw1" style="height:90px;background-color:#fff; margin:0px 15px;box-shadow: 0 3px 10px 0 rgba(26, 38, 255, 0.1), 0 10px 10px -8px rgba(26, 38, 255, 0.1);border: solid 1px #dddddd;border-radius: 5px;padding: 5px 5px;">
								<table style="width:100%;table-layout:fixed;;padding-top:5px;padding-bottom:5px">
												<tr>
												<td class="fs10 fw600" style="text-align:center">L</td>
												<td class="fs10 fw600" style="text-align:center">M</td>
												<td class="fs10 fw600" style="text-align:center">M</td>
												<td class="fs10 fw600" style="text-align:center">G</td>
												<td class="fs10 fw600" style="text-align:center">V</td>
												<td class="fs10 fw600" style="text-align:center">S</td>
												<td class="fs10 fw600" style="text-align:center">D</td>

												</tr>
								</table>
								<div class="swiper-wrapper">'; 
			
			$primogiorno=0;
			$jj=0;

				foreach($arr as $sett =>$val){
					
					$txt.='<div class="swiper-slide" >
							<table style="width:100%;table-layout:fixed">
								<tr>';
					
					foreach($val as $giorn =>$key){
						
						$serviziopres='';//servizio presente in prenextra
						$txtg='';//numero del giorno
						$active='';//vedo se giorno è selezionato
					 	
						$css='giornonormserv';//classe normale
						$giorno=$timestart0+(86400*($giorn-1))+(86400*7*$sett);
						
						$txtg=date('j',$giorno);	
						
						if($giorno==$sel){
							$active='giornoselserv';
							/*if(($giorno==$sel) && (($giorno==6)||($giorno==7))){
								$active='giornofestsel';
							}*/
						}
							
						

						if($primogiorno==0){
							$meseprimo=$mesiita[date('n',$giorno)];
							$primogiorno=1;
						}
		
						if(isset($arrserv[date('d',$giorno)])){
							$serviziopres='<div class="servpresslider"></div>';
						}
			
						$jj++;
						$func='scorridataserv('.$jj.');ricaricapagserv(1,'.$giorno.');';
						
						if($key==''){
							$css='giornnoneserv';
							$func='';
						}
						
					$txt.='<td class="fs17" style="text-align:center;height:26px; line-height:26px;position:relative">'.$serviziopres.'<div class="scegligserv '.$css.' '.$active.'" id="td'.$jj.'"  onclick="'.$func.'" padre="'.$sett.'">'.$txtg.'</div></td>';	
					}
					$txt.='</tr>
					<tr>
					<td colspan="7" style="padding-left:15px;padding-top:5px;color:#222" class="fs14 fw600 ">'.$meseprimo.'</td>
					</tr>
					</table>
					</div>';
					$primogiorno=0;
				}

						$txt.='</div></div>';

				$txt.='</div>';//fine div dataservizionuovo    

}else{
	
	
	$txt.='		<div class="content-block-title titleb" style="padding-top:10px;color:#878787;text-transform:none;margin-top:10px">Giorno</div>
						<div class="row no-gutter rowlist" style="height:40px;padding:5px 5px;margin:0px 15px; box-shadow: 0 3px 10px 0 rgba(26, 38, 255, 0.1), 0 10px 10px -8px rgba(26, 38, 255, 0.1);border: solid 1px #dddddd;border-left: 3px solid #203a93;border-radius: 5px" ><div style="font-size:20px;margin:10px auto;color: #203a93;font-weight:600">'.date('d',$timeoggi0).' '.$giorniita[date('w',$timeoggi)].'</div></div>';
	
}






if(($regola==0)&&($IDtipo!=10)){
				$txt.='<div id="oraservizionuovo">';

				$step1=15;
				$step=$step1*60;
				$cols=60/$step1;
		
				$min=86400;
				$max=0;
				$arrt=array();
				$qadd='';
				$qta=0;
	
				$query="SELECT ID,IDrest FROM infopren WHERE IDpren='$IDpren' AND pers='1'";
				$result=mysqli_query($link2,$query);
				while($row=mysqli_fetch_row($result)){
					array_push($arrpyes,$row['0']);
					$qta++;
					$IDrestrcalc.=$row['1'].',';
					$IDopt.=$row['0'].',';
				}

				$query="SELECT GROUP_CONCAT(IDorarios SEPARATOR ',') FROM assocorario WHERE IDserv='$IDserv' GROUP BY IDserv";
				$result=mysqli_query($link2,$query);
				if(mysqli_num_rows($result)>0){
					$row=mysqli_fetch_row($result);
					$grora=$row['0'];
					$qadd=" AND ID IN ($grora)";	
				}

				$query="SELECT orarioi,orariof FROM orarisotto WHERE IDsotto='$IDsottotip'  $qadd ORDER BY orarioi";
				$result=mysqli_query($link2,$query);
				if(mysqli_num_rows($result)>0){
					while($row=mysqli_fetch_row($result)){
						$timef=$row['1'];
						$timei=$row['0'];

						$var=$timei%3600;


						$timei=$timei-($timei%3600);
						$timef=$timef-($timef%3600);
						if($timef<$timei){$timef+=86400;}

						$timei=$timeoggi0+$timei;
						$timef=$timeoggi0+$timef;
						for($timei;$timei<$timef;$timei+=3600){
							$arrt[$timei]=$cols;	
						}
					}
				}
					ksort($arrt);
					$colspan=count($arrt)*$cols;
					//prenotazioni di oggi

			$minG=date('G',$timeoggi0+$min);
			$maxG=date('G',$timeoggi0+$max);
			if($maxG<$minG){$maxG+=24;}
			$spazi=($maxG-$minG+1)*2;
			$tmin=$timeoggi0+$minG*3600;
			$tminarr=array($tmin,($tmin+86400));	


				//oraritxt(date('Y-m-d',$time),$ID,$IDpers);
				$IDsala=0;
				$IDpers=0;
				$ID=0;

				$calc=1;
				if(($IDtipo==1)&&($numprenot>0)){ //se c'e' gia' un servizio ristorazione
					$calc=0;
					$or=array();
				}
				if($calc==1){
					$or=orari3(0,$dataoggi,$qta,$IDserv,$IDstruttura,0,$IDpers,1,$time,0,$ID,$checkout,1);
				}
				$arrtime=array();
				if($or){
					foreach ($or as $key =>$dato){
						foreach ($dato as $key2 =>$dato2){
							if(!isset($arrtime[$key2])){
								$arrtime[$key2]=$dato2;
							}else{
								$arrtime[$key2]+=$dato2;
							}
						}	
					}
				}



				$query2="SELECT p.ID,p.time,p.durata FROM prenextra as p WHERE p.IDpren='$IDpren' AND  sottotip='$IDsottotip' AND FROM_UNIXTIME(p.time,'%Y-%m-%d')='$dataoggi' GROUP BY p.ID";
	
	
	$result2=mysqli_query($link2,$query2);
	if(mysqli_num_rows($result2)>0){
		if($IDtipo==1){
			unset($arrtime);
		}else{
			while($row2=mysqli_fetch_row($result2)){
				$tt2=$row2['1'];
				$tt3=$row2['1']+60*$row2['2']-900;
				for($tt2;$tt2<$tt3;$tt2+=900){
					if(isset($arrtime[$tt2])){
						unset($arrtime[$tt2]);
					}
				}
			}
		}
	}

$agg=4;

	
	
	$matt=array();
	
	$arrorari=array();
	
	$time900=$time-900;
	
	foreach ($arrt as $key=>$dato){	
		for($jj=0;$jj<$cols;$jj++){
					$time3=$key+$jj*$step;
					$func='';
					$sel='';

					$dispo=0;

					$t2='';
					$calc=0;
					if(($time3==$time)||($time3==$time900)){
						$sel='';
						$dispo='2';
					}else{
						if(isset($arrtime[$time3])){
							if($arrtime[$time3]<$qta){
								$dispo='0';
							}else{
								$dispo='1';
								$IDsalainto=$arrsale[$time3];
								//if($IDins==0){
									$func=$time3."_".$IDsalainto;
								/*}else{
									$func=$time3."_".$IDsalainto."_".$IDopt."_".$IDserv;
								}*/

							}
						}else{
							$dispo='0';
						}
					}

					if($dispo>0){
						$arrorarif[$time3]=$func;
					}
				}
	}

	$stamp=0;


	$orari=array();
	if(!empty($or)){
		foreach ($or as $IDsala =>$dato){
			foreach ($dato as $timeor =>$nump){
				$min=date('i',$timeor);
				if(($min==15)||($min==45)){
					$timeor=$timeor-900;
				}
				if(!isset($orari[$timeor])){
					$orari[$timeor]='';
				}
			}
		}
	}
	
//<div class="" style="height:50px;background-color:#fff; margin:0px 15px;margin-top:30px;box-shadow: 0 3px 10px 0 rgba(26, 38, 255, 0.1), 0 10px 10px -8px rgba(26, 38, 255, 0.1);border: solid 1px #dddddd;border-radius: 5px;padding: 5px 5px;">
					$scor=0;
					$first=1;
					$txt.='<div class="content-block-title titleb" style="margin-top:10px;color:#878787;text-transform:none">Ora</div>
					<div class="swiper-container  swiper-init "  data-speed="400"  data-pagination=".swiper-pagination2" data-space-between="20" data-slides-per-view="5" data-initial-slide="2"  style="height:50px;background-color:#fff; margin:0px 15px;margin-top:10px;box-shadow: 0 3px 10px 0 rgba(26, 38, 255, 0.1), 0 10px 10px -8px rgba(26, 38, 255, 0.1);border: solid 1px #dddddd;border-radius: 5px;padding: 5px 5px;">
					<div class="swiper-wrapper" style="padding:5px 15px">';
					foreach ($orari as $timeor =>$n){
								$active='';
							if($first==1){
								$active='oraserviziosel';
								$first=0;
							}
						
						$txt.='<div class="swiper-slide">
						<div  class="oraservizio '.$active.'" onclick="scorrioraserv('.$scor.')" id="tempo'.$scor.'" value="'.$timeor.'" alt="'.$timeor.'">'.date('H:i',$timeor).'</div>
						</div>
						';
						$scor++;
					}
				$txt.='</div>
				</div>';


			$txt.='</div>';//fine div oraservizionuovo
}


							$stampa=0;
							$testo='';
							$stile='';
						if($IDtipo==1){
							$stampa=1;
							$stile='right:0;';
						}
			if(($stampa==1)&&($stampapers==1)){
					$txt.='<div style="position:relative;padding-top:35px;">
					';
					
					if($stampa==1){
						$txt.='
							<div class="content-block-title titleb" style="top:0;position:absolute;color:#878787;text-transform:none;margin-top:15px">Dove Vuoi Ricevere il Servizio?</div>
							<div onclick="selezionasaleserv('.$IDserv.')" class="row no-gutter rowlist" style="position:absolute;width:40%;margin:5px 15px; box-shadow: 0 3px 10px 0 rgba(26, 38, 255, 0.1), 0 10px 10px -8px rgba(26, 38, 255, 0.1);border: solid 1px #dddddd;border-left: 3px solid #203a93;border-radius: 5px;padding:15px 0px;">
							<input type="hidden" id="salaid" value="0">
								<span  style="color:#203a93;font-size:15px;font-weight:600;padding-left:7px" id="salaserv">Seleziona Sala</span>					
							</div>';
					}
							
							
					if($stampapers==1){
						$txt.='
						<div class="content-block-title titleb" style="top:0;'.$stile.'position:absolute;color:#878787;text-transform:none;margin-top:15px">Quante Persone?</div>
						<div id="sceglipers" class="row no-gutter rowlist" onclick="verificapersonenuovoserv('."'".$IDchecked."'".')" style="position:absolute;'.$stile.'width:40%;margin:5px 15px; box-shadow: 0 3px 10px 0 rgba(26, 38, 255, 0.1), 0 10px 10px -8px rgba(26, 38, 255, 0.1);border: solid 1px #dddddd;border-left: 3px solid #203a93;border-radius: 5px;padding:15px 0px;">
							<span  style="color:#203a93;font-size:15px;font-weight:600;padding-left:7px"><span id="numospiti">'.$stringacli.'</span></span>					
						</div>';
					}

					$txt.='</div>';

			}



					$txt.='<div class="content-block-title titleb" style="padding-top:35px;color:#878787;text-transform:none">Note</div>
					
							<div style="margin:0px 15px; box-shadow: 0 3px 10px 0 rgba(26, 38, 255, 0.1), 0 10px 10px -8px rgba(26, 38, 255, 0.1);border: solid 1px #dddddd;border-left: 3px solid #203a93;border-radius: 5px;height:80px;">
								<textarea placeholder="Note" id="note" style="resize:none;border:none;border-radius:5px;width:99%;height:94%"></textarea>
							
							</div>
					';

						


			$txt.='<br><br><br><br><br><br>
				</div>
			</div>
		</div>
	';

echo $txt;
?>
