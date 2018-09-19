<?php 


header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');



$IDutente=$_SESSION['ID'];
$IDstruttura=$_SESSION['IDstruttura'];

$query="SELECT contratto FROM contratti WHERE IDstruttura='$IDstruttura' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$contratto=$row['0'];
$_SESSION['contratto']=$contratto;

$query="SELECT IDcliente,nome FROM strutture WHERE ID='$IDstruttura' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDmainuser=$row['0'];
$nomestr=$row['1'];
$IDpos=1;

$arrper=array();

$testo='';
if($IDutente==$IDmainuser){
	$IDpos=1;
	$query="SELECT nome FROM clienti WHERE ID='$IDutente' LIMIT 1";
	$result=mysqli_query($link2,$query);
	$row=mysqli_fetch_row($result);
	$nomepers=$row['0'];
	array_push($arrper,'0');
	
	$query="SELECT m.tipo,p.nome FROM mansioni as m,mansionipers as ms,personale as p WHERE ms.IDstruttura='$IDstruttura' AND p.IDuser='$IDutente' AND ms.mansione=m.ID AND p.ID=ms.IDpers";
	$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
	while($row=mysqli_fetch_row($result)){
			$nomepers=$row['1'];
			array_push($arrper,$row['0']);
		}
	}
}else{
	//controllo personale
	$nomepers='';
	
	$query="SELECT nome FROM personale WHERE IDuser='$IDutente' LIMIT 1";
	$result=mysqli_query($link2,$query);
	$row=mysqli_fetch_row($result);
	$nomepers=$row['0'];
	
	$query="SELECT m.tipo FROM mansioni as m,mansionipers as ms,personale as p WHERE ms.IDstruttura='$IDstruttura' AND p.IDuser='$IDutente' AND ms.mansione=m.ID AND p.ID=ms.IDpers";
	$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
	while($row=mysqli_fetch_row($result)){
			array_push($arrper,$row['0']);
		}
	}
}
$query2="SELECT ID FROM personale WHERE IDuser='$IDutente' LIMIT 1";
$result2=mysqli_query($link2,$query2);
$row2=mysqli_fetch_row($result2);
$IDpers=$row2['0'];

$time24=time()-86400;

$query2="SELECT ID FROM notifichetxt WHERE IDpers='$IDpers' AND letto='0'";
$result2=mysqli_query($link2,$query2);
$numnot=mysqli_num_rows($result2);
$badgenot='';
if($numnot==0){
	$badgenot='style="display:none;"';
}

$query2="(SELECT a.ID FROM appunti as a,appuntidest as ad WHERE a.IDstr='$IDstruttura' AND a.ID=ad.IDappunto AND ad.IDdest='$IDutente' AND a.fatto='0') UNION (SELECT ID FROM appunti  WHERE IDstr='$IDstruttura' AND IDcliente='$IDutente'  AND fatto='0') ";
$result2=mysqli_query($link2,$query2);
$numapp=mysqli_num_rows($result2);

$badgeapp='';
if($numapp==0){
	$badgeapp='style="display:none;"';
}


