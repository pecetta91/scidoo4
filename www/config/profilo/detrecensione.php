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

$nomepren=estrainome($IDpren);
$timeora=oraadesso($IDstr);

$IDrec=$_GET['dato0'];

$query="SELECT titolo,recensione,time FROM recensioni WHERE ID='$IDrec' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$titolo=$row['0'];
$rec=$row['1'];
$time=$row['2'];






$testo='<div data-page="suggerimenti" class="page" > 
		 	<div class="navbar" id="navcal" >
				<div class="navbar-inner">
					<div class="left">
					<a href="#" class="link icon-only back"    >
						<i class="material-icons fs40"  >chevron_left</i>
					</a>
					</div>
					<div class="center titolonav">Dettaglio Recensione</div>
					<div class="right"></div>
				</div>
			</div>
			
		 <div class="page-content ">
		 	<div class="content-block" id="recensdet" >
			
			
			<p class="fs16 c333 p10 pt0" >
			<span class="fs11 c999">Pubblicata  '.dataita($time).' '.date('Y',$time).'</span><br>
			<b class="fs18">'.$titolo.'</b><br>
			'.$rec.'
			</p>
			
			
			
    <div class="list-block">
      <ul>';
	  
	  
	  $query="SELECT rd.voto,rp.parametro FROM recensionidet as rd, recensioniparam as rp WHERE rd.IDrecensione='$IDrec' AND rd.IDparam=rp.ID";
	  $result=mysqli_query($link2,$query);
	  if(mysqli_num_rows($result)>0){
       	while($row=mysqli_fetch_row($result)){
			
			 $testo.='<li class="item-content">
				  <div class="item-inner">
					<div class="item-title">'.$row['1'].'</div>
					<div class="item-after">';
					
					
				
								for($i=1;$i<6;$i++){
									if($i<=$row['0']){
										$testo.='<div class="star2"></div>';
									}else{
										$testo.='<div class="starg"></div>';
									}
								}
								$testo.='<br>';
					
					
					
					
					
					$testo.='</div>
				  </div>
				</li>
			';
			//<span class="badge bg-yellow">'.$row['0'].'</span>
		}
	  }
			
		$testo.='</ul></div>';	
			
			
	
					

$testo.='</div></div></div>


';




if(!isset($inc)){
echo $testo;
}




?>