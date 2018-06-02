<?php

	header('Access-Control-Allow-Origin: *');
	include('../../../config/connecti.php');
	include('../../../config/funzioni.php');
	include('../../../config/preventivoonline/config/funzioniprev.php');
	
	$IDutente=$_SESSION['ID'];
	$IDstruttura=$_SESSION['IDstruttura'];
	$IDrequest=$_SESSION['IDrequest'];
	
	$query="SELECT IDstr,notti,timearr,checkout FROM richieste WHERE ID='$IDrequest' LIMIT 1";
	$result=mysqli_query($link2,$query);
	$row=mysqli_fetch_row($result);
	$IDstruttura=$row['0'];
	$gg=$row['1'];
	$ggric=$gg;
	$timearr=$row['2'];
	$datagg=date('Y-m-d',$timearr);
	$checkout=$row['3'];
	$ggsett=date('N',$timearr);
	$testo='';
	
	
	
	
	
	$IDserv=$_POST['IDpacc'];
	$nump=$_POST['nump'];
	$tipopacchetto=$_POST['tipopacc'];
	$relo=$_POST['relo'];
	
	
	// onclick="selpacchettoprev('.$IDserv.','.$nump.','.$tipopacchetto.')"
	
	if($relo!=1){echo '<div class="popup" id="popup">';}
	
	
	echo '
			<div class="navbar">
				<div class="navbar-inner">

					<div class="center">Seleziona Ospiti</div>
					<div class="right"  onclick="myApp.closeModal();"><i class="icon f7-icons">close</i></div>
				</div>
			</div>

	<input type="hidden" id="IDpaccselect" value="'.$IDserv.'">
	<input type="hidden" id="nump" value="'.$nump.'">
	<input type="hidden" id="tipopacchetto" value="'.$tipopacchetto.'">

	<div class="content-block">
	<div class="bottombarpren" style="z-index:999;" align="center">

	<button style="border-radius:0px; font-weight:600;height:50px; border:none; color:#fff; width:92%; float:none; text-transform:uppercase; font-size:14px; background:#0064d4;margin:5px;" onclick="selpacchettoprev('.$IDserv.','.$nump.','.$tipopacchetto.')">Salva</button>

	  </div>

		<div class="list-block">
  <ul>
    
	
	';
	
	$servizio='';
	
	
