<?php 
if(!isset($inc)){
	header('Access-Control-Allow-Origin: *');
	include('../../../config/connecti.php');
	include('../../../config/funzioni.php');
	
	$testo='';
}

$IDpren=$_SESSION['IDstrpren'];
$route=$_SESSION['route'];


$query="SELECT app,gg,time,tempg,tempn,checkout,stato,IDstruttura,acconto,ID FROM prenotazioni WHERE IDv='$IDpren' LIMIT 1";
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
$nomestr=estrainomestr2($IDstr);
$IDprenc=prenotcoll($IDpren);


$timeadesso=oraadesso($IDstr);

if($timeadesso<$time){
	$datagg=$dataarr;
}else{
	$datagg=date('Y-m-d');
}


$query="SELECT GROUP_CONCAT(IDrest SEPARATOR ',') FROM infopren WHERE IDpren ='$IDpren'";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDrestrtxt=$row['0'].',';




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
$oracheckin='<span id="oracheckin" >'.date('H:i',$time).'</span>';
//$checkintxt='<span style=" color:#888; ">Check-in</span><br><span style="font-size:17px; font-weight:300;">
$checkintxt='<span style=" color:#ffde7d;font-weight:600; ">Check-in</span><br><span style="font-size:17px; font-weight:600;">
	'.date('d',$time).' '.strtoupper($mesiita2[date('n',$time)]).'</span><br>'.$giorniita[date('w',$time)].' '.$oracheckin;

//$checkouttxt='<span style=" color:#888; ">Check-out</span><br><span style="font-size:17px; font-weight:300;">

