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
//1 settimana indietro 3 avanti



list($yy, $mm, $dd) = explode("-",date('Y-m-d',$time));
$time0=mktime(0, 0, 0, $mm, $dd, $yy);

$timeinizio=$time0 - ((86400*7));
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
//arrivi
$query="SELECT FROM_UNIXTIME(time,'%d'),FROM_UNIXTIME(time,'%m') FROM prenotazioni WHERE FROM_UNIXTIME(time,'%Y-%m-%d') BETWEEN '$datain' AND '$datafin' AND IDstruttura='$IDstruttura' AND stato>='0' GROUP BY FROM_UNIXTIME(time,'%d')";
$result=mysqli_query($link2,$query);
if(mysqli_num_rows($result)>0){
	while($row=mysqli_fetch_row($result)){
	$arrserv[$row['1']][$row['0']]='ok';
	}
}
//partenze
$query="SELECT FROM_UNIXTIME(checkout,'%d'),FROM_UNIXTIME(checkout,'%m') FROM prenotazioni WHERE FROM_UNIXTIME(checkout,'%Y-%m-%d')BETWEEN '$datain' AND '$datafin'  AND IDstruttura='$IDstruttura' AND stato>='0' GROUP BY FROM_UNIXTIME(checkout,'%d')";
$result=mysqli_query($link2,$query);
if(mysqli_num_rows($result)>0){
	while($row=mysqli_fetch_row($result)){
	$arrserv[$row['1']][$row['0']]='ok';
	}
}

$testo='
<div data-page="arrivi" class="page with-subnavbar"> 
			 <div class="navbar">
				<div class="navbar-inner">
					<div class="left navbarleftsize170">
					 <a href="#" class="link icon-only back" >
						<i class="material-icons">chevron_left</i>
						<strong class="stiletitolopagine">Elenco Arrivi  </strong>
					</a>
					
					</div>
					<div class="center titolonav"></div>
					<div class="right" >
					</div>
				</div>	
			<div class="subnavbar navbarslider">
						<div class="swiper-container sw2 stileswipernuovo" style="padding-top:10px;height:75px;">
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
						}
							
						if($primogiorno==0){
							$meseprimo=$mesiita[date('n',$giorno)];
							$primogiorno=1;
						}

						$jj++;
						$func='scorridata('.$jj.');navigationtxt(17,'.$giorno.','."'arrividiv'".',0);';
						
						if(isset($arrserv[date('m',$giorno)][date('d',$giorno)])){
							$serviziopres='<div class="servpresslider"></div>';
						}
						
						$testo.='<td class="fs17 giornitd">'.$serviziopres.'<div class="sceglig '.$css.' '.$active.'" id="td'.$jj.'"  onclick="'.$func.'" padre="'.$sett.'">'.$txtg.'</div></td>';	
					}
					$testo.='</tr>
					<tr>
					<td colspan="7" class="fs14 pl15 c000 pt5">'.$meseprimo.'</td>
					</tr>
					</table>
					</div>';
					$primogiorno=0;
				}
				

			$testo.='</div></div></div>
				
				
				
			</div>';
		 

          $testo.='<div class="page-content">
	          <div class="content-block" id="arrividiv" style="padding:0px; width:100%;margin-top:60px"> 
			 ';
			 
			  echo $testo;
				$inc=1;
				include('arrivi.inc.php');
				
				
				
echo '<br><br>
</div>
</div>
</div>';


?>