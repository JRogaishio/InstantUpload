<?php

include_once('models/entity/model.php');
include_once('models/entity/log.php');
include_once('models/entity/uploader.php');
include_once('lib/database.php');

$_CONN = null;
$_LOGG = null;

$_CONN = new mysqli("localhost","root","") or die("Could not connect. " . mysqli_error());

//Create the database if it doesn't exist
$dbCreate = "CREATE DATABASE IF NOT EXISTS `instant_uploader`;";
$_CONN->query($dbCreate) OR DIE ("Could not build database!");

//Connect to our shiney new database
$_CONN->select_db("instant_uploader") or die("Could not select database. " . mysqli_error());

//Create a logging object
$_LOG = new log($_CONN, null);

?>

<html>
<head>
<title>Instant uploader</title>

<link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body>

<div class="main">
	<div class="header">	
		<h1>Instant Uploader</h1>
	</div>


	<div class="content">
	
	<?php 
	//Create a logging object
	$_LOG = new log($_CONN, null);
	$uploader = new uploader($_CONN, $_LOG);
	$uploader->buildTable();
	
	$_TYPE = isset( $_GET['type'] ) ? clean($_CONN,$_GET['type']) : null;
	$_ACTION = isset( $_GET['action'] ) ? clean($_CONN,$_GET['action']) : null;
	$_PARENT = isset( $_GET['p'] ) ? clean($_CONN,$_GET['p']) : null;

	if($_TYPE == "uploader") {
		$uploader->manage($_ACTION, $_PARENT);
	}
	
	$uploader->displayManager();
	
	$fileData = getRecords($_CONN, "upload", array("*"));

	echo "<div class=\"upload\">";

	if($fileData != false) {
		echo "<table border=1>
		<tr><th>Filename</th><th>Type</th><th>Size</th><th>Added</th><th>Link to file</th><th>Manage</th></tr>";
		
		while($row = mysqli_fetch_assoc($fileData) ) {
			$id = stripslashes($row['id']);
			$name = stripslashes($row['file_name']);
			$type = stripslashes($row['file_type']);
			$size = stripslashes($row['file_size']);
			$date = stripslashes($row['file_date']);
			
			//Format size to MB
			$size = round(($size / 1024 / 1024), 2);
			
			echo "
			<tr>
				<td>$name</td>
				<td>$type</td>
				<td>$size (MB)</td>
				<td>$date</td>
				<td><a href='uploads/$name' target='_blank'>Link</a></td>
				<td><form action='?type=uploader&action=delete&p=$id' method='post'><input type='submit' value='Delete'></form></td>
			</tr>";
		}
		echo "</table>";
	} else {
		echo "No files uploaded yet...";
	}
	echo "</div>";
	
		
	
	?>
	</div>

</div>

</body>
</html>