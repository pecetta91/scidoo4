<?php 
header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');
include('../../config/funzionilingua.php');
$testo='';



$mese=$_GET['mese'];
$mesep=$mese;


$time=time();
//$mm=date('m',$time);
//$aa=date('Y',$time);
//$meseatt=mktime(1,30,0,$mm,01,$aa);

$rigahead='';
if($meseatt!=$mese){
	$rigahead='<a href="#" onclick="navigation(2,'.$time.',0,1);offsetleftcalendario=0">Vai ad Oggi</a>';
}

$testo.='<div>';

for($i=-5;$i<6;$i++){
	
	$mesep2=$mesep+$i*32*86400;
	
	$mm=date('m',$mesep2);
	$aa=date('Y',$mesep2);
	$numeromese=convert($mm);
			
				$testo.='<div class="row rowlist no-gutter h30" style="font-size:16px;" onclick="navigation(2,'.$mesep2.',0,1);offsetleftcalendario=0">
						 <div class="col-10  "></div>
						 <div class="col-60 campiricerca"><strong>'.$mesiita[$numeromese].'</strong></div>
						 <div class="col-30 campiricerca">'.$aa.'</div>';
										
				$testo.='</div>';
	
	//$mesep=$mesep+32*86400;

}		
	
	$testo.='</div>';
	

		
?>
<div class="picker-modal " id="popoverord" style="height:400px;">
		  <div class="toolbar">
			<div class="toolbar-inner">
		  	  <div class="left"><?php echo $rigahead;?></div>
			  <div class="right"><a href="#" class="close-picker">Chiudi</a></div>
			</div>
		  </div>
		  <div class="picker-modal-inner ">
		 <div class="page-content bcw" > 
		 	<?php echo $testo;?>
			  </div>
		  </div>
</div>
