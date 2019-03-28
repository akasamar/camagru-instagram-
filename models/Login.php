<?php
class Login
{
	private  function getClean($value = "")
	{
		$value = trim($value);
		$value = stripslashes($value);
		$value = htmlspecialchars($value);
		return $value;
	}

	private  function rand_hash($count = 10)
	{	
		$outstr = '';
		$i = -1;
		$str = "abcdefghijklmnopqrstuvwxyz1234567890";
		while (++$i < $count)
			$outstr .= $str{rand(0, 35)};
		return $outstr;
	}

	public  function makeRedField()
	{

		$_POST['checklogin_red'] = $this->getClean($_POST['checklogin_red']);
		$_POST['checkpass1'] = $this->getClean($_POST['checkpass1']);
		$_POST['checkpass2'] = $this->getClean($_POST['checkpass2']);
		$_POST['checkname'] = $this->getClean($_POST['checkname']);
		$_POST['checkmail'] = $this->getClean($_POST['checkmail']);

		$count = Db::getRows("SELECT * FROM `users` WHERE `login` = '".$_POST['checklogin_red']."'");
		if ($count)
			echo json_encode(1);
		else if (!preg_match("/^[A-Z0-9.]+$/i", $_POST['checklogin_red']) && $_POST['checklogin_red'])
			echo json_encode(11);
		else if ($_POST['checkpass1'] !== $_POST['checkpass2'] && $_POST['checkpass1'] && $_POST['checkpass2'])
			echo json_encode(2);
		else if (!preg_match("/^(?=.*?[a-z])(?=.*?[0-9]).{6,}$/", $_POST['checkpass1']) && $_POST['checkpass1'] && $_POST['checkpass2'])
			echo json_encode(22);
		else if (!preg_match("/^[a-zA-Z]{2,} [a-zA-Z]{2,}$/",$_POST['checkname']) && $_POST['checkname'])
			echo json_encode(3);
		else if (!preg_match('/^([a-zA-Z0-9_-]+\.)*[a-zA-Z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/', $_POST['checkmail']) && $_POST['checkmail'])
			echo json_encode(4);
		exit();
	}

	public  function pressRegButton()
	{
		$count = Db::getRows("SELECT * FROM `users` WHERE `login` = '".$_POST['checklogin']."'");

		if ($count)
			echo json_encode(1);
		else if (!preg_match("/^[A-Z0-9.]+$/i", $_POST['checklogin']))
			echo json_encode(11);
		else if (empty($_POST['checklogin']) || empty($_POST['checkpass1']) || empty($_POST['checkpass2']) || empty($_POST['checkname']) || empty($_POST['checkmail']))
			echo json_encode(0);
		else if ($_POST['checkpass1'] !== $_POST['checkpass2'])
			echo json_encode(2);
		// (?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}
		else if (!preg_match("/^(?=.*?[a-z])(?=.*?[0-9]).{6,}$/", $_POST['checkpass1']))
			echo json_encode(22);
		else if (!preg_match("/^[a-zA-Z]{2,} [a-zA-Z]{2,}$/",$_POST['checkname']))
			echo json_encode(3);
		else if (!preg_match('/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/', $_POST['checkmail']))
			echo json_encode(4);
		else if (empty($_POST['checklogin']) || empty($_POST['checkpass1']) || empty($_POST['checkpass2']) || empty($_POST['checkname']) || empty($_POST['checkmail']))
			echo json_encode(0);
		else
		{
			// $_SESSION['user'] = $_POST['checklogin'];
			$hashmail = $this->rand_hash();
			$whirlpool = hash('whirlpool', $_POST['checkpass1']);
			Db::addNewUser($_POST['checklogin'], $whirlpool, $_POST['checkname'], $_POST['checkmail'], $hashmail);
			$arr = [5, $_POST['checklogin'], $_POST['checkmail']];
			echo json_encode($arr, JSON_FORCE_OBJECT);
			mail($_POST['checkmail'], "Activation Key for " . $_POST['checklogin'] . " onCamagru", "http://" . $_SERVER['HTTP_HOST'] . "/login/" . $_POST['checklogin'] . "/activekey/" . $hashmail);
		}
		exit(); 
	}

	public  function pressLogButton()
	{
		$_POST['checkuser'] = $this->getClean($_POST['checkuser']);
		$_POST['checkpass'] = $this->getClean($_POST['checkpass']);

		if (!strlen($_POST['checkuser']) || !strlen($_POST['checkpass']))
		{
			echo json_encode(1);
		}
		else if (Db::getRows("SELECT * FROM users WHERE login LIKE '".$_POST['checkuser']."'"))
		{
			$sth = Db::$connection->prepare("SELECT * FROM users WHERE login LIKE '".$_POST['checkuser']."'");
			$sth->execute();
			$result = $sth->fetchAll();
			if ($_POST['checkuser'] === $result[0]['login'] && hash('whirlpool', $_POST['checkpass']) === $result[0]['pass'])
				if ($result[0]['act'] == 0)
					echo json_encode(3); // не активирован акк
				else
					$_SESSION['user'] = $_POST['checkuser'];
			else
				echo json_encode(2);
		}
		else
			echo json_encode(2); // неверный пароль
        	exit();
	}

