<?php 
if(!isset($inc)){
	include('../../../config/connecti.php');
	include('../../../config/funzioni.php');
	header('Access-Control-Allow-Origin: *');
	$testo='<br>';
}

$IDpren=$_SESSION['IDstrpren'];
$route=$_SESSION['route'];

$query="SELECT app,gg,time,tempg,tempn,checkout,IDstruttura FROM prenotazioni WHERE IDv='$IDpren' LIMIT 1";

$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDapp=$row['0'];
$gg=$row['1'];
$time=$row['2'];
$tempg=round($row['3']);
$tempn=round($row['4']);
$checkout=$row['5'];
$IDstr=$row['6'];

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
					<div class="left">
					
					  <a href="#" class="link icon-only back"   >
						<i class="material-icons" style="font-size:40px;">chevron_left</i>
					</a>
					
					</div>
					<div class="center titolonav">I tuoi servizi</div>
					
				</div>
			</div>
		 <div class="page-content">
			
				
				
              <div class="content-block" id="contoospitediv">

';





$id=$IDpren;
		
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
          				<div class="content-block" style="padding:0px;">';*/
		$testo.='
		
		
			
		
		<div class="content-block-title"  style="margin-top:-10px;">Prenotazione Iniziale</div>
			<div class="list-block media-list inset" id="infoprentab" style="padding:0px; ">
			  <ul>';
			  
		
		
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
		
				 
						
			<div class="content-block-title" style="margin-top:-15px;">Servizi Extra</div>
			<div class="list-block media-list inset" id="infoprentab"  style="padding:0px;">
			  <ul>';
	$totale1=0;
		
	}
	if($ii==3){
		$query="SELECT p.ID FROM prenextra as p,prenextra2 as p2 WHERE p.IDtipo='10' AND p.IDpren IN ($IDprenc)AND p.ID=p2.IDprenextra AND p2.datacar='1'";
		$result=mysqli_query($link2,$query);
		if(mysqli_num_rows($result)>0){
			$testo.='
			</ul></div>';
			
			/*
			$testo.='</div></div>
				 <div id="tab6" class="page-content tab" >
          				<div class="content-block" style="padding:0px;">
			';*/
			$testo.='			
			<div class="content-block-title" style="margin-top:-15px;">Prodotti Acquistati</div>
			<div class="list-block media-list inset" id="infoprentab" style="padding:0px;">
			  <ul>';
		}
	}

	$query="SELECT p.ID,p.time,p2.datacar,COUNT(DISTINCT(p2.IDprenextra)),GROUP_CONCAT(DISTINCT(p.ID) SEPARATOR ','),p.tipolim,p.extra,p.modi,SUM(p2.prezzo),GROUP_CONCAT(DISTINCT(p2.IDinfop) SEPARATOR ','),SUM(p2.qta),p.sala,p.IDtipo,p.sottotip FROM prenextra as p,prenextra2 as p2 WHERE p2.pacchetto<='0' AND p2.IDpren IN ($IDprenc) AND p.ID=p2.IDprenextra AND p2.paga>'0' $qadd2 GROUP BY p.extra ORDER BY p.time";
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
						
						$num2=datafunc2($time,$row['7'],$tipolim,'',$ID);
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
			
				
		
				
		
				
				
				
				
				
				$testo.=
				'<li style="padding:0px;">
				  <div class="item-content">
					<div class="item-media" >
					<div>'.$vis.'</div>
					</div>
					<div class="item-inner">
					  <div class="item-title-row">
						<div class="item-title" style="line-height:13px; font-size:14px; font-weight:600;">'.wordwrap($servizio,25,'<br>').'<br><span style="font-size:9px; line-height:9px; color:#888; font-weight:400;">'.$qtabutt.'<br>'.$sala.'</span></div>
						<div style="font-size:13px; color:#777; font-weight:600;" align="right" >'.$butt1.'<br>'.$num2.'</div>
						
					  </div>
					</div>
				  </div>';
				 
					 
				$testo.='</li>';
			
				
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
					
					$testo.='
				<li>
				  <div class="item-content">
					<div class="item-media">
					</div>
					<div class="item-inner">
					  <div class="item-title-row">
						<div class="item-title"><span style="color:#fc0101; font-size:13px;">'.$rowsc['2'].'</span><br><span style="font-size:11px;">(Pren: '.estrainomeapp($rowsc['4']).')</span></div>
						<div class="item-after"><b>'.round($rowsc['1'],2).' €</b></div>
					  </div>
					</div>
					
				  </div>
				  
				  
				</li>
				';
					
					$totale1+=round($rowsc['1'],2);
				}
			}
			
			
			$totgen+=$totale1;

			$testo.='</ul></div>';
			
			
			
		}
	}
	
	
	

$testo.='</div></div></div>';








if(!isset($inc)){
echo $testo;
}




?>