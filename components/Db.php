<?php

class Db
{

	public static $connection;

	public static function getConnection()
	{
		if (!self::$connection)
		{
			$paramsPath = ROOT . '/config/database.php';
			$params = include($paramsPath);
			try
			{
				self::$connection = new PDO("mysql:host=" . $params['host'] .";dbname=" . $params['dbname'], $params['user'], $params['password']);
				self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			}catch (PDOException $e)
			{
				echo "DB ERROR CONNECTION";
				exit(0);
			}
		}
		return self::$connection;
	}

	public static function getRows($query)
	{
		$result = self::$connection->prepare($query);
		$result->execute();
		$nRows = $result->rowCount();
		return $nRows;
	}

	public static function addNewUser($login, $pass, $name, $mail, $hash)
	{
		$sql = "INSERT INTO users (login, pass, name, email, hashmail, avatar) VALUES ('$login', '$pass', '$name', '$mail', '$hash', '/templates/avatar/guest.jpg')";
		self::$connection->exec($sql);
	}

	public static function isUserInDatabase($login)
	{
		$sth = self::$connection->prepare("SELECT * FROM users WHERE login LIKE '$login'");
			$sth->execute();
			$result = $sth->fetchAll();
			if (isset($result[0]))
				return $result[0];
			return false;
	}

	public static function changeValue($table, $where, $who, $set, $value)
	{
		$sth = self::$connection->prepare("UPDATE $table SET $set = '$value' WHERE $where = '$who'");
		$sth->execute();
	}

}