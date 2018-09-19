<?php
header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');
include('../../config/funzionilingua.php');

$IDutente=$_SESSION['ID'];
$IDstruttura=$_SESSION['IDstruttura'];
unset($_SESSION['listIDsotto']);
unset($_SESSION['datecentro']);


$query="SELECT ID FROM appartamenti WHERE IDstruttura='$IDstruttura' AND attivo='2' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDsenzas=$row['0'];


$testo='<div data-page="calendario" class="page "> 
		 	<div class="navbar" id="navcal" style="position:fixed; border:none;">
				<div class="navbar-inner" style="position:fixed">
					<div class="left navbarleftsize170">
					
					 <a href="#" class="link icon-only" onclick="backexplode(10);" >
						<i class="material-icons">chevron_left</i>
							<strong class="stiletitolopagine">CALENDARIO</strong>
					</a>

					</div>
					<div class="center"> 
							
					</div>
					<div class="right" style="width:120px">
							<a href="#" style="width:10px;margin-top:5px" id="meseprox" onclick="" ><i class="icon f7-icons  fs25">chevron_right</i></a>
							<div id="datameseattuale" onclick=""></div>
							<a href="#" style="width:10px;margin-top:5px" id="mesescorso" onclick="" ><i class="icon f7-icons  fs25">chevron_left</i></a>
							<a href="#" onclick="addprenot(0,0,-1)" style="margin-right:25px;margin-top:5px"><i class="icon f7-icons fs25" >add</i></a>
					</div>
				</div>
			</div>
		<div class="page-content keep-navbar-on-scroll">
		<input type="hidden" id="tim" value="'.$time.'">
			<input type="hidden" id="IDsenzas" value="'.$IDsenzas.'">
		
			<div class="content-block" id="calendariodiv" style="margin-top:5px"> 
			';
			 
		
			  echo $testo;
				$inc=1;
				include('calendario2.inc.php');
				
				
				
echo '


</div>



</div>




';


?>