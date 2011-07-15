<?php
require_once('db.php');
$id = $_GET['id'];
$result = mysql_query('select title from paper where id='.$id);
if (!$result) {
    die('Query failed: ' . mysql_error());
}

while ($row = mysql_fetch_row($result))
{
	$title = $row[0];
}
mysql_free_result($result);

?>

<html>
	<head>
		<title><?php echo $title ?></title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	</head>
	
	<body>
		<h1><?php echo $title ?></h1>
	</body>
</html>