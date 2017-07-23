<?php 
if(!isset($inc)){
	include('../../../config/connecti.php');
	include('../../../config/funzioni.php');
	header('Access-Control-Allow-Origin: *');
	$testo='';
}



$testo.='

<div data-page="tempospite" class="page" > 
            <!-- Top Navbar--> 
             
	
			 
			 	<div class="navbar" id="navcal" >
				<div class="navbar-inner">
					<div class="left">
					
					  <a href="#" class="link icon-only back"   >
						<i class="material-icons" style="font-size:40px;">chevron_left</i>
					</a>
					
					</div>
					<div class="center titolonav">Temperatura Alloggio</div>
					
				</div>
			</div>
		 <div class="page-content">
			
				
				
              <div class="content-block" id="tempospitediv"> 


';

$IDpren=$_SESSION['IDstrpren'];
$route=$_SESSION['route'];

$query="SELECT app,gg,time,tempg,tempn,checkout,IDstruttura FROM prenotazioni WHERE IDv='$IDpren' LIMIT 1";

$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDapp=$row['0'];
$gg=$row['1'];
$time=$row['2'];
$tempg=round($row['3'],1);
$tempn=round($row['4'],1);
$checkout=$row['5'];
$IDstr=$row['6'];

$nomepren=estrainome($IDpren);


$timeora=oraadesso($IDstr);

$alloggio='';
$statodom='Inattivo';
$color='333';

$query="SELECT rangen,rangep FROM tempdef WHERE IDstruttura='$IDstr' LIMIT 1";
$result=mysqli_query($link2,$query);
	$row=mysqli_fetch_row($result);
	$rangen=round($row['0']);
	$rangep=round($row['1']);
	
	$query="SELECT nome,temp,statod,risc,tempg,tempn FROM appartamenti WHERE ID='$IDapp' LIMIT 1";
	$result=mysqli_query($link2,$query);
	$row=mysqli_fetch_row($result);
	$nome=$row['0'];
	$temp=$row['1'];
	$statod=$row['2'];
	$risc=$row['3'];
	$tempgdef=round($row['4'],1);
	
	$tempndef=round($row['5'],1);
	
	if(($risc='1')&&($statod==1)){
		$statodom='In Riscaldamento';
		$color='c11010';
	}
	if(($risc='0')&&($statod==1)){
		$statodom='In Raffreddamento';
		$color='1759c6';
	}
	
	$alloggio='Alloggio: '.$nome.'<br>';
		

if(($timeora>$time)&&($timeora<$checkout)){
	$testo.='
	
	<div style="width:100%; text-align:center; font-size:80px; color:#'.$color.'; line-height:45px;">
	<span style="font-size:14px; color:#888">Temperatura Attuale</span><br>
	'.$tempg.'&deg;<br>
	<div style="margin-top:0px;;font-size:15px;  font-weight:100; ">'.$statodom.'</div>
	</div>';
}else{
	$testo.='
	
	<div style="width:100%; text-align:center; font-size:80px; color:#'.$color.'; line-height:15px;">
	<span style="font-size:14px; color:#888">La Temperatura Istantanea sar&agrave;<br>visualizzata qui da momento del tuo check-in</span><br>
	</div>';
}

$ini=$tempgdef-$rangen;
			$tempf=$tempgdef+$rangep;
			$i=1;
			$wid=round((($tempf-$ini)/0.5)*65);

$testo.='

<div style="width:90%; margin:auto; text-align:left;font-weight:400; font-size:16px; margin-top:30px; color:#d94b1a;">Temperatura Giorno</div>
 <div class="list-block" style="margin-top:0px;">
      <ul>
        <li class="item-content">
          <div class="item-inner">
            <div class="item-title" id="tempergiorno" style="width:100%; overflow-x:scroll;">
			<div style="width:'.$wid.'px;">
			';
			
			for($ini;$ini<=$tempf;$ini=$ini+0.5){
				$testo.='<div onclick="modprofilo('.$IDpren.','.$ini.',1,10,1)" alt="'.$i.'" class="tempgiorno butttemp';
				if($ini==$tempg){
					$testo.=' selt';
				}
				$testo.='" style="display:inline-block;">'.$ini.'&deg</div>';
				$i++;
			}
			
			
			
			$testo.='
			</div>
			</div>
          </div>
        </li>
		
		
	</ul></div><br>
	
	';
	$ini=$tempndef-$rangen;
			$tempf=$tempndef+$rangep;
			$i=1;
	
	$wid=round((($tempf-$ini)/0.5)*65);
	
	$testo.='
	
	<div style="width:90%; font-size:16px;margin:auto; text-align:left; margin-top:-20px; color:#281ad9;font-weight:400;">Temperatura Notte</div>
 <div class="list-block" style="margin-top:0px;">
      <ul>
        <li class="item-content">
          <div class="item-inner">
            <div class="item-title" id="tempernotte" style="width:100%; overflow-x:scroll;">
			<div style="width:'.$wid.'px;">
			';
			
			for($ini;$ini<=$tempf;$ini=$ini+0.5){
				$testo.='<div class="butttemp tempnotte ';
				if($ini==$tempn){
					$testo.=' selt';
				}
				$testo.='" alt="'.$i.'" onclick="modprofilo('.$IDpren.','.$ini.',2,10,1)">'.$ini.'&deg</div>';
				$i++;
			}
			
			
			
			$testo.='</div></div>
          </div>
        </li>
		
		
	</ul></div>
		
		
		



';






$testo.='<hr><br><br><div style="width:90%; margin:auto; text-align:center;color:#af2b44;"><span style="font-weight:300; line-height:12px; font-size:12px;">Le modifiche effettuate alla temperatura dell&acute;alloggio saranno applicate istantaneamente.<br>Per qualsiasi altra informazione o modifica contrattare la struttura.</span></div>

</div></div></div>

';




if(!isset($inc)){
echo $testo;
}




?>