if($gg>0){
	$checkouttxt='<span style="  color:#ffde7d;font-weight:600; ">Check-out</span><br><span style="font-size:17px; font-weight:600;">
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

/*<div style="width:100%; position:relative;height:130px; background:url('.$route.$foto.') no-repeat center center; background-size:cover; margin-bottom:20px; margin-top:-28px;box-shadow: 1px 1px 5px 0px rgba(168,168,168,1);">
<div style="position:absolute; top:0px; left:0px; width:100%; height:100%; z-index:1; background:#333; opacity:0.1;"></div>
</div>*/

$testo.='

<input type="hidden" value="'.$IDpren.'" id="IDprenfunc">


<div class="row no-gutter" style="background:#fff; height:25px; margin-top:-30px; padding:5px;">
    <div class="col-33" onclick="navigation2(7,0,0,0)" style="text-align:center; color:#222; line-height:25px; font-size:13px; font-weight:600;"><i class="f7-icons" style="font-size:10px;color:#333;">info</i> Numeri Utili</div>
    <div class="col-33" style="text-align:center; color:#222; border-left:solid 1px #ccc; line-height:25px; font-size:13px; font-weight:600;" onclick="location.href='."' https://maps.google.com/?q=".$lat.",".$lon."  '".'"><i class="material-icons" style="font-size:10px;color:#333;">pin_drop</i> Come Arrivare</div>
	<div class="col-33" style="text-align:center; color:#222; border-left:solid 1px #ccc;line-height:25px; font-size:13px; font-weight:600;" onclick="navigation(28,0,0,0);"><i class="f7-icons" style="font-size:10px;color:#333;">help</i> Suggerimenti</div>
</div>





<div style="width:100%; position:relative;height:180px; background:url(../'.$foto.') no-repeat center center; background-size:cover;box-shadow: 1px 1px 5px 0px rgba(168,168,168,1);">

 <div style="position:absolute; top:92px; left:0px;  width:100%;z-index:2;">
 	<div class="row no-gutter" style=" height:60px; padding:5px; font-weight:600; font-size:13px; color:#fff; ">
		 <div class="col-50" style="text-align:center;">'.$checkintxt.'</div>
		 <div class="col-50" style="text-align:center; border-left:solid 1px #fff;">'.$checkouttxt.'</div>
		 <div class="col-100" style="text-align:center;padding-top:5px;"><u style="font-weight:600; font-size:14px; color:#fff;" onclick="modorarioospite()">Modifica Orario di Arrivo</u></div>
		 
	</div>	
 </div>

<div style="position:absolute; bottom:0px; left:0px; width:100%; height:93px; z-index:1; background:#333; opacity:0.6;"></div>
</div>



<div class="row no-gutter" style=" height:45px; padding:15px; margin-top:0px; background:#fff; font-weight:600; font-size:13px; color:#333; ">
		 <div class="col-70" style="font-size:24px;padding:0px; line-height:20px;  font-weight:100;" >'.$nomestr['0'].'<br><span style="font-size:15px;">'.$nomestr['1'].'</span></div>
		 <div class="col-30" align="right" style=" border-left:solid 1px #fff; font-size:20px;">'.round($totale,1).'€<br>
		 <div style="font-size:9px;color:#777; text-align:right; font-weight:400; margin-top:5px;" onclick="navigation(20,0,0,0)">
	  <u>CONTROLLA IL CONTO</u>
	 </div>
		 
		 </div>
	</div>	




';


/*
<table style="width:95%; margin:auto; "><tr><td style="line-height:15px;font-size:15px;color:#2b4795; font-weight:600; text-transform:uppercase;">'.$nomestr.'</td><td align="right" valign="top"><b style="font-size:17px;color:#2b4795; ">'.round($totale,1).' €</b></td></tr></table>



<table style="width:100%; border-top:solid 1px #ccc; background:#fff;border-bottom:solid 1px #ccc;" cellspacing="10"><tr>
<td style="text-align:center;font-weight:300;color:#203baf; font-size:11px; border-right:solid 1px #ccc;" width="52%">'.$checkintxt.'</td>
<td style="text-align:center;font-weight:300;color:#203baf;font-size:11px; " width="50%">'.$checkouttxt.'</td>

</tr></table>*/	





//elenco servizi

//$testo.='<div style="width:100%; text-align:center; margin-top:20px;">';


/* controllo autoconf lo stato attivo se 1  vedo conferma plus 
vedo stato pren */

$statopren='';
$testo.='<input type="hidden" val="'.$stato.'">';
$query2="SELECT ID,attivo FROM autoconf WHERE IDstr='$IDstr' LIMIT 1";//se presente il pulsante compare
$result2=mysqli_query($link2,$query2);
if(mysqli_num_rows($result2)>0){
	$row2=mysqli_fetch_row($result2);
	$check='';
	$IDauto=$row2['0'];
	if($row2['1']==1){//autoconferma c'è
		switch($stato){
			case 0:
			case 1:
					$statopren=1;
					$query="SELECT  t.pagamento,p.tipopag FROM pagonline as p,tipopag as t WHERE p.tipopag IN(2,3) AND p.IDstr='$IDstr' AND p.tipopag=t.ID";
						$result=mysqli_query($link2,$query);
						$row=mysqli_fetch_row($result);
						$idp=$row['1'];
						$nomep=$row['0'];
						$infop=' buttons.push({
						text: "'.$nomep.'",
						onClick: function () {
							navigation2(0,'."'2'".',0);}
						}); ';

						$testo.='<input type="hidden" value="'.base64_encode($infop).'" id="infop" >';

					//stato 1 da confermare con conto
			break;	
			
			case 2:
			case 5:
					$statopren=2;//stato 2  confermata	
			break;
			
			case 6://stato 3 da confermare 
					$query2="SELECT ID FROM confermaplus WHERE IDstr='$IDstr' AND IDpren='$IDpren' LIMIT 1";
					$result2=mysqli_query($link2,$query2);
					if(mysqli_num_rows($result2)>0){
						$statopren=2;	
					}else{
						$statopren=3;
					}
			break;
			
			default:
					$statopren=2;//stato 2 confermato	
			break;	
		}
	}else{
		$statopren=2;
	}
			
}else{
	$statopren=2;
}

		switch($statopren){
			case 1:
					$func='metodopag()';
					$colore='#b0294c';
					$cont=' Conferma Prenotazione</button><div style="font-size:11px;color:#777; text-align:center; margin-top:5px;">
						Clicca e scegli il metodo preferito per confermare </div>';
				
			break;
			
			case 2:
					$func='';
					$colore='#3cb878';
					$cont='<i class="f7-icons" style="font-size:15px;">check</i> Prenotazione Confermata</button>';
				
			break;
			case 3:
					$func='prenconferma('.$IDpren.');';
					$colore='#203a93';
					$cont='Conferma Prenotazione</button>';
			break;	
		}
	
			
		$testo.='<button onclick="'.$func.'" class=" button button-fill button-raised" style="width:90%; background:'.$colore.'; height:35px; margin:auto;">'.$cont.'<br/><br/>';
