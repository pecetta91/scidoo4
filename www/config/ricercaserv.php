<?php
$ricerca='';
if(!isset($inc)){
	header('Access-Control-Allow-Origin: *');
	include('../../config/connecti.php');
	include('../../config/funzioni.php');
	include('../../config/preventivoonline/config/funzioniprev.php');
	$testo='';
	
	if(isset($_GET['dato0'])){
		if($_GET['dato0']!='0'){
			$ricerca=$_GET['dato0'];
		}else{
			$ricerca='';
		}
	}
	
	
}


$IDutente=$_SESSION['ID'];
$IDstruttura=$_SESSION['IDstruttura'];

$IDrequest=$_SESSION['IDrequest'];



$query="SELECT IDstr,notti,timearr,stato,checkout FROM richieste WHERE ID='$IDrequest' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDstruttura=$row['0'];
$gg=$row['1'];
$ggric=$gg;
$timearr=$row['2'];
$stato=$row['3'];
$checkout=$row['4'];


$query2="SELECT ID FROM richiestep WHERE IDreq='$IDrequest' ";
$result2=mysqli_query($link2,$query2);
$qtap=mysqli_num_rows($result2);

$IDrestr=getrestrmain($IDstruttura).',';


if(strlen($ricerca)>0){
	
		
	//$query="SELECT ID,nome,cognome,mail,tel,note,noteristo,MATCH(nome,cognome,mail) AGAINST('".$ricerca."') as score FROM schedine WHERE MATCH(nome,cognome,tel,mail,note,noteristo) AGAINST('".$ricerca."' IN BOOLEAN MODE) AND IDstr='$IDstruttura'  ORDER BY score DESC LIMIT 25";
	
	
	$query="SELECT s.ID,s.servizio,t.tipolimite,s.IDtipo FROM servizi as s,tiposervizio as t WHERE s.IDstruttura='$IDstruttura' AND s.servizio LIKE '%$ricerca%' AND s.IDtipo=t.ID AND t.tipolimite NOT IN (4,5)  LIMIT 25";
	
	$testo='<div class="list-block"><ul>';
	  
	$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
		while($row=mysqli_fetch_row($result)){
			/*$note='';
			if((strlen($row['5'])>0)||(strlen($row['4'])>0)){
				$note='<br><span style="font-size:10px; color:#999;">'.$row['5'].' '.$row['6'].'</span>';
			}
			$tel='';
			$email='';
			if(strlen($row['4'])>0){
				$tel=$row['4'];
			}else{
				$tel='<span style="color:#ccc;">No Tel</span>';
			}
			
			if(strlen($row['3'])>0){
				$email=$row['3'];
			}else{
				$email='<span style="color:#ccc;">No Email</span>';
			}
			*/
			$servizio=$row['1'];
			$IDserv=$row['0'];
			$IDtipo=$row['3'];
			$tipolim=$row['2'];
				$jj=1;
				$txtcont='';				
				if($IDtipo!='9'){ 
					if($IDtipo==10){
						$prezzo=calcolaprezzoserv($IDserv,$timearr,1,$IDstruttura,0,$IDrequest,1);
					}else{
						$prezzo=calcolaprezzoserv($IDserv,$timearr,$IDrestr,$IDstruttura,0,$IDrequest,1);
					}
					if($tipolim=='2'){
						if(!isset($testop2[$IDtipo])){
							$testop2[$IDtipo]='';
						}
						$func='addservprev2(0,'.$IDserv.',0,1)';
						
					}else{
						if(!isset($testop2[$IDtipo])){
							$testop2[$IDtipo]='';
						}
						$func='addservprevent('.$IDserv.')';
					}
					$testo.='
					  <li class="ricercalistblock">
						<a href="javascript:void(0)" onclick="'.$func.'" class="item-link item-content">
							<div class="item-inner pt5 pb5" >
								<div class="item-title fs16 fw400">'.$servizio.'</div>
								
							</div>
						</a> 
					</li>
					';
				}
			
			
		
		
		}
	}
	   
	   /*<div class="item-after">€ '.$prezzo.'</div>*/
	   
	   
	   
      $testo.='</ul>
    </div>';
	
	
	


}

if(!isset($inc)){
	echo $testo;
}
			 
?>			 
			 