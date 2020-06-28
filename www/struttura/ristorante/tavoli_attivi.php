<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/' . '../../../config/connecti.php';
require_once DIR_CONFIG . '/' . 'funzioni.php';
require_once DIR_CONFIG . '/' . 'ristorante/funzioni_ristorante.php';

array_escape($_POST);
$IDstruttura = $IDstruttura ?? $_SESSION['IDstruttura'];

$content = '';
$time = time_struttura(); //$_SESSION['timeagenda'];
$data = date('Y-m-d', $time);

$has_coperto = risto_has_coperti($IDstruttura);

$IDprenotazione_post = $_POST['pren'] ?? null;
$IDsottotip = $_POST['sottotip'] ?? null;

$sottotip_sql = $IDsottotip ? " AND sc.IDsotto='$IDsottotip' " : '';
//sale
$query = "SELECT s.ID,s.nome FROM sale as s JOIN saleassoc as sc ON sc.ID=s.ID WHERE s.IDstr='$IDstruttura' $sottotip_sql AND FIND_IN_SET(1,s.servizi) GROUP BY s.ID ORDER BY sc.priorita";
$result = $link2->query($query);
$sale = $result->fetch_all();
$lista_sale = array_column($sale, 0);
$tavoli_attivi = tavoli_attivi($IDstruttura, $lista_sale);

foreach ($sale as $row) {
	list($IDsala, $nome_sala) = $row;

	$content .= '<div class="div-sala" data-sala="' . $IDsala . '">
	<div style="background:#f8f9fb;text-align:left; padding:10px;"> <strong style="line-height:20px; font-size:20px;">' . $nome_sala . '</strong></div>';
	$content .= '<div style="display: flex; flex-wrap: wrap;"> ';

	$tavoli_attivi_sala = $tavoli_attivi[$IDsala];
	foreach ($tavoli_attivi_sala as $sotto_sale) {
		$content .= '<div class="tavolodiv__wrapper" style="display: flex; position:relative;">';
		foreach ($sotto_sale as $sotto_sala) {
			$IDtavolo = $sotto_sala["id_tavolo"];
			$ID_sotto_sala = $sotto_sala["sotto_sala"];
			$IDsala = $sotto_sala["sala"];
			$ora_arrivo = $sotto_sala["ora"];
			$nome_sotto_sala = $sotto_sala["nome"];
			$nome_alloggio = $sotto_sala["alloggio"];
			$stato = $sotto_sala["stato"];
			$nome = $sotto_sala["cliente"];
			$id_prenotazione = $sotto_sala["id_prenotazione"];
			$occupato = $sotto_sala["occupato"];
			$id_sottotip = $sotto_sala['id_sottotip'];

			$class = 'libero';
			$txt = 'Libero';
			$func = 'onclick="ristorante.crea_tavolo([' . $ID_sotto_sala . ',' . 0 . ',' . $IDsala . '], ' . ($has_coperto ? 'true' : 'false') . ')"';
			$notecli = '';
//controllo se tavolo occupato
			if ($id_prenotazione) {
				$func = 'onclick="ristorante.crea_tavolo([' . "$ID_sotto_sala,0,$IDsala,$id_prenotazione" . '])"';
				$txt = 'Riservato';
				if ($occupato) {
					$txt = 'Occupato';
					$func = sprintf('onclick="navigation(28,{pren: %s, sottotip: %s, num_tavolo: %s});"', $id_prenotazione, $id_sottotip, $ID_sotto_sala);
				}
				$class = 'riservato';
			} else if ($occupato) {
				$class = 'occupato';
				$txt = 'Occupato';
				$func = sprintf('onclick="navigation(28, {tavolo: %s});"', $IDtavolo);
			}

			if ($IDprenotazione_post and $IDsottotip !== null) {
				$func = (!$id_prenotazione and !$occupato) ? 'onclick="ristorante.crea_tavolo([' . "$ID_sotto_sala,$IDsottotip,$IDsala,$IDprenotazione_post" . '])"' : '';
			}

			$content .= '<div class="tavolodiv ' . $class . '" ' . $func . ' style="position:relative;">
			<div style="float: right; font-size: 14px; color: #ad0e0e;">' . $ora_arrivo . '</div>
			<div>
			<div style="font-weight:600;">' . $nome_sotto_sala . '</div>
			<div class="nomeprent">' . $txt . '</div>
			</div>';
			if ($nome) {
				$content .= '<div class="risto-tavolo-nome">' . $nome . '</div>';
			}
			$content .= $notecli . '</div>';
		}
		$content .= '</div>';
	}
	$content .= '</div></div>';
}

echo $content;
?>
<style>
.tavolodiv__wrapper {
	font-size: 14px;
	width: 30%;
	border: 1px solid #e1e1e1;
	border-radius: 5px;
	margin: 10px 5px;
	background: #fff;
	box-shadow: 0 0 5px 1px #e1e1e1;
	color: #000;
	height: 100px;
}

.tavolodiv {
	padding: 5px;
	flex: 1;
	border-radius: 5px;
}

.tavolodiv .nomeprent {
	text-align: center;
}

.tavolodiv.libero {
	background-color: #daf8e2;
	border: solid 1px #91bfa0;
}

.tavolodiv.occupato {
	background-color: #ffca6d;
	border: solid 1px #98825b;
}

.tavolodiv.riservato {
	background-color: #b9bbff;
	border: solid 1px #98825b;
}

</style>
