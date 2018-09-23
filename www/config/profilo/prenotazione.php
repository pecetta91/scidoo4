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
$IDstruttura=$IDstr;
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


$query="SELECT GROUP_CONCAT(IDrest SEPARATOR ','),COUNT(IDrest) FROM infopren WHERE IDpren ='$IDpren' AND pers='1'";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDrestrtxt=$row['0'].',';
$numeropers=$row['1'];




	$arrideesi=array();

	$query="SELECT p.ID FROM prenextra as p,prenextra2 as p2 WHERE p.IDpren IN($IDprenc) AND p.IDstruttura='$IDstr' AND p.tipolim='7' AND p.modi>='0' AND p.ID=p2.IDprenextra AND p2.prezzo>='0' GROUP BY p.extra HAVING SUM(p2.prezzo)>='0'
	";
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
	if(!empty($arrideesi)){
		$txtin=implode(',',$arrideesi);
		$qadd4=" AND IDprenextra NOT IN($txtin)";
	}

	





$query="SELECT SUM(prezzo) FROM prenextra2 WHERE IDpren IN($IDprenc) $qadd4";


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
$checkintxt='<span class="infocheckpren">Check-in</span><br><span class="infocheckprentxt">
	'.date('d',$time).' '.strtoupper($mesiita2[date('n',$time)]).'</span><br>'.$giorniita[date('w',$time)].' '.$oracheckin;

//$checkouttxt='<span style=" color:#888; ">Check-out</span><br><span style="font-size:17px; font-weight:300;">

if($gg>0){
	$checkouttxt='<span class="infocheckpren">Check-out</span><br><span class="infocheckprentxt">
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
		
		
		if (!file_exists($route.'immagini/big'.$foto)) {echo 'bbb';
			$query="SELECT extra FROM prenextra  WHERE IDpren='$IDpren'";
			$result=mysqli_query($link2,$query);
			while($row=mysqli_fetch_row($result)){
				$IDserv=$row['0'];
				$foto=getfoto($IDserv,4,0,1);
				if (file_exists($route.'immagini/big'.$foto)) {
					break;
				}
			}
		}
		
	}else{
		
		$query="SELECT extra FROM prenextra WHERE IDpren='$IDpren' ";
		$result=mysqli_query($link2,$query);
		while($row=mysqli_fetch_row($result)){
			$IDserv=$row['0'];
			$foto=getfoto($IDserv,4,0,1);
			if (file_exists($route.'immagini/big'.$foto)) {
				break;
			}
		}
	}
	
}
$foto='immagini/big'.$foto;

/*<div style="width:100%; position:relative;height:130px; background:url('.$route.$foto.') no-repeat center center; background-size:cover; margin-bottom:20px; margin-top:-28px;box-shadow: 1px 1px 5px 0px rgba(168,168,168,1);">
<div style="position:absolute; top:0px; left:0px; width:100%; height:100%; z-index:1; background:#333; opacity:0.1;"></div>
</div>*/
//<u class="infoprenmodora" onclick="modorarioospite()">Modifica Orario di Arrivo</u></div>
$testo.='

<input type="hidden" value="'.$IDpren.'" id="IDprenfunc">



<div class="row no-gutter rigapren" style="margin-top:-70px;">
    <div class="col-33 colrigapren" onclick="navigation2(7,0,0,0)"><i class="f7-icons iconarigapren" >info</i><br/>Numeri Utili</div>
    <div class="col-33 colrigapren"  onclick="location.href='."' https://maps.google.com/?q=".$lat.",".$lon."  '".'"><i class="material-icons iconarigapren" >pin_drop</i><br/>Come Arrivare</div>
	<div class="col-33 colrigapren" onclick="navigation(28,0,0,0);"><i class="f7-icons iconarigapren">help</i><br/>Consigli</div>
</div>





