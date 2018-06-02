<?php

if(!isset($inc)){
	header('Access-Control-Allow-Origin: *');
	include('../../config/connecti.php');
	include('../../config/funzioni.php');
	include('../../config/funzionilingua.php');
	
	$IDutente=intval($_SESSION['ID']);
	$IDstruttura=intval($_SESSION['IDstruttura']);
	

}


$IDinfop=intval($_GET['dato0']);
$IDresend=$IDinfop;

$nomecliente='Nuovo Cliente';


	
$query="SELECT IDcliente FROM infopren WHERE ID='$IDinfop' AND IDstr='$IDstruttura' LIMIT 1";
	$result=mysqli_query($link2,$query);
	$row=mysqli_fetch_row($result);
	$IDcliente=$row['0'];
	





	
	if($IDcliente!=0){
		$query3="SELECT nome,cognome,tel,mail,datanas,sesso,note,noteristo,cell,prefissotel,prefissocell FROM schedine WHERE ID='$IDcliente' LIMIT 1";
		$result3=mysqli_query($link2,$query3);
		$row3=mysqli_fetch_row($result3);
		
		$nome=$row3['0'];
		$cognome=$row3['1'];
		$tel=$row3['2'];
		$email=$row3['3'];
		$datanas=$row3['4'];
		$sesso=$row3['5'];
		$note=$row3['6'];
		$noteristo=$row3['7'];
		$cell=$row3['8'];
		$prefissotel=$row3['9'];
		$prefissocell=$row3['10'];
		
		$nomecliente=$nome.' '.$cognome;
		
	}else{
		$nome='';
		$cognome='';
		$tel='';
		$cell='';
		$email='';
		$datanas='';
		$sesso='';
		$note='';
		$noteristo='';
		
		
		$query="SELECT prefisso FROM strutture WHERE ID='$IDstruttura' LIMIT 1";
		$result=mysqli_query($link2,$query);
		$row=mysqli_fetch_row($result);
		$prefisso=$row['0'];
		
			
		$query="INSERT INTO schedine VALUES(NULL,'','','0000-00-00','0','','','0','','','','','0000-00-00','$IDstruttura','','$prefisso','','$prefisso','','','')";
		$result=mysqli_query($link2,$query);
		$IDcliente=mysqli_insert_id($link2);
		
		$query="UPDATE infopren SET IDcliente='$IDcliente' WHERE ID='$IDinfop' LIMIT 1";
		$result=mysqli_query($link2,$query);
		
		
		
		
		$nomecliente='Nuovo Ospite';
		
		
	}




