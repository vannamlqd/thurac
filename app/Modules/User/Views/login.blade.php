<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link href="{{ asset('public/css/ad_style.css') }}" rel="stylesheet">
</head>
<body>
	<div class="container">
	<div class="row" style="padding-top:20%">
		<div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
			<div class="login-panel panel panel-default">
			<div class="panel-heading">Log in</div>
			<div class="panel-body">
				@include('errors.showerr')
				<form role="form" action="" method="Post">
				<input type="hidden" name="_token" value="{{csrf_token()}}">
				<fieldset>
					<div class="form-group">
					<input class="form-control" placeholder="E-mail" name="txtEmail" type="text" value="{{old('txtEmail')}}">
					</div>
					<div class="form-group">
					<input class="form-control" placeholder="Password" name="txtPassword" type="password" value="{{old('txtPassword')}}">
					</div>
					<div class="checkbox">
					<label>
						<input name="remember" type="checkbox" value="Remember Me">Remember Me
					</label>
					</div>
					<input type="submit" class="btn btn-primary" value="Login">
				</fieldset>
				</form>
			</div>
			</div>
		</div><!-- /.col-->
		</div><!-- /.row -->
	</div>  
<script   src="https://code.jquery.com/jquery-3.1.0.min.js" ></script>  
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>
