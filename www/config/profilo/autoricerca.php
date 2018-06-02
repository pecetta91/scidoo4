<?php
	header('Access-Control-Allow-Origin: *');
	include('../../../config/connecti.php');
	include('../../../config/funzioni.php');
$testo='';

$tipo=$_GET['tipologia'];
$idcliente=$_GET['idcliente'];
$_SESSION['tipologia']=$tipo;
$_SESSION['idcliente']=$idcliente;

$ricerca='
<form class="searchbar searchbar-init serchauto">
    <div class="searchbar-input">
      <input type="search" class="inputricerca"placeholder="Ricerca" onkeyup="navigationtxt2(9,this.value,'."'contentricerca'".',0)"><a href="#" class="searchbar-clear"></a>
    </div><a href="#" class="searchbar-cancel">Cancel</a>
  </form>';
//effettuo ricerche differenti in solo una pagina 




$testo='<div id="contentricerca">';
?>


<div class="picker-modal " id="popoverord" >
		  <div class="toolbar">
			<div class="toolbar-inner">
				<?php echo $ricerca; ?>
			  <div class="right"><a href="#" class="close-picker">Chiudi</a></div>
			  
			</div>
		  </div>
		  <div class="picker-modal-inner " data-searchbar="true">
		 <div class="page-content bcw" > 
		 	<?php echo $testo;?>
			  </div>
		  </div>
	</div>
</div>