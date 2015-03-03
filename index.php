<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title>HealthMate Login</title>
		<style>
			.required {
				color: #FF0000;
			}
		</style>
	</head>
	<body>
		<?php
			// Error placeholders
			$usernameError = $passwordError = "";
			// Authentication placeholders
			$username = $password = "";
			
			// Only process POST requests, not GET
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if (empty($_POST["username"])) {
					$usernameError = "Please enter a username";
				} else {
					$username = test_input($_POST["username"]);
				}
				
				if (empty($_POST["password"])) {
					$passwordError = "Please enter a password";
				} else {
					$password = test_input($_POST["password"]);
				}
			}
			
			if (($username !== "") && ($password !== "")) {
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
				
				echo "<p>Connected successfully</p>";
				
				// Grab the password for the given username
				$sql = "SELECT password FROM physician WHERE username = '" . $username . "'";
				$result = $conn->query($sql);
				
				// If there's a match, check to make sure authentication was successful
				if ($result->num_rows > 0) {
					$row = $result->fetch_assoc();
					// We know the username matches so check the password
					if ($row["password"] == $password) {
						echo "<p>Account authenticated successfully</p>";
					} else {
						// Don't let the user know which piece of data was incorrect
						echo "<p>Incorrect username or password</p>";
					}
				} else {
					echo "<p>No such username</p>";
				}
				
				$conn->close();
			}
			
			// Removes unwanted and potentially malicious characters
			// from the form data to prevent XSS hacks / exploits
			function test_input($data) {
				$data = trim($data);
				$data = stripslashes($data);
				$data = htmlspecialchars($data);
				return $data;
			}
		?>
		
		<h1>HealthMate Physician Login</h1>
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
		<p>Username: <input type="text" name="username" size="16" maxlength="16" />
		<span class="required"><?php echo $usernameError; ?></span></p>
		<p>Password: <input type="password" name="password" size="16" maxlength="16" />
		<span class="required"><?php echo $passwordError; ?></span></p>
		<input type="submit" value="Login" />
		</form>
		<form action="createaccount.php">
		<input type="submit" value="Create Account" />
		</form>
	</body>
</html>