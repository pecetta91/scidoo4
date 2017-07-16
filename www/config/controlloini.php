<?php 

header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');



$IDcode=strip_tags($_POST['IDcode']);

$filename='../../config/pw.txt';
$handle = fopen($filename, "r");
$pw = fread($handle, filesize($filename));
	
$arr=explode('_', decrypt($pw,$IDcode));
$IDutente=$arr['0'];
$IDstr=$arr['1'];
$IDstrpren=$arr['2'];
if(isset($arr['3'])){
	$IDcli=$arr['3'];
	$tipocli=$arr['4'];
}

//echo $IDutente;
//print_r($arr);
$testo='error';
if(($IDutente!=0)||($IDstrpren!=0)){
	if($IDstr!=0){
		//controllo access e generazione session
		$query="SELECT ID FROM strutture WHERE IDcliente='$IDutente' AND ID='$IDstr' LIMIT 1";
		$result=mysqli_query($link2,$query);
		if(mysqli_num_rows($result)>0){
			//echo $query;
			
			$_SESSION['IDstruttura']=intval($IDstr);
			$_SESSION['ID']=intval($IDutente);
			$testo='0';
		}else{
			$query="SELECT p.IDstr FROM personale as p WHERE p.IDuser='$IDutente' AND p.IDstr='$IDstr' LIMIT 1";
			$result=mysqli_query($link2,$query);
			if(mysqli_num_rows($result)>0){
				$_SESSION['IDstruttura']=intval($IDstr);
				$_SESSION['ID']=intval($IDutente);
				
				
				$testo='0';
			}else{
				$testo='error';
			}		
		}
	}
	
	if($IDstr==0){
		if(($IDstrpren!=0)&&(is_numeric($IDstrpren))){
			//echo $IDstrpren;
			$_SESSION['IDstrpren']=$IDstrpren;
			//echo $_SESSION['IDstrpren'];
			if(isset($IDcli)){
				$_SESSION['IDcliente']=intval($IDcli);
				$_SESSION['tipocli']=1; //schedine
			}
			$testo='1';
			
		}else{
			$testo='error';
		}
	}
}else{
	$testo='error';
}

echo $testo;

?>