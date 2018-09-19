<?php 
if(!isset($inc)){
	include('../../../config/connecti.php');
	include('../../../config/funzioni.php');
	header('Access-Control-Allow-Origin: *');
	$testo='';
}

$IDpren=$_SESSION['IDstrpren'];
$route=$_SESSION['route'];

$query="SELECT app,gg,time,tempg,tempn,checkout,stato,IDstruttura,acconto,ID FROM prenotazioni WHERE IDv='$IDpren' LIMIT 1";

$queryapp=$query;

$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDapp=$row['0'];
$gg=$row['1'];
$time=$row['2'];
$timearr=$time;
$tempg=$row['3'];
$tempn=$row['4'];
$checkout=$row['5'];
$stato=$row['6'];
$IDstr=$row['7'];
$acconto=$row['8'];
$IDprentxt=$row['9'];

$dataarr=date('Y-m-d',$time);
$datapar=date('Y-m-d',$checkout);

$nomepren=estrainome($IDpren);
$nomestr=estrainomestr($IDstr);
$IDprenc=prenotcoll($IDpren);

$query="SELECT SUM(prezzo) FROM prenextra2 WHERE IDpren IN($IDprenc)";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$totale=$row['0'];

$query="SELECT SUM(durata) FROM prenextra WHERE IDpren IN($IDprenc) AND tipolim='0'";
$result=mysqli_query($link2,$query);
if(mysqli_num_rows($result)>0){
	$row=mysqli_fetch_row($result);
	$totale+=$row['0'];
}


$query="SELECT latitude,longitude,suggerimenti FROM strutture WHERE ID='$IDstr' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$lat=$row['0'];
$lon=$row['1'];
$sugg=$row['2'];

$checkintxt='<span style=" color:#888; ">Check-in</span><br><span style="font-size:17px; font-weight:300;">
	'.date('d',$time).' '.strtoupper($mesiita2[date('n',$time)]).'</span><br>'.$giorniita[date('w',$time)].' '.date('H:i',$time);


if($gg>0){
	$checkouttxt='<span style=" color:#888; ">Check-out</span><br><span style="font-size:17px; font-weight:300;">
	'.date('d',$checkout).' '.strtoupper($mesiita2[date('n',$checkout)]).'</span><br>'.$giorniita[date('w',$checkout)].' '.date('H:i',$checkout);
}else{
	$checkouttxt='Un giorno';
}


if($gg>0){
	//estra foto appartamento
	$foto=getfoto($IDapp,2);
}else{
	$IDserv=0;
	$query="SELECT extra FROM prenextra WHERE IDpren='$IDpren' AND tipolim='5' LIMIT 1";
	$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
		$row=mysqli_fetch_row($result);
		$IDserv=$row['0'];
		$foto=getfoto($IDserv,4,0,1);
		
		if(strlen($foto)==0){
			$query="SELECT extra FROM prenextra WHERE IDpren='$IDpren' ORDER BY prezzo DESC LIMIT 1";
			$result=mysqli_query($link2,$query);
			while($row=mysqli_fetch_row($result)){
				$IDserv=$row['0'];
				$foto=getfoto($IDserv,4,0,1);
				if(strlen($foto)>0){
					break;
				}
			}
		
		}
		
	}else{
		$query="SELECT extra FROM prenextra WHERE IDpren='$IDpren' ORDER BY prezzo DESC LIMIT 1";
		$result=mysqli_query($link2,$query);
		while($row=mysqli_fetch_row($result)){
			$IDserv=$row['0'];
			$foto=getfoto($IDserv,4,0,1);
			if(strlen($foto)>0){
				break;
			}
		}
	}
	
}
$foto='immagini/big'.$foto;

$testo.='

<input type="hidden" value="'.$IDpren.'" id="IDprenfunc">

