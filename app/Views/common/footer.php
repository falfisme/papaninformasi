</div>
<!-- /.content-wrapper -->
<footer class="main-footer">
	<div class="float-right d-none d-sm-block">
		<!-- <b>Version</b> 3.0.1 -->
	</div>
	<strong>Copyright &copy; 2023.</strong> All rights
	reserved.
</footer>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
	<!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="/assets/admin/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="/assets/admin/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="/assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="/assets/admin/plugins/datatables/jquery.dataTables.js"></script>
<script src="/assets/admin/plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<!-- ChartJS -->
<script src="/assets/admin/plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="/assets/admin/plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="/assets/admin/plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="/assets/admin/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="/assets/admin/plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="/assets/admin/plugins/moment/moment.min.js"></script>
<script src="/assets/admin/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="/assets/admin/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="/assets/admin/plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="/assets/admin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="/assets/admin/dist/js/adminlte.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="/assets/admin/dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="/assets/admin/dist/js/demo.js"></script>

<script>
	app.controller('headerController', function($scope, $http) {
		$scope.loading = false;
		$scope.menu = [];
		$scope.logout = function() {
			$http({
				method: "GET",
				url: "/admin/account/actsignout",
			}).then(function(response) {
				location.href = "/admin/account/login";
			});
		}
	});

	app.controller('sidebar', function($scope, $http) {
		$scope.acc = {};
		$scope.getUsername = function() {
			$http({
				method: "GET",
				url: "/admin/account/username",
			}).then(function(response) {
				$scope.username = response.data.username
				$scope.acc = response.data.acc
				$scope.type = response.data.type
			});
		}

		$scope.user = function(){
			location.href = "/admin/account/form?id=" + $scope.acc.id;
		}
		$scope.getUsername();
	});

	function getParams(ev) {
		searchParams = new URLSearchParams(window.location.search);
		if (searchParams.has(ev)) {
			return searchParams.get(ev);
		} else {
			return false;
		}
	}
</script>

</body>

</html>