window.onload = function()
{
	function $(id)
	{
		return document.getElementById(id);
	}

////////////////////////////////Заполнение комментарии в начале загрузки страницы/////////////

var active_session;

(function(){
	var xht = new XMLHttpRequest();
	var formData = new FormData();
	xht.open("POST", "/wall/1", true);
	xht.onreadystatechange = function() 
	{
		if (this.readyState == 4 && this.status == 200) 
		{
			alert(xht.responseText);
			if (xht.responseText == 1)
				active_session = 1;
			else
				active_session = -2;
		}
	}
	formData.append('check_session', true);
	xht.send(formData);
})();

(function()
{
    var xht = new XMLHttpRequest();
	var formData = new FormData();
	xht.open("POST", "/wall/1", true);
	xht.onreadystatechange = function() 
	{
		if (this.readyState == 4 && this.status == 200) 
		{
			try {var obj = JSON.parse(xht.responseText);}catch(e){return;}
			var count = Object.keys(obj).length;
			var divComments = document.querySelectorAll("div");
			if (count)
	 			for (var i = 0; i < count; i++)
	 			{
	 				var idphoto = "comment" + obj[i]['id_photo'];
				  	for (var j = 0; j < divComments.length ; j++) 
				    	if (divComments[j].matches("#comment") && divComments[j].className === idphoto) 
				     	{	
							var mainblock = document.createElement('div');
							mainblock.style.width = "630px";
							mainblock.style.borderBottom = "1px solid #734b4bad"; 
							mainblock.style.position = "relative";
							document.getElementsByClassName(idphoto)[0].appendChild(mainblock);

				     		var divelem = document.createElement('div');
				     		divelem.style.display = "inline-block";
				     		divelem.style.height = "35px";
				     		divelem.innerHTML =  '<b>' + obj[i]['which_name'] + '</b>' + ': ' + obj[i]['comment'];
				     		mainblock.appendChild(divelem);

				     		var divdata = document.createElement('div');
				     		divdata.style.position = "absolute";
				     		divdata.style.left = 0;
				     		divdata.style.top = "22px";
				     		divdata.style.fontSize = "12px";
				     		divdata.style.color = "#a6b1a6";
				     		divdata.innerHTML = obj[i]['date_time'];
				     		mainblock.appendChild(divdata);


				   			//active_session = obj[i]['session'];

				     		if (obj[i]['admin'] == 1 || obj[i]['session'] === obj[i]['which_name'] 
				     			|| obj[i]['session_id_user'] === mainblock.parentNode.parentNode.id.split('.')[1]) 
				     		{
					     		var deleteBut = document.createElement('button');
					 			deleteBut.style.float = "right";
					 			deleteBut.style.verticalAlign = "top";

					 			deleteBut.style.height = "24px";
					 			deleteBut.style.borderRadius = "8px";
					 			deleteBut.style.margin = "6px 0px 0 0";
					 			deleteBut.style.backgroundColor = "#dbdcde";

					 			deleteBut.innerHTML = "delete";
					 			deleteBut.setAttribute("data-id", obj[i]['id']);
								mainblock.appendChild(deleteBut);
								deleteBut.onclick = function(){deleteComment(this);};
							}
				     	}
	 			}
		}
	};
	formData.append('start_check_img', true);
	xht.send(formData);
})();

function deleteFromDb(elem) // функция удаления фотографии
{
	elem.parentNode.parentNode.remove();
	var xht = new XMLHttpRequest();
	var formData = new FormData();
	xht.open("POST", "/wall/1", true);

	formData.append('delete_photo_id', elem.dataset.id);
	xht.send(formData);
}

function deleteComment(elem)
{
	elem.parentNode.remove();
	var xht = new XMLHttpRequest();
	var formData = new FormData();
	xht.open("POST", "/wall/1", true);

	formData.append('delete_comment', elem.dataset.id);
	xht.send(formData);
}

    //////////////////отправка сообщение и сохранение в базе//////////////////

	var buttonItems = document.querySelectorAll('.submit_send');

	for (var i = 0; i < buttonItems.length; i++) 
	{
		var button = buttonItems[i];
	  	button.onclick =  function(){send_message(this.name)};
	}

	function send_message(name)
	{
		if (active_session == -2)
		{
			alert("You are not log in your account!");
			return false;
		}
		var comments = document.querySelectorAll('#comment');
		var textItems = document.querySelectorAll('.input_text');
			for (var i = 0; i < textItems.length; i++) 
			{
				if (textItems[i].name == name && textItems[i].value.length)
				{
					if (textItems[i].value.length > 50)
					{
						alert("The input text cannot be more than 50 characters.");
						textItems[i].value = "";
						return;
					}

					var mainblock = document.createElement('div');
					mainblock.style.width = "630px";
					mainblock.style.borderBottom = "1px solid #734b4bad";
					document.getElementsByClassName('comment'+name)[0].appendChild(mainblock);

					var newP = document.createElement('div');
					newP.style.height = "35px";
					var b = document.createElement('div');
					b.innerHTML = "<b>*You</b>: ";
					newP.style.display = "inline-block";
					b.style.display = "inline-block";
					var test = document.createTextNode(textItems[i].value);
					newP.appendChild(b);
					newP.appendChild(test);
					mainblock.appendChild(newP);

					var xht = new XMLHttpRequest();
					xht.onreadystatechange = function() 
					{
				 		if (this.readyState == 4 && this.status == 200) 
				 		{
				 			var deleteBut = document.createElement('button');
				 			deleteBut.style.float = "right";
				 			deleteBut.style.verticalAlign = "top";
			 				deleteBut.style.height = "24px";
				 			deleteBut.style.borderRadius = "8px";
				 			deleteBut.style.margin = "6px 0px 0 0";
				 			deleteBut.style.backgroundColor = "#dbdcde";
				 			deleteBut.innerHTML = "delete";
				 			deleteBut.setAttribute("data-id", xht.responseText);
							mainblock.appendChild(deleteBut);
							deleteBut.onclick = function(){deleteComment(this);}; 
				 			
				 		}
					};
					var formData = new FormData();
					xht.open("POST", "/wall/1", true);
					formData.append('sending', true);
					formData.append('comment', textItems[i].value);
					formData.append('photoid', name);
					textItems[i].value = "";
					xht.send(formData);
				}
			}
	}
////////////////////////Кнопка удаление фотографии//////////////////
	var elements = document.getElementsByClassName('delete');
	for (var i = 0; i < elements.length; i++)
		elements[i].addEventListener('click', function(){deleteFromDb(this);});

//////////////////изменить изображение при смене лайка при загрузке///////////////
	var userlike = document.getElementsByClassName('like');
	for (var i = 0; i < userlike.length; i++)
	{
		if (userlike[i].dataset.userlike > 0)
		{
			userlike[i].setAttribute("data-mylike", 0);
			userlike[i].style.backgroundImage = "url(\"/templates/img/hearton.png\")";
		}
		else
			userlike[i].setAttribute("data-mylike", 1);
	}

///// клик лайка //////////////////////////////////

	var userlikeclk = document.getElementsByClassName('like');

	for (var i = 0; i < userlikeclk.length; i++)
		userlikeclk[i].onclick = function()
		{
			if (active_session == -2)
			{
				alert("You are not log in your account!");
				return false;
			}
			if (this.dataset.mylike > 0)
			{
				this.dataset.mylike = 0;
				this.style.backgroundImage = "url(\"/templates/img/hearton.png\")";
			}
			else
			{
				this.dataset.mylike = 1;
				this.style.backgroundImage = "url(\"/templates/img/heartoff.png\")";
			}
			addLike(this.dataset.mylike, this.dataset.idphoto, this.nextElementSibling);
		}

		function addLike(islike, photoid, elem_for_count)
		{
			var xht = new XMLHttpRequest();
			var formData = new FormData();
			xht.open("POST", "/wall/1", true);
			xht.onreadystatechange = function() 
			{
		 		if (this.readyState == 4 && this.status == 200) 
		 		{
		 			elem_for_count.innerHTML = +xht.responseText;
		 		}
		 	}
			formData.append('add_like', true);
			formData.append('is_like', islike);
			formData.append('photo_id', photoid);
			xht.send(formData);
		}

		//////////////////Paginaton//////////////////////

		var pagination = document.getElementsByClassName('pagination')[0];
		var backButton = document.getElementsByClassName('page-back')[0];
		var forwButton = document.getElementsByClassName('page-forw')[0];
		var currentPage = pagination.dataset.page; //1
		var maxPage = pagination.dataset.maxpage; //3
		// var strBackButton = "<button class=\"page-but page-back\"><<</button>";
		// var strForwardButton = "<button class=\"page-but page-forw\"><<</button>";

		
		function clearPagButtons()
		{
			if ($('left'))
				$('left').innerHTML = "";
			if ($('middle'))
				$('middle').innerHTML = "";
			if ($('right'))
				$('right').innerHTML = "";
		}

		if (backButton)
			backButton.onclick = function()
			{
				clearPagButtons();
				if (currentPage - 1 <= 1)
				{
					$('left').style.display = 'none';
					currentPage = 1;
					$('middle').innerHTML = currentPage;
					backButton.style.display = 'none';
				}
				else
				{
					currentPage -= 1;
					$('left').style.display = 'inline-block';
					$('left').innerHTML = 1;
					$('middle').innerHTML = currentPage;
					backButton.style.display = 'inline-block';
				}

				if (currentPage != maxPage)
				{
					forwButton.style.display = "inline-block";
					$('right').style.display = "inline-block";
					$('right').innerHTML = maxPage;
				}
				else
				{
					$('right').style.display = "none";
					forwButton.style.display = "none";
				}
				location.href = '/wall/' + currentPage;
			}

		if (forwButton)
			forwButton.onclick = function()
			{
				if (currentPage + 1 >= maxPage)
				{
					$('right').style.display = 'none';
					currentPage = maxPage;
					$('middle').innerHTML = currentPage;
					forwButton.style.display = 'none';
				}
				else
				{
					currentPage = +currentPage +  1;
					$('left').style.display = 'inline-block';
					$('left').innerHTML = 1;
					backButton.style.display = 'inline-block';
					$('middle').innerHTML = currentPage;
					forwButton.style.display = 'inline-block';
					$('right').style.display = 'inline-block';
					$('right').innerHTML = maxPage;
				}
				
				location.href = '/wall/' + currentPage;
			}

			document.getElementsByClassName('footer')[0].style.width = "900px";

}	


