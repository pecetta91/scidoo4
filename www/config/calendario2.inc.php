<?php

if(!isset($inc)){
	header('Access-Control-Allow-Origin: *');
	include('../../config/connecti.php');
	include('../../config/funzioni.php');
	include('../../config/funzionilingua.php');
	$IDutente=$_SESSION['ID'];
	$IDstruttura=$_SESSION['IDstruttura'];
}

if(isset($_GET['dato0'])){
		if($_GET['dato0']!='0'){
			$time=$_GET['dato0'];
		}else{
			if(isset($_SESSION['calendar'])){
				$time=$_SESSION['calendar'];
			}else{
				$time=time();
			}
		}
	}else{
		if(isset($_SESSION['calendar'])){
			$time=$_SESSION['calendar'];
		}else{
			$time=time();
		}
	}
	$_SESSION['calendar']=$time;
	$mm=date('m',$time);
	$aa=date('Y',$time);
	
	$mmsucc=$mm+1;
	
	
	
	$timei=mktime(1,30,0,$mm,01,$aa);
	$times=$timei+33*86400;
	$timep=$timei-20*86400;
	
	$mmsucc=date('n',$times);
	$mmprec=date('n',$timep);
	$yysucc=date('Y',$times);
	$yyprec=date('Y',$timep);
	$numeromese=convert($mm);
	$num2=convert($mmsucc);
	$minim=$mesiita2[$numeromese];
	$minims=$mesiita2[$num2];
	
	$ngiornimese=date("t",$timei);
	$np=7;
	
	$timef=$timei+86400*($ngiornimese+$np);
	$timefm=$timei+86400*$ngiornimese;
	
	$mesipre=10;
	
	$tempo1=mktime(0,0,0,$mm,15,$aa);
	
	$scroll=86400*30;
//navigationtxt(3,'.$times.','."'calendariodiv'".',0) // onclick="navigationtxt(3,'.$timep.','."'calendariodiv'".',0)"
			  echo '
			  <input type="hidden" id="datacal" value="'.$timei.'">
			  <table width="90%" align="center" style="margin-top:-15px;">
			 <tr>
			 <td><a href="#" class="button button-fill " onclick="navigation(2,'.$timep.',0,1)" ><i class="material-icons">arrow_back</i></a></td>
			 <td align="center" width="75%">'.$mesiita[$numeromese].' '.$aa.'</td>
			  <td><a href="#" class="button button-fill " onclick="navigation(2,'.$times.',0,1)"><i class="material-icons">arrow_forward</i></a></td>
			  </tr></table><br>';
$txtmain='';
$txtbody='';
$txtbody2='';
			  
			
$visorari=0;
$vissett=0;			
			  
$IDcat=0;
if(isset($_SESSION['appfilter'])){
	$IDcat=$_SESSION['appfilter'];
}

$cella=44;


$colspan=5;
if($_SESSION['contratto']>='3'){
	$colspan=6;
}
$txtmain.='
<tr><td>';

$txtmain.='</td></tr>';



$contid=1;
$ngiornosettimana=date("w",$timei);
$oggi=0;
$meseo=date('m');
if($mm==$meseo){
	$oggi=date("d");
}
if($mm==($meseo-1)){
	$oggi=date("d")+$ngiornimese;
}

//note 
$nota=array();
$query2="SELECT FROM_UNIXTIME(time,'%e'),COUNT(*),time FROM note WHERE time>='$timei' AND time<='$timef' AND IDstruttura='$IDstruttura' AND (titolo!='' OR descr!='') GROUP BY FROM_UNIXTIME(time,'%j')";
$result2=mysqli_query($link2,$query2);
if(mysqli_num_rows($result2)>0){
	while($row=mysqli_fetch_row($result2)){
		$jj=$row['0'];
		if($row['2']>=$timefm){
			$jj=$jj+$ngiornimese;
		}
		$nota[$jj]=$row['1'];
	}
}

//esclusivi
$esc=array();
$query2="SELECT  FROM_UNIXTIME(p.time,'%e'),COUNT(*),p.IDpren,p.time FROM prenextra as p,servizi as s WHERE p.time>='$timei' AND p.time<='$timef' AND p.IDstruttura='$IDstruttura' AND p.extra=s.ID AND s.esclusivo='1' AND modi>='0' GROUP BY FROM_UNIXTIME(p.time,'%j') ORDER BY p.time";
$result2=mysqli_query($link2,$query2);
if(mysqli_num_rows($result2)>0){
	while($row=mysqli_fetch_row($result2)){
		$jj=$row['0'];
		if($row['3']>=$timefm){
			$jj=$jj+$ngiornimese;
		}
		$esc[$jj]=$row['1'];
	}
}
$nn=$ngiornimese+$np;

