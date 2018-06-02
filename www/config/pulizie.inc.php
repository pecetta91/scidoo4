<?php
 
if(!isset($inc)){
    header('Access-Control-Allow-Origin: *');
    include('../../config/connecti.php');
    include('../../config/funzioni.php');
    include('../../config/funzionilingua.php');
     
    $IDutente=intval($_SESSION['ID']);
    $IDstruttura=intval($_SESSION['IDstruttura']);
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
}

/*
 
if(isset($_GET['dato1'])){
    if(is_numeric($_GET['dato1'])){
        $vis=$_GET['dato1'];
    }
}else{
    if(isset($_SESSION['vis'])){
        $vis=$_SESSION['vis'];
    }else{
        $vis=1;
    }
}
*/
$vis=2;
if(isset($_GET['dato1'])){
	if($_GET['dato1']!='0'){
        $vis=$_GET['dato1'];
    }}
 
$gg=7;
if(isset($_GET['dato2'])){
    if(is_numeric($_GET['dato2'])){
        $gg=$_GET['dato2'];
    }
}
 
$data=date('Y-m-d',$time);
$giorno=date('N',$time);
$testo='
<input type="hidden" id="timeristo" value="'.$time.'">
<input type="hidden" id="IDtipovis" value="'.$vis.'">
<input type="hidden" id="ggpulizie" value="'.$gg.'">
<input type="hidden" id="stati" value="da pulire_1,occupato_2,pronto_0">
';
 
 
 
 
 
                list($yy, $mm, $dd) = explode("-", $data);
                $time0=mktime(0, 0, 0, $mm, $dd, $yy);
                $timef=$time0+86400;
            $statoarr=array('Pronto','Occupato','Da Preparare');    
            $statocol=array('1dbb68','bb2c1d','d8bf18');        
            $colorebtn=array('green','red','yellow');
             

//elenco arrivi
             
