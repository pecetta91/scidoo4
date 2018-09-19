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
		//<div  id="dataform2" class="selectnewp" style="font-size:13px;">'.dataita4($checkout).'</div>
		//<div  id="dataform" class="selectnewp" style="font-size:13px;">'.dataita4($ora).'</div>
		
		
		/*<div  id="dataform">
				
				<table width="80%" style="margin-left:20px;"><tr><td rowspan="2" style="font-size:25px; color:#006cbf; width:50px; text-align:left; line-height:25px; font-weight:600;">'.date('d',$ora).'</td><td style="font-size:12px; line-height:10px; color:#333;">'.$mesiita[date('n',$ora)].'</td></tr>
			  <tr><td style="font-size:12px; color:#333;">'.date('Y',$ora).'</td></tr></table>
			  */
		
		
		$txtapp=getdisponibilita($ora,$checkout,$IDstruttura);
			 $testo.=' 
			 <div class="row" style="margin:5px;">
				 <div class="col-100" align="center">
					<div class="titlenewp">Arrivo e Partenza</div>
				 </div>
			 </div>
			 
			 <div class="row no-gutter rowlist rowlistrichieste">
			 <div class="col-50 col-h50 colborder"  style="position:relative;">
			 <div style="position:relative; height:30px;">
					 <div class="primotestodivcol">Check-in</div> 
			 
			 	
				<div  id="dataform" class="divdataformcontainer">
				</div>
			
			
			<table width="80%" class="tableformcontainer"><tr><td rowspan="2" class="tabletd1"><span  id="dataform-1">'.date('d',$ora).'</span><br/>
			<span id="dataform-4" style="font-size:10px;">'.$giorniita[date('w',$ora)].'</span>
			
			</td><td class="tabletd2" id="dataform-2">'.$mesiita[date('n',$ora)].'</td></tr>
			  <tr><td class="fs11 c333" id="dataform-3">'.date('Y',$ora).'</td></tr></table>
			  </div>
				
			</div>
			
			
			
			<div class="col-50 col-h50 " style="position:relative;">
				<div style="position:relative;height:30px;">
					 <div class="primotestodivcol">Check-out</div> 

			  <div  id="dataform2" class="divdataformcontainer"></div>
			  
			  <table width="80%" class="tableformcontainer" ><tr><td rowspan="2" class="tabletd1">
			  <span  id="dataform2-1">'.date('d',$checkout).'</span><br/>
				<span id="dataform2-4" style="font-size:10px;">'.$giorniita[date('w',$checkout)].'</span>
			  </td><td class="tabletd2" id="dataform2-2">'.$mesiita[date('n',$checkout)].'</td></tr>
			  <tr><td class="c333 fs11" id="dataform2-3">'.date('Y',$ora).'</td></tr></table>
			  
			  </div>
			</div>
			
			<div class="col-100" align="center" style="color:#999; font-size:13px; font-weight:400;padding-top:15px;text-align:center">
			Soggiorno di <span id="notti">1</span> <span id="txtnotti">Notte</span>
			</div>
			
			
			<input type="hidden" id="dataarr" value="'.date('Y-m-d',$ora).'">
			<input type="hidden" id="datapar" value="'.date('Y-m-d',$checkout).'">
				  
			 </div>';
			
	
		$testo.='
		
	
		
		<div class="row no-gutter rowlist rowlistrichieste" style="border-top:none;">
		
			<div class="col-50 col-h50 colborder " >
				<div  style="position:relative">
						<a href="#" class="item-link  smart-select" data-open-in="picker" pickerHeight="400px" data-back-on-select="true" data-searchbar="false">
						 <select id="alloggio" onChange="creasessione(this.value,95)" >';
		
						$alloggiotxt='';
					$classavail='';
						$query2="SELECT ID,nome FROM appartamenti WHERE  IDstruttura='$IDstruttura' AND attivo ='1' AND ID NOT IN($txtapp)";
						$result2=mysqli_query($link2,$query2);
						if(mysqli_num_rows($result2)>0){
							while($row2=mysqli_fetch_row($result2)){
								$testo.='<option value="'.$row2['0'].'"';

								if($_SESSION['app']==0){$_SESSION['app']=$row2['0'];}
								
								if($row2['0']==$_SESSION['app']){$testo.=' selected="selected" ';  $alloggiotxt=$row2['1'];}
								$testo.='>'.$row2['1'].'</option>';
							}
						}else{
							$alloggiotxt="No Disponibilità";
							$classavail='txtnoavail';
						}
		
						$testo.='</select>
						<div class="primotestodivcol" >Alloggio</div>
						<div class="item-after secondotestodivcol txtavail '.$classavail.'" id="txtalloggio">'.$alloggiotxt.'</div>
					  </a>
				  </div>
				
			</div>
			
			<div class="col-50 col-h50">
					<div  style="position:relative">
						<a href="#" class="item-link  smart-select" data-open-in="picker" pickerHeight="400px"   data-back-on-select="true" data-searchbar="false">
							<select id="orario"> '.generaorario($orario,8,24,60).'</select>
						 	<div class="primotestodivcol">Orario</div>
							<div class="item-after secondotestodivcol" style="top:0px;margin-top:25px;
							margin-left: 7px;
    						font-size: 18px;  color:#006cbf; font-weight:600;
							">'.$orario.'</div>
					    </a>
				  </div>	
		   </div>
		</div>';
		
		
		
	}else{
		
		// <input type="text" id="dataform" class="selectnewp"    alt="'.date('Y-m-d',$ora).'"value="'.dataita4($ora).'">
		 $testo.=' 
		<div class="col-100" align="center"><div class="titlenewp">Arrivo</div><br>
		<span id="notti" style="display:none;">0</span>
		<input type="text" id="dataarr" value="'.date('Y-m-d',$ora).'">
		
		<div class="row no-gutter rowlist rowlistrichieste">
			<div class="col-50 colborder col-h50" style="position:relative">
						<div  style="position:relative">
									
										<div class="primotestodivcol">Arrivo</div> 
										<div  id="dataform" class="divdataformcontainer">
										
										</div>
			
								<table width="80%" class="tableformcontainer"><tr><td rowspan="2" class="tabletd1">
								<span  id="dataform-1">'.date('d',$ora).'</span><br/>
								<span id="dataform-4" style="font-size:10px;">'.$giorniita[date('w',$ora)].'</span>
								</td><td class="tabletd2" id="dataform-2">'.$mesiita[date('n',$ora)].'</td></tr>
								  <tr><td class="fs12 c333" id="dataform-3">'.date('Y',$ora).'</td></tr></table>
			  
						</div>			
			</div>
			<div class="col-50 col-h50">
					<div  style="position:relative">
					
						<a href="#" class="item-link  smart-select" data-open-in="picker"   data-back-on-select="true" data-searchbar="false">
							<select id="orario"> '.generaorario($orario,8,24,60).'</select>
						 	<div class="primotestodivcol">Orario</div>
							<div class="item-after secondotestodivcol" style="padding-left:7px !important;top:0px;margin-top:25px;font-size:20px; color:#006cbf; font-weight:600;">'.$orario.'</div>
					    </a>
				  </div>	
		   </div>
		</div>';
		
		
		

		
		
	}
	

