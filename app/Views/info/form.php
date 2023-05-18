<div ng-controller="form">
<!-- form start -->
<form role="form">

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h1>Tambah Info</h1>
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
			<h3 class="card-title"><a href="../info/"><i class="fa fa-angle-left mr-3"></i></a>Detail Informasi</h3>
		</div>
		<!-- /.card-header -->
			<div class="card-body">
				<div class="form-group">
					<label>Title Info</label>
					<input type="text" ng-model="data.title" class="form-control" placeholder="Masukkan Title" important>
				</div>
				<div class="form-group">
					<label>Caption</label>
					<textarea type="text" ng-model="data.caption" class="form-control" placeholder="Masukkan caption" important></textarea>
				</div>
				<div class="form-group">
					<label>Keterangan Info</label>
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


<div class="col-md-4">
	<div class="card card-info">
		<div class="card-header">
			<h3 class="card-title"><i class="fa fa-image mr-3"></i>Foto Depan</h3>
		</div>
		<div class="card-body">
			<div class="form-group">
				<label>Gambar Info</label>
					<img ng-src="{{data.image ? '/assets/upload/' + data.image : imagetemp}}" width="100%" style="border:5px solid #f1f1f1; margin-bottom:20px;"/>  
					<div class="input-group">
						<div class="custom-file">
							<input type="file" class="custom-file-input" file-input="files" id="imgsrc" ng-model="data.image">
							<label class="custom-file-label">{{imagename || 'Choose Image' }} </label>
						</div>
					</div>
			</div>
		</div>
		<div class="card-footer">
				<div class="text-danger text-left">{{text.error2}}</div>
				<div class="text-success text-left">{{text.success2}}</div>
				<button type="submit" id="submitgambar" ng-click="uploadFile()" class="btn btn-info">Submit Gambar</button>
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

	app.directive("fileInput", function($parse){  
      return{  
           link: function($scope, element, attrs){  
                element.on("change", function(event){  
                     var files = event.target.files;
                     $scope.imagename = files[0].name;
					 $scope.imagetemp = URL.createObjectURL(files[0]); 
                     $parse(attrs.fileInput).assign($scope, element[0].files);  
                     $scope.$apply();  
                });  
           }  
      }  
 	});  

	app.controller("form", function($scope, $http) {
		
		$scope.imgsrc = '';
		$scope.text = {};
		$scope.id = getParams('id');
		$scope.data = {}; 
		$scope.data.id_user = <?=$id_user?>;

		var tombolgambar = document.getElementById('submitgambar');
		var inputgambar = document.getElementById('imgsrc');

		if(!$scope.id){
			tombolgambar.setAttribute("disabled", "");
			inputgambar.setAttribute("disabled", "");
		}

		$scope.getData = function() {
			$scope.loading = 1;
			$http({
				method: 'GET',
				url: '/admin/info/actselect',
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
			$http({
				method: 'POST',
				url: '/admin/info/actupdate',
				data: {
					data: $scope.data
				},
			}).then(function(response) {
				$scope.loading = 0;
				$scope.text.error = response.data.error;
				$scope.text.success = response.data.success;
				$scope.id = response.data.id;

				if ($scope.id && $scope.text.success) {
					tombolgambar.removeAttribute("disabled");
					inputgambar.removeAttribute("disabled");
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

		$scope.uploadFile = function(){  
           var form_data = new FormData();  
           angular.forEach($scope.files, function(file){  
                form_data.append('file', file);  
           });  
           $http.post('/admin/info/actUploadImage', form_data,  
           {  
                transformRequest: angular.identity,  
                headers: {'Content-Type': undefined,'Process-Data': false},
				params: {
					'id': $scope.id
				},
           }).then(function(response){  
				$scope.text.error2 = response.data.error;
				$scope.text.success2 = response.data.success; 
           });  
      	}  
	});

</script>