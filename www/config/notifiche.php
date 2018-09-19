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

			 
			 <div class="navbar">
				<div class="navbar-inner">
					<div class="left navbarleftsize170">
					
					 <a href="#" class="link icon-only back" onclick="notifiche()" >
					   <i class="material-icons">chevron_left</i>
						<strong class="stiletitolopagine">Notifiche</strong>
					</a>
					
					</div>
					<div class="center titolonav"></div>
					<div class="right" >
					</div>
				</div>
			</div>
	            <div class="page-content">
              <div class="content-block" id="notifichediv"> 
			 ';
			 
		
			  echo $testo;
		
					
				$inc=1;
				include('notifiche.inc.php');
				
				
				
echo '</div>
</div>';


?>