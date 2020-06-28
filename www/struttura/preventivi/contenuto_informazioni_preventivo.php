<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';
require_once __DIR__ . '/../../../config/preventivatore/funzioni/preventivatore.php';

$IDutente = $_SESSION['ID'];
$IDstruttura = $_SESSION['IDstruttura'];
$IDpreventivo = $_POST['IDpreventivo'];

$base_url = base_url();

$tab_scelta = $_POST['tab'] ?? 1;

$dati_preventivo = get_preventivi(['0' => ['ID' => $IDpreventivo]], $IDstruttura)[$IDpreventivo];

$inviato_da = $dati_preventivo['inviato_da'];
$scadenza = $dati_preventivo['scadenza'] ?? '';

$testo = '';
switch ($tab_scelta) {

case 1:

	$lista_strutture = genera_sotto_strutture_lista($IDstruttura);

	$select_inviato_da = '<div id="select_inviato_da" style="display:none;" data-titolo="Inviato da">
				<ul class="uk-list uk-list-divider uk-picker-bot " style="padding:5px 20px;">
					' . genera_strutture_uikit($IDstruttura, $inviato_da, ['tipo' => '16', 'ID' => $IDpreventivo, 'agg' => 'contenuto_informazioni_preventivo(1)']) . '
				</ul>
			</div>';

	$testo .= $select_inviato_da . '<div class="div_list_uk uk_grid_div  " uk-grid onclick="carica_content_picker(' . "'select_inviato_da'" . ')">
			    <div class="uk-width-1-3 lista_grid_nome uk-first-column">Struttura</div>
			    <div class="uk-width-expand uk-text-right lista_grid_right"> ' . $lista_strutture[$inviato_da]['nome'] . ' <span uk-icon="chevron-right" class="uk-icon"></span></div>
			</div>';

//mod_impostazioni_prev(7,' . $IDpreventivo . ',s,10);
	$data_scadenza = ($scadenza ? date('d/m/Y', $scadenza) : '');

	$data_scadenza = ($scadenza ? convertidata3($data_scadenza, 'SI') : '');

	$data_scadenza_noformat = ($scadenza ? convertiData($data_scadenza, 'NO') : '');

	$testo .= '<div class="div_list_uk uk_grid_div  " uk-grid >
			    <div class="uk-width-1-3 lista_grid_nome uk-first-column">Scadenza

			    ' . ($scadenza ? '<button onclick="set_scadenza_preventivo(0);" class="button_grey_uk">Elimina</button>' : '') . '
			    </div>


			    <div class="uk-width-expand uk-text-right lista_grid_right">

			      <input class="uk-input input_cli  uk-form-small uk-text-right" id="preventivo_partenza" style="border: 1px solid#e1e1e1;  border-radius: 3px;"
			      type="text" data-testo="Scadenza" onclick="apri_modal(this,1);" onchange="set_scadenza_preventivo();"   value="' . $data_scadenza . '"  data-noformat="' . $data_scadenza_noformat . '"  readonly> </div>


			</div>';

	break;

case 2:

	$foto_escluse = [];
	$query = "SELECT IDfoto FROM preventivo_foto_escluse WHERE IDpreventivo='$IDpreventivo' AND IDstruttura='$IDstruttura' AND IDsotto_struttura='$inviato_da' ";
	$result = mysqli_query($link2, $query);
	while ($row = mysqli_fetch_row($result)) {
		$IDfoto_prev = $row['0'];
		$foto_escluse[$IDfoto_prev] = [];
	}

	if ($inviato_da == 0) {
		$IDobj = $IDstruttura;
		$tipoobj = '11';
	} else {
		$IDobj = $inviato_da;
		$tipoobj = '15';
	}

	$lista_foto = estrai_immagini(['0' => ['IDobj' => $IDobj, 'tipoobj' => $tipoobj]]);

	$foto_video_txt = '';

	foreach ($lista_foto as $dati) {
		$IDfoto = $dati['ID'];
		$sfondo = '';
		$icona = '';
		switch ($dati['elemento']) {
		case 'immagine':
			$sfondo = ' style="background:url(' . $base_url . '/immagini/' . $dati['foto'] . '?) no-repeat center center;background-size:cover" ';
			break;
		default:
			$icona = '<div style=" text-align: center;    margin-top: 28px;">	<i style="font-size:50px" class="' . $dati['icona'] . '"></i> </div>';
			break;
		}

		$foto_video_txt .= '
			<div  class="foto_preventivatore_strutture" ' . $sfondo . '>
				<div style="position:absolute;right:0">
				<input type="checkbox" ' . (!isset($foto_escluse[$IDfoto]) ? 'checked="checked"' : '') . ' onChange="mod_impostazioni_prev(3,' . "'" . $IDpreventivo . "_" . $inviato_da . "'" . ',' . $IDfoto . ',10)" style="height:20px;width:20px;"></div>
				' . $icona . '
			</div>';
	}

	$testo .= '<div style="padding:0px 10px;margin-top:10px;">' . $foto_video_txt . '</div>';

	break;

case 3:

	$elenco_richieste = [];
	$elenco_richieste[0]['nome'] = 'All';
	$numero = 1;
	$query = "SELECT ID FROM richieste WHERE IDpreventivo='$IDpreventivo' ORDER BY ID";
	$result = mysqli_query($link2, $query);
	while ($row = mysqli_fetch_row($result)) {
		$IDproposta = $row['0'];
		$elenco_richieste[$IDproposta]['nome'] = 'Prop ' . $numero;
		$numero++;

	}

	$richieste_presenti = [];
	$query = "SELECT ID,IDrichiesta,descrizione FROM note_richieste WHERE IDpreventivo='$IDpreventivo' ";
	$result = mysqli_query($link2, $query);
	while ($row = mysqli_fetch_row($result)) {
		$ID = $row['0'];
		$IDrichiesta = $row['1'];
		if (isset($elenco_richieste[$IDrichiesta])) {
			$elenco_richieste[$IDrichiesta]['descrizione'] = $row['2'];
		}

	}

	$lista_note_txt = '';
	$descrizione_note_txt = '';
	$note_preventivo = get_note_collegate(['IDstruttura' => $IDstruttura, 'IDobj' => $IDpreventivo, 'tipoobj' => 1]);
	if (!empty($note_preventivo)) {
		foreach ($note_preventivo as $tipo_nota => $val) {

			$lista_note_txt .= '<li class="pulsante_div_note_preventivo"   alt="' . $tipo_nota . '" onclick=""><a href="#">' . $val['titolo_nota'] . ' ' . (!empty($val['descrizione']) ? '<div class="notifica_proposta_preventivo"></div>' : '') . '</a></li>';

			$descrizione_note_txt .= '
			<li class="textarea_div_note_preventivo">
			<div>
				<textarea class="uk-textarea" alt="' . $tipo_nota . '" placeholder="' . $val['titolo_nota'] . '"  onchange="modprenot(' . "'" . $IDpreventivo . "_" . $tipo_nota . "_1'" . ',this,243,11)"> ' . (!empty($val['descrizione']) ? $val['descrizione'] : '') . '</textarea>
			</div></li>';
		}
	}

	$lista_richieste_txt = '';
	$descrizioni_richieste_txt = '';
	foreach ($elenco_richieste as $IDproposta => $val) {
		$lista_richieste_txt .= '<li class="pulsante_div_note_preventivo" alt="' . $IDproposta . '"><a href="#">' . $val['nome'] . ' ' . (!empty($val['descrizione']) ? '<div class="notifica_proposta_preventivo"></div>' : '') . '</a></li>';

		$descrizioni_richieste_txt .= '
		<li class="textarea_div_note_preventivo">
		<div >
			<textarea class="uk-textarea" placeholder="' . $val['nome'] . '" alt="' . $IDproposta . '"
			 onchange="mod_impostazioni_prev(6,' . "'" . $IDpreventivo . "_" . $IDproposta . "'" . ',this,11);"> ' . (!empty($val['descrizione']) ? $val['descrizione'] : '') . '</textarea>

		</div></li>';
	}

	$testo .= '


 	<div style="margin-top:5px;">

 	<table style="width:100%;padding:5px">
 		<tr>
 			<td style="width:20%;vertical-align:top">
 			<ul  uk-tab="connect:#tab_richieste;animation: uk-animation-fade;swiping:false" style="display:block;margin:0" class="no_before">' . $lista_note_txt . '   ' . $lista_richieste_txt . '</ul>
		</td>

 			<td style="width:80%;vertical-align:top;padding-left:10px"><ul class="uk-switcher uk-margin" id="tab_richieste" >' . $descrizione_note_txt . ' ' . $descrizioni_richieste_txt . '</ul></td>

 		</tr>
 	</table>
	</div>';

	break;
case 4:

	$arr_info = get_informazioni_struttura_preventivo($IDstruttura, $IDpreventivo);
	$txt_informazioni = '';
	if (!empty($arr_info)) {
		foreach ($arr_info as $dati => $val) {

			$IDinfo = $val['ID'];
			$titolo = $val['titolo'];
			$txt_informazioni .= '
				<li> <input style="width:17px;height:17px;" type="checkbox" ' . ($val['esclusa'] ? '' : 'checked="checked"') . ' id="check' . $IDinfo . '"
					onChange="mod_impostazioni_prev(5,' . "'" . $IDpreventivo . "_" . $IDinfo . "'" . ',this,13)" > <div style=" display: inline-block; font-size:14px; vertical-align: text-top;">' . $titolo . '</div></li>  ';
		}
	}

	$testo = '<ul class="uk-list" style="padding:5px 10px;">' . $txt_informazioni . '</ul>';

	break;

}

echo $testo;
?>


