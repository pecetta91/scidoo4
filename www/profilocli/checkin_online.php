<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../config/connecti.php';
require_once __DIR__ . '/../../config/funzioni.php';
$IDprenotazione = $_SESSION['IDstrpren'];
$IDstruttura = get_IDstruttura_from_IDpren($IDprenotazione);
$lang = $_SESSION['lang'] ?: 0;

$dati = $_POST['arr_dati'] ?? [];
$IDschedina_aperta = $dati['IDschedina'] ?? 0;

$dati_struttura = genera_sotto_strutture_lista($IDstruttura)[0];
$prefisso = $dati_struttura['prefisso'];

$dettaglio_prenotazione = get_prenotazioni(['0' => ['IDprenotazione' => $IDprenotazione]], [], [], $IDstruttura)['dati'][$IDprenotazione];

$persone_prenotazione = [];
$query = "SELECT ID,IDcliente,IDrest,nome FROM infopren WHERE IDstr=$IDstruttura AND IDpren=$IDprenotazione";

$result = mysqli_query($link2, $query);
while ($row = mysqli_fetch_row($result)) {
	$IDinfo_pren = $row[0];
	$IDschedina = $row[1];

	if ($IDschedina == 0) {
		$IDschedina = inscerisci_schedina($IDstruttura, [['prefissotel' => $prefisso, 'prefissocell' => $prefisso]])[0];
		$query = "UPDATE infopren SET IDcliente=$IDschedina WHERE ID=$IDinfo_pren LIMIT 1";
		$result = mysqli_query($link2, $query);
	}

	$persone_prenotazione[$IDschedina] = ['ID' => $IDschedina, 'IDrestrizione' => $row[2], 'nome_restrizione' => $row['3'], 'IDinfo_pren' => $IDinfo_pren];
}

$lista_schedine = [];
if (!empty($persone_prenotazione)) {
	$lista_schedine = implode(',', array_keys($persone_prenotazione));
}

$lista_ospiti = estrai_dati_ospiti([['IDcliente' => $lista_schedine]], [], $IDstruttura)['dati'];

$counter = 0;

