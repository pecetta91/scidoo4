<?php

header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');

$IDutente=$_SESSION['ID'];
$IDstruttura=$_SESSION['IDstruttura'];


$time=strip_tags($_GET['time']);
$data=date('Y-m-d',$time);
$query="SELECT ID FROM appartamenti WHERE IDstruttura='$IDstruttura' AND attivo='2' LIMIT 1"; 
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDapp=$row['0'];

$testo='
<div class="navbar" >
               <div class="navbar-inner">
                  <div class="center titolonav">Prenotazioni Senza Soggiorno</div>
                  <div class="right" onclick="myApp.closeModal()">
						<i class="icon f7-icons">close</i>			  
				  </div>
               </div>
            </div>
<div class="content-block" >
		

	  	
	  
	  
		
		';
		


	$query2="SELECT ID,stato,IDv,time FROM prenotazioni WHERE IDstruttura='$IDstruttura' AND app='$IDapp' AND FROM_UNIXTIME(time,'%Y-%m-%d')= FROM_UNIXTIME('$time','%Y-%m-%d')";
	$result2=mysqli_query($link2,$query2);
	while($row=mysqli_fetch_row($result2)){
		$stato=$row['1'];
		switch($stato){
			case 0:
				$stato='incerto';
			break;
			case 1:
				$stato='base';
			break;
			case 2:
				$stato='acconto';
			break;
			case 3:
				$stato='arrivato';
			break;
			case 4:
				$stato='saldo';
			break;
			case 5:
				$stato='credit';
			break;
			case 6:
				$stato='manuale';
			break;
		}
		

		
		
		
		$nome=estrainome($row['2']);
		
		
		$query3="SELECT ID FROM infopren WHERE IDpren='".$row['2']."' AND pers='1'";
		$result3=mysqli_query($link2,$query3);
		$num=mysqli_num_rows($result3);
		
		
		
		$testo.='
				<div class="row rowlist no-gutter"  onclick="navigation(3,'."'".$row['2']."'".');myApp.closeModal();">
				<div class="col-15"><div class="'.$stato.' notifdiv"></div></div>
				<div class="col-65 coltitle">'.$nome.'
				<br><span>'.$num.' '.txtpersone($num).'</span>
				</div>
				<div class="col-20 rightcol">'.date('H:i',$row['3']).'</div>
				
				</div>';
		
		/*
		$testo.= '
		
			
		
		
			<li>
			  <a href="#"  onclick="navigation(3,'."'".$row['2']."'".');myApp.closeModal();" class="item-link item-content">
				<div class="item-media"><div class="'.$stato.' notifdiv"></div></div>
				<div class="item-inner">
				  <div class="item-title" >
					 	'.$nome.'<br>
					  <span style="font-size:12px; color:#777;">'.$num.' Persone</span>
					</div>
				  <div class="item-after">'.date('H:i',$row['3']).'
				  </div>
				</div>
			  </a>
			</li>
		

		
		'; */
	}		

	

	
	echo $testo;
?>