<?php 
header('Access-Control-Allow-Origin: *');
include('../../../../config/connecti.php');
include('../../../../config/funzioni.php');
//include('../../../../config/preventivoonline/config/funzioniprev.php');


$IDserv=$_POST['IDserv'];

$IDins=intval($_POST['IDins']);
$IDserv=$_POST['IDserv'];
$timeday=$_POST['time'];
/*
if($IDins!=-1){
	
	$_SESSION['IDservprev4']=$IDserv;
	$_SESSION['timeprev4']=$timeday;
	$_SESSION['IDinsprev4']=$IDins;
}else{
	$IDserv=$_SESSION['IDservprev4'];
	//$timeday=$_SESSION['timeprev4'];
	//$IDins=$_SESSION['IDinsprev4'];
}*/

//<input type="hidden" id="IDsaladef" value="'.$IDsaladef.'">

echo '<input type="hidden" id="IDservaggiungi" value="'.$IDserv.'">';
			


if($timeday==0){
	$_SESSION['reload']=1;
}else{
	unset($_SESSION['reload']);
}

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

$query="SELECT s.durata,s.IDsottotip,s.esclusivo,t.tipolimite,s.IDtipo,s.servizio,s.descrizione FROM servizi as s,tiposervizio as t WHERE s.ID='$IDserv' AND s.IDtipo=t.ID LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$durata=$row['0'];	
$IDsottotip=$row['1'];	
$esclusivo=$row['2'];	
$tipolim=$row['3'];
$IDtipo=$row['4'];
$servizio=$row['5'];
$destr=$row['6'];


$testo2='';
		
		if($IDins!=0){
			$testo2.='
		<div class="pages navbar-fixed" >
		<div data-page="addservprev" class="page" > 
		  <div class="navbar">
					   <div class="navbar-inner">
							<div class="left" onclick="mainView.router.back();blockPopstate=true;navigationtxt(6,0,'."'step2'".',0);calcolatot();">
								<i class="icon f7-icons">chevron_left</i>
						  </div>
						  <div class="center">MODIFICA SERVIZIO</div>
						  <div class="right"></div>
					   </div>
					</div>
		
		
		<div class="page-content"> ';
		}
		



