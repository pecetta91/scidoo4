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
					<div class="center titolonav">Nuova Recensioni</div>
					
				</div>
			</div>
			
		 <div class="page-content ">
		 	<div class="content-block" id="nuovarecens" >
			
			<div style="margin-left:15px;">
			<b style="font-size:14px;" >TITOLO</b><br>
			<input type="text" id="titolo" style="border:solid 1px #ccc; border-radius:3px;font-size:17px; width:90%; height:45px;"><br>
	<br>
			<b style="font-size:14px;">RECENSIONE</b><br>
			<textarea id="recens" style="border:solid 1px #ccc; border-radius:3px; width:92%; font-size:15px; padding:5px; min-height:130px;"></textarea>
	
	</div>
			<div class="content-block-title titleb">Caratteristiche e Voti</div>
			<div class="list-block">
      <ul>';
	  
	  $query="SELECT ID,parametro FROM recensioniparam ";
	  $result=mysqli_query($link2,$query);
	  if(mysqli_num_rows($result)>0){
       	while($row=mysqli_fetch_row($result)){
			
			 $testo.='
			 
			 	<li>
				  <a href="#" class="item-link smart-select">
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
		
		
		<br><br>
		
		<p style="font-size:11px; padding:10px;">
		<b style="font-size:13px;">Termini e Condizioni</b><br>
		La vostra recensione sar&agrave; utilizzata dalla Struttura per migliorare il proprio servizio.<br><br> 
		I dati immessi sono protetti da privacy e crittografati.<br>
		Potranno essere utilizzati soltanto dalla struttura per fini produttivi e di crescita.<b> <br><br>
		
		
		<b>(*) La tua recensiona rimarr&agrave;  anonima  ai viaggiatori.</b></p><br><br><br>
		

<div  onclick="salvarecensione()" style="buttonbottom verde">SALVA  RECENSIONE</div>

</div></div></div>


';




if(!isset($inc)){
echo $testo;
}




?>