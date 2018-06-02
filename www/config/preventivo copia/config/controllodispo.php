<?php 
header('Access-Control-Allow-Origin: *');
include('../../../../config/connecti.php');
include('../../../../config/funzioni.php');


$IDapp=$_SESSION['app'];
$IDstr=$_SESSION['IDstruttura'];



list($yy, $mm, $dd) = explode("-", $_GET['datai']);
$timearr = mktime(0, 0, 0, $mm, $dd, $yy);

list($yy, $mm, $dd) = explode("-", $_GET['dataf']);
$timepar = mktime(0, 0, 0, $mm, $dd, $yy);


$groupid=getdisponibilita($timearr,$timepar,$IDstr);

$testo='';


$sel=0;
$firstapp=0;
$query2="SELECT ID,nome FROM appartamenti WHERE  IDstruttura='$IDstr' AND attivo ='1' AND ID NOT IN($groupid)";
			$result2=mysqli_query($link2,$query2);
			if(mysqli_num_rows($result2)>0){
				while($row2=mysqli_fetch_row($result2)){
					if($firstapp==0){$firstapp=$row2['0'];}
					$testo.='<option value="'.$row2['0'].'"';
					if($row2['0']==$_SESSION['app']){$sel=1;$testo.=' selected="selected" ';}
					$testo.='>'.$row2['1'].'</option>';
				}
			}

if($sel==0){
	$_SESSION['app']=$firstapp;
}



echo $testo;


?>