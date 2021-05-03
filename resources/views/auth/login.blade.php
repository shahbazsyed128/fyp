@extends('layouts.app')
@section('content')

<div class="hold-transition login-page">
    <div class="login-box">
    <div class="login-logo">
        <a href="/"><b>FYP</b>2021</a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
        <p class="login-box-msg">Sign in to start your session</p>
        <form  action="{{ route('login') }}" method="post">
            @csrf
            <div class="form-group row">
                <div class="col-md-12">
                    <div class="input-group mb-3">
                        <input id="email" type="email" class="form-control  @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email" required autocomplete="email" autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                        
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-12">
                    <div class="input-group mb-3">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" required autocomplete="current-password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-8">
                    <div class="icheck-primary">
                    <input  type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember">
                        Remember Me
                    </label>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-4">
                    <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                </div>
                <!-- /.col -->
            </div>
        </form>

        <!-- <div class="social-auth-links text-center mb-3">
            <p>- OR -</p>
            <a href="#" class="btn btn-block btn-primary">
            <i class="fab fa-facebook mr-2"></i> Sign in using Facebook
            </a>
            <a href="#" class="btn btn-block btn-danger">
            <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
            </a>
        </div> -->
        <!-- /.social-auth-links -->

        <p class="mb-1 ">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">
                    {{ __('I forgot my password') }}
                </a>
            @endif
           
        </p>
        <p class="mb-0">
            <a href="{{ route('register') }}" class="text-center">Register a new membership</a>
        </p>
        </div>
        <!-- /.login-card-body -->
    </div>
    </div>
    <!-- /.login-box -->
</div>

@endsection
