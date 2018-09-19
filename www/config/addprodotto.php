<?php 
header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');

$IDutente=$_SESSION['ID'];
$IDstruttura=$_SESSION['IDstruttura'];
unset($_SESSION['arrservadd']);

list($IDpren,$IDprenextra)=explode(',',$_GET['IDpren']);

//$_SESSION['IDprenfunc']=$IDpren;
$testo='
<div class="pages ">
  <div data-page="addprodotto" class="page">

            <div class="navbar">
               <div class="navbar-inner">
                  <div class="left" > <a href="#" class="link back">
							<i class="material-icons" style="font-size:40px;">chevron_left</i>
						</a></div>
                  <div class="center"><b>'.estrainome($IDpren).'</b><br><span style="font-size:11px;">'.estrainomeapp($IDpren).'</span></div>
                  
               </div>
            </div>
		
			<div  class="page-content contacts-content" >
<br>
			<div class="list-block contacts-block">
			 	<ul>
';


	$IDsotto=0;
	$query="SELECT s.ID,s.servizio,s.prezzo,ss.sottotipologia,ss.ID FROM servizi as s,sottotipologie as ss  WHERE s.IDstruttura='$IDstruttura' AND s.IDtipo='10' AND s.IDsottotip=ss.ID ORDER BY ss.sottotipologia,s.servizio";
	
	$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
		while($row=mysqli_fetch_row($result)){
		
			if($row['4']!=$IDsotto){
				/*if($IDsotto!=0){
					$testo.='</ul></div>';
				}*/
				$testo.='
      			  <li class="list-group-title" style="padding-top:5px;">'.$row['3'].'</li>';
				$IDsotto=$row['4'];			
			}
			
		$testo.='
			<li>
			  <div class="item-content">
			  
			  <div class="item-media"><div class="roundb" alt="'.$row['0'].'" id="p'.$row['0'].'" onclick="addprod('.$row['0'].',0,'.$row['2'].')">0</div></div>
				<div class="item-inner" onclick="addprod('.$row['0'].',1,'.$row['2'].')">
				  <div class="item-title" >'.$row['1'].'</div>
					<div class="item-after">'.$row['2'].' €</div>
				</div>
			  </div>
			</li>
		
		
		';
		
		
		}
	}
	
	


$testo.='

</ul></div>
<br><br><br><br><br><br><br><br><br>


			<div style="width:100%; z-index:999; transform:translateZ(0); webkit-transform:translateZ(0); height:50px; position:fixed; bottom:0px; left:0px; background:#f5a149; color:#fff;">
			
			<table style="width:100%; height:100%;"><tr><td style="text-align:center; font-size:15px;">N. <span id="nprod">0</span> PRODOTTI  = € <span id="euro">0</span> </td><td style="width:30%; padding-right:5px;"><a href="#" onclick="addprod2('.$IDprenextra.');mainView.router.back();
						" class="button button-fill color-pink">Aggiungi</button> </td></tr></table>
			<div>
			


</div></div>

';
		


echo $testo;
?>