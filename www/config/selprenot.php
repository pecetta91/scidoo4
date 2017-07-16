<?php 
header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');

$IDutente=$_SESSION['ID'];
$IDstruttura=$_SESSION['IDstruttura'];
unset($_SESSION['arrservadd']);

$time=$_GET['time'];
$IDsotto=$_GET['IDsotto'];


$_SESSION['listIDsotto']=$IDsotto;

$data=date('Y-m-d',$time);
list($yy, $mm, $dd) = explode("-", $data);
$time0=mktime(0, 0, 0, $mm, $dd, $yy);
$_SESSION['listtime']=$time0;

$testo='

<div class="pages navbar-fixed">
  <div  class="page">

            <div class="navbar">
               <div class="navbar-inner">
                  
                  <div class="center titolonav">Nuovo Servizio</div>
                  <div class="right" onclick="mainView.router.back();">
						<a href="#" ><i class="icon f7-icons" >close</i></a>				  
				  </div>
               </div>
            </div>
						
			 <form data-search-list=".search-here" data-search-in=".item-title" class="searchbar searchbar-init">
      <div class="searchbar-input">
        <input type="search" placeholder="Cerca prenotazione" onkeyup="cercaprenot(this.value)"/><a href="#" class="searchbar-clear"></a>
      </div><a href="#" class="searchbar-cancel">Cancel</a>
    </form>
	
	<div class="page-content" >
	<br>
	
			<div class="list-block">
			  <ul id="listaprenot">
';


$checkout=($time0+86400);
$groupid=getprenotazioni($time0,$checkout,$IDstruttura,1,0);
					


$query="SELECT IDv,ID FROM prenotazioni WHERE IDv IN($groupid) AND stato>'0'";
$result=mysqli_query($link2,$query);
if(mysqli_num_rows($result)>0){
	while($row=mysqli_fetch_row($result)){
		
	$testo.='
		 <li  onclick="addservice('.$row['0'].',1)">
            <label class="label-radio item-content">
              <div class="item-inner" >
                <div class="item-title">'.estrainome($row['0']).'<br><span style="font-size:10px; color:#777;">'.estrainomeapp($row['0']).'</span></div>
				 <div class="item-after">ID:'.$row['1'].'</div>
				
              </div>
            </label>
          </li>
	
	';
	
	
	}
}


$testo.='</ul></div>
</div></div></div>


';
		


echo $testo;
?>