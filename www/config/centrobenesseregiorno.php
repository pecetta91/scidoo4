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



list($yy, $mm, $dd) = explode("-",date('Y-m-d',$time));
$time0=mktime(0, 0, 0, $mm, $dd, $yy);

$timeinizio=$time0 - ((86400*7)*3);
$timefine=$time0 + ((86400*7)*3);
$datain=date('Y-m-d',$timeinizio);
$datafin=date('Y-m-d',$timefine);

//time0 data di oggi
$sel=$time0;
$giornooggi=date('N',$sel);


$sett=floor((($timefine-$timeinizio)/86400)/7);

$timestart0=$timeinizio-86400*($giornooggi-1);
$arr=array();

for($j=0;$j<=$sett;$j++){
	for($i=1;$i<=7;$i++){
		
		//$tt=$timestart0+(86400*($i-1))+(86400*7*$j);
		
		//$arr[$j][$i]=date('Y-m-d',$tt);		
		$arr[$j][$i]=1;
	}
}

$arrserv=array();

$query="SELECT IDmain FROM sottotipologie WHERE ID='$IDsottotip' AND IDstr='$IDstruttura' ";
$result=mysqli_query($link2,$query);
if(mysqli_num_rows($result)>0){
	$row=mysqli_fetch_row($result);
	$IDtipol=$row['0'];
}

if($IDtipol==4){
	$query="SELECT FROM_UNIXTIME(time,'%d'),FROM_UNIXTIME(time,'%m') FROM prenextra WHERE FROM_UNIXTIME(time,'%Y-%m-%d') BETWEEN '$datain' AND '$datafin' AND sottotip='$IDsottotip' AND IDstruttura='$IDstruttura' AND modi>'0' AND sala!='0'  GROUP BY FROM_UNIXTIME(time,'%d')";
}else{
	$query="SELECT FROM_UNIXTIME(time,'%d'),FROM_UNIXTIME(time,'%m') FROM prenextra WHERE FROM_UNIXTIME(time,'%Y-%m-%d') BETWEEN '$datain' AND '$datafin'  AND IDtipo='2' AND IDstruttura='$IDstruttura' AND modi>'0' AND sala!='0'  GROUP BY FROM_UNIXTIME(time,'%d')";
}

$result=mysqli_query($link2,$query);
if(mysqli_num_rows($result)>0){
	while($row=mysqli_fetch_row($result)){
	$arrserv[$row['1']][$row['0']]='ok';
	}
}

$testo='
<div data-page="centrobenesseregiorno" class="page with-subnavbar"> 
			 <div class="navbar" >
				<div class="navbar-inner">
					<div class="left navbarleftsize170">
						 <a href="#" class="link icon-only" onclick="backexplode2(3)">
							<i class="material-icons">chevron_left</i>
							<strong class="stiletitolopagine">'.$nomesotto.'</strong>
						</a>
						
					</div>
					<div class="center titolonav"></div>
					<div class="right">
						<a href="#" onclick="selprenot()">
							<i class="icon">
								<i class="material-icons fs25">add</i><br>
							</i>
						</a>
					</div>
				</div>
				
				<div class="subnavbar navbarslider" >
						<div class="swiper-container sw3 stileswipernuovo">
						<input type="hidden" value="1" id="swiper3">
								<table class="stiletablegiorni">
												<tr>
												<td class="fs10 fw600 c000 textcenter" >L</td>
												<td class="fs10 fw600 c000 textcenter" >M</td>
												<td class="fs10 fw600 c000 textcenter" >M</td>
												<td class="fs10 fw600 c000 textcenter" >G</td>
												<td class="fs10 fw600 c000 textcenter" >V</td>
												<td class="fs10 fw600 c000 textcenter" >S</td>
												<td class="fs10 fw600 c000 textcenter" >D</td>

												</tr>
								</table>
								<div class="swiper-wrapper">'; 
			
		$primogiorno=0;
		$jj=0;

				foreach($arr as $sett =>$val){
					
					$testo.='<div class="swiper-slide" >
							<table class="stiletablegiorni">
								<tr>';
					
					foreach($val as $giorn =>$key){
						
						$serviziopres='';//servizio presente in prenextra
						$txtg='';//numero del giorno
						$active='';//vedo se giorno è selezionato
						$sospgiorn='';//sospesi
						
					 	
						$css='giornonorm';//classe normale
						$giorno=$timestart0+(86400*($giorn-1))+(86400*7*$sett);
						
						$txtg=date('j',$giorno);
						
							
						
						if(($giorn==6) || ($giorn==7)){
							$css='giornofest';
						}	
						
						if($giorno==$sel){
							$active='giornosel2';
						}


						if($primogiorno==0){
							$meseprimo=$mesiita[date('n',$giorno)];
							$primogiorno=1;
						}

						$jj++;
						$func='scorridata('.$jj.');sceglitipobenessgiorno('."'".$giorno."'".');';
						
						if(isset($arrserv[date('m',$giorno)][date('d',$giorno)])){
							$serviziopres='<div class="servpresslider2"></div>';
						}
						
					
						
					$testo.='<td class="fs17 giornitd">'.$serviziopres.'<div class="sceglig2 '.$css.' '.$active.'" id="ttd'.$jj.'"  onclick="'.$func.'" padre2="'.$sett.'">'.$txtg.'</div></td>';	
					}
					$testo.='</tr>
					<tr>
					<td colspan="7" class="fs14 c000 pl15 pt7">'.$meseprimo.'</td>
					</tr>
					</table>
					</div>';
					$primogiorno=0;
				}
				

			$testo.='</div></div>
				</div></div>
				
				
					
				  <div class="page-content" style="margin-top:20px">
	              <div class="content-block" id="centrobenesseregiornodiv" >';
			 
			echo $testo;
			$inc=1;
			include('centrobenesseregiorno.inc.php');
				
				
echo '
<input type="hidden" id="funccentro" value="navigation(14,'."'".$time.",".$IDsottotip."'".',6,1)">
<br><br>
</div></div>

<a href="#" onclick="opensosp()"  class="button button-round button-fill" style="width:60px;height:60px;position:fixed;bottom:0;right:0;margin-right:5px;margin-bottom:5px;z-index:9999;line-height:15px;padding-top:12px;padding-left:5px;border-radius:50%;"><span id="sospesi" style="margin-left:7px">0</span>&nbsp; Sospesi</a>
<div style="width:100%; height:5px;"></div>
</div>';


?>
