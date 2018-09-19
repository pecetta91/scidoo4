<?php

if(!isset($inc)){
	header('Access-Control-Allow-Origin: *');
	include('../../config/connecti.php');
	include('../../config/funzioni.php');
	include('../../config/funzionilingua.php');
	$IDutente=$_SESSION['ID'];
	$IDstruttura=$_SESSION['IDstruttura'];
}

//$inizio = round(microtime(), 3);


	if(isset($_GET['dato0'])){
		if((is_numeric($_GET['dato0']))&&($_GET['dato0']!='0')){
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
	
	//$mmsucc=date('n',$times);
	//$mmprec=date('n',$timep);
	//$yysucc=date('Y',$times);
	//$yyprec=date('Y',$timep);
	$numeromese=convert($mm);
	//$num2=convert($mmsucc);
	$minim=$mesiita2[$numeromese];
	$minims=$mesiita2[$num2];
	
	$ngiornimese=date("t",$timei);
	$np=7;
	
	$timef=$timei+86400*($ngiornimese+$np);
	$timefm=$timei+86400*$ngiornimese;
	
	$mesipre=10;
	
	$tempo1=mktime(0,0,0,$mm,15,$aa);
	
	$scroll=86400*30;
			  /*echo '
			  <input type="hidden" id="meseattuale" value="'.$timei.'">

	<div id="dataattuale" style="display:none;" ><span  style="display:block;font-weight:600;font-size:13px;width:65px;max-width:65px;">'.$mesiita[$numeromese].'</span> <br/> <span style="font-size:10px">'.$aa.'</span></div>
			  
			  <div id="dataprox" style="display:none;"><span  style="display:block;font-weight:600;font-size:13px;width:65px;max-width:65px;">'.$mesiita[$num2].'</span> <br/> <span style="font-size:10px">'.date('Y',$timep).'</span></div>
			 
			  <input type="hidden" id="dataavanti" value="'.$times.'">
			  <input type="hidden" id="datadietro" value="'.$timep.'">
			 ';*/
echo ' 
<div id="dataattuale"><span class="meseatt">'.$mesiita[$numeromese].'</span><br/><span style="font-size:10px">'.$aa.'</span></div>

<input type="hidden" id="meseattuale" value="'.$timei.'">
			   <input type="hidden" id="dataavanti" value="'.$times.'">
			  <input type="hidden" id="datadietro" value="'.$timep.'">';

$txtmain='';
$txtbody='';
$txtbody2='';
			  
			
$visorari=0;
$vissett=0;			
			  
$IDcat=0;
if(isset($_SESSION['appfilter'])){
	$IDcat=$_SESSION['appfilter'];
}

//$cella=41;
$cella=50;

$colspan=5;
if($_SESSION['contratto']>='3'){
	$colspan=6;
}
$txtmain.='<tr><td style="height:54px; background: #f9f8f9;border:none !important;"></td></tr>';





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


$lastday=$ngiornimese+1;
$txtbody2='<tr>';
for($i=1;$i<=$nn;++$i){
	$cl='';
	$mes=$minim;
	if($i>$ngiornimese){$j=$i-$ngiornimese;$mes=$minims;}else{$j=$i;}
	$datecalendario='<b>'.$j.'</b><br/>'.$giorniita3[$ngiornosettimana];
	
	if($i==$lastday)$cl.='giornofin ';
	
	//$datecalendario='<b>'.$j.'</b><br><span>'.$giorniita3[$ngiornosettimana].'</span>';
	++$ngiornosettimana;	
	if($ngiornosettimana==7)$ngiornosettimana=0;
	
	if(($ngiornosettimana==1)||(($ngiornosettimana)==0))$cl.='dom ';
	if($i==$oggi)$cl.='ogg';
	$txtbody2.='<td class="datacal '.$cl.'">'.$datecalendario.'</td>';
}
$txtbody2.='</tr>';

$txtmain.='<tr><th class="tdesclusivi">Note ed<br>Esclusivi</th></tr> ';
$txtbody.='<tr>';
$tempo2=$timei-86400;
for($i=1;$i<=$nn;++$i){
	//$tempo=$tempo2+86400*$i;
	$txt='';
	$classn='';
	
	if(isset($nota[$i])){$txt.=$nota[$i].' <span>Note</span><hr>';$classn='class="solonota notaes"';}
	if(isset($esc[$i])){$txt.=$esc[$i].' <span>Esclus.</span>'; $classn='class="noteesc notaes"';}
	if($txt==''){
		$txtbody.='<td></td>';
	}else{
		$txtbody.='<td><div '.$classn.'  alt="'.$i.'">'.$txt.'</div></td>';
	}
}
$txtbody.='</tr>';
	



$datai2=date('Y-m-d',$timei);
$dataf2=date('Y-m-d',$timef);
$query="SELECT  DATE_FORMAT(data,'%e'),IDalloggio,DATE_FORMAT(data,'%m') FROM chiusuraalloggi WHERE IDstr='$IDstruttura' AND data BETWEEN '$datai2' AND '$dataf2' ";
$result=mysqli_query($link2,$query);
$arrclose=array(array());
if(mysqli_num_rows($result)>0){
	while($row=mysqli_fetch_row($result)){
	
		$ggclose=$row['0'];
		if($row['2']!=$mm){
			$ggclose+=$ngiornimese;
		}
		$arrclose[$row['1']][$ggclose]=1;
	}
}







$arrpren=array(array());
/*
$query2="SELECT  FROM_UNIXTIME(p.time,'%e'),p.IDv,p.gg,p.time,s.classecal,p.checkout,p.app FROM prenotazioni as p,statopren as s WHERE ((p.time>='$timei' AND p.time<='$timef') OR (p.time<'$timei' AND p.checkout>'$timei')) AND p.IDstruttura='$IDstruttura' AND p.stato=s.IDstato AND p.gg>'0' ";
$result2=mysqli_query($link2,$query2);
if(mysqli_num_rows($result2)>0){
	while($row2=mysqli_fetch_row($result2)){
		$ggpren=$row2['0'];
		if($row2['3']>$timefm){
			$ggpren=$ggpren+$ngiornimese;
		}
		$notti=$row2['2'];
		
		if($row2['5']>$timef){
			$notti=ceil(($timef-$row2['3'])/86400);
		}
		$na=0;
		
		if(isset($arrpren[$row2['6']][$ggpren][$na])){
			$na++;
		}
		
		$arrpren[$row2['6']][$ggpren][$na][0]=$row2['1'];//IDv
		$arrpren[$row2['6']][$ggpren][$na][1]=$notti;//gg
		$arrpren[$row2['6']][$ggpren][$na][2]=$row2['3'];//time
		//$arrpren[$row2['7']][$ggpren][$na][3]=$row2['4'];//ID
		$arrpren[$row2['6']][$ggpren][$na][4]=$row2['4'];//classecal
		$arrpren[$row2['6']][$ggpren][$na][5]=$row2['5'];//checkout
	}
}
*/


$query2="SELECT  FROM_UNIXTIME(p.time,'%e'),p.IDv,p.gg,p.time,s.classecal,p.checkout,p.app FROM prenotazioni as p,statopren as s WHERE ((p.time>='$timei' AND p.time<='$timef') OR (p.time<'$timei' AND p.checkout>'$timei')) AND FROM_UNIXTIME(p.checkout,'%Y-%m-%d')!='$datai2'  AND p.IDstruttura='$IDstruttura' AND p.stato=s.IDstato  AND p.gg>'0'";
$result2=mysqli_query($link2,$query2);
if(mysqli_num_rows($result2)>0){
	while($row2=mysqli_fetch_row($result2)){
		$ggpren=$row2['0'];
		$notti=$row2['2'];
		
		if($row2['3']>$timefm){
			$ggpren=$ggpren+$ngiornimese;
		}else{
			if($row2['3']<$timei){
				$ggpren=1;
				$notti=floor(($row2['5']-$timei)/86400);
			}
		}
		
		if($row2['5']>$timef){
			$notti=ceil(($timef-$row2['3'])/86400);
		}
		$na=0;
		
		if(isset($arrpren[$row2['6']][$ggpren][$na])){
			$na++;
		}
		
		$arrpren[$row2['6']][$ggpren][$na][0]=$row2['1'];//IDv
		$arrpren[$row2['6']][$ggpren][$na][1]=$notti;//gg
		$arrpren[$row2['6']][$ggpren][$na][2]=$row2['3'];//time
		//$arrpren[$row2['7']][$ggpren][$na][3]=$row2['4'];//ID
		$arrpren[$row2['6']][$ggpren][$na][4]=$row2['4'];//classecal
		$arrpren[$row2['6']][$ggpren][$na][5]=$row2['5'];//checkout
		
		
	}
}









  //seleziona gli appartamenti
  $strfilt="";
  if($IDcat!=0){
	  $strfilt="AND A.categoria='$IDcat'";
  }
$query="SELECT A.ID,A.nome,A.attivo,A.temp,A.categoria,C.colore,A.stato,A.statod,C.nome FROM appartamenti as A,categorie AS C WHERE A.IDstruttura='$IDstruttura' $strfilt AND A.categoria=C.ID  ORDER BY  A.attivo DESC,A.categoria";
$result=mysqli_query($link2,$query);



$arrcolor=array('11760c','ff204a','000');
$ngiornimese2=$ngiornimese+$np; 

while($row=mysqli_fetch_row($result)){  //$row contine la query degli appartamenti  
  if($row['2']!='2'){
	  
	  // style="border-left:solid 5px #'.$row['5'].';"
	  $txtmain.='<tr><th>'.$row['1'].'<br><span>'.$row['8'].'</span></th></tr> ';
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
	
	  
	  /*
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
	*/
	  
	 $ggpren=0;
		$timep=0; 
	  
	
    
	for($i=1;$i<=$ngiornimese2;++$i){  //ripete il ciclo per il numero di volte quali il numero dei giorni di quel mese	
		
		
		
		
		 if(!isset($arrpren[$IDapp][$i])){
		
			 $classover='';
			$txtbody.='<td id="'.$i.'_'.$IDapp.'_1"   class="new '.$classover.'"></td>'; 
		
		 }else{
			
			 
			 
			$ggpren=$i;
			$timep=$arrpren[$IDapp][$i][0]['2'];
			$notti=$arrpren[$IDapp][$i][0]['1'];
			$out=$arrpren[$IDapp][$i][0]['5'];
			$classpren=$arrpren[$IDapp][$i][0]['4'];
			$IDprenv=$arrpren[$IDapp][$i][0]['0'];
			$nottibef=$notti;
			 
			if(($visorari==0)&&($notti>=1)){
				$class=$classpren;
			}else{
				$class='redback2';
			}
			//'.$class.'
			$txtbody.='<td  id="'.$i.'_'.$IDapp.'_1" class="new ">';
			
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
	
				$out2-=2;
				$txtbody.='<div class="divcal ppp '.$classpren.'" label="'.$IDprenv.'" id="cont'.$IDprenv.'" style="width:'.$out2.'px; '.$style.'">
							<div>'.estrainome($IDprenv).'</div>
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
				$out2-=2;
				
				$txtbody.='<div class="divcal ppp '.$classpren.'  " label="'.$IDprenv.'" id="cont'.$IDprenv.'" style="width:'.$out2.'px; '.$style.'">
							<div>'.estrainome($IDprenv).'</div>
							</div>';
				$notti=1;
			}
			
			
						
			$i=$i+$notti-1;
			 
			 
			 
			 if(isset($arrpren[$IDapp][$ggpren][1])){
				
				
				
				$timep=$arrpren[$IDapp][$ggpren][1]['2'];
				$notti=$arrpren[$IDapp][$ggpren][1]['1'];
				$out=$arrpren[$IDapp][$ggpren][1]['5'];
				$classpren=$arrpren[$IDapp][$ggpren][1]['4'];
				$IDprenv=$arrpren[$IDapp][$ggpren][1]['0'];
			 
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

						$out2-=2;
						$txtbody.='<div class="divcal ppp '.$classpren.'  " label="'.$IDprenv.'" id="cont'.$IDprenv.'" style="width:'.$out2.'px; border-left:solid 1px #fff;'.$style.'">	
									<div>'.estrainome($IDprenv).'</div>

									</div>

					';
					}
						/*
					$prenotazioni=mysqli_fetch_row($result2);
					$ggpren=$prenotazioni['0'];
					$timep=$prenotazioni['3'];
					$notti=$prenotazioni['2'];
					$out=$prenotazioni['6'];*/
				}
			}	 
				 
			//echo 'aaa'.$nottibef.'bbb';	 
			$txtbody.='</td>';
			for($k=1;$k<$nottibef;$k++){
				//class="new '.$class.'"
				$txtbody.='<td id="'.($i+$k).'_'.$IDapp.'_1" ></td>';
			}
			
			if($timep>=$timefm){
				$ggpren=$ggpren+$ngiornimese;
			}
			if($out>$timef){
				$notti=ceil(($timef-$timep)/86400);
			}
			$nottibef=$notti;
		
			
		
		
		
		
		
		
		
		
		
		
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
		
		
			
	  	}
	}//chiusura for
	  $txtbody.='</tr>';
  }else{//chiusura fi attivo
  
  $txtmain.='<tr><th  class="senzas">'.wordwrap($row['1'],10,'<br>').'</th><tr>';
  
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
	  $txtbody.='<td class="nosoggcal" alt="'.$i.'" ><div class="senzasdiv">'.$nump.'</div></td>';
	  $prenotazioni=mysqli_fetch_row($result2);
	  $ggpren=$prenotazioni['0'];
	  $nump=$prenotazioni['1'];
	  $timep=$prenotazioni['2'];
	  $IDv=$prenotazioni['3'];

	  if($timep>$timefm){
		$ggpren=$ggpren+$ngiornimese;
	  }
	}else{
		$txtbody.='<td class="new" id="'.$i.'_'.$IDapp.'_0" ></td>';
	}
  }
  	$txtbody.="</tr>";
  }//chiusura else attivo
  
  }//chiusura while
  
  $txtmain.='<tr><th colspan="5" class="tdannullate">Annullate</th>';
  
  
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


//$fine = round(microtime(), 3);

//$t=$fine-$inizio;


//0,11 -> 0.18
$prec=46;
$prec=55;
echo '
<div class="divaltosx"></div>

 <div id="tabdate" style="width:auto;z-index:100;">
  <table class="tabledate" style="width:'.($prec*$ngiornimese2).'px;">
     '.$txtbody2.'
  </table>
 </div>
 
<div class="table-fixed-left" id="tabappart">
  <table>
    '.$txtmain.'
  </table>
</div>


<div class="table-fixed-right"  id="tabcalmain" onscroll="scrollrig();"   style="z-index:99;" >
	  <table id="tabbody" style="margin-top:53px; z-index:100 ;    margin-left: 25px;">
		 '.$txtbody.'
	  </table>
</div>





';

//onscroll="scrollrig();"


?>
