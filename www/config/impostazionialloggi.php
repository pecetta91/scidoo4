<?php
header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');
include('../../config/funzionilingua.php');

$IDutente=intval($_SESSION['ID']);
$IDstruttura=intval($_SESSION['IDstruttura']);



$testo='
<div data-page="centrobenessere" class="page with-subnavbar"> 
			 <div class="navbar">
				<div class="navbar-inner">
					<div class="left navbarleftsize170">
					 <a href="#" class="link icon-only" onclick="mainView.router.back();" >
				     	<i class="material-icons">chevron_left</i>
						<strong class="stiletitolopagine">Impostazioni Alloggi</strong>
					</a>
					
					
					</div>
					<div class="center titolonav"></div>
					<div class="right" >
					</div>
				</div>
			</div>';
					
$testo.='<div class="page-content" style="z-index:-1;">
					<div class="content-block" style="margin-top:0px;"> 
					<div id="alloggi" style="margin-left:0px;padding:0px; width:100%;">
					
					
					';
		
			
		
				$inc=1;
				include('impostazionialloggi.inc.php');
				
			  echo $testo;	
				
echo '</div></div></div>';


?>