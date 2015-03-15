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

        if (empty($_POST["username"])) {
            $usernameError = "*";
        } else {
            $username = test_input($_POST["username"]);
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

        if (empty($_POST["gender"])) {
            $genderError = "*";
        } else {
            $gender = test_input($_POST["gender"]);
        }

        if (empty($_POST["birthday"])) {
            $birthdayError = "*";
        } else {
            $birthday = test_input($_POST["birthday"]);
        }
    }

    // As long as all variables were initialized, the data is good to go
    if (($first_name !== "") && ($last_name !== "") && ($username !== "") && ($gender !== "")
    && ($birthday !== "")) {

        // Create connection
        $conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $password = "password321";
        // Adds a new user account with form data into the physician table of the database
        // -- To do: form checking (e.g., username already exists, security, etc.)
        $sql = "INSERT INTO patient (group_id, doctor_id, username, first_name, last_name, birthday, gender, password) VALUES (2, '".$doctor_id."', '".$username."', '".$first_name."', '".$last_name."', '".$birthday."', '".$gender."', '".$password."')";

        if (username_exists($username, $conn)) {
            $result = "Username already exists.";
        } else if ($conn->query($sql) === TRUE) {
            // successful patient add
            $result = "Patient added successfully.";
        } else {
            echo "Error: " . $sql . "<br />" . $conn->error;
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

    // Checks to see if given username already exists
    function username_exists($given_username, $existing_conn) {
        $sql = "SELECT username FROM patient WHERE username = '".$given_username."'";

        $result = $existing_conn->query($sql);

        return $result->num_rows > 0;
    }
?>