<?php
header('Access-Control-Allow-Origin: *');
include('../../../config/connecti.php');
include('../../../config/funzioni.php');
include('../../../config/preventivoonline/config/funzioniprev.php');

$IDutente=$_SESSION['ID'];
$IDstruttura=$_SESSION['IDstruttura'];
$IDrequest=$_SESSION['IDrequest'];


$query="SELECT IDstr,notti,timearr,stato,checkout,note,acconto FROM richieste WHERE ID='$IDrequest' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDstruttura=$row['0'];
$gg=$row['1'];
$ggric=$gg;
$timearr=$row['2'];
$datagg=date('Y-m-d',$timearr);
$stato=$row['3'];
$checkout=$row['4'];
$note=$row['5'];
$acconto=$row['6'];

$ggsett=date('N',$timearr);

$query="SELECT acconto FROM strutture WHERE ID='$IDstruttura' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$accontop=$row['0'];


$queryp="SELECT SUM(prezzo) FROM prezzip WHERE IDreq='$IDrequest'";
$resultp=mysqli_query($link2,$queryp);
$rowp=mysqli_fetch_row($resultp);
$prezzopern=round($rowp['0'],2);
$queryp="SELECT SUM(prezzo) FROM oraripren WHERE IDreq='$IDrequest'";
$resultp=mysqli_query($link2,$queryp);
$rowp=mysqli_fetch_row($resultp);
$totale=round($rowp['0'],2);

$tot=$prezzopern+$totale;

/*$acconto=round(($tot/100)*$accontop);

$query="UPDATE richieste SET acconto='$acconto' WHERE ID='$IDrequest' LIMIT 1";
$result=mysqli_query($link2,$query);*/


// <input type="text" id="totalevacanza" class="ptb" onchange="modprenot(0,this.value,94,10,8)" value="'.$tot.'" placeholder="Totale Vacanza">
//<input type="text" class="ptb" onchange="modprenot(0,this.value,112,10,0)" value="'.$acconto.'" placeholder="Acconto Richiesto">
$testo='
<input type="hidden" id="datacalpren" value="'.$timearr.'">

<div class="list-block" >
  <div class="content-block-title titleb">Dati Prenotazione</div>
   <div class="list-group">
      <ul>
    <li onclick="modificaprezzoprev()">
      <div class="item-content">
        <div class="item-media"><i class="icon f7-icons">money_euro</i></div>
        <div class="item-inner">
          <div class="item-title ">Totale Prenotazione</div>
          <div class="item-after">
           '.$tot.' &euro;
          </div>
        </div>
      </div>
    </li>
	<li onclick="modificaaccontoprev()">
      <div class="item-content">
        <div class="item-media "><i class="icon f7-icons">money_euro</i></div>
        <div class="item-inner">
          <div class="item-title ">Acconto Richiesto ('.$accontop.'%)</div>
          <div class="item-after">
            '.$acconto.' &euro;
          </div>
        </div>
      </div>
    </li>
	
	</li></ul></div>
	 <div class="content-block-title titleb">Note ed Appunti</div>
	 <div class="list-group">
      <ul>
	
	
    <li class="align-top">
      <div class="item-content" style="height:110px;">
        <div class="item-inner">
          <div class="item-title" style=" width:100%;">
            <textarea  style="font-size:15px; padding-left:5px;border-left:solid 1px #f1f1f1; height:70px;" placeholder="Annotazione .." id="noteag" onchange="modprenot('.$IDrequest.',this.value,69,10,0)" >'.$note.'</textarea>
          </div>
        </div>
      </div>
    </li>
	</li>
	<ul></div>
	
  </ul>
</div>

';



 
echo $testo;
			 
?>			 
			 