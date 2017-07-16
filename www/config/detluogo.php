<?php

header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');
include('../../config/funzionilingua.php');



$ID=strip_tags($_GET['ID']);
$query="SELECT ID,nome,dove,descriz,latitude,longitude,telefono,email,website FROM luoghieventi WHERE ID='$ID' LIMIT 1"; 

$result=mysqli_query($link2,$query);

$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDapp=$row['0'];

$testo='


<div class="navbar" style="height:50px;  background:#b92282;">
               <div class="navbar-inner">
                  
                  <div class="center" style="font-size:14px; text-transform:uppercase; font-weight:600;">'.$row['1'].'</div>
                  <div class="right" >
						<a href="#" class="close-popup"><i class="icon f7-icons" style="color:#fff;  font-size:30px; margin-right:18px;">close</i></a>
					</div>
               </div>
            </div>



		
		
		
		';
		
		$testo.='<div style="width:90%; text-align:left;  padding-left:15px;"><br>
		
		<span style="font-size:14px; color:#555;">'.$row['3'].'</span><br>
		<br><a href="#" onclick="location.href='."' https://maps.google.com/?q=".$row['4'].",".$row['5']."  '".'">Raggiungi con Google Maps</a><br><br>
	<div style="font-size:16px; line-height:14px; font-weight:600;color:#2c529e;">Contattaci</div><br>
	
	
	<table>';
	
	if(strlen($row['6'])>0){
		$testo.='
	<tr onclick="location.href='."'tel:".$row['6']."'".'"><td valign="top" ><i class="material-icons" style="font-size:24px;color:#2c529e;">phone</i> </td><td> '.wordwrap($row['6'],20,'<br>').'</td></tr>';
	}
	if(strlen($row['7'])>0){
		$testo.='
	<tr onclick="location.href='."'mailto:".$row['7']."'".'"><td valign="top"><i class="material-icons" style="font-size:24px;color:#2c529e;">email</i> </td><td> '.wordwrap($row['7'],20,'<br>').'</td></tr>';
	}
	if(strlen($row['8'])>0){
		$web=$row['8'];
		if(strpos($row['8'],"http://")){
			$web='http://'.$web;
		}
		$testo.='
	<tr onclick="location.href='."'".$web."'".'"><td valign="top"><i class="material-icons" style="font-size:24px;color:#2c529e;">web</i> </td><td> '.wordwrap($row['8'],20,'<br>').'</td></tr>';
	}
	$testo.='
	<tr><td valign="top"><i class="material-icons" style="font-size:24px;color:#2c529e;">place</i> </td><td> '.$row['2'].'</td><td>
	
	
	</table><br>
	
	
	<br></div>';
		

	
	$testo.='</div>
	';
	
	echo $testo;
?>