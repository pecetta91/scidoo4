<?php
header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');
include('../../config/funzionilingua.php');

$IDutente=$_SESSION['ID'];
$IDstruttura=$_SESSION['IDstruttura'];
unset($_SESSION['listIDsotto']);
unset($_SESSION['datecentro']);

$testo=  '
 


<div data-page="calendario" class="page" > 
            <!-- Top Navbar--> 
             
	
			 
			 	<div class="navbar" id="navcal" >
				<div class="navbar-inner">
					<div class="left">
					
					 <a href="#" class="link icon-only back" >
						<i class="material-icons" style="font-size:30px;">apps</i>
					</a>
					
					</div>
					<div class="center titolonav">Calendario</div>
					<div class="right" >
						<a href="#" style="width:50px;" onclick="addprenot(0,0)"><i class="icon f7-icons" style="font-size:25px;">add</i></a>
					 
					</div>
				</div>
			</div>
		 <div class="page-content">
			
				
				
              <div class="content-block" id="calendariodiv"> 
			
			 ';
			 
		
			  echo $testo;
				$inc=1;
				include('calendario2.inc.php');
				
				
				
echo '


</div>



</div>




';


?>