/*
$query="SELECT ID FROM carte WHERE IDpren='$IDpren' LIMIT 1";
$result=mysqli_query($link2,$query);
$numcarte=mysqli_num_rows($result);	

if(($stato<2)&&($numcarte==0)){
/*	
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
	//<p><a href="#" onclick="profiloclienti(0);" class="button button-fill color-red button-round">Conferma la tua prenotazione</a></p>
	
	$testo.='</ul></div>';
	
	$query="SELECT  t.pagamento,p.tipopag FROM pagonline as p,tipopag as t WHERE p.tipopag IN(2,3) AND p.IDstr='$IDstr' AND p.tipopag=t.ID";
	$result=mysqli_query($link2,$query);
	$row=mysqli_fetch_row($result);
$idp=$row['1'];
$nomep=$row['0'];
$infop=' buttons.push({
		text: "'.$nomep.'",
		onClick: function () {
			navigation2(0,'."'2'".',0);}
		}); ';

$testo.='<input type="hidden" value="'.base64_encode($infop).'" id="infop" >';
	
	
	
	$testo.='
	
	 <button class=" button button-fill button-raised" style="width:90%; height:35px; background:#b0294c; margin:auto;" onclick="metodopag()"> Conferma Prenotazione</button><div style="font-size:11px;color:#777; text-align:center; margin-top:5px;">
	 Clicca e scegli il metodo preferito per confermare
	 </div><br/><br/>
	';
}else{
	
	$testo.='<button class=" button button-fill button-raised" style="width:90%; background:#3cb878; height:35px; margin:auto;"><i class="f7-icons" style="font-size:15px;">check</i> Prenotazione Confermata</button><br/><br/>';
}
*/

//foto navigation(29,0,0,0)
//contatti navigation(28,0,0,0)
$testo.='
<br/>

<div class="row no-gutter" style="background:#fff; border-top:solid 1px #e1e1e1; border-bottom:solid 1px #e1e1e1; height:35px; margin-top:-30px; padding:5px; ">
    <div class="col-33" onclick="navigation2(1,0,0,0);" style="text-align:center; color:#222; line-height:17px; font-size:13px; font-weight:600;"><i class="f7-icons" style="font-size:18px;color:#333;">camera_fill</i><br>Foto</div>
    <div class="col-33" onclick="navigation(30,0,0,0);" style="text-align:center; color:#222; border-left:solid 1px #ccc; line-height:17px; font-size:13px; font-weight:600;" onclick="location.href='."' https://maps.google.com/?q=".$lat.",".$lon."  '".'"><i class="f7-icons" style="font-size:18px;color:#333;">star_fill</i><br>Recensioni</div>
	<div class="col-33" style="text-align:center; color:#222; border-left:solid 1px #ccc;line-height:17px; font-size:13px; font-weight:600;" onclick="navigation2(8,0,0,0);"><i class="f7-icons" style="font-size:18px;color:#333;">phone_fill</i><br>Contatti</div>
