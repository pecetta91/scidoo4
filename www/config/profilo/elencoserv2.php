<?php 

	include('../../../config/connecti.php');
	include('../../../config/funzioni.php');
	header('Access-Control-Allow-Origin: *');
	//
	$testo='';

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



$query="SELECT ID FROM tiporestr WHERE IDstr='$IDstr' AND personale='1'  AND personad='1' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$restr=$row['0'].',';



$testo.='

<div data-page="elencoserv" class="page with-subnavbar" > 
			 
			 	<div class="navbar" id="navcal" >
				<div class="navbar-inner">
					<div class="left navbarleftsize170">
					
					  <a href="#" class="link icon-only back"   >
						<i class="material-icons">chevron_left</i>
							<strong class="stiletitolopagine">Elenco Servizi</strong>
					</a>
					
					</div>
					<div class="center titolonav"></div>
					<div class="right"></div>
				</div>';
				
				$infoser=array();
					$querym="SELECT GROUP_CONCAT(DISTINCT (s.IDtipo) ) FROM extraonline as ext,servizi as s WHERE ext.IDstr='$IDstr' AND ext.IDserv=s.ID GROUP BY ext.IDstr";
					$resultm=mysqli_query($link2,$querym);
					if(mysqli_num_rows($resultm)>0){
						$testo.='<div class="subnavbar subnavscroll subnavbarbordersotto" >
									<div class="subflex">';
					$rowm=mysqli_fetch_row($resultm);
					$servon=$rowm['0'];
				
				
					

				$query="SELECT ID,tipo,tipo2 FROM tiposervizio WHERE ID IN ($servon) AND ID NOT IN(9,8) ";
				
					$result=mysqli_query($link2,$query);
						if(mysqli_num_rows($result)>0){
							$testo.='<a  id="btn0" class="rigasub activerigasub" onclick="navigationtxt2(6,-1,'."'elencoserv'".',0,0);cambiaactive(0);">Tutti</a>';
					while($row=mysqli_fetch_row($result)){
						$ID=$row['0'];
						$tipo=$row['1'];
						$tipo2=$row['2'];
						if(strlen($tipo2)>15){$tipo=substr($tipo2,0,15);}
						
							
						
							//$query2="SELECT ID,sottotipologia FROM sottotipologie WHERE IDmain='$ID' AND IDstr='$IDstr'";
							$query2="SELECT s.ID,s.sottotipologia FROM sottotipologie as s,servizi as s2,extraonline as e WHERE s.IDmain='$ID' AND s.IDstr='$IDstr' AND s.ID=s2.IDsottotip AND s2.ID=e.IDserv GROUP BY s.ID";
						
						
							$result2=mysqli_query($link2,$query2);
							if(mysqli_num_rows($result2)>0){
								$testo.='<a  id="btn'.$ID.'" class="rigasub" onclick="tipologiaserv('.$ID.')">'.$tipo2.'</a>';
								
								
								while($row2=mysqli_fetch_row($result2)){
									$IDsottotip=$row2['0'];
									$nomesotto='<div>'.$row2['1'].'</div>';
										$infoser[$ID].=' buttons.push({
											text: '."'".$nomesotto."'".',
											onClick: function () {
											cambiaactive('.$ID.');
											navigationtxt2(6,'.$IDsottotip.','."'elencoserv'".',0,0);
											}
											}); ';
									}	
								$testo.='<input type="hidden" value="'.base64_encode($infoser[$ID]).'" id="servizi'.$ID.'" >';
							}	
						
								}
						}
					$testo.='</div></div>';
				}
			$testo.='</div>
		 <div class="page-content">';	
				echo $testo;
				$inc=1;
				include('elencoserv.inc.php');

echo '
</div></div>
</div>
</div>
';

?>