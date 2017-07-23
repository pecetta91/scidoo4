<?php
header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');
include('../../config/funzionilingua.php');
$IDutente=intval($_SESSION['ID']);
$IDstruttura=intval($_SESSION['IDstruttura']);
unset($_SESSION['IDsottotip']);
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
	$IDtipo=0;
	if(isset($_GET['dato1'])){
		if($_GET['dato1']==2){
			$IDtipo=2;
			$IDsottotip=2;
		}else{
			$IDsottotip=$_GET['dato1'];
		}
	}else{
		if(isset($_SESSION['IDsottotip'])){
			$IDsottotip=$_GET['dato1'];
		}else{
			$query2="SELECT ID FROM sottotipologie WHERE IDstr='$IDstruttura' AND IDmain ='4' ORDER BY ord LIMIT 1";
			$result2=mysqli_query($link2,$query2);
			$row=mysqli_fetch_row($result2);
			$IDsottotip=$row['0'];
		}
	}
	$_SESSION['IDsottotip']=$IDsottotip;
	if($IDsottotip!=2){
$query2="SELECT sottotipologia FROM sottotipologie WHERE IDstr='$IDstruttura' AND IDmain ='4' ORDER BY ord LIMIT 1";
			$result2=mysqli_query($link2,$query2);
			$row=mysqli_fetch_row($result2);
			$nomesotto=$row['0'];
	}else{
		$nomesotto='Trattamenti';
	}
	//onclick="backcentro(1)"
$testo='



<div data-page="centrobenesseregiorno" class="page"> 
			 <div class="navbar" >
				<div class="navbar-inner">
					<div class="left">
					
					 <a href="#" class="link icon-only" onclick="backexplode(4,'.$_SESSION['timecal'].')"   >
						<i class="material-icons" style="font-size:40px;">chevron_left</i>
					</a>
					
					</div>
					<div class="center titolonav">'.$nomesotto.'<br><span style="font-size:11px;">'.dataita($time).'</span></div>
					<div class="right"  >
						<a href="#" onclick="opensosp()" >
							<i class="icon" >
								<i class="material-icons" style="font-size:25px; ">watch_later</i>
								<br>
								
								<span class="badge bg-red" id="badgecentro" style="margin-left:-15px; "></span>
							</i>
						</a>
						<a href="#" onclick="selprenot()" >
							<i class="icon">
								<i class="material-icons" style="font-size:30px;">add</i><br>
								
							</i>
						</a>
					</div>
				</div>
			 ';
			 
			echo $testo;
			$inc=1;
			include('centrobenesseregiorno.inc.php');
				
				
echo '
<input type="hidden" id="funccentro" value="navigation(14,'."'".$time.",".$IDsottotip."'".',6,1)">
</div></div>';


?>