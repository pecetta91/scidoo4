<?php 
	include('../../../config/connecti.php');
	include('../../../config/funzioni.php');
	include('../../../config/funzionilingua.php');
	header('Access-Control-Allow-Origin: *');


$IDpren=$_SESSION['IDstrpren'];
$tipo=$_GET['dato0'];
$testo='<input type="hidden" value="'.$tipo.'">
<input type="hidden" value="'.$IDpren.'">';

switch($tipo)
{
	case 1:
		$testo.='  
		<div class="divpagamento">Nessun prelievo, Solo a garanzia</div>
		
					<div class="list-block">
						<ul>
							<li>
								<div class="item-content">
								   <div class="item-inner">
								  	 <div class="item-title label">Numero di carta <br/><span class="infocarta">inserire i 16 caratteri senza trattini</span></div><br/>

								  	 <div class="item-input">
									  <input autocomplete="off"  type="text" placeholder="XXXX" id="ncarta" maxlength="16" style="font-size:15px;">
								     </div>
								   </div>
							    </div>
							</li>
							<li>
								 <a href="#" class="item-link smart-select" data-open-in="picker" data-picker-close-text="Chiudi">
										<select name="annos" id="annos">'.generaanni(11).'</select>
										<div class="item-content">
										  <div class="item-inner">
											<!--prendere con html-->
											<div class="item-title">Anno di Scadenza</div>
										
											<div class="item-after"  ></div>
										  </div>
										</div>
									  </a>  	
							</li>
							<li>
							 <a href="#" class="item-link smart-select" data-open-in="picker" data-picker-close-text="Chiudi">
										<select name="meses" id="meses">'.generamesi().'</select>
										<div class="item-content">
										  <div class="item-inner">
											<div class="item-title">Mese di Scadenza</div>
										
											<div class="item-after" ></div>
										  </div>
										</div>
							</a>  
							</li>
							<li>
								<div class="item-content">
								   <div class="item-inner">
								  	 <div class="item-title label">Intestatario</div>
								  	 <div class="item-input">
									  <input type="text" placeholder="Intestatario" id="intes" style="font-size:15px;">
								     </div>
								   </div>
							    </div>
							</li>
						</ul>
					</div>
					<div style="margin-top:50px">
						<b style="color:#000;">Istruzioni:</b>
						<div class="row rowlist no-gutter impriga infobon">
							<span class="infopag"><strong>Inserire le informazioni relative alla vostra carta di credito nei campi sopra indicati. Dopodich&egrave; cliccare su conferma.</strong></span>
						</div>	
					</div>	
					<br/>
					<br/>
					<div style="padding-top:50px">
							<b style="color:#000;">Carte Accettate:</b><br/>
							<div class="row rowlist no-gutter impriga carteriga">
										<div><img alt="Credit Card Logos" title="Credit Card Logos" src="../v15/img/creditcard/mastercard.svg" width="60" height="50" border="0" /></div>
										<div><img alt="Credit Card Logos" title="Credit Card Logos" src="../v15/img/creditcard/postepay.svg" width="60" height="50" border="0" /></div>
										<div><img alt="Credit Card Logos" title="Credit Card Logos" src="../v15/img/creditcard/visa.svg" width="60" height="50" border="0" /></div>
							</div>
					</div><br><br><br><br><br><br>
					';
		
		$btn='<button class="button button-fill color-green bottoneprezzo" style="margin:auto;" onclick="controllocarta2()">Conferma</button>';
		$conferma='Carta di Credito';
	
	break;
		
		
	case 2:
		
		$query="SELECT app,gg,time,tempg,tempn,checkout,stato,IDstruttura,acconto,ID FROM prenotazioni WHERE IDv='$IDpren' LIMIT 1";
		$result=mysqli_query($link2,$query);
		$row=mysqli_fetch_row($result);
		$IDapp=$row['0'];
		$gg=$row['1'];
		$time=$row['2'];
		$timearr=$time;
		$tempg=$row['3'];
		$tempn=$row['4'];
		$checkout=$row['5'];
		$stato=$row['6'];
		$IDstr=$row['7'];
		$acconto=$row['8'];
		$IDprentxt=$row['9'];
		
		$query2="SELECT p.ID,t.pagamento,p.info,3,p.email,p.tipopag FROM pagonline as p,tipopag as t WHERE p.tipopag IN(2,3) AND p.IDstr='$IDstr' AND p.tipopag=t.ID ";
		$result2=mysqli_query($link2,$query2);
		$row2=mysqli_fetch_row($result2);
		$conferma=$row2['1'];
		
		$testo.='
			<b style="color:#000">Dati:</b>
			';
					
					switch($row2['5']){
						case 2:
							$testo.='
							<div class="row rowlist no-gutter impriga infobon">
				  				<div class="col-100"><b>Info:</b><br/> '.$row2['2'].'</div>
				  				<div class="col-100"><b>Email Struttura:</b><br/> '.$row2['4'].'</div>
							</div>';
						break;
						default:
							$testo.='
							<div class="row rowlist no-gutter impriga infobon">
				  				<div><b>Dati Pagamento:</b><br/> '.$row2['2'].'</div>
				  			</div>';
						
						break;
					}
					
					$testo.='<br/><b style="color:#000">Causale:</b><br/>
					
					<div class="row rowlist no-gutter impriga infobon">
						<div>Prenotazione N.'.$IDprentxt.' di '.$nomepren.' (Arrivo: '.dataita($time).' '.date('Y',$time).')</div>
					</div>
					<br/>
					<div style="margin-top:50px">
						<b style="color:#000;">Istruzioni:</b>
						<div class="row rowlist no-gutter impriga infobon">
							<span class="infopag"><strong>Eseguire il pagamento utilizzando i dati sopra indicati. Dopodich&egrave; cliccare su conferma.</strong></span>
						</div>	
					</div>	
			
			
			';
		
		$btn='<button class="button button-fill color-green bottoneprezzo" onclick="modprofilo('.$IDpren.','.$row2['5'].',7,10,2)">Eseguito</button>';
		
		
	break;
		
}




?>
<div data-page="confermapag" class="page" > 
		 	<div class="navbar" id="navcal" >
				<div class="navbar-inner">
					<div class="left">
					  <a href="#" class="link icon-only back">
						<i class="material-icons" style="font-size:40px;">chevron_left</i>
					  </a>
					</div>
					<div class="center titolonav">Conferma pagamento<br/><span class="fs13"><?php echo $conferma;?></span></div>
					<div class="right"></div>
				</div>
			</div>
			<div class="bottombarpren" style="background:#f1f1f1;z-index:999" align="center">
			  <table style="width:100%;height:100%;cellpadding:0;cellspacing:0;">
			   <tr>
			     <td style="width:15%">
                 </td>
				<td>
		        <?php echo $btn;?>
			    </td>
				 <td style="width:15%">
                 </td>
			   </tr>
			  </table>
			</div>
	
						
			 <div class="page-content" >
				<div class="content-block" id="confermapag" style="padding:0px; width:100%;"> 
					<?php echo $testo;?>
					
				 </div>
				 
			 </div>
</div>