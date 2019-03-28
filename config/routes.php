<?php
return array(
	'camera' => 'camera/cameraOn/',
	'upload-camera' => 'camera/uploadCamera',
	'delete-photo' => 'camera/deletePhotoCamera',
	'profile/([a-zA-Z0-9._@]+)' => 'profile/view/$1',
	'profile' => 'profile/view/myprofile',
	'upload-image' => 'profile/uploadImage',

	'login' => 'login/login',
	'login/([a-zA-Z0-9._]+)/activekey/([a-z0-9._]+)' => 'login/getactivekey/$1/$2',
	'login/resetkey/([a-zA-Z0-9._]+)/([a-z0-9._]+)' => 'login/resetpassword/$1/$2',
	'logout' => 'login/logout',
	'wall/(\d+)' => 'wall/view/$1',
	'fbauth' => 'login/getFbCode',
	'.*' => 'wall/error404'
);
?>