<div class="fotoprenotazione" style="background:url('.$route.$foto.') no-repeat center center;height:240px">
<div style="position:absolute;top:0;right:0;"><button class="button button-fill button-raised" style="font-size:15px;margin:7px 7px;border:1px #fff solid;background-color:transparent;border-radius:3px;height:32px;width:90px" onclick="navigation2(1,0,0,0);"><i class="f7-icons fs14">camera_fill</i> Foto</button></div>

 <div class="infoprenotazione" style="top:165px">
 	<div class="row no-gutter divinfopren">
		 <div class="col-50 colinfopren1" >'.$checkintxt.'</div>
		 <div class="col-50 colinfopren2" >'.$checkouttxt.'</div>';

	if(($timearr>$timeadesso)||(($stato!=4)&&($stato!=3))){
	$testo.='<div class="col-100 colinfopren3" style="margin-top:-115px;">
		 	<button class="button button-fill button-raised" style="border:1px #ccc solid;background-color:#fff; color:#333;border-radius:5px;margin:5px;line-height:3px;
			
			box-shadow: 0 2px 8px 0 rgba(0, 0, 0, 0.2), 0 4px 20px 0 rgba(0, 0, 0, 0.19);

			
			" onclick="modorarioospite()">Indica Orario di Arrivo</button>
		</div>';
	}
		 
$testo.='</div>	
 </div>

<div class="overlaypren" style="height:80px"></div>
</div>



<div class="row no-gutter rigaprenotazioneinfo" style="margin-top:5px">
		
		 <div class="col-70 colprenotazioneinfo1" >'.$nomestr['0'].'<br><span class="fs15">'.$nomestr['1'].'</span></div>
		 <div class="col-30 colprenotazioneinfo2" align="right"  onclick="navigation(20,0,0,0)"><div style="font-size:10px;margin-top:-10px; margin-bottom:3px;">Totale Soggiorno</div>'.round($totale,1).'€<br>
		 <div class="controllaconto">
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
						$button='<div>'.$nomep.'</div>';
				
						$infop=' buttons.push({
						text: '."'".$button."'".',
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
					$testo.='<div class="testometodo">Clicca e scegli il metodo preferito per confermare </div>';
					$cont='Conferma Prenotazione';
				
			break;
			
			case 2:
					$func='';
					$colore='#3cb878';
					$cont='<i class="f7-icons fs15" >check</i> Prenotazione Confermata';
				
			break;
			case 3:
					$func='prenconferma('.$IDpren.');';
					$colore='#203a93';
					$cont='Conferma Prenotazione';
			break;	
		}
	
		if(time()>($checkout+43200)){
			$testo.='<button onclick="navigation(32,0,0,0);" class=" button button-fill button-raised btnpagamento" style="background:#d6a50b;">SCRIVICI UNA RECENSIONE</button><br/><br/>';
		}else{
			$testo.='<button onclick="'.$func.'" class=" button button-fill button-raised btnpagamento" style="background:'.$colore.';	">'.$cont.'</button><br/><br/>';	
		}

		


//foto navigation(29,0,0,0)
//contatti navigation(28,0,0,0)
//<div class="col-33 colrigaservizi" onclick="navigation2(1,0,0,0);"><i class="f7-icons iconarigapren fs18">camera_fill</i><br>Foto</div>
$testo.='
<br/>

<div class="row no-gutter rigaservizi">
    <div class="col-50 colrigaservizi" onclick="navigation(30,0,0,0);"><i class="f7-icons iconarigapren fs18">star_fill</i><br>Recensioni</div>
	<div class="col-50 colrigaservizi"  onclick="navigation2(8,0,0,0);"><i class="f7-icons iconarigapren fs18">phone_fill</i><br>Contatti</div>
</div>
';
//onclick="navigation(16,0,0,0)"
//<div class="modificaelenco" onclick="navigation2(6,0,0,0)"><u>Apri Elenco e Modifica</u></div>
		

