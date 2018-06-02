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
if(isset($_GET['dato0'])){
	if($_GET['dato0']!=0){
		$time=$_GET['dato0'];
	}
}

list($yy, $mm, $dd) = explode("-",date('Y-m-d',$time));
$time0=mktime(0, 0, 0, $mm, $dd, $yy);
$_SESSION['timecal']=$time0;

$_SESSION['tipoaddnav']=1;
$_SESSION['visristo']=0;


$timeoggi=time();
			list($yy, $mm, $dd) = explode("-",date('Y-m-d',$timeoggi));
			$time0oggi=mktime(0, 0, 0, $mm, $dd, $yy);
$tt=$time0oggi+$i*86400;


$testo='
<div data-page="ristorante" class="page"> 
			


			 <div class="navbar" style="border:none;">
				<div class="navbar-inner">
					<div class="left">
					
					 <a href="#" class="link icon-only" onclick="creasessione(0,88);mainView.router.back();" >
						<i class="material-icons fs30" >apps</i>
					</a>
					
					</div>
					<div class="center titolonav">Ristorante</div>
					<div class="right">
					
					</div>
				</div>
				
				<div class="subnavbar">
					<div class="ristorante-navbar" align="center">
						<table style="width:95%">
						 <tr>
						  <td style="width:48%">
		                    <a class="buttonristo active" id="all" onclick="pulizienav(1);navigationtxt(14,'."'0,0'".','."'ristorantediv'".',15)">Ristorante</a>
						  </td>
						  <td style="width:48%">
							<a class="buttonristo" id="prog" onclick="pulizienav(2);navigationtxt(14,'."'0,1'".','."'ristorantediv'".',15)"">Menu</a>
						 </td> 
						 
						</tr>
					</table>
				</div>
					
				</div>
				
			</div>
			
			
			
			
			';
			 
			
           $testo.='
			 <div class="page-content hide-navbars-on-scroll" style="z-index:-1;">
					<div class="content-block"  style="padding:0px; margin-top:70px; width:100%;"> 
				
				<div id="ristorantediv">
				
				
			 ';
		
			  echo $testo;
		
					
				$inc=1;
				include('ristorante.inc.php');
				
				
				
echo '</div>


</div>


</div>




		
		  
		  


';


?>