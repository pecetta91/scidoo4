<?php 
	include('../../../config/connecti.php');
	include('../../../config/funzioni.php');
	include('../../../config/funzionilingua.php');
	header('Access-Control-Allow-Origin: *');
	$testo='';


$IDpren=$_SESSION['IDstrpren'];
$route=$_SESSION['route'];

$tipopriv=$_GET['dato0'];

$titolopriv='Privacy Policy';
if($tipopriv==2){
	$titolopriv='Cookie Policy';
}


$query="SELECT IDstruttura FROM prenotazioni WHERE IDv='$IDpren' LIMIT 1";

$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDstr=$row['0'];

$query="SELECT nome,mail,sito,tel FROM strutture WHERE ID='$IDstr' ";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$nome=$row['0'];
$mail=$row['1'];
$sito=$row['2'];
$tel=$row['3'];


$testo.='<div data-page="privacy" class="page" > 
		 	<div class="navbar" id="navcal" >
				<div class="navbar-inner">
					<div class="left">
					
					  <a href="#" class="link icon-only back">
						<i class="material-icons fs40" >chevron_left</i>
					</a>
					</div>
					<div class="center titolonav">'.$titolopriv.'</div>
					<div class="right"></div>
				</div>
			</div>
			 <div class="page-content">
		 		<div class="content-block" id="privacy" >
					<div class="privacyscheda">
								<p class="fs18 txtnero"><strong>Titolare del Trattamento dei Dati</strong></p>
								<p class="fs16"><strong class="bluenavbar">WEBCOOM</strong></p>
								<p class="fs18 txtnero"><strong>Responsabile del Trattamento dei Dati</strong></p>
								<p class="fs16"><strong class="bluenavbar">'.$nome.'</strong></p>
								<p class="fs16 txtnero">Email: <strong class="bluenavbar" onclick="location.href='."'mailto:".$mail."'".'">'.$mail.'</strong></p>
								<p class="fs16 txtnero">Telefono:  <strong class="bluenavbar" onclick="location.href='."'tel:".$tel."'".'">'.$tel.'</strong></p>
								<p class="fs16 txtnero">Sito:  <strong class="bluenavbar" onclick="location.href='."'http://".$sito."'".'" >'.$sito.'</strong></p>
					
					</div>';


