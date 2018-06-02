<?php 
header('Access-Control-Allow-Origin: *');
include('../../../../config/connecti.php');
include('../../../../config/funzioni.php');
//include('../../../../config/preventivoonline/config/funzioniprev.php');


$IDserv=$_POST['IDserv'];

$IDins=intval($_POST['IDins']);
if($IDins!=-1){
	$IDserv=$_POST['IDserv'];
	$timeday=@$_POST['time'];
	$_SESSION['IDservprev4']=$IDserv;
	$_SESSION['timeprev4']=$timeday;
	$_SESSION['IDinsprev4']=$IDins;
}else{
	$IDserv=$_SESSION['IDservprev4'];
	$timeday=$_SESSION['timeprev4'];
	$IDins=$_SESSION['IDinsprev4'];
}


if($timeday==0){
	$_SESSION['reload']=1;
}else{
	unset($_SESSION['reload']);
}

//echo date('d/m/Y',$timeday);
/*
echo '



            <div class="navbar" style="height:50px;">
               <div class="navbar-inner" >';
			   
			   if($IDins==0){
               	echo ' <div class="left" style="width:60px;"><div style="width:20px; margin:auto;" >
				   <a href="#" onclick="addservprev(0)"><i class="icon f7-icons" style=" font-size:27px;color:#fff;">chevron_left</i></a></div>
				  </div>
				  <div class="center" style="font-size:14px; font-weight:600;">NUOVO SERVIZIO</div>
				  ';
			   }else{
				echo '<div class="center" style="font-size:14px;font-weight:600;">MODIFICA SERVIZIO</div>';  
				}
				  echo '
                  
                  <div class="right" style="text-align:center;width:60px; height:100%;" onclick="myApp.closeModal('."'.popupadd'".');">
						<i class="icon f7-icons" style=" font-size:35px;color:#fff;">close</i>
				  </div>
               </div>
            </div>
            


';
*/

$IDrequest=$_SESSION['IDrequest'];


$IDrestr='';


$IDrestrcalc='';

$query="SELECT timearr,notti,checkout,IDstr FROM richieste WHERE ID='$IDrequest' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$check=$row['0'];
$notti=$row['1'];
$checkout=$row['2'];
$IDstruttura=$row['3'];	
$checkout2=$checkout+43200;
	
	
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


if($IDins!=0){
	$query="SELECT time,modi,GROUP_CONCAT(IDrestr SEPARATOR ',') FROM oraripren WHERE IDins='$IDins' AND IDreq='$IDrequest' GROUP BY IDins";
	$result=mysqli_query($link2,$query);
	$row=mysqli_fetch_row($result);
	$time=$row['0'];
	$modi=$row['1'];
}else{
	$modi=0;
	$time=$check;		
}

if((!is_numeric($timeday))||($timeday==0)){
	if($IDins==0){
		$timeday=$check;
	}else{
		$timeday=$time;	
	}
}

$txt='';

$_SESSION['step']=30;