<div style="width:100%; position:relative;height:130px; background:url('.$route.$foto.') no-repeat center center; background-size:cover; margin-bottom:20px; margin-top:-28px;box-shadow: 1px 1px 5px 0px rgba(168,168,168,1);">
<div style="position:absolute; top:0px; left:0px; width:100%; height:100%; z-index:1; background:#333; opacity:0.1;"></div>


</div>



<table style="width:95%; margin:auto; "><tr><td style="line-height:15px;font-size:15px;color:#2b4795; font-weight:600; text-transform:uppercase;">'.$nomestr.'</td><td align="right" valign="top"><b style="font-size:17px;color:#2b4795; ">'.round($totale,1).' €</b></td></tr></table>



<table style="width:100%; border-top:solid 1px #ccc; background:#fff;border-bottom:solid 1px #ccc;" cellspacing="10"><tr>
<td style="text-align:center;font-weight:300;color:#203baf; font-size:11px; border-right:solid 1px #ccc;" width="52%">'.$checkintxt.'</td>
<td style="text-align:center;font-weight:300;color:#203baf;font-size:11px; " width="50%">'.$checkouttxt.'</td>

</tr></table>

';



//elenco servizi

$testo.='<div style="width:100%; text-align:center; margin-top:20px;">';


$query="SELECT ID FROM carte WHERE IDpren='$IDpren' LIMIT 1";
$result=mysqli_query($link2,$query);
$numcarte=mysqli_num_rows($result);



