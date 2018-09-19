<?php
header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');
include('../../config/funzionilingua.php');

$IDutente=$_SESSION['ID'];
$IDstruttura=$_SESSION['IDstruttura'];

$testo='';
$arrper=array();

$query="SELECT IDcliente,nome FROM strutture WHERE ID='$IDstruttura' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDmainuser=$row['0'];
$nomestr=$row['1'];


if($IDutente==$IDmainuser){
	$IDpos=1;
	/*$query="SELECT nome FROM clienti WHERE ID='$IDutente' LIMIT 1";
	$result=mysqli_query($link2,$query);
	$row=mysqli_fetch_row($result);
	$nomepers=$row['0'];*/
	
	
	array_push($arrper,'0');
	
	$query="SELECT m.tipo,p.nome FROM mansioni as m,mansionipers as ms,personale as p WHERE ms.IDstruttura='$IDstruttura' AND p.IDuser='$IDutente' AND ms.mansione=m.ID AND p.ID=ms.IDpers";
	$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
	while($row=mysqli_fetch_row($result)){
			//$nomepers=$row['1'];
			array_push($arrper,$row['0']);
		}
	}
}else{
	//controllo personale
	/*$nomepers='';
	
	$query="SELECT nome FROM personale WHERE IDuser='$IDutente' LIMIT 1";
	$result=mysqli_query($link2,$query);
	$row=mysqli_fetch_row($result);
	$nomepers=$row['0'];*/
	
	$query="SELECT m.tipo FROM mansioni as m,mansionipers as ms,personale as p WHERE ms.IDstruttura='$IDstruttura' AND p.IDuser='$IDutente' AND ms.mansione=m.ID AND p.ID=ms.IDpers";
	$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
	while($row=mysqli_fetch_row($result)){
			array_push($arrper,$row['0']);
		}
	}
}


$query="SELECT nome,sesso FROM personale WHERE IDuser='$IDutente' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$nomepers=$row['0'];
$sesso=$row['1'];
$colorsesso=array('2542d9','d925ac');


$testo.='<div style="width:100%;padding-left:15px; margin-top:-15px;  font-size:24px; line-height:22px; color:#2542d9; padding-bottom:25px; border-bottom:solid 1px #ccc; text-align:left;">

<table><tr><td style="width:70px;">
<div style="width:50px; height:50px; background:#'.$colorsesso[$sesso].'; border-radius:50%; line-height:64px; text-align:center;"><i class="material-icons" style="color:#fff;font-size:30px;">person</i></div>

</td><td>
<span style="font-size:16px;">Benvenuto</span><br/><strong>'.$nomepers.'</strong>
</td></tr></table>
</div>

<div style="width:100%" class="hrcenter">
';




if(in_array('0',$arrper)){
	
	$testo.='
	<div class="buttonmenusx" style="font-size:13px; height:25px; padding-left:25px;display:block; margin-top:10px; margin-bottom:0px;  font-weight:600;">Menu/Impostazioni</div>
	<hr>
	<div class="buttonmenusx" onclick="navigation(36,0,0,0);myApp.closePanel();">Alloggi</div><hr>
	
	<div class="buttonmenusx disabled"  onclick="" >Personale<br/>
	<span>Solo da Pc/Notebook</span></div><hr>
	<div class="buttonmenusx disabled" onclick="">Listino Prezzi<br/>
	<span>Solo da Pc/Notebook</span></div><hr>
	<div class="buttonmenusx disabled" onclick="">Messaggi Automatici<br/>
	<span>Solo da Pc/Notebook</span></div><hr>
	
	';
	
	
	
	
}

$testo.='
</div>
<div style="width:100%; position:absolute; bottom:0px;border-top:solid 1px #ccc;height:50px; line-height:50px; text-align:center; font-size:16px; z-index:999; background:#f9f9f9;" onclick="myApp.closePanel();" >
Chiudi
</div>


';






echo $testo;
?>