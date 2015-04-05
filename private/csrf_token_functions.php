<?php
// Note: This implementation is based on a Tutorial
// that one of the developers used to learn about web security.
// Tutorial Name: Creating Secure PHP Websites
// Author: Kevin Skoglund
// Website of Tutorial: Lynda.com

// Functions that prevent cross-site request forgery.
// A session must have been started before any of these
// can be used.

// Generate a token for use with CSRF protection.
// This token is a string that has been hashed and generated
// using a random number generator and a unique id.
function csrf_token() {
	return md5(uniqid(rand(), TRUE));
}

// Generate and store CSRF token in user session.
function create_csrf_token() {
	$token = csrf_token();
   $_SESSION['csrf_token'] = $token;
 	$_SESSION['csrf_token_time'] = time();
	return $token;
}

// Destroys a token by removing it from the session variables.
function destroy_csrf_token() {
   $_SESSION['csrf_token'] = null;
 	$_SESSION['csrf_token_time'] = null;
	return true;
}

// Return an HTML tag including the CSRF token 
// for use in a form. You want to add this to any form
// that will be updating or making any important changes to
// the database.
// Usage: echo csrf_token_tag();
function csrf_token_tag() {
	$token = create_csrf_token();
	return "<input type=\"hidden\" name=\"csrf_token\" value=\"".$token."\">";
}

// Function returns true if user-submitted POST token is identical to 
// the previously stored SESSION token. This is to make sure that the 
// original token that was submitted with the form is equivalent to 
// the current token in the POST request.
// Returns false otherwise.
function csrf_token_is_valid() {
	if(isset($_POST['csrf_token'])) {
		$user_token = $_POST['csrf_token'];
		$stored_token = $_SESSION['csrf_token'];
		return $user_token === $stored_token;
	} else {
		return false;
	}
}

// Function to simply check the token validity and 
// handle the failure yourself, or you can use 
// this "stop-everything-on-failure" function. 
function die_on_csrf_token_failure() {
	if(!csrf_token_is_valid()) {
		die("CSRF token validation failed.");
	}
}

// Function to check to see if token is recent
function csrf_token_is_recent() {
	$max_elapsed = 60 * 60 * 24; // 1 day
	if(isset($_SESSION['csrf_token_time'])) {
		$stored_time = $_SESSION['csrf_token_time'];
		return ($stored_time + $max_elapsed) >= time();
	} else {
		// Remove expired token
		destroy_csrf_token();
		return false;
	}
}

?>
