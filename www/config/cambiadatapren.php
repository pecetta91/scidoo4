<?php
header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');
include('../../config/funzionilingua.php');

$testo='';

$IDutente=$_SESSION['ID'];
$IDstruttura=$_SESSION['IDstruttura'];

$ID=$_GET['dato0'];

$_SESSION['IDprensposta']=$ID;

$checkout=$ora+86400;

$inc=1;


$nome=estrainome($ID);

$query="SELECT time,gg,checkout,app FROM prenotazioni WHERE IDv='$ID' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$time=$row['0'];
$notti=$row['1'];
$checkout=$row['2'];
$app=$row['3'];

$query="SELECT nome FROM appartamenti WHERE ID='$app' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$alloggio=$row['0'];


/*	
				<div class="content-block-title"  style="color:#2d4e99;">Dati Prenotazione</div>
<div style="padding-left:20px; font-weight:400; font-size:14px;">
				Prenotazione: '.$nome.'<br/>
				Check-in: '.dataita2($time).'<br/>
				Check-out: '.dataita2($checkout).'<br/>
				Soggiorno di: '.$notti.' '.txtnotti($notti).'<br/>
</div><hr>
	*/


$testo.='<div data-page="cambiadata" class="page with-subnavbar"> 
			 <div class="navbar">
				<div class="navbar-inner">
					<div class="left navbarleftsize170">
					 <a href="#" class="link icon-only back" onclick="backexplode(12,0);"  >
						<i class="material-icons">chevron_left</i>
						<strong class="stiletitolopagine">Sposta Prenotazione</strong>
					</a>
					
					</div>
					<div class="center titolonav"></div>
					<div class="right" ></div>
				</div>
			</div>
			<div class="page-content" style="padding-top:30px;">
	          <div class="content-block" id="cambiadata"> 


				<div class="content-block-title"  style="color:#2d4e99;">Dati Prenotazione</div>
<div style="padding-left:20px; font-weight:400; font-size:14px;">
				Alloggio: '.$alloggio.'<br/>
				Check-in: '.dataita4($time).' ('.$notti.' '.txtnotti($notti).')<br/>
</div>
				
			
				<div style="width:100%" >
				<div class="content-block-title titleb"  style="color:#2d4e99;">Nuova Data di Check-in</div>
				
				
				<span id="notti" style="display:none;">0</span>
				<div style="display:none;" id="calsposta">'.date('Y-m-d',$time).'</div>
				<div style="display:none;" id="calsposta2">'.$time.'</div>
				<div style="display:none;" id="IDprensposta">'.$ID.'</div>

<div class="row rowlist" style="border-bottom:solid 1px #f1f1f1;">
				
				<div class="col-100 "><input type="text" readonly value="'.dataita4($time).'" id="cambiadatapren" style="width:100%; text-align:left;  height:30px; background:#fff; border-solid 1px #ccc; border-radius:5px; border:none;  font-weight:600;  outline:none; font-size:18px; text-transform:uppercase; color:#c4374b; ">
				</div>
				</div>
			
				<div class="content-block-title titleb"  style="color:#2d4e99;">Elenco Alloggi Disponibili<br>
				<span>Seleziona un alloggio</span>
				</div>
				<div id="cambiadatatxt">
				';

				include('cambiadatapren.inc.php');


				$testo.='</div>
				<br/><br/><br/><br/><br/><br/><br/>
				
				</div>
				</div>
				
				
				
				
				
				
				
				
				
			  </div>
		  </div>
	  </div>';


/*<div class="bottombarpren" style="background:#f1f1f1;z-index:999; height:75px;" align="center">

<div style="font-size:10px;text-transform:none; color:#333; margin-top:0px;">Seleziona Data e Alloggio di Destinazione</div>
					<button class="bottoneprezzo" onclick="spostapren()"><span id="avantitxt">Sposta Prenotazione</span>
					
					
					</button>
				
				</div>*/

echo $testo;

?>


