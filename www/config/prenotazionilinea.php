<?php


	header('Access-Control-Allow-Origin: *');
	include('../../config/connecti.php');
	include('../../config/funzioni.php');
	include('../../config/funzionilingua.php');


$IDstruttura=$_SESSION['IDstruttura'];
$time=$_POST['time']; //time
$IDsottotip=$_POST['IDsottotip'];//idsottotip
$tavolo=$_POST['tavolo'];//tavolo
$agg=$_POST['agg'];


$data=date('Y-m-d',$time);


//da time e sottotip mi ricavo idprenextra dalla giornata di oggi 
//AND tavoli.stato=2 AND p.ID=tavoli.IDprenextra
$testo='
<input type="hidden" value="'.$time.'" id="time">
<input type="hidden" value="'.$IDsottotip.'" id="idosottotip">
<input type="hidden" value="'.$agg.'" id="agg">';




$query="SELECT GROUP_CONCAT(IDpren SEPARATOR ','),COUNT(*) FROM prenextra WHERE FROM_UNIXTIME(time,'%Y-%m-%d')='$data' AND IDstruttura='$IDstruttura' AND IDtipo='1' AND sottotip='$IDsottotip'  GROUP BY sottotip";
		$result=mysqli_query($link2,$query);
		if(mysqli_num_rows($result)>0){
			$row=mysqli_fetch_row($result);
			$IDprennot=$row['0'];
			$num=$row['1'];
		}else{
			$IDprennot=0;
			$num=0;
		}
		
		$txt[0]='';
		$txt[2]='';
				$IDpreng=array();
		
		$numpersone[0]=0;
		$numpersone[1]=0;
		
		$query="SELECT P.ID,P.time,P.note,P.IDpren,P.modi,S.servizio,GROUP_CONCAT(P.ID SEPARATOR ',') FROM prenextra as P,servizi AS S,prenextra2 as p2 WHERE FROM_UNIXTIME(P.time,'%Y-%m-%d')='$data' AND P.IDstruttura='$IDstruttura' AND P.IDtipo='1' AND P.sottotip='$IDsottotip' AND P.extra=S.ID AND P.modi>='0' AND p2.IDprenextra=P.ID AND p2.qta>'0' GROUP BY P.sottotip,P.IDpren ORDER BY P.time DESC,p2.qta ";
		$result=mysqli_query($link2,$query);
			
			
		if(mysqli_num_rows($result)>0){
			while($row=mysqli_fetch_row($result)){
				$IDpren=$row['3'];
					if(!in_array($IDpren,$IDpreng)){	
	
					
					$IDprenunit=prenotstessotav($IDpren,$IDpreng);

					$IDprenextra=$row['0'];
					$timeprenextra=$row['1'];
					$note=$row['2'];
					$servizio=$row['5'];
					
					$add=0;
					$query2="SELECT ID,stato FROM tavoli  WHERE IDprenextra='$IDprenextra' LIMIT 1 ";
					$result2=mysqli_query($link2,$query2);
					if(mysqli_num_rows($result2)>0){
						$row2=mysqli_fetch_row($result2);
						switch($row2['1']){
							case 1:
								$add=1;
							break;
							case 2:
								$add=2;
							break;
						}
					}
						
					if(($add==0)||($add==2)){

							$arr=explode(',',$IDprenunit);
							foreach ($arr as $dato){
								array_push($IDpreng,$dato);
							}
								
						
						$nomepren='';
						$query2="SELECT a.nome,p.IDv FROM appartamenti as a,prenotazioni as p WHERE p.IDv IN($IDprenunit) AND p.IDstruttura='$IDstruttura' AND p.app=a.ID";
						$result2=mysqli_query($link2,$query2);
						$nomeapp='';
						if(mysqli_num_rows($result2)>0){
							while($row2=mysqli_fetch_row($result2)){
								
								$nomepren.=estrainome($row2['1']).', ';
								$nomeapp.=''.$row2['0'].' , ';
								
							}
							$nomepren=substr($nomepren, 0, strlen($nomepren)-2); 
							$nomeapp=substr($nomeapp, 0, strlen($nomeapp)-3); 
	
							
						}else{
							$nomeapp="";
							$nomepren="";
						}
						
						
						$query2="SELECT GROUP_CONCAT(p2.IDinfop SEPARATOR ','),COUNT(*)  FROM prenextra as p,prenextra2 as p2 WHERE FROM_UNIXTIME(p.time,'%Y-%m-%d')='$data' AND p.IDstruttura='$IDstruttura' AND  p.sottotip='$IDsottotip' AND p2.IDprenextra=p.ID AND p.modi>='0' AND p2.qta>'0' AND p.IDpren IN($IDprenunit) GROUP BY p.IDstruttura ";
						$result2=mysqli_query($link2,$query2);
						$row2=mysqli_fetch_row($result2);
						$IDgroup=$row2['0'];
						$npers=$row2['1'];
			
						
						$notecli='';
						$query2="SELECT i.ID,GROUP_CONCAT(CONCAT('<b>',s.nome,' ',s.cognome,':</b> ',s.noteristo,' ') SEPARATOR '<br>') FROM infopren as i,schedine as s WHERE i.ID IN($IDgroup) AND i.IDcliente=s.ID AND s.noteristo!='' LIMIT 1";
						$result2=mysqli_query($link2,$query2);
						if(mysqli_num_rows($result2)>0){
							$row2=mysqli_fetch_row($result2);
							if(strlen($row2['1'])>0){
								$notecli='<div class="shortcut mini17 danger infoicon popover" style="float:right;"><span>
								<b style="color:#f02415;">Note Clienti</b><br>'.$row2['1'].'</span></div>';
							}
						}
						
				            $numpersone[$add]+=$npers;
					
							$txt[$add].='
        <li class="item-link item-content" onclick="modprenextra('.$IDprenextra.','."'".$tavolo."'".',36,9,24)">
          <div class="item-inner">
					<div class="item-title">'.$nomepren.'<br>
					   <span style="font-size:10px;font-weight:400; color:#999;">'.$nomeapp.''.$notecli.'</span>
				    </div>
					<div style="margin-left:auto"><span style="font-size:13px;color:#e4492b;font-weight:400;">'.$servizio.'</span></div>
		  </div>
        </li>';
							$timearray[$timeprenextra]=1;
						}
					}
			
				
			}
			}
                  if(empty($txt[0]) && empty($txt[2])){
					  $testo.='<div>Tutti i tavoli sono stati allocati </div>';
				  }else{
					$testo.='<div class="list-block"><ul>';
					$testo.=$txt['0'];
		            $testo.=$txt['2'];
				    $testo.='</ul></div>';}
			
?>
	<div class="picker-modal" id="popoverord" >
		  <div class="toolbar">
			<div class="toolbar-inner">
			  <div class="left"></div>
			  <div class="right"><a href="#" class="close-picker">Close</a></div>
			</div>
		  </div>
		  <div class="picker-modal-inner ">
		 <div class="page-content"  style="background-color: white"> 
		 <?php
		 echo $testo;
		 ?>
		  </div>
	</div>
	</div>
