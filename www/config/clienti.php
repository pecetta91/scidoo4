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
					<div class="left">
					
					 <a href="#" class="link icon-only back" >
						<i class="material-icons" style="font-size:30px;">apps</i>
					</a>
					
					</div>
					<div class="center titolonav">Elenco Clienti</div>
					<div class="right" >
					</div>
				</div>
			</div>
			 
			 
			
				
			 
			 
            <!-- Scrollable page content--> 
            <div class="page-content">
			
				<form class="searchbar searchbar-init" >
					<div class="searchbar-input">
					  <input type="search" placeholder="Cerca Cliente" onKeyUp="navigationtxt(18,this.value,'."'clientidiv'".',0)"><a href="#" class="searchbar-clear"></a>
					</div><a href="#" class="searchbar-cancel">Cancel</a>
				  </form>
				
              <div class="content-block" id="clientidiv" style="padding:0px; width:100%;"> 
			 ';
			 
		
			  echo $testo;
		
					
				$inc=1;
				include('clienti.inc.php');
				
				
				
echo '





</div>







</div>




		
		  
		  


';


?>