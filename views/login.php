<!DOCTYPE HTML>
<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <link href="/templates/css/main.css" rel="stylesheet">
  <link href="/templates/css/reg.css" rel="stylesheet">
  <script src="/templates/js/reg.js"></script>


  <title>Camagru</title>
 </head>
 <body>
 		<?php require_once(ROOT . "/templates/block/headerblock.php");?>
 		<div class="content-head">Share photos with your friends using our service Camagru!</div>
 			<div class="log_in">
	 				<div class="pic-iphone">
	 					<div class="img-white"></div>
	 					<div class="img1"></div>
	 					<div class="img2"></div>
	 					<div class="img3"></div>
	 				</div>
	 			<div class="sign_in">
	 					<div class="error"><?php if (!empty($active_message)) echo $active_message; ?></div>
	 					<div class="recover-pass" <?php echo $this->_block; ?>>
	 						<input class="newpass1" type="password" placeholder="Input new password">
		 					<input class="newpass2" type="password" placeholder="Repeat new password">
		 					<button class="button-chg-pass">Change password</button>
		 					<button class="cancel-chg-pass">Cancel</button>
	 					</div>
	 					<div class="recover-input-login" <?php echo $this->_none; ?>>
	 						<input class="recover-login" type="text" placeholder="Input your login">
		 					<button class="button-send-mail">Recover password</button>
		 					<button class="button-send-mail-back">Back</button>
	 					</div>
		 				<div class="input-login" <?php echo $this->_none; ?>>
		 					<input class="login" type="text" placeholder="Input your login">
		 					<input class="pass" type="password" placeholder="Input your password">
		 					<button class="button2">Entrence</button>
		 					<a target='_blank'href="<?=$this->_fbLink?>"><button class="button4">Facebook account</button></a>
		 					<button class="button3">Registration</button>
		 					<button class="forgot-but">Forgot password</button>
		 				</div>
		 				<div class="register" <?php echo $this->_none; ?>>
		 					<input maxlength="20" class="login1" type="text" placeholder="Input your login">
		 					<input class="pass1" type="password" placeholder="Input your password">
		 					<input class="pass12" type="password" placeholder="Repeat your password">
		 					<input maxlength="30" class="name1" type="text" placeholder="Input your name and surname">
		 					<input maxlength="35" class="mail1" type="text" placeholder="Input your email adress">
		 					<button class="button_reg">Зарегистрироваться</button>
		 					<button class="button_back">Назад</button>
		 				</div>
 				</div>
 			</div>
 		<?php require_once(ROOT . "/templates/block/footer.php");?>
 	</div>
 </body>
</html>