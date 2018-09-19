<?php
header('Access-Control-Allow-Origin: *');
include('../../../config/connecti.php');
include('../../../config/funzioni.php');
include('../../../config/preventivoonline/config/funzioniprev.php');
$inc=1;
$IDutente=$_SESSION['ID'];
$IDstruttura=$_SESSION['IDstruttura'];
$IDrequest=$_SESSION['IDrequest'];

$query="SELECT acconto FROM strutture WHERE ID='$IDstruttura' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$accontop=$row['0'];

$queryp="SELECT SUM(prezzo) FROM prezzip WHERE IDreq='$IDrequest'";
$resultp=mysqli_query($link2,$queryp);
$rowp=mysqli_fetch_row($resultp);
$prezzopern=round($rowp['0'],2);
$queryp="SELECT SUM(prezzo) FROM oraripren2 WHERE IDreq='$IDrequest'";
$resultp=mysqli_query($link2,$queryp);
$rowp=mysqli_fetch_row($resultp);
$totale=round($rowp['0'],2);

$tot=$prezzopern+$totale;

$acconto=round(($tot/100)*$accontop);

$query="UPDATE richieste SET acconto='$acconto' WHERE ID='$IDrequest' LIMIT 1";
$result=mysqli_query($link2,$query);



$testo='

  <form class="searchbar searchbar-init ricercanuovoservform">
    <div class="searchbar-input"  >
      <input type="search" placeholder="Ricerca Ospite" onkeyup="navigationtxt(22,this.value,'."'contencli'".')"  class="ricercainput"><a href="#" class="searchbar-clear"></a>
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
			 