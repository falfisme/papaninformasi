<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>NLR Indonesia - Log in</title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Font Awesome -->
	<link rel="stylesheet" href="/assets/admin/plugins/fontawesome-free/css/all.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	<!-- icheck bootstrap -->
	<link rel="stylesheet" href="/assets/admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="/assets/admin/dist/css/adminlte.min.css">
	<!-- Google Font: Source Sans Pro -->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
	<!-- <script src="/assets/js/jquery3.4.1.min.js"></script> -->
	<script src="/assets/js/popper1.16.0.min.js"></script>
	<script src="/assets/js/angular1.5.6.min.js"></script>

</head>

<body class="hold-transition login-page" ng-app="myApp" ng-controller="doAngular">
	<div class="login-box">
		<div class="login-logo">
			<a href="<?= base_url() ?>assets/admin/index2.html"><img src="/assets/nlrlogo.jpg" style="width:120px;"></a>

		</div>
		<!-- /.login-logo -->
		<div class="card">
			<div class="card-body login-card-body">
				<p class="login-box-msg">Sign in to start your session</p>

				<form method="post">
					<div class="input-group mb-3">
						<input type="text" class="form-control" placeholder="Username" ng-model="data.username">
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-user"></span>
							</div>
						</div>
					</div>
					<div class="input-group mb-3">
						<input type="password" class="form-control" placeholder="Password" ng-model="data.password">
						<div class="input-group-append">
							<div class="input-group-text">
								<span class="fas fa-lock"></span>
							</div>
						</div>
					</div>
					<div class="row">

						<!-- /.col -->
						<div class="col">
							<button type="submit" class="btn btn-primary btn-block" ng-click="submit()" ng-if="!loading">Sign In</button>
						</div>
						<!-- /.col -->
					</div>
				</form>

				<div class="social-auth-links text-center mb-3">
					<div class="text-right mt-1 mb-2">
						<!-- <a href="/akun/forgot">Forgot password?</a> -->
					</div>
					<div class="btn btn-primary disabled w-100" ng-show="loading">
						<i class="spinner-border spinner-border-sm"></i>
					</div>
					<p>
					<div class="text-danger text-left my-2">{{text.error}}</div>
					<div class="text-success text-left my-2">{{text.success}}</div>
					</p>

				</div>
			</div>
			<!-- /.login-card-body -->
		</div>
	</div>

	<script>
		app = angular.module('myApp', []);
		app.controller("doAngular", function($scope, $http) {
			$scope.loading = 0;
			$scope.text = {};
			$scope.submit = function() {
				$scope.loading = 1;
				$http({
					url: '/admin/account/actsignin',
					method: 'post',
					data: $scope.data,
				}).then(function(response) {
					$scope.loading = 0;
					$scope.text.error = response.data.error;
					$scope.text.success = response.data.success;
					console.log(response.data);
					if (response.data.success) {
						location.href = "/admin/dashboard";
					}
				});
			};
		});
	</script>
	<script>
		function setCookie(cname, cvalue, exdays) {
			var d = new Date();
			d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
			var expires = "expires=" + d.toGMTString();
			document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
		}

		function getCookie(cname) {
			var name = cname + "=";
			var decodedCookie = decodeURIComponent(document.cookie);
			var ca = decodedCookie.split(';');
			for (var i = 0; i < ca.length; i++) {
				var c = ca[i];
				while (c.charAt(0) == ' ') {
					c = c.substring(1);
				}
				if (c.indexOf(name) == 0) {
					return c.substring(name.length, c.length);
				}
			}
			return "";
		}
	</script>
	<!-- /.login-box -->

	<!-- jQuery -->
	<script src="/assets/admin/plugins/jquery/jquery.min.js"></script>
	<!-- Bootstrap 4 -->
	<script src="/assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- AdminLTE App -->
	<script src="/assets/admin/dist/js/adminlte.min.js"></script>

</body>

</html>