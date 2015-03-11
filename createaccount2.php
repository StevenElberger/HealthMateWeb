<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title>HealthMate Login</title>
		<link rel="stylesheet" href="csstest/style.css">
	</head>
	<body>
		<?php
            // Grab security functions
            require_once("/private/initialize.php");
			// Flag for first load
			$firstLoad = true;
			// Error placeholders
			$firstNameError = $lastNameError = $usernameError = $mismatchError = "";
			$passwordError = $confirmError = $companyError = $phoneError = $requiredFields = "";
			// Placeholders for variables from form
			$username = $password = $confirm = $first_name = $last_name = $company = $phone = "";
			
			// Only process POST requests, not GET
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				$firstLoad = false;
				// Check the required fields
				if (empty($_POST["first_name"])) {
					$firstNameError = "*";
				} else {
					$first_name = test_input($_POST["first_name"]);
				}
				
				if (empty($_POST["last_name"])) {
					$lastNameError = "*";
				} else {
					$last_name = test_input($_POST["last_name"]);
				}
				
				if (empty($_POST["username"])) {
					$usernameError = "*";
				} else {
					$username = test_input($_POST["username"]);
				}
				
				if (empty($_POST["password"])) {
					$passwordError = "*";
				} else {
					$password = test_input($_POST["password"]);
				}
				
				if (empty($_POST["confirm"])) {
					$confirmError = "*";
				} else {
					$confirm = test_input($_POST["confirm"]);
				}
				
				if (empty($_POST["company"])) {
					$companyError = "*";
				} else {
					$company = test_input($_POST["company"]);
				}
				
				if (empty($_POST["phone"])) {
					$phoneError = "*";
				} else {
					$phone = test_input($_POST["phone"]);
				}
				
				if ($password !== $confirm) {
					$mismatchError = "Passwords do not match";
				}
			}
			
			// As long as all variables were initialized, the data is good to go
			if (($first_name !== "") && ($last_name !== "") && ($username !== "") && ($company !== "")
			&& ($phone !== "") && ($password !== "") && ($confirm !== "") && ($mismatchError === "")) {

                // Store the hash, not the pass
                $hash_pass = password_hash($password, PASSWORD_BCRYPT);

				// Create connection
                $conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

				// Check connection
				if ($conn->connect_error) {
					die("Connection failed: " . $conn->connect_error);
				}

				// Adds a new user account with form data into the physician table of the database
				// -- To do: form checking (e.g., username already exists, security, etc.)
				$sql = "INSERT INTO physician (group_id, username, password, first_name, last_name, company, phone) VALUES (1, '".$username."', '".$hash_pass."', '".$first_name."', '".$last_name."', '".$company."', '".$phone."')";

                if (username_exists($username, $conn)) {
                    $usernameError = "Username already exists";
                } else if ($conn->query($sql) === TRUE) {
					// Redirect upon successful account creation
					echo header("Location: /HealthMateTest/index.php");
				} else {
					echo "Error: " . $sql . "<br />" . $conn->error;
				}
			
				// Peace out
				$conn->close();
			} else {
				if (!$firstLoad) {
					$requiredFields = "The following fields are required: ";
				}
			}
			
			// Removes unwanted and potentially malicious characters
			// from the form data to prevent XSS hacks / exploits
			function test_input($data) {
				$data = trim($data);
				$data = stripslashes($data);
				$data = htmlspecialchars($data);
				return $data;
			}

            // Checks to see if given username already exists
            function username_exists($given_username, $existing_conn) {
                $sql = "SELECT username FROM physician WHERE username = '".$given_username."'";

                $result = $existing_conn->query($sql);

                return $result->num_rows > 0;
            }
		?>
		<section class="container">
			<div class="login">
				<h1>HealthMate Physician Login</h1>
				<p><span class="required"><?php echo $requiredFields; ?></span></p>
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
				
				<p>First name: <span class="required"><?php echo $firstNameError; ?></span>
				<input type="text" name="first_name" value="<?php echo $first_name; ?>" size="16" maxlength="16" /></p>
				
				<p>Last name: <span class="required"><?php echo $lastNameError; ?></span>
				<input type="text" name="last_name" value="<?php echo $last_name; ?>" size="16" maxlength="16" /></p>
				
				<p>Username: <span class="required"><?php echo $usernameError; ?></span>
				<input type="text" name="username" value="<?php echo $username; ?>" size="16" maxlength="16" /></p>
				
				<p>Password: <span class="required"><?php echo $passwordError; ?></span>
				<input type="password" name="password" size="16" maxlength="16" /></p>
				
				<p>Confirm Password: <span class="required"><?php echo $confirmError; ?><?php echo $mismatchError; ?></span>
				<input type="password" name="confirm" size="16" maxlength="16" /></p>
				
				<p>Company: <span class="required"><?php echo $companyError; ?></span>
				<input type="text" name="company" value="<?php echo $company; ?>" size="16" maxlength="16" /></p>
				
				<p>Phone Number: <span class="required"><?php echo $phoneError; ?></span>
				<input type="text" name="phone" value="<?php echo $phone; ?>" size="16" maxlength="16" /></p>
				
				<input type="submit" value="Submit" />
			</div>
		</section>
	</body>
</html>