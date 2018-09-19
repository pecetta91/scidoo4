<?php 
if(!isset($inc)){
	include('../../../config/connecti.php');
	include('../../../config/funzioni.php');
	header('Access-Control-Allow-Origin: *');
}


$h=$_GET['h'];
$_SESSION['height']=$h-65;


$IDpren=$_SESSION['IDstrpren'];
$query="SELECT IDstruttura,time,checkout,gg FROM prenotazioni WHERE IDv='$IDpren' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDstruttura=$row['0'];
$timearr=$row['1'];
$dataarr=date('Y-m-d',$row['1']);
$datapar=date('Y-m-d',$row['2']);
$gg=$row['3'];



$query="SELECT latitude,longitude,nome FROM strutture WHERE ID='$IDstruttura'";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$lat=$row['0'];
$lon=$row['1'];
$nome=$row['2'];
echo '<input type="hidden" id="nomestr" value="'.$nome.'">';


$testo='
<div class="content-block-title" style="color:#394baa; margin-left:0px; font-size:12px; font-weight:600; text-transform:uppercase;">
<table style="margin:0px; margin-bottom:-10px;"><tr><td><i class="material-icons">home</i>
</td><td>
La tua prenotazione
</td></tr></table>
</div>
<div class="list-block">
      <ul>
        <li class="item-content" alt="La tua prenotazione" onclick="navigationtxt(24,0,'."'contenutodiv'".',0);titolomenu(this)">
          <div class="item-inner">
            <div class="item-title menusx">Prenotazione</div>
            <div class="item-after"></div>
          </div>
        </li>
		
		<li class="item-content" alt="I tuoi servizi" onclick="navigationtxt(23,0,'."'contenutodiv'".',0);titolomenu(this)">
          <div class="item-inner">
            <div class="item-title menusx">I Servizi Prenotati</div>
            <div class="item-after"></div>
          </div>
        </li>';
	
		if($gg>0){
			$testo.='
			 <li class="item-content" alt="Temperatura Alloggio" onclick="navigationtxt(25,0,'."'contenutodiv'".',13);titolomenu(this)">
			  <div class="item-inner">
				<div class="item-title menusx">Temperatura Alloggio</div>
				<div class="item-after"></div>
			  </div>
			</li>';
		}
		
	$testo.='
		
		<li class="item-content" alt="Il Conto" onclick="navigationtxt(27,0,'."'contenutodiv'".',0);titolomenu(this)">
          <div class="item-inner">
            <div class="item-title menusx">Il conto</div>
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
						<div class="list-block">
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
										$testo.='<li class="item-content" id="Menu '.$row2['1'].'" onclick="navigationtxt(26,'.$row2['0'].','."'contenutodiv'".',0);titolomenu(this)">
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
<div class="list-block" style="margin-top:-10px;">
      <ul>
	  
	  <li class="accordion-item" style="padding:0px;">
				  <a href="#" class="item-link item-content" onclick="">
					<div class="item-inner">
					  <div class="item-title-row menusx">
					  	
					 	 Elenco Servizi
						
					  </div>
					</div>
				  </a>
				   <div class="accordion-item-content" style=" padding:0px;font-size:11px; background:#f1f1f1;">
						<div class="content-block"  class="details" style="padding:0px; ">
						<div class="list-block">
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
									
					$testo.='<li style=" border-left:solid 5px #'.$row['2'].'; padding:0px;">
					
					
					<a href="#" class="item-link item-content" alt="'.$row2['1'].'<br><span>Clicca per prenotare</span>" onclick="navigationtxt(28,'.$row2['0'].','."'contenutodiv'".',0);titolomenu(this)">
					
					
								  <div class="item-inner" >
									<div class="item-title menusx2" style=" padding-left:0px;color:#'.$row['2'].'">'.$row2['1'].'</div>
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
				  <li class="accordion-item" style="padding:0px; border-left:solid 5px #'.$row['2'].'">
					  <a href="#" class="item-link item-content" onclick="">
						  <div class="item-inner">
							<div class="item-title menusx2" style="color:#'.$row['2'].'">'.$row['1'].'</div>
						  </div>
				 </a>
					   <div class="accordion-item-content" style=" padding:0px;font-size:11px;">
							<div class="content-block"  class="details" style="padding:0px; ">
							<div class="list-block">
							 <ul>
							';
							
							$query2="SELECT ID,sottotipologia FROM sottotipologie WHERE IDmain='".$row['0']."' AND IDstr='$IDstruttura' ORDER BY ord";
							$result2=mysqli_query($link2,$query2);
							if(mysqli_num_rows($result2)>0){
								while($row2=mysqli_fetch_row($result2)){
									
									$testo.='<li class="item-content" alt="'.$row2['1'].'<br><span>Clicca per prenotare</span>" onclick="navigationtxt(28,'.$row2['0'].','."'contenutodiv'".',0);titolomenu(this)">
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


$testo.='</ul></div>

</div></div>
	</li>
	  
	</ul></div>



<div class="content-block-title" style="color:#e69015; margin-left:0px; margin-top:-20px; font-size:12px; font-weight:600;">

<table style="margin-left:0px; margin-bottom:0px;"><tr><td><i class="material-icons">place</i>
</td><td>
COSA OFFRE IL TERRITORIO
</td></tr></table>
</div>
<div class="list-block" style="margin-top:-10px;">
      <ul>
	  
	  
	  <li class="accordion-item" style="padding:0px;">
				  <a href="#" class="item-link item-content" onclick="">
					<div class="item-inner">
					  <div class="item-title-row menusx">
					  	
					  Scopri il Territorio
						
					  </div>
					</div>
				  </a>
				  
				   <div class="accordion-item-content" style=" padding:0px;font-size:11px; background:#f1f1f1;">
						<div class="content-block"  class="details" style="padding:0px; ">
						<div class="list-block">
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
								$testo.='<li class="item-content" alt="'.$row['1'].'" onclick="navigationtxt(29,'.$row['0'].','."'contenutodiv'".',10);titolomenu(this)">
							  <div class="item-inner">
								<div class="item-title menusx2" >'.$row['1'].'</div>
								<div class="item-after"><span class="badge" style="color:#333;background:#'.$row['2'].'">'.$num.'</span></div>
							  </div>
							</li>';
								
								
							}
						}
					}
					


	$testo.='					
	<ul></div>
	</div>
					  </div>
			</li>
	  
	  
	  </ul></div><br>
	  
	  
	  
	  <div class="list-block">
      <ul>
	   <li class="item-content" onclick="esci();myApp.closePanel('."'left'".');">
          <div class="item-inner">
            <div class="item-title menusx2" style="color:#c01313;">ESCI DA SCIDOO</div>
          </div>
        </li>
	  
	  
	  </ul></div>
	  
	  

	  
	  ';


echo $testo;



?>