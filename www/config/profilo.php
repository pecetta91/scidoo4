<?php 

header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');



$IDutente=$_SESSION['ID'];
$IDstruttura=$_SESSION['IDstruttura'];




$query="SELECT contratto FROM contratti WHERE IDstruttura='$IDstruttura' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$contratto=$row['0'];
$_SESSION['contratto']=$contratto;

$query="SELECT IDcliente,nome FROM strutture WHERE ID='$IDstruttura' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDmainuser=$row['0'];
$nomestr=$row['1'];
$IDpos=1;



$posadd='2';


		$query="SELECT s.ID FROM servizi as s,tiposervizio as t WHERE s.IDstruttura='$IDstruttura' AND s.IDtipo=t.ID AND t.tipolimite NOT IN(4,5) AND s.attivo='1' LIMIT 1";
		$result=mysqli_query($link2,$query);
		if(mysqli_num_rows($result)>0){
			$posadd.=',1';
		}

		$query="SELECT IDstr FROM setupreg WHERE IDstr='$IDstruttura' LIMIT 1";
		$result=mysqli_query($link2,$query);
		if(mysqli_num_rows($result)>0){
			$posadd.=',3';
		}
		
		$query="SELECT a.ID FROM cofanetti as c,agenzie as a WHERE a.IDstr='$IDstruttura' AND c.IDagenzia=a.ID LIMIT 1";
		$result=mysqli_query($link2,$query);
		if(mysqli_num_rows($result)>0){ 
			$posadd.=',4';
		}
		



$arrper=array();

$testo='';
if($IDutente==$IDmainuser){
	$IDpos=1;
	$query="SELECT nome FROM clienti WHERE ID='$IDutente' LIMIT 1";
	$result=mysqli_query($link2,$query);
	$row=mysqli_fetch_row($result);
	$nomepers=$row['0'];
	array_push($arrper,'0');
	
	$query="SELECT m.tipo,p.nome FROM mansioni as m,mansionipers as ms,personale as p WHERE ms.IDstruttura='$IDstruttura' AND p.IDuser='$IDutente' AND ms.mansione=m.ID AND p.ID=ms.IDpers";
	$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
	while($row=mysqli_fetch_row($result)){
			$nomepers=$row['1'];
			array_push($arrper,$row['0']);
		}
	}
}else{
	//controllo personale
	$nomepers='';
	
	$query="SELECT nome FROM personale WHERE IDuser='$IDutente' LIMIT 1";
	$result=mysqli_query($link2,$query);
	$row=mysqli_fetch_row($result);
	$nomepers=$row['0'];
	
	$query="SELECT m.tipo FROM mansioni as m,mansionipers as ms,personale as p WHERE ms.IDstruttura='$IDstruttura' AND p.IDuser='$IDutente' AND ms.mansione=m.ID AND p.ID=ms.IDpers";
	$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
	while($row=mysqli_fetch_row($result)){
			array_push($arrper,$row['0']);
		}
	}
}
$query2="SELECT ID FROM personale WHERE IDuser='$IDutente' LIMIT 1";
$result2=mysqli_query($link2,$query2);
$row2=mysqli_fetch_row($result2);
$IDpers=$row2['0'];

$time24=time()-86400;



//<i class="icon f7-icons fs25">gear</i>
$testo=  '<div data-page="profilo" class="page"> ';


		$testo.='	<div class="navbar">
				<div class="navbar-inner">
					<div class="left"><div class="cfff ml10 fs24 fw600" >
					<img src="logoscidoow.png" class="width100">
					
					</div></div>
					
					<div class="right"></div>
				</div>
			</div>
				';

$testo.='
			
			 <input type="hidden" id="posadd" value="'.$posadd.'">
            <div class="page-content p0 pt0 pb40" > 
			
			
			
			
				';
			
			
		/*
		<br>
		<div class="titleb" style="widht:80%; margin-left:10px;">Gestione Strutture</div><hr><br><br>*/	
		
	$testo1='<div class="width100per m0 p0 pt17" >
	

	';
	
	
	
	
	
	
$testo1.='<div class="content-block pb0" ">
<div class="sliderdiv">';


