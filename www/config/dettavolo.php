<?php
header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');
include('../../config/funzionilingua.php');

$IDutente=$_SESSION['ID'];
$IDstruttura=$_SESSION['IDstruttura'];
unset($_SESSION['listIDsotto']);
unset($_SESSION['datecentro']);


$IDprenextra=$_GET['dato0'];
	
	
	
$query="SELECT time,IDpren,sottotip FROM prenextra WHERE ID='$IDprenextra' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$timeprenextra=$row['0'];
$IDpren=$row['1'];
$IDsottotip=$row['2'];

$time=$timeprenextra;

$IDprenunit=prenotstessotav($IDpren);
$IDprenunitmain=$IDprenunit;

$query4="SELECT p2.IDprenextra,p2.prezzo,s.servizio,p2.qta FROM prenextra2 as p2,prenextra as p,servizi as s WHERE p2.pacchetto='-$IDprenextra' AND p2.IDprenextra=p.ID AND p.extra=s.ID";
$result4=mysqli_query($link2,$query4);
$numadd2=mysqli_num_rows($result4);

$badge='';
if($numadd2>0){
	$numadd='<span style="font-size:15px;">'.$numadd2.'</span>';
	$badge='<div class="badge bg-red bagristo">'.$numadd2.'</div>';
							
}



$testo=  '
<div class="pages navbar-fixed">
  <div data-page="dettavolo" class="page with-subnavbar">

			<input type="hidden" id="IDprenfunc" value="'.$id.'">
			<input type="hidden" id="IDprentime" value="'.$time.'">


		<div class="navbar">
      <div class="navbar-inner">
        
		<div class="left" style="margin-left:5px;"> <a href="#" class="link back" >
				<i class="material-icons" style="font-size:40px;">chevron_left</i>
						</a></div>
					<div class="center titolonav">'.estrainome($IDpren).'</div>
					<div class="right" >
					</div>
        <div class="subnavbar">
          <div class="buttons-row">
            <a href="#tab1" class="button tab-link detta active" onclick="IDtabac='."'tab1';".'" >Tavolo</a>
			<a href="#tab2" class="button tab-link detta" style="overflow:visible;" onclick="IDtabac='."'tab2';".'">Piatti e Bevande'.$badge.'</a>
			
          </div>
        </div>
      </div>
    </div>	
<div class="page-content" > 
			
 <div class="content-block"> 
			  
			  <input type="hidden" id="funccentro5" value="navigation(15,'.$IDprenextra.',8,1)">
			  
			  
			  <div class="tabs-animated-wrap">
        
				<div class="tabs">
				  <div id="tab1" class="tab active">';
				  
				  
				  $testo.='
				  
				  
				  <div class="list-block">
				  <ul>
					<li>
					  <a href="#" class="item-link item-content" onclick="modificaserv('.$IDprenextra.',1,0,0)">
						<div class="item-inner">
						  <div class="item-title">Data e Ora</div>
						  <div class="item-after">'.dataita($timeprenextra).' '.date('H:i',$timeprenextra).'</div>
						</div>
					  </a>
					</li>
					</ul></div>
				  
				  
				  
				  
				  
				  
				  
				  <div class="list-block" ><ul>';
	
	
		$query2="SELECT p2.IDinfop,p.extra  FROM prenextra as p,prenextra2 as p2 WHERE FROM_UNIXTIME(p.time,'%Y-%m-%d')=FROM_UNIXTIME('$time','%Y-%m-%d')  AND p.IDstruttura='$IDstruttura' AND  p.sottotip='$IDsottotip' AND p2.IDprenextra=p.ID AND p.modi>='0' AND p.IDpren IN($IDprenunit)  ";
		$result2=mysqli_query($link2,$query2);
		if(mysqli_num_rows($result2)>0){
			while($row=mysqli_fetch_row($result2)){
				$IDinfop=$row['0'];
				$extra=$row['1'];
				$query3="SELECT servizio FROM servizi WHERE ID='$extra' LIMIT 1";
				$result3=mysqli_query($link2,$query3);
				$row3=mysqli_fetch_row($result3);
				$servizio=$row3['0'];
				
				$nome=estrainomecli($IDinfop);
				$tipocli=estraitipocli($IDinfop);
				
				$notecli='';
				$query3="SELECT s.noteristo FROM infopren as i,schedine as s WHERE i.ID ='$IDinfop' AND i.IDcliente=s.ID AND s.noteristo!='' LIMIT 1";
				$result3=mysqli_query($link2,$query3);
				if(mysqli_num_rows($result3)>0){
					$row3=mysqli_fetch_row($result3);
					$notecli='<br><b style="color:#bb2c1d;">'.$row3['0'].'</b>';
				}
				
				$testo.='
				 <li class="item-content">
				  <div class="item-inner">
					<div class="item-title">'.$nome.'<br><span style="font-size:10px;font-weight:400; color:#999;">'.$tipocli.''.$notecli.'</span></div>
					<div class="item-after">'.$servizio.'</div>
				  </div>
				</li>
				';
				
			}
		}
		
		$testo.='</ul></div>';
				  
				  
				  $testo.='</div>
		 
				  <div id="tab2" class="tab">
				  
				
				  
				  <div class="list-block">
  					<ul>
				  
				  ';
				  
				  
				  if($numadd2>0){
			
					//$testo.='<table class="tabprod">';
					while($row4=mysqli_fetch_row($result4)){
						$testo.='
						
						 <li class="swipeout">
						  <div class="swipeout-content item-content">
							<div class="item-media">N.'.$row4['3'].'</div>
							<div class="item-inner">
								<div class="item-title">'.$row4['2'].'</div>
								<div class="item-after">'.$row4['1'].'€</div>
							</div>
						  </div>
						  <div class="swipeout-actions-right">
							<a href="#" onClick="msgboxelimina('.$row4['0'].',33,'.$IDprenextra.',0,0)" class="action1  bg-red">Elimina</a>
						  </div>
						</li>';
						
						/*
						
						
						<tr><td>N.'.$row4['3'].'</td><td>'.$row4['2'].'</td><td>'.$row4['1'].'€</td><td style="width:20px;">
						<a href="#" class="button button-fill  color-red" style="color:#fff;" >X</a>
						
						</td></tr>';*/
					}
					
				}else{
					$testo.='
					 <li class="item-content">
					  <div class="item-inner">
						<div class="item-title">Nessun prodotto aggiunto</div>
					  </div>
					</li>
					';
				}
				
				$testo.='</ul></div><br>  <a href="#"  class="button button-fill button-raised color-orange" style="font-size:14px; width:80%; color:#fff; margin:auto;" onclick="addprodotto('."'".$IDpren.','.$IDprenextra."'".',0)">Aggiungi Prodotto</a>
				
				
				';
				  
				  
				  
				  
				  $testo.='</div>
		      
				</div>
				
			  </div> 
			</div>
			  
			  
			  
			
			  ';
			  
	
			
			
	
	
			  
			  
			$testo.= '</div>
				
			
			</div></div>
				 
					 
					 
			
			';
			  echo $testo;
			 