</div>
';
//onclick="navigation(16,0,0,0)"

$testo.='<br/><div style="float:right; font-size:12px; color:#666; margin-right:10px; margin-top:10px;" onclick="navigation2(6,0,0,0)"><u>Apri Elenco e Modifica</u></div>


<div style="font-size:14px; color:#e4492b;  padding:5px; font-weight:600; ">Promemoria Servizi</div>
<div class="overmano" style="height:85px">
	<div class="swiper-wrapper" style="width:calc(100% + 10%)">
      ';



$query="SELECT p.ID,p.time,s.servizio,SUM(p2.qta),p.tipolim FROM prenextra as p,prenextra2 as p2,servizi as s WHERE  p2.IDpren IN ($IDprenc) AND p.ID=p2.IDprenextra AND p2.paga>'0' AND p.extra=s.ID AND p.tipolim IN(1,2,6) AND p.IDtipo NOT IN(10,15,16,17) AND p.time>='$timeadesso'  GROUP BY p.sottotip ORDER BY p.time";
	$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
		while($row=mysqli_fetch_row($result)){
		
		
			$ID=$row['0'];
			$time=$row['1'];
			$servizio=$row['2'];
			$tipolim=$row['4'];
			$txtinto='';
			$colorinto='';
			switch($tipolim){
				default:
					$txtinto='N.' .$row['3'].' '.$servizio;
				break;
				case 2:
					$txtinto=$servizio.' per '.$row['3'].' '.txtpersone($row['3']);
				break;
			
				
			}
		
			$testo.='<div class="scrollmano" style="position:relative;height:80px">
			
			
			<div class="slidecol1" style="height:16px; float:left; line-height:18px; width:38px; ">'.date('H:i',$time).'</div>
			<div  style=" float:left;margin-left:30px;"><i class="f7-icons" style="font-size:13px; color:#333;">compose</i></div><br>
			
			<div class="slidepro" >'.$txtinto.'</div></div>';
			
			//$testo.='<div class="swiper-slide"  style="background:#3cb878; padding:5px; height:50px; border-radius:3px; color:#fff; font-size:12px;"><div class="">'.date('H:i',$time).'</div><div >'.$txtinto.'</div></div>';
			
			
			
		}
	}else{
		$testo.='<div class="scrollmano" style="position:relative;height:80px">
			
			
			<div class="slidecol1" style="height:16px; float:left; line-height:18px; width:38px; ">--.--</div>
			<div  style=" float:left;margin-left:30px;"><i class="f7-icons" style="font-size:13px; color:#333;">compose</i></div><br>
			
			<div class="slidepro">Non hai eventi in Programma</div></div>';
	}



$testo.='
  	</div>
</div>
<br/>';


$query="SELECT ID,IDcliente FROM infopren WHERE IDstr='$IDstr' AND IDpren='$IDpren' LIMIT 1";
					$result=mysqli_query($link2,$query);
					if(mysqli_num_rows($result)>0){
						$row=mysqli_fetch_row($result);
						$ID=$row['0'];
						$IDcliente=$row['1'];
					}	

$testo.='
<div class="row no-gutter" style="background:#fff; border-top:solid 1px #e1e1e1; border-bottom:solid 1px #e1e1e1; height:50px;padding:5px; ">


aaa


	<div class="col-100" style="text-align:center; color:#a91919; line-height:15px; font-size:14px; font-weight:600;" onclick="navigation2(10,'."'".$IDcliente.",".$ID."'".',0,0)"> Check-in Online<div style="font-size:11px;color:#666;text-align:center;margin-top:3px;font-weight:500">
						Risparmia il tempo all arrivo</div>
	</div>
</div>';


//se soggiorno iniziato vedi temp senno' modifichi solo



