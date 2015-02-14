<?php
	// Some DB info - users use the HMTest user
	// and tests are done on the testdb database
	$servername = "localhost";
	$username = "HMTest";
	$password = "comp490";
	$dbname = "testdb";

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);

	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	
	echo "Connected successfully";
	
	// To do: Check values against database and send to the welcome page if successful
	//$sql = "";
	
	// Probably keep after debug...?
	if ($conn->query($sql) === TRUE) {
		echo "Account authenticated successfully.";
	} else {
		echo "Error: " . $sql . "<br />" . $conn->error;
	}
	
	$conn->close();
?>