/*<div class="col-20" style="text-align:center;"><div style="margin:1px;display:inline-block;font-weight:bold;color:#0064d4; font-size:12px; text-transform:uppercase;">Orario</div><br>
			  
			  <select class="selectnewp" style="width:100%;"> '.generaorario($ora,8,24,60).'</select>
			  
			 </div>*/


  
  $testo.='


 <div class="row " style="margin:5px; margin-top:20px;">
				 <div class="col-100" align="center">
					<div class="titlenewp">Ospiti</div>
				 </div>
			 </div>

  <div class="list-block ">
  <ul>
  ';
  
  $query5="SELECT ID,restrizione,tiporest,personale FROM tiporestr WHERE IDstr='$IDstruttura' AND limite='0' AND personale='1' ORDER BY ordine";
	$result5=mysqli_query($link2,$query5);
	$num=mysqli_num_rows($result5);
	
	$lar=50;
	$col=2;
	if(($num%3)==0){
		$lar=33;
		$col=3;	
	}
	
	$ini=0;
	
	while($row5=mysqli_fetch_row($result5)){
			/*
			if($ini==$col){
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
			
			';
		*/
			
			
			
			$testo.='<li>
				  <a href="#" class="item-link  smart-select" data-open-in="picker"  pickerHeight="400px" data-back-on-select="true" data-searchbar="false">
					<select  id="restriz'.$row5['0'].'" lang="1" alt="'.$row5['0'].'" class="selectdx inputrestr" >
			  '.generanum(0,20).'</select>
					<div class="item-content">
					  <div class="item-inner">
						<div class="item-title titleform">'.$row5['1'].'</div>
						<div class="item-after" style="color:#006cbf; font-size:18px; font-weight:600;">0</div>
					  </div>
					</div>
				  </a>
				</li>
			
				';
				
	
	}
	
  $testo.='</ul></div><br>';


