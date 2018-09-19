<?php


	header('Access-Control-Allow-Origin: *');
	include('../../config/connecti.php');
	include('../../config/funzioni.php');
	include('../../config/funzionilingua.php');



$IDstruttura=$_SESSION['IDstruttura'];
$IDprenextra=$_POST['IDprenextra'];
$agg=$_POST['agg'];

$querym="SELECT sottotip,time FROM prenextra WHERE IDstruttura='$IDstruttura' AND ID='$IDprenextra'";

$resultm=mysqli_query($link2,$querym);
$rowm=mysqli_fetch_row($resultm);
		$IDsottotip=$rowm['0'];
	    $time=$rowm['1'];
$data=date('Y-m-d',$time);


$testo='<input type="hidden" value="'.$time.'" id="time">
<input type="hidden" value="'.$IDsottotip.'" id="idosottotip">
<input type="hidden" value="'.$agg.'" id="agg">';

$query="SELECT sale.ID,sale.nome FROM sale,saleassoc WHERE sale.IDstr='$IDstruttura' AND saleassoc.IDsotto='$IDsottotip' AND saleassoc.ID=sale.ID ORDER BY saleassoc.priorita";
		$result=mysqli_query($link2,$query);
		while($row=mysqli_fetch_row($result)){
		$IDsala=$row['0'];
	    $nomesala=$row['1'];
		
			$testo.='
					<div class="sale2">'.$nomesala.'</div>
				';
			
			
		$arrt=array();
		$query2="SELECT GROUP_CONCAT(IDprenextra SEPARATOR ','),num FROM tavoli WHERE 	IDsottotip='$IDsottotip' AND sala='$IDsala' AND FROM_UNIXTIME(time,'%Y-%m-%d')='$data' AND stato='1' GROUP BY num";
		$result2=mysqli_query($link2,$query2);
		if(mysqli_num_rows($result2)>0){
			while($row2=mysqli_fetch_row($result2)){
				$arrt[$row2['1']]=$row2['0'];
				
			}
		}
		
		
		$query3="SELECT dato1,dato2 FROM sale WHERE ID='$IDsala' AND IDstr='$IDstruttura' LIMIT 1";
		$result3=mysqli_query($link2,$query3);
		$row3=mysqli_fetch_row($result3);
		$dato1=$row3['0'];
		$dato2=$row3['1'];
	
		
		for($i=$dato1;$i<=$dato2;$i++){
			$tavolo="'".$i.'_'.$IDsala."'";
			if(isset($arrt[$i])){
					
		
				$testo.='
				<div class="allocatavolo occupato">
				  <div class="num" style="color:red;" >'.$i.'</div><br>
		        </div>
				
	 ';
			/*$testo.='<p style="color:red;display:inline-block;">'.$i.'</p>';	*/
}else{
				$testo.='
				<div class="allocatavolo libero" onclick="modprenextra('.$IDprenextra.','.$tavolo.',36,9,24);">
					<div class="num" style="color:#39b071;">'.$i.'</div><br>
				</div>
			
				';
				/*$testo.='<p style="color:green;display:inline-block;">'.$i.'</p>';*/
			}
		}	
			$testo.='<br/><br/>';
	}	
?>
<div class="picker-modal">
      <div class="toolbar">
        <div class="toolbar-inner">
          <div class="left"></div>
          <div class="right"><a href="#" class="close-picker">Close</a></div>
        </div>
      </div>
      <div class="picker-modal-inner">
        <div class="page-content" style="background-color: white">
         <?php
			echo $testo;
			?>
        </div>
      </div>
	</div>

