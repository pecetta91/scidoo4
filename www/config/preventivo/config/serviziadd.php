<?php 
header('Access-Control-Allow-Origin: *');
include('../../../../config/connecti.php');
include('../../../../config/funzioni.php');
include('../../../../config/preventivoonline/config/funzioniprev.php');


$IDutente=$_SESSION['ID'];
$IDstruttura=$_SESSION['IDstruttura'];
$IDrequest=$_SESSION['IDrequest'];



$query="SELECT IDstr,notti,timearr,stato,checkout FROM richieste WHERE ID='$IDrequest' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDstruttura=$row['0'];
$gg=$row['1'];
$ggric=$gg;
$timearr=$row['2'];
$stato=$row['3'];
$checkout=$row['4'];
/*
$IDsog='';
$qtap=0;

$query2="SELECT IDrestr,COUNT(*),GROUP_CONCAT(ID SEPARATOR ',') FROM richiestep WHERE IDreq='$IDrequest' GROUP BY IDrestr";
$result2=mysqli_query($link2,$query2);
if(mysqli_num_rows($result2)){
	while($row2=mysqli_fetch_row($result2)){
		$restr[$row2['0']]=$row2['1'];
		$qtap+=$row2['1'];
		$IDsog.=$row2['2'].',';
	}
}*/

$query2="SELECT ID FROM richiestep WHERE IDreq='$IDrequest' ";
$result2=mysqli_query($link2,$query2);
$qtap=mysqli_num_rows($result2);


$IDrestr=getrestrmain($IDstruttura).',';
/*
$query="SELECT ID FROM tiporestr WHERE IDstr='$IDstruttura' AND personale='1'  AND personad='1' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$IDrestr=$row['0'].',';
*/
			 		
$testop2=array();

//$query="SELECT s.ID,s.IDtipo,s.IDsottotip,s.esclusivo,t.tipolimite,s.servizio FROM servizi as s,preferitiserv as e,tiposervizio as t WHERE s.IDstruttura='$IDstruttura' AND e.IDserv=s.ID AND t.ID=s.IDtipo AND t.tipolimite !='5' ORDER BY s.IDtipo";



$query="SELECT s.ID,s.IDtipo,s.IDsottotip,s.esclusivo,t.tipolimite,s.servizio FROM servizi as s,prenextra as p,tiposervizio as t  WHERE s.IDstruttura='$IDstruttura' AND s.ID=p.extra AND p.tipolim NOT IN(4,5) AND s.IDtipo=t.ID GROUP BY s.ID ORDER BY COUNT(p.extra) DESC LIMIT 10";


$result=mysqli_query($link2,$query);
if(mysqli_num_rows($result)>0){
	
	$i=0;
	while($row=mysqli_fetch_row($result)){
		
			//controllare se è un pacchetto e ha le notti
			$IDserv=$row['0'];
			$servizio=$row['5'];
			$IDtipo=$row['1'];
			$exc='';
			
			$esclusivo=$row['3'];
			$pos=1;
			if($IDtipo!='2'){
				$pos=controloraripren($IDserv,$IDtipo,$row['2'],$qtap,$IDrequest);
			}
			//if(($esclusivo==1)&&($pos==1)){}
			if($pos==1){
				$tipolim=$row['4'];
				$jj=1;
				$txtcont='';				
				if($IDtipo!='9'){ 
					if($IDtipo==10){
						$prezzo=calcolaprezzoserv($IDserv,$timearr,1,$IDstruttura,0,$IDrequest,1);
					}else{
						$prezzo=calcolaprezzoserv($IDserv,$timearr,$IDrestr,$IDstruttura,0,$IDrequest,1);
					}
					if($tipolim=='2'){
						if(!isset($testop2[$IDtipo])){
							$testop2[$IDtipo]='';
						}
						$func='addservprev2(0,'.$IDserv.',0,1)';
					}else{
						if(!isset($testop2[$IDtipo])){
							$testop2[$IDtipo]='';
						}
						$func='addservprevent('.$IDserv.')';
					}
					$testop2[$IDtipo].='
					  <li>
						<a href="javascript:void(0)" onclick="'.$func.'" class="item-link item-content">
							<div class="item-inner textleft">
								<div class="item-title">'.$servizio.'</div>
							
							</div>
						</a> 
					</li>
					';
				}
			}
	}

}
//	<div class="item-after">€ '.$prezzo.'</div>

$relo=0;
//if($relo!=1){echo '<div class="popup" id="popup">';}
//<div data-page="serviziadd" class="page" > <div class=" navbar-fixed" >


$testo='
	
 <div class="pages">
	<div data-page="serviziadd" class="page" > 
	
  		<div class="navbar">
               <div class="navbar-inner">
                  <div class="left navbarleftsize260">
				 	 <a href="#add1" class="tab-link tabindietro mt5" style="display:none;" onclick="backselect2()">
					  <i class="material-icons" >chevron_left</i>
					 </a>
					  <strong class="stiletitolopagine">Aggiungi un servizio</strong>
				  </div> 
                  <div class="center"  style="font-size:14px; font-weight:600; "></div>
                  <div class="right" onclick="mainView.router.back();blockPopstate=true; navigationtxt(6,0,'."'step2'".',0);calcolatot();" style="width:60px; ">
						<i class="icon f7-icons">close</i>
				  </div>
               </div>
            </div>
			
	<div class="page-content" > 		
			
			
			<div>
				<div class="buttons-row" style="display:none;">
			
				<a href="#add1" class="tab-link   active" >Passo 1</a>
				<a href="#add2" class="tab-link  " >Passo 2</a>
			
				</div>	</div>
			
			<div class="tabs-animated-wrap" style="height:100%;">
  			 	<div class="tabs" style=" overflow:visible;" align="center" valign="top">
					
					
			<div id="add1" class="tab active" style="overflow:scroll; height:100%;" >
				  <form class="searchbar searchbar-init ricercanuovoservform" >
						<div class="searchbar-input">
						  <input type="search" placeholder="Search" onkeyup="ricercaservizio(this.value)"  class="ricercainput"><a href="#" class="searchbar-clear"></a>
						</div><a href="#" class="searchbar-cancel">Cancel</a>
				  </form>
			   
			<div id="servizitrovati">
			
			
			
';


foreach ($testop2 as $key =>$dato){
	
	$query="SELECT tipo FROM tiposervizio WHERE ID='$key' LIMIT 1";
	$result=mysqli_query($link2,$query);
	$row=mysqli_fetch_row($result);
	$tipo=$row['0'];
	
	
	
	$testo.='
		 
		 <div class="content-block-title titleb">'.$tipo.'</div>
		<div class="list-block">
      <ul>
		
	'.$dato.'
	</ul></div>
	
	';
	
}
$testo.='
		
		</div></div>
		
		<div id="add2" class="tab"  style="overflow:scroll; height:100%;" >
</div>

	</div></div>';
	
	
	$testo.='
	
	<div class="bottombarpren" id="contbuttonagg" style="background:#f1f1f1;z-index:999; display:none;" align="center">
		<button style="" id="buttonaddprev" onclick="aggiungis();">Aggiungi servizio (<span id="totaleserv">0 €</span>)</button>
	</div>
		
		
		
	
</div></div></div>';	
//	
echo $testo;


?>