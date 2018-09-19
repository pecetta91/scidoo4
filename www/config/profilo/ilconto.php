<?php 
if(!isset($inc)){
	include('../../../config/connecti.php');
	include('../../../config/funzioni.php');
	header('Access-Control-Allow-Origin: *');
	$testo='<br>';
}

$IDpren=$_SESSION['IDstrpren'];
$IDprenc=prenotcoll($IDpren);

$id=$IDpren;

$route=$_SESSION['route'];

$query="SELECT app,gg,MIN(time),tempg,tempn,MAX(checkout),IDstruttura,stato FROM prenotazioni WHERE IDv IN($IDprenc) LIMIT 1";

$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDapp=$row['0'];
$gg=$row['1'];
$time=$row['2'];
$tempg=round($row['3']);
$tempn=round($row['4']);
$checkout=$row['5'];
$IDstr=$row['6'];
$stato=$row['7'];

$nomepren=estrainome($IDpren);

$col=3;
$kk=0;
$IDsottotip=0;
$query="SELECT ID FROM tiporestr WHERE IDstr='$IDstr' AND personale='1'  AND personad='1' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$restr=$row['0'].',';

$sottot=array();



$testo.='


<div data-page="contoospite" class="page" > 

		 	<div class="navbar" id="navcal" >
				<div class="navbar-inner">
					<div class="left navbarleftsize170">
					
					  <a href="#" class="link icon-only back"   >
						<i class="material-icons">chevron_left</i>
							<strong class="stiletitolopagine">I tuoi servizi</strong>
					</a>
					
					</div>
					<div class="center titolonav"></div>
					<div class="right"></div>
				</div>
			</div>
		 <div class="page-content">
			<div class="content-block" id="contoospitediv">

