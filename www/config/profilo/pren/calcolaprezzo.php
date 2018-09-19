<?php 
header('Access-Control-Allow-Origin: *');
include('../../../../config/connecti.php');
include('../../../../config/funzioni.php');
include('../../../../config/preventivoonline/config/funzioniprev.php');

$IDpren=$_SESSION['IDstrpren'];

$query="SELECT app,gg,time,IDstruttura,checkout FROM prenotazioni WHERE IDv='$IDpren' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDapp=$row['0'];
$notti=$row['1'];
$check=$row['2'];
$IDstruttura=$row['3'];
$checkout=$row['4'];



$IDserv=$_GET['idserv'];
$IDpers=$_GET['idpers'];
$timepren=$_GET['timepren'];
$sala=$_GET['sala'];

if(substr($IDpers,-1,1)==','){
	$IDpers=substr($IDpers,0,-1);
}

if(substr($timepren,-1,1)==',')
{
  $timepren=substr($timepren,0,-1);
}



$query="SELECT prezzo,durata,esclusivo FROM servizi WHERE ID='$IDserv' AND IDstruttura='$IDstruttura'  ";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$prezzo=$row['0'];
$durata=$row['1'];
$esclusivo=$row['2'];


$txt=' <input type="hidden" id="idserv" value="'.$IDserv.'" >
			  <input type="hidden" id="idpers" value="'.$IDpers.'" >
			   <input type="hidden" id="time" value="'.$timepren.'" >
			    <input type="hidden" id="sala" value="'.$sala.'" >';

						
			
						$ID=explode(',',$IDpers);
					foreach($ID as $persone)
					{
					
						$query="SELECT IDrest FROM infopren WHERE IDpren='$IDpren' AND pers='1' AND ID='$persone' ";
						$result=mysqli_query($link2,$query);
						$IDrestr=$row['2'].',';	
						$calcprezzo=calcolaprezzoserv($IDserv,$time,$IDrestr,$IDstruttura,0,$IDpren,0,$durata);
					}
	
				
				
echo $txt;
?>