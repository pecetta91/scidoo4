<?php 
	include('../../../config/connecti.php');
	include('../../../config/funzioni.php');
	include('../../../config/funzionilingua.php');
	header('Access-Control-Allow-Origin: *');
	$testo='';


$IDpren=$_SESSION['IDstrpren'];
$route=$_SESSION['route'].'immagini/';



$query="SELECT app,gg,time,tempg,tempn,checkout,IDstruttura FROM prenotazioni WHERE IDv='$IDpren' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDapp=$row['0'];
$notti=$row['1'];
$check=$row['2'];
$tempg=$row['3'];
$tempn=$row['4'];
$checkout=$row['5'];
$IDstr=$row['6'];

$nomepren=estrainome($IDpren);
$timeora=oraadesso($IDstr);


$foto='immagini/'.getfoto($IDserv,4);

echo '<input type="hidden" id="funcreload" value="navigation(25,'.$ID.',0,1)">';


$testo='<div data-page="suggerimenti" class="page" > 
		 	<div class="navbar" id="navcal" >
				<div class="navbar-inner">
					<div class="left">
					
					  <a href="#" class="link icon-only back"    >
						<i class="material-icons" style="font-size:40px;">chevron_left</i>
					</a>
					</div>
					<div class="center titolonav">Galleria e Immagini</div>
				</div>
			</div>
			
		 <div class="page-content">
		 	<div class="content-block " id="galleriadiv"> ';
			  
	
	$eval='';
	
	$query="SELECT ID,nome,descr FROM album WHERE IDstruttura='$IDstr' ";
	$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
		$testo.='   
		<div class="content-block-title titleb">Album della Struttura</div>
		<div class="list-block media-list">
 		 <ul>';
		while($row=mysqli_fetch_row($result)){
			$IDalbum=$row['0']; 
			$nomealbum=$row['1'];
			$query2="SELECT ID,foto FROM immagini WHERE IDalbum='$IDalbum'";
			$result2=mysqli_query($link2,$query2);
			$num=mysqli_num_rows($result2);
			
			$firstimg='';
			if($num>0){
				$var='myPhoto'.$IDalbum;
				$eval.='myPhoto'.$IDalbum.'= myApp.photoBrowser({photos : [';
				while($row2=mysqli_fetch_row($result2)){
					if($firstimg==''){$firstimg=$route.$row2['1'];}
					$eval.="'".$route.'big'.$row2['1']."',";
				}
				$eval=substr($eval, 0, strlen($eval)-1);
				$eval.=']});';
			}
			
			$testo.='	
			
			
			
			<li>
			  <a href="#" class="item-link item-content"  onclick="myPhoto'.$IDalbum.'.open()">
				<div class="item-media"><div style="background:url('.$firstimg.') no-repeat center center; background-size:cover; border-radius:5px; width:80px; height:80px;"></div></div>
				<div class="item-inner">
				  <div class="item-title-row">
					<div class="item-title">'.$nomealbum.'</div>
					<div class="item-after">'.$num.'</div>
				  </div>
				  <div class="item-text">'.stripslashes($row['2']).'</div>
				</div>
			  </a>
			</li>
			
			
			';
		}
		$testo.='</ul><div>';
	}		  
	
	


$testo.='
<input type="hidden" id="evals" value="'.$eval.'">

</div></div></div>


';




if(!isset($inc)){
echo $testo;
}




?>