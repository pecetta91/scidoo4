<?php 
	include('../../../config/connecti.php');
	include('../../../config/funzioni.php');
	include('../../../config/funzionilingua.php');
	header('Access-Control-Allow-Origin: *');
	$testo='';


$IDpren=$_SESSION['IDstrpren'];
$route=$_SESSION['route'];

$ID=$_GET['dato0'];



$query="SELECT app,gg,time,tempg,tempn,checkout,IDstruttura FROM prenotazioni WHERE IDv='$IDpren' LIMIT 1";

$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDapp=$row['0'];
$notti=$row['1'];
$check=$row['2'];
$tempg=$row['3'];
$tempn=$row['4'];
$checkout=$row['5'];
$IDstr=$row['6'];

$nomepren=estrainome($IDpren);
$timeora=oraadesso($IDstr);

$serviziarr=array();
$prodottiarr=array();

$query="SELECT p.IDtipo,p.tipolim,s.servizio,p.time,p.modi,p.extra,p.sottotip,p.durata,p.IDpers,p.sala FROM prenextra as p,servizi as s WHERE p.ID='$ID' AND p.extra=s.ID LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDtipo=$row['0'];
$tipolim=$row['1'];
$servizio=$row['2'];
$time=$row['3'];
$modi=$row['4'];
$IDserv=$row['5'];
$IDsotto=$row['6'];
$durata=$row['7'];
$IDpersserv=$row['8'];
$IDsalaserv=$row['9'];


$foto='immagini/'.getfoto($IDserv,4);


echo '<input type="hidden" id="funcreload" value="navigation(25,'.$ID.',0,1)">';


$testo='<div data-page="detserv" class="page" > 
		 	<div class="navbar" id="navcal" >
				<div class="navbar-inner">
					<div class="left">
					
					  <a href="#" class="link icon-only " onclick=" backexplode(7,0)"   >
						<i class="material-icons" style="font-size:40px;">chevron_left</i>
					</a>
					</div>
					<div class="center titolonav">'.$servizio.'</div>
				</div>
			</div>
		 <div class="page-content">
              <div class="content-block" id="detserv"> 
	
			  <div style="width:100%; position:relative;height:130px; background:url('.$route.$foto.') no-repeat center center; background-size:cover; margin-bottom:20px; margin-top:-28px;box-shadow: 1px 1px 5px 0px rgba(168,168,168,1);">
<div style="position:absolute; top:0px; left:0px; width:100%; height:100%; z-index:1; background:#333; opacity:0.1;"></div>
			</div>
			  
			  
			  ';
			  
			  
	
	$_SESSION['timecal']=$time;
	$data=date('Y-m-d',$time);
	
	$timemod=$time;
