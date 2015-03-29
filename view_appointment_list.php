<?php
	 // Grab security functions
    //require_once("/private/initialize.php");
    
    // Error placeholder variables
    $doctor_idError = "";
    
    // Placeholders for variables from form
    $doctor_id = $appointment_username = $first_name = $last_name = "";
    $appointment_title = $address = $city = $zip_code = "";
    $state = $date = $start_time = $end_time = "";

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
	  
	  if ($doctor_id !== "") {
		  
		  // Create connection
        $conn = new mysqli("localhost", "root", "#mws1992", "testDB");

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 
        
        $sql = "SELECT * FROM appointments WHERE doctor_id='" . $doctor_id . "'";
        
        $results = $conn->query($sql);
        if ($results->num_rows == 0) {
			  $result = "No Appointments";
           echo $result;
           return;
         }
        
        $result = "<h3 class='text-center'>Appointment List</h3>";
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
        foreach ($results as $row) {
			   $appointment_id = $row["appointment_id"];
				$patient_name = $row["first_name"] . " " . $row["last_name"];
				$title = $row["title"];
				$date = $row["date"];
				$start_time = $row["start_time"];
				$end_time = $row["end_time"];
				$location = $row["address"] . ", " . $row["city"] . ", " . $row["state"] . " " . $row["zip_code"];
				
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
		  $result = "Error";
		  echo $result;
		  return;
	  }
	  
	  $conn->close();
	  
	  echo $result;
	  
	  // Removes unwanted and potentially malicious characters
    // from the form data to prevent XSS hacks / exploits
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    
?>