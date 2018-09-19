<?php
header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');
include('../../config/funzionilingua.php');

$testo='';

$_SESSION['route']='https://www.scidoo.com/';
$_SESSION['route']='http://188.11.58.195:108/milliont/';

$IDutente=$_SESSION['ID'];
$IDstruttura=$_SESSION['IDstruttura'];

$query="SELECT ID FROM firstentry WHERE IDobj='$IDstruttura' AND IDuser='$IDutente' AND tipoobj='1' AND tipouser='1'  LIMIT 1";
$result=mysqli_query($link2,$query);
if(mysqli_num_rows($result)==0){  //==0
	
			//<div class="containerslider"><span style="font-size:25px;color:#2641da">Benvenuto</span></div>
			$tab='
			<div class="swiper-slide backgroundslider" >
				
				<div class="sliderintro1">
				<img src="'.$_SESSION['route'].'v19/scidoologor.jpg">
				
				</div>
				<div class="sliderintro2"><strong>Benvenuto su Scidoo</strong>
				<br/>
				<div class="sliderintro3">Il primo Software in Cloud che ti permette di avere una gestione completa e professionale della tua struttura ricettiva.</div>
				
				</div>
			</div>
			
			
			<div class="swiper-slide backgroundslider" >
				
				<div class="sliderintro1" style="left:50%;">
					<i class="icon f7-icons">world</i>
				</div>
				<div class="sliderintro2"><strong>Gestisci Ovunque la Tua Struttura</strong>
				<br/>
				<div class="sliderintro3">Coordina, Gestisci, Controlla ed Organizza la tua Attività ovunque ti trovi</div>
				
				</div>
			</div>
			
			<div class="swiper-slide backgroundslider">
			
				<div class="sliderintro1" style="left:50%;">
					<i class="icon f7-icons">persons</i>
				</div>
				<div class="sliderintro2"><strong>Collega i tuoi Collaboratori</strong>
				<br/>
				<div class="sliderintro3">Fornisci le credenziali ai tuoi collaboratori, affidagli delle mansioni e saranno sempre aggiornati sugli eventi della struttura.<br/>Semplifica la tua Gestione!</div>
				
				</div>
				
				
				
				
			</div>
			
			<div class="swiper-slide backgroundslider" >
			
				<div class="sliderintro1" style="left:50%;">
					<i class="material-icons">restaurant_menu</i>
				</div>
				<div class="sliderintro2"><strong>Gestisci tutti i Settori della tua Attività</strong>
				<br/>
				<div class="sliderintro3">Scidoo ti da la possibilità di gestire ed organizzare i tuoi settori interni quali: Ristorante, Centro Benessere, Negozio Online, Trattamenti Estetici e Massaggi, Pulizie, Affitti ecc.</div>
				
				</div>
			
			</div>
			
			<div class="swiper-slide backgroundslider">
			
				<div class="sliderintro1" style="left:50%;">
					<i class="icon f7-icons">graph_round</i>
				</div>
				<div class="sliderintro2"><strong>Incrementa le Prenotazioni Dirette con il Booking Engine</strong>
				<br/>
				<div class="sliderintro3">'."
				Scidoo offre il servizio di Prenotazione Online (Booking Engine) integrabile all'interno del tuo sito.<br/>Offri ai tuoi ospiti la possibilità di prenotare in autonomia, scoprire le offerte  e risparmia sulle commessioni dei portali online.".'
				
				</div>
				
				</div>
			
			
			</div>
			
			<div class="swiper-slide backgroundslider ">
			
				<div class="sliderintro1" style="left:50%;">
					<i class="icon f7-icons">world</i>
				</div>
				<div class="sliderintro2"><strong>Sincronizza le Disponibilita con i Portali Online</strong>
				<br/>
				<div class="sliderintro3">'."
				Scidoo ti da la possibilità di sincronizzare le disponibilità di sincronizzare le disponibilità di tutti i portali connesso ed evitare gli overbooking massimizzando le possibilità di ricevere nuove prenotazioni.<br/>
				Utilizza un solo calendario e risparmia tempo da dedicare ai tuoi ospiti.".'
				
				</div>
				
				</div>
			
			
			
			</div>
			
			<div class="swiper-slide backgroundslider">
				<div class="sliderintro1" style="left:50%;">
					<i class="material-icons">account_balance</i>
				</div>
				<div class="sliderintro2"><strong>Stai in contatto con il tuo Ospite</strong>
				<br/>
				<div class="sliderintro3">'."
				IMPRESSIONA i tuoi ospiti fornendogli un APP Mobile dedicata e rendi il suo soggiorno indimenticabile.<br/>
			 	Invigli dei messaggi automatici relativi alla sua prenotazione e potrà accedere alla sua area privata da cui scoprire la struttura, cosa offre il vostro territorio e controllare la sua prenotazione . ".'
				
				</div>
				
				</div>
				
				
			</div>
			
			<div class="swiper-slide backgroundslider">
			
				<div class="sliderintro1" style="left:50%;">
					<i class="material-icons">cloud_done</i>
				</div>
				<div class="sliderintro2"><strong>La tua Struttura su qualsiasi Dispositivo</strong>
				<br/>
				<div class="sliderintro3">'."
				Visualizza le tue prenotazioni su qualsiasi dispositivo (PC, Notebook, Tablet, Smartphone).<br/>
				Mediante la versione DESKTOP potrai personalizzare la tua struttura e massimizzare le tue possibilità
				".'
				
				</div>
				
				</div>
			
			</div>
			
			<div class="swiper-slide backgroundslider">
			
				<div class="sliderintro1" style="left:50%;">
					<i class="material-icons">assistant</i>
				</div>
				<div class="sliderintro2"><strong>Un Assistente<br/>dedicato solo a Te</strong>
				<br/>
				<div class="sliderintro3">'."
				L'azienda Webcoom le affiancherà un Assistente Personale che la aiuterà a configurare al meglio la sua struttura e migliorare la sua organizzazione.<br/><br/>
				INIZIA SUBITO
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

		$query2="INSERT INTO firstentry VALUES(NULL,'$IDstruttura','1','$IDutente','1')";
		$result=mysqli_query($link2,$query2);
	
	
	echo '<div class="popup">
		<div class="content-block" style="margin: 0;height: 100%">'.$testo.'</div>
		</div>';
	
}else{
	echo '1';
}



?>