if(($stato<2)&&($numcarte==0)){
	
	$query2="SELECT ID FROM richiesteconferma WHERE IDpren='$IDpren' LIMIT 1";
	$result2=mysqli_query($link2,$query2);
	if(mysqli_num_rows($result2)>0){
		$testo.='<br><b style="font-size:18px; color:#364cbb;">Richiesta di conferma ricezione pagamento inviata!</b><br><div style="font-size:13px; font-weight:300;line-height:15px; padding:5px; color:#444;"><span style="font-weight:400;">Entro 24/48h la tua prenotazione sar&agrave; confermata.</span><br>
		<span style="font-size:10px;">Se entro questo tempo non riceve comunicazioni contattare la struttura.<br/>Ad ogni modo Scidoo garantisce la presenza della sua prenotazione all&acute;interno del software della struttura ricettiva.</span>
		</div>';
		
	}else{
	
	
		$testo.='<br><b style="font-size:18px; color:#c11818;">Conferma ora la tua prenotazione!</b><br><div style="font-size:13px; font-weight:300;line-height:15px; padding:5px; color:#444;">1) Scegli il metodo<br>
		2) Inserisci i dati richiesti<br>
		3) Clicca si CONFERMA e la tua prenotazione sar&agrave; attiva
		</div>';
	}
	
	$testo.='
	
	<div class="list-block inset">
      <ul>';
	//carta di credito
	
	
	$testo.='
	 <li class="accordion-item" style="padding:0px;">
				  <a href="#" class="item-link item-content" onclick="">
					<div class="item-inner">
						<div class="item-title" style="text-align:left; line-height:12px;">Carta di Credito<br><span style="font-size:10px;color:#666;">Nessun prelievo; Solo a garanzia</span></div>
						<div class="item-after">Immediato</div>
					  </div>
				  </a>
				  
				   <div class="accordion-item-content" style=" padding:0px; font-size:11px; background:#f1f1f1;">
						<div class="content-block"  class="details" style="padding:0px; ">
						<div class="list-block inset" style="margin-top:10px;">
						
							<ul>
								<li>
								  <div class="item-content">
									<div class="item-inner" style="width:100%;">
									  <div class="item-title label" style="width:50%; text-align:left;">Numero Carta</div>
									  <div class="item-after" style="width:50%;">
										<input type="text" placeholder="XXXX" id="ncarta" style="font-size:13px;">
									  </div>
									</div>
								  </div>
								</li>
								
								<li>
								  <div class="item-content">
									<div class="item-inner" style="width:100%;">
									  <div class="item-title label" style="width:50%; text-align:left;">Mese Scadenza</div>
									  <div class="item-after" style="width:50%;">
										<select style="font-size:13px;" id="meses">'.generamesi().'</select>
									  </div>
									</div>
								  </div>
								</li>
								<li>
								  <div class="item-content">
									<div class="item-inner" style="width:100%;">
									  <div class="item-title label" style="width:50%; text-align:left;">Anno Scadenza</div>
									  <div class="item-after" style="width:50%;">
										<select style="font-size:13px;" id="annos">'.generaanni(11).'</select>
									  </div>
									</div>
								  </div>
								</li>
								<li>
								  <div class="item-content">
									<div class="item-inner" style="width:100%;">
									  <div class="item-title label" style="width:50%; text-align:left;">Intestatario</div>
									  <div class="item-after" style="width:50%;">
										<input type="text" placeholder="Intestatario" id="intes" style="font-size:13px;">
									  </div>
									</div>
								  </div>
								</li>
							</ul>
						</div><br>
						<a href="#" class="button button-fill color-green" style="width:50%; margin:auto;" onclick="controllocarta()">Conferma</a><br>
					</div>
			</div>
      </li>
	
	';
	
	$query="SELECT p.ID,t.pagamento,p.info,3,p.email,p.tipopag FROM pagonline as p,tipopag as t WHERE p.tipopag IN(2,3) AND p.IDstr='$IDstr' AND p.tipopag=t.ID";
	$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
		while($row=mysqli_fetch_row($result)){
			
			$testo.='
			
			<li class="accordion-item" style="padding:0px;">
				  <a href="#" class="item-link item-content" onclick="">
					<div class="item-inner">
						<div class="item-title">'.$row['1'].'</div>
						<div class="item-after">'.$acconto.' €</div>
					  </div>
				  </a>
				  
				  <div class="accordion-item-content" style=" padding:0px; font-size:12px; background:#f1f1f1;">
					<div class="content-block"  class="details" style="padding:0px; text-align:left; padding-left:25px; ">';
					
					switch($row['5']){
						case 2:
							$testo.='
							<b>Info:</b> '.$row['2'].'<br>
							<b>Email Struttura:</b> '.$row['4'].'<br>
							
							';
						break;
						default:
							$testo.='
							<b>Dati Pagamento:</b><br> '.$row['2'].'<br>
							
							
							';
						
						break;
					}
					
					$testo.='<br><b>Causale</b>:<br>

					Prenotazione N.'.$IDprentxt.' di '.$nomepren.' (Arrivo: '.dataita($time).' '.date('Y',$time).')
					
					<br><br>
						
						<a href="#" class="button button-fill color-green" style="width:50%;" onclick="modprofilo('.$IDpren.','.$row['5'].',7,10,2)">Conferma</a><br>
						<div style="color:#952b44;"><b>Istruzioni</b><br>
						Eseguire il pagamento utilizzando i dati sopra indicati. Dopodich&egrave; cliccare su conferma.
					</div>
					<hr>
					</div>
				</div>
      </li>
			
			
			';
		}
	}
	
	
	$testo.='</ul></div>';
}else{
	$testo.='<b style="font-size:16px; color:#269759;">Prenotazione Confermata</b>';
}


$testo.='</div><br>';


