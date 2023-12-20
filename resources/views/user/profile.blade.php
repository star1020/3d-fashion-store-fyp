@extends('user/master')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.4.1/croppie.min.js"></script>
<style>
  input{
    width: 100%;
  }

  .lx-row {
    width: 100%;
    display: flex;
    flex-flow: wrap row;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
}

.lx-column {
    display: block;
    flex-basis: 0;
    flex-grow: 1;
    flex-shrink: 1;
}

.bs-md {
    box-shadow: 0 0.5rem 1rem rgb(0 0 0 / 15%);
}

.align-stretch {
    align-items: stretch !important;
}

 main {
	 min-height: 100vh;
	 padding: 2rem 0;
}
 main section {
	 width: 100%;
}
 main section .lx-column.column-user-pic {
	 display: flex;
	 align-items: flex-end;
	 justify-content: flex-start;
   flex-direction: column;
}
 main section .profile-pic {
	 width: auto;
	 max-width: 20rem;
	 min-width: 20rem;
	 margin: 3rem 2rem;
	 padding: 2rem;
	 display: flex;
	 flex-flow: wrap column;
	 align-items: center;
	 justify-content: center;
	 border-radius: 0.25rem;
	 background-color: white;
}
 main section .profile-pic .pic-label {
	 width: 100%;
	 margin: 0 0 1rem 0;
	 text-align: center;
	 font-size: 1.4rem;
	 font-weight: 700;
}
 main section .profile-pic .pic {
	 width: 16rem;
	 height: 16rem;
	 position: relative;
	 overflow: hidden;
	 border-radius: 50%;
}
 main section .profile-pic .pic .lx-btn {
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
	 color: white;
	 background-color: rgba(0, 0, 0, 0.8);
}
 main section .profile-pic .pic img {
	 width: 100%;
	 height: 100%;
	 object-fit: cover;
	 object-position: center;
}
 main section .profile-pic .pic:focus .lx-btn, main section .profile-pic .pic:focus-within .lx-btn, main section .profile-pic .pic:hover .lx-btn {
	 opacity: 1;
	 display: flex;
}
 main section .profile-pic .pic-info {
	 width: 100%;
	 margin: 2rem 0 0 0;
	 font-size: 0.9rem;
	 text-align: center;
}
 main section form {
	 width: auto;
	 min-width: 15rem;
	 max-width: 25rem;
	 margin: 3rem 0 0 0;
}
 main section form .fieldset {
	 width: 100%;
	 margin: 2rem 0;
	 position: relative;
	 display: flex;
	 flex-wrap: wrap;
	 align-items: center;
	 justify-content: flex-start;
}
 main section form .fieldset label {
	 width: 100%;
	 margin: 0 0 1rem 0;
	 font-size: 1.2rem;
	 font-weight: 700;
}
 main section form .fieldset .input-wrapper {
	 width: 100%;
	 display: flex;
	 flex-flow: nowrap;
	 align-items: stretch;
	 justify-content: center;
}
 main section form .fieldset .input-wrapper .icon {
	 width: fit-content;
	 margin: 0;
	 padding: 0.375rem 0.75rem;
	 display: flex;
	 align-items: center;
	 border-top-left-radius: 0.25em;
	 border-bottom-left-radius: 0.25em;
	 border-top-right-radius: 0;
	 border-bottom-right-radius: 0;
	 border: 0.0625rem solid #ced4da;
	 font-size: 1rem;
	 font-weight: 400;
	 line-height: 1.5;
	 color: #495057;
	 text-align: center;
	 background-color: #e9ecef;
}
 main section form .fieldset .input-wrapper input, main section form .fieldset .input-wrapper select {
	 flex-grow: 1;
	 min-height: 3rem;
	 padding: 0.375rem 0.75rem;
	 display: block;
	 border-top-left-radius: 0;
	 border-bottom-left-radius: 0;
	 border-top-right-radius: 0.25em;
	 border-bottom-right-radius: 0.25em;
	 border: 0.0625rem solid #ced4da;
	 border-left: 0;
	 font-size: 1rem;
	 font-weight: 400;
	 line-height: 1.5;
	 color: #495057;
}
 main section form .fieldset .input-wrapper:focus .icon, main section form .fieldset .input-wrapper:focus-within .icon, main section form .fieldset .input-wrapper:hover .icon {
	 color: #6c7ae0;
}
 main section form .fieldset:first-child {
	 margin-top: 0;
}
 main section form .fieldset:last-child {
	 margin-bottom: 0;
}
 main section form .actions {
	 width: 100%;
	 display: flex;
	 align-items: center;
	 justify-content: space-between;
}
 main section form .actions .lx-btn {
	 padding: 0.5rem 1rem;
	 display: flex;
	 align-items: center;
	 justify-content: center;
	 font-weight: 700;
	 color: white;
   cursor: pointer;
}

