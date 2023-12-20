@extends('user/master')
  
@section('content')
<style>
    .login-form {
        padding-top: 2rem;
        padding-bottom: 2rem;
    }
    .login-form .card {
        margin: auto; 
        width: 100%;
        max-width: 600px;
    }
    .login-form .form-group {
        justify-content: center;
        display: flex;
    }
    .field-icon {
        float: right;
        margin-right: 10px;
        margin-top: -25px;
        position: relative;
        z-index: 2;
        font-weight: 500;
    }
</style>
<main class="login-form">
  <div class="cotainer">
      <div class="row justify-content-center">
          <div class="col-md-8">
              <div class="card">
                  <div class="card-header">Reset Password</div>
                  <div class="card-body">
  
                      <form action="/submitResetPasswordForm" method="POST">
                          @csrf
                          <input type="hidden" name="token" value="{{ $token }}">
  
                          <div class="form-group row">
                              <label for="email_address" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>
                              <div class="col-md-6">
                                  <input type="text" id="email_address" class="form-control" name="email" value="{{$email}}" readonly>
                                  @if ($errors->has('email'))
                                      <span class="text-danger">{{ $errors->first('email') }}</span>
                                  @endif
                              </div>
                          </div>
  
                          <div class="form-group row">
                              <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                              <div class="col-md-6">
                                  <input type="password" id="password-enter" class="form-control" name="password" required autofocus>
                                  <span toggle="#password-enter" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                  @if ($errors->has('password'))
                                      <span class="text-danger">{{ $errors->first('password') }}</span>
                                  @endif
                              </div>
                          </div>
  
                          <div class="form-group row">
                              <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm Password</label>
                              <div class="col-md-6">
                                  <input type="password" id="password-confirm" class="form-control" name="password_confirmation" required autofocus>
                                  <span toggle="#password-confirm" class="fa fa-fw fa-eye field-icon toggle-confirm_password"></span>
                                  @if ($errors->has('password_confirmation'))
                                      <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                                  @endif
                              </div>
                          </div>
  
                          <div class="form-group">
                              <button type="submit" class="btn btn-primary mt-2">
                                  Reset Password
                              </button>
                          </div>
                      </form>
                  </div>
              </div>
          </div>
      </div>
  </div>
</main>
<script>
    $(".toggle-password, .toggle-confirm_password").click(function() {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $($(this).attr("toggle"));
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });
</script>
@endsection