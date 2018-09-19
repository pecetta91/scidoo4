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
			<div class="navbar">
				<div class="navbar-inner">
					<div class="left"><img src="scidoob.png" style="width:100px; margin-top:5px;margin-left:20px"></div>
					
					<div class="right"><i class="icon f7-icons" style="font-size:25px;">gear</i></div>
				</div>
			</div>
				
			
			 
            <div class="page-content" style="padding:0px; padding-top:18px;padding-bottom:40px"> 
				';
			
			
		/*
		<br>
		<div class="titleb" style="widht:80%; margin-left:10px;">Gestione Strutture</div><hr><br><br>*/	
		
	$testo1='<div style="width:100%; margin:0px; padding:0px; padding-top:10px">';
	
	
	
	
	
	
	/*
	$testo1.='
		<table style="width:100%;margin-top:15px; height:70px; background:#f6f6f6; border-top:solid 1px #f1f1f1; border-bottom:solid 1px #f1f1f1;" cellspacing="10"><tr>
<td style="text-align:center;font-weight:300;font-size:15px; border-right:solid 1px #f1f1f1;" width="31%">

<span style="color:#666; text-transform:uppercase; font-size:12px;">Arrivi di oggi</span><br>
<b style="font-size:17px;color:#203baf; ">N. 5</b>

</td>
<td style="text-align:center;font-weight:300;font-size:15px; " width="31%">

<span style="color:#666; text-transform:uppercase; font-size:12px;">Incassi</span><br>
<b style="font-size:17px;color:#203baf;">150 €</b>

</td>

</tr></table>';*/

$testo1.='<div class="content-block" style="padding-bottom:100px;">
<div style="width:92%; margin:auto;">';
	
	
	
	
	
	$nn=0;
	
	$txtfunc=array('Calendario','Benessere','Ristorante','Domotica','Pulizie','Arrivi','Clienti','Prenotazioni');
	
	$txtcolor=array('0064d4','c139d1','e77f19','2cb443','d2c823','3539ca','35a2ca','7b8f97');
	
    $schermo=array('38a7ff','9113fd','e4492b','eeae00','FF2A5F','2eds2z');
    $altezze=array('160','160','160','160','160','160');
	$txtcolor=array('36b5c8','4dc3a9','93d267','fec322','ff824a','de403c','35a2ca','7b8f97');

	
	$txticon=array('calendar','','','','','list','person','bars');
	$txticonimage=array('','spa','restaurant_menu','flash_on','hotel');

   $iconaawe=array('icon ion-android-calendar','icon ion-ios-rose-outline','icon ion-android-restaurant','icon ion-ios-bolt-outline','icon ion-ios-home-outline');
	
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


	$hhsx=0;
	$hhdx=0;

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
			$sx=592;
			$float='float:left;';
			if($hhsx!=0){
				if($hhdx<$hhsx){
					$float='float:right;';
					$sx=600;
				}
			}
			
			if($sx==592){
				$hhsx+=$altezze[$dato];
			}else{
				$hhdx+=$altezze[$dato];
			}
					//background:#'.$schermo[$dato].';	
		$testo1.='
		<div  onclick="'.$funzioni[$dato].'"class="schermob" style="'.$float.';
	      border-radius:2px; border:solid 1px #e1e1e1;   height:135px; text-align:center; margin-top:9px;">
		
				<div style=" margin:auto; margin-top:20px; line-height:54px; height:50px; width:50px;  border-radius:50%; background:#'.$schermo[$dato].';"><i class="'.$iconaawe[$dato].'" style="font-size:30px; color:#fff;"></i></div>
				 <div style="color:#'.$schermo[$dato].'; font-size:16px; text-align:center; margin-top:20px;text-transform:uppercase; font-weight:100;">'.$txtfunc[$dato].'</div>
					
		</div>	';
			
			
			
	
			
			$nn++;
		}
	}



