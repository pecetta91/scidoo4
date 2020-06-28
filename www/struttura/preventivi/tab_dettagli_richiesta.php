<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';
require_once __DIR__ . '/../../../config/preventivatore/funzioni/preventivatore.php';

$IDutente = $_SESSION['ID'];
$IDstruttura = $_SESSION['IDstruttura'];

$IDrichiesta = $_SESSION['IDrequest'];
$tab_scelta = $_POST['tab_scelta'] ?? 1;

$testo = '';
switch ($tab_scelta) {

case 1:

	$testo = genera_conto_ui_kit($IDrichiesta, 1, 'switch_tab_dettagli_richiesta(1)');

	break;

case 2:
	$manuale = $link2->query("SELECT MAX(manuale) FROM deposito_preventivo WHERE IDrequest='$IDrichiesta'")->fetch_row()[0];
	if (!$manuale) {
		update_deposito_preventivo($IDrichiesta);
	}

	$sessione = &$_SESSION['preventivo'][$IDrichiesta];
	if (!is_array($sessione)) {
		$sessione = [];
	}
	if ($sessione['aggiorna_deposito'] ?? true && !$manuale) {
		$sessione['aggiorna_deposito'] = 0;
		update_deposito_preventivo($IDrichiesta);
	}

	$query = "SELECT IDregola FROM deposito_preventivo WHERE IDrequest='$IDrichiesta' LIMIT 1";
	$result = $link2->query($query);
	$row = $result->fetch_row();
	$regola_attiva = $row[0];

	$query = "SELECT ID,testo FROM deposito_regole WHERE IDstruttura='$IDstruttura'";
	$result = $link2->query($query);
	$result = array_merge([[0, 'Deposito libero']], $result->fetch_all());

	$select = '';
	$deposito_selezionato = '';
	foreach ($result as $val) {
		if ($val[0] == $regola_attiva) {
			$deposito_selezionato = $val[1];
		}

		$select .= '<li onclick="modprenot(' . "$IDrichiesta,$val[0],236,10,() => {chiudi_picker();switch_tab_dettagli_richiesta(2)}" . ');"
				value="' . $val[0] . '"   >' . $val[1] . ' ' . ($val[0] == $regola_attiva ? '<span class="uk-align-right" uk-icon="check" style="color:#2641da"></span>' : '') . '</li>';
	}

	$deposito_regola = '';
	$query = "SELECT ID,IDregola,modo,giorni,valore,tipo_valore,tipo_calcolo,giorni_calcolo,scadenza,soglia_prezzo,prezzo FROM deposito_preventivo WHERE IDrequest='$IDrichiesta'";
	$result = $link2->query($query);
	while ($row = $result->fetch_row()) {
		array_splice($row, 10, 0, [0, $IDrichiesta]);
		$regola = new Deposito(...$row);
		$info = $regola->to_string_array();

		$deposito_regola .= '
		<div class="div_list_uk uk_grid_div  " uk-grid  onclick="modal_cambia_valore_richiesta(' . $regola->ID . ',1)">
			    <div class="uk-width-1-2 uk-text-truncate lista_grid_nome uk-first-column">' . sprintf("%g", $info[6]) . ' € <br><span>' . ($info[1] ? dataita($info[1]) : '') . '</span></div>
			    <div class="uk-width-expand uk-text-right lista_grid_right " style="font-size:13px">' . $regola->to_string() . '</div>
	   	 </div>';
	}

	//. $deposito_regola

//	<select class="bnone" style="margin: 4px; margin-left:8px;" onchange="modprenot(' . "$IDrichiesta,this,236,11,() => {switch_tab_dettagli_richiesta(2)}" . ');">' . $select . '</select>
	$div_dettaglio_deposito = '
	<div id="deposito_regola" style="display:none;">
		<ul class="uk-list uk-list-divider uk-picker-bot " style="padding:5px 20px;">
			' . $select . '
		</ul>
	</div>




		<div class="div_uk_divider_list" style="margin-top:0px !important;" onclick="carica_content_picker(' . "'deposito_regola'" . ')" >Regole Deposito

			<div style="float:right;color:#333"> ' . $deposito_selezionato . ' <i class="fas fa-chevron-right"></i></div></div>
		</div>

 			  ' . $deposito_regola;

	$cancellation_regole = '';
	$manuale = $link2->query("SELECT MAX(manuale) FROM cancellation_preventivo WHERE IDrequest='$IDrichiesta'")->fetch_row()[0];
	if (!$manuale) {
		update_cancellation_preventivo($IDrichiesta);
	}

	$sessione = &$_SESSION['preventivo'][$IDrichiesta];
	if (!is_array($sessione)) {
		$sessione = [];
	}
	if ($sessione['aggiorna_cancellation'] ?? true && !$manuale) {
		$sessione['aggiorna_cancellation'] = 0;
		update_cancellation_preventivo($IDrichiesta);
	}

	$query = "SELECT IDregola FROM cancellation_preventivo WHERE IDrequest='$IDrichiesta' LIMIT 1";
	$result = $link2->query($query);
	$row = $result->fetch_row();
	$regola_attiva = $row[0];

	$query = "SELECT ID,testo FROM cancellation_regole WHERE IDstruttura='$IDstruttura'";
	$result = $link2->query($query);
	$result = array_merge([[0, 'Cancellazione libera']], $result->fetch_all());

	$select = '';
	$cancellation_selezionata = '';
	foreach ($result as $val) {
		if ($val[0] == $regola_attiva) {
			$cancellation_selezionata = $val[1];
		}
		$select .= '<li onclick="modprenot(' . "$IDrichiesta,$val[0],248,10,() => {chiudi_picker();switch_tab_dettagli_richiesta(2)}" . ');"
	 	value="' . $val[0] . '"   >' . $val[1] . ' ' . ($val[0] == $regola_attiva ? '<span class="uk-align-right" uk-icon="check" style="color:#2641da"></span>' : '') . '</li>';
	}

	$query = "SELECT ID,IDregola,modo,giorni,valore,tipo_valore,tipo_calcolo,giorni_calcolo,scadenza,soglia_prezzo,prezzo FROM cancellation_preventivo WHERE IDrequest='$IDrichiesta'";
	$result = $link2->query($query);
	while ($row = $result->fetch_row()) {
		array_splice($row, 10, 0, [0, $IDrichiesta]);
		$regola = new Cancellazione(...$row);
		$info = $regola->to_string_array();

		$cancellation_regole .= '
		<div class="div_list_uk uk_grid_div  " uk-grid  onclick="modal_cambia_valore_richiesta(' . $regola->ID . ',2)">
			    <div class="uk-width-1-2 uk-text-truncate lista_grid_nome uk-first-column">' . sprintf("%g", $info[6]) . ' € <br><span>' . ($info[1] ? dataita($info[1]) : '') . '</span></div>
			    <div class="uk-width-expand uk-text-right lista_grid_right " style="font-size:13px">' . $regola->to_string() . '</div>
	   	 </div>';

	}

	$div_dettaglio_cancellazione = '
	<div id="cancellation_regola" style="display:none;">
		<ul class="uk-list uk-list-divider uk-picker-bot " style="padding:5px 20px;">
			' . $select . '
		</ul>
	</div>

		<div class="div_uk_divider_list"  onclick="carica_content_picker(' . "'cancellation_regola'" . ')"  >Regole Cancellazione
			<div style="float:right;color:#333"> ' . $cancellation_selezionata . ' <i class="fas fa-chevron-right"></i></div></div>
		 </div>

 		' . $cancellation_regole;

	$testo = $div_dettaglio_deposito . $div_dettaglio_cancellazione;

	break;

case 3:

	$data_scadenza = null;
	$query = "SELECT ro.IDrequest,ro.scadenza,r.timearr FROM richieste AS r
	JOIN richieste_opzione AS ro ON ro.IDrequest=r.ID AND ro.IDpreventivo=0
	WHERE r.IDstr='$IDstruttura' AND r.ID='$IDrichiesta'";
	$result = $link2->query($query);
	if ($result->num_rows) {
		$info = $result->fetch_row();
		$data_scadenza = date('d/m/Y', $info[1]);
		/*
			$data_start = date('d/m/Y H:i', time_struttura());
		*/
	}

	$data_scadenza = ($data_scadenza ? convertidata3($data_scadenza, 'SI') : '');
	$data_scadenza_noformat = ($data_scadenza ? convertiData($data_scadenza, 'NO') : '');

	$testo = '

	<div class="div_list_uk uk_grid_div  " uk-grid  >
			    <div class="uk-width-expand uk-text-truncate lista_grid_nome uk-first-column">

			    	<input type="checkbox" class="apple-switch" ' . (($info ?? false) ? 'checked' : '') . '
			    	onchange="mod_preventivo(45,' . $IDrichiesta . ', {field: \'toggle\', value: 1}, \'var\',()=>{switch_tab_dettagli_richiesta(3)})">

			    		<div style=" display: inline-block; font-size:16px; vertical-align: text-top;font-weight:600">Opzione</div>
			    </div>
   	 </div>';

	if ($data_scadenza) {
		$testo .= '
  		 <div class="div_list_uk uk_grid_div  " uk-grid >
		    	<div class="uk-width-1-3 lista_grid_nome uk-first-column">Scadenza   </div>
				    <div class="uk-width-expand uk-text-right lista_grid_right">
				      <input class="uk-input input_cli  uk-form-small uk-text-right" id="scadenza_opzione" style="border: 1px solid#e1e1e1;  border-radius: 3px;"
				      type="text" data-testo="Scadenza" onclick="apri_modal(this,1);"   onchange="scadenza_opzione(' . $IDrichiesta . ')"
				        value="' . $data_scadenza . '"  data-noformat="' . $data_scadenza_noformat . '" readonly>
			        </div>
	      </div>';
	}

	break;

case 4:

	$IDrequest_str = is_array($IDrichiesta) ? implode(',', $IDrichiesta) : $IDrichiesta;
	$query = "SELECT SUM(CASE WHEN o.tipolim IN (4,5) THEN o2.prezzo ELSE 0 END),SUM(CASE WHEN o.tipolim NOT IN (4,5) THEN o2.prezzo ELSE 0 END),SUM(o2.prezzo),COUNT(CASE WHEN o.tipolim NOT IN (4,5) THEN o2.prezzo ELSE NULL END) FROM oraripren AS o JOIN oraripren2 AS o2 ON o2.IDoraripren=o.ID WHERE o.IDreq IN ($IDrequest_str)";
	$result = $link2->query($query);
	$row = $result->fetch_row();
	list($retta, $extra, $totale, $num_extra) = $row;
	$retta = sprintf('%g', round($retta, 2));
	$extra = sprintf('%g', round($extra, 2));
	$totale = sprintf('%g', round($totale, 2));

	$testo_sconti = [];

	$sconti = info_sconti_request($IDrichiesta);
	if (count($sconti)) {
		foreach ($sconti as $time => $sconto_servizio) {
			foreach ($sconto_servizio as $idx => $sconto) {
				$eliminabile = true;
				// $tmp = $sconto[5] . "<br>" . $sconto[1] . ($sconto[2] != 2 ? '€ ' : $short_tipiprezzo[$sconto[2]]) . " " . $sconto[3] . ($sconto[6] ? " - " . $sconto[6] : '') . "";
				$tmp = '<p class="testo_elissi_standard" style="display: inline-block; max-width: 25ch;margin:0">' . float_format($sconto['valore']) . ($sconto['tipo'] != 2 ? '€ ' : $short_tipiprezzo[$sconto['tipo']]) . '
				 <span style="text-decoration: line-through;  color: #aaa;">' . float_format(array_sum($sconto['prezzo_precedente'])) . '€</span>
				  ' . float_format(array_sum($sconto['prezzo'])) . '€
				 ' . $sconto['motivo'] . '
				 ' . $sconto['nome'] . '</p>';
				foreach ($testo_sconti as &$lst) {
					if (count(array_intersect($sconto['id'], $lst[3]))) {
						$lst[1] = false;
					}
				}
				$testo_sconti[] = [$tmp, $eliminabile, $time, $sconto['id']];
			}
		}
	}
	$sconti = '';
	if (count($testo_sconti)) {
		$sconti = implode(array_map(function ($arg) use ($IDrichiesta) {
			return '<div>' . $arg[0] .
				($arg[1] ? '
			 <button class="btn_sconti_dettagli"
			onclick="prev_elimina_variazioni(' . str_replace('"', '', json_encode($IDrichiesta)) . ",[" . $arg[2] . ']);" style="float: right;">Elimina</button> ' : '') . '</div> ';}, $testo_sconti));

		$sconti = '
		 	  <div class="content_interno">
		 	  		 ' . $sconti . '
	 				<hr>
				 	<button class="btn_sconti_dettagli"  onclick="prev_elimina_variazioni(' . str_replace('"', '', json_encode($IDrichiesta)) . ');">Reset</button>
			 </div> ';
	}

	$testo .= '
	<input type="hidden" id="richiesta_ID" value="' . $IDrequest_str . '">

		<div class="uk_grid_div div_list_uk"  uk-grid  onclick="modfica_prezzo_richiesta(this)" data-tipo="retta" id="tot-retta" data-totale="' . float_format($retta, true) . '">
			<div class="uk-width-expand lista_grid_nome uk-text-truncate" >Retta </div>
			<div class="uk-width-auto  lista_grid_right"> € ' . float_format($retta, true) . ' <span uk-icon="chevron-right" ></span></div>
		</div>

		' . ($extra ? '
		<div class="uk_grid_div div_list_uk"  uk-grid   onclick="modfica_prezzo_richiesta(this)"  data-tipo="extra"  >
			<div class="uk-width-expand lista_grid_nome uk-text-truncate" >Extra </div>
			<div class="uk-width-auto  lista_grid_right">€ ' . float_format($extra, true) . '<span uk-icon="chevron-right" ></span></div>
		</div>' : '') . '


		<div class="uk_grid_div div_list_uk"  uk-grid  >
			<div class="uk-width-expand lista_grid_nome uk-text-truncate" >Totale </div>
			<div class="uk-width-auto  lista_grid_right"> € ' . float_format($totale, true) . ' <span uk-icon="chevron-right" ></span></div>
		</div>



		<div style="padding:5px 10px;margin-top:10px" class="tab_content_tasto_menu">
				<div class="tasto_menu_default selected" onclick="switch_tab_menu(this)" data-tabid="1"> <i class="fas fa-percent"></i> Sconti</div>
				<div class="tasto_menu_default " onclick="switch_tab_menu(this)" data-tabid="2"> <i class="fas fa-euro-sign"></i> Storico</div>
		</div>




		<div class="div_elenco_tab" style="margin-top:10px">

			<div data-tabid="1" class="content_tab_menu">
				 	 			<div class="uk_grid_div div_list_uk"  uk-grid    data-tipo="0" data-testo="Retta"  onclick="modifica_variazione_richiesta(this)">
									<div class="uk-width-expand lista_grid_nome uk-text-truncate" >Retta </div>
									<div class="uk-width-auto  lista_grid_right"> Modifica <i class="fas fa-chevron-right"></i></div>
								</div>



				 	 	' . ($extra ? '

	 	 						<div class="uk_grid_div div_list_uk"  uk-grid   data-tipo="1" data-testo="Extra"  onclick="modifica_variazione_richiesta(this)">
									<div class="uk-width-expand lista_grid_nome uk-text-truncate" >Extra </div>
									<div class="uk-width-auto  lista_grid_right"> Modifica <i class="fas fa-chevron-right"></i></div>
								</div>

				 	 		' : '') . '



								<div class="uk_grid_div div_list_uk"  uk-grid  onclick="modifica_variazione_richiesta(this)" data-tipo="2" data-testo="Totale"   >
									<div class="uk-width-expand lista_grid_nome uk-text-truncate" >Totale </div>
									<div class="uk-width-auto  lista_grid_right"> Modifica <i class="fas fa-chevron-right"></i></div>
								</div>




								<div class="uk_grid_div div_list_uk"  uk-grid  onclick="modifica_variazione_richiesta(this)" data-tipo="3" data-testo="Variazione (%) "  >
									<div class="uk-width-expand lista_grid_nome uk-text-truncate" >Variazione (%) </div>
									<div class="uk-width-auto  lista_grid_right"> Modifica <i class="fas fa-chevron-right"></i></div>
								</div>

								<div class="uk_grid_div div_list_uk"  uk-grid  onclick="modifica_variazione_richiesta(this)" data-tipo="4" data-testo="Variazione (+/-) "  >
									<div class="uk-width-expand lista_grid_nome uk-text-truncate" >Variazione (+/-) </div>
									<div class="uk-width-auto  lista_grid_right"> Modifica <i class="fas fa-chevron-right"></i></div>
								</div>
			</div>


			<div  data-tabid="2"  class="content_tab_menu" style="display:none">

				' . $sconti . '

			</div>



		</div> ';

	$testo .= "
			<script>
			switch_tab_content_menu('.tab_content_tasto_menu .tasto_menu_default','.div_elenco_tab .content_tab_menu');
		</script>";
	break;
}

echo $testo;

?>
