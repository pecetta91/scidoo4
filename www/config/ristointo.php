<?php

if(!isset($inc)){
	header('Access-Control-Allow-Origin: *');
	include('../../config/connecti.php');
	include('../../config/funzioni.php');
	include('../../config/funzionilingua.php');
	$IDutente=intval($_SESSION['ID']);
	$IDstruttura=intval($_SESSION['IDstruttura']);
	
	$IDprenextra=$_GET['IDprenextra'];
	$tipo=$_GET['tipo'];
	if(is_numeric($tipo)){
		$_SESSION['visristo']=$tipo;
	}
	
	$query="SELECT time,IDpren,sottotip FROM prenextra WHERE ID='$IDprenextra' LIMIT 1";
	$result=mysqli_query($link2,$query);
	$row=mysqli_fetch_row($result);
	$timeprenextra=$row['0'];
	$IDpren=$row['1'];
	$IDsottotip=$row['2'];
	
	$time=$timeprenextra;
	
	$IDprenunit=prenotstessotav($IDpren);
	$IDprenunitmain=$IDprenunit;
	
	
	
	
	//IDsalap
	//$timeprenextra
	//$time
	//$IDsottotip
	//$IDprenunit
	
	
}else{
	$_SESSION['visristo']=1;
}

$numadd='';

$query4="SELECT p2.IDprenextra,p2.prezzo,s.servizio,p2.qta FROM prenextra2 as p2,prenextra as p,servizi as s WHERE p2.pacchetto='-$IDprenextra' AND p2.IDprenextra=p.ID AND p.extra=s.ID";
$result4=mysqli_query($link2,$query4);
$numadd2=mysqli_num_rows($result4);
if($numadd2>0){
	$numadd='<span style="font-size:15px;">'.$numadd2.'</span>';
}
$sel1='';$sel2='';
if($_SESSION['visristo']==1){
	$sel1='selected';
}else{
	$sel2='selected';
}



		$txtinto='	 
		<table style="width:100%;"><tr><td style="width:55px;">
		
		<button  class="buttsxristo color-orange '.$sel1.'"  onclick="aggintoristo('.$IDprenextra.',1);"><i class="material-icons">person</i><br>Persone</button>
		<button   class=" buttsxristo color-orange '.$sel2.'" onclick="aggintoristo('.$IDprenextra.',0);">'.$numadd.'<i class="material-icons" >shopping_cart
</i><br>Prodotti</button>
		<button  class="buttsxristo color-orange" onclick="modificaserv('.$IDprenextra.',1,0,1)"><i class="material-icons" >access_time</i><br>Orario</button>
		</td><td valign="top">
		
		 ';
	
	$txtpers='<div class="list-block" ><ul>';
	$txtprod='';
	
	if($_SESSION['visristo']==1){
	
		$query2="SELECT p2.IDinfop,p.extra  FROM prenextra as p,prenextra2 as p2 WHERE FROM_UNIXTIME(p.time,'%Y-%m-%d')=FROM_UNIXTIME('$time','%Y-%m-%d')  AND p.IDstruttura='$IDstruttura' AND  p.sottotip='$IDsottotip' AND p2.IDprenextra=p.ID AND p.modi>='0' AND p.IDpren IN($IDprenunit)  ";
		$result2=mysqli_query($link2,$query2);
		if(mysqli_num_rows($result2)>0){
			while($row=mysqli_fetch_row($result2)){
				$IDinfop=$row['0'];
				$extra=$row['1'];
				$query3="SELECT servizio FROM servizi WHERE ID='$extra' LIMIT 1";
				$result3=mysqli_query($link2,$query3);
				$row3=mysqli_fetch_row($result3);
				$servizio=$row3['0'];
				
				$nome=estrainomecli($IDinfop);
				$tipocli=estraitipocli($IDinfop);
				
				$notecli='';
				$query3="SELECT s.noteristo FROM infopren as i,schedine as s WHERE i.ID ='$IDinfop' AND i.IDcliente=s.ID AND s.noteristo!='' LIMIT 1";
				$result3=mysqli_query($link2,$query3);
				if(mysqli_num_rows($result3)>0){
					$row3=mysqli_fetch_row($result3);
					$notecli='<br><b style="color:#bb2c1d;">'.$row3['0'].'</b>';
				}
				
				$txtpers.='
				 <li class="item-content">
				  <div class="item-inner">
					<div class="item-title">'.$nome.'<br><span style="font-size:10px;font-weight:400; color:#999;">'.$tipocli.''.$notecli.'</span></div>
					<div class="item-after">'.$servizio.'</div>
				  </div>
				</li>
				';
				
			}
		}
		
		$txtpers.='</ul></div>';
	}

	if($_SESSION['visristo']!=1){

		//$query4="SELECT p2.IDprenextra,p2.prezzo,s.servizio,p2.qta FROM prenextra2 as p2,prenextra as p,servizi as s WHERE p2.pacchetto='-$IDprenextra' AND p2.IDprenextra=p.ID AND p.extra=s.ID";
		//$result4=mysqli_query($link2,$query4);
		if($numadd2>0){
			
			$txtprod.='<div><table class="tabprod">';
			while($row4=mysqli_fetch_row($result4)){
				$txtprod.='<tr><td>N.'.$row4['3'].'</td><td>'.$row4['2'].'</td><td>'.$row4['1'].'€</td><td style="width:20px;">
				<a href="#" class="button button-fill  color-red" style="color:#fff;" onClick="msgboxelimina('.$row4['0'].',33,'.$IDprenextra.',0,0)">X</a>
				
				</td></tr>';
			}
			$txtprod.='</table></div>';
		}else{
			$txtprod.='Nessun prodotto aggiunto';
		}
		
		$txtprod.='<br><br>
		<a href="#"  class="button button-fill button-raised color-orange" style="font-size:14px; width:80%; color:#fff; margin:auto;" onclick="addprodotto('."'".$IDpren.','.$IDprenextra."'".',0)">Aggiungi Prodotto</a>
		
		';
	}
if($_SESSION['visristo']==1){
	$txtinto.=$txtpers;
}else{
	$txtinto.=$txtprod;
}

$txtinto.='
</td></tr></table>

';
	
if(isset($inc)){
	$txtk[$IDsalap][$timeprenextra].=$txtinto;
}else{
	echo $txtinto;
}	
		 	

?>