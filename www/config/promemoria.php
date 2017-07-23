<?php 

if(!isset($inc)){
header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');


	$IDutente=$_SESSION['ID'];
	$IDstruttura=$_SESSION['IDstruttura'];
	
	$query="SELECT contratto FROM contratti WHERE IDstruttura='$IDstruttura' LIMIT 1";
	$result=mysqli_query($link2,$query);
	$row=mysqli_fetch_row($result);
	$contratto=$row['0'];
	$_SESSION['contratto']=$contratto;
	
	$query="SELECT IDcliente,nome FROM strutture WHERE ID='$IDstruttura' LIMIT 1";
	$result=mysqli_query($link2,$query);
	$row=mysqli_fetch_row($result);
	$IDmainuser=$row['0'];
	$nomestr=$row['1'];
	$IDpos=1;

	$query2="SELECT ID FROM personale WHERE  IDuser='$IDutente' LIMIT 1";
	$result2=mysqli_query($link2,$query2);
	$row2=mysqli_fetch_row($result2);
	$IDpers=$row2['0'];
}

	$time=time()-86400*3;
		
	$testo2='<div class="content-block-title titleb" style="color:#2424c1;">Promemoria</div><div class="timeline verticale2 " style="min-height:100px; width:98%; margin-left:0px;">';
	
	
	
		
		$data=date('Y-m-d');
		list($yy, $mm, $dd) = explode("-", $data);
		$time0=mktime(0, 0, 0, $mm, $dd, $yy);
		
		$timenow=time();
		$timef=$timenow+86400;
		$event=array();
		$event[$time0]='';
		
		$queryp="SELECT m.tipo FROM personale as p,mansioni as m,mansionipers as mp WHERE mp.IDstruttura='$IDstruttura' AND mp.mansione=m.ID AND mp.IDpers=p.ID AND p.ID='$IDpers' GROUP BY m.tipo";
		$resultp=mysqli_query($link2,$queryp);
		if(mysqli_num_rows($resultp)>0){
			while($rowp=mysqli_fetch_row($resultp)){
				$IDtipo=$rowp['0'];
				switch($IDtipo){
					case 1: //ristorazione - persone e tavoli suddivisi per fascia oraria
						$query="SELECT ID,sottotipologia FROM sottotipologie WHERE IDstr='$IDstruttura' AND IDmain='$IDtipo'";
					
						$result=mysqli_query($link2,$query);
						if(mysqli_num_rows($result)>0){
							while($row=mysqli_fetch_row($result)){
								$IDsottotip=$row['0'];
								//estrai fasce
								$query2="SELECT GROUP_CONCAT(ID SEPARATOR ','),COUNT(*),time FROM prenextra WHERE sottotip='$IDsottotip' AND IDstruttura='$IDstruttura' AND time>='$timenow' AND time<='$timef' AND modi>='0' GROUP BY FROM_UNIXTIME(time,'%G')";
								$result2=mysqli_query($link2,$query2);
								if(mysqli_num_rows($result2)>0){
									while($row2=mysqli_fetch_row($result2)){
										$IDgroup=$row2['0'];
										if(strlen($IDgroup)>0){
											$query3="SELECT COUNT(*),GROUP_CONCAT(IDinfop SEPARATOR ',') FROM prenextra2 WHERE IDprenextra IN($IDgroup) AND qta='1'";
											$result3=mysqli_query($link2,$query3);
											
											$row3=mysqli_fetch_row($result3);
											$nump=$row3['0'];
											$IDgroup=$row3['1'];
											if(!isset($event[$row2['2']])){
												$event[$row2['2']]='';
											}
											
											$notecli='';
											$query3="SELECT GROUP_CONCAT(CONCAT('<i>',s.nome,' ',s.cognome,'</i>:',s.noteristo) SEPARATOR '<br>') FROM infopren as i,schedine as s WHERE i.ID IN($IDgroup) AND i.IDcliente=s.ID AND s.noteristo!=''";
											$result3=mysqli_query($link2,$query3);
											if(mysqli_num_rows($result3)>0){
												$row3=mysqli_fetch_row($result3);
												$notecli='<br><span style="font-size:10px; color:#999;">'.$row3['0'].'</span>';
											}
											
											$event[$row2['2']].='
											
												<div class="timeline-item" style="width:100%;">
													<div class="timeline-item-date">'.date('H:i',$row2['2']).'</div>
													<div class="timeline-item-divider"></div>
													<div class="timeline-item-content" style="line-height:13px;"><div class="timeline-item-inner">N. '.$row2['1'].' '.$row['1'].' ('.$nump.' Persona/e)'.$notecli.'</div></div>
												  </div>
											';
										}
										
									}
								}
							}
						}
					
					break;
					case 4: //centro benessere - massaggi personali - numero di ingressi fissati - numero di grotte di sale fissate ecc
					

						$query="SELECT ID,sottotipologia FROM sottotipologie WHERE IDstr='$IDstruttura' AND IDmain='4'";
						$result=mysqli_query($link2,$query);
						if(mysqli_num_rows($result)>0){
							while($row=mysqli_fetch_row($result)){
								$IDsottotip=$row['0'];
								//estrai fasce
								$query2="SELECT GROUP_CONCAT(p.ID SEPARATOR ','),COUNT(DISTINCT(p.IDpren)),p.time,p.modi,s.servizio FROM prenextra as p,servizi as s WHERE p.sottotip='$IDsottotip' AND p.IDstruttura='$IDstruttura' AND p.time>='$timenow' AND p.time<='$timef' AND p.modi>='0' AND p.extra=s.ID GROUP BY FROM_UNIXTIME(p.time,'%G'),p.modi,p.extra";
								$result2=mysqli_query($link2,$query2);
								if(mysqli_num_rows($result2)>0){
									while($row2=mysqli_fetch_row($result2)){
										$IDgroup=$row2['0'];
										if(strlen($IDgroup)>0){
											$query3="SELECT IDprenextra FROM prenextra2 WHERE IDprenextra IN($IDgroup) AND qta='1'";
											$result3=mysqli_query($link2,$query3);
											$nump=mysqli_num_rows($result3);
											if(!isset($event[$row2['2']])){
												$event[$row2['2']]='';
											}
											if($row2['3']==0){
												$event[$time0].='
												
												
												<div class="timeline-item" style="width:100%;">
													<div class="timeline-item-date">--.--</div>
													<div class="timeline-item-divider"></div>
													<div class="timeline-item-content" style="line-height:13px;"><div class="timeline-item-inner">N. '.$row2['1'].' '.$row2['4'].' da confermare('.$nump.' Persona/e)</div></div>
												  </div>
												';
											}else{
												$event[$row2['2']].='
												
													<div class="timeline-item" style="width:100%;">
													<div class="timeline-item-date">'.date('H:i',$row2['2']).'</div>
													<div class="timeline-item-divider"></div>
													<div class="timeline-item-content" style="line-height:13px;"><div class="timeline-item-inner">N. '.$row2['1'].' '.$row2['4'].'('.$nump.' Persona/e)</div></div>
												  </div>
												';
											}
										}
										
									}
								}
							}
						}
					break;
					case 2:
						//massaggi e trattamenti assegnati
					
						$query2="SELECT s.servizio,p.time,p.IDpren FROM prenextra as p,servizi as s WHERE p.IDtipo='$IDtipo' AND p.IDstruttura='$IDstruttura' AND p.time>='$timenow' AND p.time<='$timef' AND s.ID=p.extra AND p.IDpers='$IDpers' AND p.modi>'0'";						$result2=mysqli_query($link2,$query2);
						if(mysqli_num_rows($result2)>0){
							while($row2=mysqli_fetch_row($result2)){
								
								if(!isset($event[$row2['1']])){
									$event[$row2['1']]='';
								}
								$event[$row2['1']].='
								
										<div class="timeline-item" style="width:100%;">
													<div class="timeline-item-date">'.date('H:i',$row2['1']).'</div>
													<div class="timeline-item-divider"></div>
													<div class="timeline-item-content" style="line-height:13px;"><div class="timeline-item-inner">N. 1 '.$row2['0'].'<br><span>'.estrainomeapp($row2['2'],1).'<span></div></div>
												  </div>
								';
								
							}
						}
						
						
						//massaggi e trattamenti in sospeso
						
						$query2="SELECT orai FROM strutture WHERE ID='$IDstruttura' LIMIT 1";
						$result2=mysqli_query($link2,$query2);
						$row2=mysqli_fetch_row($result2);
						$time=$time0+$row2['0'];
						
						$query2="SELECT COUNT(*),sottotip FROM prenextra WHERE IDtipo='$IDtipo' AND IDstruttura='$IDstruttura' AND FROM_UNIXTIME(time,'%Y-%m-%d')='$data' AND modi='0' GROUP BY sottotip";
						$result2=mysqli_query($link2,$query2);
						if(mysqli_num_rows($result2)>0){
							while($row2=mysqli_fetch_row($result2)){
								
								$query3="SELECT sottotipologia FROM sottotipologie WHERE ID='".$row2['1']."' LIMIT 1";
								$result3=mysqli_query($link2,$query3);
								$row3=mysqli_fetch_row($result3);
								$sotto=$row3['0'];
								
								if(!isset($event[$time0])){
									$event[$time0]='';
								}
								
								$event[$time0].='
									<div class="timeline-item" style="width:100%;">
													<div class="timeline-item-date">--.--</div>
													<div class="timeline-item-divider"></div>
													<div class="timeline-item-content" style="line-height:13px;"><div class="timeline-item-inner"><b style="color:#D1292C;"><u>N. '.$row2['0'].' '.$sotto.' in sospeso</u></b></div></div>
												  </div>
								';
							}
						}

					break;
					case 5: //arrivi di oggi - pulizie giornaliere
						$query="SELECT COUNT(*),time FROM prenotazioni WHERE IDstruttura='$IDstruttura' AND app!='0' AND FROM_UNIXTIME(time,'%Y-%m-%d')='$data' GROUP BY time";
						$result=mysqli_query($link2,$query);
						if(mysqli_num_rows($result)>0){
							while($row=mysqli_fetch_row($result)){
								if(!isset($event[$row['0']])){
									$event[$row['0']]='';
								}
								$event[$row['0']].='
									<div class="timeline-item" style="width:100%;">
													<div class="timeline-item-date">'.date('H:i',$row['0']).'</div>
													<div class="timeline-item-divider"></div>
													<div class="timeline-item-content" style="line-height:13px;"><div class="timeline-item-inner">N. '.$row['0'].' alloggi da riordinare<br><span style="font-size:11px; color:#999;">PER ARRIVI</span></div></div>
												  </div>
								';
							}
						}
					
					break;
					case 0: //arrivi  e partenze - Schedine alloggiati
						
						$query="SELECT COUNT(*),time FROM prenotazioni WHERE IDstruttura='$IDstruttura' AND gg>'0' AND stato>='0'  AND stato!='3' AND FROM_UNIXTIME(time,'%Y-%m-%d')='$data' GROUP BY time";
						$result=mysqli_query($link2,$query);
						if(mysqli_num_rows($result)>0){
							while($row=mysqli_fetch_row($result)){
								if(!isset($event[$row['1']])){
									$event[$row['1']]='';
								}
								$event[$row['1']].='
									<div class="timeline-item" style="width:100%;">
													<div class="timeline-item-date">'.date('H:i',$row['1']).'</div>
													<div class="timeline-item-divider"></div>
													<div class="timeline-item-content" style="line-height:13px;"><div class="timeline-item-inner">N. '.$row['0'].' CHECK-IN<br><span style="font-size:11px;">Con Alloggio</span></div></div>
												  </div>
								';
							}
						}
						
						$query="SELECT COUNT(*),time FROM prenotazioni WHERE IDstruttura='$IDstruttura' AND gg='0' AND stato>='0' AND FROM_UNIXTIME(time,'%Y-%m-%d')='$data' AND stato!='3' GROUP BY time";
						$result=mysqli_query($link2,$query);
						if(mysqli_num_rows($result)>0){
							while($row=mysqli_fetch_row($result)){
								if(!isset($event[$row['1']])){
									$event[$row['1']]='';
								}
								$event[$row['1']].='
									<div class="timeline-item" style="width:100%;">
													<div class="timeline-item-date">'.date('H:i',$row['1']).'</div>
													<div class="timeline-item-divider"></div>
													<div class="timeline-item-content" style="line-height:13px;"><div class="timeline-item-inner">N. '.$row['0'].' CHECK-IN<br><span style="font-size:11px;">Senza Soggiorno</span></div></div>
												  </div>
								';
							}
						}
						
						$query="SELECT COUNT(*),checkout FROM prenotazioni WHERE IDstruttura='$IDstruttura' AND stato>='0' AND gg>'0' AND FROM_UNIXTIME(checkout,'%Y-%m-%d')='$data' AND stato!='4' GROUP BY checkout";
						$result=mysqli_query($link2,$query);
						if(mysqli_num_rows($result)>0){
							while($row=mysqli_fetch_row($result)){
								if(!isset($event[$row['1']])){
									$event[$row['1']]='';
								}
								$event[$row['1']].='
									<div class="timeline-item" style="width:100%;">
													<div class="timeline-item-date">'.date('H:i',$row['1']).'</div>
													<div class="timeline-item-divider"></div>
													<div class="timeline-item-content" style="line-height:13px;"><div class="timeline-item-inner">N. '.$row['0'].' CHECK-OUT<br><span style="font-size:11px;">Con Alloggio</span></div></div>
												  </div>
								';
							}
						}
						
						
						$query="SELECT 1,time FROM schedine2 WHERE FROM_UNIXTIME(time,'%Y-%m-%d')='$data' AND IDstr='$IDstruttura' LIMIT 1";
						$result=mysqli_query($link2,$query);
						if(mysqli_num_rows($result)>0){
							$row=mysqli_fetch_row($result);
							if(!isset($event[$row['1']])){
									$event[$row['1']]='';
								}
								$event[$row['1']].='
									<div class="timeline-item" style="width:100%;">
													<div class="timeline-item-date">'.date('H:i',$row['1']).'</div>
													<div class="timeline-item-divider" ></div>
													<div class="timeline-item-content">
													<div class="timeline-item-inner" style="line-height:13px;">Ci sono delle SCHEDINE da comunicare alla questura</div></div>
												  </div>
								';
						}
						
						
					break;
				}
			}	
		}
		
		
		ksort($event);
		$ggini=date('d');
		
		foreach ($event as $key =>$dato){
			$gg=date('d',$key);
			if($gg!=$ggini){
				$ggini=$gg;
				if($key>9999){
					$testo2.='
						<div class="timeline-item">
													<div class="timeline-item-date"></div>
													<div class="timeline-item-divider"></div>
													<div class="timeline-item-content">
													<b>'.dataita($key).'</b></div>
												  </div>
					';
				}
			}
			$testo2.=$dato;
		}
	$testo2.='</div>';
	
	if(!isset($inc)){
		echo $testo2;	 
	}
	


?>