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
        if (empty($_POST["doctor_id"])) {
            $doctor_idError = "*";
        } else {
            $doctor_id = test_input($_POST["doctor_id"]);
        }
    }

    // As long as all variables were initialized, the data is good to go
    if ($doctor_id !== "") {

        // Create connection
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Retrieves all relevant patient information for patients under this doctor's care
        $sql = "SELECT patient_id, first_name, last_name, birthday, gender FROM patient WHERE doctor_id = '".$doctor_id."'";

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