$query="SELECT temp,domotraff,domotrisc FROM appartamenti WHERE ID='$IDapp' LIMIT 1";
$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
	$row=mysqli_fetch_row($result);
	$temp=$row['0'];
	$domotrisc=$row['1'];
	$domotraff=$row['2'];

	$txttemp=array();
		
	$tempoora=time();
   if($tempoora>$time){
			//sono arrivato
				if(($domotraff!=0)||($domotrisc!=0)){
					$txttemp['0'].=' onclick="navigation(17,0,0,0);" style="text-align:center; color:#222; line-height:17px; font-size:13px; font-weight:600;"><strong style="color:#a42727;">'.$temp.' &deg;</strong><br>Imposta Temperatura Alloggio</div>';
				}	   
	}else{
			   if(($domotraff!=0)||($domotrisc!=0)){
					$txttemp['0'].=' onclick="navigation(17,0,0,0);" style="text-align:center; color:#222; line-height:17px; font-size:13px; font-weight:600;">Imposta Temperatura Alloggio<br>Per il tuo arrivo</div>';
				}
		}
	
		
		
		
}
$query="SELECT GROUP_CONCAT(ID SEPARATOR ',') FROM sottotipologie WHERE IDstr='$IDstruttura' GROUP BY IDstr";
$result=mysqli_query($link2,$query);
if(mysqli_num_rows($result)>0){
	$row=mysqli_fetch_row($result);
	$IDsottotipgroup=$row['0'];

	$query="SELECT ID FROM dispgiorno WHERE IDsottotip IN($IDsottotipgroup) AND  FROM_UNIXTIME(time,'%Y-%m-%d')='$datagg' LIMIT 1";
	$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
		$txttemp['1'].='
			 onclick="navigation(30,0,0,0);" style="text-align:center; color:#222; border-left:solid 1px #ccc; line-height:17px; font-size:13px; font-weight:600;" onclick="location.href='."' https://maps.google.com/?q=".$lat.",".$lon."  '".'"><i class="f7-icons" style="font-size:18px;color:#333;">favorites_fill
		</i><br>Menu Ristorante</div>';
	}
}


	if(!empty($txttemp)){
		$testo.='<br/>
	<div class="row no-gutter" style="background:#fff; border-top:solid 1px #e1e1e1; border-bottom:solid 1px #e1e1e1; height:35px;  padding:5px; ">';
		$num=count($txttemp);
		foreach($txttemp as $dato){
			$testo.='<div class="col-'.(100/$num).'"'.$dato;
		}


		$testo.='</div>';
	}

$query="SELECT s.ID,s.servizio FROM servizi as s WHERE s.IDstruttura='$IDstr' AND s.IDtipo='1' LIMIT 1";
	$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
		$servpres=1;//se servizio presente stampo la scelta del servizio
	}else{$servpres=2;}//sennò non stampo nulla




