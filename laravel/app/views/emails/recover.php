<!DOCTYPE html>
<html>
	<head>
		<title>Reset your password</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta content="IE=7, IE=9" http-equiv="X-UA-Compatible">
		<!-- <link rel="icon" type="image/x-icon" href=""> -->
		<style type="text/css">
			body {font:16px/30px Georgia, 'Times New Roman', serif; min-width:450px; margin:0; padding:45px 0 0 0; text-align:center;}
			h1 {font-weight:normal; font-size:45px; line-height:60px; margin-bottom:5px; padding:0; margin-top:0; }
			p {padding:0 0 10px 0;}
			a {color:#336699;}
			textarea {font:12px/18px 'Helvetica', Arial, sans-serif;}
			
			.header {padding-bottom:15px;}
			.header span {font-size:22px;}
			.wrapper {width:400px; margin:0 auto 30px auto; text-align:left;}
			.container {position:relative; border-width:0 !important; border-color:transparent !important; margin:0; text-align:left; background-color:#ffffff; background:rgba(255,255,255,.9); padding:20px 20px 40px 20px; line-height:150%; -moz-box-shadow:0 1px 1px rgba(0, 0, 0, .5); -webkit-box-shadow:0 1px 1px rgba(0, 0, 0, .5); box-shadow:0 1px 1px rgba(0, 0, 0, .5); }
			.clear {clear:both;}

			/* Form */
			form {font:16px/20px 'Helvetica', Arial, sans-serif; display:block; padding:15px 0 10px 0; margin-top:15px; border-top:1px solid #ccc;}
			label {clear:both; display:block; margin:8px 0; font-weight:bold; position:relative; line-height:150%; font-family:Helvetica; font-size: 14px; color: #333333;}
			.email-group input {display:block; float:left; width:60%; margin:0 10px 0 0; min-width:200px; padding:12px 10px 11px 10px; border:1px solid #ccc; border-radius:4px; font-size:14px; box-shadow:none;}
			.email-group .button {width:30%; margin:0; min-width:75px;}
			
			.error, .errorText {margin:5px 0 0 0; padding:10px; font-size:14px; color:#6b0505; background-color:#f4bfbf;}
			.formstatus {margin-bottom:10px;}

			.success {background:#e4f3d4; font-size:14px; color:#5ca000; margin:10px 0; padding:10px; font-family:Georgia, 'Times New Roman', serif;;}
			.success a {color:#5ca000; text-decoration:underline;}

			.rounded {border-radius:4px; -moz-border-radius:4px; -webkit-border-radius:4px;}

			.button, .button-small {display:inline-block; white-space:nowrap; height:40px; line-height:42px; margin:0 5px 0 0; padding:0 22px; text-decoration:none; text-transform:uppercase; text-align:center; font-weight:bold; font-style:normal; font-size:14px; cursor:pointer; border:0; -moz-border-radius:4px; border-radius:4px; -webkit-border-radius:4px; vertical-align:top;}
			.button-small {float:none; display:inline-block; height:auto; line-height:18px !important; padding:2px 15px !important; font-size:11px !important;}
			.button:hover, .button-small:hover{opacity:.8;}

			/* Mobile Tweaks */
			body {-webkit-text-size-adjust:none;}
			input {-webkit-appearance: none;}
			input[type=checkbox] {-webkit-appearance: checkbox;}
			input[type=radio] {-webkit-appearance: radio;}
			
			/* Customizable Theme Bits */
			body {background-color:#DDDDDD; }

			

			h1.title {font-family:Georgia, Times New Roman, Times, serif; color:#111111;}
			.byline {font-family:Georgia, Times New Roman, Times, serif; color:#111111;}
			.description, label, .fake-label {font-family:Georgia, Times New Roman, Times, serif; color:#111111;}
			.button {font-family:Helvetica, Arial, sans-serif; color:#FFFFFF; background-color:#333333;}
			
			/* Fly-out tinyletter Panel */
			.tl-tab {position:fixed; bottom:0; left:0; width:100%; text-align:center; font-family:Georgia, "Times New Roman", Times, serif; color:#111;}

			.tl-button {font-family:helvetica, arial, sans-serif; background-clip:padding-box; background-color:#e6201a; border: 1px solid #b81a15; border-radius:4px; color:#fff !important; cursor:pointer; display:inline-block; font-weight:500; font-size:12px; line-height:20px; letter-spacing:0.3px; margin:.2em 15px .2em 0; padding:6px 12px; text-align:center; text-decoration:none; text-shadow: 1px 1px 1.5px rgba(0,0,0,.5); text-transform:uppercase; white-space:nowrap; width:auto; -webkit-transition: opacity 0.2s ease-out; -moz-transition: opacity 0.2s ease-out; -o-transition: opacity 0.2s ease-out; transition: opacity 0.2s ease-out; -moz-box-shadow:0 0.3em 0.15em -0.05em rgba(255, 255, 255, 0.2) inset, 0.095em 0.12em 0.1em rgba(0, 0, 0, 0.3); -webkit-box-shadow:0 0.3em 0.15em -0.05em rgba(255, 255, 255, 0.2) inset, 0.095em 0.12em 0.1em rgba(0, 0, 0, 0.3); box-shadow:0 0.3em 0.15em -0.05em rgba(255, 255, 255, 0.2) inset, 0.095em 0.12em 0.1em rgba(0, 0, 0, 0.3);}
			.tl-button:hover {background:#c60600; text-decoration:none !important; }

			.view-messages { border-top: 1px solid #AAAAAA; font-size: 13px; margin: 30px 0 -28px; padding-top: 5px; }
			#view-messages-link { font-size: 16px; text-decoration: none; color: #ED1F24; }
			#view-messages-link:hover, #view-messages-link:focus {color:#000; text-decoration:none;}
		</style>
	</head>
	<body>
		<div class="wrapper">
			<div class="container">
				<div class="header">
					<h1 class="title">Hello,</h1>
					<span class="byline">Forgot your password?</span>
				</div>
				<div class="description">
					<p>We received a request to reset the password for your account. To reset your password, click on the button below:</p>

					<p>
						<a href="<?php echo $reset_url; ?>" class="button">Reset your password now</a>
					</p>

					<p>Or copy and paste the URL into your browser: <?php echo $reset_url; ?></p>
				</div>

				<div class="view-messages"> 
					Please do not reply to this message; it was sent from an unmonitored email address. This message is a service email related to your use of <?php echo Config::get('app.domain'); ?>.
				 </div>
			</div>
		</div>
	</body>
</html>