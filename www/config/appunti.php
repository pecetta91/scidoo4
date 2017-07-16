<?php
header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');
include('../../config/funzionilingua.php');
$IDutente=intval($_SESSION['ID']);
$IDstruttura=intval($_SESSION['IDstruttura']);
unset($_SESSION['IDsottotip']);
unset($_SESSION['vis']);
unset($_SESSION['datecentro']);
$time=time();
$testo='
<div data-page="clienti" class="page"> 

			 <div class="navbar" >
				<div class="navbar-inner">
					<div class="left">
					
					 <a href="#" class="link icon-only back"  onclick="notifiche()">
						<i class="material-icons" style="font-size:30px;">apps</i>
					</a>
					
					</div>
					<div class="center titolonav">Appunti</div>
					<div class="right" onclick="detappunto(0)">
						<i class="icon f7-icons">add</i>
					</div>
				</div>
			</div>
			    <div class="page-content">
			
				
				
              <div class="content-block" id="appuntidiv"> 
			 ';
			 
		
			  echo $testo;
		
					
				$inc=1;
				include('appunti.inc.php');
				
				
				
echo '





</div>







</div>




		
		  
		  


';


?>