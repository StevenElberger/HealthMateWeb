<?php

   // Note: This implementation is based on a Tutorial
   // that one of the developers used to learn about web security.
   // Tutorial Name: Creating Secure PHP Websites
   // Author: Kevin Skoglund
   // Website of Tutorial: Lynda.com

 
    // Encryption and associated Decryption functions. These functions can be 
    // used to encrypt and decrypt a string using a key/salt. There are also
    // encode and decode functions in order to have friendly encrypted strings 
    // consisting of alphabetic characters instead of string symbols.

    // Function to encrypt a given string and salt/key
    // The encryption function uses Cipher Block Chaining
    // Mode feature with an initialization vector.
    function encrypt_string($salt, $string) {
      // The cipher type and cipher mode must match in the
      // decryption function
      $cipher_type = MCRYPT_RJINDAEL_256;
      $cipher_mode = MCRYPT_MODE_CBC;

      // The algorithm for this function requires an initialization vector
      $initialization_vector_size = 
            mcrypt_get_iv_size($cipher_type, $cipher_mode);
      $initialization_vector = 
            mcrypt_create_iv($initialization_vector_size, MCRYPT_RAND);

      // Encrypt the string using the RJINDAEL algorithm with the created
      // initialized vector
      $encrypted_string = 
            mcrypt_encrypt($cipher_type, $salt, $string, $cipher_mode, $initialization_vector);
    
      // Return the initialization vector appended to the front of the encrypted string
      // The initialization vector is needed to decrypt the string
      return $initialization_vector . $encrypted_string;
   }

   // Function to decrypt a given string and salt/key The decryption function
   // uses Cipher Block Chaining Mode feature with an initialization vector. 
   // The function assumes that the initialization vector is appended to the 
   // front of the encrypted string passed in.
   function decrypt_string($salt, $initialization_vector_with_string) {
      // The cipher type and cipher mode must match in the
      // encryption function
      $cipher_type = MCRYPT_RJINDAEL_256;
      $cipher_mode = MCRYPT_MODE_CBC;

      // Remove the initialization vector from the encrypted string
      // The initialization vector was appended on the front of the encrypted string
      $initialization_vector_size = mcrypt_get_iv_size($cipher_type, $cipher_mode);
      $initialization_vector = 
            substr($initialization_vector_with_string, 0,  $initialization_vector_size);
      $encrypted_string = 
            substr($initialization_vector_with_string, $initialization_vector_size);

      // Decrypt and return the string
      $string = mcrypt_decrypt($cipher_type, $salt, $encrypted_string, $cipher_mode, $initialize_vector);
      return $string;
   }

   // Function used to encode a string,the string is encrypted
   // before being encoded.
   function encrypt_string_and_encode($salt, $string) {
      // Encode after encryption to ensure encrypted characters are savable.
      return base64_encode(encrypt_string($salt, $string));
   }

   // Function used to decode a string, this must be done before decryption
   // if using an encoded string
   function decrypt_string_and_decode($salt, $string) {
       // Decode before decryption
       return decrypt_string($salt, base64_decode($string));
   }

?>
