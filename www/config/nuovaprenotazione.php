<?php 

header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');

$IDutente=$_SESSION['ID'];
$IDstruttura=$_SESSION['IDstruttura'];


$time=$_GET['time'];
$app=$_GET['app'];

$_SESSION['timenew']=$time;
$_SESSION['appnew']=$app;



$gg=1;

$arr=array('1','2','3','4','5','6');
	

$tabs='';




$testo='

<div class="pages" >
<div data-page="nuovapren" class="page" > 

<a href="#" onclick="addservprev()" id="buttonadd" class="floating-button color-pink"  style=" padding:0px; border-radius:5px; visibility:hidden; position:fixed;bottom:60px; right:0px  font-weight:bold;line-height:40px; font-size:25px; text-align:center;   height:50px; width:50px; transform:translateZ(0); webkit-transform:translateZ(0); z-index:999;">+</a>


<div class="navbar" >
  <div class="navbar-inner">
  	<table width="100%;"><tr>
<td width="20%" align="left"><a href="#" id="indietrobutt"  onclick="stepnew(-1,0)" class="button  indietro" style="display:none;" >Indietro</a></td>
<td align="center" id="titolodivmain" style="font-size:13px; text-transform:uppercase;">Nuova Prenotazione</td>
<td width="20%" align="right"><a href="#" class="button  avanti"   onclick="avanti2(0)" style="display:none;">Avanti</a></td>

</tr></table>
  
  

   	 <div class="tabbar" >
    <div class="toolbar-inner" style="width:100%; height:0px; position:relative; overflow:visible;">';
	
	
	
	//$testo.=' <a href="#step0" id="buttstep0"  disabled class="tab-link active  tabpren">0</a>';
	
	 $tabs.=' <div id="step0"  alt="0" class="tab active"  align="center;" >0</div>';
	
	
	
	$i=0;
		  foreach ($arr as $key =>$dato){
				$i++;
				//$testo.='<a href="#step'.$i.'" id="buttstep'.$i.'"  disabled class="tab-link  tabpren " >'.$i.'</a>';	
				
				$tabs.=' <div id="step'.$i.'" alt="'.$dato.'" class="tab" style="min-height:800px;"   >
					 
				 </div>';
				
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
	
	
	
<div class="bottombarpren">
	<table  style="width:100%;"><tr>
	<td>
	<a href="#" class="button color-white button-fill " onclick="chiudiprev()" style="height:40px; margin-top:0px;line-height:40px;color:#333; width:100px; font-size:12px;">ANNULLA</a>

	</td>
	
	<td style="text-align:right; font-size:30px; padding-right:20px;">
 <div id="totaleprev">0 €</div>
	
	</td>
	
	
	</tr></table>
	
  </div>



</div>
</div></div>

';
/*


	<td style="width:50px;">
	<a href="#" class="button color-green button-fill " onclick="addservprev(1)" id="buttonadd"  style="font-size:12px; height:40px;visibility:hidden;">
	<i class="material-icons" style="font-size:30px; margin-top:5px;">add_shopping_cart</i>
	</a>

	</td>

*/

//aventistep1()


echo $testo;
?>