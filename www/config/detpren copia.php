<?php
header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');
include('../../config/funzionilingua.php');

$IDutente=$_SESSION['ID'];
$IDstruttura=$_SESSION['IDstruttura'];
$_SESSION['contratto']=1;


$id=$_POST['dato0'];


$query="SELECT ID,datapren,time,gg,checkout,app,stato,lang,acconto,tempg,tempn,note FROM prenotazioni WHERE IDv='$id' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDpren=$row['0'];
$datapren=$row['1'];
$time=$row['2'];
$notti=$row['3'];
$checkout=$row['4'];
$IDapp=$row['5'];
$stato=$row['6'];
$lang=$row['7'];
$acconto=$row['8'];
$tempg=$row['9'];
$tempn=$row['10'];
$note=stripslashes($row['11']);


$nome=estrainome($id);

if($IDapp!=0){
	$query="SELECT attivo FROM appartamenti WHERE ID='$IDapp' LIMIT 1";
	$result=mysqli_query($link2,$query);
	$row=mysqli_fetch_row($result);
	$attivo=$row['0'];
}else{
	$attivo=0;
}

if($stato=='-1'){
		$alloggio='Senza Alloggio';
	}else{
	$q8="SELECT nome FROM appartamenti WHERE ID='$IDapp' LIMIT 1";
	$r8=mysqli_query($link2,$q8);
	$row8=mysqli_fetch_row($r8);
	$alloggio=$row8['0'];
	}



$arrstato=array('Da Confermare','Confermata Senza Acconto','Confemata Con Acconto','Arrivata nella Struttura','Saldata Completamente');
		$statimin=array('DC','CSA','CCA','AS','SC');
$colorstato=array('d43650','3688d4','d4b836','d436cb','27be59');

$testo=  '




<div data-page="calendario" class="page" > 
           
		   
		   
            <!-- Scrollable page content--> 
            <div class="page-content"> 
			
			
				 <div class="content-block-title" align="center" style="background:url(pattern.jpg) no-repeat center center ; background-size:cover; margin:0px; height:150px;   vertical-align:middle; 
				 padding-top:70px;padding-bottom:50px; position:relative; margin-top:-100px; " >
           <br>
		   
           <div style="width:150px; line-height:17px; font-size:17px; -webkit-box-shadow: 0px 1px 5px 0px rgba(0,0,0,0.75);
-moz-box-shadow: 0px 1px 5px 0px rgba(0,0,0,0.75); position:relative;
box-shadow: 0px 1px 5px 0px rgba(0,0,0,0.75); height:150px; border-radius:50%; background:#'.$colorstato[$stato].'; color:#fff; vertical-align:middle; display:table-cell">'.wordwrap($nome,10,'<br>').'<br><u style="font-size:11px;">'.$notti.' NOTTE/I</u><br><div style="font-size:10px;line-height:10px; margin-top:5px; color:#ccc; ">'.wordwrap($arrstato[$stato],13,'<br>').'</div></div>
		   
		   
		   <div style="position:absolute; right:5px; bottom:5px; color:#fff; font-size:10px;">Inserita: '.dataita($datapren).'</div>
		   
	              </div>
			 
		 
			 <div style="margin:auto; width:100%;  padding:0px; 
			 
			 -webkit-box-shadow: 0px 1px 5px 0px rgba(171,171,171,1);
-moz-box-shadow: 0px 1px 5px 0px rgba(171,171,171,1);
box-shadow: 0px 1px 5px 0px rgba(171,171,171,1)
			 
			 ">
			 <table width="100%;"><tr>
			 <td width="33%" class="tabmainmenu tilehome selectedsx" valign="bottom">Prenotazione</td>
			 <td width="33%" class=" tabmainmenu tilepren " valign="bottom">Servizi</td>
			 <td width="33%" class="tabmainmenu tileuser " valign="bottom">Clienti</td>
			 
			 </tr></table>
			
			