if(($timearr>=$timeadesso)&&($gg!=0)){
	$query="SELECT ID,IDcliente FROM infopren WHERE IDstr='$IDstr' AND IDpren='$IDpren' LIMIT 1";
	$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
		$row=mysqli_fetch_row($result);
		$IDriga=$row['0'];
		$IDcliente=$row['1'];
	}	
		/*
		if($IDriga==''){
			$IDriga='0';
		}
		*/
	$testo.='<input type="hidden" value="'.$IDpren.'">
	<br>
	<div class="titleb checktitolo" >'."Risparmia tempo all'arrivo ".'</div>
	<button onclick="navigation2(10,'."'".$IDcliente.",".$IDriga."'".',5,0)" class=" button button-fill button-raised btnpagamento">Check-in Online</button>
	
	
	<br><br/><hr>
	';	
}



if($timeadesso>=$time){
	$oracontrollo=$timeadesso;
}else{
	$oracontrollo=$time;
}

$testo.='<br/>

<div class="row">
<div class="col-65">

	<div class="promemoriaserv">Promemoria Servizi
	<br/>
	<span style="font-size:11px; color:#666;">Prossime 48h</span>

	</div>
</div>
<div class="col-35">


	<button onclick="navigation2(12,'.$oracontrollo.',3,0)"  class=" button button-fill button-raised btnprenotasosp">Elenco Servizi</button>
</div>

</div>




<div class="overmano h95">
	<div class="swiper-wrapper " style="width:calc(100% + 10%)">
      ';



$oracontrollo2=$oracontrollo+2*86400;

$gstart=0;

$query="SELECT p.ID,p.time,s.servizio,SUM(p2.qta),p.tipolim,p.modi,p.extra FROM prenextra as p,prenextra2 as p2,servizi as s WHERE  p2.IDpren IN ($IDprenc) AND p.ID=p2.IDprenextra AND p2.paga>'0' AND p.extra=s.ID AND p.tipolim IN(1,2,6) AND p.IDtipo NOT IN(10,15,16,17) AND p.time>='$oracontrollo' AND p.time<'$oracontrollo2'  GROUP BY p.ID ORDER BY p.time";
	$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
		while($row=mysqli_fetch_row($result)){
		
		
			$ID=$row['0'];
			$time=$row['1'];
			$servizio=$row['2'];
			$tipolim=$row['4'];
			$IDservizio=$row['6'];
			
			
			$giorno=date('d',$time);
			if($giorno!=$gstart){
				$gstart=$giorno;
				
				$testo.='<div class="scrollmano h80 c333" style="width:50px; border-left:solid 1px #ccc; border-radius:0px;" >
				<div class="slidepro fs14 fw400" >
				<strong style="font-size:25px;">'.$giorno.' <i class="icon f7-icons fs14 c333">arrow_right</i></strong><br/>
				'.$mesiita[date('n',$time)].'
				</div></div>';
						   
				
			}
			
			
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
			
			if($row['5']==0){
				$orarioin='--.--';
			}else{
				$orarioin=date('H:i',$time);
			}
			
			
			
		
			$testo.='<div class="scrollmano h80 c333" onclick="navigation(25,'.$ID.',0,0)">
			
			
			<div class=" infoslidecol2" >'.$orarioin.'</div>
			<div  class="floatleft ml30 txtmod">Modifica</div><br>
			
			<div class="slidepro fs13 fw400" >'.$txtinto.'</div></div>';
			
			//$testo.='<div class="swiper-slide"  style="background:#3cb878; padding:5px; height:50px; border-radius:3px; color:#fff; font-size:12px;"><div class="">'.date('H:i',$time).'</div><div >'.$txtinto.'</div></div>';
			
			
			
		}
	}else{
		$testo.='<div class="scrollmano h80 c333" onclick="navigation2(12,'.$oracontrollo.',3,0);">
			
			
			<div class=" infoslidecol2 " >--.--</div>
			
			<div class="slidepro fs13 fw400">Non hai eventi in Programma</div>
			</div>';
	}



$testo.='
  	</div>
</div>
<br/>';

