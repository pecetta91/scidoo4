<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../config/connecti.php';
require_once __DIR__ . '/../../config/funzioni.php';

$IDprenotazione = $_SESSION['IDstrpren'];

echo '<input type="hidden" value="' . $IDprenotazione . '" id="idpren"/>


		<div id="menu_dinamic"></div>

		<div class="uk-section">

		<div style="position: fixed; top:50px; bottom: 0;  box-sizing: border-box;  width: 240px !important; padding: 40px 40px 60px 40px; border-right: 1px #e5e5e5 solid;  overflow: auto;" class="scidoo_visibile_medium">';
include __DIR__ . '/menu.php';

echo ' </div>

		<div class="uk-container uk-position-relative uk-container-small container_ospite" id="container">';

include __DIR__ . '/prenotazione.php';

echo '</div>


<div style="position: fixed; top:80px; bottom: 0;right:0px;  box-sizing: border-box;  width: 240px !important; padding: 40px 40px 60px 40px;   overflow: auto;" class="scidoo_visibile_medium">


</div>

</div>';

/*
echo '
<input type="hidden" value="' . $IDstrpren . '" id="idpren"/>

<div id="offcanvas" uk-offcanvas="mode: push; overlay: true" class="uk-offcanvas">
<div class="uk-offcanvas-bar">
<div class="uk-panel">';
include 'menu.php';

echo '</div>
</div>
</div>

<div uk-sticky="sel-target: .uk-navbar-container; cls-active: uk-navbar-sticky" >
<nav class="uk-navbar-container" uk-navbar style="background:#f9f8f9">
<div class="uk-navbar-left">
<a href="../" class="uk-navbar-item uk-logo" style="color:#fff">
<img data-src="mobilescidoo.png" width="30" height="30" alt="" uk-img>  Scidoo
</a>
</div>

<div class="uk-navbar-center">
<button class="uk-button uk-button-default " onclick="goBack()" id="navigation_back"> <span uk-icon="chevron-left"></span> Indietro</button>
</div>

<div class="uk-navbar-right">
<ul class="uk-navbar-nav uk-visible@m">
<li><a   style="color:#fff">Pro</a></li>
<li><a  >Documentation</a></li>
<li><a  style="color:#fff">Changelog</a></li>
</ul>
<a uk-navbar-toggle-icon="" href="#offcanvas" uk-toggle=""
class="uk-navbar-toggle uk-hidden@m uk-icon uk-navbar-toggle-icon">
<svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" data-svg="navbar-toggle-icon" style="color: #fff">
<rect y="9" width="20" height="2"></rect>
<rect y="3" width="20" height="2"></rect>
<rect y="15" width="20" height="2"></rect></svg>
</a>
</div>
</nav>
</div>

<div class="uk-section">

<div style="position: fixed; top:50px; bottom: 0;  box-sizing: border-box;  width: 240px !important; padding: 40px 40px 60px 40px; border-right: 1px #e5e5e5 solid;  overflow: auto;" class="uk-visible@m">
<h3>Titolo</h3>';
include 'menu.php';

echo ' </div>

<div class="uk-container uk-position-relative uk-container-small" id="container">';

include 'prenotazione.php';

echo '</div>

<div style="position: fixed; top:80px; bottom: 0;right:0px;  box-sizing: border-box;  width: 240px !important; padding: 40px 40px 60px 40px;   overflow: auto;" class="uk-visible@m">
<h3>Titolo</h3>
<ul class="uk-nav uk-nav-default tm-nav">
<li class="uk-nav-header">Getting started</li>
<li class=""><a onclick="esci()">Esci</a></li>
<li class=""><a href="/docs/installation">Installation</a></li>
<li class=""><a href="/docs/less">Less</a></li>
<li class=""><a href="/docs/sass">Sass</a></li>
</ul>
</div>
</div>';
 */
