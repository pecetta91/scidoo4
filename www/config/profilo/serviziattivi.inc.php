<?php
if(!isset($inc)){
	include('../../../config/connecti.php');
	include('../../../config/funzioni.php');
	//
	$testo='';	
}

$testo='';

$IDpren=$_SESSION['IDstrpren'];
$route=$_SESSION['route'];


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

$IDprenc=prenotcoll($IDpren);

if(isset($_GET['dato0'])){	
	if($_GET['dato0']!='0'){
			$IDtiposel=$_GET['dato0'];		
}else{
	$querym="SELECT IDtipo FROM prenextra WHERE IDstruttura='$IDstr' AND IDpren='$IDpren' AND IDtipo NOT IN(8,9,10,12,13,14,15,16,17,18,19,20) ORDER BY  IDtipo ASC LIMIT 1";
	$resultm=mysqli_query($link2,$querym);
		if(mysqli_num_rows($resultm)>0){
			$rowm=mysqli_fetch_row($resultm);
			$IDtiposel=$rowm['0'];
		}
}
}


$testo.='<input type="hidden" value="'.$IDtiposel.'" id="tipo"> ';

$query="SELECT p.ID,p.time,p.modi,COUNT(DISTINCT(p2.IDprenextra)),GROUP_CONCAT(DISTINCT(p.ID) SEPARATOR ','),p.tipolim,p.extra,p.modi,SUM(p2.prezzo),GROUP_CONCAT(DISTINCT(p2.IDinfop) SEPARATOR ','),SUM(p2.qta),p.sala,p.IDtipo,p.sottotip,s.servizio,s.descrizione,MAX(p2.pacchetto) FROM prenextra as p,prenextra2 as p2,servizi as s WHERE  p2.IDpren IN ($IDprenc) AND p.ID=p2.IDprenextra AND p2.paga>'0' AND p.extra=s.ID AND p.IDtipo='$IDtiposel' AND p.IDtipo NOT IN(8,9)  GROUP BY p.ID ORDER BY p.extra,p.time";
	$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
			
		while($row=mysqli_fetch_row($result)){
		
		
			$ID=$row['0'];
				$time=$row['1'];
				$modi=$row['2'];
				$num2=$row['3'];
				$prezzo=$row['8'];			
				$extra=$row['6'];
				$qta=$row['10'];
				$IDtipo=$row['12'];
				$IDsotto=$row['13'];
				$tipolim=$row['5'];
				$servizio=$row['14'];
				$descr=$row['15'];
				$pacchetto=$row['16'];
			
				//$foto='immagini/'.getfoto($extra,4);
				
				$num2='';
				if($tipolim==6){
					$servizio='N.'.$qta.' '.$servizio;
					$qta='';
				}else{
					
					if($modi==0){
						$num2='--.--';
					}else{
						$num2=date('H:i',$time);
					}
					
					$persone='persone';
					if($qta==1){
						$persone='persona';
					}
					$qta='<span class="persservattivi">N.'.$qta.' '.$persone.'</span>';
				}
			
							
		
			$butt1='';
			
			$sala='';
			
			$vis='<div class="item-media"><img src="'.$route.$foto.'"></div>';
			
			$txt='<li>
				  <a href="#" class="item-link item-content" onclick="navigation(25,'.$ID.',0,0)">
						<div class="item-inner" >
					  <div class="item-title-row">
					  <div class="item-title" class="titoloservattivi">'.wordwrap($servizio,25,'<br>').'</div>
						<div class="item-after">'.$num2.'</div>
					  </div>
					  <div class="item-subtitle">'.$qta.'</div>
					</div>
				  </a>
				  
			</li>
			
			';
			
			
			$dd=date('Y-m-d',$time);
			
			if($tipolim==6){
				if(isset($prodottiarr[$dd])){
					$prodottiarr[$IDtipo][$dd].=$txt;
				}else{
					$prodottiarr[$IDtipo][$dd]=$txt;
				}
			}else{
				if($modi!=0){
					if(isset($serviziarr[$IDtipo][$dd])){
						$serviziarr[$IDtipo][$dd].=$txt;
					}else{
						$serviziarr[$IDtipo][$dd]=$txt;
					}
				}else{
					
					if(!isset($serviziarr[$IDtipo][0])){
						$serviziarr[$IDtipo][0]='';
					}
					
					if(isset($servizisosp[$IDtipo])){
						$servizisosp[$IDtipo].=$txt;
					}else{
						$servizisosp[$IDtipo]=$txt;
					}				
				}
			}
			
			
		
		
		
		
		
		}
	}else{
		$testo.='<span class="noservattivi">Nessun servizio prenotato</span>';
	}



foreach($serviziarr as $IDtipo =>$arr){
	if(!empty($arr)){
		
		
		$testo.='<div class="content-block">';
		
		
		if(isset($servizisosp[$IDtipo])){
			$testo.='<div class="content-block-title titleb" style="color:#f01150;">Servizi Sospesi</div>
					<div class="list-block media-list" >
					  <ul>'.$servizisosp[$IDtipo].'</ul></div>';
		}
		
		
		foreach ($arr as $data =>$cont){
			list($yy, $mm, $dd) = explode("-", $data);
			$time=mktime(0, 0, 0, $mm, $dd, $yy);
			
			if(strlen($cont)>0){
				$testo.='<div class="content-block-title titleb">'.dataita($time).' '.date('Y',$time).'</div>
					<div class="list-block media-list" >
					  <ul>'.$cont.'</ul></div>';
			}
		}
		
		$testo.='</div>';
	}
	
}





echo $testo;
?>