/*
$query="SELECT ID FROM prenextra WHERE  IDpren IN ($IDprenc) AND tipolim IN(1,2) AND modi='0'";
$result=mysqli_query($link2,$query);
if(mysqli_num_rows($result)>0){
	
	$testo.='
	<br>
	
	<div class="titleb checktitolo" style="color:#d92542;" >'."Alcuni orari dei tui servizi non sono prenotati".'<br/>
	<span>'."Prenotando anticipatamente gli orari dei tuoi servizi potrai garantirti l'orario migliore per ricevere il servizo e rendere il tuo soggiorno".'</span>
	</div>
	
	<br/><br/><hr><br/>
	';
	
	
}
*/









//se soggiorno iniziato vedi temp senno' modifichi solo




$query="SELECT temp,domotraff,domotrisc FROM appartamenti WHERE ID='$IDapp' LIMIT 1";
$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
	$row=mysqli_fetch_row($result);
	$temp=$row['0'];
	$domotrisc=$row['1'];
	$domotraff=$row['2'];

	$txttemp=array();
		

   if($timeadesso>$timearr){
			//sono arrivato
				if(($domotraff!=0)||($domotrisc!=0)){
					
					if($temp==0){
						$temp='Temperatura non modificabile telematicamente';
					}
					
					$txttemp['0'].='infodomotica" onclick="navigation(17,0,0,0);" ><strong style="color:#a42727;">'.$temp.' &deg;</strong><br>Imposta Temperatura Alloggio</div>';
				}	   
	}else{
			   if(($domotraff!=0)||($domotrisc!=0)){
					$txttemp['0'].='infodomotica" onclick="navigation(17,0,0,0);" >Imposta Temperatura Alloggio<br/>per il tuo Arrivo</div>';
				}
		}
	
		
		
		
}/*
$query="SELECT GROUP_CONCAT(ID SEPARATOR ',') FROM sottotipologie WHERE IDstr='$IDstr' AND IDmain='1' GROUP BY IDstr";
$result=mysqli_query($link2,$query);
//echo $query;
if(mysqli_num_rows($result)>0){
	$row=mysqli_fetch_row($result);
	$IDsottotipgroup=$row['0'];
//onclick="location.href='."' https://maps.google.com/?q=".$lat.",".$lon."  '".'"
	//$datagg=date('Y-m-d');
	
	$query="SELECT ID FROM dispgiorno WHERE IDsottotip IN($IDsottotipgroup) AND  FROM_UNIXTIME(data,'%Y-%m-%d')='$datagg' LIMIT 1";
	
	//echo $query;
	
	$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
		$txttemp['1'].=' infodomotica2" onclick="navigation(18,'.$row['0'].',0,0);" ><i class="f7-icons iconarigapren fs18">favorites_fill
		</i><br>Menu Ristorante</div>';
	}
}
*/

	if(!empty($txttemp)){
		$testo.='<br/>
	<div class="row no-gutter menurist" >';
		$num=count($txttemp);
		foreach($txttemp as $dato){
			$testo.='<div class="col-'.(100/$num).'   '.$dato;
		}


		$testo.='</div>';
	}

/*
$testo.='<div class="content-block-title titleb" style="padding-top:10px">Menu Ristorante</div>';
$query2="SELECT ID,sottotipologia FROM sottotipologie WHERE IDmain='1' AND ID NOT IN(17,20) AND IDstr='$IDstruttura' ORDER BY ord";			

$result2=mysqli_query($link2,$query2);
$nummenu=0;
						if(mysqli_num_rows($result2)>0){
							while($row2=mysqli_fetch_row($result2)){
								//controllo
								$sottotip=$row2['0'];
								$query3="SELECT ID FROM dispgiorno WHERE IDsottotip='$sottotip' AND  FROM_UNIXTIME(data,'%Y-%m-%d')='$datagg' LIMIT 1";
								$result3=mysqli_query($link2,$query3);
								if(mysqli_num_rows($result3)>0){
									$nummenu++;//navigation(18,'.$sottotip.',0,0)
										$menutemp[$nummenu].='colrigaservizi" onclick="navigation2(14,'.$sottotip.',3,0)" id="Menu '.$row2['1'].'"><i class="f7-icons iconarigapren fs18">favorites_fill
														</i><br/>'.$row2['1'].'</div>';
								}
								
							}
						} 

						if($nummenu==0){
							$testo.='<div class="row rowlist no-gutter" style="border-top: solid 1px #e1e1e1;border-bottom: solid 1px #e1e1e1;">
											<div class="col-100 " style="font-size:13px;text-align:center;font-weight:600;line-height:17px;">Non &egrave; stato pubblicato nessun menù</div>
									</div>';
						}else{
							
							$testo.='<div class="row no-gutter menurist " >';
							foreach($menutemp as $dato){
								$testo.='<div class="col-'.round(100/$nummenu).'   '.$dato;
							}
							$testo.='</div>';
						}

*/




	$query="SELECT s.ID,s.servizio FROM servizi as s WHERE s.IDstruttura='$IDstr' AND s.IDtipo='1' LIMIT 1";
	$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
		$servpres=1;//se servizio presente stampo la scelta del servizio
	}else{$servpres=2;}//sennò non stampo nulla




