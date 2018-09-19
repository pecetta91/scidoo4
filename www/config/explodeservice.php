<?php
header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');
include('../../config/funzionilingua.php');

$IDutente=$_SESSION['ID'];
$IDstruttura=$_SESSION['IDstruttura'];
unset($_SESSION['listIDsotto']);
unset($_SESSION['datecentro']);

$txtsend=$_GET['dato0'];

$id=$_SESSION['IDprenfunc'];

$query="SELECT ID,datapren,time,gg,checkout,app,stato,lang,acconto,tempg,tempn,note FROM prenotazioni WHERE IDv='$id' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDpren=$row['0'];
$datapren=$row['1'];
$time=$row['2'];
$notti=$row['3'];
$checkout=$row['4'];
$IDapp=$row['5'];
$stato=$row['6'];
$lang=$row['7'];
$acconto=$row['8'];
$tempg=$row['9'];
$tempn=$row['10'];
$note=stripslashes($row['11']);


$nome=estrainome($id);

if($IDapp!=0){
	$query="SELECT attivo FROM appartamenti WHERE ID='$IDapp' LIMIT 1";
	$result=mysqli_query($link2,$query);
	$row=mysqli_fetch_row($result);
	$attivo=$row['0'];
}else{
	$attivo=0;
}

if($stato=='-1'){
		$alloggio='Senza Alloggio';
	}else{
	$q8="SELECT nome FROM appartamenti WHERE ID='$IDapp' LIMIT 1";
	$r8=mysqli_query($link2,$q8);
	$row8=mysqli_fetch_row($r8);
	$alloggio=$row8['0'];
	}
$tipo=0;





