<div class="container-fluid p-3" style="font-size: 10pt;" ng-controller="form">
	<div class="card card-primary">
		<div class="card-header">
			<h3 class="card-title"><a href="../account/"><i class="fa fa-angle-left mr-3"></i></a>{{(id)?'Edit Akun':'Tambah akun baru'}}</h3>
		</div>
		<!-- /.card-header -->
		<!-- form start -->
		<form role="form">
			<div class="card-body">
				<div class="form-group" ng-show="id">
					<label>Foto Profil</label>
					<div class="col-md-3" ng-show="data.image">  
						<img ng-src="<?= base_url()?>assets/upload/{{data.image}}"  width="200" height="200" style="border:5px solid #f1f1f1; " />  
					</div>
					<!-- <div class="">  
						<input type="file" file-input="files" class="custom"/> 
						<button class="btn btn-info" ng-click="uploadFile()">Upload</button>  
					</div>   -->
				</div>
				<div class="form-group">
					<label ng-show="!id">Foto Profil</label>

					<div class="input-group">
						<div class="custom-file">
							<input type="file" class="custom-file-input" file-input="files" ng-model="imgsrc">
							<label class="custom-file-label">{{imagename || 'Choose Image' }} </label>
						</div>
						<div class="input-group-append">
							<span class="input-group-text" ng-click="uploadFile()">Upload</span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label>Nama Lengkap</label>
					<input type="text" ng-model="data.nama" class="form-control" placeholder="Masukkan nama lengkap">
				</div>
				<div class="form-group">
					<label>Alamat</label>
					<input type="text" ng-model="data.alamat" class="form-control" placeholder="Masukkan alamat">
				</div>
				<div class="form-group">
					<label>No. Telepon / HP</label>
					<input type="text" ng-model="data.telepon" class="form-control" placeholder="Masukkan nama lengkap">
				</div>
				<div class="form-group">
					<label>Jabatan</label>
					<input type="text" ng-model="data.jabatan" class="form-control" placeholder="Masukkan jabatan">
				</div>
				<hr>
				<div class="form-group">
					<label>Username (untuk login)</label>
					<input type="text" ng-model="data.username" class="form-control" placeholder="Enter username">
				</div>
				<div class="form-group">
					<label>Tipe akun</label>
					<select ng-model="data.type" class="form-control">
						<option value="1">Admin</option>
						<option value="2">Supervisor</option>
					</select>
					<!-- <input type="select" ng-model="data.email" class="form-control" placeholder="Enter email"> -->
				</div>
				<div class="form-group">
					<label>Email address</label>
					<input type="email" ng-model="data.email" class="form-control" placeholder="Enter email">
				</div>
				<div class="form-group">
					<label>Password</label>
					<input type="password" ng-model="data.passwordx" class="form-control" placeholder="Password">
				</div>
				<div class="form-group mb-0 pb-0">
					<label>Password Confirm</label>
					<input type="password" ng-model="data.confirm" class="form-control" placeholder="Password Confirm">
				</div>
			</div>
			<!-- /.card-body -->
			<div class="card-footer">
				<div class="text-danger text-left my-2">{{text.error}}</div>
				<div class="text-success text-left my-2">{{text.success}}</div>
				<button type="submit" ng-click="submit()" class="btn btn-primary">Submit</button>
			</div>
		</form>
	</div>
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
					 console.log(event.target.files);  
                     $scope.imagename = files[0].name;  
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

		$scope.getData = function() {
			$scope.loading = 1;
			$http({
				method: 'GET',
				url: '/admin/account/actselect',
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
			console.log($scope.data);
			$http({
				method: 'POST',
				url: '/admin/account/actupdate',
				data: {
					data: $scope.data
				},
			}).then(function(response) {
				$scope.loading = 0;
				$scope.text.error = response.data.error;
				$scope.text.success = response.data.success;
				if (!$scope.id && $scope.text.success) {
					$scope.data = {};
					$('.toastrDefaultSuccess').click(function() {
						toastr.success('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
					});
				}
				if ($scope.id && $scope.text.success) {
					$scope.getData();
					$('.toastrDefaultSuccess').click(function() {
						toastr.success('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
					});
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
           $http.post('/admin/account/actUploadImage', form_data,  
           {  
                transformRequest: angular.identity,  
                headers: {'Content-Type': undefined,'Process-Data': false},
				params: {
					'id': $scope.id
				},
           }).success(function(response){  
                // alert(response); 
				$scope.data.image = response.image;  
                // $scope.select();  
           });  
      	}  
    //   $scope.select = function(){  
    //        $http.get("select.php")  
    //        .success(function(data){  
    //             $scope.images = data;  
    //        });  
    //   } 
	});



	
</script>