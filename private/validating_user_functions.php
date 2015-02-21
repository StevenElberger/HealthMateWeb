<?php
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

   function ip_address_matches_server_ip() {
      if (!isset($_SESSION['ip_address']) || !isset($_SERVER['REMOTE_ADDR'])) {
         return false;
      }
      if ($_SESSION['ip_address'] === $_SERVER['REMOTE_ADDR']) {
         return true;
      } else {
         return false;
      }
   } 

   function user_agent_matches_server_agent() {
      if (!isset($_SESSION['user_agent']) || !isset($_SERVER['HTTP_USER_AGENT'])) {
         return false;
      }
      if ($_SESSION['user_agent'] === $_SERVER['HTTP_USER_AGENT']) {
         return true;
      } else {
         return false;
      }
   }

   function last_login_time_valid() {
      $max_elapsed_time = 60 * 60 * 24;  // 1 day
      if (!isset($_SESSION['last_login'])) {
         return false;
      }
      if (($_SESSION['last_login'] + $max_elapsed_time) >= time()) {
         return true;
      } else {
         return false;
      }
   }

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

   function confirm_session_is_valid() {
      if(!is_session_valid()) {
         end_session();
         header("Location: login.php");
         exit;
      }
   }

   function is_logged_in() {
      if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
         return true;
      } else {
         return false;
      }
   }
   
   function confirm_user_is_logged_in() {
      if (!is_logged_in()) {
         end_session();
         header("Location: login.php");
         exit;
      }
   }

   function after_successful_login() {
      session_regenerate_id();
      $_SESSION['logged_in'] = true;
      $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];
      $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
      $_SESSION['last_login'] = time();
   } 

   function after_successful_logout() {
      $_SESSION['logged_in'] = false;
      end_session();
   }

   function validate_user_before_displaying() {
      confirm_user_is_logged_in();
      confirm_session_is_valid();
   }

?>