$testo.='
<div class="content-block-title" style="color:#394baa; margin-left:0px; font-size:12px; font-weight:600; text-transform:uppercase;">
<table style="margin:0px; margin-bottom:-10px;"><tr><td><i class="material-icons">home</i>
</td><td>
La tua prenotazione
</td></tr></table>
</div>
<div class="list-block inset">
      <ul>
	   ';
	   	
		if(strlen($sugg)>0){
			$testo.='<li class="item-content" onclick="navigation(28,0,0,0)">
			  <div class="item-inner">
				<div class="item-title menusx">Suggerimenti agli Ospiti</div>
				<div class="item-after"></div>
			  </div>
			</li>';
		}
		
		
		
		$testo.='<li class="item-content" onclick="navigation(16,0,0,0)">
          <div class="item-inner">
            <div class="item-title menusx">Orari dei tuo Servizi</div>
            <div class="item-after"></div>
          </div>
        </li>';
	
		if($gg>0){
			$testo.='
			 <li class="item-content"  onclick="navigation(17,0,0,0)" >
			  <div class="item-inner">
				<div class="item-title menusx">Temperatura Alloggio</div>
				<div class="item-after"></div>
			  </div>
			</li>';
		}
		
	$testo.='
		
		<li class="item-content" onclick="navigation(20,0,0,0)">
          <div class="item-inner">
            <div class="item-title menusx">Il conto</div>
            <div class="item-after"></div>
          </div>
        </li>
		
		<li class="item-content"  onclick="navigation(29,0,9,0)">
          <div class="item-inner">
            <div class="item-title menusx">Galleria Foto</div>
            <div class="item-after"></div>
          </div>
        </li>
		
		<li class="item-content" onclick="navigation(30,0,0,0)">
          <div class="item-inner">
            <div class="item-title menusx">Recensioni</div>
            <div class="item-after"></div>
          </div>
        </li>
		
		<li class="item-content" onclick="location.href='."' https://maps.google.com/?q=".$lat.",".$lon."  '".'">
          <div class="item-inner">
            <div class="item-title menusx">Indicazioni Stadali - Google Maps</div>
            <div class="item-after"></div>
          </div>
        </li>
		
		
		
		
		
		
		
		
		<li class="accordion-item" style="padding:0px;">
				  <a href="#" class="item-link item-content" onclick="">
					<div class="item-inner">
					  <div class="item-title-row menusx">
					  	
					 	Menu Ristorante
						
					  </div>
					</div>
				  </a>
				   <div class="accordion-item-content" style=" padding:0px;font-size:11px; background:#f1f1f1;">
						<div class="content-block"  class="details" style="padding:0px; ">
						<div class="list-block inset">
     					 <ul>';

$query2="SELECT ID,sottotipologia FROM sottotipologie WHERE IDmain='1' AND IDstr='$IDstruttura' ORDER BY ord";
						$result2=mysqli_query($link2,$query2);
						$nummenu=0;
						
						if(mysqli_num_rows($result2)>0){
							
							while($row2=mysqli_fetch_row($result2)){
								
								//controllo
								$query3="SELECT tp.ID FROM dispgiorno as dp,piatti as p,tipopiatti as tp WHERE dp.IDsottotip='".$row2['0']."' AND  FROM_UNIXTIME(dp.data,'%Y-%m-%d') BETWEEN '$dataarr' AND '$datapar' AND dp.IDpiatto=p.ID AND tp.ID=p.IDtipo  LIMIT 1";
								//echo $query3;l
								$result3=mysqli_query($link2,$query3);
								if(mysqli_num_rows($result3)>0){
									$nummenu++;
										$testo.='<li class="item-content" id="Menu '.$row2['1'].'" onclick="navigation(18,'.$row2['0'].',0,0)" >
								  <div class="item-inner">
									<div class="item-title menusx2" style=" padding-left:10px;">'.$row2['1'].'</div>
								  </div>
								</li>';
								}
								
							}
						}
						
						
						if($nummenu==0){
							$testo.='<li class="item-content" onclick="">
								  <div class="item-inner">
									<div class="item-title" style="font-size:13px; padding-left:10px;">Non &egrave; stato pubblicato nessun menu</div>
								  </div>
								</li>';
						}







$testo.='


				</ul></div></div></div>
				</li>
		
	
</ul></div>

<div class="content-block-title" style="color:#dc2774; margin-left:0px; margin-top:-20px;font-size:12px; font-weight:600; ">

