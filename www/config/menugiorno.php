<?php
header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');
include('../../config/funzionilingua.php');
$IDutente=intval($_SESSION['ID']);
$IDstruttura=intval($_SESSION['IDstruttura']);


$time=$_SESSION['timecal'];

$data=date('Y-m-d',$time);

$dataini=$data;
$datafin=date('Y-m-d',($time+86400*7));
$IDtipo=1;
if(isset($_GET['dato0'])){
	$IDsottotip=$_GET['dato0'];
}else{
	if(isset($_SESSION['IDsottotip'])){
		$IDsottotip=$_GET['dato0'];
	}else{
		$query2="SELECT ID FROM sottotipologie WHERE IDstr='$IDstruttura' AND IDmain ='1' ORDER BY ord LIMIT 1";
		$result2=mysqli_query($link2,$query2);
		$row=mysqli_fetch_row($result2);
		$IDsottotip=$row['0'];
	}
}
$_SESSION['IDsottotip']=$IDsottotip;



$query2="SELECT sottotipologia FROM sottotipologie WHERE ID='$IDsottotip' LIMIT 1";
$result2=mysqli_query($link2,$query2);
$row2=mysqli_fetch_row($result2);
$sottotipname=$row2['0'];

$not1='';

if($not['0']>0){
	$not1='<div class="notific4">'.$not['0'].'</div>';
}

//<a href="#" onclick="nuovotavolo()" style="width:50px;" class="open-about"><i class="icon f7-icons" style="font-size:25px; line-height:35px">add</i></a>
$testo='
<div class="pages navbar-fixed">
<div data-page="ristorantegiorno" class="page with-subnavbar"> 
			 
			 <input type="hidden" id="funccentro3" value="navigation(13,'."'".$time.",".$IDsottotip."'".',6,1)">
			 
			 <div class="navbar">
				<div class="navbar-inner">
					<div class="left">
					
					 <a href="#" class="link icon-only" onclick="backexplode(5,'."'".$_SESSION['timecal'].",1'".');"  >
						<i class="material-icons" style="font-size:40px;">chevron_left</i>
					</a>
					
					</div>
					<div class="center titolonav" style="line-height:14px;">MENU: '.$sottotipname.'<br><span style="font-size:11px;">'.dataita($time).'</span></div>
					<div class="right" >
						
					</div>
					
					
				 </div>
			</div>
			   
			   <div class="page-content">
			   <div id="menugiorno">';
		
			  echo $testo;
				$inc=1;
				include('menugiorno.inc.php');
				
				
echo '</div>
</div></div>
</div>
</div>
';


?>