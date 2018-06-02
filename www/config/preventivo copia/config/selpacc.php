<?php 
header('Access-Control-Allow-Origin: *');
include('../../../../config/connecti.php');
include('../../../../config/funzioni.php');
include('../../../../config/funzionilingua.php');

$IDrequest=$_SESSION['IDrequest'];

$query="SELECT IDstr,notti,timearr,checkout FROM richieste WHERE ID='$IDrequest' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDstruttura=$row['0'];
$gg=$row['1'];
$timearr=$row['2'];
$checkout=$row['3'];

$query="SELECT checkin,checkout,oraf FROM strutture WHERE ID='$IDstruttura' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$checkstr=$row['0'];
if($gg==0){
	$checkoutstr=$row['2'];	
}else{
	$checkoutstr=$row['1'];
}

$IDapp=$_POST['IDapp'];
$pacc=$_POST['IDpacc'];


$query="DELETE FROM oraripren  WHERE IDreq='$IDrequest'";
$result=mysqli_query($link2,$query);
//$_SESSION['alloggioauto']=0;
//estrazione IDapp
/*
if($gg>0){
	if(isset($_SESSION['soluz'])){
		 
		//controlla se ci sono soluzioni gia fatte o bisogno andare a selezionare quale alloggio e' disponibile
		//deve creare delle soluzioni anche normalmente
		$soluz=$_SESSION['soluz'];
		$arrappcat=$_SESSION['appcat'];
		$ggstart=$_SESSION['ggstart'];	
		
		if($cat!=0){	
			foreach ($soluz as $IDapp2=>$dato){
				if($IDapp==0){
					foreach ($arrappcat as $key2=>$dato2){
						if(in_array($IDapp2,$dato2)){
							$IDcatinto=$key2;
							break;
						}
					}
					if($IDcatinto==$cat){
						foreach ($dato as $key2=>$dato2){
							if($key2==$ggstart){
								foreach ($dato2 as $lung){
									//echo $lung.'-'.$gg.'<br>';
									if($lung==$gg){
										$IDapp=$IDapp2;
										break;
									}
								}
							}					
						}
					}
				}
			}
		}else{
			foreach ($soluz as $IDapp2=>$dato){
				if($IDapp==0){
					foreach ($arrappcat as $key2=>$dato2){
						if(in_array($IDapp2,$dato2)){
							$IDcatinto=$key2;
							break;
						}
					}
						foreach ($dato as $key2=>$dato2){
							if($key2==$ggstart){
								foreach ($dato2 as $lung){
									//echo $lung.'-'.$gg.'<br>';
									if($lung==$gg){
										$IDapp=$IDapp2;
										break;
									}
								}
							}					
						}
				}
				
			}
			
			
			
		}
	}

}
*/
if(($IDapp!=0)||($gg==0)){
	
	
	
	//stepprev($IDrequest,2,$pacc);
	//stepprev($IDrequest,3,$cat);
	
	
	if($gg==0){
	
		$queryc="SELECT ID FROM categorie WHERE tipo=1 LIMIT 1";
		$resultc=mysqli_query($link2,$queryc);
		if(mysqli_num_rows($resultc)>0){
			$rowc=mysqli_fetch_row($resultc);
			$_SESSION['catpreventivo']=$rowc['0'];
		}
	
		list($yy, $mm, $dd) = explode("-", date('Y-m-d',$timearr));
		$timearr = mktime(0, 0, 0, $mm, $dd, $yy);
		$checkout=$timearr+$checkoutstr;
		$timearr=$timearr+$checkstr;
		
	}else{	
	
		$cat=$IDapp.'_'.$timearr.'_'.$gg;//__ per eventuale doppio alloggio
	
		$arr=explode('__',$cat);
		$arr2=explode('_',$arr['0']);
		
		$queryc="SELECT categoria FROM appartamenti WHERE ID='".$arr2['0']."' LIMIT 1";
		$resultc=mysqli_query($link2,$queryc);
		$rowc=mysqli_fetch_row($resultc);
		$_SESSION['catpreventivo']=$rowc['0'];
	
		$arr=explode('__',$cat);
		$ggtot=0;
		$timearr=999999999999999;
		foreach($arr as $dato){
			$arr2=explode('_',$dato);
			$IDapp=$arr2['0'];
			$timearr2=$arr2['1'];
			$gg=$arr2['2'];
			$ggtot+=$gg;
			if($timearr2<$timearr)$timearr=$timearr2;
		}
		
		list($yy, $mm, $dd) = explode("-", date('Y-m-d',$timearr));
		$timearr = mktime(0, 0, 0, $mm, $dd, $yy);
		
		//aggiorna richiesta
		$checkout=$timearr+$ggtot*86400+$checkoutstr;
		$timearr=$timearr+$checkstr;
		
		$query="UPDATE richieste SET timearr='$timearr',checkout='$checkout',notti='$ggtot' WHERE ID='$IDrequest' LIMIT 1";
		$result=mysqli_query($link2,$query);
	
	}
	
	
	
	$query="DELETE FROM  richiestecat  WHERE IDreq='$IDrequest' ";
	$result=mysqli_query($link2,$query);
	
	$inc=1;
	if($pacc!=0){
		$IDsog2='';
		$query2="SELECT GROUP_CONCAT(ID SEPARATOR ',') FROM richiestep WHERE IDreq='$IDrequest' GROUP BY IDrestr";
		$result2=mysqli_query($link2,$query2);
		if(mysqli_num_rows($result2)){
			while($row2=mysqli_fetch_row($result2)){
				$IDsog2.=$row2['0'].',';
			}
		}
			
		$IDsog2=substr($IDsog2, 0, strlen($IDsog2)-1);
		include('../../../../config/preventivo/config/selpers.php');
		
		
		//arrotonda
		
		
		
		
		
		
	}
	
	if($cat!=0){
		$query="DELETE FROM prezzip WHERE IDreq='$IDrequest'";
		$result=mysqli_query($link2,$query);
		$query="DELETE FROM richiestecat WHERE IDreq='$IDrequest'";
		$result=mysqli_query($link2,$query);
		
		
		$timeapp=$timearr;
		$arr=explode('__',$cat);
		$ggstart=0;
		$ii=0;
		foreach($arr as $dato){
		
			if($ii>0){
				$ggstart=$gg;
			}
			$ii++;
			$arr2=explode('_',$dato);
			$IDapp=$arr2['0'];
			$timearr=$arr2['1'];
			$gg=$arr2['2'];
			$ggini=$ggstart;
			$queryc="SELECT categoria FROM appartamenti WHERE ID='$IDapp' LIMIT 1";
			$resultc=mysqli_query($link2,$queryc);
			$rowc=mysqli_fetch_row($resultc);
			$categ=$rowc['0'];
			
			include('../../../../config/preventivo/config/selcat.php');
		}
	}
	

	/*
	
	$query="SELECT timearr,checkout FROM richieste WHERE ID='$IDrequest' LIMIT 1";
	$result=mysqli_query($link2,$query);
	$row=mysqli_fetch_row($result);
	$timearr=$row['0'];
	$checkout=$row['1'];
	
	echo date('d/m/Y',$timearr).','.date('d/m/Y',$checkout);
*/

}


?>