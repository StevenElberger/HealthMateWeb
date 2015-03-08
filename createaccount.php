<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Create an Account</title>

        <!-- Bootstrap core CSS -->
        <link href="newcss/bootstrap.css" type="text/css" rel="stylesheet">

        <!-- Custom CSS for Login -->
        <link href="newcss/login.css" type="text/css" rel="stylesheet">

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

    <div class="well login-well">
        <fieldset>
            <h2 class="text-center">Create an Account</h2>
            <div class="has-error login-error"><h4 class="text-center"><label class="control-label"><?php //echo $bad_authentication; ?></label></h4></div>
            <form role="form" id="account-form" class="form-horizontal login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                <div class="form-group" id="username-input">
                    <div class="col-md-12">
                        <label>First Name:</label>
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon">FN</span></span>
                            <input type="username" name="username" class="form-control" data-parsley-required="true" data-parsley-group="block1" data-parsley-ui-enabled="false">
                        </div>
                    </div>
                </div>
                <div class="form-group" id="username-input">
                    <div class="col-md-12">
                        <label>Last Name:</label>
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon">LN</span></span>
                            <input type="username" name="username" class="form-control" data-parsley-required="true" data-parsley-group="block1" data-parsley-ui-enabled="false">
                        </div>
                    </div>
                </div>
                <div class="form-group" id="username-input">
                    <div class="col-md-12">
                        <label>Username:</label>
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                            <input type="username" name="username" class="form-control" data-parsley-required="true" data-parsley-group="block1" data-parsley-ui-enabled="false">
                        </div>
                    </div>
                </div>
                <div class="form-group" id="password-input">
                    <div class="col-md-12">
                        <label>Password:</label>
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                            <input type="password" name="password" class="form-control" data-parsley-required="true" data-parsley-group="block2" data-parsley-ui-enabled="false">
                        </div>
                    </div>
                </div>
                <div class="form-group" id="confirm-password-input">
                    <div class="col-md-12">
                        <label>Confirm Password:</label>
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                            <input type="password" name="password" class="form-control" data-parsley-required="true" data-parsley-group="block2" data-parsley-ui-enabled="false">
                        </div>
                    </div>
                </div>
                <div class="form-group" id="email-input">
                    <div class="col-md-12">
                        <label>Email:</label>
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
                            <input type="password" name="password" class="form-control" data-parsley-required="true" data-parsley-group="block2" data-parsley-ui-enabled="false">
                        </div>
                    </div>
                </div>
                <div class="form-group" id="company-input">
                    <div class="col-md-12">
                        <label>Company:</label>
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-globe"></span></span>
                            <input type="password" name="password" class="form-control" data-parsley-required="true" data-parsley-group="block2" data-parsley-ui-enabled="false">
                        </div>
                    </div>
                </div>
                <div class="form-group" id="company-input">
                    <div class="col-md-12">
                        <label>Phone:</label>
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-phone"></span></span>
                            <input type="password" name="password" class="form-control" data-parsley-required="true" data-parsley-group="block2" data-parsley-ui-enabled="false">
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-lg btn-block btn-primary validate">Create Account</button>
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
            $('#account-form').parsley().subscribe('parsley:form:validate', function (formInstance) {

                // make sure both username and password are provided
                if (formInstance.isValid('block1', true) && formInstance.isValid('block2', true)) {
                    return;
                }

                // otherwise, stop form submission and mark
                // required fields with bootstrap
                formInstance.submitEvent.preventDefault();

                // if one was supplied, but not the other
                // remove the error class from the valid input
                if (!formInstance.isValid('block1', true)) {
                    $('#username-input').addClass("has-error");
                } else {
                    $('#username-input').removeClass("has-error");
                }

                if (!formInstance.isValid('block2', true)) {
                    $('#password-input').addClass("has-error");
                } else {
                    $('#password-input').removeClass("has-error");
                }
            });
        });
    </script>
    </body>
</html>