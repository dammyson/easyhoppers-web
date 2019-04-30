@extends('layouts.app')
@push('styles')
    <!-- Custom styles for this page-->
  <link href="{{ asset('admin/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
@endpush
@push('scripts')

@endpush
@section('content')
<!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <!-- <h1 class="h3 mb-0 text-gray-800">User Profle</h1> -->
            <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
        </div>

         <!-- Content Row -->
        <div class="row">
            <div class="col-xl-6 col-md-6 mb-6">
            <div class="col-xl-6 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">User Profile</div>
                      <!-- <div class="h5 mb-0 font-weight-bold text-gray-800">$40,000</div> -->
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-user fa-2x text-gray-300"></i>

                    </div>
                    <dl>
                        <dt>Name</dt>
                        <dd>{{{ Auth::user()->name}}}</dd>
                        <dt>Email</dt>
                        <dd>{{{ Auth::user()->email }}}</dd>
                        <dt>Unique ID</dt>
                        <dd>{{{ Auth::user()->unique_id }}}</dd>
                        <dt>Date Joined</dt>
                        <dd>{{{ Auth::user()->created_at }}}</dd>
                        
                    </dl>
                  </div>
                </div>
              </div>
            </div>
               
            </div>
        </div>
        
        <!-- /.container-fluid -->

@endsection()