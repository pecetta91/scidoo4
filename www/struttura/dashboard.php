<?php
require_once __DIR__ . '/../../config/connecti.php';
require_once __DIR__ . '/../../config/funzioni.php';

$IDutente = $_SESSION['ID'];
$IDstruttura = $_SESSION['IDstruttura'];

$dati_struttura = get_dati_struttura($IDstruttura);

$IDmainuser = $dati_struttura['IDmain_user'];
$nomestr = $dati_struttura['nome_struttura'];
$tipostr = $dati_struttura['tipo_struttura'];
$arrtipipos = $dati_struttura['tipo_servizi_possibili'] ?? [];

$dati_utente = get_dati_utente();

$nomepers = $dati_utente['nome'];
$IDpers = $dati_utente['IDpers'];
$arrper = $dati_utente['autorizzazioni'] ?? [];

$telematico = get_telematico($IDstruttura);

$timeoggi0 = time0(time_struttura());
$timedomani0 = $timeoggi0 + 86400;

$lista_arrivi_partenze = ['arrivi' => 0, 'partenze' => 0, 'permanenze' => 0];
$lista_prenotazioni = [];

$query = "(SELECT pr.IDpren,p.stato,p.time,p.checkout
FROM prenextra as pr
JOIN prenotazioni as p ON pr.IDpren=p.IDv AND p.gg>0 AND p.stato>=0 WHERE pr.IDstruttura=$IDstruttura AND pr.time>=$timeoggi0 AND pr.time<$timedomani0 GROUP BY pr.IDpren) UNION (SELECT IDv,stato,time,checkout FROM prenotazioni WHERE gg>0 AND stato>=0 AND checkout>=$timeoggi0 AND checkout<$timedomani0 AND IDstruttura=$IDstruttura GROUP BY IDv)
";
$result = mysqli_query($link2, $query);

while ($row = mysqli_fetch_row($result)) {
	$lista_prenotazioni[] = $row['0'];
	if ($row['2'] > $timeoggi0 && $row['2'] < $timedomani0 && !in_array($row['1'], [4, 3])) {
		$lista_arrivi_partenze['arrivi']++;
		continue;
	}
	if ($row['3'] > $timeoggi0 && $row['3'] < $timedomani0 && !in_array($row['1'], [7])) {
		$lista_arrivi_partenze['partenze']++;
		continue;
	}
	if (in_array($row['1'], [3])) {
		$lista_arrivi_partenze['permanenze']++;
	}
}

$pulsanti_funzione_new = '';

if (count(array_intersect([0, 8], $arrper)) > 0) {

	$query = "SELECT ID FROM appartamenti WHERE IDstruttura='$IDstruttura' LIMIT 1";
	$result = mysqli_query($link2, $query);
	if (mysqli_num_rows($result) > 0) {
		if ($telematico == 1) {
			$pulsanti_funzione_new .= '

		 	 <div class="pulsanti_funzione">
				<div class="container_funz" onclick="navigation(16,0,0,0)">
					<div class="div_icona" style="color: #3652AF;background:#3652af1a" >
						<div style=""><i class="far fa-calendar-alt" ></i></div>
					</div>
					<div class="testo">Prenotazioni
						' . ($lista_arrivi_partenze['arrivi'] != 0 ? '<div class="sotto_titolo">Arrivi : ' . $lista_arrivi_partenze['arrivi'] . '</div>' : '') . '
						' . ($lista_arrivi_partenze['partenze'] != 0 ? '<div class="sotto_titolo">Partenze : ' . $lista_arrivi_partenze['partenze'] . '</div>' : '') . '
						' . ($lista_arrivi_partenze['permanenze'] != 0 ? '<div class="sotto_titolo">Presenze :' . $lista_arrivi_partenze['permanenze'] . '</div>' : '') . '
					</div>
				</div>
			</div> 	';

		} else {
			$primo_giorno = primo_giorno_disponibile($IDstruttura) - (86400 * 2);

			$pulsanti_funzione_new .= '
			 <div class="pulsanti_funzione">
				<div class="container_funz" onclick="navigation(5,0,1,0);lastcalen_left=0;lastcalen_top=0;">
					<div class="div_icona" style="color: #3652AF;background:#3652af1a" >
						<div style=""><i class="far fa-calendar-alt" ></i></div>
					</div>
					<div class="testo">Calendario
						' . ($lista_arrivi_partenze['arrivi'] != 0 ? '<div class="sotto_titolo">Arrivi :' . $lista_arrivi_partenze['arrivi'] . '</div>' : '') . '
						' . ($lista_arrivi_partenze['partenze'] != 0 ? '<div class="sotto_titolo">Partenze :' . $lista_arrivi_partenze['partenze'] . '</div>' : '') . '
						' . ($lista_arrivi_partenze['permanenze'] != 0 ? '<div class="sotto_titolo">Presenze :' . $lista_arrivi_partenze['permanenze'] . '</div>' : '') . '
					</div>
				</div>
			</div> 	 ';
		}

	}
}

