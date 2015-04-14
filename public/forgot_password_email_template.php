<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<!-- Bootstrap core CSS-->
		<link href="../newcss/bootstrap.css" type="text/css" rel="stylesheet">
    
		<!-- Custom CSS for Login -->
		<link href="../newcss/login.css" type="text/css" rel="stylesheet">
		
		<title>Password Reset Email</title>
		
	</head>
	<body style="color: #555555 background-color: #ffffff">
		
		<h1 class="text-center" style="color: #317eac">HealthMate</h1>
		
		<p style="color: #3a87ad">
			There was a request to reset the password for an account associated with this email
		<br /></p>
		
		<p>You can use the link below to reset your password.</p>
		
		<p>
			
			<a href="[[ip_address]]/HealthMateTest/public/reset_password.php?token=[[token]]">
				[[ip_address]]/HealthMateTest/public/reset_password.php?token=[[token]]</a>
		</p>

		<p>
			If you did not make this request, you do not need to take any action. 
			Your password cannot be changed without clicking the above link to verify the request.
		</p>
		
		<hr />
  </body>
</html>
