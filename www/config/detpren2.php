<?php

if(!isset($inc)){
	header('Access-Control-Allow-Origin: *');
	include('../../config/connecti.php');
	include('../../config/funzioni.php');
	include('../../config/funzionilingua.php');
	
	$IDutente=$_SESSION['ID'];
	$IDstruttura=$_SESSION['IDstruttura'];
	
	$id=$_GET['dato0'];
	
	if(isset($_GET['dato1'])){
		$tipo=$_GET['dato1'];
	}else{
		$tipo=0;
	}
	$testo='';
}



switch($tipo){
	case 0:
	
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
		
		
		/*
		$arrstato=array('Da Confermare','Conf. Senza Acconto','Conf. Con Acconto','Arrivato','Saldato');
				$statimin=array('DC','CSA','CCA','AS','SC');
		$colorstato=array('d43650','3688d4','d4b836','d436cb','27be59');
		*/
		$testo.=  '
		 <div class="content-block-title" style="color:#2d4e99;"><b>Dettagli Prenotazione</b></div>
						<div class="list-block " id="infoprentab" >
							<ul>
				
	<li>
      <a href="#" class="item-link  smart-select" data-open-in="page" data-searchbar="false">
        <select  onchange="modprenot('.$id.',this.value,31,10,0)">'.generaorario(date('H:i',$time),1,24,60).'</select>
        <div class="item-content">
		<div class="item-media"><i class="icon f7-icons">calendar</i></div>
          <div class="item-inner">
            <div class="item-title">Arrivo<br><span style="font-size:12px;">'.dataita2($time).'</span></div>
            <div class="item-after">'.date('H:i',$time).'</div>
          </div>
        </div>
      </a>
    </li>
	
		
	<li>
      <a href="#" class="item-link  smart-select" data-open-in="page" data-searchbar="false">
        <select onchange="modprenot('.$id.',this.value,149,10,0)">'.generaorario(date('H:i',$checkout),1,24,60).'</select>
        <div class="item-content">
		<div class="item-media"><i class="icon f7-icons">calendar</i></div>
          <div class="item-inner">
            <div class="item-title">Partenza<br><span style="font-size:12px;">'.dataita2($checkout).'</span></div>
            <div class="item-after">'.date('H:i',$checkout).'</div>
          </div>
        </div>
      </a>
    </li>
	
							
							  
							  <li>
							   <div class="item-content">
								<div class="item-media"><i class="icon f7-icons">person</i></div>
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
								</div>
							  </li>
							  
							  
							  
							  <li>
								  <a href="#" class="item-link  smart-select" data-open-in="page" data-searchbar="false">
									<select onchange="modprenot('.$id.',this.value,29,10,0)">';
									
									$statotxt='Annullata';
									if($stato==-1){
									 $testo.='<option value="-1">Annullata</option>';
								  }else{
									  
										$querys="SELECT IDstato,stato FROM statopren ORDER BY ordine";
										$results=mysqli_query($link2,$querys);
										while($rows=mysqli_fetch_row($results)){
										//foreach ($arrstato as $key=>$dato){
											$testo.='<option value="'.$rows['0'].'"';
												if($rows['0']==$stato){$testo.=' selected="selected"';$statotxt=$rows['1'];}
											$testo.='>'.$rows['1'].'</option>';
										}
		
								  }
									
									$testo.='</select>
									<div class="item-content">
									<div class="item-media"><i class="icon f7-icons">check</i></div>
									  <div class="item-inner">
										<div class="item-title">Stato</div>
										<div class="item-after">'.$statotxt.'</div>
									  </div>
									</div>
								  </a>
								</li>
							  
							  
							  
							  
							  <li class="item-content" style="padding:10px;">
								<textarea style="border:solid 1px #ccc; margin-top:5px; font-size:12px; border-radius:3px;" onchange="modprenot('.$IDpren.','."'notepren'".',14,6)" id="notepren" placeholder="Note Prenotazione">'.str_replace('<br/>','&#013;',$note).'</textarea>
								
							  </li>
							  
							  
							  
							  </ul>
						 </div>
						 
						
							';
							
							  
				if($notti>0){			  
						$testo.='  
					 <div class="content-block-title"  style="color:#2d4e99;"><b>Alloggio</b></div>
						<div class="list-block "  id="infoprentab">
							<ul>
							
								 <li>
							   <div class="item-content">
								<div class="item-media"><i class="icon f7-icons">home</i></div>
								<div class="item-inner">
								  <div class="item-title">Alloggio</div>
								  <div class="item-after">'.$alloggio.'</div>
								</div>
								</div>
							  </li>
							
							
							<li>
      <a href="#" class="item-link  smart-select" data-open-in="page" data-searchbar="false">
        <select onchange="modprenot('.$IDapp.',this.value,17,10,0)">';
		
		$q8="SELECT stato FROM appartamenti WHERE ID='$IDapp' LIMIT 1";
									$r8=mysqli_query($link2,$q8);
									$row8=mysqli_fetch_row($r8);
									$proapp=$row8['0'];
									$statiapp=array('Alloggio Pronto','Alloggio Occupato','Alloggio da Preparare');		
									foreach ($statiapp as $key=>$dato){
										$testo.='<option value="'.$key.'"';
											if($key==$proapp){$testo.=' selected="selected"';}
										$testo.='>'.$dato.'</option>';
									}
									
		
		
		$testo.='</select>
        <div class="item-content">
		<div class="item-media"><i class="icon f7-icons">calendar</i></div>
          <div class="item-inner">
            <div class="item-title">Pulizia</div>
            <div class="item-after">'.$statiapp[$proapp].'</div>
          </div>
        </div>
      </a>
    </li>
	
	
	<li>
							   <div class="item-content">
								<div class="item-media"><i class="icon f7-icons">home</i></div>
								<div class="item-inner">
								  <div class="item-title">Letti</div>
								  <div class="item-after">';
								  
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
									$testo.='Nessuna disposizione';
								}
								  
								  
								  $testo.='</div>
								</div>
								</div>
							  </li>
							';
							  
							  
							  
							  if(($_SESSION['contratto']>3)&&($notti!=0)){	
									
									$testo.='
									
									<li>
      <a href="#" class="item-link  smart-select" data-open-in="page" data-searchbar="false">
        <select  id="tempg" onchange="modprenot('.$id.','."'tempg'".',21,1)">'.generadeg(15,30,intval($tempg)).'</select>
        <div class="item-content">
		<div class="item-media"><i class="icon f7-icons">filter</i></div>
          <div class="item-inner">
            <div class="item-title">T. Giorno (C&deg;)</div>
            <div class="item-after">'.$tempg.'</div>
          </div>
        </div>
      </a>
    </li>
	
		<li>
      <a href="#" class="item-link  smart-select" data-open-in="page" data-searchbar="false">
        <select  id="tempg" onchange="modprenot('.$id.','."'tempn'".',22,1)">'.generadeg(15,30,intval($tempn)).'</select>
        <div class="item-content">
		<div class="item-media"><i class="icon f7-icons">filter</i></div>
          <div class="item-inner">
            <div class="item-title">T. Notte (C&deg;)</div>
            <div class="item-after">'.$tempn.'</div>
          </div>
        </div>
      </a>
    </li>';
										 
							 
							}
							  
							  
							  
							  $testo.='	  </ul>
							 </div>
						';
				}
				
				$testo.='<div style="width:100;" align="center">';
				
		$query2="SELECT ID FROM stopconferma WHERE IDstr='$IDstruttura' AND IDpren='$id' AND reinvio='0' LIMIT 1";
		$result2=mysqli_query($link2,$query2);
		if(mysqli_num_rows($result2)>0){
			$row2=mysqli_fetch_row($result2);
			//controllo non inviare e rinviare
			$testo.='
			<br>
			<b style="font-size:18px;line-height:22px;color:#9647aa;">'."Invio notifica di inserimento ed<br>abilitazione all'APP stoppata".'</b><br><br><a href="#" class="button button-fill color-green" style="width:50%;"  onclick="modprenot(0,'.$row2['0'].',159,10,9)">Invia Notifica</a>
			';
		}else{
			if((time()-300)<$datapren){
				$query2="SELECT ID FROM messaggi WHERE tipofrom='1' AND fromID='$IDstruttura' AND toID='$id' AND tipoto='2' LIMIT 1";
				$result2=mysqli_query($link2,$query2);
				if(mysqli_num_rows($result2)==0){
					//sta per inviare
					$stamp=1;
					$testo.='
					<br>
						<b style="font-size:18px;line-height:22px;color:#9647aa;">'."Sara' inviata la notifica di inserimento ed abilitazione all'APP  tra 5 minuti..".'</b><br><br><a href="#" class="button button-fill" style="width:80%;height:35px; margin:auto; line-height:30px;" onclick="modprenot(0,'.$id.',158,10,9)"  >Non inviare</a>
						
						
						';
				}
			}else{
				$query2="SELECT ID FROM messaggi WHERE tipofrom='1' AND fromID='$IDstruttura' AND toID='$id' AND tipoto='2' LIMIT 1";
				$result2=mysqli_query($link2,$query2);
				if(mysqli_num_rows($result2)>0){
					$testo.='<br><li><b style="font-size:16px; color:#9647aa;">Nofica di inserimento prenotazione inviata correttamente</b></li>';
				}else{
					$query2="SELECT ID FROM stopconferma WHERE IDstr='$IDstruttura' AND IDpren='$id' AND reinvio='1' LIMIT 1";
					$result2=mysqli_query($link2,$query2);
					if(mysqli_num_rows($result2)>0){
						$testo.='<br><li><b style="font-size:16px; color:#9647aa;">'."Invio notifica di inserimento  ed abilitazione all'APP in corso..</b></li>";
					}
				}
			}
		}
					$testo.='</div>
					<br><br><hr>
										
					<a href="#" class="button button-fill  " onclick="msgboxelimina('.$id.',1,0,2)" style="background:#c12323; width:80%;  height:35px; margin:auto; line-height:30px;">Annulla Prenotazione</a>
	
				  
				  </div>
	
					</div>
					';
					  
	break;
	
	case 1:
	
	$testo.='<input type="hidden" id="pagdetpren" value="2">
	';
	
	/*
		$testo.='
		
		
		<div  class="navbar" style="margin:auto;margin-top:0px;margin-bottom:-100px; width:90%;background:transparent;">
						<div  style="height:32px; padding:0px;background:transparent;box-shadow:none;width:100%;border:none; ">
						  <div class="buttons-row" style=" box-shadow:none; width:100%;">
							<a href="#tab4" class="button active tab-link">Iniziale</a>
							<a href="#tab5" class="button tab-link">Extra</a>
							<a href="#tab6" class="button tab-link">Prodotti</a>
						  </div>
						</div>
					</div>
					
				<div id="tabmain2">
				
				
					<div class="tabs-animated-wrap">
					  <!-- Tabs, tabs wrapper -->
					  <div class="tabs">
					 
					 
					 
					 
		
		';*/
	
		
	$dis2='';
		
	$query2="SELECT ID,time,stato FROM prenotazioni  WHERE IDv='$id' LIMIT 1";
	$result2=mysqli_query($link2,$query2);
	$row2=mysqli_fetch_row($result2);
	$IDvis=$row2['0'];
	$timepren=$row2['1'];
	$stato=$row2['2'];
	
	$totgen=0;
	
	
	$IDprenc=prenotcoll($id);
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
          				<div class="content-block" style="padding:0px;">';
						
								

						*/
		$testo.='<div class="content-block-title titleb">PRENOTAZIONE INIZIALE</div>
			<div class="list-block" ><ul>
			 ';
			  
		
		
		$totale1=0;
		
	}
	if($ii==2){
		$scontf='2';
		$scont='cextra';
		/*
		$testo.='<div id="tab5" class="page-content tab ">
          				<div class="content-block" style="padding:0px;">';
		*/
		
		
		
			$testo.='
			<div class="content-block-title titleb">EXTRA</div>
			<div class="list-block"><ul>
			 ';
	$totale1=0;
		
	}
	if($ii==3){
		$query="SELECT p.ID FROM prenextra as p,prenextra2 as p2 WHERE p.IDtipo='10' AND p.IDpren IN ($IDprenc)AND p.ID=p2.IDprenextra AND p2.datacar='1'";
		$result=mysqli_query($link2,$query);
		if(mysqli_num_rows($result)>0){
			
			
			/*
			$testo.='</div></div>
				 <div id="tab6" class="page-content tab" >
          				<div class="content-block" style="padding:0px;">
			';*/
			$testo.='	
					<li class="list-group-title" style="color:#2740dc; ">Prodotti Acquistati</li>
			  ';
		}
	}

	$IDtipomain=0;

	$query="SELECT p.ID,p.time,p2.datacar,COUNT(DISTINCT(p2.IDprenextra)),GROUP_CONCAT(DISTINCT(p.ID) SEPARATOR ','),p.tipolim,p.extra,p.modi,SUM(p2.prezzo),GROUP_CONCAT(DISTINCT(p2.IDinfop) SEPARATOR ','),SUM(p2.qta),p.sala,p.IDtipo,p.sottotip FROM prenextra as p,prenextra2 as p2 WHERE p2.pacchetto<='0' AND p2.IDpren IN ($IDprenc) AND p.ID=p2.IDprenextra AND p2.paga>'0' $qadd2 GROUP BY p.extra ORDER BY p.time";
	
	//singolo
	$query="SELECT p.ID,p.time,p2.datacar,COUNT(DISTINCT(p2.IDprenextra)),GROUP_CONCAT(DISTINCT(p.ID) SEPARATOR ','),p.tipolim,p.extra,p.modi,SUM(p2.prezzo),GROUP_CONCAT(DISTINCT(p2.IDinfop) SEPARATOR ','),SUM(p2.qta),p.sala,p.IDtipo,p.sottotip FROM prenextra as p,prenextra2 as p2 WHERE p2.pacchetto<='0' AND p2.IDpren IN ($IDprenc) AND p.ID=p2.IDprenextra AND p2.paga>'0' $qadd2 GROUP BY p.ID ORDER BY p.sottotip";
	
	//per tipo
	$query="SELECT p.ID,p.time,p2.datacar,COUNT(DISTINCT(p2.IDprenextra)),GROUP_CONCAT(DISTINCT(p.ID) SEPARATOR ','),p.tipolim,p.extra,p.modi,SUM(p2.prezzo),GROUP_CONCAT(DISTINCT(p2.IDinfop) SEPARATOR ','),SUM(p2.qta),p.sala,p.IDtipo,p.sottotip FROM prenextra as p,prenextra2 as p2 WHERE p2.pacchetto<='0' AND p2.IDpren IN ($IDprenc) AND p.ID=p2.IDprenextra AND p2.paga>'0' $qadd2 GROUP BY p.extra ORDER BY p.sottotip";
	
	
	
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
				
				$del=1;
				
				$servizio=getnomeserv($extra,$tipolim,$ID);
				
				$funcexp='';
				
				$IDplus=str_replace(',','',$row['9']);
				$IDplus=substr($IDplus,0,5);
				//echo $ID.'---'.$IDplus.'<br>';
				
				if($tipolim=='6'){
					$qtabutt=$qta;
				}else{
					$qtabutt=round($qta/$num);
				}
				
				if($tipolim=='6'){
					$qtabutt='<div class="mdiv">N.'.$qta.' oggetti</div>';
				}else{
					$nn=round($qta/$num);
					$persontxt='persone';
					if($nn==1){$persontxt='persona';}
					$qtabutt='<div class="mdiv">N.'.$nn.' '.$persontxt.'</div>';
				}
				
				$gruppo=$row['4'];
				$txtsend='0';
				$txtsend2='';
				$modprezzo=0;
				
				$pagato=0;
				$numnon=0;
				
				
				
				$query2="SELECT IDprenextra,IDinfop,pagato FROM prenextra2 WHERE IDprenextra IN($gruppo) AND datacar='$datacar' AND pacchetto<='0'";
				$result2=mysqli_query($link2,$query2);
				if(mysqli_num_rows($result2)>0){
					while($row2=mysqli_fetch_row($result2)){
						if($row2['2']!=0){
							++$pagato;
						}else{
							++$numnon;
						}
						$txtsend.=','.$row2['0'].'/'.$row2['1'];	
					}
					$txtsend=substr( $txtsend, 2);
				}
									
				$num2='';
				$qta2='';
				
				if(($tipolim=='5')||($tipolim=='7')||($tipolim=='8')){
					$groupinfoID=$row['9'];
					$qta2=' <span style="font-size:12px;">per '.round($qta/$num).' persone</span>';
					$arrg=explode(',',$IDgroup);
					$prezzo=0;
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
				}
				$prezzotxt="";							
				$datatxt=date('d/m/Y',$time);
				$prezzotxt=round($prezzo,2);
				
				if($tipolim=='6'){
					$qta2=' <span style="font-size:12px;">N.'.$qta.'</span>';
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
				$num2='<div class="mdiv">'.dataita2($time).'</div>';
				
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
					
					$txtsend2=str_replace(',','..',$txtsend);
					$funcexp='onclick="navigation(22,'."'".$txtsend2."'".',0,0)"';
					
					
					//prezzo
					
					if(($tipolim!='8')&&($tipolim!='7')){
						$butt1=$prezzotxt.'€';
						$modprezzo=2;
						//$butt1='<input type="number" value="'.$prezzotxt.'" '.$dis2.' class="ptb" onchange="modprenextra(-'.$ID.$IDplus.','."'-".$ID.$IDplus."'".',18,11,4)" id="-'.$ID.$IDplus.'">';
					}else{
						$butt1=$prezzotxt.'€';
						$modprezzo=0;
						//$butt1='<input type="number" value="'.$prezzotxt.'" disabled="disabled" class="ptb">';
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
							$modprezzo=1;
							//$butt1='<input type="number" value="'.$prezzotxt.'" '.$dis2.' class="ptb" onchange="modprenot('.$ID.','."'-".$ID.$IDplus."'".',98,1,7)" id="-'.$ID.$IDplus.'">';
						}else{
							$butt1=$prezzotxt.'€';
							$modprezzo=2;
							//$butt1='<input type="number"  value="'.$prezzotxt.'" '.$dis2.' class="ptb" onchange="modprenextra(-'.$ID.$IDplus.','."'-".$ID.$IDplus."'".',18,11,4)" id="-'.$ID.$IDplus.'">';
						}
					}else{
						$butt1=$prezzotxt.'€';
						$modprezzo=0;
						//$butt1='<input type="number" value="'.$prezzotxt.'" disabled="disabled" class="ptb">';
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
				
				
				
				$modb='';
		
				
				//$func='onclick="vis2(-'.$ID.$IDplus.',1,'.$num.',1);"';			
				$href='#';
				$stamp=1;
				/*$func='onclick="aprimod('.$ID.$IDplus.',this)"';
				if($vis==1){
					if(($tipolim!='5')&&($tipolim!=7)&&($tipolim!=8)){
						$href='javascript:void(0)';
						$stamp=0;
						$func='';
					}
				}*/
					
					/*$swipeout='<div class="swipeout-actions-right">';
					
					if($tipolim==6){$stamp=0;$href='javascript:void(0)';}
					
					if($tipolim!='4'){
							$swipeout.='<a href="#"onclick="msgboxelimina(-'.$ID.$IDplus.',3,0,1,1);" class="action1 bg-red"><i class="material-icons" style="color:#fff;">delete_forever</i></a>';		
					}
					$swipeout.='</div>';*/
						
				/*
				<div class="item-media mediaright2">
					<div class="IDtipo">'.$vis.'</div>
					</div>*/
					
				
				
				/*
				if($IDtipomain!=$IDtipo){
					
					$IDtipomain=$IDtipo;
					$query7="SELECT tipo FROM tiposervizio WHERE ID='$IDtipo' LIMIT 1";
					$result7=mysqli_query($link2,$query7);
					$row7=mysqli_fetch_row($result7);
					
					
				}
				*/
				
			
				/*$testo.=
				'<input type="hidden" id="'.$txtsend.'" value="-'.$ID.$IDplus.'">
				
				 <li  id="tr-'.$ID.$IDplus.'" lang="'.$txtsend.'" >
				 
     			 <div class="item-content" >
				  <div class="item-media" '.$funcexp.' style="line-height:6px; width:50px; margin-left:-15px; margin-right:-10px;" align="center"><div style="width:20px; margin:0px; height:20px; border-radius:50%; font-size:12px; font-weight:bold; text-align:center; background:#0286ca; color:#fff; line-height:19px;">'.$num.'</div><br><div style="font-size:10px; color:#0286ca; margin-left:-1px;"><b>Dettagli</b></div>
				  </div>
				 
				 <div class="item-inner">
				  <div class="item-title label" style="width:70%">'.$servizio.'<br><span class="subtitle">'.$qtabutt.' '.$num2.' '.$sala.'  </span></div>
				  <div class="item-input" style="width:30%">
					'.$butt1.'
				  </div>
				  
				</div>
				</div>
				</li>
				';*/
				
				if($tipolim==4){
					$del=0;
				}
				
				$testo.=
				'<input type="hidden" id="'.$txtsend.'" value="-'.$ID.$IDplus.'" alt="'.$num.'" lang="'.$del.'">
				
				 <li id="tr-'.$ID.$IDplus.'" lang="'.$txtsend.'" title="'.$ID.'"  alt="'.$modprezzo.'" dir="'.$txtsend2.'" onclick="modservice(-'.$ID.$IDplus.')" >

     			 <div class="item-content" >
				  <div class="item-media mediaright" '.$funcexp.'><div style="width:20px; margin:0px; height:20px; border-radius:50%; font-size:12px; font-weight:bold; text-align:center; background:#0286ca; color:#fff; line-height:19px;">'.$num.'</div>
				  </div>
				 
				 <div class="item-inner">
				  <div class="item-title">'.$servizio.'<br><span class="subtitle">'.$qtabutt.' '.$num2.' '.$sala.'  </span></div>
				  <div class="item-after" >
					'.$butt1.'
				  </div>
				  
				</div>
				</div>
				</li>
				';
				
			
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
					
					$testo.='<li>
					 <div class="item-content">
					 <div class="item-inner">
					  <div class="item-title label" style="width:70%"><b style="color:#cc0828;">'.$rowsc['2'].'</b></span><br><span style="font-size:11px;">(Pren: '.estrainomeapp($rowsc['4']).')</span></span></div>
					  <div class="item-input" style="width:30%">
						<input type="text" value="'.round($rowsc['1'],2).'" '.$dis2.' class="ptb" onchange="modprenot('.$rowsc['0'].','."'prezzo".$rowsc['0']."'".',99,1,7)" id="prezzo'.$rowsc['0'].'">
					  </div>
					</div>
					</div>
					</li>
				 ';
			
					
					$totale1+=round($rowsc['1'],2);
				}
				
				
			}else{
			
			}
			
			if($ii==2){
				$testo.='</div>';
			}
			
		
			$totaletxt='TOTALE INIZIALE';
			if($ii!=1){$totaletxt='TOTALE EXTRA';}
			
			$testo.='
				 <li style="background:#d9edff;">
					 <div class="item-content">
					 <div class="item-inner">
					  <div class="item-title label" style="width:70%"><b style="font-size:14px;">'.$totaletxt.'</b></div>
					  <div class="item-input" style="width:30%">
						<input type="number" value="'.round($totale1,2).'" class="ptb"  id="'.$scont.'" onchange="modprenot('.$id.','."'".$scont."'".','.$scontf.',1,7)">
					  </div>
					</div>
					</div>
					</li>
				</ul></div>
			';
			$totgen+=$totale1;
		}
			
	}
	
	$testo.='<br><br><br><br><br><br>';
	
	
	
	break;
	case 2:
	
		$testo.='<br>

		
			<div class="list-block accordion-list" style="margin-top:10px;">
			  <ul>';
			  
			  
			  
			  
			  $query="SELECT ID,nome,IDcliente FROM infopren WHERE IDpren='$id' AND pers='1'";