$pulsanti_funzione_new .= '
<div class="pulsanti_funzione">
	<div class="container_funz" onclick="navigation(21,0,()=>{aggiorna_navbar_preventivi();})">
		<div class="div_icona" style="color: #203a93;background:#203a931a" >
			<div style=""><i class="fas fa-bars" ></i></div>
		</div>
		<div class="testo">Preventivi	</div>
	</div>
</div>';

if (count(array_intersect([0, 8, 2, 3, 4], $arrper)) > 0) {
	if (in_array('4', $arrtipipos)) {

		$pulsanti_funzione_new .= '

			 <div class="pulsanti_funzione">
						<div class="container_funz" onclick="navigation(9,0,0,0);">
							<div class="div_icona" style="color: #d80073;background:#d800731a" >
								<div style=""><i class="fas fa-spa" ></i></div>
							</div>
							<div class="testo">Centro Benessere 	</div>
						</div>
					</div>';

	}
}

if (count(array_intersect([0, 8, 1], $arrper)) > 0) {

	if (in_array('1', $arrtipipos)) {

		$pulsanti_funzione_new .= '
				 <div class="pulsanti_funzione">
						<div class="container_funz" onclick="navigation(27,0,0,0);">
							<div class="div_icona" style="color: #f0a30a;background:#f0a30a1a" >
								<div style=""><i class="fas fa-utensils" ></i></div>
							</div>
							<div class="testo">Ristorante	</div>
						</div>
					</div> 	';

	}
}

$query = "SELECT COUNT(ID) FROM appartamenti WHERE IDstruttura=$IDstruttura AND attivo=1 AND stato=2";
$result = mysqli_query($link2, $query);
$row = mysqli_fetch_row($result);
$appartamenti_da_preparare = ($row[0] ? $row[0] : null);
$pulsanti_funzione_new .= '
 <div class="pulsanti_funzione">
						<div class="container_funz" onclick="navigation(13,0,4,0);">
							<div class="div_icona" style="color: #00baaf;background:#00baaf1a" >
								<div style=""><i class="fas fa-door-open" ></i></div>
							</div>
							<div class="testo">Pulizie
							' . ($appartamenti_da_preparare ? '<div class="sotto_titolo">' . $appartamenti_da_preparare . ' ' . ($appartamenti_da_preparare > 1 ? 'Alloggi' : 'Alloggio') . '  da Preparare</div>' : '') . '
							</div>
						</div>
					</div>
				';

$query = "SELECT ID FROM servizi WHERE IDstruttura='$IDstruttura' AND IDtipo='29' LIMIT 1";
$result = mysqli_query($link2, $query);
if (mysqli_num_rows($result) > 0) {

	$pulsanti_funzione_new .= '
			 <div class="pulsanti_funzione">
						<div class="container_funz" onclick="navigation(26,{tipologia:29},8);lastcalen_left=0;lastcalen_top=0;">
							<div class="div_icona" style="color: #d0a016;background:#d0a0161a" >
								<div style=""><i class="fas  fa-umbrella-beach" ></i></div>
							</div>
							<div class="testo">Spiaggia	</div>
						</div>
					</div> ';

}

$query = "SELECT ID FROM servizi WHERE IDstruttura='$IDstruttura' AND IDtipo='28' LIMIT 1";
$result = mysqli_query($link2, $query);
if (mysqli_num_rows($result) > 0) {

	$pulsanti_funzione_new .= '
		 <div class="pulsanti_funzione">
						<div class="container_funz" onclick="navigation(26,{tipologia:29},8);lastcalen_left=0;lastcalen_top=0;">
							<div class="div_icona" style="color: #203a93;background:#203a931a" >
								<div style=""><i class="fas fa-car" ></i></div>
							</div>
							<div class="testo">Garage	</div>
						</div>
					</div>
				';

}

