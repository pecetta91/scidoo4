<?php
if(!isset($inc)){
	include('../../../config/connecti.php');
	include('../../../config/funzioni.php');
	//
	$testo='';
	
	
}
$testo='';

$IDpren=$_SESSION['IDstrpren'];
$route=$_SESSION['route'].'immagini/';




$info=1;
if(isset($_GET['dato0'])){
		if($_GET['dato0']!='0'){
			$IDsottotipo=$_GET['dato0'];
			$info=2;
		}if($_GET['dato0']=='-1'){$info=1;}
}


$query="SELECT app,gg,time,IDstruttura,checkout FROM prenotazioni WHERE IDv='$IDpren' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDapp=$row['0'];
$notti=$row['1'];
$check=$row['2'];
$IDstruttura=$row['3'];
$checkout=$row['4'];


$query="SELECT ID FROM tiporestr WHERE IDstr='$IDstr' AND personale='1'  AND personad='1' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$restr=$row['0'].',';


$testo.='<input type="hidden" value="'.$info.'" id="switch">
<input type="hidden" value="'.$_GET['dato0'].'" id="get">';
//servizi piu prenotati su prenextra--->di altre persone ( tranne i miei )

switch($info)
{
	case 1:
		$time=time();
		
		if($time<$check){
			$time=$check;
		}
	
		$datad=date('Y-m-d',$time);
		
		$sottopres=0;
		$query="SELECT GROUP_CONCAT(DISTINCT(sottotip)) FROM prenextra WHERE IDpren='$IDpren' AND FROM_UNIXTIME(time,'%Y-%m-%d')='$datad'  AND IDstruttura='$IDstruttura' GROUP BY IDstruttura";
		$result=mysqli_query($link2,$query);
		if(mysqli_num_rows($result)>0){
			$row=mysqli_fetch_row($result);
			$sottopres=$row['0'];
		}
		// se premi 
		$query="SELECT extra FROM prenextra WHERE  IDstruttura='$IDstruttura' AND sottotip NOT IN($sottopres)  AND IDtipo NOT IN(8,10,12,13,14,15,16,17,18,19,20)  GROUP BY extra ORDER BY COUNT(extra) DESC LIMIT 20 ";
		$result=mysqli_query($link2,$query);
		if(mysqli_num_rows($result)>0){		
					$txt.='<div class="content-block-title titleb">Servizi più prenotati</div> ';	
					while($row=mysqli_fetch_row($result)){
						$IDserv=$row['0'];
						$numextra=$row['1'];
						$query2="SELECT s.ID,s.servizio,s.descrizione,s.durata,t.tipolimite FROM servizi as s,tiposervizio as t WHERE s.attivo='1' AND 	s.ID='$IDserv' AND s.IDtipo=t.ID AND t.ID!=10";
						$result2=mysqli_query($link2,$query2);
						if(mysqli_num_rows($result2)>0){		
						$row2=mysqli_fetch_row($result2);
							$foto=getfoto($row2['0'],4);
							$prezzo=calcolaprezzoserv($row['0'],$time,$restr,$IDstruttura,0,$IDpren,0,$row['3']);	
							
							
							
							/*
							if(!file_exists('../../../'.$foto)){
							$foto='immagini/camera.jpg';
							}*/
							
							//$url2='url('.$route.'immagini/camera.jpg'.') center center / cover no-repeat;';
							
						
						
						$testo.='<div class="col-50 p10" >
								<div class="servcontainer" onclick="navigation(26,'.$row2['0'].',0,0)">
								<div class="servizidispo"  style="background: url('.$route.$foto.') center center / cover no-repeat "></div>
								<div><span class="servnome">'.$row2['1'].'</span></div>
								<div><span class="servprezzo">'.$prezzo.'€</span></div>
								<div><span class="servora">'.orariservizio($row2['0']).'</span></div>
						</div>
					</div>';
						}
					}
		}
		
	
		
	break;
		
	case 2:			$tipopren=0;
					$querym="SELECT t.tipo,t.tipo2,t.ID FROM tiposervizio as t,sottotipologie as s  WHERE s.ID='$IDsottotipo' AND s.IDmain=t.ID";
					$resultm=mysqli_query($link2,$querym);
					if(mysqli_num_rows($resultm)>0){
					$rowm=mysqli_fetch_row($resultm);
						$tipoid=$rowm['2'];
						
						$query2="SELECT GROUP_CONCAT(DISTINCT (s.ID) ) FROM extraonline as ext,servizi as s WHERE ext.IDstr='$IDstruttura' AND ext.IDserv=s.ID AND s.IDsottotip='$IDsottotipo' GROUP BY ext.IDstr";
						$result2=mysqli_query($link2,$query2);
						if(mysqli_num_rows($result2)>0){
							$row2=mysqli_fetch_row($result2);
							// AND s.ID=ex.IDserv AND s.IDstruttura='$IDstruttura'
							$tipopren=$row2['0'];
						}
						
						
							
					$query="SELECT s.ID,s.servizio,s.descrizione,s.durata,t.tipolimite,t.tipo FROM servizi as s,tiposervizio as t WHERE s.attivo='1' AND 	s.IDsottotip='$IDsottotipo' AND s.IDtipo=t.ID AND s.ID IN ($tipopren) ORDER BY s.ID ";
					$result=mysqli_query($link2,$query);
					if(mysqli_num_rows($result)>0){		
					$txt.='<div class="content-block-title titleb">'.$rowm['1'].'</div> ';	
					while($row=mysqli_fetch_row($result)){

						$foto=getfoto($row['0'],4);

						//$url2='url('.$route.'immagini/camera.jpg'.') center center / cover no-repeat;';
						if($tipoid=='10')
						{
							$restr=1;
						}
						
						$prezzo=calcolaprezzoserv($row['0'],$time,$restr,$IDstruttura,0,$IDpren,0,$row['3']);	

						$txtmipiace='Mi Piace';
						$query2="SELECT ID FROM mipiace WHERE  IDobj='".$row['0']."' AND tipoobj='1'";
						$result2=mysqli_query($link2,$query2);
						$mipiace=mysqli_num_rows($result2);
						if($mipiace>1){
							$txtmipiace=$mipiace.' Mi Piace';
						}

						$classmi='';
						$query2="SELECT ID FROM mipiace WHERE IDcliente='$IDcliente' AND tipocli='$tipocli' AND IDobj='".$row['0']."' AND tipoobj='1' LIMIT 1";
						$result2=mysqli_query($link2,$query2);
						if(mysqli_num_rows($result2)>0){
							$classmi='mipiace';
						}



								$testo.='<div class="col-50 p10">
								<div class="servcontainer" onclick="navigation(26,'.$row['0'].',0,0)">
								<div class="servizidispo" style="background: url('.$route.$foto.') center center / cover no-repeat;"></div>
								<div><span class="servnome">'.$row['1'].'</div>
								<div><span class="servprezzo">'.$prezzo.'€</div>
								<div><span class="servora">'.orariservizio($row['0']).'</div>
						</div>
					</div>';
		
					}
				}

		}
			
	break;
}


		echo '<div class="content-block" id="elencoserv" align="center" style="padding:0px; width:100%;">
		'.$txt.'
		<div class="row no-gutter">';
		echo $testo;
?>
</div>
</div>