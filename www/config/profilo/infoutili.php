<?php 
	include('../../../config/connecti.php');
	include('../../../config/funzioni.php');
	include('../../../config/funzionilingua.php');
	header('Access-Control-Allow-Origin: *');
	$testo='';


$IDpren=$_SESSION['IDstrpren'];
$route=$_SESSION['route'];



$query="SELECT app,gg,time,tempg,tempn,checkout,IDstruttura FROM prenotazioni WHERE IDv='$IDpren' LIMIT 1";

$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDapp=$row['0'];
$notti=$row['1'];
$check=$row['2'];
$tempg=$row['3'];
$tempn=$row['4'];
$checkout=$row['5'];
$IDstr=$row['6'];

$nomepren=estrainome($IDpren);
$timeora=oraadesso($IDstr);




$foto='immagini/'.getfoto($IDserv,4);


echo '<input type="hidden" id="funcreload" value="navigation(25,'.$ID.',0,1)">';


$testo='<div data-page="infoutili" class="page" > 
		 	<div class="navbar" id="navcal" >
				<div class="navbar-inner">
					<div class="left navbarleftsize170">
					
					  <a href="#" class="link icon-only back"    >
						<i class="material-icons">chevron_left</i>
							<strong class="stiletitolopagine">Numeri Utili</strong>
					</a>
					</div>
					<div class="center titolonav"></div>
					<div class="right"></div>
				</div>
			</div>
			
			
			
		 <div class="page-content">
		 	<div class="content-block" id="info"> 
			<div class="content-block-title titleb">Numeri utili</div>
				
			';


		$query="SELECT numero,testo,descrizione FROM numeriutili WHERE IDstr='$IDstr'";
		$result=mysqli_query($link2,$query);
		if(mysqli_num_rows($result)>0){
			while($row=mysqli_fetch_row($result)){
				 $testo.='
			  
				  <div class="row rowlist no-gutter h30 rigainfoutili" onclick="location.href='."'tel:".$row['0']."'".'"">	
						<div class="col-35 campiricerca" style="color:#ec008c; font-size:18px; text-align:center;"><strong>'.$row['0'].'</strong></div>
						<div class="col-65 campiricerca"><strong>'.$row['1'].'</strong><br/>
						
						<span style="font-size:11px; color:#666;">
						'.$row['2'].'</span></div>
					</div>

				  ';
				
			}
			
		}	  
			 


$testo.='</div></div></div>


';



/*<div class="row rowlist no-gutter h30 rigainfoutili" onclick="location.href='."'tel:112'".'"">
					<div class="col-20 campiricerca center">112</div>
					<div class="col-80 campiricerca" >Carabinieri</div>
				</div>	
				<div class="row rowlist no-gutter h30 rigainfoutili" onclick="location.href='."'tel:113'".'"">	
					<div class="col-20 campiricerca center" >113</div>
					<div class="col-80 campiricerca" >Polizia di Stato</div>
				</div>	
				<div class="row rowlist no-gutter h30 rigainfoutili" onclick="location.href='."'tel:115'".'"">	
					<div class="col-20 campiricerca center" >115</div>
					<div class="col-80 campiricerca">Vigili del Fuoco</div>
				</div>	
				*/


if(!isset($inc)){
echo $testo;
}




?>