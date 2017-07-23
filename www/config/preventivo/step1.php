<?php
header('Access-Control-Allow-Origin: *');
include('../../../config/connecti.php');
include('../../../config/funzioni.php');
include('../../../config/funzionilingua.php');

$IDutente=$_SESSION['ID'];
$IDstruttura=$_SESSION['IDstruttura'];

if($_SESSION['appnew']!=0){
	$gg=1;
}else{
	$gg=$_GET['dato0'];
}

$query="SELECT checkin,orai FROM strutture WHERE ID='$IDstruttura' LIMIT 1";
	$result=mysqli_query($link2,$query);
	$row=mysqli_fetch_row($result);
	$check=$row['0'];
	$orai=$row['1'];
	if($gg>0){
		$cll=$check;
	}else{
		$cll=$orai;
	}
	$ora=secondinv($cll);

$tipo=$_GET['dato1'];
if(!is_numeric($tipo)){
	$tipo==1;
}

$testo='';

if($tipo==1){

$testo.=' 


';

if($_SESSION['timenew']==0){
	$testo.='
		<input type="hidden" id="data" value="">
		<div class="content-block">
		  <div style="padding:0; width:auto; " class="content-block-inner">
			<div id="ks-calendar-inline-container" style="width:auto"></div>
		  </div>
		</div>  
	';
}


}else{
	
	
if($_SESSION['timenew']!=0){
	$testo.='<input type="hidden" id="prenotveloce" value="0">
		<input type="hidden" id="prenotvelocetime" value="'.date('Y-m-d',$_SESSION['timenew']).'">
		
	';
}	



$testo.='
	<br>
	<div class="list-block">
      <ul>';
	  	  
	  if($_SESSION['appnew']!=0){
			$query2="SELECT nome FROM appartamenti WHERE ID='".$_SESSION['appnew']."' LIMIT 1";
			$result2=mysqli_query($link2,$query2);
			$row2=mysqli_fetch_row($result2);
			//$txtrichiesta=$row2['0'].'<br>'.dataita2($_SESSION['timenew']);
			//
			
			$testo.='
			<li>
			  <div class="item-content">
				<div class="item-inner"> 
				  <div class="item-title">Alloggio</div>
				  <div class="item-after">'.$row2['0'].'</div>
				</div>
			  </div>
			</li>
			<li>
			  <div class="item-content">
				<div class="item-inner"> 
				  <div class="item-title">Data di Arrivo</div>
				  <div class="item-after">'.dataita2($_SESSION['timenew']).'</div>
				</div>
			  </div>
			</li>
			
			';
			
			
	}
	  
	  
	  
	  
	if($_SESSION['appnew']!=0){
		 $testo.='
		 
		 <li>
		  <a href="#" class="item-link  smart-select" data-open-in="page" data-back-on-select="true" data-searchbar="false">
			<select  id="notti" onChange="dispo2();">  '.generanotti(1,1,24).'</select>
			<div class="item-content">
			  <div class="item-inner">
				<div class="item-title">Notti</div>
				<div class="item-after">1 Notte</div>
			  </div>
			</div>
		  </a>
		</li>
		 
		 
		 
		 
		 ';
	}else{
		$testo.='<input type="hidden" id="notti" value="0">';
	}
	  	  
      $testo.=' 
	  	   
	  
	  	<li>
		  <a href="#" class="item-link  smart-select" data-open-in="page" data-back-on-select="true" data-searchbar="false">
			<select  id="orario"> '.generaorario($ora,8,24,60).'</select>
			<div class="item-content">
			  <div class="item-inner">
				<div class="item-title">Orario</div>
				<div class="item-after">'.$ora.'</div>
			  </div>
			</div>
		  </a>
		</li>
	  
	  
	  </ul></div>
	
<div class="content-block-title" style=" margin-top:-15px;"><b style="color:#ec7312;">Ospiti</b></div>
<div class="list-block">
  <ul>';
  
  /*  <li>
          <div class="item-content">
            <div class="item-inner"> 
              <div class="item-title">Orario Arrivo</div>
              <div class="item-after"><select id="orario">'.generaorario($ora,6,24,60).'</select></div>
            </div>
          </div>
        </li>*/
  
  $query5="SELECT ID,restrizione,tiporest,personale FROM tiporestr WHERE IDstr='$IDstruttura' AND limite='0' AND personale='1' ORDER BY ordine";
	$result5=mysqli_query($link2,$query5);
	while($row5=mysqli_fetch_row($result5)){
			
			$testo.='<li>
				  <a href="#" class="item-link  smart-select" data-open-in="page" data-back-on-select="true" data-searchbar="false">
					<select onChange="dispo2();" id="restriz'.$row5['0'].'" lang="1" alt="'.$row5['0'].'" class="selectdx inputrestr" >
			  '.generanum(0,100).'</select>
					<div class="item-content">
					  <div class="item-inner">
						<div class="item-title">'.$row5['1'].'</div>
						<div class="item-after">N.0</div>
					  </div>
					</div>
				  </a>
				</li>
			
			
			
			
				
				';
				
				//<select class="inputrestr" dir="1" alt="1" id="restriz'.$row5['0'].'">'.generanum(0,10,0).'</select>
	
		
	}
	
	
	
  
  
  $testo.='</ul></div>
  
  
  
  
  
<div class="list-block">
  <ul>
  
  
  ';
  
	$query5="SELECT ID,restrizione,tiporest,personale FROM tiporestr WHERE IDstr='$IDstruttura' AND limite='0' AND personale='0'";
	$result5=mysqli_query($link2,$query5);
	while($row5=mysqli_fetch_row($result5)){
			$testo.='
				<li>
				  <a href="#" class="item-link  smart-select" data-open-in="page" data-back-on-select="true" data-searchbar="false">
					<select id="restriz'.$row5['0'].'" alt="'.$row5['0'].'" class="selectdx inputrestr" >
			  '.generanum(0,10).'</select>
					<div class="item-content">
					  <div class="item-inner">
						<div class="item-title">'.$row5['1'].'</div>
						<div class="item-after">N.0</div>
					  </div>
					</div>
				  </a>
				</li>
				';
	}
	$testo.='</ul></div>
	
	
 
';

}
	
echo $testo.'<br><br><br><br><br>';
			 