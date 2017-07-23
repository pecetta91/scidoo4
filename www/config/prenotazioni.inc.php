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
		
		
		$val=$ricerca;
		
		$ricerca=' AND (';
		
		$query="SELECT GROUP_CONCAT(p.IDv SEPARATOR ',') FROM prenotazioni as p,appartamenti as a WHERE a.ID=p.app  AND a.nome LIKE '$val%' LIMIT 30";
		$result=mysqli_query($link2,$query);
		if(mysqli_num_rows($result)>0){
			$row=mysqli_fetch_row($result);
			if(strlen($row['0'])>0){
				$ricerca.="IDv IN(".$row['0'].") ";
			}
		}
		if(strlen($ricerca)>10){
			$ricerca.=" OR (ID ='$val' OR note LIKE '%$val%'  ";
		}else{
			$ricerca.=" (ID ='$val' OR note LIKE '%$val%'  ";
		}
		
		//$nome=expricerca($val);
		
		$query="SELECT ID FROM schedine WHERE  (CONCAT(nome,' ',cognome) LIKE '%$val%' OR tel LIKE '%$val' OR mail LIKE '%$val%') AND IDstr='$IDstruttura'";
		$result=mysqli_query($link2,$query);

		if(mysqli_num_rows($result)>0){
			$group='';
			while($row=mysqli_fetch_row($result)){
				$group.=$row['0'].',';
			}
			$group=substr($group, 0, strlen($group)-1); 
			$query="SELECT GROUP_CONCAT(IDpren SEPARATOR ',') FROM infopren WHERE IDcliente IN ($group) GROUP BY IDstr";
			$result=mysqli_query($link2,$query);
			$row=mysqli_fetch_row($result);
			$group2=$row['0'];
			if(strlen($group2)>0){
				$ricerca.="OR IDv IN($group2)";
			}
		}
		
	$ricerca.=")) " ;
	
		$query="SELECT IDv,ID,time FROM prenotazioni WHERE  IDstruttura='$IDstruttura'  $ricerca";

				   
	}else{
		$query="SELECT IDv,ID,time FROM prenotazioni WHERE  IDstruttura='$IDstruttura' ORDER BY IDv DESC LIMIT 25";
	}
	
	
	$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
		while($row=mysqli_fetch_row($result)){
			$note='';
			$tel='';
			$email='';
			$testo.='<li><a href="#" onclick="navigation(3,'."'".$row['0']."'".')"" class="item-content item-link" >
							<div class="item-inner">
							  <div class="item-title" style="line-height:14px;">'.estrainome($row['0']).'<br><span style="font-size:11px; color:#777;">'.estrainomeapp($row['0']).'</span></div>
							  
							</div></a>
						</li>';		
		}
	}
	   
	   
	   
	   
	   
      $testo.='</ul>
    </div>
	
	
	';
	echo $testo;	

?>