<table style="margin-left:0px; margin-bottom:0px;"><tr><td><i class="material-icons">sentiment_satisfied</i>
</td><td>
PERSONALIZZA LA TUA VACANZA
</td></tr></table>
</div>

						<div class="list-block inset">
     					 <ul>';


$query="SELECT tipipos FROM tiposervpos WHERE IDstr='$IDstruttura'";
$result=mysqli_query($link2,$query);
		$row=mysqli_fetch_row($result);
		$tipipos='0'.$row['0'];
		$tipipos=substr($tipipos, 0, strlen($tipipos)-1); 





$query="SELECT ID,tipo,colore FROM tiposervizio WHERE ID IN ($tipipos) AND ID NOT IN (5,8,9,11,10,6)";
$result=mysqli_query($link2,$query);
if(mysqli_num_rows($result)>0){
	while($row=mysqli_fetch_row($result)){
		
		$IDtipo=$row['0'];
		
		if($IDtipo==6){
			$query2="SELECT ID,sottotipologia FROM sottotipologie WHERE IDmain='$IDtipo' AND IDstr='$IDstruttura' ORDER BY ord";
			$result2=mysqli_query($link2,$query2);
			if(mysqli_num_rows($result2)>0){
				while($row2=mysqli_fetch_row($result2)){
									
					$testo.='<li>
					
					
					<a href="#" class="item-link item-content" alt="'.$row2['1'].'"  onclick="navigation(19,'.$row2['0'].',0,0)" >
					
					
								  <div class="item-inner" >
									<div class="item-title menusx2" style=" padding-left:0px;">'.$row2['1'].'</div>
								  </div>
								  </a>
								</li>';
									
								}
							}
			
			
			
		}else{
		
			$query2="SELECT ID FROM servizi WHERE attivo='1' AND IDtipo='$IDtipo'";
			$result2=mysqli_query($link2,$query2);
			$num=mysqli_num_rows($result2);
			if($num>0){
				//
				
				$testo.='
				  <li class="accordion-item" style="padding:0px; ">
					  <a href="#" class="item-link item-content" onclick="">
						  <div class="item-inner">
							<div class="item-title menusx2" ><b>'.$row['1'].'</b></div>
						  </div>
				 </a>
					   <div class="accordion-item-content" style="border-left:solid 3px #'.$row['2'].'; padding:0px;font-size:11px;">
							<div class="content-block"  class="details" style="padding:0px; ">
							<div class="list-block inset">
							 <ul>
							';
							
							$query2="SELECT ID,sottotipologia FROM sottotipologie WHERE IDmain='".$row['0']."' AND IDstr='$IDstruttura' ORDER BY ord";
							$result2=mysqli_query($link2,$query2);
							if(mysqli_num_rows($result2)>0){
								while($row2=mysqli_fetch_row($result2)){
									
									$testo.='<li class="item-content" alt="'.$row2['1'].'" onclick="navigation(19,'.$row2['0'].',0,0)" >
								  <div class="item-inner">
									<div class="item-title menusx2" style=" padding-left:10px;">'.$row2['1'].'</div>
								  </div>
								</li>';
									
								}
							}
													
							
							
							
							$testo.='
							
								
					</ul></div></div></div>
			  
			  
			  
			</li>';
				
			}
		}
	}
}


$testo.='

</div></div>
	</li>
	  
	</ul></div>



<div class="content-block-title" style="color:#e69015; margin-left:0px; margin-top:-20px; font-size:12px; font-weight:600;">

