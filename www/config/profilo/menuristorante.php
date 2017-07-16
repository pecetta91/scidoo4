<?php 
if(!isset($inc)){
	include('../../../config/connecti.php');
	include('../../../config/funzioni.php');
	header('Access-Control-Allow-Origin: *');
	$testo='';
}

$IDpren=$_SESSION['IDstrpren'];
$route=$_SESSION['route'];

$query="SELECT app,gg,time,tempg,tempn,checkout,IDstruttura FROM prenotazioni WHERE IDv='$IDpren' LIMIT 1";

$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDapp=$row['0'];
$gg=$row['1'];
$time=$row['2'];
$tempg=round($row['3']);
$tempn=round($row['4']);
$checkout=$row['5'];
$IDstr=$row['6'];

$IDsottosel=0;

if(isset($_GET['dato0'])){
	$IDsottosel=$_GET['dato0'];
}
if($IDsottosel==0){
	$query="SELECT ID FROM sottotipologie WHERE IDmain='1' AND IDstr='$IDstr' LIMIT 1";
	$result=mysqli_query($link2,$query);
	$row=mysqli_fetch_row($result);
	$IDsottosel=$row['0'];	
}

$query="SELECT sottotipologia FROM sottotipologie WHERE ID='$IDsottosel' AND IDstr='$IDstr' LIMIT 1";
	$result=mysqli_query($link2,$query);
	$row=mysqli_fetch_row($result);
	$sottotipologia=$row['0'];	



$nomepren=estrainome($IDpren);




$col=3;
$kk=0;
$IDsottotip=0;
$query="SELECT ID FROM tiporestr WHERE IDstr='$IDstr' AND personale='1'  AND personad='1' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$restr=$row['0'].',';

$sottot=array();

$testo.='


<div data-page="menuristoospite" class="page" > 

		 	<div class="navbar" id="navcal" >
				<div class="navbar-inner">
					<div class="left">
					
					  <a href="#" class="link icon-only back"   >
						<i class="material-icons" style="font-size:40px;">chevron_left</i>
					</a>
					
					</div>
					<div class="center titolonav">Menu '.$sottotipologia.'</div>
					
				</div>
			</div>
		 <div class="page-content">
			
				
				
              <div class="content-block" id="menuristoospitediv">





<br>
<div class="timeline verticale">';


