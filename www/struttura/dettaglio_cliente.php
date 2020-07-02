<?php
//header('Access-Control-Allow-Origin: *');
include '../../config/connecti.php';
include '../../config/funzioni.php';
array_escape($_POST);

$IDutente = $_SESSION['ID'];
$IDstruttura = $_SESSION['IDstruttura'];

$dati = $_POST['arr_dati'] ?? [];
$IDinfo_prenonatizione = $dati['IDinfopren'] ?? 0;
$IDcliente = $dati['IDcliente'] ?? 0;

$time0 = strtotime(date('Y-m-d'));

$query = "SELECT prefisso FROM strutture WHERE ID='$IDstruttura' LIMIT 1";
$result = mysqli_query($link2, $query);
$row = mysqli_fetch_row($result);
$prefisso = $row['0'];

$arr1 = array('Seleziona', 'Maschio', 'Femmina');
$arr2 = array('0', 'M', 'F');

if ($IDinfo_prenonatizione) {
	$query = "SELECT i.ID,i.IDcliente FROM infopren as i WHERE i.IDstr='$IDstruttura' AND i.ID='$IDinfo_prenonatizione' AND i.pers='1'";
	$result = mysqli_query($link2, $query);
	$row = mysqli_fetch_row($result);
	$IDcliente = $row['1'];
}

if ($IDcliente == 0) {
	$array_info_cli[0] = ['prefissotel' => $prefisso, 'prefissocell' => $prefisso];
	$IDscheda = inserisci_schedina($IDstruttura, $array_info_cli)[0];
	$query2 = "UPDATE infopren SET IDcliente='$IDscheda' WHERE ID='$IDinfo_prenonatizione' LIMIT 1";
	$result = mysqli_query($link2, $query2);
	$IDcliente = $IDscheda;
}

$lista_ospiti = estrai_dati_ospiti([['IDcliente' => $IDcliente]], [], $IDstruttura)['dati'][$IDcliente];

