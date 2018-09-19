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


$testo='<div data-page="contatti" class="page" > 
		 	<div class="navbar" id="navcal" >
				<div class="navbar-inner">
					<div class="left navbarleftsize170">
					
					  <a href="#" class="link icon-only back"    >
						<i class="material-icons">chevron_left</i>
							<strong class="stiletitolopagine">Contatti</strong>
					</a>
					</div>
					<div class="center titolonav"></div>
					<div class="right"></div>
				</div>
			</div>
			
			
			
		 <div class="page-content">
		 	<div class="content-block" id="info"> 
			<div class="content-block-title titleb">Contattaci</div>';


$query="SELECT nome,suggerimenti,latitude,longitude,mail,sito,dove,tipologia,tel FROM strutture WHERE ID='$IDstr' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);


$testo.='
				<div class="row rowlist no-gutter h30 rigainfoutili" onclick="location.href='."'tel:".$row['8']."'".'">
					<div class="col-40 campiricerca">Telefono</div>
					<div class="col-60 campiricerca">'.$row['8'].'</div>
				</div>
				<div class="row rowlist no-gutter h30  rigainfoutili"  onclick="location.href='."'mailto:".$row['4']."'".'">
					<div class="col-40 campiricerca">Mail</div>
					<div class="col-60 campiricerca">'.$row['4'].'</div>
				</div>
				<div class="row rowlist no-gutter h30  rigainfoutili"  onclick="location.href='."'http://".$row['5']."'".'">
					<div class="col-40 campiricerca">Sito Web</div>
					<div class="col-60 campiricerca">'.$row['5'].'</div>
				</div>
				<div class="row rowlist no-gutter  h30 rigainfoutili" >
					<div class="col-40 campiricerca">Località</div>
					<div class="col-60 campiricerca">'.$row['6'].'</div>
				</div>
				<div class="row rowlist no-gutter h30  rigainfoutili">
					<div class="col-40 campiricerca" >Posizione</div>
					<div class="col-60 campiricerca">'.$row['2'].' , '.$row['3'].'</div>
					
				</div>';
					
			
			  
			  


$testo.='</div></div></div>


';




if(!isset($inc)){
echo $testo;
}




?>