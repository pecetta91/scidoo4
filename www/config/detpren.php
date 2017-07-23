<?php
header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');
include('../../config/funzionilingua.php');

$IDutente=$_SESSION['ID'];
$IDstruttura=$_SESSION['IDstruttura'];
unset($_SESSION['listIDsotto']);
unset($_SESSION['datecentro']);

$id=$_GET['dato0'];
$_SESSION['IDprenfunc']=$id;
$query="SELECT ID,datapren,time,gg,checkout,app,stato,lang,acconto,tempg,tempn,note FROM prenotazioni WHERE IDv='$id' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDpren=$row['0'];
$datapren=$row['1'];
$time=$row['2'];
$notti=$row['3'];
$checkout=$row['4'];
$IDapp=$row['5'];
$stato=$row['6'];
$lang=$row['7'];
$acconto=$row['8'];
$tempg=$row['9'];
$tempn=$row['10'];
$note=stripslashes($row['11']);


$nome=estrainome($id);

if($IDapp!=0){
	$query="SELECT attivo FROM appartamenti WHERE ID='$IDapp' LIMIT 1";
	$result=mysqli_query($link2,$query);
	$row=mysqli_fetch_row($result);
	$attivo=$row['0'];
}else{
	$attivo=0;
}

if($stato=='-1'){
		$alloggio='Senza Alloggio';
	}else{
	$q8="SELECT nome FROM appartamenti WHERE ID='$IDapp' LIMIT 1";
	$r8=mysqli_query($link2,$q8);
	$row8=mysqli_fetch_row($r8);
	$alloggio=$row8['0'];
	}
$tipo=0;

/*
$arrstato=array('Da Confermare','Confermata Senza Acconto','Confemata Con Acconto','Arrivata nella Struttura','Saldata Completamente');
		$statimin=array('DC','CSA','CCA','AS','SC');
$colorstato=array('d43650','3688d4','d4b836','d436cb','27be59');

<div data-page="detpren" class="page navbar-fixed" style="overflow:hidden; padding-top:40px;" > 

*/
$testo=  '
<div class="pages navbar-fixed">
  <div data-page="depren" class="page with-subnavbar">


            <!-- Scrollable page content--> 
			
			
			<input type="hidden" id="IDprenfunc" value="'.$id.'">
			<input type="hidden" id="IDprentime" value="'.$time.'">


		<div class="navbar">
      <div class="navbar-inner">
        
		<div class="left" > <a href="#" class="link back" onclick="openp=0;">
							<i class="material-icons" style="font-size:40px;">chevron_left</i>
						</a></div>
					<div class="center titolonav">'.$nome.'</div>
					<div class="right">
						<a href="#" onclick="addservice('.$id.')">
							<i class="material-icons">add</i>
						</a>
					
					</div>
		
        <div class="subnavbar">
          <div class="buttons-row">
            <a href="#" class="button tab-link tl active" id="m0" onclick="navigationtxt(2,'."'".$id.",0'".','."'contenutop'".',1);" >Dettagli</a>
			<a href="#" class="button tl tab-link"  id="m4" onclick="navigationtxt(2,'."'".$id.",4'".','."'contenutop'".',1)" >Orari</a>
			<a href="#" class="button tl tab-link"  id="m1" onclick="navigationtxt(2,'."'".$id.",1'".','."'contenutop'".',1)" >Il Conto</a>
			<a href="#" class="button tl tab-link" id="m2" onclick="navigationtxt(2,'."'".$id.",2'".','."'contenutop'".',1)"" >Ospiti</a>

			<a href="#" class="button tl tab-link" id="m3" onclick="navigationtxt(2,'."'".$id.",3'".','."'contenutop'".',1)"" >Pagamenti</a>
          </div>
        </div>
      </div>
    </div>


				
<div class="page-content" > 
			
 <div class="content-block" style=" margin-bottom:30px; margin-top:20px;"> 
			  
				<div id="contenutop">
			  ';
			  $inc=1;
			  
			 	include('detpren2.php');
			  
			  
			$testo.= '</div>
				
			
			</div>
				 
					 
					 
			
			';
			  echo $testo;
			 