$testo = '
	<input type="hidden" value="' . $IDinfo_prenonatizione . '" id="IDinfop"/>
	<input type="hidden" value="' . $IDcliente . '" id="IDcliente"/>
	<div class="div_uk_divider_list" >Dati Ospite</div>

	<div uk-grid class="uk-margin-small uk_grid_div div_list_uk">

		<div class="uk-width-expand   lista_grid_nome">Nome</div>

		<div class="uk-width-expand uk-text-right lista_grid_right">
			   <input class="uk-input input_cli  uk-form-small" type="text"  value="' . $lista_ospiti['nome'] . '" onchange="mod_anagrafiche(67,' . $IDcliente . ',this,11);"  placeholder="Nome">
		</div>

	</div>

	<div uk-grid class="uk-margin-small uk_grid_div div_list_uk">

		<div class="uk-width-expand   lista_grid_nome">Cognome</div>

		<div class="uk-width-expand uk-text-right lista_grid_right">
			   <input class="uk-input input_cli  uk-form-small" type="text"  value="' . $lista_ospiti['cognome'] . '" onchange="mod_anagrafiche(68,' . $IDcliente . ',this,11);"   placeholder="Cogome">
		</div>

	</div>


	<div uk-grid class="uk-margin-small uk_grid_div div_list_uk">

		<div class="uk-width-expand   lista_grid_nome  ">Email</div>

		<div class="uk-width-expand uk-text-right lista_grid_right">
			   <input class="uk-input input_cli  uk-form-small" type="text"  value="' . $lista_ospiti['email'] . '" onchange="mod_anagrafiche(69,' . $IDcliente . ',this,11);" placeholder="E-mail" >
		</div>

	</div>




	<div id="pref_cell" style="display:none;">
		<ul class="uk-list uk-list-divider uk-picker-bot" style="padding:5px 20px;" onchange="(r)=>{
			mod_anagrafiche(70,' . $IDcliente . ',r,10,()=>{chiudi_picker();navigation(7,{IDcliente:' . $IDcliente . '});}) ;chiudi_picker()}">
			' . genera_select_uikit(generaprefisso_uikit(), $lista_ospiti['prefisso_cell'], []) . ' </ul>
	</div>
	<div uk-grid class="uk-margin-small uk_grid_div div_list_uk">
		<div class="uk-width-1-3   lista_grid_nome  "> Cellulare</div>
		<div class="uk-width-1-4"  onclick="carica_content_picker(\'pref_cell\')" style="padding-left: 10px;">
			<div class="uk-inline">
					<span class="uk-form-icon uk-form-icon-flip uk-icon uk_picker-select" uk-icon="icon:triangle-down;ratio:.7"></span>
				<div class="uk-input input_cli  uk-form-small uk_picker_select"> + ' . $lista_ospiti['prefisso_cell'] . '</div>
			</div>
		</div>

		<div class="uk-width-expand uk-text-right lista_grid_right">
		   <input class="uk-input input_cli  uk-form-small" type="number"   value="' . $lista_ospiti['cellulare'] . '"   onchange="mod_anagrafiche(71,' . $IDcliente . ',this,11);" placeholder="Cellulare" >
		</div>
	</div>



	<div id="pref_tell" style="display:none;">
		<ul class="uk-list uk-list-divider uk-picker-bot" style="padding:5px 20px;" onchange="(r)=>{
			mod_anagrafiche(72,' . $IDcliente . ',r,10,()=>{chiudi_picker();navigation(7,{IDcliente:' . $IDcliente . '});}) ;chiudi_picker()}">
			' . genera_select_uikit(generaprefisso_uikit(), $lista_ospiti['prefisso_tel'], []) . ' </ul>
	</div>
	<div uk-grid class="uk-margin-small uk_grid_div div_list_uk">
		<div class="uk-width-1-3   lista_grid_nome  "> Telefono</div>
		<div class="uk-width-1-4"  onclick="carica_content_picker(\'pref_tell\')" style="padding-left: 10px;">
			<div class="uk-inline">
					<span class="uk-form-icon uk-form-icon-flip uk-icon uk_picker-select" uk-icon="icon:triangle-down;ratio:.7"></span>
				<div class="uk-input input_cli  uk-form-small uk_picker_select"> + ' . $lista_ospiti['prefisso_tel'] . '</div>
			</div>
		</div>

		<div class="uk-width-expand uk-text-right lista_grid_right">
		   <input class="uk-input input_cli  uk-form-small" type="number"   value="' . $lista_ospiti['telefono'] . '"   onchange="mod_anagrafiche(73,' . $IDcliente . ',this,11);" placeholder="Telefono" >
		</div>
	</div>';

$etichietta_sesso = ($lista_ospiti['sesso'] == 'M' ? 'Maschio' : ($lista_ospiti['sesso'] == 'F' ? 'Femmina' : 'Seleziona'));

