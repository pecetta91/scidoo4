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
$tempg=$row['3'];
$tempn=$row['4'];
$checkout=$row['5'];
$IDstr=$row['6'];

$nomepren=estrainome($IDpren);


$timeora=oraadesso($IDstr);

//elenco servizi


$IDprenc=prenotcoll($IDpren);
$id=$IDpren;


$serviziarr=array();
$prodottiarr=array();


$testo='
<div data-page="serviziospite" class="page" > 
            <!-- Top Navbar--> 
             
	
			 
			 	<div class="navbar" id="navcal" >
				<div class="navbar-inner">
					<div class="left">
					
					  <a href="#" class="link icon-only back"   >
						<i class="material-icons" style="font-size:40px;">chevron_left</i>
					</a>
					
					</div>
					<div class="center titolonav">Promemoria Servizi</div>
					
				</div>
			</div>
		 <div class="page-content">
			
				
				
              <div class="content-block" id="servizidiv"> 


';


	
	$query="SELECT p.ID,p.time,p.modi,COUNT(DISTINCT(p2.IDprenextra)),GROUP_CONCAT(DISTINCT(p.ID) SEPARATOR ','),p.tipolim,p.extra,p.modi,SUM(p2.prezzo),GROUP_CONCAT(DISTINCT(p2.IDinfop) SEPARATOR ','),SUM(p2.qta),p.sala,p.IDtipo,p.sottotip,s.servizio,s.descrizione,MAX(p2.pacchetto) FROM prenextra as p,prenextra2 as p2,servizi as s WHERE  p2.IDpren IN ($IDprenc) AND p.ID=p2.IDprenextra AND p2.paga>'0' AND p.extra=s.ID AND p.IDtipo NOT IN(8,9)  GROUP BY p.ID ORDER BY p.extra,p.time";
	$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
			
		while($row=mysqli_fetch_row($result)){
		
		
			$ID=$row['0'];
				$time=$row['1'];
				$modi=$row['2'];
				$num2=$row['3'];
				$prezzo=$row['8'];			
				$extra=$row['6'];
				$qta=$row['10'];
				$IDtipo=$row['12'];
				$IDsotto=$row['13'];
				$tipolim=$row['5'];
				$servizio=$row['14'];
				$descr=$row['15'];
				$pacchetto=$row['16'];
			
				$foto='immagini/'.getfoto($extra,4);
				
				$num2='';
				if($tipolim==6){
					$servizio='N.'.$qta.' '.$servizio;
					$qta='';
				}else{
					
					if($modi==0){
						$num2='--.--';
					}else{
						$num2=date('H:i',$time);
					}
					
					$persone='persone';
					if($qta==1){
						$persone='persona';
					}
					$qta='<span style="font-size:11px;color:#777;">N.'.$qta.' '.$persone.'</span>';
				}
			
							
		
			$butt1='';
			
		$sala='';
			
			$vis='<div class="item-media"><img src="'.$route.$foto.'"></div>';
			
			$txt='<li>
				  <a href="#" class="item-link item-content" onclick="navigation(25,'.$ID.',0,0)">
					<div class="item-media" style="padding:0px;">
					'.$vis.'
					</div>
						<div class="item-inner" >
					  <div class="item-title-row">
					  <div class="item-title" style="font-size:13px; font-weight:600;">'.wordwrap($servizio,25,'<br>').'</div>
						<div class="item-after">'.$num2.'</div>
					  </div>
					  <div class="item-subtitle">'.$qta.'</div>
					  
					</div>
				  </a>
				  
			</li>
			
			';
			
			
			$dd=date('Y-m-d',$time);
			
			if($tipolim==6){
				if(isset($prodottiarr[$dd])){
					$prodottiarr[$dd].=$txt;
				}else{
					$prodottiarr[$dd]=$txt;
				}
			
			}else{
			
				if(isset($serviziarr[$dd])){
					$serviziarr[$dd].=$txt;
				}else{
					$serviziarr[$dd]=$txt;
				}
			}
			
			
		
		
		
		
		
		}
	}
	
//sort($serviziarr);
//sort($prodottiarr);


foreach ($serviziarr as $data =>$cont){
	list($yy, $mm, $dd) = explode("-", $data);
	$time=mktime(0, 0, 0, $mm, $dd, $yy);
	
	$testo.='
<div class="content-block-title titleb">'.dataita($time).' '.date('Y',$time).'</div>
			<div class="list-block media-list" >
			  <ul>'.$cont.'
			  </ul></div>
	';

}


$testo.='<br><br><div style="width:90%; margin:auto; text-align:center;color:#af2b44;"><span style="font-weight:300; line-height:12px; font-size:12px;">&Egrave; possibile modificare gli orari fino a 4h prima del suo inizio.<br>Per qualsiasi altre informazioni o modifica contrattare la struttura.</span></div>



</div></div></div>


';




if(!isset($inc)){
echo $testo;
}




?>