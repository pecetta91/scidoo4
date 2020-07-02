<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/' . '../../../config/connecti.php';
require_once DIR_CONFIG . '/' . 'funzioni.php';
require_once DIR_CONFIG . '/' . 'ristorante/funzioni_ristorante.php';
$IDstruttura = $IDstruttura ?? $_SESSION['IDstruttura'];

array_escape($_REQUEST);
$arr_dati = $_REQUEST['arr_dati'];
$IDtavolo = $arr_dati['tavolo'] ?? null;
$IDprenotazione = $arr_dati['pren'] ?? null;
$IDsottotip = $arr_dati['sottotip'] ?? 0;
$numero_tavolo = $arr_dati['num_tavolo'] ?? null;
$portata_selezionata = $arr_dati['portata'] ?? null;
$refresh = $arr_dati['refresh'] ?? null;

$stampa_portata = function ($ordinazione, $prodotti) {
	// $info['unita']
	$out = '';
	foreach ($prodotti as $IDprodotto) {
		$info = $ordinazione['prodotti'][$IDprodotto];
		$nome = $info['IDservizio'] ? $info['servizio'] : 'SELEZIONA PIATTO';
		$out .= '<div class="risto-ordinazione-prodotto" data-id="' . $info['ID'] . '" data-serv="' . $info['IDservizio'] . '" data-tipo="' . $info['tipo'] . '" data-menu="' . $info['IDmenu'] . '" data-riga="' . $info['riga'] . '">
		<span class="prezzo" style="width: 50px;">' . float_format($info['prezzo']) . '€</span>
		<span class="nome" style="flex: 1;">' . $nome . '</span>
		<span class="qta" style="width: 40px;" data-qta="' . $info['qta'] . '">' . $info['qta'] . '</span>';
		foreach ($info['variazioni'] ?? [] as $variazione) {
			$tipo = $variazione['tipo'] == TipoProdotto::variazione_positiva ? '+' : '-';
			$out .= '<div>' . $tipo . $variazione['servizio'] . '</div>';
		}
		$out .= '</div>';
	}
	return $out;
};

if (!$IDtavolo and $IDprenotazione and $numero_tavolo) {
	$query = "SELECT IDsala FROM sotto_sale WHERE ID='$numero_tavolo'";
	$result = $link2->query($query);
	$row = $result->fetch_row();
	$IDsala = $row[0];

	$query = "SELECT t.ID,t.num,SUM(CASE WHEN p.pagamento=0 AND p.addebito=0 THEN 1 ELSE 0 END), COUNT(p.ID)
	FROM tavoli AS t
	JOIN tavolipren AS tp ON tp.IDtav=t.ID
	LEFT JOIN prodottiport AS p on p.IDtavolo=t.ID
	WHERE t.IDstr='$IDstruttura' AND t.IDsottotip='$IDsottotip' AND tp.IDpren='$IDprenotazione' AND t.num='$numero_tavolo'
	GROUP BY t.ID ORDER BY t.ID DESC LIMIT 1";
	$result = $link2->query($query);
	$nuovo = false;
	//se esiste un tavolo vuoto o contentente prodotti non addebitati, lo apro
	//sltrimenti ne creo uno nuovo
	if ($result->num_rows) {
		$row = $result->fetch_row();
		$prodotti_aperti = $row[2];
		$prodotti_totali = $row[3];
		if ($prodotti_aperti == 0 and $prodotti_totali > 0) {
			$nuovo = true;
		} else {
			$IDtavolo = $row[0];
			$numero_tavolo = $row[1];
		}
	} else {
		$nuovo = true;
	}
	if ($nuovo) {
		$coperti = null;
		$IDtavolo = risto_nuovo_tavolo($IDstruttura, $numero_tavolo, $IDsottotip, $IDsala, $IDprenotazione, 1, $coperti);
	}
}