if($servpres==1){
//navigation(19,0,0,0)

$testo.='<br/><br/><div style="float:right; font-size:12px; color:#666; margin-right:10px; margin-top:10px;" onclick="navigation2(5,0,0,0)" ><u>Scopri tutti i Servizi</u></div>

<div style="font-size:14px; color:#e13566;  padding:5px; font-weight:600;">Personalizza il Soggiorno<br>
<span style="font-size:12px; font-weight:400; color:#777;" >Clicca e Prenota il Servizio</span>

</div>';

/*
  <div class="swiper-container swiper-4" style="height:125px; border-top:solid 1px #e1e1e1;border-bottom:solid 1px #e1e1e1; padding-top:10px; background:#fff;" >
    <div class="swiper-wrapper">
      */
$testo.='<div class="overmano">
	<div class="swiper-wrapper" style="width:calc(100% + 550px)">';

$query="SELECT s.ID,s.servizio FROM servizi as s WHERE s.IDstruttura='$IDstr' AND s.IDtipo='1' LIMIT 6";
	$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
		while($row=mysqli_fetch_row($result)){
			
			//controllo in base ad orario piu' prossimo
			//controllo presenza servizio in prenotazione (ad esempio se le pulizi ce l'ha allora niente)
			
			
		
			$IDserv=$row['0'];
			$servizio=$row['1'];
			$txtinto='';
			
			$foto=getfoto($IDserv,4);
			$prezzo=calcolaprezzoserv($IDserv,$datagg,$IDrestrtxt,$IDstr,0,$IDpren);
			
			
			/*navigation(26,'.$IDserv.',0,0);
			
			$testo.='<div class="swiper-slide" onclick="navigation2(4,'.$IDserv.',2,0);" style="background:url(../immagini/'.$foto.') no-repeat center center; position:relative; background-size:cover; padding:5px; height:105px; border-radius:3px; overflow:hidden; color:#fff; font-size:12px;">
			
			
			<div style="position:absolute;height:25px; width:40%; right:0px; top:0px; background:#333; opacity:0.4;"></div>
			
			<div style="position:absolute;height:55px;width:40%; right:0px; text-align:center; padding:2px;font-size:12px;  top:0px;">
			<strong><div style="font-size:18px;">'.$prezzo.' €</div></strong></div>
			
			
			<div style="position:absolute; bottom:0px; left:0px; width:calc(100%); font-weight:100; padding:5px; height:30px; background:#e1359b;  background:#e13566; color:#fff; "><strong style="font-size:13px;">'.$servizio.'</strong><br><span style="font-size:10px;">per 2 Persone</span></div>
		</div>'; */
			$testo.='<div onclick="navigation(26,'.$IDserv.',0,0);" class="scrollmano" style="background:url(../immagini/'.$foto.') center center / cover no-repeat;">
					<div style="position:absolute;height:25px; width:40%; right:0px; top:0px; background:#333; opacity:0.4;border-top-right-radius: 5px;"></div>
			
						<div style="position:absolute;height:55px;width:40%; right:0px; text-align:center; padding:2px;font-size:12px;  top:0px;">
						<strong><div style="font-size:18px;">'.$prezzo.' €</div></strong></div>
					<div style="position:absolute; bottom:0px; left:0px; width:calc(100%); font-weight:100;height:40px;background:#e13566; color:#fff;border-bottom-left-radius:5px;border-bottom-right-radius:5px">
						<div style="font-size:14px;padding-left:5px;padding-top:5px"><strong>'.$servizio.'</strong></div>
					</div>
		</div>';
			
			
			
		}
	}



$testo.='
  	</div>
</div>
';

}

$queryluog="SELECT ID,TRUNCATE ( 6363 * sqrt( POW( RADIANS('".$lat."') - RADIANS(latitude) , 2 ) + POW( RADIANS('".$lon."') - RADIANS(longitude) , 2 ) ) , 3 ) as distance FROM luoghieventi WHERE TRUNCATE ( 6363 * sqrt( POW( RADIANS('".$lat."') - RADIANS(latitude) , 2 ) + POW( RADIANS('".$lon."') - RADIANS(longitude) , 2 ) ) , 3 )<30 ORDER BY distance LIMIT 1";

$resultluog=mysqli_query($link2,$queryluog);
	if(mysqli_num_rows($resultluog)>0){
		$luogpres=1;//se servizio presente stampo la scelta del servizio
	}else{$luogpres=2;}//sennò non stampo nulla

