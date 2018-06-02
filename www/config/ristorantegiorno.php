<?php
header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');
include('../../config/funzionilingua.php');
$IDutente=intval($_SESSION['ID']);
$IDstruttura=intval($_SESSION['IDstruttura']);
unset($_SESSION['IDsottotip']);
unset($_SESSION['vis']);
unset($_SESSION['datecentro']);



if(isset($_GET['dato0'])){
		if($_GET['dato0']!='0'){
			$time=$_GET['dato0'];
		}else{
			if(isset($_SESSION['timecal'])){
				$time=$_SESSION['timecal'];
			}else{
				$time=time();
			}
		}
	}else{
		if(isset($_SESSION['timecal'])){
			$time=$_SESSION['timecal'];
		}else{
			$time=time();
		}
	}
	$_SESSION['timecal']=$time;
	$mm=date('m',$time);
	$aa=date('Y',$time);
	$mmsucc=$mm+1;


$data=date('Y-m-d',$time);

$dataini=$data;
$datafin=date('Y-m-d',($time+86400*7));
$IDtipo=0;
if(isset($_GET['dato1'])){
	$IDsottotip=$_GET['dato1'];
}else{
	if(isset($_SESSION['IDsottotip'])){
		$IDsottotip=$_GET['dato1'];
	}else{
		$query2="SELECT ID FROM sottotipologie WHERE IDstr='$IDstruttura' AND IDmain ='1' ORDER BY ord LIMIT 1";
		$result2=mysqli_query($link2,$query2);
		$row=mysqli_fetch_row($result2);
		$IDsottotip=$row['0'];
	}
}
$_SESSION['IDsottotip']=$IDsottotip;

$query2="SELECT IDmain,sottotipologia FROM sottotipologie WHERE ID='$IDsottotip' LIMIT 1";
$result2=mysqli_query($link2,$query2);
$row=mysqli_fetch_row($result2);
$IDtipo=$row['0'];
$sottotipname=$row['1'];


					$maxp=array();
					$sale=array();
					$IDsalamain=0;
					$query="SELECT s.ID,s.nome,s.maxp FROM sale as s,saleassoc as sc WHERE sc.IDsotto='$IDsottotip' AND sc.ID=s.ID ORDER BY sc.priorita";
					$result=mysqli_query($link2,$query);
					if(mysqli_num_rows($result)>0){
						while($row = mysqli_fetch_row($result)){
							if($IDsalamain==0)$IDsalamain=$row['0'];
							$sale[$row['0']]=$row['1'];
							$maxp[$row['0']]=$row['2'];
						}
					}
						
/*	$check1=' class="select3"';
$check2='';
if($_SESSION['tavolisosp']==2){
	$check2=' class="select3"';
	$check1='';
}
*/


$not1='';

if($not['0']>0){
	$not1='<div class="notific4">'.$not['0'].'</div>';
}

//<a href="#" onclick="nuovotavolo()" style="width:50px;" class="open-about"><i class="icon f7-icons" style="font-size:25px; line-height:35px">add</i></a>
$testo='
<div class="pages navbar-fixed">
<div data-page="ristorantegiorno" class="page with-subnavbar"> 
			 
			 <input type="hidden" id="funccentro3" value="navigation(13,'."'".$time.",".$IDsottotip."'".',6,1)">
			 
			 <div class="navbar">
				<div class="navbar-inner">
					<div class="left">
					
					 <a href="#" class="link icon-only" onclick="backexplode(5,'.$_SESSION['timecal'].')"  >
						<i class="material-icons" style="font-size:40px;">chevron_left</i>
					</a>
					
					</div>
					<div class="center titolonav" style="line-height:14px;">'.$sottotipname.'<br><span style="font-size:11px;">'.dataita($time).'</span></div>
					<div class="right">
						<a href="#" onclick="navigation(34,'."'".$time.','.$IDsottotip."'".','."'nuovotavolo'".',0);" style="width:50px;" class="open-about"><i class="icon f7-icons" style="font-size:25px; line-height:35px">add</i></a>
					</div>
				</div>
					<div class="subnavbar" align="center">
						<div class="ristorante-navbar" style="padding:0px;margin:auto;">
							<table style="width:100%; ">
							 <tr>
							  <td style="width:48%">';
                               $stringa="'".$time.','.$IDsottotip.",0'";
$testo.='
								<a class="buttonristo active" id="tavoli" onclick="buttonristoact(1);navigationtxt(20,'.$stringa.','."'".'ristorantegiornodiv'."'".',0);">Elenco Tavoli'.$not1.'</a>
							  </td> ';
					


$stringa="'".$time.','.$IDsottotip.",1'";
$testo.='	 <td style="width:47%">
<a class="buttonristo" id="sale" onclick="buttonristoact(2);navigationtxt(20,'.$stringa.','."'".'ristorantegiornodiv'."'".',0);">Sale '.$not1.'</a>
		</td> 

    
							 </tr>
						 </table>
						 </div>
					 
				 </div>
			</div>
			   
			   <div class="page-content">';
		
			  echo $testo;
				$inc=1;
				include('ristorantegiorno.inc.php');
				
				
echo '
</div></div>
</div>
</div>
';


?>