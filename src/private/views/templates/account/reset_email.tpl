<html>
	<head>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
		<title>Reset Password</title>
	</head>
	<body>

		<div class="row">
			<form method="POST" class="col-xs-6 col-xs-offset-3">
				<h2 style="text-align: center;">Livestock Tracker</h2>
				<h3>Reset Password</h3>
				{if $result == ''}
					<label>Username Or Email:</label>
					<input type="text" name="username" placeholder="Username" value="{$username_or_email}" class="form-control"/>
					</br>
					<a href="{$HOST}/account/signup">New User?</a>
					<div class="pull-right">
						<button name="send_email" type="submit" class="btn btn-primary">Send Email</button>
					</div>
				{else}
					<label>{$result}<label>
				{/if}
			</form>
		</div>



		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	</body>
</html>
