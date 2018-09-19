<?php 
header('Access-Control-Allow-Origin: *');
include('../../config/connecti.php');
include('../../config/funzioni.php');

$IDutente=$_SESSION['ID'];
$IDstruttura=$_SESSION['IDstruttura'];

$IDprenextra=$_POST['IDprenextra'];
$querym="SELECT IDpren FROM prenextra WHERE IDstruttura='$IDstruttura' AND ID='$IDprenextra' ";

$resultm=mysqli_query($link2,$querym);
$rowm=mysqli_fetch_row($resultm);
        $IDpren=$rowm['0'];



//$_SESSION['IDprenfunc']=$IDpren;
$testo='
<div class="pages ">
  <div data-page="addprodotto" class="page">

            <div class="navbar">
               <div class="navbar-inner">
                  <div class="left"></div>
                  <div class="center"><b>'.estrainome($IDpren).'</b><br/><span style="font-size:11px;">'.estrainomeapp($IDpren).'</span></div>
				  <div class="right" onclick="myApp.closeModal();"><i class="icon f7-icons">close</i></div>
				  
                  
               </div>
            </div>
		
			<div  class="page-content contacts-content" >
<br>
			
			 	
';
//<div class="list-block contacts-block"><ul>
    $categorie=array();
	$IDsotto=0;
	$query="SELECT sottotipologia,ID FROM sottotipologie  WHERE IDstr='$IDstruttura' AND IDmain IN(15,16,17) ORDER BY sottotipologia ";
    $i=0;

	$result=mysqli_query($link2,$query);
	if(mysqli_num_rows($result)>0){
		while($row=mysqli_fetch_row($result)){
			
		
			if($row['1']!=$IDsotto){
				
				$IDsotto=$row['1'];
				$categorie[$i]=$row['0'].','.$row['1'];
				$i++;
				
				$testo.='<input type="hidden" class="inputsottosel" value="" id="IDsotto'.$IDsotto.'">';
				
			}
	
		
		}
	}
    $buttoncat='';//ogni volta che premero devo far cambiare valore al bottone
 foreach ($categorie as $key){
	 
	 list($catnome,$catid)=explode(",",$key);
	 		$button='<div>'.$catnome.'</div>';
                $buttoncat.='
                    buttons.push({
                    text: '."'".$button."'".',
                    onClick: function () {
						
						var IDadd='.$catid.'+","+$$("#IDsotto'.$catid.'").val();
						navigationtxt(33,IDadd,'."'".'aggiungipiattidiv'."'".',0);
						cambiavalore("'.$catnome.'");
						$$("#IDsottoatt").val("'.$catid.'");
					}
                }); ';    
            }

$testo.=' 
	


			

            <input type="hidden" value="'.base64_encode($buttoncat).'" id="button">
			<div style="width:80%;  transform:translateZ(0); webkit-transform:translateZ(0); background:#fff; color:#fff;margin-left: calc(10% - 2px);
    margin-right: calc(10% - 2px);">
            	<a href="" style="width:100%;margin-top:40px;font-weight:600;text-transform: uppercase;" class="button button-fill" id="simplybutton" onClick="aggiungibott();">scegli la categoria</a>
			</div>
			
			
			<div id="aggiungipiattidiv"></div>
			
			<input id="IDsottoatt" type="hidden" value="0">
			<br/><br/><br/><br/><br/><br/><br/><br/>
			
			';
			
			

	
	

//</div></ul>
$testo.='






			<div class="bottombarpren" style="background:#f1f1f1;z-index:999;" align="center">
			  <table style="width:100%; height:100%;cellpadding="0";cellspacing="0";">
			   <tr>
			     <td style="width:15%">
                 </td>
				<td>
		         <button onclick="addprod2('.$IDprenextra.');"
				 style="background-color: #e4492b;color:#fff;" 
			 	 class="bottoneprezzo">Aggiungi (<span id="euro">0</span> €)</button>
			    </td>
				 <td style="width:15%">
                 </td>
			   </tr>
			  </table>
			</div>
			

</div> </div>
 

';
		

	$inc=1;
echo $testo;

?>
             
           
          

</div>