switch($vis){
    case 1:         
         
        $testo.='
        <div class="content-block-title"   style="margin-top:-18px; text-align:center; background:#e57511;color:#fff; line-height:30px; height:30px; border-radius:5px; padding:0px; overflow:hidden; position:relative; ">
        <input type="text" id="datacentro" style="position:absolute; top:0px; left:0px; opacity:0; width:100%; height:30px;">
        <table width="100%;" style="margin-top:-2px; margin-left:-2px;"><tr><td width="50%;" style="background:#d13b23;">'.dataita4($time).'</td><td>'.dataita4(($time+86400*$gg)).'</td></tr></table></div><br>
         
         
        ';
         
        $numarr=0;
         
        for($i=0;$i<7;$i++){
             
            $timeini=$time0+$i*86400;
            $timefin=$timeini+86400;
                 
            $query="SELECT ID,IDv,app,time FROM prenotazioni WHERE time>='$timeini' AND time<'$timefin' AND gg>'0' AND IDstruttura='$IDstruttura' AND stato>='0'"; 
            $result=mysqli_query($link2,$query);
     
             
            if(mysqli_num_rows($result)>0){
                $numarr++;
                $testo.='
                    <div class="content-block-title titleb" style="margin-top:-10px; color:#455ba2; ">'.dataita($timeini).'</div>
                    <div class="list-block accordion-list"  style="margin-bottom:10px;">
                    <ul>
                 
                 
            ';
                 
                while($row=mysqli_fetch_row($result)){
                    $id=$row['1'];
                    $IDapp=$row['2'];
                 
                    $query2="SELECT nome,attivo,stato FROM appartamenti WHERE ID='$IDapp'  AND IDstruttura='$IDstruttura'"; 
                    $result2=mysqli_query($link2,$query2);
                    $row2=mysqli_fetch_row($result2);
                    $nome=$row2['0'];
                     
                    $testo.='
                         
                        <li class="accordion-item" style="border-left:solid 3px #'.$statocol[$row2['2']].';"><a href="#" class="item-content item-link" >
                             
                         
                            <div class="item-inner">
                             
                             
                             
                             
                              <div class="item-title">'.$nome.'</div>
                              <div class="item-after" style="font-size:13px;line-height:12px;text-align:right;font-weight:100;"><div style="border-right:solid 1px #ccc;padding-right:5px;margin-right:5px;">'.date('H:i',$row['3']).'</div><div style="color:#'.$statocol[$row2['2']].';font-weight:600;font-size:13px;">'.$statoarr[$row2['2']].'</div></div>
                            </div></a>
                          <div class="accordion-item-content">
                            <div class="content-block" style="background:#f4f4f4; padding:0px;">
                             
                            <div class="list-block">
                                  <ul>
                                   
                                    <li class="item-content">
                                        <div class="item-title" style="width:100%; padding:0px;">
                                        <p class="buttons-row" style="width:100%; padding:0px;">
   
                                    ';
                                         
                                        $q8="SELECT stato FROM appartamenti WHERE ID='$IDapp' LIMIT 1";
                                        $r8=mysqli_query($link2,$q8);
                                        $row8=mysqli_fetch_row($r8);
                                        $proapp=$row8['0'];
                                     
                                         
                                        foreach ($statoarr as $key=>$dato){
                                            $testo.='<a href="#" onclick="modprenot('.$IDapp.','.$key.',17,10,3)" class="button button12 button-raised';
                                            if($key==$proapp){$testo.=' button-fill ';
                                                switch($key){
                                                    case 0:
                                                        $testo.='color-green';
                                                    break;
                                                    case 1:
                                                        $testo.='color-red';
                                                    break;
                                                    case 2:
                                                        $testo.='color-orange';
                                                    break;
                                                }
                                             
                                            }
                                            $testo.='" style="font-size:12px;">'.$dato.'</a>';
                                        }
                                         
                                         
                                         
                                        $testo.='</p></div>
                                    </li>
                                     
                                     
                                   
                                   
                                   
                                   
                                    <li class="item-content">
                                      <div class="item-inner">
                                        <div class="item-title">Persone</div>
                                        <div class="item-after">';
                                         
                                        $query2="SELECT GROUP_CONCAT(IDrest SEPARATOR ',') FROM infopren WHERE IDpren='$id' AND pers='1' GROUP BY IDstr";
                                        $result2=mysqli_query($link2,$query2);
                                        if(mysqli_num_rows($result2)>0){
                                            $row2=mysqli_fetch_row($result2);
                                            $group=$row2['0'].',';
                                            $testo.=txtrestr($group,0);
                                        }
                                         
                                         
                                         
                                        $testo.='</div>
                                      </div>
                                    </li>
                                     
                                     
                                     
                                    <li class="item-content">
                                      <div class="item-inner">
                                        <div class="item-title">Letti</div>
                                        <div class="item-after">';
                                         
                                         $query2="SELECT infopren.nome,tiporestr.restrizione  FROM infopren,tiporestr WHERE infopren.IDpren='$id' AND infopren.IDstr='$IDstruttura' AND infopren.pers='0' AND infopren.IDrest=tiporestr.ID";
                                        $result2=mysqli_query($link2,$query2);
                                        $num2=mysqli_num_rows($result2);
                                        if($num2>0){
                                                $j=1;
                                                while($row=mysqli_fetch_row($result2)){
                                                    if($row['0']!=0){
                                                    $testo.='N.'.$row['0'].' '.$row['1'];
                                                    if($j<$num2){$testo.=', ';if(($j%2)==0)$testo.='<br>';}
                                                }
                                            $j++;
                                            }   
                                        }else{
                                            $testo.='Nessuna disposizione';
                                        }
                                         
                                        $testo.='</div>
                                      </div>
                                    </li>
                                     
                                     
                                     
                                    <li class="item-content" style="height:80px;">
                                      <div class="item-inner" style="height:70px;">
                                        <div class="item-title">Note All.</div>
                                        <div class="item-after">
                                        <textarea style="width:100%; height:65px; font-size:11px; line-height:11px; padding:2px; border-radius:3px; margin-top:-15px; border:solid 1px #ccc;" placeholder="Note Alloggio"></textarea>
                                        ';
                                         
                                        $testo.='</div>
                                      </div>
                                    </li>
                            ';
                             
                                 
                         
                                 
                         
                            $testo.='
                             </ul></div>
                              
                            </div>
                          </div>
                        </li>
                        ';
                }
                $testo.='</ul></div>';
            }           
        }
        if($numarr==0){
            $testo.='<span style="font-size:16px;">Non ci sono arrivi questa settimana</span>';
        }
    break;
    case 2:
         
        $time=oraadesso($IDstruttura);
        $testo.='
            <div class="content-block-title titleb" >Alloggi Struttura
			<br>
			<span>Clicca per Dettagli e Modifica Stato</span></div>
              
            ';
		
		/* <div class="list-block accordion-list">
                  <ul>*/
		
        $data0=date('Y-m-d',$time);
        $datai=date('Y-m-d',($time-86400));
        $dataf=date('Y-m-d',$time+7*86400);
         
        list($yy, $mm, $dd) = explode("-", $data0);
        $time0=mktime(0, 0, 0, $mm, $dd, $yy);
         
        $prenapp=array(array());
         
        $query="SELECT p.IDv,p.app,p.time,p.gg FROM prenotazioni as p,prenextra as pr WHERE pr.IDstruttura='$IDstruttura' AND pr.IDpren=p.IDv AND FROM_UNIXTIME(pr.time,'%Y-%m-%d') BETWEEN '$datai' AND '$dataf' AND IDtipo='8'";
        $result=mysqli_query($link2,$query);
        if(mysqli_num_rows($result)>0){
            while($row=mysqli_fetch_row($result)){
                $IDpren=$row['0'];
                $IDapp=$row['1'];
                $times=$row['2'];
                     
                $gg=floor(($times-$time0)/86400);
                 
                $giorni=$row['3'];
                for($kk=0;$kk<$giorni;$kk++){
                    $prenapp[$IDapp][$kk+$gg]=$IDpren;
                }
            }
        }
         
         
         
        $ggstart=date('N',($time-86400));
         
        $query="SELECT nome,attivo,stato,ID FROM appartamenti WHERE IDstruttura='$IDstruttura' AND attivo='1' ORDER BY stato DESC"; 
        $result=mysqli_query($link2,$query);
        while($row=mysqli_fetch_row($result)){
            $IDapp=$row['3'];
            $proapp=$row['2'];
            $class='';
            switch($row['2']){
                case 0:
                    $class='tav4';
                break;
                case 1:
                    $class='tav1';
                break;
                case 2:
                    $class='tav3';
                break;
            }
             
            $IDpren=0;
            $prox='<table class="dispon"><tr>';
             
            $ggstart2=$ggstart;
             
            for($i=-1;$i<8;$i++){
				$class='';
				if($i==-1){
					$class.=' op ';
				}
                
                $into='';
                if($i==0){$into='X ';}
                if(isset($prenapp[$IDapp][$i])){
                    if($IDpren==0)$IDpren=$prenapp[$IDapp][$i];
                    $red=0;
                    if(isset($prenapp[$IDapp][$i-1])){
                        if($prenapp[$IDapp][$i-1]!=$prenapp[$IDapp][$i]){
                            $red=1;
                        }
                    }
                    if($red==0){
                        //$prox.='<td class="red"></td>';
                        $class.='red';
                    }else{
                        //$prox.='<td class="orange"></td>';    
                        $class.='orange';
                    }
                         
                }else{
                    $class.='green';
                    //$prox.='<td class="green"></td>';
                }
                 
                $prox.='<td class=" '.$class.'">'.$giorniita3[$ggstart2].'</td>';
                 
                $ggstart2++;
                if($ggstart2==8){$ggstart2=1;}
                 
                 
            }
            $prox.='</tr></table>';
 
             
            //$valorebtn=''; Perche' dichiare due volte la stessa variabile?
            $valorebtn='';
             
            //Non dovevi stampare un pulsanto per ogni stato?
            //gli stati li hai su statoarr
            foreach ($statoarr as $key =>$dato){ //in questo modo scorri l'array
                if($key!=$row['2']){ //se $key e' diverso dallo stato attuale stampa il pulsante
                        $valorebtn.='
                    buttons.push({
                    text: "'.$dato.'",
					 color: "'.$colorebtn[$key].'",
                    onClick: function () {
                        modprenot('.$IDapp.','.$key.',17,10,3);
                    }
                }); ';
                }
                 
                 
            }
         
            //questa parte qui va bene
            $testo.=' 
            <input type="hidden" value="'.base64_encode($valorebtn).'" id="valorebtn'.$IDapp.'">
            ';
             
             
             
            $testo.='
			
			<div class="row rowlist no-gutter" onclick="azionevideo('.$IDapp.','.$row['2'].')">
					<div class="col-70">'.strtoupper($row['0']).'</div>
					<div class="col-30" style="color:#'.$statocol[$row['2']].';font-weight:600; text-align:right; font-size:13px;">'.$statoarr[$row['2']].'</div>
					<div class="col-100" align="center">'.$prox.'</div>
				</div>
					
									
			
			
			
                    
                    ';
                
           
         
        }
         
        //$testo.='<ul></div>';
         
     
     
    break;
		
	case 3:
		
		
		
		$txteval=array();
		
		
			$testo.='
		<input type="hidden" value="'.$vis.'" id="vispulizie">
		<input type="hidden" value="'.$time.'" id="datapulizie">
		<a href="#" class="button button-round button-fill " id="buttdata" style="width:180px; margin-left:10px;"><i class="f7-icons" style="font-size:13px;">today</i> &nbsp;&nbsp;'.dataita($time).'</a>
		';
		
		
			$prenstato=array();
			$arrayst=array();

			$timeoggi=oraadesso($IDstruttura);

		
		
			list($yy, $mm, $dd) = explode("-", $data);
			$time0=mktime(0, 0, 0, $mm, $dd, $yy);
			$timef=$time0+86400;
			$statoarr=array('Pronto','Occupato','Da Preparare');	
			$statocol=array('1dbb68','bb2c1d','d8bf18');		
			
			

		
			
			$timeini=$time0+$i*86400;
			$timefin=$timeini+86400;
				
			$testo.='<div class="content-block-title titleb">Arrivi</div>
				<div class="row rowlist" style="background:#f1f1f1;">
						<div class="col-15">Arr.</div>
						<div class="col-45 coltitle">Alloggio</div>
						<div class="col-20">Pulizia</div>
						<div class="col-20 centercol">Arrivo</div>
						</div>
				';


			$query="SELECT ID,IDv,app,time,stato FROM prenotazioni WHERE time>='$timeini' AND time<'$timefin' AND IDstruttura='$IDstruttura' AND stato>='0' AND gg>'0' ORDER BY time"; 
			$result=mysqli_query($link2,$query);
			if(mysqli_num_rows($result)>0){
				while($row=mysqli_fetch_row($result)){
					$id=$row['1'];
					$IDapp=$row['2'];
					$statoarrtxt='<i class="f7-icons icon" style="color:#cc4d2a">close</i>';
					if(($row['4']==4)||($row['4']==3)){
						$statoarrtxt='<i class="f7-icons icon" style="color:#34a76d">check</i>';
					}
					
					$query2="SELECT nome,attivo,stato FROM appartamenti WHERE ID='$IDapp' AND IDstruttura='$IDstruttura'"; 
					$result2=mysqli_query($link2,$query2);
					$row2=mysqli_fetch_row($result2);
					$nomeapp=$row2['0'];
					$attivo=$row2['1'];
					$statopul=$row2['2'];
					
					
					
					foreach ($statoarr as $key =>$dato){ 
						if($key!=$statopul){
								$valorebtn.='
							buttons.push({
							text: "'.$dato.'",
							 color: "'.$colorebtn[$key].'",
							onClick: function () {
								modprenot('.$IDapp.','.$key.',17,10,3);
							}
						}); ';
						}  
					}
					$txteval[$IDapp]=' <input type="hidden" value="'.base64_encode($valorebtn).'" id="valorebtn'.$IDapp.'">';
					
					
					
					$statotxt='';
					
					if($attivo==1){
						$statotxt='<div style="color:#'.$statocol[$row2['2']].';font-weight:400;">'.$statoarr[$row2['2']].'</div>';
					}
					
					$testo.='
						
						<div class="row rowlist" onclick="azionevideo('.$IDapp.','.$statopul.')">
						<div class="col-15">'.date('H:i',$row['3']).'</div>
						<div class="col-45 coltitle">'.$nomeapp.'</div>
						<div class="col-20">'.$statotxt.'</div>
						<div class="col-20 centercol">'.$statoarrtxt.'</div>
						
						</div>';
					
				}
				
			
			}else{
				$testo.='
				<div class="row rowlist">
					<div class="col-100 h40">Non ci sono arrivi oggi</div>
				</div>
				';
			}
			$testo.='<br><br>';





			$testo.='<div class="content-block-title titleb">Partenze</div>
				<div class="row rowlist" style="background:#f1f1f1;">
						<div class="col-15">Part.</div>
						<div class="col-45 coltitle">Alloggio</div>
						<div class="col-20">Pulizia</div>
						<div class="col-20 centercol">Partito</div>
						</div>
				';


			$query="SELECT ID,IDv,app,checkout,stato FROM prenotazioni WHERE FROM_UNIXTIME(checkout,'%Y-%m-%d')='$data' AND FROM_UNIXTIME(time,'%Y-%m-%d')!='$data'AND IDstruttura='$IDstruttura' AND stato>='0' AND gg>'0' ORDER BY checkout"; 
			$result=mysqli_query($link2,$query);
			if(mysqli_num_rows($result)>0){
				while($row=mysqli_fetch_row($result)){
					$id=$row['1'];
					$IDapp=$row['2'];
					$statoarrtxt='<i class="f7-icons icon" style="color:#cc4d2a">close</i>';
					if(($row['4']==4)){
						$statoarrtxt='<i class="f7-icons icon" style="color:#34a76d">check</i>';
					}
					
					$query2="SELECT nome,attivo,stato FROM appartamenti WHERE ID='$IDapp' AND IDstruttura='$IDstruttura'"; 
					$result2=mysqli_query($link2,$query2);
					$row2=mysqli_fetch_row($result2);
					$nomeapp=$row2['0'];
					$attivo=$row2['1'];
					$statopul=$row2['2'];
					
					
					if(!isset($txteval[$IDapp])){
						foreach ($statoarr as $key =>$dato){ 
							if($key!=$statopul){
									$valorebtn.='
								buttons.push({
								text: "'.$dato.'",
								 color: "'.$colorebtn[$key].'",
								onClick: function () {
									modprenot('.$IDapp.','.$key.',17,10,3);
								}
							}); ';
							}  
						}
						$txteval[$IDapp]=' <input type="hidden" value="'.base64_encode($valorebtn).'" id="valorebtn'.$IDapp.'">';
					}
					
					
					
					
					
					
					$statotxt='';
					
					if($attivo==1){
						$statotxt='<span style="color:#'.$statocol[$row2['2']].';font-weight:400;">'.$statoarr[$row2['2']].'</span>';
					}
					
					$testo.='
						
						<div class="row rowlist" onclick="azionevideo('.$IDapp.','.$statopul.')">
						<div class="col-15">'.date('H:i',$row['3']).'</div>
						<div class="col-45 coltitle">'.$nomeapp.'</div>
						<div class="col-20 coltitle">'.$statotxt.'</div>
						<div class="col-20 centercol">'.$statoarrtxt.'</div>
						
						</div>';
					
					
					
				}
				
			}else{
				$testo.='
				<div class="row rowlist" onclick="navigation(3,'."'".$id."'".')">
					<div class="col-100 h40">Non ci sono partenze oggi</div>
				</div>
				';
			}
			$testo.='<br><br>';


			$testo.='<div class="content-block-title titleb">Permanenze</div>
				<div class="row rowlist" style="background:#f1f1f1;">
						<div class="col-30 ">Arrivato il</div>
						<div class="col-40 coltitle">Alloggio</div>
						<div class="col-30 rightcol">Parte il</div>
						</div>
				';


			$query="SELECT ID,IDv,app,checkout,stato,time FROM prenotazioni WHERE FROM_UNIXTIME(checkout,'%Y-%m-%d')!='$data' AND FROM_UNIXTIME(time,'%Y-%m-%d')!='$data' AND '$data' BETWEEN  FROM_UNIXTIME(time,'%Y-%m-%d') AND  FROM_UNIXTIME(checkout,'%Y-%m-%d') AND IDstruttura='$IDstruttura' AND stato>='0' AND gg>'0' ORDER BY time"; 
			$result=mysqli_query($link2,$query);
			if(mysqli_num_rows($result)>0){
				while($row=mysqli_fetch_row($result)){
					$id=$row['1'];
					$IDapp=$row['2'];
					$statoarrtxt='<i class="f7-icons icon" style="color:#cc4d2a">close</i>';
					if(($row['4']==4)){
						$statoarrtxt='<i class="f7-icons icon" style="color:#34a76d">check</i>';
					}
					
					$query2="SELECT nome,attivo,stato FROM appartamenti WHERE ID='$IDapp' AND IDstruttura='$IDstruttura'"; 
					$result2=mysqli_query($link2,$query2);
					$row2=mysqli_fetch_row($result2);
					$nomeapp=$row2['0'];
					$attivo=$row2['1'];
					$statopul=$row2['2'];
				
					if(!isset($txteval[$IDapp])){
						foreach ($statoarr as $key =>$dato){ 
							if($key!=$statopul){
									$valorebtn.='
								buttons.push({
								text: "'.$dato.'",
								 color: "'.$colorebtn[$key].'",
								onClick: function () {
									modprenot('.$IDapp.','.$key.',17,10,3);
								}
							}); ';
							}  
						}
						$txteval[$IDapp]=' <input type="hidden" value="'.base64_encode($valorebtn).'" id="valorebtn'.$IDapp.'">';
					}
					
					
					$testo.='
						
						<div class="row rowlist"  onclick="azionevideo('.$IDapp.','.$statopul.')">
						<div class="col-30  f12">'.dataita8($row['5']).'</div>
						<div class="col-40 coltitle">'.$nomeapp.'</div>
						<div class="col-30 rightcol f12">'.dataita8($row['3']).'</div>
						
						</div>';
					
					
					
				}
				
			}else{
				$testo.='
				<div class="row rowlist" onclick="navigation(3,'."'".$id."'".')">
					<div class="col-100 h40">Non ci sono partenze oggi</div>
				</div>
				';
			}
			$testo.='<br><br>';

		
		
		
			echo implode('',$txteval);
				
		
		
		
		
		
		
	break;
		
		
	case 5:
		$prenstato=array();
	    $arrayst=array();
		
		$timeoggi=oraadesso($IDstruttura);
		
		$datai=date('Y-m-d',$time-86400);
		$dataf=date('Y-m-d',$time+7*86400);
		$prenapp=array(array());  
        $query="SELECT p.IDv,p.app,p.time,p.gg,p.checkout,p.stato FROM prenotazioni as p,prenextra as pr WHERE pr.IDstruttura='$IDstruttura' AND pr.IDpren=p.IDv AND FROM_UNIXTIME(pr.time,'%Y-%m-%d') BETWEEN '$datai' AND '$dataf' AND IDtipo='8' ";	
		
        $result=mysqli_query($link2,$query);
        if(mysqli_num_rows($result)>0){
            while($row=mysqli_fetch_row($result)){
                $IDpren=$row['0'];
                $IDapp=$row['1'];
                $times=$row['2'];
				$check=$row['4'];
				$statopren=$row['5'];
                $gg=floor(($times-$time0)/86400); 
				
				//echo $gg.'<br>';
				
                $giorni=$row['3'];
				
                for($kk=0;$kk<$giorni;$kk++){
					
                    $prenapp[$IDapp][$kk+$gg]=$IDpren;
					$prenstato[$IDapp][$kk+$gg]=$statopren;
					$s=$kk+$gg;
                }
            }
        }
	
		
		
		
		$appstati=array(array());
		
		$stato2app=array();
		//0 Libero
		//1 Occupato
		//2 Da pulire urgente
		
		
		
		
		
		$infoapp=array(array()); //info 0- Nome 1 - Stato pulizia 
		
		
		$query="SELECT stato,ID,nome FROM appartamenti WHERE IDstruttura='$IDstruttura' AND attivo='1' ORDER BY stato DESC"; 
        $result=mysqli_query($link2,$query);
		while($row=mysqli_fetch_row($result)){
			$statoapp=$row['0'];
			$IDapp=$row['1'];
			$IDprenappo=0;
			
			$infoapp[$IDapp][0]=$row['2']; //nome
			$infoapp[$IDapp][1]=$row['0']; //stato pulizia
			
			for($i=-1;$i<1;$i++){
				switch($i){
					case -1: //ieri
						if(isset($prenapp[$IDapp][$i])){ //ieri occupato
							$IDprenappo=$prenapp[$IDapp][$i];
						}
					break;
					case 0: //oggi
						//$testo.=$IDprenappo.'--'.$prenapp[$IDapp][$i].'--'.$i.'<br>';;
						
						if(isset($prenapp[$IDapp][$i])){
							
							if($IDprenappo!=0){ 
								if($prenapp[$IDapp][$i]!=$IDprenappo){ //da pulire urgente
									$stato2app[$IDapp]=2;
								}else{//occupato anche ieri
									$stato2app[$IDapp]=1;
								}
							}else{ //oggi arrivo
								$stato2app[$IDapp]=1;
							}
						}else{ //oggi libero
							$stato2app[$IDapp]=0;
						}
					break;
				}
			}
			$valorebtn='';
             
           
            foreach ($statoarr as $key =>$dato){ 
                if($key!=$statoapp){
                        $valorebtn.='
                    buttons.push({
                    text: "'.$dato.'",
					 color: "'.$colorebtn[$key].'",
                    onClick: function () {
                        modprenot('.$IDapp.','.$key.',17,10,3);
                    }
                }); ';
                }  
            }
            $testo.=' 
            <input type="hidden" value="'.base64_encode($valorebtn).'" id="valorebtn'.$IDapp.'">
            ';
			
		}
			
		
		
		$testo.='
		<input type="hidden" value="'.$vis.'" id="vispulizie">
		<input type="hidden" value="'.$time.'" id="datapulizie">
		<a href="#" class="button button-round button-fill " id="buttdata" style="width:180px; margin-left:10px;"><i class="f7-icons" style="font-size:13px;">today</i> &nbsp;&nbsp;'.dataita($time).'</a><br/>
		';
		
		
		//$testo.=var_dump($stato2app);
			
		$stampa=array();
		$stampa['0']='';//0 da pulire urgente
		$stampa['1']='';//1 occupato
		$stampa['2']='';//2 libero da preparare
		$stampa['3']='';//3 Libero e Pronto
		
		
		
		foreach ($infoapp as  $IDapp =>$arr){
			if(!empty($arr)){
			$nomeapp=$arr['0'];
			$statopul=$arr['1'];
			
			$IDstampa=0;
			
			$info1='';
			$info2='';
				
				$colinfo='color:#777;';
			
			switch($stato2app[$IDapp]){
				case 0: //oggi libero
					
					$query="SELECT time,ID FROM prenotazioni WHERE IDstruttura='$IDstruttura' AND time>='$time'  AND app='$IDapp' ORDER BY time LIMIT 1";
					$result=mysqli_query($link2,$query);
				    if(mysqli_num_rows($result)>0){
						
						$row=mysqli_fetch_row($result);
						$timeprox=$row['0'];
						$IDpren=$row['1'];
						$info1='Arrivo: Oggi '.dataita4($timeprox);
					
					}else{
						$info1='Non ci sono arrivi';
					}
					
					if($statopul==0){ //pulito
							$IDstampa=3;
							//prossimo arrivo
						
						}else{//da pulire
							$IDstampa=2;
						}
					
					
				break;
				case 1: //oggi occupato
					
					
					$IDstampa=1;
					
					$query="SELECT time,ID FROM prenotazioni WHERE IDstruttura='$IDstruttura' AND app='$IDapp' AND time>='$time0' ORDER BY time LIMIT 1";
					$result=mysqli_query($link2,$query);
					$row=mysqli_fetch_row($result);
					$timeprox=$row['0'];
					$IDpren=$row['1'];
					$info1='Prossimo arrivo:'.dataita4($timeprox);
					
					$datacheckout=date('Y-m-d',$timeprox);
					
					$query="SELECT checkout FROM prenotazioni WHERE IDstruttura='$IDstruttura' AND app='$IDapp' AND FROM_UNIXTIME(checkout,'%Y-%m-%d')<'$datacheckout' ORDER BY checkout DESC LIMIT 1";
					$result=mysqli_query($link2,$query);
					$row=mysqli_fetch_row($result);
					$timepart=$row['0'];
					$info2='Libero da: <strong>'.calcolatime($time,$timepart,'').'</strong>';
					
				break;
				case 2: //da pulire urgente
					
					
					$query="SELECT time,stato,ID FROM prenotazioni WHERE IDstruttura='$IDstruttura' AND time>='$time0' AND app='$IDapp' ORDER BY time LIMIT 1";
					$result=mysqli_query($link2,$query);
					$row=mysqli_fetch_row($result);
					$timeprox=$row['0'];
					$statopreninto=$row['1'];
					$IDpren=$row['2'];
					if($statopreninto!=3){
						$IDstampa=0;
					}else{
						$IDstampa=1;
					}
					
					$info1='Prossimo arrivo: <strong>'.date('H:i',$timeprox).'</strong>';
					
					$datacheckout=date('Y-m-d',$timeprox+86400);
					
					$query="SELECT checkout FROM prenotazioni WHERE IDstruttura='$IDstruttura' AND app='$IDapp' AND FROM_UNIXTIME(time,'%Y-%m-%d')<'$datacheckout' ORDER BY checkout DESC LIMIT 1";
					$result=mysqli_query($link2,$query);
					$row=mysqli_fetch_row($result);
					$timepart=$row['0'];
					$info2='Libero dalle: <strong>'.date('H:i',$timepart).'</strong>';
					$colinfo='color:#bf2a2a;';
				break;
					
			}
			
			$stampa[$IDstampa].='<div class="row rowlist no-gutter" style="color:#000;" onclick="azionevideo('.$IDapp.','.$statopul.')">
									
									<div class="col-15" style="background:#bf2a4a; padding:2px;color:#fff; text-align:center; border-radius:6px; font-size:12px; font-weight:400;">									
									'.date('H:i',$timeprox).'</div>
									
									<div class="col-55" style="text-transform:uppercase; font-weight:600;">'.$nomeapp.'</div>
									
									
									<div class="col-30" style="color:#'.$statocol[$statopul].';font-weight:600;text-align:right;" >'.$statoarr[$statopul].'</div>
									
									';
				
					$stampa[$IDstampa].='<div class="col-50" style="font-size:12px;color:#666;">ID: '.$IDpren.'</div><div class="col-50" style="font-size:12px; padding:5px; text-align:right; '.$colinfo.'">'.$info2.'</div>';
				
				
				$stampa[$IDstampa].='</div>';
				
				/*
			$stampa[$IDstampa].='<li class="item-link item-content" onclick="azionevideo('.$IDapp.','.$statopul.')" >
						<div class="item-inner">
							<div class="item-title">
								ID: '.$IDpren.' - '.$nomeapp.'
							<br>
							
							<span style="font-size:10px;">'.$info1.'</span>
							
							
							</div>
							<div class="" style="color:#'.$statocol[$statopul].';font-weight:600; text-align:right; line-height:14px; width:150px; font-size:13px;padding:2px;">'.$statoarr[$statopul].'<br><span style="font-size:10px;">'.$info2.'</span></div>
							  </div>
							</li>';*/
			}
		}
		//,'Da pulire - Libero','Libero e Pronto'
		
		$titoli=array('Alloggi da Liberare','Alloggi Liberi');
		
		for($i=0;$i<2;$i++){
			//$testo.='<div class="content-block-title titleb">'.$titoli[$i].'</div>';
			if(!empty($stampa[$i])){
					$testo.=$stampa[$i];
				
				//$testo.='<div style="margin-left:15px;text-align:left;">Non ci sono appartamenti </div>';
			}/*else{
				//$testo.='<div class="list-block"><ul>'.$stampa[$i].'</ul></div>';
				$testo.=$stampa[$i];
			} */
			
			
		}
		
		
		
		
		
		

		
		
		
		
		
		
		
		
		/*
		
		
        $ggstart=date('N',($time-86400));
		$ggoggi=date('N',$time);
        $query="SELECT nome,attivo,stato,ID FROM appartamenti WHERE IDstruttura='$IDstruttura' AND attivo='1' ORDER BY stato DESC"; 
        $result=mysqli_query($link2,$query);
        while($row=mysqli_fetch_row($result)){
            $IDapp=$row['3'];
            $proapp=$row['2'];
            $IDpren=0;
            $ggstart2=$ggstart;
			$valorebtn='';
			
			foreach ($statoarr as $key =>$dato){ 
                if($key!=$row['2']){ 
                        $valorebtn.='
                    buttons.push({
                    text: "'.$dato.'",
					 color: "'.$colorebtn[$key].'",
                    onClick: function () {
                        modprenot('.$IDapp.','.$key.',17,10,3);
                    }
                }); ';
                }                 
            }
			$testo.='<input type="hidden" value="'.base64_encode($valorebtn).'" id="valorebtn'.$IDapp.'">';
			
				$query3="SELECT time,checkout FROM prenotazioni WHERE app='$IDapp' AND IDstruttura='$IDstruttura' AND time>'$time' ORDER BY time LIMIT 1";
						$result3=mysqli_query($link2,$query3);
						if(mysqli_num_rows($result)>0){
						$row3=mysqli_fetch_row($result3);
						$lastcheckout=$row3['1'];
						$lastcheckin=$row3['0'];
						$ora=date('G:i',$lastcheckin);
						$temporim=calcolatime($lastcheckin,$time);
						if(empty($temporim)){
							$tempo='';
						}
						else{
						  $tempo='</br><span style="font-size:10px">Prossimo arrivo: '.$temporim.' '.$ora.'</span>';
						}
				}else{
					$tempo='';
				}
			
			
			
			
			
			
            for($i=-1;$i<7;$i++){
                $class='';
                $into='';
                if($i==0){$into='X ';}
                if(isset($prenapp[$IDapp][$i])){
                    if($IDpren==0)$IDpren=$prenapp[$IDapp][$i];
                    $red=0;
                    if(isset($prenapp[$IDapp][$i-1])){
                        if($prenapp[$IDapp][$i-1]!=$prenapp[$IDapp][$i]){
                            $red=1;
                        }
                    }
                    if($red==0){
                        //$prox.='<td class="red"></td>';
                        $class.='red';
						$stat=0;
						$orad=date('G:i',$check);	
						$orario=calcolatime($check,$time);
						//$libero='</br><span style="font-size:10px">libero: '.$orario.' '.$orad.'</span>';
						
                    }else{
                        //$prox.='<td class="orange"></td>';    
                        $class.='orange';
						$stat=1;
						$orad=date('G:i',$check);
						$orario=calcolatime($check,$time);
						if($prenstato[$IDapp][$i]==3){	
						echo $IDapp;
					    $stat=0;
						}
						
					    $libero='</br><span style="font-size:10px">libero: '.$orario.' '.$orad.'</span>';
                    }
                         
                }else{
                    $class.='green';
					$stat=2;
                    //$prox.='<td class="green"></td>';
                }
				if($ggstart2==$ggoggi)
				{
					if($stat==2)
					{
						
							$arrayst[$stat][$proapp].='<li class="item-link item-content" onclick="azionevideo('.$IDapp.','.$proapp.')" >
						<div class="item-inner">
							<div class="item-title" style="margin-left:10px;margin-right:auto;">
							<span style="font-size:16px;font-weight:600;color:black;">'.$row['0'].'</span>'.$tempo.'</div>
							
							<div class="item-after" style="color:#'.$statocol[$proapp].';font-weight:600; font-size:13px;">'.$statoarr[$proapp].'</div>
							  </div>
							</li>';
						
					}
					else
					{
						
					
					$arrayst[$stat].='<li class="item-link item-content" onclick="azionevideo('.$IDapp.','.$proapp.')" >
						<div class="item-inner">
							<div class="item-title" style="margin-left:10px;margin-right:auto;">
							<span style="font-size:16px;font-weight:600;color:black;">'.$row['0'].'</span>'.$tempo.''.$libero.'</div>
							<div class="item-after">
							<span style="color:#'.$statocol[$proapp].';font-weight:600; font-size:13px;">'.$statoarr[$proapp].'</span></div>
							  </div>
							</li>';
					}
				}                 
                $ggstart2++;
				$libero='';
                if($ggstart2==8){$ggstart2=1;}     
            }
          }

		
        
		$testo.='<div class="content-block-title titleb">da pulire urgente</div>';
   	if(empty($arrayst[1])){
				$testo.='<div style="margin-left:15px;text-align:left;">Non ci sono appartamenti </div>';
			}else{
					$testo.='<div class="list-block"><ul>';
					$testo.=$arrayst[1];
				    $testo.='</ul></div>';
				} 
		
		$testo.='<div class="content-block-title titleb">occupati </div>';
		if(empty($arrayst[0])){
			$testo.='<div style="margin-left:15px;text-align:left;">Non ci sono appartamenti </div>';
		}else{
			$testo.='<div class="list-block"><ul>';
			$testo.=$arrayst[0];
			$testo.='</ul></div>';
		} 
		
		
		$testo.='<div class="content-block-title titleb">libero da preparare</div>';
		if(empty($arrayst[2][2])){
			$testo.='<div style="margin-left:15px;text-align:left;">Non ci sono appartamenti </div>';
		}else{
			$testo.='<div class="list-block"><ul>';
			$testo.=$arrayst[2][2];
		    $testo.='</ul></div>';
		} 
		
		
		$testo.='<div class="content-block-title titleb">libero pronto</div>';
		if(empty($arrayst[2][0])){
			$testo.='<div style="margin-left:15px;text-align:left;">Non ci sono appartamenti </div>';
		}else{
			$testo.='<div class="list-block"><ul>';
			$testo.=$arrayst[2][0];
			$testo.='</ul></div>';
		} 
		
		*/
		
		break;
		
		
		case 4:
		
			$testo.='
		<input type="hidden" value="'.$vis.'" id="vispulizie">
		<input type="hidden" value="'.$time.'" id="datapulizie">
		<a href="#" class="button button-round button-fill " id="buttdata" style="width:180px; margin-left:10px;"><i class="f7-icons" style="font-size:13px;">today</i> &nbsp;&nbsp;'.dataita($time).'</a><br/>
		';
		
		
		$pulizie=array();
		$titolo=array();
		
		
		//prendo tutte le pren di oggi con la pulizia scelta
		$query="SELECT px.ID,pr.IDv,pr.app,px.extra,px.modi FROM prenotazioni as pr,prenextra as px WHERE FROM_UNIXTIME(pr.time,'%Y-%m-%d')='$data'  AND pr.IDstruttura='$IDstruttura' AND px.IDtipo='5' AND pr.IDv=px.IDpren";
		 $result=mysqli_query($link2,$query);
		 if(mysqli_num_rows($result)>0){
		 while($row=mysqli_fetch_row($result)){
		$ID=$row['0'];	 
	    $IDpren=$row['1'];
		$app=$row['2'];
		$extra=$row['3']; 
		$mod=$row['4'];
		$check='';
			if($mod<0)
			{
				$check='checked';
			}
		$nomepren=estrainome($IDpren);	 
			 
		$query2="SELECT ID,nome,stato FROM appartamenti WHERE IDstruttura='$IDstruttura' AND  ID='$app'";
		$result2=mysqli_query($link2,$query2);
		$row2=mysqli_fetch_row($result2);
			 
		$query3="SELECT servizio,descrizione FROM servizi WHERE IDstruttura='$IDstruttura' AND ID='$extra' AND IDtipo='5'  ";	
		$result3=mysqli_query($link2,$query3);
		$row3=mysqli_fetch_row($result3);
		$serv=$row3['0'];
		$desc=$row3['1'];
			 
		$titolo[$extra]=$serv;	 
			
			  $pulizie[$extra].=' 
			  
	<li>
		<label class="label-checkbox item-content">
					<input type="checkbox" onclick="modprenot('.$extra.','."'servizio".$IDpren."'".',142,7)"  '.$check.' name="my-checkbox" id="servizio'.$IDpren.'">
					  <div class="item-media">
						  <i class="icon icon-form-checkbox"></i>
					  </div>
          		<div class="item-inner">
            		<div class="item-title">
					<span style="font-size:14px;">'.$nomepren.'</span><br/>
					<span style="font-size:12px;">'.$row2['1'].'</span></div>
					<div class="item-after">'.$desc.'</div>
          		</div>
		 </label>
	</li>';
			 /*<div class="item-inner">
            <div class="item-title" style="margin-left:10px;margin-right:auto;">
			<span style="font-size:16px;color:black;">'.$row2['1'].'</span>
			</div>
			<div class="item-after" style="color:#'.$statocol[$row2['2']].';font-weight:600; font-size:13px;">'.$statoarr[$row2['2']].'</div>
          </div>*/
			 
   }
   }
		
		foreach($titolo as $indice =>$val )
		{
			$testo.='<div class="content-block-title titleb">'.$val.'</div>';
			if(empty($pulizie[$indice])){
				$testo.='<div style="margin-left:15px;text-align:left;">Non ci sono appartamenti</div>';
			}else{
					$testo.='<div class="list-block"><ul>';
					$testo.=$pulizie[$indice];
				    $testo.='</ul></div>';
				} 
			
			
		}
		
		
		break;
		
}	


