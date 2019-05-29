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
            <div class="col-xl-6 col-md-6 mb-6">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">User Profile</div>
                      <!-- <div class="h5 mb-0 font-weight-bold text-gray-800">$40,000</div> -->
                    </div>
                    <div class="col-auto">
                    <dl>
                        <dt>Name</dt>
                        <dd>{{$user->name}}</dd>
                        <dt>Email</dt>
                        <dd>{{ $user->email }}</dd>
                        <dt>Unique ID</dt>
                        <dd>{{ $user->unique_id }}</dd>
                        <dt>Date Joined</dt>
                        <dd>{{ $user->created_at }}</dd>
                        @if($user->role == 'agent')
                             @if($user->status == 0)
                              <span class="btn btn-sm btn-warning" onclick="window.location='{{url("/user/status/$user->id/1")}}'">Suspend </span>
                              <span class="btn btn-sm btn-info" onclick="window.location='{{url("/user/status/$user->id/2")}}'">Deactivate </span>
                            @elseif($user->status == 1)
                              <span class="btn btn-sm btn-primary" onclick="window.location='{{url("/user/status/$user->id/0")}}'">Lift Suspension</span>
                            @elseif($user->status == 2)
                              <span class="btn btn-sm btn-success" onclick="window.location='{{url("/user/status/$user->id/0")}}'">Activate Agent</span>
                            @endif
                            <span class="btn btn-sm btn-danger" onclick="javascript:ConfirmDelete()">Delete</span> 
                            @endif
                    </dl>

                    </div>
                </div>
            </div>
            <button class="btn btn-sm btn-primary" onclick="window.location='{{url("/users/list")}}' "> Back </button>
              </div>
            </div>
               
            </div>
        </div>
        
        <!-- /.container-fluid -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<script type="text/javascript">
    function ConfirmDelete(){
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                    url: "/user/delete/{{$user->id}}",
                    type: "get", //send it through get method
                    success: function(response) {
                        //var parsedResponse = JSON.parse(response);
                        if(response.status === true){
                            Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            )
                        }
                    },
                    error: function(xhr) {
                        Swal.fire(
                                'Oops!',
                                'An error occured',
                                'error'
                            )
                    }
                });
                setTimeout(
                    function () {
                        window.location='{{url("/users/list")}}';
                }, 5000);
  
            }
        })

    }
</script>


@endsection()