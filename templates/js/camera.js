
window.onload = function () {
	function $($id)
	{
		return document.getElementById($id);
	}
	////////////////////////////////Working with photo into album////////////////////////
	(function(){
		for (var i = 0; i < $('inneralbum').childNodes.length; i++) //события на фото в альбоме
			if ($('inneralbum').childNodes[i].nodeType == 1)
				$('inneralbum').childNodes[i].addEventListener("click", function(){stretchSizePhoto(this);});			
	}());


	function stretchSizePhoto(elem) //события на фото в альбоме
	{
		$('inneralbum').style.display = "none";
		var clone = elem.cloneNode(true);
		$('album').insertBefore(clone, $('album').firstChild);
		clone.style.width = "745px";
		clone.style.height = "485px";
		clone.style.opacity = '1';
		clone.onclick = function()
		{
			this.remove(); 
			$('inneralbum').style.display = "block";
			$('butdel').style.display = "none";
			$('butsetava').style.display = "none";
		}

		$('butdel').style.display = "inline-block";
		$('butsetava').style.display = "inline-block";
		$('butdel').onclick = function(){ajaxDeletePhoto(elem, clone)};
		$('butsetava').onclick = function(){ajaxAddAvatar(clone)};
		$('butsetava').style.display = "inline-block";
	}

	function ajaxDeletePhoto(elem, clone)
	{
	    var ajax = new XMLHttpRequest();
        ajax.open("POST", '/delete-photo', true);
        var formData = new FormData();
        ajax.onreadystatechange = function()
		{
			if (ajax.readyState == 4 && ajax.status == 200)
			{
				clone.remove();
				elem.remove();
				$('inneralbum').style.display = "block";
				$('butdel').style.display = "none";
				$('butsetava').style.display = "none";
			}
		}
        formData.append('delete', true);
        formData.append('link-image', clone.style.backgroundImage);
        ajax.send(formData);
	}

	function ajaxAddAvatar(clone)
	{
	    var ajax = new XMLHttpRequest();
        ajax.open("POST", '/delete-photo', true);
        var formData = new FormData();
        ajax.onreadystatechange = function()
		{
		if (ajax.readyState == 4 && ajax.status == 200)
				alert("Your profile photo has been successfully updated!");
		}
        formData.append('addavatar', true);
        formData.append('link-image', clone.style.backgroundImage);
        ajax.send(formData);
	}

	/////////////////////////////////////////////////////////////////////////////////////////
	//*****************************************************************************************


	///////////////////////Upload photo////////////////////
		var form = document.getElementById('file-form');
		var fileSelect = document.getElementById('file-select');
		var uploadButton = document.getElementById('upload-button');
		var formData = new FormData();
		var isUploadImage = false;

		document.getElementById('file-select').addEventListener('change', function() {
		formData = new FormData();
		var files = fileSelect.files;
		var file = files[0];
		formData.append('avatar', file);
		 });	


		form.onsubmit = function(event) 
		{
			event.preventDefault();
			var progressdiv = document.getElementById('progressdiv');
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() 
			{
		 		if (this.readyState == 4 && this.status == 200) 
		 		{
		 			isUploadImage = true;
		 			$('uploadedphoto').src = "/templates/album/" + this.responseText; // не обновляет сразу
		 		}
			};
			xhttp.open("POST", "/delete-photo", true);
			xhttp.send(formData);
	 	}


	///////////////////////////Create Upload button/////////////////////////////////////


	//var canvas_state = false;
	var video = document.getElementById('video');
	var canvas = document.getElementById('canvas');
	var context = canvas.getContext('2d');


	var arr = [];
	var save = false;
	var checkErrorCamera = 0;

	var canvas_block = document.getElementById('can');
	var video_block = document.getElementById('vid');
	$('butsave').style.backgroundColor = "grey";

	navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.oGetUserMedia || navigator.msGetUserMedia;
	if (navigator.getUserMedia)
		navigator.getUserMedia({video:true}, streamWebCam, throwError);

	function streamWebCam(stream)
	{
		video.srcObject = stream;
		video.play();
	}
	function throwError (e)
	{
		setTimeout(errorVideo, 2000);
		//alert(e.name);
		checkErrorCamera = 1;
		$('loading').style.display = "block";
		$('butoption').style.backgroundColor = "grey";
		$('butoption').innerHTML = "Use webcamera";
	}

	function errorVideo()
	{
		$('loading').style.display = "none";

		$('file-form').style.display = "block";
		$('video').style.display = "none";
		$('uploadedphoto').style.display = "inline-block";
		option = !option;
	}


	document.getElementById('but').addEventListener('click', snap, false);
	document.getElementById('but_repeat').addEventListener('click', repeat, false);


	var option = false; //false = video; true = upload
	var activeButtonOption = false;

	$('butoption').onclick = function()
	{
		if (checkErrorCamera)
		{
			$('butoption').style.backgroundColor = "grey";
			return ;
		}
		if (activeButtonOption)
			return ;
		option = !option;
		if (option)
		{
			$('file-form').style.display = "block";
			$('video').style.display = "none";
			$('uploadedphoto').style.display = "inline-block";
			$('butoption').innerHTML = "Use webcamera";
		}
		else
		{
			$('file-form').style.display = "none";
			$('video').style.display = "inline-block";
			$('uploadedphoto').style.display = "none";
			$('butoption').innerHTML = "Upload your own photo";
		}
	}


	function snap()
	{
		if (!option)
		{
			canvas.width = video.clientWidth;
			canvas.height = video.clientHeight;
			video_block.style.display = "none";
			canvas_block.style.display = "block";
			context.drawImage(video, 0, 0);
		}
		else
		{
			if (!isUploadImage)
			{
				alert("The image didn't upload! Try again.");
				return ;
			}
			canvas.width = 640;
			canvas.height = 480;
			video_block.style.display = "none";
			canvas_block.style.display = "block";
			context.drawImage($('uploadedphoto'), 0, 0, 640, 480);
		}
		$('but').style.backgroundColor = "grey";
        $('butsave').style.backgroundColor = "#6a6fa7";
        $('butsave').addEventListener('click', saveCanvas, false);
		$('but').removeEventListener('click', snap, false);
		activeButtonOption = true;
		$('butoption').style.backgroundColor = "grey";
		for (var i = 0; i < arr.length; i++)
		{
			if (arr[i].alive)
			{
				var posX = arr[i].style.top.replace(/[^0-9]/gim,'');
				var posY = arr[i].style.left.replace(/[^0-9]/gim,'');
				var width = arr[i].style.width.replace(/[^0-9]/gim,'');
				var height = arr[i].style.height.replace(/[^0-9]/gim,'');
	    		context.drawImage(arr[i], posY, posX, width, height);
    		}
		}
	}
	//////////////////////////////добавление размеров элементам сбоку видео/////////////////////

	   $('img1').addEventListener('click', function(){ addImgClick(230, 325, 1); },false);
	   $('img2').addEventListener('click', function(){ addImgClick(150, 100, 2); },false);
	   $('img3').addEventListener('click', function(){ addImgClick(115, 120, 3); },false);
	   $('img4').addEventListener('click', function(){ addImgClick(80, 20, 4); },false);
	   $('img5').addEventListener('click', function(){ addImgClick(100, 30, 5); },false);
	   $('img6').addEventListener('click', function(){ addImgClick(150, 75, 6); },false);
	   $('img7').addEventListener('click', function(){ addImgClick(195, 120, 7); },false);
	   $('img8').addEventListener('click', function(){ addImgClick(225, 120, 8); },false);
	   $('img9').addEventListener('click', function(){ addImgClick(300, 250, 9); },false);
	   $('img10').addEventListener('click', function(){ addImgClick(250, 180, 10); },false);
	   $('img11').addEventListener('click', function(){ addImgClick(450, 250, 11); },false);
	   $('img12').addEventListener('click', function(){ addImgClick(260, 220, 12); },false);
	   $('img13').addEventListener('click', function(){ addImgClick(260, 115, 13); },false);
	   $('img14').addEventListener('click', function(){ addImgClick(250, 110, 14); },false);
	   $('img15').addEventListener('click', function(){ addImgClick(200, 180, 15); },false);
	   $('img16').addEventListener('click', function(){ addImgClick(220, 120, 16); },false);
	   $('img17').addEventListener('click', function(){ addImgClick(230, 125, 17); },false);
	   $('img18').addEventListener('click', function(){ addImgClick(150, 100, 18); },false);
	   $('img19').addEventListener('click', function(){ addImgClick(255, 120, 19); },false);
	   $('img20').addEventListener('click', function(){ addImgClick(500, 200, 20); },false);
	   $('img21').addEventListener('click', function(){ addImgClick(550, 200, 21); },false);


		function addImgClick(sizepic_w, sizepic_h, nameimg)
		{
			var newElem = document.createElement('img');
			newElem.src = "/templates/img/" + nameimg + ".png";
			newElem.style.position = "absolute";
			newElem.style.top = 0;
			newElem.style.left = 0;
			newElem.style.width = sizepic_w + "px";
			newElem.style.height = sizepic_h + "px";
			newElem.alive = 1;
			newElem.ondblclick = function(){newElem.remove(); newElem.alive = 0;}
			video_block.appendChild(newElem);
			arr.push(newElem);


			newElem.addEventListener('mousedown', function (e)
			{
				move(this, e);
			}, false);

			function move(elem, event)
			{
				var mousePosX = event.clientX,
					mousePosY = event.clientY;

				var elemPosY = elem.offsetTop,
					elemPosX = elem.offsetLeft;

				var difX = mousePosX - elemPosX,
					difY = mousePosY - elemPosY;

				document.addEventListener('mousemove', mouseMove, false);
				document.addEventListener('mouseup', removeEvent, false);

				function mouseMove(e)
				{
					elem.style.top = (e.clientY - difY) + 'px';
					elem.style.left = (e.clientX - difX) + 'px';
				}

				function removeEvent()
				{
					document.removeEventListener('mousemove', mouseMove, false);
					document.removeEventListener('mouseup', removeEvent, false);
					if (elem.style.top.replace(/[^-0-9]/gim,'') < 0)
						elem.style.top = 0;
					if (elem.style.left.replace(/[^-0-9]/gim,'') < 0)
						elem.style.left = 0;
					if (elem.style.left.replace(/[^-0-9]/gim,'') > 640 - elem.style.width.replace(/[^-0-9]/gim,''))
						elem.style.left = 640 - elem.style.width.replace(/[^-0-9]/gim,'') + "px";
					if (elem.style.top.replace(/[^-0-9]/gim,'') > 480 - elem.style.height.replace(/[^-0-9]/gim,''))
						elem.style.top = 480 - elem.style.height.replace(/[^-0-9]/gim,'') + "px";
				}
			}
		}

		//*********************************************************************************************

		function repeat() //кнопка обновить канвас
		{
			context.clearRect(0, 0, canvas.width, canvas.height);
			$('uploadedphoto').src = "/templates/img/white.png";
			isUploadImage = false;
			video_block.style.display = "block";
			canvas_block.style.display = "none";
            $('butsave').style.backgroundColor = "grey";
            $('butsave').removeEventListener('click', saveCanvas, false);
            activeButtonOption = false;
            $('butoption').style.backgroundColor = "#6a6fa7";
			for (var i = 0; i < arr.length; i++)
				arr[i].remove();
			arr = [];
			save ? setTimeout(function(){activateSnap()}, 3000) : activateSnap();
		}

		function activateSnap()
		{
			$('but').addEventListener('click', snap, false);
			$('but').style.backgroundColor = "#6a6fa7";
			save = false;
		}

		function saveCanvas() 
		{
            $('butsave').style.backgroundColor = "grey";
            var imgstr = $('canvas').toDataURL("image/png");
            var postData = "canvasData=" + imgstr;
            save = true;
            isUploadImage = false;
            $('butoption').style.backgroundColor = "#6a6fa7";
            activeButtonOption = false;
            var ajax = new XMLHttpRequest();
            ajax.open("POST", '/upload-camera', true);
            ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            ajax.onreadystatechange = function()
			{
				if (ajax.readyState == 4)
				{
					var newPhoto = document.createElement('div');
					newPhoto.className = "photo";
					newPhoto.style.backgroundImage = "url(" + ajax.responseText + ")";
					newPhoto.style.opacity = "0.01";
					$('inneralbum').insertBefore(newPhoto, $('inneralbum').firstChild);
					inter = setInterval(function() { addNewPhoto(newPhoto); }, 20);
					newPhoto.addEventListener("click", function(){stretchSizePhoto(this);});
				}
			}
            ajax.send(postData);
            repeat();
		}

		function addNewPhoto(elem)
		{
			if (+elem.style.opacity < 1)
				elem.style.opacity = +elem.style.opacity + 0.01;
			else
				clearInterval(inter);
		}


		(function(){
		$('vid').style.display = "none";
		setTimeout(loadedVideo, 2000);

		function loadedVideo()
		{
			$('vid').style.display = "block";
			$('loading').style.display = "none";
			$('vid-pic').style.display = "inline-block";
		}
		})();
};

