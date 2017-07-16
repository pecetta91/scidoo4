<?php

header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');

$IDutente=$_SESSION['ID'];
$IDstruttura=$_SESSION['IDstruttura'];

$time=strip_tags($_GET['time']);

$testo='
<div class="navbar" >
               <div class="navbar-inner">
                  <div class="center titolonav" style="width:80%">Esclusivi e Note</div>
                  <div class="right" >
						<a href="#" class="close-popup"><i class="icon f7-icons" style="font-size:30px; margin-right:18px;">close</i></a>				  
				  </div>
               </div>
            </div>
			
        <div class="content-block">
  
		
		';
		
		$query2="SELECT  p.time,s.servizio,p.IDpren FROM prenextra as p,servizi as s WHERE FROM_UNIXTIME(p.time,'%d/%m/%Y')=FROM_UNIXTIME('$time','%d/%m/%Y') AND p.IDstruttura='$IDstruttura' AND p.modi>='0' AND s.ID=p.extra AND s.esclusivo='1' ORDER BY p.time";
		$result2=mysqli_query($link2,$query2);
		if(mysqli_num_rows($result2)>0){
			
			$testo.='
			<div class="content-block-title titleb">Prenotazioni con servizi esclusivi</div>
			<div class="list-block">
      <ul>';
			
			
			while($row=mysqli_fetch_row($result2)){
				$nome=estrainome($row['2']);		
				
				$query3="SELECT ID FROM infopren WHERE IDpren='".$row['2']."' AND pers='1'";
				$result3=mysqli_query($link2,$query3);
				$num=mysqli_num_rows($result3);
				
				$testo.='
				
					
				<li>
				  <a href="#"  onclick="navigation(3,'."'".$row['2']."'".');myApp.closeModal();" class="item-link item-content">
					<div class="item-inner">
					  <div class="item-title">'.$nome.'<br>
						  <span style="font-size:11px;"><b>'.$row['1'].'</b><br>'.$num.' Persone</span>
						</div>
					  <div class="item-after">'.date('H:i',$row['0']).'</div>
					  
					  
					</div>
				  </a>
				</li>
		
				
				';
				
			}
			$testo.='</ul></div>';
		}
		
		
		

		
		
		$query2="SELECT titolo,descr,time FROM note WHERE FROM_UNIXTIME(time,'%d/%m/%Y')=FROM_UNIXTIME('$time','%d/%m/%Y') AND IDstruttura='$IDstruttura' AND (titolo!='' OR descr!='') ORDER BY time";
	$result2=mysqli_query($link2,$query2);
		if(mysqli_num_rows($result2)>0){
			
			$testo.='
			<div class="content-block-title titleb">Note del Giorno</div>
			<div class="list-block">
      <ul>';
			
			
			while($row=mysqli_fetch_row($result2)){
				
				
				$tit='';
				if($row['0']!=''){$tit='<b>'.$row['0'].'</b>:';}
				$testonota=str_replace('\n','<br>',$tit.'<br><span style="font-size:13px;color:#555;">'.TagliaStringa($row['1'],0,100)).'</span>';
			
			
				$testo.='
				
					
				<li>
					<div class="item-inner">
					  <div class="item-title" style="width:100%; padding:10px;">
							<span style="font-size:14px; ">'.$testonota.'</span><br>
						</div>
					 
					</div>
				</li>
		
				
				';
				
			}
			$testo.='</ul></div>';
		}
		
		
				




	
	$testo.='
	</div><br><br><br>
	</div>';
	
	echo $testo;
?>