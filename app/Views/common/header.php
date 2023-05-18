<!DOCTYPE html>
<html ng-app="myApp">

<head>
	<meta charset="UTF-8">
	<title>NLR Indonesia - <?= $title ?></title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="icon" href="/assets/icon.png" type="image/gif" sizes="16x16" />
	<link rel="stylesheet" href="/assets/bootstrap4.3.1/css/bootstrap.min.css">
	<!-- <link rel="stylesheet" href="/assets/adminmenu.css?ver=20210120"> -->
	<link rel="stylesheet" href="/assets/js/jquery-ui.css">
	<!-- <link rel="stylesheet" href="/assets/fontawesome/font-awesome.min.css"> -->
	<link rel="stylesheet" href="/assets/admin/plugins/fontawesome-free/css/all.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	<!-- DataTables -->
	<link rel="stylesheet" href="/assets/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="/assets/admin/dist/css/adminlte.min.css">
	<!-- Google Font: Source Sans Pro -->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
	<!-- ckeditor -->
	<script src="/assets/ckeditor/ckeditor.js" type="text/javascript"></script>
	<script src="/assets/ckeditor/samples/js/sample.js" type="text/javascript"></script>
	<script src="/assets/js/jquery3.4.1.min.js"></script>
	<script src="/assets/js/popper1.16.0.min.js"></script>
	<script src="/assets/js/jquery-ui.js"></script>
	<script src="/assets/bootstrap4.3.1/js/bootstrap.min.js"></script>
	<script src="/assets/js/angular1.5.6.min.js"></script>
	<script>
		app = angular.module('myApp', []);
	</script>
	<!-- <script src="/assets/autonumber.js"></script> -->
</head>

<body class="hold-transition sidebar-mini">
	<div class="wrapper">
		<!-- Navbar -->
		<nav class="main-header navbar navbar-expand navbar-orange navbar-dark" ng-controller="headerController">
			<!-- Left navbar links -->
			<ul class="navbar-nav">
				<li class="nav-item">
					<a class="nav-link" data-widget="pushmenu" href=""><i class="fas fa-bars"></i></a>
				</li>
				<li class="nav-item d-none d-sm-inline-block">
					<a href="/admin/dashboard" class="nav-link">Home</a>
				</li>
				<li class="nav-item d-none d-sm-inline-block">
					<div ng-click="logout()" style="cursor: pointer;" class="nav-link">Log Out</div>
				</li>
			</ul>
		</nav>
		<!-- /.navbar -->
		<!-- Main Sidebar Container -->
		<aside class="main-sidebar sidebar-dark-primary elevation-4">
			<!-- Brand Logo -->
			<a href="/dashboard" class="brand-link logo-switch">
				<img src="/assets/nlrlogo.jpg" alt="AdminLTE Logo" class="brand-image-xl elevation-3" style="opacity: .8">
				<!-- <i class="nav-icon fas fa-check text-info ml-3 p-1"></i> -->
				<span class="brand-text font-weight-bold">NLR Indonesia</span>
			</a>

			<!-- Sidebar -->
			<div class="sidebar" ng-controller="sidebar">
				<!-- Sidebar user (optional) -->
				<div class="user-panel mt-3 pb-3 mb-3 d-flex">
					<div class="image">
						<img src="/assets/admin/dist/img/gambar2.jpg" class="img-circle elevation-2" alt="User Image">
					</div>
					<div class="info">
						<div class="d-block text-white">{{username}}</div>
					</div>
				</div>

				<!-- Sidebar Menu -->
				<nav class="mt-2">
					<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
						<!-- Add icons to the links using the .nav-icon class
           with font-awesome or any other icon font library -->

						<li class="nav-header">Main Menu</li>

						<!-- MENU DASHBOARD -->

						<li class="nav-item">
							<a href="/admin/dashboard/" class="nav-link waria">
								<i class="nav-icon fa fa-door-open"></i>
								<p>On leave / Out work</p>
							</a>
						</li>


						<li class="nav-item">
							<a href="/admin/info/" class="nav-link dasbor">
								<i class="nav-icon fas fa-info "></i>
								<p>Informations</p>
							</a>
						</li>

						<li class="nav-item">
							<a href="/admin/dashboard" class="nav-link wps">
								<i class="nav-icon fa fa-calendar"></i>
								<p>Events</p>
							</a>
						</li>

						<li class="nav-header" ng-show="type == 1">Settings</li>
						<li class="nav-item" ng-show="type == 1">
							<a href="/admin/account/index" class="nav-link akun">
								<i class="nav-icon fas fa-users"></i>
								<p>User Management</p>
							</a>
						</li>
						<li class="nav-item" ng-show="type == 1">
							<a href="/admin/account/index/" class="nav-link akun">
								<i class="nav-icon fas fa-users"></i>
								<p>Employee Management</p>
							</a>
						</li>

						<!-- MENU PENGGUNA -->
						<!-- <li class="nav-item has-treeview">
							<a href="#" class="nav-link">
								<i class="nav-icon fas fa-user"></i>
								<p>
									WPS
									<i class="right fas fa-angle-left"></i>
								</p>
							</a>
							<ul class="nav nav-treeview">
								<li class="nav-item">
									<a href="#" class="nav-link">
										<i class="fas fa-table nav-icon"></i>
										<p>Data Pengguna</p>
									</a>
								</li>
								<li class="nav-item">
									<a href="#" class="nav-link">
										<i class="fas fa-plus nav-icon"></i>
										<p>Tambah Pengguna</p>
									</a>
								</li>
							</ul>
						</li> -->

					</ul>
				</nav>
				<!-- /.sidebar-menu -->
			</div>
			<!-- /.sidebar -->
		</aside>

		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">


			<script>
				$(function() {
					var current = location.pathname;
					if (current == "/dashboard/" || current == "/dashboard") {
						$(".dasbor").addClass("active");
					}

					if (current == "/dashboard/index.php/main/wps") {
						$(".wps").addClass("active");
					}

					if (current == "/dashboard/index.php/main/lsl") {
						$(".lsl").addClass("active");
					}

					if (current == "/dashboard/index.php/main/pelanggan") {
						$(".pelanggan").addClass("active");
					}

					if (current == "/dashboard/index.php/main/penasun") {
						$(".penasun").addClass("active");
					}

					if (current == "/dashboard/index.php/main/waria") {
						$(".waria").addClass("active");
					}

					if (current.includes('/dashboard/index.php/account')) {
						$(".akun").addClass("active");
					}
				})
			</script>