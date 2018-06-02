<?php



$email=strip_tags($_GET['email']);
$pass=strip_tags($_GET['pass']);
$nome=strip_tags($_GET['nome']);
$nomestr=strip_tags($_GET['nomestr']);
$tel=strip_tags($_GET['tel']);
$tipo=strip_tags($_GET['tipo']);
$numcam=strip_tags($_GET['numc']);
$prezzom=strip_tags($_GET['prezzom']);
$serv=strip_tags($_GET['serv']);



$parametri=array();
parse_str($serv,$parametri);

foreach($parametri as $key =>$al){
 echo '<p>'.$key.' ' .$al. '</p>';
}

//quando passo a questa pagina prima salvo i dati sul db poi eseguo un login automatico 

?>