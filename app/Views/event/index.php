<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <!-- <div class="col-sm-6"> -->
            <h1>Events</h1>
            <a href="/admin/event/form" class="btn btn-success ml-auto"><i class="fa fa-plus"></i> Tambah Data</a>
            <!-- </div> -->
        </div>
    </div><!-- /.container-fluid -->
</section>

<section class="content" ng-controller="eventindex">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Event List</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Keterangan Event</th>
                        <th>Lokasi</th>
                        <th>PIC</th>
                        <th>Active</th>
                        <th>Created By</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="event in events">
                        <td ng-bind="event.date_start"></td>   
                        <td>{{ event.keterangan }}</td>
                        <td>{{ event.location }}</td>
                        <td>{{ event.pic }}</td>
                        <td>{{ event.active == 1 ? 'Ya' : 'Tidak' }}</td>
                        <td>{{ event.nama }}</td>
                        <td><a href="/admin/event/form?id={{event.id}}" class="btn btn-warning btn-sm mr-2"><i class="fa fa-edit"></i> Edit</a><button class="btn btn-danger btn-sm" ng-click="delete(event.id)"><i class="fa fa-trash"></i> Delete</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<script>
    app.controller('eventindex', function($scope, $http, $timeout) {
        $scope.event = function() {
            $http({
                method: 'POST',
                url: '/admin/event/actindex'
            }).then(function(response) {
                $scope.events = response.data.data;
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
                url: '/admin/event/actdelete',
                data: {
                    id: id,
                }
            }).then(function(response){
                console.log(response.data);
                $('#example1').DataTable().destroy();
                $scope.event();
            })
        }
        $scope.event();
    })
</script>