<?php

include_once ROOT . '/models/Wall.php';

class WallController
{
	public function actionView($params)
	{
		$wall = new Wall();
		$maxPage = ceil($wall->getCountPhoto() / 5);
			if ($wall->getCountPhoto() > 0 && $params[0] == 0)
				header ('Location: /wall/1');
			if ($params[0] > $maxPage)
				header ("Location: /wall/$maxPage");

		$params[0] != $maxPage ? $display = 'display: inline-block;' : $display = 'display: none;';
		$params[0] != 1 ? $display2 = 'display: inline-block;' : $display2 = 'display: none;'; 

		if(isset($_SESSION['user']) && isset($_POST['sending']))
			$wall->sendMessage();
		if (isset($_POST['delete_comment']))
			$wall->deleteComment();
		if (isset($_POST['delete_photo_id']))
			$wall->deletePhotoById();
		if(isset($_SESSION['user']) && isset($_POST['add_like']))
			$wall->addLike();
		if (isset($_POST['start_check_img']))
			$wall->fillCommentsOnLoading();
		if (isset($_POST['check_session']))
			$wall->checkSession();
		require_once ROOT . '/views/wall.php';
	}

	public function actionError404($params)
	{
		require_once ROOT . '/views/error.php';
	}
}
?>
