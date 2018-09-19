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
                  <div class="left " style="width:70%">Esclusivi e Note</div>
                  <div class="right" >
						<a href="#" class="close-popup"><i class="icon f7-icons" style="font-size:30px;">close</i></a>				  
				  </div>
               </div>
            </div>
			
        <div class="content-block">
  
		
		';
		
		$IDprenini=0;
		$data=date('Y-m-d',$time);

		$query="SELECT  p.IDpren FROM prenextra as p  WHERE FROM_UNIXTIME(p.time,'%Y-%m-%d')='$data' AND p.IDstruttura='$IDstruttura' AND p.modi>='0' AND  p.esclusivo='1' GROUP BY p.IDpren";


		$result=mysqli_query($link2,$query);
		if(mysqli_num_rows($result)>0){
			
			$testo.='
			<div class="content-block-title titleb">Prenotazioni con servizi esclusivi</div>
			';
			
			
			while($row=mysqli_fetch_row($result)){
				$IDpren=$row['0'];
				
				$nome=estrainome($IDpren);		
				
				/*$timearr=explode(',',$row['0']);
				$serviziarr=explode(',',$row['1']);
				$qtaarr=explode(',',$row['3']);*/
				
				
				
				$testo.='
				<div class="row rowlist no-gutter"  onclick="navigation(3,'."'".$row['2']."'".');myApp.closeModal();">
				<div class="col-100">'.$nome.'</div>
				';
				
				$query2="SELECT  time,s.servizio,COUNT(*) FROM prenextra as p,servizi as s,prenextra2 as p2 WHERE FROM_UNIXTIME(p.time,'%Y-%m-%d')='$data' AND p.IDstruttura='$IDstruttura' AND p.modi>='0' AND s.ID=p.extra AND s.esclusivo='1' AND p.ID=p2.IDprenextra GROUP BY p.ID ORDER BY p.time";
				$result2=mysqli_query($link2,$query2);
				if(mysqli_num_rows($result2)>0){
					while($row2=mysqli_fetch_row($result2)){
						$testo.='
						<div class="col-5 h40"></div>
						<div class="col-70 coltitle">'.$row2['1'].'<br><span>'.$row2['2'].' '.txtpersone($row2['2']).'</span></div>
						<div class="col-25 rightcol">'.date('H:i',$row2['0']).'</div>
						';
					}
				}
				
				/*
				
				foreach($timearr as $key =>$time){
					$testo.='
					<div class="col-5"></div>
					<div class="col-70">'.$serviziarr[$key].'<br><span>'.$qtaarr[$key].' '.txtpersone(qtaarr[$key]).'</span></div>
					<div class="col-25">'.date('H:i',$time).'</div>
					';
				}*/
				
				
				$testo.='</div>';
				
				/*	
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
		*/
				
				
			}
			//$testo.='</ul></div>';
		}
		
		
		$testo.='<br><br><br>';
		


		$query2="SELECT titolo,descr,time FROM note WHERE FROM_UNIXTIME(time,'%Y-%m-%d')='$data' AND IDstruttura='$IDstruttura' AND (titolo!='' OR descr!='') ORDER BY time";
	$result2=mysqli_query($link2,$query2);
		if(mysqli_num_rows($result2)>0){
			
			$testo.='<div class="content-block-title titleb">Note del Giorno</div>';
			
			
			while($row=mysqli_fetch_row($result2)){
				
				
				$tit='';
				if($row['0']!=''){$tit='<b>'.$row['0'].'</b>:';}
				$testonota=str_replace('\n','<br>',$tit.'<br><span style="font-size:13px;color:#555;">'.TagliaStringa($row['1'],0,100)).'</span>';
			

				
				$testo.='
				<div class="row rowlist no-gutter"  onclick="navigation(3,'."'".$row['2']."'".');myApp.closeModal();">
				<div class="col-90">'.$row['0'].'
				<br>'.$row['1'].'
				
				
				</div>
				<div class="col-10 rightcol">'.date('H:i',$row2['2']).'</div>
				
				</div>';

				
				/*
				$testo.='
				
					
				<li>
					<div class="item-inner">
					  <div class="item-title" style="width:100%; padding:10px;">
							<span style="font-size:14px; ">'.$testonota.'</span><br>
						</div>
					 
					</div>
				</li>
		
				
				';*/
				
			}
			//$testo.='</ul></div>';
		}
		
		
				




	
	$testo.='
	</div><br><br><br>
	</div>';
	
	echo $testo;
?>