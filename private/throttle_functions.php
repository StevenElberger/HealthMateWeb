<?php
 require_once("initialize.php");
   // Note: This implementation is based on a Tutorial
   // that one of the developers used to learn about web security.
   // Tutorial Name: Creating Secure PHP Websites
   // Author: Kevin Skoglund
   // Website of Tutorial: Lynda.com


   // This file contains functions that can used to throttle a user when a number of
   // attemtped logins have been used an failed. These functions utilize a database
   // and table that have specified columns(id, username, ip_address, attempts, last_time).
   // The throttle amount of time is hardcoded. The functions provide the ability to record
   // and clear failed logins. There is also a throttle function that implements the throttle.

   //session_start();

   // Function that connects to a database, attempts to see
   // if the given user exists in the table of failed logins.
   // If the user does exist, there count at attempts at login in
   // is increased. If they do not exist, they are added to the table.
   function record_failed_login($username) {

      // Attempt to connect to the database
      $db = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
      if (mysqli_connect_errno()) {
         die("Database connection failed: " . mysqli_connect_error() .
            " (" . mysqli_connect_errno() . ")");
      }

      // SQL statement to retrieve rows that have the username column equal to the given username      
      $sql_statement = "SELECT * FROM failed_logins WHERE username='".$username."'";

      // execute query
      $failed_login_results = $db->query($sql_statement);

      // check if anything was returned by database
      if ($failed_login_results->num_rows > 0) {

         // fetch the first row of the results of the query
         $row = $failed_login_results->fetch_assoc();

         // Update the information for the found user
         $row['attempts'] = $row['attempts'] + 1;
         $row['last_time'] = time();
         $sql_statement = "UPDATE failed_logins SET attempts='".$row['attempts']."', last_time='"
            .$row['last_time']."', ip_address='".$_SESSION['ip_address']."' WHERE id='".$row['id']."'";

         // execute query
         $db->query($sql_statement);

      } else {
         // statement to insert user into the table of failed logins
         $sql_statment = "INSERT INTO failed_logins (username, ip_address, attempts, last_time) VALUES ('".
            $username."', '".$_SESSION['ip_address']."', '1', '".time()."')";

         // execute query
         $db->query($sql_statment);  
      }

      return true;
   }

   // Function to clear the recorded failed logins for the given user
   // This function clears the given username's failed logins if they
   // exist in the table of failed logins
   function clear_failed_login($username) {

      // Attempt to connect to the database
      $db = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
      if (mysqli_connect_errno()) {
         die("Database connection failed: " . mysqli_connect_error() .
            " (" . mysqli_connect_errno() . ")");
      }

      // SQL statement to select all the rows that have the username equal to the given username
      $sql_statement = "SELECT * FROM failed_logins WHERE username='".$username."'";

      // execute query
      $failed_login_results = $db->query($sql_statement);

      // If query returns something then attempt to reset the time and number of 
      // attemtps for the user
      if ($failed_login_results->num_rows > 0) {

         // Get the first row of the return results
         $row = $failed_login_results->fetch_assoc();

         // reset the number of attempts and give a new time
         $row['attempts'] = 0;
         $row['last_time'] = time();

         // SQL statment to update the specific row of the given username
         $sql_statement = "UPDATE failed_logins SET attempts='".$row['attemtps']."', last_time='"
            .$row['last_time']."', ip_address='".$_SESSION['ip_address']."' WHERE id='".$row['id']."'";

         // execute query
         $db->query($sql_statement);
      }

      return true;
   }

   // Function that throttles the given username
   // The user will not be allowed to attempt to login for a specified
   // time.
   function throttle_failed_logins($username) {
      
      // The user can attempt to login and fail 5 times
      // If the user fails a 6 time, there username will be locked
      $throttle_at = 5;

      // Time of delay before the user can attempt to login in
      $delay_in_minutes = 10;
      $delay = 60 * $delay_in_minutes;

      // Attempt to connect to the database
      $db = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
      if (mysqli_connect_errno()) {
         die("Database connection failed: " . mysqli_connect_error() .
            " (" . mysqli_connect_errno() . ")");
      }

      // SQL command to select all usernames with the given username
      $sql_statement = "SELECT * FROM failed_logins WHERE username='".$username."'";

      // execute query
      $failed_login_results = $db->query($sql_statement);

      // check if anything has been returned
      // if this is true update the information, otherwise do nothing
      if ($failed_login_results->num_rows > 0) {

         // get the first row in the result
         $row = $failed_login_results->fetch_assoc();

         // check if attempts exceeds the maximum number of attempts
         if ($row['attempts'] >= $throttle_at) {

            // delay the user for a specified time  
            $remaining_delay = ($row['last_time'] + $delay) - time();
            $remaining_delay_in_minutes = ceil($remaining_delay / 60);
            return $remaining_delay_in_minutes;

         } else {

            return 0;
         }
      }
   }
?>