if($servpres==1){

	


	

	$numero=1;	
	$qadd='';
	$query="SELECT GROUP_CONCAT(sottotip SEPARATOR ',') FROM prenextra WHERE IDpren='$IDpren' GROUP BY IDpren ";
	$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
		$row=mysqli_fetch_row($result);
		$IDsottonot=$row['0'];
		$qadd=" AND s.IDsottotip NOT IN($IDsottonot)";
	}

	$IDrestrmain=getrestrmain($IDstr);


		$query="SELECT s.ID,s.servizio,t.tipolimite,s.descrizione,s.prezzo FROM servizi as s,prenextra as p,extraonline as e,tiposervizio as t WHERE s.IDstruttura='$IDstr'  AND s.attivo='1' AND s.IDtipo<'8' AND s.ID=e.IDserv AND p.extra=s.ID AND t.ID=s.IDtipo $qadd GROUP BY s.ID ORDER BY COUNT(*) DESC LIMIT 10";
		$result=mysqli_query($link2,$query);
		$ok=0;
		if(mysqli_num_rows($result)>0){
			$ok=1;
		}
	
	
		if($ok==1){
			
			$testo.='<br/><br/><div class="modificaelenco" onclick="navigation2(5,-1,0,0);" ><u>Scopri tutti i Servizi</u></div>

			<div class="personalizza">Personalizza il Soggiorno<br>
			<span class="prenotaservizio" >Clicca e Prenota il Servizio</span>

			</div>
		<div class="swiper-container swiperserv swiper-init"  data-pagination=".swiper-pagination" data-space-between="10" data-slides-per-view="auto" data-centered-slides="true" data-loop="true" style="height:380px;background-color:#fff; padding-top:10px;">
							<div class="swiper-pagination"></div>
							<div class="swiper-wrapper">';
			
			
			while($row=mysqli_fetch_row($result)){

				//controllo in base ad orario piu' prossimo
				//controllo presenza servizio in prenotazione (ad esempio se la pulizia c'e'  allora niente)



				$IDserv=$row['0'];
				$servizio=$row['1'];
				$tipolim=$row['2'];
				$desc=$row['3'];
				$prezzoini=$row['4'];
				$txtinto='';
				
				$tipoprezzo='';
				
				$foto=getfoto($IDserv,4);
				switch($tipolim){
					case 1:
						$prezzo=calcolaprezzoserv($IDserv,$datagg,$IDrestrmain.',',$IDstr,0,$IDpren);
						$tipoprezzo='a persona';
					break;
					case 6:
						$prezzo=calcolaprezzoserv($IDserv,$datagg,1,$IDstr,0,$IDpren);
						$tipoprezzo='per prodotto';
					break;
					case 2:
						$prezzo=calcolaprezzoserv($IDserv,$datagg,$IDrestrtxt,$IDstr,0,$IDpren);
						
						if($numeropers==1){
							$tipoprezzo='per '.$numeropers.' persona';
						}else{
							$tipoprezzo='per '.$numeropers.' persone';
						}
						
					break;

				}
						
						/*
							if(!file_exists('../../../immagini/'.$foto)){
								$foto='camera.jpg';
							}*/
				//se lo sconto è presente allora visualizzo sia il prezzo normale che quello scontato
				if($prezzo<$prezzoini){
					$testoprezzo='<div class="sbarraprezzo prezzoverticaleserv prezzoold" >'.$prezzoini.' €
										<div class="stileprezzo">'.$prezzo.' €</div> 
										<div style="position:absolute;right:15px;top:10px"><span class="fs11">'.$tipoprezzo.'</span></div>
							  </div>';
				}else{
					$testoprezzo='<div class="prezzoverticaleserv stileprezzo" >'.$prezzo.' €</div>
						<div style="position:absolute;right:15px;top:10px"><span class="fs11">'.$tipoprezzo.'</span></div>
						';
				}

				$desc= preg_replace("#(<br\s*/?>\s*)+#"," ,",$desc);

				if(strlen($desc)>40){
					$desc=stripslashes(substr($desc,0,40));
				 }

				if($desc==''){
					$desc='<br/>';
				}

				if(($tipolim==2)||($tipolim==6)){
					$func='navigation2(13,'."'".$IDserv.",".$oracontrollo.",1'".',4,0);';// navigation2(4,'.$IDserv.',2,0)
					$pulsante='Prenota Ora';
				}else{
					$func='navigation(26,'.$IDserv.',0,0);';
					$pulsante='Informazioni';
				}
				$orariserv=orariservizio($IDserv);
				$orariserv=preg_replace("/<div\s(.+?)>(.+?)<\/div>/is", "<span>$2</span>   ",$orariserv);



				$testo.='

							  <div class="swiper-slide  pags" >

										<div class="paginaslider">
											<div class="prenotaservscroller">
													<div class="corpoorizzontalescrol">
																<div class="mr15 ml15" >
																		<div class="servnome mr30" >'.$servizio.'</div>
																		'.$testoprezzo.'
																</div>													
														<div class="mt5 mr15 ml15 servora">'.$orariserv.'</div>
														<div class="mr15 ml15 mt10 fs12 fw400" >'.$desc.'</div>
														<button class="button button-fill button-raised prenotaoraoriz" onclick="'.$func.'">'.$pulsante.'</button>
													</div>
											</div>
											<div onclick="navigation(26,'.$IDserv.',0,0);" class="orizontalscroll" style="background:url('.$route.'/immagini/'.$foto.') center center / cover no-repeat;border:1px solid #e1e1e1"></div>
									</div>



							  </div>';
				$numero++;
		

			}
			$testo.='</div></div>';
		}else{
			
			$testo.='<br/><hr/><br/><div class="titleb checktitolo" >Personalizza il tuo Soggiorno</div>
	<button  onclick="navigation2(5,-1,0,0);" class="button button-fill button-raised checkbtn">Scopri i Servizi</button>
		<br/><hr/>
			';
			
		}
}