$ordinazione = ristorante_get_ordinazione($IDstruttura, $IDtavolo);
uasort($ordinazione['prodotti'], function ($a, $b) {
	return ($a['portata'] - $b['portata']) ?: ($a['ID'] - $b['ID']);
});
$portata = $portata_selezionata ?? 1; //(count($ordinazione['prodotti']) ? max(array_column($ordinazione['prodotti'], 'portata')) : 1);
$pagina_html = '';
$pagina_html .= '<div id="ristorante-ordinazione" style="display: flex; flex-direction: column; height: calc(100vh - 50px);">';
$pagina_html .= sprintf('<div style="display:none;" id="ristorante-info-ordinazione" data-tavolo="%s" data-portata="%s" data-prenotazione="%s" data-sottotip="%s" data-numero="%s"></div>', $IDtavolo, $portata, $IDprenotazione, $IDsottotip, $numero_tavolo);

//html da copiare nel picker
$azioni_piatto = '<div id="ristorante-opzioni-piatto-template" style="display: none;">
<div class="nome" style="background: #2574ec; color: #fff; font-size: 16px; text-align: center;"></div>
<ul class="uk-list uk-list-divider uk-picker-bot ristorante-opzioni-piatto">
	<li class="qta" style="display: flex;"><div style="flex: 1;">Quantità</div>
		<div class="stepper stepperrestr" style="padding-right:15px;">
			<div class="stepper-button-minus" uk-icon="minus"></div>
			<div class="stepper-value" min="1" max="100"></div>
			<div class="stepper-button-plus" uk-icon="plus"></div>
		</div>
	</li>
	<li class="qta" style="display: flex;"><div style="flex: 1;">Portata</div>
		<div class="stepper stepperrestr" style="padding-right:15px;">
			<div class="stepper-button-minus" uk-icon="arrow-down"></div>
			<div class="stepper-value" min="0" max="10"></div>
			<div class="stepper-button-plus" uk-icon="arrow-up"></div>
		</div>
	</li>
	<li class="clickable" data-action="varia">Variazione</li>
	<li class="clickable" data-action="elimina" style="color: red;">Elimina</li>
</ul>
</div>';

$pagina_html .= $azioni_piatto;

$portata = null;
$index = 3;
$prodotti_portate = array_fill_keys(range(0, 9), []);
foreach ($ordinazione['prodotti'] as $IDprodotto => $info) {
	if ($portata != $info['portata']) {
		if ($info['portata'] > 0 and $portata === null) {$index = $info['portata'] + 2;}
		$portata = $info['portata'];
	}
	$prodotti_portate[$portata][] = $IDprodotto;
}
if ($portata_selezionata !== null) {
	$index = $portata_selezionata + 2;
}

$menu_bar = '<ul id="ordinazione-selettore-portate" uk-tab="connect: #ristorante-elenco-prodotti; animation: uk-animation-fade;swiping:false; active: ' . $index . ';" class="uk-tab uk-tab-ordinazione">
		<li class="' . (count($ordinazione['menu']) ? 'notice' : '') . '"><a href="#">Menu</a></li>
		<li class="static" style="pointer-events: none;"><a href="#">Portate:</a></li>
		<li class="' . (count($prodotti_portate[0]) ? 'notice' : '') . '" style="width: 50px;" data-index="0"><a href="#">0</a></li>
		<li class="' . (count($prodotti_portate[1]) ? 'notice' : '') . '" style="width: 50px;" data-index="1"><a href="#">1</a></li>
		<li class="' . (count($prodotti_portate[2]) ? 'notice' : '') . '" style="width: 50px;" data-index="2"><a href="#">2</a></li>
		<li class="' . (count($prodotti_portate[3]) ? 'notice' : '') . '" style="width: 50px;" data-index="3"><a href="#">3</a></li>
		<li class="' . (count($prodotti_portate[4]) ? 'notice' : '') . '" style="width: 50px;" data-index="4"><a href="#">4</a></li>
		<li class="' . (count($prodotti_portate[5]) ? 'notice' : '') . '" style="width: 50px;" data-index="5"><a href="#">5</a></li>
		<li class="' . (count($prodotti_portate[6]) ? 'notice' : '') . '" style="width: 50px;" data-index="6"><a href="#">6</a></li>
		<li class="' . (count($prodotti_portate[7]) ? 'notice' : '') . '" style="width: 50px;" data-index="7"><a href="#">7</a></li>
		<li class="' . (count($prodotti_portate[8]) ? 'notice' : '') . '" style="width: 50px;" data-index="8"><a href="#">8</a></li>
		<li class="' . (count($prodotti_portate[9]) ? 'notice' : '') . '" style="width: 50px;" data-index="9"><a href="#">9</a></li>
	</ul>';