$testo1.='
<div class="overmano btnone">
	  <div class="swiper-wrapper">';



	
	
		
		$data=date('Y-m-d');
		list($yy, $mm, $dd) = explode("-", $data);
		$time0=mktime(0, 0, 0, $mm, $dd, $yy);
		
		$timenow=time();
		$timef=$timenow+86400;
		$event=array();
		$event[$time0]='';
		

		$timeadesso=oraadesso($IDstruttura);

		

		$queryp="SELECT m.tipo FROM personale as p,mansioni as m,mansionipers as mp WHERE mp.IDstruttura='$IDstruttura' AND mp.mansione=m.ID AND mp.IDpers=p.ID AND p.ID='$IDpers' GROUP BY m.tipo";
		$resultp=mysqli_query($link2,$queryp);
		if(mysqli_num_rows($resultp)>0){
			while($rowp=mysqli_fetch_row($resultp)){
				$IDtipo=$rowp['0'];
				
				switch($IDtipo){
					case 1: //ristorazione - persone e tavoli suddivisi per fascia oraria
						$query="SELECT ID,sottotipologia FROM sottotipologie WHERE IDstr='$IDstruttura' AND IDmain='$IDtipo'";
					
						$result=mysqli_query($link2,$query);
						if(mysqli_num_rows($result)>0){
							while($row=mysqli_fetch_row($result)){
								$IDsottotip=$row['0'];
								//estrai fasce
								$query2="SELECT GROUP_CONCAT(ID SEPARATOR ','),COUNT(*),time FROM prenextra WHERE sottotip='$IDsottotip' AND IDstruttura='$IDstruttura' AND time>='$timeadesso' AND FROM_UNIXTIME(time,'%Y-%m-%d')='$data' AND modi>='0' GROUP BY FROM_UNIXTIME(time,'%G')";
								$result2=mysqli_query($link2,$query2);
								if(mysqli_num_rows($result2)>0){
									while($row2=mysqli_fetch_row($result2)){
										$IDgroup=$row2['0'];
										if(strlen($IDgroup)>0){
											$query3="SELECT IDp2 FROM prenextra2 WHERE IDprenextra IN($IDgroup) AND qta='1'";
											$result3=mysqli_query($link2,$query3);
											$nump=mysqli_num_rows($result3);
											
											if(!isset($event[$row2['2']])){
												$event[$row2['2']]='';
											}
											
											$testointo='N. '.$row2['1'].' '.$row['1'].' ('.$nump.' '.txtpersone($nump).')';
											//$testointo=substr($testointo, 0,20);
											$testointo=TagliaStringa2($testointo,38);
											
											$event[$row2['2']].='
											
												<div class="swiper-slide"><div class="slidecol1">'.date('H:i',$row2['2']).'</div><div class="slidepro">'.$testointo.'</div></div>
											
											';
										}
										
									}
								}
							}
						}
					
					break;
					case 4: //centro benessere - massaggi personali - numero di ingressi fissati - numero di grotte di sale fissate ecc
					

						$query="SELECT ID,sottotipologia FROM sottotipologie WHERE IDstr='$IDstruttura' AND IDmain='4'";
						$result=mysqli_query($link2,$query);
						if(mysqli_num_rows($result)>0){
							while($row=mysqli_fetch_row($result)){
								$IDsottotip=$row['0'];
								//estrai fasce
								$query2="SELECT GROUP_CONCAT(p.ID SEPARATOR ','),COUNT(DISTINCT(p.IDpren)),p.time,p.modi,s.servizio FROM prenextra as p,servizi as s WHERE p.sottotip='$IDsottotip' AND p.IDstruttura='$IDstruttura' AND p.time>='$timeadesso' AND FROM_UNIXTIME(p.time,'%Y-%m-%d')='$data' AND p.modi>='0' AND p.extra=s.ID GROUP BY FROM_UNIXTIME(p.time,'%G'),p.modi,p.extra";
								$result2=mysqli_query($link2,$query2);
								if(mysqli_num_rows($result2)>0){
									while($row2=mysqli_fetch_row($result2)){
										$IDgroup=$row2['0'];
										if(strlen($IDgroup)>0){
											$query3="SELECT IDprenextra FROM prenextra2 WHERE IDprenextra IN($IDgroup) AND qta='1' GROUP BY IDinfop";
											$result3=mysqli_query($link2,$query3);
											$nump=mysqli_num_rows($result3);
											if(!isset($event[$row2['2']])){
												$event[$row2['2']]='';
											}
											if($row2['3']==0){
												
												$testointo='N. '.$row2['1'].' '.$row2['4'].' ('.$nump.' '.txtpersone($nump).')';
												$testointo=TagliaStringa2($testointo,38);

												
												$event[$time0].='
												<div class="swiper-slide"><div class="slidecol40">--.--</div><div class="slidepro">'.$testointo.'</div></div>
												';
											}else{
												
												$testointo='N. '.$row2['1'].' '.$row2['4'].'('.$nump.' Persona/e)';
												$testointo=TagliaStringa2($testointo,38);
												
												$event[$row2['2']].='
													<div class="swiper-slide"><div class="slidecol41">'.date('H:i',$row2['2']).'</div><div class="slidepro">'.$testointo.'</div></div>
												';
											}
										}
										
									}
								}
							}
						}
					break;
					case 2:
						//massaggi e trattamenti assegnati
					
						$query2="SELECT s.servizio,p.time,p.IDpren FROM prenextra as p,servizi as s WHERE p.IDtipo='$IDtipo' AND p.IDstruttura='$IDstruttura' AND p.time>='$timeadesso' AND FROM_UNIXTIME(p.time,'%Y-%m-%d')='$data' AND s.ID=p.extra AND p.IDpers='$IDpers' AND p.modi>'0'";$result2=mysqli_query($link2,$query2);
						if(mysqli_num_rows($result2)>0){
							while($row2=mysqli_fetch_row($result2)){
								
								if(!isset($event[$row2['1']])){
									$event[$row2['1']]='';
								}
								
								$testointo='N. 1 '.$row2['0'];
								
								$testointo=TagliaStringa2($testointo,38);
							
								$event[$row2['1']].='
									<div class="swiper-slide"><div class="slidecol41">'.date('H:i',$row2['2']).'</div><div class="slidepro">'.$testointo.'</div></div>
								';
								
							}
						}
						
						
						//massaggi e trattamenti in sospeso
						
						$query2="SELECT orai FROM strutture WHERE ID='$IDstruttura' LIMIT 1";
						$result2=mysqli_query($link2,$query2);
						$row2=mysqli_fetch_row($result2);
						$time=$time0+$row2['0'];
						
						$query2="SELECT COUNT(*),sottotip FROM prenextra WHERE IDtipo='$IDtipo' AND IDstruttura='$IDstruttura' AND FROM_UNIXTIME(time,'%Y-%m-%d')='$data' AND time>='$timeadesso' AND modi='0' GROUP BY sottotip";
						$result2=mysqli_query($link2,$query2);
						if(mysqli_num_rows($result2)>0){
							while($row2=mysqli_fetch_row($result2)){
								
								$query3="SELECT sottotipologia FROM sottotipologie WHERE ID='".$row2['1']."' LIMIT 1";
								$result3=mysqli_query($link2,$query3);
								$row3=mysqli_fetch_row($result3);
								$sotto=$row3['0'];
								
								if(!isset($event[$time0])){
									$event[$time0]='';
								}
								
								$testointo='N. '.$row2['0'].' '.$sotto;
								$testointo=TagliaStringa2($testointo,38);
								
								$event[$time0].='
								<div class="swiper-slide"><div class="slidecol40">--.--</div><div class="slidepro">'.$testointo.'</div></div>
									
								
								';
							}
						}

					break;
					case 5: //arrivi di oggi - pulizie giornaliere
						$query="SELECT COUNT(*),time FROM prenotazioni WHERE IDstruttura='$IDstruttura' AND app!='0' AND gg>'0' AND FROM_UNIXTIME(time,'%Y-%m-%d')='$data' GROUP BY time";
						$result=mysqli_query($link2,$query);
						if(mysqli_num_rows($result)>0){
							while($row=mysqli_fetch_row($result)){
								if(!isset($event[$row['0']])){
									$event[$row['0']]='';
								}
								
								$testointo='N. '.$row['0'].' alloggi da riordinare';
								
								$event[$row['0']].='
									<div class="swiper-slide"><div class="slidecol5">'.date('H:i',$row['0']).'</div><div class="slidepro">'.$testointo.'</div></div>
								
								';
							}
						}
					
					break;
					case 0: //arrivi  e partenze - Schedine alloggiati
						
						$query="SELECT COUNT(*),time FROM prenotazioni WHERE IDstruttura='$IDstruttura' AND gg>'0' AND stato>='0'  AND stato!='3' AND FROM_UNIXTIME(time,'%Y-%m-%d')='$data' GROUP BY time";
						$result=mysqli_query($link2,$query);
						if(mysqli_num_rows($result)>0){
							while($row=mysqli_fetch_row($result)){
								if(!isset($event[$row['1']])){
									$event[$row['1']]='';
								}
								
								$testointo='N. '.$row['0'].' CHECK-IN con Alloggio';
								
								$event[$row['1']].='
								
									<div class="swiper-slide"><div class="slidecol0">'.date('H:i',$row['1']).'</div><div class="slidepro">'.$testointo.'</div></div>
								
								';
							}
						}
						
						$query="SELECT COUNT(*),time FROM prenotazioni WHERE IDstruttura='$IDstruttura' AND gg='0' AND stato>='0' AND FROM_UNIXTIME(time,'%Y-%m-%d')='$data' AND time>='$timeadesso' AND stato!='3' GROUP BY time";
						$result=mysqli_query($link2,$query);
						if(mysqli_num_rows($result)>0){
							while($row=mysqli_fetch_row($result)){
								if(!isset($event[$row['1']])){
									$event[$row['1']]='';
								}
								
								$testointo='N. '.$row['0'].' Arrivo Prenotazioni Giornaliere';
								
								$event[$row['1']].='
								
									<div class="swiper-slide"><div class="slidecol0">'.date('H:i',$row['1']).'</div><div class="slidepro">'.$testointo.'</div></div>
								
								
								
									
								';
							}
						}
						
						$query="SELECT COUNT(*),checkout FROM prenotazioni WHERE IDstruttura='$IDstruttura' AND stato>='0' AND gg>'0' AND FROM_UNIXTIME(checkout,'%Y-%m-%d')='$data' AND stato!='4' GROUP BY checkout";
						$result=mysqli_query($link2,$query);
						if(mysqli_num_rows($result)>0){
							while($row=mysqli_fetch_row($result)){
								if(!isset($event[$row['1']])){
									$event[$row['1']]='';
								}
								
								$testointo='N. '.$row['0'].' CHECK-OUT con Alloggio';
								
								$event[$row['1']].='
								
									<div class="swiper-slide"><div class="slidecol00">'.date('H:i',$row['1']).'</div><div class="slidepro">'.$testointo.'</div></div>
									
								
								';
							}
						}
						
						
						$query="SELECT 1,time FROM schedine2 WHERE FROM_UNIXTIME(time,'%Y-%m-%d')='$data' AND IDstr='$IDstruttura' LIMIT 1";
						$result=mysqli_query($link2,$query);
						if(mysqli_num_rows($result)>0){
							$row=mysqli_fetch_row($result);
							if(!isset($event[$row['1']])){
									$event[$row['1']]='';
								}
							
								$testointo='Ci sono delle SCHEDINE da comunicare alla questura';
								$event[$row['1']].='
									
									<div class="swiper-slide"><div class="slidecol00">'.date('H:i',$row['1']).'</div><div class="slidepro">'.$testointo.'</div></div>
									
								
								';
						}
						
						
					break;
				}
			}	
		}
		
		ksort($event);
		$eventtxt='';
		foreach ($event as $key =>$dato){
			$eventtxt.=$dato;
		}
      	if(strlen($eventtxt)>0){
			$testo1.=$eventtxt;
		}else{
			$testo1.='
			<div class="swiper-slide"><div class="slidecol0">'.date('H:i').'</div><div class="slidepro">Non ci sono mansioni in programma oggi</div></div>
			';
		}

    $testo1.='</div>
  </div>
