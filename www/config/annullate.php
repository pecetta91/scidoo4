<?php

header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');
include('../../config/funzionilingua.php');

$IDutente=$_SESSION['ID'];
$IDstruttura=$_SESSION['IDstruttura'];


$time=strip_tags($_GET['time']);
$data=date('Y-m-d',$time);

$testo='
<div class="navbar" >
               <div class="navbar-inner">
                  <div class="center titolonav" style="width:80%">Prenotazioni Annullate</div>
                  <div class="right" onclick="myApp.closeModal()" style="text-align:right;">
						<i class="icon f7-icons">close</i>			  
				  </div>
               </div>
            </div>
			
		
		 <div class="list-block">
      <ul style="padding:0px;">
		
		';
	
	
	
	$query2="SELECT ID,stato,IDv,time,gg FROM prenotazioni WHERE IDstruttura='$IDstruttura' AND app='0' AND stato='-1' AND FROM_UNIXTIME(time,'%Y-%m-%d')= FROM_UNIXTIME('$time','%Y-%m-%d')";
	
	$result2=mysqli_query($link2,$query2);
	while($row=mysqli_fetch_row($result2)){
		
		$stato='annullata';
		
		/*switch($stato){
			case 0:$stato='incerto';
			break;
			case 1:$stato='base';
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
		}*/
		$nome=estrainome($row['2']);
		
		
		$query3="SELECT ID FROM infopren WHERE IDpren='".$row['2']."' AND pers='1'";
		$result3=mysqli_query($link2,$query3);
		$num=mysqli_num_rows($result3);
		
		
			
		$testo.= '
		
		
			<li>
			  <a href="#"  onclick="navigation(3,'."'".$row['2']."'".');myApp.closeModal();" class="item-link item-content">
				<div class="item-media"><div class="'.$stato.' notifdiv"></div></div>
				<div class="item-inner">
				  <div class="item-title" style="line-height:13px;">
					 	<span style="font-size:14px; ">'.$nome.'</span><br>
					  <span style="font-size:11px;">N.'.$row['4'].' Notti - '.$num.' Persone</span>
					</div>
				  <div class="item-after">
				  	<div class="item-after notif" ><b>'.date('H:i',$row['3']).'</b></div>
				  
				  </div>
				</div>
			  </a>
			</li>
		
		
			
		
		
		
		
		'; 
	}		

	
	$testo.='</div>
	</div>';
	
	echo $testo;
?>