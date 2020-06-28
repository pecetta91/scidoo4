<?php
//header('Access-Control-Allow-Origin: *');
require_once __DIR__ . '/../../config/connecti.php';
require_once __DIR__ . '/../../config/funzioni.php';

$IDoggetto = $_POST['IDoggetto'] ?? 0;
$tipo_oggetto = $_POST['tipo_oggetto'] ?? 0;

$allow = 'JPEG,PNG,PDF,DOC,XLS,PPS,PFX';

$testo = '
<div class="nav navbar_picker_flex" >
 	<div onclick="chiudi_picker()"><i class="fas fa-times icona_chiudi" ></i></div>
 	<div style="margin-top:5px;padding-right:10px">
 		<button class="button_salva_preventivo"  >Continua</button>

 	</div>
</div>

<div class="content" style="margin-top:0">
	<div id="dettagli_tab" style="padding-top:5px;">
	 <div id="upload-div" style="height: 400px;
    border: 2px dashed #888;
    overflow-y: auto;
    background: #f1f1f1;
    margin: 5px;
    border-radius: 5px;">
	 	<div>Clicca  per selezionare i documenti , massimo 10 MB</div>
	 		<br><div>Formati permessi:<br>' . $allow . '</div>

	</div>
	</div>
</div>';

echo $testo;

$template = preg_replace('/\s+/', ' ', trim('
<div class="dz-preview dz-file-preview">
<img data-dz-thumbnail />
<div class="dz-details">
	<div class="dz-filename"><span data-dz-name></span></div>
	<div class="dz-size" data-dz-size></div>
</div>
<div class="dz-progress"><div class="dz-upload" data-dz-uploadprogress></div></div>
<div class="dz-success-mark"><span><i class="fas fa-check"></i></span></div>
<div class="dz-error-mark"><span><i class="fas fa-ban"></i></span></div>
<div class="dz-error-message"><span data-dz-errormessage></span></div>
</div>'));

?>

<script>

	$("#upload-div").dropzone({
		url: baseurl+"config/scriptallegati/upload/post_allegati.php",
		params: {
			IDobj: "<?=$IDoggetto?>",
			tipoobj: "<?=$tipo_oggetto?>"
		},
		acceptedFiles: 'image/jpg,image/jpeg,image/png,application/msword,application/pdf,.pfx,application/vnd.ms-excel,application/vnd.ms-powerpoint',
		maxFileSize: 10,
		uploadprogress:function(file, progress, bytesent) {
			if (file.previewElement) {
				var progressElement = $(file.previewElement).find(".dz-upload");
				progressElement.css('width', progress + '%');
			}
		},
		success: function(file, response) {
			var elem = $(file.previewElement);
			elem.find('img').css('opacity', '0.3');
			elem.find(".dz-upload").hide('ease');
			elem.find(".dz-success-mark").show('ease');
		},
		error: function(file, errorMessage) {
			console.log(errorMessage);
			var elem = $(file.previewElement);
			elem.find('img').css('opacity', '0.3');
			elem.find(".dz-upload").hide('ease');
			elem.find(".dz-error-mark").show('ease');
			let msg = /errore?:\s*(.*)/.exec(errorMessage);
			if (msg) {
				alertify.error(msg[1]);
			}
		},
		previewTemplate: '<?php echo $template; ?>'
	});
</script>


<style>
	.dz-started .dz-message{
    display: none;
}

.dz-preview{
    display: inline-block;
    position: relative;
    width: 120px;
    margin: 10px;
}

.dz-preview img {
    border-radius: 10px;
    height: 120px;
}

.dz-preview:hover img {
    opacity: 0.3;
}

.dz-details {
    position: absolute;
    top: 0;
    left: 0;
    padding: 80px 0 0 10px;
}

.dz-progress {
    height: 20px;
    background-color: gray;
    width: 80%;
    border-radius: 3px;
    position: absolute;
    top: 50px;
    left: 10%;
}

.dz-upload {
    height: 20px;
    background-color: green;
    border-radius: 3px;
    width: 0;
    position: absolute;
    left: 0;

}
    .dz-success-mark i{margin-top: 7px; color:#35AD45;}
    .dz-error-mark i{margin-top: 7px; color:#A61B1D;}

.dz-success-mark,
.dz-error-mark {
    display: none;
    position: absolute;
    top: 20px;
    width: 100%;
    font-size: 25px;text-align: center;
}

.dz-success-mark span,
.dz-error-mark span {
    background-color: white;
    border-radius: 50%;
    height: 40px;
    width: 40px;
    display: block;
    line-height: 50px;margin: auto;
}

.dz-message{
    padding: 5px;
}

.dz-error-message {
    position: absolute;
    top: 0;
    left: 0;
}
    .dz-filename{font-size:11px;}


</style>
