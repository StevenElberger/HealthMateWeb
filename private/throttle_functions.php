<?php

session_start();

   function record_failed_login($username) {
      $db = mysqli_connect('localhost', 'root', '#mws1992', 'testDB');
      if (mysqli_connect_errno()) {
         die("Database connection failed: " . mysqli_connect_error() .
            " (" . mysqli_connect_errno() . ")");
      }
      
      $sql_statement = "SELECT id, username, attempts, last_time FROM failed_logins WHERE username='".$username."'";
      $failed_login_results = $db->query($sql_statement);
      if (mysql_num_rows($failed_login_results) > 0) {
         $row = mysql_fetch_row($failed_login_results);
         $row['attempts'] = $row['attempts'] + 1;
         $row['last_time'] = time();
         $sql_statement = "UPDATE failed_logins SET attempts='".$row['attemtps']."', last_time='"
            .$row['last_time']."', ip_address='".$_SESSION['ip_address']."' WHERE username='".$row['id']."'";
         $db->query($sql_statement);
      } else {
         $sql_statment = "INSERT INTO failed_logins (username, ip_address, attempts, last_time) VALUES ('".
            $username."', '".$ip_address."', '1', '".time()."')";
         $db->query($sql_statment);  
      }
      return true;
   }


   function clear_failed_login($username) {
      $db = mysqli_connect('localhost', 'root', '#mws1992', 'testDB');
      if (mysqli_connect_errno()) {
         die("Database connection failed: " . mysqli_connect_error() .
            " (" . mysqli_connect_errno() . ")");
      }

      $sql_statement = "SELECT id, username, attempts, last_time FROM failed_logins WHERE username='".$username."'";
      $failed_login_results = $db->query($sql_statement);
      if (mysql_affected_rows($failed_login_results) > 0) {
         $row = mysql_fetch_assoc($result);
         $row['attempts'] = 0;
         $row['last_time'] = time();
         $sql_statement = "UPDATE failed_logins SET attempts='".$row['attemtps']."', last_time='"
            .$row['last_time']."', ip_address='".$_SESSION['ip_address']."' WHERE id='".$row['id']."'";
         $db->query($sql_statement);
      }

      return true;
   }

   function throttle_failed_logins($username) {
      $throttle_at = 5;
      $delay_in_minutes = 1;
      $delay = 60 * $delay_in_minutes;
      $db = mysqli_connect('localhost', 'root', '#mws1992', 'testDB');
      if (mysqli_connect_errno()) {
         die("Database connection failed: " . mysqli_connect_error() .
            " (" . mysqli_connect_errno() . ")");
      }

      $sql_statement = "SELECT id, username, attempts, last_time FROM failed_logins WHERE username='".$username."'";
      $failed_login_results = $db->query($sql_statement);
      if (mysql_affected_rows($failed_login_results) > 0) {
         $row = mysql_fetch_assoc($result);
         if ($row['attempts'] >= $throttle_at) {
         $remaining_delay = ($row['last_time'] + $delay) - time();
         $remaining_delay_in_minutes = ceil($remaining_delay / 60);
         return $remaining_delay_in_minutes;
         } else {
            return 0;
         }
      }
   }

$username = $_POST['username'];
$password = $_POST['password'];

if($username != "" && $password != "") {
   
   record_failed_login($username);

   $throttle_delay = throttle_failed_logins($username);
   if($throttle_delay > 0) {
                                // Throttled at the moment, try again after delay period
    $message  = "Too many failed logins. ";
    $message .= "You must wait {$throttle_delay} minutes before you can attempt another login.";

 }
}
echo "No Errors";

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Log in</title>
  </head>
  <body>

    <?php
      if($message != "") {
        echo '<p>' . $message . '</p>';
      }
    ?>

    <p>Please log in.</p>

    <form action="" method="POST" accept-charset="utf-8">
      Username: <input type="text" name="username" value="<?php echo $username; ?>" /><br />
                        <br />
      Password: <input type="password" name="password" value="" /><br />
                        <br />
      <input type="submit" name="submit" value="Log in" />
    </form>

   </body>
</html>

