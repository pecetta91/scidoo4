<?php

header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');

$IDstruttura=$_SESSION['IDstruttura'];

$ID=$_POST['ID']; //IDprincipale
$npers=$_POST['npers'];//npers
$tipo=$_POST['tipo'];
$IDclienti=$_POST['IDclienti'];//idclienti

//estrai prefisso

$txt='<input type="hidden" id="id" value="'.$ID.'">
<input type="hidden" id="IDclienti" value="'.$IDclienti.'">

<input type="hidden" id="IDnuovotav" value="'.$IDclienti.'">
<input type="hidden" id="tipopersnuovo" value="'.$tipo.'">

';



switch($tipo){
	case 1:
		//$ID=substr_replace($ID,'',-1);
		
		
		$arrp=explode(',',$ID);
		$arrp = array_filter($arrp);   
		$IDclienti=implode(',',$arrp);

		$npers=count($arrp);
		if($npers>0){
			
			$nomepren='';
			$ID='';
			$query="SELECT IDpren FROM infopren WHERE ID IN($IDclienti) GROUP BY IDpren";
			$result=mysqli_query($link2,$query);
			if(mysqli_num_rows($result)>0){
				while($row=mysqli_fetch_row($result)){
					$IDpren=$row['0'];
					$ID.=$IDpren.',';
					$nomepren.=estrainome($IDpren).' ,';
				}
			}

			$nomepren=substr($nomepren, 0, strlen($nomepren)-2);

			$ID=substr($ID, 0, strlen($ID)-1);

			$txt.='<li>
				<div class="item-link item-content"  onclick="visionepersone('."'".$ID."'".','."'".$IDclienti."'".');" id="prentavolo" alt="'.$ID.'" title="'.$IDclienti.'">
				 <div class="item-inner">
				  <div class="item-title" id="idpers">Prenotazione<span style="font-size:10px;color:#666;"><br/>'.$nomepren.'</span></div>
				  <div class="item-after" id="dettaglio">
				  <strong id="perspren">'.$npers.' '.txtpersone($npers).'</strong> 
				  </div>
			   </div>
			  </div>
			</li>
			
			<input type="hidden" id="IDclientipren" value="'.$IDclienti.'">
			
			';
		
		
			
		}
		
		
	break;
		
	case 2:
		$query="SELECT nome,cognome,tel,cell,mail,noteristo FROM schedine WHERE ID='$ID' AND IDstr='$IDstruttura'";
		$result=mysqli_query($link2,$query);
		$row=mysqli_fetch_row($result);
		
		
		$txt.='
		

		
		
		 <li>
      <div class="item-content">
        <div class="item-inner">
          <div class="item-title label">Nome</div>
          <div class="item-input" >
            <input type="text" id="nomeric" value="'.$row['0'].'" onchange="modprenot('.$ID.',this,40,11,0);" placeholder="Nome" >
          </div>
        </div>
      </div>
    </li>
	 <li>
      <div class="item-content">
        <div class="item-inner">
          <div class="item-title label">Cognome</div>
          <div class="item-input" >
            <input type="text" id="cognome" value="'.$row['1'].'"  onchange="modprenot('.$IDcliente.',this,41,11,0);" placeholder="Cognome">
          </div>
        </div>
      </div>
    </li>';
		
	if(strlen($row['2'])>0){
		$txt.='
		<li>
		  <div class="item-content">
			<div class="item-inner">
			  <div class="item-title label">Telefono</div>
			  <div class="item-input">
				<input type="number" id="telefono" value="'.$row['2'].'" onchange="modprenot('.$ID.',this,57,11,0);">
			  </div>
			</div>
		  </div>
		</li>';
	}else{
		if(strlen($row['3'])>0){
			$txt.='
			<li>
			  <div class="item-content">
				<div class="item-inner">
				  <div class="item-title label">Telefono</div>
				  <div class="item-input">
					<input type="number" id="telefono" value="'.$row['3'].'"  onchange="modprenot('.$IDcliente.',this,153,11,0);">
				  </div>
				</div>
			  </div>
			</li>';
		}else{
			$txt.='
			<li>
			  <div class="item-content">
				<div class="item-inner">
				  <div class="item-title label">Telefono</div>
				  <div class="item-input">
					<input type="number" id="telefono" value="'.$row['2'].'" onchange="modprenot('.$ID.',this,57,11,0);">
				  </div>
				</div>
			  </div>
			</li>';
		}
	}
		
	
				
	$txt.='
	<li>
      <div class="item-content">
        <div class="item-inner">
          <div class="item-title label">E-mail</div>
          <div class="item-input">
            <input type="email" id="email" value="'.$row['4'].'" onchange="modprenot('.$ID.',this,56,11,0);" placeholder="E-mail">
          </div>
        </div>
      </div>
    </li>
	
	
	';

	break;	
		
	case 3:
		
		$txt.='
		<li>
		  <div class="item-content">
        <div class="item-inner">
          <div class="item-title label">Nome</div>
          <div class="item-input">
            <input type="text" id="nome" placeholder="Nome">
          </div>
        </div>
      </div>
    </li>
	<li>
		  <div class="item-content">
        <div class="item-inner">
          <div class="item-title label">Cognome</div>
          <div class="item-input">
            <input type="text" id="cognome" placeholder="Cognome">
          </div>
        </div>
      </div>
    </li>
    <li>
		  <div class="item-content">
			<div class="item-inner">
			 <div class="item-title label">Email</div>
			  <div class="item-input">
				<input type="email"  id="email" placeholder="E-mail">
			  </div>
			</div>
		  </div>
		</li>
	<li>
      <div class="item-content">
        <div class="item-inner">
          <div class="item-title label">Telefono</div>
          <div class="item-input">
            <input type="text" id="telefono" placeholder="Telefono">
          </div>
        </div>
      </div>
    </li>

	
	
	';

		
break;
}

if(($tipo==2) || ($tipo==3))
{
	$query="SELECT ID,restrizione,tiporest,personale FROM tiporestr WHERE IDstr='$IDstruttura' AND limite='0' AND personale='1' ORDER BY ordine";
	$result=mysqli_query($link2,$query);
	$lar=50;
	$col=2;
	$ini=0;
	
	$txt.='<li style="background:#f8f8f8;">
				
					<div class="item-content">
					  <div class="item-inner">
						<div class="item-title"><strong>Numero Persone</strong></div>
					  </div>
					</div>
				</li>';
	
	
	while($row=mysqli_fetch_row($result)){

			
			$txt.='<li>
				  <a href="#" class="item-link  smart-select" data-open-in="picker"   data-back-on-select="true" data-searchbar="false">
					<select  onchange="contapers();" id="restriz'.$row['0'].'" lang="1" alt="'.$row['0'].'" class="selectdx inputrestr" >
			  '.generanum(0,20).'</select>
					<div class="item-content">
					  <div class="item-inner">
						<div class="item-title">'.$row['1'].' <span>[<span class="prezzopersone" id="prezzoschermo'.$row['0'].'">0</span>€]</span></div>
						<div class="item-after prezzonumper"  id="numpers'.$row['0'].'" >0</div>
					  </div>
					</div>
				  </a>
				</li>';}

}

echo $txt;
?>

	
	





