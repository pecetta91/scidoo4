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
<div data-page="arrivi" class="page"> 

			 <div class="navbar">
				<div class="navbar-inner">
					<div class="left">
					 <a href="#" class="link icon-only back" >
						<i class="material-icons" style="font-size:30px;">apps</i>
					</a>
					</div>
					<div class="center titolonav">Elenco Arrivi</div>
					<div class="right" >
					</div>
				</div>
			</div>
		 
            <!-- Scrollable page content--> 
            <div class="page-content">
	          <div class="content-block" id="arrividiv" style="padding:0px; width:100%;"> 
			 ';
			 
			  echo $testo;
				$inc=1;
				include('arrivi.inc.php');
				
				
				
echo '





</div>







</div>




		
		  
		  


';


?>