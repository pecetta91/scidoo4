<?php 
header('Access-Control-Allow-Origin: *');
include('../../../../config/connecti.php');
include('../../../../config/funzioni.php');
include('../../../../config/preventivoonline/config/funzioniprev.php');
$testo='';

$IDpren=$_SESSION['IDstrpren'];

$IDserv=$_GET['IDserv'];


$query="SELECT IDstruttura FROM prenotazioni WHERE IDv='$IDpren' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDstruttura=$row['0'];

$query="SELECT IDsottotip FROM servizi WHERE IDstruttura='$IDstruttura' AND ID='$IDserv' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDsottotip=$row['0'];


$query="SELECT sale.ID,sale.nome FROM sale,saleassoc WHERE sale.IDstr='$IDstruttura' AND saleassoc.IDsotto='$IDsottotip' AND saleassoc.ID=sale.ID ORDER BY saleassoc.priorita";
$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
		$testo.='<div id="nuovoserv">';
		while($row=mysqli_fetch_row($result)){
		
			
				$testo.='<div class="row rowlist no-gutter h30" onclick="cambiasala('.$row[0].','."'".$row[1]."'".');myApp.closeModal();" id="'.$row['0'].'">
									<div class="col-5"></div>
									<div class="col-95 campiricerca">'.$row['1'].'</div>
									';
				$testo.='</div>';
		}
		$testo.='</div>';
	}
?>
<div class="picker-modal " id="popoverord" >
		  <div class="toolbar">
			<div class="toolbar-inner">
		  	  <div class="left"></div>
			  <div class="right"><a href="#" class="close-picker">Chiudi</a></div>
			</div>
		  </div>
		  <div class="picker-modal-inner ">
		 <div class="page-content bcw" > 
		 	<?php echo $testo;?>
			  </div>
		  </div>
</div>
