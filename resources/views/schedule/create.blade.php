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
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Schedule Management</h1>
            <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
        </div>

         <!-- Content Row -->
        <div class="row">
            <div class="col-xl-6 col-md-6 mb-6">

                @if(Session::has('error'))
                <div class="card bg-danger text-white shadow">
                    <div class="card-body">
                      Oops!!
                      <div class="text-white-50 small">{{Session::get('error')}}</div>
                    </div>
                  </div>
                @endif

                <div class="card shadow mb-6">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Create New</h6>
                    </div>
               
                    <div class="card-body">
                     <form class="user" method="POST" action="{{ route('uploadSchedule') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <input type="text" class="form-control{{ $errors->has('schedule_name') ? ' is-invalid' : '' }} form-control-user" id="exampleInputName" aria-describedby="emailHelp" placeholder="Enter Schedule Name..."  name="schedule_name" value="{{ old('schedule_name') }}" required autofocus>
                            @if ($errors->has('schedule_name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('schedule_name') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                        <input type="file" class="" id="exampleInputCode" placeholder="Code" name="file" required>
                        
                            @if ($errors->has('file'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('file') }}</strong>
                                </span>
                            @endif

                        </div>
                        
                    
                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                        {{ __('Upload') }}
                        </button>
                                 
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- /.container-fluid -->

@endsection()