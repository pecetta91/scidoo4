<?php


	header('Access-Control-Allow-Origin: *');
	include('../../config/connecti.php');
	include('../../config/funzioni.php');
	include('../../config/funzionilingua.php');


$IDstruttura=$_SESSION['IDstruttura'];

if(isset($_GET['dato0']))
{
	$IDprenextra=$_GET['dato0']; //IDprenextra
}



$var=0;
if(isset($_GET['dato1'])){
	$var=$_GET['dato1'];
}



	
$numadd='';
$txtprod='

<input type="hidden" value="'.$IDprenextra.'" id="IDprenextrapopover">
<input type="hidden" value="'.$var.'" id="var">
';
$query4="SELECT p2.IDprenextra,p2.prezzo,s.servizio,p2.qta FROM prenextra2 as p2,prenextra as p,servizi as s WHERE p2.pacchetto='-$IDprenextra' AND p2.IDprenextra=p.ID AND p.extra=s.ID";
$result4=mysqli_query($link2,$query4);
$numadd2=mysqli_num_rows($result4);
if($numadd2>0){
	$numadd='<span style="font-size:15px;">'.$numadd2.'</span>';
}

if($numadd2>0){
			
			$txtprod.='<div><table class="tabprod">';
			while($row4=mysqli_fetch_row($result4)){
				$txtprod.='<tr style="height:60px"><td>N.'.$row4['3'].'</td><td>'.$row4['2'].'</td><td>'.$row4['1'].'€</td><td style="width:20px;">
				<a href="#" id="eliminabox" class="button button-fill  color-red" style="color:#fff;" onClick="msgboxelimina('.$row4['0'].',33,'.$IDprenextra.',0,0)">X</a>
				
				</td></tr>';
			}
			$txtprod.='</table></div>';
		}else{
			$txtprod.='Nessun prodotto aggiunto';
		}


if($var==1){
	
	echo $txtprod;
}else{
	echo '

	<div class="picker-modal " id="popoverord" >
		  <div class="toolbar">
			<div class="toolbar-inner">
			  <div class="left">
			  <a href="#" onclick="myApp.closeModal();aggiungipiatti('.$IDprenextra.');">Aggiungi piatti</a></div>
			  <div class="right"><a href="#" class="close-picker">Close</a></div>
			</div>
		  </div>
		  <div class="picker-modal-inner ">
		 <div class="page-content" id="ordinazione" style="background-color: white"> 
		  '.$txtprod.'
		  </div>
	</div>
	</div>

	';
	
	
	
}





?>



