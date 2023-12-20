<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Socialite;
use Session;
use Mail;
use Illuminate\Support\Facades\DB;
use App\Mail\SendMail;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Repositories\Interfaces\MembershipRepositoryInterface;

class UserController extends Controller
{
    private $userRepository;
    private $membershipRepository;

    public function __construct(UserRepositoryInterface $userRepository, MembershipRepositoryInterface $membershipRepository)
    {
        $this->userRepository = $userRepository;
        $this->membershipRepository = $membershipRepository;
    }

    public function index(){
        $users = $this->userRepository->allUser();
        return view('admin/all-customer', compact('users'));
    }

    public function indexStaff()
    {
        $staffs = $this->userRepository->allStaff();
        return view('admin/all-staff', compact('staffs'));
    }

    public function create(){
        return view('admin/add-staff');
    }

    public function edit($id){
        $user = $this->userRepository->findUser($id);
        if ($user->role == 'user'){
            return view('admin/edit-customer', compact('user'));
        }
        return view('admin/edit-staff', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|regex:/^[a-zA-Z\s]*$/',
            'email' => 'required|email'
        ]);

        $changed_profile_image = $request->post('changed-profile-image');
        $image_name = '';
        if($changed_profile_image != ""){
            list($type, $changed_profile_image) = explode(';',$changed_profile_image);
            list(, $changed_profile_image) = explode(',',$changed_profile_image);

            $image = base64_decode($changed_profile_image);
            $image_name = uniqid(rand(), false) . '.png';
            file_put_contents('../public/user/images/profile_image/'.$image_name, $image);
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'gender' => $request->genderRadios,
            'address' => $request->address,
            'image' => $image_name,
            'role'  =>  $request->roleRadios,
            'phone_number' => $request->phone_number,
        ];
        $this->userRepository->updateUser($data, $id);

