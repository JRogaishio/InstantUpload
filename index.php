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
	<h3>Instant Uploader</h3><br />
	<?php
	if(isset($_POST['submit'])) {
	  if ($_FILES["file"]["error"] > 0)
		{
			if($_FILES["file"]["error"] == 4)
			{
			
			echo "Please select a file before pressing the submit button.<br /><br />";
			}
			echo $_FILES["file"]["error"];
		}
	  else
		{
		
		
		$fileSize = round(($_FILES["file"]["size"] / 1024 / 1024), 2);
		echo "<h4>Your upload details</h4>";
		echo "Size: $fileSize Mb<br />";

		if (file_exists("uploads/" . $_FILES["file"]["name"]))
		  {
		  echo $_FILES["file"]["name"] . " already exists. <br /><br />";
		  }
		else
		  {
		  move_uploaded_file($_FILES["file"]["tmp_name"],
		  "uploads/" . $_FILES["file"]["name"]);
		  
		  echo "Upload of: " . $_FILES["file"]["name"] . " was successful!<br /><br />";
		  echo "Type: " . $_FILES["file"]["type"] . "<br />";
		  echo "Stored in: " . "/uploads/" . $_FILES["file"]["name"];
		  echo"<br /><br />";
		  }
		}
	}
	?>

	
	<form action="index.php" method="post"
	enctype="multipart/form-data">
	<label for="file">Filename:</label>
	<input type="file" name="file" id="file" /> 
	<br />
	<input type="submit" name="submit" value="Submit" />
	</form>
	
	</div>

</div>

</body>
</html>