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
            <h1 class="h3 mb-0 text-gray-800">Agents Management</h1>
            <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
        </div>
        
                @if(Session::has('success'))
                    <div class="card bg-success text-white shadow">
                        <div class="card-body">
                        Success
                        <div class="text-white-50 small">{{Session::get('success')}}</div>
                        </div>
                    </div>
                @endif
                @if(Session::has('error'))
                    <div class="card bg-danger text-white shadow">
                        <div class="card-body">
                        Oops!!
                        <div class="text-white-50 small">{{Session::get('error')}}</div>
                        </div>
                    </div>
                @endif

         <!-- Content Row -->
        <div class="row">
               
            <div class="col-xl-6 col-md-6 mb-6">

                <div class="card shadow mb-6">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Bulk Upload Agents</h6>
                    </div>
               
                    <div class="card-body">
                     <form class="user" method="POST" action="{{ route('uploadUsers') }}" enctype="multipart/form-data">
                        @csrf

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

            <div class="col-xl-6 col-md-6 mb-6">
                <div class="card shadow mb-6">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Single Upload Agents</h6>
                    </div>

                    <div class="card-body">
                    <span style="font-weight:900;">Unique ID: {{$unique_id}}</span>
                    <br><br>
                    <form class="user" method="POST" action="{{ route('saveUser') }}">
                         @csrf
                    <div class="form-group">
                      <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }} form-control-user" id="exampleInputName" aria-describedby="nameHelp" placeholder="Enter Fullname..."  name="name" value="{{ old('name') }}" required autofocus>
                      <input type="text" class=""   name="unique_id" value="{{ $unique_id }}" hidden>
                        @if ($errors->has('name'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group">
                      <input type="text" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }} form-control-user" id="exampleInputPhone" aria-describedby="phoneHelp" placeholder="Enter PhoneNumber..."  name="phone" value="{{ old('phone') }}" required>
                        @if ($errors->has('unique_id'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('phone') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group">
                      <input type="text" class="form-control{{ $errors->has('terminal') ? ' is-invalid' : '' }} form-control-user" id="exampleInputTerminal" aria-describedby="terminalHelp" placeholder="Enter Terminal..."  name="terminal" value="{{ old('terminal') }}" required>
                        @if ($errors->has('terminal'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('terminal') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <select name="state" id="state" class="form-control " required>
                            <option value="" selected="selected">- Select State-</option>
                            <option value="Abuja FCT">Abuja FCT</option>
                            <option value="Abia">Abia</option>
                            <option value="Adamawa">Adamawa</option>
                            <option value="Akwa Ibom">Akwa Ibom</option>
                            <option value="Anambra">Anambra</option>
                            <option value="Bauchi">Bauchi</option>
                            <option value="Bayelsa">Bayelsa</option>
                            <option value="Benue">Benue</option>
                            <option value="Borno">Borno</option>
                            <option value="Cross River">Cross River</option>
                            <option value="Delta">Delta</option>
                            <option value="Ebonyi">Ebonyi</option>
                            <option value="Edo">Edo</option>
                            <option value="Ekiti">Ekiti</option>
                            <option value="Enugu">Enugu</option>
                            <option value="Gombe">Gombe</option>
                            <option value="Imo">Imo</option>
                            <option value="Jigawa">Jigawa</option>
                            <option value="Kaduna">Kaduna</option>
                            <option value="Kano">Kano</option>
                            <option value="Katsina">Katsina</option>
                            <option value="Kebbi">Kebbi</option>
                            <option value="Kogi">Kogi</option>
                            <option value="Kwara">Kwara</option>
                            <option value="Lagos">Lagos</option>
                            <option value="Nassarawa">Nassarawa</option>
                            <option value="Niger">Niger</option>
                            <option value="Ogun">Ogun</option>
                            <option value="Ondo">Ondo</option>
                            <option value="Osun">Osun</option>
                            <option value="Oyo">Oyo</option>
                            <option value="Plateau">Plateau</option>
                            <option value="Rivers">Rivers</option>
                            <option value="Sokoto">Sokoto</option>
                            <option value="Taraba">Taraba</option>
                            <option value="Yobe">Yobe</option>
                            <option value="Zamfara">Zamfara</option>
                            <option value="Outside Nigeria">Outside Nigeria</option>
                        </select>
                        @if ($errors->has('state'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('state') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group">
                      <input type="text" class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }} form-control-user" id="exampleInputCity" aria-describedby="cityHelp" placeholder="Enter City..."  name="city" value="{{ old('city') }}" required>
                        @if ($errors->has('city'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('city') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group">
                      <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }} form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Enter Email Address..."  name="email" value="{{ old('email') }}" required>
                        @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group">
                      <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }} form-control-user " id="exampleInputPassword" placeholder="Password" name="password" required>
                    
                        @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif

                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                    {{ __('Submit') }}
                    </button>
                    <hr>

                  </form>
                    </div>
                </div>
               
            </div>

        </div>
        
        <!-- /.container-fluid -->

@endsection()

