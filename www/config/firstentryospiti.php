<?php
header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');

$testo='';
$IDpren=$_SESSION['IDstrpren'];


$query="SELECT IDstruttura,gg FROM prenotazioni WHERE IDv='$IDpren' LIMIT 1";

$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDstr=$row['0'];
$notti=$row['1'];


$query="SELECT nome FROM strutture WHERE ID='$IDstr' LIMIT 1";
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
			$slider1='
			
			<div class="swiper-slide backgroundslider" >
					<div class="sliderintro1" style="left:50%;">
						<i class="material-icons">star</i>
					</div>
					<div class="sliderintro2"><strong>Personalizza il tuo Soggiorno</strong>
						<br/>

						<div class="sliderintro3">'."
						Scropri e Prenota nuovi Servizi e rendi UNICA e MAGICA la tua Vacanza!
						".'

						</div>
					</div>
				</div>
			
			
			';
			
			$slider2='
			
			<div class="swiper-slide backgroundslider" >
					<div class="sliderintro1" style="left:50%;">
						<i class="material-icons">timer</i>
					</div>
					<div class="sliderintro2"><strong>Organizza gli Orari dei tuoi Servizi</strong>
						<br/>

						<div class="sliderintro3">'."
						Indica ed Organizza il tuo soggiorno in modo da ricevere sempre il miglior servizio.
						".'

						</div>
					</div>
				</div>
			
			
			
			';
		}
	
	$query2="SELECT ID,attivo FROM autoconf WHERE IDstr='$IDstr' LIMIT 1";//se presente il pulsante compare
	$result2=mysqli_query($link2,$query2);
	if(mysqli_num_rows($result)>0){
		
		$slider3='
			<div class="swiper-slide backgroundslider" >
					<div class="sliderintro1" style="left:50%;">
						<i class="material-icons">security</i>
					</div>
					<div class="sliderintro2"><strong>Conferma la tua Prenotazione in modo Sicuro</strong>
						<br/>

						<div class="sliderintro3">'."
						Scidoo ti permette di confermare la prenotazione in modo Sicuro e Garantito e Tutela la tua Privacy
						".'

						</div>
					</div>
				</div>
		
		
		
		';
	}
	
	$query="SELECT ID FROM luoghieventi WHERE IDstr='$IDstr' LIMIT 1 ";
	$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
		
		$slider4='
			<div class="swiper-slide backgroundslider" >
					<div class="sliderintro1" style="left:50%;">
						<i class="material-icons">location_on</i>
					</div>
					<div class="sliderintro2"><strong>Scoprire Luoghi ed Eventi</strong>
						<br/>

						<div class="sliderintro3">'."
						Avrete sempre a portata di mano i Luoghi da Visitare e gli Eventi che la struttura vi suggerisce.
						".'

						</div>
					</div>
				</div>
		
		
		';
	}
	
	
			$tab='
			
			<div class="swiper-slide backgroundslider" >
			
				<div class="sliderintro1" style="left:50%;">
					<i class="material-icons">sentiment_satisfied</i>
				</div>
				<div class="sliderintro2"><strong>Benvenuto a<br/>
				'.$nomestr.'
				
				</strong>
				<br/>
				<div class="sliderintro3">'."
				Per ".$nomestr." il suo soggiorno e' importante e per questo avrà a disposizione questa App al fine di rendere la sua vacanza straordinaria!
				".'
				
				</div>
				</div>
				
				
			</div>';
	
			
			if($gg>0){
				$tab.='<div class="swiper-slide backgroundslider" >
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
				</div>
				
				<div class="swiper-slide backgroundslider" >
					<div class="sliderintro1" style="left:50%;">
						<i class="material-icons">access_time</i>
					</div>
					<div class="sliderintro2"><strong>Indicare il tuo Orario di Arrivo</strong>
						<br/>

						<div class="sliderintro3">'."
						Indica e Modifica l'orario di arrivo. Saremo sempre pronti ad accoglierla al meglio!
						".'

						</div>
					</div>
				</div>
				
				
				
				';
				
				
				
			}
			
	
	
			
			
			
			
			$tab.='
			'.$slider3.'
			'.$slider1.'
			
			
		<div class="swiper-slide backgroundslider" >
					<div class="sliderintro1" style="left:50%;">
						<i class="material-icons">map</i>
					</div>
					<div class="sliderintro2"><strong>Controllare la Strada Migliore per Arrivare</strong>
						<br/>

						<div class="sliderintro3">'."
						Inizia con il piede giusto!<br/>Conoscere la strada migliore per arrivare a destinazione ti farà arrivare più rilassato e nel modo più veloce possibile.
						".'

						</div>
					</div>
				</div>
			
			'.$slider4.'
			'.$slider2.'
			
			
			<div class="swiper-slide backgroundslider" >
					<div class="sliderintro1" style="left:50%;">
						<i class="material-icons">sentiment_very_satisfied</i>
					</div>
					<div class="sliderintro2"><strong>Buon Soggiorno da<br/>'.$nomestr.'</strong>
						<br/>

						<div class="sliderintro3">'."
						Servizio di Scidoo Booking
						".'

						</div>
					</div>
				</div>
			';

	

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
	$result=mysqli_query($link2,$query2);

	echo '<div class="popup">
		<div class="content-block" style="margin: 0;height: 100%">'.$testo.'</div>
		</div>';
	
}else{
	echo '1';
}

?>

