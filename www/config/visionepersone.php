<?php

header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');

$IDstruttura=$_SESSION['IDstruttura'];

$ID=$_POST['ID'];
$IDarr=explode(',',$_POST['ID']);
$clienti=$_POST['IDclienti'];
$IDclientiarr=explode(',',$clienti);

$testo='
<input type="text" id="ID" value="'.$ID.'">
<input type="text" id="clienti" value="'.$clienti.'">
';


foreach($IDarr as $IDpren){
	//se il cliente id sta nell'aray clienti che passa allora lo seleziono
	
	$testo.='
	<div class="titleb content-block-title">'.estrainome($IDpren).'</div>
	<div class="list-block">
      <ul>';
	  
	$checked='';
	$query="SELECT ID,nome,IDcliente FROM infopren WHERE IDpren='$IDpren' AND pers='1' ";
	$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
		while($row=mysqli_fetch_row($result)){
			$tipologiacli=$row['1'];
			$checked="";
			if (in_array($row['0'], $IDclientiarr)) {
             	$checked="checked";
            }
			
			$testo.='<li>
							  <label class="label-checkbox item-content" >
								<input type="checkbox" class="checkboxpersona" name="my-checkbox" '.$checked.' id="'.$row['0'].'">
								<div class="item-media">
								  <i class="icon icon-form-checkbox"></i>
								</div>	
								<div class="item-inner">
								  <div class="item-title">'.estrainomecli($row['0']).'</div>
								  <div class="item-after">'.$tipologiacli.'</div>
								  <input type="hidden" id="" >
								</div>
							  </label>
							</li>
	';
			
			
		}
		
	}	
	

	$testo.=' </ul></div>';	
}

$link='<a href="#" class="close-picker" onclick="vedicheckbox(1);chiudimodal();">Conferma</a>';

?>
<div class="picker-modal smart-select-picker" id="popoverord">
      <div class="toolbar">
        <div class="toolbar-inner">
          <div class="left"></div>
          <div class="right"><?php echo $link ?></div>
        </div>
      </div>
      <div class="picker-modal-inner">
		  <div class="page-content" style="background: white;">
		
					<?php 
						echo $testo;
					?>
			
		  </div>
      </div>
</div>
