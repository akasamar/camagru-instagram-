window.onload = function()
{
	function $($classname)
	{
		return document.getElementsByClassName($classname)[0];
	}

	$('footer').style.width = "915px";

	$('button-change').onclick = function()
	{
		var xhr2 = new XMLHttpRequest();
		xhr2.open("POST", "/upload-image", true);
		xhr2.onreadystatechange = function()
 		{
 			if (xhr2.readyState == 4 && xhr2.status == 200)
 			{
 				if (xhr2.responseText == 0)
 					document.getElementsByTagName('h6')[0].innerHTML = "<span style=\"color: red;\">You can't change info when log in via facebook!</span>";
 				else if (xhr2.responseText == 1)
 					document.getElementsByTagName('h6')[0].innerHTML = "<span style=\"color: red;\">One of the fields is empty</span>";
 				else if (xhr2.responseText == 2)
					document.getElementsByTagName('h6')[0].innerHTML = "<span style=\"color: red;\">Login can include in itself only [A-Z a-z 0-9 .]</span>";
				else if (xhr2.responseText == 3)
					document.getElementsByTagName('h6')[0].innerHTML = "<span style=\"color: red;\">The login is exist yet</span>";
				else if (xhr2.responseText == 4)
					document.getElementsByTagName('h6')[0].innerHTML = "<span style=\"color: red;\">The name have to have [Name Surname] and use [A-Z a-z]</span>";
				else if (xhr2.responseText == 5)
					document.getElementsByTagName('h6')[0].innerHTML = "<span style=\"color: red;\">Incorrect form of email adress[user@camagru.com]</span>";
				else if (xhr2.responseText == 6)
				{
					document.getElementsByTagName('h6')[0].innerHTML = "<span style=\"color: green;\">Изменения успешно выполнены</span>";
					document.getElementsByTagName('h2')[0].innerHTML = $('p2').value + ' @' + $('p1').value;
				}
			}
		}
		var formData = new FormData();
		formData.append("button_click", true);
    	formData.append("user", $('p1').value);
    	formData.append("name", $('p2').value);
    	formData.append("mail", $('p3').value);
    	formData.append("checkbox", $('p4').checked);
		xhr2.send(formData);
	}


		var form = document.getElementById('file-form');
		var fileSelect = document.getElementById('file-select');
		var uploadButton = document.getElementById('upload-button');
		var formData = new FormData();

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
		 		if (this.readyState == 4 && this.status == 200 && this.responseText.length == 0) 
		 			document.getElementById('image').src = "/templates/avatar/" + this.responseText; // не обновляет сразу
			};
			xhttp.open("POST", "/upload-image", true);
			xhttp.send(formData);
	 	}
  		
  		document.getElementsByClassName('delete-account')[0].onclick = function()
		{
			if (!confirm("Are you sure that you want to delete this account forever?"))
				return ;
			var xhr2 = new XMLHttpRequest();
			xhr2.open("POST", "/upload-image", true);
			xhr2.onreadystatechange = function()
	 		{
	 			if (xhr2.readyState == 4 && xhr2.status == 200)
	 			{
	 				location.href = '/login';
	 			}
	 		}
	 		var formData = new FormData();
			formData.append("delete_account", true);
			formData.append("user_id", this.dataset.id);
			xhr2.send(formData);
 		}

}