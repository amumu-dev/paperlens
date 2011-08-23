<h3>Please login/signup : </h3>
<form action="/site/login.php" method="post" style="width:100%;float:left;">
	<div style="float:left;width:100%;"><span>Email&nbsp;</span><input type="text" name="email" class="textinput"/></div>
	<div style="float:left;width:100%;"><span>Password&nbsp;</span><input type="password" name="password" class="textinput"/></div>
	<input type="submit" value="Login/Signup" class="button" />
	<input type="hidden" name="callback" value="<?php echo "http://".$_SERVER ['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" />
</form>