<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';

$testo = '';
$IDutente = $_SESSION['ID'];
$IDstruttura = $_SESSION['IDstruttura'];

$IDpreventivo = $_POST['IDpreventivo'];

$query = "SELECT checkin,checkout,IDcliente,IDagenzia,inviato_da,lingua,time,scadenza,IDsequenziale,chiusura,lato_amministratore,IDprovenienza,gruppo,stato FROM preventivo WHERE IDstruttura='$IDstruttura' AND ID='$IDpreventivo'";
$result = $link2->query($query);
list($checkin, $checkout, $IDcliente, $IDagenzia, $inviato_da, $lingua, $time, $scadenza, $IDsequenziale, $mostra_chiusure, $mostra_lato_amministratore, $IDprovenienza_canale, $p_di_gruppo, $stato_preventivo) = $result->fetch_row();
$scadenza = $scadenza ? intval(($scadenza - $time) / 86400) : 0;
$data_oggi = date('d/m/Y', $checkin);
$data_domani = date('d/m/Y', $checkout);
$giorni = intval((time0($checkout) - time0($checkin)) / 86400);

//--------------orari default
$checkin_orario = $checkin - time0($checkin);
$checkout_orario = $checkout - time0($checkout);

$checkin_orario = sprintf("%02d:%02d", $checkin_orario / 3600, (($checkin_orario % 3600) / 60));
$checkout_orario = sprintf("%02d:%02d", $checkout_orario / 3600, (($checkout_orario % 3600) / 60));

$data_checkin = convertidata3($data_oggi, 'SI');
$data_checkin_noformat = convertiData($data_oggi, 'NO');

$data_checkout = convertidata3($data_domani, 'SI');
$data_checkout_noformat = convertiData($data_domani, 'NO');

$query = "SELECT IDrestr,quantita FROM preventivo_persone WHERE IDpreventivo='$IDpreventivo'";
$result = $link2->query($query);
$persone_preventivo = [];
while ($row = $result->fetch_row()) {
	$persone_preventivo[$row[0]] = $row[1];
}
//modifica_preventivo([' . $IDpreventivo . ',' . $IDrestrizione . '],' . "'restriz" . $IDrestrizione . "'" . ',4,8)

/*

<div class="stepper  stepper-init stepperrestr">

<div class="stepper-button-minus uk-icon" onclick="selezionainfo(' . "'restriz" . $IDrestrizione . "'" . ',2,0)" uk-icon="minus"> </div>

<div class="stepper-value  inputrestr" min="0" max="100" onchange="
mod_preventivo(11,[' . $IDpreventivo . ',' . $IDrestrizione . '],' . "'restriz" . $IDrestrizione . "'" . ',8); "  id="restriz' . $IDrestrizione . '">' . $numero . '</div>

<div class="stepper-button-plus uk-icon" onclick="selezionainfo(' . "'restriz" . $IDrestrizione . "'" . ',1,0)" uk-icon="plus"> </div>
</div>
 */
$scelta_persone = '';
$query = "SELECT ID,restrizione,tiporest,personale,personad FROM tiporestr WHERE IDstr='$IDstruttura' AND limite='0' AND personale=1 ORDER BY ordine";
$result = $link2->query($query);
$scelta_persone .= '<table><tr>';
while ($row = $result->fetch_row()) {
	$IDrestrizione = $row[0];
	$numero = $persone_preventivo[$IDrestrizione] ?? '0';
	$scelta_persone .= '
	<div class="div_list_uk uk_grid_div  " uk-grid >
		    <div class="uk-width-1-2 uk-text-truncate lista_grid_nome uk-first-column">' . $row[1] . '</div>
		    <div class="uk-width-expand uk-text-right lista_grid_right ">

			    <div class="stepper  stepper-init stepperrestr">
	    			   <div class="stepper-button-minus" style="color:#0075ff;border:none" onclick="selezionainfo(\'restriz' . $IDrestrizione . '\',2,0)"><i class="fas fa-minus"></i></div>


					   <div class="stepper-value  restrizione"  min="0" max="100"  id="restriz' . $IDrestrizione . '" style="border-bottom:1px solid #d6d6d6"
					   onchange="   mod_preventivo(11,[' . $IDpreventivo . ',' . $IDrestrizione . '],\'restriz' . $IDrestrizione . '\',8); "
					   >' . $numero . ' </div>

					   <div class="stepper-button-plus" style="color:#0075ff;border:none" onclick="selezionainfo(\'restriz' . $IDrestrizione . '\',1,0)"><i class="fas fa-plus"></i></div>
				 </div>

		    </div>
		</div> ';
}

