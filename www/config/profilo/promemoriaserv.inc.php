<?php
if(!isset($inc)){
	include('../../../config/connecti.php');
	include('../../../config/funzioni.php');
	$testo='';
}
$testo='';

$IDpren=$_SESSION['IDstrpren'];
$route=$_SESSION['route'];

$timeoggi=time();
if(isset($_GET['dato0'])){	
	if($_GET['dato0']!='0'){
			$timeoggi=$_GET['dato0'];		
			$_SESSION['promemserv']=$timeoggi;
	}else{
		if(isset($_SESSION['promemserv'])){
			$timeoggi=$_SESSION['promemserv'];
		}
	}
}

$dataoggi=date('Y-m-d',$timeoggi);





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

$servizigior=array();

$servizidafix=array();

	$query="SELECT p.ID,p.time,p.modi,COUNT(DISTINCT(p2.IDprenextra)),GROUP_CONCAT(DISTINCT(p.ID) SEPARATOR ','),p.tipolim,p.extra,p.modi,SUM(p2.prezzo),GROUP_CONCAT(DISTINCT(p2.IDinfop) SEPARATOR ','),SUM(p2.qta),p.sala,p.IDtipo,p.sottotip,s.servizio,s.descrizione,MAX(p2.pacchetto),s.durata FROM prenextra as p,prenextra2 as p2,servizi as s WHERE  p2.IDpren IN ($IDprenc) AND p.ID=p2.IDprenextra AND p2.paga>'0' AND p.extra=s.ID AND FROM_UNIXTIME(p.time,'%Y-%m-%d')='$dataoggi' AND  p.tipolim IN(1,2) AND p.modi>'0'  GROUP BY p.ID ORDER BY p.extra,p.time";
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
				$IDtipo=$row['11'];
				$IDtipo=$row['12'];
				$IDsotto=$row['13'];
				$tipolim=$row['5'];
				$servizio=$row['14'];
				$descr=$row['15'];
				$pacchetto=$row['16'];
				
				$durata=$row['17'];
			
			
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
			
						if(($durata!='')&&($IDtipo!=1)){
							$testodur=$durata.' min.';
						}
			
						
			
				$servizigior[$IDtipo].='
										<div class="row no-gutter rowlist" onclick="navigation(25,'.$ID.',0,0)">
											<div class="col-15 coltitle"><strong style="color:#2542d9;">'.$num2.'</strong>
											<br>
											<span>'.$testodur.'</span>
											</div>
											<div class="col-60 h40">'.$servizio.'<br>
											'.$qta.'</div>
											<div class="col-25">
											<button class=" button button-fill button-raised">Modifica</button>
											
											</div>
										
										
												
										</div>';
			
			/*	<div class="col-70 h40">'.$servizio.'</div>
													<div class="col-30" style="margin:auto"><div style="text-align:right">'.$num2.' &nbsp;<i class="f7-icons iconarigapren fs15">compose</i></div></div>
													<div class="col-40 fs11" style="color:#717171">'.$testodur.'</div>
													<div class="col-40 fs11">'.$qta.'</div>*/
			
			
		}
	
	
	}



$query="SELECT p.ID,p.time,p.modi,COUNT(DISTINCT(p2.IDprenextra)),GROUP_CONCAT(DISTINCT(p.ID) SEPARATOR ','),p.tipolim,p.extra,p.modi,SUM(p2.prezzo),GROUP_CONCAT(DISTINCT(p2.IDinfop) SEPARATOR ','),SUM(p2.qta),p.sala,p.IDtipo,p.sottotip,s.servizio,s.descrizione,MAX(p2.pacchetto),s.durata FROM prenextra as p,prenextra2 as p2,servizi as s WHERE  p2.IDpren IN ($IDprenc) AND p.ID=p2.IDprenextra AND p2.paga>'0' AND p.extra=s.ID AND p.tipolim IN(1,2) AND p.modi='0'  GROUP BY p.ID ORDER BY p.extra,p.time";
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
				$IDtipo=$row['11'];
				$IDtipo=$row['12'];
				$IDsotto=$row['13'];
				$tipolim=$row['5'];
				$servizio=$row['14'];
				$descr=$row['15'];
				$pacchetto=$row['16'];
				
				$durata=$row['17'];
			
			
						$num2='';
						if($tipolim==6){
							$servizio='N.'.$qta.' '.$servizio;
							$qta='';
						}else{
							$num2='--.--';

							$persone='persone';
							if($qta==1){
								$persone='persona';
								
							}
							$qta='<span class="persservattivi">N.'.$qta.' '.$persone.'</span>';
						}
			
						if(($durata!='')&&($IDtipo!=1)){
							$testodur=$durata.' min.';
						}
			
						
			
				$servizidafix[$IDtipo].='
				
							<div class="row no-gutter rowlist" onclick="navigation(25,'.$ID.',0,0)">
											<div class="col-15 coltitle"><strong style="color:#2542d9;">--.--</strong>
											<br>
											<span>'.$testodur.'</span>
											</div>
											<div class="col-60 h40">'.$servizio.'<br>
											'.$qta.'</div>
											<div class="col-25">
											<button class=" button button-fill button-raised bpink">Imposta</button>
											
											</div>
										
										
												
										</div>
				';
			
			/*
				
										<div class="row no-gutter rowlist" onclick="navigation(25,'.$ID.',0,0)">
											
										
										
													<div class="col-70 h40">'.$servizio.'</div>
													<div class="col-30" style="margin:auto"><div style="text-align:right">'.$num2.' &nbsp;<i class="f7-icons iconarigapren fs15">compose</i></div></div>
													<div class="col-40 fs11" style="color:#717171">'.$testodur.'</div>
													<div class="col-40 fs11">'.$qta.'</div>
										</div>*/
			
			
		}
	
	}




$query="SELECT ID,tipo FROM tiposervizio";
$result=mysqli_query($link2,$query);
while($row=mysqli_fetch_row($result)){
	$nomeservizi[$row['0']]=$row['1'];
}



$testo.='
<div class="content-block" style="padding-top:5px">
			<input type="hidden" value="'.$timeoggi.'" id="tipo">';

			foreach($servizigior as $IDtipo =>$arr){
				$testo.='<div class="content-block-title titleb">'.$nomeservizi[$IDtipo].'</div>'.$arr;
			}
			if(empty($servizigior)){
				$testo.='<div class="content-block-title titleb">Non hai nessun servizio in programma</div>';
			}




			if(!empty($servizidafix)){
				$testo.='
				
				<br/><br/>
				<div class="content-block-title titleb bpink" style=" color:#fff; padding:5px; border-radius:5px;">
				<i class="f7-icons icon" style="color:#fff;">arrow_down</i>
				
				Servizi senza Orario Prenotato<br>
				<span style="color:#fff;">Puoi indicare un orario fino a 4 ore prima </span>
				
				</div>';
				foreach($servizidafix as $IDtipo =>$arr){
					$testo.='<div class="content-block-title titleb">'.$nomeservizi[$IDtipo].'</div>'.$arr;
				}
				
			}




			








			

echo $testo;
?>
</div>