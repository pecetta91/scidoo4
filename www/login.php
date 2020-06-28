<?php
//header('Access-Control-Allow-Origin: *');
require_once '../config/connecti.php';
require_once '../config/funzioni.php';

$testo = '
<div class="uk-section uk-section-muted uk-flex uk-flex-middle uk-animation-fade" uk-height-viewport>

 	<div><img data-src="' . base_url() . '/sito_scidoo/logo.png" width="150" height="40" alt="" uk-img style="    margin: 20px 0;
    position: absolute;   top: 25px;  right: 50%;   transform: translateX(50%);"></div>

	<div class="uk-width-1-1" style="    margin-top: -50px;">
		<div class="uk-container">
			<div class="uk-grid-margin uk-grid uk-grid-stack" uk-grid>
				<div class="uk-width-1-1@m">
					<div class="uk-margin uk-width-large uk-margin-auto uk-card uk-card-default uk-card-body uk-box-shadow-large">
						<h3 class="uk-card-title uk-text-center">Bentornato!!</h3>
							<div class="uk-margin">
								<div class="uk-inline uk-width-1-1">
									<span class="uk-form-icon" uk-icon="icon: mail"></span>
									<input class="uk-input uk-form-large" type="text" placeholder="Email" id="email">
								</div>
							</div>
							<div class="uk-margin">
								<div class="uk-inline uk-width-1-1">
									<span class="uk-form-icon" uk-icon="icon: lock"></span>
									<input class="uk-input uk-form-large" type="password" id="pass" placeholder="Password">
								</div>
							</div>


							<div class="uk-margin">
								<button class="uk-button uk-button-primary uk-button-large uk-width-1-1" onclick="accedi_str()">Login</button>
							</div>

							<div class="uk-margin">
								<hr>
								<div class="uk-inline uk-width-1-1"><a href="' . base_url() . '/app_uikit/registrati.php">Premi qui per richiedere una demo</a></div>
							</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>';

echo $testo;

?>
