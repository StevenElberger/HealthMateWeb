<?php
	 // Grab security functions
    require_once("/private/initialize.php");
    
   // Error placeholders
    $firstNameError = $lastNameError = $appointmentUsernameError = "";
    $appointmentTitleError = $addressError = $cityError = $zipCodeError = "";
    $stateError = $dateError = $startTimeError = $endTimeError = $doctor_idError = "";
    // Placeholders for variables from form
    $doctor_id = $appointment_username = $first_name = $last_name = "";
    $appointment_title = $address = $city = $zip_code = "";
    $state = $date = $start_time = $end_time = "";

    // Return string
    $result = "";

    // Only process POST requests, not GET
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        // Check that the required fields have been set
        if (empty($_POST["doctor_id"])) {
            $doctor_idError = "*";
        } else {
            $doctor_id = test_input($_POST["doctor_id"]);
        }

        if (empty($_POST["appointment_username"])) {
            $appointmentUsernameError = "*";
        } else {
            $appointment_username = test_input($_POST["appointment_username"]);
        }
        
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

        if (empty($_POST["appointment_title"])) {
            $appointmentTitleError = "*";
        } else {
            $appointment_title = test_input($_POST["appointment_title"]);
        }

        if (empty($_POST["appointment_address"])) {
            $addressError = "*";
        } else {
            $address = test_input($_POST["appointment_address"]);
        }
        
        if (empty($_POST["appointment_city"])) {
            $cityError = "*";
        } else {
            $city = test_input($_POST["appointment_city"]);
        }

        if (empty($_POST["appointment_zipcode"])) {
            $zipCodeError = "*";
        } else {
            $zip_code = test_input($_POST["appointment_zipcode"]);
        }

        if (empty($_POST["appointment_state"])) {
            $stateError = "*";
        } else {
            $state = test_input($_POST["appointment_state"]);
        }
        
        if (empty($_POST["appointment_date"])) {
            $dateError = "*";
        } else {
            $date = test_input($_POST["appointment_date"]);
        }

        if (empty($_POST["appointment_start_time"])) {
            $startTimeError = "*";
        } else {
            $start_time = test_input($_POST["appointment_start_time"]);
        }

        if (empty($_POST["appointment_end_time"])) {
            $endTimeError = "*";
        } else {
            $end_time = test_input($_POST["appointment_end_time"]);
        }
    }

    // As long as all variables were initialized, the data is good to go
    if (($first_name !== "") && ($last_name !== "") && ($appointment_username !== "") && ($doctor_id !== "")
    && ($appointment_title !== "") && ($address !== "") && ($city !== "") && ($zip_code !== "")
    && ($state !== "") && ($date !== "") && ($start_time !== "") && ($end_time !== "")) {

        // Create connection
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // new stuff -- get actual patient_id
        $get_patient_id_sql = "SELECT patient_id FROM patient WHERE first_name = '".$first_name."' AND last_name = '".$last_name."'";

        $patient_id_result = $conn->query($get_patient_id_sql);

        $patient_id = "";

        if ($patient_id_result->num_rows > 0) {
            while ($row = $patient_id_result->fetch_assoc()) {
                $patient_id = $row["patient_id"];
            }
        }



        // Adds a new appointment with form data into the appointments table of the database
        $sql = "INSERT INTO appointments (doctor_id, patient_id, first_name, last_name, title, address, city, zip, state, date, start, end)
         VALUES ('".$doctor_id."', '".$patient_id."', '".$first_name."', '".$last_name."', '".$appointment_title."', '"
         .$address."', '".$city."', '".$zip_code."', '".$state."', '".$date."', '".$start_time."', '".$end_time."')";


         if ($conn->query($sql) === TRUE) {

            $sql_get_appointments = "SELECT * FROM appointments WHERE doctor_id='" . $doctor_id . "' AND patient_id ='" . $patient_id .
												"' AND title = '" . $appointment_title . "'";
												
            $get_appointments = $conn->query($sql_get_appointments);

				// If the appointment was not added successfully, display an error
            if ($get_appointments->num_rows == 0) {
                $result = "ERROR";
                echo $result;
                return;
            }
            
            // If the appointment was added successfully, display the information about the
            // appointment in a table
            $result = "<h3 class='text-center'>Appointment Created Successfully</h3>";
            $result .= "<table class='table table-striped table-hover'>";
            $result .= "<thead>
                    <tr>
                        <th>AID #</th>
                        <th>Patient Name</th>
                        <th>Appointment Title</th>
                        <th>Date</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Location</th>
                    </tr>
                    </thead>
                    <tbody>";       
            foreach ($get_appointments as $row) {
				$appointment_id = $row["appointment_id"];
				$patient_name = $row["first_name"] . " " . $row["last_name"];
				$title = $row["title"];
				$date = $row["date"];
				$start_time = $row["start"];
				$end_time = $row["end"];
				$location = $row["address"] . ", " . $row["city"] . ", " . $row["state"] . " " . $row["zip"];
				
            $result .= "<tr>
                        <td>".$appointment_id."</td>
                        <td>".$patient_name."</td>
                        <td>".$title."</td>
                        <td>".$date."</td>
                        <td>".$start_time."</td>
                        <td>".$end_time."</td>
                        <td>".$location."</td>
                    </tr>";
			}
			$result .= "</tbody>";
         $result .= "</table>";
         
        } else {
            echo "Error: " . $sql . "<br />" . $conn->error;
        }

        // Closed the connection to the database
        $conn->close();
        echo $result;
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
