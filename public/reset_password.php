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
         $db = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
         if (mysqli_connect_errno()) {
            die("Database connection failed: " . mysqli_connect_error() .
              " (" . mysqli_connect_errno() . ")");
         }
   
         // SQL statement to retrieve rows that have the username column equal to the given username      
         $sql_statement = "SELECT * FROM physician WHERE username='" .$username. "'";

         // execute query
         $users = $db->query($sql_statement);
      
         // check if anything was returned by database
         if ($users->num_rows > 0) {
			   $sql_statement = "UPDATE physician SET password='" .$hashed_password. "' WHERE username ='" .$username."'";
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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Reset Password</title>
    
     <!-- Bootstrap core CSS-->
    <link href="../newcss/bootstrap.css" type="text/css" rel="stylesheet">
    
    <!-- Custom CSS for Login -->
    <link href="../newcss/login.css" type="text/css" rel="stylesheet">
  </head>
  <body style="padding-top: 0;">
    <!-- begin navigation bar -->
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="">HealthMate</a>
                </div>

                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="">Home<span class="sr-only">(current)</span></a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Patient <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="#">Add Patient</a></li>
                                <li class="divider"></li>
                                <li><a href="#">View Patient List</a></li>
                            </ul>
                        </li>
                        <li><a href="">Settings</a></li>
                        <li><a href="">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- end navigation bar -->

    <?php
      if($message != "") {
        echo '<p>' . sanitize_html($message) . '</p>';
      }
    ?>
		<div class="well login-well">
			<fieldset>
				<p>Set your new password.</p>
	    
					<?php $url = "reset_password.php?token=" . sanitize_url($token); ?>
				<form action="<?php echo $url; ?>" method="POST" accept-charset="utf-8" class="form-horizontal login-form">
					<?php echo csrf_token_tag(); ?>
					<div class="col-md-12">
						<label>Password:</label> 
						<div class="input-group">
							<input type="password" name="password" value="" /><br /><br />
						</div>
					</div>
					<div class="col-md-12">
						<label>Confirm Password:</label> 
						<div class="input-group">
							<input type="password" name="password_confirm" value="" /><br /><br />
						</div>
					</div>
					<div class="col-md-12">
						<input type="submit" name="submit" value="Set password" class="btn btn-lg btn-block btn-primary" />
					</div>
				</form>
			</fieldset>
		</div>
  </body>
</html>
