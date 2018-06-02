<?php 
header('Access-Control-Allow-Origin: *');
include('../../../../config/connecti.php');
include('../../../../config/funzioni.php');
include('../../../../config/preventivoonline/config/funzioniprev.php');


$IDpren=$_SESSION['IDstrpren'];

$query="SELECT app,gg,time,IDstruttura,checkout FROM prenotazioni WHERE IDv='$IDpren' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDapp=$row['0'];
$notti=$row['1'];
$check=$row['2'];
$IDstruttura=$row['3'];
$checkout=$row['4'];


$IDserv=$_GET['dato0'];
$giornoscelto=$_GET['dato1'];

$data=date('Y-m-d',$giornoscelto);

list($yy, $mm, $dd) = explode("-",$data);
$time0=mktime(0, 0, 0, $mm, $dd, $yy);


$query="SELECT servizio,IDtipo,IDsottotip FROM servizi WHERE ID='$IDserv' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$servizio=$row['0'];
$IDtipo=$row['1'];
$IDsottotip=$row['2'];

$query="SELECT s.durata,s.IDsottotip,s.esclusivo,t.tipolimite,s.IDtipo,s.servizio FROM servizi as s,tiposervizio as t WHERE s.ID='$IDserv' AND s.IDtipo=t.ID LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$durata=$row['0'];	
$IDsottotip=$row['1'];		
$esclusivo=$row['2'];	
$tipolim=$row['3'];
$IDtipo=$row['4'];
$servizio=$row['5'];



$txt='		 	
<input type="hidden" id="temposcelto" value="'.date('Y-m-d',$giornoscelto).'">	
<input type="hidden" id="sotto" value="'.$IDsottotip.'">	

<div class="content-block-title titleb">Orari disponibili il '.date('d-m-Y',$giornoscelto).'</div> ';

	$arrpnot=array();
	$arrpyes=array();
		
	$IDopt='';
	
	$time=0;
	$modi=0;
	$qta=0;
	$qadd2='';
$query="SELECT ID,IDrest FROM infopren WHERE IDpren='$IDpren' AND pers='1'";
	$result=mysqli_query($link2,$query);
	while($row=mysqli_fetch_row($result)){
		array_push($arrpyes,$row['0']);
		$qta++;
		$IDrestrcalc.=$row['1'].',';
		$IDopt.=$row['0'].',';
	}

	if($durata==0)$durata=15;

$step1=15;

$step=$step1*60;
$cols=60/$step1;


//fascia oraria
$min=86400;
$max=0;
$arrt=array();
$qadd='';
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
		
		$timei=$time0+$timei;
		$timef=$time0+$timef;
		for($timei;$timei<$timef;$timei+=3600){
			$arrt[$timei]=$cols;	
		}
	}
}

ksort($arrt);
$colspan=count($arrt)*$cols;
//prenotazioni di oggi



$minG=date('G',$time0+$min);
$maxG=date('G',$time0+$max);
if($maxG<$minG){$maxG+=24;}
$spazi=($maxG-$minG+1)*2;
$tmin=$time0+$minG*3600;
$tminarr=array($tmin,($tmin+86400));


	$timefin=$time+$durata*60;
	

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
		$or=orari3(0,$data,$qta,$IDserv,$IDstruttura,0,$IDpers,1,$check,0,$ID,$checkout,1);
	}
	
	
	$arrsale=array();
	$arrtime=array();
	if($or){
		foreach ($or as $key =>$dato){
			foreach ($dato as $key2 =>$dato2){
				if(!isset($arrtime[$key2])){
					$arrtime[$key2]=$dato2;
					$arrsale[$key2]=$key;
				}else{
					$arrtime[$key2]+=$dato2;
				}
			}	
		}
	}
	
	
	//controllare i servizi in orari pren e toglierli da $or
	//eliminare le sale e le fascie orario se ci sono esclusivi
	//raggruppare gli orari su un'unica sala e collegargli la sala in modo da selezionarla già da adesso

	
	$IDsaladef=0;
	
	
	$query="SELECT s.ID,s.maxp FROM sale as s,saleex as sc WHERE sc.IDserv='$IDserv' AND sc.IDsala=s.ID";
	$result=mysqli_query($link2,$query);
	$maxp=0;
	if(mysqli_num_rows($result)>0){
		while($row=mysqli_fetch_row($result)){
			if($IDsaladef==0){$IDsaladef=$row['0'];}
			$maxp+=$row['1'];
		}
	}else{
		$query="SELECT s.ID,s.maxp FROM sale as s,saleassoc as sc WHERE sc.IDsotto='$IDsottotip' AND sc.ID=s.ID";
		$result=mysqli_query($link2,$query);
		$arrs=array(array());
		if(mysqli_num_rows($result)>0){
			while($row=mysqli_fetch_row($result)){
				if($IDsaladef==0){$IDsaladef=$row['0'];}
				$maxp+=$row['1'];
			}
		}
	}
	
	
	
	$arrs=array();
	$arrs2=array();
	$sale='';

	
	$arrs=array(array());

	
	
$txt.='';
	
	
	
	$query2="SELECT p.ID,p.time,p.durata FROM prenextra as p WHERE p.IDpren='$IDpren' AND  sottotip='$IDsottotip' AND FROM_UNIXTIME(p.time,'%Y-%m-%d')=FROM_UNIXTIME('$giornoscelto','%Y-%m-%d') GROUP BY p.ID";
	
	
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


	
	$IDsala='0';
	$query="SELECT s.ID,s.maxp FROM sale as s,saleex as sc WHERE sc.IDserv='$IDserv' AND sc.IDsala=s.ID LIMIT 1";
	$result=mysqli_query($link2,$query);
	$txt.='<input type="hidden" value="'.$IDsottotip.'"> ';
	if(mysqli_num_rows($result)>0){
		$row=mysqli_fetch_row($result);
		$IDsala=$row['0'];
	}else{
		$query="SELECT s.ID,s.maxp FROM sale as s,saleassoc as sc WHERE sc.IDsotto='$IDsottotip' AND sc.ID=s.ID ORDER BY sc.priorita LIMIT 1";
		
		$result=mysqli_query($link2,$query);
		if(mysqli_num_rows($result)>0){
			$row=mysqli_fetch_row($result);
			$IDsala=$row['0'];
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
					$orari[$timeor]=$IDsala;
				}
			}
		}
	}



$scor=0;
	
/*<div class="row rowlist time" id="tempo'.$scor.'" value="'.$timeor.'" alt="'.$timeor.'" onclick="prenditime('.$scor.')">'.date('H:i',$timeor).'
		
				</div>*/


	foreach ($orari as $timeor =>$n){
		
		$txt.='<div class="row rowlist no-gutter time" id="tempo'.$scor.'" value="'.$timeor.'" alt="'.$timeor.'" onclick="cambiaiconatime('.$scor.')">
								<div class="col-15">
									<div style="color:#a1a1a1;font-size:22px">
									 <i class="f7-icons">circle</i>
									</div>
								</div>
				<div class="col-40" style="margin-top:5px"><span style="margin-left:5px;font-size:16px;">'.date('H:i',$timeor).'</span></div>
				<div class="col-25" style="margin-top:5px"><span style="font-size:13px;font-weight:100">'.date('l',$timeor).'</span></div>
			</div>
		
		
		
		';
		$scor++;
	}


  
?>
<div class="content-block" id="prenotanuovservstep"> 
<?php echo $txt;?>
</div>