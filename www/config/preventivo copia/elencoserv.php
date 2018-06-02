<?php

if(!isset($inc)){
	header('Access-Control-Allow-Origin: *');
	include('../../../config/connecti.php');
	include('../../../config/funzioni.php');
	include('../../../config/preventivoonline/config/funzioniprev.php');
	
	$IDutente=$_SESSION['ID'];
	$IDstruttura=$_SESSION['IDstruttura'];
	$IDrequest=$_SESSION['IDrequest'];
	
	$query="SELECT IDstr,notti,timearr,checkout FROM richieste WHERE ID='$IDrequest' LIMIT 1";
	$result=mysqli_query($link2,$query);
	$row=mysqli_fetch_row($result);
	$IDstruttura=$row['0'];
	$gg=$row['1'];
	$ggric=$gg;
	$timearr=$row['2'];
	$datagg=date('Y-m-d',$timearr);
	$checkout=$row['3'];
	$ggsett=date('N',$timearr);
	$tipo=$_GET['tipo'];
	$testo='';

}





$paccin=array();


$paccaltro=array();

		$tipostr=0;
		
		
		
		$serviziarr=array();
		$servizitxt=array();
	
		$query2="SELECT r.IDrestr,r.ID,t.restrizione,r.paccnotti FROM richiestep as r,tiporestr as t WHERE r.IDreq='$IDrequest' AND r.IDrestr=t.ID ";
		$result2=mysqli_query($link2,$query2);
		if(mysqli_num_rows($result2)){
			while($row2=mysqli_fetch_row($result2)){
				
				$IDpersona=$row2['1'];
				$pacc=0;
				$tipopacc=0;
				
				$query3="SELECT pacc,tipopacc FROM oraripren WHERE IDreq='$IDrequest' AND pacc!='0' AND IDrestr='$IDpersona' LIMIT 1";
				$result3=mysqli_query($link2,$query3);
				if(mysqli_num_rows($result3)>0){
					$row3=mysqli_fetch_row($result3);
					$pacc=$row3['0'];
					$tipopacc=$row3['1'];
				}
				
				$rest=$row2['0'];
				$paccnotti=$row2['3'];
				if($paccnotti=='1'){
					$calc=1;
				}	
				if(isset($arrnum[$row2['0']])){
					$arrnum[$row2['0']]++;
				}else{
					$arrnum[$row2['0']]=1;
				}	
				
				if($tipopacc==0){
					if($pacc==0){
						
						
						if(!isset($serviziarr[$pacc])){
							$serviziarr[$pacc]='';
							$servizitxt[$pacc]='Nessun Trattamento';
						}
						$serviziarr[$pacc].='<tr><td colspan="2">'.$arrnum[$row2['0']].'&deg; '.$row2['2'].'</td><td></td></tr>';
					}else{
						$query3="SELECT sottotip,servizio FROM servizi WHERE ID='$pacc' LIMIT 1";
						$result3=mysqli_query($link2,$query3);
						$row3=mysqli_fetch_row($result3);
						$sottotip=$row3['0'];
						$servizio=$row3['1'];	
						if($sottotip=='1'){ //trattamento
							//calcolo prezzo
							
							if(!isset($serviziarr[$pacc])){
								$serviziarr[$pacc]='';
								$servizitxt[$pacc]=$servizio;
							}	
								
							if(isset($paccin[$pacc])){
								$paccin[$pacc]++;
							}else{
								$paccin[$pacc]=1;
							}
								
							/*		
							$txt2.='<tr><td><b>'.$arrnum[$row2['0']].'&deg; '.$row2['2'].'</b><br>'.$servizio.'</td><td align="right">
							'.$gg.' x <input type="text" class="inpprev" onchange="modprenot('."'".$pacc.'///'.$IDpersona."///0'".',this.value,92,10,27)" value="'.$prezzop.'"> € = <input type="text" class="inpprev" onchange="modprenot('."'".$pacc.'///'.$IDpersona."///1'".',this.value,92,10,27)" value="'.$prezzotot.'"> €</td>';*/
						}else{					
							//calcolo prezzo singolo
							
							if(!isset($serviziarr[$pacc])){
								$serviziarr[$pacc]='';
								$servizitxt[$pacc]=$servizio;
							}
						
							if(isset($paccin[$pacc])){
								$paccin[$pacc]++;
							}else{
								$paccin[$pacc]=1;
							}
						}
					}
				}
	
				if($tipopacc==1){
					if(!isset($serviziarr[$tipopacc.$pacc])){
						$query3="SELECT codiceidea FROM ideeregalosold WHERE ID='$pacc' LIMIT 1";
						$result3=mysqli_query($link2,$query3);
						$row3=mysqli_fetch_row($result3);
						$idea=$row3['0'];
						$serviziarr[$tipopacc.$pacc]='';
						$servizitxt[$tipopacc.$pacc]=$idea;
						array_push($paccaltro,$tipopacc.$pacc);
					}
					
					$serviziarr[$tipopacc.$pacc].=$arrnum[$row2['0']].'&deg; '.$row2['2'].', ';
				}
				if($tipopacc==2){
					
					if(!isset($serviziarr[$tipopacc.$pacc])){
						$query3="SELECT cofanetto FROM cofanetti WHERE ID='$pacc' LIMIT 1";
						$result3=mysqli_query($link2,$query3);
						$row3=mysqli_fetch_row($result3);
						$idea=$row3['0'];
						$serviziarr[$tipopacc.$pacc]='';
						$servizitxt[$tipopacc.$pacc]=$idea;
						array_push($paccaltro,$tipopacc.$pacc);
					}
					
					
					$serviziarr[$tipopacc.$pacc].=$arrnum[$row2['0']].'&deg; '.$row2['2'].', ';
					
					
					
					
				}
				
			}
		}