$result=mysqli_query($link2,$query);
$j=1;
$numpers=mysqli_num_rows($result);
while($row=mysqli_fetch_row($result)){
	$IDinfop=$row['0'];
	$IDsend='-'.$IDinfop;
	
	$nome='';
	$tipocli=$row['1'];
	$IDcliente=$row['2'];
	$tel='';
	$cognome='';
	$email='';
	$datanas='';
	$query3="SELECT nome,cognome,tel,mail,datanas,sesso,note,noteristo,cell,prefissotel,prefissocell FROM schedine WHERE ID='$IDcliente' LIMIT 1";
	$result3=mysqli_query($link2,$query3);
	if(mysqli_num_rows($result3)>0){
		$row3=mysqli_fetch_row($result3);
		$IDsend=$IDcliente;
		$nome=$row3['0'];
		$cognome=$row3['1'];
		$tel=$row3['2'];
		$email=$row3['3'];
		$datanas=$row3['4'];
		$sesso=$row3['5'];
		$note=$row3['6'];
		$noteristo=$row3['7'];
		$cell=$row3['8'];
		$prefissotel=$row3['9'];
		$prefissocell=$row3['10'];
		
	}else{
		$nome=$row['1'];
		$cognome='';
		$tel='';
		$cell='';
		$email='';
		$datanas='';
		$sesso='';
		$note='';
		$noteristo='';
		
		
		
	}
	

	$query4="SELECT SUM(p2.prezzo) FROM prenextra as p,prenextra2 AS p2 WHERE p.IDpren='$id' AND p2.paga>'0' AND p2.IDinfop='".$row['0']."' AND p.ID=p2.IDprenextra";
	$result4=mysqli_query($link2,$query4);
	$row4=mysqli_fetch_row($result4);
	$prezzotot=$row4['0'];
	if($prezzotot==''){
		$prezzotot=0;
	}
	
	if(isset($arr4[$row['0']])){ //sconto
		$prezzotot+=$arr4[$row['0']];
	}
	if(isset($arr7[$row['0']])){ //sconto
		$prezzotot+=$arr7[$row['0']];
	}



/*


<div style="float:right; width:75px; text-align:right;">';
						
						if(strlen($tel)>0){
							$testo.='<i class="material-icons" style="border:solid 1px #333; border-radius:50%;margin-right:4px; padding:4px;"  onclick="location.href='."'tel:".$tel."'".'">local_phone</i>';
						}
						if(strlen($cell)>0){
							$testo.='<i class="material-icons" style="border:solid 1px #333; border-radius:50%;margin-right:4px; padding:4px;"  onclick="location.href='."'tel:".$cell."'".'">local_phone</i>';
						}
						
						if(strlen($email)>0){
							$testo.='<i class="material-icons" style="border:solid 1px #333;border-radius:50%; padding:4px;" onclick="location.href='."'mailto:".$email."'".'">email</i>';
						}
						$testo.='</div>
*/
	
	
$teltxt=$tel;
$celltxt=$cell;
$emailtxt=$email;
if(strlen($tel)<2){$tel=0;}

if(strlen($cell)<2){$cell=0;}
if(strlen($email)<2){$email=0;}

	
	

//navigation(24,'.$IDsend.',0,0)

	
	$testo.='<li  id="IDinfop'.$IDinfop.'" onclick="detinfop('.$IDsend.','."'".$tel."','".$cell."','".$email."'".')">
				  <a href="#" class="item-link item-content" >
					
					<div class="item-inner">
					  <div class="item-title-row" style=" width:100%;">
						<div class="item-title" style="line-height:14px;"><b style="color:#b4402c">'.stripslashes($nome).' '.stripslashes($cognome).'</b><br><span style="font-size:10px; color:#a9a9a9;">'.$tipocli.'</span><br>
						<span style="font-size:13px; line-height:15px; color:#0e42a2;"  >
						'.$emailtxt.' '.$teltxt.' '.$celltxt.'</span>
						
						
						</div>
						
						
						
					  </div>
					</div>
										
					
				  </a>
				  
				  
				  
				</li>';
	
	
}
	
			  
			  
			  
			  

			  
			  
			 $testo.='</ul></div> <br><br><br><br><br>
			  
		
		';
	
	
		
	
	break;
	case 3:
		$testo.='
		<br>

		
		
		<div class="list-block tablet-inset" id="infoprentab" style="margin-top:10px;">
							<ul>
							  
							  
							  
							  
							  
							  
							  <li>
							  <div class="item-content">
								<div class="item-media"><i class="icon f7-icons">at_fill</i></div>
								<div class="item-inner">
								  <div class="item-title"  style="width:100%;">Agenzia<br>';
								  
								  
								  
								  
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
									$testo.=' '.$nomeag.'';	
									
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
					
					
					$txtpag.='<li><div class="item-content">
									<div class="item-media"><i class="icon f7-icons">money_euro</i></div>
									<div class="item-inner">
									  <div class="item-title"  style="width:100%;"><b>'.$tipopag.'</b>
									  <br>'.metodopag($row3['2']).'<br>
									  <b style="font-size:12px;">'.$rowacc['1'].' &euro;</b> - <span style="font-size:12px;">'.dataita2($timepag).'</span>
									  
									  </div>
									</div></div>
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
							
							<li><div class="item-content">
									<div class="item-media"><i class="icon f7-icons">money_euro</i></div>
									<div class="item-inner">
									  <div class="item-title"  style="width:100%;"><b>Voucher</b>
									  <br>'.metodopag($row3['2']).'<br>
									  <b style="font-size:12px;">'.$rowacc['1'].' &euro;</b> - <span style="font-size:12px;">'.dataita2($timepag).'</span>
									  
									  </div>
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
								
								
								<li><div class="item-content">
									<div class="item-media"><i class="icon f7-icons">at_fill</i></div>
									<div class="item-inner">
									  <div class="item-title"  style="width:100%;">Agenzia
									  <br>'.metodopag($row4['1']).'<br>
									  <b style="font-size:12px;">'.$row3['0'].' &euro;</b> - <span style="font-size:12px;">'.date('d/m/Y',$timepag).'</span>
									  
									  </div>
									</div></div>
								  </li>
								';
								
							}else{
								$txtdapag.='
								
								<li><div class="item-content">
									<div class="item-media"><i class="icon f7-icons">at_fill</i></div>
									<div class="item-inner">
									  <div class="item-title"  style="width:100%;">Agenzia
									  <br>
									  '.round($agetot-$rowag['3'],2).' &euro;
									  
									  
									  </div>
									</div></div>
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
								  <li><div class="item-content">
									<div class="item-media"><i class="icon f7-icons">at_fill</i></div>
									<div class="item-inner">
									  <div class="item-title"  style="width:100%;">Agenzia
									  <br>
									  
									  '.metodopag($row4['1']).'<br>
									  <b style="font-size:12px;">'.$row3['0'].' &euro;</b> - <span style="font-size:12px;">'.date('d/m/Y',$timepag).'</span>
									  
									  </div>
									</div></div>
								  </li>
								  ';
								
							}else{
								$txtdaeffet.='
								
								
								<li><div class="item-content">
									<div class="item-media"><i class="icon f7-icons">at_fill</i></div>
									<div class="item-inner">
									  <div class="item-title"  style="width:100%;">Agenzia<br>
									  '.round($rowag['3'],2).' &euro;
									  
									  
									  </div>
									</div></div>
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
								
								  <li><div class="item-content">
									<div class="item-media"><i class="icon f7-icons">at_fill</i></div>
									<div class="item-inner">
									  <div class="item-title"  style="width:100%;">Agenzia
									  <br>'.metodopag($row4['1']).'<br>
									  <b style="font-size:12px;">'.$row3['0'].' &euro;</b> - <span style="font-size:12px;">'.date('d/m/Y',$timepag).'</span>
									  
									  </div>
									</div></div>
								  </li>
								';
								
							}else{
								$txtdaeffet.='
								
								<li><div class="item-content">
									<div class="item-media"><i class="icon f7-icons">at_fill</i></div>
									<div class="item-inner">
									  <div class="item-title"  style="width:100%;">Agenzia
									  <br>'.round($rowag['3'],2).' &euro;
									  
									  
									  </div>
									</div></div>
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
					<li><div class="item-content">
									<div class="item-media"><i class="icon f7-icons">money_euro</i></div>
									<div class="item-inner">
									  <div class="item-title"  style="width:100%;">Cofanetto
									  <br>
									  Paga ad Agenzia<br>
									  <b style="font-size:12px;">'.round($agetot,2).'€</b> - <span style="font-size:12px;">'.date('d/m/Y',$rowag['2']).'</span>
									  
									  </div>
									</div></div>
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
							<li><div class="item-content">
									<div class="item-media"><i class="icon f7-icons">money_euro</i></div>
									<div class="item-inner">
									  <div class="item-title" style="width:100%;">Voucher
									  <br>
									  Paga ad Agenzia<br>
									  <b style="font-size:12px;">'.round($prezzodasaldreg,2).'€</b> 
									  
									  </div>
									</div></div>
								  </li>
							
							';
							$accontoreg+=$prezzodasaldreg;
					}else{
						if($prezzodasaldreg>0){
							$txtdapag.='
							
							<li><div class="item-content">
									<div class="item-media"><i class="icon f7-icons">money_euro</i></div>
									<div class="item-inner">
									  <div class="item-title" style="width:100%;">Voucher
									  <br>Paga ad Agenzia
									  </div>
									  <div class="item-after">'.round($prezzodasaldreg,2).'€
									  </div>
									  </div>
									</div></div>
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
				
				<li><div class="item-content">
									<div class="item-media"><i class="icon f7-icons">money_euro</i></div>
									<div class="item-inner">
									  <div class="item-title"  style="width:100%;">Da Ricevere</div>
									  <div class="item-after">'.round($prezzosald2,2).'€
									  </div>
									  </div>
									</div></div>
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
			
			
			
			
			
			
			
			if(strlen($txtpag)>0){
				$testo.=$txtpag;
				
			}else{
				$testo.='<li><div class="item-content">
									<div class="item-media"><i class="icon f7-icons">money_euro</i></div>
									<div class="item-inner">
									  <div class="item-title" style="width:100%;">Ricevuti
									  <br>
									  <span style="font-size:13px;">Nessun pagamento ricevuto</span> 
									  
									  </div>
									</div></div>
								  </li>';
			}
			
			if(strlen($txtdapag)>0){
				$testo.=$txtdapag;
			}
			
			
			
			if(strlen($txteffet)>0){
				$testo.=$txteffet;
			}
			
			if(strlen($txtdaeffet)>0){
				$testo.=$txtdaeffet;
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
							  
							  
							  
							  
							  	  </ul>
						 </div>
					
						
					 
						<div class="list-block " id="infoprentab">
							<ul>
							  
							  
							  
				
					<li ><div class="item-content">
									<div class="item-media"><i class="icon f7-icons" >money_euro</i></div>
									<div class="item-inner">
									  <div class="item-title">Iniziale</div>
									  <div class="item-after">
									  <b style="font-size:12px;">'.$prezzoini.'€</b> 
									  
									  </div>
									</div></div>
								  </li>
								  
								  <li><div class="item-content">
									<div class="item-media"><i class="icon f7-icons">money_euro</i></div>
									<div class="item-inner">
									  <div class="item-title">Extra</div>
									  <div class="item-after">
									  <b style="font-size:12px;">'.$prezzoextra.'€</b> 
									  
									  </div>
									</div></div>
								  </li>
								  
								  <li><div class="item-content">
									<div class="item-media"><i class="icon f7-icons">money_euro</i></div>
									<div class="item-inner">
									  <div class="item-title">Abbuoni</div>
									  <div class="item-after">
									  '.$sconti.'€
									  
									  </div>
									</div></div>
								  </li>
								  
								  <li><div class="item-content">
									<div class="item-media"><i class="icon f7-icons">money_euro</i></div>
									<div class="item-inner">
									  <div class="item-title">Totale</div>
									  <div class="item-after">
									  <b style="font-size:12px;">'.round($prezzoini+$prezzoextra+$sconti,2).'€</b> 
									  
									  </div>
									</div></div>
								  </li>
							  </ul>';
	
	
	break;
	case 4:
		$testo.='
		
		
		<input type="hidden" id="pagdetpren" value="1">
		';
		
		
		
$query="SELECT app,gg,time,tempg,tempn,checkout,IDstruttura FROM prenotazioni WHERE IDv='$id' LIMIT 1";

$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDapp=$row['0'];
$gg=$row['1'];
$time=$row['2'];
$tempg=$row['3'];
$tempn=$row['4'];
$checkout=$row['5'];
$IDstr=$row['6'];
$IDpren=$id;

$nomepren=estrainome($IDpren);

		
	
		
$timeora=oraadesso($IDstr);

//elenco servizi


$IDprenc=prenotcoll($IDpren);
$id=$IDpren;


$serviziarr=array();
$prodottiarr=array();




	
	$query="SELECT p.ID,p.time,p.modi,COUNT(DISTINCT(p2.IDprenextra)),GROUP_CONCAT(DISTINCT(p.ID) SEPARATOR ','),p.tipolim,p.extra,p.modi,SUM(p2.prezzo),GROUP_CONCAT(DISTINCT(p2.IDinfop) SEPARATOR ','),SUM(p2.qta),p.sala,p.IDtipo,p.sottotip,s.servizio,s.descrizione,MAX(p2.pacchetto) FROM prenextra as p,prenextra2 as p2,servizi as s WHERE  p2.IDpren IN ($IDprenc) AND p.ID=p2.IDprenextra AND p2.paga>'0' AND p.extra=s.ID AND p.IDtipo NOT IN(8,9)  GROUP BY p.ID ORDER BY p.time";
	$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
			
		while($row=mysqli_fetch_row($result)){
		
		
			$ID=$row['0'];
				$time=$row['1'];
				$modi=$row['2'];
				$num2=$row['3'];
				$prezzo=$row['8'];			
				$extra=$row['6'];
				$qta=$row['10'];
				$IDtipo=$row['12'];
				$IDsotto=$row['13'];
				$tipolim=$row['5'];
				$servizio=$row['14'];
				$descr=$row['15'];
				$pacchetto=$row['16'];
			
				
				
				$num2='';
				if($tipolim==6){
					$servizio='N.'.$qta.' '.$servizio;
					$qta='';
				}else{
					
					if($modi==0){
						$num2='--.--';
					}else{
						$num2=date('H:i',$time);
					}
					
					$persone='persone';
					if($qta==1){
						$persone='persona';
					}
					$qta='N.'.$qta.' '.$persone;
				}
			
							
		
			$butt1='';
			
		$sala='';
			
			
			$txt='<li>
				  <a href="#" class="item-link item-content" onclick="modificaserv('.$ID.',1,0,2)">
						<div class="item-inner" >
					  <div class="item-title-row">
					  <div class="item-title" >'.$servizio.'</div>
						<div class="item-after subt">'.$qta.'</div>
					  </div>
					  <div class="item-subtitle">'.$num2.'</div>
					  
					</div>
				  </a>
				  
				   
			</li>
			
			';
			
			
			$dd=date('Y-m-d',$time);
			
			if($tipolim==6){
				if(isset($prodottiarr[$dd])){
					$prodottiarr[$dd].=$txt;
				}else{
					$prodottiarr[$dd]=$txt;
				}
			
			}else{
			
				if(isset($serviziarr[$dd])){
					$serviziarr[$dd].=$txt;
				}else{
					$serviziarr[$dd]=$txt;
				}
			}
			
			
		
		
		
		
		
		}
	}
	
//sort($serviziarr);
//sort($prodottiarr);


foreach ($serviziarr as $data =>$cont){
	list($yy, $mm, $dd) = explode("-", $data);
	$time=mktime(0, 0, 0, $mm, $dd, $yy);
	
	$testo.='
<div class="content-block-title titleb">'.dataita($time).' '.date('Y',$time).'</div>
			<div class="list-block">
			  <ul>'.$cont.'
			  </ul></div>
	';

}


	
	
		
	break;
	
	
						  
}

if(!isset($inc)){
	echo $testo;
}