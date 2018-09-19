<?php
	header('Access-Control-Allow-Origin: *');
	include('../../../config/connecti.php');
	include('../../../config/funzioni.php');
$testo='';

$tipo=$_GET['tipologia'];
$idcliente=$_GET['idcliente'];
$_SESSION['tipologia']=$tipo;
$_SESSION['idcliente']=$idcliente;
$placeholder='';
$help='<div><ul>
			<li>Se sei Italiano inserire il comune</li>
			<li>Se non sei Italiano inserisci lo stato</li>
	   </ul></div>';
switch($tipo){
	case 1:
		$placeholder='cittadinanza';
		$help='';
	break;	
	case 2:
		$placeholder='luogo di nascita';
	break;	
	case 3:
		$placeholder='residenza';
	break;	
	case 4:
		$placeholder='luogo rilascio doc';
	break;	
	case 5:
		$placeholder='documento';
		$help='';
	break;	
}


$ricerca='
<form class="searchbar searchbar-init">
    <div class="searchbar-input">
      <input type="search" class="inputricerca inputricerca ricercainputpicker" style="margin-top: -10px;
    height: 40px;" placeholder="Ricerca '.$placeholder.'" onkeyup="navigationtxt2(9,this.value,'."'contentricerca'".',0)"><a href="#" class="searchbar-clear"></a>
    </div><a href="#" class="searchbar-cancel">Cancel</a>
  </form>';
//effettuo ricerche differenti in solo una pagina 




$testo='<div id="contentricerca">'.$help;
?>


<div class="popup">
	   
	   <div class="navbar">
			<div class="navbar-inner">
				<div class="left" style="width:80%;"><?php echo $ricerca; ?></div>
				<div class="right" onclick="chiudimodal();">Chiudi</div>
			</div>
		</div>
	
   <div class="content-block " style="margin: 0px;">
	
	 
	  
	   
	   <div class="bcw">
	<?php echo $testo;?>
	
	   </div>
	</div>
 </div>

<!--
<div class="picker-modal " id="popoverord" >
		  <div class="toolbar">
			<div class="toolbar-inner">
			  <div class="right"><a href="#" class="close-picker">Chiudi</a></div>
			  
			</div>
		  </div>
		  <div class="picker-modal-inner " data-searchbar="true">
		 <div class="page-content bcw" > 
			  </div>
		  </div>
	</div>
</div>-->