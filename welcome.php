<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title>HealthMate</title>
		<link rel="stylesheet" href="css/style.css">
		<?php
			session_start();
			$username = $_SESSION["username"];
			
			$patient_info = "";
			
			// Only process POST requests, not GET
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				// Some DB info - users use the HMTest user
				// and tests are done on the testdb database
				$servername = "localhost";
				$dbusername = "HMTest";
				$dbpassword = "comp490";
				$dbname = "testdb";
				
				// Create connection
				$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

				// Check connection
				if ($conn->connect_error) {
					die("Connection failed: " . $conn->connect_error);
				}
				
				// For now, all patients will be shown (table needs to be re-designed
				$sql = "SELECT * FROM patient WHERE group_id = 2";
				$result = $conn->query($sql);
				
				// Generate the proper HTML elements and display
				// all the patient info
				if ($result->num_rows > 0) {
					// output each row
					while ($row = $result->fetch_assoc()) {
						$patient_info .= "<section class='container'>";
						$patient_info .= "<div class='login'>";
						$patient_info .= "Patient ID: " . $row["patient_id"] . "<br />";
						$patient_info .= "First name: " . $row["first_name"] . "<br />";
						$patient_info .= "Last name: " . $row["last_name"] . "<br />";
						$patient_info .= "Gender: " . $row["gender"] . "<br />";
						$patient_info .= "Birthday: " . $row["birthday"] . "<br />";
						$patient_info .= "</div>";
						$patient_info .= "</section>";
					}
				} else {
					echo "Error: " . $sql . "<br />" . $conn->error;
				}
			
				// Peace out
				$conn->close();
			}
		?>
	</head>
	<body>
		<section class="container">
			<div class="login">
				<h1>Welcome</h1>
				<center>
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
					<input type="submit" value="View Patient List" />
				</form>
				<form action="index.php">
					<input type="submit" value="Logout" />
				</form>
				</center>
			</div>
		</section>
		<?php echo $patient_info ?>
	</body>
</html>