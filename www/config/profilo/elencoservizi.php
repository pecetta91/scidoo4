<?php 
if(!isset($inc)){
	include('../../../config/connecti.php');
	include('../../../config/funzioni.php');
	header('Access-Control-Allow-Origin: *');
	//
}
$testo='';
$IDpren=$_SESSION['IDstrpren'];
$route=$_SESSION['route'];

$query="SELECT app,gg,time,tempg,tempn,checkout,IDstruttura FROM prenotazioni WHERE IDv='$IDpren' LIMIT 1";

$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDapp=$row['0'];
$gg=$row['1'];
$time=$row['2'];
$tempg=round($row['3']);
$tempn=round($row['4']);
$checkout=$row['5'];
$IDstr=$row['6'];

$nomepren=estrainome($IDpren);

$time=oraadesso($IDstr);


$IDsottotip=0;
$query="SELECT ID FROM tiporestr WHERE IDstr='$IDstr' AND personale='1'  AND personad='1' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$restr=$row['0'].',';

if(isset($_GET['dato0'])){
	$IDsottosel=$_GET['dato0'];
}

$query="SELECT sottotipologia FROM sottotipologie WHERE ID='$IDsottosel' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$sottotipologia=$row['0'];


echo '

<div data-page="elencoserv" class="page" > 
            <!-- Top Navbar--> 
             
	
			 
			 	<div class="navbar" id="navcal" >
				<div class="navbar-inner">
					<div class="left">
					
					  <a href="#" class="link icon-only back"   >
						<i class="material-icons" style="font-size:40px;">chevron_left</i>
					</a>
					
					</div>
					<div class="center titolonav">'.$sottotipologia.'</div>
					
				</div>
			</div>
		 <div class="page-content">
			 <div class="content-block">
			<input type="hidden" id="IDsottotipsel" value="'.$IDsottosel.'">	
			
		<div class="list-block media-list">
		  <ul>';

$sottot=array();

$IDcliente=$_SESSION['IDcliente'];
$tipocli=$_SESSION['tipocli'];


$query="SELECT s.ID,s.servizio,s.descrizione,s.durata,t.tipolimite FROM servizi as s,tiposervizio as t WHERE s.attivo='1' AND s.IDsottotip='$IDsottosel' AND s.IDtipo=t.ID ORDER BY s.ID ";
$result=mysqli_query($link2,$query);
while($row=mysqli_fetch_row($result)){
	
	$foto=getfoto($row['0'],4);

	$foto='immagini/'.$foto;
	
	$prezzo=calcolaprezzoserv($row['0'],$time,$restr,$IDstr,0,$IDpren,0,$row['3']);	
	
	
	$prenota='';
	$func='';
	if(($row['4']==2)||($row['4']==3)||($row['4']==5)){
		$func='prenotaora('.$row['0'].',0)';
		$prenota='<a href="#" class="link"  onclick="'.$func.'">Prenota</a>';
	}else{
		$prenota='<div style="font-size:11px; color:#222;">PER PRENOTARE<br>CONTATTARE LA STRUTTURA</div>';
	}
	
	//errore
	
	$prenota='<a href="#" class="link"  style="margin-left:60px; font-size:15px;"  onclick="'.$func.'">Prenota</a>';
	
	
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
	
	
	
	$testo.='<li>
		  <a href="#" class="item-link item-content"  onclick="navigation(26,'.$row['0'].',0,0)">
			<div class="item-media"><div style="background:url('.$route.$foto.') no-repeat center center; background-size:cover; border-radius:5px; width:80px; height:80px;"></div></div>
			<div class="item-inner">
			  <div class="item-title-row">
				<div class="item-title">'.$row['1'].'</div>
				<div class="item-after">'.$prezzo.'€</div>
			  </div>
			  <div class="item-subtitle" style="color:#666;font-size:11px;">'.orariservizio($row['0']).'</div>
			  <div class="item-text">'.stripslashes($row['2']).'</div>
			</div>
		  </a>
		</li>';
		
}

$testo.='</ul></div>';


if(!isset($inc)){
echo $testo;
}




?>