$testo .= '
			<div id="sesso" style="display:none;">
				<ul class="uk-list uk-list-divider uk-picker-bot" style="padding:5px 20px;" onchange="(r)=>{
			mod_anagrafiche(74,' . $IDcliente . ',r,10,()=>{chiudi_picker();navigation(7,{IDcliente:' . $IDcliente . '});}) ;chiudi_picker()}">
					' . genera_select_uikit([0 => 'Seleziona', 'M' => 'Maschio', 'F' => 'Femmina'], $lista_ospiti['sesso']) . ' </ul>
			</div>

			<div uk-grid class="uk-margin-small uk_grid_div div_list_uk " onclick="carica_content_picker(\'sesso\')">
				<div class="uk-width-expand   lista_grid_nome  ">Sesso</div>
				<div class="uk-width-expand c000 lista_grid_right">' . $etichietta_sesso . '</div>
			</div>




	<hr>


	<div uk-grid class="uk-margin-small uk_grid_div div_list_uk">
		<div class="uk-width-expand   lista_grid_nome  ">Cittadinanza</div>
	    <div class="  uk-width-expand  uk-text-right lista_grid_right" style="position:relative">

	       <input class="uk-input input_cli  uk-form-small  uk-form-small   "  value="' . $lista_ospiti['cittadinanza'] . '"  data-url="' . base_url() . '/config/searchbox/alloggiati_stati.php"
	       id="cittadinanza" type="text" autocomplete="off" placeholder="Cittadinanza " >
    	</div>
	</div>
		<script>
			$("#cittadinanza").searchBox({
				onclick:function (args){
					mod_anagrafiche(75,' . $IDcliente . ',args.id,10,()=>{navigation(7,{IDcliente:' . $IDcliente . '});});
				 },autoSelect:1,
			});
		</script>

			<div uk-grid class="uk-margin-small uk_grid_div div_list_uk">

				<div class="uk-width-expand   lista_grid_nome  uk-text-truncate"> Luogo di Nascita </div>

				<div class="uk-width-expand uk-text-right lista_grid_right"  style="position:relative">

				 <input class="uk-input input_cli  uk-form-small  uk-form-small   "   data-url="' . base_url() . '/config/searchbox/alloggiati_comune.php"
			       id="luogo_nas"  value="' . $lista_ospiti['luogonas'] . '" type="text" autocomplete="off" placeholder="Luogo di Nascita" >

				</div>
			</div>
		<script>
				$("#luogo_nas").searchBox({
					onclick:function (args){
						mod_anagrafiche(76,' . $IDcliente . ',args.id,10,()=>{navigation(7,{IDcliente:' . $IDcliente . '});});
					 },autoSelect:1,
				});
			</script>



			<div uk-grid class="uk-margin-small uk_grid_div div_list_uk">

				<div class="uk-width-expand   lista_grid_nome  ">Data di Nascita</div>

				<div class="uk-width-expand uk-text-right lista_grid_right">
					   <input class="uk-input input_cli  uk-form-small" type="text" data-testo="Seleziona anno di nascita" onclick="apri_modal(this,1);"
					   onchange="mod_anagrafiche(77,' . $IDcliente . ',this,11);"
					     value="' . ($lista_ospiti['datanas'] != '0000-00-00' ? convertidata3($lista_ospiti['datanas'], 'SI') : 'dd/mm/yyyy') . '"
					   data-noformat="' . ($lista_ospiti['datanas'] != '0000-00-00' ? date('d-m-Y', strtotime($lista_ospiti['datanas'])) : '') . '" placeholder="Data di Nascita"  readonly>
				</div>
			</div>



				<div uk-grid class="uk-margin-small uk_grid_div div_list_uk">

					<div class="uk-width-expand   lista_grid_nome  uk-text-truncate"> Residenza </div>

					<div class="uk-width-expand uk-text-right lista_grid_right"  style="position:relative">

					 <input class="uk-input input_cli  uk-form-small  uk-form-small   "   data-url="' . base_url() . '/config/searchbox/alloggiati_comune.php"
				       id="residenza"  value="' . $lista_ospiti['residenza'] . '" type="text" autocomplete="off" placeholder="Residenza" >

					</div>
				</div>
			<script>
					$("#residenza").searchBox({
						onclick:function (args){
							 mod_anagrafiche(78,' . $IDcliente . ',args.id,10,()=>{navigation(7,{IDcliente:' . $IDcliente . '});});},autoSelect:1,
					});
			</script>



			<div uk-grid class="uk-margin-small uk_grid_div div_list_uk">

				<div class="uk-width-expand   lista_grid_nome  ">Indirizzo</div>

				<div class="uk-width-expand uk-text-right lista_grid_right" style="position:relative">

					   <input class="uk-input input_cli  uk-form-small " type="text"  value="' . $lista_ospiti['indirizzo'] . '" onchange="mod_anagrafiche(79,' . $IDcliente . ',this,11);" placeholder="Indirizzo">
				</div>

			</div>


			<hr>

			<div uk-grid class="uk-margin-small uk_grid_div div_list_uk">

					<div class="uk-width-expand   lista_grid_nome  uk-text-truncate"> Documento </div>

					<div class="uk-width-expand uk-text-right lista_grid_right"  style="position:relative">

					 <input class="uk-input input_cli  uk-form-small  uk-form-small   "   data-url="' . base_url() . '/config/searchbox/alloggiati_documenti.php"
				       id="documento"  value="' . $lista_ospiti['documento'] . '" type="text" autocomplete="off" placeholder="Documento" >

					</div>
				</div>
			<script>
					$("#documento").searchBox({
						onclick:function (args){
							 mod_anagrafiche(80,' . $IDcliente . ',args.id,10,()=>{navigation(7,{IDcliente:' . $IDcliente . '});});
						 },autoSelect:1,
					});
				</script>




			<div uk-grid class="uk-margin-small uk_grid_div div_list_uk">

				<div class="uk-width-expand   lista_grid_nome  uk-text-truncate">Numero Documento</div>

				<div class="uk-width-expand uk-text-right lista_grid_right">
					   <input class="uk-input input_cli  uk-form-small" type="text" onchange="mod_anagrafiche(81,' . $IDcliente . ',this,11);"  value="' . $lista_ospiti['numero_documento'] . '" placeholder="Numero documento" >
				</div>
			</div>



			<div uk-grid class="uk-margin-small uk_grid_div div_list_uk">

				<div class="uk-width-expand   lista_grid_nome  ">Data di Rilascio</div>

				<div class="uk-width-expand uk-text-right lista_grid_right">
					   <input class="uk-input input_cli  uk-form-small" type="text"  data-testo="Seleziona anno di rilascio" onclick="apri_modal(this,1);"
					   onchange="mod_anagrafiche(82,' . $IDcliente . ',this,11);"   value="' . ($lista_ospiti['data_rilascio'] != '0000-00-00' ? convertidata3($lista_ospiti['data_rilascio'], 'SI') : 'dd/mm/yyyy') . '"
					   data-noformat="' . ($lista_ospiti['data_rilascio'] != '0000-00-00' ? date('d-m-Y', strtotime($lista_ospiti['data_rilascio'])) : '') . '"  placeholder="Data di Rilascio" id="dr" readonly >
				</div>
			</div>





			<div uk-grid class="uk-margin-small uk_grid_div div_list_uk">

					<div class="uk-width-expand   lista_grid_nome  uk-text-truncate"> Luogo di Rilascio </div>

					<div class="uk-width-expand uk-text-right lista_grid_right"  style="position:relative">

					 <input class="uk-input input_cli  uk-form-small  uk-form-small   "   data-url="' . base_url() . '/config/searchbox/alloggiati_comune.php"
				       id="luogo_ril"  value="' . $lista_ospiti['luogoril'] . '" type="text" autocomplete="off" placeholder="Luogo di Rilascio" >

					</div>
				</div>
			<script>
					$("#luogo_ril").searchBox({
						onclick:function (args){
							 mod_anagrafiche(83,' . $IDcliente . ',args.id,10,()=>{navigation(7,{IDcliente:' . $IDcliente . '});});
						 },autoSelect:1,
					});
			</script>



			<button class="button_salva_preventivo" style="margin:10px " onclick="aggiungi_foto(' . $IDcliente . '19,()=>{navigation_ospite(10,{IDcliente:' . $IDcliente . '},0,1);});"><i class="fas fa-file-upload"></i> Aggiungi Documento</button> ';

$lista_foto = estrai_immagini([['IDobj' => $IDcliente, 'tipoobj' => 19]], $IDstruttura);
$foto_txt = '';
//mod_ospite(36,' . $dati_foto['ID'] . ',0,10,()=>{navigation_ospite(10,{IDschedina:' . $IDschedina . '},0,1)});
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
				  	  	 <button  class="button_salva_preventivo" style="font-size:13px;background:#CB0003"  onclick=""><i class="fas fa-trash-alt"></i> ' . traduci('Elimina', $lang) . ' </button>

				    </div>

			</div>  ';
	}
}

$foto_txt .= '<div style="margin:10px 0">' . $foto_txt . '</div><div class="uk-padding uk-margin"></div>';

echo $testo;
?>
