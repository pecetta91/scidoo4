<?php 

	include('../../../config/connecti.php');
	include('../../../config/funzioni.php');
	header('Access-Control-Allow-Origin: *');
	//
	$testo='';

$IDpren=$_SESSION['IDstrpren'];
$route=$_SESSION['route'];

$query="SELECT app,gg,time,tempg,tempn,checkout,IDstruttura FROM prenotazioni WHERE IDv='$IDpren' LIMIT 1";

$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDapp=$row['0'];
$gg=$row['1'];
$time=$row['2'];
$tempg=round($row['3']);
$tempn=round($row['4']);
$checkout=$row['5'];
$IDstr=$row['6'];

$nomepren=estrainome($IDpren);

$time=oraadesso($IDstr);


$testo.='

<div data-page="elencoserv" class="page with-subnavbar" > 
            <!-- Top Navbar--> 
			 	<div class="navbar" id="navcal" >
				<div class="navbar-inner">
					<div class="left" style="width:90%;">
					
					  <a href="#" class="link icon-only back lh15"   >
						<i class="material-icons">chevron_left</i>
							<strong class="stiletitolopagine">Documenti Ospiti - Check-in Online</strong>
					</a>
					
					</div>
				</div>';
					$num=0;
					$query="SELECT ID,nome,IDcliente,IDrest FROM infopren WHERE IDstr='$IDstr' AND IDpren='$IDpren' AND pers='1'";
					$result=mysqli_query($link2,$query);
					if(mysqli_num_rows($result)>0){
						//estrainomecli
						$testo.='<div class="subnavbar subnavscroll subnavbarbordersotto">
									<div class="subflex">';
						while($row=mysqli_fetch_row($result)){
							//$cliente=$row['1'];
							if($controllo==0){
								$active='activerigasub';
								$controllo=1;
							}else{$active='';}
								
								$cliente=estrainomecli($row['0']);
								if(strlen($cliente)<=1){
									$cliente=$row['1'];
								}
							
							$testo.='<a  id="btn'.$num.'" class="rigasub '.$active.'" onclick="navigationtxt2(8,'."'".$row['2'].",".$row['0']."'".','."'tabellacli'".',1,0);cambiaactive('.$num.');">'.$cliente.'</a>';
							$num++;
						}
				
				
					$testo.='</div></div>';
				}
			$testo.='</div>
		 <div class="page-content">';	
				echo $testo;
				$inc=1;
				include('check-in.inc.php');
				
echo '
</div></div>
</div>
</div>
';

?>