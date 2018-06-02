<?php
header('Access-Control-Allow-Origin: *');
include('../../../config/connecti.php');
include('../../../config/funzioni.php');
include('../../../config/preventivoonline/config/funzioniprev.php');

$IDutente=$_SESSION['ID'];
$IDstruttura=$_SESSION['IDstruttura'];
$IDrequest=$_SESSION['IDrequest'];

$query="SELECT IDstr,notti,timearr,stato,checkout,agenzia FROM richieste WHERE ID='$IDrequest' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDstruttura=$row['0'];
$gg=$row['1'];
$ggric=$gg;
$timearr=$row['2'];
$datagg=date('Y-m-d',$timearr);
$stato=$row['3'];
$checkout=$row['4'];
$IDagenzia=$row['5'];
$ggsett=date('N',$timearr);

/*
$IDsog='';
$qtap=0;
$IDrestrmain=0;
$query2="SELECT IDrestr,COUNT(*),GROUP_CONCAT(ID SEPARATOR ',') FROM richiestep WHERE IDreq='$IDrequest' GROUP BY IDrestr";
$result2=mysqli_query($link2,$query2);
if(mysqli_num_rows($result2)){
	while($row2=mysqli_fetch_row($result2)){
		if($IDrestrmain==0){$IDrestrmain=$row2['0'];}
		$restr[$row2['0']]=$row2['1'];
		$qtap+=$row2['1'];
		$IDsog.=$row2['2'].',';
	}
}
*/
$testo='';

	
	
	$IDapp=$_SESSION['app'];
	




	
	$testo.='
	<br>
	<p class="buttons-row" style="width:90%; margin:auto;">
	  <a href="#" onclick="tabservizi(5)" id="step15" class="button step1 active" >Servizi</a>';


	$query="SELECT IDstr FROM setupreg WHERE IDstr='$IDstruttura' LIMIT 1";
	$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
		$testo.='<a href="#" class="button step1"  onclick="tabservizi(7)" id="step17">Voucher</a>';
	}
		
	$query="SELECT a.ID FROM cofanetti as c,agenzie as a WHERE a.IDstr='$IDstruttura' AND c.IDagenzia=a.ID LIMIT 1";
	$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){ 
		$testo.='<a href="#" class="button step1"  onclick="tabservizi(8)" id="step18">Cofanetti</a>';
	}
	 
	  
	$testo.='</p>
	<br>
	<div id="contenutoservizi">';
	
	
	$inc=1;
	$tipo=5;
	include('elencoserv.php');
	


echo $testo.'';
			 