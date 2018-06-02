<?php


	header('Access-Control-Allow-Origin: *');
	include('../../config/connecti.php');
	include('../../config/funzioni.php');
	include('../../config/funzionilingua.php');


$IDstruttura=$_SESSION['IDstruttura'];

if(isset($_GET['dato0'])){
	$portata=$_GET['dato0']; //IDprenextra
}


$IDsottotip=$_SESSION['IDsottotip'];
$time=$_SESSION['timecal'];



	
$numadd='';
$txt='

<div class="list-block list contacts-list">

';

	$q7="SELECT ID,sottotipologia FROM sottotipologie WHERE IDstr='$IDstruttura' AND IDmain IN(15)";
	$r7=mysqli_query($link2,$q7);
	
	if(mysqli_num_rows($r7)>0){
		while($risult7=mysqli_fetch_row($r7)){
			$IDs=$risult7['0'];
			$q8="SELECT servizio,ID FROM servizi WHERE IDsottotip='$IDs' AND IDstruttura='$IDstruttura'";
			$r8=mysqli_query($link2,$q8);
			if(mysqli_num_rows($r8)>0){
			$txt.=' <div class="list-group">
      				<ul>
        			<li class="list-group-title">'.$risult7['1'].'</li>';
				while($ris8=mysqli_fetch_row($r8)){
					
					$txt.='<li onclick="modportate('."'".$time."_".$portata."_".$IDsottotip."'".','.$ris8['1'].',37,10,1);">
					  <div class="item-content">
						<div class="item-inner">
						  <div class="item-title">'.$ris8['0'].'</div>
						</div>
					  </div>
					</li>';
					
				}
			$txt.='</ul></div>';
			}
		}
	}
	$txt.='</div>';


	echo '

	<div class="picker-modal " id="popoverord" >
		  <div class="toolbar">
			<div class="toolbar-inner">
			  <div class="left"></div>
			  <div class="right"><a href="#" class="close-picker">Close</a></div>
			</div>
		  </div>
		  <div class="picker-modal-inner ">
		 <div class="page-content" style="background-color: white"> 
		  '.$txt.'
		  </div>
	</div>
	</div>

	';
	
	
	





?>



