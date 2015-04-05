<?php

// Note: This implementation is based on a Tutorial
// that one of the developers used to learn about web security.
// Tutorial Name: Creating Secure PHP Websites
// Author: Kevin Skoglund
// Website of Tutorial: Lynda.com

// Function to see if the request is from the same domain.
// Use with request_is_post() to block posting from off-site forms
function request_is_same_domain() {
	if(!isset($_SERVER['HTTP_REFERER'])) {
		// No refererer sent, so can't be same domain
		return false;
	} else {
		// Get the domain of the requester
		$referer_host = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
		
		// Get the domain from the server
		$server_host = $_SERVER['HTTP_HOST'];
		
		// Compare to see if they are the same
		if ($referer_host == $server_host) {
			return true;
		} else {
			return false;
		}
	}
}

?>
