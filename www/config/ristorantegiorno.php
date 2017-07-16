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



if(isset($_GET['dato0'])){
		if($_GET['dato0']!='0'){
			$time=$_GET['dato0'];
		}else{
			if(isset($_SESSION['timecal'])){
				$time=$_SESSION['timecal'];
			}else{
				$time=time();
			}
		}
	}else{
		if(isset($_SESSION['timecal'])){
			$time=$_SESSION['timecal'];
		}else{
			$time=time();
		}
	}
	$_SESSION['timecal']=$time;
	$mm=date('m',$time);
	$aa=date('Y',$time);
	$mmsucc=$mm+1;


$data=date('Y-m-d',$time);

$dataini=$data;
$datafin=date('Y-m-d',($time+86400*7));
$IDtipo=0;
if(isset($_GET['dato1'])){
	$IDsottotip=$_GET['dato1'];
}else{
	if(isset($_SESSION['IDsottotip'])){
		$IDsottotip=$_GET['dato1'];
	}else{
		$query2="SELECT ID FROM sottotipologie WHERE IDstr='$IDstruttura' AND IDmain ='1' ORDER BY ord LIMIT 1";
		$result2=mysqli_query($link2,$query2);
		$row=mysqli_fetch_row($result2);
		$IDsottotip=$row['0'];
	}
}
$_SESSION['IDsottotip']=$IDsottotip;

$query2="SELECT IDmain,sottotipologia FROM sottotipologie WHERE ID='$IDsottotip' LIMIT 1";
$result2=mysqli_query($link2,$query2);
$row=mysqli_fetch_row($result2);
$IDtipo=$row['0'];
$sottotipname=$row['1'];



$testo='
<div data-page="ristorantegiorno" class="page"> 
			 
			 <input type="hidden" id="funccentro3" value="navigation(13,'."'".$time.",".$IDsottotip."'".',6,1)">
			 
			 <div class="navbar">
				<div class="navbar-inner">
					<div class="left">
					
					 <a href="#" class="link icon-only" onclick="backexplode(5,'.$_SESSION['timecal'].')"  >
						<i class="material-icons" style="font-size:40px;">chevron_left</i>
					</a>
					
					</div>
					<div class="center titolonav" style="line-height:14px;">'.$sottotipname.'<br><span style="font-size:11px;">'.dataita($time).'</span></div>
					<div class="right" >
						<a href="#" onclick="selprenot()" style="width:50px;" class="open-about"><i class="icon f7-icons" style="font-size:25px; line-height:35px">add</i></a>
					</div>
				</div>
			
			
			 
			 ';
		
			  echo $testo;
				$inc=1;
				include('ristorantegiorno.inc.php');
				
				
echo '</div>
</div>
';


?>