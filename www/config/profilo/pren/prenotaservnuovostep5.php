<?php 
header('Access-Control-Allow-Origin: *');
include('../../../../config/connecti.php');
include('../../../../config/funzioni.php');
include('../../../../config/preventivoonline/config/funzioniprev.php');


$IDpren=$_SESSION['IDstrpren'];

$query="SELECT app,gg,time,IDstruttura,checkout FROM prenotazioni WHERE IDv='$IDpren' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDapp=$row['0'];
$notti=$row['1'];
$check=$row['2'];
$IDstruttura=$row['3'];
$checkout=$row['4'];

$IDserv=$_GET['dato0'];

list($yy, $mm, $dd) = explode("-", date('Y-m-d',$check));
$check0=mktime(0, 0, 0, $mm, $dd, $yy);



$query="SELECT servizio,IDtipo,IDsottotip,prezzo,durata FROM servizi WHERE ID='$IDserv' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$servizio=$row['0'];
$IDtipo=$row['1'];
$IDsottotip=$row['2'];
$prezzo=$row['3'];
$durata=$row['4'];

$testo='<input type="hidden" id="idserv" value="'.$IDserv.'">';

$arrpyes=array();
$arrpnot=array();

$prezzopers=array();

$query="SELECT ID FROM regolains WHERE IDserv='$IDserv' ";
$result=mysqli_query($link2,$query);
if(mysqli_num_rows($result)>0){
	$row=mysqli_fetch_row($result);
	$IDregins=$row['0'];
	
	$query2="SELECT IDrest,prezzo FROM regolainspers WHERE IDregola='$IDregins'";
	$result2=mysqli_query($link2,$query2);
	$row2=mysqli_fetch_row($result2);
	$restrizione=$row2['0'];//bambini ragazzi adulti
	$prezzopers[$row['0']]=$row['1'];
	
	
	
}else{
	
$query="SELECT ID,tipoadd,ogni,da,ad FROM regolaserv WHERE  IDserv='$IDserv' AND IDstr='$IDstruttura' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDreg=$row['0'];
$tipologia=$row['1'];
$ogni=$row['2'];
$giornoin=$row['3'];
$giornofin=$row['4'];
	
}

	

