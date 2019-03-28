window.onload = function()
{
	var num = 1;
	var inter;
	var img1 = document.getElementsByClassName('img1')[0];
	var img2 = document.getElementsByClassName('img2')[0];
	var img3 = document.getElementsByClassName('img3')[0];
	setTimeout(start, 3000);


	function start()
	{
		num == 3 ? num = 0 : 0;
		switch (++num)
		{
		  case 1:
		    choice(1);
		    break;
		  case 2:
		    choice(2);
		    break;
		  case 3:
		    choice(3);
		    break;
		  default:
		    alert( 'error' );
		}
	}

	function choice(imgNum)
	{
		if (imgNum == 1)
			inter = setInterval(function() { action(img3, img1); }, 15);
		else if (imgNum == 2)
			inter = setInterval(function() { action(img1, img2); }, 15);
		else
			inter = setInterval(function() { action(img2, img3); }, 15);
	}

	function action(img_fst, img_scd)
	{
		if (img_scd.style.opacity == 1)
		{
			img_fst.style.opacity = 0;
			clearInterval(inter);
			setTimeout(start, 3000);
		}
		else
		{
			img_scd.style.opacity = +img_scd.style.opacity + 0.01;
			if (+img_fst.style.opacity > 0)
				img_fst.style.opacity = +img_fst.style.opacity - 0.01;
		}
	}


		function $($classname)
		{
			return document.getElementsByClassName($classname)[0];
		}

		document.onkeyup = function(e) // проверка на аккаунт
		{

			var xhr2 = new XMLHttpRequest();
 			xhr2.open("POST", "/login", true);
 			xhr2.onreadystatechange = function()
	 		{
	 			if (xhr2.readyState == 4)
 					if (xhr2.status == 200)
 					{
  						if (xhr2.responseText == 1 || xhr2.responseText == 11)
  						{
 								$('login1').style.backgroundColor = "#ff7279";
  						}
 						else
 						{
 							if ($('login1').value)
 								$('login1').style.backgroundColor = "#75ff9f";
 							else
 								$('login1').style.backgroundColor = "white";
 						}
  						if (xhr2.responseText == 2 || xhr2.responseText == 22)
  						{
 								$('pass1').style.backgroundColor = "#ff7279";
 								$('pass12').style.backgroundColor = "#ff7279";
  						}
 						else
 						{
 							if ($('pass1').value && $('pass12').value)
 							{
 								$('pass1').style.backgroundColor = "#75ff9f";
 								$('pass12').style.backgroundColor = "#75ff9f";
 							}
 							else
 							{
 								$('pass1').style.backgroundColor = "white";
 								$('pass12').style.backgroundColor = "white";
 							}
 						}
 						if (xhr2.responseText == 3)
 						{
 							$('name1').style.backgroundColor = "#ff7279";
 						}
 						else
 						{
 							if ($('name1').value)
 								$('name1').style.backgroundColor = "#75ff9f";
 							else
 								$('name1').style.backgroundColor = "white";
 						}
 						if (xhr2.responseText == 4)
 						{
 							$('mail1').style.backgroundColor = "#ff7279";
 						}
 						else
 						{
 							if ($('mail1').value)
 								$('mail1').style.backgroundColor = "#75ff9f";
 							else
 								$('mail1').style.backgroundColor = "white";
 						}
 					}
			}
			var formData = new FormData();
        	formData.append("checklogin_red", $('login1').value);
        	formData.append("checkpass1", $('pass1').value);
        	formData.append("checkpass2", $('pass12').value);
        	formData.append("checkname", $('name1').value);
        	formData.append("checkmail", $('mail1').value);
			xhr2.send(formData);
		}

		$('button2').onclick = function() // логин пароль
		{
			var xhr2 = new XMLHttpRequest();
 			xhr2.open("POST", "/login", true);
 			xhr2.onreadystatechange = function()
	 		{
	 			if (xhr2.readyState == 4 && xhr2.status == 200)
 				{
 					if (xhr2.responseText == 1)
 						$('error').innerHTML = "The one of fields is empty!";
 					else if (xhr2.responseText == 2)
 						$('error').innerHTML = "Incorrect login or password!";
 					else if (xhr2.responseText == 3)
 						$('error').innerHTML = "Current account doesn't activated! Check your email!.";
 					else 
 						location.href = '/wall/1';
 				}
			}
			var formData = new FormData();
			formData.append("button_log", true);
        	formData.append("checkuser", $('login').value);
        	formData.append("checkpass", $('pass').value);
			xhr2.send(formData);
		}

		$('button3').onclick = function() // меняет блоки между логин и регистрация
		{
			$('input-login').style.display = "none";
			$('register').style.display = "block";
			$('sign_in').style.height = "380px";
		}

		$('button_back').onclick = function() // меняет блоки между логин и регистрация
		{
			$('input-login').style.display = "block";
			$('register').style.display = "none";
			$('sign_in').style.height = "315px";
		}  

		$('button_reg').onclick = function()
		{
			$('error').innerHTML = "";
			var xhr2 = new XMLHttpRequest();
 			xhr2.open("POST", "/login", true);
 			xhr2.onreadystatechange = function()
	 		{
	 			if (xhr2.readyState == 4 && xhr2.status == 200)
 				{
 					//alert(xhr2.responseText);
 					var obj = JSON.parse(xhr2.responseText);
					if (xhr2.responseText == 0)
						$('error').innerHTML = "The one of the fields is empty";
					if (xhr2.responseText == 1)
						$('error').innerHTML = "This account is registered yet!";
					if (xhr2.responseText == 11)
						$('error').innerHTML = "The login can use only (A-Z 0-9 .)";
					if (xhr2.responseText == 2)
						$('error').innerHTML = "Input password don't the same!";
					if (xhr2.responseText == 22)
						$('error').innerHTML = "The password must contain at least 6 symbols and one letter or number";
					if (xhr2.responseText == 3)
						$('error').innerHTML = "Name and surname must have structure as <br> [Name Surname]";
					if (xhr2.responseText == 4)
						$('error').innerHTML = "Incorrect email adress";
					if (obj[0] == 5)
					{
						$('error').innerHTML = "Your account has been successfully created!";
						alert("To your email " + obj[2] + " was sent a link for verification an account \'"+ obj[1] +"\'.\n Follow the link for making activation one.");
						setTimeout(function(){location.href = '/login';}, 1000);
					}
 				}
			}
			var formData = new FormData();
			formData.append("button_reg", true);
        	formData.append("checklogin", $('login1').value);
        	formData.append("checkpass1", $('pass1').value);
        	formData.append("checkpass2", $('pass12').value);
        	formData.append("checkname", $('name1').value);
        	formData.append("checkmail", $('mail1').value);
			xhr2.send(formData);
		}

		$('button-send-mail-back').onclick = function()
		{
			$('recover-input-login').style.display = 'none';
			$('input-login').style.display = 'block';
			$('error').innerHTML = "Login not found";
		}

		$('forgot-but').onclick = function()
		{
			$('recover-input-login').style.display = 'block';
			$('input-login').style.display = 'none';
			var xhr2 = new XMLHttpRequest();
 			xhr2.open("POST", "/login", true);
 			xhr2.onreadystatechange = function()
	 		{
	 			if (xhr2.readyState == 4 && xhr2.status == 200)
 				{
 				}
 			}
 			var formData = new FormData();
			formData.append("send_recover_mail", true);
        	xhr2.send(formData);
		}

		$('button-send-mail').onclick = function()
		{
			$('error').innerHTML = "";
			var xhr2 = new XMLHttpRequest();
 			xhr2.open("POST", "/login", true);
 			xhr2.onreadystatechange = function()
	 		{
	 			if (xhr2.readyState == 4 && xhr2.status == 200)
 				{
 					if (xhr2.responseText == 1)
 					{
 						alert("We have sent to your mail adress a link for recovering your password. Check it out.");
 						$('recover-input-login').style.display = 'none';
						$('input-login').style.display = 'block';
 					}
 					else
 					{
 						$('error').innerHTML = "Login not found";
 					}
 				}
 			}
 			var formData = new FormData();
			formData.append("send_recover_mail", true);
			formData.append("input-login", $('recover-login').value);
        	xhr2.send(formData);
		}

		$('button-chg-pass').onclick = function()
		{
			$('error').innerHTML = "";
			var xhr2 = new XMLHttpRequest();
 			xhr2.open("POST", "/login", true);
 			xhr2.onreadystatechange = function()
	 		{
	 			if (xhr2.readyState == 4 && xhr2.status == 200)
 				{
 					if (xhr2.responseText == 1)
 					{
 						alert("The password was successfully changed.");
 						location.href = '/login';
 					}
 					else if (xhr2.responseText == 2)
 					{
 						$('error').innerHTML = "These passwords are not the same or empty";
 					}
 					else if (xhr2.responseText == 3)
 					{
 						$('error').innerHTML = "The password must contain at least 6 symbols and one letter or number";
 					}
 					else
 						location.href = '/login';
 				}
 			}
 			var formData = new FormData();
			formData.append("change_password", true);
			formData.append("pass1", $('newpass1').value);
			formData.append("pass2", $('newpass2').value);
			formData.append("urlname", window.location.pathname.slice(1).split('/')[2]);
			formData.append("urlcode", window.location.pathname.slice(1).split('/')[3]);
        	xhr2.send(formData);
		}

		$('cancel-chg-pass').onclick = function(){location.href = '/login';}

}