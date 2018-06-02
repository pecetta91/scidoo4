<?php
header('Access-Control-Allow-Origin: *');
include('../../../config/connecti.php');
include('../../../config/funzioni.php');
//include('../../../config/preventivoonline/config/funzioniprev.php');

$IDutente=$_SESSION['ID'];
$IDstruttura=$_SESSION['IDstruttura'];
$IDrequest=$_SESSION['IDrequest'];




$txtp='';

$query="SELECT IDstr,notti,timearr,stato,checkout FROM richieste WHERE ID='$IDrequest' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDstruttura=$row['0'];
$gg=$row['1'];
$ggric=$gg;
$timearr=$row['2'];
$datagg=date('Y-m-d',$timearr);
$stato=$row['3'];
$checkout=$row['4'];
$ggsett=date('N',$timearr);



$ac1='';
$ac2='';
if(!isset($_SESSION['tabconto'])){
	$ac1='active';
	$tipoconto=0;
}else{
	switch($_SESSION['tabconto']){
		default:
			$ac1='active';
			$tipoconto=0;
		break;
		case 1:
			$ac2='active';
			$tipoconto=1;
		break;
	}
	
	
}

echo '
<br>
	<p class="buttons-row" style="width:90%; margin:auto;">
	  <a href="#" onclick="tabconto(0)" id="conto0" class="button conto1 '.$ac1.'" >Il Conto</a>
	  <a href="#" onclick="tabconto(1)" id="conto1" class="button conto1 '.$ac2.'" >Orari</a>
	</p>
	<br>
	<div id="contenutoconto">

';

$inc=1;
include('ilconto.php');

echo '</div>';
			 
?>			 
			 