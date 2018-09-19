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

$_SESSION['tipoaddnav']=0;

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
$query="SELECT GROUP_CONCAT(DISTINCT (ID) ) FROM sottotipologie WHERE IDmain IN(2,4) AND IDstr='$IDstruttura' GROUP BY IDstr";
$result=mysqli_query($link2,$query);
if(mysqli_num_rows($result)>0){
	$row=mysqli_fetch_row($result);
	$sottotipo=$row['0'];
}
$query="SELECT FROM_UNIXTIME(time,'%d'),FROM_UNIXTIME(time,'%m') FROM prenextra  WHERE FROM_UNIXTIME(time,'%Y-%m-%d') BETWEEN '$datain' AND '$datafin' AND sottotip IN($sottotipo) AND IDstruttura='$IDstruttura' AND  modi>'0'  GROUP BY FROM_UNIXTIME(time,'%d')";
$result=mysqli_query($link2,$query);
if(mysqli_num_rows($result)>0){
	while($row=mysqli_fetch_row($result)){
	$arrserv[$row['1']][$row['0']]='ok';
	}
}

//background-color:#203a93;

$testo='
<div data-page="centrobenessere" class="page with-subnavbar"> 
			 <div class="navbar">
				<div class="navbar-inner">
					<div class="left navbarleftsize170">
					 <a href="#" class="link icon-only" onclick="creasessione(0,88);mainView.router.back();" >
				     	<i class="material-icons">chevron_left</i>
						<strong class="stiletitolopagine">Centro Benessere</strong>
					</a>
					
					
					</div>
					<div class="center titolonav"></div>
					<div class="right" >
							<div style="position:relative;width:40px;height:44px;margin-left:35px;margin-top:15px">
								<div style="position:absolute;width:40px;height:44px"><i class= "f7-icons fs25" >calendar</i></div>
								<input type="text" id="datacalen" onclick="myCalendar2.open()" style="position:absolute;z-index:999;width:44px;height:40px;background:none;font-size:0px">
						</div>
					
					<input type="hidden" id="tempotime" value="">
					</div>
				</div>';
					$testo.='
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
						$func='scorridata('.$jj.');navigationtxt(13,'.$giorno.','."'centrobenesserediv'".',0);';
						
						if(isset($arrserv[date('m',$giorno)][date('d',$giorno)])){
							$serviziopres='<div class="servpresslider"></div>';
						}
						
					
						
						$testo.='<td class="fs16 giornitd">'.$serviziopres.'<div class="sceglig '.$css.' '.$active.'" id="td'.$jj.'"  onclick="'.$func.'" padre="'.$sett.'">'.$txtg.'</div></td>';	
					}
					$testo.='</tr>
					<tr>
					<td colspan="7" class="fs14 c000 pl15 pt7">'.$meseprimo.'</td>
					</tr>
					</table>
					</div>';
					$primogiorno=0;
				}
				

			$testo.='</div></div></div>
			</div>';
			 
			/*
			
			$timeoggi=time();
			
			list($yy, $mm, $dd) = explode("-",date('Y-m-d',$timeoggi));
			$time0oggi=mktime(0, 0, 0, $mm, $dd, $yy);
			
			
			$ggstart=date('N',$time0oggi);
			$lastday=8;
			if(isset($_SESSION['lasttimedx'])){
				$lastday=($_SESSION['lasttimedx']-$time0oggi)/86400;
				$lastday=round($lastday);
			}
			
			$ggstart2=$ggstart;
			$line1='';
			$line2='';
			
			$larg=75*$lastday;
			for($i=0;$i<$lastday;$i++){
				$class='';
				$into='';
				$mex='';
				$classoggi='';
				if(($ggstart2==6)||($ggstart2==7)){
					$classoggi='week';
				}
				
				
				$classsel='';
				if($i==0){
					$classoggi='oggi';
					
					//$mex.='<br>Oggi';
				}
				$tt=$time0oggi+$i*86400;
				
				if($tt==$_SESSION['timecal']){
					$classsel="selected";
				}
				
				$ins=0;
				//
				$line1.='
				<div class="buttdatesup '.$classoggi.' "  onclick="navigationtxt(13,'.$tt.','."'centrobenesserediv'".',6)"><div class="buttdate  '.$classsel.'" id="'.$tt.'">'.$giorniita2[$ggstart2].'<br><b>'.date('d',$tt).'</b><br>'.$mesiita2[date('n',$tt)].'</div></div>
				';
				
				$ggstart2++;
				if($ggstart2==8){$ggstart2=1;}
				
				
			}
			$_SESSION['lasttimedx']=$time0oggi+86400*$lastday; 
			
			//$prox.='<tr >'.$line1.'</tr></table>';
			 
			 $testo.='<div class="infinitemaindiv" id="infinitemain" onscroll="infinitedx()"><div style="width:'.$larg.'px;" id="divintoinfinite">
			 
			
			 '.$line1.'</div></div>
			 	
			 <div class="page-content" style="z-index:-1;">
					<div class="content-block"  style="padding:0px; width:100%;"> 
				
				<div  style="margin-left:0px;" id="centrobenesserediv" style="padding:0px; width:100%;"> 
			 ';
*/ 
$testo.='<div class="page-content" style="z-index:-1;">
					<div class="content-block" style="margin-top:60px"> 
					<div id="centrobenesserediv" style="margin-left:0px;padding:0px; width:100%;">
					
					
					';
		
			  echo $testo;
		
				$inc=1;
				include('centrobenessere.inc.php');
				
				
				
echo '</div></div></div></div>';


?>