$arr=explode('..',$txtsend);

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
					
					if(($tipolim=='6')||($tipolim==9)){
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







$testo=  '
<div class="pages navbar-fixed">
  <div data-page="explodeservice" class="page with-subnavbar">
		<input type="hidden" id="txtsend2" value="'.$txtsend.'">

		<div class="navbar">
      <div class="navbar-inner">
        
		<div class="left" > <a href="#" class="link" onclick="backexplode(1);" >
							<i class="material-icons" style="font-size:40px;">chevron_left</i>
						</a></div>
					<div class="center titolonav">Dettaglio Servizio</div>
			<div class="right">
			</div>
      </div>
    </div>


				
<div class="page-content" > 
			
 <div class="content-block">
 
 
 
 ';
 
 
 
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
					
					
					$txt.='<input type="hidden" id="'.$arrinfo[$key].'" value="'.$key.'" alt="1" lang="1">
				
					 <li  id="tr'.$key.'"  lang="'.$arrinfo[$key].'" title="'.$key.'"  alt="1" dir="'.$arrinfo[$key].'" onclick="modservice('.$key.')" >
					 
					 <div class="item-content" >
					  <div class="item-media" '.$funcexp.' style="line-height:6px; width:50px; margin-left:-15px; margin-right:-10px;" align="center"><div style="width:20px; margin:0px; height:20px; border-radius:50%; font-size:12px; font-weight:bold; text-align:center; background:#0286ca; color:#fff; line-height:19px;">'.$num.'</div>
					  </div>

					 <div class="item-inner">
					  <div class="item-title" >'.$servizio.'<br><span class="subtitle">'.$arrqta[$key].' persone '.dataita4($time).' '.$nomesala.'  </span></div>
					  <div class="item-after">
						'.$arrprice[$key].' €
					  </div>
					</div>
					</div>
					</li>';
					
					/*
					<tr id="tr'.$key.'"  lang="'.$arrinfo[$key].'" ><td>'.$servizio.'</td>
					
					<td class="sala">'.$nomesala.'</td>
					<td>'.datafunc($time,$modi,$tipolim,'modserv2('.$time.','.$arrsottot[$key].','.$key.')',$key).'</td>
					
					<td>
					<div class="iconuser" alt="'.$key.'">'.$arrqta[$key].'</div></td>
					
					<td><input type="text" value="'.$arrprice[$key].'" class="bnone pricetab ptb" onchange="modprenot('.$key.','."'".$key."'".',98,1,38)" id="'.$key.'"></td>
					<td><button class="shortcut mini10 popover  settingmod4" onclick="modifIDp('.$key.',this,'.$tipolim.','.$datacar.',1,1)"><span>Altre Funzioni</span></button></td>
					<td>'.$butt6.'</td>
					<td><button class="shortcut mini10 popover danger del3icon" onclick="msgboxelimina('.$key.',3,1,'.$elagg.',0);prenfunc=1;" ><span>Elimina</span></button></td></tr>';
					$height+=$colh;*/
			}

			
	
		break;
		case 2:
		case 4:
	
			//$txt.='<tr><td></td><td style="width:150px;"></td><td style="width:85px;"></td><td style="width:40px;"></td><td style="width:80px;"></td><td style="width:30px;"></td><td style="width:30px;"></td><td style="width:30px;"></td></tr>';
			
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
				
					/*
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
					
					*/
					$del=1;
					if($tipolim==4){$del=0;}
					
					
					$txt.='
					
					<input type="hidden" id="'.$arrinfo[$key].'" value="'.$key.'" alt="1" lang="'.$del.'">
				
					 <li  id="tr'.$key.'" lang="'.$arrinfo[$key].'" title="'.$key.'"  alt="2" dir="'.$arrinfo[$key].'" onclick="modservice('.$key.')" >
					 
					 <div class="item-content" >
					  <div class="item-media" '.$funcexp.' style="line-height:6px; width:50px; margin-left:-15px; margin-right:-10px;" align="center"><div style="width:20px; margin:0px; height:20px; border-radius:50%; font-size:12px; font-weight:bold; text-align:center; background:#0286ca; color:#fff; line-height:19px;">1</div>
					  </div>

					 <div class="item-inner">
					  <div class="item-title" >'.$servizio.'<br><span class="subtitle">'.$arrqta[$key].' persone '.dataita4($time).' '.$nomesala.'  </span></div>
					  <div class="item-after">
						'.$arrprice[$key].' €
					  </div>
					</div>
					</div>
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
					$height+=$colh;*/

			}

			
		break;
		case 5:
			
			//$txt.='<tr><td style="width:30px;"></td><td></td><td style="width:85px;"></td><td style="width:40px;"></td><td style="width:80px;"></td><td style="width:30px;"></td><td style="width:30px;"></td><td style="width:30px;"></td></tr>';
			
			
			
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
					
					/*
					$butt6='';
					
					if(($pagato!=0)&&($numnon!=0)){
						$butt6='<button class="shortcut  mini10 warning portacassaw popover" ><span>Servizio pagato in parte</span></button>';
					}
					if($pagato==0){
						$butt6='<button class="shortcut  mini10 whites portacassa popover"><span>Servizio non pagato</span></button>';
					}
					if(($pagato!=0)&&($numnon==0)){
						$butt6='<button class="shortcut  mini10 success portacassaw popover" ><span>Servizio saldato completamente</span></button>';
					}*/
					
					
					$del=1;
					
					$txt.='
					
					<input type="hidden" id="'.$arrinfo[$key].'" value="'.$key.'" alt="1" lang="'.$del.'">
				
					 <li  id="tr'.$key.'" lang="'.$arrinfo[$key].'" title="'.$key.'"  alt="2" dir="'.$arrinfo[$key].'" onclick="modservice('.$key.')" >
					 
					 <div class="item-content" >
					  <div class="item-media" '.$funcexp.' style="line-height:6px; width:50px; margin-left:-15px; margin-right:-10px;" align="center"><div style="width:20px; margin:0px; height:20px; border-radius:50%; font-size:12px; font-weight:bold; text-align:center; background:#0286ca; color:#fff; line-height:19px;">1</div>
					  </div>

					 <div class="item-inner">
					  <div class="item-title" >'.$servizio.'<br><span class="subtitle">'.$arrqta[$key].' persone '.dataita4($time).' '.$nomesala.'  </span></div>
					  <div class="item-after">
						'.$arrprice[$key].' €
					  </div>
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
		case 9:
			//$txt.='<thead><tr><th>Prodotto</th><th>Data</th><th style="width:70px;">Prezzo (&euro;)</th><th></th></tr></thead>';
			//$txt.='<tr><td></td><td style="width:85px;"></td><td style="width:40px;"></td><td style="width:80px;"></td><td style="width:30px;"></td><td style="width:30px;"></td><td style="width:30px;"></td></tr>';
			
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
					/*
					if($pacchetto==0){
						$dove="(Al minuto)";
					}else{
						$dove="(Al tavolo)";
					}*/
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
					/*
					$butt6='';
					
					if(($pagato!=0)&&($numnon!=0)){
						$butt6='<button class="shortcut  mini10 warning portacassaw popover" ><span>Servizio pagato in parte</span></button>';
					}
					if($pagato==0){
						$butt6='<button class="shortcut  mini10 whites portacassa popover"><span>Servizio non pagato</span></button>';
					}
					if(($pagato!=0)&&($numnon==0)){
						$butt6='<button class="shortcut  mini10 success portacassaw popover" ><span>Servizio saldato completamente</span></button>';
					}*/
					
					//$butt3='<button class="shortcut mini whites '.$datacaricon[$datacar].'" onclick="spostaini('.$key.','.$datacar.')" alt="'.$datacarfra[$datacar].'"></button>';
				
					
					
					$del=1;
					
					$txt.='
					
					<input type="hidden" id="'.$key.'/0" value="'.$key.'" alt="1" lang="'.$del.'">
				
					 <li  id="tr'.$key.'" lang="'.$key.'/0" title="'.$key.'"  alt="2" dir="'.$arrinfo[$key].'" onclick="modservice('.$key.')" >
					 
					 <div class="item-content" >
					  <div class="item-media" '.$funcexp.' style="line-height:6px; width:50px; margin-left:-15px; margin-right:-10px;" align="center"><div style="width:20px; margin:0px; height:20px; border-radius:50%; font-size:12px; font-weight:bold; text-align:center; background:#0286ca; color:#fff; line-height:19px;">1</div>
					  </div>

					 <div class="item-inner">
					  <div class="item-title" >'.$servizio.'<br><span class="subtitle">N.'.$arrqta[$key].' - '.dataita4($time).'   </span></div>
					  <div class="item-after">
						'.$arrprice[$key].' €
					  </div>
					</div>
					</div>
					</li>';
					
					
					/*
					$button='';
					$buttonora='';
					$txt.='<tr id="tr'.$key.'" lang="'.$key.'/0"><td>'.$servizio.'</td>
					<td>'.datafunc($time,$modi,6,'','').'</td>
					<td><div class="iconqta">'.$arrqta[$key].'</div></td>
					<td><input type="text" value="'.$arrprice[$key].'" class="bnone pricetab ptb" onchange="modprenot('.$key.','."'".$key."'".',98,1,38)" id="'.$key.'"></td>
					<td><button class="shortcut mini10 popover  settingmod4" onclick="modifIDp('.$key.',this,'.$tipolim.','.$datacar.',1,1)"><span>Altre Funzioni</span></button></td>
					<td>'.$butt6.'</td>
					<td><button class="shortcut mini danger del3icon" onclick="msgboxelimina('.$key.',3,1,'.$elagg.',0);prenfunc=1;" alt="Elimina"></button></td>
					</tr>';
		
					$height+=$colh;*/
			}
		break;
		}
 
  
  $testo.='<div class="content-block-title titleb" style="margin-top:-40px;">Dettaglio</div>
			<div class="list-block"><ul>'.$txt.'</ul></div>';
				
			
$testo.='</div>
				 
					 
</div>		 
			
			';
			  echo $testo;
			 