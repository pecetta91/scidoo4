<?php 
header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');

$IDutente=$_SESSION['ID'];
$IDstruttura=$_SESSION['IDstruttura'];
$ricerca=mysqli_real_escape_string($link2,$_GET['val']);
$tipo=$_GET['tipo'];
$testo='';


switch($tipo){
	case 1:
	default:

		$qadd3='';
		if(isset($_SESSION['listIDsotto'])){
			if($_SESSION['listIDsotto']==2){
				$qadd3=" AND s.IDtipo='".$_SESSION['listIDsotto']."' ";
				//$query="SELECT ID,servizio FROM servizi  WHERE IDstruttura='$IDstruttura' AND IDtipo='".$_SESSION['IDtipo2']."'";
			}else{
				$qadd3=" AND s.IDsottotip='".$_SESSION['listIDsotto']."' ";
			}	
		}
		
		if(strlen($ricerca)>0){
			$query="SELECT s.ID,s.servizio,s.prezzo,t.tipolimite,s.IDtipo,s.durata FROM servizi as s,tiposervizio as t,sottotipologie as st WHERE  s.IDstruttura='$IDstruttura'  AND attivo>'0' AND s.servizio LIKE '%$ricerca%' AND s.IDtipo=t.ID AND t.tipolimite!='4'  AND s.IDsottotip=st.ID $qadd3 LIMIT 20";
			
		}else{
			if(isset($_SESSION['listIDsotto'])){
				$query="SELECT s.ID,s.servizio,s.prezzo,t.tipolimite,s.IDtipo,s.durata FROM servizi as s,tiposervizio as t,sottotipologie as st WHERE  s.IDstruttura='$IDstruttura' AND attivo>'0' AND s.IDtipo=t.ID AND t.tipolimite!='4' AND t.ID NOT IN(15,16)  AND s.IDsottotip=st.ID $qadd3 LIMIT 20";
			
			}else{
				$query="SELECT s.ID,s.servizio,s.prezzo,t.tipolimite,s.IDtipo,s.durata FROM servizi as s,preferitiserv as e,tiposervizio as t,sottotipologie as st WHERE s.IDstruttura='$IDstruttura'  AND e.IDserv=s.ID AND t.ID=s.IDtipo AND s.IDsottotip=st.ID AND s.IDtipo NOT IN(8,9,12,13,14,15,16,17) ORDER BY s.IDtipo";				
			}
		}
		
		$result=mysqli_query($link2,$query);
		if(mysqli_num_rows($result)>0){
			$testo.='<div class="list-block">
			<ul>';
			while($row=mysqli_fetch_row($result)){
			$testo.='<li>
						<a href="javascript:void(0)" onclick="selectservice('.$row['0'].','.$row['3'].','.$row['4'].','.$row['5'].')" class="item-link item-content">
							<div class="item-inner textleft">
								<div class="item-title f16" id="nome'.$row['0'].'">'.$row['1'].'</div>
							</div>
						</a> 
					</li>';
			}
			$testo.='</ul>
			</div>';
			
		}
		
		/*
		<div class="row h40 rowlist no-gutter" onclick="selectservice('.$row['0'].','.$row['3'].','.$row['4'].','.$row['5'].')">
				<div class="col-80"  id="nome'.$row['0'].'">'.$row['1'].'</div>
				<div class="col-20">'.$row['2'].' €</div>
				</div>';
		
		*/
		
		
		
	break;
	case 3:
		
		if(strlen($ricerca)>0){	
	
			$queryreg = "SELECT id,codiceidea,prezzo,statov,persone,idea FROM ideeregalosold WHERE IDstruttura='$IDstruttura' AND codiceidea LIKE '%$ricerca%'  ORDER BY statov,codiceidea LIMIT 15";
			$resultreg = mysqli_query($link2,$queryreg);
			if(mysqli_num_rows($resultreg)>0){
				while($rowreg=mysqli_fetch_row($resultreg)){
					//$cla=' link voucher';
					//$func=' onclick="selreg(this)"';
					//$func2=' onclick="sellreg('.$rowreg['0'].')"';
					$title='Utilizzabile';
					$classecolor='c2542d9';

					$pagato='';
					$color='';
					$pag=controllopagreg($rowreg['0']);
					if($pag!=0){
						$color='30c96a';
						$pagato='Pagato';
					}else{
						$color='f40c53';
						$pagato='Non Pagato';
					}

					$pos=1;
					switch($rowreg['3']){
						case 2:
							$cla='';$func=''; $func2=''; $title='<strong style="color:#b01f3e;">Già Utilizzato</strong>';$pos=0;
						break;
						case 3:
							$cla='';$func=''; $func2=''; $title='<strong style="color:#b01f3e;">Annullato</strong>';$pos=0;
						break;
					}



					$testo.='<div class="row h40 rowlist no-gutter " alt="'.$rowreg['4'].'" dir="'.$rowreg['2'].'" id="'.$rowreg['0'].'"  onclick="selaccontoreg(this)">
							<div class="col-40 coltitle"  id="nome'.$row['0'].'">
							<strong class="fs16 '.$classecolor.'">'.$rowreg['1'].'</strong><br><span>'.$rowreg['5'].'</span>
								

							</div>
							<div class="col-20 coltitle"><span>N.'.$rowreg['4'].' '.txtpersone($rowreg['4']).'</span></div>
							<div class="col-40 rightcol">'.$title.'<br><span style="color:#'.$color.'; font-size:11px">'.$pagato.'</span></div>
							</div>';

	/*
					$testo.='
					<tr class=" hovernone">
					<td>
					<span>'.$rowreg['1'].' - N.'.$rowreg['4'].' '.txtpersone($rowreg['4']).'</span><br>

					<b>'.$rowreg['5'].'</b>


					</td>
					<td style="font-size:13px;">'.$title.'<br><span style="color:#'.$color.'; font-size:11px">'.$pagato.'</span></td>

					<td>';

					if($pos==1){
						$testo.='<button class="shortcut mini15 userselect info popover" '.$func.' alt="'.$rowreg['4'].'" dir="'.$rowreg['2'].'" id="'.$rowreg['0'].'" lang="'.$rowreg['1'].'"><span style="font-weight:600;">Procedi inserendo i servizi inclusi</span></button><button class="shortcut mini15 iconpayment success popover" '.$func2.'><span style="font-weight:600;">Utilizza come un Acconto</span></button>';
					}

					$testo.='</td>
					</tr>
					';*/
			}
		}

	}else{
		$testo.='<br>
		<h3>Ricerca Voucher</h3>';
		
	}
		
		
		
		
		
		
	break;
	case 4:
		
		
		
		$testo.='';
	
		$query2="SELECT c.ID,c.cofanetto,a.nome,c.persone FROM cofanetti as c,agenzie as a WHERE a.IDstr='$IDstruttura' AND a.ID=c.IDagenzia AND c.cofanetto LIKE '%$ricerca%'";
				$txtsel='';
				$result2=mysqli_query($link2,$query2);
				if(mysqli_num_rows($result2)>0){
					while($row2=mysqli_fetch_row($result2)){
						$IDcof=$row2['0'];
						
						$altattr=$IDcof.'_'.$row2['3'].'_'.$row2['1'];
						
						$testo.='<div class="row h40 rowlist no-gutter" onclick="selcof('."'".$altattr."'".')">
						<div class="col-70 coltitle fs16"  id="nome'.$row['0'].'">'.$row2['1'].'<br><span>'.$row2['2'].'</span></div>
						<div class="col-30">'.$row2['3'].' '.txtpersone($row2['3']).'</div>
						</div>';
						
						
						//$testo.='<tr onclick="selcof('."'".$altattr."'".')"><td></td><td>'.$row2['3'].' persone</td></tr>';
						
					}
				}
	
	
		
		
		
	break;
		
		
		
}


echo $testo;
?>