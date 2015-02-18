<?php
 
   // This file contains general functions that can be useful
   // to reduce and modularize code that is used throughout the
   // website.

   // Redirect function that will change the current
   // webpage being to display to a new page.
   function redirect_to($new_location) {
      header("Location: " . $new_location);
      exit;
   }

?>
