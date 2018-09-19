<?php
$solotxtinto=0;
if(!isset($inc)){
	header('Access-Control-Allow-Origin: *');
	include('../../config/connecti.php');
	include('../../config/funzioni.php');
	include('../../config/funzionilingua.php');
	
	$IDutente=$_SESSION['ID'];
	$IDstruttura=$_SESSION['IDstruttura'];
}

	if(isset($_GET['dato0'])){
		if($_GET['dato0']!='0'){
			$time=$_GET['dato0'];
		}else{
			if(isset($_SESSION['timecal'])){
				$time=$_SESSION['timecal'];
			}else{
				$time=time();
			}
		}
	}else{
		if(isset($_SESSION['timecal'])){
			$time=$_SESSION['timecal'];
		}else{
			$time=time();
		}
	}
		
	$_SESSION['timecal']=$time;
		$mm=date('m',$time);
		$aa=date('Y',$time);
		$mmsucc=$mm+1;
	
	
	
	$data=date('Y-m-d',$time);
	
	$dataini=$data;
	$datafin=date('Y-m-d',($time+86400*7));
	$IDtipo=0;
	if(isset($_GET['dato1'])){
		$IDsottotip=$_GET['dato1'];
	}else{
		if(isset($_SESSION['IDsottotip'])){
			$IDsottotip=$_SESSION['IDsottotip'];
		}else{
			$query2="SELECT ID FROM sottotipologie WHERE IDstr='$IDstruttura' AND IDmain ='1' ORDER BY ord LIMIT 1";
			$result2=mysqli_query($link2,$query2);
			$row=mysqli_fetch_row($result2);
			$IDsottotip=$row['0'];
		}
	}

	if(isset($_GET['dato2'])){
		if($_GET['dato2']==1){
			$solotxtinto=1;
		}
	}


	$_SESSION['IDsottotip']=$IDsottotip;
	$query2="SELECT IDmain,sottotipologia FROM sottotipologie WHERE ID='$IDsottotip' LIMIT 1";
	$result2=mysqli_query($link2,$query2);
	$row=mysqli_fetch_row($result2);
	$IDtipo=$row['0'];
	$sottotipname=$row['1'];
	$inc=1;

unset($_SESSION['orario']);
//estrazione IDsottotip
$ricrea=1;
if(isset($_SESSION['datecentro'])){
	if($_SESSION['datecentro'][0]==date('Y-m-d',$time)){
		$ricrea=0;
	}
}
if($ricrea==1){
	$_SESSION['datecentro'][0]=date('Y-m-d',$time);
}

if(isset($_GET['dato2'])){
	$button=$_GET['dato2'];
	$_SESSION['visristo']=$button;
}else{
	if(isset($_SESSION['visristo'])){
		$button=$_SESSION['visristo'];
	}else{
		$button=0;
		$_SESSION['visristo']=$button;
	}
}



$testo='

<input type="hidden" id="IDsottoristogiorno" value="'.$IDsottotip.'">
<input type="hidden" id="timeristogiorno" value="'.$time.'">
<input type="hidden" id="contenutodivgiorno" value="'.$button.'">
';
list($yy, $mm, $dd) = explode("-", $data);
$time0=mktime(0, 0, 0, $mm, $dd, $yy);
				$timef=$time0+86400;


