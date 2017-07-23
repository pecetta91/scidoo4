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

$_SESSION['tipoaddnav']=0;

$testo='
<div data-page="centrobenessere" class="page"> 

			 <div class="navbar">
				<div class="navbar-inner">
					<div class="left">
					 <a href="#" class="link icon-only" onclick="creasessione(0,88);mainView.router.back();" >
						<i class="material-icons" style="font-size:30px;">apps</i>
					</a>
					
					</div>
					<div class="center titolonav">Centro Benessere</div>
					<div class="right" >
						
					</div>
				</div>
			</div>';
			 
			
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
		
			  echo $testo;
		
				$inc=1;
				include('centrobenessere.inc.php');
				
				
				
echo '</div></div>';


?>