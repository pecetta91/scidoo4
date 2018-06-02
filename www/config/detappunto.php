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
	/*<div class="item-title" style="width:100%;">
					<textarea  id="noteappunto" style="width:100%;padding-left:4px; height:110px;font-size:14px;" placeholder="Appunto"></textarea>
				
				</div>*/

	
	$testo.='
	
	
		<div class="list-block">
		  <ul>
			<li>
			 <div class="item-content" style="height:100%;">
			  <div class="item-inner" style="width:100%;height:100%;">
				  <div class="item-input">
            		<textarea id="noteappunto" style="height:150px" placeholder="Appunto"></textarea>
        	  </div>
			 </div>
			 </div>
			</li>
			</ul></div>
		<div class="list-block"><ul>
			<li>
		
		  <a href="#" class="item-link smart-select" data-open-in="picker" pickerHeight="400px">
			<select name="arg" id="argrec" onchange="argomentonuov()">
			<option value=""></option>
			<option value="0" >Nuovo Argomento</option>
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
				
			$testo.='
			</select>
			<div class="item-content">
			  <div class="item-inner">
				<div class="item-title">Argomento</div>
				<div class="item-after" id="newval"></div>
			  </div>
			</div>
		  </a>
		</li>
			
			
			<input type="hidden" id="argnew">
				
		<li>
		
      <a href="#" class="item-link smart-select " data-open-in="picker" pickerHeight="400px" >
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
            <div class="item-after" style="font-size:13px"></div>
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
			
			
		$testo.='
		<div class="bottombarpren" style="background:#f1f1f1;z-index:999;" align="center">
			  <table style="width:100%; height:100%;cellpadding:0;cellspacing:0;">
			   <tbody><tr>
			   <td style="width:15%">
			   </td>
			     <td>
				  <button href="#" class="bottoneprezzo" onclick="salvaappunto();">Salva Appunto</button>
                 </td>
				 <td style="width:15%">
			   </td>
			   </tr>
			  </tbody></table>
			</div>
			
			';
				
				
			

		
	
		
	
	

}else{

}

			
					
	$testo.='
	<br><br><br><br><br>
	
	</div></div></div>
	';
	
	echo $testo;
?>