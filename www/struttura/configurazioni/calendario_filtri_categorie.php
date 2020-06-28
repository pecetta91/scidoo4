<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';
$IDstruttura = $_SESSION['IDstruttura'];

$categorie_sel = (isset($_SESSION['categorie_calendario']) ? $_SESSION['categorie_calendario'] : []);
//onclick="nascondi_riga_calendario('.$IDcategoria.');"
$categorie = get_categorie($IDstruttura);
$testo_categorie = '';
if (!empty($categorie)) {
	foreach ($categorie as $IDcategoria => $dati) {
		$testo_categorie .= '

		<li> <input  style="width:17px;height:17px;" class="uk-radio" name="categorie" type="checkbox" value="' . $IDcategoria . '"  ' . (in_array($IDcategoria, $categorie_sel) ? 'checked="checked"' : '') . '> ' . $dati['categoria'] . '  </li> ';
	}
}

$testo = '

<div class="nav navbar_picker_flex" >
 	<div onclick="chiudi_picker()"><i class="fas fa-times icona_chiudi" ></i></div>
 	<div style="margin-top:5px;margin-right:10px;"></div>
</div>


<div class="content scroll_chat_auto config" style="height:75vh">


<div id="filtro_categorie" style="display:none;">
<ul class="uk-list uk-list-divider uk-picker-bot" style="padding:5px 20px;"  >' . $testo_categorie . '</ul></div>

<div class="div_list_uk uk_grid_div " uk-grid   onclick="carica_content_picker(' . "'filtro_categorie'" . ')" >
    <div class="uk-width-1-3 lista_grid_nome uk-first-column">Categoria</div>
    <div class="uk-width-expand uk-text-right lista_grid_right">  <span uk-icon="chevron-right" class="uk-icon"></span></div>
</div>



</div> ';

echo $testo;