/*
$cont=0;
	$testo.='<div class="row" style="margin:5px;">
				 <div class="col-100" style="text-align:left; padding-left:15px;">
					<div class="titlenewp">Ospiti</div>
				 </div>
			 </div>';
					$query5="SELECT ID,restrizione,tiporest,personale FROM tiporestr WHERE IDstr='$IDstruttura' AND limite='0' AND personale='1' ORDER BY ordine";
					$result5=mysqli_query($link2,$query5);
					$num=mysqli_num_rows($result5);
					//`$colonne=floor(10/$num);
					while($row5=mysqli_fetch_row($result5)){
						
						
						if($cont==0){
							$testo.='<div class="row rowlist no-gutter rowlistrichieste">';
						}
						$cont++;
						$testo.='<div class="col-50 colborder" >
								<div class="p0">
								<a href="#" class="item-link  smart-select" data-open-in="picker"   data-back-on-select="true" data-searchbar="false">
								  <select  id="restriz'.$row5['0'].'" lang="1" alt="'.$row5['0'].'" class="selectdx inputrestr" >'.generanum(0,20).'</select>
									<div class="c000 fs11 p0 textcenter" >'.$row5['1'].'</div>
									<div class="item-after c000 fs11 p0 textcenter pt5">0</div>
								</a>
								</div>
						</div>';
						if($cont==2){
							$testo.='</div>';
							$cont=0;
						}
					}
				
					if($cont<2){
						$testo.='</div>';
					}


  
  */
  

  
  if($attivo!='2'){
	  

	  	$testo.=' <div class="list-block">
  			<ul>';
	  	
		$query5="SELECT ID,restrizione,tiporest,personale FROM tiporestr WHERE IDstr='$IDstruttura' AND limite='0' AND personale='0'";
		$result5=mysqli_query($link2,$query5);
		while($row5=mysqli_fetch_row($result5)){
				
				
				$testo.='<li>
					  <a href="#" class="item-link  smart-select" data-open-in="picker" pickerHeight="400px" data-back-on-select="true" data-searchbar="false">
						<select  id="restriz'.$row5['0'].'"  alt="'.$row5['0'].'" class="selectdx inputrestr" >
				  '.generanum(0,5).'</select>
						<div class="item-content">
						  <div class="item-inner">
							<div class="item-title titleform">'.$row5['1'].'</div>
							<div class="item-after">0</div>
						  </div>
						</div>
					  </a>
					</li>';
		}
		$testo.='</ul></div><br>';
	
	  /*
	  		$x=0;
					$query5="SELECT ID,restrizione,tiporest,personale FROM tiporestr WHERE IDstr='$IDstruttura' AND limite='0' AND personale='0'";
					$result5=mysqli_query($link2,$query5);
					$num=mysqli_num_rows($result5);
	  				if($num>0){
						$testo.='<div class="row" style="margin:5px; margin-top:10px;">
							 <div class="col-100" style="text-align:left; padding-left:15px;">
								<div class="titlenewp">Altre Informazioni</div>
							 </div>
						 </div>';
						
						
					
	  
						$testo.='<div class="row rowlist no-gutter rowlistrichieste">';
						while($row5=mysqli_fetch_row($result5)){
							if($x==2){					
								$testo.='</div>
								<div class="row rowlist no-gutter rowlistrichieste">';
								$x=0;
							}

							$testo.='<div class="col-50 colborder" >
									<div class="p0">
									<a href="#" class="item-link  smart-select" data-open-in="picker"   data-back-on-select="true" data-searchbar="false">
									  <select  id="restriz'.$row5['0'].'" 	 alt="'.$row5['0'].'" class="selectdx inputrestr" >'.generanum(0,5).'</select>
										<div class="c000 fs14 p0 textcenter" >'.$row5['1'].'</div>
										<div class="item-after c000 fs13 p0 textcenter pt10">0</div>
									</a>
									</div>
							</div>';
							$x++;
						}
						$testo.='</div>';
					}
	  
	  
	  */
	  }
  


echo $testo.'<br><br><br><br><br>';
			 