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


$testo='<div data-page="suggerimenti" class="page" > 
		 	<div class="navbar" id="navcal" >
				<div class="navbar-inner">
					<div class="left">
					
					  <a href="#" class="link icon-only back"    >
						<i class="material-icons" style="font-size:40px;">chevron_left</i>
					</a>
					</div>
					<div class="center titolonav">Suggerimenti della Struttura</div>
				</div>
			</div>
			
			
			
		 <div class="page-content">
		 	<div class="content-block" id="sugg"> ';
			  
			  
			  
		$query="SELECT suggerimenti FROM strutture WHERE ID='$IDstr' LIMIT 1";
		$result=mysqli_query($link2,$query);
		$row=mysqli_fetch_row($result);
			 
		$testo.='<p style="text-align:left; padding:10px; padding-left:25px; color:#333; font-weight:400; font-size:16px; line-height:23px;">'.$row['0'].'</p>';
			  
			  
	
					

$testo.='</div></div></div>


';




if(!isset($inc)){
echo $testo;
}




?>