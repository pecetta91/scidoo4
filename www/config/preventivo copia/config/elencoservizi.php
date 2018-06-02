<?php 
header('Access-Control-Allow-Origin: *');
include('../../../../config/connecti.php');
include('../../../../config/funzioni.php');
include('../../../../config/preventivoonline/config/funzioniprev.php');
include('../../../../config/funzionilingua.php');

$IDrequest=$_SESSION['IDrequest'];

$query="SELECT IDstr,notti,timearr,checkout FROM richieste WHERE ID='$IDrequest' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDstruttura=$row['0'];
$gg=$row['1'];
$timearr=$row['2'];
$checkout=$row['3'];


$query2="SELECT ID FROM richiestep WHERE IDreq='$IDrequest' ";
$result2=mysqli_query($link2,$query2);
$qtap=mysqli_num_rows($result2);

$query="SELECT ID FROM tiporestr WHERE IDstr='$IDstruttura' AND personale='1'  AND personad='1' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDrestr=$row['0'].',';



$testo='
  <div class="navbar">
               <div class="navbar-inner">
                  <div class="left" align="center">
				  </div>
                  <div class="center" >Nuovo Servizio</div>
                  <div class="right" >
						<a href="#" onclick="myApp.closePanel();"><i class="icon f7-icons" style="color:#2170e7;">close</i></a>
				
				  
				  </div>
               </div>
            </div>

';

$testop2=array();

$query="SELECT s.ID,s.IDtipo,s.IDsottotip,s.esclusivo,t.tipolimite,s.servizio FROM servizi as s,preferitiserv as e,tiposervizio as t WHERE s.IDstruttura='$IDstruttura' AND e.IDserv=s.ID AND t.ID=s.IDtipo AND t.tipolimite NOT IN (4,5) ORDER BY s.IDtipo";
$result=mysqli_query($link2,$query);
if(mysqli_num_rows($result)>0){
	
	$i=0;
	while($row=mysqli_fetch_row($result)){
		
			//controllare se è un pacchetto e ha le notti
			$IDserv=$row['0'];
			$servizio=$row['5'];
			$IDtipo=$row['1'];
			$exc='';
			
			$esclusivo=$row['3'];
			$pos=1;
			if($IDtipo!='2'){
				$pos=controloraripren($IDserv,$IDtipo,$row['2'],$qtap,$IDrequest);
			}
			//if(($esclusivo==1)&&($pos==1)){}
			if($pos==1){
				$tipolim=$row['4'];
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
						$func='addservprev2(0,'.$IDserv.')';
						
					}else{
						if(!isset($testop2[$IDtipo])){
							$testop2[$IDtipo]='';
						}
						$func='addservprenvent('.$IDserv.')';
						
					}
					$testop2[$IDtipo].='
					  <li class="accordion-item">
						<a href="#" onclick="'.$func.'" class="item-link item-content">
							<div class="item-inner">
								<div class="item-title" style="font-size:13px;">'.$servizio.'</div>
								<div class="item-after"style="font-size:13px;">€ '.$prezzo.'</div>
							</div>
						</a> 
					</li>
					';
				}
			}
	}

}

foreach ($testop2 as $key =>$dato){
	
	$query="SELECT tipo FROM tiposervizio WHERE ID='$key' LIMIT 1";
	$result=mysqli_query($link2,$query);
	$row=mysqli_fetch_row($result);
	$tipo=$row['0'];
	
	$testo.='
	<div class="content-block-title">'.$tipo.'</div>
	<div class="list-block">
      <ul>'.$dato.'</ul></div>
	';
}


echo $testo;


?>