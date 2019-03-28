 <!DOCTYPE HTML>
<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link href="/templates/css/main.css" rel="stylesheet">
  <link href="/templates/css/wall.css" rel="stylesheet">
  <script src="/templates/js/wall.js"></script>
  <title>Camagru</title>
 </head>
 <body>
<?php require_once(ROOT . "/templates/block/headerblock.php");?>
<?php 

	$wall->wallPartOne();
	foreach($wall->album as $photo)
	{
		$sth2 = Db::$connection->prepare("SELECT * FROM users WHERE id = '".$photo['user_id']."'");
		$sth2->execute();
		$user = $sth2->fetchAll();
		$wall->counter++;
		if ($wall->counter <= ($params[0] * 5 - 5) || $wall->counter > ($params[0] * 5))
			continue;
?>
<div id="photoWrapper.<?php echo $photo['user_id']; ?>" class="photowrap">
	 <div id="foruserinfo">
	 	<div id="avatar" style="background-image: url(<?php echo $user[0]['avatar']; ?>);"></div>
	 	<a class="href" href="/profile/<?php echo $user[0]['login'];?>"><h1>@<?php echo $user[0]['login'] . ' (' . $user[0]['name'] . ')';?></h1></a>
	 	<p><?php echo $photo['date_time'];?></p>
	 </div>
	<div id="photo" style="background-image: url(<?php echo $photo['pic_way'] ?>);"></div>
	<div id="likewrapper">
		<div class="like" data-idphoto="<?php echo $photo['id'];?>" data-userlike="<?php echo Db::getRows("SELECT * FROM likes WHERE who_like = '$wall->session_id' AND photo_id = '".$photo['id']."'"); ?>"></div>
		<div class="numlikes" data-countlikes="<?php echo $countlikes = Db::getRows("SELECT * FROM likes WHERE photo_id = '".$photo['id']."'"); ?>"><?php echo $countlikes; ?></div>
		<?php if (isset($_SESSION['user']) && (Db::isUserInDatabase($_SESSION['user'])['admin'] == 1 || (isset($_SESSION['user']) && $photo['user_id'] === Db::isUserInDatabase($_SESSION['user'])['id']))): ?>
		<button class="delete" data-id="<?php echo $photo['id'];?>">Delete photo</button>
		<?php endif; ?>
	</div>
	<div class="comment<?php echo $photo['id'];?>" id="comment"></div>
	<input class="input_text" type="text" name="<?php echo $photo['id'];?>">
	<input class="submit_send" type="submit" name="<?php echo $photo['id'];?>" value="Send">
</div>
<?php } ?>

<?php if ($maxPage): ?>
	<div class='pagination' data-maxpage="<?php echo $maxPage; ?>" data-page="<?php echo $params[0]; ?>"><div style="<?php echo $display2; ?>" id='left'><?php echo 1; ?></div> <button style="<?php echo $display2; ?>" class="page-but page-back"><<</button> <div id='middle'><?php echo $params[0]; ?></div>  <button style="<?php echo $display; ?>" class="page-but page-forw">>></button> <div style="<?php echo $display; ?>" id='right'><?php echo $maxPage; ?></div></div>
<?php else: ?>
	<div class='pagination' style="visibility: hidden; margin-top: 500px"></div>
<?php endif; ?>

<?php require_once(ROOT . "/templates/block/footer.php");?>
</div> <!-- wrapper -->
 </body>
</html>