<?php
$arrins=array();

if(!isset($inc)){
header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');

$IDutente=$_SESSION['ID'];
$IDstruttura=$_SESSION['IDstruttura'];

	if(isset($_GET['dato0'])){
		$IDcategoria=$_GET['dato0'];
	}
	if(isset($_GET['dato1'])){
		if(strlen($_GET['dato1'])>0){
			$insert=explode('/////',$_GET['dato1']);
			foreach($insert as $dato){
				list($ID,$qta)=explode('_',$dato);
				$arrins[$ID]=$qta;
			}
		}
       
	}
	
	
}



//print_r($arrins);


$testo='<br>';;

       $query="SELECT ID,servizio,prezzo FROM servizi  WHERE IDsottotip='$IDcategoria'";

       $result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
		while($row=mysqli_fetch_row($result)){
			
			
			$qtain=0;
			$sel='';
			if(isset($arrins[$row['0']])){
				$qtain=$arrins[$row['0']];
				$sel=' selected';
			}
			
			
			$testo.='

			<div class="row rowlist no-gutter h40">
				<div class="col-10"  onclick="addprod('.$row['0'].',0,'.$row['2'].')">

				<div class="buttaddin">-</div>
				</div>
				<div class="col-10" style="text-align:center;"><div class="buttaddin2 '.$sel.'" alt="'.$row['0'].'" id="p'.$row['0'].'">'.$qtain.'</div></div>
				<div class="col-10" align="right"  onclick="addprod('.$row['0'].',1,'.$row['2'].')"><div class="buttaddin">+</div></div>
				<div class="col-5"></div>
				<div class="col-50 coltitle">'.$row['1'].'</div>
				<div class="col-15">'.$row['2'].' €</div>


			</div>
			';
			
			/*<li>
			  <div class="item-content">
			  
			  <div class="item-media"><div class="roundb" alt="'.$row['0'].'" id="p'.$row['0'].'" onclick="addprod('.$row['0'].',0,'.$row['2'].')">0</div></div>
				<div class="item-inner" onclick="addprod('.$row['0'].',1,'.$row['2'].')">
				  <div class="item-title" >'.$row['1'].'</div>
					<div class="item-after">'.$row['2'].' €</div>
				</div>
			  </div>
			</li>*/
			
			
		}
	}


			//$testo.='</ul></div>';

echo $testo;
?>