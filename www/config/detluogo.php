<?php

header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');
include('../../config/funzionilingua.php');



$ID=strip_tags($_GET['dato0']);
$query="SELECT ID,nome,dove,descriz,latitude,longitude,telefono,email,website FROM luoghieventi WHERE ID='$ID' LIMIT 1"; 

$result=mysqli_query($link2,$query);

$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDapp=$row['0'];

$testo='

<div data-page="detluogo" class="page" > 
            <!-- Top Navbar--> 
             
	
			 
			 	<div class="navbar" id="navcal" >
				<div class="navbar-inner">
					<div class="left">
					
					  <a href="#" class="link icon-only back"   >
						<i class="material-icons" style="font-size:40px;">chevron_left</i>
					</a>
					
					</div>
					<div class="center titolonav">'.$row['1'].'</div>
					
				</div>
			</div>
			
			
			<div class="toolbar tabbar tabbar-labels">
    <div class="toolbar-inner">

        <a href="#tab1" class="tab-link active">
            <i class="icon material-icons" style="font-size:27px;">info</i>
            <span class="tabbar-label">Descrizione</span>
        </a>
        <a href="#tab2" class="tab-link">
            <i class="icon material-icons" style="font-size:27px;">location_on</i>
            <span class="tabbar-label">Mappa</span>
        </a>
        <a href="#tab3" class="tab-link">
            <i class="icon material-icons" style="font-size:27px; ">phone</i>
            <span class="tabbar-label">Contatti</span>
        </a>
        
		 <a href="#tab4" class="tab-link">
            <i class="icon material-icons" style="font-size:27px; ">photo_camera</i>
            <span class="tabbar-label">Foto</span>
        </a>
		<a href="#" class="tab-link">

             <i class="icon material-icons" style="font-size:27px;">thumb_up</i>
            <span class="tabbar-label">Mi piace</span>
        </a>
	</div>
</div>
			
		 <div class="page-content">
			<div class="tabs tabluoghi">
			  <div class="tab active" id="tab1" style="padding:20px;">
<br>
				<span style="font-size:16px; color:#555;">'.$row['3'].'</span><br><br><br><br><br><br>

			  </div>
			  <div class="tab" id="tab2"  style="padding:20px;">

			  </div>
			  <div class="tab" id="tab3" style="padding:20px;">

	<a href="#" onclick="location.href='."' https://maps.google.com/?q=".$row['4'].",".$row['5']."  '".'">Raggiungi con Google Maps</a><br><br>
	<div style="font-size:16px; line-height:14px; font-weight:600;color:#2c529e;">CONTATTI</div><br>
					
					
					<table >';
	
	if(strlen($row['6'])>0){
		$testo.='
	<tr onclick="location.href='."'tel:".$row['6']."'".'"><td valign="top" ><i class="material-icons" style="font-size:24px;color:#2c529e;">phone</i> </td><td style="text-align:left;"> '.wordwrap($row['6'],20,'<br>').'</td></tr>';
	}
	if(strlen($row['7'])>0){
		$testo.='
	<tr onclick="location.href='."'mailto:".$row['7']."'".'"><td valign="top"><i class="material-icons" style="font-size:24px;color:#2c529e;">email</i> </td><td style="text-align:left;"> '.wordwrap($row['7'],20,'<br>').'</td></tr>';
	}
	if(strlen($row['8'])>0){
		$web=$row['8'];
		if(strpos($row['8'],"http://")){
			$web='http://'.$web;
		}
		$testo.='
	<tr onclick="location.href='."'".$web."'".'"><td valign="top"><i class="material-icons" style="font-size:24px;color:#2c529e;">web</i> </td><td style="text-align:left;"> '.wordwrap($row['8'],20,'<br>').'</td></tr>';
	}
	$testo.='
	<tr><td valign="top"><i class="material-icons" style="font-size:24px;color:#2c529e;">place</i> </td><td style="text-align:left;"> '.$row['2'].'</td><td>
	
	
	</table><br>
					
					


			  </div>
			  
			  
			   <div class="tab" id="tab4" style="padding:20px;">
			   4
			   </div>
			   
			   <div class="tab" id="tab5" style="padding:20px;">
			   5
			   </div>
			   
			   
			</div>  



		
		
		
		';
		
		$testo.='<div style="width:90%; text-align:left;  padding-left:15px;">
	
	
	
	
	
	<br></div>';
		

	
	$testo.='</div></div></div>
	
	';
	
	echo $testo;
?>