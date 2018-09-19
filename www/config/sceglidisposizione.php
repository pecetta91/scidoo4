<?php
header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');
include('../../config/funzionilingua.php');

$testo='';

$IDutente=$_SESSION['ID'];
$IDstruttura=$_SESSION['IDstruttura'];

$IDpren=$_GET['IDpren'];

$nome=estrainome($IDpren);

$sx=' <div class="left lh15 navbarleftsize170"> <span class="fs20">'.$nome.'</span></div>';



 	$testo.=' <div class="list-block">
  			<ul>';
	  	
		$query5="SELECT ID,restrizione,tiporest,personale FROM tiporestr WHERE IDstr='$IDstruttura' AND limite='0' AND personale='0'";
		$result5=mysqli_query($link2,$query5);
		while($row5=mysqli_fetch_row($result5)){
				
				
				$testo.='<li>
					  <a href="#" class="item-link  smart-select" data-open-in="picker" pickerHeight="400px" data-back-on-select="true" data-searchbar="false">
						<select  id="restriz'.$row5['0'].'"  alt="'.$row5['0'].'" class="selectdx inputrestr" >
				  '.generanum(0,5).'</select>
						<div class="item-content">
						  <div class="item-inner">
							<div class="item-title titleform">'.$row5['1'].'</div>
							<div class="item-after">0</div>
						  </div>
						</div>
					  </a>
					</li>';
		}
	$testo.='</ul></div><br>';




?>

<div class="popup">
	  	   <div class="navbar">
			<div class="navbar-inner">
				 <?php echo $sx; ?>
				<div class="right" onclick="chiudimodal();">Chiudi</div>
			</div>
		</div>
	
   <div class="content-block " style="margin: 0px;">
	   <div class="bcw">
	<?php echo $testo;?>
	
	   </div>
	</div>
 </div>
