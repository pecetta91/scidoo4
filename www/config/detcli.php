<?php

header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');
include('../../config/funzionilingua.php');

$IDutente=$_SESSION['ID'];
$IDstruttura=$_SESSION['IDstruttura'];


$IDcliente=strip_tags($_GET['ID']);


$query3="SELECT nome,cognome,tel,mail,datanas,sesso,note,noteristo FROM schedine WHERE ID='$IDcliente' LIMIT 1";
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


$testo='
<div class="navbar" style="background:#32ae6c;color:#fff; height:50px;">
               <div class="navbar-inner" style="height:45px;">
                  <div class="left" align="center">
				  </div>
                  <div class="center" style="line-height:12px;">
				  
				  <span style="font-size:15px;">Dettaglio</span><br>
				  <span style="font-size:12px; font-weight:100;">'.$nome.' '.$cognome.'</span>
				  
				  </div>
                  <div class="right" >
						<a href="#" onclick="myApp.closePanel();"><i class="icon f7-icons" style="color:#fff;  font-size:30px; ">check</i></a>
				  </div>
               </div>
            </div>


';
		
		
	$testo.='
	
	
	<div class="list-block">
  <ul>
    <!-- Text inputs -->
    <li>
      <div class="item-content">
        <div class="item-media mediaright"><i class="icon f7-icons">person</i></div>
        <div class="item-inner">
          <div class="item-title label">Nome</div>
          <div class="item-input">
            <input type="text" style="font-size:13px;" value="'.$nome.'" onchange="modprenot('.$IDcliente.',this,40,11,0);" placeholder="Nome Cliente">
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
            <input type="text" style="font-size:13px;" value="'.$cognome.'" onchange="modprenot('.$IDcliente.',this,41,11,0);" placeholder="Cognome Cliente">
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
            <input type="email" style="font-size:13px;"  value="'.$email.'" onchange="modprenot('.$IDcliente.',this,56,11,0);" placeholder="E-mail">
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
            <input type="tel" style="font-size:13px;"  value="'.$tel.'" onchange="modprenot('.$IDcliente.',this,57,11,0);" placeholder="Numero di Telefono">
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
            <select style="font-size:13px;" onchange="modprenot('.$IDcliente.',this,43,11,0);">';
			
			
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
            <input type="date" style="font-size:13px;" value="'.$datanas.'" onchange="modprenot('.$IDcliente.',this,42,11,0);" placeholder="Data di Nascita" >
          </div>
        </div>
      </div>
    </li>
    
    
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
</div>
						
						
					
	
	
	<br><br><br><br><br>';
	
	echo $testo;
?>