switch($tipopriv){
		
	case 1:			
		$testo.='
		
		<div class="privacyscheda">
		
		<p class="fs18 txtnero"><strong>Informativa sulla privacy</strong></p>
								<p>Questa Applicazione raccoglie alcuni Dati Personali dei propri Utenti.</p>

					
								<p class="fs18 txtnero"><strong>Tipologie di Dati raccolti</strong></p>
								
								<p class="fs14">Fra i Dati Personali raccolti da questa Applicazione, in modo autonomo o tramite terze parti, ci sono: Cookie, Dati di utilizzo, Nome, Cognome, Numero di Telefono, Indirizzo, Numero di Fax, Nazione, Stato, Provincia, Email e CAP.</p>
								
								<p class="fs14">Altri Dati Personali raccolti potrebbero essere indicati in altre sezioni di questa privacy policy o mediante testi informativi visualizzati contestualmente alla raccolta dei Dati stessi.<br>I Dati Personali possono essere inseriti volontariamente dall’Utente, oppure raccolti in modo automatico durante l’uso di questa Applicazione.<br>Il mancato conferimento da parte dell’Utente di alcuni Dati Personali potrebbe impedire a questa Applicazione di erogare i propri servizi.</p>
								
								<p class="fs16">L’Utente si assume la responsabilità dei Dati Personali di terzi pubblicati o condivisi mediante questa Applicazione e garantisce di avere il diritto di comunicarli o diffonderli, liberando il Titolare da qualsiasi responsabilità verso terzi.</p>
					
								<p class="fs16 txtnero"><strong>Modalità e luogo del trattamento dei Dati raccolti</strong></p>
								<p class="fs14 txtnero"><strong>Modalità di trattamento</strong></p>
								
								<p>Il Titolare tratta i Dati Personali degli Utenti adottando le opportune misure di sicurezza volte ad impedire l’accesso, la divulgazione, la modifica o la distruzione non autorizzate dei Dati Personali.</p>
								
								<p class="fs14 txtnero"><strong>Luogo</strong></p>
								
								<p>I Dati sono trattati presso le sedi operative del Titolare ed in ogni altro luogo in cui le parti coinvolte nel trattamento siano localizzate. Per ulteriori informazioni, contatta il Titolare.</p>
								
								<p class="fs14 txtnero"><strong>Tempi</strong></p>
								
								<p>I Dati sono trattati per il tempo necessario allo svolgimento del servizio richiesto dall’Utente, o richiesto dalle finalità descritte in questo documento, e l’Utente può sempre chiedere l’interruzione del Trattamento o la cancellazione dei Dati.</p>
						
								<p class="fs16 txtnero"><strong>Finalità del Trattamento dei Dati raccolti</strong></p>
								<p>I Dati dell’Utente sono raccolti per consentire al Titolare di fornire i propri servizi.</p>
							
								<p class="fs16 txtnero"><strong>Permessi Facebook richiesti da questa Applicazione</strong></p>
								<p>Questa Applicazione può richiedere alcuni permessi Facebook che le consentono di eseguire azioni con l’account Facebook dell’Utente e di raccogliere informazioni, inclusi Dati Personali, da esso.</p>
								
								<p class="fs16 txtnero"><strong>I permessi richiesti sono i seguenti:</strong></p>
								
								<p class="fs14 txtnero"><strong>Informazioni di base</strong></p>
								<p>Le informazioni di base dell’Utente registrato su Facebook che normalmente includono i seguenti Dati: id, nome, immagine, genere e lingua di localizzazione ed, in alcuni casi gli “Amici” di Facebook. Se l’Utente ha reso disponibili pubblicamente Dati ulteriori, gli stessi saranno disponibili.</p>
								<p class="fs14 txtnero"><strong>Email</strong></p>
								<p>Fornisce accesso all’indirizzo email primario dell’Utente</p>
								
								<p class="fs16 txtnero"><strong>Dettagli sul trattamento dei Dati Personali</strong></p>
								<p>I Dati Personali sono raccolti per le seguenti finalità ed utilizzando i seguenti servizi:</p>
		</div>
								<div class="list-block accordion-list">
									  <ul>
										<li class="accordion-item bordoaccordion" id="accordion1" onclick="apriacc(1)">
										<a href="#" class="item-content item-link">
											<div class="item-inner">
											  <div class="item-title">Accesso agli account su servizi terzi</div>
											</div>
										</a>
										  <div class="accordion-item-content">
											<div class="content-block p5 c333">
												 <p>Questi servizi permettono a questa Applicazione di prelevare Dati dai tuoi account su servizi terzi ed eseguire azioni con essi.<br>Questi servizi non sono attivati automaticamente, ma richiedono l’espressa autorizzazione dell’Utente.</p>
												<p class="fs14 txtnero"><strong>Accesso all’account Facebook (Questa Applicazione)</strong></p>
												<p>Questo servizio permette a questa Applicazione di connettersi con l’account dell’Utente sul social network Facebook, fornito da Facebook, Inc.</p>
												<p>Permessi richiesti: Email.</p>
												<p>Luogo del trattamento: USA
												</p>
											</div>
										  </div>
										</li>
										
										<li class="accordion-item bordoaccordion" id="accordion2" onclick="apriacc(2)">
										<a href="#" class="item-content item-link">
											<div class="item-inner">
											  <div class="item-title">Pubblicità</div>
											</div>
										</a>
										  <div class="accordion-item-content">
											<div class="content-block p5 c333">
											  <p>Questi servizi consentono di utilizzare i Dati dell’Utente per finalità di comunicazione commerciale in diverse forme pubblicitarie, quali il banner, anche in relazione agli interessi dell’Utente.
											  <br>Ciò non significa che tutti i Dati Personali vengano utilizzati per questa finalità. Dati e condizioni di utilizzo sono indicati di seguito.</p>
											<p class="fs14 txtnero"><strong>Google AdSense (Google Inc.)</strong></p>
											<p>Google AdSense è un servizio di advertising fornito da Google Inc. Questo servizio usa il Cookie “Doubleclick”, che traccia l’utilizzo di questa Applicazione ed il comportamento dell’Utente in relazione agli annunci pubblicitari, ai prodotti e ai servizi offerti.
											<br>
											Dati personali raccolti: Cookie e Dati di utilizzo.<br/>
											Luogo del trattamento: USA
											</p>
											</div>
										  </div>
										</li>
										<li class="accordion-item bordoaccordion" id="accordion4" onclick="apriacc(4)">
										<a href="#" class="item-content item-link">
											<div class="item-inner">
											  <div class="item-title">Gestione dei pagamenti</div>
											</div>
										</a>
										  <div class="accordion-item-content ">
											<div class="content-block p5 c333">
											    <p>I servizi di gestione dei pagamenti permettono a questa Applicazione di processare pagamenti tramite carta di credito, bonifico bancario o altri strumenti. I dati utilizzati per il pagamento vengono acquisiti direttamente dal gestore del servizio di pagamento richiesto senza essere in alcun modo trattati da questa Applicazione.<br>Alcuni di questi servizi potrebbero inoltre permettere l’invio programmato di messaggi all’Utente, come email contenenti fatture o notifiche riguardanti il pagamento.</p>
											<p class="fs14 txtnero"><strong>PayPal (Paypal)</strong></p>
											<p>PayPal è un servizio di pagamento fornito da PayPal Inc., che consente all’Utente di effettuare pagamenti online utilizzando le proprie credenziali PayPal.</p>
											<p>Dati personali raccolti: Varie tipologie di Dati</p>
											</div>
										  </div>
										</li>
										<li class="accordion-item bordoaccordion" id="accordion5" onclick="apriacc(5)">
										<a href="#" class="item-content item-link">
											<div class="item-inner">
											  <div class="item-title">Remarketing e Behavioral Targeting</div>
											</div>
										</a>
										  <div class="accordion-item-content">
											<div class="content-block p5 c333">
													<p>Questi servizi consentono a questa Applicazione ed ai suoi partner di comunicare, ottimizzare e servire annunci pubblicitari basati sull’utilizzo passato di questa Applicazione da parte dell’Utente.<br>Questa attività viene effettuata tramite il tracciamento dei Dati di Utilizzo e l’uso di Cookie, informazioni che vengono trasferite ai partner a cui l’attività di remarketing e behavioral targeting è collegata.</p>
											<p class="fs14 txtnero"><strong>AdWords Remarketing (Google Inc.)</strong></p>
											<p>AdWords Remarketing è un servizio di Remarketing e Behavioral Targeting fornito da Google Inc. che collega l’attività di questa Applicazione con il network di advertising Adwords ed il Cookie Doubleclick.<br/>
											Dati personali raccolti: Cookie e Dati di utilizzo.<br/>
											Luogo del trattamento: USA</p>  	
											</div>
										  </div>
										</li>
										<li class="accordion-item bordoaccordion" id="accordion6" onclick="apriacc(6)">
										<a href="#" class="item-content item-link">
											<div class="item-inner">
											 <div class="item-title">Statistica</div>
											</div>
										</a>
										  <div class="accordion-item-content">
											<div class="content-block p5 c333">
												  <p>I servizi contenuti nella presente sezione permettono al Titolare del Trattamento di monitorare e analizzare i dati di traffico e servono a tener traccia del comportamento dell’Utente.</p>
												<p class="fs14 txtnero"><strong>Google Analytics (Google Inc.)</strong></p>
												<p>Google Analytics è un servizio di analisi web fornito da Google Inc. (“Google”). Google utilizza i Dati Personali raccolti allo scopo di tracciare ed esaminare l’utilizzo di questa Applicazione, compilare report e condividerli con gli altri servizi sviluppati da Google.<br>Google potrebbe utilizzare i Dati Personali per contestualizzare e personalizzare gli annunci del proprio network pubblicitario.<br/>
												Dati personali raccolti: Cookie e Dati di utilizzo.<br/>
												Luogo del trattamento: USA</p>
												<p class="fs14 txtnero"><strong>Monitoraggio conversioni di Google AdWords (Google Inc.)</strong></p>
												<p>Il monitoraggio conversioni di Google AdWords è un servizio di statistiche fornito da Google Inc. che collega i dati provenienti dal network di annunci Google AdWords con le azioni compiute all’interno di questa Applicazione.<br/>
												Dati personali raccolti: Cookie e Dati di utilizzo.<br/>
												Luogo del trattamento: USA</p>
												<p class="fs14 txtnero"><strong>Woopra (Woopra)</strong></p>
												<p>Woopra è un servizio di statistica fornito da Woopra Inc.<br/>
												Dati personali raccolti: Cookie e Dati di utilizzo.<br/>
												Luogo del trattamento: USA</p>
											</div>
										  </div>
										</li>
										
										 <li class="accordion-item bordoaccordion" id="accordion7" onclick="apriacc(7)">
										 <a href="#" class="item-content item-link">
											<div class="item-inner">
											  <div class="item-title">Visualizzazione di contenuti da piattaforme esterne</div>
											</div>
										</a>
										  <div class="accordion-item-content">
											<div class="content-block p5 c333">
											   	 <p>Questi servizi permettono di visualizzare contenuti ospitati su piattaforme esterne direttamente dalle pagine di questa Applicazione e di interagire con essi.<br>Nel caso in cui sia installato un servizio di questo tipo, è possibile che, anche nel caso gli Utenti non utilizzino il servizio, lo stesso raccolga dati di traffico relativi alle pagine in cui è installato.</p>
												<p class="fs14 txtnero"><strong>Google Fonts (Google Inc.)</strong></p>
												<p>Google Fonts è un servizio di visualizzazione di stili di carattere gestito da Google Inc. che permette a questa Applicazione di integrare tali contenuti all’interno delle proprie pagine.<br/>
												Dati personali raccolti: Cookie e Dati di utilizzo.<br/>
												Luogo del trattamento: USA</p> 
											</div>
										  </div>
										</li>
										 <li class="accordion-item bordoaccordion" id="accordion8" onclick="apriacc(8)">
										 <a href="#" class="item-content item-link">
											<div class="item-inner">
											  <div class="item-title">Monitoraggio dell’infrastruttura</div>
											</div>
										</a>
										  <div class="accordion-item-content">
											<div class="content-block p5 c333">
											  	<p>Questi servizi permettono a questa Applicazione di monitorare l’utilizzo ed il comportamento di componenti della stessa, per consentirne il miglioramento delle prestazioni e delle funzionalità, la manutenzione o la risoluzione di problemi.<br>I Dati Personali trattati dipendono dalle caratteristiche e della modalità d’implementazione di questi servizi, che per loro natura filtrano l’attività di questa Applicazione.</p>
												<p class="fs14 txtnero"><strong>New Relic (New Relic)</strong></p>
												<p>New Relic è un servizio di monitoraggio fornito da New Relic Inc.<br>Le modalità di integrazione di New Relic prevedono che questo filtri tutto il traffico di questa Applicazione, ossia le comunicazioni fra questa Applicazione ed il browser o il device dell’Utente, permettendo anche la raccolta di dati statistici su di esso.<br/>
												Dati personali raccolti: Varie tipologie di Dati.<br/>
												Luogo del trattamento: USA</p>
												<p class="fs14 txtnero"><strong>Pingdom (Pingdom AB)</strong></p>
												<p>Pingdom è un servizio di monitoraggio fornito da Pingdom AB.<br/>
												Dati personali raccolti: Cookie e Dati di utilizzo.<br/>
												Luogo del trattamento: Svezia</p>	
											</div>
										  </div>
										</li>
									  </ul>
								  </div>
								  

								  
        <div class="privacyscheda">
								<p class="fs16 txtnero"><strong>Ulteriori informazioni sul trattamento</strong></p>
								<p class="fs14 txtnero"><strong>Difesa in giudizio</strong></p>
								<p>I Dati Personali dell’Utente possono essere utilizzati per la difesa da parte del Titolare in giudizio o nelle fasi propedeutiche alla sua eventuale instaurazione, da abusi nell’utilizzo della stessa o dei servizi connessi da parte dell’Utente.<br>L’Utente dichiara di essere consapevole che il Titolare potrebbe essere richiesto di rivelare i Dati su richiesta delle pubbliche autorità.</p>
								
								<p class="fs14 txtnero"><strong>Informative specifiche</strong></p>
								
								<p>Su richiesta dell’Utente, in aggiunta alle informazioni contenute in questa privacy policy, questa Applicazione potrebbe fornire all’Utente delle informative aggiuntive e contestuali riguardanti servizi specifici, o la raccolta ed il trattamento di Dati Personali.</p>
								
								<p class="fs14 txtnero"><strong>Log di sistema e manutenzione</strong></p>
								
								<p>Per necessità legate al funzionamento ed alla manutenzione, questa Applicazione e gli eventuali servizi terzi da essa utilizzati potrebbero raccogliere Log di sistema, ossia file che registrano le interazioni e che possono contenere anche Dati Personali, quali l’indirizzo IP Utente.</p>
								
								<p class="fs14 txtnero"><strong>Informazioni non contenute in questa policy</strong></p>
								<p>Maggiori informazioni in relazione al trattamento dei Dati Personali potranno essere richieste in qualsiasi momento al Titolare del Trattamento utilizzando le informazioni di contatto.</p>
								
								<p class="fs14 txtnero"><strong>Esercizio dei diritti da parte degli Utenti</strong></p>
								
								<p>I soggetti cui si riferiscono i Dati Personali hanno il diritto in qualunque momento di ottenere la conferma dell’esistenza o meno degli stessi presso il Titolare del Trattamento, di conoscerne il contenuto e l’origine, di verificarne l’esattezza o chiederne l’integrazione, la cancellazione, l’aggiornamento, la rettifica, la trasformazione in forma anonima o il blocco dei Dati Personali trattati in violazione di legge, nonché di opporsi in ogni caso, per motivi legittimi, al loro trattamento. Le richieste vanno rivolte al Titolare del Trattamento.</p>
								<p>Questa Applicazione non supporta le richieste “Do Not Track”. Per conoscere se gli eventuali servizi di terze parti utilizzati le supportano, consulta le loro privacy policy.</p>
								
								<p class="fs14 txtnero"><strong>Modifiche a questa privacy policy</strong></p>
								
								<p>Il Titolare del Trattamento si riserva il diritto di apportare modifiche alla presente privacy policy in qualunque momento dandone pubblicità agli Utenti su questa pagina. Si prega dunque di consultare spesso questa pagina, prendendo come riferimento la data di ultima modifica indicata in fondo. Nel caso di mancata accettazione delle modifiche apportate alla presente privacy policy, l’Utente è tenuto a cessare l’utilizzo di questa Applicazione e può richiedere al Titolare del Trattamento di rimuovere i propri Dati Personali. Salvo quanto diversamente specificato, la precedente privacy policy continuerà ad applicarsi ai Dati Personali sino a quel momento raccolti.</p>
								
								<p class="fs14 txtnero"><strong>Informazioni su questa privacy policy</strong></p>
								
								<p>Il Titolare del Trattamento dei Dati è responsabile per questa privacy policy.</p>
						</div>		
																
								';
	break;
		
	case 2:
				$testo.='
				<div class="privacyscheda">
					
						<p class="fs18 txtnero"><strong>Informativa estesa sui cookie</strong></p>
						<p class="fs16 txtnero"><strong>Che cosa sono i cookie e a che cosa servono</strong></p>
						
						<p>Un cookie è un piccolo file di testo che un sito web visitato dall’utente invia al suo terminale (computer, dispositivo mobile quale smartphone o tablet) dove viene memorizzato per essere poi ritrasmesso a tale sito in occasione di una visita successiva al sito medesimo. </p>
						<p>I Cookie sono costituiti da porzioni di codice installate all’interno del browser che assistono il Titolare nell’erogazione del servizio in base alle finalità descritte. Alcune delle finalità di installazione dei Cookie potrebbero, inoltre, necessitare del consenso dell’Utente.</p>
						
								<p class="fs16 txtnero"><strong>Cookie tecnici che non richiedono il consenso preventivo dell’Utente</strong></p>

							<p>I cookie tecnici sono utilizzati per consentire il funzionamento dell’applicazione e/o per fornire all’Utente un servizio o una funzione che l’Utente ha espressamente richiesto.</p>
							
								<p class="fs16 txtnero"><strong>Altre tipologie di Cookie o strumenti terzi che potrebbero farne utilizzo</strong></p>

						<p>Alcuni dei servizi elencati di seguito potrebbero non richiedere il consenso dell’Utente o potrebbero essere gestiti direttamente dal titolare – a seconda di quanto descritto – senza l’ausilio di terzi.</p>
						<p>Qualora fra gli strumenti indicati in seguito fossero presenti servizi gestiti da terzi, questi potrebbero – in aggiunta a quanto specificato ed anche all’insaputa del Titolare – compiere attività di tracciamento dell’Utente.</p>
					
				</div>
				<div class="list-block accordion-list">
									  <ul>
										<li class="accordion-item bordoaccordion" id="accordion1" onclick="apriacc(1)">
										<a href="#" class="item-content item-link">
											<div class="item-inner">
											  <div class="item-title">Interazione con social network e piattaforme esterne</div>
											</div>
										</a>
										  <div class="accordion-item-content">
											<div class="content-block p5 c333">
												<p>Questi servizi permettono di effettuare interazioni con i social network, o con altre piattaforme esterne, direttamente dalle pagine di questa Applicazione.<br>Le interazioni e le informazioni acquisite da questa Applicazione sono in ogni caso soggette alle impostazioni privacy dell’Utente relative ad ogni social network.<br>Nel caso in cui sia installato un servizio di interazione con i social network, è possibile che, anche nel caso gli Utenti non utilizzino il servizio, lo stesso raccolga dati di traffico relativi alle pagine in cui è installato.</p>
											
												<p class="fs14 txtnero"><strong>Pulsante Mi Piace e widget sociali di Facebook (Facebook, Inc.)</strong></p>
												<p>Il pulsante “Mi Piace” e i widget sociali di Facebook sono servizi di interazione con il social network Facebook, forniti da Facebook, Inc.<br/>
												Dati personali raccolti: Cookie e Dati di utilizzo.<br/>
												Luogo del trattamento: USA</p>
											</div>
										  </div>
										</li>
										
										<li class="accordion-item bordoaccordion" id="accordion2" onclick="apriacc(2)">
										<a href="#" class="item-content item-link">
											<div class="item-inner">
											  <div class="item-title">Pubblicità</div>
											</div>
										</a>
										  <div class="accordion-item-content">
											<div class="content-block p5 c333">
											  <p>Questi servizi consentono di utilizzare i Dati dell’Utente per finalità di comunicazione commerciale in diverse forme pubblicitarie, quali il banner, anche in relazione agli interessi dell’Utente.
											  <br>Ciò non significa che tutti i Dati Personali vengano utilizzati per questa finalità. Dati e condizioni di utilizzo sono indicati di seguito.</p>
											<p class="fs14 txtnero"><strong>Google AdSense (Google Inc.)</strong></p>
											<p>Google AdSense è un servizio di advertising fornito da Google Inc. Questo servizio usa il Cookie “Doubleclick”, che traccia l’utilizzo di questa Applicazione ed il comportamento dell’Utente in relazione agli annunci pubblicitari, ai prodotti e ai servizi offerti.
											<br>
											Dati personali raccolti: Cookie e Dati di utilizzo.<br/>
											Luogo del trattamento: USA
											</p>
											<p class="fs14 txtnero"><strong>Widget Google Maps (Google Inc.)</strong></p>
											<p>E’ un servizio di visualizzazione di mappe gestito da Google Inc. che permette a questo sito di integrare tali contenuti all’interno delle proprie pagine.<br/>
											Dati personali raccolti: cookie e dati di utilizzo.<br/>
											</p>
											
											</div>
										  </div>
										</li>
				
					
									<li class="accordion-item bordoaccordion" id="accordion5" onclick="apriacc(5)">
										<a href="#" class="item-content item-link">
											<div class="item-inner">
											  <div class="item-title">Remarketing e Behavioral Targeting</div>
											</div>
										</a>
										  <div class="accordion-item-content">
											<div class="content-block p5 c333">
													<p>Questi servizi consentono a questa Applicazione ed ai suoi partner di comunicare, ottimizzare e servire annunci pubblicitari basati sull’utilizzo passato di questa Applicazione da parte dell’Utente.<br>Questa attività viene effettuata tramite il tracciamento dei Dati di Utilizzo e l’uso di Cookie, informazioni che vengono trasferite ai partner a cui l’attività di remarketing e behavioral targeting è collegata.</p>
											<p class="fs14 txtnero"><strong>AdWords Remarketing (Google Inc.)</strong></p>
											<p>AdWords Remarketing è un servizio di Remarketing e Behavioral Targeting fornito da Google Inc. che collega l’attività di questa Applicazione con il network di advertising Adwords ed il Cookie Doubleclick.<br/>
											Dati personali raccolti: Cookie e Dati di utilizzo.<br/>
											Luogo del trattamento: USA</p>  	
											</div>
										  </div>
										</li>
										<li class="accordion-item bordoaccordion" id="accordion6" onclick="apriacc(6)">
										<a href="#" class="item-content item-link">
											<div class="item-inner">
											 <div class="item-title">Statistica</div>
											</div>
										</a>
										  <div class="accordion-item-content">
											<div class="content-block p5 c333">
												  <p>I servizi contenuti nella presente sezione permettono al Titolare del Trattamento di monitorare e analizzare i dati di traffico e servono a tener traccia del comportamento dell’Utente.</p>
												<p class="fs14 txtnero"><strong>Google Analytics (Google Inc.)</strong></p>
												<p>Google Analytics è un servizio di analisi web fornito da Google Inc. (“Google”). Google utilizza i Dati Personali raccolti allo scopo di tracciare ed esaminare l’utilizzo di questa Applicazione, compilare report e condividerli con gli altri servizi sviluppati da Google.<br>Google potrebbe utilizzare i Dati Personali per contestualizzare e personalizzare gli annunci del proprio network pubblicitario.<br/>
												Dati personali raccolti: Cookie e Dati di utilizzo.<br/>
												Luogo del trattamento: USA</p>
												<p class="fs14 txtnero"><strong>Monitoraggio conversioni di Google AdWords (Google Inc.)</strong></p>
												<p>Il monitoraggio conversioni di Google AdWords è un servizio di statistiche fornito da Google Inc. che collega i dati provenienti dal network di annunci Google AdWords con le azioni compiute all’interno di questa Applicazione.<br/>
												Dati personali raccolti: Cookie e Dati di utilizzo.<br/>
												Luogo del trattamento: USA</p>
												<p class="fs14 txtnero"><strong>Woopra (Woopra)</strong></p>
												<p>Woopra è un servizio di statistica fornito da Woopra Inc.<br/>
												Dati personali raccolti: Cookie e Dati di utilizzo.<br/>
												Luogo del trattamento: USA</p>
											</div>
										  </div>
										</li>
										
										 <li class="accordion-item bordoaccordion" id="accordion7" onclick="apriacc(7)">
										 <a href="#" class="item-content item-link">
											<div class="item-inner">
											  <div class="item-title">Visualizzazione di contenuti da piattaforme esterne</div>
											</div>
										</a>
										  <div class="accordion-item-content">
											<div class="content-block p5 c333">
											   	 <p>Questi servizi permettono di visualizzare contenuti ospitati su piattaforme esterne direttamente dalle pagine di questa Applicazione e di interagire con essi.<br>Nel caso in cui sia installato un servizio di questo tipo, è possibile che, anche nel caso gli Utenti non utilizzino il servizio, lo stesso raccolga dati di traffico relativi alle pagine in cui è installato.</p>
												<p class="fs14 txtnero"><strong>Google Fonts (Google Inc.)</strong></p>
												<p>Google Fonts è un servizio di visualizzazione di stili di carattere gestito da Google Inc. che permette a questa Applicazione di integrare tali contenuti all’interno delle proprie pagine.<br/>
												Dati personali raccolti: Cookie e Dati di utilizzo.<br/>
												Luogo del trattamento: USA</p> 
											</div>
										  </div>
										</li>
										 <li class="accordion-item bordoaccordion" id="accordion8" onclick="apriacc(8)">
										 <a href="#" class="item-content item-link">
											<div class="item-inner">
											  <div class="item-title">Monitoraggio dell’infrastruttura</div>
											</div>
										</a>
										  <div class="accordion-item-content">
											<div class="content-block p5 c333">
											  	<p>Questi servizi permettono a questa Applicazione di monitorare l’utilizzo ed il comportamento di componenti della stessa, per consentirne il miglioramento delle prestazioni e delle funzionalità, la manutenzione o la risoluzione di problemi.<br>I Dati Personali trattati dipendono dalle caratteristiche e della modalità d’implementazione di questi servizi, che per loro natura filtrano l’attività di questa Applicazione.</p>
												<p class="fs14 txtnero"><strong>New Relic (New Relic)</strong></p>
												<p>New Relic è un servizio di monitoraggio fornito da New Relic Inc.<br>Le modalità di integrazione di New Relic prevedono che questo filtri tutto il traffico di questa Applicazione, ossia le comunicazioni fra questa Applicazione ed il browser o il device dell’Utente, permettendo anche la raccolta di dati statistici su di esso.<br/>
												Dati personali raccolti: Varie tipologie di Dati.<br/>
												Luogo del trattamento: USA</p>
												<p class="fs14 txtnero"><strong>Pingdom (Pingdom AB)</strong></p>
												<p>Pingdom è un servizio di monitoraggio fornito da Pingdom AB.<br/>
												Dati personali raccolti: Cookie e Dati di utilizzo.<br/>
												Luogo del trattamento: Svezia</p>	
											</div>
										  </div>
										</li>
									  </ul>
								  </div>
								
							<div class="privacyscheda">
									<p class="fs16 txtnero"><strong>Informazioni fornite da '.$nome.' ai sensi dell’art. 13 del Codice della Privacy</strong></p>
									
									<p class="fs14 txtnero"><strong>'.$nome.', in qualità di titolare del trattamento, precisa quanto segue:</strong></p>
									
									<p class="fs13">I dati sono raccolti solo per le finalità e per la durata indicate nelle tabelle che precedono e sono trattati con modalità informatiche.</p>
									<p class="fs13">Per quanto riguarda i cookie “tecnici”, come sopra indicati, si ribadisce l’utilizzo di tali cookie non richiede il consenso preventivo dell’utente.</p>
									<p class="fs13">I dati raccolti dai cookie di prima parte potranno essere comunicati a soggetti che agiscono per conto di '.$nome.' in qualità di responsabili o incaricati del trattamento, per finalità connesse a quelle sopra descritte.</p>
										
								   <p class="fs13">Infine, con riferimento ai cookie di terze parti, si ricorda che le finalità di tali cookie, le logiche sottese ai relativi trattamenti nonché la gestione delle preferenze dell’utente rispetto ai cookie medesimi non sono determinate e/o verificate da questo spazio online, ma dal soggetto terzo che li fornisce, in qualità di fornitore e titolare del trattamento, come indicato nella tabella sopra riportata.</p>

							</div>
				
				
				';
		
		
		
		
		
	break;	
		
}











