<?php
header('Access-Control-Allow-Origin: *');
include('../../../config/connecti.php');
include('../../../config/funzioni.php');
include('../../../config/preventivoonline/config/funzioniprev.php');

$IDutente=$_SESSION['ID'];
$IDstruttura=$_SESSION['IDstruttura'];
$IDrequest=$_SESSION['IDrequest'];

$query="SELECT IDstr,notti,timearr,stato,checkout,agenzia FROM richieste WHERE ID='$IDrequest' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDstruttura=$row['0'];
$gg=$row['1'];
$ggric=$gg;
$timearr=$row['2'];
$datagg=date('Y-m-d',$timearr);
$stato=$row['3'];
$checkout=$row['4'];
$IDagenzia=$row['5'];
$ggsett=date('N',$timearr);


$IDsog='';
$qtap=0;

$query2="SELECT IDrestr,COUNT(*),GROUP_CONCAT(ID SEPARATOR ',') FROM richiestep WHERE IDreq='$IDrequest' GROUP BY IDrestr";
$result2=mysqli_query($link2,$query2);
if(mysqli_num_rows($result2)){
	while($row2=mysqli_fetch_row($result2)){
		$restr[$row2['0']]=$row2['1'];
		$qtap+=$row2['1'];
		$IDsog.=$row2['2'].',';
	}
}


$txthead='';
if($gg==0){
	$txthead='Pacchetti Disponibili';
}else{
	$txthead='Alloggi Disponibili';
}


echo '
<div class="divmainmenu">
<table width="100%;"><tr>
<td width="20%" align="left"><a href="#"   onclick="stepnew(-1,0)" class="button color-indigo button-raised button-fill indietro" >Indietro</a></td>
<td>'.$txthead.'</td>
<td width="20%" align="right"><a href="#" class="button button-raised button-fill color-indigo avanti"  onclick="avantistep2()">Avanti</a></td>

</tr></table>
</div>
		

';



