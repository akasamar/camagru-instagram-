<?php

class Camera
{
	public function fillAlbum()
	{
		$user_id = Db::isUserInDatabase($_SESSION['user'])['id'];
 		$sth = Db::$connection->prepare("SELECT * FROM album WHERE user_id = $user_id ORDER BY id DESC");
		$sth->execute();
		$result = $sth->fetchAll();
		foreach ($result as $arr) 
			echo "<div class=\"photo\" style=\"background-image: url('" . $arr['pic_way'] . "');\"></div>";
	}

	public function rand_hash($count = 10)
	{	
		$outstr = '';
		$i = -1;
		$str = "abcdefghijklmnopqrstuvwxyz1234567890";
		while (++$i < $count)
			$outstr .= $str{rand(0, 35)};
		return $outstr;
	}
}