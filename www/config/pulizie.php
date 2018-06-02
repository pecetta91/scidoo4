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
$_SESSION['timecal']=time();
/*
<a href="#" onclick="aprimod(10,this)" class="tab-link " style="text-align:center;  line-height:13px;">
					<i class="icon" style="line-height:10px;">
						<i class="icon f7-icons" style="color:#fff; font-size:25px;margin-top:-2px; ">menu</i>
						<br><div style="font-size:10px;color:#fff; margin-top:-8px;">Vista</div>
					</i>
				</a>*/



$testo='
<div data-page="pulizie" class="page with-subnavbar"> 

			 
			 <div class="navbar">
				<div class="navbar-inner">
					<div class="left">
					
					 <a href="#" class="link icon-only back" >
						<i class="material-icons" style="font-size:30px;">apps</i>
					</a>
					
					</div>
					<div class="center titolonav">Pulizie</div>
					<div class="right"></div>
					<div class="subnavbar">
					<div class="ristorante-navbar" style="margin-left: calc(10% - 9%);" >
						<table style="width:100%">
						 <tr>
						  <td style="width:30%">
		                    <a class="buttonristo active" id="all" onclick="pulizienav(1);navigationtxt(15,'."'0,2'".','."'".'puliziediv'."'".',0);">Alloggi</a>
						  </td>
						  <td style="width:30%">
							<a class="buttonristo" id="prog" onclick="pulizienav(2);navigationtxt(15,'."'0,3'".','."'".'puliziediv'."'".',14);">Arrivi</a>
						 </td> 
						 <td style="width:30%">
							<a class="buttonristo" onclick="pulizienav(3);navigationtxt(15,'."'0,4'".','."'".'puliziediv'."'".',14);" id="pul">Pulizie Extra</a>
						 </td>
						</tr>
					</table>
				</div>
					
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