 <div id="header_outside"></div>
 	<div id="wrapper">
 			<div class="cama_name">Camagru</div>
 			<div class="fornav">
	 			<nav class="menu">
	 				<ul>
	 					<li><a href="#">Menu</a>
	 						<ul>
	 							<li style="margin-left: 100px;"><a href="/wall/1">Main</a>
	 							<?php if (isset($_SESSION['user'])): ?>
	 							<li><a href="<?php if(isset($_SESSION['user'])) echo "/profile/" . $_SESSION['user']; else echo "login";?>">My profile</a>
	 							<li><a href="/camera">Album</a>
	 							<?php endif; ?>
	 							<?php if (empty($_SESSION['user'])): ?>
	 							<li><a href="/login">Sign in</a>
	 							<?php else: ?>
	 							<li><a href="/logout">Sign out</a>
	 							<?php endif; ?>
	 						</ul>
	 					</li>
	 				</ul>
	 			</nav>
 			</div>

