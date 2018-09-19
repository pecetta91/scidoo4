<?php 
header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');

$IDutente=$_SESSION['ID'];
$IDstruttura=$_SESSION['IDstruttura'];
unset($_SESSION['ponline']);

$time=$_GET['time'];
$app=$_GET['app'];
$_SESSION['appmobile']=1;
if($time==0){
	$_SESSION['timenew']=time();	
}else{
	$_SESSION['timenew']=$time;
}

$_SESSION['app']=$app;


$arr=array('1','2','3','4','5','6');
	
$tabs='';

$testo='

<div class="pages" >
<div data-page="nuovapren" class="page" > 




<div class="navbar" >
  <div class="navbar-inner">
  	
	<div class="left"  onclick="stepnew(-1,0)"><i class="icon f7-icons" id="indietro" style="display:none;">arrow-left</i></div>
	<div class="center"> <strong class="stiletitolopagine" id="titolodivmain">Nuova Prenotazione</strong> </div>
	<div class="right"  onclick="chiudiprev()"><i class="icon f7-icons">close</i></div>
	

   	 <div class="tabbar"  style="display:none;">
    <div class="toolbar-inner" style="width:100%; height:0px; position:relative; overflow:visible;">';
	
	
	
	//$testo.=' <a href="#step0" id="buttstep0"  disabled class="tab-link active  tabpren">0</a>';
	//style="min-height:800px;"
	 $tabs.=' <div id="step0"  alt="0" class="tab active">0</div>';
	
	
	
	$i=0;
		  foreach ($arr as $key =>$dato){
				$i++;
				//$testo.='<a href="#step'.$i.'" id="buttstep'.$i.'"  disabled class="tab-link  tabpren " >'.$i.'</a>';	
				// style="min-height:800px;"
				$tabs.=' <div id="step'.$i.'" alt="'.$dato.'" class="tab"></div>';
				
			}
	
	
	
	$testo.='</div></div>
	
	
	
	
	
  </div>
</div>

    <div class="page-content" >

	  <div id="tabmain4" >
	  
      <div class="tabs-animated-wrap" >
        <div class="tabs"  >
 			'.$tabs.'
	        </div>
        
      </div> 
    </div>
</div>




<div class="bottombarpren" style="background:#f1f1f1;z-index:999;" align="center">


<table style="width:100%;" cellpadding="0" cellspacing="0"><tr><td style="width:15%">
</td><td>

	<button class="bottoneprezzo" onclick="avanti2(0)"><span id="avantitxt">Avanti</span> (<span id="totaleprev">0 €</span>)</button>
</td>
<td style="width:15%">

	<a href="#" onclick="addservprev()" id="buttonadd" class="button color-pink"  style=" border-radius:5px;  font-weight:bold;line-height:40px;  text-align:center;   height:50px; width:50px; line-height:50px; border-color:#ccc; color:#333;">
	<i class="material-icons" style="margin-top:10px;font-size:28px;">add_shopping_cart</i>
	
	</a>


</td></tr></table>

</div>




</div></div>

';



echo $testo;
?>