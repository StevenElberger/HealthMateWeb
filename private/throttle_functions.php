<?php

   function record_failed_login($username) {
      $db; // = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME); 
      $sql_statement = "SELECT id, username, attempts, last_time;"
      $failed_login_results;// db->query($sql_statement);
   }

?>
