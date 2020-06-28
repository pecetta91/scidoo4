<?php
//header('Access-Control-Allow-Origin: *');
include '../../config/connecti.php';
include '../../config/funzioni.php';

$IDutente = $_SESSION['ID'];
$IDstruttura = $_SESSION['IDstruttura'];

$IDdom = $_POST['IDdom'];
$manuale = $_POST['manuale'];
$testo = '';

$query = "SELECT etichetta FROM domotica WHERE ID='$IDdom' LIMIT 1";
$result = mysqli_query($link2, $query);
$row = mysqli_fetch_row($result);
$nomedom = $row['0'];

$testo = '';
$statoarr = array('Spento', 'Acceso');
$query2 = "SELECT timei,timef,acceso FROM pianificazione WHERE IDdom='$IDdom' ";
$result2 = mysqli_query($link2, $query2);
if (mysqli_num_rows($result2) <= 0) {$elimina = 0;} else { $elimina = 1;}

$testo = '<div class="uk-text-lead	uk-text-capitalize	uk-text-bold	uk-text-small uk-margin-bottom	" style="color:#2641da;">' . $nomedom . '</div>';

switch ($manuale) {

case 1:

	//	'.generadurata(60,480,0,60).'
	//	<option value="604800">Una Settimana</option>
	//<option value="31536000">Un Anno</option>
	$testo .= '
		<div class="uk-text-lead	uk-text-capitalize	uk-text-bold	uk-text-small uk-margin-bottom	"  ">Manuale a Tempo</div>

		<div class="uk-margin-small uk_grid_div " uk-grid >


		    <div class="uk-width-2-3 lista_grid_nome uk-first-column">Durata Accensione</div>
		    <div class="uk-width-expand uk-text-right lista_grid_right"> <select style="width:80px; direction:inherit; padding-left:20px;   font-weight:400; border-radius:4px; border:solid 1px #ccc; font-size:14px;" id="oreatt">
				' . generadurata(60, 480, 0, 60) . '
				<option value="604800">Una Settimana</option>
				<option value="31536000">Un Anno</option>
				</select>
			 </div>
		</div> ';

	break;
case 2:

	$testo .= '
			<div class="uk-text-lead	uk-text-capitalize	uk-text-bold	uk-text-small uk-margin-bottom	" style="color:#2641da;">Manuale ad Intervallo</div>



		<div class="uk-margin-small uk_grid_div " uk-grid >


		    <div class="uk-width-1-2  ">
		   	 <select style="width:100%; height:30px; direction:inherit;padding-left:33px;  font-weight:400; border-radius:4px; border:solid 1px #ccc; font-size:14px;" id="accendi">' . generaorario(15, 0, 24) . '</select>
		    </div>


		       <div class="uk-width-1-2  ">

		       <select style="width:100%;height:30px; direction:inherit;   padding-left:33px;   font-weight:400; border-radius:4px; border:solid 1px #ccc; font-size:14px;" id="spegni">' . generaorario(15, 0, 24) . '</select>

		       	</div>

		</div>

			 <div class="uk-margin">';

	for ($i = 0; $i < 7; $i++) {
		$testo .= '
					 <div class="uk-form-controls uk-form-controls-text">
			            <label><input class="uk-checkbox" type="checkbox" name="giornidom" value="' . $i . '"> ' . $giorniita[($i + 1)] . '</label><br>
       				 </div> 	';

	}

	$testo .= '</div>';

	break;
}

$statoarr = array('Spento', 'Acceso');
$query2 = "SELECT timei,timef,acceso FROM pianificazione WHERE IDdom='$IDdom' ";
$result2 = mysqli_query($link2, $query2);
if (mysqli_num_rows($result2) > 0) {
	$testo .= ' <div class="uk-text-small uk-margin  uk-text-lighter "> Programmi Attivi</div>  ';
	while ($row2 = mysqli_fetch_row($result2)) {
		$testo .= '

				<div uk-grid class="uk-margin-small uk_grid_div ">
						<div class="uk-width-expand  lista_grid_nome">' . dataita($row2['0']) . '<br><span class="uk-text-lighter uk-text-muted" style="font-size:10px;">' . $statoarr[$row2['2']] . '</span> </div>

						<div class="uk-width-auto   lista_grid_right"> ' . date('H:i', $row2['0']) . ' - ' . date('H:i', $row2['1']) . '	 </div>


				</div> ';
	}
	$testo .= '<br/><br/><br/> ';
}

$picker = '
<div class="nav navbar_picker_flex" >
 	<div onclick="chiudi_picker()"><i class="fas fa-times icona_chiudi" ></i></div>
 	<div style="margin-top:5px;padding-right:10px">
 		 <button  style=" width: 100%;   background: #2542d9; border: none;  color: #fff;   border-radius: 5px;   padding: 5px 10px;   font-size: 16px;"  onclick="pulsacc(' . $IDdom . ',' . $manuale . ')">Accendi/Spegni</button>
 	</div>
</div>

<div class="content" style="margin-top:0;height:calc(100% - 70px)">
	<div   style="padding:5px;">
        	' . $testo . '
        </div>
   </div>
';

echo $picker;

?>
