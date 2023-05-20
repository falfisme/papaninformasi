<div ng-controller="form">
<!-- form start -->
<form role="form">

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h1>Tambah Chart</h1>
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
			<h3 class="card-title"><a href="../chart/"><i class="fa fa-angle-left mr-3"></i></a>Detail Chart</h3>
		</div>
		<!-- /.card-header -->
			<div class="card-body">
				<div class="form-group">
					<label>Title</label>
					<input type="text" ng-model="data.title" class="form-control" placeholder="Masukkan Judul Chart" important>
				</div>
				<div class="form-group">
					<label>Keterangan</label>
					<input type="text" ng-model="data.ket_1" class="form-control" placeholder="Masukkan Keterangan" important>
				</div>
				<div class="form-group">
					<label>Keterangan Tambahan</label>
					<input type="text" ng-model="data.ket_2" class="form-control" placeholder="Opsional">
				</div>
				<div class="form-group">
					<label>Muncul di TV?</label>
					<select ng-model="data.active" class="form-control" important>
						<option value="1">Aktif</option>
						<option value="2">Nonaktif</option>
					</select>
				</div>

				<div class="form-group">
					<label>Key dan Value</label>
					<div class="row" ng-repeat="i in [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]">
						<div class="col-md-6 mb-3">
							<input type="text" ng-model="data.k[$index]" class="form-control" placeholder="Masukkan Key" important>
						</div>
						<div class="col-md-6">
							<input type="text" ng-model="data.val[$index]" class="form-control" placeholder="Masukkan Value" important>
						</div>	
					</div>
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
				url: '/admin/chart/actselect',
				params: {
					'id': $scope.id
				},
			}).then(function(response) {
				$scope.text.error = response.data.error;
				$scope.data = response.data.model;
				$scope.loading = 0;
			}).catch(function(fallback) {
				$scope.loading = 0;
				$scope.text.error = fallback;
			});;
		}

		$scope.submit = function() {
			$scope.loading = 1;
			// console.log($scope.data);
			// return;
			$http({
				method: 'POST',
				url: '/admin/chart/actupdate',
				data: {
					data: $scope.data
				},
			}).then(function(response) {
				$scope.loading = 0;
				$scope.text.error = response.data.error;
				$scope.text.success = response.data.success;
				// $scope.id = response.data.id;

				if($scope.text.success){
					$scope.data = {};
				}

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