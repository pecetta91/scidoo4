<?php 
	include('../../../config/connecti.php');
	include('../../../config/funzioni.php');
	include('../../../config/funzionilingua.php');
	header('Access-Control-Allow-Origin: *');
	$testo='';


$IDpren=$_SESSION['IDstrpren'];
$route=$_SESSION['route'];



$query="SELECT app,gg,time,tempg,tempn,checkout,IDstruttura FROM prenotazioni WHERE IDv='$IDpren' LIMIT 1";

$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDapp=$row['0'];
$notti=$row['1'];
$check=$row['2'];
$tempg=$row['3'];
$tempn=$row['4'];
$checkout=$row['5'];
$IDstr=$row['6'];






$testo='<div data-page="suggerimenti" class="page" > 
		 	<div class="navbar" id="navcal" >
				<div class="navbar-inner">
					<div class="left">
					<a href="#" class="link icon-only back"    >
						<i class="material-icons" style="font-size:40px;">chevron_left</i>
					</a>
					</div>
					<div class="center titolonav">Nuova Recensione</div>
					<div class="right"></div>
				</div>
			</div>
			<div class="bottombarpren" style="background:#f1f1f1;z-index:999" align="center">
			  <table style="width:100%;height:100%;cellpadding:0;cellspacing:0;">
			   <tr>
			     <td style="width:15%">
                 </td>
				<td>
		       <button class="button button-fill color-green bottoneprezzo" style="margin:auto;" onclick="salvarecensione2()">Salva Recensione</button>
			    </td>
				 <td style="width:15%">
                 </td>
			   </tr>
			  </table>
			</div>
			
			
			
			
		 <div class="page-content ">
		 	<div class="content-block" id="nuovarecens" >
			
				<div class="list-block">
		  <ul>
			<li>
			 <div class="item-content h100" >
			  <div class="item-inner h100" >
				  <div class="item-input">
            		<textarea id="titolo" style="height:90px" placeholder="Titolo"></textarea>
        	  </div>
			 </div>
			 </div>
			</li>
			</ul></div>
			
			<div class="list-block"><ul>
				<li>
			 <div class="item-content h100" >
			  <div class="item-inner h100" >
				  <div class="item-input">
            		<textarea id="recensione" class="textareanew" placeholder="Recensione"></textarea>
        	  </div>
			 </div>
			 </div>
			</li>
			
			</ul></div>
			
		
			<div class="content-block-title titleb">Caratteristiche e Voti</div>
			<div class="list-block">
      <ul>';
	  
	  $query="SELECT ID,parametro FROM recensioniparam ";
	  $result=mysqli_query($link2,$query);
	  if(mysqli_num_rows($result)>0){
       	while($row=mysqli_fetch_row($result)){
			
			 $testo.='
			 
			 	<li>
				  <a href="#" class="item-link smart-select" data-open-in="picker">
					<select class="param" id="param'.$row['0'].'" alt="'.$row['0'].'">
						<option value="0">--</option>
					
					 ';
					 
					 for($i=1;$i<6;$i++){
						$testo.='<option value="'.$i.'">Voto '.$i.'</option>';
						}
					 
					 $testo.='
					</select>
					<div class="item-content">
					  <div class="item-inner">
						<div class="item-title">'.$row['1'].'</div>
						<div class="item-after"></div>
					  </div>
					</div>
				  </a>
				</li>
			 
			 
			';
		}
	  }
			
		$testo.='</ul></div>
		
		<p class="privacyinfo">
		<b style="font-size:13px;">Termini e Condizioni</b><br>
		La vostra recensione sar&agrave; utilizzata dalla Struttura per migliorare il proprio servizio.<br><br> 
		I dati immessi sono protetti da privacy e crittografati.<br>
		Potranno essere utilizzati soltanto dalla struttura per fini produttivi e di crescita.<b> <br><br>
		
		
		<b>(*) La tua recensiona rimarr&agrave;  anonima  ai viaggiatori.</b></p><br><br><br>
		
</div></div></div>


';




if(!isset($inc)){
echo $testo;
}




?>