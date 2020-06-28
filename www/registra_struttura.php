<?php
//header('Access-Control-Allow-Origin: *');
require_once '../config/connecti.php';
require_once '../config/funzioni.php';

$tipologia = [];
$query = "SELECT ID,tipologia FROM tipostr ORDER BY tipo ";
$result = mysqli_query($link2, $query);
while ($row = mysqli_fetch_row($result)) {
	$tipologia[$row[0]] = $row['1'];
}

echo '
	<div id="tipologia_struttura" style="display:none;">
		<ul class="uk-list uk-list-divider uk-picker-bot " style="padding:5px 20px;"  onchange="(r,s)=>{cambia_valore_html_select(\'tipo_struttura\',r,s);}">
			' . genera_select_uikit($tipologia, 1) . '
		</ul>
	</div>
	<div id="numero_appartamenti" style="display:none;">
		<ul class="uk-list uk-list-divider uk-picker-bot " style="padding:5px 20px;"  onchange="(r,s)=>{cambia_valore_html_select(\'appartamenti\',r,s);}">
			' . genera_select_uikit(genera_numeri_array(1, 20), 1) . '
		</ul>
	</div>
	<div id="prefisso_select" style="display:none;">
		<ul class="uk-list uk-list-divider uk-picker-bot " style="padding:5px 20px;"  onchange="(r,s)=>{cambia_valore_html_select(\'prefisso\',r,s);}">
			' . genera_select_uikit(generaprefisso_uikit(), 39) . '
		</ul>
	</div>


	';

