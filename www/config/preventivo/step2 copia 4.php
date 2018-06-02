<?php
header('Access-Control-Allow-Origin: *');
include('../../../config/connecti.php');
include('../../../config/funzioni.php');
//include('../../../config/preventivoonline/config/funzioniprev.php');

$IDutente=$_SESSION['ID'];
$IDstruttura=$_SESSION['IDstruttura'];
$IDrequest=$_SESSION['IDrequest'];




$txtp='';

$query="SELECT IDstr,notti,timearr,stato,checkout FROM richieste WHERE ID='$IDrequest' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDstruttura=$row['0'];
$gg=$row['1'];
$ggric=$gg;
$timearr=$row['2'];
$datagg=date('Y-m-d',$timearr);
$stato=$row['3'];
$checkout=$row['4'];
$ggsett=date('N',$timearr);

/*
$IDsog='';
$qtap=0;

$query2="SELECT IDrestr,COUNT(*),GROUP_CONCAT(ID SEPARATOR ',') FROM richiestep WHERE IDreq='$IDrequest' GROUP BY IDrestr";
$result2=mysqli_query($link2,$query2);
if(mysqli_num_rows($result2)){
	while($row2=mysqli_fetch_row($result2)){
		$restr[$row2['0']]=$row2['1'];
		$qtap+=$row2['1'];
		$IDsog.=$row2['2'].',';
	}
}
*/

$testo='
<br>
	<p class="buttons-row" style="width:90%; margin:auto;">
	  <a href="#" onclick="tabservizi(0)" id="step10" class="button conto1 " >Il Conto</a>
	  <a href="#" class="button step1 active"  onclick="tabservizi(1)" id="conto2">Orari</a>
	</p>
	<br>
	

';