';

	
	
$testo1.='</div>
<div class="mauto mt0 width95per" >';
	
	$nn=0;
	
	$txtfunc=array('Calendario','Benessere','Ristorante','Domotica','Pulizie','Arrivi','Clienti','Prenotazioni');
	
	$txtcolor=array('0064d4','c139d1','e77f19','2cb443','d2c823','3539ca','35a2ca','7b8f97');
	
    $schermo=array('0950e1','e800f0','f04400','eeae00','FF2A5F','2eds2z');
    $altezze=array('160','160','160','160','160','160');
	$txtcolor=array('36b5c8','4dc3a9','93d267','fec322','ff824a','de403c','35a2ca','7b8f97');

	
	$txticon=array('calendar','','','','','list','person','bars');
	$txticonimage=array('','spa','restaurant_menu','flash_on','hotel');

   //$iconaawe=array('icon ion-android-calendar','icon ion-ios-rose-outline','icon ion-android-restaurant','icon ion-ios-bolt-outline','icon ion-ios-home-outline','icon ion-ios-home-outline');

 $iconaawe=array('icon ion-ios-calendar-outline','icon ion-leaf','icon ion-android-restaurant','icon ion-ios-bolt-outline','icon ion-ios-home-outline','icon ion-android-list');
	
	$funzioni=array("navigation(2,0,1)",
	'navigation(4,0,2)',
	'navigation(5,0,3)',
	'navigation(10,1,0)',
	'navigation(6,0,4)',
	'navigation(7,0,5)',
	'navigation(9,0,0)',
	'navigation(8,0,0)');
	
	$abilitate=array();
	
	$felenchi=array();
	$ffunc=array();
	
	foreach ($arrper as $dato){
		$funzione='';
		$txtmenu='';
		switch ($dato){
			case 0:
				array_push($ffunc,0,1,2,3,4,5);
				//array_push($felenchi,5,6,7);
			break;
			case 1:
				array_push($ffunc,2);
			break;
			case 5:
				array_push($ffunc,4);
			break;
			case 3:
			case 2:
			case 4:
				array_push($ffunc,1);
			break;
		}
	}	
	
	$ffunc=array_unique($ffunc);	  
	$felenchi=array_unique($felenchi);	  
	$time24=time();
	
	$query="SELECT tipipos FROM tiposervpos WHERE IDstr='$IDstruttura'  LIMIT 1";
	$result=mysqli_query($link2,$query);
	$row=mysqli_fetch_row($result);
	$arrtipi=explode(',',$row['0']);


	$hhsx=0;
	$hhdx=0;

	foreach($ffunc as $dato){
		$descr='';
		$pos=1;
		switch($dato){
			case 0:
				$query="SELECT ID FROM appartamenti WHERE IDstruttura='$IDstruttura' LIMIT 1";
				$result=mysqli_query($link2,$query);
				if(mysqli_num_rows($result)>0){
					$query="SELECT COUNT(IDv) FROM prenotazioni WHERE FROM_UNIXTIME(time,'%Y-%m-%d')= FROM_UNIXTIME('$time24','%Y-%m-%d') AND IDstruttura='$IDstruttura' AND stato>='0' ";
					$result=mysqli_query($link2,$query);
					if(mysqli_num_rows($result)>0){
						$row=mysqli_fetch_row($result);
						$descr.='<span class="badge" style=" background-color:#'.$txtcolor[$dato].'; ">'.$row['0'].'</span>';
						//$descr.= 'N.'.$row['0'].' prenotazioni in arrivo';
					}
				}else{
					$pos=0;
				}
			break;
			case 1:
				$sum=0;
				
				//$query="SELECT IDstr FROM tiposervpos WHERE IDstr='$IDstruttura' AND  tipipos LIKE '%,4,%' LIMIT 1";
				//$result=mysqli_query($link2,$query);
				if(in_array('4',$arrtipi)){
					
					$datagg=date('Y-m-d',$time24);
					$IDprensosp=getprenotazioni($datagg,0,$IDstruttura,1,1);
					
					
					$query="SELECT DISTINCT(p.ID) FROM prenextra as p,prenextra2 as p2 WHERE ((FROM_UNIXTIME(p.time,'%Y-%m-%d')= FROM_UNIXTIME('$time24','%Y-%m-%d') AND modi>'0') OR (p.IDpren IN($IDprensosp) AND modi='0')) AND p.IDstruttura='$IDstruttura' AND p.ID=p2.IDprenextra AND p2.qta>'0' AND p.IDtipo IN (2,4) AND p.modi>='0'";
						
					$result=mysqli_query($link2,$query);
                    $sum=mysqli_num_rows($result);
					
					
					if($sum>0){
						$descr.='<span class="badge" style=" background-color:#'.$txtcolor[$dato].'; ">'.$sum.'</span>';
					}	
				}else{
					$pos=0;
				}
				
			break;
			case 2:
				$sum=0;
				//$query="SELECT IDstr FROM tiposervpos WHERE IDstr='$IDstruttura' AND  tipipos LIKE '%,1,%' LIMIT 1";
				//$result=mysqli_query($link2,$query);
				if(in_array('1',$arrtipi)){
					$query="SELECT COUNT(DISTINCT(p.ID)),SUM(p2.qta),p.sottotip FROM prenextra as p,prenextra2 as p2 WHERE FROM_UNIXTIME(p.time,'%Y-%m-%d')= FROM_UNIXTIME('$time24','%Y-%m-%d') AND p.IDstruttura='$IDstruttura' AND p.ID=p2.IDprenextra AND p2.qta>'0' AND p.IDtipo='1' AND p.modi>='0' GROUP BY p.sottotip  ";
					$result=mysqli_query($link2,$query);
                    if(mysqli_num_rows($result)>0){
						while($row=mysqli_fetch_row($result)){
							$IDsottotip=$row['2'];
							$query2="SELECT sottotipologia FROM sottotipologie WHERE ID='$IDsottotip' LIMIT 1";
							$result2=mysqli_query($link2,$query2);
							$row2=mysqli_fetch_row($result2);
							$sottotip=$row2['0'];
							$sum+=$row['0'];
							//$descr.= 'N.'.$row['0'].' '.$sottotip.' ('.$row['1'].'p.) <br>';
						}
					}
					if($sum>0){
						$descr.='<span class="badge" style=" background-color:#'.$txtcolor[$dato].'; ">'.$sum.'</span>';
					}	
				}else{
					$pos=0;
				}
				
				
				
			break;
			case 3:
				if($_SESSION['contratto']<=3){
					$pos=0;
				}
			break;
			
		}
		
		
		if($pos==1){
			$sx=592;
			$float='float:left;';
			if($hhsx!=0){
				if($hhdx<$hhsx){
					$float='float:right;';
					$sx=600;
				}
			}
			
			if($sx==592){
				$hhsx+=$altezze[$dato];
			}else{
				$hhdx+=$altezze[$dato];
			}
					//background:#'.$schermo[$dato].';	
			
			//<div style=" margin:auto; margin-top:20px; line-height:54px; height:50px; width:50px;  border-radius:50%; background:#'.$schermo[$dato].';"></div>
			//border-color:#'.$schermo[$dato].';
		$testo1.='
		<div  onclick="'.$funzioni[$dato].'"class="schermob">
		<div class="schermoint" >
				<div class="'.$iconaawe[$dato].' fs35 mt-5" style="color:#'.$schermo[$dato].';"></div></div>
				 <div class="testoschermoint">'.$txtfunc[$dato].'</div>
		</div>	';
			
		
	
			
			$nn++;
		}
	}