$txt['0']='';
$txt['1']='';
$txt['2']='';


		$i=0;
		foreach($serviziarr as $pacc =>$dato){
			$style='';
			if($i>0){$style='margin-top:0px;';}
			
			if(in_array($pacc,$paccaltro)){
				$tip = substr($pacc, 0, 1);
				$pacc2=substr( $pacc, 1 );
				
				$tit='';
				switch($tip){
					case 1:
						$txt[$tip].='<div style="background:#f1f1f1;" onclick="selectpers(0,0,0,0)"><br><div class="titleb" style="border-bottom:solid 1px #ccc; margin-left:15px; color:#2473e9; '.$style.'">Carrello Voucher</div>';
					break;
					case 2:
						$txt[$tip].='<div style="background:#f1f1f1;" onclick="selectpers(0,0,0,0)"><br><div class="titleb" style="border-bottom:solid 1px #ccc; margin-left:15px; color:#2473e9; '.$style.'">Carrello Cofanetti</div>';
					break;
					//<button class="shortcut recta13 danger" style="position:absolute; margin-top:-30px; right:15px;" onclick="eliminapaccprev('.$pacc2.','.$tip.')">Elimina</button>
					
				}
				
				$txt[$tip].='
				
				<div style="padding:10px; padding-left:25px; font-weight:600; font-size:13px;">
				<li>'.$servizitxt[$pacc].'<br>
				<div style="color:#777;padding-left:25px;font-weight:400;">Per :'.substr($dato, 0, strlen($dato)-2).'</div></li>
				</div></div>
				
				';
			}else{
				$txttit='';
				$txttit=$servizitxt[$pacc];
				
				if($pacc!=0){
					
					/*$txt[0].='
					<div class="titleb" style="border-bottom:solid 1px #ccc; color:#2473e9; '.$style.'">'.$txttit.'</div>
					<table class="tablist">'.$dato.'</table><br>
					';*/
				}
			}
			
			$i++;
			
		}
		





