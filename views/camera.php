<!DOCTYPE HTML>
<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link href="/templates/css/main.css" rel="stylesheet">
  <link href="/templates/css/camera.css" rel="stylesheet">
  <script src="/templates/js/camera.js"></script>


  <title>Camagru</title>
 </head>
 <body>
 	<?php require_once(ROOT . "/templates/block/headerblock.php");?>
 	<div id="loading"></div>
 	<div id="vid"><video id="video"></video><img id="uploadedphoto"><div id="vid-pic">
 		<?php require_once(ROOT . "/templates/block/pictures.php");?>
 	</div></div>
 	<div id="can"><canvas id="canvas"></canvas></div>
 	<br>
		<form action="" enctype="multipart/form-data" id="file-form" method="POST" style="text-align: center; display: none;">
		    <input style="margin-left: 72px" type="file" name="file-select"  id="file-select" accept="image/*"><br>
		    <button type="submit" id="upload-button">Загрузить фото</button>
		</form>
 	<div class="for_buttons">
	 	<button id="but">Make photo</button>
	 	<button id="but_repeat">Repeat</button>
	 	<button id="butsave">Save into album</button>
	 	<button id="butoption">Upload your own photo</button>
	 	<hr>
	 	<h2>Your photos</h2>
	 	<div id="album">
	 		<div id="inneralbum">
	 			<?php $camera->fillAlbum(); ?>
	 		</div>
	 		<button id="butdel">Delete</button><button id="butsetava">Set avatar</button>
	 	</div>
 	</div>

 	<?php require_once(ROOT . "/templates/block/footer.php");?>
 	</div>
 </body>
</html>