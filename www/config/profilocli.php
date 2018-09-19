<?php 
header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');


$IDpren=$_SESSION['IDstrpren'];

$query="SELECT app,gg,time,tempg,tempn,IDstruttura FROM prenotazioni WHERE IDv='$IDpren' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDapp=$row['0'];
$gg=$row['1'];
$time=$row['2'];
$tempg=$row['3'];
$tempn=$row['4'];
$IDstruttura=$row['5'];

//$_SESSION['route']='http://127.0.0.1/milliont/';

//$_SESSION['route']='http://192.168.1.106/milliont/';
$_SESSION['route']='https://www.scidoo.com/';
//$_SESSION['route']='http://188.11.58.195:108/milliont/';

$nomepren='';
$query="SELECT IDcliente,nome FROM infopren WHERE IDpren IN($IDpren) AND pers='1'";
		$result=mysqli_query($link2,$query);
		if(mysqli_num_rows($result)>0){
			while($row=mysqli_fetch_row($result)){
				$IDcliente=$row['0'];
				if($IDcliente!=0){
					$query2="SELECT nome,cognome FROM schedine WHERE ID='$IDcliente' LIMIT 1";
					$result2=mysqli_query($link2,$query2);
					$row2=mysqli_fetch_row($result2);
					$nomepren=$row2['0'];
				}
			}
		}


/*<img src="ScidooLOGOmin.png" style="width:110px;margin-top:5px;">*/
/*<div class="fb-like" data-href="https://www.facebook.com/wwwebcoom/"  data-layout="button" data-action="like" data-size="small" data-show-faces="false" data-share="false"></div>*/

/*<div class="fb-like" data-href="https://www.facebook.com/wwwebcoom/"  data-layout="button" data-action="like" data-size="small" data-show-faces="false" data-share="false"></div>*/


$testo.='

<div id="fb-root"></div>
<div data-page="profilocli" class="page"> 	

		
		
		
			
			 
			 
			 
			 
			 
            <div class="page-content"> 

              <div class="content-block" ><div id="contenutodiv">';
	
			  $inc=1;
			  
			 include('profilo/prenotazione.php');
				 
           $testo.=' </div>
		   </div> 
     
	 
	 
<div class="list-block" onclick="esci();">
		  <ul>
		  
		  <li  style="background-color:#bc3a30;" >
			  <a href="#" class="item-link item-content">
				  <div class="item-media">
				  </div>
				  <div class="item-inner">
					<div class="item-title fs16 whitetxt" >Esci da Scidoo</div>
					<div class="item-after"></div>
				  </div></a>
				</li>
		  </ul></div>
		  
		  <div class="row no-gutter">
		  	<div class="marauto">
			<a class="fs13 bluenavbar mr10" onclick="navigation2(11,1,0,0)">Privacy Policy</a>
			<a class="fs13 bluenavbar" onclick="navigation2(11,2,0,0)">Cookie Policy</a>
			</div>
		  		
		  </div>

<br><br>

		  
		  ';
		  
	

echo $testo;  

?>