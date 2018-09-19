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

$query="SELECT ID FROM recensioni WHERE IDstr='$IDstr' AND IDutente='$IDutente' AND tipoutente='$tipoutente' AND visibile='1'";
$result=mysqli_query($link2,$query);

if(mysqli_num_rows($result)==0){
	$posrec='<div class="bottombarpren" style="background:#f1f1f1;z-index:999" align="center">
			  <table style="width:100%;height:100%;cellpadding:0;cellspacing:0;">
			   <tr>
			     <td style="width:15%">
                 </td>
				<td>
		       <button class="button button-fill color-green bottoneprezzo" style="margin:auto;background:#d80c4d;" onclick="navigation(32,0,0,0)">Scrivi Recensione</button>
			    </td>
				 <td style="width:15%">
                 </td>
			   </tr>
			  </table>
			</div>';
	/*
			
	<div  onclick="navigation(32,0,0,0)" class="buttonbottom fucsia">FAI UNA RECENSIONE</div>*/
}





$testo='<div data-page="suggerimenti" class="page" > 
		 	<div class="navbar" id="navcal" >
				<div class="navbar-inner">
					<div class="left navbarleftsize170">
					<a href="#" class="link icon-only back"    >
						<i class="material-icons">chevron_left</i>
							<strong class="stiletitolopagine">Recensioni</strong>
					</a>
					</div>
					<div class="center titolonav"></div>
					<div class="right"></div>
				</div>
			</div>
			
			'.$posrec.'
			
		 <div class="page-content " >
		 	<div class="content-block" id="recens" >
			';
$numrecen=0;
						$query="SELECT ID,titolo,recensione,time FROM recensioni WHERE IDstr='$IDstr' AND visibile='1' ORDER BY time DESC LIMIT 10";
			$result=mysqli_query($link2,$query);
			if(mysqli_num_rows($result)>0){
				
				
				$testo.='<div class="content-block-title titleb">Ultime 10 Recensioni</div>
			
			<div class="swiper-container  swiper-init"  data-speed="400"  data-pagination=".swiper-pagination" data-space-between="10" data-slides-per-view="auto" data-centered-slides="true" data-loop="true" style="height:225px;background-color:#fff; padding-top:10px;">
						<div class="swiper-pagination"></div>
						<div class="swiper-wrapper">';
				
				
				
				while($row=mysqli_fetch_row($result)){
					$IDrec=$row['0'];
							$rec='';
							
					
							$query2="SELECT AVG(voto) FROM recensionidet WHERE IDrecensione='$IDrec'";
							$result2=mysqli_query($link2,$query2);
							$row2=mysqli_fetch_row($result2);
							$media=round($row2['0']);
							if($media>=1){
								for($i=1;$i<6;$i++){
									if($i<=$media){
										$rec.='<div class="star2"></div>';
									}else{
										$rec.='<div class="starg"></div>';
									}
								}
								$rec.='<br>';
							}	
					
							if(strlen($row['2'])>150){
									$row['2']=stripslashes(substr($row['2'],0,150));
								}
						
						
							$testo.='<div class="swiper-slide  pags" >
						  			<div class="paginaslider">
										<div class="prenotaservscroller">
												<div class="corpoorizzontalescrol" style="margin-top:140px">
													<button class="button button-fill button-raised prenotaoraoriz" onclick="navigation(31,'.$IDrec.',0,0)">Dettaglio Recensione</button>
												</div>
										</div>
										<div  class="orizontalscroll" style="border:1px solid #e1e1e1;height:125px">
											<div class="row no-gutter" style="padding-left:15px;padding-top:15px">
												<div class="servnome col-60 " >'.$row['1'].'</div>
												<div class="col-40 ">'.$rec.'</div>
												<div class="col-40 fs11 c999">'.dataita5($row['3']).'</div>
											</div>	
											<div style="padding:10px 15px;color:#333">'.$row['2'].'</div>
										</div>
								</div>
						  </div>';
					$numrecen++;
				}
				
					$testo.='	</div>
				</div>';
				
			}
						

			
			
			
	 
			$query="SELECT ID,titolo,recensione,time FROM recensioni WHERE IDstr='$IDstr' AND visibile='1' ORDER BY time DESC LIMIT $numrecen,50";
			$result=mysqli_query($link2,$query);
			if(mysqli_num_rows($result)>0){
			$testo.='<div class="content-block-title titleb" style="padding-top:10px">Ultime Recensioni</div>';
				while($row=mysqli_fetch_row($result)){
					$IDrec=$row['0'];
					$rec='';
						$testo.='<div class="row no-gutter rowlist" onclick="navigation(31,'.$IDrec.',0,0)" style="position:relative">';
							
							$query2="SELECT AVG(voto) FROM recensionidet WHERE IDrecensione='$IDrec'";
							$result2=mysqli_query($link2,$query2);
							$row2=mysqli_fetch_row($result2);
							$media=round($row2['0']);
							if($media>=1){
								for($i=1;$i<6;$i++){
									if($i<=$media){
										$rec.='<div class="star2" style="padding:0px"></div>';
									}else{
										$rec.='<div class="starg" style="padding:0px"></div>';
									}
								}
								$rec.='<br>';
							}
					
							$testo.='<div class="col-60">'.$row['1'].'</div>
											<div class="col-40" style="padding:0px">'.$rec.'</div>
											<div class="col-40 fs11 c999">'.dataita5($row['3']).'  </div>
											<div>
												<div style="position:absolute;right:0;top:0;padding:11px 10px;color:#737373"><i class="material-icons" style="font-size:25px;">chevron_right</i></div> 
											</div>
									</div>';
							
							
					
				}
			}else{
				$testo.='<div class="titleb" style="text-align:center;">Non è stata ancora registrata nessuna recensione.<br/><br/>
				Scrivila tu per primo!
				</div>
											';
				
				
			}
/*
<div class="list-block media-list">
			 <ul>
			 
			 
			 $testo.='<li onclick="navigation(31,'.$IDrec.',0,0)">
					  <a href="#" class="item-link item-content"  >
					  	
						<div class="item-inner">
						  <div class="item-title-row">
							<div class="item-title">';
							
							
							
							
			 	$testo.=$row['1'].'</div>
							<div class="item-after fs11 c999">'.dataita5($row['3']).'</div>
						  </div>
						  
						  <div class="item-text">'.$row['2'].'</div>
						</div>
					  </a>
					</li>';
					
</ul></div>	
*/

$testo.='<br><br><br><br><br>

</div></div></div>


';




if(!isset($inc)){
echo $testo;
}




?>