<?php
header('Access-Control-Allow-Origin: *');
include('../../../config/connecti.php');
include('../../../config/funzioni.php');
include('../../../config/funzionilingua.php');

$IDutente=$_SESSION['ID'];
$IDstruttura=$_SESSION['IDstruttura'];

$gg=1;

	$nomeapp='';
	$attivo=0;
	
	if($_SESSION['app']!=0){
		$query2="SELECT nome,attivo FROM appartamenti WHERE ID='".$_SESSION['app']."' LIMIT 1";
		$result2=mysqli_query($link2,$query2);
		$row2=mysqli_fetch_row($result2);
		$nomeapp=$row2['0'];
		$attivo=$row2['1'];
		
	}else{
		 $_SESSION['timenew']=time();
	}
	
	$query="SELECT checkin,orai FROM strutture WHERE ID='$IDstruttura' LIMIT 1";
	$result=mysqli_query($link2,$query);
	$row=mysqli_fetch_row($result);
	$check=$row['0'];
	$orai=$row['1'];
	if($attivo!=2){	
		$cll=$check;
	}else{
		$cll=$orai;
	}
	$orario=secondinv($cll);



$testo='';
/*
if($_SESSION['timenew']!=0){
	$testo.='<input type="hidden" id="prenotveloce" value="0">
		<input type="hidden" id="prenotvelocetime" value="'.date('Y-m-d',$_SESSION['timenew']).'">';
}	
*/


$ora=$_SESSION['timenew'];
$checkout=$ora+86400;

$testo.='

<div class="content-block">

	

';


	if($attivo!='2'){
		//border-color:#0064d4; color:#0064d4;
		
		$txtapp=getdisponibilita($ora,$checkout,$IDstruttura);
			 $testo.=' <div class="row" style="margin:5px;">
			 <div class="col-100" style="text-align:center;">
			 <div class="titlenewp">Arrivo e Partenza</div>
			 </div>
			 </div>
			 <div class="row">
			 <div class="col-50" align="center">
			 <input type="text" id="dataform" class="selectnewp"   alt="'.date('Y-m-d',$ora).'" value="'.dataita4($ora).'"  >
			</div><div class="col-50" align="center">
			  <input type="text" id="datapar" class="selectnewp" alt="'.date('Y-m-d',$checkout).'" value="'.dataita4($checkout).'"  >
			 </div>
			 </div>
			
						 
						 
		
		<br>
		<div class="list-block">
  			<ul>
		
		
				<li>
				  <a href="#" class="item-link  smart-select" data-open-in="picker"   data-back-on-select="true" data-searchbar="false">
				   <select id="alloggio" onChange="creasessione(this.value,95)" >';
			 
			 $alloggiotxt='';
			$query2="SELECT ID,nome FROM appartamenti WHERE  IDstruttura='$IDstruttura' AND attivo ='1' AND ID NOT IN($txtapp)";
			$result2=mysqli_query($link2,$query2);
			if(mysqli_num_rows($result2)>0){
				while($row2=mysqli_fetch_row($result2)){
					$testo.='<option value="'.$row2['0'].'"';
					
					if($row2['0']==$_SESSION['app']){$testo.=' selected="selected" ';  $alloggiotxt=$row2['1'];}
					$testo.='>'.$row2['1'].'</option>';
				}
			}
			 
			 
			 
			 
			 
			 $testo.='</select>
					
					<div class="item-content" style="height:45px;">
					  <div class="item-inner">
						<div class="item-title titleform">Alloggio per <i id="notti">1</i> Notte</div>
						<div class="item-after">'.$alloggiotxt.'</div>
					  </div>
					</div>
				  </a>
				</li>
				
				<li>
				  <a href="#" class="item-link  smart-select" data-open-in="picker"   data-back-on-select="true" data-searchbar="false">
					<select id="orario" > '.generaorario($orario,8,24,60).'</select>
					<div class="item-content" style="height:45px;">
					  <div class="item-inner">
						<div class="item-title titleform">Orario</div>
						<div class="item-after">'.$orario.'</div>
					  </div>
					</div>
				  </a>
				</li>
		
		
		
		</ul>
			
		
	</div>
	
	
	';
	}else{
		
		
		 $testo.=' 
		 <div class="row" >
			 <div class="col-100" align="center"><div class="titlenewp">Arrivo</div><br>
			 <input type="text" id="dataform" class="selectnewp"    alt="'.date('Y-m-d',$ora).'"value="'.dataita4($ora).'"  >
			 </div>
			 
		</div>
		<br>
		<span id="notti" style="display:none;">0</span>
			<div class="list-block">
  			<ul>
			<li>
				  <a href="#" class="item-link  smart-select" data-open-in="picker"   data-back-on-select="true" data-searchbar="false">
					<select id="orario" > '.generaorario($orario,8,24,60).'</select>
					<div class="item-content" style="height:45px;">
					  <div class="item-inner">
						<div class="item-title titleform">Orario</div>
						<div class="item-after">'.$orario.'</div>
					  </div>
					</div>
				  </a>
				</li>
			
		</ul></div>
		
	
	
	
	';
		
		
	}