$testo=  '<div data-page="profilo" class="page"> 			
			<div class="navbar" >
				<div class="navbar-inner">
					<div class="center"><img src="scidoob.png" style="width:110px; margin-top:5px;"></div>
				</div>
				</div>
				
				
			<div class="toolbar tabbar tabbar-labels">
			<div class="toolbar-inner">
				<a href="#" class="tab-link" onClick="navigation(12,0,0)">
					<i class="icon f7-icons"  style="color:#666;">book
					
					<span class="badge bg-green" id="badgeapp" '.$badgeapp.'>'.$numapp.'</span>
					</i>
					<span class="tabbar-label">Appunti</span>
				</a>
			
				<a href="#" class="tab-link" onClick="navigation(11,0,0)">
					<i class="icon f7-icons" style="color:#666;">alarm
						<span class="badge bg-red" id="badgenot" '.$badgenot.'>'.$numnot.'</span>
					</i>
					<span class="tabbar-label">Notifiche</span>
				</a>
				
				<a href="#" class="tab-link" onclick="cambiastruttura()">
					<i class="icon f7-icons" style="color:#666;">bars
					</i>
					<span class="tabbar-label">Strutture</span>
				</a>
				
				<a href="#tab4" class="tab-link" onclick="esci();">
					<i class="icon f7-icons" style="color:#666;">logout
					</i>
					<span class="tabbar-label">Esci</span>
				</a>
			</div>
		</div>
				
			 
            <div class="page-content" style="padding:0px; padding-top:18px;"> 
				';
			
			
			
		
	$testo1='<div style="width:100%; margin:0px; padding:0px; padding-top:45px">
	<br>
		<div class="titleb" style="margin-left:20px;">Gestione Strutture</div>
		<div class="list-block inset">
      <ul>
      
	';
	
	
	$txtfunc=array('Calendario','Centro Benessere','Ristorante','Domotica','Pulizie','Arrivi','Clienti','Prenotazioni');
	
	
	
	
	$txtcolor=array('0064d4','c139d1','e77f19','2cb443','d2c823','3539ca','35a2ca','7b8f97');
	$txticon=array('calendar','','','','','list','person','bars');
	$txticonimage=array('','spa','restaurant_menu','flash_on','hotel');
	
	$funzioni=array("navigation(2,0,1)",
	'navigation(4,0,2)',
	'navigation(5,0,3)',
	'navigation(10,1,0)',
	'navigation(6,0,4)',
	'navigation(7,0,5)',
	'navigation(9,0,0)',
	'navigation(8,0,0)');
	
	$abilitate=array();
	
	$felenchi=array();
	$ffunc=array();
	
	foreach ($arrper as $dato){
		$funzione='';
		$txtmenu='';
		switch ($dato){
			case 0:
				array_push($ffunc,0,1,2,3,4);
				array_push($felenchi,5,6,7);
			break;
			case 1:
				array_push($ffunc,2);
			break;
			case 5:
				array_push($ffunc,4);
			break;
			case 3:
			case 2:
			case 4:
				array_push($ffunc,1);
			break;
		}
	}	
	
	$ffunc=array_unique($ffunc);	  
	$felenchi=array_unique($felenchi);	  
	$time24=time();
	
	$query="SELECT tipipos FROM tiposervpos WHERE IDstr='$IDstruttura'  LIMIT 1";
	$result=mysqli_query($link2,$query);
	$row=mysqli_fetch_row($result);
	$arrtipi=explode(',',$row['0']);
	foreach($ffunc as $dato){
		$descr='';
		$pos=1;
		switch($dato){
			case 0:
				$query="SELECT ID FROM appartamenti WHERE IDstruttura='$IDstruttura' LIMIT 1";
				$result=mysqli_query($link2,$query);
				if(mysqli_num_rows($result)>0){
					$query="SELECT COUNT(IDv) FROM prenotazioni WHERE FROM_UNIXTIME(time,'%Y-%m-%d')= FROM_UNIXTIME('$time24','%Y-%m-%d') AND IDstruttura='$IDstruttura' AND stato>='0' ";
					$result=mysqli_query($link2,$query);
					if(mysqli_num_rows($result)>0){
						$row=mysqli_fetch_row($result);
						$descr.='<span class="badge" style=" background-color:#'.$txtcolor[$dato].'; ">'.$row['0'].'</span>';
						//$descr.= 'N.'.$row['0'].' prenotazioni in arrivo';
					}
				}else{
					$pos=0;
				}
			break;
			case 1:
				$sum=0;
				
				//$query="SELECT IDstr FROM tiposervpos WHERE IDstr='$IDstruttura' AND  tipipos LIKE '%,4,%' LIMIT 1";
				//$result=mysqli_query($link2,$query);
				if(in_array('4',$arrtipi)){
					
					$datagg=date('Y-m-d',$time24);
					$IDprensosp=getprenotazioni($datagg,0,$IDstruttura,1,1);
					
					
					$query="SELECT DISTINCT(p.ID) FROM prenextra as p,prenextra2 as p2 WHERE ((FROM_UNIXTIME(p.time,'%Y-%m-%d')= FROM_UNIXTIME('$time24','%Y-%m-%d') AND modi>'0') OR (p.IDpren IN($IDprensosp) AND modi='0')) AND p.IDstruttura='$IDstruttura' AND p.ID=p2.IDprenextra AND p2.qta>'0' AND p.IDtipo IN (2,4) AND p.modi>='0'";
						
					$result=mysqli_query($link2,$query);
                    $sum=mysqli_num_rows($result);
					
					
					if($sum>0){
						$descr.='<span class="badge" style=" background-color:#'.$txtcolor[$dato].'; ">'.$sum.'</span>';
					}	
				}else{
					$pos=0;
				}
				
			break;
			case 2:
				$sum=0;
				//$query="SELECT IDstr FROM tiposervpos WHERE IDstr='$IDstruttura' AND  tipipos LIKE '%,1,%' LIMIT 1";
				//$result=mysqli_query($link2,$query);
				if(in_array('1',$arrtipi)){
					$query="SELECT COUNT(DISTINCT(p.ID)),SUM(p2.qta),p.sottotip FROM prenextra as p,prenextra2 as p2 WHERE FROM_UNIXTIME(p.time,'%Y-%m-%d')= FROM_UNIXTIME('$time24','%Y-%m-%d') AND p.IDstruttura='$IDstruttura' AND p.ID=p2.IDprenextra AND p2.qta>'0' AND p.IDtipo='1' AND p.modi>='0' GROUP BY p.sottotip  ";
					$result=mysqli_query($link2,$query);
                    if(mysqli_num_rows($result)>0){
						while($row=mysqli_fetch_row($result)){
							$IDsottotip=$row['2'];
							$query2="SELECT sottotipologia FROM sottotipologie WHERE ID='$IDsottotip' LIMIT 1";
							$result2=mysqli_query($link2,$query2);
							$row2=mysqli_fetch_row($result2);
							$sottotip=$row2['0'];
							$sum+=$row['0'];
							//$descr.= 'N.'.$row['0'].' '.$sottotip.' ('.$row['1'].'p.) <br>';
						}
					}
					if($sum>0){
						$descr.='<span class="badge" style=" background-color:#'.$txtcolor[$dato].'; ">'.$sum.'</span>';
					}	
				}else{
					$pos=0;
				}
				
				
				
			break;
			case 3:
				if($_SESSION['contratto']<=3){
					$pos=0;
				}
			break;
			
		}
		if($pos==1){
	
			$testo1.= '
			
			
			  <li onclick="'.$funzioni[$dato].'" >
			  <a href="#" class="item-link item-content">
				  <div class="item-media">
				  
				  <div class="roundimg" style=" border:solid 1px #'.$txtcolor[$dato].';">
				
				';
				
				if($txticon[$dato]==''){
					$testo1.='
					<div style="text-align:center;margin-top:5px;"><i class="material-icons" style="color:#'.$txtcolor[$dato].'; font-size:18px;">'.$txticonimage[$dato].'</i></div>';
				}else{
					
					$testo1.='<div style="text-align:center;margin:0px;"><i class="icon f7-icons" style="color:#'.$txtcolor[$dato].'; font-size:15px;">'.$txticon[$dato].'</i></div>';
				}
				$testo1.='
				</div>
				  
				  </div>
				  <div class="item-inner" style="height:50px;">
					<div class="item-title" style="font-size:16px; color:#'.$txtcolor[$dato].'">'.$txtfunc[$dato].'</div>
					<div class="item-after">'.$descr.'</div>
				  </div></a>
				</li>';
				
		}
	}
	$testo1.='
	
	</ul></div>';
	
	if(!empty($felenchi)){
	
			$testo1.='
		<div class="titleb" style="margin-left:20px;">Elenchi</div>
		<div class="list-block inset">
		  <ul>';
		  
		  foreach($felenchi as $dato){
			  
			  $testo1.= '
			
			
			  <li onclick="'.$funzioni[$dato].'" >
			  <a href="#" class="item-link item-content">
				  <div class="item-media">
				  
				  <div class="roundimg" style=" border:solid 1px #'.$txtcolor[$dato].'; ">
				
				';
				
				if($txticon[$dato]==''){
					$testo1.='
					<div style="text-align:center;margin-top:5px;"><i class="material-icons" style="color:#'.$txtcolor[$dato].'; font-size:18px;">'.$txticonimage[$dato].'</i></div>';
				}else{
					
					$testo1.='<div style="text-align:center;"><i class="icon f7-icons" style="color:#'.$txtcolor[$dato].'; font-size:15px;">'.$txticon[$dato].'</i></div>';
				}
				$testo1.='
				</div>
				  
				  </div>
				  <div class="item-inner">
					<div class="item-title" style="font-size:16px; color:#'.$txtcolor[$dato].'">'.$txtfunc[$dato].'</div>
					<div class="item-after">'.$descr.'</div>
				  </div></a>
				</li>';
			  
			  
		  }
		  
		  
		
		
		
		$testo1.='</ul></div>';
	}
	
	$testo1.='</div>';
	
	
	$inc=1;
	
	//include('promemoria.php');
	
	//include('notifiche.php');
	//include('appunti.php');
		
		
	$txtcambia="0";
	$query="SELECT DISTINCT(s.ID),s.nome FROM strutture as s,personale as p WHERE (s.IDcliente='$IDutente') OR (p.IDuser='$IDutente' AND p.IDstr=s.ID)";
		
		
	$result=mysqli_query($link2,$query);
	$evalcambia='';
	if(mysqli_num_rows($result)>0){
		$txtcambia="";
		while($row=mysqli_fetch_row($result)){
			$txtcambia.=$row['0'].'-'.$row['1'].',';
			$evalcambia.='
			buttons.push({
					text: "'.$row['1'].'",
					onClick: function () {
						modcambio('.$row['0'].',3);
					}
				}); 	
			';
		}
	}
	if(strlen($txtcambia>1)){
		$txtcambia="'".$txtcambia."'";
	}
		
	
		
		
	$testo.=$testo1.'<br><br>
	<input type="hidden" value="'.base64_encode($evalcambia).'" id="evalcambia">
	
	<br><br><br><br>
	
	
	
	';
	
	/*
	<div class="list-block">
		  <ul>
		  
		  <li onclick="cambiastruttura('.$txtcambia.')" style="background-color:#e18b00;" >
			  <a href="#" class="item-link item-content">
				  <div class="item-media">
				  </div>
				  <div class="item-inner">
					<div class="item-title" style="font-size:16px; color:#fff;">Cambia Struttura</div>
					<div class="item-after"></div>
				  </div></a>
				</li>
		  </ul></div>
	
	
	<div class="list-block">
		  <ul>
		  
		  <li onclick="esci();" style="background-color:#bc3a30;" >
			  <a href="#" class="item-link item-content">
				  <div class="item-media">
				  </div>
				  <div class="item-inner">
					<div class="item-title" style="font-size:16px; color:#fff;">Esci da Scidoo</div>
					<div class="item-after"></div>
				  </div></a>
				</li>
		  </ul></div>*/
	
	
	
	
	echo $testo;
	
?>