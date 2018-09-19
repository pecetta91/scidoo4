<?php 
header('Access-Control-Allow-Origin: *');
include('../../../../config/connecti.php');
include('../../../../config/funzioni.php');
include('../../../../config/preventivoonline/config/funzioniprev.php');
$testo='';

$IDpren=$_SESSION['IDstrpren'];

$stringaid=$_GET['stringaid'];


$query="SELECT IDstruttura FROM prenotazioni WHERE IDv='$IDpren' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDstruttura=$row['0'];




$query="SELECT ID,IDcliente,IDrest,nome FROM infopren WHERE IDpren='$IDpren' AND pers='1'";
$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
		$testo.='<div id="nuovoserv">';
		while($row=mysqli_fetch_row($result)){
				$checked='';
				if($row['2']!=0){
					$query3="SELECT nome,cognome FROM schedine WHERE ID='".$row['1']."' LIMIT 1";
					$result3=mysqli_query($link2,$query3);
					$row3=mysqli_fetch_row($result3);
					$nome='<strong>'.$row3['0'].' '.$row3['1'].'</strong>';
				}	
							if(preg_match('/'.$row['0'].'/',$stringaid)){
								$checked='checked';
							}
			
				$testo.='<div class="row rowlist no-gutter h30" id="'.$row['0'].'" alt="'.$row['1'].'" >
							<div class="col-15">
								<label class="label-checkbox item-content">
									<input type="checkbox" '.$checked.' class="scegliperscheckbox" alt="'.$row['2'].'"  id="'.$row['0'].'">
									  <div class="item-media">
										  <i class="icon icon-form-checkbox"></i>
									  </div>
							</div>
									<div class="col-35 campiricerca">'.$row['3'].'</div>
									<div class="col-50 campiricerca">'.$nome.'</div>';
									
			
				$testo.='</div>';

		}
		$testo.='</div>';
	
	}

		
?>
<div class="picker-modal " id="popoverord" >
		  <div class="toolbar">
			<div class="toolbar-inner">
		  	  <div class="left"></div>
			  <div class="right"><a href="#" class="close-picker" onclick="contapersnuovoserv();myApp.closeModal();">Chiudi</a></div>
			</div>
		  </div>
		  <div class="picker-modal-inner ">
		 <div class="page-content bcw" > 
		 	<?php echo $testo;?>
			  </div>
		  </div>
</div>
