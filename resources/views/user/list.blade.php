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
<h1 class="h3 mb-2 text-gray-800">Users</h1>
          <!-- <p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below. For more information about DataTables, please visit the <a target="_blank" href="https://datatables.net">official DataTables documentation</a>.</p> -->

          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">List of Users</h6>
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
                      <th>Name</th>
                      <th>UniqueID</th>
                      <th>Email</th>
                      <th>Role</th>
                      <th>Date Joined</th>
                      <th>Last Activity</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tfoot>
                  <tr>
                  <th>Name</th>
                      <th>UniqueID</th>
                      <th>Email</th>
                      <th>Role</th>
                      <th>Date Joined</th>
                      <th>Last Activity</th>
                      <th>Status</th>
                    </tr>
                  </tfoot>
                  <tbody>
                      @foreach($users as $user)
                        <tr>
                          <td>{{$user->name}}</td>
                          <td>{{$user->unique_id}}</td>
                          <td>{{$user->email}}</td>
                          <td>{{$user->role}}</td>
                          <td>{{$user->created_at}}</td>
                          <td>{{$user->updated_at}}</td>
                          <td>
                            @if((Carbon\Carbon::now()->diffInHours($user->updated_at) > 48))
                              <span class="badge badge-danger">Inactive</span>
                            @else
                              <span class="badge badge-success">Active</span>
                            @endif
                          </td>
                      </tr>
                      @endforeach
                   
                  </tbody>
                </table>

              </div>
            </div>
          </div>
        <!-- /.container-fluid -->

@endsection()