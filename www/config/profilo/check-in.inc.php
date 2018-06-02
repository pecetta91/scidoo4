<?php
if(!isset($inc)){
	include('../../../config/connecti.php');
	include('../../../config/funzioni.php');
	//
	$testo='';
	
	
}
$testo='';

$IDpren=$_SESSION['IDstrpren'];
$route=$_SESSION['route'];


$modificabile='';

$info=0;
if(isset($_GET['dato1'])){
		if($_GET['dato1']!='0'){
			$ID=$_GET['dato1'];
		}
}

if(isset($_GET['dato0'])){
		if($_GET['dato0']!='0'){
			$IDcliente=$_GET['dato0'];
			$info=2;
		}
	if($_GET['dato0']=='0'){
			$info=1;}
}

$query="SELECT app,gg,time,IDstruttura,checkout FROM prenotazioni WHERE IDv='$IDpren' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDapp=$row['0'];
$notti=$row['1'];
$check=$row['2'];
$IDstruttura=$row['3'];
$checkout=$row['4'];






$testo.='<input type="hidden" value="'.$info.'" id="switch">
<input type="hidden" value="'.$IDcliente.'" id="idcli">
<input type="hidden" value="'.$ID.'" id="ID"> ';
$selectedf='';
$selectedm='';

$query="SELECT ID,IDcliente FROM infopren WHERE IDstr='$IDstruttura' AND IDpren='$IDpren' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDriga=$row['0'];


if($IDriga==$ID)
{
	$modificabile='readonly';
}

