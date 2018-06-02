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

$testo='<div data-page="calendario" class="page" > 
		 	<div class="navbar" id="navcal" >
				<div class="navbar-inner">
					<div class="left">
					
					 <a href="#" class="link icon-only" onclick="backexplode(10);" >
						<i class="material-icons fs30" >apps</i>
					</a>
					
					</div>
					<div class="center titolonav">Calendario</div>
					<div class="right" >
						<a href="#"  onclick="addprenot(0,0,-1)"><i class="icon f7-icons fs25" >add</i></a>
					 
					</div>
				</div>
			</div>
		<div class="page-content ">
		
			<input type="hidden" id="IDsenzas" value="'.$IDsenzas.'">
		
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