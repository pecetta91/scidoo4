<?php 
header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');

$IDutente=$_SESSION['ID'];
$IDstruttura=$_SESSION['IDstruttura'];


$IDpren=$_SESSION['IDprenfunc'];

$IDserv=$_GET['ID'];
$tipolim=$_GET['tipolim'];


$data=date('Y-m-d');

list($yy, $mm, $dd) = explode("-", $data);
$timeoggi=mktime(0, 0, 0, $mm, $dd, $yy);

if(isset($_GET['time'])){
	if(is_numeric($_GET['time'])&&($_GET['time']>0)){
		$timeoggi=$_GET['time'];
		//$data=date('Y-m-d',$_GET['time']);
	}
}



unset($_SESSION['IDsalaadd']);
unset($_SESSION['timeadd']);
unset($_SESSION['IDpersadd']);
	
	switch($tipolim){
		case 7:
			$IDtipo=0;
			$IDsotto=0;
			$esclusivo=0;
			$content='';
			$query="SELECT persone,codiceidea FROM ideeregalosold WHERE ID='$IDserv' AND IDstruttura='$IDstruttura'";
			$result=mysqli_query($link2,$query);
			$row=mysqli_fetch_row($result);
			$codiceid=$row['1'];
			$persone=$row['0'];
		
			$query="SELECT c.IDservcont,COUNT(*) FROM composizionireg as c,servizi as s WHERE c.IDreg='$IDserv' AND c.IDstr='$IDstruttura' AND c.IDservcont=s.ID AND s.IDtipo!='9' GROUP BY c.IDservcont";
		$result=mysqli_query($link2,$query);
		
			$content='<hr><table class="wrapper3" style="table-layout:auto; width:70%;">
			<tr><tr><th style="height:35px;">Voucher</th><th>Contenuto</th><th>Persone</th></th>
			<tr><td style="width:170px;">'.$codiceid.'</td><td class="serv"> ';
			$c=0;
			$c2=0;
			while($row5=mysqli_fetch_row($result)){
				$serv=$row5['0'];
				$query2="SELECT s.servizio,t.tipolimite,t.tipo FROM servizi as s,tiposervizio as t WHERE s.ID='$serv' AND s.IDstruttura='$IDstruttura' AND s.IDtipo=t.ID";
				$result2=mysqli_query($link2,$query2);
				$row2=mysqli_fetch_row($result2);
				$servizio=$row2['0'];
				$tipoliminto=$row2['1'];
				
				if($tipoliminto=='4'){
					$pern=$row2['2'];
					$content.='N.'.$row5['1'].' '.$pern;
				}else{
					$content.='N.'.$row5['1'].' '.$servizio;
				}
				if($c2==2){$content.='<br>';
				}else{
					$content.=',  ';
				}
				
				$c++;	$c2++;	
			}
			$content=substr($content, 0, strlen($content)-2);
		
			$content.='</td><td style="width:150px;">Per <span id="npers">'.$persone.'</span> persone</td></tr></table><hr>';
			echo $content;
		
		
		break;
		case 8:
			
			$IDtipo=0;
			$IDsotto=0;
			$esclusivo=0;
			$content='';
			$query="SELECT persone,cofanetto FROM cofanetti WHERE ID='$IDserv'";
			$result=mysqli_query($link2,$query);
			$row=mysqli_fetch_row($result);
			$persone=$row['0'];
			$codiceid=$row['1'];
			
		
			$query="SELECT c.IDservcont,COUNT(*) FROM composizionicof as c,servizi as s WHERE c.IDcof='$IDserv' AND c.IDservcont=s.ID AND s.IDtipo!='9' GROUP BY c.IDservcont";
			$result=mysqli_query($link2,$query);
		
			$content='<hr><table class="wrapper3" style="table-layout:auto; width:70%;">
			<tr><tr><th style="height:35px;">Cofanetto</th><th>Contenuto</th><th>Persone</th></th>
			<tr><td style="width:170px;">'.$codiceid.'</td><td class="serv"> ';
			$c=0;
			$c2=0;
			while($row5=mysqli_fetch_row($result)){
				$serv=$row5['0'];
				
				$query2="SELECT s.servizio,t.tipolimite,t.tipo FROM servizi as s,tiposervizio as t WHERE s.ID='$serv' AND s.IDstruttura='$IDstruttura' AND s.IDtipo=t.ID";
				$result2=mysqli_query($link2,$query2);
				$row2=mysqli_fetch_row($result2);
				$servizio=$row2['0'];
				$tipoliminto=$row2['1'];
				
				
				if($tipoliminto=='4'){
					$pern=$row2['2'];
					$content.='N.'.$row5['1'].' '.$pern;
				}else{
					$content.='N.'.$row5['1'].' '.$servizio;
				}
				if($c2==2){$content.='<br>';
				}else{
					$content.=',  ';
				}
				
				$c++;	$c2++;	
			}
			$content=substr($content, 0, strlen($content)-2);
		
			$content.='</td><td style="width:150px;">Per <span id="npers">'.$persone.'</span> persone</td></tr></table><hr>';
			echo $content;
			

		
		break;
		default:
			echo '<span id="npers" style="display:none;">-1</span>';
			if(is_numeric($IDserv)){
				$query="SELECT IDtipo,IDsottotip,esclusivo,servizio FROM servizi  WHERE ID='$IDserv' LIMIT 1 ";
				$result=mysqli_query($link2,$query);
				$row=mysqli_fetch_row($result);
				$IDtipo=$row['0'];
				$IDsotto=$row['1'];
				$esclusivo=$row['2'];
				$servizio=$row['3'];
			}
		break;
	
	
	
	}
	
	
	
	if($tipolim=='6'){
		$arr=array();
		if(isset($_SESSION['arrservadd'])){
			$arr=$_SESSION['arrservadd'];
		}
		if(is_numeric($IDserv)){
			if(!isset($arr[$IDserv])){
				$arr[$IDserv]=1;		
			}
		}
		$_SESSION['arrservadd']=$arr;
		$time0=time();
		$txt='
		
		 <div class="content-block-title" style="font-size:20px; text-align:left;">Carrello</div>
		<div class="list-block">
		  <ul>
			
			';
		$totale=0;	
			
		foreach ($arr as $key=>$dato){
			if(is_numeric($key)){
				$query="SELECT servizio,prezzo FROM servizi WHERE ID='$key' LIMIT 1";
				$result=mysqli_query($link2,$query);
				$row=mysqli_fetch_row($result);
				$serv=$row['0'];		
				$prezzo=$row['1'];
				$totale+=$prezzo*$dato;
				
				$txt.='
				<li class="item-content">
				  <div class="item-inner">
					<div class="item-title">'.$serv.' ('.$prezzo.'€)</div>
					<div class="item-after">
					<table class="tabplus"><tr><td class="less sopra" ><div onclick="creasessione('.$key.',47)" style="padding:5px; width:17px; height:17px; border-radius:4px; font-size:17px; text-align:center; line-height:17px; background:#ccc;">-</div></td><td style="width:20px; text-align:center;" class="cent" alt="'.$key.'"  dir="'.$time0.'">'.$dato.'</td><td ><div class="color-green" onclick="creasessione('.$key.',48)" style="padding:5px; width:17px; height:17px; border-radius:4px; font-size:17px; text-align:center; line-height:17px; background:#ccc;">+</div></td></tr></table>
					
									
					</div>
				  </div>
				</li>
				
				
				';
				
			}
		
		
		}
		
		$txt.='</ul></div>
		';
		
		
		
	}else{ //se diverso da 6
	
	
	$query="SELECT time,checkout FROM prenotazioni WHERE IDv='$IDpren' LIMIT 1";
	$result=mysqli_query($link2,$query);
	$row=mysqli_fetch_row($result);
	$check=$row['0'];
	$checkout=$row['1'];
	
	
	$data=date('Y-m-d',$check);
	list($yy, $mm, $dd) = explode("-", $data);
	$time0=mktime(0, 0, 0, $mm, $dd, $yy);
	
	if($tipolim=='5'){
		$arrserv=array();
		$arrsotto=array();
		$arresc=array();
		$nottipacc=1;
		$querypacc="SELECT c.IDservcont,c.qta,s.IDsottotip,t.tipolimite,s.esclusivo FROM composizioni as c,servizi as s,tiposervizio as t WHERE c.IDserv='$IDserv' AND c.IDservcont=s.ID AND s.IDtipo=t.ID AND (t.tipolimite='4' OR t.ID='1' OR s.esclusivo='1')";
		$resultpacc=mysqli_query($link2,$querypacc);
		if(mysqli_num_rows($resultpacc)>0){
			while($rowp=mysqli_fetch_row($resultpacc)){
				$IDservcon=$rowp['0'];
				$arrserv[$IDservcon]=$rowp['1'];	
				$arrsotto[$IDservcon]=$rowp['2'];
				$arresc[$IDservcon]=$rowp['4'];						
				if($rowp['3']=='4'){
					$nottipacc=$rowp['1'];
				}
			}
		}
		$checkout=$checkout-($nottipacc*86400);
	}
	if($tipolim=='7'){
		$arrserv=array();
		$arrsotto=array();
		$arresc=array();
		$nottipacc=0;
		$querypacc="SELECT c.IDservcont,COUNT(*),s.IDsottotip,s.IDtipo,s.esclusivo FROM composizionireg as c,servizi as s WHERE c.IDreg='$IDserv' AND c.IDservcont=s.ID AND (s.IDtipo IN (1,8) OR s.esclusivo='1') GROUP BY s.IDsottotip,s.esclusivo";
		$resultpacc=mysqli_query($link2,$querypacc);
		if(mysqli_num_rows($resultpacc)>0){
			while($rowp=mysqli_fetch_row($resultpacc)){
				$IDservcon=$rowp['0'];
				$arrserv[$IDservcon]=$rowp['1'];	
				$arrsotto[$IDservcon]=$rowp['2'];
				$arresc[$IDservcon]=$rowp['4'];	
				if($rowp['3']=='8'){
					$nottipacc=$rowp['1'];
				}
			}
		}
		$checkout=$checkout-($nottipacc*86400);
	}
	
	if($tipolim=='8'){
		$arrserv=array();
		$arrsotto=array();
		$arresc=array();
		$nottipacc=0;
		$querypacc="SELECT c.IDservcont,COUNT(*),s.IDsottotip,s.IDtipo,s.esclusivo FROM composizionicof as c,servizi as s WHERE c.IDcof='$IDserv' AND c.IDservcont=s.ID AND (s.IDtipo IN (1,8) OR s.esclusivo='1') GROUP BY s.IDsottotip,s.esclusivo";
		$resultpacc=mysqli_query($link2,$querypacc);
		if(mysqli_num_rows($resultpacc)>0){
			while($rowp=mysqli_fetch_row($resultpacc)){
				$IDservcon=$rowp['0'];
				$arrserv[$IDservcon]=$rowp['1'];	
				$arrsotto[$IDservcon]=$rowp['2'];
				$arresc[$IDservcon]=$rowp['4'];	
				if($rowp['3']=='8'){
					$nottipacc=$rowp['1'];
				}
			}
		}
		$checkout=$checkout-($nottipacc*86400);
	}
	
	
	
	$numg=ceil(($checkout-$time0)/86400);
	if($numg==0){
		$numg=1;
		$checkout+=86400;
	}
	
	
	$txt='<div class="titleb" style="margin-left:20px;">Seleziona data</div>';
	
	
	$restpers=array();
	$arrpers=array();
	$arrname=array();
	
	$query="SELECT ID,IDrest,nome FROM infopren WHERE IDpren='$IDpren' AND pers='1'";
	$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
		while($row=mysqli_fetch_row($result)){
			$restr[$row['0']]=$row['1'];
			$arrname[$row['0']]='<b>'.estrainomecli($row['0']).'</b><br><i style="font-size:9px;">'.$row['2'].'</i>';
		}
	}
	
	$wid=round((100-25)/$numg);
	
	
	$dataoggi=date('Y-m-d');
	
	
	$txtaccord=array();
	/*
	for($i=$time0;$i<$checkout;$i+=86400){
		$classog='';
		if(date('Y-m-d',$i)==$dataoggi){
			$classog=' class="oggiaddserv" ';
		}
		$txtaccord[$i]='
			<li class="accordion-item"><a href="#" class="item-content item-link">
				<div class="item-inner">
				  <div class="item-title">'.dataita($i).'</div>
				  <div class="item-after">
				  <span  class="eletxt" id="ele'.$i.'">0 Elementi selezionati</span>
				  </div>
				</div></a>
			  <div class="accordion-item-content">
				<div class="content-block">
					<div class="list-block">
  						<ul >
				
		
		';
		
	}*/
	
	$i=0;
	$arr=array();
	$arrtipi=array();
	if($esclusivo=='1'){
		
		$query="SELECT ID FROM infopren WHERE IDpren='$IDpren' AND pers='1'";
		$result=mysqli_query($link2,$query);
		while($row=mysqli_fetch_row($result)){
			$arrp[$row['0']]=2;
		}
			
			$data=date('Y-m-d',$i);
		
			$query="SELECT ID,extra,FROM_UNIXTIME(time,'%Y-%m-%d') FROM prenextra WHERE FROM_UNIXTIME(time,'%Y-%m-%d') BETWEEN FROM_UNIXTIME('$check','%Y-%m-%d') AND  FROM_UNIXTIME('$checkout','%Y-%m-%d') AND esclusivo='1' AND modi>='0'  ORDER BY time";
			$result=mysqli_query($link2,$query);
			if(mysqli_num_rows($result)>0){
				while($row=mysqli_fetch_row($result)){
					list($yy, $mm, $dd) = explode("-", $row['2']);
					$tt=mktime(0, 0, 0, $mm, $dd, $yy);
					$arr[-1][$tt]=0;
					$arrtipi[-1][$tt]=2; //aggiungi lo stesso
				}	
			}
	}else{
		if($tipolim=='2'){
			$query="SELECT p.ID,p.extra,FROM_UNIXTIME(p.time,'%Y-%m-%d'),p2.IDinfop FROM prenextra as p,prenextra2 as p2 WHERE FROM_UNIXTIME(time,'%Y-%m-%d') BETWEEN FROM_UNIXTIME('$check','%Y-%m-%d') AND  FROM_UNIXTIME('$checkout','%Y-%m-%d') AND p.IDpren='$IDpren' AND p.sottotip='$IDsotto' AND p.ID=p2.IDprenextra ORDER BY p.time";
			$result=mysqli_query($link2,$query);
			if(mysqli_num_rows($result)>0){
				while($row=mysqli_fetch_row($result)){
					list($yy, $mm, $dd) = explode("-", $row['2']);
					$tt=mktime(0, 0, 0, $mm, $dd, $yy);
					
					if($IDtipo==1){	
						$arr[$row['3']][$tt]=$row['0'];				
						$arrtipi[$row['3']][$tt]=0; //sostituisci - se 1) non può essere ne aggiunto ne sostituito
					}else{
						// per adesso non fa vedere nulla
						
						//$arr[$row['3']][$tt]=$row['0'];	
						//$arrtipi[$row['3']][$tt]=4; //aggiungi lo stesso
					}
				}	
			}
			
		}
		
		if(($tipolim=='5')||($tipolim=='7')||($tipolim=='8')){
			for($i=$time0;$i<$checkout;$i+=86400){
				foreach($arrserv as $key=>$dato){
					$IDsottotip=$arrsotto[$key];
					$ret=nposserv2($IDsottotip,$key,$i,$arrserv[$key],$IDpren,$arresc[$key],$nottipacc,$check,$checkout);
					$arrp=$ret;
				//	print_r($arrp);echo '<br>';
					if(isset($arrp)){
						foreach ($arrp as $key=>$dato){
							$arr[$key][$i]=0;
							if(isset($arrtipi[$key][$i])){
								if($arrtipi[$key][$i]>$dato){
									$arrtipi[$key][$i]=$dato;
								}
							}else{
								$arrtipi[$key][$i]=$dato;
							}
						}
					}
				}
			}
		}
	}
	
	
	
	
	
	$testodata='';
	$testorestr='';
	
	
	$arrmess=array('<i class="material-icons">cached</i>Sostituisci',
	'<i class="material-icons">pan_tool</i>Conflitto Pacchetti',
	'<i class="material-icons">pan_tool</i>Esclusivo Presente<br><span>Abilitato</span>',
	'',
	'<i class="material-icons">check</i>Già in Prentazione');
	$kk=0;
	
	
	$messaggiins=array();
	
	
	$numr=count($restr);
	
	$typeinput='checkbox';
	if($tipolim==1){
		$typeinput='radio';		
	}
	
	
		foreach ($restr as $key2=>$dato2){
			for($i=$time0;$i<=$checkout;$i+=86400){
				
				if(!isset($txtaccord[$i])){$txtaccord[$i]='';}
				
				$kk++;
				
				if($tipolim==5){
					$prezzo=prezzopacc($IDserv,$i,$dato2,$IDstruttura);
				}else{
					if($row['3']==10){
						$calc=1;
					}else{
						$calc=$dato2.',';
					}
					$prezzo=calcolaprezzoserv($IDserv,$i,$calc,$IDstruttura,0,$IDpren);
				}
				
				
				
				$prezzotxt=''.$prezzo.'€';
				
				if((isset($arr[$key2][$i]))||(isset($arr[-1][$i]))){
					$dis='';
					
					$IDins=0;
					$mess='';
					$tt2=0;
					$disab='';
					$func='';
					if(!isset($arr[$key2][$i])){
						$tipo=$arrtipi[-1][$i];
						$mess=$arrmess[2];
						$tt2=2;
						$func='onchange="selectbutt(this)"';
					}else{
						$tipo=$arrtipi[$key2][$i];
						
						$IDins=$arr[$key2][$i];
						$mess=$arrmess[$arrtipi[$key2][$i]];
						$tt2=$arrtipi[$key2][$i];
						//$disab='disab';
					}
					
					
					if(($tt2==0)||($tt2==1)||($tt2==4)){
						if(!isset($messaggiins[$i])){$messaggiins[$i]=0;}
						$messaggiins[$i]++;
					}
					
					
					$func='onchange="selectbutt(this)"';
					
					$txtaccord[$i].='
					<li id="'.$kk.'">
					  <label class="label-checkbox item-content ">
						
						<input type="'.$typeinput.'"  '.$func.' name="soggetti"  alt="'.$prezzo.'" align="'.$i.'" lang="'.$key2.'_'.$i.'_'.$IDserv.'_'.$dato2.'" dir="'.$tipo.'">
						<div class="item-media">
						  <i class="icon icon-form-checkbox"></i>
						</div>
						<div class="item-inner">
						  <div class="item-title" >'.$arrname[$key2].'</div>
						   <div class="item-after" >'.$mess.'</div>
						</div>
					  </label>
					</li>
				
				';
					
					/*
					$txt.='<div class="sopra t'.$i.' in'.$key2.' infos icon'.$tipo.'g bb '.$disab.'" alt="'.$IDins.'" align="'.$i.'" lang="'.$key2.'_'.$i.'_'.$IDserv.'_'.$dato2.'" dir="'.$tipo.'" '.$func.' id="'.$kk.'" ></div><div class="mex">'.$mess.'</div>';	
					*/
				}else{
					//$txt.='<input type="checkbox" class="wcheck t'.$i.' in'.$key2.'" lang="'.$key2.'_'.$i.'_'.$IDserv.'" onChange="selezionati3()">';
					
					//controlla quanti ne ha gia'
					$pres='';
					if(($tipolim==1)||($tipolim=='2')){
						if($IDtipo==2){
							$queryc="SELECT ID FROM prenextra as p,prenextra2 as p2 WHERE p2.IDinfop='$key2' AND p.IDtipo='$IDtipo' AND p2.IDprenextra=p.ID AND FROM_UNIXTIME(p.time,'%Y-%m-%d')=FROM_UNIXTIME('$i','%Y-%m-%d')";
						}else{
							$queryc="SELECT ID FROM prenextra as p,prenextra2 as p2 WHERE p2.IDinfop='$key2' AND p.sottotip='$IDsotto' AND p2.IDprenextra=p.ID AND FROM_UNIXTIME(p.time,'%Y-%m-%d')=FROM_UNIXTIME('$i','%Y-%m-%d')";
						}
						$resultc=mysqli_query($link2,$queryc);
						$numc=mysqli_num_rows($resultc);
						if($numc>0){
							$pres='<div class="sugger">'.$numc.'</div>';
						}
					}
					
					$txtaccord[$i].='
					<li  id="'.$kk.'">
					  <label class="label-checkbox item-content">
						<input type="'.$typeinput.'" name="soggetti"  onchange="selectbutt(this)" lang="'.$key2.'_'.$i.'_'.$IDserv.'_'.$dato2.'" alt="'.$prezzo.'"	align="'.$i.'" lang="'.$key2.'_'.$i.'_'.$IDserv.'_'.$dato2.'">
						<div class="item-media">
						  <i class="icon icon-form-checkbox"></i>
						</div>
						<div class="item-inner">
						  <div class="item-title">'.$arrname[$key2].'</div>
						  <div class="item-after">'.$prezzotxt.' </div>
						</div>
					  </label>
					</li>
				
				';
					
	
					
				}
				
				
				
			}
		}
	
	
	$txt.='
	<div class="list-block">
  <ul>
    <li>
      <a href="#" class="item-link smart-select" data-open-in="popup">
        <select name="data" onchange="selectservice('.$IDserv.','.$tipolim.','.$IDtipo.',0,0,this.value)">

	';
	
	$start=0;
	$isel=$timeoggi;
	if(isset($messaggiins[$timeoggi])){
		if($messaggiins[$timeoggi]==$numr){
			$start=1;
			$isel=$time0;
		}
	}
	$ok=0;
	
	
	$timeoggi=$isel;
	
	for($i=$time0;$i<=$checkout;$i+=86400){		
		$sel='';
		
		$dis='';
		if(isset($messaggiins[$i])){
			if($messaggiins[$i]==$numr){
				$dis='disabled="disabled"';
			}
		}

		if(($i==$isel)&&($dis=='')&&($ok==0)){
			$sel=' selected="selected"';
			$ok=1;
		}else{
			if(($ok==0)&&($start==1)){
				$isel+=86400;
			}
		}
		
		$txt.='<option value="'.$i.'" '.$sel.' '.$dis.'>'.dataita4($i).'</option>';			
	}
	$txt.='
	
	</select>
        <div class="item-content">
          <div class="item-inner">
            <div class="item-title">Data</div>
            <div class="item-after">'.dataita4($isel).'</div>
          </div>
        </div>
      </a>
    </li>
  </ul>
</div>   
<div class="titleb" style="margin-left:20px;">Seleziona persone</div>

<div class="list-block">
  <ul>'.$txtaccord[$isel].'
  </ul>
  </div>


	
	';
/*				
	foreach($txtaccord as $tt=> $dato){
		$stamp=1;
		
		if(isset($_SESSION['datecentro'])){
			if(!in_array(date('Y-m-d',$tt),$_SESSION['datecentro'])){
				$stamp=0;
			}
		}
		if($stamp==1){			
			$txt.=$dato.'
			 </ul>
		</div> 
		
			</div></div></li>
			';
		}
	}
	$txt.='
	</ul>
	</div> 
	';*/
	}


//va a stampare tutto




echo $txt;

?>
