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
/*
					<a href="#" onclick="navigationtxt(16,'."'1'".','."'domoticadiv'".',0)" style="height:45px; margin-left:8px;" >
						<i class="icon " style="line-height:10px; margin-top:5px;" >
						<i class="icon f7-icons" style="font-size:19px; ">view_list</i><br>
						<div style="font-size:9px;font-weight:600;">Strumenti</div>
					</i>
					</a>
					<a href="#" onclick="navigationtxt(16,'."'2'".','."'domoticadiv'".',0)"   style="height:45px;">
						<i class="icon " style="line-height:10px; margin-top:5px;" >
						<i class="icon f7-icons" style="font-size:19px; ">home</i><br>
						<div style="font-size:9px;font-weight:600;">Alloggi</div>
					</i>
					</a>*/
$testo='
<div data-page="domotica" class="page with-subnavbar"> 

			 
			 <div class="navbar">
				<div class="navbar-inner">
					<div class="left">
					
					 <a href="#" class="link icon-only back" >
						<i class="material-icons fs30" >apps</i>
					</a>
					
					</div>
					<div class="center titolonav">Domotica</div>
					<div class="right"></div>
				<div class="subnavbar" align="center">
					<div class="ristorante-navbar"  >
						<table style="width:90%;">
						 <tr>
						  <td style="width:46%">
		                    <a class="buttonristo active" id="tavoli" onclick="buttonristoact(1);navigationtxt(16,'."'1'".','."'domoticadiv'".',0)">Strumenti</a>
						  </td>
						  <td style="width:46%">
							<a class="buttonristo" id="sale" onclick="buttonristoact(2);navigationtxt(16,'."'2'".','."'domoticadiv'".',0);">Alloggi</a>
						 </td> 
						</tr>
					</table>
				</div>
				</div>
			</div>
		</div>
			 
			 
			
				
			 
			 
            <!-- Scrollable page content--> 
            <div class="page-content">
			
				
				
              <div class="content-block" id="domoticadiv" style="padding:0px;"> 
			 ';
			 
		
			  echo $testo;
		
					
				$inc=1;
				include('domotica.inc.php');
				
				
				
echo '





</div>







</div>




		
		  
		  


';


?>