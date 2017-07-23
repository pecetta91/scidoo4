<?php 
header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');

$IDutente=$_SESSION['ID'];
$IDstruttura=$_SESSION['IDstruttura'];
unset($_SESSION['arrservadd']);

$IDpren=$_GET['IDpren'];
$_SESSION['IDprenfunc']=$IDpren;
$testo='
<div class="pages navbar-fixed">
<div data-page="addservice" class="page" > 

            <div class="navbar">
               <div class="navbar-inner">
                  <div class="left">
				 	 <a href="#add1" class="tab-link tabindietro" style="display:none;" onclick="backselect()"><i class="icon f7-icons" style=" width:60px;  font-size:25px;">chevron_left</i></a>
				  </div> 
                  <div class="center titolonav" style="line-height:14px;">'.estrainome($IDpren).'<br><span style="font-size:11px;">'.estrainomeapp($IDpren).'</span></div>
                  <div class="right" onclick="mainView.router.back();">
						<a href="#"><i class="icon f7-icons" style=" font-size:30px; margin-right:18px;">close</i></a>				  
				  </div>
               </div>
            </div>
			
			
			<div class="page-content" > 
			
			
			<div  style="display:none;" >
				<div class="buttons-row">
			
				<a href="#add1" class="tab-link active" >Passo 1</a>
				<a href="#add2" class="tab-link " >Passo 2</a>
			
				</div>	</div>
			
			
			<div class="tabs-animated-wrap" style="height:auto;">
  			 	<div class="tabs" style="height:auto;" valign="top">
					
					
			<div id="add1" class="tab active" style="overflow-y:visible;" >
			
			
			 <form data-search-list=".search-here" data-search-in=".item-title" class="searchbar searchbar-init" >
      <div class="searchbar-input"  >
        <input type="search" placeholder="Ricerca il servizio"  onkeyup="cercaservizio(this.value)"/><a href="#" class="searchbar-clear"></a>
      </div><a href="#" class="searchbar-cancel">Cancel</a>
    </form>
	<div class="list-block" style="margin-top:5px;">
			  <ul id="listaservizi">
';



if(isset($_SESSION['listIDsotto'])){
	
	
	
	if($_SESSION['listIDsotto']==2){
		$query="SELECT s.ID,s.servizio,s.prezzo,t.tipolimite,s.IDtipo,s.durata FROM servizi as s,tiposervizio as t WHERE s.IDstruttura='$IDstruttura' AND s.IDtipo ='".$_SESSION['listIDsotto']."' AND s.IDtipo=t.ID LIMIT 10";
	}else{
		$query="SELECT s.ID,s.servizio,s.prezzo,t.tipolimite,s.IDtipo,s.durata FROM servizi as s,tiposervizio as t  WHERE s.IDstruttura='$IDstruttura' AND s.IDsottotip='".$_SESSION['listIDsotto']."' AND t.ID=s.IDtipo LIMIT 10";
	}
	
	$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
		while($row=mysqli_fetch_row($result)){
			
		$testo.='
			 <li  onclick="selectservice('.$row['0'].','.$row['3'].','.$row['4'].','.$row['5'].')">
				<label class="label-radio item-content">
				  <div class="item-inner" >
					<div class="item-title">'.$row['1'].'</div>
					 <div class="item-after">'.$row['2'].' €</div>
					
				  </div>
				</label>
			  </li>
		
		';
		
		
		}
	}
	

}else{
	$testo.='<br><div style="width:100%;" align="center"><h3>Ricerca e Aggiungi il servizio<h3></div><br><br>';
}
$testo.='</ul></div>';

$agg=1;
if(isset($_SESSION['datecentro'])){
	$agg=2;
}

$testo.='

</div>
<div id="add2" class="tab " style="overflow-y:visible;padding-top:28px; padding-bottom:100px;" >
</div>

</div>	
</div>








';


$agg=1;
if(isset($_SESSION['datecentro'])){
	$agg=2;
}

$testo.='

<div id="buttadddiv" style="position:fixed; display:none; background:#f5f5f5;  bottom:0px; left:0px; width:100%; height:60px;padding-top:10px; border-top:solid 1px #ccc; transform:translateZ(0); webkit-transform:translateZ(0);" align="center">
<table width="100%"><tr><td width="20%" align="center" style="font-size:19px;"><span id="totaleadd">0</span>€
</td><td><p class="buttons-row" style="margin:auto; width:90%;">

  <a href="#"  onclick="addservice2('.$agg.',0)" class="button button-fill color-green" style="height:40px; font-size:17px; line-height:35px;">+ Iniziale</a>
  <a href="#" onclick="addservice2('.$agg.',1)" class="button button-fill" style="height:40px; font-size:17px; line-height:35px;">+ Extra</a>
</p>
</td></tr></table>

</div>


</div>
</div></div>


';
		


echo $testo;
?>