$clienti_div = '';
if (!empty($lista_ospiti)) {
	foreach ($lista_ospiti as $dati) {
		$IDschedina = $dati['ID'];

		$dati_scheda = '

		<div uk-grid class="uk-margin-small uk_grid_div div_list_uk">
			<div class="uk-width-expand   lista_grid_nome">' . traduci('Nome', $lang) . '</div>
			<div class="uk-width-expand uk-text-right lista_grid_right">
				   <input class="uk-input input_cli  uk-form-small" type="text"  value="' . $dati['nome'] . '"  onchange="mod_ospite(1,' . $IDschedina . ',this,11)"  placeholder="' . traduci('Nome', $lang) . '">
			</div>
		</div>


		<div uk-grid class="uk-margin-small uk_grid_div div_list_uk">
			<div class="uk-width-expand   lista_grid_nome">' . traduci('Cognome', $lang) . '</div>
			<div class="uk-width-expand uk-text-right lista_grid_right">
				   <input class="uk-input input_cli  uk-form-small" type="text"  value="' . $dati['cognome'] . '"    onchange="mod_ospite(2,' . $IDschedina . ',this,11)"  placeholder="' . traduci('Cognome', $lang) . '">
			</div>
		</div> ';

		$etichietta_sesso = ($dati['sesso'] == 'M' ? traduci('Maschio', $lang) : ($dati['sesso'] == 'F' ? traduci('Femmina', $lang) : traduci('Seleziona', $lang)));

		$dati_scheda .= '
			<div id="sesso' . $IDschedina . '" style="display:none;">
				<ul class="uk-list uk-list-divider uk-picker-bot" style="padding:5px 20px;" onchange="(r)=>{mod_ospite(17,' . $IDschedina . ',r,10,()=>{navigation_ospite(10,{IDschedina:' . $IDschedina . '},0,0)});chiudi_picker()}">
					' . genera_select_uikit([0 => traduci('Seleziona', $lang), 'M' => traduci('Maschio', $lang), 'F' => traduci('Femmina', $lang)], $dati['sesso']) . ' </ul>
			</div>

			<div uk-grid class="uk-margin-small uk_grid_div div_list_uk " onclick="carica_content_picker(\'sesso' . $IDschedina . '\')">
				<div class="uk-width-expand   lista_grid_nome  ">' . traduci('Sesso', $lang) . '</div>
				<div class="uk-width-expand c000 lista_grid_right">' . $etichietta_sesso . '</div>
			</div>





			<div uk-grid class="uk-margin-small uk_grid_div div_list_uk">

				<div class="uk-width-expand   lista_grid_nome  ">' . traduci('Cittadinanza', $lang) . '</div>


			    <div class="  uk-width-expand  uk-text-right lista_grid_right" style="position:relative">

			       <input class="uk-input input_cli  uk-form-small  uk-form-small   "  value="' . $dati['cittadinanza'] . '"  data-url="' . base_url() . '/config/searchbox/alloggiati_stati.php"
			       id="cittadinanza' . $IDschedina . '" type="text" autocomplete="off" placeholder="' . traduci('Cittadinanza', $lang) . '" >
		    	</div>
			</div>
			<script>
				$("#cittadinanza' . $IDschedina . '").searchBox({
					onclick:function (args){
						 mod_ospite(6,' . $IDschedina . ',args.id,10,()=>{navigation_ospite(10,{IDschedina:' . $IDschedina . '},0,1)});

					 },autoSelect:1,
				});
			</script>

			<div uk-grid class="uk-margin-small uk_grid_div div_list_uk">

				<div class="uk-width-expand   lista_grid_nome  uk-text-truncate">' . traduci('Luogo di Nascita', $lang) . '</div>

				<div class="uk-width-expand uk-text-right lista_grid_right"  style="position:relative">

				 <input class="uk-input input_cli  uk-form-small  uk-form-small   "   data-url="' . base_url() . '/config/searchbox/alloggiati_comune.php"
			       id="luogo_nas' . $IDschedina . '"  value="' . $dati['luogonas'] . '" type="text" autocomplete="off" placeholder="' . traduci('Luogo di Nascita', $lang) . '" >

				</div>
			</div>
			<script>
				$("#luogo_nas' . $IDschedina . '").searchBox({
					onclick:function (args){
						 mod_ospite(7,' . $IDschedina . ',args.id,10,()=>{navigation_ospite(10,{IDschedina:' . $IDschedina . '},0,1)});
					 },autoSelect:1,
				});
			</script>


			<div uk-grid class="uk-margin-small uk_grid_div div_list_uk">

				<div class="uk-width-expand   lista_grid_nome  ">' . traduci('Data di Nascita', $lang) . '</div>


				<div class="uk-width-expand uk-text-right lista_grid_right">
					   <input class="uk-input input_cli  uk-form-small" type="text" data-testo="Seleziona anno di nascita" onclick="apri_modal(this,1);"
					    onchange=" mod_ospite(13,' . $IDschedina . ',this,11)"
					   value="' . ($dati['datanas'] != '0000-00-00' ? convertidata3($dati['datanas'], 'SI') : 'dd/mm/yyyy') . '"
					   data-noformat="' . ($dati['datanas'] != '0000-00-00' ? date('d-m-Y', strtotime($dati['datanas'])) : '') . '" placeholder="' . traduci('Data di Nascita', $lang) . '"  readonly>
				</div>


			</div>


			<div uk-grid class="uk-margin-small uk_grid_div div_list_uk">

				<div class="uk-width-expand   lista_grid_nome  uk-text-truncate">' . traduci('Residenza', $lang) . '</div>

				<div class="uk-width-expand uk-text-right lista_grid_right"  style="position:relative">

				 <input class="uk-input input_cli  uk-form-small  uk-form-small   "   data-url="' . base_url() . '/config/searchbox/alloggiati_comune.php"
			       id="residenza' . $IDschedina . '"  value="' . $dati['residenza'] . '"  type="text" autocomplete="off" placeholder="' . traduci('Residenza', $lang) . '" >

				</div>
			</div>
			<script>
				$("#residenza' . $IDschedina . '").searchBox({
					onclick:function (args){
					 mod_ospite(8,' . $IDschedina . ',args.id,10,()=>{navigation_ospite(10,{IDschedina:' . $IDschedina . '},0,1)});
					 },autoSelect:1,
				});
			</script>


			<div uk-grid class="uk-margin-small uk_grid_div div_list_uk">

				<div class="uk-width-expand   lista_grid_nome  ">' . traduci('Indirizzo', $lang) . '</div>

				<div class="uk-width-expand uk-text-right lista_grid_right" style="position:relative">

					   <input class="uk-input input_cli  uk-form-small " type="text"  value="' . $dati['indirizzo'] . '" onchange=" mod_ospite(9,' . $IDschedina . ',this,11)" placeholder="' . traduci('Indirizzo', $lang) . '">
				</div>

			</div>
				<hr>
			';

		if ($counter == 0) {

			$dati_scheda .= '


					<div uk-grid class="uk-margin-small uk_grid_div div_list_uk">

						<div class="uk-width-expand   lista_grid_nome  uk-text-truncate">' . traduci('Documento', $lang) . '</div>

						<div class="uk-width-expand uk-text-right lista_grid_right"  style="position:relative">

						 <input class="uk-input input_cli  uk-form-small  uk-form-small   "   data-url="' . base_url() . '/config/searchbox/alloggiati_documenti.php"
					       id="documento' . $IDschedina . '"  type="text" autocomplete="off" placeholder="' . traduci('Documento', $lang) . '"  value="' . $dati['documento'] . '">

						</div>
					</div>
					<script>
						$("#documento' . $IDschedina . '").searchBox({
							onclick:function (args){
								 mod_ospite(10,' . $IDschedina . ',args.id,10,()=>{navigation_ospite(10,{IDschedina:' . $IDschedina . '},0,1)});
							 },autoSelect:1,
						});
					</script>



					<div uk-grid class="uk-margin-small uk_grid_div div_list_uk">

						<div class="uk-width-expand   lista_grid_nome  uk-text-truncate">' . traduci('Numero Documento', $lang) . '</div>

						<div class="uk-width-expand uk-text-right lista_grid_right">
							   <input class="uk-input input_cli  uk-form-small" type="text" onchange="mod_ospite(11,' . $IDschedina . ',this,11)"  value="' . $dati['numero_documento'] . '"  placeholder="' . traduci('Numero Documento', $lang) . '" >
						</div>
					</div>



					<div uk-grid class="uk-margin-small uk_grid_div div_list_uk">

						<div class="uk-width-expand   lista_grid_nome  ">' . traduci('Data di Rilascio', $lang) . '</div>



						<div class="uk-width-expand uk-text-right lista_grid_right">
							   <input class="uk-input input_cli  uk-form-small" type="text" data-testo="Seleziona anno di nascita" onclick="apri_modal(this,1);"
							    onchange=" mod_ospite(14,' . $IDschedina . ',this,11)"
							   value="' . ($dati['data_rilascio'] != '0000-00-00' ? convertidata3($dati['data_rilascio'], 'SI') : 'dd/mm/yyyy') . '"
							   data-noformat="' . ($dati['data_rilascio'] != '0000-00-00' ? date('d-m-Y', strtotime($dati['data_rilascio'])) : '') . '" placeholder="' . traduci('Data di Rilascio', $lang) . '"  readonly>
						</div>


					</div>

					<div uk-grid class="uk-margin-small uk_grid_div div_list_uk">

						<div class="uk-width-expand   lista_grid_nome uk-text-truncate ">' . traduci('Luogo di Rilascio', $lang) . '</div>

						<div class="uk-width-expand uk-text-right lista_grid_right" style="position:relative">

							 <input class="uk-input input_cli  uk-form-small  uk-form-small   "   data-url="' . base_url() . '/config/searchbox/alloggiati_comune.php"
						       id="rilascio' . $IDschedina . '" type="text" autocomplete="off" placeholder="' . traduci('Luogo di Rilascio', $lang) . '"  value="' . $dati['luogoril'] . '">

							</div>
					</div>

					<script>
						$("#rilascio' . $IDschedina . '").searchBox({
							onclick:function (args){
									 mod_ospite(12,' . $IDschedina . ',args.id,10,()=>{navigation_ospite(10,{IDschedina:' . $IDschedina . '},0,1)});
							 },autoSelect:1,
						});
					</script>
			<hr>  ';
		}

		$dati_scheda .= '
		<div uk-grid class="uk-margin-small uk_grid_div div_list_uk">

			<div class="uk-width-expand   lista_grid_nome  ">Email</div>

			<div class="uk-width-expand uk-text-right lista_grid_right">
				   <input class="uk-input input_cli  uk-form-small" type="text"  value="' . $dati['email'] . '"   onchange="mod_ospite(3,' . $IDschedina . ',this,11)"  placeholder="E-mail" >
			</div>

		</div>




			<div id="pref_cell' . $IDschedina . '" style="display:none;">
				<ul class="uk-list uk-list-divider uk-picker-bot" style="padding:5px 20px;" onchange="(r)=>{mod_ospite(15,' . $IDschedina . ',r,10,()=>{navigation_ospite(10,{IDschedina:' . $IDschedina . '},0,0)});chiudi_picker()}">
					' . genera_select_uikit(generaprefisso_uikit(), $dati['prefisso_cell'], []) . ' </ul>
			</div>

			<div uk-grid class="uk-margin-small uk_grid_div div_list_uk">
				<div class="uk-width-1-3   lista_grid_nome  ">' . traduci('Cellulare', $lang) . '</div>
				<div class="uk-width-1-4"  onclick="carica_content_picker(\'pref_cell' . $IDschedina . '\')" style="padding-left: 10px;">
					<div class="uk-inline">
							<span class="uk-form-icon uk-form-icon-flip uk-icon uk_picker-select" uk-icon="icon:triangle-down;ratio:.7"></span>
						<div class="uk-input input_cli  uk-form-small uk_picker_select"> + ' . $dati['prefisso_cell'] . '</div>
					</div>
				</div>

				<div class="uk-width-expand uk-text-right lista_grid_right">
					   <input class="uk-input input_cli  uk-form-small" type="number"   value="' . $dati['cellulare'] . '"   onchange="mod_ospite(4,' . $IDschedina . ',this,11)" placeholder="' . traduci('Cellulare', $lang) . '" >
				</div>
			</div>



			<div id="pref_tell' . $IDschedina . '" style="display:none;">
				<ul class="uk-list uk-list-divider uk-picker-bot" style="padding:5px 20px;" onchange="(r)=>{mod_ospite(16,' . $IDschedina . ',r,10,()=>{navigation_ospite(10,{IDschedina:' . $IDschedina . '},0,0)});chiudi_picker()}">
					' . genera_select_uikit(generaprefisso_uikit(), $dati['prefisso_tel'], []) . ' </ul>
			</div>

			<div uk-grid class="uk-margin-small uk_grid_div div_list_uk">
				<div class="uk-width-1-3   lista_grid_nome  ">' . traduci('Telefono', $lang) . '</div>
				<div class="uk-width-1-4" onclick="carica_content_picker(\'pref_tell' . $IDschedina . '\')" style="padding-left: 10px;">
					 <div class="uk-inline">
						<span class="uk-form-icon uk-form-icon-flip uk-icon uk_picker-select" uk-icon="icon:triangle-down;ratio:.7"></span>
						<div  class="uk-input input_cli  uk-form-small uk_picker_select"> + ' . $dati['prefisso_tel'] . '</div>
					</div>
				</div>
				<div class="uk-width-expand uk-text-right lista_grid_right">
					   <input class="uk-input input_cli  uk-form-small" type="number"   value="' . $dati['telefono'] . '"  onchange="mod_ospite(5,' . $IDschedina . ',this,11)"  placeholder="' . traduci('Telefono', $lang) . '"  >
				</div>
			</div> ';

		if ($counter == 0) {

			$lista_foto = estrai_immagini([['IDobj' => $IDschedina, 'tipoobj' => 19]], $IDstruttura);
			$foto_txt = '';

			if (!empty($lista_foto)) {
				$foto_txt = '<div class="div_uk_divider_list" >' . traduci('Documenti', $lang) . ' </div>';
				foreach ($lista_foto as $dati_foto) {

					$foto_txt .= '
				<div class=" uk_grid_div div_list_uk" uk-grid   uk-lightbox data-uk-lightbox="toggle: .visualizza_doc ">

					<div class="uk-width-1-2 visualizza_doc">
						 <a  style="font-size:13px;margin-right:5px" onclick=" "  href="' . base_url() . '/immagini/big' . $dati_foto['foto'] . '" >
							<div style="height:100px;background-size:cover;background-image:url(' . base_url() . '/immagini/big' . $dati_foto['foto'] . ');background-repeat:no-repeat;background-position:center;position:relative;">

									<div style="    width: 30px;   height: 30px;  position: absolute;  color: #fff;
									    background: #0e0e0e4f;  left: 50%;   transform:translate(-50%,-50%); top: 50%;     font-size: 20px;
									    text-align: center;    cursor: pointer;  border-radius: 2px;"><i class="fas fa-search" style="  line-height: 30px; "></i>
									</div>
							</div>
							</a>
					</div>

				    <div class="uk-width-auto lista_grid_nome  " style="padding: 0px; text-align: right;">
				  	  	 <button  class="button_salva_preventivo" style="font-size:13px;background:#CB0003"  onclick="mod_ospite(36,' . $dati_foto['ID'] . ',0,10,()=>{navigation_ospite(10,{IDschedina:' . $IDschedina . '},0,1)});"><i class="fas fa-trash-alt"></i> ' . traduci('Elimina', $lang) . ' </button>

				    </div>

			</div>  ';
				}

			}

			$dati_scheda .= '<div style="margin-top:20px">
			<button class="button_salva_preventivo" style="margin:10px " onclick="aggiungi_foto(' . $IDschedina . ',19,()=>{navigation_ospite(10,{IDschedina:' . $IDschedina . '},0,1);});"><i class="fas fa-file-upload"></i> Aggiungi Documento</button>

				<div style="margin:10px 0">' . $foto_txt . '</div><div class="uk-padding uk-margin"></div>

		</div>';
		}

		$arr = [];

		$response = controllocheckin_online($IDprenotazione, $IDstruttura, 0, $arr, 1, $IDschedina, $counter, 1);
		$counter++;

		$clienti_div .= '
		    <li ' . (isset($IDschedina_aperta) ? (($IDschedina_aperta == $IDschedina) ? 'class="uk-open"' : '') : '') . '>
		        <a class="uk-accordion-title no_before">
				        <div class=" uk_grid_div div_list_uk" uk-grid  style="border-radius: 5px;  background: #e5e5e5; padding: 5px;">
						    <div class="uk-width-expand lista_grid_nome" style="padding-left: 15px;"> ' . ($dati['nome'] ? $dati['nome'] . ' ' . $dati['cognome'] : '') . '  <br>
							    <span class="uk-text-muted uk-text-small" >
							    ' . $persone_prenotazione[$IDschedina]['nome_restrizione'] . '
							    ' . ($dati['email'] != '' ? ' , <i class="fas fa-envelope"></i> ' . $dati['email'] : '') . '
						        ' . ($dati['cellulare'] != '' ? ' , <i class="fas fa-phone"></i> ' . $dati['cellulare'] : '') . '

							    </span>
						    </div>


					    	 <div class="uk-width-auto  uk-text-right lista_grid_right  " >
							     ' . ($response ? '<span style="color:#4caf50;font-weight:600">' . traduci('Effettuato', $lang) . '</span>' : '<span style="color: e11b1b;font-weight:600">' . traduci('Da effettuare', $lang) . '</span>') . '
								<i class="fas fa-chevron-right"></i>
							</div>
						</div>
		        </a>
				        <div class="uk-accordion-content">
				            	' . $dati_scheda . '
				        </div>
    </li> ';

	}
}

$testo = '<ul uk-accordion style="padding:0 10px;">' . $clienti_div . '</ul><br><br/>


<div><button class="button_salva_preventivo" style="margin:10px " onclick="mod_ospite(26,0,0,10)"> <i class="fas fa-share-square"></i> ' . traduci('Condividi App', $lang) . '</button></div>
';

echo $testo;
?>
