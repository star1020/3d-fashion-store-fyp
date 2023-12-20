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
                  <div class="card-header">Change Password</div>
                  <div class="card-body">
                    @if(\Session::has('success'))
                        <div class="alert alert-success">
                            <p>{{ \Session::get('success') }}</p>
                        </div><br>
                    @endif
                      <form action="{{route('submitChangePassword', ['id' => $data->id])}}" method="POST">
                          @csrf
                          @if(!(\Session::has('appendFields')) && !$errors->has("password"))
                          <div class="form-group row">
                              <label for="current_password" class="col-md-4 col-form-label text-md-right">Password</label>
                              <div class="col-md-6">
                                  <input type="password" id="current_password" class="form-control" placeholder="Enter Current Password" name="current_password" @if (\Session::has('error')) autofocus @endif>
                                  <span toggle="#current_password" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                  @if(\Session::has('error'))
                                    <span class="text-danger" style="font-size: 14px">{{ \Session::get('error') }}</span>
                                  @endif
                              </div>
                          </div>
                          @else
                          <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">New Password</label>
                            <div class="col-md-6">
                                <input type="password" id="password-new" class="form-control" name="password" placeholder="New Password" autofocus>
                                <span toggle="#password-new" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                @if ($errors->has('password'))
                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                @endif
                            </div>
                          </div>
                          
                          <div class="form-group row">
                              <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm Password</label>
                              <div class="col-md-6">
                                  <input type="password" id="password-confirm" class="form-control" name="password_confirmation">
                                  <span toggle="#password-confirm" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                  @if ($errors->has('password_confirmation'))
                                      <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                                  @endif
                              </div>
                          </div>
                          @endif

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary mt-2">
                                    Change Password
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
    $(".toggle-password").click(function() {
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