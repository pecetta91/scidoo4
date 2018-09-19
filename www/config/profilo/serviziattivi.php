<?php 
if(!isset($inc)){
	include('../../../config/connecti.php');
	include('../../../config/funzioni.php');
	header('Access-Control-Allow-Origin: *');
	$testo='';
}

$IDpren=$_SESSION['IDstrpren'];
$route=$_SESSION['route'];



$query="SELECT app,gg,time,tempg,tempn,checkout,IDstruttura FROM prenotazioni WHERE IDv='$IDpren' LIMIT 1";

$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDapp=$row['0'];
$gg=$row['1'];
$time=$row['2'];
$tempg=$row['3'];
$tempn=$row['4'];
$checkout=$row['5'];
$IDstr=$row['6'];

$nomepren=estrainome($IDpren);


$timeora=oraadesso($IDstr);

//elenco servizi


$IDprenc=prenotcoll($IDpren);
$id=$IDpren;

$servizisosp=array(array());
$serviziarr=array(array());
$prodottiarr=array(array());
	$controllo=0;$active='';

$testo='
<div data-page="serviziospite" class="page with-subnavbar" > 
		 	<div class="navbar" id="navcal" >
				<div class="navbar-inner">
					<div class="left">
					
					  <a href="#" class="link icon-only back"   >
						<i class="material-icons fs40" >chevron_left</i>
					</a>
					
					</div>
					<div class="center titolonav">Promemoria Servizi</div>
					<div class="right"></div>
				</div>';

	
			$query="SELECT GROUP_CONCAT(DISTINCT (IDtipo)) FROM prenextra WHERE IDstruttura='$IDstr' AND IDpren='$IDpren' AND IDtipo NOT IN(8,9,10,12,13,14,15,16,17,18,19,20) GROUP BY IDstruttura";
			$result=mysqli_query($link2,$query);
					if(mysqli_num_rows($result)>0){
						$testo.='<div class="subnavbar subnavscroll" >
									<div class="subflex">';
					$row=mysqli_fetch_row($result);
						
					$tipopren=$row['0'];
						$query="SELECT ID,tipo FROM tiposervizio WHERE ID IN ($tipopren) ";
				
					$result=mysqli_query($link2,$query);
						if(mysqli_num_rows($result)>0){
					while($row=mysqli_fetch_row($result)){
						$ID=$row['0'];
						$tipo=$row['1'];
						if(strlen($tipo)>15){$tipo=substr($tipo,0,15);}
							if($controllo==0){
								$active='activerigasub';
								$controllo=1;
							}else{$active='';}

							$testo.='<a  id="btn'.$ID.'" class="rigasub '.$active.'" onclick="cambiaactive('.$ID.');navigationtxt2(7,'.$ID.','."'servizidiv'".',0,0)">'.$tipo.'</a>';
							//navigationtxt2(7,'.$ID.','."'servizidiv'".',0,0);
							
						
								}
						}
					$testo.='</div></div>';
				}
			$testo.='</div>
		 <div class="page-content">
			<div class="content-block" id="servizidiv">'; 



//vedo le tipologie attive sul conto del cliente e le metto nella navbar


				echo $testo;
				$inc=1;
				include('serviziattivi.inc.php');

echo '
</div>
	<br><br><br><br>
	<div class="infoservattivi"><span class="infoservattivitxt">&Egrave; possibile modificare gli orari fino a 4h prima del suo inizio.<br>Per qualsiasi altre informazioni o modifica contrattare la struttura.</span></div>
	</div>
</div>
</div>
</div>
';
?>
