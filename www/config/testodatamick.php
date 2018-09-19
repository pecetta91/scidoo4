<?php
header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');
include('../../config/funzionilingua.php');

$data=$_POST['data'];

list($yy, $mm, $dd) = explode("-", $data);
$time0=mktime(0, 0, 0, $mm, $dd, $yy);



echo date('d',$time0).'//'.$mesiita[date('n',$time0)].'//'.date('Y',$time0).'//'.$giorniita[date('w',$time0)];

?>