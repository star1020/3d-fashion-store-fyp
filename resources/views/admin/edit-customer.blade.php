@extends('admin/master')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/luxonauta/luxa@8a98/dist/compressed/luxa.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.4.1/croppie.min.js"></script>
<style>
  .pic {
	 width: 16rem;
	 height: 16rem;
	 position: relative;
	 overflow: hidden;
	 border-radius: 50%;
  }

  .lx-btn {
    opacity: 0;
    width: 100%;
    height: 100%;
    margin: 0;
    padding: 0;
    position: absolute;
    transform: translate(-50%, -50%);
    top: 50%;
    left: 50%;
    display: none;
    align-items: center;
    justify-content: center;
    text-transform: none;
    font-size: 1rem;
    color: white !important;
    background-color: rgba(0, 0, 0, 0.8);
  }

  .pic img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
  }

   .pic:focus .lx-btn, .pic:focus-within .lx-btn, .pic:hover .lx-btn {
	 opacity: 1;
	 display: flex;
}
</style>
<div class="col-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        @if(\Session::has('success'))
            <div class="alert alert-success">
                <p>{{ \Session::get('success') }}</p>
            </div><br>
        @endif
        <h4 class="card-title">Customer</h4>
        <p class="card-description"> Edit Info </p>
        <form class="forms-sample" method="POST" action="{{ route('customers.update', $user->id) }}" >
          @csrf
          <input type="hidden" name="_method" value="PATCH"> 
          <div class="form-group">
            <label for="exampleInputName1">Name</label>
            <input type="text" name="name" class="form-control" id="exampleInputName1" placeholder="Name" value="{{old('name', $user->name)}}">
            @if ($errors->has('name'))
                <span class="text-danger" style="font-size: 14px">{{ $errors->first('name') }}</span>
            @endif
          </div>
          <div class="form-group">
            <label for="exampleInputEmail3">Email address</label>
            <input type="email" name="email" class="form-control" id="exampleInputEmail3" placeholder="Email" value="{{old('email', $user->email)}}">
            @if ($errors->has('email'))
                <span class="text-danger" style="font-size: 14px">{{ $errors->first('email') }}</span>
            @endif
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Role</label>
            <div class="col-sm-4">
              <div class="form-check">
                <label class="form-check-label">
                  <input type="radio" class="form-check-input" name="roleRadios" id="roleRadios1" value="user" checked> User </label>
              </div>
            </div>
            <div class="col-sm-5">
              <div class="form-check">
                <label class="form-check-label">
                  <input type="radio" class="form-check-input" name="roleRadios" id="roleRadios2" value="staff"> Staff </label>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label for="exampleInputAddress">Address</label>
            <input type="text" name="address" class="form-control" id="exampleInputAddress" placeholder="Address" value="{{old('address', $user->address)}}">
          </div>
          <div class="form-group">
            <label for="exampleInputPhoneNum">Phone No</label>
            <input type="text" name="phone_number" class="form-control" id="exampleInputPhoneNum" placeholder="Phone No" value="{{old('phone_number', $user->phone_number)}}">
          </div>
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Gender</label>
            <div class="col-sm-4">
              <div class="form-check">
                <label class="form-check-label">
                  <input type="radio" class="form-check-input" name="genderRadios" id="genderRadios1" value="male" {{ old('gender', $user->gender) == 'male' ? 'checked' : '' }}> Male </label>
              </div>
            </div>
            <div class="col-sm-5">
              <div class="form-check">
                <label class="form-check-label">
                  <input type="radio" class="form-check-input" name="genderRadios" id="genderRadios2" value="female" {{ old('gender', $user->gender) == 'female' ? 'checked' : '' }}> Female </label>
              </div>
            </div>
          </div>
          <input id='selectedFile' class="disp-none" style="display: none" type='file' accept="image/*">
          <input type="hidden" name="changed-profile-image" class="changed-profile-image" id="changed-profile-image" value="">
          <div class="form-group">
            <label for="">Profile picture</label>
            <div class="pic bs-md">
              <img src="{{asset('user/images/profile_image/'.$user->image)}}" id="profile-image" alt="" width="4024" height="6048" loading="lazy">
              <a id="change-avatar" class="lx-btn"><i class="fas fa-camera-retro"></i>&nbsp;&nbsp;Change your profile picture.</a>
            </div>
          </div>
          <button type="submit" class="btn btn-gradient-primary me-2">Submit</button>
          <a href="{{route('customers.index')}}" class="btn btn-light">Cancel</a>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="imageModalContainer" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
      <div class="modal-content modal-content1 modal-content1">
        <div class="modal-header">
          <h5 class="modal-title" id="imageModal">Crop Image</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
          </button>
        </div>
        <div class="modal-body modal-body1">
          <div id='crop-image-container'>
  
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary save-modal">Save</button>
        </div>
      </div>
    </div>
  </div>

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

  <script>
    $(document).on('click', '#change-avatar', function () {
        document.getElementById('selectedFile').click();
    });

    $('#selectedFile').change(function () {
        // if (this.files[0] == undefined)
        // return;

        // Check if any file is selected.
        if (!(this.files.length > 0)) {
            // document.querySelector('#profile-image').setAttribute("src", "https://i.ibb.co/0Y9JcSV/upload-image.png");
            return false;
        }

        var fileInput = document.getElementById('selectedFile');
        var filePath = fileInput.value;
        // Allowing file type
        var allowedExtensions = /(\.PNG|\.png|\.JPEG|\.jpeg|\.jpg|\.JPG)$/i;

        if (!allowedExtensions.exec(filePath)) {
            // document.querySelector('#profile-image').setAttribute("src", "https://i.ibb.co/0Y9JcSV/upload-image.png");

            swal({
                title: "Sorry!",
                text: "Only image having extension jpg/png/jpeg is allowed",
                icon: "warning",
                button: "OK",
            });
            fileInput.value = '';
            return false;
        }

        // check file size
        const fileSize = this.files[0].size / 1024 / 1024; // in MiB
        if (fileSize > 1) {
            // document.querySelector('#profile-image').setAttribute("src", "https://i.ibb.co/0Y9JcSV/upload-image.png");

            swal({
                title: "Sorry!",
                text: "Image size exceeds 1MB",
                icon: "warning",
                button: "OK",
            });
            $(this).val(''); //for clearing with Jquery
            return false;

        }

        $('#imageModalContainer').modal('show');
        let reader = new FileReader();
        reader.addEventListener("load", function () {
        window.src = reader.result;
        $('#selectedFile').val('');
        }, false);
        if (this.files[0]) {
        reader.readAsDataURL(this.files[0]);
        }
    });

    let croppi;
    $('#imageModalContainer').on('shown.bs.modal', function () {
    let width = document.getElementById('crop-image-container').offsetWidth - 20;
    $('#crop-image-container').height((width - 80) + 'px');
        croppi = $('#crop-image-container').croppie({
        viewport: {
            width: 200,
            height: 200,
            type: 'circle'
        },
        });
    $('.modal-body1').height(document.getElementById('crop-image-container').offsetHeight + 50 + 'px');
    croppi.croppie('bind', {
        url: window.src,
    }).then(function () {
        croppi.croppie('setZoom', 0.6);
    });
    });
    $('#imageModalContainer').on('hidden.bs.modal', function () {
    croppi.croppie('destroy');
    });

    $(document).on('click', '.save-modal', function (ev) {
    croppi.croppie('result', {
        type: 'base64',
        format: 'jpeg',
        size: 'circle'
    }).then(function (resp) {
        document.querySelector('#changed-profile-image').setAttribute("value", resp);
        document.querySelector('#profile-image').setAttribute("src", resp);
        $('.modal').modal('hide');
    });
    });
  </script>
@endsection