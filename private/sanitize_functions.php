<?php require_once("initialize.php");

   // Note: This implementation is based on a Tutorial
   // that one of the developers used to learn about web security.
   // Tutorial Name: Creating Secure PHP Websites
   // Author: Kevin Skoglund
   // Website of Tutorial: Lynda.com


   // Sanitization Functions
   // These functions are used to convert strings harmless from being used 
   // to generate html, or run javascript functions, or run php functions. 
   // Since this may need to be done often, having function calls for this
   // can make it simpler.


   // Sanitize string for html output
   function sanitize_html($string) {
      return htmlspecialchars($string);
   }

   // Sanitize string for javascript output
   function sanitize_jscript($string) {
      return json_encode($string);
   }

   // Sanitize string for URL output
   function sanitize_url($string) {
      return urlencode($string);
   }

   // Sanitize string for SQL output
   function sanitize_sql($string) {
      // Attempt to connect to the database
      $db = mysqli_connect("DB_SERVER", "DB_USER", "DB_PASS", "DB_NAME");
      
      // If connection is successful, use mysqli function to sanitize
      // Otherwise use php function to sanitize by adding slashes
      if (mysqli_connect_errno()) {
         $string = mysqli_real_escape_string($db, $string);
      } else {
         $string = addslashes($string);
      }

      // Return sanitized string
      return $string;
   }

?>

   
