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



//onscroll="infscroll(this)" per infinite scroll
$posrec='';

$query="SELECT IDcliente FROM infopren WHERE IDpren='$IDpren' AND IDstr='$IDstr' AND IDcliente!='0' ORDER BY IDcliente LIMIT 1 ";
		$result=mysqli_query($link2,$query);
		$row=mysqli_fetch_row($result);
		$IDutente=$row['0'];
		$tipoutente=0;

$query="SELECT ID FROM recensioni WHERE IDstr='$IDstr' AND IDutente='$IDutente' AND tipoutente='$tipoutente'";
$result=mysqli_query($link2,$query);

if(mysqli_num_rows($result)==0){
	$posrec='<div  onclick="navigation(32,0,0,0)" class="buttonbottom fucsia">FAI UNA RECENSIONE</div>';
	
}





$testo='<div data-page="suggerimenti" class="page" > 
		 	<div class="navbar" id="navcal" >
				<div class="navbar-inner">
					<div class="left">
					<a href="#" class="link icon-only back"    >
						<i class="material-icons" style="font-size:40px;">chevron_left</i>
					</a>
					</div>
					<div class="center titolonav">Recensioni</div>
					<div class="right"></div>
				</div>
			</div>
			<div class="bottombarpren" style="background:#f1f1f1;z-index:999" align="center">
			  <table style="width:100%;height:100%;cellpadding:0;cellspacing:0;">
			   <tr>
			     <td style="width:15%">
                 </td>
				<td>
		       <button class="button button-fill color-green bottoneprezzo" style="margin:auto;" onclick="navigation(32,0,0,0)">Scrivi Recensione</button>
			    </td>
				 <td style="width:15%">
                 </td>
			   </tr>
			  </table>
			</div>
			
			
			
		 <div class="page-content " >
		 	<div class="content-block" id="recens" >
			
			
			'.$posrec.'

			
			
			
			
			<div class="content-block-title titleb">Ultime 50 Recensioni</div>
			<div class="list-block media-list">
			 <ul>';
			 
			$query="SELECT ID,titolo,recensione,time FROM recensioni WHERE IDstr='$IDstr' ORDER BY time DESC LIMIT 50";
			$result=mysqli_query($link2,$query);
			if(mysqli_num_rows($result)>0){
				while($row=mysqli_fetch_row($result)){
					$IDrec=$row['0'];
					
					$testo.='<li onclick="navigation(31,'.$IDrec.',0,0)">
					  <a href="#" class="item-link item-content"  >
					  	
						<div class="item-inner">
						  <div class="item-title-row">
							<div class="item-title">';
							
							$query2="SELECT AVG(voto) FROM recensionidet WHERE IDrecensione='$IDrec'";
							$result2=mysqli_query($link2,$query2);
							$row2=mysqli_fetch_row($result2);
							$media=round($row2['0']);
							if($media>=1){
								for($i=1;$i<6;$i++){
									if($i<=$media){
										$testo.='<div class="star2"></div>';
									}else{
										$testo.='<div class="starg"></div>';
									}
								}
								$testo.='<br>';
							}
							
							$testo.=$row['1'].'</div>
							<div class="item-after fs11 c999">'.dataita5($row['3']).'</div>
						  </div>
						  
						  <div class="item-text">'.$row['2'].'</div>
						</div>
					  </a>
					</li>';
					
					
				}
			} 
					
					
					
					
					
			$testo.='</ul></div>		
					
					
				<br><br><br><br><br>
			 ';

		
		
		

					  
	
	
					

$testo.='

</div></div></div>


';




if(!isset($inc)){
echo $testo;
}




?>