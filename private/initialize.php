<?php

   // Note: This implementation is based on a Tutorial
   // that one of the developers used to learn about web security.
   // Tutorial Name: Creating Secure PHP Websites
   // Author: Kevin Skoglund
   // Website of Tutorial: Lynda.com


   // This file performs the intilization of the php files used throughout
   // the application. 

   // Set constants to easily reference public and private directories
   define("APP_ROOT", dirname(dirname(__FILE__)));
   define("PRIVATE_PATH", APP_ROOT . "/private");
   define("PUBLIC_PATH", APP_ROOT . "/public");

   // Helper PHP files that exist in the private directory of the application
   require_once(PRIVATE_PATH . "/definitions.php");
   require_once(PRIVATE_PATH . "/csrf_request_type_functions.php");
   require_once(PRIVATE_PATH . "/csrf_token_functions.php");
   require_once(PRIVATE_PATH . "/request_forgery_functions.php");
   require_once(PRIVATE_PATH . "/reset_token_functions.php");
   require_once(PRIVATE_PATH . "/encryption_functions.php");
   require_once(PRIVATE_PATH . "/general_functions.php");
   require_once(PRIVATE_PATH . "/sanitize_functions.php");
   require_once(PRIVATE_PATH . "/validating_user_functions.php");
   require_once(PRIVATE_PATH . "/throttle_functions.php");
?>
