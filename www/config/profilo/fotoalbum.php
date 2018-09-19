<?php 
	include('../../../config/connecti.php');
	include('../../../config/funzioni.php');
	include('../../../config/funzionilingua.php');
	header('Access-Control-Allow-Origin: *');
	$testo='';

//$_SESSION['route']='http://188.11.58.195:108/milliont/';
$IDpren=$_SESSION['IDstrpren'];
$route=$_SESSION['route'].'immagini/';

$IDalbum=$_GET['dato0'];

$query="SELECT app,IDstruttura FROM prenotazioni WHERE IDv='$IDpren' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDapp=$row['0'];
$IDstr=$row['1'];

$query="SELECT nome,descr FROM album WHERE IDstruttura='$IDstr'	AND ID='$IDalbum' ";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$nome=$row['0'];
$desc=$row['1'];

$testo='<div data-page="fotoalbum" class="page" > 
		 	<div class="navbar" id="navcal" >
				<div class="navbar-inner">
					<div class="left navbarleftsize260">
					
					  <a href="#" class="link icon-only back lh15" >
						<i class="material-icons">chevron_left</i>
							<strong class="stiletitolopagine">Album <br><span class="fs13">'.$nome.'</span></strong>
					</a>
					</div>
					<div class="center titolonav"></div>
					<div class="right"></div>
				</div>
			</div>
			
		 <div class="page-content">
		 	<div class="content-block" id="galleriadiv"> 
			<input type="hidden" value="'.$IDalbum.'"> ';

$num=0;
	$query2="SELECT foto FROM immagini  WHERE IDalbum='$IDalbum' ";
	$result2=mysqli_query($link2,$query2);
	if(mysqli_num_rows($result2)>0)
	{
		$testo.='<div class="fototitolo">'.$nome.'<br>
		<span class="fs10">'.stripslashes($desc).'</span></div>
		<div class="row no-gutter mt20"  id="album'.$IDalbum.'">';
		
			while($row2=mysqli_fetch_row($result2))
			{
				if(file_exists('../../../immagini/'.$row2['0'])){
					$path=$route.$row2['0'];
					$pathbig=$route.'big'.$row2['0'];
					
					$testo.='<div class="col-33 prendifoto"   style="background:url('.$path.') no-repeat center center;"  onclick="foto('.$IDalbum.','.$num.');" alt="'.$pathbig.'"  idphoto="'.$num.' " ></div>';
					$num++;
				}
			}
		
		$testo.='</div>';
	}

$testo.='</div></div>';



if(!isset($inc)){
echo $testo;
}




?>