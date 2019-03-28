<?php
	$conn = new PDO("mysql:host=localhost;", "root", "111111");
	$result = $conn->prepare("show databases like 'db'");
	$result->execute();
	$res = $result->rowCount();

	if (!$res)
	{
		$result = $conn->prepare("CREATE DATABASE IF NOT EXISTS `db`");
		$result->execute();
		$conn = new PDO("mysql:host=localhost;dbname=db", "root", "111111");

		$result = $conn->prepare("CREATE TABLE `album` (
								  `id` int(11) NOT NULL,
								  `user_id` int(11) NOT NULL,
								  `date_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
								  `pic_way` varchar(255) NOT NULL
								) ENGINE=InnoDB DEFAULT CHARSET=utf8");
		$result->execute();

		$result = $conn->prepare("CREATE TABLE `comments` (
								  `id` int(11) NOT NULL,
								  `id_photo` int(11) NOT NULL,
								  `comment` text NOT NULL,
								  `which_id` int(11) NOT NULL,
								  `date_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
								  `which_name` varchar(255) NOT NULL
								) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
		$result->execute();

		$result = $conn->prepare("CREATE TABLE `likes` (
								  `id` int(11) NOT NULL,
								  `photo_id` int(11) NOT NULL,
								  `who_like` int(11) NOT NULL
								) ENGINE=InnoDB DEFAULT CHARSET=utf8");
		$result->execute();

		$result = $conn->prepare("CREATE TABLE `users` (
								  `id` int(12) NOT NULL,
								  `login` varchar(250) NOT NULL,
								  `pass` varchar(250) NOT NULL,
								  `name` varchar(255) NOT NULL,
								  `email` varchar(255) NOT NULL,
								  `act` int(11) NOT NULL DEFAULT '0',
								  `admin` int(11) NOT NULL DEFAULT '0',
								  `avatar` varchar(255) NOT NULL DEFAULT 'guest.jpg',
								  `hashmail` varchar(255) NOT NULL,
								  `checkedbox` int(11) NOT NULL DEFAULT '0'
								) ENGINE=InnoDB DEFAULT CHARSET=utf8");
		$result->execute();

		$result = $conn->prepare("ALTER TABLE `album`
  ADD PRIMARY KEY (`id`)");
		$result->execute();
		$result = $conn->prepare("ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`)");
		$result->execute();
		$result = $conn->prepare("ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`)");
		$result->execute();
		$result = $conn->prepare("ALTER TABLE `users`
  ADD PRIMARY KEY (`id`)");
		$result->execute();
		$result = $conn->prepare("ALTER TABLE `album`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0");
		$result->execute();
		$result = $conn->prepare("ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0");
		$result->execute();
		$result = $conn->prepare("ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0");
		$result->execute();
		$result = $conn->prepare("ALTER TABLE `users`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0");
		$result->execute();

	}

?>