$pagina_html .= $menu_bar;
$pagina_html .= '<div id="ristorante-elenco-prodotti" style="flex: 1; overflow-y: auto;">
<div class="ordinazione-portata">';
foreach ($ordinazione['menu'] as $info) {
	$pagina_html .= '<div class="risto-ordinazione-prodotto">' . $info['servizio'] . '</div>';
}
$pagina_html .= '</div>
<div class="ordinazione-portata">';
$pagina_html .= '</div>';

foreach ($prodotti_portate as $portata => $prodotti) {
	$pagina_html .= '<div class="ordinazione-portata" data-portata="' . $portata . '">';
	//funzione definita a inizio file
	$pagina_html .= $stampa_portata($ordinazione, $prodotti);
	$pagina_html .= '</div>';
}
$pagina_html .= '</div>';
$pagina_html .= '<div id="ristorante-selezione-prodotti" class="risto-selezione" style="height: 45%; overflow: hidden; border-top: 1px solid #888;">
	<div class="pannello piatti" style="display: flex;">
		<div id="ristorante-selezione-sottotip" class="risto-container-selezione" style="overflow-y: auto; flex: 1; display: flex; flex-direction: column; border-right: 1px solid #888;"></div>
		<div id="ristorante-selezione-piatti" class="risto-container-selezione" style="overflow-y: auto; flex: 2; display: grid; grid-template-columns: 1fr 1fr; align-content: start;"></div>
	</div>
	<div class="pannello variazioni" style="display: none;">
		<div id="ordinazione-modo-variazione" class="ordinazione-select-mode">
			<div data-value="0" class="selected"><i class="fas fa-minus"></i></div>
			<div data-value="1"><i class="fas fa-plus"></i></div>
		</div>
		<div id="ristorante-selezione-variazioni" class="risto-container-selezione" style="overflow-y: auto; flex: 1; display: grid; grid-template-columns: 1fr 1fr 1fr; align-content: start;"></div>
	</div>
	<div class="pannello scelta-menu" style="display: none;">
		<div id="ristorante-selezione-piatti-menu" class="risto-container-selezione" style="overflow-y: auto; flex: 1; display: grid; grid-template-columns: 1fr 1fr 1fr; align-content: start;"></div>
	</div>
	<div class="pannello impostazioni" style="display: flex;">

	</div>
</div>';
$pagina_html .= '</div>';
if ($refresh) {
	echo $stampa_portata($ordinazione, $prodotti_portate[$portata_selezionata] ?? []);
} else {
	echo $pagina_html;
}
?>
<script>
(() => {
function abilita_stepper(container) {
	$(container).find('.stepper').on('click', function(event) {
		let value = $(this).closest('.stepper').find('.stepper-value');
		let change = 0;
		if ($(event.target).closest('.stepper-button-minus').length) {
			change = -1;
		} else if ($(event.target).closest('.stepper-button-plus').length) {
			change = 1;
		}
		if (!change) {return ;}
		let new_value = (parseInt(value.html()) || 0) + change;

		let min = parseInt(value.attr('min'));
		let max = parseInt(value.attr('max'));
		if (!isNaN(min) && new_value < min) { new_value = min; }
		if (!isNaN(max) && new_value > max) { new_value = max; }
		value.html(new_value);
		$(this).closest('.stepper').change();
	});
}

function selezione_menu(elem) {
	let menu = elem.dataset.menu;
	let riga = elem.dataset.riga;
	$.post('struttura/ristorante/piatti_menu.php', {menu, riga}, (data) => {
		ristorante.selezione_piatti(5, [menu, riga], $('#ristorante-selezione-piatti-menu'), data);
	}, 'json');
}

$('#ristorante-elenco-prodotti .risto-ordinazione-prodotto').off().on('click', function() {
	let $this = $(this);
	if ($this.data('serv') == 0 && $this.data('tipo') == 2) {
		return selezione_menu(this);
	}

	let picker = crea_picker();
	picker = $('#' + picker);
	picker.html($('#ristorante-opzioni-piatto-template').html());
	picker.find('.nome').html($(this).find('.nome').html());
	let id = this.dataset.id;
	let qta = $(this).find('.qta').data('qta');
	picker.find('.qta .stepper-value').html(qta);
	abilita_stepper(picker);

	let info = $('#ristorante-info-ordinazione');
    var IDtav = info.data('tavolo');

	$(picker).find('.clickable').on('click', function() {
		let azione = this.dataset.action;
		switch (azione) {
		case 'elimina':
			modordinazione(12, IDtav, id, 10, () => {
				chiudi_picker();
				ristorante.reload_ordinazione();
			});
			break;
		case 'varia':
			chiudi_picker();
			ristorante.selezione_piatti(4, id, $('#ristorante-selezione-variazioni'));
			break;
		}
	});
});
<?php if ($refresh) {
	//in caso di refresh, si ferma qui
	echo '})()
	</script>';
	return;
}
?>
ristorante.selezione_piatti(0,0,$('#ristorante-selezione-sottotip'));

$('#ordinazione-selettore-portate li').on('click', function() {
	let info = $('#ristorante-info-ordinazione');
	info.data('portata', this.dataset.index);
});

$('#ordinazione-modo-variazione').on('click', function(event) {
	let target = $(event.target).closest('div');
    if (target.data('value') !== undefined) {
        $(this).find('div').removeClass('selected');
        target.addClass('selected');
    }
});
})();
</script>
<style>
.ordinazione-portata:not(.uk-active) {
	display: none;
}

