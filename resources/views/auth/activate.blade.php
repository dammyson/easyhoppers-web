@extends('layouts.webapp')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Verify Your Email Address') }}</div>

                <div class="card-body">
                    @if(Session::has('success'))
                        <div class="alert alert-success" role="alert">
                            <!-- {{ __('A fresh verification link has been sent to your email address.') }} -->
                            {{Session::get('success')}}
                        </div>
                    @endif
                    @if(Session::has('error'))
                        <div class="alert alert-danger" role="alert">
                            <!-- {{ __('A fresh verification link has been sent to your email address.') }} -->
                            {{Session::get('error')}}
                        </div>
                    @endif
                    <!-- {{ __('Before proceeding, please check your email for a verification link.') }}
                    {{ __('If you did not receive the email') }}, <a href="{{ route('verification.resend') }}">{{ __('click here to request another') }}</a>. -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