$txtbody2='<tr>';
for($i=1;$i<=$nn;++$i){
	$mes=$minim;
	if($i>$ngiornimese){$j=$i-$ngiornimese;$mes=$minims;}else{$j=$i;}
	$datecalendario=$giorniita3[$ngiornosettimana].'<br><b>'.$j.'</b><br><span>'.$mes.'</span>';
	//$datecalendario='<b>'.$j.'</b><br><span>'.$giorniita3[$ngiornosettimana].'</span>';
	++$ngiornosettimana;	
	if($ngiornosettimana==7)$ngiornosettimana=0;
	$cl="";
	if(($ngiornosettimana==1)||(($ngiornosettimana)==0))$cl='dom';
	if($i==$oggi)$cl='ogg';
	$txtbody2.='<td class="datacal '.$cl.'">'.$datecalendario.'</td>';
}
$txtbody2.='</tr>';

$txtmain.='<tr><td style=" background:#BC3B3D; color:#fff;">Note ed<br>Esclusivi</td></tr> ';
$txtbody.='<tr>';
$tempo2=$timei-86400;
for($i=1;$i<=$nn;++$i){
	//$tempo=$tempo2+86400*$i;
	$txt='';
	$classn='';
	
	if(isset($nota[$i])){$txt.='Note:'.$nota[$i].'<br>';$classn='class="solonota"';}
	if(isset($esc[$i])){$txt.='Esc:'.$esc[$i].'<br>'; $classn='class="noteesc"';}
	if($txt==''){
		$txtbody.='<td></td>';
	}else{
		$txtbody.='<td '.$classn.' alt="'.$i.'">'.$txt.'</td>';
	}
}
$txtbody.='</tr>';
	
  //seleziona gli appartamenti
  $strfilt="";
  if($IDcat!=0){
	  $strfilt="AND A.categoria='$IDcat'";
  }
$query="SELECT A.ID,A.nome,A.attivo,A.temp,A.categoria,C.colore,A.stato,A.statod FROM appartamenti as A,categorie AS C WHERE A.IDstruttura='$IDstruttura' $strfilt AND A.categoria=C.ID  ORDER BY  A.attivo DESC,A.categoria";
$result=mysqli_query($link2,$query);



$arrcolor=array('11760c','ff204a','000');

