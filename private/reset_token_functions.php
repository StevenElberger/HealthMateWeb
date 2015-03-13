<?php

	require_once("/var/www/html/HealthMateWeb/private/PHPMailer/class.phpmailer.php");
	require_once("/var/www/html/HealthMateWeb/private/PHPMailer/class.smtp.php");
	require_once("/var/www/html/HealthMateWeb/private/definitions.php");
// Reset token functions

// Function that generates a string that can be used as a reset token. 
// The function generates this unique token by generating a random number,
// generating a unique id from that number and then hashing it.
function reset_token() {
	return md5(uniqid(rand()));
}

// Looks up a user and sets their reset_token to
// the given value. Can be used both to create and
// to delete the token.
function set_user_reset_token($username, $token_value) {
	
	// Attempt to connect to the database
   $db = mysqli_connect("localhost", "root", "#mws1992", "testDB");
   if (mysqli_connect_errno()) {
      die("Database connection failed: " . mysqli_connect_error() .
         " (" . mysqli_connect_errno() . ")");
   }
   
   // SQL statement to retrieve rows that have the username column equal to the given username      
    $sql_statement = "SELECT * FROM users WHERE username='".$username."'";

   // execute query
   $users = $db->query($sql_statement);

   // check if anything was returned by database
   if ($users->num_rows > 0) {

      // fetch the first row of the results of the query
      $row = $users->fetch_assoc();

      // Set reset token for the found user
      $sql_statement = "UPDATE users SET reset_token='".$token_value."' WHERE username='".$username."'";

      // execute query
      $db->query($sql_statement);
      $db->close();
      return true;
	} else {
		return false;
	}
}

// Function that generates a new reset token,
// and sets the reset token for the given user.
function create_reset_token($username) {
	$token = reset_token();
	return set_user_reset_token($username, $token);
}

// Function that removes the token for the given
// user by setting the value to null.
function delete_reset_token($username) {
	$token = null;
	return set_user_reset_token($username, $token);
}

// Returns the user record for a given reset token.
// If token is not found, returns null.
function find_user_with_token($token) {
	if(empty($token)) {
		// We were expecting a token and didn't get one.
		return null;
	} else {
		
	   // Attempt to connect to the database
      $db = mysqli_connect("localhost", "root", "#mws1992", "testDB");
      if (mysqli_connect_errno()) {
         die("Database connection failed: " . mysqli_connect_error() .
           " (" . mysqli_connect_errno() . ")");
      }
   
      // SQL statement to retrieve rows that have the username column equal to the given username      
      $sql_statement = "SELECT * FROM users WHERE reset_token='".$token."'";

      // execute query
      $users = $db->query($sql_statement);
      
      // check if anything was returned by database
      if ($users->num_rows > 0) {

         // fetch the first row of the results of the query
         $row = $users->fetch_assoc();
		
		   // return the username associated with the reset_token if found
		   // otherwise return null
		   return $row["username"];
	   } else {
		   return null;
	   }
	}
}

// A function to email the reset token to the email
// address on file for this user.
// This is a placeholder since we don't have email
// abilities set up in the demo version.
function email_reset_token($username) {
	
	// Attempt to connect to the database
   $db = mysqli_connect("localhost", "root", "#mws1992", "testDB");
   if (mysqli_connect_errno()) {
      die("Database connection failed: " . mysqli_connect_error() .
         " (" . mysqli_connect_errno() . ")");
   }
   
   // SQL statement to retrieve rows that have the username column equal to the given username      
    $sql_statement = "SELECT * FROM users WHERE username='".$username."'";

   // execute query
   $users = $db->query($sql_statement);

   // check if anything was returned by database
   if ($users->num_rows > 0) {

      // fetch the first row of the results of the query
      $row = $users->fetch_assoc();
      
      $ip_address = $_SERVER['SERVER_ADDR'];
      
      $to_name = $row["username"];
      $to = $row["email"];
      $subject = "HealthMate Reset Password";
      $body = file_get_contents('email_template.php');
      $body = str_replace("[[token]]", $row["reset_token"], $body);
      $body = str_replace("[[ip_address]]", $ip_address, $body);
      
      $from_name = "HealthMate Dev";
      $from = EMAIL_USERNAME;
      
      $mail = new PHPMailer();
      $mail->IsSMTP();
      $mail->Host = "smtp.mail.yahoo.com";
      $mail->SMTPSecure = "tls";
      $mail->SMTPAuth = true;
      $mail->Username = $from;
      $mail->Password = EMAIL_PASSWORD;
      $mail->From = $from;
      $mail->FromName = $from_name;
      $mail->AddAddress($to, $to_name);
      $mail->Subject = $subject;
      $mail->AltBody = "To view this message, please use an HTML compatible email viewer";
      $mail->IsHTML(true);
      $mail->MsgHTML($body);
      $mail->WordWrap = 70;
      
      // Email the user
      $result = $mail->Send();
      
      // Uncomment For Testing
      /*if ($result) {
			echo "Success";
		} else {
			echo $mail->ErrorInfo;
		}*/

		// close database connection
      $db->close();
	} 
}

?>
