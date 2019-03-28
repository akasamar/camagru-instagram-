<?php

require_once  ROOT.'/models/Camera.php';

class CameraController
{
	public function actionCameraOn()
	{
		if (!isset($_SESSION['user']))
			header("Location: /login");
		$camera = new Camera();
		require_once ROOT.'/views/camera.php';
	} 

	public function actionUploadCamera()
	{
		$postdata = file_get_contents("php://input");
		if (isset($postdata))
		{
			$album = Db::$connection->prepare("SELECT * FROM `album`");
			$album->execute();
			if (!$album->rowCount())
			{
				$sth = Db::$connection->prepare("INSERT INTO album (user_id, pic_way) VALUES ('-1', '')");
		   		$sth->execute();
			}
			$sth = Db::$connection->prepare("SELECT id FROM album ORDER BY id DESC LIMIT 1"); 
			$sth->execute();

			$result = $sth->fetchAll();
			$user_id = Db::isUserInDatabase($_SESSION['user'])['id'];
			$plus = (int)$result[0]['id'] + 1;
			$sth = Db::$connection->prepare("INSERT INTO album (user_id, pic_way) VALUES ('$user_id', 0)");
	   		$sth->execute();

	   		$sth = Db::$connection->prepare("SELECT * FROM album ORDER BY id DESC LIMIT 1"); 
			$sth->execute();
			$result = $sth->fetchAll();
			$name_img = "/templates/album/" . $result[0]['id'] . '.png';
			$sth = Db::$connection->prepare("UPDATE album SET pic_way = '$name_img' WHERE id = '".$result[0]['id']."'");
	   		$sth->execute();
		
		    $removeHeaders = substr($postdata, strpos($postdata, ",") + 1);
		    $image = str_replace(" ", "+", $removeHeaders);
		    $decode = base64_decode($image);
		    file_put_contents(ROOT . $name_img, $decode);
		    echo $name_img;
		}
		exit();
	} 

	public function actionDeletePhotoCamera()
	{
		$camera = new Camera();
		if (isset($_POST['delete']))
		{
			$img_link = preg_replace("~url\(\"/templates/album/(\d+)\.png\"\)~", "/templates/album/$1.png", $_POST['link-image']);

			$sth = Db::$connection->prepare("SELECT * FROM album WHERE pic_way = '$img_link'");
			$sth->execute();
			$result = $sth->fetchAll();



			$sth = Db::$connection->prepare("DELETE FROM likes WHERE photo_id = '".$result[0]['id']."'");
			$sth->execute();
			$sth = Db::$connection->prepare("DELETE FROM comments WHERE id_photo = '".$result[0]['id']."'");
			$sth->execute();

			$sth = Db::$connection->prepare("SELECT * FROM album WHERE id = '".$result[0]['id']."'");
			$sth->execute();
			$result2 = $sth->fetchAll();

			$sth = Db::$connection->prepare("SELECT * FROM users WHERE login = '".$_SESSION['user']."'");
			$sth->execute();
			$result3 = $sth->fetchAll();
			if ($result2[0]['pic_way'] == $result3[0]['avatar'])
			{
				$sth = Db::$connection->prepare("UPDATE users SET avatar = '/templates/avatar/guest.jpg' WHERE login = '".$_SESSION['user']."'");
				$sth->execute();
			}

			if (file_exists(ROOT.$result2[0]['pic_way']))
				unlink(ROOT.$result2[0]['pic_way']);
			$sth = Db::$connection->prepare("DELETE FROM album WHERE id = '".$result[0]['id']."'");
			$sth->execute();
		}
		if (isset($_POST['addavatar']))
		{
			$img_link = preg_replace("~url\(\"/templates/album/(\d+)\.png\"\)~", "/templates/album/$1.png", $_POST['link-image']);
			$sth = Db::$connection->prepare("UPDATE users SET avatar = '$img_link' WHERE login = '".$_SESSION['user']."'");
			$sth->execute();
		}
		if(!empty($_FILES['avatar']['name']))
		{
			$name_img = $camera->rand_hash() . '.png';
			$uploads_dir = ROOT.'/templates/album/'. $name_img;
			move_uploaded_file(($_FILES['avatar']['tmp_name']), $uploads_dir);
			echo $name_img;
		}
		exit();
	}
}