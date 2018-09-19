<?php

if(!isset($inc)){
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


	$tipoconto=$_GET['tipo'];

}




$testo='';

$_SESSION['tabconto']=$tipoconto;

switch($tipoconto){
	case 0:
		
	$paccaltro=array();

	$IDrestrdef=getrestrmain($IDstruttura);
	
	
	$tipostr=0;
		
		
		$serviziarr=array();
		$servizitxt=array();
	
		$query2="SELECT r.IDrestr,r.ID,t.restrizione,r.paccnotti FROM richiestep as r,tiporestr as t WHERE r.IDreq='$IDrequest' AND r.IDrestr=t.ID ";
		$result2=mysqli_query($link2,$query2);
		if(mysqli_num_rows($result2)){
			while($row2=mysqli_fetch_row($result2)){
				
				$IDpersona=$row2['1'];
				$pacc=0;
				$tipopacc=0;
				$IDoraripren=0;

				$query3="SELECT o.IDserv,o.tipolim,o.ID FROM oraripren as o,oraripren2 as o2 WHERE o.IDreq='$IDrequest' AND o.tipolim IN(5,7,8) AND o.ID=o2.IDoraripren AND o2.IDsog='$IDpersona'   LIMIT 1";
				
				
				$result3=mysqli_query($link2,$query3);
				if(mysqli_num_rows($result3)>0){
					$row3=mysqli_fetch_row($result3);
					$pacc=$row3['0'];
					$tipolimpacc=$row3['1'];
					$IDoraripren=$row3['2'];
				}
				
				$rest=$row2['0'];
				$paccnotti=$row2['3'];
				
				
				
				if($paccnotti=='1'){
					$calc=1;
				}	
				if(isset($arrnum[$row2['0']])){
					$arrnum[$row2['0']]++;
				}else{
					$arrnum[$row2['0']]=1;
				}	
				
				if($IDoraripren!=0){
					
					if($tipolimpacc==5){
						
							$query3="SELECT sottotip,servizio FROM servizi WHERE ID='$pacc' LIMIT 1";
							$result3=mysqli_query($link2,$query3);
							$row3=mysqli_fetch_row($result3);
							$sottotip=$row3['0'];
							$servizio=$row3['1'];	
							if($sottotip=='1'){ //trattamento
								//calcolo prezzo
								$prezzotot=0;
								



								
								$query3="SELECT SUM(o2.prezzo) FROM oraripren as o,oraripren2 as o2 WHERE o.IDreq='$IDrequest' AND o.tipolim='5' AND o.ID=o2.IDoraripren AND o2.IDsog='$IDpersona'";
								$result3=mysqli_query($link2,$query3);
								
								$row3=mysqli_fetch_row($result3);
								$prezzotot=round($row3['0'],1);
								$prezzop=round(($prezzotot/$gg),1);
								
								
								
								if(!isset($serviziarr[$pacc])){
									$serviziarr[$pacc]='';
									$servizitxt[$pacc]=$servizio;

								}	


							
								
								
								$serviziarr[$pacc].='
								
								
								<div class="row no-gutter rowlist">
									<div class="col-30" >'.$arrnum[$row2['0']].'&deg; '.$row2['2'].'</div>
									<div class="col-5" >'.$gg.'</div>
									<div class="col-5 centercol">x</div>
									<div class="col-20 inputd" onclick="modprezzoprev('."'".$pacc.'///'.$IDpersona."///0'".',2)">'.$prezzop.' €</div>
									<div class="col-5">=</div>
									<div class="col-20 inputd" onclick="modprezzoprev('."'".$pacc.'///'.$IDpersona."///1'".',3)">'.$prezzotot.' €</div>
								
								
								';	
								
								/*<tr><td>'.$arrnum[$row2['0']].'&deg; '.$row2['2'].'</td><td  class="tdprez">

								 <input type="text" class="inpprev" onchange="modprenot('."'".$pacc.'///'.$IDpersona."///0'".',this.value,92,10,27)" value="'.$prezzop.'"> € = <input type="text" class="inpprev" onchange="modprenot('."'".$pacc.'///'.$IDpersona."///1'".',this.value,92,10,27)" value=""> €

								</td>*/

							}else{					
								//calcolo prezzo singolo


								$query3="SELECT prezzo FROM oraripren2 WHERE IDreq='$IDrequest' AND IDoraripren='$IDoraripren' AND IDsog='$IDpersona' LIMIT 1";
								$result3=mysqli_query($link2,$query3);
								$row3=mysqli_fetch_row($result3);
								$prezzotot=round($row3['0'],2);	


								if(!isset($serviziarr[$pacc])){
									$serviziarr[$pacc]='';
									$servizitxt[$pacc]=$servizio;
								}


								$serviziarr[$pacc].='
								
								
								<div class="row rowlist no-gutter">
									<div class="col-35">'.$arrnum[$row2['0']].'&deg; '.$row2['2'].'</div>
									<div class="col-20"></div>
									<div class="col-30 inputd" onclick="modprezzoprev('."'".$IDoraripren.'///'.$IDpersona."///2'".',1)" >'.$prezzotot.' €</div>
								
								';
								
								/*<tr><td>'.$arrnum[$row2['0']].'&deg; '.$row2['2'].'</td><td  class="tdprez"><input type="text" onchange="modprenot('."'".$IDoraripren.'///'.$IDpersona."///2'".',this.value,92,10,27)" value="'.$prezzotot.'"> &euro;</td>*/
								
							}
							$serviziarr[$pacc].='
							
							
							<div class="col-10">
							
							<i class="f7-icons fs20" style="color:#b42125;" onclick="eliminapaccprev('.$IDpersona.',0)">trash</i>
							</div>
								</div>
							
							';
						/*<td class="td2"><button class="shortcut danger recta13" onclick="eliminapaccprev('.$IDpersona.',0)">Elimina</button></td></tr>*/

						
					}
					if($tipolimpacc==7){

						if(!isset($serviziarr[$tipolimpacc.$pacc])){
							$query3="SELECT codiceidea FROM ideeregalosold WHERE ID='$pacc' LIMIT 1";
							$result3=mysqli_query($link2,$query3);
							$row3=mysqli_fetch_row($result3);
							$idea=$row3['0'];
							$serviziarr[$tipolimpacc.$pacc]='';
							$servizitxt[$tipolimpacc.$pacc]=$idea;
							array_push($paccaltro,$tipolimpacc.$pacc);
						}

						$serviziarr[$tipolimpacc.$pacc].='
						
						<div class="row rowlist no-gutter">
									<div class="col-50">'.$arrnum[$row2['0']].'&deg; '.$row2['2'].'</div>
									<div class="col-50"></div>
								</div>
						';
						
						
						/*<tr><td><b>'.$arrnum[$row2['0']].'&deg; '.$row2['2'].'</b></td><td><td></td></tr>*/
						
					}
					if($tipolimpacc==8){

						if(!isset($serviziarr[$tipolimpacc.$pacc])){
							$query3="SELECT cofanetto FROM cofanetti WHERE ID='$pacc' LIMIT 1";
							$result3=mysqli_query($link2,$query3);
							$row3=mysqli_fetch_row($result3);
							$idea=$row3['0'];
							$serviziarr[$tipolimpacc.$pacc]='';
							$servizitxt[$tipolimpacc.$pacc]=$idea;
							array_push($paccaltro,$tipolimpacc.$pacc);
						}


						$serviziarr[$tipolimpacc.$pacc].='
							
						<div class="row rowlist no-gutter">
									<div class="col-50">'.$arrnum[$row2['0']].'&deg; '.$row2['2'].'</div>
									<div class="col-50"></div>
								</div>
						
						';
						/*<tr><td>'.$arrnum[$row2['0']].'&deg; '.$row2['2'].'</td><td></td><td></td></tr>*/

					}

					
				}else{
					/*if(!isset($serviziarr[$pacc])){
						$serviziarr[$pacc]='';
						$servizitxt[$pacc]='Nessun Trattamento';
					}
					$serviziarr[$pacc].='
					
					
					<div class="row rowlist no-gutter">
									<div class="col-50">'.$arrnum[$row2['0']].'&deg; '.$row2['2'].'</div>
									<div class="col-50"></div>
								</div>
					
					';*/
					//<tr><td colspan="2">'.$arrnum[$row2['0']].'&deg; '.$row2['2'].'</td><td></td></tr>
					
					
				}
				
				
				
			}
		}
		
		
		
		
		$i=0;
		foreach($serviziarr as $pacc =>$dato){
			$style='';
			if($i>0){$style='margin-top:0px;';}
			
			if(in_array($pacc,$paccaltro)){
				$tip = substr($pacc, 0, 1);
				$pacc2=substr( $pacc, 1 );
				
				$tit='';
				switch($tip){
					case 1:
						$tit='<button class="shortcut mini13 infoimg popover" style="margin:3px;margin-top:0px; margin-right:10px;"><span>'.contregalo($pacc2,1,$IDstruttura).'</span></button><span style="color:#ba123e;">Voucher: </span>';
					break;
					case 2:
						$tit='<button class="shortcut mini13 infoimg popover" style="margin:3px;margin-top:0px; margin-right:10px;"><span>'.contregalo($pacc2,2,$IDstruttura).'</span></button><span style="color:#ba123e;">Cofanetto: </span>';
					break;
				}
				
				$testo.='
				<div class="row"><div class="col-70"><div class="titleb">'.$tit.' '.$servizitxt[$pacc].'</div>
				
				</div><div class="col-30"  onclick="eliminapaccprev('.$pacc2.','.$tip.')" style="font-size:11px; color:#b42125;">Elimina</div></div>
				'.$dato.'
				
				';
			}else{
				$txttit='';
				if($pacc==0){
					$txttit=$servizitxt[$pacc];
				}else{
					$txttit='<button class="shortcut mini13 infoimg popover" style="margin:3px;margin-top:0px; margin-right:10px;"><span>'.contpacchetto($pacc,0,-1,0,$IDrestrdef,$IDstruttura).'</span></button>'.$servizitxt[$pacc];
				}
				
				
				
				$testo.='
				<div class="titleb">'.$txttit.'</div>
				'.$dato.'
				';
			}
			
			$i++;
			
		}
		
		
	
	$arrplusq=array(" AND o.pacc='0'");
	
	$gruppi=array(array());
	$gruppi[0][0]='';
	$gruppi[1][0]='';
	$ii=-1;
		
		$ii++;
		
		$query="SELECT o.IDserv,1,GROUP_CONCAT(o2.IDsog SEPARATOR ','),o.time,COUNT(*),s.IDsottotip,s.servizio,s.IDtipo,o.ID,o.modi,SUM(o2.prezzo) FROM oraripren as o,servizi as s,tiposervizio as t,oraripren2 as o2 WHERE o.IDreq='$IDrequest' AND s.ID=o.IDserv AND t.ID=s.IDtipo AND o.tipolim NOT IN (5,8,7) AND o.ID=o2.IDoraripren AND o2.pacchetto='0'  GROUP BY o.ID ORDER BY s.IDtipo";
		
		//echo $query;
		
		

		$result=mysqli_query($link2,$query);
		if(mysqli_num_rows($result)>0){
			$testo.='<br/><div class="titleb">Servizi Extra</div>';
			
			
			while($row=mysqli_fetch_row($result)){
				$IDoraripren=$row['8'];
				$pacc=$row['1'];
				$IDserv=$row['0'];
				
					$servizio=$row['6'];			
					$qtap=$row['4'];
					$times=$row['3'];
					$IDtipo=$row['7'];
					$IDsottotip=$row['5'];
					$IDorp='';
					$modi=$row['9'];
					
					
					$IDrestr=$row['2'];
					
					if($row['10']==0){
						$prezzo='Inc.';
					}else{
						$prezzo=$row['10'].' &euro;';
					}
					
				
					$testo.='
					<div class="row rowlist no-gutter">
						<div class="col-55">'.$servizio.'</div>
						<div class="col-35 inputd">'.$prezzo.'</div>
						<div class="col-10" >
							<i class="f7-icons fs20" onclick="eliminaextraprev('.$IDoraripren.','."'".$IDrestr."'".')" style="color:#b42125;">delete_round_fill</i>
						</div>
					</div>
					';

				/*
					$txt2.= '<tr><td style="padding:1px;">'.$servizio.'</td><td style="width:60px;">'.$prezzo.'</td>
					<td class="td2"><button class="shortcut danger recta13" onclick="eliminaextraprev('.$IDoraripren.','."'".$IDrestr."'".')">Elimina</button></td>
					</tr>';	*/
				//}
			}
		}
	//}	
	
		
		
		
		
		if($gg>0){
			
			
			//$txt2.='<br><div class="titleb" style="border-bottom:solid 1px #ccc; color:#2473e9; ">Conto Pernottamento</div>';
		
		
			
			
			$testo.='<br><div class="titleb" >Conto Pernottamento</div>';
			
			$prezzo=0;
			$query="SELECT SUM(prezzo) FROM prezzip WHERE IDreq='$IDrequest' ";
			$result=mysqli_query($link2,$query);
			if(mysqli_num_rows($result)>0){
				$row=mysqli_fetch_row($result);
				$prezzo=$row['0'];
			}
			if($prezzo==0){
				$testo.='
				<div class="row rowlist no-gutter">
					<div class="col-70">Pernottamento</div>
					<div class="col-30">Incluso</div>
				</div>';
			}else{
				$testo.=testopern2($IDrequest,25,$tipostr);
				
			}
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	break;
	case 1:
		
		

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
					<li onclick="addservprev2('.$IDoraripren.','.$IDserv.','.$times.',0)">
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
						<li onclick="addservprev2('.$IDoraripren.','.$IDserv.','.$times.',0)">
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
						<li onclick="addservprev2('.$IDoraripren.','.$IDserv.','.$times.',0)">
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
	
	$testo.='<div class="list-block" style="margin-top:-20px;">';
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

		break;
		
		
}


 
 
echo $testo;
			 
?>			 
			 