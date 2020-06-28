<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';
$IDutente = $_SESSION['ID'];
$IDstruttura = $_SESSION['IDstruttura'];

$cerca_servizio = (isset($_POST['cerca_servizio']) ? $link2->real_escape_string($_POST['cerca_servizio'] ?? '') : '');

$tipologie = $_SESSION['filtro_tipologie'];

$div_servizi = '';
if ($cerca_servizio == '') {

	$filtro = ['tipolim' => ['escludi' => [4, 5]], 'tipologia' => ['includi' => $tipologie]];
	$albero = albero_servizi($IDstruttura, $filtro);

	foreach ($albero as $id_tipo => &$tipo) {
		$sottotipologie_html = '';
		foreach ($tipo['sottotipologie'] as $id_sottotipologia => &$sottotipologia) {
			$servizi_html = '';
			foreach ($sottotipologia['servizi'] as $id_servizio => $info_servizio) {
				//servizio
				$checked = ($array_servizi[0][$id_servizio] ?? false) ? 'checked' : '';
				$servizi_html .= '<div  class="servizio premi_servizio" data-id="' . $info_servizio['ID'] . '" data-type="0">' . $info_servizio['nome'] . '</div>';
			}
			//contentiore sottotipologia
			$checked = ($array_servizi[1][$id_sottotipologia] ?? false) ? 'checked' : '';
			$sottotipologie_html .= '
					<ul uk-accordion="multiple: true" class="accordion_servizi" style="padding-left:5px">
					    <li data-id="' . $id_sottotipologia . '" data-type="1" class="' . ($tipologie ? 'uk-open' : '') . '">
					        <a class="nome_servizio uk-accordion-title" href="#">' . $sottotipologia['nome'] . '</a>
					        <div class="uk-accordion-content">  ' . $servizi_html . '  </div>
					    </li>
					</ul> ';
		}

		$div_servizi .= '
			<ul uk-accordion="multiple: true" class="accordion_servizi">
			    <li  data-id="' . $id_tipo . '" data-type="2" class="' . ($tipologie ? 'uk-open' : '') . '">
			        <a class="nome_servizio uk-accordion-title" > ' . $tipo['nome'] . '</a>
			        <div class="uk-accordion-content">  ' . $sottotipologie_html . '  </div>
			    </li>
			</ul>';
	}

} else {

	$ricerca = '%' . $cerca_servizio . '%';
	$lista_ID = [];
	$query = "SELECT ID FROM servizi WHERE IDstruttura='$IDstruttura' AND  servizio LIKE '$ricerca'  ";
	$result = mysqli_query($link2, $query);
	$row = mysqli_fetch_all($result);
	$lista_ID = array_column($row, 0);

	$tipolim_specifici = [];
	if ($tipologie) {
		$tipolim_specifici['tipolim'] = $tipologie;
	}

	$lista_servizi = get_elenco_servizi([['IDservizio' => (!empty($lista_ID) ? $lista_ID : 0), 'no_tipolim' => [4, 5], $tipolim_specifici]], $IDstruttura);

	$lista_servizi_txt = '';
	foreach ($lista_servizi as $dati) {
		$lista_servizi_txt .= ' <li   data-id="' . $dati['ID'] . '"  class="premi_servizio"  >' . $dati['servizio'] . '</li>';
	}
	$div_servizi = '<ul class="uk-list uk-list-divider uk-picker-bot" >' . $lista_servizi_txt . '</ul>';
}

echo '<div style="padding:5px 10px;">' . $div_servizi . '</div>';
?>

<script >$('.premi_servizio').on('click',function(){
	var IDservizio=$(this).data('id');
	scegli_servizio_da_aggiungere(IDservizio);
});</script>