$luogpres=2;//non stampo i luoghi, un altra volta li stampo
if($luogpres==1){
//navigation(21,0,0,0);
$testo.='<br/><br/><div style="float:right; font-size:12px; color:#666; margin-right:10px; margin-top:10px;" onclick="navigation2(3,0,0,0);"><u>Scopri i Luoghi ed Eventi</u></div>

<div style="font-size:14px; color:#a79254; font-weight:400; padding:5px; font-weight:400;">Rendi Unico il Soggiorno</div>

  <div class="overmano">
	<div class="swiper-wrapper"  style="width:calc(100% + 160px)">
      ';

$query="SELECT ID,nome,dove,descriz,latitude,longitude,TRUNCATE ( 6363 * sqrt( POW( RADIANS('".$lat."') - RADIANS(latitude) , 2 ) + POW( RADIANS('".$lon."') - RADIANS(longitude) , 2 ) ) , 3 ) as distance FROM luoghieventi WHERE TRUNCATE ( 6363 * sqrt( POW( RADIANS('".$lat."') - RADIANS(latitude) , 2 ) + POW( RADIANS('".$lon."') - RADIANS(longitude) , 2 ) ) , 3 )<30 ORDER BY distance LIMIT 3";
	$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
		while($row=mysqli_fetch_row($result)){
		
		
			$ID=$row['0'];
			$nome=$row['1'];
			$dove=$row['2'];
			$descriz=$row['3'];
			$km=round($row['6'],1);
			
			$foto=getfoto($ID,4);
			
			$testo.='<div onclick="navigation(33,'.$ID.',0,0);" class="scrollmano" style="background:url(../immagini/scavi.jpg) center center / cover no-repeat;">
			
			
			<div style="position:absolute;height:25px; width:40%; right:0px; top:0px; background:#333; opacity:0.4;"></div>
			
			<div style="position:absolute;height:55px;width:40%; right:0px; text-align:center; padding:2px;font-size:12px;  top:0px;">
			<strong><div style="font-size:18px;">'.$km.' km</div></strong></div>
			
			<div style="position:absolute; bottom:0px; left:0px; width:calc(100%); font-weight:100;height:40px;background:#ffde7d;color:#000;border-bottom-left-radius:5px;border-bottom-right-radius:5px">
						<div style="font-size:13px;padding-left:5px;padding-top:5px"><strong>'.$nome.'</strong><br><span style="font-size:12px;">'.$dove.'</span></div>
					</div>
			</div>';		
		}
	}



$testo.='
  	</div>
</div>
';
}

/*


<a href="#" style="color:black" onclick="profiloclienti(1);">
<div style="width:100%; position:relative;height:130px; background:url('.$route.$foto.') no-repeat center center; background-size:cover; margin-bottom:20px; box-shadow: 1px 1px 5px 0px rgba(168,168,168,1);">
<div style="position:absolute; bottom:0px; left:0px;background-color:white; width:100%;z-index:1;">Scopri la tua prenotazione</div></div></a>

<a href="#" style="color:black" onclick="profiloclienti(2);">
<div style="width:100%; position:relative;height:130px; background:url('.$route.$foto.') no-repeat center center; background-size:cover; margin-bottom:20px; box-shadow: 1px 1px 5px 0px rgba(168,168,168,1);">
<div style="position:absolute; bottom:0px; left:0px;background-color:white; width:100%;z-index:1;">Personalizza la tua vacanza</div></div></a>

<a href="#" style="color:black" onclick="profiloclienti(3);">
<div style="width:100%; position:relative;height:130px; background:url('.$route.$foto.') no-repeat center center; background-size:cover; margin-bottom:20px; box-shadow: 1px 1px 5px 0px rgba(168,168,168,1);">
<div style="position:absolute; bottom:0px; left:0px;background-color:white; width:100%;z-index:1;">Scopri il territorio</div></div></a>


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
*/
/*
$testo.='<div class="content-block-title" style="color:#dc2774; margin-left:0px; margin-top:-20px;font-size:12px; font-weight:600; ">

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
*/

/*$testo.='<div class="content-block-title" style="color:#e69015; margin-left:0px; margin-top:-20px; font-size:12px; font-weight:600;">

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
	<ul></div>';

*/

$query="SELECT nome,suggerimenti,latitude,longitude,mail,sito,dove,tipologia,tel FROM strutture WHERE ID='$IDstr' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);


$testo.='<br/><div style="width:90%; text-align:left; padding-left:15px;" id="contatti"><br>
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




';

if(!isset($inc)){
echo $testo;
}




?>