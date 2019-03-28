<?php
class Wall
{
	public $album;
	public $counter = 0;
	public $session_id;
	private  function getClean($value = "")
	{
		$value = trim($value);
		$value = stripslashes($value);
		$value = htmlspecialchars($value);
		return $value;
	}

	public function sendMessage()
	{
		$_POST['comment'] = $this->getClean($_POST['comment']);
		$sql = "INSERT INTO comments (id_photo, comment, which_id, which_name) VALUES ('".$_POST['photoid']."', '".$_POST['comment']."', '".Db::isUserInDatabase($_SESSION['user'])['id']."', '".$_SESSION['user']."')";
		Db::$connection->exec($sql);

		$user_id = Db::isUserInDatabase($_SESSION['user'])['id'];
		$sth = Db::$connection->prepare("SELECT * FROM comments WHERE which_id = $user_id ORDER BY id DESC LIMIT 1");
		$sth->execute();
		$result = $sth->fetchAll();
		echo $result[0]['id'];

		$sth = Db::$connection->prepare("SELECT * FROM album WHERE id = '".$_POST['photoid']."'");
		$sth->execute();
		$result = $sth->fetchAll();

		$sth = Db::$connection->prepare("SELECT * FROM users WHERE id = '".$result[0]['user_id']."'");
		$sth->execute();
		$result = $sth->fetchAll();
		if ($result[0]['checkedbox'] && $result[0]['login'] !== $_SESSION['user'])
			mail($result[0]['email'], "You have got a new comment from" . $_SESSION['user'] . " onCamagru", "The user [" . $_SESSION['user'] . "] wrote a new comment to your photo.");

		exit();
	}

	public function deleteComment()
	{
		$sth = Db::$connection->prepare("DELETE FROM comments WHERE id = '".$_POST['delete_comment']."'");
		$sth->execute();
		exit(); // мб
	}

	public function deletePhotoById()
	{
		$sth = Db::$connection->prepare("DELETE FROM likes WHERE photo_id = '".$_POST['delete_photo_id']."'");
		$sth->execute();
		$sth = Db::$connection->prepare("DELETE FROM comments WHERE id_photo = '".$_POST['delete_photo_id']."'");
		$sth->execute();

		$sth = Db::$connection->prepare("SELECT * FROM album WHERE id = '".$_POST['delete_photo_id']."'");
		$sth->execute();
		$result = $sth->fetchAll();

		$sth = Db::$connection->prepare("SELECT * FROM users WHERE login = '".$_SESSION['user']."'");
		$sth->execute();
		$result2 = $sth->fetchAll();
		if ($result[0]['pic_way'] == $result2[0]['avatar'])
		{
			$sth = Db::$connection->prepare("UPDATE users SET avatar = '/templates/avatar/guest.jpg' WHERE login = '".$_SESSION['user']."'");
			$sth->execute();
		}

		if (file_exists(ROOT.$result[0]['pic_way']))
			unlink(ROOT.$result[0]['pic_way']);
		$sth = Db::$connection->prepare("DELETE FROM album WHERE id = '".$_POST['delete_photo_id']."'");
		$sth->execute();
		exit();
	}

	public function addLike()
	{
		$user_id = Db::isUserInDatabase($_SESSION['user'])['id'];

		if ($_POST['is_like'] == 0)
		{
			$sth = Db::$connection->prepare("INSERT INTO likes (photo_id, who_like) VALUES ('".$_POST['photo_id']."','$user_id')");
			$sth->execute();
		}
		else
		{
			$sth = Db::$connection->prepare("DELETE FROM likes WHERE who_like = '$user_id' AND photo_id = '".$_POST['photo_id']."'");
			$sth->execute();
		}
		echo Db::getRows("SELECT * FROM likes WHERE photo_id = '".$_POST['photo_id']."'");
		exit();
	}

	public function fillCommentsOnLoading()
	{
		$sth = Db::$connection->prepare("SELECT * FROM comments");
		$sth->execute();
		$result = $sth->fetchAll();
		foreach ($result as $arr => &$value)
		{
			$sth = Db::$connection->prepare("SELECT * FROM users WHERE id LIKE '".$value['which_id']."'");
			$sth->execute();
			$user = $sth->fetchAll();
			if ($value['which_name'] !== $user[0]['login'])
			{
				$sth = Db::$connection->prepare("UPDATE comments SET which_name = '".$user[0]['login']."' WHERE id = '".$value['id']."'");
				$sth->execute();
			}
			$value['which_name'] =  $user[0]['login'];
			isset($_SESSION['user']) ? $value['session'] = $_SESSION['user'] : $value['session'] = -2;
			isset($_SESSION['user']) ? $value['session_id_user'] = Db::isUserInDatabase($_SESSION['user'])['id'] : $value['session_id_user'] = -2;
			isset($_SESSION['user']) ? $value['admin'] = Db::isUserInDatabase($_SESSION['user'])['admin'] : $value['admin'] = 0;
		}
		echo json_encode($result, JSON_FORCE_OBJECT);
		exit();
	}

	public function getCountPhoto()
	{
		$countPhoto = Db::getRows("SELECT * FROM album WHERE user_id != -1");
		return $countPhoto;
	}

	public function wallPartOne()
	{
		isset($_SESSION['user']) ? $this->session_id = Db::isUserInDatabase($_SESSION['user'])['id'] : $this->session_id = -1;
		$sth = Db::$connection->prepare("SELECT * FROM album WHERE user_id <> -1 ORDER BY date_time DESC");
		$sth->execute();
		$this->album = $sth->fetchAll();
	}

	public function checkSession()
	{
		if (isset($_SESSION['user']))
			echo 1;
		else
			echo 0;
		exit();
	}
}
?>