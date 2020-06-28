<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../config/connecti.php';
require_once __DIR__ . '/../../config/funzioni.php';

$IDstruttura = $_SESSION['IDstruttura'];

$dati = (isset($_POST['arr_dati']) ? $_POST['arr_dati'] : []);
$IDprenotazione = $dati['IDprenotazione'] ?? 0;

/*
$nome = estrainome($IDpren);
$categ = get_categoria_prenotazione_giorno($IDpren, $IDstruttura);
$IDapp = $categ['appartamento'];

$query = "SELECT p.time,p.gg,p.checkout,app.nome  FROM prenotazioni as p
JOIN appartamenti as app ON app.ID='$IDapp'
WHERE p.IDv='$IDpren' LIMIT 1";

$result = mysqli_query($link2, $query);
$row = mysqli_fetch_row($result);
$timearr = $row['0'];
$notti = $row['1'];
$checkout = $row['2'];
$appartamento = $row['3'];

$data_noformat = date('d-m-Y', $timearr);
$data = date('Y/m/d', $timearr);
 */

$dettaglio_prenotazione = get_prenotazioni(['0' => ['IDprenotazione' => $IDprenotazione]])['dati'][$IDprenotazione];
$IDapp = 0;
$div_cambia_time = '';
if ($dettaglio_prenotazione['notti'] > 0) {

	$query = "SELECT ID,nome FROM appartamenti WHERE IDstruttura='$IDstruttura' AND attivo IN(0,1)";
	$result = $link2->query($query);
	$alloggi = $result->fetch_all();
	$lista_alloggi = array_combine(array_column($alloggi, 0), array_column($alloggi, 1));

	$sessione = &$_SESSION['prenotazione'][$IDprenotazione];

	$query = "SELECT p.time,p.sala,a.nome,a.stato FROM prenextra AS p LEFT JOIN appartamenti AS a ON a.ID=p.sala WHERE p.IDstruttura='$IDstruttura' AND p.IDpren='$IDprenotazione' AND p.tipolim=4 ORDER BY p.time";
	$result = $link2->query($query) or trigger_error($query);
	$tmp = [];
	$sala_corrente = null;
	$spezzo = $sessione['spezzo'] ?? [];
	$date_pernotti = [];
	while ($row = $result->fetch_row()) {

		if ($IDapp == 0) {$IDapp = $row['1'];}

		$row[0] = strtotime(date('Y-m-d', $row[0]));
		$date_pernotti[] = $row[0];
		if ($sala_corrente != $row[1] or (in_array($row[0], $spezzo))) {
			$sala_corrente = $row[1];
			$tmp[] = [];
		}
		$tmp[count($tmp) - 1][] = $row;
	}
	$soggiorno = [];
	$giorno_arrivo = null;
	$giorno_partenza = null;
	foreach ($tmp as $riga) {
		$da = min(array_column($riga, 0));
		$a = max(array_column($riga, 0));
		$giorno_arrivo = $giorno_arrivo ? min($giorno_arrivo, $da) : $da;
		$giorno_partenza = $giorno_partenza ? max($giorno_partenza, $a) : $a;
		$soggiorno[] = ['da' => $da, 'a' => $a, 'alloggio' => $riga[0][1], 'stato_alloggio' => $riga[0][3]];
	}
	$giorno_partenza += 86400;

	foreach ($sessione['insert'] ?? [] as $s => $info) {
		if ($s == $giorno_arrivo) {
			$r1 = reset($soggiorno);
			$r = ['da' => $info['da'], 'a' => $info['a'], 'alloggio' => 0, 'stato_alloggio' => $r1['stato_alloggio'], 'tmp' => $s];
			array_unshift($soggiorno, $r);
			for ($i = $info['a']; $i >= $info['da']; $i -= 86400) {
				array_unshift($date_pernotti, $i);
			}
		}
		if ($s == $giorno_partenza) {
			$rf = end($soggiorno);
			$r = ['da' => $info['da'], 'a' => $info['a'], 'alloggio' => 0, 'stato_alloggio' => $rf['stato_alloggio'], 'tmp' => $s];
			array_push($soggiorno, $r);
			for ($i = $info['da']; $i <= $info['a']; $i += 86400) {
				array_push($date_pernotti, $i);
			}
		}
	}

	//alloggi
	$query = "SELECT ID,nome FROM appartamenti WHERE IDstruttura='$IDstruttura' AND attivo IN(0,1)";
	$result = $link2->query($query);
	$row = $result->fetch_all();
	$alloggi = array_combine(array_column($row, 0), array_column($row, 1));

	//alloggi disponibili per spostamento
	if (count($date_pernotti)) {
		$min_date = date('Y-m-d', min($date_pernotti));
		$max_date = date('Y-m-d', max($date_pernotti));

		$min_time = strtotime($min_date);
		$max_time = strtotime($max_date);

		/*$query = "SELECT FROM_UNIXTIME(p.time,'%Y-%m-%d'),p.sala
			        FROM prenextra AS p
			        WHERE p.IDstruttura='$IDstruttura' AND (p.tipolim=4 OR (p.tipolim=5 AND p.sottotip=1)) AND FROM_UNIXTIME(p.time,'%Y-%m-%d') BETWEEN '$min_date' AND '$max_date' AND p.IDpren!='$id' AND p.sala!=0 AND modi>=0";
		*/
		$query = "SELECT FROM_UNIXTIME(p.time,'%Y-%m-%d'),p.sala
      FROM prenextra AS p
      WHERE p.IDstruttura='$IDstruttura' AND (p.tipolim=4 OR (p.tipolim=5 AND p.sottotip=1)) AND p.time>=$min_time AND p.time<$max_time AND p.IDpren!='$IDprenotazione' AND p.sala!=0 AND modi>=0";
		$result = $link2->query($query);
		$alloggi_occupati = [];
		while ($row = $result->fetch_row()) {
			$time0_in = strtotime($row['0']);

			if (!isset($alloggi_occupati[$time0_in])) {
				$alloggi_occupati[$time0_in] = [];
			}
			$alloggi_occupati[$time0_in][] = $row[1];
		}
	}

	foreach ($soggiorno as $idriga => $riga) {
		$da = date('d/m/Y', $riga['da']);
		$a = date('d/m/Y', $riga['a'] + 86400);
		$giorni = intval(($riga['a'] - $riga['da']) / 86400) + 1;
		$disable_inizio = ($idriga != 0) ? 'disabled' : '';
		$disable_fine = ($idriga != (count($soggiorno) - 1)) ? 'disabled' : '';
		$disable_giorni = ($disable_fine != '') ? 'disabled' : '';
		$tipo_giorno = ($idriga != 0 or count($soggiorno) == 1) ? 1 : 0;
		//$stato_allo = $stati_alloggio[$riga['stato_alloggio']];
		$elimina = ((count($soggiorno) > 1) and !($disable_inizio and $disable_fine));
		/*
			    $div_stati_alloggio = '<div>';
			    foreach ($stati_alloggio as $s) {
			      $div_stati_alloggio .= '<div style="padding:5px;cursor:pointer;" class="hover--bold" onclick="modprenot(' . $riga['alloggio'] . "," . $s['ID'] . ",17,10,23" . ');closemodif();"><button class="shortcut cerc3 statiapp ' . $s['classe'] . ' ' . $s['icon'] . '"></button> ' . $s['stato'] . '</div>';
			    }
			    $div_stati_alloggio .= '</div>';
		*/

		$da_time0 = $riga['da'];
		$a_time0 = $riga['a'];
		$alloggi_disponibili = array_filter(array_keys($alloggi), function ($allo) use ($alloggi_occupati, $da_time0, $a_time0) {
			for ($i = $da_time0; $i <= $a_time0; $i += 86400) {
				if (isset($alloggi_occupati[$i])) {
					if (in_array($allo, $alloggi_occupati[$i])) {
						return false;
					}
				}
			}
			return true;
		});

		/*
			    <tr class="date-line">
			  <td><input ' . $disable_inizio . ' id="datai' . $idriga . '" class="data-inizio" style="width:65px;" type="text" value="' . $da . '"></td>
			  <td><input ' . $disable_fine . ' id="dataf' . $idriga . '" class="data-fine" style="width:65px;" type="text" value="' . $a . '"></td>
			  <td><input ' . $disable_giorni . ' id="giorni' . $idriga . '" data-notti="' . $giorni . '" data-giorni="' . $notti . '" data-data="' . date('Y-m-d', $time) . '" style="width:35px;" type="text" value="' . $giorni . '"></td>
			  <td><select id="alloggi' . $idriga . '" class="multiselect" style="width:unset;">
			  <option value="0"></option>

			  ' . generaappart($riga['alloggio'], $alloggi_disponibili) . '</select></td>
		*/
		/*
			    if ($riga['tmp'] ?? false) {
			      $div_cambia_time .= "
			          let alloggio = $(this).val();
			          let tipo = " . (!$disable_inizio ? '0' : '1') . ";
			          let target = tipo ? '#dataf" . $idriga . "' : '#datai" . $idriga . "';
			          let data = $(target).val();
			          controllo_check($id,data,{tipo: tipo, alloggio: alloggio},true)";
			    } else {
			      $div_cambia_time .= "if($(this).val()!=0){sposta_date_prenotazione($IDprenotazione,{$riga['da']},$giorni,{$riga['alloggio']},this.value);}";
			    }  $evento_giorno_alloggi='set_notti_prenotazione('.$IDprenotazione.',this,'.$tipo_giorno.');';
		*/
		$alloggio_singolo = (count($soggiorno) == 1);

		$evento_giorno_arrivo = 'controllo_check(' . $IDprenotazione . ',this.value,0,' . ($alloggio_singolo ? 'false' : 'true') . ')';
		$evento_giorno_partenza = 'controllo_check(' . $IDprenotazione . ',this.value,1,true)';
		$evento_giorno_notti = 'set_notti_prenotazione(' . $IDprenotazione . ',this,' . $tipo_giorno . ');';
		$evento_giorno_alloggi = 'sposta_date_prenotazione(' . $IDprenotazione . ',' . $riga['da'] . ',' . $giorni . ',' . $riga['alloggio'] . ',r);';

		if ($riga['tmp'] ?? false) {
			$evento_giorno_arrivo = 'modprenot(' . $IDprenotazione . ',[' . $riga['tmp'] . ',this.value,0],267,10,() => {navigation(6,{IDprenotazione:' . $IDprenotazione . '},0)});';
			$evento_giorno_partenza = 'modprenot(' . $IDprenotazione . ',[' . $riga['tmp'] . ',this.value,1],267,10,() => {navigation(6,{IDprenotazione:' . $IDprenotazione . '},0)});';
			$evento_giorno_notti = 'modprenot(' . $IDprenotazione . ',[' . $riga['tmp'] . ',this.value,1],268,10,() => {navigation(6,{IDprenotazione:' . $IDprenotazione . '},0)});';

			$tipo = (!$disable_inizio ? '0' : '1');
			$data = ($tipo ? date('d/m/Y', $riga['a'] + 86400) : date('d/m/Y', $riga['da']));
			$evento_giorno_alloggi = 'controllo_check(' . $IDprenotazione . ',' . $data . ',{tipo: ' . $tipo . ', alloggio: r},true)';
		}

		$div_cambia_time .= '

      <div class=" uk_grid_div div_list_uk" uk-grid  >
        <div class="uk-width-1-3 lista_grid_nome">Arrivo </div>
        <div class="uk-width-expand uk-text-right lista_grid_right"  >
          <input class="uk-input uk-text-right input_cli  uk-form-small" type="text" data-testo="Seleziona Nuovo giorno Checkin" onclick="apri_modal(this,1);"


            onchange="' . $evento_giorno_arrivo . '"
              value="' . date('d/m/Y', $riga['da']) . '"  data-noformat="' . date('d-m-Y', $riga['da']) . '" placeholder="Data checkin"  readonly id="datai' . $idriga . '">
        </div>
    </div>


     <div class=" uk_grid_div div_list_uk" uk-grid  >
        <div class="uk-width-1-3 lista_grid_nome">Partenza </div>
        <div class="uk-width-expand uk-text-right lista_grid_right"  >
             <input class="uk-input uk-text-right input_cli  uk-form-small" type="text" data-testo="Seleziona Nuovo giorno Checkin" onclick="apri_modal(this,1);"
                onchange="' . $evento_giorno_partenza . '"
              value="' . date('d/m/Y', $riga['a'] + 86400) . '"  data-noformat="' . date('d-m-Y', $riga['a'] + 86400) . '" placeholder="Data checkin"  readonly id="dataf' . $idriga . '">
         </div>
    </div>


    <div class="div_list_uk uk_grid_div " uk-grid>
          <div class="uk-width-1-2 uk-text-truncate lista_grid_nome"  >Notti</div>
            <div class="uk-width-expand uk-text-right lista_grid_right ">
              <div class="stepper  stepper-init stepperrestr">


                <div class="stepper-button-minus" style="color:#0075ff;border:none"  onclick="selezionainfo(\'giorni' . $idriga . '\',2,0)"  ><i class="fas fa-minus"></i></div>


               <div class="stepper-value  inputrestr" min="0"   onchange="' . $evento_giorno_notti . '"   max="99"   style="border-bottom:1px solid #d6d6d6"
               id="giorni' . $idriga . '" data-notti="' . $giorni . '" data-giorni="' . $dettaglio_prenotazione['notti'] . '" data-data="' . date('Y-m-d', $dettaglio_prenotazione['checkin']) . '"
                >  ' . $giorni . '</div>

                <div class="stepper-button-plus" style="color:#0075ff;border:none" onclick="selezionainfo(\'giorni' . $idriga . '\',1,0)"  ><i class="fas fa-plus"></i></div>
             </div>
            </div>
        </div>';

		$select_alloggi = $lista_alloggi;

		if (!empty($alloggi_disponibili)) {
			foreach ($lista_alloggi as $idall => $nome) {
				if (!in_array($idall, $alloggi_disponibili)) {continue;}
				$select_alloggi[$idall] = $nome;
			}

		}

		$div_cambia_time .= '
        <div id="select' . $idriga . '" style="display:none;">
              <ul class="uk-list uk-list-divider uk-picker-bot" style="padding:5px 20px;" onchange="(r,s)=>{cambia_valore_html_select(\'alloggi' . $idriga . '\',r,s);' . $evento_giorno_alloggi . '}">
                ' . genera_select_uikit($select_alloggi, $riga['alloggio']) . '
              </ul>
            </div>

       <div class=" uk_grid_div div_list_uk" uk-grid    onclick="carica_content_picker($(\'#select' . $idriga . '\'))">
                   <div class="uk-width-1-3 lista_grid_nome">Alloggio </div>
                  <div class="uk-width-expand uk-text-right lista_grid_right c000"  id="alloggi' . $idriga . '"   data-select="' . $riga['alloggio'] . '"   > ' . $select_alloggi[$riga['alloggio']] . '  </div>
          </div>
 ';
		/*

						    if (!($riga['tmp'] ?? false)) {} else {
						      $pernottamenti .= '<button class="shortcut danger mini15 popover" onclick="modprenot(' . "$id,[" . $riga['tmp'] . ",0,1],268,10,() => {aprifunc($id,1,0)}" . ');"><i class="fas fa-times"></i><span>Rimuovi riga</span>';
						    }

						    $opzioni = '';
						    $opzioni .= '
						      <div style="display: flex; flex-direction: column; justify-content: center;" class="shortcut buttonimpo2" onchange="modprenot(' . "$id,[this.dataset.value,'split',$idriga],234,10,1" . '); closemodif();" id="split' . $idriga . '"  data-value="' . $da . '">Spezza</div>
						      <script>' . "
						      setTimeout(function() {
						        let first = true;
						      $('#split" . $idriga . "').dateRangePicker({
						        format: 'DD/MM/YYYY',
						        singleDate: true,
						        startDate:'" . date('d/m/Y', ($riga['da'] + 86400)) . "',
						        endDate:'" . date('d/m/Y', ($riga['a'])) . "',
						        getValue: function(){
						          return this.dataset.value;
						        },
						        setValue: function(s, s1) {
						          if (s != this.dataset.value) {
						            this.dataset.value = s;
						            $(this).trigger('change');
						          }
						        }
						      });
						      });
						      " . '
						      </script>
						      ';

						    if (!$disable_inizio) {
						      $opzioni .= '
						        <div style="display: flex; flex-direction: column; justify-content: center;" class="shortcut buttonimpo2" onclick="creasessione([' . "$id," . $riga['da'] . ',0], 143); closemodif();" id="split' . $idriga . '"  value="' . $da . '">Inserisci riga sopra</div>
						        ';
						    }
						    if (!$disable_fine) {
						      $opzioni .= '
						        <div style="display: flex; flex-direction: column; justify-content: center;" class="shortcut buttonimpo2" onclick="creasessione([' . "$id," . ($riga['a'] + 86400) . ',1], 143); closemodif();" id="split' . $idriga . '"  value="' . $da . '">Inserisci riga sotto</div>
						        ';
						    }

						    if (!($riga['tmp'] ?? false)) {
						      $pernottamenti .= '<td><button class="shortcut grey mini15" onclick="modifIDp_html(this)"><i class="fas fa-ellipsis-h"></i></button><div class="modp-html" style="display: none;">' . $opzioni . '</div></td>';
						    } else {
						      $pernottamenti .= '<td><button class="shortcut success mini15 popover" onclick="alertify.error(\'Selezionare un alloggio\'); $(this).closest(\'tr\').find(\'.fs-wrap\').css(\'background-color\', \'#a92111\');"><i class="fas fa-check"></i><span>Conferma riga</span></td>';
						    }
		*/

		$data_start = ((count($soggiorno) > 1) and !$disable_fine) ? $riga['da'] : $riga['da'] + 86400;
		$data_stop = ((count($soggiorno) > 1) and !$disable_inizio) ? $riga['a'] + 86400 : $riga['a'];

	}

} else {

	$div_cambia_time = '
   <div class=" uk_grid_div div_list_uk" uk-grid  >
        <div class="uk-width-1-3 lista_grid_nome">Arrivo  </div>
        <div class="uk-width-expand uk-text-right lista_grid_right" >
              <input class="uk-input uk-text-right input_cli  uk-form-small" type="text" data-testo="Seleziona Nuovo giorno Checkin" onclick="apri_modal(this,1);"
             onchange="modprenot(19,[\'' . date('d/m/Y', $dettaglio_prenotazione['checkin']) . '\',this.value],257,10,()=>{goBack()})"

              value="' . date('d/m/Y', $dettaglio_prenotazione['checkin']) . '"  data-noformat="' . date('d-m-Y', $dettaglio_prenotazione['checkin']) . '" placeholder="Data checkin"  readonly>
        </div>
    </div>';
}

$testo = '<div class="div_uk_divider_list" style="margin-top:0px !important;">Dati prenotazione</div>


 ' . $div_cambia_time . '

';

echo $testo;
