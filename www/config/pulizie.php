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

/*
<a href="#" onclick="aprimod(10,this)" class="tab-link " style="text-align:center;  line-height:13px;">
					<i class="icon" style="line-height:10px;">
						<i class="icon f7-icons" style="color:#fff; font-size:25px;margin-top:-2px; ">menu</i>
						<br><div style="font-size:10px;color:#fff; margin-top:-8px;">Vista</div>
					</i>
				</a>*/

$testo='
<div data-page="pulizie" class="page"> 

			 
			 <div class="navbar">
				<div class="navbar-inner">
					<div class="left">
					
					 <a href="#" class="link icon-only back" >
						<i class="material-icons" style="font-size:30px;">apps</i>
					</a>
					
					</div>
					<div class="center titolonav">Pulizie</div>
					<div class="right" style="padding-right:20px;">
					
					
					
					
					
					
				
					
					</div>
				</div>
			</div>
			 
			 
			
				
			 
			 
            <!-- Scrollable page content--> 
            <div class="page-content">
			
				
				
              <div class="content-block" id="puliziediv" style="padding:0px; width:100%;"> 
			 ';
			 
		
			  echo $testo;
		
					
				$inc=1;
				include('pulizie.inc.php');
				
				
				
echo '





</div>







</div>




		
		  
		  


';


?>