<table style="margin-left:0px; margin-bottom:0px;"><tr><td><i class="material-icons">place</i>
</td><td>
COSA OFFRE IL TERRITORIO
</td></tr></table>
</div>

						<div class="list-block inset">
      <ul>';
						
					$query="SELECT ID,tipologia,color FROM tipoluoghi";
					$result=mysqli_query($link2,$query);
					if(mysqli_num_rows($result)>0){
						while($row=mysqli_fetch_row($result)){
							$IDtipo=$row['0'];
							if($IDtipo==1){
								
								$qadd='';
								for($i=0;$i<$gg;$i++){
									$tt=$timearr+86400*$i;
									$qadd="(data<'$tt' AND dataf>'$tt') OR ";
								}
								if(strlen($qadd)>0){
									$qadd=substr($qadd, 0, strlen($qadd)-3); 
									$qadd='AND  ('.$qadd.')';
								}
								
								
								$query2="SELECT ID FROM luoghieventi WHERE tipo='$IDtipo' AND TRUNCATE ( 6363 * sqrt( POW( RADIANS('".$lat."') - RADIANS(latitude) , 2 ) + POW( RADIANS('".$lon."') - RADIANS(longitude) , 2 ) ) , 3 ) < 30 $qadd  ";
							}else{
								$query2="SELECT ID FROM luoghieventi WHERE tipo='$IDtipo' AND TRUNCATE ( 6363 * sqrt( POW( RADIANS('".$lat."') - RADIANS(latitude) , 2 ) + POW( RADIANS('".$lon."') - RADIANS(longitude) , 2 ) ) , 3 ) < 30 ";
							}
							$result2=mysqli_query($link2,$query2);
							$num=mysqli_num_rows($result2);
							if($num>0){
								$testo.='<li class="item-content" alt="'.$row['1'].'" onclick="navigation(21,'.$row['0'].',0,0)" >
							  <div class="item-inner">
								<div class="item-title menusx2" ><b>'.$row['1'].'</b></div>
								<div class="item-after"><span class="badge" style="color:#333;background:#'.$row['2'].'">'.$num.'</span></div>
							  </div>
							</li>';
								
								
							}
						}
					}
					


	$testo.='					
	<ul></div>

		
	  
	  
	  ';







$query="SELECT nome,suggerimenti,latitude,longitude,mail,sito,dove,tipologia,tel FROM strutture WHERE ID='$IDstr' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);


$testo.='<div style="width:90%; text-align:left; padding-left:15px;"><br>
	<div style="font-size:16px; line-height:14px; font-weight:600;color:#2c529e;">Contattaci</div><br>
	
	<table style="font-weight:300; ">
	<tr onclick="location.href='."'tel:".$row['8']."'".'"><td valign="top" ><i class="material-icons" style="font-size:24px;color:#2c529e;">phone</i> </td><td> '.$row['8'].'</td></tr>
	<tr onclick="location.href='."'mailto:".$row['4']."'".'"><td valign="top"><i class="material-icons" style="font-size:24px;color:#2c529e;">email</i> </td><td> '.$row['4'].'</td></tr>
	<tr onclick="location.href='."'http://".$row['5']."'".'"><td valign="top"><i class="material-icons" style="font-size:24px;color:#2c529e;">web</i> </td><td> '.$row['5'].'</td></tr>
	<tr><td valign="top"><i class="material-icons" style="font-size:24px;color:#2c529e;">pin_drop</i> </td><td> '.$row['6'].'</td><td>
	<tr><td valign="top"><i class="material-icons" style="font-size:24px;color:#2c529e;">place</i> </td><td> '.$row['2'].','.$row['3'].'</td><td>
	
	
	</table><br>
	
	';
	
$testo.='
</div>

<div class="list-block">
		  <ul>
		  
		  <li onclick="esci();" style="background-color:#bc3a30;" >
			  <a href="#" class="item-link item-content">
				  <div class="item-media">
				  </div>
				  <div class="item-inner">
					<div class="item-title" style="font-size:16px; color:#fff;">Esci da Scidoo</div>
					<div class="item-after"></div>
				  </div></a>
				</li>
		  </ul></div>

<br><br>



';

if(!isset($inc)){
echo $testo;
}




?>