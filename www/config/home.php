<?php 


header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');


echo 'aa';
return false;


$testo=  '<div data-page="profilo" class="page"> 			
	          <div class="page-content" style="padding:0px; padding-top:18px;"> 
				
				
				<div class="list-block inputs-list" style="width:95%; margin:auto;">
        <ul style="background:#fff; border-radius:5px; ">

		<li class="item-content">
            <div class="item-inner"> 
              <div class="item-title label" style="color:#333; text-transform:none;">E-mail</div>
              <div class="item-input">
                <input type="text" name="email" id="email" style="font-size:15px;" placeholder="info@scidoo.com"/>
              </div>
            </div>
          </li>
         <li class="item-content">
            <div class="item-inner"> 
              <div class="item-title label" style="color:#333; text-transform:none;">Password</div>
              <div class="item-input">
                <input type="password" name="pass" id="pass" style="font-size:15px;" placeholder="" />
              </div>
            </div>
          </li>
         
        </ul>
      </div><br>
      	<a href="javascript:void(0)" onclick="sendform()" class="button button-big button-fill sendform" style=" width:60%; margin:auto; background:#ff9c00;;">Accedi</a>
       <br>
	   
        <a href="#" onclick="location.href='."'https://www.scidoo.com/registrati.php'".';"  class="button button-big button-fill sendform" style=" width:60%; margin:auto; background:#266FAB;;">Registrati</a>
      
       		
		
			  
			  </div>
		</div>
				';
			
			
		
	
	echo $testo;
	
?>