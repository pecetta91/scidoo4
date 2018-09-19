<?php 
header('Access-Control-Allow-Origin: *');
include('../../../config/connecti.php');
include('../../../config/funzioni.php');
include('../../../config/preventivoonline/config/funzioniprev.php');


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

$timeday=$_GET['dato1'];



if($timeday==0){
	$timeday=$check;
}



$query="SELECT servizio,IDtipo,IDsottotip FROM servizi WHERE ID='$IDserv' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$servizio=$row['0'];
$IDtipo=$row['1'];
$IDsottotip=$row['2'];

$orarinot=array();
$note='';
if($IDtipo==2){
	$query2="SELECT ID,extra,time FROM prenextra WHERE IDpren='$IDpren' AND IDtipo='$IDtipo' AND FROM_UNIXTIME(time,'%Y-%m-%d')=FROM_UNIXTIME($timeday,'%Y-%m-%d') AND modi!='0' ";
}else{
	$query2="SELECT ID,extra,time FROM prenextra WHERE IDpren='$IDpren' AND sottotip='$IDsottotip' AND FROM_UNIXTIME(time,'%Y-%m-%d')=FROM_UNIXTIME($timeday,'%Y-%m-%d') AND modi!='0'";	
}

$numprenot=0;

$result2=mysqli_query($link2,$query2);
if(mysqli_num_rows($result2)>0){
	$note.='<div style="font-size:11px; padding-left:15px;"><b>NOTE:</b><br>';
	while($row2=mysqli_fetch_row($result2)){
		$numprenot++;
		if($row2['1']==$IDserv){
			$note.=" - Hai gia'  lo stesso servizio per questa data alle ".date('H:i',$row2['2']).'';
		}else{
			$note.=" - Hai gia'  un servizio simile per questa data alle ".date('H:i',$row2['2']).'';
		}
	}
	$note.='</div><br>';
}



echo '


<div data-page="addserv" class="page" > 
            <div class="navbar" >
               <div class="navbar-inner">
			   		<div class="left">
					
					  <a href="#" class="link icon-only back"    >
						<i class="material-icons" style="font-size:40px;">chevron_left</i>
					</a>
					</div>
                  <div class="center titolonav">'.strtoupper($servizio).'</div>
                  <div class="right">
						
				  </div>
               </div>
            </div>
            
<div class="page-content">
		 	
<div class="content-block" id="detserv"> 

';




$IDrestr='';

$IDrestrcalc='';


list($yy, $mm, $dd) = explode("-", date('Y-m-d',$check));
$check0=mktime(0, 0, 0, $mm, $dd, $yy);

	
	
$dcheck=date('d',$check);
$dcheckout=date('d',$checkout);	
	
$modi=0;
$time=0;

$query="SELECT s.durata,s.IDsottotip,s.esclusivo,t.tipolimite,s.IDtipo,s.servizio FROM servizi as s,tiposervizio as t WHERE s.ID='$IDserv' AND s.IDtipo=t.ID LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$durata=$row['0'];	
$IDsottotip=$row['1'];	
$esclusivo=$row['2'];	
$tipolim=$row['3'];
$IDtipo=$row['4'];
$servizio=$row['5'];


$modi=0;
$time=$check;		

if($timeday==0){
	$timeday=$check;
}

$txt='';

$_SESSION['step']=30;


$testo='';

	$arrpnot=array();
	$arrpyes=array();
		
	$IDopt='';
	
	$time=0;
	$modi=0;
	$qta=0;
	$qadd2='';
	/*if(isset($_SESSION['IDrestrcalc'])){
		$var2=substr($_SESSION['IDrestrcalc'], 0, strlen($_SESSION['IDrestrcalc'])-1); 
		$qadd2=" AND ID IN($var2)";
	}
	*/
	
	$query="SELECT ID,IDrest FROM infopren WHERE IDpren='$IDpren' AND pers='1'";
	$result=mysqli_query($link2,$query);
	while($row=mysqli_fetch_row($result)){
		array_push($arrpyes,$row['0']);
		$qta++;
		$IDrestrcalc.=$row['1'].',';
		$IDopt.=$row['0'].',';
	}
	
	
	echo '<input type="hidden" id="IDrestr" value="'.$IDrestrcalc.'">';

	if($durata==0)$durata=15;

$step1=15;

$step=$step1*60;
$cols=60/$step1;

$data=date('Y-m-d',$timeday);
list($yy, $mm, $dd) = explode("-", $data);
$time0 = mktime(0,0, 0, $mm, $dd, $yy);



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
$checked='';


