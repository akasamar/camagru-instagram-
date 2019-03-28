<?php

include_once ROOT . '/models/Login.php';

class LoginController
{
	private $_login;
	private $_block;
	private $_none;
	private $_fbLink;

	function __construct() {
		$this->_login = new Login();
		$this->_block = "";
		$this->_none = "";
		$this->_fbLink = "https://www.facebook.com/v3.2/dialog/oauth?client_id=".ID."&redirect_uri=".URL."&response_type&scope=email";
	}

	public function actionLogin($params)
	{	
		if (isset($_SESSION['user']))
			header('Location: /wall/1');
		if (isset($_POST['checklogin_red']))
			$this->_login->makeRedField();
		if (isset($_POST['button_reg']))
			$this->_login->pressRegButton();
		if (isset($_POST['button_log']))
			$this->_login->pressLogButton();
		if (isset($_POST['send_recover_mail']))
			$this->_login->pressForgotPassword();
		if (isset($_POST['change_password']))
			$this->_login->changePassword();
		require_once ROOT . '/views/login.php';
	}

	public function actionGetactivekey($params)
	{
		$active_message = $this->_login->isActiveAccount($params);
		require_once ROOT . '/views/login.php';
	}

	public function actionLogout($params)
	{
		if (isset($_SESSION['user']))
			unset($_SESSION['user']);
		header('Location: /login');
	}

	public function actionResetpassword($params)
	{
		if ($this->_login->keyValidation($params))
		{
			$this->_block = "style=\"display: block\"";
			$this->_none = "style=\"display: none\"";
		}
		require_once ROOT . '/views/login.php';
	}

	public function actionGetFbCode($params)
	{
		$this->_login->facebookActions();
	}
}

?>