<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <!-- <div class="col-sm-6"> -->
            <h1>On leave</h1>
            <a href="/admin/onleave/form" class="btn btn-success ml-auto"><i class="fa fa-plus"></i> Tambah Data</a>
            <!-- </div> -->
        </div>
    </div><!-- /.container-fluid -->
</section>

<section class="content" ng-controller="onleaveindex">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Onleave List</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Task</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Active</th>
                        <th>Created By</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="onleave in onleaves">
                        <td ng-bind="onleave.nama"></td>   
                        <td>{{ onleave.jabatan }}</td>
                        <td>{{ onleave.task }}</td>
                        <td>{{ onleave.date_start }}</td>
                        <td>{{ onleave.date_end }}</td>
                        <td>{{ onleave.active == 1 ? 'Ya' : 'Tidak' }}</td>
                        <td>{{ onleave.namapembuat }}</td>
                        <td><a href="/admin/onleave/form?id={{onleave.id}}" class="btn btn-warning btn-sm mr-2"><i class="fa fa-edit"></i> Edit</a><button class="btn btn-danger btn-sm" ng-click="delete(onleave.id)"><i class="fa fa-trash"></i> Delete</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<script>
    app.controller('onleaveindex', function($scope, $http, $timeout) {
        $scope.onleave = function() {
            $http({
                method: 'POST',
                url: '/admin/onleave/actindex'
            }).then(function(response) {
                $scope.onleaves = response.data.data;
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
                url: '/admin/onleave/actdelete',
                data: {
                    id: id,
                }
            }).then(function(response){
                console.log(response.data);
                $('#example1').DataTable().destroy();
                $scope.onleave();
            })
        }
        $scope.onleave();
    })
</script>