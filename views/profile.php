 <!DOCTYPE HTML>
<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link href="/templates/css/main.css" rel="stylesheet">
  <link href="/templates/css/profile.css" rel="stylesheet">
  <script src="/templates/js/prof.js"></script>
  <title>Camagru</title>
 </head>
 <body>
<?php require_once(ROOT . "/templates/block/headerblock.php");?>

<div class="space"></div>
<div class="prof_block">
<div class="head_block"><h1>Profile</h1></div>
	<?php if (Db::isUserInDatabase($params[0])): ?>
		<?php $user = Db::isUserInDatabase($params[0]);?>
		<?php if (isset($_SESSION['user']) && $_SESSION['user'] == $user['login']): ?>
			<h1>Your own profile</h1>
		<?php else: ?>
			<h1>User profile</h1>
		<?php endif; ?>	
			<h2><?php echo $user['name'] . ' <span style="color: blue;">@</span>' . $user['login'];?></h2>
			<img id="image" src="<?php echo $user['avatar']; ?>" title="Profile photo" alt="Profile photo">
		<?php if (isset($_SESSION['user']) && $_SESSION['user'] == $user['login']): ?>
			<div class="form_upload">
				<form action="" enctype="multipart/form-data" id="file-form" method="POST">
			  		<div id="upup">
					    <p id="progressdiv"><progress max="100" value="0" id="progress" style="display: none;"></progress></p>
					    <input type="file" name="file-select"  id="file-select" accept="image/*"><br>
					    <button class="button-change-photo" type="submit" id="upload-button">Изменить фото</button>
		  			</div>
				</form>
			</div>
				<hr>
				<div class="infouser">
					<h6><?php echo $profile->verifStatus; ?></h6>
					<ul>
						<li><b style="padding-right: 15px;">Логин:</b> 
							<input title="Изменный логин станет вашей новой учетной записью для входа на сайт" class="p1" type="text" value="<?php echo $user['login']; ?>"></li>
						<li><b style="padding-right: 35px;">Имя:</b><input class="p2" type="text" value="<?php echo $user['name']; ?>"></li>
						<li style="position: relative"><b style="padding-right: 12px;">E-mail:</b> 
							<input class="p3" type="text" value="<?php echo $user['email']; ?>"> </li>
						<li><b style="padding-right: 6px;">Статус:</b><?php if($user['admin']) echo " Администратор"; else echo " Пользователь";?></li>
					</ul>
					<div id="question">
						<p>Notify you to mail when you get a new comment?</p>
						<input class="p4" type="checkbox" name="same" <?php echo $checkbox; ?>>
					</div>
					<button class="button-change">Save changes</button>
					<button class="delete-account" type="submit" data-id="<?php echo $user['id'] ?>">Delete account</button>
				</div>
			<?php else: ?>
				<div class="infouser">
					<h6><?php echo $profile->verifStatus; ?></h6>
					<ul>
						<li><b style="padding-right: 30px;">Ник:</b><?php echo $user['login'];?></li>
						<li><b style="padding-right: 29px;">Имя:</b><?php echo $user['name'];?></li>
						<li style="position: relative"><b style="padding-right: 12px;">E-mail:</b><?php echo $user['email']; ?></li>
						<li><b>Статус:</b><?php if($user['admin']) echo " Администратор"; else echo " Пользователь";?></li>
					</ul>
				</div>
			<?php endif; ?>	
	<?php else: ?>
		<h2>Пользователь с такой учетной записью не найден :(</h2>
	<?php endif; ?>	
</div>
<?php require_once(ROOT . "/templates/block/footer.php");?>
</div> <!-- wrapper -->
 </body>
</html>

<!-- 
http://www.php.su/phphttp/?uploads
https://ruseller.com/lessons.php?rub=32&id=2876 -->