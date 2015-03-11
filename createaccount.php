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
        $passwordError = $confirmError = $emailError =  $companyError = $phoneError = $requiredFields = "";
        // Placeholders for variables from form
        $username = $password = $confirm = $first_name = $last_name = $email = $company = $phone = "";

        // in case form was submitted and the username already exists
        if (isset($usernameError)) {
            $usernameError = "";
        }

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
                $usernameError = "<div class='alert alert-danger' id='username-exists' role='alert'>";
                $usernameError .= "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>";
                $usernameError .= "<span class='sr-only'>Error:</span>";
                $usernameError .= "<span> Username already exists</span>";
                $usernameError .= "</div>";
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
            <div class="alert alert-danger hidden login-error" id="error-alert" role="alert">
                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                <span class="sr-only">Error:</span>
                <span id="list-errors">The following fields have errors:</span>
            </div>
            <?php echo $usernameError; ?>
            <form role="form" id="account-form" class="form-horizontal login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
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
                <div class="form-group" id="username-input">
                    <div class="col-md-12">
                        <label>Username:</label>
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                            <input type="text" name="username" id="username" class="form-control" value="<?php echo $username; ?>" data-container="body" data-toggle="popover" data-trigger="focus" data-content="8 - 16 alphanumeric characters" data-parsley-required="true" data-parsley-type="alphanum" data-parsley-length="[8, 16]" data-parsley-group="block3" data-parsley-ui-enabled="false">
                        </div>
                    </div>
                </div>
                <div class="form-group" id="password-input">
                    <div class="col-md-12">
                        <label>Password:</label>
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                            <input type="password" id="password" name="password" class="form-control" data-container="body" data-toggle="popover" data-trigger="focus" data-content="8 - 16 characters long" data-parsley-required="true" data-parsley-length="[8, 16]" data-parsley-group="block4" data-parsley-ui-enabled="false">
                        </div>
                    </div>
                </div>
                <div class="form-group" id="confirm-input">
                    <div class="col-md-12">
                        <label>Confirm Password:</label>
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                            <input type="password" id="confirm" name="confirm" class="form-control" data-container="body" data-toggle="popover" data-trigger="focus" data-content="must match password" data-parsley-required="true" data-parsley-equalto="#password" data-parsley-length="[8, 16]" data-parsley-group="block5" data-parsley-ui-enabled="false">
                        </div>
                    </div>
                </div>
                <div class="form-group" id="email-input">
                    <div class="col-md-12">
                        <label>Email:</label><label class="control-label" id="email-control"></label>
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
                            <input type="text" id="email" name="email" class="form-control" value="<?php echo $email; ?>" data-container="body" data-toggle="popover" data-trigger="focus" data-content="must be valid email address" data-parsley-required="true" data-parsley-type="email" data-parsley-group="block6" data-parsley-ui-enabled="false">
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
            // activate all popovers
            $(function () {
                $('[data-toggle="popover"]').popover();
            });

            $('#account-form').parsley().subscribe('parsley:form:validate', function (formInstance) {

                var firstName = formInstance.isValid('block1', true);
                var lastName = formInstance.isValid('block2', true);
                var username = formInstance.isValid('block3', true);
                var password = formInstance.isValid('block4', true);
                var confirm = formInstance.isValid('block5', true);
                var email = formInstance.isValid('block6', true);
                var company = formInstance.isValid('block7', true);
                var phone = formInstance.isValid('block8', true);

                if (firstName && lastName && username && password && confirm
                    && email && company && phone) {
                    return;
                }

                // otherwise, stop form submission and mark
                // required fields with bootstrap
                formInstance.submitEvent.preventDefault();

                // show error alert
                $('#error-alert').removeClass("hidden");
                // hide username already exists error
                $('#username-exists').addClass("hidden");

                /*
                    Input validation rules:
                    - All forms required
                    - Username must be alphanumeric characters, 8 to 16 characters long
                    - Password must be 8 to 16 characters long
                    - Confirm password must match password, 8 to 16 characters long
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

                if (!username) {
                    $('#username-input').addClass("has-error");
                    $('#username').popover('show');
                } else {
                    $('#username-input').removeClass("has-error");
                }

                if (!password) {
                    $('#password-input').addClass("has-error");
                    $('#password').popover('show');
                } else {
                    $('#password-input').removeClass("has-error");
                }

                if (!confirm) {
                    $('#confirm-input').addClass("has-error");
                    $('#confirm').popover('show');
                } else {
                    $('#confirm-input').removeClass("has-error");
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