switch($button)
{
	case 0:
		     
		
		$stamp=0;
		
		$query="SELECT GROUP_CONCAT(IDpren SEPARATOR ','),COUNT(*) FROM prenextra WHERE FROM_UNIXTIME(time,'%Y-%m-%d')='$data' AND IDstruttura='$IDstruttura' AND IDtipo='1' AND sottotip='$IDsottotip'  GROUP BY sottotip";
		$result=mysqli_query($link2,$query);
		if(mysqli_num_rows($result)>0){
			$row=mysqli_fetch_row($result);
			$IDprennot=$row['0'];
			$num=$row['1'];
		}else{
			$IDprennot=0;
			$num=0;
		}
		
		$txt[0]=array();
		$txt[2]=array();
				$IDpreng=array();
		
		$numpersone[0]=0;
		$numpersone[1]=0;
		
		$query="SELECT P.ID,P.time,P.note,P.IDpren,P.modi,S.servizio,GROUP_CONCAT(P.ID SEPARATOR ',') FROM prenextra as P,servizi AS S,prenextra2 as p2 WHERE FROM_UNIXTIME(P.time,'%Y-%m-%d')='$data' AND P.IDstruttura='$IDstruttura' AND P.IDtipo='1' AND P.sottotip='$IDsottotip' AND P.extra=S.ID AND P.modi>='0' AND p2.IDprenextra=P.ID AND p2.qta>'0' GROUP BY P.sottotip,P.IDpren ORDER BY P.time DESC,p2.qta ";
		$result=mysqli_query($link2,$query);
			
			
		if(mysqli_num_rows($result)>0){
			while($row=mysqli_fetch_row($result)){
				$IDpren=$row['3'];
					if(!in_array($IDpren,$IDpreng)){	
					
					$IDprenunit=prenotstessotav($IDpren,$IDpreng);

					$IDprenextra=$row['0'];
					$timeprenextra=$row['1'];
					$note=$row['2'];
					$servizio=$row['5'];
					
					$add=0;
					$query2="SELECT ID,stato FROM tavoli  WHERE IDprenextra='$IDprenextra' LIMIT 1 ";
					$result2=mysqli_query($link2,$query2);
					if(mysqli_num_rows($result2)>0){
						$row2=mysqli_fetch_row($result2);
						switch($row2['1']){
							case 1:
								$add=1;
							break;
							case 2:
								$add=2;
							break;
						}
					}
						
					if(($add==0)||($add==2)){

							$arr=explode(',',$IDprenunit);
							foreach ($arr as $dato){
								array_push($IDpreng,$dato);
							}
								
						
						$nomepren='';
						$query2="SELECT a.nome,p.IDv FROM appartamenti as a,prenotazioni as p WHERE p.IDv IN($IDprenunit) AND p.IDstruttura='$IDstruttura' AND p.app=a.ID";
						$result2=mysqli_query($link2,$query2);
						$nomeapp='';
						if(mysqli_num_rows($result2)>0){
							while($row2=mysqli_fetch_row($result2)){
								
								$nomepren.=estrainome($row2['1']).', ';
								$nomeapp.=''.$row2['0'].' , ';
								
							}
							$nomepren=substr($nomepren, 0, strlen($nomepren)-2).'<br>'; 
							$nomeapp=substr($nomeapp, 0, strlen($nomeapp)-3).'<br>'; 
						}else{
							$nomeapp="";
							$nomepren="";
						}
						
						$elimina=1;
						
						$query2="SELECT GROUP_CONCAT(p2.IDinfop SEPARATOR ','),COUNT(*),GROUP_CONCAT(p2.pacchetto SEPARATOR ','),GROUP_CONCAT(DISTINCT(p.ID) SEPARATOR ',')  FROM prenextra as p,prenextra2 as p2 WHERE FROM_UNIXTIME(p.time,'%Y-%m-%d')='$data' AND p.IDstruttura='$IDstruttura' AND  p.sottotip='$IDsottotip' AND p2.IDprenextra=p.ID AND p.modi>='0' AND p2.qta>'0' AND p.IDpren IN($IDprenunit) GROUP BY p.IDstruttura ";
						$result2=mysqli_query($link2,$query2);
						$row2=mysqli_fetch_row($result2);
						$IDgroup=$row2['0'];
						$npers=$row2['1'];
						$pacchetto=explode(',',$row2['2']);
						
						$IDprenextragroup=$row2['3'];
						
						
						foreach($pacchetto as $dato){
							if(!is_numeric($dato)){
								$elimina=0;
								break;
							}
						}
			
						
						$notecli='';
						/*$query2="SELECT i.ID,GROUP_CONCAT(CONCAT('<b>',s.nome,' ',s.cognome,':</b> ',s.noteristo,' ') SEPARATOR '<br>') FROM infopren as i,schedine as s WHERE i.ID IN($IDgroup) AND i.IDcliente=s.ID AND s.noteristo!='' LIMIT 1";
						$result2=mysqli_query($link2,$query2);
						if(mysqli_num_rows($result2)>0){
							$row2=mysqli_fetch_row($result2);
							if(strlen($row2['1'])>0){
								$notecli='<div class="shortcut mini17 danger infoicon popover" style="float:right;"><span>
								<b style="color:#f02415;">Note Clienti</b><br>'.$row2['1'].'</span></div>';
							}
						}*/
						
						
						$query2="SELECT i.ID FROM infopren as i,schedine as s WHERE i.ID IN($IDgroup) AND i.IDcliente=s.ID AND s.noteristo!='' LIMIT 1";
						$result2=mysqli_query($link2,$query2);
						if(mysqli_num_rows($result2)>0){
							$notecli='<i class="f7-icons" style="color:#ba1515;font-size:16px;">info</i>';
						}
						
				            $numpersone[$add]+=$npers;
							
							if(!isset($txt[$add][$timeprenextra])){
								$txt[$add][$timeprenextra]='';
							}
						
							$txt[$add][$timeprenextra].='
							<div class="row rowlist no-gubber" onclick="elencotavoli('.$IDprenextra.','.$elimina.','."'".$IDprenextragroup."'".');">
							<div class="col-10">'.date('H:i',$timeprenextra).'</div>
							<div class="col-5">'.$notecli.'</div>
							<div class="col-45 h45 f15 coltitle" >
								<b>'.$nomepren.'</b>
								<span>'.$nomeapp.'</span>
							</div>
							<div class="col-25 h40 f13">
								<span style="color:#e4492b;">'.$servizio.'</span>
							</div>
							<div class="col-15 h40">
								'.$npers.' <i class="material-icons">person</i>
							</div>
							</div>
							
							';
							
		
							$timearray[$timeprenextra]=1;
						}
					}
				}
			}
		
		
		
			
			
			
			if(!empty($txt[0])){
				$testo.='<br/>
					 <div class="sale" style="margin-left:15px; margin-bottom:5px; text-align:left;">
					 <div style="width:100%; font-size:15px; font-weight:600;  color:#888; ">Tavoli Prenotati</div></div>';
				ksort($txt['0']);
					
				foreach ($txt['0'] as $timep =>$dato){
					//$testo.='<div class="titleb">'.date('H:i',$timep).'</div>'.$dato.'<br>';
					$stamp=1;
					$testo.=$dato;
				}
				

			}
		
		
		
		$query="SELECT sale.ID,sale.nome FROM sale,saleassoc WHERE sale.IDstr='$IDstruttura' AND saleassoc.IDsotto='$IDsottotip' AND saleassoc.ID=sale.ID ORDER BY saleassoc.priorita";
		$result=mysqli_query($link2,$query);
		while($row=mysqli_fetch_row($result)){
			$IDsala=$row['0'];
			$nomesala=$row['1'];
			
			

			$arrt=array();
			$query2="SELECT GROUP_CONCAT(IDprenextra SEPARATOR ','),num FROM tavoli WHERE 	IDsottotip='$IDsottotip' AND sala='$IDsala' AND FROM_UNIXTIME(time,'%Y-%m-%d')='$data' AND stato='1' GROUP BY num";
			$result2=mysqli_query($link2,$query2);
			if(mysqli_num_rows($result2)>0){
				
				$testo.='<br/><br/>
				 <div class="sale" style="margin-left:15px; margin-bottom:5px; text-align:left;">
					 <div style="width:100%; font-size:15px; font-weight:600;  color:#777; ">'.$nomesala.'</div></div>
				
				';
				$stamp=1;
				
				while($row2=mysqli_fetch_row($result2)){
					$arrt[$row2['1']]=$row2['0'];

				}
			}


			$query3="SELECT dato1,dato2 FROM sale WHERE ID='$IDsala' AND IDstr='$IDstruttura' LIMIT 1";
			$result3=mysqli_query($link2,$query3);
			$row3=mysqli_fetch_row($result3);
			$dato1=$row3['0'];
			$dato2=$row3['1'];


			for($i=$dato1;$i<=$dato2;$i++){
				if(isset($arrt[$i])){



						$query4="SELECT GROUP_CONCAT(P.IDpren SEPARATOR ','),ID,P.time FROM prenextra as P WHERE  FROM_UNIXTIME(P.time,'%Y-%m-%d')='$data' AND P.IDstruttura='$IDstruttura' AND P.IDtipo='1' AND P.sottotip='$IDsottotip' AND P.modi>='0' AND P.ID IN(".$arrt[$i].") GROUP BY P.IDstruttura";
						$result4=mysqli_query($link2,$query4);
						$row4=mysqli_fetch_row($result4);
						$IDprenunit=$row4['0'];
						$arr2=explode(',',$IDprenunit);
						$IDpren=$arr2['0'];
						$IDprenextra=$row4['1'];
						$timeprenextra=$row4['2'];

						$nomepren='';
							$query5="SELECT a.nome,p.IDv FROM appartamenti as a,prenotazioni as p WHERE p.IDv IN($IDprenunit) AND p.IDstruttura='$IDstruttura' AND p.app=a.ID";
							$result5=mysqli_query($link2,$query5);
							$nomeapp='';
							if(mysqli_num_rows($result5)>0){
								while($row5=mysqli_fetch_row($result5)){

									$nomepren.=estrainome($row5['1']).', ';
									$nomeapp.=''.$row5['0'].' , ';

								}
								$nomepren=substr($nomepren, 0, strlen($nomepren)-2).'<br>'; 
								$nomeapp=substr($nomeapp, 0, strlen($nomeapp)-3); 


							}else{
								$nomeapp="";
								$nomepren="";
							}

							$query6="SELECT GROUP_CONCAT(p2.IDinfop SEPARATOR ','),COUNT(*)  FROM prenextra as p,prenextra2 as p2 WHERE FROM_UNIXTIME(p.time,'%Y-%m-%d')='$data' AND p.IDstruttura='$IDstruttura' AND  p.sottotip='$IDsottotip' AND p2.IDprenextra=p.ID AND p.modi>='0' AND p2.qta>'0' AND p.IDpren IN($IDprenunit) GROUP BY p.IDstruttura ";
							$result6=mysqli_query($link2,$query6);
							$row6=mysqli_fetch_row($result6);
							$IDgroup=$row6['0'];
							$npers=$row6['1'];



							$notecli='';
					
						$query2="SELECT i.ID FROM infopren as i,schedine as s WHERE i.ID IN($IDgroup) AND i.IDcliente=s.ID AND s.noteristo!='' LIMIT 1";
						$result2=mysqli_query($link2,$query2);
						if(mysqli_num_rows($result2)>0){
							$notecli='<i class="f7-icons" style="color:#ba1515;font-size:16px;">info</i>';
						}
					/*
							$query7="SELECT i.ID,GROUP_CONCAT(CONCAT('<b>',s.nome,' ',s.cognome,':</b> ',s.noteristo,' ') SEPARATOR '<br>') FROM infopren as i,schedine as s WHERE i.ID IN($IDgroup) AND i.IDcliente=s.ID AND s.noteristo!='' LIMIT 1";
							$result7=mysqli_query($link2,$query7);
							if(mysqli_num_rows($result7)>0){
								$row7=mysqli_fetch_row($result7);
								if(strlen($row7['1'])){
									$notecli='<div class="shortcut mini17 info infoicon popover" style="position:absolute; right:-2px;z-index:2; top:4px;"><span>
									<b style="color:#f02415;">Note Clienti</b><br><br>

									'.$row7['1'].'</span></div>';
								}
							}*/


						$testo.='
						
						
						<div class="row rowlist no-gubber" onclick="tavolisala('.$IDprenextra.');">
							<div class="col-10"><div style="background:#ff9c00; width:100%; text-align:center; font-weight:400; color:#fff; padding:3px; border-radius:4px;">'.$i.'</div></div>
							<div class="col-5">'.$notecli.'</div>
							<div class="col-45 f15 h40 coltitle" >
								<b>'.$nomepren.'</b>
								<span>'.$nomeapp.'</span>
							</div>
							<div class="col-25 h45 f13">
								<span style="color:#e4492b;">'.$servizio.'</span>
							</div>
							<div class="col-15 h40">
								'.$npers.' <i class="material-icons">person</i>
							</div>
							</div>
					';
				}

			}
		}
		
		
		if(!empty($txt[2])){
			$testo.='<br/>
				<div class="sale" style="margin-left:20px; margin-bottom:15px; text-align:left;">
				<div style="border-bottom:solid 1px #33ae59; color:#33ae59;">Tavoli conclusi</div></div>';
			$stamp=1;
			foreach ($txt['2'] as $timep =>$dato){
				$testo.=$dato;
				
			}
		}
		
		
		if($stamp==0){
			$testo.='<br/><br/>Non ci sono tavoli prenotati<br><br><br><br>
			
			<button class="button button-raised button-fill" onclick="navigation(34,'."'".$time.','.$IDsottotip."'".','."'nuovotavolo'".',0);">Aggiungi una Prenotazione</button>
			';
		}
		
		
		$testo.='<br/><br/><br/><br/>';	    
	break;
		
	case 1:  
		     $testo.='<div style="margin-top:15px"></div>';
		
		     
		
		$query="SELECT sale.ID,sale.nome FROM sale,saleassoc WHERE sale.IDstr='$IDstruttura' AND saleassoc.IDsotto='$IDsottotip' AND saleassoc.ID=sale.ID ORDER BY saleassoc.priorita";
		$result=mysqli_query($link2,$query);
		while($row=mysqli_fetch_row($result)){
				$IDsala=$row['0'];
				$nomesala=$row['1'];

				$testo.='<div class="titleb" style="font-size:16px;  width:90%; text-align:left;color:#333; margin-left:15px;">'.$nomesala.'</div><br/><br/>
				<div class="row no-gutter">

				';

				$arrt=array();
				$query2="SELECT GROUP_CONCAT(IDprenextra SEPARATOR ','),num FROM tavoli WHERE 	IDsottotip='$IDsottotip' AND sala='$IDsala' AND FROM_UNIXTIME(time,'%Y-%m-%d')='$data' AND stato='1' GROUP BY num";
				$result2=mysqli_query($link2,$query2);
				if(mysqli_num_rows($result2)>0){
					while($row2=mysqli_fetch_row($result2)){
						$arrt[$row2['1']]=$row2['0'];

					}
				}


				$query3="SELECT dato1,dato2 FROM sale WHERE ID='$IDsala' AND IDstr='$IDstruttura' LIMIT 1";
				$result3=mysqli_query($link2,$query3);
				$row3=mysqli_fetch_row($result3);
				$dato1=$row3['0'];
				$dato2=$row3['1'];


				for($i=$dato1;$i<=$dato2;$i++){
					if(isset($arrt[$i])){

							$query4="SELECT GROUP_CONCAT(P.IDpren SEPARATOR ','),ID,P.time FROM prenextra as P WHERE  FROM_UNIXTIME(P.time,'%Y-%m-%d')='$data' AND P.IDstruttura='$IDstruttura' AND P.IDtipo='1' AND P.sottotip='$IDsottotip' AND P.modi>='0' AND P.ID IN(".$arrt[$i].") GROUP BY P.IDstruttura";
							$result4=mysqli_query($link2,$query4);
							$row4=mysqli_fetch_row($result4);
							$IDprenunit=$row4['0'];
							$arr2=explode(',',$IDprenunit);
							$IDpren=$arr2['0'];
							$IDprenextra=$row4['1'];
							$timeprenextra=$row4['2'];


							$nomepren='';
								$query5="SELECT a.nome,p.IDv FROM appartamenti as a,prenotazioni as p WHERE p.IDv IN($IDprenunit) AND p.IDstruttura='$IDstruttura' AND p.app=a.ID";
								$result5=mysqli_query($link2,$query5);
								$nomeapp='';
								if(mysqli_num_rows($result5)>0){
									while($row5=mysqli_fetch_row($result5)){

										$nomepren.=estrainome($row5['1']).', ';
										$nomeapp.=''.$row5['0'].' , ';

									}
									$nomepren=substr($nomepren, 0, strlen($nomepren)-2).'<br>'; 
									$nomeapp=substr($nomeapp, 0, strlen($nomeapp)-3); 


								}else{
									$nomeapp="";
									$nomepren="";
								}

								$query6="SELECT GROUP_CONCAT(p2.IDinfop SEPARATOR ','),COUNT(*)  FROM prenextra as p,prenextra2 as p2 WHERE FROM_UNIXTIME(p.time,'%Y-%m-%d')='$data' AND p.IDstruttura='$IDstruttura' AND  p.sottotip='$IDsottotip' AND p2.IDprenextra=p.ID AND p.modi>='0' AND p2.qta>'0' AND p.IDpren IN($IDprenunit) GROUP BY p.IDstruttura ";
								$result6=mysqli_query($link2,$query6);
								$row6=mysqli_fetch_row($result6);
								$IDgroup=$row6['0'];
								$npers=$row6['1'];



								$notecli='';
								$query7="SELECT i.ID,GROUP_CONCAT(CONCAT('<b>',s.nome,' ',s.cognome,':</b> ',s.noteristo,' ') SEPARATOR '<br>') FROM infopren as i,schedine as s WHERE i.ID IN($IDgroup) AND i.IDcliente=s.ID AND s.noteristo!='' LIMIT 1";
								$result7=mysqli_query($link2,$query7);
								if(mysqli_num_rows($result7)>0){
									$row7=mysqli_fetch_row($result7);
									if(strlen($row7['1'])){
										$notecli='<div class="shortcut mini17 info infoicon popover" style="position:absolute; right:-2px;z-index:2; top:4px;"><span>
										<b style="color:#f02415;">Note Clienti</b><br><br>

										'.$row7['1'].'</span></div>';
									}
								}
			
								$testo.='
									<div class="col-33">
									<div class="tavolodiv occupato" onclick="tavoloprenotato('.$IDprenextra.');"><div style="line-height:20px;">
									'.$notecli.'

									  <div class="num">'.$i.'</div>
									  <div class="nomeprent">'.$nomepren.'</div>
									  <div style="font-size:11px;">'.$nomeapp.'</div>
									  <span style="font-size:13px;line-height:25px;">'.date('H:i',$timeprenextra).'</span>
									  <span style="margin-left:25px">'.$npers.' <i class="material-icons" style="font-size:12px;">person</i></span>
									</div>	
							</div>
							</div>
						 ';

				}else{
						$tavolo="'".$i.'_'.$IDsala."'";
						$testo.='
						<div class="col-33">
							<div class="tavolodiv libero" onclick="vistasalelib('.$time.','.$IDsottotip.','.$tavolo.');">

							<div class="num">'.$i.'</div>
							<div  style="color:#39b071;">Libero</div>
							</div>
						</div>

						';
				}
			}//chiusura for
			
			$testo.='</div>
						<div style="width:100%; height:35px;"></div>';
		}										
		

		break;
}

echo $testo;	
				 
?>	

						  
						  