$pulsanti_funzione_new .= '


			 <div class="pulsanti_funzione">
						<div class="container_funz"  onclick="navigation(23,0,0)">
							<div class="div_icona" style="color: #203a93;background:#203a931a" >
								<div style=""><i class="fas fa-shopping-cart" ></i></div>
							</div>
							<div class="testo">Vendite	</div>
						</div>
					</div>

						 <div class="pulsanti_funzione">
						<div class="container_funz" onclick="navigation(25,0,0)">
							<div class="div_icona" style="color: #203a93;background:#203a931a" >
								<div style=""><i class="fas fa-euro-sign" ></i></div>
							</div>
							<div class="testo">Prezzi Giornalieri	</div>
						</div>
					</div>';

$be_cm = '';
$query = "SELECT p.IDv FROM prenotazioni as p
JOIN notifichetxt as nt ON nt.IDgroup=p.IDv AND nt.tipogroup=1
JOIN notifichepers as np ON np.IDnotifica=nt.ID AND np.IDpers=$IDpers AND np.letto=0
WHERE p.IDstruttura=$IDstruttura AND p.settore_inserimento IN (2,4,6) AND p.time>$timeoggi0 AND p.stato!='-1'";
$result = mysqli_query($link2, $query);
if (mysqli_num_rows($result) > 0) {
	$be_cm = ' <div class="numero_not_giorn" style="  right: 35px;  top: -5px;  line-height: 12px;">' . mysqli_num_rows($result) . '</div> ';
}

$notifiche = '';
$query = "SELECT IDpers FROM notifichepers WHERE IDpers='$IDpers' AND letto='0'";
$result = mysqli_query($link2, $query);
if (mysqli_num_rows($result) > 0) {
	$notifiche = ' <div class="numero_not_giorn" style="  right: 35px;  top: -5px;  line-height: 12px;">' . mysqli_num_rows($result) . '</div> ';
}

$testo = ' <div style="margin:10px; padding-bottom:60px "> ' . $pulsanti_funzione_new . '</div>
<div class="toolbar_dashboard">
     <div class=" uk_grid_div div_list_uk" uk-grid   style="padding:0;border:0;text-align:center;">
        <div class="uk-width-1-4 lista_grid_nome" style="padding:0"><i class="fas fa-edit"  style="margin-top: 5px;"></i>
        					<div style="    margin-top: 5px; font-size: 14px;">Appunti</div>

        		 </div>
       	 <div class="uk-width-1-4 lista_grid_nome" style="padding:0" onclick="apri_notifiche()"><i class="fas fa-bell" style="margin-top: 5px;"></i>
       		 <div style="    margin-top: 5px; font-size: 14px;">Notifiche ' . $notifiche . '</div>
       	 </div>


       	  <div class="uk-width-1-4 lista_grid_nome uk-position-relative" style="padding:0" onclick="navigation(30,0)"><i class="fas fa-globe-europe" style="margin-top: 5px;"></i>
       	  	 <div style="    margin-top: 5px; font-size: 14px;">BE/CM ' . $be_cm . '</div>
       	   </div>

       	  <div class="uk-width-1-4 lista_grid_nome" style="padding:0" onclick="navigation(29,0)"><i class="fas fa-comments" style="margin-top: 5px;"></i>
       	  	 <div style="    margin-top: 5px; font-size: 14px;">Messaggi</div>
       	   </div>
    </div>
</div>

';

echo $testo;

?>

<style>

.pulsanti_funzione {margin: 10px 0; display: inline-flex; width: 49%;  place-content: space-evenly;}
.pulsanti_funzione .div_icona{ text-align: center; background: #f3f3f3;   border-radius: 50%;  font-size: 25px;margin: 10px auto;  width: 55px;  height:55px;position:relative}
.pulsanti_funzione .div_icona i{ position: absolute;  top: 50%;    left: 50%;  transform: translate(-50%, -50%);}
.pulsanti_funzione .testo{color: #333; font-weight: 600; font-size: 16px;text-align:center;;margin-top:5px;}
.pulsanti_funzione .container_funz{width: 170px;
    height: 145px;
    border-radius: 10px;
    place-content: center;
    box-shadow: 0 0 10px 1px #efefef;
    padding: 5px;
    background: #fff;}

  .pulsanti_funzione  .sotto_titolo{font-size: 13px;}

@media (min-width: 992px){
	.pulsanti_funzione {width: 24%; }
}

</style>
