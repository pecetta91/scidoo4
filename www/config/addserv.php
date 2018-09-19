<?php 
header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');

$IDutente=$_SESSION['ID'];
$IDstruttura=$_SESSION['IDstruttura'];
unset($_SESSION['arrservadd']);

$IDpren=$_GET['IDpren'];
$tipo=$_GET['tipo'];


$_SESSION['IDprenfunc']=$IDpren;



if($tipo==2){
	$input='Aggiungi Servizio';
}else{
	$txtc='';
	switch($tipo){
		
		case 1:
		default:
			$txtc='Ricerca il servizio';
		break;
		case 3:
			$txtc='Ricerca il Voucher';
		break;
		case 4:
			$txtc='Ricerca il Cofanetto';
		break;
	}
	
	
	$input='<input type="search" class="ricercainput" id="ricercaserv" placeholder="'.$txtc.'" style="height:30px;"  onkeyup="cercaservizio(this.value,'.$tipo.')"/>';
}



$testo='
<div class="pages navbar-fixed">
<div data-page="addservice" class="page" > 
            <div class="navbar">
			   		<div class="row" style="margin-top:10px;">
					<div class="col-10"> <a href="#add1" class="tab-link tabindietro" style="display:none;" onclick="backselect()"><i class="icon f7-icons" style=" width:30px;  font-size:25px;">chevron_left</i></a></div>
					<div class="col-70" style="font-weight:600; font-size:15px; text-transform:uppercase; text-align:center;">'.$input.'</div>
					<div class="col-20" onclick="mainView.router.back();"><i class="icon f7-icons" style=" font-size:30px; margin-right:18px;">close</i>	</div>
					</div>
            </div>
			
			
			<div class="page-content" > 
			
			
			<div  style="display:none;" >
				<div class="buttons-row">
			
				<a href="#add1" class="tab-link active" >Passo 1</a>
				<a href="#add2" class="tab-link " >Passo 2</a>
			
				</div>	</div>
			
			
			<div class="tabs-animated-wrap" style="height:auto;">
  			 	<div class="tabs" style="height:auto;" valign="top">
					
					
			<div id="add1" class="tab active" style="overflow-y:visible;padding-top:15px;" >
		
			 <div id="listaservizi">
';


