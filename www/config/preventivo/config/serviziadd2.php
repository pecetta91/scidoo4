<?php 
header('Access-Control-Allow-Origin: *');
include('../../../../config/connecti.php');
include('../../../../config/funzioni.php');

$IDutente=$_SESSION['ID'];
$IDstruttura=$_SESSION['IDstruttura'];


$testo='
<div class="pages navbar-fixed">
<div data-page="addservice" class="page" > 

            <div class="navbar">
               <div class="navbar-inner">
                  <div class="left" style="width:70px;">
				 	 <a href="#add1" class="tab-link tabindietro" style="display:none;" onclick="backselect()"><i class="icon f7-icons" style="color:#fff; margin-left:20px; width:60px;  font-size:25px;">chevron_left</i></a>
				  </div> 
                  <div class="center titolonav" style="line-height:14px;">Aggiungi un Servizio</div>
                  <div class="right" style="width:40px; text-align:center;">
						<a href="#" class="back"><i class="icon f7-icons" >close</i></a>				  
				  </div>
               </div>
            </div>
			<div class="page-content" > 
			
			<div class="content-block-title" style="color:#2d4e99;"><b>Ricerca Servizio</b></div>
			<div class="list-block">
			  <ul>
				<li>
				  <a href="#" id="autocomplete-standalone-ajax" class="item-link item-content autocomplete-opener">
					<input type="hidden">
					<div class="item-inner">
					  <div class="item-title">Language</div>
					  <div class="item-after"></div>
					</div>
				  </a>
				</li>
			  </ul>
			</div>
			
			
			
			<div class="content-block-title" style="color:#2d4e99;"><b>Dettaglio Servizio</b></div>
			<div class="list-block " >
				<ul>
					<li>
					  <a href="#" class="item-link  smart-select" data-searchbar="false">
						<select  onchange="modprenot('.$id.',this.value,31,10,0)">'.generaorario(date('H:i',$time),1,24,60).'</select>
						<div class="item-content">
						  <div class="item-inner">
							<div class="item-title">Data</div>
							<div class="item-after">'.date('H:i',$time).'</div>
						  </div>
						</div>
					  </a>
					</li>
					
					<li>
					  <a href="#" class="item-link  smart-select"  data-searchbar="false">
						<select  onchange="modprenot('.$id.',this.value,31,10,0)">'.generaorario(date('H:i',$time),1,24,60).'</select>
						<div class="item-content">
						  <div class="item-inner">
							<div class="item-title">Orario</div>
							<div class="item-after">'.date('H:i',$time).'</div>
						  </div>
						</div>
					  </a>
					</li>
					</ul></div>
			
			<div class="content-block-title" style="color:#2d4e99;"><b>Persone</b></div>
			<div class="list-block " >
				<ul>
					<li>
					  <a href="#" class="item-link  smart-select" data-searchbar="false">
						<select  onchange="modprenot('.$id.',this.value,31,10,0)">'.generaorario(date('H:i',$time),1,24,60).'</select>
						<div class="item-content">
						  <div class="item-inner">
							<div class="item-title">Persone</div>
							<div class="item-after">'.date('H:i',$time).'</div>
						  </div>
						</div>
					  </a>
					</li>
					
			</ul></div>
			
			<a href="#" class="button button-fill  " onclick="msgboxelimina('.$id.',1,0,2)" style="width:80%; font-size:16px;  height:45px; margin:auto; line-height:40px;">AGGIUNGI SERVIZIO</a>
	
			
			
			

</div>


</div>
</div></div>


';
		


echo $testo;
?>