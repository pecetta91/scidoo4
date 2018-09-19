<?php 
header('Access-Control-Allow-Origin: *');
include('../../../../config/connecti.php');
include('../../../../config/funzioni.php');
include('../../../../config/preventivoonline/config/funzioniprev.php');


$IDpren=$_SESSION['IDstrpren'];
$route=$_SESSION['route'];

$query="SELECT app,gg,time,IDstruttura,checkout FROM prenotazioni WHERE IDv='$IDpren' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDapp=$row['0'];
$notti=$row['1'];
$check=$row['2'];
$IDstruttura=$row['3'];
$checkout=$row['4'];

$IDserv=$_GET['dato0'];
$tempopass=$_GET['dato1'];


$query="SELECT servizio,IDtipo,IDsottotip FROM servizi WHERE ID='$IDserv' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$servizio=$row['0'];
$IDtipo=$row['1'];
$IDsottotip=$row['2'];


$query="SELECT s.durata,s.IDsottotip,s.esclusivo,t.tipolimite,s.IDtipo,s.servizio FROM servizi as s,tiposervizio as t WHERE s.ID='$IDserv' AND s.IDtipo=t.ID LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$durata=$row['0'];	
$IDsottotip=$row['1'];	
$esclusivo=$row['2'];	
$tipolim=$row['3'];
$IDtipo=$row['4'];
$servizio=$row['5'];


$txt='		 	
<div class="content-block-title titleb">Sale Disponibili</div> 
<div class="row no-gutter">

';
 
	$query="SELECT sale.ID,sale.nome FROM sale,saleassoc WHERE sale.IDstr='$IDstruttura' AND saleassoc.IDsotto='$IDsottotip' AND saleassoc.ID=sale.ID ORDER BY saleassoc.priorita";
		$result=mysqli_query($link2,$query);
		while($row=mysqli_fetch_row($result)){
			$IDsala=$row['0'];
			$nomesala=$row['1'];
			$foto='immagini/big0IRjAOaZIk10igSmF4Lg.jpg';
			if(strlen($foto)>0){
				$img='style="background: url('.$route.$foto.') center center / cover no-repeat;">';
			}else{
				$img='><i class="far fa-building fa-2x">';
			}
					$txt.='<div class="col-50" style="padding:10px;"><div class=" prendisala" style="display:inline-block;width:100%; height:150px;background: #fff;" alt="'.$IDsala.'" id="data'.$IDsala.'"  onclick="prendisala('.$IDsala.');">
								<div class=" salacerchio " '.$img.'</div>
								<div style="font-size:14px;padding-top:30px;color:#e4492b" align="center">'.$nomesala.'</div>
							</div></div>
							
							 ';
			
				}/*
		$query2="SELECT nome FROM appartamenti WHERE ID='$IDapp' AND IDstruttura='$IDstruttura'";
		$result2=mysqli_query($link2,$query2);
		$row2=mysqli_fetch_row($result2);
		
			$txt.='<div class="col-50" style="padding:10px;"><div class=" prendisala" style="display:inline-block;width:100%; height:150px;background: #fff;" alt="'.$IDapp.'" id="data'.$IDapp.'"  onclick="prendisala('.$IDapp.');">
									<div class=" salacerchio " '.$img.'</div>
									<div style="font-size:14px;padding-top:30px;color:#e4492b" align="center">'.$row2[0].'</div>
								</div></div>';*/
		

$txt.='</div>';

?>
<div class="content-block" id="prenotanuovservstep"> 
<?php echo $txt;?>
