<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>HealthMate</title>

        <!-- Bootstrap core CSS -->
        <link href="newcss/bootstrap.css" type="text/css" rel="stylesheet">

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
                    <li class="active"><a href="#">Home<span class="sr-only">(current)</span></a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Patient <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#">Add Patient</a></li>
                            <li class="divider"></li>
                            <li><a href="#">View Patient List</a></li>
                        </ul>
                    </li>
                    <li><a href="#">Settings</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- end navigation bar -->
    <!-- Bootstrap core JavaScript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    </body>
</html>