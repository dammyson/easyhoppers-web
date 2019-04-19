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
            <h1 class="h3 mb-0 text-gray-800">Flight Management</h1>
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
                     <form class="user" method="POST" action="{{ route('addRoute') }}">
                        @csrf

                        <div class="form-group">
                            <label>Departure Port:</label>
                            <select class="form-control{{ $errors->has('departure_port') ? ' is-invalid' : '' }}" name="departure_port">
                                <option value="" disabled selected>Select departure port</option>
                                @foreach($airportList as $code => $name)
                                    <option value="{{$code}}">{{$name}}</option>
                                @endforeach
                            </select>
                            
                            @if ($errors->has('departure_port'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('departure_port') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Arrival Port:</label>
                            <select class="form-control{{ $errors->has('arrival_port') ? ' is-invalid' : '' }}" name="arrival_port">
                            <option value="" disabled selected>Select arrival port</option>
                                @foreach($airportList as $code => $name)
                                    <option value="{{$code}}">{{$name}}</option>
                                @endforeach
                            </select>
                            
                            @if ($errors->has('arrival_port'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('arrival_port') }}</strong>
                                </span>
                            @endif

                        </div>
                    
                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                        {{ __('Create Route') }}
                        </button>
                                 
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- /.container-fluid -->

@endsection()