	public function isActiveAccount($params)
	{
		$sth = Db::$connection->prepare("SELECT * FROM users WHERE id = '".$params[0]."'");
		$sth->execute();
		$result = $sth->fetchAll();
		if (Db::getRows("SELECT * FROM users WHERE login = '".$params[0]."'"))
		{
			$sth = Db::$connection->prepare("SELECT * FROM users WHERE login = '".$params[0]."'");
			$sth->execute();
			$result = $sth->fetchAll();
			if ($result[0]['act'] == 1)
				return "The account is activated yet!";
			else
			{
				if ($result[0]['hashmail'] === $params[1])
				{
					$sth = Db::$connection->prepare("UPDATE users SET act = '1' WHERE login = '".$params[0]."'");
					$sth->execute();
					$rand = $this->rand_hash();
					$sth = Db::$connection->prepare("UPDATE users SET hashmail = '$rand' WHERE login = '".$params[0]."'");
					$sth->execute();
					return "The account was successfully activated!";
				}
				else
					return "Invalid activation code!";
			}
		}
		else 
			header('Location: /login');
	}

	public function pressForgotPassword()
	{
		$_POST['input-login'] = $this->getClean($_POST['input-login']);
		if (Db::getRows("SELECT * FROM users WHERE login = '".$_POST['input-login']."'"))
		{
			$sth = Db::$connection->prepare("SELECT * FROM users WHERE login = '".$_POST['input-login']."'");
			$sth->execute();
			$result = $sth->fetchAll();
			mail($result[0]['email'], "Recover password for " . $_POST['input-login'] . " on Camagru", "http://" . $_SERVER['HTTP_HOST'] . "/login/resetkey/" . $_POST['input-login'] . "/" . $result[0]['hashmail']);
			echo '1';
		}
		exit();
	}

	public function keyValidation($params)
	{
		if (Db::getRows("SELECT * FROM users WHERE login = '".$params[0]."' AND hashmail = '".$params[1]."'"))
			return 1;
		else
			header('Location: /login');
	}

	public function changePassword() //whirlpoolneed
	{
		$_POST['pass1'] = $this->getClean($_POST['pass1']);
		$_POST['pass2'] = $this->getClean($_POST['pass2']);

		if (Db::getRows("SELECT * FROM users WHERE login = '".$_POST['urlname']."' AND hashmail = '".$_POST['urlcode']."'"))
		{
			if ($_POST['pass1'] && $_POST['pass2'] && $_POST['pass1'] === $_POST['pass2'])
			{
				if (preg_match("/^(?=.*?[a-z])(?=.*?[0-9]).{6,}$/", $_POST['pass1']))
				{
				$whirlpool = hash('whirlpool', $_POST['pass1']);
				$sth = Db::$connection->prepare("UPDATE users SET pass = '$whirlpool' WHERE login = '".$_POST['urlname']."'");
				$sth->execute();
				$rand = $this->rand_hash();
				$sth = Db::$connection->prepare("UPDATE users SET hashmail = '$rand' WHERE login = '".$_POST['urlname']."'");
				$sth->execute();
				echo '1'; //okay
				}
				else
					echo '3'; //пароль меньше 6 сиимволов или не имеет
			}
			else
				echo '2'; //wrong password
		}
		else
			echo '4'; //cheating
		exit();
	}

	public function facebookActions()
	{
		if (!$_GET['code'])
			exit('error code'); // ссылка на ошибку

		$token = json_decode(file_get_contents('https://graph.facebook.com/v3.2/oauth/access_token?client_id='.ID.'&redirect_uri='.URL.'&client_secret='.SECRET.'&code='. $_GET['code']), true);

		if (!$token)
			exit('empty token');

		$data = json_decode(file_get_contents('https://graph.facebook.com/v3.2/me?client_id='.ID.'&redirect_uri='.URL.'&client_secret='.SECRET.'&code='. $_GET['code'] . '&access_token=' . $token['access_token'] . '&fields=name,email'), true);

		if (!$data)
			exit('empty token');
		if (!Db::getRows("SELECT * FROM users WHERE email = '".$data['email']."'"))
		{
			Db::addNewUser('FB_' . explode('@', $data['email'])[0], 000, $data['name'], $data['email'], 000);
			$sth = Db::$connection->prepare("UPDATE users SET checkedbox = '1' WHERE email = '".$data['email']."'");
			$sth->execute();
		}
		$_SESSION['user'] = 'FB_' . explode('@', $data['email'])[0];
		header('Location: /wall/1');
	}
}
?>