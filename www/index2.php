<?php 
$rand=rand(0,1000);
?>

<!DOCTYPE html>
<html>
    <head>
   
        <!--<meta http-equiv="Content-Security-Policy" content="default-src 'self' data: gap: https://ssl.gstatic.com 'unsafe-eval'; style-src 'self' 'unsafe-inline'; media-src *; img-src 'self' data: content:;">
        <meta name="format-detection" content="telephone=no">
        <meta name="msapplication-tap-highlight" content="no">-->
        <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width">

        <link rel="stylesheet" type="text/css" href="css/index.css?r=<?php echo $rand; ?>">
        <link rel="stylesheet" href="css/framework7.ios.css">
        <link rel="stylesheet" href="css/framework7.ios.colors.min.css">
        <link rel="stylesheet" href="css/material-icons.css">
        <link rel="stylesheet" href="css/framework7-icons.css">
        <link rel="stylesheet" href="css/kitchen-sink.css?r=<?php echo $rand; ?>">
        <link rel="stylesheet" href="css/ionicons.css">
        <link rel="stylesheet" href="css/michelecss.css?r=<?php echo $rand; ?>">
        
        <title>Scidoo</title>

		<script type="text/javascript" src="jquery-3.2.1.min.js"></script>
		<script type="text/javascript" src="js/framework7.js"></script>
		<script type="text/javascript" src="js/fontawesome-all.js"></script>
		
		
    </head>
    <body>
<!--
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/it_IT/sdk.js#xfbml=1&version=v3.0';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
			-->	
			
    <div class="statusbar-overlay"></div>
     <div id="divsottoover" class="divsottoover" onclick="chiudimodal();"></div>

  	<div class="panel-overlay"  onClick="chiudimodal();" ></div>
		
					  
  <div class="panel panel-left  panel-cover">
    <div class="content-block" id="contentpanelsx">
      
    </div>
  </div>
		
    
    	<div class="views">
    	  <div class="view view-main">
    
    		<div class="pages navbar-fixed">
          <div data-page="indice" class="page bcw" >
    
       <!-- <div class="app mt-90" >
        </div>
        <div id="logged" style="opacity:1; margin-top:300px;"></div>-->
			  
			  
			  <div class="divimg" ><img src="scidoologor.jpg" class="width340" style="width:180px;" ></div>
        <div id="logindiv" class="indexopacity">
			
			<a href="#" onclick="navigation2(9,0,0,1)"  class="button button-big sendform registrati">PROVALO PER 30 GIORNI</a>
			
						
            <div class="list-block inputs-list indexdiv">
				
				
            <ul class="br5">
    
            <li class="item-content">
                <div class="item-inner"> 
                  <div class="item-title label c333 fs16 left" >Email</div>
                  <div class="item-input">
                    <input type="text" name="email" id="email" class="fs15" placeholder="Inserisci Email"/>
                  </div>
                </div>
              </li>
             <li class="item-content">
                <div class="item-inner"> 
                  <div class="item-title label c333 fs16 left " >Password</div>
                  <div class="item-input">
                    <input type="password" name="pass" id="pass" class="fs15"  placeholder="Inserisci Password" />
                  </div>
                </div>
              </li>
             
            </ul>
          </div><br><br>
            <a href="javascript:void(0)" onclick="sendform()" class="button button-big button-fill sendform accedi">ACCEDI</a><br/><hr/>
           
			<p class="fs12 c666">Scidoo e' la prima APP che ti permette di organizzare ed automatizzare la gestione della tua struttura ricettiva<br/><br/><strong>Let-s grow your hotel!</strong></p>
            
         </div>

		<input type="hidden" id="IDnotpush" value="0">
        
        
       </div></div> </div></div>
        
	
		 <script type="text/javascript" src="cordova.js"></script>
        <script type="text/javascript" src="js/index.js?r=<?php echo $rand; ?>"></script>
         <script type="text/javascript" src="js/kitchen-sink.js?r=<?php echo $rand; ?>"></script>
    	<script type="text/javascript" src="js/funzioniprev.js?r=<?php echo $rand; ?>"></script>
       <script type="text/javascript" src="js/michelejs.js?r=<?php echo $rand; ?>"></script>
        
    
        
        
    </body>
</html>
