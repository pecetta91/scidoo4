<?php
header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');

$IDutente=intval($_SESSION['ID']);
$IDstruttura=intval($_SESSION['IDstruttura']);

$query2="SELECT ID FROM personale WHERE IDuser='$IDutente' AND IDstr='$IDstruttura' LIMIT 1";
$result2=mysqli_query($link2,$query2);
$row2=mysqli_fetch_row($result2);
$IDpers=$row2['0'];
	
$time=time()-86400*3;

$numnot=0;
$numapp=0;
if($IDpers!=0){

	$query2="SELECT ID FROM notifichetxt WHERE IDpers='$IDpers' AND letto='0'";
	$result2=mysqli_query($link2,$query2);
	$numnot=mysqli_num_rows($result2);
	
	$query2="(SELECT a.ID FROM appunti as a,appuntidest as ad WHERE a.IDstr='$IDstruttura' AND a.ID=ad.IDappunto AND ad.IDdest='$IDutente' AND a.fatto='0') UNION (SELECT ID FROM appunti  WHERE IDstr='$IDstruttura' AND IDcliente='$IDutente'  AND fatto='0') ";
	$result2=mysqli_query($link2,$query2);
	$numapp=mysqli_num_rows($result2);
}


echo $numnot.','.$numapp;

?>