</div>

			

			
              <div class="content-block"> 
			  
			  ';
			  $tipo=0;
			  
			  include('detpren.php');
			  
			  
			  
			  <style>
			 

			 .tabmainmenu {font-weight:100;font-size:13px;text-align:center; height:50px;}
			 
			 
			 .tilepren{border-left: solid 4px #00aff0;background: url(img/cartb.svg) no-repeat center 5px;  background-size:25px 25px; background-color:#fff;}
			
			.tileuser{border-left: solid 4px #FFB413; background: url(img/users.svg) no-repeat center 5px; background-size:25px 25px;  background-color:#fff;}
			
			.tilehome{border-left: solid 4px #2B78C4; background: url(img/home2.svg) no-repeat center 6px;background-size:24px 24px; background-color:#fff; }
			 
			 
			 .tileuser.selectedsx{background: url(img/usersw.svg) no-repeat center 5px; background-size:25px 25px; background-color: #FFB413; color:#fff;}
.tilepren.selectedsx{background: url(img/cartw.svg) no-repeat center 5px; background-size:25px 25px;background-color: #00aff0; color:#fff;}
.tilehome.selectedsx{background: url(img/homew.svg) no-repeat center 6px; background-size:24px 24px;background-color: #2B78C4; color:#fff;}
			 
			 .head{font-size:13px; font-weight:bold; color:#888;}
			 
			 
			 .clock{background: url(img/clock.svg) no-repeat center center; height:40px; width:35px; background-size:35px 35px;}
			 .users{background: url(img/users2.svg) no-repeat center center; height:40px; width:35px; background-size:35px 35px;}
			 
			 
			 .alloggioicon{background: url(img/home3.svg) no-repeat center center; height:40px; width:35px; background-size:35px 35px;}
			 .cleanicon{background: url(img/clean.svg) no-repeat center center; height:40px; width:35px; background-size:35px 35px;}
			 .bedicon{background: url(img/bed.svg) no-repeat center center; height:40px; width:35px; background-size:35px 35px;}
			 
			 
			 .agenziaicon{background: url(img/travel.svg) no-repeat center center; height:40px; width:35px; background-size:35px 35px;}
			 .payicon{background: url(img/coin.svg) no-repeat center center; height:40px; width:35px; background-size:35px 35px;}
			 



			 
			 
			 .item-media{width:40px;padding:0px;}
			 .item-title{font-size:14px;}
			 </style>
			 
			  <a href="#" class="back" style="position:absolute; top:10px; left:10px; width:30px; height:30px; background:url(img/back2.svg) no-repeat center center; background-size:30px 30px;"></a>
			 
			 
			 
				<div class="list-block tablet-inset" style="width:110%; margin-left:-14px;">
					<ul>
					    <li class="item-divider">Dati prenotazione</li>
					  <li class="item-content">
						<div class="item-media clock"></div>
						<div class="item-inner">
						  <div class="item-title">Arrivo</div>
						  <div class="item-after">'.dataita($time).'</div>
						</div>
					  </li>
					  
					  <li class="item-content">
						<div class="item-media clock"></div>
						<div class="item-inner">
						  <div class="item-title">Partenza</div>
						  <div class="item-after">'.dataita($checkout).'</div>
						</div>
					  </li>
					  
					  <li class="item-content">
						<div class="item-media users"></div>
						<div class="item-inner">
						  <div class="item-title">Persone</div>
						  <div class="item-after">';
						  
						  
						  
							$query="SELECT GROUP_CONCAT(IDrest SEPARATOR ',') FROM infopren WHERE IDpren='$id' AND pers='1' GROUP BY IDstr";
							$result=mysqli_query($link2,$query);
							if(mysqli_num_rows($result)>0){
								$row=mysqli_fetch_row($result);
								$group=$row['0'].',';
								$testo.=txtrestr($group,0);
							}

						  
						  $testo.='</div>
						</div>
					  </li>
					  
					  <li class="item-divider">Alloggio</li>
					  <li class="item-content">
						<div class="item-media alloggioicon"></div>
						<div class="item-inner">
						  <div class="item-title">Alloggio</div>
						  <div class="item-after">'.$alloggio.'</div>
						</div>
					  </li>
					  
					  <li class="item-content">
						<div class="item-media cleanicon"></div>
						<div class="item-inner">
						  <div class="item-title">Stato</div>
						  <div class="item-after">'.dataita($checkout).'</div>
						</div>
					  </li>
					  
					  <li class="item-content">
						<div class="item-media bedicon"></div>
						<div class="item-inner">
						  <div class="item-title">Letti</div>
						  <div class="item-after" style="height:auto; overflow:visible; padding:auto;">';
						  
						  
						  $query2="SELECT infopren.nome,tiporestr.restrizione  FROM infopren,tiporestr WHERE infopren.IDpren='$id' AND infopren.IDstr='$IDstruttura' AND infopren.pers='0' AND infopren.IDrest=tiporestr.ID";
						$result2=mysqli_query($link2,$query2);
						$num2=mysqli_num_rows($result2);
						if($num2>0){
								$i=1;
								while($row=mysqli_fetch_row($result2)){
									if($row['0']!=0){
									$testo.='N.'.$row['0'].' '.$row['1'];
									if($i<$num2){$testo.=', ';if(($i%2)==0)$testo.='<br>';}
								}
							$i++;
							}	
						}else{
							$testo.='Nessuna disposizione<br>impostata';
						}

						  
						  
						  $testo.='</div>
						</div>
					  </li>
					  
					  
					  <li class="item-divider">Agenzia</li>
					  
					  <li class="item-content">
						<div class="item-media agenziaicon"></div>
						<div class="item-inner">
						  <div class="item-title">Agenzia</div>
						  <div class="item-after">';
						  
						  
						  
						  
						  $query2="SELECT extra FROM prenextra WHERE tipolim='8' AND IDpren='$id' LIMIT 1";
						$result2=mysqli_query($link2,$query2);
						if(mysqli_num_rows($result2)>0){
							$row2=mysqli_fetch_row($result2);
							$cof=$row2['0'];
							
							$query2="SELECT IDagenzia FROM cofanetti WHERE ID='$cof' LIMIT 1";
							$result2=mysqli_query($link2,$query2);
							$row2=mysqli_fetch_row($result2);
							$IDagenzia=$row2['0'];
							$query2="SELECT nome FROM agenzie WHERE ID='$IDagenzia' LIMIT 1";
							$result2=mysqli_query($link2,$query2);
							$row2=mysqli_fetch_row($result2);
							$nomeag=$row2['0'];
							$testo.='<b>'.$nomeag.'</b>';	
							
						}else{
						
							$query2="SELECT IDagenzia,corrispettivo,perc FROM agenziepren WHERE IDobj='$id' AND tipoobj='0' LIMIT 1";
							$result2=mysqli_query($link2,$query2);
							if(mysqli_num_rows($result2)>0){
								$row2=mysqli_fetch_row($result2);
								
								$IDagenzia=$row2['0'];
								$corrisp=$row2['1'];
								$perc=$row2['2'];
								$query2="SELECT nome FROM agenzie WHERE ID='$IDagenzia' LIMIT 1";
								$result2=mysqli_query($link2,$query2);
								$row2=mysqli_fetch_row($result2);
								$nomeag=$row2['0'];
								$testo.='<b>'.$nomeag.'<br><span style="font-size:12px; color:#888;">(Quota: '.$corrisp.' &euro; - Perc: '.$perc.'%)</span></b>';	
							}else{
								$testo.='Prenot. senza Agenzia';		
							}
						}
						
										  
										  
						  
						  $testo.='</div>
						</div>
					  </li>
					  
					  
					   ';
					   
					   
					   
	$IDprenc=prenotcoll($id);
					   
					   
	$query="SELECT time FROM prenotazioni WHERE IDv='$id' LIMIT 1";
	$result=mysqli_query($link2,$query);
	$row=mysqli_fetch_row($result);
	$timearr=$row['0'];
	
	$query="SELECT SUM(p2.prezzo) FROM prenextra2 as p2,prenextra as p WHERE p.IDpren IN($IDprenc) AND p.ID=p2.IDprenextra AND p2.datacar='0' AND p2.IDpren=p.IDpren  AND p2.paga='1'";
	$result=mysqli_query($link2,$query);
	$row=mysqli_fetch_row($result);
	$prezzoini=round($row['0'],2);
	
	$query="SELECT SUM(p2.prezzo) FROM prenextra2 as p2,prenextra as p WHERE p.IDpren IN($IDprenc) AND p.ID=p2.IDprenextra AND p2.datacar='1' AND p2.IDpren=p.IDpren AND p2.paga='1'";
	$result=mysqli_query($link2,$query);
	$row=mysqli_fetch_row($result);
	$prezzoextra=round($row['0'],2);
	
	
	$query="SELECT SUM(durata) FROM prenextra  WHERE IDpren IN($IDprenc) AND IDtipo='0'";
	$result=mysqli_query($link2,$query);
	$row=mysqli_fetch_row($result);
	$sconti=round($row['0'],2);
	
	$prezzotot=$prezzoextra+$prezzoini;
	
	
	$txtpag='';
	
	$query="SELECT GROUP_CONCAT(extra SEPARATOR ',') FROM prenextra WHERE IDpren IN($IDprenc) AND IDstruttura='$IDstruttura' AND tipolim='7' AND modi>='0'";
	$result=mysqli_query($link2,$query);
	$row=mysqli_fetch_row($result);
	$idee=$row['0'];
	$arridee=explode(',',$idee);
	
	//pagamenti ad idee
	
	//pagamento a prenotazione
	
	//pagamenti a servizi
	
	$acconto=0;
	$queryacc="SELECT IDscontr,SUM(valore),tipoobj FROM scontriniobj WHERE IDobj IN($IDprenc) AND tipoobj IN(1,2,0) GROUP BY IDscontr";
	
	$resultacc=mysqli_query($link2,$queryacc);
	if(mysqli_num_rows($resultacc)>0){		
		while($rowacc=mysqli_fetch_row($resultacc)){
			
			$tipopag='';
			if($rowacc['2']==0)$tipopag='<b>Servizi</b>';
			if($rowacc['2']==1)$tipopag='<b>Saldo</b>';
			if($rowacc['2']==2)$tipopag='<b>Acconto</b>';
			$query3="SELECT timepag,IDcliente,metodopag FROM scontrini WHERE ID='".$rowacc['0']."' LIMIT 1";
			$result3=mysqli_query($link2,$query3);
			$row3=mysqli_fetch_row($result3);
			$timepag=$row3['0'];
			$IDcli=$row3['1'];
			if($IDcli!='0'){
				$tipopag.='<br><span style="font-size:10px;color:#999;">'.estrainomecli($IDcli).'</span>';
			}
			
			
			$txtpag.='<li class="item-content">
							<div class="item-media payicon"></div>
							<div class="item-inner">
							  <div class="item-title">'.$tipopag.'</div>
							  <div class="item-after">'.metodopag($row3['2']).'<br>
							  <b style="font-size:12px;">'.$rowacc['1'].' &euro;</b> - <span style="font-size:12px;">'.dataita2($timepag).'</span>
							  
							  </div>
							</div>
						  </li>
			
			';
			$acconto+=$rowacc['1'];
		}		
	}
	
	
	
	$prezzoidee=0;
	
	
	foreach($arridee as $idea){
		if(is_numeric($idea)){
			$queryacc="SELECT IDscontr,SUM(valore),tipoobj FROM scontriniobj WHERE IDobj='$idea' AND tipoobj ='7' GROUP BY IDscontr";
			$resultacc=mysqli_query($link2,$queryacc);
			if(mysqli_num_rows($resultacc)>0){		
				while($rowacc=mysqli_fetch_row($resultacc)){
					$tipopag='';
					$query3="SELECT timepag FROM scontrini WHERE ID='".$rowacc['0']."' LIMIT 1";
					$result3=mysqli_query($link2,$query3);
					$row3=mysqli_fetch_row($result3);
					$timepag=$row3['0'];
					
					
					$txtpag.='
					
					<li class="item-content">
							<div class="item-media payicon"></div>
							<div class="item-inner">
							  <div class="item-title">Voucher</div>
							  <div class="item-after">'.metodopag($row3['2']).'<br>
							  <b style="font-size:12px;">'.$rowacc['1'].' &euro;</b> - <span style="font-size:12px;">'.dataita2($timepag).'</span>
							  
							  </div>
							</div>
						  </li>
					
					';
					
					
					
					$prezzoidee+=$rowacc['1'];
				}		
			}

		}
	}
	
	$quotaagenzia=0;
	$txtdapag='';
	$txteffet='';
	$txtdaeffet='';
	$txtagenzia='';
	$IDagenziapren=0;
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
				
				
				$quotaagenzia+=$agetot;
				//controllo pagamento acconto
			/*
				$txtagenzia.='
				
				<li class="item-content">
							<div class="item-media payicon"></div>
							<div class="item-inner">
							  
							  <div class="item-after">Paga ad Agenzia<br><br>
							  <b style="font-size:12px;">'.round($agetot-$rowag['3'],2).'€ &euro;</b> - <span style="font-size:12px;">'.date('d/m/Y',$rowag['1']).'</span>
							  
							  </div>
							</div>
						  </li>
				';*/
			
			
				if($agetot>0){
	
					//controllo pagamanto
					
					$query3="SELECT valore,IDscontr FROM scontriniobj WHERE IDobj='$IDagenziapren' AND tipoobj='10' LIMIT 1";
					$result3=mysqli_query($link2,$query3);
					if(mysqli_num_rows($result3)>0){
						$row3=mysqli_fetch_row($result3);
						$query4="SELECT timepag,metodopag FROM scontrini WHERE ID='".$row3['1']."' LIMIT 1";
						$result4=mysqli_query($link2,$query4);
						$row4=mysqli_fetch_row($result4);
						$timepag=$row4['0'];
						$txtpag.='
						
						
						<li class="item-content">
							<div class="item-media agenziaicon"></div>
							<div class="item-inner">
							  <div class="item-title">Agenzia</div>
							  <div class="item-after">'.metodopag($row4['1']).'<br>
							  <b style="font-size:12px;">'.$row3['0'].' &euro;</b> - <span style="font-size:12px;">'.date('d/m/Y',$timepag).'</span>
							  
							  </div>
							</div>
						  </li>
						';
						
					}else{
						$txtdapag.='
						
						<li class="item-content">
							<div class="item-media agenziaicon"></div>
							<div class="item-inner">
							  <div class="item-title">Agenzia</div>
							  <div class="item-after">'.round($agetot-$rowag['3'],2).' &euro;
							  
							  
							  </div>
							</div>
						  </li>
						';
						
					}
				}
			
			
			break;
			case 1: //paga alla struttura
				/*
				$txtagenzia.='
				
				
				<li class="item-content">
							<div class="item-media payicon"></div>
							<div class="item-inner">
							  <div class="item-title">Agenzia</div>
							  <div class="item-after">Paga in Struttura<br>
							  <b style="font-size:12px;">'.round($agetot-$rowag['3'],2).'€</b> - <span style="font-size:12px;">'.date('d/m/Y',$rowag['1']).'</span>
							  
							  </div>
							</div>
						  </li>
				
				';	
				*/
				
				
				
				if($agetot>0){
	
					//controllo pagamanto
					
					$query3="SELECT valore,IDscontr FROM scontriniobj WHERE IDobj='$IDagenziapren' AND tipoobj='10' LIMIT 1";
					$result3=mysqli_query($link2,$query3);
					if(mysqli_num_rows($result3)>0){
						$row3=mysqli_fetch_row($result3);
						$query4="SELECT timepag,metodopag FROM scontrini WHERE ID='".$row3['1']."' LIMIT 1";
						$result4=mysqli_query($link2,$query4);
						$row4=mysqli_fetch_row($result4);
						$timepag=$row4['0'];
						$txteffet.='
						
						
						  
						  <li class="item-content">
							<div class="item-media agenziaicon"></div>
							<div class="item-inner">
							  <div class="item-title">Agenzia</div>
							  <div class="item-after">'.metodopag($row4['1']).'<br>
							  <b style="font-size:12px;">'.$row3['0'].' &euro;</b> - <span style="font-size:12px;">'.date('d/m/Y',$timepag).'</span>
							  
							  </div>
							</div>
						  </li>
						  ';
						
					}else{
						$txtdaeffet.='
						
						
						<li class="item-content">
							<div class="item-media agenziaicon"></div>
							<div class="item-inner">
							  <div class="item-title">Agenzia</div>
							  <div class="item-after">'.round($rowag['3'],2).' &euro;
							  
							  
							  </div>
							</div>
						  </li>
						';
						
					}
				}
				
				
			
			break;
			case 2: //pagamento automatico
				/*
				$txtagenzia.='
				
				
				<li class="item-content">
							<div class="item-media payicon"></div>
							<div class="item-inner">
							  <div class="item-title">Agenzia</div>
							  <div class="item-after">Prelievo Auto<br>
							  <b style="font-size:12px;">'.round($agetot-$rowag['3'],2).'€</b> - <span style="font-size:12px;">'.date('d/m/Y',$rowag['1']).'</span>
							  
							  </div>
							</div>
						  </li>
				
				
				';	
				*/
				
				
				if($agetot>0){
	
					//controllo pagamanto
					
					$query3="SELECT valore,IDscontr FROM scontriniobj WHERE IDobj='$IDagenziapren' AND tipoobj='10' LIMIT 1";
					$result3=mysqli_query($link2,$query3);
					if(mysqli_num_rows($result3)>0){
						$row3=mysqli_fetch_row($result3);
						$query4="SELECT timepag,metodopag FROM scontrini WHERE ID='".$row3['1']."' LIMIT 1";
						$result4=mysqli_query($link2,$query4);
						$row4=mysqli_fetch_row($result4);
						$timepag=$row4['0'];
						$txteffet.='
						
						  <li class="item-content">
							<div class="item-media agenziaicon"></div>
							<div class="item-inner">
							  <div class="item-title">Agenzia</div>
							  <div class="item-after">'.metodopag($row4['1']).'<br>
							  <b style="font-size:12px;">'.$row3['0'].' &euro;</b> - <span style="font-size:12px;">'.date('d/m/Y',$timepag).'</span>
							  
							  </div>
							</div>
						  </li>
						';
						
					}else{
						$txtdaeffet.='
						
						<li class="item-content">
							<div class="item-media agenziaicon"></div>
							<div class="item-inner">
							  <div class="item-title">Agenzia</div>
							  <div class="item-after">'.round($rowag['3'],2).' &euro;
							  
							  
							  </div>
							</div>
						  </li>
						
						';
						
					}
				}
				
				
				
			
			break;
		}
	}
	
	$queryacc="SELECT ID,extra,time FROM prenextra WHERE IDpren IN($IDprenc) AND tipolim='8'"; //controllo cofanetti
	$resultacc=mysqli_query($link2,$queryacc);
	if(mysqli_num_rows($resultacc)>0){
		
		while($rowag=mysqli_fetch_row($resultacc)){
			$IDprenextra=$rowag['0'];
			$IDcof=$rowag['1'];
			
			$query2="SELECT codice,IDagenzia FROM cofanettivend WHERE IDprenextra='$IDprenextra'";
			$result2=mysqli_query($link2,$query2);
			$row2=mysqli_fetch_row($result2);
			$codice=$row2['0'];
			$IDagenzia=$row2['1'];
			
			$query2="SELECT prezzo,persone,cofanetto FROM cofanetti WHERE ID='$IDcof' LIMIT 1";
			$result2=mysqli_query($link2,$query2);
			$row2=mysqli_fetch_row($result2);
			$agetot=$row2['0'];
			$persone=$row2['1'];
			$cof=$row2['2'];
			
			$query2="SELECT nome FROM agenzie WHERE ID='$IDagenzia' LIMIT 1";
			$result2=mysqli_query($link2,$query2);
			$row2=mysqli_fetch_row($result2);
			$agenzia=$row2['0'];
			//suddividere da pagare cofanetto - non mischiare con acconto
			 
			$acconto+=$agetot;
			
			$txtagenzia.='
			
			
			<li class="item-content">
							<div class="item-media payicon"></div>
							<div class="item-inner">
							  <div class="item-title">Cofanetto</div>
							  <div class="item-after">Paga ad Agenzia<br>
							  <b style="font-size:12px;">'.round($agetot,2).'€</b> - <span style="font-size:12px;">'.date('d/m/Y',$rowag['2']).'</span>
							  
							  </div>
							</div>
						  </li>
			';		
		}
	}
	
	
	$accontoreg=0;
	
	$prezzodasaldreg=0;
	$queryacc="SELECT extra,ID FROM prenextra WHERE tipolim='7' AND IDpren IN($IDprenc)";
	$resultacc=mysqli_query($link2,$queryacc);
	if(mysqli_num_rows($resultacc)>0){
		while($rowacc=mysqli_fetch_row($resultacc)){
			$IDreg=$rowacc['0'];
			$pacchetto=$rowacc['0'].'/'.$rowacc['1'];
			$query3="SELECT SUM(prezzo) FROM prenextra2 WHERE pacchetto='$pacchetto'";
			$result3=mysqli_query($link2,$query3);
			$row3=mysqli_fetch_row($result3);
			$prezzoreg2=$row3['0'];
			
			$query3="SELECT v.tipocliente,v.IDcliente FROM vendite as v,venditeoggetti as vo WHERE vo.IDfinale='$IDreg' AND vo.tipoobj='7' AND vo.IDvendita=v.ID LIMIT 1";
			$result3=mysqli_query($link2,$query3);
			$row3=mysqli_fetch_row($result3);
			$tipocli=$row3['0'];
			$IDcli=$row3['1'];
			$insasacc=0;
			if($prezzoreg2<0){$prezzoreg2=$prezzoreg2*-1;$insasacc=1;} //caso di voucher come acconto
				
			$prezzodasaldreg2=($prezzoreg2-controllopagreg($IDreg));
			$prezzodasaldreg+=$prezzodasaldreg2;
				
			if($tipocli=='5'){
				$query4="SELECT nome FROM agenzie WHERE ID='$IDcli' LIMIT 1";
				$result4=mysqli_query($link2,$query4);
				$row4=mysqli_fetch_row($result4);
				$nagenzia=$row4['0'];
				
				$txtdapag.='
				
					
					<li class="item-content">
							<div class="item-media payicon"></div>
							<div class="item-inner">
							  <div class="item-title">Voucher</div>
							  <div class="item-after">Paga ad Agenzia<br>
							  <b style="font-size:12px;">'.round($prezzodasaldreg,2).'€</b> 
							  
							  </div>
							</div>
						  </li>
					
					';
					$accontoreg+=$prezzodasaldreg;
			}else{
				if($prezzodasaldreg>0){
					$txtdapag.='
					
					<li class="item-content">
							<div class="item-media payicon"></div>
							<div class="item-inner">
							  <div class="item-title">Voucher</div>
							  <div class="item-after">Paga ad Agenzia<br>
							  <b style="font-size:12px;">'.round($prezzodasaldreg,2).'€</b> 
							  
							  </div>
							</div>
						  </li>
					
					';
				}
			}
			
		}
	}

	

	$query3="SELECT ID,extra FROM prenextra  WHERE IDpren IN($IDprenc) AND tipolim='7' ";
	$result3=mysqli_query($link2,$query3);
	if(mysqli_num_rows($result3)>0){
		while($row3=mysqli_fetch_row($result3)){
			$pacc=$row3['1'].'/'.$row3['0'];
			$query4="SELECT SUM(prezzo) FROM prenextra2 WHERE pacchetto='$pacc'";
			$result4=mysqli_query($link2,$query4);
			$row4=mysqli_fetch_row($result4);
			$prezzoreg3=$row4['0'];
			if($prezzoreg3<0){ //voucher come acconto tolto al totale
				$prezzoini-=$prezzoreg3;
				$prezzotot-=$prezzoreg3;
				//$prezzodasaldreg-=$prezzoreg3;
			}
		}
	}
	
	
	$prezzotot+=$sconti;
	
	$prezzosald=round($prezzotot-$acconto-$prezzoidee-$quotaagenzia,2);
	
	
	$prezzosald2=round($prezzosald-$prezzodasaldreg,2);
	
	
	if($prezzosald2!=0){
		$txtdapag.='
		
		<li class="item-content">
							<div class="item-media payicon"></div>
							<div class="item-inner">
							  <div class="item-title">Prenotazione</div>
							  <div class="item-after">
							  <b style="font-size:12px;">'.round($prezzosald2,2).'€</b> 
							  
							  </div>
							</div>
						  </li>
		';
	}
	
	
	//controllo idee
	//controllo saldo aggiornato
	
	
	
	
	$queryf="SELECT SUM(totale) FROM fatture WHERE IDobj IN($IDprenc) AND tipo='0' AND tipoobj='0'";
	$resultf=mysqli_query($link2,$queryf);
	$rowf=mysqli_fetch_row($resultf);
	$fatturaf=$rowf['0'];
	$queryf="SELECT SUM(totale) FROM fatture WHERE IDobj IN($IDprenc) AND tipo='1' AND tipoobj='0'";
	$resultf=mysqli_query($link2,$queryf);
	$rowf=mysqli_fetch_row($resultf);
	$ricevutaf=$rowf['0'];
	
	
	
	if(strlen($txtagenzia)>0){
		$testo.='<li class="item-divider">Agenzia</li>'.$txtagenzia;
		
	}
	
	
	
	
	if(strlen($txtpag)>0){
		$testo.='
		<li class="item-divider">Pagamenti Ricevuti</li>'.$txtpag;
		
	}else{
		$testo.='
		<li class="item-divider">Pagamenti Ricevuti</li>
		<li class="item-content">
							<div class="item-media payicon"></div>
							<div class="item-inner">
							  <div class="item-title">Prenotazione</div>
							  <div class="item-after">
							  <b style="font-size:12px;">Nessun pagamento ricevuto</b> 
							  
							  </div>
							</div>
						  </li>';
	}
	
	if(strlen($txtdapag)>0){
		$testo.='
		<li class="item-divider">Pagamenti da Ricevere</li>'.$txtdapag;
	}
	
	
	
	if(strlen($txteffet)>0){
		$testo.='
		
		<li class="item-divider">Pagamenti Effettuati</li>'.$txteffet;
	}
	
	if(strlen($txtdaeffet)>0){
		$testo.='
		
		<li class="item-divider">Pagamenti da Effettuare</li>'.$txtdaeffet;
	}
	/*
	$testo.='<br><br>
	<div style="color:#666;  margin-bottom:8px; font-size:21px;">Ricevute & Fatture Fiscali</div>
	';
	
	
	
	
	
	
	$tipofatt=array('Fattura','Ricevuta');
	$queryacc="SELECT ID,data,numero,anno,testovoce,totale,IDintestazione,tipo,voci FROM fatture WHERE IDobj IN($IDprenc) AND tipoobj='0' AND stampa='1'";
	$resultacc=mysqli_query($link2,$queryacc);
	if(mysqli_num_rows($resultacc)>0){
		$testo.='
		<table  class="tabcli" width="100%" style=" font-size:14px;" cellspacing="0" cellpadding="0">';
		while($rowag=mysqli_fetch_row($resultacc)){
		
			if($rowag['8']=='1'){
				$testovoce=$rowag['4'];
			}else{
				$testovoce='Elenco Servizi';
			}
			if($rowag['7']=='0'){
				$intestazione='';
				$queryint="SELECT intestazione FROM intestazioni WHERE ID='".$rowag['7']."' LIMIT 1";
				$resultint=mysqli_query($link2,$queryint);
				$rowi=mysqli_fetch_row($resultint);
				$intestazione=$rowi['0'];
				$txtric='Fattura';
			}else{
				$txtric='Ricevuta';
				$intestazione=estrainomeschedina($rowag['6']);
			}
			
			
			
			$testo.='<tr><td>'.$tipofatt[$rowag['7']].'</td><td>n.<u>'.$rowag['2'].'</u>/'.$rowag['3'].'<br><b style="font-size:11px;">'.date('d/m/Y',$rowag['1']).'<b></td><td><b>'.round($rowag['5'],2).' &euro;</b></td><td>'.$testovoce.'</td><td>'.$intestazione.'</td><td>
			
			
			<button class="shortcut mini10 popover success iconfatture" style="background-size:19px 19px;" onclick="location.href='."'config/fatture/".$txtric.$rowag['0'].".pdf'".'"><span>Apri PDF</span></button>
			<button class="shortcut mini10 danger del3icon popover" onclick="msgboxelimina('.$rowag['0'].',23,0,4)"><span>Elimina Documento</span></button></td></tr>';		
		}
		$testo.='</table>';
	}else{
		$testo.='<span style="font-size:16px;">Non è stato registrata nessuna ricevuta/fattura</span><br>';
	}
	
	$txtcarta='';
	$queryc="SELECT ID FROM carte WHERE IDpren IN($IDprenc)";
	$resultc=mysqli_query($link2,$queryc);
	if(mysqli_num_rows($resultc)>0){
		$rowc=mysqli_fetch_row($resultc);
		$txtcarta='<br><b style="font-size:14px; color:#d42aa6;">PRENOTAZIONE GARANTITA<br>con Carta di Credito</b><br><span style="font-size:10px;">Clicca su Carta di Credito per visualizzare</span><br><br>';
	}
		
					   
					   */
					   
					   
					   
					   
					  
					  
					  $testo.='
					  
					  
					  
		<li class="item-divider">Totali</li>
			<li class="item-content">
							<div class="item-media payicon"></div>
							<div class="item-inner">
							  <div class="item-title">Iniziale</div>
							  <div class="item-after">
							  <b style="font-size:12px;">'.$prezzoini.'€</b> 
							  
							  </div>
							</div>
						  </li>
						  
						  <li class="item-content">
							<div class="item-media payicon"></div>
							<div class="item-inner">
							  <div class="item-title">Extra</div>
							  <div class="item-after">
							  <b style="font-size:12px;">'.$prezzoextra.'€</b> 
							  
							  </div>
							</div>
						  </li>
						  
						  <li class="item-content">
							<div class="item-media payicon"></div>
							<div class="item-inner">
							  <div class="item-title">Abbuoni</div>
							  <div class="item-after">
							  <b style="font-size:12px;">'.$sconti.'€</b> 
							  
							  </div>
							</div>
						  </li>
						  
						  <li class="item-content">
							<div class="item-media payicon"></div>
							<div class="item-inner">
							  <div class="item-title">Totale</div>
							  <div class="item-after">
							  <b style="font-size:12px;">'.round($prezzoini+$prezzoextra+$sconti,2).'€</b> 
							  
							  </div>
							</div>
						  </li>
					  
					  
					  
					  
					  <li class="item-divider">Note</li>
					  
					  <li class="item-content">
						
						<textarea style="border:solid 1px #ccc; width:93%; margin-top:5px; border-radius:3px;">'.str_replace('<br/>','&#013;',$note).'</textarea>
						
					  </li>
					  
					  
					  
					  
					 
					</ul>
						</div>		
				
				
								
				
				
				
				<br><br><br><br><br><br><br><br><br><br>
				
				
	
	
			 
			 	
			 
			
			
			
			</div></div>
			  
			  
			  
			  
			  ';
			  echo $testo;
			 