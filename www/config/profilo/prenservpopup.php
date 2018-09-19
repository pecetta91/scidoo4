<?php 
header('Access-Control-Allow-Origin: *');
include('../../../config/connecti.php');
include('../../../config/funzioni.php');
include('../../../config/preventivoonline/config/funzioniprev.php');
$testo='';

$IDpren=$_SESSION['IDstrpren'];

$IDsotto=$_GET['IDsotto'];
$timeoggi=$_GET['tempo'];
$datagg=date('Y-m-d',$timeoggi);

$query="SELECT IDstruttura FROM prenotazioni WHERE IDv='$IDpren' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDstruttura=$row['0'];


$query="SELECT GROUP_CONCAT(IDrest SEPARATOR ',') FROM infopren WHERE IDpren ='$IDpren' AND pers='1'";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDrestrtxt=$row['0'].',';

$IDrestrmain=getrestrmain($IDstr);





	
$query="SELECT s.ID,s.servizio,t.tipolimite,s.descrizione FROM servizi as s,prenextra as p,extraonline as e,tiposervizio as t WHERE s.IDstruttura='$IDstruttura'  AND s.attivo='1' AND s.IDtipo<'8' AND s.ID=e.IDserv AND t.ID=s.IDtipo AND s.IDsottotip  IN($IDsotto)  GROUP BY s.ID ORDER BY COUNT(*) DESC LIMIT 10";
	$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
		$testo.='
		<div class="swiper-container sw2"   style="height:250px;padding:40px 15px;">
						<div class="swiper-pagination"></div>
						<div class="swiper-wrapper">';
		while($row=mysqli_fetch_row($result)){
		
			$IDserv=$row['0'];
			$servizio=$row['1'];
			$tipolim=$row['2'];
			$desc=$row['3'];
			$txtinto='';
			
			$foto=getfoto($IDserv,4);
			switch($tipolim){
				case 1:
					$prezzo=calcolaprezzoserv($IDserv,$datagg,$IDrestrmain.',',$IDstruttura,0,$IDpren);
				break;
				case 6:
					$prezzo=calcolaprezzoserv($IDserv,$datagg,1,$IDstruttura,0,$IDpren);
				break;
				case 2:
					$prezzo=calcolaprezzoserv($IDserv,$datagg,$IDrestrtxt,$IDstruttura,0,$IDpren);
				break;
				
			}
				
							if(10<$prezzo)
							{
								$testoprezzo='<div class="sbarraprezzo prezzoverticaleserv prezzoold" >'.$prezzo.' €
														<div class="stileprezzo">50 €</div> 
											  </div>';
							}else{
								
								$testoprezzo='<div class="prezzoverticaleserv stileprezzo" >'.$prezzo.' €</div>';
								
							}
			
			$desc= preg_replace("#(<br\s*/?>\s*)+#"," ,",$desc);
			
			if(strlen($desc)>300){
				$desc=stripslashes(substr($desc,0,300));
			 }
			
			if($desc==''){
					$desc='<br/>';
			}
			
			if(($tipolim==2)||($tipolim==6)){
				$func='navigation2(13,'."'".$IDserv.",".$timeoggi.",2'".',0,0);';
				$pulsante='Prenota Ora';
			}else{
				$func='navigation(26,'.$IDserv.',0,0);';
				$pulsante='Informazioni';
			}
			$orariserv=orariservizio($IDserv);
			$orariserv=preg_replace("/<div\s(.+?)>(.+?)<\/div>/is", "<span>$2</span>",$orariserv);
			
			$testo.='
						  <div class="swiper-slide pags" >
						  		
						  			<div class="paginaslider">
										<div class="prenotaservscroller">
													<div class="corpoorizzontalescrol" style="margin-top:170px">
														<button class="button button-fill button-raised prenotaoraoriz" onclick="'.$func.'">'.$pulsante.'</button>
													</div>
										</div>
										
										<div  class="orizontalscroll" style="border:1px solid #e1e1e1;height:160px">
											<div class="row no-gutter" style="padding-left:15px;padding-top:15px;position:relative">
												<div class="servnome col-100 " style="position:relative;" >'.$servizio.' '.$testoprezzo.'</div>
												<div class="col-100 servora mt5">'.$orariserv.'</div>
											</div>	
											<div style="padding:10px 15px;color:#333">'.$desc.'</div>
										</div>		
									</div>
						  </div>';
			$numero++;

		}
			
		$testo.='</div></div>';
		
		
		
	}else{
		$testo.='<div style="padding:10px">Nessun Servizio Disponibile</div>';
	}







	
?>
<div class="picker-modal " id="popoverord" style="height:400px;">
		  <div class="toolbar">
			<div class="toolbar-inner">
		  	  <div class="left">Scegli un Servizio</div>
			  <div class="right"><a href="#" class="close-picker" >Chiudi</a></div>
			</div>
		  </div>
		  <div class="picker-modal-inner ">
		 <div class="page-content bcw" > 
		 	<?php echo $testo;?>
		  </div>
		  </div>
</div>