$testo.='<br/><br/>
<div class="row no-gutter rigaservizi" style="margin-top:0px">
    <div class="col-100 colrigaservizi" onclick="navigation2(1,0,0,0);" ><i class="f7-icons iconarigapren fs18">camera_fill</i><br>Galleria</div>
</div><br/><br/>';


$query="SELECT ID,sottotipologia FROM sottotipologie WHERE IDmain='1'  AND IDstr='$IDstruttura' ORDER BY ord";
$result=mysqli_query($link2,$query);
if(mysqli_num_rows($result2)>0){
	while($row=mysqli_fetch_row($result)){
		$idmenu=$row['0'];	
		$nomemenu=$row['1'];
		$buttonmenu='<div>Menu '.$nomemenu.'</div>';

		$infomenu.=' buttons.push({
		text: '."'".$buttonmenu."'".',
		onClick: function () {
		navigation2(14, '."'".$idmenu."'".',3,0);}
		}); ';
	}
}
$testo.='<input type="hidden" value="'.base64_encode($infomenu).'" id="infomenu" >';

					



$query="SELECT GROUP_CONCAT(ID SEPARATOR ',') FROM sottotipologie WHERE IDmain='1'  AND IDstr='$IDstruttura' ORDER BY ord";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDsotto=$row['0'];
$query2="SELECT ID,IDsottotip FROM dispgiorno WHERE IDsottotip IN ($IDsotto) AND  FROM_UNIXTIME(data,'%Y-%m-%d')='$datagg' ";
$result2=mysqli_query($link2,$query2);
if(mysqli_num_rows($result2)>0){
	
list($yy, $mm, $dd) = explode("-",$datagg);
$timeagg=mktime(0, 0, 0, $mm, $dd, $yy);

$testo.='<br/><br/><div class="modificaelenco" onclick="pulsantimenu();"><u>Scopri tutti i Menu</u></div>
		<div class="personalizza" >Menù del '.dataita4($timeagg).' </div>

<div class="swiper-container swiperserv swiper-init"  data-pagination=".swiper-pagination" data-space-between="10" data-slides-per-view="auto" data-centered-slides="true" data-loop="true" style="height:380px;background-color:#fff; padding-top:10px;">
<div class="swiper-pagination"></div>
<div class="swiper-wrapper">';
	
	
	while($row2=mysqli_fetch_row($result2)){
		
		$sottotip=$row2['1'];
		$query3="SELECT ID,sottotipologia FROM sottotipologie WHERE IDstr='$IDstruttura' AND ID='$sottotip' ";
		$result3=mysqli_query($link2,$query3);
		$row3=mysqli_fetch_row($result3);
		$nomesotto=$row3['1'];
		
		$query4="SELECT GROUP_CONCAT(s.servizio SEPARATOR ' , '),dp.portata,GROUP_CONCAT(DISTINCT(st.sottotipologia) SEPARATOR ' , ') FROM dispgiorno as dp,servizi as s,sottotipologie as st WHERE dp.IDsottotip='$sottotip' AND  FROM_UNIXTIME(dp.data,'%Y-%m-%d')='$datagg' AND dp.IDpiatto=s.ID AND s.IDsottotip=st.ID  GROUP BY dp.portata ORDER BY dp.portata";
				$result4=mysqli_query($link2,$query4);
				if(mysqli_num_rows($result4)>0){
					$txtmenu='';
					$contmenu=0;
					while($row4=mysqli_fetch_row($result4)){
							if($contmenu<=3){
								$txtmenu.='
								<div class="row rowlistt no-gutter">
									<div class="col-100">
										<div style="color:#9c2420; font-size:17px; text-transform:capitalize;">'.strtolower($row4['2']).'</div>
									</div>
									<div class="col-100"><span style=" font-size:14px;color:#333">'.$row4['0'].'</span></div>
								</div>	
								<hr style="width:85%;">';
								$contmenu++;
							}else{
								$txtmenu.='<div style="position:absolute;bottom:6px;right:3px;color:#000"><span>...</span></div>';
							}
					}
					if(strlen($txtmenu)>0){
						$testo.=' 
						<div class="swiper-slide  pags" >
									<div class="textcenter pb5" >
										<div class="servnome">Menù  '.$nomesotto.'</div>
									</div>	
						 <div class="paginaslider">
							<div class="prenotaservscroller">
								<div class="corpoorizzontalescrol" >											
									<button class="button button-fill button-raised prenotaoraoriz" onclick="navigation2(14,'.$sottotip.',3,0)" >Scopri Ora</button>
								</div>
							</div>
								<div class="orizontalscroll" style="border:1px solid #e1e1e1;text-align:center;">
									<div style="padding-left: 10px;padding-top: 10px;padding-right:10px">'.$txtmenu.'</div>
								</div>
						 </div>
						</div>';
					}
				}
	}
	$testo.='</div></div>';
	
}else{
	$testo.='<div  class="personalizza" >Il Menù Non &egrave; stato ancora pubblicato</div>';
}

