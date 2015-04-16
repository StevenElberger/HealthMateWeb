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
         $db = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
         if (mysqli_connect_errno()) {
            die("Database connection failed: " . mysqli_connect_error() .
              " (" . mysqli_connect_errno() . ")");
         }
   
         // SQL statement to retrieve rows that have the username column equal to the given username      
         $sql_statement = "SELECT * FROM physician WHERE username='".$username."'";
         
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

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Forgot Password</title>
    <!-- Bootstrap core CSS-->
    <link href="../newcss/bootstrap.css" type="text/css" rel="stylesheet">
    
    <!-- Custom CSS for Login -->
    <link href="../newcss/login.css" type="text/css" rel="stylesheet">
    
  </head>
  <body>
    <?php
      if($message != "") {
        echo '<p class="btn-primary" align = "center">' . sanitize_html($message) . '</p>';
      }
    ?>
    <div class="well login-well" style="padding-top: 15px;">
		 <fieldset>
		 <p>Enter your username to reset your password.</p>
		 <form role="form" id="reset-password-form" class="form-horizontal login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
			<?php echo csrf_token_tag(); ?>
			<div class="form-group" id="username-input">
				<div class="col-md-12">
					 <label>Username:</label>
					 <div class="input-group">
						  <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
						  <input type="text" name="username" id="username" class="form-control" value="<?php echo $username; ?>" data-container="body" data-toggle="popover" data-trigger="focus" data-content="Please Enter a Username" data-parsley-required="true" data-parsley-type="alphanum" data-parsley-length="[8, 16]" data-parsley-group="block1" data-parsley-ui-enabled="false">
					 </div>
				</div>
		  </div>
			<div class="col-md-12">
				<input type="submit" name="submit" value="Submit" class="btn btn-lg btn-block btn-primary"/>
				<a class="text-center" style="display: block;" href="forgot_username.php">Forgot your username?</a>
				<a class="text-center" style="display: block;" href="../index.php">Back to Login</a>
			</div>
		 </form>
		 </fieldset>
    </div>
    
            <!-- Bootstrap core JavaScript -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <!-- Form validation from Parsley -->
        <script src="../js/parsley.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                // activate all popovers
                $(function () {
                    $('[data-toggle="popover"]').popover();
                });

                $('#reset-password-form').parsley().subscribe('parsley:form:validate', function (formInstance) {

                    var username = formInstance.isValid('block1', true);

                    if (username) {
                        return;
                    }

                    // otherwise, stop form submission and mark
                    // required fields with bootstrap
                    formInstance.submitEvent.preventDefault();

                    // show error alert
                    $('#error-alert').removeClass("hidden");

                    /*
                        Input validation rules:
                        - Username Required
                     */
                    if (!username) {
                        $('#username-input').addClass("has-error");
                        $('#username').popover('show');
                    } else {
                        $('#username-input').removeClass("has-error");
                    }
                });
            });
        </script>
</html>
