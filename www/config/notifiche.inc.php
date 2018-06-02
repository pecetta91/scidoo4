<?php 

if(!isset($inc)){
	header('Access-Control-Allow-Origin: *');
	include('../../config/connecti.php');
	include('../../config/funzioni.php');
	
	$IDutente=$_SESSION['ID'];
	$IDstruttura=$_SESSION['IDstruttura'];
}
	
	
$colornot=array();
$query="SELECT ID,colore FROM tiponotifica";
$result=mysqli_query($link2,$query);
while($row=mysqli_fetch_row($result)){
	$colornot[$row['0']]=$row['1'];
}



		$query="SELECT ID FROM personale WHERE  IDuser='$IDutente' LIMIT 1";
		$result=mysqli_query($link2,$query);
		$row=mysqli_fetch_row($result);
		$IDpers=$row['0'];
		
		$timenow=oraadesso($IDstruttura);
		
		$time=$timenow-86400*500;
				
		
		$txt='';
		$txt.='
		
		';
	
		$query="SELECT ID,tipo,colore FROM tiponotifica";
		$result=mysqli_query($link2,$query);
		$arrt=array();
		$arrc=array();
		while($row=mysqli_fetch_row($result)){
			$arrt[$row['0']]=$row['1'];
			$arrc[$row['0']]=$row['2'];
		}
		$not='0';



		$txtarr=array();
	 
	 
	 	//Raggruppa per tipogroup
	 
		$query="SELECT GROUP_CONCAT(nt.ID SEPARATOR ','),nt.IDgroup,nt.tipogroup,nt.tipo,MAX(np.letto),nt.time FROM notifichetxt as nt,notifichepers as np WHERE np.IDpers='$IDpers' AND nt.time>='$time' AND nt.ID=np.IDnotifica AND tipogroup!='0'  GROUP BY IDgroup,tipogroup  ORDER BY nt.time DESC LIMIT 35";
		
		$result=mysqli_query($link2,$query);
		if(mysqli_num_rows($result)>0){
			while($row=mysqli_fetch_row($result)){
				$titolo='';
				
				$IDgroup=$row['0'];
				
				$time=$row['5'];
				
				
				switch($row['2']){
					case 1:
						$titolo=estrainome($row['1']).'<br><span style="font-size:11px; color:#666; text-transform:capitalize; font-weight:400;">'.estrainomeapp($row['1']).'</span>';
					break;
					
				}
				
				if(strlen($titolo)>0){
					$titolo='<b style="font-size:13px; text-transform:uppercase; font-weight:600;  line-height:20px;">'.$titolo.'</b><br>';
				}
				$letto='';
				$stato=1;
				if($row['4']==0){
					$letto=' background:#e4e9f1; border-left:solid 2px #4372bd;';
					$stato=0;
				}
				
				if(!isset($txtarr[$time])){
					$txtarr[$time]='';
				}
				
				
				$txtarr[$time].='
				
				
				
				<div class="titleb" style="margin-left:15px;>'.$titolo.'</div>';
				
				
				
				$query2="SELECT ID,testo,time FROM notifichetxt WHERE ID IN($IDgroup) ORDER BY time DESC";
				
				$result2=mysqli_query($link2,$query2);
				if(mysqli_num_rows($result2)>0){
					$txtarr[$time].='';
					while($row2=mysqli_fetch_row($result2)){
						$txtarr[$time].='
						
						<div class="row" style="background:#fff;  '.$letto.' color:#000; border-top:solid 1px #f1f1f1; border-bottom:solid 1px #f1f1f1;">
							<div class="col-20"
							style="font-weight:100;color:#203a93; font-size:13px;padding-top:10px; padding-left:10px; "
							
							><strong>'.date('H:i',$row2['2']).'</strong><br/><span style="font-size:9px;color:#666; ">'.calcolatime($timenow,$row2['2']).'</span></div>
							<div class="col-80" style="font-weight:400;padding:5px; font-size:14px; line-height:22px;">'.str_replace('/','',$row2['1']).'</div>
						</div>
						 
						
						 
						 

					
						
						
						';
						
						
						/*	
						<li>
						  <div class="item-content">
						
							<div class="item-inner">
								<div class="item-media" style="font-size:12px; "></div>
							
								<div class="item-title" style="line-height:20px;  ">'.str_replace('/','',$row2['1']).'<br/><br/><br/></div>
							  
							</div></div></li>*/
						
					}
					$txtarr[$time].='<br/>';
				}else{
					
				}
				
				
			}
		}
		
		
		//Raggruppa per alarm diversi da IDgroup
		
		
		$query="SELECT GROUP_CONCAT(nt.ID SEPARATOR ','),nt.IDgroup,nt.tipogroup,nt.tipo,MAX(np.letto),nt.time,nt.titolo FROM notifichetxt as nt,notifichepers as np WHERE np.IDpers='$IDpers' AND nt.time>='$time' AND nt.ID=np.IDnotifica AND tipogroup='0'  GROUP BY nt.tipo  ORDER BY nt.time DESC LIMIT 35";
		
		$result=mysqli_query($link2,$query);
		if(mysqli_num_rows($result)>0){
			while($row=mysqli_fetch_row($result)){
				$titolo=$row['6'];
				
				$IDgroup=$row['0'];
				
				$time=$row['5'];
				//$titolo=$row['3'];
				
				
				
				if(strlen($titolo)>0){
					$titolo='<b style="font-size:13px; text-transform:uppercase; font-weight:600;  line-height:15px;">'.$titolo.'</b><br>';
				}
				$letto='';
				$stato=1;
				if($row['4']==0){
					$letto=' style="background:#e5eef6;"';
					$stato=0;
				}
				
				if(!isset($txtarr[$time])){
					$txtarr[$time]='';
				}
				
				
				$txtarr[$time].='
				<div class="titleb">'.$titolo.'</div>';
				
				
				
				$query2="SELECT ID,testo FROM notifichetxt WHERE ID IN($IDgroup)";
				$result2=mysqli_query($link2,$query2);
				if(mysqli_num_rows($result2)>0){
					$txtarr[$time].='<div class="list media-list"><ul>';
					
					while($row2=mysqli_fetch_row($result2)){
						$txtarr[$time].='
						
						<li >
						  <div class="item-link item-content">
							<div class="item-media">-</div>
							<div class="item-inner">
							  <div class="item-title-row">
								<div class="item-title">'.str_replace('/','',$row2['1']).'</div>
								<div class="item-after">'.date('H:i',$row2['2']).'</div>
							  </div>
							  
							</div></div></li>';
						
						
					}
					$txtarr[$time].='</ul></div>';
				}
				
				$txtarr[$time].='
				';
				
			}
		}
		
		
		krsort($txtarr);
		
		foreach($txtarr as $dato){
			$txt.=$dato;
		}
					
		$txt.='<br/>';
		



		//<button class="shortcut recta4 primary" style="width:100%" onClick="allarmi()">Visualizza Tutte</button>
		
	
		$query="UPDATE notifichepers SET letto='1' WHERE IDpers='$IDpers'";
		$result=mysqli_query($link2,$query);


		echo $txt;	 
	
	


?>