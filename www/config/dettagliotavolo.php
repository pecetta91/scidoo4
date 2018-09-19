<?php

header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');


$IDstruttura=$_SESSION['IDstruttura'];
$IDprenextra=$_POST['IDprenextra'];
$vis=@$_POST['vis'];

$query="SELECT time,IDpren,sottotip FROM prenextra WHERE ID='$IDprenextra' LIMIT 1";
	$result=mysqli_query($link2,$query);
	$row=mysqli_fetch_row($result);
	$timeprenextra=$row['0'];
	$IDpren=$row['1'];
	$IDsottotip=$row['2'];
	
	$time=$timeprenextra;
	
	$IDprenunit=prenotstessotav($IDpren);
	$IDprenunitmain=$IDprenunit;

	
$numadd='';

$query4="SELECT p2.IDprenextra,p2.prezzo,s.servizio,p2.qta FROM prenextra2 as p2,prenextra as p,servizi as s WHERE p2.pacchetto='-$IDprenextra' AND p2.IDprenextra=p.ID AND p.extra=s.ID";
$result4=mysqli_query($link2,$query4);
$numadd2=mysqli_num_rows($result4);
if($numadd2>0){
	$numadd='<span style="font-size:15px;">'.$numadd2.'</span>';
}

$txtpers='<input type="hidden" id="IDprenextradet" value="'.$IDprenextra.'">';

$query2="SELECT p2.IDinfop,p.extra,p2.prezzo,p2.pacchetto,p2.paga,p2.qta  FROM prenextra as p,prenextra2 as p2 WHERE FROM_UNIXTIME(p.time,'%Y-%m-%d')=FROM_UNIXTIME('$time','%Y-%m-%d')  AND p.IDstruttura='$IDstruttura' AND  p.sottotip='$IDsottotip' AND p2.IDprenextra=p.ID AND p.modi>='0' AND p.IDpren IN($IDprenunit)  ";
		$result2=mysqli_query($link2,$query2);
		if(mysqli_num_rows($result2)>0){
			while($row=mysqli_fetch_row($result2)){
				$IDinfop=$row['0'];
				$extra=$row['1'];
				$prezzo=$row['2'];
				$pacc=$row['3'];
				
				$paga=$row['4'];
				$qta=$row['5'];
				
				$modprezzo=1;
				if($pacc!=0){
					$prezzo='Incluso';
					$modprezzo=0;
				}else{
					$prezzo=$prezzo.' €';
				}
				if($qta>0){
					if($paga==1){
						$presente='Presente e Paga';
						$color="31b061";
					}
				}else{
					if($paga==1){
						$presente='Assente e Paga';
						$color="fc8e00";
					}else{
						$presente='Assente e Non Paga';
						$color="b83c48";
					}
				}
				
				
				
				$query3="SELECT servizio FROM servizi WHERE ID='$extra' LIMIT 1";
				$result3=mysqli_query($link2,$query3);
				$row3=mysqli_fetch_row($result3);
				$servizio=$row3['0'];
				
				$nome=estrainomecli($IDinfop);
				$tipocli=estraitipocli($IDinfop);
				
				$notecli='';
				$query3="SELECT s.noteristo FROM infopren as i,schedine as s WHERE i.ID ='$IDinfop' AND i.IDcliente=s.ID AND s.noteristo!='' LIMIT 1";
				$result3=mysqli_query($link2,$query3);
				if(mysqli_num_rows($result3)>0){
					$row3=mysqli_fetch_row($result3);
					$notecli='<br><b style="color:#bb2c1d;">'.$row3['0'].'</b>';
				}
				
				
				//presenza
				//costo / pacchetto
				
				$txtsend=$IDprenextra.'/'.$IDinfop;
				
				
				$txtpers.='
				<div class="row rowlist no-gutter" onclick="presenzaospiti('."'".$txtsend."'".','.$modprezzo.')">
					<div class="col-45">'.$nome.'<br><span style="font-size:10px;font-weight:400; color:#999;">'.$tipocli.''.$notecli.'</span></div>
					<div class="col-30">'.$servizio.'<br><span style="font-size:10px; font-weight:400; color:#'.$color.';">'.$presente.'</span></div>
					<div class="col-15">'.$prezzo.'</div>
					<div class="col-10"><i class="f7-icons icon">compose</i></div>
				</div>
				';
				
				
				/*<li class="item-content">
				  <div class="item-inner">
					<div class="item-title"></div>
					<div class="item-after">'.$servizio.'</div>
				  </div>
				</li>*/
				
				
			}
		}
		
	//	$txtpers.='</ul></div>';


if($vis==1){
	echo $txtpers;
}else{
	echo '
		<div class="picker-modal smart-select-picker" id="popoverord">
		  <div class="toolbar">
			<div class="toolbar-inner">
			  <div class="left"></div>
			  <div class="right"><a href="#" class="close-picker">Chiudi</a></div>
			</div>
		  </div>
		 <div class="picker-modal-inner ">
		 <div class="page-content" style="background-color: white;" > 
		 <div id="txtpersdet">
		  '.$txtpers.'</div>
		  </div>
	</div>
	</div>
	
	
	';
}

	
?>




