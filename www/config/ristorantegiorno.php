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


					$maxp=array();
					$sale=array();
					$IDsalamain=0;
					$query="SELECT s.ID,s.nome,s.maxp FROM sale as s,saleassoc as sc WHERE sc.IDsotto='$IDsottotip' AND sc.ID=s.ID ORDER BY sc.priorita";
					$result=mysqli_query($link2,$query);
					if(mysqli_num_rows($result)>0){
						while($row = mysqli_fetch_row($result)){
							if($IDsalamain==0)$IDsalamain=$row['0'];
							$sale[$row['0']]=$row['1'];
							$maxp[$row['0']]=$row['2'];
						}
					}
						
/*	$check1=' class="select3"';
$check2='';
if($_SESSION['tavolisosp']==2){
	$check2=' class="select3"';
	$check1='';
}
*/

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
$query="SELECT GROUP_CONCAT(DISTINCT (ID) ) FROM servizi WHERE IDtipo='1' AND IDsottotip='$IDsottotip' AND IDstruttura='$IDstruttura' GROUP BY IDstruttura";
$result=mysqli_query($link2,$query);
if(mysqli_num_rows($result)>0){
	$row=mysqli_fetch_row($result);
	$servsottorip=$row['0'];
}
$query="SELECT FROM_UNIXTIME(time,'%d'),FROM_UNIXTIME(time,'%m') FROM prenextra WHERE FROM_UNIXTIME(time,'%Y-%m-%d') BETWEEN '$datain' AND '$datafin' AND extra IN($servsottorip) AND IDstruttura='$IDstruttura' AND tipolim NOT IN(4,5,7,8) GROUP BY FROM_UNIXTIME(time,'%d')";
$result=mysqli_query($link2,$query);
if(mysqli_num_rows($result)>0){
	while($row=mysqli_fetch_row($result)){
	$arrserv[$row['1']][$row['0']]='ok';
	}
}







$not1='';

if($not['0']>0){
	$not1='<div class="notific4">'.$not['0'].'</div>';
}

/*<div class="subnavbar" align="center">
						<div class="ristorante-navbar" style="padding:0px;margin:auto;">
							<table style="width:100%; ">
							 <tr>
							  <td style="width:48%">';
                               $stringa="'".$time.','.$IDsottotip.",0'";
$testo.='
								<a class="buttonristo active" id="tavoli" onclick="buttonristoact(1);navigationtxt(20,'.$stringa.','."'".'ristorantegiornodiv'."'".',0);">Elenco Tavoli'.$not1.'</a>
							  </td> ';
					


$stringa="'".$time.','.$IDsottotip.",1'";
$testo.='	 <td style="width:47%">
<a class="buttonristo" id="sale" onclick="buttonristoact(2);navigationtxt(20,'.$stringa.','."'".'ristorantegiornodiv'."'".',0);">Sale '.$not1.'</a>
		</td> 

    
							 </tr>
						 </table>
						 </div>
					 
				 </div>*/








//<a href="#" onclick="nuovotavolo()" style="width:50px;" class="open-about"><i class="icon f7-icons" style="font-size:25px; line-height:35px">add</i></a>
$testo='
<div data-page="ristorantegiorno" class="page with-subnavbar"> 

			 <input type="hidden" id="funccentro3" value="navigation(13,'."'".$time.",".$IDsottotip."'".',6,1)">
			 <input type="hidden" id="IDsottotip" value="'.$IDsottotip.'">
			 <div class="navbar">
				<div class="navbar-inner">
					<div class="left navbarleftsize170">
					
					 <a href="#" class="link icon-only" onclick="backexplode2(2)"  >
						<i class="material-icons" style="font-size:40px;">chevron_left</i>
						<strong class="stiletitolopagine">'.$sottotipname.'</strong>
					</a>
					
					</div>
					<div class="center titolonav" ></div>
					<div class="right">
						<a href="#" onclick="navigation(34,'."'".$time.','.$IDsottotip."'".','."'nuovotavolo'".',0);" style="width:50px;" class="open-about">
						<i class="icon f7-icons fs25 " >add</i></a>
					</div>
				</div>		
			<div class="subnavbar navbarslider" >
						<div class="swiper-container sw3 stileswipernuovo" >
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
						$func='scorridata('.$jj.');sceglitiporistgiorno('."'".$giorno."'".');';
						
						if(isset($arrserv[date('m',$giorno)][date('d',$giorno)])){
							$serviziopres='<div class="servpresslider2"></div>';
						}
						
					
						
					$testo.='<td class="fs17 giornitd">'.$serviziopres.'<div class="sceglig2 '.$css.' '.$active.'" id="ttd'.$jj.'"  onclick="'.$func.'" padre2="'.$sett.'">'.$txtg.'</div></td>';	
					}
					$testo.='</tr>
					<tr>
					<td colspan="7" class="fs14 pl15 pt7 c000">'.$meseprimo.'</td>
					</tr>
					</table>
					</div>';
					$primogiorno=0;
				}
				

			$testo.='			</div>
						</div>
			</div>
		</div>
				
				
				<div class="page-content">
				<div class="content-block" id="ristorantegiornodiv" align="center" style="padding-top:10px; width:100%;">';
		
			  echo $testo;
				$inc=1;
				include('ristorantegiorno.inc.php');
				
				
echo '
</div>
<br><br>


</div>

<div class="button button-round button-fill pulscentroben" onclick="sceglitiporistgiorno(0);" id="pulscontenuto2" style="position:absolute;bottom:10px;right:10px;width:105px;height:30px;background-color:#007aff;z-index:999">Sale</div>



</div>


';


?>