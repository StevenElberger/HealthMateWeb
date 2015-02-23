<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">

<!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]> <html class="lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]> <html class="lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en"> <!--<![endif]-->

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>Login Form</title>
  <link rel="stylesheet" href="css/style.css">
  <!--[if lt IE 9]><script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->


</head>
<body>
	<?php
		// Error placeholders
		$usernameError = $passwordError = "";
		// Authentication placeholders
		$username = $password = "";
		$bad_authentication = "";
		
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
			
			// Grab the password for the given username
			$sql = "SELECT password FROM physician WHERE username = '" . $username . "'";
			$result = $conn->query($sql);
			
			// If there's a match, check to make sure authentication was successful
			if ($result->num_rows > 0) {
				$row = $result->fetch_assoc();
				// We know the username matches so check the password
				if ($row["password"] == $password) {
					// Redirect to the welcome page
					echo header("Location: /HealthMateTest/welcome.php");
				} else {
					// Don't let the user know which piece of data was incorrect
					$bad_authentication = "Incorrect username or password";
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
	
  <section class="container">
    <div class="login">		
      <h1>Login to HealthMate</h1>
	  <p><span class="required"><?php echo $bad_authentication; ?></span></p>
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
        <p><span class="required"><?php echo $usernameError; ?></span>
		<input type="text" name="username" placeholder="Username or Email"></p>
        <p><span class="required"><?php echo $passwordError; ?></span>
		<input type="password" name="password" placeholder="Password"></p>
        <p class="remember_me">
          <label>
            <input type="checkbox" name="remember_me" id="remember_me">
            Remember me on this computer
          </label>
        </p>
        <p class="submit"><input type="submit" value="Login" /></p>
      </form>
	  <form action="createaccount.php">
		<p class="submit"><input type="submit" value="Create Account" /></p>
	  </form>
    </div>

    <div class="login-help">
      <p>Forgot your password? <a href="index.php">Click here to reset it</a>.</p>
    </div>
  </section>

</body>
</html>