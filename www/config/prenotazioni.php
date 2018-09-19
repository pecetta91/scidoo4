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
					
					 <a href="#" class="link icon-only back" >
						<i class="material-icons">chevron_left</i>
							<strong class="stiletitolopagine">Elenco Prenotazioni</strong>
					</a>
				
					</div>
					<div class="center"></div>
					<div class="right" >
					</div>
				</div>
				<div class="subnavbar">
						<form class="searchbar searchbar-init" >
						<div class="searchbar-input">
						  <input type="search" placeholder="Cerca Prenotazione" onKeyUp="navigationtxt(19,this.value,'."'prenotazionidiv'".',0)"><a href="#" class="searchbar-clear"></a>
						</div><a href="#" class="searchbar-cancel">Cancel</a>
					  </form>
					</div>
				
				
			</div>
			 
			 
			
            <div class="page-content">
			
              <div class="content-block" id="prenotazionidiv" style="padding:0px; padding-top:20px; width:100%;"> 
			 ';
			 
		
			  echo $testo;
		
					
				$inc=1;
				include('prenotazioni.inc.php');
				
				
				
echo '





</div>







</div>




		
		  
		  


';


?>