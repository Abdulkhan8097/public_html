<!doctype html>
<html lang="en-US">

<head>
	<meta charset="utf-8" />
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title><?php echo SITE_FULL_NAME; ?> | <?php echo (isset($page_title)) ? $page_title : ''; ?></title>
	<meta name="description" content="" />
	<meta name="Author" content="Dorin Grigoras [www.stepofweb.com]" />

	<!-- mobile settings -->
	<meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0" />

	<!-- WEB FONTS -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700,800&amp;subset=latin,latin-ext,cyrillic,cyrillic-ext" rel="stylesheet" type="text/css" />

	<!-- CORE CSS -->
	<link href="<?php echo ADMIN_ASSETS; ?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

	<!-- THEME CSS -->
	<link href="<?php echo ADMIN_ASSETS; ?>assets/css/essentials.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo ADMIN_ASSETS; ?>assets/css/layout.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo ADMIN_ASSETS; ?>assets/css/color_scheme/blue.css" rel="stylesheet" type="text/css" id="color_scheme" />

</head>
<!--
		.boxed = boxed version
	-->

<body>
	<!-- <?php echo BASEURL; ?> -->
	<div class="padding-15">
		<div class="login-box">
			<!-- login form -->
			<!-- <div>
					<img height="80" width="350" src="<?php echo UPLOADPATH_ADMIN; ?>images/logos/demo-logo-png-8.png">
				</div> -->
			<br>
			<form action="<?php echo USER_LOGIN; ?>" method="post" class="sky-form boxed" autocomplete="off">
				<header class="text-center"><i class="fa fa-sign-in"></i> <b>LOGIN</b></header>
				<!--
					<div class="alert alert-danger noborder text-center weight-400 nomargin noradius">
						Invalid Email or Password!
					</div>

					<div class="alert alert-warning noborder text-center weight-400 nomargin noradius">
						Account Inactive!
					</div>

					<div class="alert alert-default noborder text-center weight-400 nomargin noradius">
						<strong>Too many failures!</strong> <br />
						Please wait: <span class="inlineCountdown" data-seconds="180"></span>
					</div>
					-->
				<fieldset>
					<!-- ERROR MESSAGE START -->
					<?php $this->load->view('admin/component/dismissible_message.php'); ?>
					<!-- ERROR MESSAGE END -->
					<section>
						<label class="label"><strong>E-mail</strong></label>
						<label class="input">
							<i class="icon-append fa fa-envelope"></i>
							<input type="email" name="email" id="email" placeholder="Enter Email..." value="<?php if (isset($_COOKIE['email'])) echo $_COOKIE['email']; ?>" required="required">
							<span class="tooltip tooltip-top-right">Email Address</span>
						</label>
					</section>

					<section>
						<label class="label"><strong>Password</strong></label>
						<label class="input">
							<i class="icon-append fa fa-lock"></i>
							<input type="password" name="password" id="password" placeholder="Enter Password..." value="<?php if (isset($_COOKIE['password'])) echo $_COOKIE['password']; ?>" required="required">
							<b class="tooltip tooltip-top-right">Type your Password</b>
						</label>
						<label class="checkbox"><input type="checkbox" name="remember_me" id="remember_me" <?php if (isset($_COOKIE['remember_me'])) {
																												echo 'checked="checked"';
																											} else {
																												echo '';
																											} ?> value="1"><i></i>Keep me logged in</label>
					</section>

				</fieldset>

				<footer>
					<button type="submit" class="btn btn-primary pull-right"><i class="fa fa-sign-in"></i>
						Login</button>
					<div class="forgot-password pull-left">
						<a href="<?php echo ADMIN_FORGET_PASSWORD_DETAILS_URL; ?>"><b>Forgot password?</b></a> <br />
						<!-- <a href="page-register.html"><b>Need to Register?</b></a> -->
					</div>
				</footer>
			</form>
			<!-- /login form -->

			<hr />

			<div class="text-center">
				© <?php echo date('Y') ?> Copyright <a href="<?php echo FRONT_HOME_URL; ?>" target="_blank"><?php echo SITE_FULL_NAME; ?></a>. All Rights Reserved.
			</div>


			<!-- <div class="socials margin-top-10 text-center">
					<a href="#" class="btn btn-facebook"><i class="fa fa-facebook"></i> Facebook</a>
					<a href="#" class="btn btn-twitter"><i class="fa fa-twitter"></i> Twitter</a>
				</div> -->

		</div>

	</div>

	<!-- JAVASCRIPT FILES -->
	<script type="text/javascript">
		var plugin_path = '<?php echo ADMIN_ASSETS; ?>assets/plugins/';
	</script>
	<script type="text/javascript" src="<?php echo ADMIN_ASSETS; ?>assets/plugins/jquery/jquery-2.2.3.min.js"></script>
	<script type="text/javascript" src="<?php echo ADMIN_ASSETS; ?>assets/js/app.js"></script>
</body>

</html>