/*
$testo.='<div class="personalizza">Scopri i luoghi<br></div>
		<div class="swiper-container  swiper-init"  data-speed="400"  data-pagination=".swiper-pagination" data-space-between="10" data-slides-per-view="auto" data-centered-slides="true" data-loop="true" style="height:380px;background-color:#fff; padding-top:10px;">
							<div class="swiper-pagination"></div>
							<div class="swiper-wrapper">';
							while($row=mysqli_fetch_row($result)){
								$testo.='

							  <div class="swiper-slide  pags" >

										<div class="paginaslider">
											<div class="prenotaservscroller">
													<div class="corpoorizzontalescrol">
																<div class="mr15 ml15" >
																		<div class="servnome mr30" >'.$servizio.'</div>
																</div>													
														<div class="mt5 mr15 ml15 servora">'.$orariserv.'</div>
														<div class="mr15 ml15 mt10 fs12 fw400" >'.$desc.'</div>
														<button class="button button-fill button-raised prenotaoraoriz" onclick="'.$func.'">'.$pulsante.'</button>
													</div>
											</div>
											<div onclick="navigation(26,'.$IDserv.',0,0);" class="orizontalscroll" style="background:url('.$route.'/immagini/'.$foto.') center center / cover no-repeat;border:1px solid #e1e1e1"></div>
									</div>
								</div>';
							}
							$testo.='</div></div>';			
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

/*
	$queryluog="SELECT ID,TRUNCATE ( 6363 * sqrt( POW( RADIANS('".$lat."') - RADIANS(latitude) , 2 ) + POW( RADIANS('".$lon."') - RADIANS(longitude) , 2 ) ) , 3 ) as distance FROM luoghieventi WHERE TRUNCATE ( 6363 * sqrt( POW( RADIANS('".$lat."') - RADIANS(latitude) , 2 ) + POW( RADIANS('".$lon."') - RADIANS(longitude) , 2 ) ) , 3 )<30 ORDER BY distance LIMIT 1";

	$resultluog=mysqli_query($link2,$queryluog);
	if(mysqli_num_rows($resultluog)>0){
		$luogpres=1;//se servizio presente stampo la scelta del servizio
	}else{
		$luogpres=2;
	}
		//sennò non stampo nulla

$luogpres=2;//non stampo i luoghi, un altra volta li stampo
if($luogpres==1){
//navigation(21,0,0,0);
$testo.='<br/><br/><div class="luoghiscroller" onclick="navigation2(3,0,0,0);"><u>Scopri i Luoghi ed Eventi</u></div>

<div class="titololuoghiscroller">Rendi Unico il Soggiorno</div>

  <div class="overmano">
	<div class="swiper-wrapper wrapperwidth160">
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
			
			
			<div class="scrollertop"></div>
			
			<div class="scrollertoptxt" >
			<strong><div class="fs18">'.$km.' km</div></strong></div>
			
			<div class="testoscrollerluoghi">
						<div class="testodivscroller"><strong>'.$nome.'</strong><br><span class="fs12">'.$dove.'</span></div>
					</div>
			</div>';		
		}
	}



$testo.='
  	</div>
</div>
';
}
*/
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


$testo.='<br/><div class="testocontatti" id="contatti"><br>
	<div class="contattaci">Contattaci</div><br>
	
	<table class="fw300">
	<tr onclick="location.href='."'tel:".$row['8']."'".'"><td valign="top" ><i class="material-icons iconesotto">phone</i> </td><td> '.$row['8'].'</td></tr>
	<tr onclick="location.href='."'mailto:".$row['4']."'".'"><td valign="top"><i class="material-icons iconesotto">email</i> </td><td> '.$row['4'].'</td></tr>
	<tr onclick="location.href='."'http://".$row['5']."'".'"><td valign="top"><i class="material-icons iconesotto">web</i> </td><td> '.$row['5'].'</td></tr>
	<tr><td valign="top"><i class="material-icons iconesotto">pin_drop</i> </td><td> '.$row['6'].'</td><td>
	<tr><td valign="top"><i class="material-icons iconesotto" >place</i> </td><td> '.$row['2'].','.$row['3'].'</td><td>
	
	
	</table><br>
	
	';
	
$testo.='
</div>';
if(!isset($inc)){
echo $testo;
}




?>