<?php
$ricerca='';
if(!isset($inc)){
	header('Access-Control-Allow-Origin: *');
	include('../../config/connecti.php');
	include('../../config/funzioni.php');
	include('../../config/funzionilingua.php');
	
	$IDutente=intval($_SESSION['ID']);
	$IDstruttura=intval($_SESSION['IDstruttura']);
	
	
	
	if(isset($_GET['dato0'])){
		if($_GET['dato0']!='0'){
			$ricerca=$_GET['dato0'];
		}else{
			$ricerca='';
		}
	}
}


	
	
	$testo='

	  <div class="list-block">
      <ul>';
	  
	   
    if(strlen($ricerca)>0){
		$ricerca=expricerca($ricerca);
		
		$query="SELECT ID,nome,cognome,mail,tel,note,noteristo,MATCH(nome,cognome,mail) AGAINST('".$ricerca."') as score FROM schedine WHERE MATCH(nome,cognome,tel,mail,note,noteristo) AGAINST('".$ricerca."' IN BOOLEAN MODE) AND IDstr='$IDstruttura'  ORDER BY score DESC LIMIT 25";
				   
	}else{
		$query="SELECT ID,nome,cognome,mail,tel,note,noteristo FROM schedine WHERE IDstr='$IDstruttura' ORDER BY ID DESC LIMIT 25 ";	
	}
	
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
						<li><a href="#" onclick="detcli('.$row['0'].')" class="item-content item-link" >
							<div class="item-inner" >
							  <div class="item-title" style="line-height:13px;"><b style="font-size:14px;">'.$row['1'].' '.$row['2'].'</b>
							  <br>
							 <span style="font-size:12px;color:#555;">'.$tel.'; '.$email.'</span>
							  
							  '.$note.'</div>
							</div></a>
						 
						</li>
						';
			
			
		
		
		}
	}
	   
	   
	   
	   
	   
      $testo.='</ul>
    </div>
	
	
	';
	echo $testo;	

?>