<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Password Reset Email</title>
  </head>
  <body>
	
	 <p>There was a request to reset the password for an account associated with this email<br /></p>
	 <p>Thank you for using HealthMate<br /></p>
    <p>You can use the link below to reset your password.</p>
		
		<p>
			
			<a href="[[ip_address]]/HealthMateWeb/public/reset_password.php?token=[[token]]">
				[[ip_address]]/HealthMateWeb/public/reset_password.php?token=[[token]]</a>
		</p>

		<p>If you did not make this request, you do not need to take any action. Your password cannot be changed without clicking the above link to verify the request.</p>
		
		<hr />
  </body>
</html>