$testo2='
<input type="hidden" id="IDservadd" value="'.$IDserv.'">




<div class="content-block-title titleb">Data e Orario</div>
						<div class="list-block">
  <ul>
    <li>
      <a href="#" class="item-link smart-select" data-searchbar="false" data-open-in="popup" >
        <select id="datamod" onChange="prenotaora('.$IDserv.',this.value,1)">';
	
		
			for($i=0;$i<=$notti;$i++){
			
				$tt=$check0+$i*86400;
				$sele='';
				if($tt==$time0)$sele=' selected="selected"';	
				$testo2.='<option value="'.$tt.'" '.$sele.'>'.dataita($tt).' '.date('Y',$tt).'</option>';
			}
	
          
        $testo2.='</select>
        <div class="item-content">
          <div class="item-inner">
            <div class="item-title">Data del Servizio</div>
            <div class="item-after"></div>
          </div>
        </div>
      </a>
    </li>


';

//$finecheck=$check0+($notti+1)*86400;






	$timefin=$time+$durata*60;
	

	//oraritxt(date('Y-m-d',$time),$ID,$IDpers);
	$data=date('Y-m-d',$timeday);
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

	
	
$txt1='';
	
	
	
	$query2="SELECT p.ID,p.time,p.durata FROM prenextra as p WHERE p.IDpren='$IDpren' AND  sottotip='$IDsottotip' AND FROM_UNIXTIME(p.time,'%Y-%m-%d')=FROM_UNIXTIME('$timeday','%Y-%m-%d') GROUP BY p.ID";
	
	
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
	$txtinto='';
	
	
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
	
	foreach ($orari as $timeor =>$IDsala){
		$stamp++;
		$func=$timeor.'_'.$IDsala;
		//$txtinto.='<div alt="'.$func.'" onclick="selorario(this)" class="buttore" >'.date('H:i',$timeor).'</div>';
		
		$txtinto.='<option value="'.$func.'">'.date('H:i',$timeor).'</option>';
		
	}
	
	
	
		
		if(strlen($txtinto)>0){
			//if($IDins==0){
			$val0='0_0';
			$func='ricalcolaadd()';
			
			
	
			$txt1.='
			<li>
			  <a href="#" class="item-link smart-select" data-searchbar="false" data-open-in="popup" >
				<select id="orariadd">'.$txtinto.'</select>
				<div class="item-content">
				  <div class="item-inner">
					<div class="item-title">Orario</div>
					<div class="item-after"></div>
				  </div>
				</div>
			  </a>
			</li>';
			
	
	
		}else{
			$txt1.='
				 <li>
     
				<div class="item-content">
				  <div class="item-inner">
					<div class="item-title">Orari</div>
					<div class="item-after">Non ci sono orari disponibili</div>
				  </div>
				</div>
			  </a>
			</li>
		
	
			';
			
		
		}
		$txt1.='</ul></div>';
	


	if(($stamp==0)){
		echo '<input type="hidden" id="reload" value="1">';
		/*echo '<script>modorpren('.$IDins.','.$IDserv.','.($time0+90000).')</script>';*/
	}
	
	$func=$time0."_0_".$IDopt."_".$IDserv;
		$select='';
		if($time==$time0){
			$select=' selectclass ';
		}
		$testo2.=$txt1.'
		
		'.$note.'
		<div style="  width:100%;text-align:center;  font-size:13px; color:#b92282; font-weight:600;">PERSONE</div>
		<div class="list-block" style="margin-top:5px;">
			  <ul>';
			  
		$persone=0;
		
		$totale=0;
		$query="SELECT ID,IDcliente,IDrest,nome FROM infopren WHERE IDpren='$IDpren' AND pers='1'";
		$result=mysqli_query($link2,$query);
		while($row=mysqli_fetch_row($result)){
			//$nome=traducis($row['3'],9,$lang,0);
			$nome=$row['3'];
			if($row['2']!=0){
				$query3="SELECT nome,cognome FROM schedine WHERE ID='".$row['1']."' LIMIT 1";
				$result3=mysqli_query($link2,$query3);
				$row3=mysqli_fetch_row($result3);
				
				
				$nome='<b>'.$row3['0'].' '.$row3['1'].'</b><br><span style="font-size:9px;">'.$nome.'</span>';
			}	
			
			$IDrestr=$row['2'].',';	
			$prezzo='Incluso';	
			$prezzo=calcolaprezzoserv($IDserv,$time,$IDrestr,$IDstruttura,0,$IDpren,0,$durata);	
			$clas='';
			$pacchetto='';
			if(in_array($row['0'],$arrpyes)){
				$clas=' checked="checked"';
				$pacchetto=$prezzo.' €';
				$totale+=$prezzo;	
				$persone++;
			}
			$ok=0;
			if($IDtipo!='1'){
				$ok=1;
			}else{
				$ok=1;
				//$query4="SELECT o.ID FROM oraripren as o,servizi as s WHERE o.IDreq='$IDrequest' AND FROM_UNIXTIME(o.time,'%Y-%m-%d')=FROM_UNIXTIME('$time','%Y-%m-%d') AND o.IDserv!='$IDserv' AND o.IDserv=s.ID AND s.IDtipo='1' AND s.IDsottotip='$IDsottotip' AND o.IDrestr='".$row['0']."' LIMIT 1";
				
				//se qualcun'altro ce l'ha gia' lo esclude
				/*
				$query4="SELECT ID FROM prenextra WHERE IDpren='$IDpren' AND FROM_UNIXTIME(time,'%Y-%m-%d')=FROM_UNIXTIME('$time','%Y-%m-%d') AND o.IDserv!='$IDserv' AND o.IDserv=s.ID AND s.IDtipo='1' AND s.IDsottotip='$IDsottotip' AND o.IDrestr='".$row['0']."' LIMIT 1";
				
				$result4=mysqli_query($link2,$query4);
				if(mysqli_num_rows($result4)==0){
					$ok=1;
				}*/
			}
			
			if($ok==1){
			
				$dis='';
				$color="333";
				if(in_array($row['0'],$arrpnot)){
					$dis=' disabled="disabled"';
					$clas='checked="checked" ';
					//$pacchetto=traduci('Incluso',$lang,1,0);
					$color='229068';
				}
				$func='';
				
				$func='ricarcolaadd()';
				
				$testo2.='
					<li style="font-size:13px;">
				  <label class="label-checkbox item-content" >
					<input type="checkbox" class="soggetti"  '.$dis.' id="person'.$row['0'].'" '.$clas.' alt="'.$prezzo.'"  onChange="'.$func.'"  value="'.$row['0'].'" >
					<div class="item-media">
					  <i class="icon icon-form-checkbox"></i>
					</div>
					<div class="item-inner">
					  <div class="item-title" style="line-height:12px;">'.$nome.'</div>
					  <div class="item-after">'.$prezzo.'€</div>
					</div>
				  </label>
				</li>
			  
				
				';
			}
	
}		
		$testo2.='</ul></div>';
		
		if($totale==0){
			if($IDins!=0){
				$totale='Servizio incluso';
			}else{
				$totale='<span id="totaleserv">'.$totale.'</span> €';
			}
		}else{
			$totale='<span id="totaleserv">'.$totale.'</span> €';
		}
		$func='prenotaora2()';
		
		
		
		
		if(strlen($txtinto)>0){
			
			$testo2.='<div  onclick="'.$func.'" style="width:100%; height:50px; font-size:16px; font-weight:bold; line-height:50px; padding:0px; text-align:center; position:fixed; background:#28a164; color:#fff;  bottom:0px; left:0px;transform:translateZ(0); webkit-transform:translateZ(0);">PRENOTA</div>';
		}else{
			$testo2.='<div style="width:100%; height:45px; font-size:16px; font-weight:bold; line-height:20px; padding:0px;  padding-top:5px;text-align:center; position:fixed; background:#ccc; color:#666;  bottom:0px; left:0px;transform:translateZ(0); webkit-transform:translateZ(0);">NON DISPONIBILE<br><span style="font-size:13px; font-weight:100;">'.dataita4($timeday).'</span></div>';
		}
		
		
		/*
		$testo2.='<div style="width:100%; margin-top:-15px;" align="center">
		<span style="font-size:20px;  font-weight:600; color:#22c782;">'.$totale.'</span><br><br>
		<a class="button active button-fill" id="confbutton" onclick="'.$func.'"  style="width:60%; margin:auto;">Prenota Ora</a><br><br>';
		*/
		$testo2.='<div style="width:95%;" align="right">
		<span style="font-size:30px;  font-weight:600; color:#22c782;">'.$totale.'</span><br><br>
		</div>
		<hr>
		<span style=" font-size:11px; color:#999;">(*) Per aggiungiere un servizio è necessario selezionare un orario</span>
		';
	
$txt.=$testo2.'</div></div>';

echo $txt;
?>