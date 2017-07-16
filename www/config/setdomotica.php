<?php 
header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');

$IDutente=$_SESSION['ID'];
$IDstruttura=$_SESSION['IDstruttura'];

$IDdom=$_GET['IDdom'];

$nomedom='';

		
		/*$arr=explode('_',$altro);
		$IDdom=$arr['0'];
		$acc=$arr['1'];
		$man=$arr['2'];
		*/
		
		$query="SELECT etichetta FROM domotica WHERE ID='$IDdom' LIMIT 1";
		$result=mysqli_query($link2,$query);
		$row=mysqli_fetch_row($result);
		$nomedom=$row['0'];




$testo='
            <div class="navbar">
               <div class="navbar-inner">
                  <div class="left" align="center">
				  </div>
                  <div class="center titolonav"  >Set Domotica<br><span style="font-size:12px;">'.$nomedom.'</span></div>
                  <div class="right" onclick="myApp.closeModal()">
						<i class="icon f7-icons">close</i>
				  </div>
               </div>
            </div>
			
		
		<div class="content-block-title titleb">Manuale a Tempo</div>
		<div class="list-block">
		  <ul>
			<li class="item-content">
			  <div class="item-inner">
				<div class="item-title">Durata Accensione</div>
				<div class="item-after">
				
				<select style="width:80px; direction:inherit; padding-left:20px;   font-weight:400; border-radius:4px; border:solid 1px #ccc; font-size:14px;" id="oreatt">
				'.generadurata(60,480,0,60).'
				<option value="604800">Una Settimana</option>
				<option value="31536000">Un Anno</option>
				</select>
				
				</div>
			  </div>
			</li>
			
			
			<li class="item-content">
			  <div class="item-inner" style="width:100%;">
				<div class="item-title" style="width:100%;">
				
					<div class="buttons-row" >
					  <a href="#" class="button button-fill color-teal" " onclick="accendidom2('.$IDdom.',1)">Accendi</a>
					  <a href="#" class="button  button-fill color-indigo"    onclick="accendidom2('.$IDdom.',0)">Spegni</a>
					</div>  
									
				
				
				</div>
			  </div>
			</li>
			
			</ul>
		</div>
		
		
		<div class="content-block-title titleb">Manuale ad Intervallo</div>
		<div class="list-block">
		  <ul>
			<li class="item-content">
			  <div class="item-inner" style="width:100%;">
				<div class="item-title" style="width:50%;">
				<select style="width:10)%; height:30px; direction:inherit;padding-left:33px;  font-weight:400; border-radius:4px; border:solid 1px #ccc; font-size:14px;" id="accendi">'.generaorario(15,0,24).'</select>
				</div>
				<div class="item-after"  style="width:50%;">
				
				<select style="width:100%;height:30px; direction:inherit;   padding-left:33px;   font-weight:400; border-radius:4px; border:solid 1px #ccc; font-size:14px;" id="spegni">'.generaorario(15,0,24).'</select>
				
				</div>
			  </div>
			</li>
			';
				
				
				for($i=0;$i<7;$i++){
					$testo.='
					 <li>
					  <label class="label-checkbox item-content">
						<!-- Checked by default -->
						<input type="checkbox" name="giornidom" value="'.$i.'">
						<div class="item-media">
						  <i class="icon icon-form-checkbox"></i>
						</div>
						<div class="item-inner">
						  <div class="item-title">'.$giorniita[($i+1)].'</div>
						</div>
					  </label>
					</li>
					
					
					';
					
				}
			
			
   
				
				
					
				
				$testo.='
			
			
			
			<li class="item-content">
			  <div class="item-inner" style="width:100%;">
				<div class="item-title" style="width:100%;">
				
					<div class="buttons-row" >
					  <a href="#" class="button button-fill color-teal"  onclick="accendidom('.$IDdom.',1)">Accendi</a>
					  <a href="#" class="button d button-fill color-indigo"   onclick="accendidom('.$IDdom.',0)">Spegni</a>
					</div>  
									
				
				
				</div>
			  </div>
			</li>
			
			</ul>
		</div>
		
	
		';
	
		
		$statoarr=array('Spento','Acceso');
		$query2="SELECT timei,timef,acceso FROM pianificazione WHERE IDdom='$IDdom' ";
		$result2=mysqli_query($link2,$query2);
		if(mysqli_num_rows($result2)>0){
			$testo.='
			
				<div class="content-block-title">Programmi Attivi</div>
					<div class="list-block">
					  <ul>
						
					
				';
			
			
			while($row2=mysqli_fetch_row($result2)){
				$testo.='
				
				<li class="item-content">
			 	 <div class="item-inner">
					<div class="item-title" style="line-height:13px;">'.dataita($row2['0']).'<br><span style="font-size:10px;color:#999;">'.$statoarr[$row2['2']].'</span></div>
					<div class="item-after">
					'.date('H:i',$row2['0']).' - '.date('H:i',$row2['1']).'				
					</div>
				  </div>
				</li>
				
				';
			}
			$testo.='
			
			<li class="item-content">
			  <div class="item-inner" style="width:100%;">
				<div class="item-title" style="width:100%;">
				
					<div class="buttons-row" >
					  <a href="#" class="button button-fill color-red" onclick="modprenot('.$IDdom.','."'0_0'".',63,10,1)">Annulla programmi manuali</a>
					</div>  
				</div>
			  </div>
			</li>
			
			</ul></div>';
		}	
				
			
			
			
		


echo $testo;
?>