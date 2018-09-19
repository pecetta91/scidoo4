<?php 
header('Access-Control-Allow-Origin: *');
include('../../../../config/connecti.php');
include('../../../../config/funzioni.php');
include('../../../../config/preventivoonline/config/funzioniprev.php');
$testo='';

$IDpren=$_SESSION['IDstrpren'];

$IDserv=$_GET['IDserv'];
$restr=$_GET['restr'];
$giorno=$_GET['Giorno'];
$data=date('Y-m-d',$giorno);


$query="SELECT IDstruttura FROM prenotazioni WHERE IDv='$IDpren' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDstruttura=$row['0'];

$query="SELECT s.durata,t.tipolimite FROM servizi as s,tiposervizio as t WHERE s.ID='$IDserv' AND s.IDstruttura='$IDstruttura' AND s.IDtipo=t.ID LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$durata=$row['0'];
$tipolim=intval($row['1']);

$IDrestrmain=getrestrmain($IDstruttura);

$prezzo=0;

switch($tipolim){
		case 1:
			$prezzo=calcolaprezzoserv($IDserv,$data,$IDrestrmain.',',$IDstruttura,0,$IDpren);
		break;
		case 2:
			$prezzo=calcolaprezzoserv($IDserv,$data,$restr,$IDstruttura,0,$IDpren);
		break;
				
}




echo $prezzo;

		
?>