$txtpaccarr[0]='';
$txtpaccarr[1]='';



		$query="SELECT o.IDserv,GROUP_CONCAT(o2.IDsog SEPARATOR ','),o.time,COUNT(*),s.IDsottotip,s.servizio,s.IDtipo,SUM(o2.prezzo),o.ID,o2.pacchetto FROM oraripren as o,servizi as s,oraripren2 as o2 WHERE o.IDreq='$IDrequest' AND s.ID=o.IDserv AND o.tipolim IN (2) AND o.ID=o2.IDoraripren GROUP BY o.ID ORDER BY o.time";
		
		
		$result=mysqli_query($link2,$query);
		if(mysqli_num_rows($result)>0){
			while($row=mysqli_fetch_row($result)){
				$IDserv=$row['0'];
				$IDsog=$row['1'];
				$times=$row['2'];
				$qtap=$row['3'];
				$IDsottotip=$row['4'];
				$servizio=$row['5'];			
				$IDtipo=$row['6'];
				$IDoraripren=$row['8'];
				$pacc=$row['9'];
				
					/*if($row['7']==0){
						$prezzo='Inc.';
					}else{
						$prezzo=$row['7'].' &euro;';
					}*/
					$ora=date('H:i',$times);
						if($ora=='00:00'){
							$data='<u style="color:#e6692c;"><b>Imposta</b></u>';
						}else{
							$data=	'<b style="font-weight:600;font-size:13px;">'.$ora.'</b><br>'.dataita7($times);
						}
						
				
			
				
					$txtpaccarr[1].='
					<li >
					  <div class="item-content">
						<div class="item-inner">
							<div class="item-title">'.$servizio.'<br><span>Per n.'.$qtap.' '.txtpersone($qtap).'</span></div>
							<div class="item-after" style="font-size:13px;  line-height:13px; width:50px;"><div style="width:100%; text-align:center;">'.$data.'</div></div>
						
						</div>
					  </div></li>';	
				
				
					
								
					//$txt2.= '</tr>';	
				
			}
		}

	
		
		
		
	$arrnomore=array();
		
	/*$query="SELECT o.IDserv,o.pacc,o.time,s.IDsottotip,s.servizio,s.IDtipo,o.IDrestr,o.IDins,o.prezzo,t.tipolimite,o.durata,o.ID FROM oraripren as o,servizi as s,tiposervizio as t WHERE IDreq='$IDrequest' AND s.ID=o.IDserv AND t.ID=s.IDtipo  AND t.tipolimite NOT IN (2,4) AND s.IDtipo=t.ID ORDER BY s.IDtipo,o.IDrestr";
		*/
		

	$pacc=0;

	$query="SELECT o.IDserv,o.time,s.IDsottotip,s.servizio,s.IDtipo,o2.IDsog,o2.prezzo,o.durata,o.ID,o2.pacchetto,o.tipolim,SUM(o2.qta) FROM oraripren as o,servizi as s,oraripren2 as o2 WHERE o.IDreq='$IDrequest' AND s.ID=o.IDserv  AND o.tipolim NOT IN (2,4,5) AND o.ID=o2.IDoraripren GROUP BY o2.IDsog,o.IDserv ORDER BY s.IDtipo,s.ID,o2.IDsog";	
		
		
		
	$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
		
		/*$txt2.='
		</table><br>
	<div class="titleb" style="border-bottom:solid 1px #ccc; color:#2473e9;">Altri servizi prenotati</div>
<table class="tabmag3 tabtrhover td30" style="font-size:14px; margin:auto; margin-left:5px; width:100%;" >';*/
		
		
		while($row=mysqli_fetch_row($result)){
			$IDserv=$row['0'];
			$times=$row['1'];
			$IDsottotip=$row['2'];
			$servizio=$row['3'];
			$IDtipo=$row['4'];
			$IDsog=$row['5'];
			$prezzo=$row['6'];
			$durata=$row['7'];
			$IDoraripren=$row['8'];
			$pacchetto=$row['9'];
			$tipolim=$row['10'];
			$qta=$row['11'];
			
			
				switch($tipolim){
					case 6:
					case 9:
						
						
						
						$txtpaccarr[0].='
						<li>
						  <div class="item-content">
							<div class="item-inner">
								<div class="item-title">'.$servizio.'<br><span>n.'.$qtap.' </span></div>
								<div class="item-after" style="font-size:13px;  line-height:13px; width:50px;"></div>

							</div>
						  </div></li>';	
				
					break;
						
						
					default:
						$query2="SELECT restrizione,IDrestr FROM richiestep WHERE ID='$IDsog' LIMIT 1";
						$result2=mysqli_query($link2,$query2);
						$row2=mysqli_fetch_row($result2);
						$restr=$row2['0'];
						$IDrestr=$row2['1'].',';
						$oratxt="";
						
						
							$txtpaccarr[0].='
						<li>
						  <div class="item-content">
							<div class="item-inner">
								<div class="item-title">N.1 '.$servizio.'<br><span>'.$restr.'</span></div>
								<div class="item-after" style="font-size:13px;  line-height:13px; width:50px;"><div style="width:100%; text-align:center;"></div></div>

							</div>
						  </div></li>';	
				
						
						
					break;
				
				
				}
				
		
		}	
	}
	
	//$txt2.='</table>';
			









