<?php 
if(!isset($inc)){
	include('../../../config/connecti.php');
	include('../../../config/funzioni.php');
	header('Access-Control-Allow-Origin: *');
	$testo='<br>';
}

$IDpren=$_SESSION['IDstrpren'];
$route=$_SESSION['route'];

$query="SELECT app,gg,time,tempg,tempn,checkout,IDstruttura FROM prenotazioni WHERE IDv='$IDpren' LIMIT 1";

$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDapp=$row['0'];
$gg=$row['1'];
$time=$row['2'];
$tempg=round($row['3']);
$tempn=round($row['4']);
$checkout=$row['5'];
$IDstr=$row['6'];

$nomepren=estrainome($IDpren);

$tipo=0;
if(isset($_GET['dato0'])){
	$tipo=$_GET['dato0'];
}

//$_SESSION['height']=500;

$height=$_SESSION['height'];

$query="SELECT latitude,longitude FROM strutture WHERE ID='$IDstr'";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$lat=$row['0'];
$lon=$row['1'];


$testo.='

<div data-page="luoghi" class="page" > 
            <!-- Top Navbar--> 
             
	
			 
			 	<div class="navbar" id="navcal" >
				<div class="navbar-inner">
					<div class="left">
					
					  <a href="#" class="link icon-only back"   >
						<i class="material-icons" style="font-size:40px;">chevron_left</i>
					</a>
					
					</div>
					<div class="center titolonav">Luoghi da Visitare</div>
					<div class="right"></div>
				</div>
			</div>
		 <div class="page-content">
			
				
				
              <div class="content-block" id="luoghidiv"> 



<input type="hidden" id="latstr" value="'.$lat.'">
<input type="hidden" id="lonstr" value="'.$lon.'">



<div class="list-block media-list">
  <ul >

';

$qadd="";

if($lat>0){
	$qadd="AND TRUNCATE ( 6363 * sqrt( POW( RADIANS('".$lat."') - RADIANS(latitude) , 2 ) + POW( RADIANS('".$lon."') - RADIANS(longitude) , 2 ) ) , 3 ) < 30";
}


$IDcliente=$_SESSION['IDcliente'];
$tipocli=$_SESSION['tipocli'];

$query="SELECT ID,nome,dove,descriz,latitude,longitude,TRUNCATE ( 6363 * sqrt( POW( RADIANS('".$lat."') - RADIANS(latitude) , 2 ) + POW( RADIANS('".$lon."') - RADIANS(longitude) , 2 ) ) , 3 ) as distance FROM luoghieventi WHERE  tipo='$tipo'  AND TRUNCATE ( 6363 * sqrt( POW( RADIANS('".$lat."') - RADIANS(latitude) , 2 ) + POW( RADIANS('".$lon."') - RADIANS(longitude) , 2 ) ) , 3 )<30 ORDER BY distance  ";
$result=mysqli_query($link2,$query);
if(mysqli_num_rows($result)>0){
	while($row=mysqli_fetch_row($result)){
		
		
		$IDpoi=$row['0'];
		$txtmipiace='';
		$query2="SELECT ID FROM mipiace WHERE  IDobj='".$row['0']."' AND tipoobj='2'";
		$result2=mysqli_query($link2,$query2);
		$mipiace=mysqli_num_rows($result2);
		if($mipiace>1){
			$txtmipiace=$mipiace;
		}

		$classmi='';
		$query2="SELECT ID FROM mipiace WHERE IDcliente='$IDcliente' AND tipocli='$tipocli' AND IDobj='".$row['0']."' AND tipoobj='2' LIMIT 1";
		$result2=mysqli_query($link2,$query2);
		if(mysqli_num_rows($result2)>0){
			$classmi='mipiace';
		}
		//<div class="item-media"><img src="..." width="80"></div>
		
		$consigliato='';
		
		$classmi='';
		$query2="SELECT ID FROM luogoconsigliato WHERE ID='$IDpoi' AND IDstr='$IDstr' LIMIT 1";
		$result2=mysqli_query($link2,$query2);
		if(mysqli_num_rows($result2)>0){
			$consigliato='<div class="item-subtitle" style="font-size:12px; color:#d9b136;"><b>Consigliato</b></div>';
		}
		
		
		$testo.='
			<li>
		  <a href="#" class="item-link item-content" onclick="navigation(33,'.$row['0'].',0,0)">
			<div class="item-inner">
			  <div class="item-title-row">
				<div class="item-title">'.$row['1'].'</div>
				<div class="item-after">'.round($row['6'],1).'km</div>
			  </div>
			 	'.$consigliato.'
			  <div class="item-text" style="font-size:12px;color:#777;">'.$row['2'].'</div>
			</div>
		  </a>
		</li>
		
		
	
		
		';
		
		
		/*
		  <table width="100%" class="tabluoghi"><tr>
			  <td style="border-right:solid 1px #ccc;"><i class="material-icons" onclick="infopoi('.$row['0'].')">info_outline</i></td>
			  <td style="border-right:solid 1px #ccc;" onclick="setlocation('.$row['4'].','.$row['5'].','."'".$row['1']."'".')"><i class="material-icons">place3</i></td>
			  <td onclick="mipiace('.$row['0'].',2,1)"><span id="numl'.$row['0'].'">'.$txtmipiace.'</span><i class="f7-icons '.$classmi.'" id="icon'.$row['0'].'" style="font-size:16px; line-height:25px">heart_fill</i></td>
			  </tr></table>*/
		
		
		
		
		
	}
}




$testo.='

</ul></div>';

//cancellation policy




//annulla prenotazione









if(!isset($inc)){
echo $testo;
}




?>