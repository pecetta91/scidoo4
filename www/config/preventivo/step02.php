<?php
header('Access-Control-Allow-Origin: *');
include('../../../config/connecti.php');
include('../../../config/funzioni.php');
include('../../../config/funzionilingua.php');

$IDutente=$_SESSION['ID'];
$IDstruttura=$_SESSION['IDstruttura'];

unset($_SESSION['orarioprev']);

$testo='

<div style="width:100%;" align="center"><br>
<div class="card cardmenu"  align="center"  onclick="statostep=0;avanti2('."'1,1'".')" style="float:none;width:97%; margin:4px; height:120px;">
			  <div class="card-content" style=" float:none; display:table-cell; vertical-align:middle;">
				
				<table  align="center"><tr><td style="width:100px;" align="center">
				
					<div class="roundimg" style=" background-color:#3261ae;width:70px;line-height:60px; height:70px;">
					<div><i class="icon f7-icons" style="color:#fff; ">home</i>
						
					</div>
				</td><td>
				<b style="font-size:18px; font-weight:400;">Prenotazione con Soggiorno</b><br>			
				</td></tr></table>
			  </div>
			</div>
			
			<div class="card cardmenu"  align="center" onclick="statostep=0;avanti2('."'0,1'".')" style="float:none;  width:97%;margin:4px; height:120px;">
			  <div class="card-content" style=" float:none; display:table-cell; vertical-align:middle;">
				
				<table align="center"><tr><td style="width:100px;" align="center">
				
					<div class="roundimg" style=" background-color:#32ae6c;width:70px;line-height:60px; height:70px;">
				<div><i class="icon f7-icons" style="color:#fff; font-size:25px;">today</i>
					
				</div>
				</td><td>
						
				<b style="font-size:18px; font-weight:400;">Prenotazione Senza Soggiorno</b><br>
				
				</td></tr></table>
			  </div>
			</div>
			
			


			</div>
			
			


';







echo $testo;
			 