$testo='
<div data-page="detcliente" class="page"> 

			 <input type="hidden" id="IDinfopdet" value="'.$IDresend.'">
			 <div class="navbar">
				<div class="navbar-inner">
					<div class="left">
					 <a href="#" class="link icon-only" onClick="backexplode(6)" >
						<i class="f7-icons">arrow-left</i>
					</a>
					
					</div>
					<div class="center titolonav">'.$nomecliente.'</div>
					<div class="right"> <a href="#" onclick="ricercaclidetdiv('.$IDinfop.')" class="link icon-only" >
						<i class="f7-icons" style="font-size:20px;">search</i>
					</a></div>
				</div>
			</div>
			 
			 
            <div class="page-content">';
			
			if($IDcliente!=0){
				$testo.='
					<div class="content-block-title titleb">Dati ospite</div>
	
	<div class="list-block">
  <ul>
    <li>
      <div class="item-content">
        <div class="item-media mediaright"><i class="icon f7-icons">person</i></div>
        <div class="item-inner">
          <div class="item-title label">Nome</div>
          <div class="item-input">
            <input type="text" value="'.$nome.'" onchange="modprenot('.$IDcliente.',this,40,11,0);" placeholder="Nome">
          </div>
        </div>
      </div>
    </li>
	<li>
      <div class="item-content">
        <div class="item-media mediaright"><i class="icon f7-icons">person</i></div>
        <div class="item-inner">
          <div class="item-title label">Cognome</div>
          <div class="item-input">
            <input type="text"  value="'.$cognome.'" onchange="modprenot('.$IDcliente.',this,41,11,0);" placeholder="Cognome">
          </div>
        </div>
      </div>
    </li>
	
    <li>
      <div class="item-content">
        <div class="item-media mediaright"><i class="icon f7-icons">email</i></div>
        <div class="item-inner">
          <div class="item-title label">E-mail</div>
          <div class="item-input">
            <input type="email"   value="'.$email.'" onchange="modprenot('.$IDcliente.',this,56,11,0);" placeholder="E-mail">
          </div>
        </div>
      </div>
    </li>
	
	<li>
      <div class="item-content">
        <div class="item-media mediaright"><i class="icon f7-icons">phone</i></div>
        <div class="item-inner">
          <div class="item-title label">Telefono</div>
          <div class="item-input">
		  
		  	
				<select  onchange="modprenot('.$IDcliente.',this,156,11,0);" style="width:50px; font-size:11px; border:solid 1px #ccc; border-radius:4px; color:#444; direction:initial; display:inline-block;">'.generaprefisso($prefissotel).'</select>
				<input type="tel" style=" width:50%; display:inline-block;"  value="'.$tel.'"  onchange="modprenot('.$IDcliente.',this,57,11,0);" placeholder="Telefono">
		  
          </div>
        </div>
      </div>
    </li>
	
	<li>
      <div class="item-content">
        <div class="item-media mediaright"><i class="icon f7-icons">phone</i></div>
        <div class="item-inner">
          <div class="item-title label">Cellulare</div>
          <div class="item-input">
            <select  onchange="modprenot('.$IDcliente.',this,157,11,0);" style="width:50px; font-size:11px; border:solid 1px #ccc; border-radius:4px; color:#444; direction:initial; display:inline-block;">'.generaprefisso($prefissocell).'</select>
			  
				<input type="tel" style="width:50%; display:inline-block;"  value="'.$cell.'"  onchange="modprenot('.$IDcliente.',this,153,11,0);" placeholder="Cellulare">
          </div>
        </div>
      </div>
    </li>
	
	
    <!-- Select -->
    <li>
      <div class="item-content">
        <div class="item-media mediaright"><i class="icon f7-icons">persons</i></div>
        <div class="item-inner">
          <div class="item-title label">Sesso</div>
          <div class="item-input">
            <select  onchange="modprenot('.$IDcliente.',this,43,11,0);">';
			
			
			$arr1=array('Maschio','Femmina');
			$arr2=array('M','F');
			for($i=0;$i<2;$i++){
				$testo.='<option value="'.$arr2[$i].'"';
				if($arr2[$i]==$sesso)$testo.='selected="selected"';
				$testo.='>'.$arr1[$i].'</option>';
			}
			
			  
            $testo.='</select>
          </div>
        </div>
      </div>
    </li>
    <!-- Date -->
    <li>
      <div class="item-content">
        <div class="item-media mediaright"><i class="icon f7-icons">calendar</i></div>
        <div class="item-inner">
          <div class="item-title label">Data di Nascita</div>
          <div class="item-input">
            <input type="date"  value="'.$datanas.'" onchange="modprenot('.$IDcliente.',this,42,11,0);" placeholder="Data di Nascita" >
          </div>
        </div>
      </div>
    </li>
	</ul></div>
	
	
	<div class="titleb" style="margin-left:15px;">Note</div>
		<div class="list-block">
		  <ul>
			<li>
			 <div class="item-content" style="height:100%;">
			  <div class="item-inner" style="width:100%;height:100%;">
				  <div class="item-input">
            		<textarea onchange="modprenot('.$IDcliente.',this,70,11,0);" style="height:90px" placeholder="Note">'.$note.'</textarea>
        	  </div>
			 </div>
			 </div>
			</li>
			</ul></div>
    <div class="titleb" style="margin-left:15px;">Note al Ristorante</div>
	<div class="list-block">
		  <ul>
			<li>
			 <div class="item-content" style="height:100%;">
			  <div class="item-inner" style="width:100%;height:100%;">
				  <div class="item-input">
            		<textarea style="height:90px"  onchange="modprenot('.$IDcliente.',this,137,11,0);">'.$noteristo.'</textarea>
        	  </div>
			 </div>
			 </div>
			</li>
			</ul></div>
	
	
	
    
	';
	
				/*
    <!-- Textarea -->
    <li class="align-top">
      <div class="item-content">
        <div class="item-media mediaright"><i class="icon f7-icons">chat</i></div>
        <div class="item-inner">
          <div class="item-title label">Note</div>
          <div class="item-input">
            <textarea  class="resizable" style="font-size:13px;" onchange="modprenot('.$IDcliente.',this,70,11,0);" >'.$note.'</textarea>
          </div>
        </div>
      </div>
    </li>
	
	<!-- Textarea -->
    <li class="align-top">
      <div class="item-content">
        <div class="item-media mediaright"><i class="icon f7-icons">chat</i></div>
        <div class="item-inner">
          <div class="item-title label">Note Ristorante</div>
          <div class="item-input">
            <textarea  class="resizable" style="font-size:13px;" onchange="modprenot('.$IDcliente.',this,137,11,0);">'.$noteristo.'</textarea>
          </div>
        </div>
      </div>
    </li>
  </ul>
</div>*/
						
}else{
	
	/*$testo.='<br>
	<div style="width:100%; font-weight:600; text-align:left; font-size:15px;text-align:center;">RICERCA OSPITE</div><br>
	<form  data-search-list=".list-block-search" data-search-in=".item-title" class="searchbar searchbar-init" style="margin-top:35px;  width:100%; margin:auto;">
    <div class="searchbar-input"  >
      <input type="search" style="color:#333;" placeholder="Ricerca Cliente" onkeyup="ricercaclidet(30,this.value,'.$IDinfop.','."'contencli'".')"><a href="#" class="searchbar-clear"></a>
    </div><a href="#" class="searchbar-cancel">Cancel</a>
  </form>
  <div id="contencli">
  	
  </div><br><hr>
  
  <br><a href="#" class="button button-raised button-fill color-orange ripple-green" style="width:90%; padding:10px; height:50px; font-size:16px; font-weight:600; margin:auto;" onclick="modprenot(0,'.$IDinfop.',67,10,6);">NUOVO OSPITE</a>
	
	';
	*/
	
}
				
			
			
			
				
				
	$testo.='</div></div>';
	



			echo $testo;	

?>