<?php

header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');
include('../../config/funzionilingua.php');

$IDutente=$_SESSION['ID'];
$IDstruttura=$_SESSION['IDstruttura'];


$IDsotto=$_SESSION['IDsottotip'];
$time=strip_tags($_GET['time']);

			
$testo='

<div class="pages navbar-fixed">
  <div  class="page">
<div class="navbar" >
               <div class="navbar-inner">
                  <div class="center titolonav">Servizi Sospesi</div>
                  <div class="right" onclick="mainView.router.back();" >
						<a href="#" ><i class="icon f7-icons" >close</i></a>				  
				  </div>
               </div>
            </div>
			
			<div class="page-content" >
			
        <div class="content-block" >
		
		
		 <div class="list-block">
      <ul>
		
		';
		
	

$groupid=getprenot($time,$IDstruttura);
					
										
					switch($IDsotto){
						
						case 2:
						$query2="SELECT  p.ID,p.time,s.servizio,p.IDpren,1,p.IDpers FROM prenextra as p,prenextra2 as p2,servizi as s WHERE p.IDtipo='2'  AND p.IDpren IN($groupid) AND p.modi='0' AND p.ID=p2.IDprenextra AND s.ID=p.extra";
						break;
						default:
						$query2="SELECT  p.ID,p.time,s.servizio,p.IDpren,SUM(p2.qta),p.esclusivo FROM prenextra as p,prenextra2 as p2,servizi as s WHERE p.sottotip='$IDsotto'  AND p.IDpren IN($groupid) AND p.modi='0' AND p.ID=p2.IDprenextra AND s.ID=p.extra GROUP BY p.ID";
						break;
					}
					
					$ins=0;
					$result2=mysqli_query($link2,$query2);
					$num=mysqli_num_rows($result2);
					if($num>0){
						
						while($row2=mysqli_fetch_row($result2)){
							$IDpren=$row2['3'];
							if(is_numeric($IDpren)){
															
								$timees=$row2['1'];
								
								$nome=estrainome($IDpren);
								$esclusivo='';
								if($row2['5']=='1'){
									$esclusivo='Esclusivo';
								}
								$testo.='
								
								<li>
							  <a href="#"  onclick="modificaserv('.$row2['0'].',1,0,1,1);myApp.closeModal();" class="item-link item-content">
								
								<div class="item-inner">
								  <div class="item-title" >
								 <b style="color:#2749dc;"> '.$row2['2'].'</b>
										<br>
										<span style="font-size:12px;">'.$nome.'<br><i>'.estrainomeapp($IDpren).'</i></span>
										
										
									</div>
								  <div class="item-after">'.$row2['4'].'<i class="material-icons" style="font-size:15px;">person</i></div>
								  
								</div>
							  </a>
							</li>
								
							';
							}
						}
					}
					
				
	
	$testo.='
	</ul>
	</div>
	</div>
	</div>
	</div></div>
	';
	
	echo $testo;
?>