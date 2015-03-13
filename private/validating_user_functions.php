<?php
 
   // Note: This implementation is based on a Tutorial
   // that one of the developers used to learn about web security.
   // Tutorial Name: Creating Secure PHP Websites
   // Author: Kevin Skoglund
   // Website of Tutorial: Lynda.com

   
   // These functions can be used to validate that a use has access to a private page. 
   // The purpose of these functions are to ensure that a session is valid and to
   // determine that the user is logged into the site.

   // Comment This out when not testing
   // session_start();

   // Function that forcibly ends the current session
   function end_session() {
      // Thses two functions are used to destroy or remove all the
      // current session data. The purpose of using both have to do
      // with broswer compatability.
      session_unset();
      session_destroy();
   }

   
   // The next three functions are used for the validation of the session
   // ip_address_matches_server_ip()
   // user_agent_matches_server_agent()
   // last_login_time_valid()

   // Function to check if the current ip address matches the original ip
   // address used when login in. Note: 'ip_address' should be set as a session
   // variable when the user successfully logs in to use this function properly.
   function ip_address_matches_server_ip() {

      // Check if values exists for the ip addresses
      if (!isset($_SESSION['ip_address']) || !isset($_SERVER['REMOTE_ADDR'])) {
         return false;
      }

      // Check if the ip addresses match
      if ($_SESSION['ip_address'] === $_SERVER['REMOTE_ADDR']) {
         return true;
      } else {
         return false;
      }
   } 

   // Function to check if the current user agent matches the original user agent
   // that was recorded at login. Note: 'user_agent' should be set as a session
   // variable when the user successfully  logs in to use this function properly.
   function user_agent_matches_server_agent() {

      // Check if the user agent variables exists
      if (!isset($_SESSION['user_agent']) || !isset($_SERVER['HTTP_USER_AGENT'])) {
         return false;
      }

      // Check if the user agents match
      if ($_SESSION['user_agent'] === $_SERVER['HTTP_USER_AGENT']) {
         return true;
      } else {
         return false;
      }
   }

   // Function to Check if the user has been logged in no longer
   // than a specified time period. Note: 'last_login' should be set as
   // a session variable when the user successfully logs in.
   function last_login_time_valid() {

      // The maximum amount of time the user can be login\
      // for a given session
      $max_elapsed_time = 60 * 60 * 24;  // 1 day

      // check to see if the time of the user's login was recorded
      if (!isset($_SESSION['last_login'])) {
         return false;
      }

      // Check if the last login has not exceed the time limit
      if (($_SESSION['last_login'] + $max_elapsed_time) >= time()) {
         return true;
      } else {
         return false;
      }
   }


   // Function to Check if the session is a valid session
   // Helper function
   function is_session_valid() {
      
      if (!ip_address_matches_server_ip()) {
         return false;
      }
      if (!user_agent_matches_server_agent()) {
         return false;
      }
      if (!last_login_time_valid()) {
         return false;
      }

      return true;
   }

   // Function to confirm the session is valid
   // If the session is not valid, redirect the
   // user to the login page.
   function confirm_session_is_valid() {

      if(!is_session_valid()) {
         end_session();
         header("Location: index.php");
         exit;
      }
   }

   // Function to check if the user is logged in
   // Helper function
   function is_logged_in() {
       return isset($_SESSION['logged_in']) && $_SESSION['logged_in'];
   }

   // Function to confirm that the user is logged in
   // If the user is not logged in, redirect the
   // user to the login page.   
   function confirm_user_is_logged_in() {
      if (!is_logged_in()) {
         end_session();
         header("Location: index.php");
         exit;
      }
   }

   // Function that resets the session id for the current session
   // and sets the necessary Session variables for security checks
   // on the site.
   function after_successful_login() {
      session_regenerate_id();
       $_SESSION['logged_in'] = true;
       $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];
       $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
       $_SESSION['last_login'] = time();
   }

   // Function that sets the user login record to 
   // false indicating that the user is no longer 
   // logged in and ends the current session.
   function after_successful_logout() {
      $_SESSION['logged_in'] = false;
      end_session();
   }

   // Function to validate that the user is logged in and
   // that the current session is valid.
   // Note: Call this function before displaying any
   // protected page on the site to prevent invalid
   // users from viewing/accessing restricted content.
   function validate_user_before_displaying() {
      confirm_user_is_logged_in();
      confirm_session_is_valid();
   }

?>
