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



/*
$gg=7;
if(isset($_GET['dato1'])){
	if(is_numeric($_GET['dato1'])){
		$gg=$_GET['dato1'];
	}
}*/
$data=date('Y-m-d',$time);


$testo='
<input type="hidden" id="timearrivi" value="'.$time.'">

<a href="#"  class="button button-round button-fill " id="prova" style="width:200px; margin-left:10px;"><i class="f7-icons" style="font-size:13px;">today</i> &nbsp;&nbsp;'.dataita($time).'</a>	


';

			list($yy, $mm, $dd) = explode("-", $data);
			$time0=mktime(0, 0, 0, $mm, $dd, $yy);
			$timef=$time0+86400;
			$statoarr=array('Pronto','Occupato','Da Preparare');	
			$statocol=array('1dbb68','bb2c1d','d8bf18');		
			
			

		
			
			$timeini=$time0+$i*86400;
			$timefin=$timeini+86400;
				
			$testo.='<div class="content-block-title titleb">Arrivi</div>
				<div class="row rowlist" style="background:#f1f1f1;">
						<div class="col-15">Arr.</div>
						<div class="col-45 coltitle">Prenotazione</div>
						<div class="col-20">Pulizia</div>
						<div class="col-20 centercol">Arrivo</div>
						</div>
				';


			$query="SELECT ID,IDv,app,time,stato FROM prenotazioni WHERE time>='$timeini' AND time<'$timefin' AND IDstruttura='$IDstruttura' AND stato>='0' ORDER BY time"; 
			$result=mysqli_query($link2,$query);
			if(mysqli_num_rows($result)>0){
				while($row=mysqli_fetch_row($result)){
					$id=$row['1'];
					$IDapp=$row['2'];
					$statoarrtxt='<i class="f7-icons icon" style="color:#cc4d2a">close</i>';
					if(($row['4']==4)||($row['4']==3)){
						$statoarrtxt='<i class="f7-icons icon" style="color:#34a76d">check</i>';
					}
					
					$query2="SELECT nome,attivo,stato FROM appartamenti WHERE ID='$IDapp' AND IDstruttura='$IDstruttura'"; 
					$result2=mysqli_query($link2,$query2);
					$row2=mysqli_fetch_row($result2);
					$nomeapp=$row2['0'];
					$attivo=$row2['1'];
					
					$statotxt='';
					
					if($attivo==1){
						$statotxt='<div style="color:#'.$statocol[$row2['2']].';font-weight:400;">'.$statoarr[$row2['2']].'</div>';
					}
					
					$testo.='
						
						<div class="row rowlist" onclick="navigation(3,'."'".$id."'".')">
						<div class="col-15">'.date('H:i',$row['3']).'</div>
						<div class="col-45 coltitle">'.estrainome($id).'<br>
						<span>'.$nomeapp.'</span></div>
						<div class="col-20">'.$statotxt.'</div>
						<div class="col-20 centercol">'.$statoarrtxt.'</div>
						
						</div>';
					
				}
				
			
			}else{
				//onclick="navigation(3,'."'".$id."'".')"
				$testo.='
				<div class="row rowlist" >
					<div class="col-100 h40">Non ci sono arrivi oggi</div>
				</div>
				';
			}
			$testo.='<br><br>';





			$testo.='<div class="content-block-title titleb">Partenze</div>
				<div class="row rowlist" style="background:#f1f1f1;">
						<div class="col-15">Part.</div>
						<div class="col-45 coltitle">Prenotazione</div>
						<div class="col-20">Pulizia</div>
						<div class="col-20 centercol">Partito</div>
						</div>
				';


			$query="SELECT ID,IDv,app,checkout,stato FROM prenotazioni WHERE FROM_UNIXTIME(checkout,'%Y-%m-%d')='$data' AND FROM_UNIXTIME(time,'%Y-%m-%d')!='$data'AND IDstruttura='$IDstruttura' AND stato>='0' ORDER BY checkout"; 
			$result=mysqli_query($link2,$query);
			if(mysqli_num_rows($result)>0){
				while($row=mysqli_fetch_row($result)){
					$id=$row['1'];
					$IDapp=$row['2'];
					$statoarrtxt='<i class="f7-icons icon" style="color:#cc4d2a">close</i>';
					if(($row['4']==4)){
						$statoarrtxt='<i class="f7-icons icon" style="color:#34a76d">check</i>';
					}
					
					$query2="SELECT nome,attivo,stato FROM appartamenti WHERE ID='$IDapp' AND IDstruttura='$IDstruttura'"; 
					$result2=mysqli_query($link2,$query2);
					$row2=mysqli_fetch_row($result2);
					$nomeapp=$row2['0'];
					$attivo=$row2['1'];
					
					$statotxt='';
					
					if($attivo==1){
						$statotxt='<span style="color:#'.$statocol[$row2['2']].';font-weight:400;">'.$statoarr[$row2['2']].'</span>';
					}
					
					$testo.='
						
						<div class="row rowlist" onclick="navigation(3,'."'".$id."'".')">
						<div class="col-15">'.date('H:i',$row['3']).'</div>
						<div class="col-45 coltitle">'.estrainome($id).'<br>
						<span>'.$nomeapp.'</span></div>
						<div class="col-20 coltitle">'.$statotxt.'</div>
						<div class="col-20 centercol">'.$statoarrtxt.'</div>
						
						</div>';
					
					
					
				}
				
			}else{
				$testo.='
				<div class="row rowlist">
					<div class="col-100 h40">Non ci sono partenze oggi</div>
				</div>
				';
			}
			$testo.='<br><br>';


			$testo.='<div class="content-block-title titleb">Permanenze</div>
				<div class="row rowlist" style="background:#f1f1f1;">
						<div class="col-30 ">Arrivato il</div>
						<div class="col-40 coltitle">Prenotazione</div>
						<div class="col-30 rightcol">Parte il</div>
						</div>
				';


			$query="SELECT ID,IDv,app,checkout,stato,time FROM prenotazioni WHERE FROM_UNIXTIME(checkout,'%Y-%m-%d')!='$data' AND FROM_UNIXTIME(time,'%Y-%m-%d')!='$data' AND '$data' BETWEEN  FROM_UNIXTIME(time,'%Y-%m-%d') AND  FROM_UNIXTIME(checkout,'%Y-%m-%d') AND IDstruttura='$IDstruttura' AND stato>='0' ORDER BY time"; 
			$result=mysqli_query($link2,$query);
			if(mysqli_num_rows($result)>0){
				while($row=mysqli_fetch_row($result)){
					$id=$row['1'];
					$IDapp=$row['2'];
					$statoarrtxt='<i class="f7-icons icon" style="color:#cc4d2a">close</i>';
					if(($row['4']==4)){
						$statoarrtxt='<i class="f7-icons icon" style="color:#34a76d">check</i>';
					}
					
					$query2="SELECT nome,attivo,stato FROM appartamenti WHERE ID='$IDapp' AND IDstruttura='$IDstruttura'"; 
					$result2=mysqli_query($link2,$query2);
					$row2=mysqli_fetch_row($result2);
					$nomeapp=$row2['0'];
					$attivo=$row2['1'];
					
				
					
					$testo.='
						
						<div class="row rowlist" onclick="navigation(3,'."'".$id."'".')">
						<div class="col-30  f12">'.dataita8($row['5']).'</div>
						<div class="col-40 coltitle">'.estrainome($id).'<br>
						<span>'.$nomeapp.'</span></div>
						<div class="col-30 rightcol f12">'.dataita8($row['3']).'</div>
						
						</div>';
					
					
					
				}
				
			}else{
				$testo.='
				<div class="row rowlist">
					<div class="col-100 h40">Non ci sono partenze oggi</div>
				</div>
				';
			}
			$testo.='<br><br>';


			




			echo $testo;	

?>