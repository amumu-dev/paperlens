<?php
session_start();
?>
<html>
	<head>
		<title>PaperLens : Open Source Academic Recommender System</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="./css/main.css" />
	</head>
	
	<body>
		<div id="content">
			<div id="header">
				<div id="logo">PaperLens</div>
			</div>
			
			<div class="login">
				<form action="signup.php" method="post" style="width:100%;float:left;">
					<div style="float:left;width:100%;"><span>Email&nbsp;</span><input type="text" name="email" class="textinput"/></div>
					<div style="float:left;width:100%;"><span>Password&nbsp;</span><input type="text" name="password" class="textinput"/></div>
					<div style="float:left;width:100%;"><span>Research Area&nbsp;</span><input type="text" name="keywords" class="textinput"/></div>
					<input type="submit" value="SignUp" class="button" />
				</form>
			</div>
			
			<div class="login">
				<form action="login.php" method="post" style="width:100%;float:left;">
					<div style="float:left;width:100%;"><span>Email&nbsp;</span><input type="text" name="email" class="textinput"/></div>
					<div style="float:left;width:100%;"><span>Password&nbsp;</span><input type="text" name="password" class="textinput"/></div>
					<input type="submit" value="Login" class="button" />
				</form>
			</div>
		</div>
	</body>
</html>