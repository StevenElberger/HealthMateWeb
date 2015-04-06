<?php
    // Grab security functions
    require_once("/private/initialize.php");
    // Error placeholders
    $firstNameError = $lastNameError = $usernameError = "";
    $genderError = $birthdayError = $requiredFields = $doctor_idError = "";
    // Placeholders for variables from form
    $doctor_id = $username = $first_name = $last_name = $gender = $birthday = "";

    // Return string
    $result = "";

    // Only process POST requests, not GET
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check the required fields
        if (empty($_POST["patient_id"])) {
            $patient_idError = "*";
        } else {
            $patient_id = test_input($_POST["patient_id"]);
        }
    }

    // As long as all variables were initialized, the data is good to go
    if ($patient_id !== "") {

        // Create connection
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // --- First Query ---
        // Retrieves all relevant patient information for patients under this doctor's care
        $sql = "SELECT patient_id, first_name, last_name, birthday, gender FROM patient WHERE patient_id = '".$patient_id."'";

        $queryResult = $conn->query($sql);
        if ($queryResult->num_rows > 0) {

            $result = "<h3 class='text-center'>Patient List</h3>";
            $result .= "<table class='table table-striped table-hover'>";
            $result .= "<thead>
                    <tr>
                        <th>PID #</th>
                        <th>Patient Name</th>
                        <th>Gender</th>
                        <th>Birthday</th>
                    </tr>
                    </thead>
                    <tbody>";

            while ($row = $queryResult->fetch_assoc()) {
                $patient_id = $row["patient_id"];
                $patient_first_name = $row["first_name"];
                $patient_last_name = $row["last_name"];
                $patient_gender = $row["gender"];
                $patient_birthday = $row["birthday"];
                $result .= "<tr>
                        <td>".$patient_id."</td>
                        <td>".$patient_first_name. " " . $patient_last_name."</td>
                        <td>".$patient_gender."</td>
                        <td>".$patient_birthday."</td>
                    </tr>";
            }

            $result .= "</tbody>";
            $result .= "</table>";
        } else {
            $result = "ERROR";
            echo $result;
            return;
        }

        // --- Second Query ---
        // Retrieves all relevant patient information for patients under this doctor's care
        $second_sql = "SELECT medication_list_id, patient_id, doctor_id, medication_id, patient_name, dosage, frequency FROM medicationlist WHERE patient_id = '".$patient_id."'";

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // new stuff - get the actual patient_username
        $get_patient_username_sql = "SELECT username FROM patient WHERE patient_id = '" . $patient_id . "'";

        $patient_username_result = $conn->query($get_patient_username_sql);

        $patient_username = "";

        if ($patient_username_result->num_rows > 0) {
            while ($row = $patient_username_result->fetch_assoc()) {
                $patient_username = $row["username"];
            }
        }

        $second_queryResult = $conn->query($second_sql);
        if ($second_queryResult->num_rows > 0) {

            $result .= "<h3 class='text-center'>Medication List</h3>";
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

            while ($row = $second_queryResult->fetch_assoc()) {
                // get the medication_name
                $get_medication_name_sql = "SELECT name FROM medications WHERE medication_id = '" . $row["medication_id"] . "'";

                $patient_medication_name_result = $conn->query($get_medication_name_sql);

                $medication_name = "";

                if ($patient_medication_name_result->num_rows > 0) {
                    while ($second_row = $patient_medication_name_result->fetch_assoc()) {
                        $medication_name = $second_row["name"];
                    }
                }

                $medication_list_id = $row["medication_list_id"];
                $patient_name = $row["patient_name"];
                $dosage = $row["dosage"];
                $frequency = $row["frequency"];
                $result .= "<tr>
                        <td>".$medication_list_id."</td>
                        <td>".$medication_name."</td>
                        <td>".$patient_username."</td>
                        <td>".$patient_name."</td>
                        <td>".$dosage."</td>
                        <td>".$frequency."</td>
                    </tr>";
            }

            $result .= "</tbody>";
            $result .= "</table>";
        } else {
            $result = "ERROR";
            echo $result;
            return;
        }

        // Peace out
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