<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title>HealthMate</title>
		<link rel="stylesheet" href="css/style.css">
		<?php
			session_start();
			$username = $_SESSION["username"];
			
			function rand_patient() {
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
				
				$sql = "SELECT * FROM patient WHERE group_id = 2";
				$result = $conn->query($sql);
				
				if ($result->num_rows > 0) {
					// output each row
					while ($row = $result->fetch_assoc()) {
						echo "first name: " . $row["first_name"] . "<br />";
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
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
					<input type="submit" value="Patient List" />
				</form>
			</div>
		</section>
	</body>
</html>