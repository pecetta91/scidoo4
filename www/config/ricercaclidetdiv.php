<?php


	header('Access-Control-Allow-Origin: *');
	include('../../config/connecti.php');
	include('../../config/funzioni.php');
	include('../../config/funzionilingua.php');


$IDstruttura=$_SESSION['IDstruttura'];
$IDinfop=$_POST['IDinfop']; //time


//da time e sottotip mi ricavo idprenextra dalla giornata di oggi 
//AND tavoli.stato=2 AND p.ID=tavoli.IDprenextra


	/*<form  data-search-list=".list-block-search" data-search-in=".item-title" class="searchbar searchbar-init" style="margin-top:35px;  width:100%; margin:auto;">
    <div class="searchbar-input"  >
     <a href="#" class="searchbar-clear"></a>
    </div><a href="#" class="searchbar-cancel">Cancel</a>
  </form>*/

$testo='

  <div id="contencli">
  	
  </div>
';

?>
	<div class="picker-modal" id="popoverord">
		  <div class="toolbar">
			<div class="toolbar-inner">
			  <div class="left"> <input type="text" placeholder="Ricerca Cliente" onkeyup="<?php echo 'ricercaclidet(30,this.value,'.$IDinfop.','."'contencli'".')'; ?>" class="inputricerca"></div>
			  <div class="right" style="padding-right:10px;"><a href="#" class="close-picker">
				  <i class="f7-icons">check</i>
				  
				  </a></div>
			</div>
		  </div>
		  <div class="picker-modal-inner ">
		 <div class="page-content"> 
		 <?php
		 echo $testo;
		 ?>
		  </div>
	</div>
	</div>