$testo='';

	$arrpnot=array();
	$arrpyes=array();
		
	$IDopt='';
	
	if($IDins!=0){
		$query="SELECT o.time,o.modi,GROUP_CONCAT(o.IDrestr SEPARATOR ','),COUNT(*),GROUP_CONCAT(o.pacc SEPARATOR ','),GROUP_CONCAT(r.IDrestr SEPARATOR ',') FROM oraripren as o,richiestep as r WHERE o.IDins='$IDins' AND o.IDreq='$IDrequest' AND o.IDrestr=r.ID GROUP BY o.IDins";
		$result=mysqli_query($link2,$query);
		$row=mysqli_fetch_row($result);
		
		$modi=$row['1'];
		$time=$row['0'];
		
		
		$opt1=$row['2'];
		$qta=$row['3'];

		$pacc=$row['4'];
		$arropt=explode(',',$opt1);
		$arrpacc=explode(',',$pacc);
		$arrrestr=explode(',',$row['5']);
		
		foreach($arrpacc as $key3=>$dato3){
			if($dato3!=0){
				array_push($arrpnot,$arropt[$key3]);
			}else{
				array_push($arrpyes,$arropt[$key3]);
				$IDrestrcalc.=$arrrestr[$key3].',';	
			}	
			$IDopt.=$arropt[$key3].',';
		}
		$arrp=explode(',',$opt1);
	}else{
		$time=0;
		$modi=0;
		$qta=0;
		$qadd2='';
		if(isset($_SESSION['IDrestrcalc'])){
			$var2=substr($_SESSION['IDrestrcalc'], 0, strlen($_SESSION['IDrestrcalc'])-1); 
			$qadd2=" AND ID IN($var2)";
		}
		
		$query="SELECT ID,IDrestr FROM richiestep WHERE IDreq='$IDrequest' $qadd2";
		$result=mysqli_query($link2,$query);
		while($row=mysqli_fetch_row($result)){
			array_push($arrpyes,$row['0']);
			$qta++;
			$IDrestrcalc.=$row['1'].',';
			$IDopt.=$row['0'].',';
		}
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

$mint=86400;
$maxt=0;

$query="SELECT orarioi,orariof FROM orarisotto WHERE IDsotto='$IDsottotip'  $qadd ORDER BY orarioi";
$result=mysqli_query($link2,$query);
if(mysqli_num_rows($result)>0){
	while($row=mysqli_fetch_row($result)){
		$timef=$row['1'];
		$timei=$row['0'];
		
		$var=$timei%3600;
		if($timef>$maxt){$maxt=$timef;}
		if($timei<$mint){$mint=$timei;}
		
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
if(isset($_SESSION['abilitaall'])){
	$checked=' checked';
}
$testo2='';

if($IDins!=0){
	$testo2.='
<div class="pages navbar-fixed" >
<div data-page="addservprev" class="page" > 
  <div class="navbar">
               <div class="navbar-inner">
			   		<div class="left" onclick="mainView.router.back();" style="width:60px; ">
						<div style="float:left; width:100%;"><i class="icon f7-icons">chevron_left</i></div>
				  </div>
                  <div class="center"  style="font-size:14px; font-weight:600; ">MODIFICA SERVIZIO</div>
                  
               </div>
            </div>


<div class="page-content"> ';
}


$testo2.='



<div class="list-block" style="font-size:13px;" >
  <ul>
  
  	 <li>
      <div class="item-content">
        <div class="item-inner">
          <div class="item-title" style="width:100%; overflow-x:scroll;" id="dataadd">
            <div style="width:'.(80*($notti+1)).'px;" >
        

';

//$finecheck=$check0+($notti+1)*86400;

for($i=0;$i<=$notti;$i++){

	$tt=$check0+$i*86400;
	$cla='';
	if($tt==$time0)$cla='selected';	
	
	
	$testo2.='<a href="#" alt="'.$i.'" onclick="addservprev2('.$IDins.','.$IDserv.','.$tt.',1)" class="roundb3 '.$cla.'">'.$giorniita2[date('N',$tt)].'<br>'.date('d',$tt).'</a>';
	
	
		
		//$testo2.= '<option value="'.$tt.'" '.$sele.'>'.dataita2($tt).'</option>';
	//}
}



$testo2.= '
       
	    </div>
        </div>
      </div>
    </li>
	

';

	$timefin=$time+$durata*60;
	

	//oraritxt(date('Y-m-d',$time),$ID,$IDpers);
	$data=date('Y-m-d',$timeday);
	$IDsala=0;
	$IDpers=0;
	$ID=0;
	
	if(isset($_SESSION['orarioprev'][$IDserv][$data])){
		$or=$_SESSION['orarioprev'][$IDserv][$data];
	}else{
		$or=orari3(0,$data,$qta,$IDserv,$IDstruttura,0,$IDpers,1,$check,0,$ID,$checkout);
		$_SESSION['orarioprev'][$IDserv][$data]=$or;
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
	
	echo '
	<input type="hidden" id="IDservadd" value="'.$IDserv.'">
<input type="hidden" id="IDsaladef" value="'.$IDsaladef.'">
	
	';
	
	
	$arrs=array();
	$arrs2=array();
	$sale='';

	
	$arrs=array(array());

	
	
$txt1='';
	
	$query2="SELECT o.ID,o.time,o.durata FROM oraripren as o,servizi as s WHERE o.IDreq='$IDrequest' AND o.IDserv=s.ID AND s.IDsottotip='$IDsottotip' AND FROM_UNIXTIME(o.time,'%Y-%m-%d')=FROM_UNIXTIME('$timeday','%Y-%m-%d') AND o.IDins!='$IDins' GROUP BY o.IDins";
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

$agg=3;
if($IDins==0){
	$agg=4;
}

	
	
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
							if($IDins==0){
								$func=$time3."_".$IDsalainto."_".$IDopt."_".$IDserv;
							}else{
								$func=$time3."_".$IDsalainto."_".$IDopt."_".$IDserv;
							}
							
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
	
	
	$start=secondinv($mint);
	$fine=secondinv($maxt);
		
	$fine=substr($fine, 0, strlen($fine)-3); 
	$start=substr($start, 0, strlen($start)-3); 	
		
	$fine=$time0+$fine*3600;
	$ini=$time0+$start*3600;	
	
	
	$txtinto='';
	
			$first=0;
			$i=0;
				for($ini;$ini<$fine;$ini+=1800){
					$i++;
					$pos=0;
					$dir='';
					$select='';
					
					if(isset($arrorarif[$ini])){
						$pos=1;
						$dir=''.$arrorarif[$ini].'';
					}else{
						if($IDins==0){
							$dir=$ini."_".$IDsaladef."_".$IDopt."_".$IDserv;
						}else{
							$dir=$ini."_".$IDsaladef."_".$IDopt."_".$IDserv;
						}
					}
					$cla='';
					if($ini==$time){
						$pos=1;
						$cla='selected';
					}
					//$txt1.='<td class="'.$class.'" '.$func.'></td>';
					
					$addcla='';
					if($pos==1){
						$stamp++;
						
						if($first==0){
							if($time==0){
								$cla='selected';
								$first=1;
							}
						}
					}else{
						$addcla='red';
					}
					
					
					if($IDins==0){
						$func='selezorario(this)';
						//$func='gestioneric('.$IDins.','."'".$func."'".',2,10,1)';	
					}else{
						$func='gestioneric('.$IDins.','."'".$dir."'".',2,10,3)';	
					}
					
					//$txtinto.=$func.'<br>';
					$txtinto.='<a href="#" alt="'.$i.'"  id="'.$ini.'" onclick="'.$func.'" class="roundb6  '.$cla.'">'.date('H:i',$ini).'</a>';
					
					
					
				
				}
	
		
		if(strlen($txtinto)>0){
			
			$txt1.='
				 <li>
				  <div class="item-content">
					<div class="item-inner">
					  <div class="item-title" style="width:100%; overflow-x:scroll;" id="orarioadd">
						<div style="width:'.($i*55).'px">
						
						'.$txtinto.'</div>
		    </div>
        </div>
      </div>
    </li>
	';
	}
	

	$txt1.='</ul></div>';

	if(($stamp==0)&&($IDins==0)&&(isset($_SESSION['reload']))){
		echo '<input type="hidden" id="reload" value="1">';
		/*echo '<script>modorpren('.$IDins.','.$IDserv.','.($time0+90000).')</script>';*/
	}
	
	
	
	$func=$time0."_0_".$IDopt."_".$IDserv;
		$select='';
		if($time==$time0){
			$select=' selectclass ';
		}
	
		$testo2.=$txt1.'
		<div class="content-block-title" style="margin-top:-25px;margin-bottom:4px;">Persone</div>
		<div class="list-block">
			  <ul>';
			  
		$persone=0;
		
		$totale=0;
		$query="SELECT ID,restrizione,IDcliente,IDrestr FROM richiestep WHERE IDreq='$IDrequest'";
		$result=mysqli_query($link2,$query);
		while($row=mysqli_fetch_row($result)){
			//$nome=traducis($row['3'],9,$lang,0);
			$nome=$row['1'];
			if($row['2']!=0){
				$query3="SELECT nome,cognome FROM schedine WHERE ID='".$row['2']."' LIMIT 1";
				$result3=mysqli_query($link2,$query3);
				$row3=mysqli_fetch_row($result3);
				$nome=$row3['0'].' '.$row3['1'];
			}	
			$IDrestr=$row['3'].',';	
			
			$clas='';
			$pacchetto='';
			$prezzo=calcolaprezzoserv($IDserv,$time,$IDrestr,$IDstruttura,0,$IDrequest,1,$durata).' €';	
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
				$query4="SELECT o.ID FROM oraripren as o,servizi as s WHERE o.IDreq='$IDrequest' AND FROM_UNIXTIME(o.time,'%Y-%m-%d')=FROM_UNIXTIME('$time','%Y-%m-%d') AND o.IDserv!='$IDserv' AND o.IDserv=s.ID AND s.IDtipo='1' AND s.IDsottotip='$IDsottotip' AND o.IDrestr='".$row['0']."' LIMIT 1";
				$result4=mysqli_query($link2,$query4);
				if(mysqli_num_rows($result4)==0){
					$ok=1;
				}
			}
			
			if($ok==1){
			
				$dis='';
				$color="333";
				if(in_array($row['0'],$arrpnot)){
					$dis=' disabled="disabled"';
					$clas='checked="checked" ';
					$color='229068';
					$prezzo='Incluso';	
				}
				$func='';
				if($IDins!=0){
					$func='cambiadestprev('.$row['0'].','.$IDins.','.$IDserv.')';
				}else{
					$func='ricarcolaadd()';
				}
		
				$testo2.='
					<li>
				  <label class="label-checkbox item-content" >
					<input type="checkbox" class="soggetti"  '.$dis.' id="person'.$row['0'].'" '.$clas.' alt="'.$prezzo.'"  onChange="'.$func.'"  value="'.$row['0'].'" >
					<div class="item-media">
					  <i class="icon icon-form-checkbox"></i>
					</div>
					<div class="item-inner">
					  <div class="item-title">'.$nome.'</div>
					  <div class="item-after">'.$prezzo.'</div>
					</div>
				  </label>
				</li>
				
				';
			}
	
}		
		$testo2.='</ul></div>';
		
		if($totale==0){
			if($IDins!=0){
				$totale='<span style="font-size:16px;">Servizio incluso</span>';
			}else{
				$totale='Totale: <span id="totaleserv">'.$totale.'</span> ';
			}
		}else{
			$totale='Totale: <span id="totaleserv">'.$totale.'</span> ';
		}
		$dis='';
		if($IDins==0){
			$func='aggiungis()';
			if($persone==0){$dis='disabled';}
		}else{
			$func="myApp.closeModal('.popupadd');stepnew(0,0)";
		}
	
		
		$testo2.='
		<span style=" font-size:11px; color:#999;">(*) Per aggiungiere un servizio è necessario selezionare un orario</span>
		
		
		
		
		
		';
	
if($IDins!=0){
	$testo2.='
	
	
	
	
	</div></div>	';
}
	
$txt.=$testo2;

echo $txt;
?>