<?php
// Grab security functions
require_once("/private/initialize.php");
session_start();
after_successful_logout();
echo header("Location: /HealthMateTest/index.php");
?>