<?php 
header('Access-Control-Allow-Origin: *');
include('../../../../config/connecti.php');
include('../../../../config/funzioni.php');
include('../../../../config/preventivoonline/config/funzioniprev.php');

$IDpren=$_SESSION['IDstrpren'];
$txt='<input type="hidden" value="'.$IDpren.'" id="idpren">';


$query="SELECT app,gg,time,IDstruttura,checkout FROM prenotazioni WHERE IDv='$IDpren' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDapp=$row['0'];
$notti=$row['1'];
$check=$row['2'];
$IDstruttura=$row['3'];
$checkout=$row['4'];



$IDserv=$_GET['dato0'];

$timeday=$_GET['dato1'];



if($timeday==0){
	$timeday=$check;
}

$pag=1;


$query="SELECT servizio,IDtipo,IDsottotip FROM servizi WHERE ID='$IDserv' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$servizio=$row['0'];
$IDtipo=$row['1'];
$IDsottotip=$row['2'];






list($yy, $mm, $dd) = explode("-", date('Y-m-d',$check));
$check0=mktime(0, 0, 0, $mm, $dd, $yy);

	
	
$dcheck=date('d',$check);
$dcheckout=date('d',$checkout);	
	
$modi=0;
$time=0;

$query="SELECT s.durata,s.IDsottotip,s.esclusivo,t.tipolimite,s.IDtipo,s.servizio FROM servizi as s,tiposervizio as t WHERE s.ID='$IDserv' AND s.IDtipo=t.ID LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$durata=$row['0'];	
$IDsottotip=$row['1'];	
$esclusivo=$row['2'];	
$tipolim=$row['3'];
$IDtipo=$row['4'];
$servizio=$row['5'];


$txt.='
 
<div class="content-block-title titleb" style="font-size:16px;text-align:center;padding-bottom:10px">Scegli il Giorno Per il Servizio</div> 
<input type="hidden" id="tipofun" value="2">
';//2 radiobox
  /*<div class="prenotanuovoserv" onclick="prendidata('.$i.')" id="data'.$i.'" alt="'.$tt.'" value="'.$tt.'">
								<span style="font-size:20px;color:#4cd964">'.date('j',$tt).'</span><br>
								<span style="font-size:13px;color:#4cd964">'.date('l',$tt).'</span><br>
								<span style="font-size:13px;color:#4cd964">'.date('F',$tt).'</span><br>
								<span style="font-size:12px;color:#ff2422">'.date('Y',$tt).'</span>
							</div>
							<div class="row rowlist no-gutter " onclick="prendidata('.$i.')" id="data'.$i.'" alt="'.$tt.'" value="'.$tt.'">
								<div class="col-20" style="height:50px;line-height:20px"><span style="color:#4cd964;margin-top:10px;margin-left:20px;font-size:18px">'.date('j',$tt).'<br/></span><span style="margin-left:20px;font-size:13px">'.date('m',$tt).'</span></div>
								<div class="col-60">'.date('l',$tt).'</div>
								<div class="col-10">'.date('Y',$tt).'</div>
						   </div>
							check_round*/
	
	for($i=0;$i<=$notti;$i++){

					$tt=$check0+$i*86400;
					$sele='';
					$txt.='<div class="row rowlist no-gutter sceglidataserv" onclick="cambiaicona('.$i.')" id="data'.$i.'" alt="'.$tt.'" value="'.$tt.'">
								<div class="col-15">
									<div style="color:#a1a1a1;font-size:22px">
									 <i class="f7-icons">circle</i>
									</div>
								</div>
								<div class="col-40"><span style="font-size:20px">'.date('j',$tt).'</span><span style="margin-left:5px;font-size:16px;font-weight:100">'.date('l',$tt).'</span><br/><span style="color:#a29f9f;font-size:14px;line-height:20px">'.date('F',$tt).'</span></div>
								<div class="col-45"></div>
						   </div>';
				}




?>
<div class="content-block" id="prenotanuovservstep"> 
<?php echo $txt;?>
