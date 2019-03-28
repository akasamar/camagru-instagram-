<?php

include_once ROOT . '/models/Profile.php';

class ProfileController
{
	public function actionView($params)
	{
			$profile = new Profile();
			$user_status = $profile->showUserStatus($params);

			if (isset($_SESSION['user']) && $profile->isCheckedbox())
				$checkbox = 'checked';
			else
				$checkbox = '';
			require_once ROOT . '/views/profile.php';
	}


	public function actionUploadImage()
	{
		$profile = new Profile();
		if (isset($_POST['button_click']))
			$profile->changeProfile();
		if (isset($_POST['delete_account']))
			$profile->deleteAccount();
		if(!empty($_FILES['avatar']['name']))
			$profile->uploadPhoto();
	}
}

?>