';





	/*$arrideesi=array();
	$arrideeno=array();

	$query="SELECT ID FROM prenextra WHERE IDpren IN($IDprenc) AND IDstruttura='$IDstr' AND tipolim='7' AND modi>='0'";
	$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
		while($row=mysqli_fetch_row($result)){
			$ID=$row['0'];
			array_push($arrideesi,$ID);
		}
	}


	$query="SELECT ID FROM prenextra WHERE IDpren IN($IDprenc) AND IDstruttura='$IDstr' AND tipolim='8' AND modi>='0'";
	$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
		while($row=mysqli_fetch_row($result)){
			$ID=$row['0'];
			array_push($arrideesi,$ID);
		}
	}

	$qadd4='';
	if(!empty($arrideeno)){
		$txtin=implode(',',$arrideeno);
		$qadd4=" AND p.ID NOT IN($txtin)";
	}*/



		
	
	
	$totgen=0;
	
	
	if($stato<0)$dis2=' disabled="disabled" ';
	
	$arrfor=array(" AND p2.datacar='0'"," AND p2.datacar='1' AND p.tipolim!='6'","AND p2.datacar='1' AND p.tipolim='6' ");
	$arrfor2=array(""," AND p.modi='0'","","AND p.modi='1'");
	$ii=0;
	foreach($arrfor as $qadd2){
	$ii++;
	if($ii==1){
		$scont='cini';
		$scontf='3';
		/*
		$testo.=' <div id="tab4" class="page-content tab active" >
          				<div class="content-block" style="padding:0px;">';*/
		$testo.='
		<div class="content-block-title titleb" >Prenotazione Iniziale</div>
			<div id="infoprentab " class="p5" >';
			  
		
		
		$totale1=0;
		
	}
	if($ii==2){
		$scontf='2';
		$scont='cextra';
		/*
		$testo.='<div id="tab5" class="page-content tab ">
          				<div class="content-block" style="padding:0px;">';
		*/
			$testo.='<div class="content-block-title mt10 titleb"  >Servizi Extra</div>
			<div id="infoprentab  " class="p5" >';
	$totale1=0;
		
	}
	if($ii==3){
		$query="SELECT p.ID FROM prenextra as p,prenextra2 as p2 WHERE p.IDtipo='10' AND p.IDpren IN ($IDprenc)AND p.ID=p2.IDprenextra AND p2.datacar='1'";
		$result=mysqli_query($link2,$query);
		if(mysqli_num_rows($result)>0){
			$testo.='</div>';
			
			$testo.='			
			<div class="content-block-title mt10 titleb" >Prodotti Acquistati</div>
			<div id="infoprentab " class="p5" >';
		}
	}

	$query="SELECT p.ID,p.time,p2.datacar,COUNT(DISTINCT(p2.IDprenextra)),GROUP_CONCAT(DISTINCT(p.ID) SEPARATOR ','),p.tipolim,p.extra,p.modi,SUM(p2.prezzo),GROUP_CONCAT(DISTINCT(p2.IDinfop) SEPARATOR ','),SUM(p2.qta),p.sala,p.IDtipo,p.sottotip FROM prenextra as p,prenextra2 as p2 WHERE p2.pacchetto<='0' AND p2.IDpren IN ($IDprenc) AND p.ID=p2.IDprenextra AND p2.paga>'0' $qadd2 $qadd4 GROUP BY p.extra ORDER BY p.time";
	$result=mysqli_query($link2,$query);

		$i=0;
		while($row=mysqli_fetch_row($result)){
			
		
			$IDgroup=$row['4'];
			
			$tipolim=$row['5'];
			$query2="SELECT qta FROM prenextra2 WHERE IDprenextra IN ($IDgroup) AND pacchetto<='0' AND paga='1' GROUP BY IDprenextra";
			$result2=mysqli_query($link2,$query2);
			if((mysqli_num_rows($result2)>0)||($tipolim=='4')){
				$modifica="";	
				$ID=$row['0'];
				$time=$row['1'];
				$datacar=$row['2'];
				$num2=$row['3'];
				$num=$num2;
				$prezzo=$row['8'];			
				$extra=$row['6'];
				$qta=$row['10'];
				$IDtipo=$row['12'];

				$servizio=getnomeserv($extra,$tipolim,$ID);
				
				if($tipolim=='6'){
					$qtabutt=$qta;
				}else{
					$qtabutt=round($qta/$num);
				}
				
				if($tipolim=='6'){
					$qtabutt='N.'.$qta.' oggetti';
				}else{
					$nn=round($qta/$num);
					$persontxt='persone';
					if($nn==1){$persontxt='persona';}
					$qtabutt='N.'.$nn.' '.$persontxt;
					
				}
				
				$gruppo=$row['4'];
				$txtsend='0';
				$pagato=0;
				$numnon=0;
				
													
				$num2='';
				$qta2='';
				
				if(($tipolim=='5')||($tipolim=='7')||($tipolim=='8')){
					$groupinfoID=$row['9'];
					
					
					$qta2='<span class="fs12">per '.round($qta/$num).' persone</span>';

						$arrg=explode(',',$IDgroup);
						//$prezzo=0;
						foreach ($arrg as $dato){
							$pacchetto=$extra.'/'.$dato;
							$query2="SELECT SUM(prezzo) FROM prenextra2 WHERE pacchetto ='$pacchetto' AND IDpren IN ($IDprenc) AND IDinfop IN ($groupinfoID) AND IDinfop!='0' AND paga='1'";
							$result2=mysqli_query($link2,$query2);
							if(mysqli_num_rows($result2)>0){
								$row2=mysqli_fetch_row($result2);
								$prezzo+=$row2['0'];
							}
							$query2="SELECT SUM(prezzo) FROM prenextra2 WHERE pacchetto ='$pacchetto' AND IDpren IN ($IDprenc) AND IDinfop ='0' AND paga='1'";
							$result2=mysqli_query($link2,$query2);
							if(mysqli_num_rows($result2)>0){
								$row2=mysqli_fetch_row($result2);
								$prezzo+=$row2['0'];
							}
						}
					
					if(($tipolim==7)||($tipolim==8)){
						if($prezzo>=0){
							$prezzo=0;
						}
					}
				}
				$prezzotxt="";							
				$datatxt=date('d/m/Y',$time);
				$prezzotxt=round($prezzo,2);
				
				if($tipolim=='6'){
					$qta2=' <span class="fs12">N.'.$qta.'</span>';
				}else{
					if(($num==1)&&(($tipolim=='2')||($tipolim=='1')||($tipolim=='4'))){
						//$num2=datafunc($time,$row['7'],$tipolim,'openmorph(2,'.$ID.','.$id.')',$ID);
						
						if(($row['12']==2)||($row['12']==1)){
							$sottot=$row['12'];
						}else{
							$sottot=$row['13'];
						}
						
						//$num2=datafunc2($time,$row['7'],$tipolim,'',$ID);
						//$num2='prova';
					}
					
					
				}
				$numtxt=$num.'+';

				$butt1='';//prezzo
				$butt3='';//sposta
				$butt4=''; //elimina
				$butt5='';//orario
				
				$vis=''; //visualizza
				
				$sost=0;
				
				if($num>1){
					//visualizza onclick="vis2(-'.$ID.$IDplus.',1,'.$num.',1);
					$vis=$num.'+';
					
					
					//prezzo
					
					
					if(($tipolim!='8')&&($tipolim!='7')){
						$butt1=$prezzotxt.'€';
						//$butt1='<input type="text" value="'.$prezzotxt.'" '.$dis2.' class="bnone pricetab ptb" onchange="modprenextra(-'.$ID.$IDplus.','."'-".$ID.$IDplus."'".',18,11,18)" id="-'.$ID.$IDplus.'">';
					}else{
						$butt1=$prezzotxt.'€';
						//$butt1='<input type="text" value="'.$prezzotxt.'" disabled="disabled" class="bnone pricetab ptb">';
					}
					
				}else{
					if(($tipolim=='5')||($tipolim=='7')||($tipolim=='8')){
						$vis=$num.'+';
						
						//$vis='<button class="shortcut mini10 info popover" onclick="vis2(-'.$ID.$IDplus.',1,'.$num.',1);" '.$dis2.'><span>Visualizza contenuto</span>'.$num.'+</button> ';
					}else{
						$vis=$num;
						//$vis='<button class="shortcut mini10 popover" '.$dis2.'>'.$num.'<span>Servizio Singolo</span></button> ';
					}
					//prezzo
					if(($tipolim!='8')&&($tipolim!='7')){
						
						if(($tipolim=='6')||($tipolim=='1')){
							$butt1=$prezzotxt.'€';
							//$butt1='<input type="text" value="'.$prezzotxt.'" '.$dis2.' class="bnone pricetab ptb" onchange="modprenot('.$ID.','."'-".$ID.$IDplus."'".',98,1,38)" id="-'.$ID.$IDplus.'">';
						}else{
							$butt1=$prezzotxt.'€';
							//$butt1='<input type="text" value="'.$prezzotxt.'" '.$dis2.' class="bnone pricetab ptb" onchange="modprenextra(-'.$ID.$IDplus.','."'-".$ID.$IDplus."'".',18,11,18)" id="-'.$ID.$IDplus.'">';
						}
					}else{
						$butt1=$prezzotxt.'€';
						//$butt1='<input type="text" value="'.$prezzotxt.'" disabled="disabled" class="bnone pricetab ptb">';
					}
				}
				//$butt2='<button class="shortcut mini10 popover  settingmod4"  '.$dis2.' onclick="modifIDp(-'.$ID.$IDplus.',this,'.$tipolim.','.$datacar.',1,'.$num.')"><span>Altre Funzioni</span></button>';

				$sala='';

				if(($tipolim=='2')||($tipolim=='1')){
					$query3="SELECT nome FROM sale WHERE ID='".$row['11']."' LIMIT 1";
					$result3=mysqli_query($link2,$query3);
					if(mysqli_num_rows($result3)>0){
						$row3=mysqli_fetch_row($result3);
						$sala=$row3['0'];
					}
				}
				
				/*if($tipolim!='4'){
					$butt4='<button class="shortcut mini10 danger del3icon popover"  '.$dis2.' onclick="msgboxelimina(-'.$ID.$IDplus.',3,'.$num.',11,0);prenfunc=0;"><span>Elimina</span></button>';
				}*/
				
				//sposta
				$butt3='';
			
				

				 
					 
				$testo.='<div class="row rowlist no-gutter">
							<div class="col-10">'.$vis.'</div>
							<div class="col-70">'.wordwrap($servizio,25,'<br>').'<br><span class="conto1">'.$qtabutt.' | '.dataita($time).'</span></div>
							<div class="col-20" >'.$butt1.'</div>
						</div>';
			
				
				$totale1+=$prezzo;
			}
		
		}
		
		if(($ii==1)||($ii==3)){
			
			
			//sconti modi = vecchio datacar
			
			$qadd3=$arrfor2[$ii];
			$querysc="SELECT p.ID,SUM(p.durata),s.servizio,p.modi,p.IDpren FROM prenextra as p,servizi as s WHERE p.IDpren IN($IDprenc) AND p.IDtipo='0' AND p.extra=s.ID $qadd3  GROUP BY p.IDpren,p.extra";
			$resultsc=mysqli_query($link2,$querysc);
			if(mysqli_num_rows($resultsc)>0){
				
				//$testo.='<tr ><td colspan="9"><div class="etich2" style=" color:#bc4504;">Abbuoni</div></td></tr>';
				while($rowsc=mysqli_fetch_row($resultsc)){
					

					
					$testo.='<div class="row rowlist no-gutter">
							<div class="col-50"><span class="conto2">'.$rowsc['2'].'</span><br><span class="fs11">(Pren: '.estrainomeapp($rowsc['4']).')</span></div>
							<div class="col-40"><b>'.round($rowsc['1'],2).' €</b></div>
						</div>';
					
					$totale1+=round($rowsc['1'],2);
				}
			}
			
			
			$totgen+=$totale1;
			$testo.='</div>';

		}
	}
	
	
	

