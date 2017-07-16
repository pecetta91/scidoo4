<?php

if(!isset($inc)){
	header('Access-Control-Allow-Origin: *');
	include('../../config/connecti.php');
	include('../../config/funzioni.php');
	include('../../config/funzionilingua.php');
	
	$IDutente=intval($_SESSION['ID']);
	$IDstruttura=intval($_SESSION['IDstruttura']);
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
}




$gg=7;
if(isset($_GET['dato1'])){
	if(is_numeric($_GET['dato1'])){
		$gg=$_GET['dato1'];
	}
}
$data=date('Y-m-d',$time);


$testo='
<input type="hidden" id="timearrivi" value="'.$time.'">
<input type="hidden" id="ggpulizie" value="'.$gg.'">
';

list($yy, $mm, $dd) = explode("-", $data);
				$time0=mktime(0, 0, 0, $mm, $dd, $yy);
				$timef=$time0+86400;
			$statoarr=array('Pronto','Occupato','Da Preparare');	
			$statocol=array('1dbb68','bb2c1d','d8bf18');		
			
			
//elenco arrivi
			
/*
			$testo.='
		<div class="content-block-title"   style="margin-top:-18px; text-align:center; background:#e57511;color:#fff; line-height:30px; height:30px; border-radius:5px; padding:0px; overflow:hidden; position:relative; ">
		<input type="text" id="dataarrivi" style="position:absolute; top:0px; left:0px; opacity:0; width:100%; height:30px;">
		<table width="100%;" style="margin-top:-2px; margin-left:-2px;"><tr><td width="50%;" style="background:#d13b23;">'.dataita4($time).'</td><td>'.dataita4(($time+86400*$gg)).'</td></tr></table></div>
		
		';
	*/	
		
		for($i=0;$i<$gg;$i++){
			
			$timeini=$time0+$i*86400;
			$timefin=$timeini+86400;
				
			$query="SELECT ID,IDv,app,time FROM prenotazioni WHERE time>='$timeini' AND time<'$timefin' AND IDstruttura='$IDstruttura' AND stato>='0'"; 
			$result=mysqli_query($link2,$query);
	
			
			if(mysqli_num_rows($result)>0){
				$testo.='
					<div class="content-block-title titleb">'.dataita($timeini).'</div>
					<div class="list-block accordion-list"  style="margin-bottom:10px;">
					<ul>
				
				
			';
				
				while($row=mysqli_fetch_row($result)){
					$id=$row['1'];
					$IDapp=$row['2'];
				
					$query2="SELECT nome,attivo,stato FROM appartamenti WHERE ID='$IDapp' AND IDstruttura='$IDstruttura'"; 
					$result2=mysqli_query($link2,$query2);
					$row2=mysqli_fetch_row($result2);
					$nome=$row2['0'];
					$attivo=$row2['1'];
					
					$statotxt='';
					
					if($attivo==1){
						$statotxt='<div style="color:#'.$statocol[$row2['2']].';font-weight:400;">'.$statoarr[$row2['2']].'</div>';
					}
					
					$testo.='
						
						<li style="border-left:solid 3px #'.$statocol[$row2['2']].';"><a href="#" onclick="navigation(3,'."'".$id."'".')" class="item-content item-link" >
							<div class="item-inner">
							  <div class="item-title">'.estrainome($id).'<br>
							  
							  <span style="font-size:11px; color:#777;">'.$nome.'</span></div>
							  <div class="item-after" style="font-size:13px;line-height:12px;text-align:right;font-weight:100;"><div style="border-right:solid 1px #ccc;padding-right:5px;margin-right:5px;">'.date('H:i',$row['3']).'</div>'.$statotxt.'</div>
							</div></a>
						 
						</li>
						';
					
				}
				$testo.='</ul></div>';
			
			}
			
			
		}
					
					
					
					$testo.='
					<br><br><br><br><br>
		';


			echo $testo;	

?>