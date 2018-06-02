<?php


	header('Access-Control-Allow-Origin: *');
	include('../../config/connecti.php');
	include('../../config/funzioni.php');
	include('../../config/funzionilingua.php');


$IDstruttura=$_SESSION['IDstruttura'];

$time=$_GET['dato0']; //time
$IDsottotip=$_GET['dato1'];//idsottotip

if(isset($_GET['dato2'])){
	$_SESSION['tavoloalloca']=$_GET['dato2'];
}else{
	unset($_SESSION['tavoloalloca']);
}


$data=date('Y-m-d',$time);

list($yy, $mm, $dd) = explode("-",$data);
$time0=mktime(0, 0, 0, $mm, $dd, $yy);


$query="SELECT prefisso FROM strutture WHERE ID='$IDstruttura' LIMIT 1";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$prefisso=$row['0'];

//generaservizi('.$IDserv.',1,'.$IDsottotip.'); 
$txt='
<input type="hidden" id="prefisso" value="'.$prefisso.'">
<input type="hidden" id="data" value="'.date('d/m/Y',$time).'">

<input type="hidden" value="'.$time.'" id="time">
<input type="hidden" value="'.$IDsottotip.'" id="idosottotip">';
$IDserv=0;

$nomesottotip='';
$query="SELECT sottotipologia FROM sottotipologie WHERE ID='$IDsottotip' AND IDstr='$IDstruttura' ";
$result=mysqli_query($link2,$query);
$row=mysqli_fetch_row($result);
$nomesottotip=$row['0'];



$query2="SELECT MIN(orarioi),MAX(orariof) FROM orarisotto WHERE IDsotto='$IDsottotip' AND IDstr='$IDstruttura' ";  
$result2=mysqli_query($link2,$query2);
$row2=mysqli_fetch_row($result2);
$orarioin=$row2['0'];
$orariofin=$row2['1'];
$oraini=$time0+$orarioin;
$orastart=$oraini-1800;
$orariofin=$time0+$orariofin+1800;

$nav='<a href="#" class="link icon-only" onclick="tastiricerca('.$time.','.$IDsottotip.');"><i class="material-icons" >search</i></a>';

$txt.='<div  class="content-block">

<div class="content-block-title titleb">Servizio</div>
		<div class="list-block">
		  <ul>
		   <li>
		     <div class="item-link item-content" onclick="selezionaserv('.$IDsottotip.','.$time.');">
          	 <div class="item-inner">
              <div class="item-title" ><strong>Servizio:</strong></div>
			  <div class="item-after" id="nomeserv" style="font-weight:600; color:#b83c48;">Seleziona</div>
			  <input type="hidden" id="idserv" value="">	
		   </div>
		  </div>
		</li>
		
		
		
		<li>
				  <a href="#" class="item-link  smart-select" data-open-in="picker"   data-back-on-select="true" data-searchbar="false">
					<select  onchange="" id="timeserv" >
			  		'.generaorariotime3($oraini,$orastart,$orariofin).'</select>
					<div class="item-content">
					  <div class="item-inner">
						<div class="item-title">Orario</div>
						<div class="item-after"  id="orarioserv" >0</div>
					  </div>
					</div>
				  </a>
				</li>
		</ul>
			</div>


<input type="hidden" id="prezzo">
<input type="hidden" id="personemax">
		 
<div class="content-block-title titleb">Dati Ospite</div>
		<div class="list-block" >
		
		 <ul>	 
		 <div class="dettagliocliente" id="dettagliocliente">
		 
		 <input type="hidden" id="IDnuovotav" value="0">
		 <input type="hidden" id="tipopersnuovo" value="3">

		 
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
          <div class="item-input" >
            <input type="text" id="cognome"  placeholder="Cognome">
          </div>
        </div>
      </div>
    </li>
	
	
    <li>
		  <div class="item-content">
			<div class="item-inner">
			 <div class="item-title label">Email</div>
			  <div class="item-input">
				<input type="email"  id="email" placeholder="E-mail" >
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
<li style="background:#f8f8f8;">
				
					<div class="item-content">
					  <div class="item-inner">
						<div class="item-title"><strong>Numero Persone</strong></div>
					  </div>
					</div>
				</li>
';
$query="SELECT ID,restrizione,tiporest,personale FROM tiporestr WHERE IDstr='$IDstruttura' AND limite='0' AND personale='1' ORDER BY ordine";
	$result=mysqli_query($link2,$query);
	$lar=50;
	$col=2;
	$ini=0;
	
	while($row=mysqli_fetch_row($result)){
			
			$txt.='<li>
				  <a href="#" class="item-link  smart-select" data-open-in="picker"   data-back-on-select="true" data-searchbar="false">
					<select  onchange="contapers();" id="restriz'.$row['0'].'" lang="1" alt="'.$row['0'].'" class="selectdx inputrestr" >
			  '.generanum(0,20).'</select>
					<div class="item-content">
					  <div class="item-inner">
						<div class="item-title">'.$row['1'].' <span>[<span class="prezzopersone" id="prezzoschermo'.$row['0'].'">0</span>€]</span></div>
						<div class="item-after prezzonumper"  id="numpers'.$row['0'].'" >N.0</div>
					  </div>
					</div>
				  </a>
				</li>';
	}
		 
		 $txt.='
		 </div>
		  
		  
		 </ul>
		</div>

		
			
			
	  <div class="titleb" style="margin-left:15px;">Note</div>
	<div class="list-block">
		  <ul>
			<li>
			 <div class="item-content" style="height:100%;">
			  <div class="item-inner" style="width:100%;height:100%;">
				  <div class="item-input">
            		<textarea style="height:100px" id="note"></textarea>
        	  </div>
			 </div>
			 </div>
			</li>
			</ul></div>
</div>		
<div class="content-block" style="height:100px"></div>				
		
';

//onclick="orariotavolonuovo('.$IDsottotip.','.$time.');"

//backexplode(5,'.$_SESSION['timecal'].')"
/**/
?>
<div class="pages navbar-fixed">
  <div data-page="nuovotavolo" class="page">
     <div class="navbar">
               <div class="navbar-inner">
                  <div class="left" >
					 <a href="#" class="link icon-only" onclick="creasessione(0,88);mainView.router.back();">
						<i class="material-icons" style="font-size:30px;">apps</i>
					</a>
					
					</div>
                  <div class="center"><b>Aggiungi Tavolo</b><br/><span style="font-size:11px;"><?php echo $nomesottotip ?></span></div>
				   <div class="right"><?php  echo $nav; ?> 
             	 </div> 
               </div>
            </div>
            <div class="bottombarpren" style="background:#f1f1f1;z-index:999;" align="center">
			  <table style="width:100%; height:100%;cellpadding:0;cellspacing:0;">
			   <tr>
			     <td style="width:15%">
                 </td>
				<td>
		         <button onclick="salvatavolo();"
				 style="background-color: #e4492b;color:#fff;" 
			 	 class="bottoneprezzo">Aggiungi (<span id="euro">0</span>€)</button>
			    </td>
				 <td style="width:15%">
                 </td>
			   </tr>
			  </table>
			</div>
            
	<div id="nuovotavolo" class="page-content" style="width: 100%;">
		 <?php
		 echo $txt;
		 ?>
		 
	</div>
	
  </div>	
</div>

	
	
	