$testo.='</div>';




$testo.='
<hr><br/>

<div class="content-block-title mt10 titleb" style="color:#15a143;">Pagamenti</div>
			<div id="infoprentab  " class="p5" >';


	$acconto=0;
	$emettifattura=0;	
	$queryacc="SELECT IDscontr,SUM(valore),tipoobj,IDobj FROM scontriniobj WHERE IDobj IN($IDprenc) AND tipoobj IN(1,2,0,14) GROUP BY IDscontr";
	$resultacc=mysqli_query($link2,$queryacc);
	if(mysqli_num_rows($resultacc)>0){		
		while($rowacc=mysqli_fetch_row($resultacc)){
			
			$tipopag='';
			$colorpag='';
			$buttadd='';
			$pagatok=0;
			
			$effettuato=0;
			$sospeso=0;
			
			/*$txtstamp=getstampfattura($rowacc['0']);
			if($txtstamp){
				$pagatok=1;
				$row=mysqli_fetch_row($result);
				$buttadd.='<strong style="text-transform:uppercase; font-size:12px;">'.$txtstamp.'</strong>';
			}else{
				$emettifattura++;
				$buttadd.='<button class="shortcut recta16 bnone scontrin selectno" onclick="selezionascontr('.$rowacc['0'].','.$rowacc['2'].',this,'.$rowacc['3'].',8)" id="'.$rowacc['0'].'" alt="'.$rowacc['2'].'">Seleziona</button>';
			}*/
			
			
			switch($rowacc['2']){
				case 0:
					$tipopag='Pagamento Singoli Servizi';$colorpag='info';
					
				break;
				case 1:
					$tipopag='Saldo Finale';$colorpag='success';
					
				break;
				case 2:
					$tipopag='Acconto';$colorpag='warning';
					
				break;
				case 14:
					$tipopag='Caparra';$colorpag='warning';
					
				break;
			}

			$metodopag=0;
			$query3="SELECT timepag,IDcliente,metodopag FROM scontrini WHERE ID='".$rowacc['0']."' LIMIT 1";
			//echo $query3;
			$result3=mysqli_query($link2,$query3);
			if(mysqli_num_rows($result3)>0){
				$row3=mysqli_fetch_row($result3);
				$timepag=$row3['0'];
				$IDcli=$row3['1'];
				$metodopag=$row3['2'];
				if($IDcli!='0'){
					$tipopag.='<br><span style="font-size:10px;color:#999;">'.estrainomecli($IDcli).'</span>';
				}
			}

			$arroggetti[$codobj][6]=0;
			$txtoggetto='';
			$pulsanti='';
			
			if($metodopag==0){
				
				$effettuato=round($rowacc['1'],2);

				$pulsanti='<button class="shortcut recta200 info popover" onclick="modifichep('.$rowacc['0'].',this,2,17,0,0)" >Segnala pagamento</button>';
				$txtoggetto=$tipopag.'<br><span class="conto2">Pagamento da Effettuare</span>';;
				
			}else{
				$effettuato=round($rowacc['1'],2);
				$txtoggetto=$tipopag.'<br><span class="conto1">'.metodopag($row3['2']).'</span>';
			}
			
			
		
			
			
				$testo.='<div class="row rowlist no-gutter">
							<div class="col-10"></div>
							<div class="col-70">'.$txtoggetto.'</div>
							<div class="col-20"><b>'.$effettuato.' €</b></div>
						</div>';
			
			
			$acconto+=$rowacc['1'];
		}		
	}
	




$queryacc="SELECT totale,data,IDagenzia,corrispettivo,contratto,ID FROM agenziepren WHERE IDobj IN($IDprenc) AND tipoobj='0'";
	$resultacc=mysqli_query($link2,$queryacc);
	if(mysqli_num_rows($resultacc)>0){
		$rowag=mysqli_fetch_row($resultacc);
		$agetot=$rowag['0'];
		$IDagenziapren=$rowag['5'];
		
		
		$query6="SELECT nome FROM agenzie WHERE ID='".$rowag['2']."' LIMIT 1";
		$result6=mysqli_query($link2,$query6);
		$row6=mysqli_fetch_row($result6);
		$nomeag=$row6['0'];
		
		switch($rowag['4']){
			case 0: //paga all'agenzia

				//$quotaagenzia+=$agetot;
				
				
				$testo.='<div class="row rowlist no-gutter">
							<div class="col-10"></div>
							<div class="col-70">Pagamento presso '.$nomeag.'</div>
							<div class="col-20"><b>'.$agetot.' €</b></div>
						</div>';
				
				
			break;
			
		}
	}






$testo.='</div></div>';

if(!isset($inc)){
echo $testo;
}




?>