<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../../config/connecti.php';
require_once __DIR__ . '/../../../config/funzioni.php';
$IDprenotazione = $_SESSION['IDstrpren'];
$IDstruttura = get_IDstruttura_from_IDpren($IDprenotazione);

$IDmenu = $_POST['menu'];
$parent = $_POST['parent'];
$riga = $_POST['riga'];
$lang = $_SESSION['lang'] ?: 0;

/*
$dati_prenotazione = get_prenotazioni(['0' => ['IDprenotazione' => $IDprenotazione]], null, [], $IDstruttura);
$dettaglio_prenotazione = $dati_prenotazione['dati'][$IDprenotazione];*/

$IDsottotip = get_info_from_IDserv($IDmenu, 'IDsottotip', $IDstruttura);

$query = [];

/*
$query_array[] = "SELECT s.ID,s.servizio,s.prezzo
FROM limiticatportate AS l
JOIN servizi AS s ON (s.IDsottotip=l.IDobj)
WHERE l.IDstr='$IDstruttura' AND l.IDserv='$IDmenu' AND l.riga='$riga' AND l.tipoobj=1";
 */
$query_array[] = "SELECT s.ID,s.servizio,s.prezzo
FROM limiticatportate AS l
LEFT JOIN servizi AS s ON (l.IDobj=s.ID)
WHERE l.IDstr='$IDstruttura' AND l.IDserv='$IDmenu' AND l.riga='$riga' AND l.tipoobj=2";

$prodotti = [];
foreach ($query_array as $query) {
	$result = mysqli_query($link2, $query);
	while ($row = $result->fetch_row()) {
		$prodotti[$row[0]] = $row;
	}
}

$lista_servizi = array_keys($prodotti);

$lista_ingredienti = get_ingredienti_servizio($lista_servizi, $IDstruttura);

$riga_prodotti = '';
foreach ($prodotti as $prodotto) {

	$lista_tag = get_lista_tag($prodotto[0], 3, $IDstruttura);
	$allergeni = [];
	if (!empty($lista_tag)) {
		foreach ($lista_tag as $tag) {
			if ($tag['IDtag_categoria'] != 12) {continue;}
			$nome_tag = traducis($tag['nome'], $tag['ID'], 25, $lang);
			$allergeni[] = $nome_tag;

		}
	}

	$ingredienti = [];
	if (!empty($lista_ingredienti[$prodotto[0]])) {
		foreach ($lista_ingredienti[$prodotto[0]] as $dati) {
			$ingredienti[] = traducis('', $dati['IDingrediente'], 1, $lang);
		}
	}

	$foto = getfoto($prodotto[0], 4);

	$riga_prodotti .= '
	<div class=" uk_grid_div div_list_uk" uk-grid   onclick="pulsanti_piatti_portate(' . $prodotto[0] . ')">

	' . ($foto != 'camera.jpg' ? '
		<div class="uk-width-1-4">
			<div style="height:60px;background-size:cover;background-image:url(' . base_url() . '/immagini/big' . $foto . ');background-repeat:no-repeat;background-position:center;"></div>
		</div>

		' : '') . '

		    <div class="uk-width-expand lista_grid_nome  " style="padding-left: 10px; margin: 2px;">
		  	  <div class="uk-text-truncate">  ' . strtolower(traducis('', $prodotto[0], 1, $lang)) . '</div>
			    ' . (!empty($ingredienti) ? '   <div style="font-size:11px">' . traduci('Ingredienti', $lang) . ' : ' . implode(', ', $ingredienti) . '</div>' : '') . '
			   ' . (!empty($allergeni) ? '   <div style="font-size:11px">' . traduci('Allergeni', $lang) . ' : ' . implode(', ', $allergeni) . '</div>' : '') . '
		    </div>
		    <div class="uk-width-auto uk-text-right lista_grid_right" >  <i class="fas fa-chevron-right"></i>   </div>
		</div> ';
}
$elenco_servizi = '';
$servizi = [];

$testo = '
<div class="nav navbar_picker_flex" >
 	<div onclick="chiudi_picker()"><i class="fas fa-times icona_chiudi" ></i></div>
 	<div style="margin-top:5px;padding-right:10px">  ' . traduci('Elenco Piatti', $lang) . '

 	</div>
</div>

<input type="hidden" value="' . $IDmenu . '" id="IDmenu_servizio">
<input type="hidden" value="' . $parent . '" id="parent">


<div class="content" style="margin-top:0;height: calc(100% - 50px);">
	<div  style="padding-top:5px;">
			' . $riga_prodotti . '
	</div>
</div>

';

echo $testo;

?>



<script>

function pulsanti_piatti_portate(IDservizio){
    var btn='';


	btn+='<li onclick="chiudi_picker();visualizza_informazioni_servizio_menu('+IDservizio+ ')"> Informazioni </li> ';
	btn+='<li onclick="chiudi_picker();collega_piatto_menu('+IDservizio+ ');"  > Seleziona </li> ';


	  picker_modal_action(btn);
}


function visualizza_informazioni_servizio_menu(IDservizio){
    $.ajax({
        url: baseurl+'app_uikit/profilocli/menu/informazioni_servizio_menu.php',
        method: 'POST',
        dataType: 'text',
        cache: false,
        timeout: 5000,
        data: { IDservizio:IDservizio},
        error: function(html) {
           loader();
        },

        success: function(html) {
           loader();
   	      var IDpicker=crea_picker(()=>{},{'height':'85%'});
           $('#'+IDpicker+'.stampa_contenuto_picker').html(html);
        }
      });
}

</script>