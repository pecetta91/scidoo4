<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../config/connecti.php';
require_once __DIR__ . '/../../config/funzioni.php';
$IDprenotazione = $_SESSION['IDstrpren'];
$IDstruttura = get_IDstruttura_from_IDpren($IDprenotazione);
$lang = $_SESSION['lang'] ?: 0;

$lista_ospiti = estrai_dati_ospiti([['IDprenotazione' => $IDprenotazione]], [], $IDstruttura)['dati'];

$IDcliente = reset($lista_ospiti)['ID'];
$arr_privacy_ospiti = get_privacy_ospiti_collegati($IDcliente, $IDstruttura, 3);

$privacy_content = '';
$stampato = 0;

$privacy_non_accettate = 0;
$lista_privacy = prepara_privacy_ospite($arr_privacy_ospiti);

foreach ($lista_privacy as $IDprivacy => $val) {

	$consenso = $val['consenso'];
	$opzionabile = 0;
	if (isset($val['collegamenti_postazione'][3])) {
		$opzionabile = $val['collegamenti_postazione'][3]['opzionabile'];
	}

	$titolo = traducis($val['titolo'], $IDprivacy, 26, $lang);
	$descrizione = traducis($val['desc'], $IDprivacy, 27, $lang);

	$link_documento = '';

	if (isset($val['documento'])) {
		$link_documento = ' <a href="' . base_url() . '/preventivov2/privacy_policy.php?cod=' . $IDstruttura . ($val['documento'] > 0 ? '&documento=' . $val['documento'] : '') . '" target="_blank" style="cursor:pointer;color:#007bff">  ' . ($val['documento'] > 0 ? traducis('', $val['documento'], 30, $lang) : traduci('Privacy Policy', $lang)) . ' </a>';
	}

	if (($opzionabile == 0) && ($consenso == 0)) {
		$privacy_non_accettate++;
	}

	$privacy_content .= '


	<li style="margin:0">
		 <div class=" uk_grid_div div_list_uk " uk-grid   >
		    <div class="uk-width-auto lista_grid_nome">
		     <input class="apple-switch check_privacy' . ($opzionabile == 0 ? ' obbligatorio ' : '') . '"  ' . ($consenso ? 'checked="checked"' : '') . '   type="checkbox"  onchange="mod_ospite(37,' . $IDcliente . ',' . $IDprivacy . ',10)">
		     ' . ($opzionabile ? '<div style="font-size:11px;color:#6e6e6e" >' . traduci('opzionale', $lang) . '</div>' : '') . '
		    </div>
		    <div class="uk-width-expand   lista_grid_right c000" style="font-size:14px" >' . strip_tags($titolo) . ' <br>
		    <span class="apri_accordion" style="text-decoration:underline;padding:1px 5px;color:#4a4a4a;font-size:12px;cursor:pointer">' . traduci('Visualizza', $lang) . '</span></div>
		</div>

		<div class="uk-accordion-content" style="margin:0">
			' . strip_tags($descrizione) . '
			<div> ' . $link_documento . '</div>
		</div>
   </li>';

}

if ($privacy_non_accettate == 0) {
	$testo = 1;
} else {

	$testo = '
	<div class="nav navbar_picker_flex" >
		 	<div  ><i class="fas fa-times icona_chiudi" ></i></div>
		 	<div style="margin-top:5px;padding-right:10px"> ' . traduci('Autorizzazione Privacy', $lang) . '	</div>
	</div>
	<div class="content" style="margin-top:0 ;    height: calc(100% - 60px);">

		<button class="button_salva_preventivo accetta_tutto" style="margin:10px;background: #2574ec!important; ">' . traduci('Accetta Tutto', $lang) . '</button>


		<ul style="margin:0 ;    padding: 5px 10px;" uk-accordion="multiple: true;toggle:.apri_accordion" >' . $privacy_content . '</ul>


		<div style="    width: 100%;  position: fixed;  bottom: 0;   left: 0;">
				<button style="background: #2574ec!important;    border: none;   width: 100%; padding: 10px;   color: #fff; font-size: 20px; outline: none;     border-radius: 0;  "  class="avanti_privacy"  >' . traduci('Avanti', $lang) . ' </button>
		</div>
	</div> ';

	$testo .= "
	<style>
	.dati-mancanti { border: solid 1px #D0181B !important;}
	</style>
	<script>

	$('.icona_chiudi').unbind('click');
	$('.uk-picker-overlay').unbind('click');
	var check_obbligatori=0;

	$('.icona_chiudi, .uk-picker-overlay , .avanti_privacy').on('click',function(){
		$('.check_privacy.obbligatorio').each(function(){
			if($(this).is(':checked')){
				check_obbligatori=0;
				$(this).removeClass('dati-mancanti');
			}else{
				check_obbligatori=1;
				return false;
			}
		});

		if(check_obbligatori==1){
			 $('.check_privacy.obbligatorio:not(:checked)').addClass('dati-mancanti');
			  apri_notifica({'messaggio':'" . traduci('Per poter procedere alla navigazione è necessario accettare le regole privacy', $lang) . "','status':'danger','time':4000});
		}else{
			close_all_picker();
		}
	});

	$('.accetta_tutto').on('click',function(){
		$('.check_privacy:not(:checked)').prop('checked', true).change();
		close_all_picker();
	});
</script>";
}

echo $testo;
?>