// <li class="' . (($p_di_gruppo) ? 'uk-active' : '') . '" ><a href="#">Preventivo di Gruppo</a></li>

$testo .= '
<div style="padding-bottom:10px;">
			<ul  uk-tab="animation: uk-animation-fade;swiping:false" style="margin-bottom:0;    place-content: center;">
		        <li class="' . ((!$p_di_gruppo and $giorni) ? 'uk-active' : '') . '"  onclick="mostra_giornaliero_preventivo(\'soggiorno\')"><a href="#">Soggiorno</a></li>

		        <li class="' . ((!$p_di_gruppo and !$giorni) ? 'uk-active' : '') . '" onclick=" mostra_giornaliero_preventivo(\'giornaliero\')"><a href="#">Giornaliero</a></li>
	        </ul>
	         </div>

	<div uk-grid class="uk-margin-small uk_grid_div div_list_uk">

			<div class="uk-width-expand   lista_grid_nome  ">Arrivo</div>

			<div class="uk-width-expand uk-text-right lista_grid_right">
				   <input class="uk-input input_cli  uk-form-small" id="preventivo_arrivo"
				   onchange="modifica_data_preventivo(' . $IDpreventivo . ')"
				   type="text" data-testo="Seleziona Arrivo" onclick="apri_modal(this,1);"  value="' . $data_checkin . '"  data-noformat="' . $data_checkin_noformat . '" placeholder="Arrivo"  readonly>
			</div>
		</div>

		<div uk-grid class="uk-margin-small uk_grid_div div_list_uk div_partenza_prev" style="' . (!$giorni ? 'display:none' : '') . '">

			<div class="uk-width-expand   lista_grid_nome  ">Partenza</div>

			<div class="uk-width-expand uk-text-right lista_grid_right">
				   <input class="uk-input input_cli  uk-form-small" id="preventivo_partenza"
				   onchange="modifica_data_preventivo(' . $IDpreventivo . ');"
				   type="text" data-testo="Seleziona Arrivo" onclick="apri_modal(this,1);"  value="' . $data_checkout . '"  data-noformat="' . $data_checkout_noformat . '" placeholder="Partenza"  readonly>
			</div>
		</div>

			<div uk-grid class="uk-margin-small uk_grid_div div_list_uk">

				<div class="uk-width-expand   lista_grid_nome  ">Filtri</div>


			    <div class="  uk-width-expand  uk-text-right lista_grid_right" style="position:relative">

			       <input class="uk-input input_cli  uk-form-small  uk-form-small   "  value=""  data-url="' . base_url() . '/config/searchbox/tag_preventivo.php"
			       id="tag" type="text" autocomplete="off" placeholder="Filtra per Alloggio,Categoria e Tag" >
		    	</div>
			</div>
			<script>


				$("#tag").searchBox({
					onclick:function (args){
						mod_preventivo(42, ' . $IDpreventivo . ', args, \'var\',()=>{  chiudi_picker();modifica_dettagli_nuovo_preventivo( ' . $IDpreventivo . ');})
					},
				});

				$(".preventivatore__tag-container").on("click", function(evt) {
						let elem = $(evt.target).closest(".preventivatore__tag__delete");
				if (!elem.length) return;
				let id = elem.closest(".preventivatore__tag").data("id");
						mod_preventivo(43, ' . $IDpreventivo . ', id, \'var\',()=>{  chiudi_picker();modifica_dettagli_nuovo_preventivo( ' . $IDpreventivo . ');})
				});
			</script>

<div style="margin:10px 0" class="preventivatore__tag-container">' . prev_elenco_tag($IDpreventivo) . '</div>


<div class="div_uk_divider_list"> Ospiti </div>' . $scelta_persone;

$picker = '
<div class="nav navbar_picker_flex" >
 	<div onclick="chiudi_picker()"><i class="fas fa-times icona_chiudi" ></i></div>
 	<div style="margin-top:5px;margin-right:10px;"><button class="btn_avanti_preventivo" onclick="chiudi_picker();inizia_ricerca_preventivo()">Ricerca</button></div>
</div>

<div class="content" style="padding:10px 5px;"> ' . $testo . ' </div> ';

echo $picker;

?>
