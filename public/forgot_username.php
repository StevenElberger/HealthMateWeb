<?php require_once("../private/initialize.php"); ?>

<?php
session_start();

// initialize variables to default values
$email = "";
$message = "";

if(request_is_post() && request_is_same_domain()) {
	
  if(!csrf_token_is_valid() || !csrf_token_is_recent()) {
  	$message = "Sorry, request was not valid.";
  } else {
    // CSRF tests passed--form was created by us recently.

	// retrieve the values submitted via the form
    $email = $_POST['email'];
    
		if(!empty($email)) {
			
			// Search our fake database to retrieve the user data
			// Attempt to connect to the database
         $db = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
         if (mysqli_connect_errno()) {
            die("Database connection failed: " . mysqli_connect_error() .
              " (" . mysqli_connect_errno() . ")");
         }
   
         // SQL statement to retrieve rows that have the email column equal to the given email      
         $sql_statement = "SELECT * FROM physician WHERE email='".$email."'";
         
         // execute query
         $users = $db->query($sql_statement);
      
         // check if anything was returned by database
         if ($users->num_rows > 0) {
            // fetch the first row of the results of the query
            $row = $users->fetch_assoc();
            $user = $row['username'];

	         if($user) {
				   // Username was found; okay to reset
				   create_reset_token($user);
				   email_username_token($email);
	          } else {
	            // Username was not found; don't do anything
	          }
			 }
	
			// Message returned is the same whether the user 
			// was found or not, so that we don't reveal which 
			// usernames exist and which do not.
			$message = "The username associated with this email account has been emailed.";
		
		} else {
			$message = "Please enter a email.";
		}
  }
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Forgot Username</title>
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
		 <p>Enter your email to retrieve your username.</p>
		 <form role="form" id="reset-password-form" class="form-horizontal login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
			<?php echo csrf_token_tag(); ?>
			<div class="form-group" id="email-input">
				<div class="col-md-12">
					 <label>Email:</label><label class="control-label" id="email-control"></label>
					 <div class="input-group">
						  <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
						  <input type="text" id="email" name="email" class="form-control" value="<?php echo $email; ?>" data-container="body" data-toggle="popover" data-trigger="focus" data-content="must be valid email address" data-parsley-required="true" data-parsley-type="email" data-parsley-length="[8, 32]" data-parsley-group="block1" data-parsley-ui-enabled="false">
					 </div>
				</div>
		  </div>
			<div class="col-md-12">
				<input type="submit" name="submit" value="Submit" class="btn btn-lg btn-block btn-primary"/>
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

                    var email = formInstance.isValid('block1', true);

                    if (email) {
                        return;
                    }

                    // otherwise, stop form submission and mark
                    // required fields with bootstrap
                    formInstance.submitEvent.preventDefault();

                    // show error alert
                    $('#error-alert').removeClass("hidden");

                    /*
                        Input validation rules:
                        - Valid Email Required
                     */
                    if (!email) {
                        $('#email-input').addClass("has-error");
                        $('#email').popover('show');
                    } else {
                        $('#email-input').removeClass("has-error");
                    }
                });
            });
        </script>
  </body>
</html>