main section form .actions .lx-btn#save {
	 color: white !important;
}

main section form .actions .lx-btn#cancel, main section form .actions .lx-btn.cancel {
	 background-color: #f55;
}

main section form .actions .lx-btn#save, main section form .actions .lx-btn.save {
	 background-color: #6c7ae0;
}

main section form .actions .lx-btn#cancel:hover,
main section form .actions .lx-btn.cancel:hover {
    background-color: #e04444;
}

main section form .actions .lx-btn#save:hover,
main section form .actions .lx-btn.save:hover {
    background-color: #5a68d3;
}

 @media screen and (max-width: 64rem) {
	 main section .profile-pic {
		 max-width: 100%;
		 margin: 0;
	}
}
 @media screen and (max-width: 56.25rem) {
	 main section form {
		 max-width: 100%;
		 margin: 0;
	}
}

.alert{
  left: 10%;
  width: 80%;
  margin-top: 1rem;
}

.card {
    width: 400px;
    border: none;
    height: 300px;
    /* box-shadow: 0px 5px 20px 0px #d2dae3; */
    z-index: 1;
    display: flex;
    justify-content: center;
    align-items: center
}

.card h6 {
    color: red;
    font-size: 20px
}

.inputs input {
    width: 40px;
    height: 40px
}

input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    margin: 0
}

.form-control:focus {
    box-shadow: none;
    border: 2px solid red
}

.validate {
    /* border-radius: 20px; */
    height: 40px;
    background-color: red;
    border: 1px solid red;
    width: 140px
}

.footer_part{
  display: none;
}

.pic.bs-md{
  cursor: pointer;
}

.membership-badge {
  display: inline-block;
  padding: 5px 10px;
  border-radius: 20px;
  font-weight: bold;
  font-size: 14px;
  text-transform: uppercase;
}

.gold {
  background-color: #ffc107;
  color: #fff;
}

.silver {
  background-color: #c4c4c4;
  color: #fff;
}

.bronze {
  background-color: #cd7f32;
  color: #fff;
}

.platinum {
  background-color: #e5e4e2;
  color: #0c0c0c;
  border: 2px solid #c0c0c0;
}

.alert-container {
  display: flex;
  justify-content: center;
  align-items: center;
}

.alert {
  width: 80%;
}

.claim-reward-btn {
    display: inline-block;
    padding: 10px 20px;
    margin-top: 15px;
    background-color: #4CAF50;
    color: white;
    text-align: center;
    text-decoration: none;
    font-size: 16px;
    border-radius: 5px;
    transition: background-color 0.3s, color 0.3s;
}

.claim-reward-btn:hover {
    background-color: #45a049; /* Darker green background */
    color: #ddd; /* Lighter text */
    text-decoration: none; /* No underline on hover */
}

</style>

