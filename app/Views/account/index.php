<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <!-- <div class="col-sm-6"> -->
            <h1>Users</h1>
            <a href="/admin/account/form" class="btn btn-success ml-auto"><i class="fa fa-plus"></i> Add user</a>
            <!-- </div> -->
        </div>
    </div><!-- /.container-fluid -->
</section>

<section class="content" ng-controller="accountindex">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Users</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Last Login</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="user in users">
                        <td>{{ user.username }}</td>
                        <td>{{ user.email }}</td>
                        <td>{{ user.last_login }}</td>
                        <td><a href="/admin/account/form?id={{user.id}}" class="btn btn-warning btn-sm mr-2"><i class="fa fa-edit"></i> Edit</a><button class="btn btn-danger btn-sm" ng-click="delete(user.id)"><i class="fa fa-trash"></i> Delete</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<script>
    app.controller('accountindex', function($scope, $http, $timeout) {
        $scope.akun = function() {
            $http({
                method: 'POST',
                url: '/admin/account/index2'
            }).then(function(response) {
                $scope.users = response.data.data;
                $timeout(function() {
                    $('#example1').DataTable();
                }, 200);
            })
        };

        $scope.delete = function (id) {
            $http({
                method: 'POST',
                url: '/admin/account/actdelete',
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