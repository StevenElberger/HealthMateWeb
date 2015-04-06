<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Account Settings</title>

        <!-- Bootstrap core CSS -->
        <link href="newcss/bootstrap.css" type="text/css" rel="stylesheet">

        <!-- Custom CSS for Login -->
        <link href="newcss/login.css" type="text/css" rel="stylesheet">

    </head>

    <body>
        <?php
        // Grab security functions
        require_once("/private/initialize.php");

        session_start();

        // validate that the user is logged in and the
        // session is valid
        validate_user_before_displaying();
        
        // Flag for first load
        $firstLoad = true;
        
        // Error placeholders
        $firstNameError = $lastNameError =  "";
        $emailError =  $companyError = $phoneError = $requiredFields = "";
        
        // Placeholders for variables from form
        $first_name = $last_name = $email = $company = $phone = "";
        $username = $_SESSION["username"];

        // Check if this is the first time the user enters this form
        // Update is set when the user clicks update in the account settings form
        if (empty($_POST["update"])) {
			  
			  // Create connection
            $conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            
            
            // Grab all the information that is stored with this account at
            // registration
            $sql = "SELECT * FROM physician WHERE username='".$username."'";
            
            $results = $conn->query($sql);
            
            // If the query was executed properly, fill the form
            // with the existing values in the appropriate locations 
            if ($results->num_rows > 0) {
					
					$row = $results->fetch_assoc();
					
					$first_name = $row["first_name"];
					$last_name = $row["last_name"];
					$email = $row["email"];
					$company = $row["company"];
					$phone = $row["phone"];
					
					} else {
						echo "Error: " . $sql . "<br />" . $conn->error;
               }

            // Close the database connection
            $conn->close();
		  }
		  
		  // Only accept POST Requests
        else if ($_SERVER["REQUEST_METHOD"] == "POST") {
			  
            $firstLoad = false;
            // Check that the required fields have been set
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

            if (empty($_POST["email"])) {
                $emailError = "*";
            } else {
                $email = test_input($_POST["email"]);
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
        }

        // As long as all variables were initialized, the data is good to go
        if (($first_name !== "") && ($last_name !== "") && ($company !== "") && ($phone !== "") && !empty($_POST["update"])) {

            // Create connection
            $conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Update the user account with form data into the physician table of the database
            $sql = "UPDATE physician SET first_name='".$first_name."', last_name='".$last_name."', company='".$company."', phone='".
            $phone."', email='".$email."' WHERE username='".$username."'";
            
            if ($conn->query($sql) === TRUE) {
					
                // Redirect upon successful account update
                header("Location: /HealthMateTest/welcome.php");
                
            } else {
					
					// Error in Update
                echo "Error: " . $sql . "<br />" . $conn->error;
                
            }

            // Close the database connection
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

?>

<!-- Account Settings Form -->
    <div class="well login-well">
        <fieldset>
            <h2 class="text-center">Account Settings</h2>
            <div class="alert alert-danger hidden login-error" id="error-alert" role="alert">
                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                <span class="sr-only">Error:</span>
                <span id="list-errors">The following fields have errors:</span>
            </div>
            <form role="form" id="account-settings-form" class="form-horizontal login-form" action="#" method="post">
                <div class="form-group" id="first-name-input">
                    <div class="col-md-12">
                        <label>First Name:</label>
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon">FN</span></span>
                            <input type="text" name="first_name" id="first-name" class="form-control" data-toggle="tooltip" data-placement="right" title="Wenis" value="<?php echo $first_name; ?>" data-parsley-required="true" data-parsley-group="block1" data-parsley-ui-enabled="false">
                        </div>
                    </div>
                </div>
                <div class="form-group" id="last-name-input">
                    <div class="col-md-12">
                        <label>Last Name:</label>
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon">LN</span></span>
                            <input type="text" name="last_name" id="last-name" class="form-control" value="<?php echo $last_name; ?>" data-parsley-required="true" data-parsley-group="block2" data-parsley-ui-enabled="false">
                        </div>
                    </div>
                </div>
                <div class="form-group" id="email-input">
                    <div class="col-md-12">
                        <label>Email:</label><label class="control-label" id="email-control"></label>
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
                            <input type="text" id="email" name="email" class="form-control" value="<?php echo $email; ?>" data-container="body" data-toggle="popover" data-trigger="focus" data-content="must be valid email address" data-parsley-required="true" data-parsley-type="email" data-parsley-length="[8, 32]" data-parsley-group="block6" data-parsley-ui-enabled="false">
                        </div>
                    </div>
                </div>
                <div class="form-group" id="company-input">
                    <div class="col-md-12">
                        <label>Company:</label>
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-globe"></span></span>
                            <input type="text" id="company" name="company" class="form-control" value="<?php echo $company; ?>" data-parsley-required="true" data-parsley-group="block7" data-parsley-ui-enabled="false">
                        </div>
                    </div>
                </div>
                <div class="form-group" id="phone-input">
                    <div class="col-md-12">
                        <label>Phone:</label>
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-phone"></span></span>
                            <input type="text" id="phone" name="phone" class="form-control" value="<?php echo $phone; ?>" data-container="body" data-toggle="popover" data-trigger="focus" data-content="7 - 10 digits" data-parsley-required="true" data-parsley-type="digits" data-parsley-length="[7, 10]" data-parsley-group="block8" data-parsley-ui-enabled="false">
                        </div>
                        <input type="hidden" name="update" value="true"/>
                        <button type="submit" style="margin-top: 5%;" class="btn btn-lg btn-block btn-primary validate">Update Account</button>
                    </div>
                </div>
            </form>
        </fieldset>
    </div><!-- /.container -->

    <!-- Bootstrap core JavaScript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!-- Form validation from Parsley -->
    <script src="js/parsley.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            // activate all popovers
            $(function () {
                $('[data-toggle="popover"]').popover();
            });

            $('#account-settings-form').parsley().subscribe('parsley:form:validate', function (formInstance) {

                var firstName = formInstance.isValid('block1', true);
                var lastName = formInstance.isValid('block2', true);
                var email = formInstance.isValid('block6', true);
                var company = formInstance.isValid('block7', true);
                var phone = formInstance.isValid('block8', true);
                
                // If all the required fields are given and valid,
                // submit
                if (firstName && lastName && email && company && phone) {
                    return;
                }

                // otherwise, stop form submission and mark
                // required fields with bootstrap
                formInstance.submitEvent.preventDefault();

                // show error alert
                $('#error-alert').removeClass("hidden");

                /*
                    Input validation rules:
                    - All forms required
                    - E-mail must be a valid e-mail address
                    - Phone number must be digits only, length 7 to 10
                 */
                 
                if (!firstName) {
                    $('#first-name-input').addClass("has-error");
                } else {
                    $('#first-name-input').removeClass("has-error");
                }

                if (!lastName) {
                    $('#last-name-input').addClass("has-error");
                } else {
                    $('#last-name-input').removeClass("has-error");
                }

                if (!email) {
                    $('#email-input').addClass("has-error");
                    $('#email').popover('show');
                } else {
                    $('#email-input').removeClass("has-error");
                }

                if (!company) {
                    $('#company-input').addClass("has-error");
                } else {
                    $('#company-input').removeClass("has-error");
                }

                if (!phone) {
                    $('#phone-input').addClass("has-error");
                    $('#phone').popover('show');
                } else {
                    $('#phone-input').removeClass("has-error");
                }
            });
        });
    </script>
    </body>
</html>
