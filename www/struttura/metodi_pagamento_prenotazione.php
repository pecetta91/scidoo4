<?php
//header('Access-Control-Allow-Origin: *');
require_once '../../config/connecti.php';
require_once '../../config/funzioni.php';

$IDutente = $_SESSION['ID'];
$IDstruttura = $_SESSION['IDstruttura'];

$arr = implode(',', $_POST['arr_dati']);
list($IDtipo, $tipo, $metodo_inserimento, $totale) = explode(',', $arr);

$testo = '<div class="div_uk_divider_list" style="margin-top:0px !important;">Dettagli</div>';

switch ($tipo) {
case 0: //visualizzo
	$query = "SELECT timepag,metodopag,IDpers,totale FROM scontrini WHERE IDstr='$IDstruttura' AND ID='$IDtipo' LIMIT 1";
	$result = mysqli_query($link2, $query);
	$row = mysqli_fetch_row($result);
	$timepag = $row['0'];
	$metodopag = $row['1'];
	$IDpers = $row['2'];
	$totale = $row['3'];

	//persone
	$query2 = "SELECT nome FROM personale WHERE ID='$IDpers' LIMIT 1";
	$result2 = mysqli_query($link2, $query2);
	$row2 = mysqli_fetch_row($result2);
	$nomepers = $row2['0'];

	//tipopagamento
	$query3 = "SELECT ID,pagamento FROM tipopag WHERE ID='$metodopag' LIMIT 1";
	$result3 = mysqli_query($link2, $query3);
	$row3 = mysqli_fetch_row($result3);
	$pagamento = $row3['1'];

	$query4 = "SELECT tipoobj,IDobj FROM scontriniobj WHERE IDscontr='$IDtipo' AND tipoobj IN(1,2,14) LIMIT 1";
	$result4 = mysqli_query($link2, $query4);
	$row4 = mysqli_fetch_row($result4);
	$metodoins = $row4['0'];

	$data_noformat = date('d-m-Y', $timepag);
	$data = date('Y/m/d', $timepag);

	$testo .= '
			 <div class=" uk_grid_div div_list_uk" uk-grid   >
			    <div class="uk-width-2-3 lista_grid_nome">Data </div>
			    <div class="uk-width-expand uk-text-right lista_grid_right" >
			     <input class="uk-input input_cli  uk-form-small" type="text" data-testo="Seleziona anno di nascita" onclick="apri_modal(this,1);" onchange="console.log(this)" value="' . $data . '"  data-noformat="' . $data_noformat . '" placeholder="Nuova date Prenotazione"  readonly>
			       </div>
			</div>


		    <div class=" uk_grid_div div_list_uk" uk-grid  >
			    <div class="uk-width-2-3 lista_grid_nome">Registrato Da</div>
			    <div class="uk-width-expand uk-text-right lista_grid_right" >  ' . $nomepers . '  </div>
			</div>

			<div class=" uk_grid_div div_list_uk" uk-grid  >
			    <div class="uk-width-2-3 lista_grid_nome">Valore</div>
			    <div class="uk-width-expand uk-text-right lista_grid_right" > ' . $totale . ' €</div>
			</div>

			<div class=" uk_grid_div div_list_uk" uk-grid  >
			    <div class="uk-width-2-3 lista_grid_nome">Pagato con</div>
			    <div class="uk-width-expand uk-text-right lista_grid_right" > ' . $pagamento . ' </div>
			</div> ';

	break;

case 1: //modifico

	$testo .= '
			 <div class=" uk_grid_div div_list_uk" uk-grid   >
			    <div class="uk-width-2-3 lista_grid_nome">Data </div>
			    <div class="uk-width-expand uk-text-right lista_grid_right" >
			     <input class="uk-input input_cli  uk-form-small" type="text" data-testo="Seleziona anno di nascita" onclick="apri_modal(this,1);" onchange="console.log(this)" value="' . $data . '"  data-noformat="' . $data_noformat . '" placeholder="Nuova date Prenotazione"  readonly>
			       </div>
			</div>


		    <div class=" uk_grid_div div_list_uk" uk-grid  >
			    <div class="uk-width-2-3 lista_grid_nome">Registrato Da</div>
			    <div class="uk-width-expand uk-text-right lista_grid_right" >  ' . $nomepers . '  </div>
			</div>

			<div class=" uk_grid_div div_list_uk" uk-grid  >
			    <div class="uk-width-2-3 lista_grid_nome">Valore</div>
			    <div class="uk-width-expand uk-text-right lista_grid_right" > ' . $totale . ' €</div>
			</div>

			<div class=" uk_grid_div div_list_uk" uk-grid  >
			    <div class="uk-width-2-3 lista_grid_nome">Pagato con</div>
			    <div class="uk-width-expand uk-text-right lista_grid_right" > ' . $pagamento . ' </div>
			</div> ';

	break;
}

echo $testo;
