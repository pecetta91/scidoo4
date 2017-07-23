<?php
header('Access-Control-Allow-Origin: *');

$ID=$_GET['ID'];
$tipol=$_GET['tipo'];
$tipol2=$tipol+1;
$multi=$_GET['multi'];
$claadd='';
include('../../config/connecti.php');
include('../../config/funzioni.php');

$IDstruttura=$_SESSION['IDstruttura'];

$height=30;
$colh=38;
$ok=0;

$txtsend=$ID;

$arr=explode(',',$ID);

echo '<input id="prenextrasel" value="'.$ID.'" type="hidden">';

$arrID=array();
$arrtime=array();
$arrmodi=array();
$arrqta=array();
$arrsala=array();

$arrsottot=array();
$tipolim=0;
$totale=0;
$pp=0;
$first=0;
$firstinfo=0;

foreach($arr as $dato){
	if(strlen($dato)>0){

		$arr2=explode('/',$dato);
		$IDgroup=$arr2['0'];
		$IDinfop=$arr2['1'];
	
		$query="SELECT ID,extra,time,modi,tipolim,sala,IDtipo,sottotip FROM prenextra WHERE ID IN($IDgroup)";
		$result=mysqli_query($link2,$query);
		if(mysqli_num_rows($result)>0){
			$prezzou2=0;
			while($row=mysqli_fetch_row($result)){
				$IDtipo=$row['6'];
				$IDp=$row['0'];
				$tipolim=$row['4'];
				$arrtime[$IDp]=$row['2'];
				$arrmodi[$IDp]=$row['3'];
				$arrsala[$IDp]=$row['5'];				
				if($first==0){
					$first=$IDp;
					$firstinfo=$IDinfop;
				}
				
				if(($row['6']==2)||($row['6']==1)){
					$arrsottot[$IDp]=$row['6'];
				}else{
					$arrsottot[$IDp]=$row['7'];
				}
				if(($tipolim=='5')||($tipolim=='7')||($tipolim=='8')){
					$pacchetto=$row['1'].'/'.$IDp;
					
					$query2="SELECT SUM(prezzo),SUM(qta),COUNT(*) FROM prenextra2 WHERE pacchetto='$pacchetto' AND IDinfop='$IDinfop' AND paga='1'";
					$result2=mysqli_query($link2,$query2);
					$row2=mysqli_fetch_row($result2);
					$prezzo=$row2['0'];
					$div=$row2['2'];
					if($div==0)$div=1;
					
					if(isset($arrprice[$IDp])){
						$arrprice[$IDp]+=round($prezzo,2);
						$arrID[$IDp].=','.$IDinfop;
						$arrqta[$IDp]+=round($row2['1']/$div);
					}else{
						$arrprice[$IDp]=round($prezzo,2);
						$arrID[$IDp]='0,'.$IDinfop;
						$arrqta[$IDp]=round($row2['1']/$div);
					}
					
					//$arrprice[$IDp][$IDinfop]=$prezzo;
				}else{
					
					if($tipolim=='6'){
						$query2="SELECT prezzo,pacchetto,qta FROM prenextra2 WHERE IDprenextra='$IDp'";
						$result2=mysqli_query($link2,$query2);
						$row2=mysqli_fetch_row($result2);
						$prezzo=$row2['0'];
						$pacchetto=$row2['1'];
						$qta=$row2['2'];
						$arrqta[$IDp]=$qta;
						$arrprice[$IDp]=round($prezzo,2);
						$arrID[$IDp]=$qta.'/'.$pacchetto;
						
					}else{
						$query2="SELECT prezzo,qta FROM prenextra2 WHERE IDprenextra='$IDp' AND IDinfop='$IDinfop' AND paga='1' LIMIT 1";
						$result2=mysqli_query($link2,$query2);
						$row2=mysqli_fetch_row($result2);
						$prezzo=$row2['0'];
						
						if(isset($arrprice[$IDp])){
							$arrprice[$IDp]+=round($prezzo,2);
							$arrID[$IDp].=','.$IDinfop;
							$arrqta[$IDp]+=$row2['1'];
						}else{
							$arrprice[$IDp]=round($prezzo,2);
							$arrID[$IDp]='0,'.$IDinfop;
							$arrqta[$IDp]=$row2['1'];
						}
					}
					//$arrprice[$IDp][$IDinfop]=$prezzo;
				}
			}
		}
	}
}

