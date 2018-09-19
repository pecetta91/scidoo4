<?php
if(!isset($inc)){
	header('Access-Control-Allow-Origin: *');
	include('../../config/connecti.php');
	include('../../config/funzioni.php');
	include('../../config/funzionilingua.php');
	$IDutente=intval($_SESSION['ID']);
	$IDstruttura=intval($_SESSION['IDstruttura']);
	$testo='';
}





$query="SELECT a.ID,a.nome,c.nome,c.ID  FROM appartamenti as a,categorie as c WHERE a.IDstruttura='$IDstruttura' AND a.categoria=c.ID ORDER BY c.ID,a.ordine ";
$result=mysqli_query($link2,$query);

if(mysqli_num_rows($result)>0){
	$IDcat=0;
	while($row=mysqli_fetch_row($result)){
		if($row['3']!=$IDcat){
			$style='';
			if($IDcat!=0){
				$testo.='</ul></div>';
			}else{
				$style='style="margin-top:0px;"';
			}
			$IDcat=$row['3'];
			
			$testo.='
			<div style="float:right;font-size:11px; margin-right:10px; color:#a03f3f;" onclick="modificaalloggio(0,'.$row['3'].')"><u>Aggiungi alloggio</u></div>
			<div class="content-block-title" '.$style.'>'.$row['2'].'</div>
				<div class="list-block">
				  <ul>';
			
		}
		
		
        $testo.='<li>
		 <a href="#" class="item-link item-content" onclick="modificaalloggio('.$row['0'].','.$row['3'].')">
          <div class="item-inner">
            <div class="item-title">'.$row['1'].'</div>
            <div class="item-after"><span>Modifica</span></div>
          </div>
		  </a>
        </li>';
		
		
		
		
		
		
	}
	$testo.='</ul></div>';
}
 



if(!isset($inc)){
	echo $testo;
}







?>