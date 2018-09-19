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
	  
	
	
	
	
	$ricercaquery="";
	$order='';
    if(strlen($ricerca)>0){
		
		
		$val=$ricerca;
		
		
		
		
		
		
		
		
		/*
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
		}*/
		
		
		$IDric=0;
		$qadd='';
		if(strlen($ricerca)>2){
			$arr=ricercacliente($ricerca,$IDstruttura);

			
			
			if(!empty($arr)){
				$IDric=implode(',',$arr);
			}

		}
		$order='';

		if(strlen($IDric)>1){
			$group2='0';
			$query="SELECT GROUP_CONCAT(IDpren SEPARATOR ',') FROM infopren WHERE IDcliente IN ($IDric) GROUP BY IDstr ORDER BY POSITION(IDcliente IN '($IDric)')  LIMIT 30";
			$result=mysqli_query($link2,$query);
			if(mysqli_num_rows($result)>0){
				$row=mysqli_fetch_row($result);
				$group2=$row['0'];
				$ricercaquery.="AND p.IDv IN($group2)";
				$order=" ORDER BY POSITION(p.IDv IN '($group2)') ";

			}else{
				$ricercaquery.="AND p.IDv IN(0)";
			}
		}else{
			$ricercaquery.="AND p.IDv IN(0)";
		}
		
	}
	
	$query="SELECT p.IDv,p.ID,p.time,p.checkout,p.gg FROM prenotazioni as p WHERE  p.IDstruttura='$IDstruttura' $ricercaquery $order LIMIT 25";
	$result=mysqli_query($link2,$query);

	if(mysqli_num_rows($result)>0){
		while($row=mysqli_fetch_row($result)){
			$note='';
			$tel='';
			$email='';
			//<br><span style="font-size:11px; color:#777;">'.estrainomeapp($row['0']).'</span>
			
			$after='';
			if($row['4']==0){
				$after='<div class="dd">'.date('d',$row['2']).'</div>';
			}else{
				$after='
					<div class="dd">'.date('d',$row['2']).'</div>
			
				
				';
			}
			
			
			
			
			$testo.='<li><a href="#" onclick="navigation(3,'."'".$row['0']."'".')" class="item-content item-link" >
							<div class="item-inner">
							  <div class="item-title">'.estrainome($row['0']).'</div>
							  <div class="item-after"><b>'.date('d/m/Y',$row['2']).'</b><br/><span>'.$row['4'].' '.txtnotti($row['4']).'</span></div>
							</div></a>
						</li>';		
		}
	}else{
		$testo.='<li><a href="#"  class="item-content item-link" >
							<div class="item-inner">
							  <div class="item-title">Nessun Risultato</div>
							</div></a>
						</li>';		
	}
	   
	   
	   
	   
	   
      $testo.='</ul>
    </div>
	
	
	';
	echo $testo;	

?>