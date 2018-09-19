<?php
$ricerca='';
if(!isset($inc)){
	header('Access-Control-Allow-Origin: *');
	include('../../../config/connecti.php');
	include('../../../config/funzioni.php');
	include('../../../config/preventivoonline/config/funzioniprev.php');
	$testo='';
	
	if(isset($_GET['dato0'])){
		if($_GET['dato0']!='0'){
			$ricerca=$_GET['dato0'];
		}else{
			$ricerca='';
		}
	}
	
}


$IDutente=$_SESSION['ID'];
$IDstruttura=$_SESSION['IDstruttura'];
$IDrequest=$_SESSION['IDrequest'];

/*
$query="SELECT IDstr,notti,timearr,stato,checkout,agenzia FROM richieste WHERE ID='$IDrequest' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDstruttura=$row['0'];
$gg=$row['1'];
$ggric=$gg;
$timearr=$row['2'];
$datagg=date('Y-m-d',$timearr);
$stato=$row['3'];
$checkout=$row['4'];
$IDagenzia=$row['5'];
$ggsett=date('N',$timearr);
*/



$query="SELECT ID,restrizione,IDcliente FROM richiestep WHERE IDreq='$IDrequest'";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDrestr=$row['0'];
$IDcliente=$row['2'];


if($IDcliente==0){
	$nome='';
	$cognome='';
	$email='';
	$tel='';
	$note='';
	$noteristo='';
	$cell='';
	
	
	$query="SELECT prefisso FROM strutture WHERE ID='$IDstruttura' LIMIT 1";
	$result=mysqli_query($link2,$query);
	$row=mysqli_fetch_row($result);
	$prefisso=$row['0'];
	
	$query="INSERT INTO schedine VALUES(NULL,'$nome','','0000-00-00','0','','','0','','','','','0000-00-00','$IDstruttura','','$prefisso','','$prefisso','','','')";
	$result=mysqli_query($link2,$query);
	$IDdoc=mysqli_insert_id($link2);
	$query="UPDATE richiestep SET IDcliente='$IDdoc' WHERE ID='$IDrestr' LIMIT 1";
	$result=mysqli_query($link2,$query);
	$IDcliente=$IDdoc;
	$nome='';

	$prefissotel=$prefisso;
	$prefissocell=$prefisso;


}else{
	$query3="SELECT nome,cognome,tel,mail,note,noteristo,cell,prefissotel,prefissocell FROM schedine WHERE ID='$IDcliente' LIMIT 1";
	$result3=mysqli_query($link2,$query3);
	$row3=mysqli_fetch_row($result3);
	$nome=$row3['0'];
	$cognome=$row3['1'];
	$tel=$row3['2'];
	$email=$row3['3'];
	$note=$row3['4'];
	$noteristo=$row3['5'];
	$cell=$row3['6'];
	$prefissotel=$row3['7'];
	$prefissocell=$row3['8'];
}

