<?php


	header('Access-Control-Allow-Origin: *');
	include('../../config/connecti.php');
	include('../../config/funzioni.php');
	include('../../config/funzionilingua.php');


$IDstruttura=$_SESSION['IDstruttura'];
$idcheckbox=$_POST['id'];
$npers=$_POST['npers'];
$idcheckbox=substr_replace($idcheckbox,'',-1);
$npers=substr_replace($npers,'',-1);

$testo='<input type="hidden" id="qls" value="'.$idcheckbox.'" >';

foreach(explode(',', $idcheckbox) as $button){
$query="SELECT a.nome,p.IDv,p.time FROM appartamenti as a,prenotazioni as p WHERE p.IDv IN($button) AND p.IDstruttura='$IDstruttura' AND p.app=a.ID ";
						$result=mysqli_query($link2,$query);
						$nomeapp='';
	                    $nomepren='';
	                    $time='';
						if(mysqli_num_rows($result)>0){
							while($row=mysqli_fetch_row($result)){
								$nomepren.=estrainome($row['1']).', ';
								$nomeapp.=''.$row['0'].' , ';
								$time=$row['2'];
							}
							$nomepren=substr($nomepren, 0, strlen($nomepren)-2); 
							$nomeapp=substr($nomeapp, 0, strlen($nomeapp)-3); 
						}else{
							$nomeapp="";
							$nomepren="";
						}
	
	
	

	
	
	
	$testo.='
	<li>
	<div class="item-content item-link" onclick="visionepersone('.$button.');">
		 <div class="item-media" id="timepren" style="font-size:13px">'.date('Y-m-d',$time).'</div>
		 <div class="item-inner">
		   <div class="item-title">
		     <span id="nomepren">'.$nomepren.'</span><br>
		     <span id="nomeal">'.$nomeapp.'</span>
			 <span><i class="material-icons" style="font-size:15px; color:#1649b1;">person</i>'.$npers.'</span>
		   </div>
		</div>
	</div>
	</li>
	';
	/*
	
	$query1="SELECT ID,IDcliente,IDrest,nome FROM infopren WHERE IDpren='$button' AND pers='1' AND IDstr='$IDstruttura'";
    $result1=mysqli_query($link2,$query1);
    $row1=mysqli_fetch_row($result1);
	$nomep=estrainomecli($row1['0']);
	$testo.='
							<li>
							  <label class="label-checkbox item-content">
								<input type="checkbox" name="my-checkbox" disabled="disabled" id="'.$row1['0'].'">
								<div class="item-media">
								  <i class="icon icon-form-checkbox"></i>
								</div>
								<div class="item-inner">
								  <div class="item-title">'.$nomep.'</div>
								</div>
							  </label>
							</li>';
	*/
	

}

//<a href="#" onclick="prova2();" class="button button-round">Reset</a>
?>
<div class="picker-modal smart-select-picker" id="popoverord">
      <div class="toolbar">
        <div class="toolbar-inner">
          <div class="left"></div>
          <div class="right"><a href="#" class="close-picker">Chiudi</a></div>
        </div>
      </div>
      <div class="picker-modal-inner">
		  <div class="page-content" style="background: white">
			<div class="list-block" style="margin-top:0px;">
				<ul>
					<?php 
						echo $testo;
					?>
			   </ul>
		   </div>
		   
		  </div>
      </div>
</div>