        if($request->roleRadios == 'user'){
            return redirect('admin/customers')->with('success', 'Information has been updated');
        }
        return redirect()->route('staffs.all')->with('success', 'Information has been updated');
    }

    public function edit_profile(){
        $user_id = auth()->user()->id;
        $data = $this->userRepository->findUser($user_id);
        if($data->membership_level){
            $membership = $this->membershipRepository->findMembershipByLevel($data->membership_level);
            $data->setAttribute('membership_discount', $membership->discount);
        }
        return view('user/profile', compact('data'));
    }

    public function submitEditProfileForm(Request $request, $id){
        $changed_profile_image = $request->post('changed-profile-image');
        $image_name = '';
        if($changed_profile_image != ""){
            list($type, $changed_profile_image) = explode(';',$changed_profile_image);
            list(, $changed_profile_image) = explode(',',$changed_profile_image);

            $image = base64_decode($changed_profile_image);
            $image_name = uniqid(rand(), false) . '.png';
            file_put_contents('../public/user/images/profile_image/'.$image_name, $image);
        }
        $data = [
            'name' => $request->post('user-name'),
            'email' => $request->post('email'),
            'gender' => $request->post('gender'),
            'address' => $request->post('address'),
            'phone_number' => $request->post('phone-number'),
            'image' => $image_name,
            'role' => auth()->user()->role,
        ];
        $this->userRepository->updateUser($data, $id);

        return redirect('user/edit-profile')->with('success', 'Successfully changed!');
    }

    public function destroy(string $id)
    {
        $this->userRepository->destroyUser($id);
        $user = $this->userRepository->findUser($id);
        if ($user->role == 'user'){
            return redirect('admin/customers')->with('success', 'Information has been deleted');
        }
        return redirect()->route('staffs.all')->with('success', 'Information has been deleted');
    }

    public function login(Request $req){
        $credentials = $req->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication successful
            $this->userRepository->updateUserLoginTime(auth()->user()->id);
            return response()->json(['success' => 'Login successful.']);
        } else {
            // Authentication failed
            return response()->json(['error' => 'Email or password is not matched'], 401);
        }

    }

    public function register(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required|string|regex:/^[a-zA-Z\s]*$/',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|regex:/^(?=.*[A-Za-z])(?=.*[0-9])(?=.*[!@#$%^&*()_+\-=\[\]{}:"<>?;\',.\/\\`~])[A-Za-z0-9!@#$%^&*()_+\-=\[\]{}:"<>?;\',.\/\\`~]{8,16}$/|confirmed',
            'password_confirmation' => 'required'
        ], [
            'password.regex' => 'The password must be 8-16 characters long and contain at least one letter, one number, and one special character.'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = [
            'name' => $req->name,
            'email' => $req->email,
            'password' => Hash::make($req->password),
            'image' => 'unknown_profile.png'
        ];
        $this->userRepository->storeUser($data);

        return response()->json(['register_success' => 'Account registration successful! Please log in to your account']);
    }

    public function forgetPassword(Request $request){
        $request->validate([
            'email' => 'required|email|exists:users|unique:password_reset_tokens',
        ]);

        $token = Str::random(64);

        DB::table('password_reset_tokens')->insert([
            'email' => $request->email, 
            'token' => $token, 
            'created_at' => Carbon::now()

          ]);

        $testMailData = [
            'email' => base64_encode($request->email), 
            'token' => $token
        ];

        Mail::to($request->email)->send(new SendMail($testMailData));

        return back()->with('message', 'We have e-mailed your password reset link!');
    }

    public function verify_reset_password(){
        $email = base64_decode(request()->route('email'));
        $token = request()->route('token');

        $verify = DB::table('password_reset_tokens')
                           ->where([
                             'email' => $email, 
                             'token' => $token
                           ])
                           ->first();

        if(!$verify){
            return redirect('/forget_password')->with('error', 'This link can only reset password once! Please resend again your reset password form');
        }
        return view('user/resetPasswordForm', compact('email', 'token'));
    }

   public function submitResetPasswordForm(Request $request)
   {
       $request->validate([
           'email' => 'required|email|exists:users',
           'password' => 'required|string|regex:/^(?=.*[A-Za-z])(?=.*[0-9])(?=.*[!@#$%^&*()_+\-=\[\]{}:"<>?;\',.\/\\`~])[A-Za-z0-9!@#$%^&*()_+\-=\[\]{}:"<>?;\',.\/\\`~]{8,16}$/|confirmed',
           'password_confirmation' => 'required'

       ], [
            'password.regex' => 'The password must be 8-16 characters long and contain at least one letter, one number, and one special character.'
        ]);

       $updatePassword = DB::table('password_reset_tokens')
                           ->where([
                             'email' => $request->email, 
                             'token' => $request->token
                           ])
                           ->first();

       if(!$updatePassword){
           return back()->withInput()->with('error', 'Invalid token!');
       }

    //    $user = User::where('email', $request->email)
    //                ->update(['password' => Hash::make($request->password)]);
                   
       $this->userRepository->password_reset($request);
       
       DB::table('password_reset_tokens')->where(['email'=> $request->email])->delete();

       return redirect('/')->with('success', 'Your password has been changed! Please login again');

   }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')
        ->with(['prompt' => 'select_account'])
        ->redirect();
    }
    
    public function handleGoogleCallback(Request $req)
    {
        $user = Socialite::driver('google')->user();

        // Check if the user already exists in the database
        $existingUser = $this->userRepository->findUserByEmail($user->getEmail());

        if ($existingUser) {
            // If the user exists, log them in
            Auth::login($existingUser);
            $this->userRepository->updateUserLoginTime(auth()->user()->id);
        } else {
            // If the user does not exist, create a new user account
            $data = [
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'password' => bcrypt(Str::random(16)),
                'image' => 'unknown_profile.png'
            ];
            $newUser = $this->userRepository->storeUser($data);
            // Log in the new user
            Auth::login($newUser);
            $this->userRepository->updateUserLoginTime(auth()->user()->id);
        }

        // Redirect to the home page
        return redirect('/');
    }

    function sendOTP($phoneNumber) {
        $basic  = new \Vonage\Client\Credentials\Basic("08be4743", "KV9sx0mPOEbygjZZ");
        $client = new \Vonage\Client($basic);

        $otp = rand(100000, 999999);
        Session::put('otp', $otp);

        $response = $client->sms()->send(
            new \Vonage\SMS\Message\SMS("6".$phoneNumber, "Respectism", 'Hello! Your Respectism OTP code is '.$otp.'.')
        );
        
        $message = $response->current();
        
        if ($message->getStatus() == 0) {
            $response = array('message' => 'The message was sent successfully');
            
        } else {
            $response = array('message' => $message->getStatus());
        }
        return json_encode($response);
    }
    
    function validateOTP($otp) {
        $sessionOTP = Session::get('otp');
        if ($sessionOTP == $otp) {
            // OTP is valid
            $response = array('message' => 'true');
            Session::forget('otp');
        } else {
            // OTP is invalid
            $response = array('message' => 'false');
        }
        return json_encode($response);
    }

    public function changePassword(){
        $user_id = auth()->user()->id;
        $data = $this->userRepository->findUser($user_id);
        return view('user/changePassword', compact('data'));
    }

    public function edit_password(Request $req, $id){
        $user = $this->userRepository->findUser($id);
        if(!$req->has('password')){
            if(!Hash::check($req->current_password, $user->password))
            {
                return back()->with('error', 'Invalid current password');
            }
            else
            {
                return back()->with('appendFields', true);
            }
        } else {
            $req->validate([
                'password' => 'required|string|regex:/^(?=.*[A-Za-z])(?=.*[0-9])(?=.*[!@#$%^&*()_+\-=\[\]{}:"<>?;\',.\/\\`~])[A-Za-z0-9!@#$%^&*()_+\-=\[\]{}:"<>?;\',.\/\\`~]{8,16}$/|confirmed',
            ], [
                'password.regex' => 'The password must be 8-16 characters long and contain at least one letter, one number, and one special character.'
            ]);
            $this->userRepository->edit_password($req, $id);
            return back()->with('success', 'Password changed successfully');
        }
    }

    function userDemographic_report()
    {
        $userDemographic = $this->userRepository->userDemographic_report();

        $labelArray = [];
        foreach ($userDemographic as $data) {
            $labelArray[] = $data->gender === null ? 'not filled' : $data->gender;
        }
        $labelArray = str_replace('"', "'", json_encode($labelArray));
        
        $dataArray = [];
        foreach ($userDemographic as $data) {
            $dataArray[] = $data->count;
        }
        
        return view('admin/report-userDemographic', compact('labelArray', 'dataArray'));
    }

}
