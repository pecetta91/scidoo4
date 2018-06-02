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




$query="SELECT s.IDtipo,t.tipolimite,s.servizio,s.sottotip,s.durata FROM servizi as s,tiposervizio as t WHERE s.ID='$ID' AND s.IDtipo=t.ID LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDtipo=$row['0'];
$tipolim=$row['1'];
$servizio=$row['2'];
$IDsotto=$row['3'];
$durata=$row['4'];

$IDserv=$ID;




$foto='immagini/big'.getfoto($IDserv,4);


echo '<input type="hidden" id="funcreload" value="navigation(25,'.$ID.',0,1)">';


$testo='<div data-page="detserv" class="page" > 
		 	<div class="navbar" id="navcal" >
				<div class="navbar-inner">
					<div class="left">
					
					  <a href="#" class="link icon-only back"    >
						<i class="material-icons" style="font-size:40px;">chevron_left</i>
					</a>
					</div>
					<div class="center titolonav">'.$servizio.'</div>
					<div class="right"></div>
				</div>
			</div>
			
			
			
		 <div class="page-content">
		 	

		 
		 
              <div class="content-block" id="detserv"> 
	
					

	
	
			  <div class="fotodetserv "style=" background:url('.$route.$foto.') no-repeat center center; ">
			  	<div class="overlayserv"></div>
			</div>
			  
			  <hr style="width:90%; margin:auto; background:#ccc;">
			  <div style="padding:20px; text-align:center;"><b>Il Servizio</b><br><br>'.traducis('',$ID,2,$lang,0).'</div>
			  <hr style="width:90%; margin:auto; background:#ccc; ">
			  
			 ';
			   
			   //'.traducis($IDserv,2,0,1).'
			  
			
			  
			  
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
			
$testo.='<br><br><br><br><br><br>';

//$func='navigation(27,'.$IDserv.',0,0)';
$func='';
	if(($tipolim==2)||($tipolim==6)){
		$func='navigation2(4,'.$IDserv.',2,0)';
		$testo.='<div  onclick="'.$func.'" class="tastoservizio">PRENOTA SUBITO</div>';
	}else{
		$testo.='<div class="tastoservizio">CONTATTA LA STRUTTURA</div>';
	}
	

$testo.='</div></div></div>


';




if(!isset($inc)){
echo $testo;
}




?>