$testo.='  					
								<div class="list-block">
  								<ul>
								 <li class="accordion-item" id="accordion9" onclick="apriacc(9)">
								 <a href="#" class="item-content item-link">
									<div class="item-inner">
									  <div class="item-title">Definizioni e riferimenti legali</div>
									</div>
								</a>
							<div class="accordion-item-content bordoaccordion">
								<div class="content-block p5 c333">
								 	<p class="fs14 txtnero"><strong>Dati Personali (o Dati)</strong></p>
										<p>Costituisce dato personale qualunque informazione relativa a persona fisica, identificata o identificabile, anche indirettamente, mediante riferimento a qualsiasi altra informazione, ivi compreso un numero di identificazione personale.</p>
										
										<p class="fs14 txtnero"><strong>Dati di Utilizzo</strong></p>
										
										<p>Sono le informazioni raccolti in maniera automatica di questa Applicazione (o dalle applicazioni di parti terze che questa Applicazione utilizza), tra i quali: gli indirizzi IP o i nomi a dominio dei computer utilizzati dall’Utente che si connette con questa Applicazione, gli indirizzi in notazione URI (Uniform Resource Identifier), l’orario della richiesta, il metodo utilizzato nel sottoporre la richiesta al server, la dimensione del file ottenuto in risposta, il codice numerico indicante lo stato della risposta dal server (buon fine, errore, ecc.) il Paese di provenienza, le caratteristiche del browser e del sistema operativo utilizzati dal visitatore, le varie connotazioni temporali della visita (ad esempio il tempo di permanenza su ciascuna pagina) e i dettagli relativi all’itinerario seguito all’interno dell’Applicazione, con particolare riferimento alla sequenza delle pagine consultate, ai parametri relativi al sistema operativo e all’ambiente informatico dell’Utente.</p>
										
										<p class="fs14 txtnero"><strong>Utente</strong></p>
										
										<p>L individuo che utilizza questa Applicazione, che deve coincidere con l’Interessato o essere da questo autorizzato ed i cui Dati Personali sono oggetto del trattamento.</p>
										
										<p class="fs14 txtnero"><strong>Interessato</strong></p>
										
										<p>La persona fisica o giuridica cui si riferiscono i Dati Personali.</p>
										
										<p class="fs14 txtnero"><strong>Responsabile del Trattamento (o Responsabile)</strong></p>
										
										<p>La persona fisica, giuridica, la pubblica amministrazione e qualsiasi altro ente, associazione od organismo preposti dal Titolare al trattamento dei Dati Personali, secondo quanto predisposto dalla presente privacy policy.</p>
										<p class="fs14 txtnero"><strong>Titolare del Trattamento (o Titolare)</strong></p>
										
										<p>La persona fisica, giuridica, la pubblica amministrazione e qualsiasi altro ente, associazione od organismo cui competono, anche unitamente ad altro titolare, le decisioni in ordine alle finalità, alle modalità del trattamento di dati personali ed agli strumenti utilizzati, ivi compreso il profilo della sicurezza, in relazione al funzionamento e alla fruizione di questa Applicazione. Il Titolare del Trattamento, salvo quanto diversamente specificato, è il proprietario di questa Applicazione.</p>
										
										<p class="fs14 txtnero"><strong>Questa Applicazione</strong></p>
										
										<p>Lo strumento hardware o software mediante il quale sono raccolti i Dati Personali degli Utenti. </p>
										
										<p class="fs14 txtnero"><strong>Cookie</strong></p>
										
										<p>Piccola porzione di dati conservata all’interno del dispositivo dell’Utente.</p>

										<p class="fs16 txtnero"><strong>Riferimenti legali</strong></p>
										
										<p>Avviso agli Utenti europei: la presente informativa privacy è redatta in adempimento degli obblighi previsti dall’Art. 10 della Direttiva n. 95/46/CE, nonché a quanto previsto dalla Direttiva 2002/58/CE, come aggiornata dalla Direttiva 2009/136/CE, in materia di Cookie.</p>
										<p>Questa informativa privacy riguarda esclusivamente questa Applicazione.</p>
								</div>
							  </div>
							</li>
                     </ul>
					</div>






</div></div></div></div>';



echo $testo;

?>