switch($tipo){
	case 0:
		
		
		
		$IDapp=$_SESSION['app'];
	
	
	
		$clas='';
		$add='';
		if(isset($paccin[0])){
				$clas=' style="background:#37b05f; color:#fff;"';
				$add.='<br><span style="font-size:10px;color:#fff;">N. '.$paccin[$IDserv].' '.txtpersone($paccin[$IDserv]).'</span>';
		}

//selpacc('.$IDapp.',0,this)
		$testo.='<div class="list-block">
  <ul>';
  
  if($gg>0){
	  
	  
	  		
			$txtpers='';
			$txtprezzo='&euro; 0';
			$query="SELECT COUNT(DISTINCT(IDsog)),SUM(prezzo) FROM prezzip WHERE IDreq='$IDrequest' ";
			$coloraft='style="color:#208c43;"';
			$clas='';
			$result=mysqli_query($link2,$query);
			if(mysqli_num_rows($result)>0){
				$row=mysqli_fetch_row($result);
				if($row['0']!=0){
					
					$clas=' style="background:#e1e1e1;"';
					//$coloraft='style="color:#fff;"';
					
					$txtpers='<br><span style="font-size:10px; ">N.'.$row['0'].' '.txtpersone($row['0']).'<span>';
					$txtprezzo='&euro; '.$row['1'];
				}
			}
	  
	  		
	  
  		$testo.='<li>
				 <a href="#" class="item-link item-content" '.$clas.'>
							<div class="item-inner">
							  <div class="item-title">Solo Pernotto'.$txtpers.'</div>
							  <div class="item-after" '.$coloraft.'>'.$txtprezzo.'</div>
							</div>
						  </a>
						</li>
						
						
							';
  }
						  
							 $query5="SELECT ID,servizio,IDsottotip,sottotip,prezzo FROM servizi WHERE IDstruttura='$IDstruttura' AND IDtipo='9' AND attivo>'0' ORDER BY sottotip DESC,prezzo";
							$result5=mysqli_query($link2,$query5);
							if(mysqli_num_rows($result5)>0){
								
								
								while($row5=mysqli_fetch_row($result5)){
									$IDserv=$row5['0'];
									$posb='1';
									
									if($row5['3']==1){
										$jj=1;
										if($gg==0){
											$jj=0;
										}
									}else{
										$jj=0;
										
										
										if($gg==0){
											$query6="SELECT s.servizio FROM composizioni as c,servizi as s WHERE c.IDserv='$IDserv' AND c.IDservcont=s.ID AND s.IDtipo='8'  LIMIT 1";
											$result6=mysqli_query($link2,$query6);
											if(mysqli_num_rows($result6)==0){
												$jj=1;
											}
										}else{
											$query6="SELECT s.servizio FROM composizioni as c,servizi as s WHERE c.IDserv='$IDserv' AND c.IDservcont=s.ID AND s.IDtipo='8' AND c.qta='$gg' LIMIT 1";
											$result6=mysqli_query($link2,$query6);
											if(mysqli_num_rows($result6)>0){
												$jj=1;
											}
										}
											
										if($jj==1){
											$query="SELECT s.IDtipo FROM composizioni as c,servizi as s WHERE c.IDserv='$IDserv' AND c.IDservcont=s.ID  AND s.esclusivo='1'";
											$result=mysqli_query($link2,$query);
											if(mysqli_num_rows($result)>0){
												//controlla se c'e' la possibilita' durante il soggiorno di ricevere il servizio esclusivo
												while($row=mysqli_fetch_row($result)){
													$IDtipoint=$row['0'];
													
														$ggappo=$gg;
														if($ggappo==0){$ggappo=1;}
														for($k=0;$k<=$ggappo;$k++){
															$datat=date('Y-m-d',$timearr+$k*86400);
															$query3="SELECT ID FROM prenextra WHERE IDstruttura='$IDstruttura' AND IDtipo='$IDtipoint' AND esclusivo='1' AND FROM_UNIXTIME(time,'%Y-%m-%d')='$datat' LIMIT 1";
															$result3=mysqli_query($link2,$query3);
															if(mysqli_num_rows($result3)>0){
																$jj=0;
															}
														}
													
												}
											}
										}		
									}
									
									
									
									
									if($jj==1){
									
											$prezzo=0;
												$prezzopacctot=0;
													
													$prezzo=$row5['4'];
													
													//onclick="selpacc('.$IDapp.','.$IDserv.',this)"
													
													$clas='';
													$add='';
													$coloraft='style="color:#37b05f;"';
													if(isset($paccin[$IDserv])){
														$clas=' style="background:#e1e1e1;"';
														$add.='<br><span style="font-size:10px;">N. '.$paccin[$IDserv].' '.txtpersone($paccin[$IDserv]).'</span>';
														//$coloraft='style="color:#fff;"';
														
													}
													$testo.='
														<li>
														  <a href="#" class="item-link item-content" '.$clas.' onclick="selectpers('.$IDserv.',0,0,0)">
															<div class="item-inner">
															  <div class="item-title">'.$row5['1'].$add.'</div>
															  <div class="item-after"  '.$coloraft.'>€ '.$prezzo.'</div>
															</div>
														  </a>
														</li>
													';
									}
								
								}
							} 
							
						  
						  $testo.='</ul></div>';
		
		
		
		
	break;
	case 1:
	
		//elenco voucher inseriti
		
	
		//elenco voucher con ricerca
		$cerca='';
		if(isset($_GET['cerca'])){
			$cerca=strip_tags($_GET['cerca']);
		}
		
		
		if(isset($txt[$tipo])){
			$testo.=$txt[$tipo];
		}
		
		$testo.='
		<a href="#" class="button button-big " style="width:80%;  margin:auto; border-color:#ccc; padding:0px;line-height:45px; font-weight:600; text-transform:uppercase;" onclick="cercavoucher()">Inserisci Codice Voucher</a><br>
		
		
		';
		
		
		if(strlen($cerca)>0){
			//effettaure ricerca
			
				$queryreg = "SELECT id,codiceidea,prezzo,statov,persone,idea FROM ideeregalosold WHERE IDstruttura='$IDstruttura' AND codiceidea LIKE '%$cerca%'  ORDER BY statov,codiceidea LIMIT 25";
				$resultreg = mysqli_query($link2,$queryreg);
				if(mysqli_num_rows($resultreg)>0){
					
					$testo.='<div class="list-block media-list">
  <ul>';
					while($rowreg=mysqli_fetch_row($resultreg)){
						
						$pagatotxt='';
						$utilizzatotxt='';
						
						
						$pagato='';
						$color='';
						$pag=controllopagreg($rowreg['0']);
						if($pag!=0){
							$color='#30c96a';
							$pagatotxt='<b style="color:'.$color.'">Pagato</b>';
							
						//	$pagato='<div  style="color:'.$color.'; font-size:20px;"> &euro;</div>';//pagato
						}else{
							$color='#f40c53';
							$pagatotxt='<b style="color:'.$color.'">Non Pagato</b>';
							//$pagato='<div style="color:'.$color.'; font-size:20px;"> &euro;</div>';
					}
					
					
					$func='onclick="selectpers('.$rowreg['0'].','.$rowreg['4'].',1,0)"';
					
					$utilizzatotxt='<b style="color:#30c96a">Utilizzabile</b>';
					if($rowreg['3']=='2'){
						$func='';  
						$utilizzatotxt='<b style="color:#f40c53">Non Utilizzabile</b>';
					}
					
					
					$testo.='
						
						  <a href="#" class="item-link item-content" style="height:55px;"  '.$func.'>
							<div class="item-inner">
							  <div class="item-title-row">
								 <div class="item-title" style="font-weight:600;">'.$rowreg['1'].'</div>
								 <div class="item-after" style="font-size:11px;color:#208c43;">'.$pagatotxt.'</div>
							  </div>
							  <div class="item-text" style="font-size:10px; color:#777;">'.$utilizzatotxt.'</div>
							</div>
						  </a>
						</li>
						
						
						
						';
					
					
					
					
				}
				$testo.='</ul></div>';
			}else{
				$testo.='<p>Nessun risultato trovato</p>';
			}
				
			
		}	
		
		
		
		
		
		
		
	break;
	case 2:
		
		//elenco cofanetti
		
		if(isset($txt[$tipo])){
			$testo.=$txt[$tipo];
		}
		$testo.='<div class="list-block media-list">
  <ul>
  ';
		$query2="SELECT c.ID,c.cofanetto,a.nome,c.persone FROM cofanetti as c,agenzie as a WHERE a.IDstr='$IDstruttura' AND a.ID=c.IDagenzia ";
		$txtsel='';
		$result2=mysqli_query($link2,$query2);
				if(mysqli_num_rows($result2)>0){
					while($row2=mysqli_fetch_row($result2)){
						$IDcof=$row2['0'];
						
						//$altattr=$IDcof.'_'.$row2['3'].'_'.$row2['1'];
						
						
						//$func='onclick="selpacchettosog('.$IDcof.','.$row2['3'].',2)"';
						
						
						$testo.='
						
						  <a href="#" class="item-link item-content" style="height:55px;"  onclick="selectpers('.$IDcof.','.$row2['3'].',2,0)">
							<div class="item-inner">
							  <div class="item-title-row">
								 <div class="item-title" style="font-weight:600;">'.$row2['1'].'</div>
								 <div class="item-after" style="color:#208c43;">€ '.$row2['3'].'</div>
							  </div>
							  <div class="item-text" style="font-size:10px; color:#777;">'.$row2['2'].'</div>
							</div>
						  </a>
						</li>
						
						
						
						';
						/*
						$txt2.='<tr '.$func.' class="popover">
						<td class="ball" ><span>
						'.contregalo($IDcof,2,$IDstruttura).'
							</span><div class="cercmini3"></div></td>
						
						<td >'.$row2['1'].'</td><td><div style="font-size:11px; margin-top:5px; color:#777;">'.$row2['2'].'</div></td><td class="price">'.$row2['3'].'</td></tr>';*/
						
					}
				}
		 $testo.='</ul></div>';
	break;
	
}






if(!isset($inc)){
	echo $testo.'<br><br>';
}


			 