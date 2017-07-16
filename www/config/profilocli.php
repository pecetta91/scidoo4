<?php 
include('../../config/connecti.php');
include('../../config/funzioni.php');
header('Access-Control-Allow-Origin: *');

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

$_SESSION['route']='http://192.168.1.106/milliont/';
//$_SESSION['route']='https://www.scidoo.com/';

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





$testo=  '

<div data-page="profilocli" class="page"> 	

		
			<div class="navbar" id="navcal">
				<div class="navbar-inner">
					<div class="left">
					
					</div>
					<div class="center titolonav">Benvenuto '.$nomepren.'</div>
					
				</div>
			</div>
		
		
			
			 
			 
			 
			 
			 
            <div class="page-content"> 

              <div class="content-block" ><div id="contenutodiv">';
	
			  $inc=1;
			  
			 include('profilo/prenotazione.php');
				 
           $testo.=' </div>
		   
		   </div> 
     
			  
		    <div class="list-block">
      <ul>
	   <li class="item-content" onclick="esci();myApp.closePanel('."'left'".');">
          <div class="item-inner">
            <div class="item-title menusx2" style="color:#c01313;">ESCI DA SCIDOO</div>
          </div>
        </li>
	  
	  
	  </ul></div>
	  
		  
		  ';
		  
	

echo $testo;  

?>