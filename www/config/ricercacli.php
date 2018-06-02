<?php


header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');


$IDstruttura=$_SESSION['IDstruttura'];



//da time e sottotip mi ricavo idprenextra dalla giornata di oggi 
//AND tavoli.stato=2 AND p.ID=tavoli.IDprenextra

$form='
<form class="searchbar searchbar-init" style="background:none;">
    <div class="searchbar-input" style="width:90%" >
      <input type="search" class="inputricerca" placeholder="Ricerca Ospite" onkeyup="navigationtxt(36,this.value,'."'contencli'".')"><a href="#" class="searchbar-clear"></a>
    </div><a href="#" class="searchbar-cancel">Cancel</a>
  </form>';
  
  $txt='<div id="contencli">';


?>
	<div class="picker-modal" id="popoverord" >
		  <div class="toolbar">
			<div class="toolbar-inner">
			  	<?php
			 echo $form;
			 ?>
			  <div class="right"><a href="#" class="close-picker">Chiudi</a></div>
			</div>
		  </div>
		  <div class="picker-modal-inner ">
		 <div class="page-content"  style="background-color: white"> 
		 
		 <?php
			 echo $txt;
			 ?>
		  </div>
	</div>
	</div>
