@extends('layouts.app')
@push('styles')
    <!-- Custom styles for this page-->
  <link href="{{ asset('admin/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush
@push('scripts')
  <!-- Page level plugins -->
  <script src="{{ asset('admin/vendor/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('admin/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

  <!-- Page level custom scripts -->
  <script src="{{ asset('admin/js/demo/datatables-demo.js') }}"></script>
@endpush
@section('content')
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Schedules</h1>
          <!-- <p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below. For more information about DataTables, please visit the <a target="_blank" href="https://datatables.net">official DataTables documentation</a>.</p> -->

          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Schedule listing </h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                @if(Session::has('success'))
                    <div class="card bg-success text-white shadow">
                        <div class="card-body">
                        Success
                        <div class="text-white-50 small">{{Session::get('success')}}</div>
                        </div>
                    </div>
                @endif
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Name</th>
                      <th>Airline Code</th>
                      <th>RouteID</th>
                      <th>Last Modified</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Id</th>
                      <th>Name</th>
                      <th>Airline Code</th>
                      <th>RouteID</th>
                      <th>Last Modified</th>
                    </tr>
                  </tfoot>
                  <tbody>
                      @foreach($schedules as $schedule)
                      <tr>
                        <td>{{$schedule->id}}</td>
                        <td>{{$schedule->schedule_name}}</td>
                        <td>{{$schedule->airlineCode}}</td>
                        <td>{{$schedule->route_id}}</td>
                        <td>{{$schedule->updated_at}}</td>
                      </tr>
                      @endforeach
                  </tbody>
                </table>

              </div>
            </div>
          </div>
        <!-- /.container-fluid -->

@endsection()