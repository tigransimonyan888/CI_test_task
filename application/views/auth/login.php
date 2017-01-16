<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login</title>

	<!-- Bootstrap -->
	<link href="<?=base_url('assets/styles/bootstrap.min.css')?>" rel="stylesheet">
    <!-- Custom styles	-->
	<link href="<?=base_url('assets/styles/login.css')?>" rel="stylesheet">
</head>
<body>
<div class="login-form">
	<?=validation_errors('<div class="error">', '</div>'); ?>
	<?=form_open('auth/verifylogin'); ?>
		<div class="form-group">
			<label for="username">Username</label>
			<input type="text" class="form-control" name="username" placeholder="Username">
		</div>
		<div class="form-group">
			<label for="password">Password</label>
			<input type="password" class="form-control" name="password" placeholder="Password">
		</div>
		<div class="form-group">
			<input type="submit" value="Login" class="btn pull-right">
		</div>
	<?=form_close(); ?>
</div>
</body>
</html>