while($row=mysqli_fetch_row($result)){  //$row contine la query degli appartamenti  
  if($row['2']!='2'){
	  
	  // style="border-left:solid 5px #'.$row['5'].';"
	  $txtmain.='<tr><td>'.wordwrap($row['1'],20,'<br>').'</td></tr> ';
	  /*
	if($colspan==6){
		$testo.='<td ';
		if($row['3']!=0){
			$testo.='onclick="apri(8,'.$row['0'].')" style="color:#'.$arrcolor[$row['7']].';font-size:10px;" >'.$row['3'];
	  	}else{
			$testo.='>';
	  	}
	  	$testo.='</td>';
	}*/
	
	$txtbody.='<tr>';
  $nomeapp=$row['1'];
  $IDapp=$row['0'];
  $j=0;
  $z=0;
	$timepren=$timei-86400;
	 $min=0;
	//$query2="SELECT  FROM_UNIXTIME(time,'%e'),IDv,gg,time,ID,stato,checkout FROM prenotazioni WHERE time>='$timei' AND time<='$timef' AND app = '$IDapp' AND IDstruttura='$IDstruttura'  UNION SELECT  FROM_UNIXTIME(time,'%e'),IDv,gg,time,ID,stato,checkout FROM prenotazioni WHERE time<'$timei' AND checkout>'$timei' AND app = '$IDapp' AND IDstruttura='$IDstruttura' ORDER BY time";
	
	$query2="SELECT  FROM_UNIXTIME(p.time,'%e'),p.IDv,p.gg,p.time,p.ID,s.classecal,p.checkout FROM prenotazioni as p,statopren as s WHERE ((p.time>='$timei' AND p.time<='$timef') OR (p.time<'$timei' AND p.checkout>'$timei')) AND p.app = '$IDapp' AND p.IDstruttura='$IDstruttura' AND p.stato=s.IDstato  ORDER BY p.time";
	
	
	$result2=mysqli_query($link2,$query2);
	if(mysqli_num_rows($result2)>0){
		$prenotazioni=mysqli_fetch_row($result2);
		$ggpren=$prenotazioni['0'];
		$timep=$prenotazioni['3'];
		$notti=$prenotazioni['2'];
		$out=$prenotazioni['6'];
		
		if($timep>$timefm){
			$ggpren=$ggpren+$ngiornimese;
		}
		if($timep<$timei){
			$ggpren=1;
			$less=ceil(($timei-$timep)/86400);
			$notti=$notti-$less;
			
			if($notti==0){
				$prenotazioni=mysqli_fetch_row($result2);
				$ggpren=$prenotazioni['0'];
				$timep=$prenotazioni['3'];
				$notti=$prenotazioni['2'];
				if($timep>$timefm){
					$ggpren=$ggpren+$ngiornimese;
				}
			}else{
				$min=1;
			}
		}
		if($out>$timef){
			$notti=ceil(($timef-$timep)/86400);
		}
	}else{
		$ggpren=0;
		$timep=0;
	}
	
	$ngiornimese2=$ngiornimese+$np; 
    
	for($i=1;$i<=$ngiornimese2;++$i){  //ripete il ciclo per il numero di volte quali il numero dei giorni di quel mese	
		
		
		
		
		if($i!=$ggpren){
			
			//onclick="aggiungip('.$timegiorno.','.$IDapp.',1);"
			$classover='';
			//if($row['2']=='-1')$classover='redback';
			
			
			$txtbody.='<td id="'.$i.'_'.$IDapp.'_1"   class="new '.$classover.'"></td>'; 
		}else{
			
			if(($visorari==0)&&($notti>=1)){
				$class=$prenotazioni['5'];
			}else{
				$class='redback2';
			}
			//'.$class.'
			$txtbody.='<td   id="'.$i.'_'.$IDapp.'_1" class="new '.$class.'">';
			
			$max=0;		
			if($notti>($ngiornimese2-$ggpren)){
				$notti=$ngiornimese2-$ggpren+1;
				$max=1;
			}
	
					
					
					
			if($notti>=1){	
			//estrainome($prenotazioni['1'])
			
				if($visorari==1){
					$oarr=date('G',$timep);
					$oout=date('G',$out);
					
					$appo1=$min;
					$appo2=$max;
					if($min==1){
						$oarr=0;
						$min=0;
					}
					if($max==1){
						$oout=0;
						$max=0;
					}
					
					if(($oout>$oarr)&&($appo1==0))$oout+=24;
					$left=round(($cella/24)*$oarr);
					$out2=round(($cella*$notti)-(($oarr-$oout)*($cella/24)));
					
					if($notti>1){$out2+=(($notti-1)*5);}
						
					$style='margin-left:'.$left.'px;';

				}else{
					$style='';
					$out2=$cella*$notti;
					if($notti>1){$out2+=(($notti-1)*5);}//funzione perfetta
				}
	
	
				$txtbody.='<div class="divcal ppp '.$prenotazioni['5'].'" label="'.$prenotazioni['1'].'" id="cont'.$prenotazioni['1'].'" style="width:'.$out2.'px; '.$style.'">
							<div>'.estrainome($prenotazioni['1']).'</div>
							</div>';
							
			}else{
				if($visorari==1){
					$oarr=date('G',$timep);
					$oout=date('G',$out);
					
					$left=round(($cella/24)*$oarr);
					$out2=($oout-$oarr)*($cella/24);
					$style=' margin-left:'.$left.'px;';
				}else{
					$style='';
					
					//if($notti>1){$out2+=(($notti-1)*5);}
					
					$out2=$cella*$notti;
				}
				
				//$wid=($cella*$notti);
				$txtbody.='<div class="divcal ppp '.$prenotazioni['5'].'  " label="'.$prenotazioni['1'].'" id="cont'.$prenotazioni['1'].'" style="width:'.$out2.'px; '.$style.'">
							<div>'.estrainome($prenotazioni['1']).'</div>
							</div>';
				$notti=1;
			}
			
			$nottibef=$notti;
						
			$i=$i+$notti-1;
			$prenotazioni=mysqli_fetch_row($result2);
			$ggpren=$prenotazioni['0'];
			$timep=$prenotazioni['3'];
			$notti=$prenotazioni['2'];
			$out=$prenotazioni['6'];
			
			if($ggpren==$i){
				
				$out2bef=$out2;
				if($notti>($ngiornimese2-$ggpren)){
					$notti=$ngiornimese2-$ggpren+1;
				}
					
				if($notti>=1){	
					
					if($visorari==1){
						$oarr=date('G',$timep);
						$oout=date('G',$out);
						
						$left=round(($cella/24)*$oarr)-6;
						$out2=round($cella*($notti)-(($oarr-$oout)*($cella/24)))-5;
						$style=' margin-left:'.$left.'px"';
					}else{
						$style=' margin-left:'.($out2bef+3).'px;';
						$out2=$cella*$notti-$out2bef-4;
						if($notti>1){$out2+=(($notti-1)*5);}
						
					}
				
					$txtbody.='<div class="divcal ppp '.$prenotazioni['5'].'  " label="'.$prenotazioni['1'].'" id="cont'.$prenotazioni['1'].'" style="width:'.$out2.'px; border-left:solid 1px #fff; '.$style.'">	
								<div >'.estrainome($prenotazioni['1']).'</div>
															
								</div>
								
				';
				}else{
					/*
					if($visorari==1){	
						$oarr=date('G',$timep);
						$oout=date('G',$out);
					
						$left=round((($cella*$notti)/24)*$oarr);
						$out2=round(($oout-$oarr)*($cella/24));
						$style='margin-left:'.$left.'px;';
					}else{
						$style='';
						$out2=$cella*$notti-7;
					}
					
					$txtbody.='
								<div class="divcal ppp '.$prenotazioni['5'].'  " label="'.$prenotazioni['1'].'" id="cont'.$prenotazioni['1'].'" style="width:'.$out2.'px; '.$style.'">
								<div style="overflow:hidden;">'.estrainome($prenotazioni['1']).'</div>
								</div>
							
				';
					$notti=1;*/
				}
					
				$prenotazioni=mysqli_fetch_row($result2);
				$ggpren=$prenotazioni['0'];
				$timep=$prenotazioni['3'];
				$notti=$prenotazioni['2'];
				$out=$prenotazioni['6'];
			}
			
			
			if($timep>=$timefm){
				$ggpren=$ggpren+$ngiornimese;
			}
			if($out>$timef){
				$notti=ceil(($timef-$timep)/86400);
			}
			
			$txtbody.='</td>';
			for($k=1;$k<$nottibef;$k++){
				//
				$txtbody.='<td id="'.($i+$k).'_'.$IDapp.'_1" class="new '.$class.'"></td>';
			}
		}
		
		
		
		
		
		
		
		
		
		/*
		
		
		
		
        if($i!=$ggpren){
			$timegiorno=$i*86400+$timepren;
			$txtbody.='<td class="addpren" alt="'.$timegiorno.'" lang="'.$IDapp.'"></td>'; 
		}else{
			
			
			if($notti>($ngiornimese2-$ggpren)){
				$notti=$ngiornimese2-$ggpren+1;
			}
			//label="'.$prenotazioni['1'].'"
			$txtbody.='<td class="ppren '.$prenotazioni['5'].'" alt="'.$prenotazioni['1'].'" style=" width:'.(46*$notti+($notti-1)*3).'px;" alt="'.$prenotazioni['1'].'">'.wordwrap(estrainome($prenotazioni['1']),($notti*10),'<br>').'</td>';
						
			$i=$i+$notti-1;
			$prenotazioni=mysqli_fetch_row($result2);
			$ggpren=$prenotazioni['0'];
			$timep=$prenotazioni['3'];
			$notti=$prenotazioni['2'];
			$out=$prenotazioni['6'];
			if($timep>=$timefm){
				$ggpren=$ggpren+$ngiornimese;
			}
			if($out>$timef){
				$notti=ceil(($timef-$timep)/86400);
			}
			
	
		} //chiusura else*/
		
		
			
	  }//chiusura for
	  
	  $txtbody.='</tr>';
  }else{//chiusura fi attivo
  
  $txtmain.='<tr><td  class="senzas">'.wordwrap($row['1'],10,'<br>').'</td><tr>';
  
  $nomeapp=$row['1'];
  $IDapp=$row['0'];
   
   $query2="SELECT FROM_UNIXTIME(time,'%e'),COUNT(*),time,IDv FROM prenotazioni WHERE IDstruttura ='$IDstruttura' AND time>'$timei' AND checkout<'$timef' AND app = '$IDapp' GROUP BY FROM_UNIXTIME(time,'%d/%m') ORDER BY time";
   
  $result2=mysqli_query($link2,$query2);
  if(mysqli_num_rows($result2)>0){
	  $prenotazioni=mysqli_fetch_row($result2);
	  $ggpren=$prenotazioni['0'];
	  $nump=$prenotazioni['1'];
	  $timep=$prenotazioni['2'];
	  $IDv=$prenotazioni['3'];
	  if($timep>$timefm){
		$ggpren=$ggpren+$ngiornimese;
	  }
  }else{
	$ggpren=0;
	$nump=0;
	$timep=0; 
  }
  
  
  
  
  $ngiornimese2=$ngiornimese+$np;
  //$timei=$timei-86400;
//$timei2=$timei-86400;
	
	$txtbody.='<tr>';
	
 for($i=1;$i<=$ngiornimese2;$i++){
	//$timegg=$timei2+86400*$i;
	//echo $timegg.'-'.date('d/m',$timegg).'<br>';
	if($i==$ggpren){
	  $txtbody.='<td class="nosoggcal" alt="'.$i.'" >'.$nump.'</td>';
	  $prenotazioni=mysqli_fetch_row($result2);
	  $ggpren=$prenotazioni['0'];
	  $nump=$prenotazioni['1'];
	  $timep=$prenotazioni['2'];
	  $IDv=$prenotazioni['3'];

	  if($timep>$timefm){
		$ggpren=$ggpren+$ngiornimese;
	  }
	}else{
		$txtbody.='<td class="new" id="'.$i.'_'.$IDapp.'_1" ></td>';
	}
  }
  	$txtbody.="</tr>";
  }//chiusura else attivo
  
  }//chiusura while
  
  $txtmain.='<tr><td colspan="5" style="background:#ce3f18; color:#fff;">Annullate</td>';
  
  
  $query="SELECT FROM_UNIXTIME(time,'%e'),COUNT(*),time,IDv FROM prenotazioni WHERE time>'$timei' AND checkout<'$timef' AND app = '0' AND IDstruttura='$IDstruttura' GROUP BY FROM_UNIXTIME(time,'%e') ORDER BY time";
  $result=mysqli_query($link2,$query);
  if(mysqli_num_rows($result)>0){
	  $prenotazioni=mysqli_fetch_row($result);
	  $ggpren=$prenotazioni['0'];
	  $nump=$prenotazioni['1'];
	  $timep=$prenotazioni['2'];
	  $IDv=$prenotazioni['3'];
	  if($timep>$timefm){
		$ggpren=$ggpren+$ngiornimese;
	  }
  }else{
	$ggpren=0;
	$nump=0;
	$timep=0; 
  }
  
  
  $txtbody.='<tr>';
  for($i=1;$i<=$ngiornimese2;++$i){
	if($i==$ggpren){
	 // $ggd=date('d',$row2['1']);
	 //label="0"
	  $txtbody.='<td class="annullata" alt="'.$i.'" >'.$nump.'</td>';
	  $prenotazioni=mysqli_fetch_row($result);
	  $ggpren=$prenotazioni['0'];
	  $nump=$prenotazioni['1'];
	  $timep=$prenotazioni['2'];
	  $IDv=$prenotazioni['3'];
	  if($timep>$timefm){
		$ggpren=$ggpren+$ngiornimese;
	  }
	  
	}else{
		$txtbody.='<td></td>';
	}
 }
  	$txtbody.="</tr>";

//echo $testo."</table>';<i>M.</i>

echo '
<div class="table-fixed-left" id="tabappart" style="z-index:101;">
  <table>
    '.$txtmain.'
  </table>
</div>
<div class="table-fixed-right"  id="tabcalmain" onscroll="scrollrig()" >
 <div id="tabdate" style="z-index:100;">
  <table>
     '.$txtbody2.'
  </table></div>
  <table id="tabbody">
     '.$txtbody.'
  </table>
</div>

</div>
</div>
';


?>