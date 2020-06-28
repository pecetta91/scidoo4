<?php
header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../config/connecti.php';
require_once __DIR__ . '/../../config/funzioni.php';
var_dump($link2);
var_dump(session_save_path());
$IDutente = $_SESSION['ID'];
$IDstruttura = $_SESSION['IDstruttura'];
$dati_struttura = get_dati_struttura($IDstruttura);
//	<img data-src="https://www.scidoo.com/sito_scidoo/logo.png" width="125" alt="" uk-img>
echo '<input type="hidden" value="' . $IDstruttura . '" id="IDstr"/>

			<div id="offcanvas" uk-offcanvas="mode: push; overlay: true" class="uk-offcanvas  menu_dinamic">
    			<div class="uk-offcanvas-bar">
    				<div class="uk-panel">' . (include "menu.php") . '</div>
				</div>
			</div>

  			<div style="position: fixed; top:50px; bottom: 0;  box-sizing: border-box;  width: 240px !important; padding: 40px 40px 60px 40px; border-right: 1px #e5e5e5 solid;  overflow: auto;" class="uk-visible@m">
			 ' . (include "menu.php") . '
			 </div>


	<div id="menu_dinamic">



			<div uk-sticky="sel-target: .uk-navbar-container; cls-active: uk-navbar-sticky" class="menu_dinamic">
				  <nav class="uk-navbar-container sfondo_navbar" uk-navbar  >
    				<div class="uk-navbar-left uk-navbar-item">
						<div class="testo_sinistra">' . $dati_struttura['nome_struttura'] . '</div>
                	</div>

                	<div class="uk-navbar-center">
                		 <button class="uk-button uk-button-default " onclick="goBack()" id="navigation_back"> <span uk-icon="chevron-left"></span> Indietro</button>
                	</div>

                     <div class="uk-navbar-right">

	         				<a  href="#offcanvas" uk-toggle="" class="uk-navbar-toggle uk-hidden@m ">
	         				<i class="fas fa-bars" style="color: #2542d9"></i>

	 						</a>
             		 </div>
 				 </nav>
				</div>

		</div>


		<div class="uk-section">
			<div class="uk-container uk-position-relative uk-container-small container_no_mobile" id="container">';
include 'dashboard.php';
echo '</div>



	    </div>';

?>
