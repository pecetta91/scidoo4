<?php

header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');
include('../../config/funzionilingua.php');

$IDutente=$_SESSION['ID'];
$IDstruttura=$_SESSION['IDstruttura'];

$IDappunto=strip_tags($_GET['ID']);
$testo='

<div class="pages navbar-fixed">
  <div  class="page">

  <div class="navbar">
               <div class="navbar-inner">
			   		<div class="left"> <a href="#" class="link" onclick="backexplode(3)">
						<i class="material-icons" style="font-size:40px;">chevron_left</i>
						</a></div>
                  <div class="center titolonav">Appunto</div>
                  <div class="right"></div>
               </div>
            </div>
			
		<div class="page-content" >
			
        <div class="content-block" >	
			

';
if($IDappunto==0){
	
	$testo.='
	
	
		<div class="list-block">
		  <ul>
			
			<li class="item-content">
			  <div class="item-inner" style="width:100%;">
				<div class="item-title" style="width:100%;">
					<textarea  id="noteappunto" style="width:100%;padding-left:4px; height:110px;font-size:14px;" placeholder="Appunto"></textarea>
				
				</div>
			  </div>
			</li>
			
			
			
			<li>
		
		  <a href="#" class="item-link smart-select">
			<select name="arg" id="argrec">
			<option value="0" >Seleziona Argomento</option>
			';
			
			$query="SELECT argomento FROM appunti WHERE IDstr='$IDstruttura' AND argomento!='0' GROUP BY UPPER(argomento)";
				
				
				$result=mysqli_query($link2,$query);
				$selected=0;
				$j=0;
				while($row=mysqli_fetch_row($result)){
					if(strlen($row['0'])>0){
						$testo.='<option value="'.$row['0'].'" >'.$row['0'].'</option>';
					}
				}
				
			$testo.='</select>
			<div class="item-content">
			  <div class="item-inner">
				<div class="item-title">Argomento</div>
				<div class="item-after"></div>
			  </div>
			</div>
		  </a>
		</li>
			
			
			
			<li class="item-content">
			  <div class="item-inner" style="width:100%;">
				<div class="item-title" style="width:100%;">
					<input type="text" id="argnew" style="width:100%;font-size:14px; " placeholder="Oppure inseriscine uno nuovo qui">
				</div>
			  </div>
			</li>
		<li>
		
      <a href="#" class="item-link smart-select">
        <select name="dest" id="destinatari" multiple>';
		
		 $query="SELECT IDuser,nome FROM personale WHERE IDstr='$IDstruttura' AND attivo='1' AND IDuser>'0'";
		
			$result=mysqli_query($link2,$query);
			$selected=0;
			$j=0;
			while($row=mysqli_fetch_row($result)){
				$testo.='<option value="'.$row['0'].'">'.$row['1'].'</option>';
			}
			
        $testo.='</select>
        <div class="item-content">
          <div class="item-inner">
            <div class="item-title">Destinatari</div>
            <div class="item-after"></div>
          </div>
        </div>
      </a>
    </li>
	</ul>
</div>';
		
			/*
		$testo.='	
		<div class="content-block-title" style="color:#324cae;">Destinatari Appunto</div>
		<div class="list-block">
		  <ul>	
		  ';
		  
		  
		  
		 	 $query="SELECT IDuser,nome FROM personale WHERE IDstr='$IDstruttura' AND attivo='1' AND IDuser>'0'";
		
			$result=mysqli_query($link2,$query);
			$selected=0;
			$j=0;
			while($row=mysqli_fetch_row($result)){
				
				$testo.='
					 <li>
					  <label class="label-checkbox item-content">
						<!-- Checked by default -->
						<input type="checkbox" name="dests" value="'.$row['0'].'">
						<div class="item-media">
						  <i class="icon icon-form-checkbox"></i>
						</div>
						<div class="item-inner">
						  <div class="item-title">'.$row['1'].'</div>
						</div>
					  </label>
					</li>
					
					
					';
				}
		  
		  $testo.='
		  
		  </ul></div>*/
			
			
		$testo.='	<a href="#" class="button button-raised button-fill color-indigo" style="font-size:16px; width:75%; margin:auto;"   onclick="salvaappunto();">Salva Appunto</a>
			
			';
				
				
			

		
	
		
	
	

}else{

}

			
					
	$testo.='
	<br><br><br><br><br>
	
	</div></div></div>
	';
	
	echo $testo;
?>