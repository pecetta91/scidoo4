<?php
header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');

$testo='';
$IDpren=$_SESSION['IDstrpren'];


$query="SELECT IDstruttura FROM prenotazioni WHERE IDv='$IDpren' LIMIT 1";

$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDstr=$row['0'];

$query="SELECT nome FROM struttura WHERE ID='$IDstr' LIMIT 1";

$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$nomestr=$row['0'];



$query="SELECT ID FROM firstentry WHERE IDobj='$IDstr' AND IDuser='$IDpren' AND tipoobj='0' AND tipouser='2'  LIMIT 1";
$result=mysqli_query($link2,$query);
if(mysqli_num_rows($result)==0){
	
	
	$slider1='';
	$slider2='';
	$slider3='';
	$slider4='';
	
	$query="SELECT s.ID,s.IDtipo FROM servizi as s,extraonline as ex WHERE s.IDstruttura='$IDstr' AND s.attivo>'0' AND s.ID=ex.IDserv LIMIT 1";
	$result=mysqli_query($link2,$query);
		if(mysqli_num_rows($result)>0){
			//ci sono servizi online prenotabili
			$slider1='<div class="swiper-slide" >
				<div class="containerslider"><span style="width:100%;font-size:20px;color:#2641da">Personalizza il tuo soggiorno</span></div>
			</div>';
			
			$slider2='
			<div class="swiper-slide" >
				<div class="containerslider"><span style="width:100%;font-size:20px;color:#2641da">Modifica gli orari, prenota e rendi unico il tuo servizio</span></div>
			</div>';
		}
	
	$query2="SELECT ID,attivo FROM autoconf WHERE IDstr='$IDstr' LIMIT 1";//se presente il pulsante compare
	$result2=mysqli_query($link2,$query2);
	if(mysqli_num_rows($result)>0){
		
		$slider3='<div class="swiper-slide" >
			<div class="containerslider"><span style="width:100%;font-size:20px;color:#2641da">Conferma la tua prenotazione</span></div>
		</div>';
	}
	
	$query="SELECT ID FROM luoghieventi WHERE IDstr='$IDstr' LIMIT 1 ";
	$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
		
		$slider4='<div class="swiper-slide">
			<div class="containerslider"><span style="width:100%;font-size:20px;color:#2641da">Scopri i luoghi da visitare</span></div>
		</div>';
	}
	
	
			$tab='
			
			<div class="swiper-slide" >
			
				<div class="sliderintro1" style="left:50%;">
					<i class="material-icons">sentiment_satisfied</i>
				</div>
				<div class="sliderintro2"><strong>Benvenuto<br/>
				a '.$nomestr.'
				
				</strong>
				<br/>
				
				
				</div>
			
				<div class="sliderintro3">'."
				Per ".$nomestr." il suo soggiorno e' importante e per questo avrà a disposizione questa App al fine di rendere la sua vacanza straordinaria!
				".'
				
				</div>
				
				
				
			</div>
			
			
			<div class="swiper-slide" >
				<div class="sliderintro1" style="left:50%;">
				<strong>Con Scidoo potrai</strong><br/>
					<i class="material-icons">touch_app</i>
				</div>
				<div class="sliderintro2"><strong>Effettuare il Check-in Online</strong>
				<br/>
				<div class="sliderintro3">'."
				Risparmia tempo all'arrivo e goditi a pieno il tuo soggiorno!
				".'
				
				</div>
				
				




			</div>
			
			<div class="swiper-slide" >
				<div class="containerslider"><span style="width:100%;font-size:20px;color:#2641da">Indica il tuo orario di arrivo</span></div>
			</div>
			'.$slider3.'
			'.$slider1.'
			
			
			<div class="swiper-slide" >
				<div class="containerslider"><span style="width:100%;font-size:20px;color:#2641da">Scopri il percorso migliore per arrivare</span></div>
			</div>
			
			'.$slider4.'
			'.$slider2.'
			<div class="swiper-slide" >
				<div class="containerslider"><span style="width:100%;font-size:20px;color:#2641da">Buone vacanze da Scidoo</span></div>
			</div>';

	

$testo.='<div class="swiper-container swiper-6" style="height:100%">
						<div class="swiper-wrapper">
							'.$tab.'
						</div>
						
						<div class="swiper-avanti" id="avanti"><a>Avanti</a></div>
						
						
						<div class="swiper-pagination"></div>
				</div>
				
				<div onclick="myApp.closeModal();" class="chiudi-swiper"><a class="salta-swiper">Salta</a></div>
				';
	

	$query2="INSERT INTO firstentry VALUES(NULL,'$IDstr','0','$IDpren','2')";
	//$result=mysqli_query($link2,$query2);

	echo '<div class="popup">
		<div class="content-block" style="margin: 0;height: 100%">'.$testo.'</div>
		</div>';
	
}else{
	echo '1';
}

?>

