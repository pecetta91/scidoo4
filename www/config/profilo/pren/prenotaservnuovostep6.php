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
$orariopren=$_GET['dato1'];


$query="SELECT servizio,IDtipo,IDsottotip,prezzo FROM servizi WHERE ID='$IDserv' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$servizio=$row['0'];
$IDtipo=$row['1'];
$IDsottotip=$row['2'];
$prezzo=$row['3'];

$testo='';

$arrpyes=array();
$arrpnot=array();

	$query="SELECT ID,IDrest FROM infopren WHERE IDpren='$IDpren' AND pers='1'";
	$result=mysqli_query($link2,$query);
	while($row=mysqli_fetch_row($result)){
		array_push($arrpyes,$row['0']);
		$qta++;
		$IDrestrcalc.=$row['1'].',';
		$IDopt.=$row['0'].',';
	}
/*<div class="prenotanuovoserv sceglidataserv" onclick="prendidata('.$i.')" id="data'.$i.'" alt="'.$tt.'" value="'.$tt.'">
								<span style="font-size:20px;color:#4cd964">'.date('j',$tt).'</span><br>
								<span style="font-size:13px;color:#4cd964">'.date('l',$tt).'</span><br>
								<span style="font-size:13px;color:#4cd964">'.date('F',$tt).'</span><br>
								<span style="font-size:12px;color:#ff2422">'.date('Y',$tt).'</span>
							</div>*/


$testo.='<input type="hidden" id="idserv" value="'.$IDserv.'">	
<input type="hidden" id="timepren" value="'.$orariopren.'">	
<div class="content-block-title titleb">Riepilogo</div> 
<div class="row rowlist" style="margin-bottom:30px">
		 		<div class="col-50" style="margin-left:10px"><span style="font-size:17px;">'.date('d-m-Y',$orariopren).'</span></div>
				<div class="col-45" style="margin-top:5px"><span style="font-size:18px;">'.date('l',$orariopren).'</span></div>
			   </div>';
				



		$testo.='
		<div style="  width:100%;text-align:center;  font-size:13px; color:#b92282; font-weight:600;">PERSONE</div>
		<div class="list-block" style="margin-top:5px;">
			  <ul>';
			  
		$persone=0;
		
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
			$prezzo='Incluso';	
			$prezzo=calcolaprezzoserv($IDserv,$time,$IDrestr,$IDstruttura,0,$IDpren,0,$durata);	
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
				</li>
			  
				
				';
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
		
	

?>
<div class="content-block" id="prenotanuovservstep"> 
<?php echo $testo;?>