$testo.='<br>';
switch($tipo){
	case 1:
	default:
	
		$qadd3='';
		if(isset($_SESSION['listIDsotto'])){
			if($_SESSION['listIDsotto']==2){
				$qadd3=" AND s.IDtipo='".$_SESSION['listIDsotto']."' ";
				//$query="SELECT ID,servizio FROM servizi  WHERE IDstruttura='$IDstruttura' AND IDtipo='".$_SESSION['IDtipo2']."'";
			}else{
				$qadd3=" AND s.IDsottotip='".$_SESSION['listIDsotto']."' ";
			}	
		}
	

		if(isset($_SESSION['listIDsotto'])){
					$query="SELECT s.ID,s.servizio,s.prezzo,t.tipolimite,s.IDtipo,s.durata FROM servizi as s,tiposervizio as t,sottotipologie as st WHERE  s.IDstruttura='$IDstruttura' AND attivo>'0' AND s.IDtipo=t.ID AND t.tipolimite!='4' AND t.ID NOT IN(15,16)  AND s.IDsottotip=st.ID $qadd3 LIMIT 30";

				}else{
					$query="SELECT s.ID,s.servizio,s.prezzo,t.tipolimite,s.IDtipo,s.durata FROM servizi as s,preferitiserv as e,tiposervizio as t,sottotipologie as st WHERE s.IDstruttura='$IDstruttura'  AND e.IDserv=s.ID AND t.ID=s.IDtipo AND s.IDsottotip=st.ID AND s.IDtipo NOT IN(8,9,12,13,14,15,16,17) ORDER BY s.IDtipo LIMIT 30";				
				}

		$result=mysqli_query($link2,$query);
		if(mysqli_num_rows($result)>0){
			$testo.='<div class="list-block">
			<ul>';
			while($row=mysqli_fetch_row($result)){
				$testo.='
					<li>
						<a href="javascript:void(0)" onclick="selectservice('.$row['0'].','.$row['3'].','.$row['4'].','.$row['5'].')" class="item-link item-content">
							<div class="item-inner textleft">
								<div class="item-title f16" id="nome'.$row['0'].'">'.$row['1'].'</div>
							
							</div>
						</a> 
					</li>
				';
			}
			$testo.='</ul></div>';
		}else{
			$testo.='<br><div style="width:100%;" align="center"><h3>Ricerca e Aggiungi il servizio<h3></div><br><br>';
		}

		
/*
while($row=mysqli_fetch_row($result)){
				$testo.='
					<div class="row  h40 rowlist no-gutter" onclick="selectservice('.$row['0'].','.$row['3'].','.$row['4'].','.$row['5'].')">
					<div class="col-80" id="nome'.$row['0'].'">'.$row['1'].'</div>
					<div class="col-20">'.$row['2'].' €</div>
					</div>
				';
			}
		}else{
			$testo.='<br><div style="width:100%;" align="center"><h3>Ricerca e Aggiungi il servizio<h3></div><br><br>';
		}

*/

	break;
	case 2:
		//$testo.='Appunto';
		
		
		$testo.='
		<div class="titleb">Appunto / Servizio</div>
		<div class="list-block">
		  <ul class="primadopo">
			<li>
			 <div class="item-content" style="height:100%;">
			  <div class="item-inner" style="width:100%;height:100%;">
				  <div class="item-input">
            		<textarea id="notaserv" class="textareapren" style="height:90px" placeholder="Appunto"></textarea>
        	  </div>
			 </div>
			 </div>
			</li>
		</ul></div>
		
		
		<div class="titleb">Prezzo (€)</div>
		<div class="list-block">
		  <ul class="primadopo">
			<li>
			 <div class="item-content" style="height:100%;">
			  <div class="item-inner" style="width:100%;height:100%;">
				  <div class="item-input">
            		<input type="number" id="notaprezzo" class="textareapren" placeholder="Prezzo" style="text-align:left; font-size:16px;" value="">
        	  </div>
			 </div>
			 </div>
			</li>
		</ul></div>
		<div class="bottombarpren" id="contbuttonagg" style="background:#f1f1f1;z-index:999;" align="center">
			<button style="" id="buttonaddprev" onclick="addservnoteprec()">Aggiungi Servizio</button>
		</div>
		';
		
				
	break;
	case 3:
		
		
		$testo.='<br><div style="width:100%;" align="center"><h3>'.$txtc.'<h3></div><br><br>';
		
		
		
	break;
	case 4:
		
		
		$query2="SELECT c.ID,c.cofanetto,a.nome,c.persone FROM cofanetti as c,agenzie as a WHERE a.IDstr='$IDstruttura' AND a.ID=c.IDagenzia LIMIT 25";
		$txtsel='';
		$result2=mysqli_query($link2,$query2);
		if(mysqli_num_rows($result2)>0){
			while($row2=mysqli_fetch_row($result2)){
				$IDcof=$row2['0'];
						
				$altattr=$IDcof.'_'.$row2['3'].'_'.$row2['1'];
						
						
				$testo.='<div class="row h40 rowlist no-gutter" onclick="selcof('."'".$altattr."'".')">
						<div class="col-70 coltitle"  id="nome'.$row['0'].'" >'.$row2['1'].'<br><span>'.$row2['2'].'</span></div>
						<div class="col-30">'.$row2['3'].' '.txtpersone($row2['3']).'</div>
						</div>';
						
			}
		}
		
		
		
		
		
		
	break;
		
		
}







$testo.='</div>';

$agg=1;
if(isset($_SESSION['datecentro'])){
	$agg=2;
}

$testo.='

</div>
<div id="add2" class="tab " style="overflow-y:visible;padding-top:28px; padding-bottom:100px;" >
</div>

</div>	
</div>








';


$agg=1;
if(isset($_SESSION['datecentro'])){
	$agg=2;
}

$testo.='

<div id="buttadddiv" class="divisioneprez" align="center">

<table style="width:100%;" cellpadding="0" cellspacing="0">
<tr>
<td style="width:15%"></td>
<td>

<button onclick="pulsservizio('.$agg.');" id="buttonaddprev">Aggiungi (<span id="totaleadd">0</span> <span> €</span>)</button>
</td>
<td style="width:15%"></td>
</tr>
</table>

</div>


</div>
</div></div>


';
		


echo $testo;
?>