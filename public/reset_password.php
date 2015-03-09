<?php require_once("../private/initialize.php"); ?>
<?php

session_start();

$message = "";
$token = $_GET['token'];

// Confirm that the token sent is valid
$username = find_user_with_token($token);
if(!isset($username)) {
	// Token wasn't sent or didn't match a user.
	redirect_to('forgot_password.php');
}

if(request_is_post() && request_is_same_domain()) {
	
  if(!csrf_token_is_valid() || !csrf_token_is_recent()) {
  	$message = "Sorry, request was not valid.";
  } else {
    // CSRF tests passed--form was created by us recently.

		// retrieve the values submitted via the form
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];
    
		if(empty($password) || empty($password_confirm)) {
			$message = "Password and Confirm Password are required fields.";
		} elseif($password !== $password_confirm) {
			$message = "Password confirmation does not match password.";
		} else {
			// password and password_confirm are valid
			// Hash the password and save it to the fake database
			$hashed_password = password_hash($password, PASSWORD_BCRYPT);
			
			// Update Password in Database and Remove Token
			// Attempt to connect to the database
         $db = mysqli_connect("localhost", "root", "#mws1992", "testDB");
         if (mysqli_connect_errno()) {
            die("Database connection failed: " . mysqli_connect_error() .
              " (" . mysqli_connect_errno() . ")");
         }
   
         // SQL statement to retrieve rows that have the username column equal to the given username      
         $sql_statement = "SELECT * FROM users WHERE username='" .$username. "'";
         echo $sql_statement;

         // execute query
         $users = $db->query($sql_statement);
      
         // check if anything was returned by database
         if ($users->num_rows > 0) {
			   $sql_statement = "UPDATE users SET password='" .$hashed_password. "' WHERE username ='" .$username."'";
			   $db->query($sql_statement);
			   // fetch the first row of the results of the query
            $row = $users->fetch_assoc();
			   delete_reset_token($row['username']);
			   $db->close();
			   
		   }
			redirect_to('../index.php');
		}

	}
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Reset Password</title>
  </head>
  <body>

    <?php
      if($message != "") {
        echo '<p>' . sanitize_html($message) . '</p>';
      }
    ?>

    <p>Set your new password.</p>
    
		<?php $url = "reset_password.php?token=" . sanitize_url($token); ?>
    <form action="<?php echo $url; ?>" method="POST" accept-charset="utf-8">
      <?php echo csrf_token_tag(); ?>
      Password: <input type="password" name="password" value="" /><br />
			<br />
      Confirm Password: <input type="password" name="password_confirm" value="" /><br />
			<br />
      <input type="submit" name="submit" value="Set password" />
    </form>
  </body>
</html>
