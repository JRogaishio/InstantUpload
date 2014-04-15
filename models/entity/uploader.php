<?php

class uploader extends model {

    public function displayManager() {
        echo "<br />
			<form action='index.php?type=uploader&action=upload' method='post' enctype='multipart/form-data'>
				<label for='file'>Filename:</label>
				<input type='file' name='file' id='file' /> 
				<br />
				<input type='submit' value='Upload file' />
			</form>
			<br /><br />
        ";
    }
    
    public function manage($action, $parent) {
    	$ret = false;
    	
    	if($action == "delete") {
    		$fileData = getRecords($this->conn, "upload", array("*"), "id=$parent");
    		
    		//If the file exists, delete it
    		if($fileData != false) {
	    		$data = mysqli_fetch_assoc($fileData);
	
	    		if(file_exists("uploads/" . $data["file_name"])) {
	    			unlink("uploads/" . $data["file_name"]);
	    		} else {
	    			echo "Could not find file on server. Removing record...<br />";
	    		}
	    		
	    		
	    		$sql = "DELETE FROM upload WHERE id=$parent";
	    		
	    		$result = $this->conn->query($sql) OR DIE ("Could delete file!");
	    		
	    		$ret = true;
	    		
	    		echo "File deleted successfully.";
    		} else {
    			echo "Could not delete file. Reason: Could not find file in database with ID: $parent!";
    		}
    	} else if($action == "upload") {
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
	    		if (file_exists("uploads/" . $_FILES["file"]["name"]))
	    		{
	    			echo "A file named <strong>" . $_FILES["file"]["name"] . "</strong> already exists. Could not upload file. <br /><br />";
	    		}
	    		else
	    		{
	    			move_uploaded_file($_FILES["file"]["tmp_name"], "uploads/" . $_FILES["file"]["name"]);
	    			echo "File uploaded successfully!<br />";

	    			$sql = "INSERT INTO upload (file_name, file_type, file_size, file_date, file_created) VALUES";
	    			$sql .= "('" . $_FILES["file"]["name"] . "', '" . $_FILES["file"]["type"] . "', '" . $_FILES["file"]["size"] . "', '" . date('Y-m-d H:i:s') . "','" . time() . "')";
	    			
	    			$result = $this->conn->query($sql) OR DIE ("Could not write to file table!");
	    			
	    			$ret = true;	    			
	    		}
	    	}
    	}
    	
    	return $ret;
    }
    
    /**
     * Builds the necessary tables for this object
     *
     */
    public function buildTable() {
    	/*Table structure for table `log` */
    	$sql = "CREATE TABLE IF NOT EXISTS `upload` (
		  `id` int(16) NOT NULL AUTO_INCREMENT,
		  `file_name` varchar(64) DEFAULT NULL,
		  `file_type` varchar(64) DEFAULT NULL,
		  `file_size` int(255) DEFAULT NULL,
		  `file_date` datetime DEFAULT NULL,
		  `file_created` varchar(128) DEFAULT NULL,
		  PRIMARY KEY (`id`)
		)";
    	$this->conn->query($sql) OR DIE ("Could not build table \"file\"");
    
    }
}

?>