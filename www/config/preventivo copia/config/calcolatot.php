<?php 
header('Access-Control-Allow-Origin: *');
include('../../../../config/connecti.php');
include('../../../../config/funzioni.php');

$IDrequest=$_SESSION['IDrequest'];

$queryp="SELECT SUM(prezzo) FROM prezzip WHERE IDreq='$IDrequest'";
$resultp=mysqli_query($link2,$queryp);
$rowp=mysqli_fetch_row($resultp);
$prezzopern=round($rowp['0'],2);

$queryp="SELECT SUM(prezzo) FROM oraripren WHERE IDreq='$IDrequest' ";
$resultp=mysqli_query($link2,$queryp);
$rowp=mysqli_fetch_row($resultp);
$totale=round($rowp['0'],2);
$tot=$prezzopern+$totale;

echo $tot;


?>