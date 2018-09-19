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
$tempg=$row['3'];
$tempn=$row['4'];
$checkout=$row['5'];
$IDstr=$row['6'];


$data=date('Y-m-d',$time);
$dataoggi=date('Y-m-d',$timeoggi);
$finepren=date('Y-m-d',$checkout);

list($yy, $mm, $dd) = explode("-", $data);
$timearr0=mktime(0, 0,0, $mm, $dd, $yy);

list($yy, $mm, $dd) = explode("-", $dataoggi);
$timeoggi0=mktime(0, 0,0, $mm, $dd, $yy);

$arrserv=array();

$query="SELECT FROM_UNIXTIME(time,'%d') FROM prenextra WHERE FROM_UNIXTIME(time,'%Y-%m-%d') BETWEEN '$data' AND '$finepren' AND IDpren='$IDpren' AND IDstruttura='$IDstr' AND tipolim NOT IN(4,5,7,8) GROUP BY FROM_UNIXTIME(time,'%d')";
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
			/*if($tt<$timeoggi0){
					$arr[$j][$i]='';
				}
*/
		}else{
			$arr[$j][$i]='';
		}
	}
}
























/*

		if($timeoggi>$time){
			//data di inizio è oggi
			$giornoin=date('N',$timeoggi);		
		}else{
			//data di inizio è il giorno della pren
			$giornoin=date('N',$time);
		}
		$data[0][0]=$giornoin;
	
		$diff=floor(($checkout-$time)/86400); 
		
			if($diff>=7)
			{
				//più di una settimana 
				//
				
			}
					
					$timepren=$time;//check-in
	
			for($i=0;$i<$diff;$i++){
				//prendo la data
				//controllo se è minore del giorno di inizio o maggiore
				//se minore va in negato senno in positivo
						if($timepren==$giornoin){
							//non fai nulla
						}
					
						if($timepren<$giornoin)
						{
							//metti i giorni in negativo
							$giorno=$timepren;
						}
						if($timepren>$giornoin)
						{
							//metti i giorni in positivo
							$giorno=$timepren;
						}
				$timepren+=86400;
				
			}


foreach($data as $key => $valore){
	
	
}*/
	
	
// swiper-init"  data-speed="400"  data-pagination=".swiper-pagination" data-space-between="10" data-slides-per-view="1




$testo='
<div data-page="promemoriaserv" class="page with-subnavbar" > 
		 	<div class="navbar" id="navcal" >
				<div class="navbar-inner">
					<div class="left navbarleftsize170">
					
					  <a href="#" class="link icon-only back"   >
						<i class="material-icons">chevron_left</i>
							<strong class="stiletitolopagine">Promemoria</strong>
					</a>
					
					</div>
					<div class="center titolonav"></div>
					<div class="right" onclick="navigation2(5,-1,0,0)"><i class="material-icons fs30">add</i></div>
				</div>
				<div class="subnavbar navbarslider " >
						<div class="swiper-container sw1 stileswipernuovo" >
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
						$func='scorridata('.$jj.');navigationtxt2(10,'.$giorno.','."'promemoriaservdiv'".',0);';
						
						if($key==''){
							$css='giornnone';
							$func='';
						}
						
					$testo.='<td class="fs17 giornitd">'.$serviziopres.'<div class="sceglig '.$css.' '.$active.'" id="td'.$jj.'"  onclick="'.$func.'" padre="'.$sett.'">'.$txtg.'</div></td>';	
					}
					$testo.='</tr>
					<tr>
					<td colspan="7"  class="fs14 pl15 c000 pt5">'.$meseprimo.'</td>
					</tr>
					</table>
					</div>';
					$primogiorno=0;
				}
				

			$testo.='</div></div>
			
			
				</div>
			</div>	
				<div class="page-content">
			<div class="content-block" id="promemoriaservdiv"> ';
				//print_r($arr);
				//echo $start;

echo $testo;
$inc=1;
	include('promemoriaserv.inc.php');

echo '
</div>
</div>
</div>';
?>