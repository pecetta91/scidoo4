<?php
header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');

$time=$_SESSION['lasttimedx'];
			 
$tipoaddnav=$_SESSION['tipoaddnav'];

$func='';

			$ggstart=date('N',$time);
			
			$ggstart2=$ggstart;
			$line1='';
			$line2='';
			
			for($i=0;$i<8;$i++){
				$class='';
				$into='';
				$mex='';
				$classoggi='';
				if(($ggstart2==6)||($ggstart2==7)){
					$classoggi='week';
				}
				
								
				$ins=0;
				$tt=$time+$i*86400;
				$func='';
				switch($tipoaddnav){
					case 0:
						$func='onclick="navigationtxt(13,'.$tt.','."'centrobenesserediv'".',6)"';
					break;
					case 1:
						$func='onclick="navigationtxt(14,'.$tt.','."'ristorantediv'".',6)"';
					break;
				}
				
				
				$line1.='<div class="buttdatesup '.$classoggi.'" '.$func.'>
				<div class="buttdate " id="'.$tt.'">'.$giorniita2[$ggstart2].'<br><b>'.date('d',$tt).'</b><br>'.$mesiita2[date('n',$tt)].'</div></div>
				';
				
				$ggstart2++;
				if($ggstart2==8){$ggstart2=1;}
				
				
			}
			$_SESSION['lasttimedx']=$time+86400*8;
			
			echo $line1;
?>