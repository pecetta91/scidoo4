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
			$ricerca=mysqli_real_escape_string($link2,$_GET['dato0']);
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
	
	$IDric=0;
	
	$arr=ricercacliente($ricerca,$IDstruttura);
	if(!empty($arr)){
		$IDric=implode(',',$arr);
	}
		
		
	$query="SELECT ID,nome,cognome,mail,tel,cell FROM schedine WHERE ID IN($IDric) AND IDstr='$IDstruttura' ORDER BY POSITION(ID IN '($IDric)')  LIMIT 25";
	
	$testo='
	  <div class="row no-gutter rowlist h40" onclick="modprenot('.$IDrestr.',0,150,10,4)">
			<div class="col-5  h40"></div>
			<div class="col-95 coltitle fs16"><b style="color:#b71c40;">Nuovo Cliente</b><br/>
			<span>Aggiungi un nuovo ospite</span>
			
			</div>
		</div>';
	  
	$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
		while($row=mysqli_fetch_row($result)){
			/*$note='';
			if((strlen($row['5'])>0)||(strlen($row['4'])>0)){
				$note='<br><span style="font-size:10px; color:#999;">'.$row['5'].' '.$row['6'].'</span>';
			}*/
			$tel='';
			$email='';
			if(strlen($row['4'])>0){
				$tel=$row['4'].'; ';
			}
			if(strlen($row['5'])>0){
				$tel.=$row['5'];
			}
			if(strlen($tel)==0){
				$tel.='<span style="color:#ccc;">No Tel</span>';
			}
			
			
			if(strlen($row['3'])>0){
				$email=$row['3'];
			}else{
				$email='<span style="color:#ccc;">No Email</span>';
			}
			
			
			$testo.='
			
			<div class="row no-gutter rowlist h40" onclick="modprenot('.$IDrestr.','.$row['0'].',150,10,4)">
									<div class="col-5"></div>
									<div class="col-95 coltitle fs16">'.$row['1'].' '.$row['2'].'<br>
									<span>'.$tel.' | '.$email.'</span></div>
						</div>
			';
					
			
	
		
		}
	}
	   
}else{
	
	$testo.='
	<div class="list-block">
	
	  <ul>
		<!-- Text inputs -->
		<li>
		  <div class="item-content">
			<div class="item-media "><i class="icon f7-icons">person</i> <span class="spancampo">&nbsp;Cognome</span> </div>
			<div class="item-inner">
			  <div class="item-input">
				<input type="text" onchange="modprenot('.$IDcliente.',this,41,11,0);"  style="font-size:15px;" value="'.$cognome.'" placeholder="Cognome">
			  </div>
			</div>
		  </div>
		</li>
		<li>
		  <div class="item-content">
			<div class="item-media "><i class="icon f7-icons">person</i> <span class="spancampo">&nbsp;Nome</span></div>
			<div class="item-inner">
			  <div class="item-input">
				<input type="text" onchange="modprenot('.$IDcliente.',this,40,11,0);"  style="font-size:15px;" value="'.$nome.'" placeholder="Nome">
			  </div>
			</div>
		  </div>
		</li>
		
		
		<li>
		  <div class="item-content">
			<div class="item-media "><i class="icon f7-icons">email</i> <span class="spancampo">&nbsp;Email</span></div>
			<div class="item-inner">
			  <div class="item-input">
				<input type="email"   onchange="modprenot('.$IDcliente.',this,56,11,0);"  style="font-size:15px;" value="'.$email.'" placeholder="E-mail">
			  </div>
			</div>
		  </div>
		</li>
		
		
		<li>
		  <div class="item-content">
			<div class="item-media "><i class="icon f7-icons">phone</i><span class="spancampo">&nbsp;Cellulare&nbsp;</span></div>
			<div class="item-inner">
			  <div class="item-input">
			  	<select  onchange="modprenot('.$IDcliente.',this,157,11,0);" style="width:70px; height:35px; font-size:13px;  color:#444; display:inline-block;">'.generaprefisso($prefissocell).'</select>
			  
				<input type="tel" style="width:50%; display:inline-block; font-size:15px; margin-left:5px; float:right;"  value="'.$cell.'"  onchange="modprenot('.$IDcliente.',this,153,11,0);" placeholder="Cellulare">
			  </div>
			</div>
		  </div>
		</li>
		
		<li>
		  <div class="item-content">
			<div class="item-media "><i class="icon f7-icons">phone</i><span class="spancampo">&nbsp;Telefono&nbsp;</span></div>
			<div class="item-inner">
			  <div class="item-input">
				
				<select  onchange="modprenot('.$IDcliente.',this,156,11,0);" style="width:70px; font-size:13px; height:35px;  color:#444; display:inline-block;">'.generaprefisso($prefissotel).'</select>
				<input type="tel" style=" width:50%; display:inline-block; font-size:15px; margin-left:5px;float:right;"  value="'.$tel.'"  onchange="modprenot('.$IDcliente.',this,57,11,0);" placeholder="Telefono">
			  </div>
			</div>
		  </div>
		</li>
	</ul></form>
	
	
	<div class="titleb" style="margin-left:15px;">Note</div>
		<div class="list-block">
		  <ul class="primadopo">
			<li >
			 <div class="item-content" style="height:100%;">
			  <div class="item-inner" style="width:100%;height:100%;">
				  <div class="item-input">
            		<textarea style="height:90px;" class="textareapren" placeholder="Note" onchange="modprenot('.$IDcliente.',this,70,11,0);" >'.$note.'</textarea>
        	  </div>
			 </div>
			 </div>
			</li>
			</ul></div>
    <div class="titleb" style="margin-left:15px;">Note al Ristorante</div>
	<div class="list-block">
		  <ul class="primadopo">
			<li >
			 <div class="item-content" style="height:100%;">
			  <div class="item-inner" style="width:100%;height:100%;">
				  <div class="item-input">
            		<textarea style="height:90px;" class="textareapren"  onchange="modprenot('.$IDcliente.',this,137,11,0);" placeholder="Note al Ristorante">'.$noteristo.'</textarea>
        	  </div>
			 </div>
			 </div>
			</li>
			</ul></div>
	
		
		
		
		
	
	
	';
}


if(!isset($inc)){
	echo $testo;
}
			 
?>			 
			 