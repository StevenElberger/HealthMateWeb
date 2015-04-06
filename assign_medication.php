<?php
    // Grab security functions
    require_once("/private/initialize.php");
    
    // Error placeholders
    $medicationNameError = $patientUsernameError = $patientNameError = "";
    $dosageError = $frequencyError = $doctor_idError = "";
    
    // Placeholders for variables from form
    $doctor_id = $medication_name = $patient_username = $patient_name = $dosage = $frequency = "";

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

        if (empty($_POST["medication_name"])) {
            $medicationNameError = "*";
        } else {
            $medication_name = test_input($_POST["medication_name"]);
        }

        if (empty($_POST["patient_username"])) {
            $patientUsernameError = "*";
        } else {
            $patient_username = test_input($_POST["patient_username"]);
        }

        if (empty($_POST["patient_name"])) {
            $patientNameError = "*";
        } else {
            $patient_name = test_input($_POST["patient_name"]);
        }

        if (empty($_POST["dosage"])) {
            $dosageError = "*";
        } else {
            $dosage = test_input($_POST["dosage"]);
        }

        if (empty($_POST["frequency"])) {
            $frequencyError = "*";
        } else {
            $frequency = test_input($_POST["frequency"]);
        }
    }

    // As long as all variables were initialized, the data is good to go
    if (($medication_name !== "") && ($patient_username !== "") && ($patient_name !== "") && ($dosage !== "")
    && ($frequency !== "")) {

        // Create connection
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // new stuff
        $get_patient_id_sql = "SELECT patient_id FROM patient WHERE username = '" . $patient_username . "'";

        $patient_id_result = $conn->query($get_patient_id_sql);

        $patient_id = "";

        if ($patient_id_result->num_rows > 0) {
            while ($row = $patient_id_result->fetch_assoc()) {
                $patient_id = $row["patient_id"];
            }
        }
        
        // Adds a new medication associated with a patient with form data into the medication list table of the database
        $sql = "INSERT INTO medicationlist (patient_id, user_id, medication_id, patient_name, dosage, frequency)
        VALUES ('".$patient_id."', '".$doctor_id."','".$medication_name."', '".$patient_name."', '".$dosage."', '".$frequency."')";

        // Only assign the medication to the patient if the patient does not have the medication already assigned to them
        if (assign_medication_exists($medication_name, $patient_name, $conn)) {
            $result = "Medication already Assigned.";
        } else if ($conn->query($sql) === TRUE) {

				// select the assigned medication from the database that has just been inserted
            $sql_get_medication_list_id = "SELECT medication_list_id FROM medicationlist WHERE patient_id ='".$patient_username."' AND medication_id ='".$medication_name."'";
				
				// Execute query
            $get_medication_list_id = $conn->query($sql_get_medication_list_id);
            $medication_list_id = "";

				// Verify that the medication has been assigned and stored in the database
            if ($get_medication_list_id->num_rows > 0) {
                while ($row = $get_medication_list_id->fetch_assoc()) {
                    $medication_list_id .= $row["medication_list_id"];
                }
            } else {
                $result = "ERROR";
                echo $result;
                return;
            }
            
            // Create table with information of the assigned medication that was just entered
            // into the database
            $result = "<h3 class='text-center'>Medication Added Successfully</h3>";
            $result .= "<table class='table table-striped table-hover'>";
            $result .= "<thead>
                    <tr>
                        <th>MLID #</th>
                        <th>Medication Name</th>
                        <th>Patient Username</th>
                        <th>Patient Name</th>
                        <th>Dosage</th>
                        <th>Frequency</th>
                    </tr>
                    </thead>
                    <tbody>";
            $result .= "<tr>
                        <td>".$medication_list_id."</td>
                        <td>".$medication_name."</td>
                        <td>".$patient_username."</td>
                        <td>".$patient_name."</td>
                        <td>".$dosage."</td>
                        <td>".$frequency."</td>
                    </tr>";
            $result .= "</tbody>";
            $result .= "</table>";
        } else {
            echo "Error: " . $sql . "<br />" . $conn->error;
        }

        // Close the connection
        $conn->close();

		  // Display the table or error depending on success
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

    // Checks to see if given username already exists
    function assign_medication_exists($given_medication_name, $patient_name, $existing_conn) {
        $sql = "SELECT medication_list_id FROM medicationlist WHERE patient_name = '".$patient_name."' AND medication_id = '".$given_medication_name."'";

        $result = $existing_conn->query($sql);

        return $result->num_rows > 0;
    }
?>