/*
$query="SELECT GROUP_CONCAT(ID SEPARATOR ',') FROM tiposervizio WHERE tipolimite='2' GROUP BY tipolimite";
	$result=mysqli_query($link2,$query);
	$row=mysqli_fetch_row($result);
	$gtipo=$row['0'];
	
	$arrplusq=array(" AND o.pacc!='0'"," AND o.pacc='0'");
	
	$gruppi=array(array());
	$gruppi[0][0]='';
	$gruppi[1][0]='';
	$ii=-1;
	foreach ($arrplusq as $qadd){
		$ii++;
		$query="SELECT o.IDserv,o.pacc,GROUP_CONCAT(o.IDrestr SEPARATOR ','),o.time,COUNT(*),s.IDsottotip,s.servizio,s.IDtipo,o.IDins,o.modi,SUM(o.prezzo) FROM oraripren as o,servizi as s WHERE o.IDreq='$IDrequest' AND s.ID=o.IDserv AND s.IDtipo IN ($gtipo) $qadd  GROUP BY o.IDins ORDER BY o.time";
		
			
		
		$result=mysqli_query($link2,$query);
		if(mysqli_num_rows($result)>0){
			while($row=mysqli_fetch_row($result)){
				$IDins=$row['8'];
				$pacc=$row['1'];
				$IDserv=$row['0'];
				if(!(in_array($IDserv,$gruppi[$ii]))){
					$gruppi2=0;
					
					$query3="SELECT o.ID FROM oraripren AS o WHERE o.IDserv='$IDserv' AND o.IDreq='$IDrequest' AND o.pacc!='0' $qadd GROUP BY o.IDins ";
					$result3=mysqli_query($link2,$query3);
					$num2=mysqli_num_rows($result3);
					if($num2>2){
						$gruppi2=1;
						array_push($gruppi[$ii],$IDserv);
					}
					$servizio=$row['6'];			
					$qtap=$row['4'];
					$times=$row['3'];
					$IDtipo=$row['7'];
					$IDsottotip=$row['5'];
					$IDorp='';
					$modi=$row['9'];
					
					if($pacc!='0'){
						$prezzo='<b>Incluso</b>';
					}else{
						$IDrestr=$row['2'];
						$prezzo=$row['10'].' &euro;';
					}	
					
					$dd='';
					if($gruppi2==0){
						$dd=date('d',$times);
						
						$ora=date('H:i',$times);
						if(($ora=='00:00')||($times==0)){
							$data='--:--';
							$dd='';
						}else{
							$data=$ora;
						}
						
					}else{
						$dd=''.$num2.' volte';
						$data=date('H:i',$times);
					}
					
					$pacc=1;
					if($row['1']==0){
						$pacc=0;
					}
					
					if($pacc!='0'){
						$prezzo='<b>Incluso</b>';
					}else{
						$prezzo=$row['10'];
						$prezzo.=' &euro;';
					}	
					
					/*$modb='
					<li><a href="#" class="list-button item-link" onclick="addservprev2('.$IDins.','.$IDserv.','.$times.',0)">Modifica</a></li>
					';
					
					
					if($pacc==0){
						$modb.='
						<li><a href="#" class="list-button item-link" id="deleteb" onclick="eliminaextraprev('.$IDins.')">Elimina</a></li>';
					}
					//aprimod('.$IDins.',this)
				
					$txtpaccarr[$pacc].='
					<li onclick="addservprev2('.$IDins.','.$IDserv.','.$times.',0)">
					  <div class="item-content">
						<div class="item-media"><b>'.$prezzo.'</b></div>
						<div class="item-inner">
							<div class="item-title">'.$servizio.'<br><span>Per n.'.$qtap.' '.txtpersone($qtap).'</span></div>
							<div class="item-after" style="font-size:13px;  line-height:13px; width:50px;"><div style="width:100%; text-align:center;"><b>'.$dd.'</b><br>'.$data.'</div></div>
						
						</div>
					  </div>
					  ';
					  					  //<div id="menu'.$IDins.'" style="display:none;" >'.base64_encode($modb).'</div>
					$txtpaccarr[$pacc].='</li>';	
						
				}
			}
		}
	}	
	
	
	$arrnomore=array();
		
		
	$query="SELECT o.IDserv,o.pacc,o.time,s.IDsottotip,s.servizio,s.IDtipo,o.IDrestr,o.IDins,o.prezzo FROM oraripren as o,servizi as s WHERE IDreq='$IDrequest' AND s.ID=o.IDserv AND s.IDtipo NOT IN ($gtipo,8) ORDER BY s.IDtipo";
	$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
		//$txtriep.='<tr><td colspan="6" style="text-align:center;padding:3px; background:#fcfcfc; font-weight:400; font-size:13px;">Servizi modificabili all&acute;arrivo</td></tr>';
		while($row=mysqli_fetch_row($result)){
			$IDserv=$row['0'];
			if(!in_array($IDserv,$arrnomore)){
				$pacc=$row['1'];
				$servizio=$row['4'];			
				$times=$row['2'];
				$IDtipo=$row['5'];
				$IDrestr=$row['6'];
				$IDins=$row['7'];
				if($IDtipo=='10'){
					array_push($arrnomore,$IDserv);
					$restr='n.1';
					$oratxt='';
				}else{
					$query2="SELECT restrizione,IDrestr FROM richiestep WHERE ID='$IDrestr' LIMIT 1";
					$result2=mysqli_query($link2,$query2);
					$rows=mysqli_fetch_row($result2);
					$restr=$rows['0'];
					$IDrestr='Per '.$rows['1'].'';
					$oratxt="";
				}
				if($pacc!='0'){
					$prezzo='<b>Incluso</b>';
				}else{
					$prezzo=$row['8'];
					$prezzo.=' &euro;';
				}	
				
				
				if($pacc!=0){
					$pacc=1;
				}
				//- <span style="font-size:10px; font-weight:100; color:#999;"><b>'.$restr.'</b><span>  (persona che riceve)
				
				/*$modb='';
				$func2='';
				if($pacc==0){
					$modb.='
					<li><a href="#" class="list-button item-link" id="deleteb" onclick="eliminaextraprev('.$IDins.')">Elimina</a></li>
					';
					$func2='onclick="aprimod('.$IDins.',this)"';
				}
				
				$func2='onclick="addservprev2('.$IDins.','.$IDserv.','.$times.',0)"';
				
				$txtpaccarr[$pacc].='
				<li '.$func2.'>
				  <div class="item-content">
					<div class="item-media"><b>'.$prezzo.'</b></div>
					<div class="item-inner">
						<div class="item-title">'.$servizio.'<br><span>'.$restr.'</span></div>
						<div class="item-after">--.--</div>
					
					</div>
				  </div>';
				
				 
				$txtpaccarr[$pacc].='</li>';	
			}
		}	
	}
	
	*/

