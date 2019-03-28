<?php


class Router
{
	private $routes;

	public function __construct()
	{
		$routesPath = ROOT.'/config/routes.php';
		$this->routes = include($routesPath);
	}

	private function getURI()
	{
		if (!empty($_SERVER['REQUEST_URI']))
			$uri = trim($_SERVER['REQUEST_URI'], '/');
		if (empty($uri))
			header('Location: /wall/1');
		$uri = strtok($uri, '?');
		return $uri;
	}

	private function clearSession()
	{
		if (isset($_SESSION['user']) && Db::getRows("SELECT * FROM users WHERE login = '".$_SESSION['user']."'") == 0)
			unset($_SESSION['user']);
	}

	public function run() // принимает управление от FC
	{
		$this->clearSession();
		$uri = $this->getURI();
		foreach ($this->routes as $key => $path)
			if (preg_match("~^$key$~", $uri))
			{
				$internalRoute = preg_replace("~^$key$~", $path, $uri); // 1 = то что ищем в 3 и меняем на 2

				$segments = explode('/', $internalRoute);
				$controllerName = array_shift($segments).'Controller';
				$controllerName = ucfirst($controllerName);
				$actionName = 'action' . ucfirst(array_shift($segments));
				$params = $segments;	
				$controllerFile = ROOT . '/controllers/' .$controllerName . '.php';


				if (!file_exists($controllerFile))
					exit("404 - Not object");
				include_once($controllerFile);
				$controllerObject = new $controllerName;
				$result = $controllerObject->$actionName($params);
				break ;
			}
	}
}

?>