?>
<html>
    <head>
        <title>SCIDOO</title>

        <meta name="apple-mobile-web-app-title" content="SCIDOO">
        <meta name="description" content="Applicazione Concierge - SCIDOO ">

        <meta charset="utf-8">
        <meta name="viewport" content="target-densityDpi=device-dpi, width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no , viewport-fit=cover" />

        <link rel="icon" href="<?php echo base_url() . '/favicon.png'; ?>">



        <link rel="stylesheet" href="<?php echo base_url() . '/app_uikit/css/uikit.min.css'; ?>" />

        <link rel="stylesheet" href="<?php echo base_url() . '/app_uikit/css/main.css?rand=' . $rand; ?>" />


        <script  src="https://code.jquery.com/jquery-3.4.1.js"  integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="  crossorigin="anonymous"></script>

         <link rel="stylesheet" href="<?php echo base_url() . '/preventivo_picker/css/preventivo_picker.css?r=' . $rand; ?> " />




         <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">


         <link href="https://fonts.googleapis.com/css?family=Varela+Round&display=swap" rel="stylesheet">

        <script src="<?php echo base_url() . '/config/dropzone/dropzone.js'; ?>"></script>
        <script src="<?php echo base_url() . '/app_uikit/js/uikit.js?r=' . $rand; ?>"></script>
        <script src="<?php echo base_url() . '/app_uikit/js/uikit-icons.min.js'; ?>"></script>
        <script src="<?php echo base_url() . '/app_uikit/js/main.js?r=' . $rand; ?>"></script>

    </head>
    <body >


        <span uk-spinner="ratio:4.5" id="loader" style="display: none;position:fixed;  top: 50%;  left: 50%;z-index: 99;translate(-50%,-50%);
        color:#a2a2a2; border-radius: 10px;transform: translate(-50%,-50%);"></span>


        <div id="overlay_ricerca_preventivo" style="height:100%; position: fixed;  top: 0;
         bottom: 0; left: 0;    right: 0;  background: rgba(0,0,0,.5);  z-index: 1001;  transition: opacity .15s linear;display: none;">
        	<span uk-spinner="ratio:4.5"   style="position:fixed;  top: 50%;  left: 50%;z-index: 1002;color:#fff; border-radius: 10px;transform: translate(-50%,-50%);"></span>
    	</div>



		<div class="uk-section uk-section-muted uk-flex uk-flex-middle uk-animation-fade" uk-height-viewport>



			<div class="uk-width-1-1" style="margin-top: 20px;">
				<div class="uk-container">
					<div class="uk-grid-margin uk-grid uk-grid-stack" uk-grid>
						<div class="uk-width-1-1@m">


							<div class="uk-margin  uk-margin-auto uk-card uk-card-default uk-card-body uk-box-shadow-large">
								<div><img data-src="<?php echo base_url() . '/sito_scidoo/logo.png'; ?>" width="150" height="40" alt="" uk-img style="
		    position: absolute;   top: 15px;  right: 50%;   transform: translateX(50%);"></div>
								<h3 class="uk-card-title uk-text-center">Registrati  </h3>

									<div class="uk-grid-small uk-child-width-expand@s" uk-grid>
									    <div>
									    	<label class="uk-form-label" for="form-stacked-text">Email</label>
							    			<div class="uk-inline  uk-width-1-1">
												<span class="uk-form-icon" uk-icon="icon: mail"></span>
												<input class="uk-input uk-form-large" type="text" placeholder="Email *" id="email">
											</div>
									    </div>
								        <div>
								        	<label class="uk-form-label" for="form-stacked-text">Password</label>
							         		<div class="uk-inline   uk-width-1-1">
												<span class="uk-form-icon" uk-icon="icon: lock"></span>
												<input class="uk-input uk-form-large" type="password" placeholder="Password *" id="password">
											</div>
									    </div>

								   </div>

									<div class="uk-grid-small uk-child-width-expand@s" uk-grid>


								        <div>
								        	<label class="uk-form-label" for="form-stacked-text">Nome</label>
							         		<div class="uk-inline   uk-width-1-1">
												<span class="uk-form-icon" uk-icon="icon: user"></span>
												<input class="uk-input uk-form-large" type="text" placeholder="Nome *" id="nome">
											</div>
									    </div>


									    <div>
									    	<label class="uk-form-label" for="form-stacked-text">Nome Struttura</label>
							    			<div class="uk-inline  uk-width-1-1">
												<span class="uk-form-icon" uk-icon="icon: home"></span>
												<input class="uk-input uk-form-large" type="text" placeholder="Nome Struttura *" id="nome_struttura">
											</div>
									    </div>

									</div>

									<div class="uk-grid-small uk-child-width-expand@s" uk-grid>
									    <div>
									    		<label class="uk-form-label" for="form-stacked-text">Alloggi (Limitati a 20 per demo)</label>
							         		<div class="uk-inline   uk-width-1-1">
												<span class="uk-form-icon" uk-icon="icon: home"></span>
											<div class="uk-input uk-form-large" type="text" readonly="" id="appartamenti" data-select="1" onclick="carica_content_picker($('#numero_appartamenti'))">1</div>
											</div>
									    </div>
									    <div>
									    	<label class="uk-form-label" for="form-stacked-text">Tipologia Struttura</label>
									    	<div class="uk-inline   uk-width-1-1">
												<span class="uk-form-icon" uk-icon="icon: world"></span>
												<div class="uk-input uk-form-large" type="text" readonly="" id="tipo_struttura" data-select="1" onclick="carica_content_picker($('#tipologia_struttura'))">Agriturismo</div>
											</div>

									    </div>
									</div>
									<div class="uk-grid-small uk-child-width-expand@s" uk-grid>
									    <div>
								    		<label class="uk-form-label" for="form-stacked-text">Prezzo medio a Notte </label>
							         		<div class="uk-inline   uk-width-1-1">
												<span class="uk-form-icon" uk-icon="icon: calendar"></span>
												<input class="uk-input uk-form-large" type="number" placeholder="Prezzo medio a Notte ( € ) *" id="prezzo">
											</div>
									    </div>
						    		    <div>
									    	<label class="uk-form-label" for="form-stacked-text">Telefono</label>
							         		<div class="uk-inline   uk-width-1-1">
												<span class="uk-form-icon" uk-icon="icon: phone"></span>
												<input class="uk-input uk-form-large" type="text" placeholder="Telefono *" id="telefono">
											</div>
									    </div>
									</div>
									<div class="uk-grid-small uk-child-width-expand@s" uk-grid>
										    <div>
							    		    	<label class="uk-form-label" for="form-stacked-text">Prefisso</label>
										    	<div class="uk-inline   uk-width-1-1">
													<span class="uk-form-icon" uk-icon="icon: receiver"></span>
													<div class="uk-input uk-form-large" type="text" readonly="" id="prefisso" data-select="39" onclick="carica_content_picker($('#prefisso_select'))">+39</div>
												</div>
										    </div>
						    		    <div>  </div>
									</div>



									<div class="uk-grid-small uk-child-width-expand@s " uk-grid>

									    <div><label>
									    	<input class="apple-switch" id="privacy_policy" type="checkbox"  > * Informativa sulla privacy , <a href="<?php echo base_url() . '/sito_scidoo/privacy2.php'; ?>" target="_blank"> Link Privacy Policy</a></label>
							 		  </div>

									</div>




									<div class="uk-margin">
										<button class="uk-button uk-button-primary uk-button-large uk-width-1-1" onclick="registra_nuova_struttura()">Registrati</button>
									</div>




							</div>


						</div>
					</div>
				</div>
			</div>
		</div>


    </body>
</html>