/*<div class="col-20" style="text-align:center;"><div style="margin:1px;display:inline-block;font-weight:bold;color:#0064d4; font-size:12px; text-transform:uppercase;">Orario</div><br>
			  
			  <select class="selectnewp" style="width:100%;"> '.generaorario($ora,8,24,60).'</select>
			  
			 </div>*/

  
  
  $testo.='

  <div class="list-block">
  <ul>
  ';
  
  $query5="SELECT ID,restrizione,tiporest,personale FROM tiporestr WHERE IDstr='$IDstruttura' AND limite='0' AND personale='1' ORDER BY ordine";
	$result5=mysqli_query($link2,$query5);
	$num=mysqli_num_rows($result5);
	
	$lar=50;
	$col=2;
	/*if(($num%3)==0){
		$lar=33;
		$col=3;	
	}*/
	
	$ini=0;
	
	while($row5=mysqli_fetch_row($result5)){
			
			/*if($ini==$col){
				$testo.='</div>';
				$ini=0;
			}
			if($ini==0){
				$testo.='<div class="row">';
			}
			$ini++;
		
			$testo.='
			<div class="col-'.$lar.'" style="text-align:center;" >
			 	<div style="margin:5px; font-weight:bold; height:15px; font-size:11px;color:#333;overflow:hidden;text-transform:uppercase;">'.$row5['1'].'</div>
			 
			 <select  id="restriz'.$row5['0'].'" lang="1" alt="'.$row5['0'].'" class="selectdx inputrestr selectnewp" style="width:60%;" >'.generanum(0,20).'</select>
			 
			 </div>
			
			';*/
			
			
			
			$testo.='<li>
				  <a href="#" class="item-link  smart-select" data-open-in="picker"   data-back-on-select="true" data-searchbar="false">
					<select  id="restriz'.$row5['0'].'" lang="1" alt="'.$row5['0'].'" class="selectdx inputrestr" >
			  '.generanum(0,20).'</select>
					<div class="item-content" style="height:45px;">
					  <div class="item-inner">
						<div class="item-title" style="color:#0064d4; ">'.$row5['1'].'</div>
						<div class="item-after">N.0</div>
					  </div>
					</div>
				  </a>
				</li>
			
				';
				
	
	}
	
	
  
  
  
  $testo.='</ul></div><br>
   <div class="list-block">
  <ul>
  
  ';
  
  
  

  
  if($attivo!='2'){
	  
		$query5="SELECT ID,restrizione,tiporest,personale FROM tiporestr WHERE IDstr='$IDstruttura' AND limite='0' AND personale='0'";
		$result5=mysqli_query($link2,$query5);
		while($row5=mysqli_fetch_row($result5)){
				
				
				$testo.='<li>
					  <a href="#" class="item-link  smart-select" data-open-in="picker" data-back-on-select="true" data-searchbar="false">
						<select  id="restriz'.$row5['0'].'"  alt="'.$row5['0'].'" class="selectdx inputrestr" >
				  '.generanum(0,5).'</select>
						<div class="item-content" style="height:45px;">
						  <div class="item-inner">
							<div class="item-title">'.$row5['1'].'</div>
							<div class="item-after">N.0</div>
						  </div>
						</div>
					  </a>
					</li>
				
					
					';
					
		
		}
		
	
	  
	  $testo.='</ul></div><br>';
	  }
  
  /*
  $testo.='
  
  
  
  
  
<div class="list-block">
  <ul>
  
  
  ';
  
	$query5="SELECT ID,restrizione,tiporest,personale FROM tiporestr WHERE IDstr='$IDstruttura' AND limite='0' AND personale='0'";
	$result5=mysqli_query($link2,$query5);
	while($row5=mysqli_fetch_row($result5)){
			$testo.='
				<li>
				  <a href="#" class="item-link  smart-select" data-open-in="page" data-back-on-select="true" data-searchbar="false">
					<select id="restriz'.$row5['0'].'" alt="'.$row5['0'].'" class="selectdx inputrestr" >
			  '.generanum(0,10).'</select>
					<div class="item-content">
					  <div class="item-inner">
						<div class="item-title">'.$row5['1'].'</div>
						<div class="item-after">N.0</div>
					  </div>
					</div>
				  </a>
				</li>
				';
	}
	$testo.='</ul></div>
	
	
 
';*/


echo $testo.'<br><br><br><br><br>';
			 