$testo1.='
</div>


<div class="floatleft width100per h50">
</div>
</div></div>';


	
	$inc=1;
	

		
		
	$txtcambia="0";
	$query="SELECT DISTINCT(s.ID),s.nome FROM strutture as s,personale as p WHERE (s.IDcliente='$IDutente') OR (p.IDuser='$IDutente' AND p.IDstr=s.ID)";
		
		
	$result=mysqli_query($link2,$query);
	$evalcambia='';
	if(mysqli_num_rows($result)>0){
		$txtcambia="";
		while($row=mysqli_fetch_row($result)){
			$txtcambia.=$row['0'].'-'.$row['1'].',';
			$evalcambia.='
			buttons.push({
					text: "'.$row['1'].'",
					onClick: function () {
						modcambio('.$row['0'].',3);
					}
				}); 	
			';
		}
	}
	if(strlen($txtcambia>1)){
		$txtcambia="'".$txtcambia."'";
	}
		
	
		
		
	$testo.=$testo1.'
	<input type="hidden" value="'.base64_encode($evalcambia).'" id="evalcambia">
	
	
	
	
	';
	




$query2="SELECT IDpers FROM notifichepers WHERE IDpers='$IDpers' AND letto='0'";
$result2=mysqli_query($link2,$query2);
$numnot=mysqli_num_rows($result2);
$badgenot='';
if($numnot==0){
	$badgenot='style="display:none;"';
}


