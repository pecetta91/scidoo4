<?php
header('Access-Control-Allow-Origin: *');
include('../../../config/connecti.php');
include('../../../config/funzioni.php');


$testo='';

	
if(isset($_GET['dato0'])){
	if($_GET['dato0']!='0'){
		$ricerca=$_GET['dato0'];
	}else{
		$ricerca='';
	}
}
$tipo=$_SESSION['tipologia'];
$IDcliente=$_SESSION['idcliente'];
$testo.='<input type="hidden" id="idcliente" value="'.$IDcliente.'">';

					
if(strlen($ricerca)>0){
	$ricerca=mysqli_real_escape_string($link2,$ricerca);
	
		
	switch($tipo){
			
		case 1://cittadinanza
				$query="SELECT cod,descrizione,provincia FROM alloggiati.stati WHERE descrizione LIKE '%" .$ricerca. "%' ORDER BY descrizione LIMIT 15";
				$result=mysqli_query($link2,$query);//comuni('.$row['0'].')
				if(mysqli_num_rows($result)>0){
					while($row=mysqli_fetch_row($result)){

						$testo.='<div class="row rowlist no-gutter h30" id="'.$row['0'].'" alt="'.$row['1'].'" onclick="autoscrivi('.$row['0'].',1);chiudimodal();">
									<div class="col-20 campiricerca " >'.$row['2'].'</div>
									<div class="col-80 campiricerca"><strong>'.$row['1'].'</strong></div>
								</div>';	
					}
				}else{
					$testo.= '<span>Non &egrave; stato trovato nessun risultato.</span>';
				}

		break;
			
		case 2://comune
				$query="SELECT cod,descrizione,provincia,MATCH(descrizione) AGAINST('".$ricerca."') as score FROM alloggiati.comuni WHERE MATCH(descrizione) AGAINST('*".$ricerca."*' IN BOOLEAN MODE) ORDER BY descrizione DESC LIMIT 15";// funascita('."'".$row['0']."'".')
				$result=mysqli_query($link2,$query);
				if(mysqli_num_rows($result)>0){
					while($row=mysqli_fetch_row($result)){

						$testo.='<div class="row rowlist no-gutter h30" id="'.$row['0'].'" alt="'.$row['1'].'" onclick="autoscrivi('.$row['0'].',2);chiudimodal();">
									<div class="col-20 campiricerca">'.$row['2'].'</div>
									<div class="col-80 campiricerca"><strong>'.$row['1'].'</strong></div>
								</div>';	
					}
				}else{
					$testo.= '<span>Non &egrave; stato trovato nessun risultato.</span>';
				}
		
		break;	
		
		case 3://residenza?
				$query="SELECT cod,descrizione,provincia,MATCH(descrizione) AGAINST('".$ricerca."*') as score FROM alloggiati.comuni WHERE MATCH(descrizione) AGAINST('*".$ricerca."*' IN BOOLEAN MODE) ORDER BY descrizione DESC LIMIT 15";//furesidenza('.$row['0'].')
				$result=mysqli_query($link2,$query);
				if(mysqli_num_rows($result)>0){
					while($row=mysqli_fetch_row($result)){

						$testo.='<div class="row rowlist no-gutter h30" id="'.$row['0'].'" alt="'.$row['1'].'" onclick="autoscrivi('.$row['0'].',3);chiudimodal();">
									<div class="col-20 campiricerca">'.$row['2'].'</div>
									<div class="col-80 campiricerca"><strong>'.$row['1'].'</strong></div>
								</div>';	
					}
				}else{
					$testo.= '<span>Non &egrave; stato trovato nessun risultato.</span>';
				}
		
		break;	
		
		case 4://luogo di rilascio
				$query="SELECT cod,descrizione,provincia,MATCH(descrizione) AGAINST('".$ricerca."*' IN BOOLEAN MODE) as score FROM alloggiati.comuni WHERE MATCH(descrizione) AGAINST('*".$ricerca."*' IN BOOLEAN MODE) ORDER BY descrizione DESC LIMIT 15";//furilascio('.$row['0'].')
				$result=mysqli_query($link2,$query);
				if(mysqli_num_rows($result)>0){
					while($row=mysqli_fetch_row($result)){

						$testo.='<div class="row rowlist no-gutter h30" id="'.$row['0'].'" alt="'.$row['1'].'" onclick="autoscrivi('.$row['0'].',4);chiudimodal();">
									<div class="col-20 campiricerca">'.$row['2'].'</div>
									<div class="col-80 campiricerca"><strong>'.$row['1'].'</strong></div>
								</div>';	
					}
				}else{
					$testo.= '<span>Non &egrave; stato trovato nessun risultato.</span>';
				}
		break;
		
		case 5://documento
				$query="SELECT cod,descrizione FROM alloggiati.documenti WHERE descrizione LIKE '%".$ricerca."%' ORDER BY descrizione LIMIT 15";
				$result=mysqli_query($link2,$query);//fudocumenti('."'".$row['0']."'".')
				if(mysqli_num_rows($result)>0){
					while($row=mysqli_fetch_row($result)){

						$testo.='<div class="row rowlist no-gutter h30" id="'.$row['0'].'" alt="'.$row['1'].'" onclick="autoscrivi('."'".$row['0']."'".',5);chiudimodal();">
									<div class="col-80 campiricerca"><strong>'.$row['1'].'</strong></div>
								</div>';	
					}
				}else{
					$testo.= '<span>Non &egrave; stato trovato nessun risultato.</span>';
				}
		break;	
	}

}

	echo $testo;

			 
?>			 
			 