/*
$queryp="SELECT SUM(prezzo) FROM oraripren2 WHERE IDreq='$IDrequest' ";
$resultp=mysqli_query($link2,$queryp);
$rowp=mysqli_fetch_row($resultp);
$totale=round($rowp['0'],2);
	*/

	
	
	
$testo.='<div class="list-block" >';
$adding=0;	
if(strlen($txtpaccarr[1])>0){
	$adding=1;
	$testo.='
	<div class="content-block-title titleb">Orari servizi prenotati</div>
	<div class="list-group item45"> <ul>
	'.$txtpaccarr[1].'</ul></div>';
}


if(strlen($txtpaccarr[0])>0){
	$adding=1;
	$testo.='
	<div class="content-block-title titleb">Altri servizi</div>
	<div class="list-group item45"> <ul>
	'.$txtpaccarr[0].'</ul></div>';
}


	
 if($gg>0){
	$queryp="SELECT SUM(prezzo) FROM prezzip WHERE IDreq='$IDrequest'";
	$resultp=mysqli_query($link2,$queryp);
	$rowp=mysqli_fetch_row($resultp);
	$prezzo=round($rowp['0'],2);
	
	$testo.='
	 <div class="content-block-title titleb">Pernottamento</div>
	<div class="list-group item45"> <ul>';
	
	
	if($prezzo==0){
		$testo.='
			<li class="item-content">
					<div class="item-inner">
						<div class="item-title">Alloggio:</div>
						<div class="item-after">Incluso</div>
					</div>
			</li>
		';
	}else{
		
		$query="SELECT ID FROM prezzip WHERE modi='1' AND  IDreq='$IDrequest' LIMIT 1";
		$result=mysqli_query($link2,$query);
		if(mysqli_num_rows($result)>0){
			$query="SELECT MIN(p.data),MAX(p.data),GROUP_CONCAT(DISTINCT(r.restrizione) SEPARATOR '<br>'),SUM(p.prezzo),GROUP_CONCAT(DISTINCT(r.ID) SEPARATOR ','),COUNT(DISTINCT(r.ID)) FROM prezzip as p,richiestep as r WHERE p.IDreq='$IDrequest' AND p.IDsog=r.ID AND p.modi>'0' GROUP BY p.modi ORDER BY r.IDrestr";
			$result=mysqli_query($link2,$query);
			if(mysqli_num_rows($result)>0){
				while($row=mysqli_fetch_row($result)){
					$mind=$row['0'];
					$maxd=$row['1'];
					$restr=$row['2'];
					$prezzo=round($row['3'],1);
					$nottix=(diff2date($maxd,$mind)+1);
					
					$after=convertidata3($mind,'SI');
					if($nottix>1){
						$after.='to '.convertidata3($maxd,'SI');
					}
					$testo.='
	
					<li class="item-content">
								<div class="item-media"><b>'.$prezzo.' €</b></div>
								<div class="item-inner">
									<div class="item-title">Alloggio per '.$nottix.' '.txtnotti($nottix).'
									<br><span>'.$after.'</span>
									</div>
								</div>
						</li>
					';
				}
			}
			
			
			
			$query="SELECT MIN(p.data),MAX(p.data),r.restrizione,SUM(p.prezzo),p.prezzo,p.IDsog,COUNT(*) FROM prezzip as p,richiestep as r WHERE p.IDreq='$IDrequest' AND p.IDsog=r.ID AND p.modi='0' GROUP BY p.IDsog";
			$result=mysqli_query($link2,$query);
			if(mysqli_num_rows($result)>0){
				while($row=mysqli_fetch_row($result)){
					$mind=$row['0'];
					$maxd=$row['1'];
					$restr=$row['2'];
					$prezzo=round($row['3'],1);
					
					
					$after=convertidata3($mind,'SI');
					if($row['6']>1){
						$after.=' to '.convertidata3($maxd,'SI');
					}
					$testo.='
					<li class="item-content">
					<div class="item-media"><b>'.round($prezzo,1).' €</b><br>
					<span>'.$restr.'</span>
					</div>
								<div class="item-inner">
									<div class="item-title">Alloggio per '.$row['6'].' '.txtnotti($row['6']).'
									<br><span>'.$after.'</span>
									</div>
								</div>
						</li>';
					
				}
			}
			
		}else{
			
			
			$query="SELECT MIN(p.data),MAX(p.data),r.restrizione,SUM(p.prezzo),p.prezzo,p.IDsog,COUNT(*) FROM prezzip as p,richiestep as r WHERE p.IDreq='$IDrequest' AND p.IDsog=r.ID AND p.modi='0' GROUP BY p.IDsog";
			$result=mysqli_query($link2,$query);
			if(mysqli_num_rows($result)>0){
				while($row=mysqli_fetch_row($result)){
					$mind=$row['0'];
					$maxd=$row['1'];
					$restr=$row['2'];
					$prezzo=round($row['3'],1);
					
					
					$testo.='
					<li class="item-content" >
					<div class="item-media">'.round($prezzo,1).' €
					</div>
								<div class="item-inner">
									<div class="item-title">Alloggio per '.$row['6'].' notte/i
									<br><span>'.convertidata3($mind,'SI').' to '.convertidata3($maxd,'SI').'</span>
									</div>
									<div class="item-after"><i style="font-size:10px;">'.$restr.'</i></div>
								</div>
						</li>
					';
				}
			}
		}
	
	}
	
	$testo.='</ul></div>';
	
	
}
 	$testo.='
	
	
	
	
	</div>';
 
 
 
echo $testo.'';
			 
?>			 
			 