/* $testo1.='


<div class="content-block">
 
 	<div style="width:99%; margin:auto;">';

for($i=0;$i<6;$i++) 
{
	if($i%2)
	{
		//left
		$testo1.='
		<div  onclick="'.$funzioni[$i].'" style="border-left:0px;  height:180px; text-align:center; background-color:#38a7ff; border-radius:3px; width: calc(50% - 6px); float:right; display:inline-block; margin-left:4px; margin-top:4px;">
		
				<div style=" margin:auto; margin-top:30px; line-height:54px; height:50px; width:50px;  border-radius:50%; background:#fff;"><i class="'.$iconaawe[$i].'" style="font-size:30px; color:#38a7ff;"></i></div>
				 <div style="color:#fff; font-size:12px; margin-top:30px;text-transform:uppercase; font-weight:600;">'.$txtfunc[$i].'</div>
					
		</div>	';
	}
	else{
		//right
		$testo1.='
		<div  onclick="'.$funzioni[$i].'" style="  border-left:0px;  height:140px; text-align:center; background-color:#ea6248; border-radius:3px; width: calc(50% - 6px); margin-left:4px; float:left; display:inline-block;margin-top:4px;">
		
				<div style=" margin:auto; margin-top:30px; line-height:54px; height:50px; width:50px;  border-radius:50%; background:#fff;"><i class="'.$iconaawe[$i].'" style="font-size:30px; color:#ea6248;"></i>
				
				</div>
				 <div style="color:#fff; font-size:12px; margin-top:30px;text-transform:uppercase; font-weight:600;">'.$txtfunc[$i].'</div>
					
		</div>
		';
	}
}
*/
$testo1.='
<div style="float:left; width:100%; height:50px;">
</div>
</div></div>';

/*
$testo1.='





 <div class="content-block">
 
 	<div style="width:99%; margin:auto;">
 
        <div  onclick="'.$funzioni['0'].'" style="border-left:0px;  height:180px; text-align:center; background-color:#38a7ff; border-radius:3px; width: calc(50% - 6px); float:left; display:inline-block; margin-left:4px;">
		
				<div style=" margin:auto; margin-top:30px; line-height:54px; height:50px; width:50px;  border-radius:50%; background:#fff;"><i class="'.$iconaawe['0'].'" style="font-size:30px; color:#38a7ff;"></i></div>
				 <div style="color:#fff; font-size:12px; margin-top:30px;text-transform:uppercase; font-weight:600;">'.$txtfunc['0'].'</div>
					
		</div>			
        
		<div  onclick="'.$funzioni['1'].'" style="  border-left:0px;  height:140px; text-align:center; background-color:#ea6248; border-radius:3px; width: calc(50% - 6px); margin-left:4px; float:right; display:inline-block; ">
		
				<div style=" margin:auto; margin-top:30px; line-height:54px; height:50px; width:50px;  border-radius:50%; background:#fff;"><i class="'.$iconaawe['1'].'" style="font-size:30px; color:#ea6248;"></i>
				
				</div>
				 <div style="color:#fff; font-size:12px; margin-top:30px;text-transform:uppercase; font-weight:600;">'.$txtfunc['1'].'</div>
					
		</div>
		
		<div onclick="'.$funzioni['2'].'" style="border-left:0px;  height:180px; text-align:center; background-color:#38a7ff; border-radius:3px; width: calc(50% - 6px); float:right; display:inline-block; margin-left:4px;margin-top:4px;">
		
				<div style=" margin:auto; margin-top:30px; line-height:54px; height:50px; width:50px;  border-radius:50%; background:#fff;"><i class="'.$iconaawe['2'].'" style="font-size:30px; color:#38a7ff;"></i></div>
				 <div style="color:#fff; font-size:12px; margin-top:30px;text-transform:uppercase; font-weight:600;">'.$txtfunc['2'].'</div>
					
		</div>
		
		<div  onclick="'.$funzioni['3'].'" style="  border-left:0px;  height:140px; text-align:center; background-color:#ea6248; border-radius:3px; width: calc(50% - 6px); margin-left:4px; float:left; display:inline-block;margin-top:4px; ">
		
				<div style=" margin:auto; margin-top:30px; line-height:54px; height:50px; width:50px;  border-radius:50%; background:#fff;"><i class="'.$iconaawe['3'].'" style="font-size:30px; color:#ea6248;"></i>
				
				</div>
				 <div style="color:#fff; font-size:12px; margin-top:30px;text-transform:uppercase; font-weight:600;">'.$txtfunc['3'].'</div>
					
		</div>
		
		 <div  onclick="'.$funzioni['4'].'" style="border-left:0px;  height:180px; text-align:center; background-color:#38a7ff; border-radius:3px; width: calc(50% - 6px); float:left; display:inline-block; margin-left:4px;margin-top:4px;">
		
				<div style=" margin:auto; margin-top:30px; line-height:54px; height:50px; width:50px;  border-radius:50%; background:#fff;"><i class="'.$iconaawe['4'].'" style="font-size:30px; color:#38a7ff;"></i></div>
				 <div style="color:#fff; font-size:12px; margin-top:30px;text-transform:uppercase; font-weight:600;">'.$txtfunc['4'].'</div>
					
		</div>
		
		
		
		
		
		
		
		
		
		
	  	
       
  </div>
	  </div>



';
	
*/	
	
	
	/*
	
	if(!empty($felenchi)){
	
		  
		  foreach($felenchi as $dato){
			  
				
				$nn++;
				
				
				
				
				
				
				$testo1.= '
			
			
				  <div onclick="'.$funzioni[$dato].'" style="width:33%; float:left; height:130px; display:inline-block; margin:0px;">
				  <div style=" background:#'.$txtcolor[$dato].'; background:#fff; box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.2);
  width:103px; padding:0px; height:103px; border-radius:5px; padding:10px; text-align:left;" >
				
				';
				
				if($txticon[$dato]==''){
					$testo1.='
					
					<div style="font-weight:600;  color:#555; text-align:center; color:#555; font-size:13px; text-transform:uppercase; ">'.$txtfunc[$dato].'</div>
					<div style=" text-align:center; margin:auto; margin-top:13px; width:65px;  height:65px; border-radius:50%; background-color:#'.$txtcolor[$dato].';">
					
					<i class="icon f7-icons" style="color:#fff;line-height:60px;font-size:30px;">'.$txticon[$dato].'</i>
					
					</div>
					
					';
				}else{
					$testo1.='
					
					
					<div style=" text-align:center; margin:auto; margin-top:13px; width:65px;  height:65px; border-radius:50%; background-color:#'.$txtcolor[$dato].';">
					
					<i class="icon f7-icons" style="color:#fff;line-height:65px;font-size:30px;">'.$txticon[$dato].'</i>
					
					</div>
					
					<div style="font-weight:600;  color:#555; text-align:center; color:#555; font-size:13px; text-transform:uppercase; ">'.$txtfunc[$dato].'</div>
					
					
					';
					//$testo1.='<i class="icon f7-icons" style="color:#fff; font-size:35px;">'.$txticon[$dato].'</i>';
				}
				$testo1.='
				</div>
				  
				  </div>
				  ';
				
				
				
			  
			  
		  }
		
	}
	
	
	$rest=3-($nn%3);
	for($i=0;$i<$rest;$i++){
		$testo1.='<div  style="width:31%; height:150px; display:inline-block; margin:0px;"></div>';
	}
	
	
	*/

	
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
		
	
		
		
	$testo.=$testo1.'
	<input type="hidden" value="'.base64_encode($evalcambia).'" id="evalcambia">
	
	
	
	
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
$testo.='

