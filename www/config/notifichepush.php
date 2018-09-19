<?php 

header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');

$IDnotpush=strip_tags($_POST['IDnotpush']);
$tipo=strip_tags($_POST['tipo']);

$IDutente=$_SESSION['ID'];

if($IDnotpush!=0){
	if($IDutente!=0){
			//controlla ed inserisce IDdevice
			$query="SELECT ID FROM deviceIDpers WHERE IDcliente='$IDutente' AND code='$IDnotpush' LIMIT 1 ";
			$result=mysqli_query($link2,$query);
			if(mysqli_num_rows($result)==0){
				$time=time();
				$query="INSERT INTO deviceIDpers VALUES (NULL,'$IDutente','$IDnotpush','$time')";
				$result=mysqli_query($link2,$query);
			}else{
				if($tipo==-1){
					$query="DELETE FROM deviceIDpers WHERE IDcliente='$IDutente' AND code='$IDnotpush' LIMIT 1";
					$result=mysqli_query($link2,$query);
				}else{
					$time=time();
					$query="UPDATE deviceIDpers SET lasttime='$time' WHERE IDcliente='$IDutente' AND code='$IDnotpush'";
					$result=mysqli_query($link2,$query);
				}
			}
	}
}

//echo $testo;

?>