<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';
$IDstruttura = $_SESSION['IDstruttura'];
$IDutente = $_SESSION['ID'];

$lista_configurazioni = get_configurazioni($IDstruttura, 11, 0, $IDutente);

$dati_aggiuntivi['25'] = genera_numeri_array(10, 60, 1, 'px');
$dati_aggiuntivi['27'] = genera_numeri_array(40, 60, 1, 'px');
$elenco_configurazioni = stampa_configurazioni_campi($IDstruttura, $lista_configurazioni, $IDutente, $dati_aggiuntivi);

$lista_configurazioni_txt = '';
if (!empty($elenco_configurazioni)) {
	foreach ($elenco_configurazioni as $val) {
		$lista_configurazioni_txt .= '
		<div class="div_list_uk uk_grid_div uk-grid" uk-grid="">
				    <div class="uk-width-expand lista_grid_nome uk-first-column" style="font-size:14px" >' . $val['proprieta'] . ' </div>
				 <div class="uk-width-auto uk-text-right lista_grid_right">    ' . $val['campo'] . ' </div>
		</div> ';
	}
}

$testo = '

<div class="nav navbar_picker_flex" >
 	<div onclick="chiudi_picker()"><i class="fas fa-times icona_chiudi" ></i></div>
 	<div style="margin-top:5px;margin-right:10px;"></div>
</div>


<div class="content scroll_chat_auto config" style="height:75vh">



	' . $lista_configurazioni_txt . '



</div> ';

echo $testo;

?>

<style>
	.lista_grid_right select{width: 100%;}
</style>

<script>
$('.config input[type="checkbox"]').addClass('apple-switch');
</script>
