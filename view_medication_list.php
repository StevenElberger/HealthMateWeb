<?php
	 // Grab security functions
    //require_once("/private/initialize.php");
    
    // Error placeholder variables
    $doctor_idError = "";
    
    // Placeholders for variables from form
    $doctor_id = $medication_id = $medication_name = $medication_type = "";
    $intake_method = $max_dosage = $min_dosage = "";

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
        
        $sql = "SELECT * FROM medications";
        
        $results = $conn->query($sql);
        if ($results->num_rows == 0) {
			  $result = "No Medications";
           echo $result;
           return;
         }
        
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
        foreach ($results as $row) {
			   $medication_id = $row["m_id"];
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