$query2="SELECT r.IDrestr,r.ID,t.restrizione,r.paccnotti FROM richiestep as r,tiporestr as t WHERE r.IDreq='$IDrequest' AND r.IDrestr=t.ID ";
	$result2=mysqli_query($link2,$query2);
	if(mysqli_num_rows($result2)){
		while($row2=mysqli_fetch_row($result2)){
			
			$IDpersona=$row2['1'];
			$pacc=0;
			$tipopacc=0;
			//$tipolimpacc=0;
			
			$query3="SELECT o.IDserv,o.tipolim,o.ID FROM oraripren as o,oraripren2 as o2 WHERE o.IDreq='$IDrequest' AND o.tipolim IN(5,7,8) AND o.ID=o2.IDoraripren AND o2.IDsog='$IDpersona'   LIMIT 1";
				
				$result3=mysqli_query($link2,$query3);
				if(mysqli_num_rows($result3)>0){
					$row3=mysqli_fetch_row($result3);
					$pacc=$row3['0'];
					$tipopacc=$row3['1'];
					//$tipopacc=$tipolimpacc;
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
			
			//se tipopacc ==1 ti da la possiblita di eliminare 
			$check='';
			$dis='';
			$classe='class="checkpers"';
			if($tipopacc==0){
				if($pacc!=0){
					$dis="disabled";
					$check='checked';
					$classe='';
				}
			}else{
				$dis="disabled";
				$check='checked';
				$classe='';
			}
			
			$rim='';
			$add='';
			switch($tipopacc){
				case 5:
					if($dis!=''){
					$rim='<div class="item-after" style="padding-top:10px;height:100%;" onclick="eliminapaccprev('.$IDpersona.',0)">Rimuovi</div>';
					
					$query3="SELECT servizio FROM servizi WHERE ID='$pacc' LIMIT 1";
					$result3=mysqli_query($link2,$query3);
					$row3=mysqli_fetch_row($result3);
					$servizio=$row3['0'];
					$add.='<br><span style="font-size:10px; color:#666;">'.$row3['0'].'</span>';
				}
				break;
				case 7:
					$query3="SELECT idea FROM ideeregalosold WHERE ID='$pacc' LIMIT 1";
					$result3=mysqli_query($link2,$query3);
					$row3=mysqli_fetch_row($result3);
					$servizio=$row3['0'];
					$add.='<br><span style="font-size:10px; color:#666;">Voucher: '.$row3['0'].'</span>';
					$rim='<div class="item-after" style="padding-top:10px;height:100%;" onclick="eliminapaccprev('.$pacc.','.$tipopacc.')">Rimuovi</div>';
				break;
				case 8:
					$query3="SELECT cofanetto FROM cofanetti WHERE ID='$pacc' LIMIT 1";
					$result3=mysqli_query($link2,$query3);
					$row3=mysqli_fetch_row($result3);
					$servizio=$row3['0'];
					$add.='<br><span style="font-size:10px; color:#666;">Cofanetto: '.$row3['0'].'</span>';
					$rim='<div class="item-after" style="padding-top:10px;height:100%;" onclick="eliminapaccprev('.$pacc.','.$tipopacc.')">Rimuovi</div>';
				break;
				
			}
			
			
			
			echo '
				<li>
				  <label class="label-checkbox item-content">
					<input type="checkbox" '.$classe.'  '.$dis.' id="'.$IDpersona.'" '.$check.'>
					<div class="item-media">
					  <i class="icon icon-form-checkbox"></i>
					</div>
					<div class="item-inner">
					  <div class="item-title">'.$arrnum[$row2['0']].'&deg; '.$row2['2'].$add.'</div>
					  '.$rim.'
					</div>
				  </label>
				</li>
			
			';
			/*
			
			$testo.='<tr><td class="tdtit">'.$arrnum[$row2['0']].'&deg; '.$row2['2'].'</td>';
			
			$checked='';
			$dis='';
			$testo.='<td style="text-align:center;">';
			if($tipopacc==0){
				if($pacc==0){
					$testo.='<input type="checkbox"  style="width:20px;" id="'.$IDpersona.'" class="checkpers">';
				}else{
					$testo.="Ha gia' un pacchetto";
				}
			}else{
				$testo.="Ha gia' un pacchetto";
			}
			$testo.='</td></tr>';*/
		}
	}

			
   echo ' </ul></div>';
   
   
   if($IDserv!=0){
	   
	   	echo '<br><div style="font-weight:600; color:#1fa55b;  font-size:14px; padding:10px;">';
		switch($tipopacchetto){
				case 5:
					$query3="SELECT servizio FROM servizi WHERE ID='$IDserv' LIMIT 1";
					$result3=mysqli_query($link2,$query3);
					$row3=mysqli_fetch_row($result3);
					$servizio=$row3['0'];
				
					$IDrestrdef=getrestrmain($IDstruttura);
					echo 'PACCHETTO - '.$servizio.':<br><br>'.contpacchetto($IDserv,0,-1,0,$IDrestrdef,$IDstruttura);
				break;
				case 7:
					$query3="SELECT idea FROM ideeregalosold WHERE ID='$IDserv' LIMIT 1";
					$result3=mysqli_query($link2,$query3);
					$row3=mysqli_fetch_row($result3);
					$servizio=$row3['0'];
					echo 'VOUCHER - '.$servizio.':<br><br>'.contregalo($IDserv,1,$IDstruttura);
					
				break;
				case 8:
					$query3="SELECT cofanetto FROM cofanetti WHERE ID='$IDserv' LIMIT 1";
					$result3=mysqli_query($link2,$query3);
					$row3=mysqli_fetch_row($result3);
					$servizio=$row3['0'];
					echo 'COFANETTO - '.$servizio.':<br><br>'.contregalo($IDserv,2,$IDstruttura);
				break;
				
			}
			echo '</div>';
   }
   
   echo '</div>';
   
   
   
   if($relo!=1){
   echo '
	</div>
	';
	
   }
	
?>


			 