<br><br><br><br>

</div></div>
<div class="toolbar" style="border-top:solid 1px #e0e0e0; height:43px;">
    <div class="toolbar-inner" style="text-align:center;padding-top:10px;">
       <div href="#"  onClick="navigation(11,0,0)" style=" text-align:center; height:44px; width:24%;">
					<i class="icon ion-ios-bell-outline" style="color:#888;font-size:31px; >
						<span class="badge bg-red" id="badgenot" '.$badgenot.'>'.$numnot.'</span>
					</i><br/>
						
					
				</div>
		<div href="#" onClick="navigation(12,0,0)" style=" text-align:center; height:44px; width:24%;">
					<i class="icon ion-ios-book-outline" style="color:#888;font-size:31px;>
						<span class="badge bg-red" id="badgenot" '.$badgenot.'>'.$numnot.'</span>
					</i><br/>
						
				</div>
		<div href="#" onclick="cambiastruttura()" style=" text-align:center; height:44px; width:24%;">
					<i class="icon ion-android-menu" style="color:#888;font-size:31px;>
						<span class="badge bg-red" id="badgenot" '.$badgenot.'>'.$numnot.'</span>
					</i><br/>
					
				</div>
				
		
				
			<div href="#"  onClick="navigation(11,0,0)" style=" text-align:center; height:44px; width:24%;">
					<i class="icon ion-android-exit" style="color:#888;font-size:31px;">
						<span class="badge bg-red" id="badgenot" '.$badgenot.'>'.$numnot.'</span>
					</i><br/>
						
				</div>
	
    </div>
</div>
	
';	
	
	
	echo $testo;
	
?>