.risto-ordinazione-prodotto {
    display: flex;
    height: 38px;
    align-items: center;
    font-size: 16px;
}

.risto-selezione .pannello {
	height: 100%;
	overflow: hidden;
}

.risto-ordinazione-prodotto > span {
	padding: 2px 5px;
}

.risto-ordinazione-prodotto > .prezzo {
	text-align: right;
}

.ordinazione-pannello-prodotti {
	overflow-y: auto;
	height: calc(100% - 50px);
}

.ordinazione-select-mode {
	display: flex;
	overflow: auto;
	height: 50px;
	align-items: stretch;
}

.ordinazione-select-mode > div {
	width: 65px;
	flex: 1 0 auto;
	text-align: center;
	display: flex;
	align-items: center;
	justify-content: center;
	background: #fff;
}

.ordinazione-select-mode > div.selected {
	color: #fff;
	background: #2574ec;
}

.ordinazione-btn {
	min-width: 0;
	height: 60px;
	border: 1px solid #ccc;
	border-radius: 0;
	position: relative;
	flex: 0 0 auto;
}

.ordinazione-btn.categoria{
	background: #eee;
}

.ordinazione-btn.prodotto{
	background: #f1f1f1;
}

.ordinazione-btn:focus {
	outline: none;
}

.ordinazione-btn .tag-prezzo {
	position: absolute;
	top: 2px;
	right: 2px;
	font-size: 11px;
}

.ordinazione-btn .tag-qta {
	position: absolute;
	left: 2px;
	top: 2px;
	font-weight: 600;
	font-size: 16px;
	color: red;
}

.ordinazione-btn-back {
	color: white;
}

.ordinazione-btn-action {
	position: absolute;
	bottom: 100%;
	background: none;
	border: unset;
	font-size: 20px;
	color: #fff;
	padding: 10px;
	font-weight: bold;
	padding-top: 30px; /*facilita il click*/
}

.ristorante-opzioni-piatto li {
	padding: 10px;
}

.uk-tab-ordinazione {
    padding: 10px 0;
    overflow: auto;
    white-space: nowrap;
    background: #fff;
    margin: 0;
    flex-wrap: unset;
    background: #2574ec;
}

.uk-tab-ordinazione li > a {
	position: relative;
}

.uk-tab-ordinazione li:not(.static) > a {
    border: none;
    color: #2574ec;
    background: #ffffff;
    outline: none;
    border-radius: 3px;
}

.uk-tab-ordinazione li.notice > a::before {
	position: absolute;
    /* background: red; */
    width: 0;
    height: 0;
    top: 0;
    left: 0;
    border-top-left-radius: 3px;
    content: "";
    border: 5px solid red;
    border-bottom: 5px solid transparent;
    border-right: 5px solid transparent;
}

.uk-tab-ordinazione li.static > a {
    color: #ffffff;
    background: #2574ec;
}

.uk-tab-ordinazione li.uk-active > a {
	color: #fff;
    text-decoration: underline;
    background: #1d51a0;
}
</style>
