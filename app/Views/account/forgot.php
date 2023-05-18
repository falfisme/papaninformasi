<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Crawl Product Manager</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="icon" href="/asset/icon.png" type="image/gif" sizes="16x16"/>
	<link rel="stylesheet" href="/asset/bootstrap4.3.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="/asset/adminmenu.css?ver=20210120">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script src="/asset/js/jquery3.4.1.min.js"></script>
	<script src="/asset/js/popper1.16.0.min.js"></script>
	<script src="/asset/bootstrap4.3.1/js/bootstrap.min.js"></script>
	<script src="/asset/js/angular1.5.6.min.js"></script>
    </head>
    <body class="mt-5 bg-dark text-white" ng-app="myApp">
	<div class="container" ng-controller="doAngular">
	    <div class="row">
		<div class="col-12 col-md-6 offset-md-3 text-center">
		    <h2><b>Crawl Product</b>Manager</h2>
		    <div class="jumbotron mt-4 py-4 px-4 text-black-50">
			Forgot password? please enter your email
			<input class="form-control mt-3" placeholder="email" ng-model="data.email">
			<div class="text-right mt-3 mb-2">
			    <small>
				<div class="text-danger text-left my-2">{{text.error}}</div>
				<div class="text-success text-left my-2">{{text.success}}</div>
			    </small>
			</div>
			<div class="btn btn-primary disabled w-100" ng-show="loading">
			    <i class="spinner-border spinner-border-sm"></i>
			</div>
			<button class="btn btn-primary w-100" ng-click="submit()" ng-if="!loading">Submit</button>
		    </div>
		</div>
	    </div>
	</div>
	<script>
            app = angular.module('myApp', []);
            app.controller("doAngular", function ($scope, $http, $timeout) {
		$scope.loading = 0;
		$scope.text = {};
		$scope.submit = function() {
		    $scope.loading = 1;
		    $http({
			url: '/api/akun/resetpassword',
			method: 'post',
			data: $scope.data,
		    }).then(function(response) {
			$scope.loading = 0;
			$scope.text.error = response.data.error;
			$scope.text.success = response.data.success;
			if (response.data.success) {
			    $timeout(function() {
				location.href="/";
			    }, 4000);
			}
		    });
		}
            });
	</script>
</html>