<?php require_once("../private/initialize.php"); ?>

<?php
session_start();

// initialize variables to default values
$username = "";
$message = "";

if(request_is_post() && request_is_same_domain()) {
	
  if(!csrf_token_is_valid() || !csrf_token_is_recent()) {
  	$message = "Sorry, request was not valid.";
  } else {
    // CSRF tests passed--form was created by us recently.

		// retrieve the values submitted via the form
    $username = $_POST['username'];
    
		if(!empty($username)) {
			
			// Search our fake database to retrieve the user data
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
            $user = $row['username'];

	         if($user) {
				   // Username was found; okay to reset
				   create_reset_token($username);
				   email_reset_token($username);
	          } else {
	            // Username was not found; don't do anything
	          }
			 }
	
			// Message returned is the same whether the user 
			// was found or not, so that we don't reveal which 
			// usernames exist and which do not.
			$message = "A link to reset your password has been sent to the email address on file.";
		
		} else {
			$message = "Please enter a username.";
		}
  }
}

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Forgot Password</title>
  </head>
  <body>
    
    <?php
      if($message != "") {
        echo '<p>' . sanitize_html($message) . '</p>';
      }
    ?>
    
    <p>Enter your username to reset your password.</p>
    
    <form action="forgot_password.php" method="POST" accept-charset="utf-8">
      <?php echo csrf_token_tag(); ?>
      Username: <input type="text" name="username" value="<?php echo sanitize_html($username); ?>" /><br />
			<br />
      <input type="submit" name="submit" value="Submit" />
    </form>
  </body>
</html>