if($gg==0){
	
	$testo='
	
	<div class="list-block media-list">
					<ul>
					
					
					<li >
															<label class="label-radio item-content">
															  <input type="radio" name="pacchetto" value="1" />
															   <div class="item-media" style="margin-left:-5px; margin-right:-20px;">
          <i class="icon icon-form-radio"></i>
        </div>
															  <div class="item-inner" onclick="selpacc(0,0)">
																<div class="item-title-row">
																  <div class="item-title" style="font-size:13px; color:#9b32ae;">Soggiorno Personalizzato
																  </div>
																  <div class="item-after" style="width:10%;"></div>
																</div>
								
															  </div>
															</label>
															
														  </li>
					
					
					
					';
	
	
	$query5="SELECT ID,servizio,IDsottotip,sottotip FROM servizi WHERE IDstruttura='$IDstruttura' AND IDtipo='9' AND sottotip='0' ORDER BY IDsottotip";
	$result5=mysqli_query($link2,$query5);
	while($row5=mysqli_fetch_row($result5)){
		$IDserv=$row5['0'];
		$sottotip=$row5['3'];
		$jj=0;
		
		//controllo che non ci siamo pernotti - non solo extra online
		
		
		$query="SELECT s.servizio FROM composizioni as c,servizi as s WHERE c.IDserv='$IDserv' AND c.IDservcont=s.ID AND s.IDtipo='8' LIMIT 1";
		$result=mysqli_query($link2,$query);
		if(mysqli_num_rows($result)==0){
			$query="SELECT IDserv FROM extraonline WHERE IDserv='$IDserv' LIMIT 1";
			$result=mysqli_query($link2,$query);
			if(mysqli_num_rows($result)>0){
				$jj=1;
			}
		}
		if($jj==1){
			$posb='1';
			$prezzo=0;
			$timec=$timearr-86400;
			//foreach ($restr as $key=> $dato){
				$prezzo=prezzopacc($IDserv,$timec,$key,$IDstruttura)*$dato;
			//}
			$testo.='<li >
															<label class="label-radio item-content">
															  <input type="radio" name="pacchetto" value="1"  />
															   <div class="item-media" style="margin-left:-5px; margin-right:-20px;">
          <i class="icon icon-form-radio"></i>
        </div>
															  
															  <div class="item-inner" onclick="selpacc(0,'.$IDserv.')">
																<div class="item-title-row">
																  <div class="item-title" style="font-size:13px;">'.$row5['1'].'
																  
																  </div>
																  <div class="item-after" style="width:10%;font-size:13px; color:#1f63d3;">€'.$prezzo.'</div>
																</div>
								
															  </div>
															</label>
															
														  </li>
														';
			
			
			
				
		}
	}
	
	$testo.='</ul>
	</div>';
	
	

}else{
	
	$testo='
	<div class="list-block accordion-list">
	  <ul>';
	
	list($yy, $mm, $dd) = explode("-", date('Y-m-d',$timearr));
	$time0=mktime(0, 0, 0, $mm, $dd, $yy);
	
	list($yy, $mm, $dd) = explode("-", date('Y-m-d',$checkout));
	$time0c=mktime(0, 0, 0, $mm, $dd, $yy);
	  
	  
	  
	$groupid=getdisponibilita($timearr,$checkout,$IDstruttura); 
	  
		$start=$timearr-5*86400;
		$IDallocc=array();
			  
		$query2="SELECT IDpren,time FROM prenextra WHERE IDstruttura='$IDstruttura' AND FROM_UNIXTIME(time,'%Y-%m-%d') BETWEEN '".date('Y-m-d',$start)."' AND '".date('Y-m-d',$timearr)."' AND IDtipo='8' AND modi>='0'  GROUP BY time,IDpren";
		$result2=mysqli_query($link2,$query2);
		if(mysqli_num_rows($result2)>0){
			while($row2=mysqli_fetch_row($result2)){
				$tt=$row2['1'];
				$IDpren=$row2['0'];
				$query3="SELECT app FROM prenotazioni WHERE IDv='$IDpren' LIMIT 1";
				$result3=mysqli_query($link2,$query3);
				$row3=mysqli_fetch_row($result3);
				$IDapp=$row3['0'];
				$dd=floor(($tt-$time0)/86400);
				$IDallocc[$dd][$IDapp]=1;
			}
		}
		
		
		$query2="SELECT IDpren,time FROM prenextra WHERE IDstruttura='$IDstruttura' AND FROM_UNIXTIME(time,'%Y-%m-%d') BETWEEN '".date('Y-m-d',$checkout)."' AND '".date('Y-m-d',($checkout+86400*5))."' AND IDtipo='8' AND modi>='0' GROUP BY time,IDpren";
		$result2=mysqli_query($link2,$query2);
		if(mysqli_num_rows($result2)>0){
			while($row2=mysqli_fetch_row($result2)){
				$tt=$row2['1'];
				$IDpren=$row2['0'];
				$query3="SELECT app FROM prenotazioni WHERE IDv='$IDpren' LIMIT 1";
				$result3=mysqli_query($link2,$query3);
				$row3=mysqli_fetch_row($result3);
				$IDapp=$row3['0'];
				$dd=floor(($tt-$time0)/86400);
				$IDallocc[$dd][$IDapp]=1;
			}
		}
	
	$finestep=$gg+5;
	$arrli=array();
	$arrprezzo=array();	
		
	$query="SELECT a.nome,a.categoria,a.ID,c.colore FROM appartamenti as a,categorie as c WHERE a.ID NOT IN($groupid)  AND a.attivo='1' AND a.IDstruttura='$IDstruttura' AND a.categoria=c.ID ORDER BY a.categoria";
	$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
		while($row=mysqli_fetch_row($result)){
			$IDcat=$row['1'];
			$IDapp=$row['2'];
			/*
			if(isset($arrprezzo[$IDcat])){
				$prezzopern=$arrprezzo[$IDcat];
			}else{
				$prezzopern=getprezzo($IDcat,$IDrequest,$gg,$timearr,$IDstruttura);
				$arrprezzo[$IDcat]=$prezzopern;	
			}
			*/
			
			$color="ccc";
			if(strlen($row['3'])>2)$color=$row['3'];
			
			$testo.='
				
		<li class="accordion-item"><a href="#" class="item-content item-link">
			<div class="" style="background:#'.$color.';margin-right:3px;  width:15px; height:15px;border-radius:50%; " ></div>
			<div class="item-inner">
				
			  <div class="item-title" style=" padding-left:4px; font-size:14px;">'.$row['0'].' 
			  <br>
			  <table class="dispon"><tr>';
			  
			  for ($i=-5;$i<=$finestep;$i++){
					$cla='emp';
					if(($i>=0)&&($i<$gg)){
						$cla='sel';
					}else{
						if(isset($IDallocc[$i][$row['2']])){
							$cla='ful';
						}
					}
					$testo.='<td class="'.$cla.'"></td>';
				}
			  $testo.='</tr></table>
			  </div>
			  <div class="item-after"></div>
			</div></a>
		  <div class="accordion-item-content" style="background:#f8f8f8;padding:0px;">
			<div class="content-block" style="padding:0px;">
			 
				 <div class="list-block media-list">
					<ul>';
						$testo.='<li style="background:#f8f8f8;" >
							<label class="label-radio item-content" >
							  <input type="radio" name="pacchetto" value="1" />
							  
							   <div class="item-media" style="margin-left:-5px; margin-right:-20px;">
          <i class="icon icon-form-radio"></i>
        </div>
							  <div class="item-inner" onclick="selpacc('.$IDapp.',0)">
								<div class="item-title-row">
								  <div class="item-title" style="font-size:13px;">Solo Pernotto</div>
								  <div class="item-after" style="width:10%;color:#1f63d3;font-size:13px;">€ '.$prezzopern.'</div>
								</div>
	
							  </div>
							</label>
						  </li>';
						  
							 $query5="SELECT ID,servizio,IDsottotip,sottotip FROM servizi WHERE IDstruttura='$IDstruttura' AND IDtipo='9' AND attivo>'0' AND sottotip='1' ORDER BY sottotip,IDsottotip";
							$result5=mysqli_query($link2,$query5);
							if(mysqli_num_rows($result5)>0){
								
								
								while($row5=mysqli_fetch_row($result5)){
								$IDserv=$row5['0'];
										$posb='1';
										
											$prezzo=0;
												$prezzopacctot=0;
													
													
													
													if(isset($arrli[$IDserv])){
														$prezzopacctot=$arrli[$IDserv];
													}else{
														$timec=$timearr-86400;
														for($i=0;$i<$gg;$i++){
															$timec+=86400;
															foreach ($restr as $key=> $dato){
																$prezzopacctot+=prezzopacc($IDserv,$timec,$key,$IDstruttura)*$dato;
															}
														}
														$arrli[$IDserv]=$prezzopacctot;
													}
													
													$prezzo=$prezzopern+$prezzopacctot;
													
													$testo.='<li style="background:#f8f8f8;" >
														<label class="label-radio item-content">
														  <input type="radio" name="pacchetto" value="1" />
														   <div class="item-media" style="margin-left:-5px; margin-right:-20px;">
          <i class="icon icon-form-radio"></i>
        </div>
														  <div class="item-inner" onclick="selpacc('.$IDapp.','.$IDserv.')">
															<div class="item-title-row">
															  <div class="item-title" style="font-size:13px;">Pernotto + '.$row5['1'].'</div>
															  <div class="item-after" style="width:10%;font-size:13px; color:#1f63d3;">€ '.$prezzo.'</div>
															</div>
								
														  </div>
														</label>
													  </li>
													';
							
								
								}
							} 
							
							
							
							$query5="SELECT ID,servizio,IDsottotip,sottotip FROM servizi WHERE IDstruttura='$IDstruttura' AND IDtipo='9' AND attivo>'0' AND sottotip='0' ORDER BY sottotip,IDsottotip";
							$result5=mysqli_query($link2,$query5);
							if(mysqli_num_rows($result5)>0){
								
								
								while($row5=mysqli_fetch_row($result5)){
									$IDserv=$row5['0'];
									//controllo notti
									$jj=0;
									$query6="SELECT s.servizio FROM composizioni as c,servizi as s WHERE c.IDserv='$IDserv' AND c.IDservcont=s.ID AND s.IDtipo='8' AND c.qta='$gg' LIMIT 1";
									$result6=mysqli_query($link2,$query6);
									if(mysqli_num_rows($result6)>0){
										$jj=1;
									}
													
									if($jj==1){
											$posb='1';
											
											$prezzo=0;
												
											$timec=$timearr-86400;
											if(isset($arrli[$IDserv])){
												$prezzo=$arrli[$IDserv];
											}else{
												foreach ($restr as $key=> $dato){
													$prezzo+=prezzopacc($IDserv,$timec,$key,$IDstruttura)*$dato;
												}
												$arrli[$IDserv]=$prezzo;
											}
												
											$testo.='<li style="background:#f8f8f8;" >
															<label class="label-radio item-content">
															  <input type="radio" name="pacchetto" value="1" />
															   <div class="item-media" style="margin-left:-5px; margin-right:-20px;">
          <i class="icon icon-form-radio"></i>
        </div>
															 
															  <div class="item-inner" onclick="selpacc('.$IDapp.','.$IDserv.')">
																<div class="item-title-row">
																  <div class="item-title" style="font-size:13px;">'.$row5['1'].'
																  
																  </div>
																  <div class="item-after" style="width:10%;font-size:13px; color:#1f63d3;">€'.$prezzo.'</div>
																</div>
								
															  </div>
															</label>
															
														  </li>
														';
									}
								
								}
							} 
						  
						  
						  
						  
						  
		 
			 $testo.='
					</ul>
				</div>
			</div>
		  </div>
		</li>
			
			
			';
			
		
		
		
		
		
		
		}
	}
	
	$testo.='
	
	</ul><//div>
	';
}

echo $testo;
			 