if(isset($_GET['dato1'])){
	if(is_numeric($_GET['dato1'])&&($_GET['dato1']>0)){
		$timemod=$_GET['dato1'];
		$data=date('Y-m-d',$timemod);
	}
}
	
	$step=30;
				$steps=$step*60;
				
				$stepb=$durata/$step;
				
		if($tipolim==2){
			  
			  
			  $first='';
				list($yy, $mm, $dd) = explode("-", $data);
				$time0=mktime(0, 0, 0, $mm, $dd, $yy);
				
				
				$qadd="";
				$query="SELECT GROUP_CONCAT(IDorarios SEPARATOR ',') FROM assocorario WHERE IDserv='$IDserv' GROUP BY IDserv";
				$result=mysqli_query($link2,$query);
				if(mysqli_num_rows($result)>0){
					$row=mysqli_fetch_row($result);
					$grora=$row['0'];
					$qadd=" AND ID IN ($grora)";	
				}
				
			
				
				$orari=array();
				$query="SELECT orarioi,orariof,ID FROM orarisotto WHERE IDsotto='$IDsotto' $qadd";
				$result=mysqli_query($link2,$query);
				if(mysqli_num_rows($result)>0){
					while($row=mysqli_fetch_row($result)){
						$orai=$time0+$row['0'];
						$oraf=$time0+$row['1'];
						for($orai;$orai<=$oraf;$orai+=$steps){
							array_push($orari,$orai);
						}
					}
				}
					
	
				
					
				
				
				
				$query="SELECT SUM(qta),GROUP_CONCAT(IDinfop SEPARATOR ',') FROM prenextra2 WHERE IDprenextra='$ID' GROUP BY IDprenextra";
				$result=mysqli_query($link2,$query);
				$row=mysqli_fetch_row($result);
				$qta=$row['0'];
				$groupid=$row['1'];
				
				
				$dis1=0;
				
				if($IDtipo==1){
					$query2="SELECT p.ID FROM prenextra as p,prenextra2 as p2 WHERE FROM_UNIXTIME(p.time,'%Y-%m-%d')=FROM_UNIXTIME('$timemod','%Y-%m-%d') AND p.IDstruttura='$IDstr' AND  p.IDpren='$IDpren' AND p.sottotip='$IDsotto' AND p.modi>'0' AND p.ID!='$ID' AND p.ID=p2.IDprenextra AND p2.IDinfop IN($groupid)";
					
					$result2=mysqli_query($link2,$query2);
					if(mysqli_num_rows($result2)>0){
						$dis1=1;
					}
				}
				
				
				//estrarre tutto il personale
				$maxp=array();
				$sale=array();
				$IDsalamain=0;
				$query="SELECT s.ID,s.nome,s.maxp FROM sale as s,saleassoc as sc WHERE sc.IDsotto='$IDsotto' AND sc.ID=s.ID ORDER BY sc.priorita";
				$result=mysqli_query($link2,$query);
				if(mysqli_num_rows($result)>0){
					while($row = mysqli_fetch_row($result)){
						if($IDsalamain==0){$IDsalamain=$row['0'];}
						$sale[$row['0']]=$row['1'];
						$maxp[$row['0']]=$row['2'];
					}
				}
				
				
				
				
				
				if($IDsalaserv==0){$IDsalaserv=$IDsalamain;}
							
					$testo.='<div class="content-block-title titleb">Data e Orario</div>
						<div class="list-block">
  <ul>
    <li>
      <a href="#" class="item-link smart-select" data-searchbar="false" data-open-in="popup" >
        <select id="datamod" onChange="modificaorario('.$ID.',1,this.value,1)">';
	
		 for($i=0;$i<=$notti;$i++){
							$tt=$check+86400*$i;
							$cla=''; 
							if(date('Y-m-d',$tt)==$data){
								$cla='selected';
							}
							$testo.='<option value="'.$tt.'" '.$cla.'>'.dataita($tt).' '.date('Y',$tt).'</option>';
							//$testo.='<a href="#"  alt="'.$i.'" onclick="modificaserv('.$ID.',1,'.$tt.','."'".$riagg."'".',1)" class="roundb3 '.$cla.'">'.$giorniita3[date('N',$tt)].'<br>'.date('d',$tt).'</a>';
						}
          
        $testo.='</select>
        <div class="item-content">
          <div class="item-inner">
            <div class="item-title">Data del Servizio</div>
            <div class="item-after"></div>
          </div>
        </div>
      </a>
    </li>
  
';
				
			
				
				
				$orari=array_unique($orari);
				
				
				$valarr=array();
	
	
	
				if($dis1==1){
					$testo.='<div style="margin:5px; font-size:15px;  color:#a43c32;font-weight:100;">Questo servizio non pu&ograve; essere ricevuto due volte lo stesso giorno.</div>';
				}else{
					$testo.='
					 


					';
	
	
					if(isset($_SESSION['orario'][$IDserv][$data])){
						$or=$_SESSION['orario'][$IDserv][$data];
					}else{
						$or=orari3(0,$data,$qta,$IDserv,$IDstruttura,0,$IDpers,2,$check,0,0,$checkout);
						
						$_SESSION['orario'][$IDserv][$data]=$or;
					}
		
					
					$txtinto='';
					
					//$testo.= '<div class="buttons-row">';
					
					
					//$active='';
					//$active2='';
					$IDpersactive='';
					foreach($sale as $IDsala =>$nomesala){
						
						//$testo.='<a href="#IDsalamod'.$IDsala.'" class="tab-link '.$active.' button ">'.$nomesala.'</a>';
							
						$okpers=0;
						
						$first=0;
						
						
						foreach ($orari as $times){
							
							
							
							//$clas='notdispo';
							$idinto='';
							$txtdispo='NON DISPONIBILE';
							
							$val=$IDsala.'_'.$times.'_0';
							
							if(!isset($valarr[$times])){
								$valarr[$times]=$IDsala.'_'.$times.'_0';
							}		
									
							//$func=' onclick="modprenextra('.$ID.','.$val.',1,9,2);" ';
							$dis='';
							
							$qta=0;
							
							
							
							
						}
					}
					$txtinto='<option value="0" >--</option>';
					foreach ($valarr as $times =>$val){
						$sel='';
						if($times==$time){$sel='selected="selected"'; $okpers=1;}
						$txtinto.='<option value="'.$val.'" '.$sel.'>'.date('H:i',$times).'</option>';		
					}
					
					
					
					$testo.='
						
							<li>
						  <a href="#" class="item-link smart-select" data-open-in="popup">
							<select   onchange="modprofilo('.$ID.',this.value,6,10,5)">
							  '.$txtinto.'
							</select>
							
							<div class="item-content">
								
							  <div class="item-inner">
								<div class="item-title">Orario</div>
								<div class="item-after">--</div>
							  </div>
							</div>
						  </a>
						</li>';
						
					
					
					
					$testo.='
					
					<input type="hidden" value="'.$IDpersactive.'" id="IDperssel">
					';
			  
			  
			  
				}
			  
			  $testo.='</ul></div>';
			  
			  
			  
			  
			  
			  
		}else{
			if($tipolim=='1'){
				$testo.='<p style="padding:10px;">'."<b>Per modificare l'orario si prega di contattare  il personale addetto. <br>Grazie</b></p>";
			}
		}
			  
			  
			  $testo.='<hr style="width:90%; margin:auto; background:#ccc;">
			  <div style="padding:20px; text-align:center;"><b>Il Servizio</b><br><br>'.traducis($IDserv,2,0,1).'</div>
			  <hr style="width:90%; margin:auto; background:#ccc; ">
			  
			 ';
			   
			   
			  
			
			  
			  
			  if(($tipolim==2)||($tipolim==1)){
					
				 $testo.=' 
				 	 <div style="padding:20px; text-align:center;">
					   <b>Gli Orari</b><br>
					  '.orariservizio($IDserv).'
					  
					  </div>
					   <hr style="width:90%; margin:auto; background:#ccc; ">
				 
				 
				  ';	
					
					
					
				$querylim="SELECT s.ID,s.nome,s.maxp FROM sale as s,saleassoc as sa WHERE s.IDstr='$IDstr' AND s.ID=sa.ID AND sa.IDsotto='$IDsotto'";
				$resultlim=mysqli_query($link2,$querylim);
				if(mysqli_num_rows($resultlim)>0){
					$testo.='<div style="padding:20px; text-align:center;">
					   <b>Le Sale</b><br>';
					 
					while($rowlim=mysqli_fetch_row($resultlim)){
						$testo.=$rowlim['1'].'<br>';
					}	
					
					$testo.='</div>
					 <hr style="width:90%; margin:auto; background:#ccc; ">';
				}
				
				
			}
			  
			  
			  
			  
			  
			  
			  
			  
			  //operatori
			  

$testo.='<br><br><div style="width:90%; margin:auto; text-align:center;color:#af2b44;"><span style="font-weight:300; line-height:12px; font-size:12px;">&Egrave; possibile modificare gli orari fino a 4h prima del suo inizio.<br>Per qualsiasi altre informazioni o modifica contrattare la struttura.</span></div>



</div></div></div>


';




if(!isset($inc)){
echo $testo;
}




?>