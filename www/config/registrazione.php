<?php
	header('Access-Control-Allow-Origin: *');
	include('../../config/connecti.php');
	include('../../config/funzioni.php');


/*<div class="left" onclick="indietroindex();">
						<i class="material-icons fs40">chevron_left</i>
					</div>*/
$testo='
<div data-page="registrazione" class="page" > 
		 	<div class="navbar" id="navcal" >
				<div class="navbar-inner">
					<div class="left navbarleftsize170">
					
					 <a href="#" class="link icon-only" onclick="indietroindex();" >
						<i class="material-icons">chevron_left</i>
							<strong class="stiletitolopagine">HOME SCIDOO</strong>
					</a>
					
			
					
					
					</div>
				
				
					
					<div class="center"></div>
					<div class="right"></div>
				</div>
			</div>
			
			 <div class="page-content">
			<div class="content-block">
		
		  	<div class="content-block-title titleb">Registrati e Provalo Subito!</div>
		   <div class="list-block">
				<ul>
						<li>
						  <div class="item-content">
							<div class="item-inner">
							  <div class="item-title label">E-mail</div>
							  <div class="item-input" >
								<input type="email" id="email"  placeholder="E-mail" >
							  </div>
							</div>
						  </div>
						</li>
						<li>
						  <div class="item-content">
							<div class="item-inner">
							  <div class="item-title label">Password</div>
							  <div class="item-input" >
								<input type="password" id="pass"  placeholder="Password" >
							  </div>
							</div>
						  </div>
						</li>
					</ul>
				</div>
				<div class="list-block">
					<ul>
						<li>
						  <div class="item-content">
							<div class="item-inner">
							  <div class="item-title label">Nome</div>
							  <div class="item-input" >
								<input type="text" id="nome"  placeholder="Nome" >
							  </div>
							</div>
						  </div>
						</li>
						 <li>
						  <div class="item-content">
							<div class="item-inner">
							  <div class="item-title label">Nome Struttura</div>
							  <div class="item-input" >
								<input type="text"  id="nomestr" placeholder="Nome Struttura">
							  </div>
							</div>
						  </div>
						</li>
						<li>
						  <div class="item-content">
							<div class="item-inner">
							  <div class="item-title label">Telefeno</div>
								  <div>
									  <a href="#" class="item-link smart-select" data-open-in="picker" pickerHeight="400px" >
										<select class="preftelpicker" id="prefisso">'.generaprefisso(39).'</select>
									 </a>
								 </div>
							  <div class="item-input">
								<input type="tel" id="tel"  placeholder="Telefono" >
							  </div>
							</div>
						  </div>
						</li>
						</ul>
					</div>
					<div class="list-block">
						<ul>
						 <li>
							<a href="#" class="item-link smart-select" data-open-in="picker">
								<select id="tipo">
                                    '.generatipostr(0).'
									</select>
								<div class="item-content">
								  <div class="item-inner">
									<div class="item-title">Tipologia Struttura</div>
									<div class="item-after" >Tipo di attività</div>
								  </div>
								</div>
							  </a>
						</li>
						
						 <li>
							<a href="#" class="item-link smart-select" data-open-in="picker">
								<select id="numc">
                                    '.generanum(0,30).'
									</select>
								<div class="item-content">
								  <div class="item-inner">
									<div class="item-title">Numero Stanze</div>
									<div class="item-after"  >Numero Stanze</div>
								  </div>
								</div>
							  </a>
						</li>

						 <li>
						  <div class="item-content">
							<div class="item-inner">
							  <div class="item-title label">Prezzo Medio<br/><span>In € a Notte</span></div>
							  <div class="item-input" >
								<input type="number" id="prezzom"  placeholder="Prezzo medio ">
							  </div>
							</div>
						  </div>
						</li>
				</ul> 
			</div>  
			
			';
/*


						
						
						<li>
						  <div class="item-content">
							<div class="item-inner">
							  <div class="item-title label">Numero Camere</div>
							  <div class="item-input" >
								<input type="number" id="numc"  placeholder="Numero camere" >
							  </div>
							</div>
						  </div>
						</li>

			
				<div class="row rowlist no-gutter checkl" onclick="selezionacheck(0)" id="check0" name="pulg">
						<div class="col-15">
							<div style="color:#a1a1a1;font-size:22px">
							 <i class="f7-icons" style="color:blue">circle</i>
							</div>
						</div>
					<div class="col-80" style="margin-top:5px"><span style="font-size:15px">Pulizia Giornaliera</span></div>	
			   </div>
			   <div class="row rowlist no-gutter checkl" onclick="selezionacheck(1)" id="check1" name="pulf" >
						<div class="col-15">
							<div style="color:#a1a1a1;font-size:22px">
							 <i class="f7-icons" style="color:blue">circle</i>
							</div>
						</div>
					<div class="col-80" style="margin-top:5px"><span style="font-size:15px">Pulizia Finale</span></div>	
			   </div>
			   <div class="row rowlist no-gutter checkl" onclick="selezionacheck(2)"  id="check2" name="primacol">
						<div class="col-15">
							<div style="color:#a1a1a1;font-size:22px">
							 <i class="f7-icons" style="color:blue">circle</i>
							</div>
						</div>
					<div class="col-80" style="margin-top:5px"><span style="font-size:15px">Prima Colazione</span></div>	
			   </div>
			   <div class="row rowlist no-gutter checkl" onclick="selezionacheck(3)" id="check3" name="mezzap" >
						<div class="col-15">
							<div style="color:#a1a1a1;font-size:22px">
							 <i class="f7-icons" style="color:blue">circle</i>
							</div>
						</div>
					<div class="col-80" style="margin-top:5px"><span style="font-size:15px">Mezza Pensione</span></div>	
			   </div>
			   <div class="row rowlist no-gutter checkl" onclick="selezionacheck(4)" id="check4" name="pencl" >
						<div class="col-15">
							<div style="color:#a1a1a1;font-size:22px">
							 <i class="f7-icons" style="color:blue">circle</i>
							</div>
						</div>
					<div class="col-80" style="margin-top:5px"><span style="font-size:15px">Pensione Completa</span></div>	
			   </div>
			   
		  */
		  
		  $testo.='<br>
		  
            <a href="javascript:void(0)" onclick="datiregistrati()" class="button button-big button-fill sendform accedi" style="font-size: 18px !important;" >Registrati</a><br/><hr class="mt20">
           
			<p class="fs13 c666 mt15 center padding:10px;" >
			<b>Le informazioni che verranno inserite per impostare la DEMO potranno essere personalizzate e modificate in seguito.</b><br/><br/>
			<u>La registrazione è GRATUITA e non implica nessun obbligo di acquisto.</u><br/><br/>
			Un nostro tecnico vi ricontatterà nei prossimi giorni, senza nessun costo, per una consulenza gratuita e per aiutarvi ad utilizzare e configurare al meglio la piattaforma.<br/><br/><strong>Buona Gestione</strong>
			
			
			
			
			</strong></p>
            
        
     
     
        
       </div></div> </div>';

echo $testo;
?>