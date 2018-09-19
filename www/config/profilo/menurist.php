<?php 
	include('../../../config/connecti.php');
	include('../../../config/funzioni.php');
	header('Access-Control-Allow-Origin: *');
	$testo='';


$IDpren=$_SESSION['IDstrpren'];
$route=$_SESSION['route'];

$timeoggi=time();


$query="SELECT app,gg,time,tempg,tempn,checkout,IDstruttura FROM prenotazioni WHERE IDv='$IDpren' LIMIT 1";

$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDapp=$row['0'];
$gg=$row['1'];
$time=$row['2'];
$tempg=round($row['3']);
$tempn=round($row['4']);
$checkout=$row['5'];
$IDstr=$row['6'];

$IDsottosel=0;

if(isset($_GET['dato0'])){
	$IDsottosel=$_GET['dato0'];
}
if($IDsottosel==0){
	$query="SELECT ID FROM sottotipologie WHERE IDmain='1' AND IDstr='$IDstr' LIMIT 1";
	$result=mysqli_query($link2,$query);
	$row=mysqli_fetch_row($result);
	$IDsottosel=$row['0'];	
}

$query="SELECT sottotipologia FROM sottotipologie WHERE ID='$IDsottosel' AND IDstr='$IDstr' LIMIT 1";
	$result=mysqli_query($link2,$query);
	$row=mysqli_fetch_row($result);
	$sottotipologia=$row['0'];	






$data=date('Y-m-d',$time);
$dataoggi=date('Y-m-d',$timeoggi);
$finepren=date('Y-m-d',$checkout);

list($yy, $mm, $dd) = explode("-", $data);
$timearr0=mktime(0, 0,0, $mm, $dd, $yy);

list($yy, $mm, $dd) = explode("-", $dataoggi);
$timeoggi0=mktime(0, 0,0, $mm, $dd, $yy);

$arrserv=array();


$query="SELECT FROM_UNIXTIME(dp.data,'%d') FROM dispgiorno as dp,servizi as s WHERE dp.IDsottotip='$IDsottosel' AND  FROM_UNIXTIME(dp.data,'%Y-%m-%d') BETWEEN '$dataoggi' AND '$finepren' AND dp.IDpiatto=s.ID GROUP BY FROM_UNIXTIME(dp.data,'%d')";
$result=mysqli_query($link2,$query);
if(mysqli_num_rows($result)>0){
	while($row=mysqli_fetch_row($result)){
	$arrserv[$row['0']]='ok';
	}
}



$sel=$timearr0;
$giornosel=date('N',$time);	
$giornocheckout=date('N',$checkout);	


if(($timeoggi>$time)&&($timeoggi<$checkout)){
	$sel=$timeoggi0;
}

$giornooggi=date('N',$sel);
if($giornooggi>=$giornosel){
	$start=floor((($sel-$timearr0)/86400)/7);
}else{
	$start=1+floor((($sel-$timearr0)/86400)/7);	
}

if($giornocheckout>=$giornosel){
	$sett=floor((($checkout-$timearr0)/86400)/7);
}else{
	$sett=1+floor((($checkout-$timearr0)/86400)/7);
	
}

$arr=array();
$start2=0;
$giornoocc=array();

$timestart0=$timearr0-86400*($giornosel-1);

for($j=0;$j<=$sett;$j++){
	for($i=1;$i<=7;$i++){
		
		$tt=$timestart0+(86400*($i-1))+(86400*7*$j);
		
		if($tt==$sel){
			//selezionato
		}

		if(($tt>=$timearr0)&&($tt<=$checkout)){
			$arr[$j][$i]='funzione';
			
			if($tt<$timeoggi0){
					$arr[$j][$i]='';
				}

		}else{
			$arr[$j][$i]='';
		}
	}
}

$testo='
<div data-page="menuristo" class="page with-subnavbar" > 
		 	<div class="navbar" id="navcal" >
				<div class="navbar-inner">
					<div class="left navbarleftsize260">
						<a href="#" class="link icon-only back fs15"   >
							<i class="material-icons">chevron_left</i>
							<strong class="stiletitolopagine">Menu '.$sottotipologia.'</strong>
						</a>
					</div>
					<div class="center titolonav"></div>
					<div class="right"></div>
				</div>
				<div class="subnavbar navbarslider">
						<div class="swiper-container sw1 stileswipernuovo">
								<table class="stiletablegiorni">
												<tr>
												<td class="fs8 fw600 c000 textcenter">L</td>
												<td class="fs8 fw600 c000 textcenter">M</td>
												<td class="fs8 fw600 c000 textcenter">M</td>
												<td class="fs8 fw600 c000 textcenter">G</td>
												<td class="fs8 fw600 c000 textcenter">V</td>
												<td class="fs8 fw600 c000 textcenter">S</td>
												<td class="fs8 fw600 c000 textcenter">D</td>

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
					 	
						$css='giornonorm';//classe normale
						$giorno=$timestart0+(86400*($giorn-1))+(86400*7*$sett);
						
						$txtg=date('j',$giorno);
						
							
						
						if(($giorn==6) || ($giorn==7)){
							$css='giornofest';
						}	
						
						if($giorno==$sel){
							$active='giornosel';
							/*if(($giorno==$sel) && (($giorno==6)||($giorno==7))){
								$active='giornofestsel';
							}*/
						}
							
						

						if($primogiorno==0){
							$meseprimo=$mesiita[date('n',$giorno)];
							$primogiorno=1;
						}
		
						if(isset($arrserv[date('d',$giorno)])){
							$serviziopres='<div class="servpresslider"></div>';
						}
			
						$jj++;
						$func='scorridata('.$jj.');navigationtxt2(12,'."'".$IDsottosel.",".$giorno."'".','."'menuservdiv'".',0)';
						
						if($key==''){
							$css='giornnone';
							$func='';
						}
						
					$testo.='<td class="fs17 giornitd" >'.$serviziopres.'<div class="sceglig '.$css.' '.$active.'" id="td'.$jj.'"  onclick="'.$func.'" padre="'.$sett.'">'.$txtg.'</div></td>';	
					}
					$testo.='</tr>
					<tr>
					<td colspan="7" class="fs14 pl15 c000 pt5">'.$meseprimo.'</td>
					</tr>
					</table>
					</div>';
					$primogiorno=0;
				}
				

			$testo.='</div></div>
			
			
				</div>
			</div>	
				<div class="page-content">
			<div class="content-block" id="menuservdiv"> ';
				//print_r($arr);
				//echo $start;

echo $testo;
$inc=1;

include('menurist.inc.php');
	
echo '
</div>
</div>
</div>';
?>