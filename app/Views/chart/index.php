<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <!-- <div class="col-sm-6"> -->
            <h1>Charts</h1>
            <a href="/admin/chart/form" class="btn btn-success ml-auto"><i class="fa fa-plus"></i> Tambah Data</a>
            <!-- </div> -->
        </div>
    </div><!-- /.container-fluid -->
</section>

<section class="content" ng-controller="chartindex">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Chart List</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Chart Title</th>
                        <th>Keterangan</th>
                        <th>Active</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="chart in charts">
                        <td ng-bind="chart.title"></td>   
                        <td>{{ chart.ket_1 }}</td>
                        <td>{{ chart.active == 1 ? 'Ya' : 'Tidak' }}</td>
                        <td><a href="/admin/chart/form?id={{chart.id}}" class="btn btn-warning btn-sm mr-2"><i class="fa fa-edit"></i> Edit</a><button class="btn btn-danger btn-sm" ng-click="delete(chart.id)"><i class="fa fa-trash"></i> Delete</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<script>
    app.controller('chartindex', function($scope, $http, $timeout) {
        $scope.chart = function() {
            $http({
                method: 'POST',
                url: '/admin/chart/actindex'
            }).then(function(response) {
                $scope.charts = response.data.data;
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
                url: '/admin/chart/actdelete',
                data: {
                    id: id,
                }
            }).then(function(response){
                console.log(response.data);
                $('#example1').DataTable().destroy();
                $scope.chart();
            })
        }
        $scope.chart();
    })
</script>