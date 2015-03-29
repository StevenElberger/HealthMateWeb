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
           /* // Grab security functions
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
*/
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
                        <li class="active"><a id="home-link" href="/HealthMateTest/welcome.php">Home<span class="sr-only">(current)</span></a></li>
                        <li id="view-patient" class="dropdown">
                            <a href="#" class="dropdown-toggle view-patient" data-toggle="dropdown" role="button" aria-expanded="false">Patient <span class="caret"></span></a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a id="add-patient" href="#">Add Patient</a></li>
                                <li class="divider"></li>
                                <li id="view-patient-list"><a href="#">View Patient List</a></li>
                            </ul>
                        </li>
                        
                        <!-- Appointments navigation and dropdown -->
                        <li id="view-appointment" class="dropdown">
                           <a href="#" class="dropdown-toggle view-patient" data-toggle="dropdown" role="button" aria-expanded="false">Appointments <span class="caret"></span></a>
                           <ul class="dropdown-menu" role="menu">
                              <li><a id="create-appointment" href="#">New Appointment</a></li>
                              <li class="divider"></li>
                              <li id="view-appointment-list"><a href="#">View Appointments</a></li>
                           </ul>
                        </li>
                        
                        <!-- End of Appointments navigation and dropdown -->
                        
                        <li><a href="/HealthMateTest/welcome.php">Settings</a></li>
                        <li><a href="/HealthMateTest/logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- end navigation bar -->

        <div class="progress progress-striped active hidden" id="progdiv" style="margin-top: -20px;">
            <div class="progress-bar" id="progbar" style="width: 0%"></div>
        </div>

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
                                        <input type="username" id="username" name="username" class="form-control" data-container="body" data-toggle="popover" data-trigger="focus" data-content="8 - 16 alphanumeric characters" data-parsley-required="true" data-parsley-type="alphanum" data-parsley-length="[8, 16]" data-parsley-group="block1" data-parsley-ui-enabled="false">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" id="first-name-input">
                                <div class="col-md-12">
                                    <label>Patient First Name:</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon">FN</span></span>
                                        <input type="text" id="first_name" name="first_name" class="form-control" data-parsley-required="true" data-parsley-group="block2" data-parsley-ui-enabled="false">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" id="last-name-input">
                                <div class="col-md-12">
                                    <label>Patient Last Name:</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon">LN</span></span>
                                        <input type="text" id="last_name" name="last_name" class="form-control" data-parsley-required="true" data-parsley-group="block3" data-parsley-ui-enabled="false">
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
            
            <!-- Create Appointment Form -->
            <div class="panel panel-default hidden" id="create-appointment-panel">
                <div class="panel-body">
                    <h3 class="text-center">Appointment Form</h3>
                    <fieldset>
                        <form role="form" id="create-appointment-form" class="form-horizontal login-form" method="post">
                            <div class="form-group" id="appointment-username-input">
                                <div class="col-md-12">
                                    <label>Patient Username:</label><br />
                                    <select name="appointment_username" id="appointment_username" class="form-control" data-parsley-required="true" data-parsley-group="block14" data-parsley-ui-enabled="false">
													<option selected disabled>Select a Patient</option>
													<option>Other</option>
													<?php
														// Create connection
														$conn = new mysqli("localhost", "root", "#mws1992", "testDB");

														// Check connection
														if ($conn->connect_error) {
															die("Connection failed: " . $conn->connect_error);
														}
				
														// Select all patients that are associated with this doctor
														//$sql = "SELECT * FROM patient WHERE doctor_id =" . $username ."";
														$sql = "SELECT * FROM patient WHERE doctor_id = 'test'";
														
														// Execute Query
														$result = $conn->query($sql);
														
														// Create a selectable option for each patient associated
														// with the current doctor
														foreach($result as $row) {
															$patient_username = $row["username"];
															echo "<option>{$patient_username}</option>";
														}
													?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group" id="appointment-first-name-input">
                                <div class="col-md-12">
                                    <label>Patient First Name:</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon">FN</span></span>
                                        <input type="text" id="appointment_first_name" name="appointment_first_name" class="form-control" data-parsley-required="true" data-parsley-group="block4" data-parsley-ui-enabled="false">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" id="appointment-last-name-input">
                                <div class="col-md-12">
                                    <label>Patient Last Name:</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon">LN</span></span>
                                        <input type="text" id="appointment_last_name" name="appointment_last_name" class="form-control" data-parsley-required="true" data-parsley-group="block5" data-parsley-ui-enabled="false">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" id="appointment-title-input">
                                <div class="col-md-12">
                                    <label>Appointment Title:</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon">T</span></span>
                                        <input type="text" id="appointment_title" name="appointment_title" class="form-control" data-parsley-required="true" data-parsley-group="block6" data-parsley-ui-enabled="false">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" id="appointment-address-input">
                                <div class="col-md-12">
                                    <label>Address:</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
                                        <input type="text" id="appointment_address" name="appointment_address" class="form-control" data-parsley-required="true" data-parsley-group="block7" data-parsley-ui-enabled="false">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" id="appointment-city-input">
                                <div class="col-md-12">
                                    <label>City:</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
                                        <input type="text" id="appointment_city" name="appointment_city" class="form-control" data-parsley-required="true" data-parsley-group="block8" data-parsley-ui-enabled="false">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" id="appointment-zipcode-input">
                                <div class="col-md-12">
                                    <label>Zip Code:</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
                                        <input type="text" id="appointment_zipcode" name="appointment_zipcode" class="form-control" data-container="body" data-toggle="popover" data-trigger="focus" data-content="5 - 9 numerical characters (no spaces)" data-parsley-required="true" data-parsley-type="number" data-parsley-length="[5, 9]" data-parsley-group="block9" data-parsley-ui-enabled="false">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" id="appointment-state-input">
                                <div class="col-md-12">
                                    <label>State:</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
                                        <input type="text" id="appointment_state" name="appointment_state" class="form-control" data-parsley-required="true" data-parsley-group="block10" data-parsley-ui-enabled="false">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" id="appointment-date-input">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <label>Date:</label>
                                        <input type="date" id="appointment_date" style="margin-top: 15px; margin-left: 15px;" class="form-control" data-container="body" data-toggle="popover" data-trigger="focus" data-content="Date must be later than current Date" data-parsley-required="true" data-parsley-group="block11" data-parsley-ui-enabled="false">
                                    </div>
                                 </div>
                            </div>
                            <div class="form-group" id="appointment-starttime-input">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <label>Start Time:</label>
                                        <input type="time" id="appointment_start_time" style="margin-top: 15px; margin-left: 15px;" class="form-control" data-container="body" data-toggle="popover" data-trigger="focus" data-content="Start Time must be before End Time" data-parsley-required="true" data-parsley-group="block12" data-parsley-ui-enabled="false">
                                    </div>
                                 </div>
                            </div>
                            <div class="form-group" id="appointment-endtime-input">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <label>End Time:</label>
                                        <input type="time" id="appointment_end_time" style="margin-top: 15px; margin-left: 15px;" class="form-control" data-container="body" data-toggle="popover" data-trigger="focus" data-content="End Time must be after Start Time" data-parsley-required="true" data-parsley-group="block13" data-parsley-ui-enabled="false">
                                    </div>
                                </div>
                             </div>
                                <div class="col-md-12" style="margin-top: 5%;">
                                    <button type="submit" id="create-appointment-button" class="btn btn-lg btn-block btn-primary validate">Create Appointment</button>
                                </div>
                            </div>
                        </form>
                    </fieldset>
                </div>
            </div>
            <!-- End of create appointment form -->

            <div id="myDiv" class="panel panel-default hidden"></div>

            <div id="results" class="panel panel-default hidden"></div>

        </div>

        <!-- Bootstrap core JavaScript -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <!-- Form validation from Parsley -->
        <script src="js/parsley.min.js"></script>
        <script type="text/javascript">
            function testAJAX() {
                var xmlhttp;
                if (window.XMLHttpRequest) {
                    xmlhttp = new XMLHttpRequest();
                }
                xmlhttp.onreadystatechange = function () {
                    $("#progdiv").fadeIn(400).removeClass('hidden');
                    if (xmlhttp.readyState == 1) {
                        $("#progbar").css("width", "25%");
                    } else if (xmlhttp.readyState == 2) {
                        $("#progbar").css("width", "50%");
                    } else if (xmlhttp.readyState == 3) {
                        $("#progbar").css("width", "75%");
                    }
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        $("#progbar").css("width", "100%");
                        setTimeout(function() {
                            $("#progdiv").fadeOut(800).delay(400).addClass('hidden');
                        }, 1000);
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
            ////////////////////////////////////////////////////////////////////////
            function submiteAppointmentWithAJAX() {
					var xmlhttp;
               if (window.XMLHttpRequest) {
                    xmlhttp = new XMLHttpRequest();
                }
                xmlhttp.onreadystatechange = function () {
                    $("#progdiv").fadeIn(400).removeClass('hidden');
                    if (xmlhttp.readyState == 1) {
                        $("#progbar").css("width", "25%");
                    } else if (xmlhttp.readyState == 2) {
                        $("#progbar").css("width", "50%");
                    } else if (xmlhttp.readyState == 3) {
                        $("#progbar").css("width", "75%");
                    }
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        $("#progbar").css("width", "100%");
                        setTimeout(function() {
                            $("#progdiv").fadeOut(800).delay(400).addClass('hidden');
                        }, 1000);
                        // clear out the form and present the result
                        $("#create-appointment-panel").fadeOut(400);
                        $("#welcome-jumbo").slideUp(400).delay(400).fadeIn(400);
                        $("#results").html(xmlhttp.responseText).fadeIn(800).removeClass('hidden');
                    }
                };
                
                var appointment_username = $("#appointment_username").val();
                // Doctor ID on my machine is underfined
                // change when moved to host machine
                //var doc_id = $("#doctor_id").html();
                var doc_id = "test";
                var first_name = $("#appointment_first_name").val();
                var last_name = $("#appointment_last_name").val();
                var appointment_title = $("#appointment_title").val();
                var appointment_address = $("#appointment_address").val();
                var appointment_city = $("#appointment_city").val();
                var appointment_zipcode = $("#appointment_zipcode").val();
                var appointment_state = $("#appointment_state").val();
                var appointment_date = $("#appointment_date").val();
                var appointment_start_time = $("#appointment_start_time").val();
                var appointment_end_time = $("#appointment_end_time").val();
                xmlhttp.open("POST","create_appointment.php",true);
                // HTTP header required for POST
                xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");

                xmlhttp.send("appointment_username=" + appointment_username + "&doctor_id=" + doc_id + "&first_name=" + first_name + "&last_name=" + last_name + 
                "&appointment_title=" + appointment_title + "&appointment_address=" + appointment_address + "&appointment_city=" + appointment_city + 
                "&appointment_zipcode=" + appointment_zipcode + "&appointment_state=" + appointment_state + "&appointment_date=" + appointment_date + 
                "&appointment_start_time=" + appointment_start_time + "&appointment_end_time=" + appointment_end_time);
					
				}
				/////////////////////////////////////////////////////////////////////////////
				function viewAppointmentsAJAX() {
                var xmlhttp;
                if (window.XMLHttpRequest) {
                    xmlhttp = new XMLHttpRequest();
                }
                xmlhttp.onreadystatechange = function () {
                    $("#progdiv").fadeIn(400).removeClass('hidden');
                    if (xmlhttp.readyState == 1) {
                        $("#progbar").css("width", "25%");
                    } else if (xmlhttp.readyState == 2) {
                        $("#progbar").css("width", "50%");
                    } else if (xmlhttp.readyState == 3) {
                        $("#progbar").css("width", "75%");
                    }
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        $("#progbar").css("width", "100%");
                        setTimeout(function() {
                            $("#progdiv").fadeOut(800).delay(400).addClass('hidden');
                        }, 1000);
                        // clear out the form and present the result
                        $("#welcome-container").fadeOut(400);
                        $("#create-appointment-panel").fadeOut(400);
                        $("#welcome-jumbo").slideUp(400).delay(400).fadeIn(400);
                        $("#results").html(xmlhttp.responseText).fadeIn(800).removeClass('hidden');
                    }
                };
                var doc_id = $("#doctor_id").html();
                xmlhttp.open("POST","view_appointment_list.php",true);
                // HTTP header required for POST
                xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
                xmlhttp.send("doctor_id=" + doc_id);
            }

            $(document).ready(function(){

                // activate all popovers
                $(function () {
                    $('[data-toggle="popover"]').popover();
                });

                // show the welcome screen
                $("#welcome-jumbo").fadeIn(800).removeClass('hidden');
                

                // animate the jumbo to close and re-open with appropriate contents
                // when clicking on the add patient link
                $("#add-patient").click(function(){
						  $('#add-patient-form').show();
						  $("#create-appointment-form").hide();
						  $("#create-appointment-panel").hide();
                    $("#welcome-container").fadeOut(400);
                    $("#welcome-jumbo").slideUp(200).delay(400).fadeIn(400);
                    $("#results").fadeOut(400).delay(1000).addClass('hidden').empty();
                    // reset the form and errors
                    $("#add-patient-form").trigger("reset");
                    $("#username-input").removeClass("has-error");
                    $("#first-name-input").removeClass("has-error");
                    $("#last-name-input").removeClass("has-error");
                    // ugly, but it works
                    $("#add-patient-panel").fadeIn(800).removeClass('hidden');
                });

                $('#add-patient-form').parsley().subscribe('parsley:form:validate', function (formInstance) {

                    var username = formInstance.isValid('block1', true);
                    var firstName = formInstance.isValid('block2', true);
                    var lastName = formInstance.isValid('block3', true);

                    if (firstName && lastName && username) {
                        // submit form with AJAX
                        testAJAX();
                        event.preventDefault();
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
                });
                
                // Appointment Creation Form
                $("#create-appointment").click(function(){
						  $("#create-appointment-form").show();
						  $("#add-patient-form").hide();
						  $("#add-patient-panel").hide();
                    $("#welcome-container").fadeOut(400);
                    $("#welcome-jumbo").slideUp(200).delay(400).fadeIn(400);
                    $("#results").fadeOut(400).delay(1000).addClass('hidden').empty();
                    // reset the form and errors
                    $("#create-appointment-form").trigger("reset");
                    $("#appointment-username-input").removeClass("has-error");
                    $("#appointment-first-name-input").removeClass("has-error");
                    $("#appointment-last-name-input").removeClass("has-error");
                    $("#appointment-title-input").removeClass("has-error");
                    $("#appointment-address-input").removeClass("has-error");
                    $("#appointment-city-input").removeClass("has-error");
                    $("#appointment-zipcode-input").removeClass("has-error");
                    $("#appointment-state-input").removeClass("has-error");
                    $("#appointment-date-input").removeClass("has-error");
                    $("#appointment-starttime-input").removeClass("has-error");
                    $("#appointment-endtime-input").removeClass("has-error");
                    // ugly, but it works
                    $("#create-appointment-panel").fadeIn(800).removeClass('hidden');
                });
                
                $('#create-appointment-form').parsley().subscribe('parsley:form:validate', function (formInstance) {

                    var firstName = formInstance.isValid('block4', true);
                    var lastName = formInstance.isValid('block5', true);
                    var appointmentTitle = formInstance.isValid('block6', true);
                    var address = formInstance.isValid('block7', true);
                    var city = formInstance.isValid('block8', true);
                    var zipCode = formInstance.isValid('block9', true);
                    var state = formInstance.isValid('block10', true);
                    var date = formInstance.isValid('block11', true);
                    var startTime = formInstance.isValid('block12', true);
                    var endTime = formInstance.isValid('block13', true);
                    var appointmentUsername = formInstance.isValid('block14', true);
                    
                    // check if the date the user entered is before current date
                    
                    var dateString = $("#appointment_date").val();
                    var dateArray = dateString.split("-");
                    var day = parseInt(dateArray[2]);
                    var month = parseInt(dateArray[1]) - 1;
                    var year = parseInt(dateArray[0]);
                    var inputDate = new Date(year, month, day);
                    var currentDate = new Date();
                    var dateValid = false;
                    
                    if (date && (inputDate.setHours(0, 0, 0, 0) >= currentDate.setHours(0, 0, 0, 0))) {
							  dateValid = true;
						  }
						  
						  var validInputTime = false;
                    
                    // Check if the user entered a valid end time
                    if (startTime && endTime) {
								var time = $("#appointment_start_time").val();
								var startTime = time.split(":");
								time = $("#appointment_end_time").val();
								var endTime = time.split(":");
								var startDate = new Date(year, month, day);
								var endDate = new Date(year, month, day);
                    
								startDate.setHours(startTime[0], startTime[1], 0, 0);
								endDate.setHours(endTime[0], endTime[1], 0, 0);
							
								if (startDate <= endDate) {
									validInputTime = true;
								}
							}


                    if (firstName && lastName && appointmentTitle && address && city && zipCode &&
									state && date && dateValid && startTime && endTime && validInputTime && appointmentUsername) {
                        // submit form with AJAX
                        submiteAppointmentWithAJAX();
                        event.preventDefault();
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
                     - Username must be alphanumeric characters, 8 to 16 characters long
                     */
                     if (!appointmentUsername) {
								$('#appointment-username-input').addClass("has-error");
                    } else {
                        $('#appointment-username-input').removeClass("has-error");
                    }
                    
                    if (!firstName) {
                        $('#appointment-first-name-input').addClass("has-error");
                    } else {
                        $('#appointment-first-name-input').removeClass("has-error");
                    }
                    
                    if (!lastName) {
                        $('#appointment-last-name-input').addClass("has-error");
                    } else {
                        $('#appointment-last-name-input').removeClass("has-error");
                    }

                    if (!appointmentTitle) {
                        $('#appointment-title-input').addClass("has-error");
                    } else {
                        $('#appointment-title-input').removeClass("has-error");
                    }
                    
                    if (!address) {
                        $('#appointment-address-input').addClass("has-error");
                    } else {
                        $('#appointment-address-input').removeClass("has-error");
                    }

                    if (!city) {
                        $('#appointment-city-input').addClass("has-error");
                    } else {
                        $('#appointment-city-input').removeClass("has-error");
                    }

                    if (!zipCode) {
                        $('#appointment-zipcode-input').addClass("has-error");
                        $('#appointment_zipcode').popover('show');
                    } else {
                        $('#appointment-zipcode-input').removeClass("has-error");
                    }
                    
                    if (!state) {
                        $('#appointment-state-input').addClass("has-error");
                    } else {
                        $('#appointment-state-input').removeClass("has-error");
                    }
                    
                    if (!date || !dateValid) {
                        $('#appointment-date-input').addClass("has-error");
                        $('#appointment_date').popover('show');
                    } else {
                        $('#appointment-date-input').removeClass("has-error");
                    }

                    if (!startTime) {
                        $('#appointment-starttime-input').addClass("has-error");
                        $('#appointment_start_time').popover('show');
                    } else {
                        $('#appointment-starttime-input').removeClass("has-error");
                    }
                    
                    if (!endTime || !validInputTime) {
                        $('#appointment-endtime-input').addClass("has-error");
                        $('#appointment_end_time').popover('show');
                    } else {
                        $('#appointment-endtime-input').removeClass("has-error");
                    }
                });
                
                $("#view-appointment-list").click(function() {
						 $("#results").fadeOut(400).delay(1000).addClass('hidden').empty();
						 viewAppointmentsAJAX();
					 });
            });
        </script>
    </body>
</html>
