<?php

		header('Access-Control-Allow-Origin: *');
	include('../../config/connecti.php');
	include('../../config/funzioni.php');
	include('../../config/funzionilingua.php');

	$testo='';
	
	if(isset($_GET['dato0'])){
		if($_GET['dato0']!='0'){
			$ricerca=$_GET['dato0'];
		}else{
			$ricerca='';
		}
	}
	



$IDutente=$_SESSION['ID'];
$IDstruttura=$_SESSION['IDstruttura'];



if(strlen($ricerca)>2){
	
	$ricerca=mysqli_real_escape_string($link2,$ricerca);
		
	//$query="SELECT ID,nome,cognome,mail,tel,cell,MATCH(nome,cognome,mail) AGAINST('".$ricerca."') as score FROM schedine WHERE MATCH(nome,cognome,tel,mail,note,noteristo) AGAINST('".$ricerca."' IN BOOLEAN MODE) AND IDstr='$IDstruttura'  ORDER BY score DESC LIMIT 15";
	
	$query="SELECT ID,nome,cognome,mail,tel,cell FROM schedine WHERE (mail LIKE '%$ricerca%' OR tel LIKE '%$ricerca%' OR cell LIKE '%$ricerca%' OR CONCAT (nome,' ',cognome) LIKE '%$ricerca%') AND IDstr='$IDstruttura' ORDER BY ID DESC LIMIT 25";
	
	$testo='';
	  
	$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
		while($row=mysqli_fetch_row($result)){
			
			$tel='';
			$email='';
			if(strlen($row['4'])>0){
				$tel=$row['4'].';';
			}
			if(strlen($row['5'])>0){
				$tel.=$row['5'].';';
			}
			if(strlen($tel)>0){
				$tel.='<br>';
			}
			
			if(strlen($row['3'])>0){
				$email=$row['3'];
			}
			
			$IDpers=$row['0'];
			$testo.='
			<div class="row rowlist no-gubber h40" onclick="prenotaztavolo(2,'.$IDpers.');chiudimodal();">
				<div class="col-60"><strong>'.$row['1'].' '.$row['2'].'</strong></div>
				<div class="col-40 f11 rightcol" >'.$tel.''.$email.'</div>
			
			</div>
			
			
			';
			
			
			
		}
	}else{
		/*
		 $testo.='<li>
		 <a href="#" onclick="prenotaztavolo(3);chiudimodal();"class="item-content item-link" >
							<div class="item-inner" >
							  <div class="item-title" style="line-height:13px;font-size:15px;"><b>Inserire nuovo cliente</b>
							  </div>
							</div>
		</a>
						</li>';*/
		
		$testo.='
		<div class="row rowlist no-gubber h40"  onclick="prenotaztavolo(3);chiudimodal();">
				<div class="col-100" style="color:#3cb878;"><strong>Nuovo Cliente</strong></div>
				
			
			</div>';
	}
   
     
}

	echo $testo;

			 
?>			 
			 