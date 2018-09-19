<?php 
header('Access-Control-Allow-Origin: *');
include('../../../../config/connecti.php');
include('../../../../config/funzioni.php');
include('../../../../config/preventivoonline/config/funzioniprev.php');
$txt='';

$IDpren=$_SESSION['IDstrpren'];

$query="SELECT app,gg,time,IDstruttura,checkout FROM prenotazioni WHERE IDv='$IDpren' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDapp=$row['0'];
$notti=$row['1'];
$check=$row['2'];
$IDstruttura=$row['3'];
$checkout=$row['4'];

$IDserv=$_GET['dato0'];


$query="SELECT servizio,IDtipo,IDsottotip FROM servizi WHERE ID='$IDserv' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$servizio=$row['0'];
$IDtipo=$row['1'];
$IDsottotip=$row['2'];


if($IDtipo!=1)
{
	//eseguo un salto allo step 2
	
	$arr=array('0','2','3');
	$var='<input type="hidden" value="1,3,4" id="statostep">';
}
else{
	//passo allo step 1
	$arr=array('0','1','2','3');
	$var='<input type="hidden" value="1,2,3,4" id="statostep">';
}

$query="SELECT tipolimite FROM tiposervizio WHERE  ID='$IDtipo' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDtipolim=$row['0'];


if($IDtipolim==6){
	//vedere regole
	$query="SELECT ID FROM regolaserv WHERE  IDserv='$IDserv' AND IDstr='$IDstruttura' LIMIT 1";
	$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
	$row=mysqli_fetch_row($result);
	$IDreg=$row['0'];
		
		// se obbligo presente vai a pagina finale prezzo e controlla se c'è un altro pacchetto
		$arr=array('4');
		$var='<input type="hidden" value="4" id="statostep">';
		
	}else{
			$arr=array('0','3');
			$var='<input type="hidden" value="1,4" id="statostep">';
			// se non presente scegli data e vai a prezzo
		}
}

$txt.='
<div class="pages" >
<div data-page="addserv" class="page" > 
<input type="hidden" id="IDservadd" value="'.$IDserv.'">
'.$var.'
            <div class="navbar" >
               <div class="navbar-inner">
			   		<div class="left"  onclick="scorristep(-1,0)"><i class="icon f7-icons" id="indietro" style="display:none;">arrow-left</i></div>
					<div class="center" >Prenota '.strtoupper($servizio).'</div>
					<div class="right"  onclick="chiudiprev()"><i class="icon f7-icons">close</i></div>

				  <div class="tabbar"  style="display:none;">
    				<div class="toolbar-inner" style="width:100%; height:0px; position:relative; overflow:visible;">';
				
	 		

					$i=0;
				  foreach ($arr as $key =>$dato){
						
						//$testo.='<a href="#step'.$i.'" id="buttstep'.$i.'"  disabled class="tab-link  tabpren " >'.$i.'</a>';	
						// style="min-height:800px;"
						$tabs.=' <div id="step'.$i.'" alt="'.$dato.'" class="tab"></div>';
$i++;
					}

	
		$txt.='</div></div>


				   </div>
				</div>
				
				
				
				<div class="bottombarpren" style="background:#f1f1f1;z-index:999;" align="center">
					<table style="width:100%;" cellpadding="0" cellspacing="0"><tr>
					<td style="width:15%"></td>
				    <td>
 						<button class="button button-fill color-green bottoneprezzo" style="margin:auto;" onclick="stepdopo('.$IDserv.')"><span id="avantitxt">Avanti</span></button>
				    </td>
				<td style="width:15%"></td>
				</tr></table>
			</div>
			
            
			<div class="page-content">
				<div class="content-block" id="prenotanuovserv">
					<div class="tabs-animated-wrap" >
						<div class="tabs"  >
							'.$tabs.'
						</div>
 				    </div> 
				</div>
			</div>
		</div>
	</div>';

echo $txt;
?>
