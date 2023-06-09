<div ng-controller="form">
<!-- form start -->
<form role="form">

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h1>Tambah Event</h1>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<div class="container-fluid p-3" style="font-size: 10pt;">
<div class="row">
<!-- </div> -->
<div class="col-md-8">
	<div class="card card-primary">
		<div class="card-header">
			<h3 class="card-title"><a href="../event/"><i class="fa fa-angle-left mr-3"></i></a>Detail Event</h3>
		</div>
		<!-- /.card-header -->
			<div class="card-body">
				<div class="form-group">
					<label>Keterangan Event</label>
					<input type="text" ng-model="data.keterangan" class="form-control" placeholder="Masukkan Judul Event" important>
				</div>
				<div class="form-group">
					<label>Lokasi</label>
					<input type="text" ng-model="data.location" class="form-control" placeholder="Masukkan Lokasi" important>
				</div>
				<div class="form-group">
					<label>Tanggal Event</label>
					<input type="date" ng-model="data.date_start" class="form-control">
				</div>
				<div class="form-group">
					<label>Penanggung Jawab</label>
					<input type="text" ng-model="data.pic" class="form-control" placeholder="Masukkan PIC" important>
				</div>
				<div class="form-group">
					<label>Muncul di TV?</label>
					<select ng-model="data.active" class="form-control" important>
						<option value="1">Aktif</option>
						<option value="2">Nonaktif</option>
					</select>
				</div>
			</div>
			<!-- /.card-body -->
			<div class="card-footer">
				<div class="text-danger text-left ">{{text.error}}</div>
				<div class="text-success text-left ">{{text.success}}</div>
				<button type="submit" ng-click="submit();" class="btn btn-primary mt-0">Submit Data</button>
			</div>
	</div>
</div>

</div>
</div>
</form>
</div>
<script>
	app.controller("myapp", function($scope) {
		$scope.page = (id) ? 'Edit Akun' : 'Tambah akun baru'
	});

	app.controller("form", function($scope, $http) {
		
		$scope.imgsrc = '';
		$scope.text = {};
		$scope.id = getParams('id');
		$scope.data = {}; 
		$scope.data.id_user = <?=$id_user?>;

		$scope.getData = function() {
			$scope.loading = 1;
			$http({
				method: 'GET',
				url: '/admin/event/actselect',
				params: {
					'id': $scope.id
				},
			}).then(function(response) {
				$scope.text.error = response.data.error;
				$scope.data = response.data.model;
				$scope.data.date_start = new Date($scope.data.date_start);
				$scope.loading = 0;
			}).catch(function(fallback) {
				$scope.loading = 0;
				$scope.text.error = fallback;
			});;
		}

		$scope.submit = function() {
			$scope.loading = 1;
			$http({
				method: 'POST',
				url: '/admin/event/actupdate',
				data: {
					data: $scope.data
				},
			}).then(function(response) {
				$scope.loading = 0;
				$scope.text.error = response.data.error;
				$scope.text.success = response.data.success;
				$scope.id = response.data.id;

				if ($scope.id && $scope.text.success) {
					$scope.getData();
				}

			}).catch(function(fallback) {
				$scope.loading = 0;
				$scope.text.error = fallback;
			});;
		}

		if ($scope.id) {
			$scope.getData();
		}
 
	});

</script>