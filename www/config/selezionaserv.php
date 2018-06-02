<?php

header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');
include('../../config/funzionilingua.php');

$IDstruttura=$_SESSION['IDstruttura'];

$IDsottotip=$_POST['IDsottotip'];
$time=$_POST['time'];


$testo='<input type="hidden" value="'.$time.'" id="time">
<input type="hidden" value="'.$IDsottotip.'" id="idosottotip">';


$query="SELECT s.ID,s.servizio,s.prezzo,t.tipolimite,s.IDtipo,s.durata FROM servizi as s,tiposervizio as t  WHERE s.IDsottotip='$IDsottotip' AND s.IDstruttura='$IDstruttura' AND t.ID=s.IDtipo";
$result=mysqli_query($link2,$query);	
		if(mysqli_num_rows($result)>0){
			while($row=mysqli_fetch_row($result)){
				$IDserv=$row[0];
				$servizio=$row[1];
				$prezzo=$row[2];
				$tipolimite=$row[3];
				$IDtipo=$row[4];
				$durata=$row[5];
				
				$testo.='

	<li class="item-link item-content" onclick="cambiaservizio('."'".$servizio."'".','.$IDserv.','.$prezzo.');" >
          <div class="item-inner">
					<div class="item-title">'.$servizio.'</div>
					<div style="margin-left:auto"><span style="font-size:13px;color:#e4492b;font-weight:400;">'.$prezzo.'€ </span></div>
		  </div>
        </li>';
				
				
			}
		}

?>
<div class="picker-modal smart-select-picker" id="popoverord">
      <div class="toolbar">
        <div class="toolbar-inner">
          <div class="left"></div>
          <div class="right"><a href="#" class="close-picker">Chiudi</a></div>
        </div>
      </div>
      <div class="picker-modal-inner">
		  <div class="page-content" style="background-color: white">
			<div class="list-block" style="margin-top:0px;">
				<ul>
					<?php 
						echo $testo;
					?>
			   </ul>
		   </div>
		  </div>
      </div>
</div>