switch($tipolim){
	case 2:
		if($IDins!=0){
			$query="SELECT time,modi FROM oraripren WHERE ID='$IDins' AND IDreq='$IDrequest' LIMIT 1";
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
				
				//$query="SELECT o.time,o.modi,GROUP_CONCAT(o.IDrestr SEPARATOR ','),COUNT(*),GROUP_CONCAT(o.pacc SEPARATOR ','),GROUP_CONCAT(r.IDrestr SEPARATOR ',') FROM oraripren as o,richiestep as r WHERE o.IDins='$IDins' AND o.IDreq='$IDrequest' AND o.IDrestr=r.ID GROUP BY o.IDins";
				
				$query="SELECT o.time,o.modi,GROUP_CONCAT(o2.IDsog SEPARATOR ','),COUNT(*),GROUP_CONCAT(o2.pacchetto SEPARATOR ','),GROUP_CONCAT(r.IDrestr SEPARATOR ',') FROM oraripren as o,oraripren2 as o2,richiestep as r WHERE o.ID='$IDins' AND o.IDreq='$IDrequest' AND o.ID=o2.IDoraripren AND o2.IDsog=r.ID GROUP BY o.ID";
				
				
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
				
				
				$arrp=array();
				
				foreach($arropt as $key => $IDsog){
					$arrp[$IDsog]=$arrpacc[$key];
				}
				
				
				
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
		
		$query="SELECT MIN(orarioi),MAX(orariof) FROM orarisotto WHERE IDsotto='$IDsottotip'  $qadd ORDER BY orarioi";
		$result=mysqli_query($link2,$query);
		if(mysqli_num_rows($result)>0){
			while($row=mysqli_fetch_row($result)){
				$timef=$row['1'];
				$timei=$row['0'];
				
			}
		}
		
		$timei=$timei+$time0;
		$timef=$timef+$time0-$durata*60;
		
		
		
		$funcnext='';
		if($IDins==0){
			$funcnext='addservprev2('.$IDins.','.$IDserv.',this.value,1)';
		}else{
			$funcnext='addservprev2('.$IDins.','.$IDserv.',this.value,2)';
		}
		
		
	
		$testo2.='
		<br>
		<div class="list-block item45" >
		  <ul>
		  
		  <li>
			  <a href="#" class="item-link  smart-select"  data-searchbar="false" data-open-in="picker">
				<select id="dataaddserv"  onchange="'.$funcnext.'">';
				
				for($i=0;$i<=$notti;$i++){
					$tt=$check0+$i*86400;
					$cla='';
					if($tt==$time0)$cla=' selected="selected" ';	
				
					$testo2.= '<option value="'.$tt.'" '.$cla.'>'.dataita($tt).'</option>';
				}
				
				$testo2.='</select>
				<div class="item-content">
				  <div class="item-inner">
					<div class="item-title titleform">Data </div>
					<div class="item-after">'.dataita($time0).'</div>
				  </div>
				</div>
			  </a>
			</li>
		  
		  ';
		  
		//	$timefin=$time+$durata*60;
			
		
			//oraritxt(date('Y-m-d',$time),$ID,$IDpers);
			//$data=date('Y-m-d',$timeday);
			$IDsala=0;
			$IDpers=0;
			$ID=0;
			
			/*if(isset($_SESSION['orarioprev'][$IDserv][$data])){
				$or=$_SESSION['orarioprev'][$IDserv][$data];
			}else{*/
		
			
		
				$or=orari3(0,$data,$qta,$IDserv,$IDstruttura,0,$IDpers,1,$check,0,$ID,$checkout);
				$_SESSION['orarioprev'][$IDserv][$data]=$or;
			//}
			
			
			
			
		
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
		
		
		
		
		
		
		
		
			
		$arrdis=array();	
		if($IDtipo==1){
		
		
		
			$query2="SELECT o2.IDsog FROM oraripren as o,oraripren2 as o2 WHERE o.IDreq='$IDrequest' AND o.ID=o2.IDoraripren AND o.IDsottotip='$IDsottotip' AND o.IDserv!='$IDserv' AND FROM_UNIXTIME(o.time,'%Y-%m-%d')='$data'";
			$result2=mysqli_query($link2,$query2);
			if(mysqli_num_rows($result2)>0){
				
				//controlla se tutti eliminia tutto altrimenti abilita normalmente ma solo per chi puo'
				while($row2=mysqli_fetch_row($result2)){
					$arrdis[]=$row2['0'];
				}
				
				
			
				
				
				/*	
				$query2="SELECT ID,IDrestr FROM richiestep WHERE IDreq='$IDrequest' AND ID NOT IN ($IDr)";
				
				$result2=mysqli_query($link2,$query2);
				if(mysqli_num_rows($result2)>0){
					$qta=0;

					while($row2=mysqli_fetch_row($result2)){
						array_push($arrpyes,$row2['0']);
						$qta++;
						$IDrestrcalc.=$row2['1'].',';
						$IDopt.=$row2['0'].',';
					}
					
				}else{
					unset($arrtime);
				}*/
			}
		
	
	}else{
		//$query2="SELECT o.ID,o.time,o.durata FROM oraripren as o,servizi as s WHERE o.IDreq='$IDrequest' AND o.IDserv=s.ID AND s.IDsottotip='$IDsottotip' AND FROM_UNIXTIME(o.time,'%Y-%m-%d')=FROM_UNIXTIME('$timeday','%Y-%m-%d') AND o.IDins!='$IDins' GROUP BY o.IDins";
		
		
		
		$query2="SELECT ID,time,durata FROM oraripren WHERE IDreq='$IDrequest' AND IDsottotip='$IDsottotip' AND FROM_UNIXTIME(time,'%Y-%m-%d')=FROM_UNIXTIME('$timeday','%Y-%m-%d') AND ID!='$ID' GROUP BY ID";
		
		
		$result2=mysqli_query($link2,$query2);
		if(mysqli_num_rows($result2)>0){
			
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
		
	
		
		
		
	$okfunc=1;

	if(empty($arrtime)){
		$okfunc=0;
	}


		
		$txtinto='';
		$orario='';
		
		
		//echo '<br><br>'.$timei.'-'.$time;
		
		$orarid='';
		$orarind='';
		$selected=0;
		for($timei;$timei<=$timef;$timei+=900){
			$dis='';
			$value='';
			$o1=0;
			if(isset($arrtime[$timei])){
				$o1=1;
				$IDsala=$arrsale[$timei];
				//$value=$timei."_".$IDsala."_".$IDopt."_".$IDserv;
				$value=$timei."_".$IDsala;
			}else{
				//$value=$timei."_".$IDsaladef."_".$IDopt."_".$IDserv;
				$value=$timei."_".$IDsaladef;
			}
			$sel='';
			if($timei==$time){
				$sel='selected="selected"';
				$selected++;
			}
			if($o1==1){
				$orarid.='<option value="'.$value.'" '.$sel.'>'.date('H:i',$timei).'</option>';
			}else{
				$orarind.='<option value="'.$value.'" '.$sel.'>'.date('H:i',$timei).'</option>';
			}
		}
		
		
		if(strlen($orarid)>0){
			$txtinto.='<optgroup label="Orari Disponibili">'.$orarid.'</optgroup>';
		}
		if(strlen($orarind)>0){
			$txtinto.='<optgroup label="Orari Non Disponibili">'.$orarind.'</optgroup>';
		}
		
		if($time!=0){
			$orario=date('H:i',$time);
		}
		
		$sel='';
		if($selected==0){
			$sel='selected="selected"';
			$orario='Scegli Orario in Seguito';
		}
		
		$txtinto='<option value="'.$time0.'_0" '.$sel.'>Scegli Orario in Seguito</option>'.$txtinto;
		
		
		
		
		$funcselect='';
		if($IDins!=0){
			$funcselect='nuovoservprev('.$IDins.',this.value,0)';
		}
		
		
		$testo2.='
				<li>
				  <a href="#" class="item-link  smart-select"  data-searchbar="false" data-open-in="picker">
					<select onchange="'.$funcselect.'" id="orarioadd">
					
					'.$txtinto.'</select>
					<div class="item-content">
					  <div class="item-inner">
						<div class="item-title titleform">Orario</div>
						<div class="item-after">'.$orario.'</div>
					  </div>
					</div>
				  </a>
				</li>
				</ul></div>
			';
		
		
		
		$testo2.='</br><div class="titleb">Persone</div>
				<div class="list-block">
					  <ul>';
		
		$persone=0;
		
		//print_r($arrp);
		//print_r($arrdis);

		
				$totale=0;
				$query="SELECT ID,restrizione,IDcliente,IDrestr FROM richiestep WHERE IDreq='$IDrequest'";
				$result=mysqli_query($link2,$query);
				while($row=mysqli_fetch_row($result)){
					//$nome=traducis($row['3'],9,$lang,0);
					$IDsog=$row['0'];
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
					$prezzo=calcolaprezzoserv($IDserv,$time0,$IDrestr,$IDstruttura,0,$IDrequest,1,$durata).' €';
					
					/*if(in_array($row['0'],$arrpyes)){
						$clas=' checked="checked"';
						$pacchetto=$prezzo.' €';
						$totale+=$prezzo;	
						$persone++;
					}*/
					$ok=0;
					$dis='';
					$back='';
					if($IDtipo!='1'){
						$ok=1;
					}else{

						if(in_array($row['0'],$arrdis)){
							$ok=2;
							$dis=' disabled="disabled"';
							$prezzo='Non selezionabile';	
							$back=' style="background:#ffe7e8; opacity:0.7; color:#fff;"';
						}else{
						
							$query4="SELECT o.ID FROM oraripren as o,oraripren2 as o2 WHERE o.IDreq='$IDrequest' AND FROM_UNIXTIME(o.time,'%Y-%m-%d')='$data' AND o.IDsottotip='$IDsottotip' AND o.ID!='$IDins' AND o.ID=o2.IDoraripren AND o2.IDsog='".$row['0']."' LIMIT 1";
							$result4=mysqli_query($link2,$query4);
							if(mysqli_num_rows($result4)==0){
								$ok=1;
							}
						}
					}
					
					if($ok>0){
					
						
						$color="333";
						if($ok==1){
							if(isset($arrp[$IDsog])){
								if($arrp[$IDsog]!=0){
									$clas='checked="checked" ';
									$dis=' disabled="disabled"';
									$prezzo='Incluso';	
								}else{
									$clas='checked="checked" ';
								}
								
							}
						}
						
						
						$func='';
						
						if($IDins!=0){
							$func='cambiadestprev('.$row['0'].','.$IDins.','.$IDserv.')';
						}else{
							$func='ricarcolaadd()';
						}
				
						$testo2.='
							<li '.$back.'>
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
		$testo2.='
		</ul></div>';
		
		
		
			$buttdel='';
				
				if($totale==0){
					if($IDins!=0){
						$totale='<span style="font-size:16px;">Servizio incluso</span>';
						$totale='';
					}else{
						$buttdel='
						
						<div class="list-block">
						  <ul>
							<li>
							  <a href="#" class="item-link list-button " style="font-weight:600; color:#ee1b43;"  onclick="eliminaextraprev('.$IDins.',0,1)">ELIMINA</a>
							</li>
						</ul>
						</div>
						
						';//<br><a href="#" class="button  " id="deleteb">Elimina</a>
						//$totale='Totale: <span id="totaleserv">'.$totale.' Euro</span> ';
					}
				}else{
					//$totale='Totale: <span id="totaleserv">'.$totale.' Euro</span> ';
					//$totale='';
					$buttdel='<br><br><br><br><br><br>
					<div class="list-block">
						  <ul>
							<li>
							  <a href="#" class="item-link list-button " style="font-weight:600; color:#ee1b43;"  onclick="eliminaextraprev('.$IDins.',0,1)">ELIMINA</a>
							</li>
						</ul>
						</div>
					
					
					';
				}

				$testo2.='<input type="hidden" id="totalecalcolato" value="'.$totale.'">';
			
				if($IDins!=0){
					$testo2.='<div style=" font-size:14px; color:#777;text-align:center;">(*) Ogni modifica viene salvata istantaneamente</div>'.$buttdel;
				}
				
				
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		/*
		
		
			if($IDins==0){
				
				$query2="SELECT o.ID,o.time,o.durata FROM oraripren as o,servizi as s WHERE o.IDreq='$IDrequest' AND o.IDserv=s.ID AND s.IDsottotip='$IDsottotip' AND FROM_UNIXTIME(o.time,'%Y-%m-%d')=FROM_UNIXTIME('$timeday','%Y-%m-%d') GROUP BY o.IDins";
			}else{
				$query2="SELECT o.ID,o.time,o.durata FROM oraripren as o,servizi as s WHERE o.IDreq='$IDrequest' AND o.IDserv=s.ID AND s.IDsottotip='$IDsottotip' AND FROM_UNIXTIME(o.time,'%Y-%m-%d')=FROM_UNIXTIME('$timeday','%Y-%m-%d') AND o.IDins!='$IDins' GROUP BY o.IDins";
			}
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
								$cla='selected="selected"';
							}
							//$txt1.='<td class="'.$class.'" '.$func.'></td>';
							
							//$addcla='';
							if($pos==1){
								$stamp++;
								
								if($first==0){
									if($time==0){
										$cla='selected="selected"';
										$first=1;
										$time=$ini;
									}
								}
								$txtinto.='<option value="'.$dir.'" '.$cla.'>'.date('H:i',$ini).'</option>';
								
								
							}
							
				
						}
			
				if($IDins==0){
					$func='';
					//$func='selezorario(this.value)';
				}else{
					$func='gestioneric('.$IDins.',this.value,2,10,3)';	
				}
				if($time!=0){
					$orario=date('H:i',$time);
				}else{
					$orario='Nessun Orario Disponibile';
				}
				$txt1.='
				<li>
				  <a href="#" class="item-link  smart-select"  data-searchbar="false" data-open-in="picker">
					<select onchange="'.$func.'" id="orarioadd">
					<option value="0">Seleziona Orario</option>
					'.$txtinto.'</select>
					<div class="item-content">
					  <div class="item-inner">
						<div class="item-title titleform">Orario</div>
						<div class="item-after">'.$orario.'</div>
					  </div>
					</div>
				  </a>
				</li>
				</ul></div>
			';
		
			if(($stamp==0)&&($IDins==0)&&(isset($_SESSION['reload']))){
				echo '<input type="text" id="reload" value="1">';
			}
			
			
			
			$func=$time0."_0_".$IDopt."_".$IDserv;
				$select='';
				if($time==$time0){
					$select=' selectclass ';
				}
			
				$testo2.=$txt1.'
				<div class="titleb" style="text-align:left; margin-left:20px;">Persone</div>
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
						if($prezzo==0){
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
				
				$buttdel='';
				
				if($totale==0){
					if($IDins!=0){
						$totale='<span style="font-size:16px;">Servizio incluso</span>';
						$totale='';
					}else{
						$buttdel='
						
						<div class="list-block">
						  <ul>
							<li>
							  <a href="#" class="item-link list-button " style="font-weight:600; color:#ee1b43;"  onclick="eliminaextraprev('.$IDins.')">ELIMINA</a>
							</li>
						</ul>
						</div>
						
						';//<br><a href="#" class="button  " id="deleteb">Elimina</a>
						//$totale='Totale: <span id="totaleserv">'.$totale.' Euro</span> ';
					}
				}else{
					//$totale='Totale: <span id="totaleserv">'.$totale.' Euro</span> ';
					//$totale='';
					$buttdel='<br><br><br><br><br><br>
					
					<div class="list-block">
						  <ul>
							<li>
							  <a href="#" class="item-link list-button " style="font-weight:600; color:#ee1b43;"  onclick="eliminaextraprev('.$IDins.')">ELIMINA</a>
							</li>
						</ul>
						</div>
					
					
					';
					//<a href="#" class="button button-fill color-red butt90" id="deleteb" onclick="eliminaextraprev('.$IDins.')">Elimina</a>
				}
				//<div style=" width:95%; font-size:19px; text-align:right; padding-right:15px;">'.$totale.'</div><hr>
				$testo2.='<input type="hidden" id="totalecalcolato" value="'.$totale.'">';
			
				if($IDins==0){
					//$testo2.='<div style=" font-size:14px; width:90%; margin:auto; color:#777; text-align:center;">(*) Per aggiungiere un servizio è necessario selezionare un orario</div>';
				}else{
					$testo2.='<div style=" font-size:14px; color:#777;text-align:center;">(*) Ogni modifica viene salvata istantaneamente</div>'.$buttdel;
				}
				
				
				*/
				
				
				
		break;
		case 1:
		
			//calcolo totale
			$query="SELECT prezzo FROM oraripren  WHERE IDreq='$IDrequest' AND IDins='$IDins'";
			$result=mysqli_query($link2,$query);
			$row=mysqli_fetch_row($result);
			$totale=$row['0'];
		
		
			$testo2.='
			<div class="content-block-title titleb">Servizio</div>
			<div class="list-block media-list">
			  <ul>
				<li>
				  <div class=" item-content">
					<div class="item-inner">
					  <div class="item-title-row">
						<div class="item-title">'.$servizio.' </div>
						<div class="item-after">'.$totale.' €</div>
					  </div>
					  <div class="item-text">'.$descr.'</div>
					</div>
				  </div>
				</li>
			 
			  </ul>
			</div>
			';
			
			if($totale!=0){
				if($IDins!=0){
					$testo2.='<br><br><br><br>
					<div class="list-block">
						  <ul>
							<li>
							  <a href="#" class="item-link list-button " style="font-weight:600; color:#ee1b43;"  onclick="eliminaextraprev('.$IDins.')">ELIMINA</a>
							</li>
						</ul>
						</div>
					
					';
					}
			}
			
			
		
			
		break;
		case 6:
		case 9:
		
			$query="SELECT prezzo,durata FROM oraripren  WHERE IDreq='$IDrequest' AND IDins='$IDins'";
			$result=mysqli_query($link2,$query);
			$row=mysqli_fetch_row($result);
			$totale=$row['0'];
			$qta=$row['1'];
		
			$testo2.='
			<div class="content-block-title titleb">Servizio</div>
			<div class="list-block media-list">
			  <ul>
				<li>
				  <div class=" item-content">
					<div class="item-inner">
					  <div class="item-title-row">
						<div class="item-title">N.'.$qta.' '.$servizio.' </div>
						<div class="item-after">'.$totale.' €</div>
					  </div>
					  <div class="item-text">'.$descr.'</div>
					</div>
				  </div>
				</li>
			  </ul>
			</div>
			';
			
			if($totale!=0){
				if($IDins!=0){
					$testo2.='<br><br><br><br>
					
					<div class="list-block">
						  <ul>
							<li>
							  <a href="#" class="item-link list-button " style="font-weight:600; color:#ee1b43;"  onclick="eliminaextraprev('.$IDins.')">ELIMINA</a>
							</li>
						</ul>
						</div>
					
					';
				}
			}
			
		
		
		break;
		default:
		break;
}
	
	
	

	
if($IDins!=0){
	$testo2.='</div></div>';
}
$txt.=$testo2;

echo $txt;
?>