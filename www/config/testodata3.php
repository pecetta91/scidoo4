<?php
header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');
include('../../config/funzionilingua.php');

$data=$_POST['data'];

list($yy, $mm, $dd) = explode("-", $data);
$time0=mktime(0, 0, 0, $mm, $dd, $yy);



echo dataita4($time0);

?>