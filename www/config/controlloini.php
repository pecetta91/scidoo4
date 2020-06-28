<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../config/connecti.php';
require_once __DIR__ . '/../../config/funzioni.php';
$testo = 'error';

if (isset($_SESSION['blocca_struttura'])) {
	if (isset($_SESSION['IDstrpren'])) {
		echo '1';
		return false;
	}
}

if (isset($_COOKIE['scidoostr'])) {

	$codice = $_COOKIE['scidoostr'];
	//$output=decrypt($key,$codice,1);
	$output = data_decrypt($codice, $key);
	$arr = explode('_', $output);
	$IDstr = $arr['0'];
	$IDutente = $arr['1'];
	$testo = '0';
} else {
	if (isset($_COOKIE['scidoo'])) {
		$codice = $_COOKIE['scidoo'];
		$IDutente = data_decrypt($codice, $key);
		//$IDutente = decrypt($key, $codice, 1);
		//controlla la struttura di questa persona altrimenti niente

		$query = "(SELECT ID FROM strutture WHERE IDcliente='$IDutente' LIMIT 1) UNION (SELECT ID FROM personale WHERE  IDuser='$IDutente' LIMIT 1) ";
		$result = mysqli_query($link2, $query);
		if (mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_row($result);
			$IDstr = $row['0'];
			$_SESSION['IDstruttura'] = $IDstr;

			//creo cookie

			$text = $IDutente . '_' . $IDstr;
			//$codice = encrypt($key, $text, 1);
			$codice = data_encrypt($text, $key);
			setcookie("scidoostr", $codice, time() + 2678400, '/', "scidoo.com"); //cookie che dura un mese
		}
	} else {
		if (isset($_COOKIE['scidooguest'])) {
			$codice = $_COOKIE['scidooguest'];
			//$output = decrypt($key, $codice, 1);

			$output = data_decrypt($codice, $key);
			$arr = explode('_', $output);

			if (isset($arr['2'])) {

				$IDpren = intval($arr['2']);
				if (is_numeric($IDpren)) {
					$IDcliente = $arr['3'];
					$tipocli = $arr['4'];
					$_SESSION['IDstrpren'] = $IDpren;
					$_SESSION['IDcliente'] = $IDcliente;
					$_SESSION['tipocli'] = $tipocli; //schedine
					$testo = '1';
				}
			}

		}
	}

}

echo $testo;

?>