for($i=0;$i<=$gg;$i++){
	$timeg=$time+$i*86400;
	
	$testo.='
	<div class="timeline-item">
    <div class="timeline-item-date" style="width:40px;line-height:14px;">'.date('d',$timeg).'<br><small>'.$giorniita3[date('w',$timeg)].'</small></div>
    <div class="timeline-item-divider"></div>
    <div class="timeline-item-content">

	
	';
	
	//foreach($sottot as $IDsotto =>$sottotipologia){
		
		$txtmenu=estraimenu($IDsottosel,$timeg);
		if(strlen($txtmenu)>0){
			$testo.='<div style=" background:#fff; border-radius:5px; font-size:13px; padding:4px;" onclick="openmenuoggi('.$timeg.')">'.$txtmenu.'<br>';
			
				$query2="SELECT ID FROM prenextra WHERE IDpren='$IDpren' AND sottotip='$IDsottosel' AND FROM_UNIXTIME(time,'%Y-%m-%d')=FROM_UNIXTIME($timeg,'%Y-%m-%d') LIMIT 1";
				$result2=mysqli_query($link2,$query2);
				if(mysqli_num_rows($result2)==0){			
					$query2="SELECT ID,servizio FROM servizi WHERE IDtipo='1' AND IDstruttura='$IDstr' AND IDsottotip='$IDsottosel' AND attivo='1'";
					$result2=mysqli_query($link2,$query2);
					if(mysqli_num_rows($result2)>0){
						$testo.='
						<div id="menu'.$timeg.'" style="display:none;"> 
						<table width="100%" class="tabristo"  >';
						while($row2=mysqli_fetch_row($result2)){
							
							$txt='';
							$query3="SELECT t.nome FROM portatemenu as pt,tipopiatti as t WHERE pt.IDserv='".$row2['0']."' AND pt.IDtipopiatti=t.ID ORDER BY t.tipologia,t.ordine";
							$result3=mysqli_query($link2,$query3);
							if(mysqli_num_rows($result3)>0){
								while($row3=mysqli_fetch_row($result3)){
									$txt.=$row3['0'].' + ';
								}
								$txt=substr($txt, 0, strlen($txt)-2);
							}

							if(strlen($txt)>0){
								$prezzo=calcolaprezzoserv($row2['0'],$i,$restr,$IDstr,0,0,0,0);
								$testo.='<tr onclick="prenotaora('.$row2['0'].','.$timeg.');"><td><b>'.$row2['1'].'</b><br><span>'.$txt.'</span></td><td ><div class="prezzoristo" >'.$prezzo.'&euro;<div></td></tr>';
							}
						}
						$testo.='</table></div>';
					}
				}else{
					$testo.='
						<div id="menu'.$timeg.'"  style="display:none;"> 
							<div style="font-size:16px;padding-left:10px;">Il menu &egrave; gi&agrave; incluso nella sua prenotazione</div>			
						</div>
						';
						
				}
				
			
			
			
			
			
			$testo.='</div>	';
		}
		//$testo.='<br>';
	//}
		
	$testo.='</div></div>';
	
	
	
}
$testo.='</div>';
		
		
		/*
		$content.= '<hr><div style=" width:100%; text-align:center;"><span style="font-size:20px; color:#333;">'.$sotto.'<br><span style="font-size:12px;">Orari : '.$orari.' <br>Sale adibite : '.$sale.'</span></div>';
		for($i=$timei;$i<=$timef;$i+=86400){
			
			$menu=estraimenu($dato,$i,0);
			if(strlen($menu)>0){
				$content.='<div style="width:245px;padding:0px; border:solid 1px #ccc; margin:5px; display:inline-block; float:left;" align="center">
				
				
				<div style="font-size:16px; color:#45c342;margin-left:94px;padding:5px; margin-bottom:4px;  padding-bottom:0px; width:40px; height:40px; background:#fff; border:solid 3px #ed6a3c;">
			
	
			<b style="font-size:25px; color:#ed6a3c; line-height:25px;">'.date('d',$i).'</b><br><span style="font-size:11px; color:#666;">'.$giorniita[date('w',$i)].'</span>
			
			</div>
				<span style="font-size:12px;">'.utf8_decode(utf8_encode($menu)).'</span>
				
				<br>
				<table width="98%" style="font-size:8px; background:#f9f9f9;">';

				$query2="SELECT ID,servizio FROM servizi WHERE IDtipo='1' AND IDstruttura='$IDstruttura' AND IDsottotip='$dato' AND attivo='1'";
				$result2=mysqli_query($link2,$query2);
				if(mysqli_num_rows($result2)>0){
					while($row2=mysqli_fetch_row($result2)){
						
						$txt='';
						$query3="SELECT t.nome FROM portatemenu as pt,tipopiatti as t WHERE pt.IDserv='".$row2['0']."' AND pt.IDtipopiatti=t.ID ORDER BY t.tipologia,t.ordine";
						$result3=mysqli_query($link2,$query3);
						if(mysqli_num_rows($result3)>0){
							while($row3=mysqli_fetch_row($result3)){
								$txt.=$row3['0'].' + ';
							}
							$txt=substr($txt, 0, strlen($txt)-2);
						}
						
						if(strlen($txt)>0){
						
							$prezzo=calcolaprezzoserv($row2['0'],$i,$restr,$IDstruttura,0,0,0,0);
						
							$content.='<tr><td><b>'.$row2['1'].'</b><br>'.$txt.'</td><td style="background:#ccc; color:#fff;  width:25px; text-align:center;">'.$prezzo.'&euro;</td></tr>';
						}
					
					
					}
				}
				
				
				$content.='</table>
				</div>
				';
				$stamp++;
				if($stamp==3){$content.='<br>';$stamp=0;}
				
			}
		
			
			
		}
	}
	
	
			
	
	
}



*/



$testo.='<hr><br><br><div style="width:90%; margin:auto; text-align:center;color:#af2b44;"><span style="font-weight:300; line-height:12px; font-size:12px;">I menu riportati nella seguente sezione potranno essere modificati fino ad un giorno prima.<br>Per qualsiasi altra informazione contrattare la struttura.</span></div>';




if(!isset($inc)){
echo $testo;
}




?>