<?php
    // Grab security functions
    require_once("/private/initialize.php");
    // Error placeholders
    $medicationNameError = $medicationTypeError = $intakeMethodError = "";
    $maxDosageError = $minDosageError = $doctor_idError = "";
    // Placeholders for variables from form
    $doctor_id = $medication_name = $medication_type = $intake_method = $max_dosage = $min_dosage = "";

    // Return string
    $result = "";

    // Only process POST requests, not GET
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
		 
        // Check that required fields have been set
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

        if (empty($_POST["medication_type"])) {
            $medicationTypeError = "*";
        } else {
            $medication_type = test_input($_POST["medication_type"]);
        }

        if (empty($_POST["intake_method"])) {
            $intakeMethodError = "*";
        } else {
            $intake_method = test_input($_POST["intake_method"]);
        }

        if (empty($_POST["max_dosage"])) {
            $maxDosageError = "*";
        } else {
            $max_dosage = test_input($_POST["max_dosage"]);
        }

        if (empty($_POST["min_dosage"])) {
            $minDosageError = "*";
        } else {
            $min_dosage = test_input($_POST["min_dosage"]);
        }
    }

    // As long as all variables were initialized, the data is good to go
    if (($medication_name !== "") && ($medication_type !== "") && ($intake_method !== "") && ($max_dosage !== "")
    && ($min_dosage !== "")) {

        // Create connection
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        // Adds a new mediation with form data into the medications table of the database
        $sql = "INSERT INTO medications (name, type, intake_method, max_dosage, min_dosage) 
        VALUES ('".$medication_name."', '".$medication_type."', '".$intake_method."', '".$max_dosage."', '".$min_dosage."')";

		  // Add the medication to the database only if it does not already exist
        if (medication_exists($medication_name, $conn)) {
            $result = "Medication already exists.";
        } else if ($conn->query($sql) === TRUE) {

				// Verify that the medication was added successfully to the database
            $sql_get_medication_id = "SELECT medication_id FROM medications WHERE name = '" . $medication_name . "'";
            $get_medication_id = $conn->query($sql_get_medication_id);
            $medication_id = "";

				// If medication not added, display ERROR
            if ($get_medication_id->num_rows > 0) {
                while ($row = $get_medication_id->fetch_assoc()) {
                    $medication_id .= $row["medication_id"];
                }
            } else {
                $result = "ERROR";
                echo $result;
                return;
            }
            
            // Create a table with information about the medication that was just added
            // to the database.
            $result = "<h3 class='text-center'>Medication Added Successfully</h3>";
            $result .= "<table class='table table-striped table-hover'>";
            $result .= "<thead>
                    <tr>
                        <th>MID #</th>
                        <th>Medication Name</th>
                        <th>Medication Type</th>
                        <th>Intake Method</th>
                        <th>Max Dosage</th>
                        <th>Min Dosage</th>
                    </tr>
                    </thead>
                    <tbody>";
            $result .= "<tr>
                        <td>".$medication_id."</td>
                        <td>".$medication_name."</td>
                        <td>".$medication_type."</td>
                        <td>".$intake_method."</td>
                        <td>".$max_dosage."</td>
                        <td>".$min_dosage."</td>
                    </tr>";
            $result .= "</tbody>";
            $result .= "</table>";
        } else {
            echo "Error: " . $sql . "<br />" . $conn->error;
        }

        // Close Connection to Database
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

    // Checks to see if given medication already exists
    function medication_exists($given_medication_name, $existing_conn) {
        $sql = "SELECT name FROM medications WHERE name = '".$given_medication_name."'";

        $result = $existing_conn->query($sql);

        return $result->num_rows > 0;
    }
?>
