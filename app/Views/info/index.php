<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <!-- <div class="col-sm-6"> -->
            <h1>Information</h1>
            <a href="/admin/info/form" class="btn btn-success ml-auto"><i class="fa fa-plus"></i> Tambah Data</a>
            <!-- </div> -->
        </div>
    </div><!-- /.container-fluid -->
</section>

<section class="content" ng-controller="infoindex">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Information List</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Date Created</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Active</th>
                        <th>Created By</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="user in users">
                        <td> {{ user.date_created | date: "dd-MM-yyyyTHH:mm:ss"}}</td>    
                        <td><img src="{{ user.image ? '/assets/upload/' + user.image : '' }}" class="img-thumbnail" width="200px" alt=""></td>
                        <td>{{ user.title }}</td>
                        <td>{{ user.active == 1 ? 'Ya' : 'Tidak' }}</td>
                        <td>{{ user.nama }}</td>
                        <td><a href="/admin/info/form?id={{user.id}}" class="btn btn-warning btn-sm mr-2"><i class="fa fa-edit"></i> Edit</a><button class="btn btn-danger btn-sm" ng-click="delete(user.id)"><i class="fa fa-trash"></i> Delete</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<script>
    app.controller('infoindex', function($scope, $http, $timeout) {
        $scope.akun = function() {
            $http({
                method: 'POST',
                url: '/admin/info/actindex'
            }).then(function(response) {
                $scope.users = response.data.data;
                $timeout(function() {
                    $('#example1').DataTable({
                        order: [[1, 'desc']],
                    });
                }, 200);
            })
        };

        $scope.delete = function (id) {
            $http({
                method: 'POST',
                url: '/admin/info/actdelete',
                data: {
                    id: id,
                }
            }).then(function(response){
                console.log(response.data);
                $('#example1').DataTable().destroy();
                $scope.akun();
            })
        }
        $scope.akun();
    })
</script>