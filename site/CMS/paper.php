<?php
require_once('../../api/db.php');

$id = 0;
if(isset($_GET['id'])) $id = $_GET['id'];
if(isset($_POST['id'])) $id = $_POST['id'];
$result = mysql_query("select title from paper where id=$id");
$title = "";
if ($result) {
	while ($row = mysql_fetch_row($result))
	{
		$title = $row[0];
		break;
	}
}

if(isset($_POST['link']))
{
	$link = $_POST['link'];
	mysql_query("replace into paper_link (id, link, created_at) values ($id, '" . trim($link) . "','" . date("Y-m-d H:i:s") . "');");
}

?>
<html>
	<body>
		<h3><?php echo $title; ?></h3>
		<form action="paper.php" method="post">
			<input type="text" name="link" />
			<input type="hidden" name="id" value="<?php echo $id;?>"/>
			<input type="submit" value="submit" />
		</form>
	</body>
</html>