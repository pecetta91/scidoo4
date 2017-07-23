<?php 
header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');

$IDutente=$_SESSION['ID'];
$IDstruttura=$_SESSION['IDstruttura'];

$IDsotto=$_SESSION['listIDsotto'];
$time=$_SESSION['listtime'];


$val=mysqli_real_escape_string($link2,$_GET['val']);

$ricerca='';

$time0=$time;
$checkout=($time0+86400);
					$groupid=getprenotazioni($time0,$checkout,$IDstruttura);
					if(strlen($groupid==0)){$groupid='0';}
					
					$query="SELECT GROUP_CONCAT(DISTINCT(IDv) SEPARATOR ',') FROM prenotazioni WHERE time>='$time0' AND time<='$checkout' AND IDv NOT IN($groupid) AND stato>='0' AND IDstruttura='$IDstruttura' GROUP BY IDstruttura";
					
					$result=mysqli_query($link2,$query);
					if(mysqli_num_rows($result)>0){
						$row=mysqli_fetch_row($result);
						$groupid.=','.$row['0'];
					}
					$query="SELECT GROUP_CONCAT(DISTINCT(IDv) SEPARATOR ',')  FROM prenotazioni WHERE FROM_UNIXTIME(checkout,'%Y-%m-%d')= FROM_UNIXTIME('$time','%Y-%m-%d') AND IDv NOT IN($groupid) AND stato>='0' AND IDstruttura='$IDstruttura' GROUP BY IDstruttura";
					$result=mysqli_query($link2,$query);
					if(mysqli_num_rows($result)>0){
						$row=mysqli_fetch_row($result);
						$groupid.=','.$row['0'];
					}




if($val!=''){
		
		
		$ricerca.=' AND (';
		
		$query="SELECT GROUP_CONCAT(p.IDv SEPARATOR ',') FROM prenotazioni as p,appartamenti as a WHERE p.IDv IN($groupid) AND a.ID=p.app  AND a.nome LIKE '$val%' LIMIT 30";
		$result=mysqli_query($link2,$query);
		if(mysqli_num_rows($result)>0){
			$row=mysqli_fetch_row($result);
			if(strlen($row['0'])>0){
				$ricerca.="IDv IN(".$row['0'].") ";
			}
		}
		if(strlen($ricerca)>10){
			$ricerca.=" OR (ID ='$val' OR note LIKE '%$val%'  ";
		}else{
			$ricerca.=" (ID ='$val' OR note LIKE '%$val%'  ";
		}
		
		$nome=expricerca($val);
		
		$query="SELECT ID FROM schedine WHERE MATCH(nome,cognome,tel,mail) AGAINST('".$nome."' IN BOOLEAN MODE) AND IDstr='$IDstruttura'";
		$result=mysqli_query($link2,$query);

		if(mysqli_num_rows($result)>0){
			$group='';
			while($row=mysqli_fetch_row($result)){
				$group.=$row['0'].',';
			}
			$group=substr($group, 0, strlen($group)-1); 
			$query="SELECT GROUP_CONCAT(IDpren SEPARATOR ',') FROM infopren WHERE IDcliente IN ($group) GROUP BY IDstr";
			$result=mysqli_query($link2,$query);
			$row=mysqli_fetch_row($result);
			$group2=$row['0'];
			if(strlen($group2)>0){
				$ricerca.="OR IDv IN($group2)";
			}
		}
		
	$ricerca.=")) " ;
	
	$query="SELECT IDv,ID FROM prenotazioni WHERE IDv IN($groupid) $ricerca";
}else{
	$query="SELECT IDv,ID FROM prenotazioni WHERE IDv IN($groupid)";
}

$testo='';

$result=mysqli_query($link2,$query);
if(mysqli_num_rows($result)>0){
	while($row=mysqli_fetch_row($result)){
		
	$testo.='
		 <li  onclick="addservice('.$row['0'].',1)">
            <label class="label-radio item-content">
              <div class="item-inner" >
                <div class="item-title" style="font-size:13px; line-height:12px;">'.estrainome($row['0']).'<br><span style="font-size:10px; color:#777;">'.estrainomeapp($row['0']).'</span></div>
				 <div class="item-after" style="font-size:13px;">ID:'.$row['1'].'</div>
				
              </div>
            </label>
          </li>
	
	';
	
	
	}
}

echo $testo;
?>