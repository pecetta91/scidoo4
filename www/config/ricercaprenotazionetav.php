<?php


	header('Access-Control-Allow-Origin: *');
	include('../../config/connecti.php');
	include('../../config/funzioni.php');
	include('../../config/funzionilingua.php');

$IDstruttura=$_SESSION['IDstruttura'];

$time=$_POST['time'];
$data=date('Y-m-d',$time);

$IDsottotip=$_POST['IDsottotip'];
//prendere prenotazioni con checkout il giorno stesso


$arr=array();
$arrtime=array();
$testo='';

$data=date('Y-m-d',$time);

$IDprens=getprenot($time,$IDstruttura,3);



$query="SELECT IDv,FROM_UNIXTIME(time,'%Y-%m-%d'),time FROM prenotazioni WHERE IDv IN($IDprens) ORDER BY time";
$result=mysqli_query($link2,$query);
if(mysqli_num_rows($result)>0){
 while($row=mysqli_fetch_row($result)){
	 $IDpren=$row['0'];
	 
	 
	 
		 
	 
	 
	 
	$IDclienti='';
	 
	$IDprenextraIN=0;
	$query2="SELECT GROUP_CONCAT(p2.IDinfop SEPARATOR ',') FROM prenextra as p,prenextra2 as p2 WHERE p.IDpren='$IDpren' AND FROM_UNIXTIME(p.time,'%Y-%m-%d')='$data' AND p.sottotip='$IDsottotip' AND p.ID=p2.IDprenextra GROUP BY p.IDpren";
	$result2=mysqli_query($link2,$query2);
	if(mysqli_num_rows($result2)>0){
		$row2=mysqli_fetch_row($result2);
		$IDprenextraIN=$row2['0'];
	}
	 
	 
	$query2="SELECT GROUP_CONCAT(ID SEPARATOR ',') FROM infopren WHERE IDpren='$IDpren' AND pers='1' AND IDstr='$IDstruttura' AND ID NOT IN($IDprenextraIN)  GROUP BY IDstr";
	$result2=mysqli_query($link2,$query2);
	if(mysqli_num_rows($result2)>0){
		$row2=mysqli_fetch_row($result2);
	  	 $IDclienti.=$row2['0'].',';
	 
		
		
		$arrtime[$row['1']]=$row['2'];
	 
	 
	 $query1="SELECT a.nome,p.IDv,COUNT(i.pers) FROM appartamenti as a,prenotazioni as p,infopren as i WHERE p.IDv='$IDpren' AND p.app=a.ID AND p.IDv=i.IDpren
	 LIMIT 1";
	 $result1=mysqli_query($link2,$query1);
	 $nomeapp='';
	  $npers='';
	 if(mysqli_num_rows($result1)>0){
							while($row1=mysqli_fetch_row($result1)){
								$nomeapp.=''.$row1['0'].' , ';	
								$npers=$row1['2'];
							}
							$nomeapp=substr($nomeapp, 0, strlen($nomeapp)-3); 
		                 }else{
							$nomeapp="";
						
						}
       
             
				$arr[$row['1']][$row['0']]='
				
				 <li>
				 <label class="label-checkbox item-content">
					<input type="checkbox" class="ricercacheckbox" alt="'.$IDclienti.'"  id="'.$row['0'].'">
					  <div class="item-media">
						  <i class="icon icon-form-checkbox"></i>
					  </div>
				
          <div class="item-inner">
            <div class="item-title">'.estrainome($IDpren).'<br>
			<span style="font-size:10px;color:#666;">'.$nomeapp.'</span>
			</div>
            <div class="item-after"><strong>'.$npers.'</strong><i class="material-icons" style="font-size:15px; color:#1649b1;">person</i></div>
			
          </div>
		  </label>
        </li>';
	}


	 
 }//<input type="hidden" name="npers" value="'.$npers.'">
}
	 



if(empty($arr)){
	 $testo.='<div class="list-block">
      <ul>
	 <li>
		 <a href="#" onclick="prenotaztavolo(3);chiudimodal();"class="item-content item-link" >
							<div class="item-inner" >
							  <div class="item-title" style="line-height:13px;font-size:15px;"><b>Inserire nuovo cliente</b>
							  </div>
							</div>
		</a>
						</li>
						</ul></div>';
}

foreach($arr as $key => $pos){
	$txt=implode('',$pos);
	
	$testo.='
	<div class="titleb content-block-title">'.dataita($arrtime[$key]).'</div>
	<div class="list-block">
      <ul>'.$txt.'</ul></div>';
	
}
		


$testo.='';

?>
	<div class="picker-modal" id="popoverord" >
		  <div class="toolbar">
			<div class="toolbar-inner">
				<div class="left"></div>
			  <div class="right"><a href="#" class="" onclick="prenotaztavolointo(1);myApp.closeModal();">Avanti</a></div>
			</div>
		  </div>
		  <div class="picker-modal-inner ">
		 <div class="page-content" id="prova" style="background-color: white">
		 <?php
	 echo $testo;
			 ?>
			  </div>
		  </div>
	</div>



