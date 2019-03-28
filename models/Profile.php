<?php

class Profile
{

	public $verifStatus = "";


	public function showUserStatus($params)
	{
		if (isset($_SESSION['user']) && Db::isUserInDatabase($_SESSION['user']))
			return "owner";  			/*проверка на профиль владельца для коррекции*/
		else if (Db::isUserInDatabase($params[0]))
			return "not-owner";
		else
			return "guest"; //временно не используется
	}

	public function changeProfile()
	{
		if (preg_match('/FB_/', $_SESSION['user']))
			echo json_encode(0);
		else if (empty($_POST['name']) || empty($_POST['mail']) || empty($_POST['user']))
			echo json_encode(1);
		else if (!preg_match("/^[A-Z0-9._]+$/i", $_POST['user']))
			echo json_encode(2);
		else if (Db::isUserInDatabase($_POST['user']) && $_POST['user'] !== $_SESSION['user'])
			echo json_encode(3);
		else if (!preg_match("/^[a-zA-Z]{2,} [a-zA-Z]{2,}$/",$_POST['name']))
			echo json_encode(4);
		else if (!preg_match('/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/', $_POST['mail']))
			echo json_encode(5);
		else
		{
			Db::changeValue('users', 'login', $_SESSION['user'], 'name', $_POST['name']);
			Db::changeValue('users', 'login', $_SESSION['user'], 'email', $_POST['mail']);
			Db::changeValue('users', 'login', $_SESSION['user'], 'login', $_POST['user']);
			$_SESSION['user'] = $_POST['user'];
			if ($_POST['checkbox'] == "true")
				Db::changeValue('users', 'login', $_SESSION['user'], 'checkedbox', 1);
			else	
				Db::changeValue('users', 'login', $_SESSION['user'], 'checkedbox', 0);
			echo json_encode(6);
		}
		exit();
	}

	public function isCheckedbox()
	{
		$sth = Db::$connection->prepare("SELECT * FROM users WHERE login = ?");
		$sth->execute([$_SESSION['user']]);
		$result = $sth->fetchAll();
		if ($result[0]['checkedbox'])
			return 1;
		else
			return 0;
	}

	public function deleteAccount()
	{
		$sth = Db::$connection->prepare("DELETE FROM comments WHERE which_id = '".$_POST['user_id']."'");
		$sth->execute();
		$sth = Db::$connection->prepare("DELETE FROM likes WHERE who_like = '".$_POST['user_id']."'");
		$sth->execute();

		$sth = Db::$connection->prepare("SELECT * FROM album WHERE user_id = '".$_POST['user_id']."'");
		$sth->execute();
		$result = $sth->fetchAll();
		foreach($result as $arr => $value)
			unlink(ROOT.$value['pic_way']);


		$sth = Db::$connection->prepare("DELETE FROM album WHERE user_id = '".$_POST['user_id']."'");
		$sth->execute();
		$sth = Db::$connection->prepare("DELETE FROM users WHERE id = '".$_POST['user_id']."'");
		$sth->execute();
		if (file_exists(ROOT."/templates/avatar/".$_POST['user_id'].".png"))
			unlink(ROOT."/templates/avatar/".$_POST['user_id'].".png");
		exit();
	}

	public function uploadPhoto()
	{
		$sth = Db::$connection->prepare("SELECT * FROM users WHERE login = '".$_SESSION['user']."'");
		$sth->execute();
		$result = $sth->fetchAll();
		$name_img = $result[0]['id'] . '.png';
		$send_img = '/templates/avatar/'.$name_img;
		$uploads_dir = ROOT.'/templates/avatar/'. $name_img;
		move_uploaded_file(($_FILES['avatar']['tmp_name']), $uploads_dir);
		Db::changeValue('users', 'login', $_SESSION['user'], 'avatar', $send_img);
		echo $name_img;
		exit();
	}
}

?>