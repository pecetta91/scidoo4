 <?php 
	include('../../../config/connecti.php');
	include('../../../config/funzioni.php');
	include('../../../config/funzionilingua.php');
	header('Access-Control-Allow-Origin: *');
	$testo='';


$IDpren=$_SESSION['IDstrpren'];
$route=$_SESSION['route'].'immagini/';


$query="SELECT app,gg,IDstruttura FROM prenotazioni WHERE IDv='$IDpren' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDapp=$row['0'];
$notti=$row['1'];
$IDstr=$row['2'];





//echo '<input type="hidden" id="funcreload" value="navigation(25,'.$ID.',0,1)">';


$testo.='<div data-page="galleria" class="page" > 
		 	<div class="navbar" id="navcal" >
				<div class="navbar-inner">
					<div class="left navbarleftsize170">
					  <a href="#" class="link icon-only back">
							<i class="material-icons">chevron_left</i>
							<strong class="stiletitolopagine">Galleria e Immagini</strong>
					  </a>
					</div>
					<div class="center titolonav"></div>
					<div class="right"></div>
				</div>
			</div>
			
		 <div class="page-content">
		 	<div class="content-block" id="galleriadiv"  style="padding:0px; width:100%;"> ';

if($notti>0){
	// se è maggiore di 0 stampo le foto dell'appartamento
	$query="SELECT nome FROM appartamenti WHERE IDstruttura='$IDstr' AND ID='$IDapp' LIMIT 1 ";
	$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
		$row=mysqli_fetch_row($result);
		$nomeapp=$row['0'];
		//$IDapp='46';
		$testo.='<div class="fototitolo">Foto Alloggio: '.$nomeapp.'</div>
		 <div class="swiper-container swiper-init"  data-speed="400" data-space-between="50" data-pagination=".swiper-pagination" style="height:220px;margin:0 5px 15px 5px">
		 	 <div class="swiper-pagination"></div>
			 <div class="swiper-wrapper">';

				$query2="SELECT i.foto FROM assocalbum as a,immagini as i WHERE a.IDobj='$IDapp' AND a.tipo=2 AND a.IDalb=i.ID AND i.foto!='' LIMIT 4";
				//prendo le foto dell'appartamento
				$result2=mysqli_query($link2,$query2);
				if(mysqli_num_rows($result2)>0){
						while($row2=mysqli_fetch_row($result2)){
							$img='big'.$row2['0'];
							$foto=$route.$img;
							/*
								if(!file_exists('../../../immagini/'.$img)){
									$foto=$route.'camera.jpg';
								}
							*/
							$testo.='
							<div class="swiper-slide" style="background:url('.$foto.') center center / cover no-repeat;"></div>';	
						}
					}

		 $testo.='
		 </div>
			</div>';
	}
	
}




$query="SELECT ID,nome,descr FROM album WHERE IDstruttura='$IDstr'	LIMIT 3 ";
	$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
		$testo.='<div class="fototitolo">Album della Struttura</div>
 <div class="overmano" style="height:215px">
	<div class="swiper-wrapper ">';
		while($row=mysqli_fetch_row($result)){
			$IDalbum=$row['0']; 
			$nomealbum=$row['1'];
			$query2="SELECT ID,foto FROM immagini WHERE IDalbum='$IDalbum'";
			$result2=mysqli_query($link2,$query2);
			$num=mysqli_num_rows($result2);
			
			$firstimg='';
			if($num>0){
				
				while($row2=mysqli_fetch_row($result2)){
					if($firstimg==''){$firstimg=$route.$row2['1'];}
				}
			
					
			}
			if(!file_exists('../../../immagini/'.$row2['1'])){
				$firstimg=$route.'camera.jpg';
			}
			
			//myPhoto'.$IDalbum.'.open()
			if(strlen($row['2'])>20){$row['2']=stripslashes(substr($row['2'],0,20));}

$testo.='<div  onclick="navigation2(2,'.$IDalbum.',0,0)" class="scrollmano" style="background:url('.$firstimg.') center center / cover no-repeat;height:190px;width:220px">
			
				<div class="album">
				<strong class="fs13 pl5">'.$nomealbum.'</strong><br><span class="fs10 pl5">'.stripslashes($row['2']).'</span>
			</div>
		</div>';

		}			
$testo.='</div></div>';
	
	}




		  
	/*
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
	
	$num=0;
	$query="SELECT ID,nome,descr FROM album WHERE IDstruttura='$IDstr' ";
	$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
		$testo.='<div style="font-size:14px; color:#e4492b;  padding:5px; font-weight:600;">Foto della Struttura<br></div>
		<div style="margin-top:20px" class="galleria content-block">
		<div class="row rowlist no-gutter" >';
		while($row=mysqli_fetch_row($result)){
			$IDalbum=$row['0']; 
			$nomealbum=$row['1'];
			$query2="SELECT ID,foto FROM immagini WHERE IDalbum='$IDalbum'";
			$result2=mysqli_query($link2,$query2);
			while($row=mysqli_fetch_row($result)){
			$testo.='<div class="col-33">
			<a onclick="foto('.$num.')" style="margin-bottom:10px;margin-right:10px"><img data-src="'.$route.$row['0'].'" class="lazy prendifoto" width="100" height="100" alt="'.$route.'big'.$row['0'].'" id="'.$num.'"></a>
			</div>';
			$num++;
			}
			}
			$testo.='</div></div>';
			}	<a onclick="foto(1,'.$num.')" style="margin-bottom:10px;margin-right:10px"><img data-src="'.$route.$row['0'].'" class="lazy prendifoto" width="100" height="100" alt="'.$route.'big'.$row['0'].'" id="'.$num.'"></a>
			
			
			
			
	
	
	
*/		
	$num=0;
$querym="SELECT  GROUP_CONCAT(DISTINCT(ID)) FROM album WHERE IDstruttura='$IDstr' GROUP BY IDstruttura";
	$resultm=mysqli_query($link2,$querym);
		if(mysqli_num_rows($resultm)>0){
			$rowm=mysqli_fetch_row($resultm);
			$sottoalbum=$rowm['0'];
		}
	$query2="SELECT foto FROM immagini  WHERE IDalbum IN($sottoalbum)";

	$result2=mysqli_query($link2,$query2);
	if(mysqli_num_rows($result2)>0){
		
		
		//id="album1" 
		$testo.='<br/><br/><div class="fototitolo">Foto della Struttura<br></div>
		<div class="row no-gutter" id="album1">';
		
		while($row2=mysqli_fetch_row($result2)){
				$path=$route.$row2['0'];
				$pathbig=$route.'big'.$row2['0'];
			
				if(file_exists('../../../immagini/'.$row2['0'])){
					//background:url('.$path.') no-repeat center center;
				//$testo.='<div class="col-33" style=" border:solid; height:100px;" alt="'.$pathbig.'" id="'.$num.'" idphoto="'.$num.' "  onclick="foto(1,'.$num.');"></div>';
					// onclick="foto(1,'.$num.');" alt="'.$pathbig.'" 
					$testo.='<div class="col-33 prendifoto"   style="background:url('.$path.') no-repeat center center;"  onclick="foto(1,'.$num.');" alt="'.$pathbig.'"  idphoto="'.$num.' " ></div>';
					$num++;
				}
					
			}		
	}

$testo.='</div>



</div></div></div>


';

//<input type="hidden" id="evals" value="'.$eval.'">

echo $testo;
?>