switch($info)
{
		
	case 1:
		$query="SELECT prefisso FROM strutture WHERE ID='$IDstruttura' LIMIT 1";
		$result=mysqli_query($link2,$query);
		$row=mysqli_fetch_row($result);
		$prefisso=$row['0'];
		
		$query="INSERT INTO schedine VALUES(NULL,'','','0000-00-00','0','','','0','','','','','0000-00-00','$IDstruttura','','$prefisso','','$prefisso','','','')";
		$result=mysqli_query($link2,$query);
		$IDcliente=mysqli_insert_id($link2);
		$query="UPDATE infopren SET IDcliente='$IDcliente' WHERE ID='$ID' LIMIT 1";
		$result=mysqli_query($link2,$query);
		
	break;	
		
		
	case 2:
		$query="SELECT nome,cognome,tel,mail,datanas,sesso,note,noteristo,cell,prefissotel,prefissocell,cittadinanza,luogonas,residenza,indirizzo,documento,numero,dataril,luogoril FROM schedine WHERE ID='$IDcliente' LIMIT 1";
			$result=mysqli_query($link2,$query);
			if(mysqli_num_rows($result)>0){
				$row=mysqli_fetch_row($result);
				$nome=$row['0'];
				$cognome=$row['1'];
				$tel=$row['2'];
				$email=$row['3'];
				$datanas=$row['4'];
				$sesso=$row['5'];
				if(strcmp($sesso,'F')==0){
					$selectedf='selected';
				}
				else{
					$selectedm='selected';
				}

				$noteristo=stripslashes($row['7']);
	
				$prefissotel=$row['9'];
	 
				$cittadinanza=$row['11'];
					$query2="SELECT cod,descrizione FROM alloggiati.stati WHERE cod='$cittadinanza' ORDER BY descrizione LIMIT 1";
					$result2=mysqli_query($link2,$query2);
					if(mysqli_num_rows($result2)>0){
						$row2=mysqli_fetch_row($result2);
						$cittadinanzaver=ucwords(strtolower($row2['1']));
					}
				$luogonas=$row['12'];
					$query2="SELECT cod,descrizione FROM alloggiati.comuni WHERE cod='$luogonas' ORDER BY descrizione LIMIT 1";
					$result2=mysqli_query($link2,$query2);
					if(mysqli_num_rows($result2)>0){
						$row2=mysqli_fetch_row($result2);
						$luogonasver=ucwords(strtolower($row2['1']));
					}
				$residenza=$row['13'];
					$query2="SELECT cod,descrizione FROM alloggiati.comuni WHERE cod='$residenza' ORDER BY descrizione LIMIT 1";
					$result2=mysqli_query($link2,$query2);
					if(mysqli_num_rows($result2)>0){
						$row2=mysqli_fetch_row($result2);
						$residenzaver=ucwords(strtolower($row2['1']));
					}
				
				$documento=$row['15'];
					$query2="SELECT cod,descrizione FROM alloggiati.documenti WHERE cod='$documento' ORDER BY descrizione LIMIT 1";
					$result2=mysqli_query($link2,$query2);
					if(mysqli_num_rows($result2)>0){
						$row2=mysqli_fetch_row($result2);
						$documentover=ucwords(strtolower($row2['1']));
					}

				
				$numerodoc=$row['16'];
				$dataril=$row['17'];
				
				$luogoril=$row['18'];
					$query2="SELECT cod,descrizione FROM alloggiati.comuni WHERE cod='$luogoril' ORDER BY descrizione LIMIT 1";
					$result2=mysqli_query($link2,$query2);
					if(mysqli_num_rows($result2)>0){
						$row2=mysqli_fetch_row($result2);
						$luogorilver=ucwords(strtolower($row2['1']));
					}
			}
	
	break;
}
			
				$testo.='<div class="content-block-title titleb">Informazioni Cliente</div>
					<div class="list-block">
						<ul>';
					$testo.='
						<li>
						  <div class="item-content">
							<div class="item-inner">
							  <div class="item-title label">Nome</div>
							  <div class="item-input" >
								<input type="text" onchange="modprofilo('.$IDcliente.','."'nome'".',22,0)" id="nome" value="'.$nome.'"  '.$modificabile.'>
							  </div>
							</div>
						  </div>
						</li>
						<li>
						  <div class="item-content">
							<div class="item-inner">
							  <div class="item-title label">Cognome</div>
							  <div class="item-input" >
								<input type="text" onchange="modprofilo('.$IDcliente.','."'cognome'".',23,0)" id="cognome" value="'.$cognome.'" '.$modificabile.'>
							  </div>
							</div>
						  </div>
						</li>
						<li><a href="#" class="item-link smart-select" data-open-in="picker">
							<select name="sesso"  onchange="modprofilo('.$IDcliente.',this,25,11)">
							  <option value="M" '.$selectedm.'>M</option>
							  <option value="F" '.$selectedf.'>F</option>
							</select>
							<div class="item-content">
							  <div class="item-inner">
								<div class="item-title">Sesso</div>
								<div class="item-after" id="sesso">'.$sesso.'</div>
							  </div>
							</div>
						  </a>
						  
						</li>
						<li>
						  <div class="item-content" onclick="autoricerca(1,'.$IDcliente.');">
							<div class="item-inner">
							  <div class="item-title label">Cittadinanza</div>
							  <div class="item-input cittadinanzaver autoricerca" id="cittadinanza" alt="'.$cittadinanza.'">'.$cittadinanzaver.'</div>
							</div>
						  </div>
						</li>
						</ul>
						</div>';
			
						$testo.='<div class="list-block">
						<ul>
						<li>
						  <div class="item-content">
							<div class="item-inner">
							  <div class="item-title label">Data Di Nascita</div>
							  <div class="item-input" >
								<input type="date" onchange="modprofilo('.$IDcliente.','."'datanas'".',24,0)" id="datanas" value="'.$datanas.'"  >
							  </div>
							</div>
						  </div>
						</li>
						<li>
						  <div class="item-content" onclick="autoricerca(2,'.$IDcliente.');">
							<div class="item-inner">
							  <div class="item-title label">Luogo Di Nascita</div>
							  <div class="item-input luogonasver autoricerca" id="luogonascita">'.$luogonasver.'</div>
							</div>
						  </div>
						</li>
						<li>
						  <div class="item-content" onclick="autoricerca(3,'.$IDcliente.');">
							<div class="item-inner">
							  <div class="item-title label">Residenza</div>
							  <div class="item-input residenzaver autoricerca" id="residenza" >'.$residenzaver.'</div>
							</div>
						  </div>
						</li>
						</ul>
						</div>';
				
						$testo.='<div class="list-block">
						<ul>
						<li>
						  <div class="item-content" onclick="autoricerca(5,'.$IDcliente.');">
							<div class="item-inner">
							  <div class="item-title label">Documento</div>
							  <div class="item-input documentover autoricerca" id="documento">'.$documentover.'</div>
							</div>
						  </div>
						</li>
						<li>
						  <div class="item-content">
							<div class="item-inner">
							  <div class="item-title label">Numero Documento</div>
							  <div class="item-input" >
								<input type="text" id="numerodoc" onchange="modprofilo('.$IDcliente.','."'numerodoc'".',27,0)" value="'.$numerodoc.'"  >
							  </div>
							</div>
						  </div>
						</li>
						<li>
						  <div class="item-content">
							<div class="item-inner">
							  <div class="item-title label">Data Rilascio</div>
							  <div class="item-input" >
								<input type="date" id="datarilascio" onchange="modprofilo('.$IDcliente.','."'datarilascio'".',33,0)" value="'.$dataril.'"  >
							  </div>
							</div>
						  </div>
						</li>
						<li>
						  <div class="item-content" onclick="autoricerca(4,'.$IDcliente.');">
							<div class="item-inner">
							  <div class="item-title label">Luogo Rilascio</div>
							  <div class="item-input luogorilver autoricerca" id="luogorilascio" >'.$luogorilver.'</div>
							</div>
						  </div>
						</li>
						</ul>
						</div>';
				
						
				
				$testo.='<div class="list-block">
						<ul>
						<li>
						  <div class="item-content">
							<div class="item-inner">
							  <div class="item-title label">Telefono</div>
							  <div>
								  <a href="#" class="item-link smart-select" data-open-in="picker" pickerHeight="400px" >
									<select class="preftelpicker" id="prefisso" onchange="modprofilo('.$IDcliente.',this,38,11)">'.generaprefisso($prefissotel).' </select>
								 </a>
								</div>
							  <div class="item-input" >
								<input type="text" id="tel" onchange="modprofilo('.$IDcliente.','."'tel'".',35,0)" value="'.$tel.'" '.$modificabile.' >
							  </div>
							</div>
						  </div>
						</li>
						<li>
						  <div class="item-content">
							<div class="item-inner">
							  <div class="item-title label">E-mail</div>
							  <div class="item-input" >
								<input type="text" id="email" onchange="modprofilo('.$IDcliente.','."'email'".',34,0)" value="'.$email.'" '.$modificabile.' >
							  </div>
							</div>
						  </div>
						</li>
						</ul>
						</div>';

						$testo.='<div class="content-block-title titleb">Note Ristorante</div>
						<div class="list-block">
							<ul>
								<li>
									<div class="item-content h100">
										<div class="item-inner h100" >
										  <div class="item-input">
											<textarea  class="textareanew" id="noteristo"  onchange="modprofilo('.$IDcliente.','."'noteristo'".',37,0)" >'.$noteristo.'</textarea>
										  </div>
										</div>
									</div>
								</li>
							</ul>
						</div>
						

						<p class="privacyinfo">
							<b class="fs13">Termini e Condizioni</b><br> 
							I dati immessi sono protetti da privacy e crittografati.<br>
							Potranno essere utilizzati soltanto dalla struttura per fini produttivi e di crescita.
							</p>
							<br><br><br>
						
						';
			
 
		echo '<div class="content-block" id="tabellacli" style="padding:0px; width:100%;">';
		echo $testo;
?>
</div>