$query="SELECT ID,IDrest FROM infopren WHERE IDpren='$IDpren' AND pers='1'";
	$result=mysqli_query($link2,$query);
	while($row=mysqli_fetch_row($result)){
		array_push($arrpyes,$row['0']);
		$qta++;
		$IDrestrcalc.=$row['1'].',';
		$IDopt.=$row['0'].',';
	}
				
			$testo.='<div class="content-block-title titleb" style="font-size:16px;text-align:center;padding-bottom:10px">Richiedi il servizio</div>
				<input type="hidden" id="tipofun" value="1">';

	
				
							for($i=0;$i<=$notti;$i++){

									$tt=$check0+$i*86400;
									$testo.='<div class="row rowlist no-gutter sceglidataserv" onclick="cambiaicona('.$i.');prendidatav2();" id="data'.$i.'" alt="'.$tt.'" value="'.$tt.'">
										<div class="col-15">
											<div style="color:#a1a1a1;font-size:22px">
											 <i class="f7-icons">circle</i>
											</div>
										</div>
										<div class="col-40"><span style="font-size:20px">'.date('j',$tt).'</span><span style="margin-left:5px;font-size:16px;font-weight:100">'.date('l',$tt).'</span><br/><span style="color:#a29f9f;font-size:14px;line-height:20px">'.date('F',$tt).'</span></div>
										<div class="col-45"></div>
								   </div>';
							}
		
				
		
		
		$restrizione;

		if($ogni==1){//prezzo calcolato a persona
					$testo.='<input type="hidden" id="timepren" >	
					<div style="  width:100%;text-align:center;  font-size:13px; color:#b92282; font-weight:600;">PERSONE</div>
					<div class="list-block" style="margin-top:5px;">
					  <ul>';

				$persone=0;
				$personeid='';
			
				$totale=0;
				$query="SELECT ID,IDcliente,IDrest,nome FROM infopren WHERE IDpren='$IDpren' AND pers='1'";
				$result=mysqli_query($link2,$query);
				while($row=mysqli_fetch_row($result)){
					//$nome=traducis($row['3'],9,$lang,0);
					$nome=$row['3'];
					if($row['2']!=0){
						$query3="SELECT nome,cognome FROM schedine WHERE ID='".$row['1']."' LIMIT 1";
						$result3=mysqli_query($link2,$query3);
						$row3=mysqli_fetch_row($result3);


						$nome='<b>'.$row3['0'].' '.$row3['1'].'</b><br><span style="font-size:9px;">'.$nome.'</span>';
					}	

						$IDrestr=$row['2'].',';	
					   //$prezzo=calcolaprezzoserv($IDserv,$time,$IDrestr,$IDstruttura,0,$IDpren,0,$durata);	
						$clas='';
						$pacchetto='';
						if(in_array($row['0'],$arrpyes)){
							$clas=' checked="checked"';
							$pacchetto=$prezzo.' €';
							$totale+=$prezzo;	
							$persone++;
							$personeid=$personeid.$row['0'].',';//id persone checkatte
						}
						$ok=0;
						if($IDtipo!='1'){
							$ok=1;
						}else{
							$ok=1;
						}

					if($ok==1){

						$dis='';
						$color="333";
						if(in_array($row['0'],$arrpnot)){
							$dis=' disabled="disabled"';
							$clas='checked="checked" ';
							//$pacchetto=traduci('Incluso',$lang,1,0);
							$color='229068';
						}
						$func='';

						$func='ricarcolaadd()';
						
					

						$testo.='
							<li style="font-size:13px;">
						  <label class="label-checkbox item-content" >
							<input type="checkbox" class="soggetti"  '.$dis.' id="person'.$row['0'].'" '.$clas.' alt="'.$prezzo.'"  onChange="controllacheck()"  value="'.$row['0'].'" >
							<div class="item-media">
							  <i class="icon icon-form-checkbox"></i>
							</div>
							<div class="item-inner">
							  <div class="item-title" style="line-height:12px;">'.$nome.'</div>
							  <div class="item-after">'.$prezzo.'€</div>
							</div>
						  </label>
						</li>';
						}
				}		
				$testo.='<input type="hidden"  id="idpers" value="'.$personeid.'">
				</ul></div>';

							if($totale==0){
					if($IDins!=0){
						$totale='Servizio incluso';
					}else{
						$totale='<span id="totaleserv">'.$totale.'</span> €';
					}
				}else{
					$totale='<span id="totaleserv">'.$totale.'</span> €';
				}
				$func='prenotaora2()';

				$testo.='<div style="width:95%;" align="right">
				<span style="font-size:30px;  font-weight:600; color:#22c782;">'.$totale.'</span><br><br>
				</div>';
	
		}else{//prezzo singolo 	
			
			
			$testo.='<input type="hidden"  id="npers" value="0">';//servizio
			if($totale==0){
								$testo.='<input type="hidden" value="'.$prezzo.'" id="prezzoservi"> ';
								if($IDins!=0){
									$totale='Servizio incluso';
								}else{
									$totale='<span id="totaleserv">'.$prezzo.'</span> €';
								}
					}else{
						$totale='<span id="totaleserv">'.$prezzo.'</span> €';
					}
						$func='prenotaora2()';

						$testo.='<div style="width:95%;" align="right">
						<span style="font-size:30px;  font-weight:600; color:#22c782;">'.$totale.'</span><br><br>
						</div>';
			}
				
	
		


?>
<div class="content-block" id="prenotanuovservstep"> 
<?php echo $testo;?>