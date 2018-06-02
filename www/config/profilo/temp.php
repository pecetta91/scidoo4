<?php 
if(!isset($inc)){
	include('../../../config/connecti.php');
	include('../../../config/funzioni.php');
	header('Access-Control-Allow-Origin: *');
}

$IDpren=$_SESSION['IDstrpren'];


$query="SELECT app,gg,time,tempg,tempn FROM prenotazioni WHERE IDv='$IDpren' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDapp=$row['0'];
$gg=$row['1'];
$time=$row['2'];
$tempg=$row['3'];
$tempn=$row['4'];

$nomepren=estrainome($IDpren);
$alloggio='';
$statodom='Inattivo';
$color='333';
if($gg!=0){
	
	$query="SELECT nome,temp,statod,risc FROM appartamenti WHERE ID='$IDapp' LIMIT 1";
	$row=mysqli_fetch_row($result);
	$nome=$row['0'];
	$temp=$row['1'];
	$statod=$row['2'];
	$risc=$row['3'];
	
	
	if(($risc='1')&&($statod==1)){
		$statodom='In Riscaldamento';
		$color='c11010';
	}
	if(($risc='0')&&($statod==1)){
		$statodom='In Raffreddamento';
		$color='1759c6';
	}
	
	$alloggio='Alloggio: '.$nome.'<br>';
		
}

//<br><span style="line-height:5px; color:#999; font-size:15px; font-weight:100; margin-top:-15px;">'.$statodom.'</span>

$txt='<span style="font-size:13px;">
			  	Prenotazione: '.$nomepren.'<br>
				Data di Arrivo: '.dataita($time).' '.date('Y',$time).'<br>
				'.$alloggio.'<hr>
				 </span>
				  <div class="card ks-card-header-pic" style="border:solid 3px #e6692c;">
				  <div class="card-content"> 
					<div class="card-content-inner" style="text-align:center; font-size:20px;"> 
					  <p class="color-gray">Temperatura Attuale</p>
					</div>
				  </div><div style="text-align:center; font-size:55px; margin-top:0px;" valign="bottom" class=" color-white no-border" style="color:#'.$color.'">'.$temp.'&deg;</div>
				  
				</div>

				<div class="card ks-facebook-card">
				  <div class="card-header no-border">
					<div >Temperatura Giorno</div>
					<div class="ks-facebook-date" style="margin-left:0px;">09:00 - 17:00</div>
				  </div>
				  <div class="card-content" style="text-align:center; font-size:55px;">'.$tempg.'&deg;</div>
				  <div class="card-footer no-border"><a href="javascript:void(0)" class="link" style="font-size:50px;" onclick="modprofilo('.$IDapp.',0,1,10,1)">-</a><a href="javascript:void(0)" class="link" style="font-size:40px;" onclick="modprofilo('.$IDapp.',0,2,10,1)">+</a></div>
				</div>
				
				
				<div class="card ks-facebook-card">
				  <div class="card-header no-border">
					<div >Temperatura Notte</div>
					<div class="ks-facebook-date" style="margin-left:0px;">17:00 - 09:00</div>
				  </div>
				  <div class="card-content" style="text-align:center; font-size:55px;">'.$tempn.'&deg;</div>
				  <div class="card-footer no-border"><a href="javascript:void(0)" class="link" style="font-size:50px;" onclick="modprofilo('.$IDapp.',1,1,10,1)">-</a><a href="javascript:void(0)" onclick="modprofilo('.$IDapp.',1,2,10,1)" class="link" style="font-size:40px;">+</a></div>
				</div>
				</div>';
				
if(isset($inc)){
	$testo.=$txt;
}else{
	echo $txt; 
}



?>