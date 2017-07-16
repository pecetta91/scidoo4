<?php
header('Access-Control-Allow-Origin: *');
include('../../../config/connecti.php');
include('../../../config/funzioni.php');
include('../../../config/preventivoonline/config/funzioniprev.php');
$inc=1;
$IDutente=$_SESSION['ID'];
$IDstruttura=$_SESSION['IDstruttura'];
$IDrequest=$_SESSION['IDrequest'];

$testo='

  <form  data-search-list=".list-block-search" data-search-in=".item-title" class="searchbar searchbar-init">
    <div class="searchbar-input"  >
      <input type="search" placeholder="Ricerca Cliente" onkeyup="navigationtxt(22,this.value,'."'contencli'".')"><a href="#" class="searchbar-clear"></a>
    </div><a href="#" class="searchbar-cancel">Cancel</a>
  </form>
  
  <div id="contencli">
';

//echo $testo;

include('step4cerca.php');

$testo.='



</div>
</div>

';


 
echo $testo;
			 
?>			 
			 