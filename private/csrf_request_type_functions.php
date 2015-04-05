<?php
// Functions that determine what kind of request is being made.
// Note: We should only be making changes on a POST request,
// no changes should be made if it is a GET request.

function request_is_get() {
	return $_SERVER['REQUEST_METHOD'] === 'GET';
}

function request_is_post() {
	return $_SERVER['REQUEST_METHOD'] === 'POST';
}

?>
