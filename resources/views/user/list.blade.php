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
<!-- <h1 class="h3 mb-2 text-gray-800">Users</h1> -->
          <!-- <p class="mb-4">DataTables is a third party plugin that is used to generate the demo table below. For more information about DataTables, please visit the <a target="_blank" href="https://datatables.net">official DataTables documentation</a>.</p> -->

             <!-- Content Row -->
          <div class="row">

<!-- Earnings (Monthly) Card Example -->
<div class="col-xl-3 col-md-6 mb-4">
  <div class="card border-left-primary shadow h-100 py-2">
    <div class="card-body">
      <div class="row no-gutters align-items-center">
        <div class="col mr-2">
          <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Users</div>
          <div class="h5 mb-0 font-weight-bold text-gray-800">{{{$totalUsers}}}</div>
        </div>
        <div class="col-auto">
          <i class="fas fa-calendar fa-2x text-gray-300"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Earnings (Monthly) Card Example -->
<div class="col-xl-3 col-md-6 mb-4">
  <div class="card border-left-success shadow h-100 py-2">
    <div class="card-body">
      <div class="row no-gutters align-items-center">
        <div class="col mr-2">
          <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Active Users</div>
          <div class="h5 mb-0 font-weight-bold text-gray-800">{{{$activeUsers}}}</div>
        </div>
        <div class="col-auto">
          <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Earnings (Monthly) Card Example -->
<div class="col-xl-3 col-md-6 mb-4">
  <div class="card border-left-info shadow h-100 py-2">
    <div class="card-body">
      <div class="row no-gutters align-items-center">
        <div class="col mr-2">
          <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Agents</div>
          <div class="row no-gutters align-items-center">
            <div class="col-auto">
              <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{{$totalAgents}}}</div>
            </div>
            <div class="col">
              <div class="progress progress-sm mr-2">
                <div class="progress-bar bg-info" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-auto">
          <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Pending Requests Card Example -->
<div class="col-xl-3 col-md-6 mb-4">
  <div class="card border-left-warning shadow h-100 py-2">
    <div class="card-body">
      <div class="row no-gutters align-items-center">
        <div class="col mr-2">
          <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Active Agents</div>
          <div class="h5 mb-0 font-weight-bold text-gray-800">{{{$activeAgents}}}</div>
        </div>
        <div class="col-auto">
          <i class="fas fa-comments fa-2x text-gray-300"></i>
        </div>
      </div>
    </div>
  </div>
</div>
</div>

<!--Filter Dropdown -->

<form >
<label> </label>

<select class="form-control col-md-2">
  <option value=""selected disabled>::Select to filter::</option>
  <option value="Agents">Agents</option>
  <option value="Customers">Customers</option>
</select>
      
</form>
<br>
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
                      <th>State</th>
                      <th>City</th>
                      <th>Date Joined</th>
                      <th>Last Activity</th>
                      <th>Time Away <sup>Hrs</sup></th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tfoot>
                  <tr>
                  <th>Name</th>
                      <th>UniqueID</th>
                      <th>Email</th>
                      <th>Role</th>
                      <th>State</th>
                      <th>City</th>
                      <th>Date Joined</th>
                      <th>Last Activity</th>
                      <th>Time Away <sup>Hrs</sup></th>
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
                          <td>{{$user->state}}</td>
                          <td>{{$user->city}}</td>
                          <td>{{$user->created_at}}</td>
                          <td>{{$user->updated_at}}</td>
                          <td>{{Carbon\Carbon::now()->diffInHours($user->updated_at)}}</td>
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