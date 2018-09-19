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
	}else{
		if((isset($_SESSION['timecal']))&&($_SESSION['timecal']!=0)){
			$time=$_SESSION['timecal'];
		}
		
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
//$tt=$time0oggi+$i*86400;




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
$query="SELECT GROUP_CONCAT(DISTINCT (ID) ) FROM sottotipologie WHERE IDmain='1' AND IDstr='$IDstruttura' GROUP BY IDstr";
$result=mysqli_query($link2,$query);
if(mysqli_num_rows($result)>0){
	$row=mysqli_fetch_row($result);
	$sottotipo=$row['0'];
}
$query="SELECT FROM_UNIXTIME(time,'%d'),FROM_UNIXTIME(time,'%m') FROM prenextra WHERE FROM_UNIXTIME(time,'%Y-%m-%d') BETWEEN '$datain' AND '$datafin' AND sottotip IN($sottotipo) AND IDstruttura='$IDstruttura' AND tipolim NOT IN(4,5,7,8) GROUP BY FROM_UNIXTIME(time,'%d')";
$result=mysqli_query($link2,$query);
if(mysqli_num_rows($result)>0){
	while($row=mysqli_fetch_row($result)){
	$arrserv[$row['1']][$row['0']]='ok';
	}
}

//<div class="row no-gutter" style="background-color:#f1f1f1"><div class="col-20"><i class="icon ion-android-restaurant fs25" style="color:#007aff"></i></div><div class="col-80" style="font-size:19px;text-align:left;color:#333;font-weight:400">'.$nomes.'</div></div>

$testo='
<div data-page="ristorante" class="page with-subnavbar"> 
			 <div class="navbar" style="border:none;">
				<div class="navbar-inner">
					<div class="left navbarleftsize170" >
					
					 <a href="#" class="link icon-only" onclick="creasessione(0,88);mainView.router.back();" >
						<i class="material-icons">chevron_left</i>
						<strong class="stiletitolopagine">Ristorante</strong>
					</a>
					
					</div>
					
					<div class="center "></div>
					<div class="right" >
						<div style="position:relative;width:40px;height:44px;margin-left:35px;margin-top:15px">
							<div style="position:absolute;width:40px;height:44px"><i class= "f7-icons fs25" >calendar</i></div>
							<input type="text" id="datacalen" onclick="myCalendar2.open()" style="position:absolute;z-index:999;width:44px;height:40px;background:none;font-size:0px">
						</div>
					
					<input type="hidden" id="tempotime" value="">
				
					<a  class="link icon-only" onclick="accordionsottotip();" style="margin-top:8px"><i class="f7-icons fs25" >add</i></a>  ';

			$query="SELECT ID,sottotipologia FROM sottotipologie WHERE IDmain='1' AND IDstr='$IDstruttura' ORDER BY ord";
			$result=mysqli_query($link2,$query);
			if(mysqli_num_rows($result)>0){
			while($row=mysqli_fetch_row($result)){
				$IDsottotip=$row['0'];
				$nomes=$row['1'];
				
				$button='<div>'.$nomes.'</div>';
				
						$infop.=' buttons.push({
						text: '."'".$button."'".',
						onClick: function () {
							navigation(34,'."'".$time.','.$IDsottotip."'".','."'nuovotavolo'".',0);}
						}); ';
				}
			}
						$testo.='<input type="hidden" value="'.base64_encode($infop).'" id="infosottotip" >';


				$testo.='</div>
				</div>
					<div class="subnavbar navbarslider">
						<div class="swiper-container sw2 stileswipernuovo">
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
					 	
						$css='giornonorm';//classe normale
						$giorno=$timestart0+(86400*($giorn-1))+(86400*7*$sett);
						
						$txtg=date('j',$giorno);
						
							
						
						if(($giorn==6) || ($giorn==7)){
							$css='giornofest';
						}	
						
						if($giorno==$sel){
							$active='giornosel';
						}
							

						if($primogiorno==0){
							$meseprimo=$mesiita[date('n',$giorno)];
							$primogiorno=1;
						}

						$jj++;
						$func='scorridata('.$jj.');sceglitiporist('."'".$giorno."'".');';
						
						if(isset($arrserv[date('m',$giorno)][date('d',$giorno)])){
							$serviziopres='<div class="servpresslider"></div>';
						}
						
					
						
						$testo.='<td class="fs17 giornitd">'.$serviziopres.'<div class="sceglig '.$css.' '.$active.'" id="td'.$jj.'"  onclick="'.$func.'" padre="'.$sett.'">'.$txtg.'</div></td>';	
					}
					$testo.='</tr>
					<tr>
					<td colspan="7"  class="fs14 pl15 c000 pt7">'.$meseprimo.'</td>
					</tr>
					</table>
					</div>';
					$primogiorno=0;
				}
				

			$testo.='</div></div></div>
				
				
				
			</div>';
			 

/* <div class="subnavbar">
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
					
				</div>*/

			
           $testo.='
			 <div class="page-content hide-navbars-on-scroll" style="z-index:-1;">
					<div class="content-block"  style="padding:0px; margin-top:55px; width:100%;"> 
				
				<div id="ristorantediv">
				
				
			 ';
		
			  echo $testo;

				$inc=1;
				include('ristorante.inc.php');
				
				
				
echo '<br><br>
</div>




</div>

</div>


<div class="button button-round button-fill pulscentroben" onclick="sceglitiporist(0);" id="pulscontenuto" style="position:absolute;bottom:10px;right:10px;width:55px;height:55px;background-color:#007aff;line-height:52px">Men&#xFA;</div>

</div>

		
		  
		  


';


?>