<?php
	 // Grab security functions
    require_once("/private/initialize.php");
    
    // Error placeholder variables
    $doctor_idError = "";
    
    // Placeholders for variables from form
    $doctor_id = $medication_id = $medication_name = $medication_type = "";
    $intake_method = $max_dosage = $min_dosage = "";

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
	  }
	  
	  if ($doctor_id !== "") {
		  
		  // Create connection
          $conn = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 
        
        // Select all existing medications from the medications table
        $sql = "SELECT * FROM medications";
        
        // If there are no medications, display No Medications
        $results = $conn->query($sql);
        if ($results->num_rows == 0) {
			  $result = "No Medications";
           echo $result;
           return;
         }
        
        // Start the construction of the Medications table
        $result = "<h3 class='text-center'>Medications List</h3>";
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
                    
        // for each medication in result, add a new row to the table
        foreach ($results as $row) {
			   $medication_id = $row["medication_id"];
				$medication_name = $row["name"];
				$medication_type = $row["type"];
				$intake_method = $row["intake_method"];
				$max_dosage = $row["max_dosage"];
				$min_dosage = $row["min_dosage"];
				
				$result .= "<tr>
                        <td>".$medication_id."</td>
                        <td>".$medication_name."</td>
                        <td>".$medication_type."</td>
                        <td>".$intake_method."</td>
                        <td>".$max_dosage."</td>
                        <td>".$min_dosage."</td>
                    </tr>";
		  }
		  $result .= "</tbody>";
        $result .= "</table>";
	  } else {
		  $result = "Error";
		  echo $result;
		  return;
	  }
	  
	  // Close the connection to the database
	  $conn->close();
	  
	  // Display the results to the user
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