<main class="has-dflex-center">
    <section>
      <div class="lx-container-70">
          @if(\Session::has('success'))
            <div class="alert-container">
                <div class="alert alert-success">
                  <p>{{ \Session::get('success') }}</p>
                </div>
            </div>
          @endif
        <div class="lx-row align-stretch">
          <div class="lx-column column-user-pic">
            <div class="profile-pic bs-md">
              <h1 class="pic-label">Profile picture</h1>
              <div class="pic bs-md">
                <img src="{{asset('user/images/profile_image/'.$data->image)}}" id="profile-image" alt="" width="4024" height="6048" loading="lazy">
                <a id="change-avatar" class="lx-btn"><i class="fa fa-camera-retro"></i>&nbsp;&nbsp;Change your profile picture.</a>
              </div>
              <div class="pic-info">
                <p><i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;This photo will appear on the platform, in your contributions or where it is mentioned.</p>
              </div>
            </div>
            @if($data->membership_level)
              <script>
                document.querySelector('.lx-column.column-user-pic').style.justifyContent = 'flex-end';
              </script>
              <div class="profile-pic bs-md">
                  <h4 class="pic-label">Membership</h4>
                  <p>Tier: <span class="membership-badge {{$data->membership_level}}" data-toggle="tooltip" data-placement="right" title="Membership Level">{{$data->membership_level}}</span></p>
                  <p>Discount: <span class="membership-badge" data-toggle="tooltip" data-placement="right" title="You get the discount when you make payment">{{$data->membership_discount}}%</span></p>
                  <p>Points: <span class="membership-badge" data-toggle="tooltip" data-placement="right" title="You can use this points to claim reward">{{$data->reward_point}}</span></p>
                  <a href="{{route('reward')}}" id="claim-reward" class="lx-btn claim-reward-btn">
                    <i class="fa fa-gift"></i>&nbsp;&nbsp;Claim Reward
                  </a>
              </div>
            @endif
          </div>
          <div class="lx-column">
            <form class="edit-profile-form" id="edit-profile-form" method="POST" action="{{ route('submitEditProfileForm', ['id' => $data->id]) }}" enctype="multipart/form-data">
                @csrf
                <input id='selectedFile' class="disp-none" style="display: none" type='file' accept="image/*">
                <input type="hidden" name="changed-profile-image" class="changed-profile-image" id="changed-profile-image" value="">
              <div class="fieldset">
                <label for="user-name">Name</label>
                <div class="input-wrapper">
                  <span class="icon"><i class="fa fa-user"></i></span>
                  <input type="text" id="user-name" name="user-name" value="{{$data->name}}" autocomplete="username" required>
                </div>
                <div id="user-name-helper" class="helper">
                  <p>Your name can appear on the platform, in your contributions or where it is mentioned.</p>
                </div>
              </div>
              <div class="fieldset">
                <label for="gender">Gender</label>
                <div class="input-wrapper">
                  <span class="icon"><i class="fa fa-transgender-alt"></i></span>
                  <label for="male" style="margin-left: 15px">Male</label>
                  <input type="radio" name="gender" id="gender" value="male" @if($data->gender == 'male') checked @endif>
                  <label for="female">Female</label>
                  <input type="radio" name="gender" id="gender2" value="female" @if($data->gender == 'female') checked @endif>
                </div>
                <div id="gender-helper" class="helper"></div>
              </div>
              <div class="fieldset">
                <label for="phone-number">Phone Number</label>
                <div class="input-wrapper">
                  <span class="icon"><i class="fa fa-phone"></i></span>
                  <input type="text" id="phone-number" name="phone-number" value="{{$data->phone_number}}" required>
                  <button class="btn btn-outline-secondary" type="button" id="button-addon2" disabled>Send OTP</button>
                </div>
                <div id="phone-number-helper" class="helper"></div>
              </div>
              <div class="fieldset">
                <label for="email">E-mail</label>
                <div class="input-wrapper">
                  <span class="icon"><i class="fa fa-envelope"></i></span>
                  <input type="email" id="email" name="email" value="{{$data->email}}" autocomplete="username">
                </div>
                <div id="email-helper" class="helper"></div>
              </div>
              <div class="fieldset">
                <label for="address">Address</label>
                <div class="input-wrapper">
                  <span class="icon"><i class="fa fa-map-marker"></i></span>
                  <input type="text" id="address" name="address" value="{{$data->address}}" autocomplete="username">
                </div>
                <div id="address-helper" class="helper"></div>
              </div>
              <div class="actions">
                <a href="/" id="cancel" class="lx-btn"><i class="fa fa-ban"></i>&nbsp;&nbsp;Cancel</a>
                <a id="save" class="lx-btn"><i class="fa fa-save"></i>&nbsp;&nbsp;Save</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>
  </main>

  <div class="modal fade" id="imageModalContainer" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
      <div class="modal-content modal-content1 modal-content1" style="top: 60px">
        <div class="modal-header">
          <h5 class="modal-title" id="imageModal">Crop Image</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body modal-body1">
          <div id='crop-image-container'>
  
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary save-modal">Save</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="verifyOTPModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Verify OTP</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="container d-flex justify-content-center align-items-center">
            <div class="position-relative">
                <div class="card p-2 text-center">
                    <h6>Please enter the one time password <br> to verify your account</h6>
                    <div> <span>A code has been sent to</span> <small>*******9897</small> </div>
                    <div id="otp" class="inputs d-flex flex-row justify-content-center mt-2"> <input class="m-2 text-center form-control rounded" type="text" id="first" maxlength="1" /> <input class="m-2 text-center form-control rounded" type="text" id="second" maxlength="1" /> <input class="m-2 text-center form-control rounded" type="text" id="third" maxlength="1" /> <input class="m-2 text-center form-control rounded" type="text" id="fourth" maxlength="1" /> <input class="m-2 text-center form-control rounded" type="text" id="fifth" maxlength="1" /> <input class="m-2 text-center form-control rounded" type="text" id="sixth" maxlength="1" /> </div>
                </div>
            </div>
        </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-danger px-4 validate" id="validateOTPBtn">Validate</button>
        </div>
      </div>
    </div>

  <script>
    $(function () {
      $('[data-toggle="tooltip"]').tooltip()
    })
  </script>

  <script>
    // Get the input element
    var inputElement = document.getElementById('phone-number');
    var inputValue = $("input[name='phone-number']").val();
    var validateOTP = false;
    // Set up an event listener to monitor changes to the input value
    inputElement.addEventListener('input', function() {
      // Get the current value of the input
      inputValue = inputElement.value;
      console.log(inputValue);

      if(inputValue != "{{$data->phone_number}}"){
        document.querySelector('#button-addon2').disabled = false;
      } else {
        document.querySelector('#button-addon2').disabled = true;
      }
    });
  </script>

  <script>
    const button = document.querySelector('#button-addon2');
    let sec = 60;
    let countdown = null;

    const updateButton = () => {
      button.innerHTML = `wait ${sec}s`;
      
      if (sec === 0) {
        clearInterval(countdown);
        sec = 60;
        button.innerHTML = 'Send OTP';
        button.disabled = false;
        return;
      }

      sec--;
    }

    button.onclick = () => {
      button.disabled = true;
      $('#verifyOTPModal').modal('show');
      updateButton();
      countdown = setInterval(function() {
        updateButton();
      }, 1000);
      fetch('/user/sendOTP/'+inputValue)
        .then(response => response.json())
        .then(data => {
          $("input[name='phone-number']").val("");
        });
    }
  </script>

  <script>
    const validateOTPBtn = document.querySelector('#validateOTPBtn');
    validateOTPBtn.onclick = () => {
      var input1Value = document.getElementById("first").value;
      var input2Value = document.getElementById("second").value;
      var input3Value = document.getElementById("third").value;
      var input4Value = document.getElementById("fourth").value;
      var input5Value = document.getElementById("fifth").value;
      var input6Value = document.getElementById("sixth").value;
      var concatenatedString = input1Value + input2Value + input3Value + input4Value + input5Value + input6Value;

      fetch('/user/validateOTP/'+concatenatedString)
        .then(response => response.json())
        .then(data => {
            if(data.message == 'true'){
              $('#verifyOTPModal').modal('hide');
              swal({
                  title: "Success!",
                  text: "OTP code matched!",
                  icon: "success",
                  button: "OK",
              });
              $("input[name='phone-number']").val(inputValue);
              validateOTP = true;
            } else {
              swal({
                  title: "Invalid OTP code!",
                  text: "Please enter again",
                  icon: "error",
                  button: "OK",
              });

            }
        });
    }
  </script>

  <script>
    document.addEventListener("DOMContentLoaded", function(event) {

    function OTPInput() {
    const inputs = document.querySelectorAll('#otp > *[id]');
    for (let i = 0; i < inputs.length; i++) { inputs[i].addEventListener('keydown', function(event) { if (event.key==="Backspace" ) { inputs[i].value='' ; if (i !==0) inputs[i - 1].focus(); } else { if (i===inputs.length - 1 && inputs[i].value !=='' ) { return true; } else if (event.keyCode> 47 && event.keyCode < 58) { inputs[i].value=event.key; if (i !==inputs.length - 1) inputs[i + 1].focus(); event.preventDefault(); } else if (event.keyCode> 64 && event.keyCode < 91) { inputs[i].value=String.fromCharCode(event.keyCode); if (i !==inputs.length - 1) inputs[i + 1].focus(); event.preventDefault(); } } }); } } OTPInput(); });
  </script>

  <script>
    $("#save").click(function(){
      if(inputValue != "{{$data->phone_number}}" && validateOTP == false){
        swal({
            title: "Please send OTP code",
            text: "The phone number will not be saved if it has not been verified, do you want to continue?",
            icon: "warning",
            buttons: true,
        }).then((willDelete) => {
            if (willDelete) {
              $("input[name='phone-number']").val("{{$data->phone_number}}");
              $("#edit-profile-form").submit();
            } 
          });
      } else {
        $("#edit-profile-form").submit();
      }
      
    });
  </script>

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