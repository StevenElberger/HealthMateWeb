<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>HealthMate</title>

        <!-- Bootstrap core CSS -->
        <link href="newcss/bootstrap.css" type="text/css" rel="stylesheet">

        <!-- Custom CSS for welcome page -->
        <link href="newcss/welcome.css" type="text/css" rel="stylesheet">

        <?php
            // Grab security functions
            require_once("/private/initialize.php");
            session_start();
            // Make sure the session is still active
            validate_user_before_displaying();
			$username = $_SESSION["username"];

            // Check if logout button was pressed
            if (isset($_POST['logout'])) {
                after_successful_logout();
                echo header("Location: /HealthMateTest/index.php");
            }

			$patient_info = "";

			// Only process POST requests, not GET
			if ($_SERVER["REQUEST_METHOD"] == "POST") {

				// Create connection
                $conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

				// Check connection
				if ($conn->connect_error) {
					die("Connection failed: " . $conn->connect_error);
				}
				
				// For now, all patients will be shown (table needs to be re-designed
				$sql = "SELECT * FROM patient WHERE group_id = 2";
				$result = $conn->query($sql);
				
				// Generate the proper HTML elements and display
				// all the patient info
				if ($result->num_rows > 0) {
					// output each row
					while ($row = $result->fetch_assoc()) {
						$patient_info .= "<section class='container'>";
						$patient_info .= "<div class='login'>";
						$patient_info .= "Patient ID: " . $row["patient_id"] . "<br />";
						$patient_info .= "First name: " . $row["first_name"] . "<br />";
						$patient_info .= "Last name: " . $row["last_name"] . "<br />";
						$patient_info .= "Gender: " . $row["gender"] . "<br />";
						$patient_info .= "Birthday: " . $row["birthday"] . "<br />";
						$patient_info .= "</div>";
						$patient_info .= "</section>";
					}
				} else {
					echo "Error: " . $sql . "<br />" . $conn->error;
				}
			
				// Peace out
				$conn->close();
			}

		?>
	</head>
    <body>
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
                    <a class="navbar-brand" href="/HealthMateTest/welcome.php">HealthMate</a>
                </div>

                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="/HealthMateTest/welcome.php">Home<span class="sr-only">(current)</span></a></li>
                        <li id="view-patient" class="dropdown">
                            <a href="#" class="dropdown-toggle view-patient" data-toggle="dropdown" role="button" aria-expanded="false">Patient <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a id="add-patient" href="#">Add Patient</a></li>
                                <li class="divider"></li>
                                <li id="view-patient-list"><a href="#">View Patient List</a></li>
                            </ul>
                        </li>
                        <li><a href="/HealthMateTest/welcome.php">Settings</a></li>
                        <li><a href="/HealthMateTest/logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- end navigation bar -->

        <div class="jumbotron welcome-jumbo hidden" id="welcome-jumbo">
            <!-- Contains the welcome information -->
            <div class="container" id="welcome-container">
                <div class="alert alert-dismissible alert-info">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span><strong> Warning!</strong>
                    Please note that HealthMate is currently being developed so some functionality may be missing!
                </div>

                <h1>Welcome, <span id="doctor_id"><?php echo $username; ?></span>!</h1>

                <div class="panel panel-default">
                    <div class="panel-body">
                        This is the HealthMate welcome page. From here you can view your patient list, modify patient information, change your settings, and more!
                    </div>
                </div>
            </div>
            <!-- End of welcome -->

            <!-- Add patient form -->
            <div class="panel panel-default hidden" id="add-patient-panel">
                <div class="panel-body">
                    <h3 class="text-center">Patient Form</h3>
                    <fieldset>
                        <form role="form" id="add-patient-form" class="form-horizontal login-form" method="post">
                            <div class="form-group" id="username-input">
                                <div class="col-md-12">
                                    <label>Patient Username:</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                                        <input type="username" id="username" name="username" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" id="first-name-input">
                                <div class="col-md-12">
                                    <label>Patient First Name:</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon">FN</span></span>
                                        <input type="text" id="first_name" name="first_name" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" id="last-name-input">
                                <div class="col-md-12">
                                    <label>Patient Last Name:</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon">LN</span></span>
                                        <input type="text" id="last_name" name="last_name" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" id="age-input">
                                <div class="col-md-12">
                                    <div class="input-group" style="padding-top: 15px;">
                                        <label>Patient Gender:</label>
                                        <select name="gender" id="gender" class="form-control-static" style="margin-left: 15px;">
                                            <option>Male</option>
                                            <option>Female</option>
                                            <option>Other</option>
                                        </select>
                                    </div>
                                    <div class="input-group">
                                        <label>Patient Birthday:</label>
                                        <input type="date" id="birthday" style="margin-top: 15px; margin-left: 15px;">
                                    </div>
                                </div>
                                <div class="col-md-12" style="margin-top: 5%;">
                                    <button type="submit" id="add-patient-button" class="btn btn-lg btn-block btn-primary validate">Add Patient</button>
                                </div>
                            </div>
                        </form>
                    </fieldset>
                </div>
            </div>
            <!-- End of add patient -->

            <div id="myDiv" class="panel panel-default hidden"></div>

            <div id="results" class="panel panel-default hidden"></div>

        </div>

        <!-- Bootstrap core JavaScript -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script type="text/javascript">
            function testAJAX() {
                var xmlhttp;
                if (window.XMLHttpRequest) {
                    xmlhttp = new XMLHttpRequest();
                }
                xmlhttp.onreadystatechange = function () {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        // clear out the form and present the result
                        $("#add-patient-panel").fadeOut(400);
                        $("#welcome-jumbo").slideUp(400).delay(400).fadeIn(400);
                        $("#results").html(xmlhttp.responseText).fadeIn(800).removeClass('hidden');
                    }
                };
                var doc_id = $("#doctor_id").html();
                var username = $("#username").val();
                var first_name = $("#first_name").val();
                var last_name = $("#last_name").val();
                var gender = $("#gender").val();
                var birthday = $("#birthday").val();
                var password = "password321";
                xmlhttp.open("POST","createaccount2.php",true);
                // HTTP header required for POST
                xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
                xmlhttp.send("doctor_id=" + doc_id + "&username=" + username + "&first_name=" + first_name +
                            "&last_name=" + last_name + "&gender=" + gender + "&birthday=" + birthday + "&password=" + password);
            }

            $(document).ready(function(){
                $("#welcome-jumbo").fadeIn(800).removeClass('hidden');

                $("#add-patient-form").submit(function(event) {
                    testAJAX();
                    event.preventDefault();
                });

                $("#add-patient").click(function(){
                    $("#welcome-container").fadeOut(400);
                    $("#welcome-jumbo").slideUp(400).delay(400).fadeIn(400);
                    // ugly, but it works
                    $("#add-patient-panel").fadeIn(800).removeClass('hidden');
                });
            });
        </script>
    </body>
</html>