if(strlen($ricerca)>0){
	
	$ricerca=expricerca($ricerca);
		
	$query="SELECT ID,nome,cognome,mail,tel,note,noteristo,MATCH(nome,cognome,mail) AGAINST('".$ricerca."') as score FROM schedine WHERE MATCH(nome,cognome,tel,mail,note,noteristo) AGAINST('".$ricerca."' IN BOOLEAN MODE) AND IDstr='$IDstruttura'  ORDER BY score DESC LIMIT 25";
	
	
	$testo='<div class="list-block">
      <ul>
	  
	  	<li><a href="#"  onclick="modprenot('.$IDrestr.',0,150,10,4)" class="item-content item-link" >
							<div class="item-inner" >
							  <div class="item-title"><b style="color:#b71c40;">Nuovo Cliente</b>
							  </div>
							</div></a>
						 
						</li>
	  
	  
	  
	  
	  ';
	  
	$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
		while($row=mysqli_fetch_row($result)){
			$note='';
			if((strlen($row['5'])>0)||(strlen($row['4'])>0)){
				$note='<br><span style="font-size:10px; color:#999;">'.$row['5'].' '.$row['6'].'</span>';
			}
			$tel='';
			$email='';
			if(strlen($row['4'])>0){
				$tel=$row['4'];
			}else{
				$tel='<span style="color:#ccc;">No Tel</span>';
			}
			
			if(strlen($row['3'])>0){
				$email=$row['3'];
			}else{
				$email='<span style="color:#ccc;">No Email</span>';
			}
			
			$testo.='
						<li><a href="#" onclick="modprenot('.$IDrestr.','.$row['0'].',150,10,4)" class="item-content item-link" >
							<div class="item-inner" >
							  <div class="item-title" style="line-height:13px;font-size:15px;"><b>'.$row['1'].'</b>'.$row['2'].'
							  <br>
							 <span style="font-size:12px;color:#555;">'.$tel.'; '.$email.'</span>
							  
							  '.$note.'</div>
							</div></a>
						 
						</li>
						';
			
			
		
		
		}
	}
	   
	   
	   
	   
	   
      $testo.='</ul>
    </div>';
	
	
	


}else{
		
	$testo.='
	
	
	 <form class="list-block inputs-list item45">
	  <ul>
		<!-- Text inputs -->
		<li>
		  <div class="item-content">
			<div class="item-media "><i class="icon f7-icons">person</i></div>
			<div class="item-inner">
			  <div class="item-input">
				<input type="text" onchange="modprenot('.$IDcliente.',this,41,11,0);" value="'.$cognome.'" placeholder="Cognome">
			  </div>
			</div>
		  </div>
		</li>
		<li>
		  <div class="item-content">
			<div class="item-media "><i class="icon f7-icons">person</i></div>
			<div class="item-inner">
			  <div class="item-input">
				<input type="text" onchange="modprenot('.$IDcliente.',this,40,11,0);" value="'.$nome.'" placeholder="Nome">
			  </div>
			</div>
		  </div>
		</li>
		
		
		<li>
		  <div class="item-content">
			<div class="item-media "><i class="icon f7-icons">email</i></div>
			<div class="item-inner">
			  <div class="item-input">
				<input type="email"   onchange="modprenot('.$IDcliente.',this,56,11,0);" value="'.$email.'" placeholder="E-mail">
			  </div>
			</div>
		  </div>
		</li>
		
		
		<li>
		  <div class="item-content">
			<div class="item-media "><i class="icon f7-icons">phone</i></div>
			<div class="item-inner">
			  <div class="item-input">
			  	<select  onchange="modprenot('.$IDcliente.',this,157,11,0);" style="width:70px; height:35px; font-size:13px;  color:#444; display:inline-block;">'.generaprefisso($prefissocell).'</select>
			  
				<input type="tel" style="width:50%; display:inline-block; margin-left:5px;"  value="'.$cell.'"  onchange="modprenot('.$IDcliente.',this,153,11,0);" placeholder="Cellulare">
			  </div>
			</div>
		  </div>
		</li>
		
		<li>
		  <div class="item-content">
			<div class="item-media "><i class="icon f7-icons">phone</i></div>
			<div class="item-inner">
			  <div class="item-input">
				
				<select  onchange="modprenot('.$IDcliente.',this,156,11,0);" style="width:70px; font-size:13px; height:35px;  color:#444; display:inline-block;">'.generaprefisso($prefissotel).'</select>
				<input type="tel" style=" width:50%; display:inline-block; margin-left:5px;"  value="'.$tel.'"  onchange="modprenot('.$IDcliente.',this,57,11,0);" placeholder="Telefono">
			  </div>
			</div>
		  </div>
		</li>
		
		
		<!-- Textarea -->
		<li class="align-top">
		  <div class="item-content">
			<div class="item-media "><i class="icon f7-icons">chat</i></div>
			<div class="item-inner">
			  <div class="item-input" style="overflow:visible;">
				<textarea placeholder="Note" style="font-size:17px; color:#333;" class="resizable" onchange="modprenot('.$IDcliente.',this,70,11,0);" >'.$note.'</textarea>
			  </div>
			</div>
		  </div>
		</li>
		
		<!-- Textarea -->
		<li style=" height:auto;">
		  <div class="item-content" style=" height:auto;">
			<div class="item-media"><i class="icon f7-icons">chat</i></div>
			<div class="item-inner">
			  <div class="item-input">
				<textarea placeholder="Note Ristorante"  style="font-size:17px; height:auto; color:#333;" class="resizable" onchange="modprenot('.$IDcliente.',this,137,11,0);">'.$noteristo.'</textarea>
			  </div>
			</div>
		  </div>
		</li>
	  </ul>
	</form>
	
	
	';
}

if(!isset($inc)){
	echo $testo;
}
			 
?>			 
			 