/*
if(($vis==3) || ($vis==4))
{
						$timeoggi=time();
						$timedmn=time()+86400;
						$domani=$time+86400;
						$stringa="'".$domani.','.$vis."'";
                        $stringa2="'".$timeoggi.','.$vis."'";
                     
              
                     

                    list($yy, $mm, $dd) = explode("-",date('Y-m-d',$timeoggi));
					$timg=mktime(0, 0, 0, $mm, $dd, $yy);
                    
						if($time0==$timg)
						{
							$minus='';
						}
						else{
							$minus='<a href="#" onclick="navigationtxt(15,'.$stringa2.','."'".'puliziediv'."'".',0);">
				 <i class="f7-icons"" style="color:#fff;">chevron_left</i></a>';
						}
					list($yy, $mm, $dd) = explode("-",date('Y-m-d',$timedmn));
					$tidm=mktime(0, 0, 0, $mm, $dd, $yy);

						if($time0==$tidm){
							$plus='';
						}
						else{
							$plus='<a href="#" onclick="navigationtxt(15,'.$stringa.','."'".'puliziediv'."'".',0);">
				 <i class="f7-icons" style="color:#fff;">chevron_right</i></a>';
						}
						
                        
						

						<div class="bottombarpren" style="background:#f1f1f1;z-index:999;" align="center">
						<button>'.date('Y-m-d',$time).'</button>
						<button onclick="navigationtxt(15,'.$stringa.','."'".'puliziediv'."'".',0);">'.date('Y-m-d',$domani).'</button>
						<input type="text" value="'.$timeoggi.'">
			</div>
                
         
}
 */
 
            echo $testo;    
 
?>