$arrinfo=array();

$datacarfra=array('Sposta su Extra','Sposta su Iniziale');
$datacaricon=array('movedicon','moveicon');

foreach ($arrID as $key =>$dato){
	$dato=substr( $dato, 2);
	$arrId[$key]=$dato;
	if(strlen($dato)>0){
		if(!isset($arrinfo[$key])){
			$arrinfo[$key]='0';
		}
		$arr3=explode(',',$dato);
		foreach ($arr3 as $key2 =>$dato2){
			$arrinfo[$key].=','.$key.'/'.$dato2;
		}
		$arrinfo[$key]=substr( $arrinfo[$key], 2);
	}
}

$query="SELECT datacar FROM prenextra2 WHERE IDprenextra='$first' AND IDinfop='$firstinfo' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$datacar=$row['0'];


if(($tipolim!='7')&&($tipolim!='8')){
	$query="SELECT s.servizio,p.IDpren,p.extra FROM prenextra as p,servizi as s WHERE p.ID='$first' AND p.extra=s.ID LIMIT 1";
	$result=mysqli_query($link2,$query);
	$row=mysqli_fetch_row($result);
	$servizio=$row['0'];
	$IDpren=$row['1'];
	$extra=$row['2'];

}else{
	$query="SELECT IDpren,extra FROM prenextra  WHERE ID='$first' LIMIT 1";
	$result=mysqli_query($link2,$query);
	$row=mysqli_fetch_row($result);
	$IDpren=$row['0'];
	$extra=$row['1'];
	if($tipolim=='7'){
		$query="SELECT idea FROM ideeregalosold  WHERE ID='$extra' LIMIT 1";
	}
	if($tipolim=='8'){
		$query="SELECT codice FROM cofanettivend WHERE IDprenextra='$first' LIMIT 1";
	}
	$result=mysqli_query($link2,$query);
	$row=mysqli_fetch_row($result);
	$servizio=$row['0'];	
	
}
$multi=count($arrprice);

	
	
	
	if($multi>1){
		
		$txt='<div class="list-block" style="background:#f1f1f1;" >
			  <ul>';

			if(count($arrID)>1){
				$elagg=1;
			}else{
				$elagg=11;
			}
			switch($tipolim){
				case 3:
				case 1:
				
			foreach ($arrID as $key=>$dato){
				
					$time=$arrtime[$key];
					$modi=$arrmodi[$key];
					$IDsala=$arrsala[$key];
					$nomesala='';
					$query3="SELECT nome FROM sale WHERE ID='$IDsala' LIMIT 1";
					$result3=mysqli_query($link2,$query3);
					if(mysqli_num_rows($result3)>0){
						$row3=mysqli_fetch_row($result3);
						$nomesala=$row3['0'];
					}
					
					$pagato=0;
					$numnon=0;
					
					$query2="SELECT pagato FROM prenextra2 WHERE IDprenextra='$key'  AND pacchetto<='0'";
					$result2=mysqli_query($link2,$query2);
					if(mysqli_num_rows($result2)>0){
						while($row2=mysqli_fetch_row($result2)){
							if($row2['0']!=0){
								++$pagato;
							}else{
								++$numnon;
							}
						}
					}
					$butt6='';
					
					if(($pagato!=0)&&($numnon!=0)){
						$butt6='<button class="shortcut  mini10 warning portacassaw popover" ><span>Servizio pagato in parte</span></button>';
					}
					if($pagato==0){
						$butt6='<button class="shortcut  mini10 whites portacassa popover"><span>Servizio non pagato</span></button>';
					}
					if(($pagato!=0)&&($numnon==0)){
						$butt6='<button class="shortcut  mini10 success portacassaw popover" ><span>Servizio saldato completamente</span></button>';
					}
					
					
					$qtabutt='Per '.$arrqta[$key].' persone';
					/*
					$modb='<li><a href="#" class="list-button item-link" onclick="modificaserv('.$key.',1,0,'."'".$txtsend."'".')">Modifica Orario</a></li>
					<li><a href="#" class="list-button item-link" id="deleteb" onclick="msgboxelimina('.$key.',3,'."'".$txtsend."'".',1,1);">Elimina</a></li>
					';*/
					
					$num2=datafunc2($time,$modi,$tipolim,'',$key);
					$num=1;
					$txt.='
						<li class="IDtipol'.$IDtipo.'" id="tr'.$key.'" lang="'.$arrinfo[$key].'" onclick="aprimod('.$key.',this)">
						  <div  class="item-content">
							<div class="item-inner">
							  <div class="item-title-row">
								<div class="item-title">'.$servizio.'<br><span style="font-size:11px; color:#666;">'.$qtabutt.'<br>'.$nomesala.'</span></div>
								<div style="font-size:13px; color:#777;" align="right" >'.$arrprice[$key].' €<br>'.$num2.'</div>
							  </div>
							</div>
						  </div>
						  <div class="swipeout-actions-right">
						  	<a href="#" onclick="modrestr('.$key.',0);" class="action1 bg-orange"><i class="material-icons">euro_symbol</i></a>
						   <a href="#"onclick="msgboxelimina('.$key.',3,'."'".$txtsend."'".',1,1);" class="action1 bg-red"><i class="material-icons">delete_forever</i></a>
						  </div>
						</li>';
						/*
						$txt.='
				 
				
				
					
					
					
					
					<tr id="tr'.$key.'"  lang="'.$arrinfo[$key].'" ><td>'.$servizio.'</td>
					
					<td class="sala">'.$nomesala.'</td>
					<td>'.datafunc($time,$modi,$tipolim,'modserv2('.$time.','.$arrsottot[$key].','.$key.')',$key).'</td>
					
					<td>
					<div class="iconuser" alt="'.$key.'">'.$arrqta[$key].'</div></td>
					
					<td><input type="text" value="'.$arrprice[$key].'" class="bnone pricetab ptb" onchange="modprenot('.$key.','."'".$key."'".',98,1,38)" id="'.$key.'"></td>
					<td><button class="shortcut mini10 popover  settingmod4" onclick="modifIDp('.$key.',this,'.$tipolim.','.$datacar.',1,1)"><span>Altre Funzioni</span></button></td>
					<td>'.$butt6.'</td>
					<td><button class="shortcut mini10 popover danger del3icon" onclick="msgboxelimina('.$key.',3,1,'.$elagg.',0);prenfunc=1;" ><span>Elimina</span></button></td></tr>';
					$height+=$colh;
					*/
					
			}

	
		break;
		case 2:
		case 4:
	
			
			
			foreach ($arrID as $key=>$dato){
				
					$time=$arrtime[$key];
					$modi=$arrmodi[$key];
					$IDsala=$arrsala[$key];
					$nomesala='';
					$query3="SELECT nome FROM sale WHERE ID='$IDsala' LIMIT 1";
					$result3=mysqli_query($link2,$query3);
					if(mysqli_num_rows($result3)>0){
						$row3=mysqli_fetch_row($result3);
						$nomesala=$row3['0'];
					}
				
					
					$pagato=0;
					$numnon=0;
					
					$query2="SELECT pagato FROM prenextra2 WHERE IDprenextra='$key'  AND pacchetto<='0'";
					$result2=mysqli_query($link2,$query2);
					if(mysqli_num_rows($result2)>0){
						while($row2=mysqli_fetch_row($result2)){
							if($row2['0']!=0){
								++$pagato;
							}else{
								++$numnon;
							}
						}
					}
					$butt6='';
					
					
					if(($pagato!=0)&&($numnon!=0)){
						$butt6='<button class="shortcut  mini10 warning portacassaw popover" ><span>Servizio pagato in parte</span></button>';
					}
					if($pagato==0){
						$butt6='<button class="shortcut  mini10 whites portacassa popover"><span>Servizio non pagato</span></button>';
					}
					if(($pagato!=0)&&($numnon==0)){
						$butt6='<button class="shortcut  mini10 success portacassaw popover" ><span>Servizio saldato completamente</span></button>';
					}
					
					
					$button='';
					$buttonora='';
					$sostbutt='';//vis2('.$row['0'].','.$tipol2.',1,-1)
					if($tipolim==2){
						$sostbutt='<button class="shortcut primary changeicon mini" onclick="addserv(7,'.$key.',0,'.$tipolim.');" alt="Sostituisci"></button>';
					}
				
					$qtabutt='Per '.$arrqta[$key].' persone';
					$num2=datafunc2($time,$modi,$tipolim,'',$key);
					$num=1;
					
					$modb='<li><a href="#" class="list-button item-link" onclick="modificaserv('.$key.',1,0,'."'".$txtsend."'".')">Modifica Orario</a></li>
					<li><a href="#" class="list-button item-link" id="deleteb" onclick="msgboxelimina('.$key.',3,'."'".$txtsend."'".',1,1);">Elimina</a></li>
					';
					
					
					
					
					$txt.='
						<li class="IDtipol'.$IDtipo.'" onclick="aprimod('.$key.',this)" id="tr'.$key.'"  lang="'.$arrinfo[$key].'">
						  <a href="#" class="item-link item-content">
							<div class="item-inner">
							  <div class="item-title-row">
								<div class="item-title titlemain" style="line-height:14px;"><b>'.$servizio.'</b><br><span style="font-size:11px; color:#666;">'.$qtabutt.'<br>'.$nomesala.'</span></div>
								<div style="font-size:13px; color:#777;" align="right" >'.$arrprice[$key].' €<br>'.$num2.'</div>
							  </div>
							</div>
						  </a>
						   <div id="menu'.$key.'" style="display:none;" >'.base64_encode($modb).'</div>
						 </li>';
					
					/*
					$txt.='<tr id="tr'.$key.'" lang="'.$arrinfo[$key].'"><td>'.$servizio.'</td>
					<td class="sala">'.$nomesala.'</td>
					<td>'.datafunc($time,$modi,$tipolim,'modserv2('.$time.','.$arrsottot[$key].','.$key.')',$key).'</td>
					<td><div class="iconuser" alt="'.$key.'">'.$arrqta[$key].'</div></td>
					
					<td><input type="text" value="'.$arrprice[$key].'" class="bnone pricetab ptb" onchange="modprenextra('.$key.','."'".$key."'".',18,11,18)" id="'.$key.'">
					
					</td>
					<td><button class="shortcut mini10 popover  settingmod4" onclick="modifIDp('.$key.',this,'.$tipolim.','.$datacar.',1,1)"><span>Altre Funzioni</span></button></td>
					<td>'.$butt6.'</td>
					<td>';
					if($tipolim=='2'){
						$txt.='<button class="shortcut mini10 danger del3icon popover" onclick="msgboxelimina('.$key.',3,1,'.$elagg.',0);prenfunc=1;" ><span>Elimina</span></button>';
					}
					$txt.='</td></tr>';
					$height+=$colh;
					*/
					

			}

			
		break;
		case 5:
			
			foreach ($arrID as $key=>$dato){
				
					$time=$arrtime[$key];
					$modi=$arrmodi[$key];
					
					
					
					$pagato=0;
					$numnon=0;
					
					$query2="SELECT pagato FROM prenextra2 WHERE IDprenextra='$key'  AND pacchetto<='0'";
					$result2=mysqli_query($link2,$query2);
					if(mysqli_num_rows($result2)>0){
						while($row2=mysqli_fetch_row($result2)){
							if($row2['0']!=0){
								++$pagato;
							}else{
								++$numnon;
							}
						}
					}
					$butt6='';
					
					if(($pagato!=0)&&($numnon!=0)){
						$butt6='<button class="shortcut  mini10 warning portacassaw popover" ><span>Servizio pagato in parte</span></button>';
					}
					if($pagato==0){
						$butt6='<button class="shortcut  mini10 whites portacassa popover"><span>Servizio non pagato</span></button>';
					}
					if(($pagato!=0)&&($numnon==0)){
						$butt6='<button class="shortcut  mini10 success portacassaw popover" ><span>Servizio saldato completamente</span></button>';
					}
					
					
					
					
					
					$qtabutt='Per '.$arrqta[$key].' persone';
					
					$num2=datafunc2($time,$modi,$tipolim,'',$key);
					$num=1;
					
					$vis='<div class="numb" style="width:30px; height:30px; line-height:30px;">1</div>';
					//datafunc2($time,$row['7'],$tipolim,'modserv2('.$time.','.$sottot.','.$ID.')',$ID);
					
					$txt.='
						<li class="accordion-item IDtipol'.$IDtipo.'" id="tr'.$key.'"  lang="'.$arrinfo[$key].'" >
						  <a href="#" class="item-link item-content" onclick="vis2('.$key.',1,'.$num.',1);" >
							<div class="item-inner" style="background:none;">
							  <div class="item-title-row">
								<div class="item-title titlemain" style="line-height:14px;"><b>'.$servizio.'</b><br><span style="font-size:11px; color:#666;">'.$qtabutt.'</span></div>
								<div style="font-size:13px; color:#777;" align="right"><b>'.$arrprice[$key].' €</b><br>'.$num2.'</div>
							  </div>
							</div>
						  </a>
						  <div class="accordion-item-content" style="padding:0px; width:100%;">
								<div class="content-block details" id="into1-'.$key.'">
								 ...
								</div>
							  </div>
						  
						  
						</li>';
					
					
					
					/*
					
					$txt.='<tr id="tr'.$key.'" lang="'.$arrinfo[$key].'">
					<td><button class="shortcut mini10 info popover" onclick="vis2('.$key.','.$tipol2.',1,2);" alt="Visualizza contentuo">1+</button></td>
					<td>'.$servizio.'</td><td>'.datafunc($time,$modi,5,0,0).'</td>
					
					<td><div class="iconuser" alt="'.$key.'">'.$arrqta[$key].'</div></td>
					
					<td>
					<input type="text" value="'.$arrprice[$key].'" class="bnone pricetab ptb" onchange="modprenextra('.$key.','."'".$key."'".',18,11,18)" id="'.$key.'">
					
					</td>
					<td><button class="shortcut mini10 popover  settingmod4" onclick="modifIDp('.$key.',this,'.$tipolim.','.$datacar.',1,1)"><span>Altre Funzioni</span></button></td><td>'.$butt6.'</td><td><button class="shortcut mini10 danger del3icon popover" onclick="msgboxelimina('.$key.',3,1,'.$elagg.',0);prenfunc=1;"><span>Elimina</span></button></td></tr>
					<tr><td colspan="8"  align="right"><div id="into'.$tipol2.'-'.$key.'" class="details"></div></td></tr>
					';
		
					$height+=$colh;*/

			}
		break;
		case 6:
			
			
			$arrID[$IDp]=$qta.'/'.$pacchetto;
			
			if(count($arrID)>1){
				$elagg=1;
			}else{
				$elagg=11;
			}
			
			
			foreach ($arrID as $key=>$dato){
					$arr3=explode('/',$dato);
					
					$qta=$arr3['0'];
					$pacchetto=$arr3['1'];
					
					if($pacchetto==0){
						$dove="(Al minuto)";
					}else{
						$dove="(Al tavolo)";
					}
					$time=$arrtime[$key];
					$modi=$arrmodi[$key];
				
				
					$pagato=0;
					$numnon=0;
					
					$query2="SELECT pagato FROM prenextra2 WHERE IDprenextra='$key'  AND pacchetto<='0'";
					$result2=mysqli_query($link2,$query2);
					if(mysqli_num_rows($result2)>0){
						while($row2=mysqli_fetch_row($result2)){
							if($row2['0']!=0){
								++$pagato;
							}else{
								++$numnon;
							}
						}
					}
					$butt6='';
					
					if(($pagato!=0)&&($numnon!=0)){
						$butt6='<button class="shortcut  mini10 warning portacassaw popover" ><span>Servizio pagato in parte</span></button>';
					}
					if($pagato==0){
						$butt6='<button class="shortcut  mini10 whites portacassa popover"><span>Servizio non pagato</span></button>';
					}
					if(($pagato!=0)&&($numnon==0)){
						$butt6='<button class="shortcut  mini10 success portacassaw popover" ><span>Servizio saldato completamente</span></button>';
					}
					
					//$butt3='<button class="shortcut mini whites '.$datacaricon[$datacar].'" onclick="spostaini('.$key.','.$datacar.')" alt="'.$datacarfra[$datacar].'"></button>';
				
					
					/*$button='';
					$buttonora='';
					$txt.='<tr id="tr'.$key.'" lang="'.$key.'/0"><td>'.$servizio.' '.$dove.'</td>
					<td>'.datafunc($time,$modi,6,'','').'</td>
					<td><div class="iconqta">'.$arrqta[$key].'</div></td>
					<td><input type="text" value="'.$arrprice[$key].'" class="bnone pricetab ptb" onchange="modprenot('.$key.','."'".$key."'".',98,1,38)" id="'.$key.'"></td>
					<td></td>
					<td>'.$butt6.'</td>
					<td><button class="shortcut mini danger del3icon" onclick="msgboxelimina('.$key.',3,1,'.$elagg.',0);prenfunc=1;" alt="Elimina"></button></td>
					</tr>';
					
					*/
					
					
					
					$num2=datafunc2($time,$modi,$tipolim,'',$key);
					$num=1;
					
					
					$modb='
					<li><a href="#" class="list-button item-link" id="deleteb" onclick="msgboxelimina('.$key.',3,'."'".$txtsend."'".',1,1);">Elimina</a></li>';
					
					
					
					
					$txt.='
						<li class="IDtipol'.$IDtipo.'" onclick="aprimod('.$key.',this)" id="tr'.$key.'" lang="'.$key.'/0">
						  <a href="#" class="item-link item-content">
							<div class="item-inner">
							  <div class="item-title-row">
								<div class="item-title titlemain" style="line-height:14px;"><b>N. '.$arrqta[$key].' '.$servizio.'</b><br><span style="font-size:11px; color:#666;">'.$dove.'</span></div>
								<div style="font-size:13px; color:#777;" align="right" ><b>'.$arrprice[$key].'</b> €<br>'.$num2.'</div>
							  </div>
							</div>
						  </a>
						   <div id="menu'.$key.'" style="display:none;" >'.base64_encode($modb).'</div>
						 </li>';
					
					/*
					
					$txt.='
					<li class="IDtipol'.$IDtipo.'" id="tr id="tr'.$key.'" lang="'.$key.'/0">
						  <a href="#" class="item-link item-content" onclick="vis2('.$key.',1,'.$num.',1);">
							<div class="item-inner">
							  <div class="item-title-row">
								<div class="item-title titlemain" style="line-height:14px;"><b>N. '.$arrqta[$key].' '.$servizio.'</b><br><span style="font-size:11px; color:#666;">'.$dove.'</span></div>
								<div style="font-size:13px; color:#777;" align="right" ><b>'.$arrprice[$key].'</b> €<br>'.$num2.'</div>
							  </div>
							</div>
						  </a>
						  
						</li>';*/
					
					
					
					
		
					//$height+=$colh;

			}
		break;
		}
			
	}else{
		
		$txt='<div class="list-block" style="background:#fff;" >
			  <ul>';
		
		
		switch($tipolim){
		
			case 5:
			case 8:
			case 7:
			
					$pacchetto=$extra.'/'.$ID;		
					$disprezzo='';
					
					$pagato=0;
					if(($tipolim=='7')||($tipolim=='8')){
						$disprezzo=' disabled="disabled" ';
						//controlla pagamento voucher
					}else{
						//controllo pagamento voucher
					}
					
					foreach ($arrID as $key=>$dato){
						
							$IDinfop=$dato;
							$pacchetto=$extra.'/'.$key;
							
							
							$query="SELECT p.ID,p.time,p.tipolim,s.servizio,p.modi,p.sala,p.IDtipo,p.sottotip FROM prenextra as p,servizi as s WHERE p.ID IN (SELECT IDprenextra FROM prenextra2 WHERE pacchetto='$pacchetto' AND IDpren='$IDpren' AND IDinfop IN ($IDinfop)) AND p.IDpren='$IDpren' AND p.IDstruttura='$IDstruttura' AND s.ID=p.extra ORDER BY p.time";
							$result=mysqli_query($link2,$query);
							while($row=mysqli_fetch_row($result)){
									
									
								$modb='';	
									
								$IDprenextra=$row['0'];
								$IDtipo=$row['6'];
								$IDsala=$row['5'];
								$nomesala='';
								$query3="SELECT nome FROM sale WHERE ID='$IDsala' LIMIT 1";
								$result3=mysqli_query($link2,$query3);
								if(mysqli_num_rows($result3)>0){
									$row3=mysqli_fetch_row($result3);
									$nomesala=$row3['0'];
								}
								
								
								//$butt3='';
								
								$query2="SELECT SUM(qta),GROUP_CONCAT(CONCAT(IDprenextra,'/', IDinfop) SEPARATOR ',')FROM prenextra2 WHERE IDprenextra='$IDprenextra' AND IDinfop IN ($IDinfop) GROUP BY IDpren";
								$result2=mysqli_query($link2,$query2);
								$row2=mysqli_fetch_row($result2);
								$qta2=$row2['0'];
								$arri=$row2['1'];
								$button="";
								$sostb='';
								if($row['2']=='5'){
									$button='<button class="shortcut mini10 info popover" onclick="vis2('.$row['0'].','.$tipol2.',1,2);">1+<span>Visualizza contenuto</span></button>';
									$prezzo='';
								}else{
									$query3="SELECT SUM(prezzo) FROM prenextra2 WHERE IDprenextra='$IDprenextra' AND IDpren='$IDpren' AND pacchetto='$pacchetto' AND IDinfop IN ($IDinfop)";
									$result3=mysqli_query($link2,$query3);
									$row3=mysqli_fetch_row($result3);
									$prezzo=$row3['0'].' &euro;';
								}
								
								
								$func2='';
								if(($row['2']==2)||($row['2']==1)){
									$sottot=$row['6'];
									$func2='onclick="aprimod('.$row['0'].',this)"';
									$modb='<li><a href="#" class="list-button item-link" onclick="modificaserv('.$row['0'].',1,0,'."'".$txtsend."'".')">Modifica Orario</a></li>
					';
								}else{
									$sottot=$row['7'];
								}
								$qtabutt='N.'.$qta2.' persone';
								$txtsend=$arri;
								if($row['2']==6){
									$txtsend=$row['0'].'/0';
									$qtabutt='';
									$servizio='N.'.$qta2.' '.$servizio;
								}
								
								$num2=datafunc2($row['1'],$row['4'],$row['2'],'',$row['0']);
								
								/*
								$txt.='<tr id="tr'.$row['0'].'" lang="'.$txtsend.'"><td>'.$row['3'].'</td>
								<td class="sala">'.$nomesala.'</td>
								<td>'.datafunc($row['1'],$row['4'],$row['2'],'modserv2('.$row['1'].','.$sottot.','.$row['0'].')',$row['0']).'</td>
								<td><div class="iconuser" alt="'.$row['0'].'">'.$qta2.'</div></td>
								<td>
								<input type="text" value="'.round($prezzo,2).'" '.$disprezzo.' class="bnone pricetab ptb" onchange="modprenextra('.$row['0'].','."'".$row['0']."'".',18,11,18)" id="'.$row['0'].'">
								</td>
								<td><button class="shortcut mini10 popover  settingmod4" onclick="modifIDp('.$row['0'].',this,'.$row['2'].','.$datacar.',1,1)"><span>Altre Funzioni</span></button></td>
								<td></td>
								<td></td>
							</tr>
							<tr><td colspan="6" align="right"><div id="into'.$tipol2.'-'.$row['0'].'" class="details"></div></td></tr>
							';
							
							*/
							
							
							
							
							$txt.='
						<li class="IDtipol'.$IDtipo.'" '.$func2.'>
						  <a href="#" class="item-link item-content">
							<div class="item-inner">
							  <div class="item-title-row">
								<div class="item-title" style="line-height:14px;">'.$row['3'].'<br><span style="font-size:11px; color:#666;">'.$qtabutt.'</span></div>
								<div style="font-size:13px; color:#777;" align="right" >'.round($prezzo,2).' €<br>'.$num2.'</div>
							  </div>
							</div>
						  </a>';
						  
						  if(strlen($modb)>0){
						  	$txt.='<div id="menu'.$row['0'].'" style="display:none;" >'.base64_encode($modb).'</div>';
						  }
						  
						 $txt.='</li>';
							
							
							
							/*
								$txt.='
									<li class="accordion-item IDtipol'.$IDtipo.'"  id="tr'.$row['0'].'" lang="'.$txtsend.'" style=" padding:0px; margin:0px; " >
									  <a href="#" class="item-link item-content" onclick="vis2('.$row['0'].','.$tipol.',1,1);">
										<div class="item-inner" style="padding-top:2px;">
										  <div class="item-title-row">
											<div class="item-title" style="line-height:14px;">'.$row['3'].'<br><span style="font-size:11px; color:#666;">'.$qtabutt.'</span></div>
											<div style="font-size:13px; color:#777;" align="right" >'.round($prezzo,2).' €<br>'.$num2.'</div>
										  </div>
										</div>
									  </a>
									  <div class="accordion-item-content" style="padding:0px;" >
											<div class="content-block" id="into'.$tipol.'-'.$row['0'].'" class="details">
									 ...
									</div>
								  </div>
							</li>';
							
							*/
							
							$height+=$colh;
						}
					}
			
			break;
			
		}
		
		
		
	}
	$txt.= '</ul></div>';
	
	echo '<div id="divinto'.$tipol.'" '.$claadd.'>'.$txt.'</div>';

?>