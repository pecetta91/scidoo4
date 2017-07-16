<?php 
header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');

$IDutente=$_SESSION['ID'];
$IDstruttura=$_SESSION['IDstruttura'];
$val=mysqli_real_escape_string($link2,$_GET['val']);

$testo='';

if(isset($_SESSION['listIDsotto'])){
	if($_SESSION['listIDsotto']==2){
		$query="SELECT s.ID,s.servizio,s.prezzo,t.tipolimite,s.IDtipo,s.durata FROM servizi as s,tiposervizio as t WHERE s.IDstruttura='$IDstruttura' AND s.IDtipo ='".$_SESSION['listIDsotto']."' AND s.IDtipo=t.ID AND t.tipolimite!='4' AND s.servizio LIKE '%$val%' LIMIT 10";
	}else{
		$query="SELECT s.ID,s.servizio,s.prezzo,t.tipolimite,s.IDtipo,s.durata FROM servizi as s,tiposervizio as t  WHERE s.IDstruttura='$IDstruttura' AND s.IDsottotip='".$_SESSION['listIDsotto']."' AND t.ID=s.IDtipo AND t.tipolimite!='4' AND s.servizio LIKE '%$val%' LIMIT 10";
	}
}else{
	$query="SELECT s.ID,s.servizio,s.prezzo,t.tipolimite,s.IDtipo,s.durata FROM servizi as s,tiposervizio as t WHERE s.IDstruttura='$IDstruttura' AND s.servizio LIKE '%$val%' AND s.IDtipo=t.ID AND t.tipolimite!='4' ORDER BY servizio LIMIT 20";
}
$result=mysqli_query($link2,$query);
if(mysqli_num_rows($result)>0){
	while($row=mysqli_fetch_row($result)){
	$testo.='
		 <li onclick="selectservice('.$row['0'].','.$row['3'].','.$row['4'].','.$row['5'].')">
            <label class="item-content">
              <div class="item-inner" >
                <div class="item-title">'.$row['1'].'</div>
				 <div class="item-after">'.$row['2'].' €</div>
              </div>
            </label>
          </li>
	
	';
	}
}

echo $testo;
?>