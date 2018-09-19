<?php
if(!isset($inc)){
	include('../../../config/connecti.php');
	include('../../../config/funzioni.php');
	$testo='';
}
$testo='';

$IDpren=$_SESSION['IDstrpren'];
$route=$_SESSION['route'];



if(isset($_GET['dato0'])){
	$IDsottosel=$_GET['dato0'];
}


if(isset($_GET['dato1'])){	
	if($_GET['dato1']!='0'){
			$timeoggi=$_GET['dato1'];		
	}else{
	 $timeoggi=time();
	}
}

$dataoggi=date('Y-m-d',$timeoggi);



/*
$query2="SELECT GROUP_CONCAT(s.servizio SEPARATOR ' , '),dp.portata,s.ID FROM dispgiorno as dp,servizi as s WHERE dp.IDsottotip='$IDsottosel' AND  FROM_UNIXTIME(dp.data,'%Y-%m-%d')='$dataoggi' AND dp.IDpiatto=s.ID  GROUP BY dp.portata ORDER BY dp.portata";
$result2=mysqli_query($link2,$query2);
	//echo $query2;
	if(mysqli_num_rows($result2)>0){
		while($row=mysqli_fetch_row($result2)){
			$arrm[$row['1']]=$row['0'];
		}
		
		foreach($arrm as $x => $val){
					$txtmenu.='<div style="background:#fff;padding:5px 5px;border-radius:15px;margin: 0px 15px;margin-bottom:20px;box-shadow: 0 3px 10px 0 rgba(26, 38, 255, 0.1), 0 10px 10px -8px rgba(26, 38, 255, 0.1);border: solid 1px #dddddd;border-left: 3px solid #203a93;">
								<div style="font-weight: 600;color:#203a93;padding: 5px 5px;text-transform: uppercase;">Portata '.$x.'</div>
									<div style="padding:10px 15px">';
								$val=explode(',',$val);
							
									foreach($val as $x=>$key)
									{
										$txtmenu.='<div style="padding:10px 0px;color:#333;border-bottom:solid 1px #dddddd">'.$key.'</div>';
									}
			
			
						$txtmenu.=' </div> 	 
						
						</div>';
		}
		
}
*/


$query2="SELECT GROUP_CONCAT(s.servizio SEPARATOR ' <br/> '),dp.portata,GROUP_CONCAT(DISTINCT(st.sottotipologia) SEPARATOR ' , ') FROM dispgiorno as dp,servizi as s,sottotipologie as st WHERE dp.IDsottotip='$IDsottosel' AND  FROM_UNIXTIME(dp.data,'%Y-%m-%d')='$dataoggi' AND dp.IDpiatto=s.ID AND s.IDsottotip=st.ID  GROUP BY dp.portata ORDER BY dp.portata";
	$result2=mysqli_query($link2,$query2);
	if(mysqli_num_rows($result2)>0){
		while($row=mysqli_fetch_row($result2)){
			
			$txtmenu.='
			<br/>
			<div style="color:#9c2420; font-size:17px; text-transform:capitalize;">'.strtolower($row['2']).'</div>
			
			<span style=" font-size:14px;">'.$row['0'].'</span><br/><br/>
			<hr style="width:50%;">
			
			';
			
			
			
			
			
			
		}
	}





//$txtmenu=estraimenu($IDsottosel,$timeoggi);

if(strlen($txtmenu)>0){
	
	$txt.='<div style="text-align:center;">
				'.$txtmenu.'
				
	
			</div>';
	
	$query2="SELECT ID FROM prenextra WHERE IDpren='$IDpren' AND sottotip='$IDsottosel' AND FROM_UNIXTIME(time,'%Y-%m-%d')='$dataoggi' LIMIT 1";
	$result2=mysqli_query($link2,$query2);
		if(mysqli_num_rows($result2)>0){			
			$txt.='
				<div style="padding-top:30px; text-align:center; font-size:15px; color:#9c2420; ">Il menu è già incluso nella sua prenotazione</div>			
				</div>
				';

		}else{
				



		$txt.='<div class="bottombarpren" style="background:#f1f1f1;z-index:999" align="center">
			  <table style="width:100%;height:100%;cellpadding:0;cellspacing:0;">
			   <tr>
				 <td style="width:15%">
				 </td>
				<td>
			   <button class="button button-fill color-green bottoneprezzo" style="margin:auto;background:#203a93" onclick="apriprenotaora('.$IDsottosel.','.$timeoggi.')">Prenota Ora</button>
				</td>
				 <td style="width:15%">
				 </td>
			   </tr>
			  </table>
			</div>';

		}
			
	$txt.='<hr><br><br><div style="width:90%; margin:auto; text-align:center;color:#af2b44;"><span style="font-weight:300; line-height:12px; font-size:12px;">I menu riportati nella seguente sezione potranno essere modificati fino ad un giorno prima.<br>Per qualsiasi altra informazione contrattare la struttura.</span></div>';

	
}else{
	
	$txt.='<div style="margin:30px 25px; text-align:center;border-radius:15px;background:#fff;box-shadow: 0 3px 10px 0 rgba(26, 38, 255, 0.1), 0 10px 10px -8px rgba(26, 38, 255, 0.1);border: solid 1px #dddddd;border-left: 3px solid #203a93;padding:30px 25px;">
	<div style="font-size:15px;color:#333;" >Il Menù Non &egrave; stato ancora pubblicato<br></div>
	</div>';
}


$testo.='
<div class="content-block" style="padding-top:10px">
			<input type="hidden" value="'.$timeoggi.'" id="tempo">
			'.$txt.'
			<br><br><br><br><br><br>
			';




echo $testo;
?>
</div>