$query2="(SELECT a.ID FROM appunti as a,appuntidest as ad WHERE a.IDstr='$IDstruttura' AND a.ID=ad.IDappunto AND ad.IDdest='$IDutente' AND a.fatto='0') UNION (SELECT ID FROM appunti  WHERE IDstr='$IDstruttura' AND IDcliente='$IDutente'  AND fatto='0') ";
$result2=mysqli_query($link2,$query2);
$numapp=mysqli_num_rows($result2);

$badgeapp='';

if($numapp==0){
	$badgeapp='style="display:none;"';
}

	
$testo.='

<br><br><br><br>

</div>';


/*
$testo.='<div class="toolbar" style="border-top:solid 1px #e1e1e1; height:45px; background:#203a93;">
    <div class="toolbar-inner" style="text-align:center;padding-top:10px;">
       <div href="javascript:void(0);"   onClick="navigation(11,0,0)" style=" text-align:center; height:44px; margin-left:30px;">
					<i class="icon ion-ios-bell-outline" style="color:#fff;font-size:31px;">
						<span class="badge bg-red" id="badgenot" '.$badgenot.'>'.$numnot.'</span>
					</i><br/>
						
					
				</div>
		<div href="javascript:void(0);" onClick="navigation(12,0,0)" style=" text-align:center; height:44px;">
					<i class="icon ion-ios-book-outline" style="color:#fff;font-size:31px;">
						<span class="badge bg-red" id="badgenot" '.$badgeapp.' >'.$numapp.'</span>
					</i><br/>
						
				</div>
		<div href="javascript:void(0);" onclick="cambiastruttura()" style=" text-align:center; height:44px; ">
					<i class="icon ion-android-menu" style="color:#fff;font-size:31px;">
					</i><br/>
					
				</div>
								
			<div href="javascript:void(0);"  onClick="esci()" style="text-align:center; height:44px; margin-right:30px;">
					<i class="icon ion-android-exit" style="color:#fff;font-size:31px;">
					</i><br/>
						
				</div>
	
    </div>
</div>
	
';	
	*/


$testo.='<div class="toolbar tabbar tabbar-labels brtop1" >
			<div class="toolbar-inner">
				<a href="#" class="tab-link" onClick="navigation(12,0,0)">
					<i class="icon f7-icons c666"  >book
					
					<span class="badge bg-red" id="badgeapp" '.$badgeapp.'>'.$numapp.'</span>
					</i>
					<span class="tabbar-label fw400" >Appunti</span>
				</a>
			
				<a href="#" class="tab-link" onClick="navigation(11,0,0)">
					<i class="icon f7-icons c666" >alarm
						<span class="badge bg-red" id="badgenot" '.$badgenot.'>'.$numnot.'</span>
					</i>
					<span class="tabbar-label fw400">Notifiche</span>
				</a>
				
				<a href="#" class="tab-link" onclick="cambiastruttura()">
					<i class="icon f7-icons c666" >bars
					</i>
					<span class="tabbar-label fw400" >Strutture</span>
				</a>
				
				<a href="#tab4" class="tab-link" onclick="esci();">
					<i class="icon f7-icons c666" >logout
					</i>
					<span class="tabbar-label fw400" ">Esci</span>
				</a>
			</div>
		</div>';
	
	echo $testo;
	
?>