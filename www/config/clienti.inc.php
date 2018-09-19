<?php
$ricerca='';
if(!isset($inc)){
	header('Access-Control-Allow-Origin: *');
	include('../../config/connecti.php');
	include('../../config/funzioni.php');
	include('../../config/funzionilingua.php');
	
	$IDutente=intval($_SESSION['ID']);
	$IDstruttura=intval($_SESSION['IDstruttura']);
	
	
	
	if(isset($_GET['dato0'])){
		if($_GET['dato0']!='0'){
			$ricerca=$_GET['dato0'];
		}else{
			$ricerca='';
		}
	}
}


	
	
	$testo='

	  <div class="list-block">
      <ul>';
	  
	   

	$IDric=0;
	$qadd='';
	if(strlen($ricerca)>2){
		$arr=ricercacliente($ricerca,$IDstruttura);
		
		if(!empty($arr)){
			$IDric=implode(',',$arr);
		}
		
	}

    if(strlen($ricerca)>0){
		 $query="SELECT ID,nome,cognome,mail,tel,note,noteristo FROM schedine WHERE ID IN ($IDric) AND IDstr='$IDstruttura' ORDER BY POSITION(ID IN '($IDric)') LIMIT 50";	   
	}else{
		$query="SELECT ID,nome,cognome,mail,tel,note,noteristo FROM schedine WHERE IDstr='$IDstruttura' AND nome!='' AND cognome!=''   ORDER BY ID DESC LIMIT 25 ";	
	}

	

	
	$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
		while($row=mysqli_fetch_row($result)){
			$note='';
			if((strlen($row['5'])>0)||(strlen($row['4'])>0)){
				$note=$row['5'].' '.$row['6'];
			}
			$tel='';
			$txtaft='';
			$email='';
			if(strlen($row['4'])>0){
				//$tel=$row['4'];
				$txtaft.='<i class="icon f7-icons">phone</i>';
			}else{
				//$tel='No Tel.';
			}
			
			if(strlen($row['3'])>0){
				//$email=$row['3'];
			}else{
				//$email='No Email';
				$txtaft.=' &nbsp;<i class="icon f7-icons">email</i>';
			}
			
			/*<br>
							 <span>'.$tel.' | '.$email.'</span>
							  
							  '.$note.'*/
			
			
			$testo.='
						<li><a href="#" onclick="navigation(24,'."'".$row['0'].",1'".',0,0);" class="item-content item-link" >
							<div class="item-inner" >
							  <div class="item-title">'.$row['1'].' '.$row['2'].'</div>
							  <div class="item-after">'.$txtaft.'</div>
							</div></a>
						 
						</li>
						';
			
			
		
		
		}
	}
	   
	   
	   
	   
	   
      $testo.='</ul>
    </div>
	
	
	';
	echo $testo;	

?>