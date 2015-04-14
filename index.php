<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>HealthMate Login</title>

    <!-- Bootstrap core CSS -->
    <link href="newcss/bootstrap.css" type="text/css" rel="stylesheet">

    <!-- Custom CSS for Login -->
    <link href="newcss/login.css" type="text/css" rel="stylesheet">

</head>

<body>

<?php
// Grab security functions
require_once("/private/initialize.php");
// Error placeholders
$usernameError = $passwordError = "";
// Authentication placeholders
$username = $password = "";
$bad_authentication = "";
$message = "";
session_start();
// taken from Michael's example
// tons of security checks up in this house!
if(request_is_post() && request_is_same_domain()) {
    $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];
    if(!csrf_token_is_valid()) {
        $message = "Sorry, request was not valid. 1 ";
    } else if (!csrf_token_is_recent()) {
        $message = "Sorry, request was not valid. 2 ";
    } else {
        // CSRF tests passed--form was created by us recently.
        if (empty($_POST["username"])) {
            $usernameError = "Please enter a username";
        } else {
            $username = test_input($_POST["username"]);
        }
        if (empty($_POST["password"])) {
            $passwordError = "Please enter a password";
        } else {
            $password = test_input($_POST["password"]);
        }
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
        // check to make sure username actually exists
        if (username_exists($username, $conn)) {
            // then check if the user is throttled
            $throttle_delay = throttle_failed_logins($username);
            if($throttle_delay > 0) {
                // Throttled at the moment, try again after delay period
                //$message = "Too many failed logins.";
                //$message .= "You must wait {$throttle_delay} minutes before you can attempt another login.";
                $bad_authentication = "<div class='alert alert-danger login-error' role='alert'>";
                $bad_authentication .= "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>";
                $bad_authentication .= "<span class='sr-only'>Error:</span>";
                $bad_authentication .= "<span>Too many failed logins.</span>";
                $bad_authentication .= "<span>You must wait {$throttle_delay} minutes before you can attempt another login.</span>";
                $bad_authentication .= "</div>";
            } else {
                // not throttled - make connection to db
                // and check the credentials
                if (($username !== "") && ($password !== "")) {
                    // Create connection
                    $conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
                    // Check connection
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }
                    // Grab the password for the given username
                    $sql = "SELECT password FROM physician WHERE username = '" . $username . "'";
                    $result = $conn->query($sql);
                    // If there's a match, check to make sure authentication was successful
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        // We know the username matches so check the password against the hash
                        if (password_verify($password, $row["password"])) {
                            // Initialize session data and
                            // redirect user to the welcome page
                            $_SESSION["username"] = $username;
                            clear_failed_login($username);
                            after_successful_login();
                            echo header("Location: /HealthMateTest/welcome.php");
                        } else {
                            record_failed_login($username);
                            // Don't let the user know which piece of data was incorrect
                            $bad_authentication = "<div class='alert alert-danger login-error' role='alert'>";
                            $bad_authentication .= "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>";
                            $bad_authentication .= "<span class='sr-only'>Error:</span>";
                            $bad_authentication .= "<span> Incorrect username or password</span>";
                            $bad_authentication .= "</div>";
                        }
                    } else {
                        record_failed_login($username);
                        $bad_authentication = "<div class='alert alert-danger' role='alert'>";
                        $bad_authentication .= "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>";
                        $bad_authentication .= "<span class='sr-only'>Error:</span>";
                        $bad_authentication .= "<span> Incorrect username or password</span>";
                        $bad_authentication .= "</div>";
                    }
                    $conn->close();
                }
            }
        } else {
            // no such username
            $bad_authentication = "<div class='alert alert-danger' role='alert'>";
            $bad_authentication .= "<span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span>";
            $bad_authentication .= "<span class='sr-only'>Error:</span>";
            $bad_authentication .= "<span> Incorrect username or password</span>";
            $bad_authentication .= "</div>";
        }
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
        <h1 class="text-center">HealthMate</h1>
        <?php echo $bad_authentication; ?>
        <form role="form" id="login-form" class="form-horizontal login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <?php echo csrf_token_tag(); ?>
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
                <div class="col-md-12" style="margin-top: 5%;">
                    <button type="submit" class="btn btn-lg btn-block btn-primary validate">Login</button>
        </form>
        <form action="createaccount.php">
            <button type="submit" class="btn btn-lg btn-block btn-default">Create Account</button>
        </form>
        <a class="text-center" style="display: block;" href="public/forgot_username.php">Forgot your username?</a>
        <a class="text-center" style="display: block;" href="public/forgot_password.php">Forgot your password?</a>
</div>
</div>
</fieldset>
</div><!-- /.container -->


<!-- Bootstrap core JavaScript -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<!-- Form validation from Parsley -->
<script src="js/parsley.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#login-form').parsley().subscribe('parsley:form:validate', function (formInstance) {
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
