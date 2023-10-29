<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Role;
use App\Models\ResetCodePassword;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Firebase\Auth\Token\Exception\InvalidToken;
use \App\Http\Controllers\Api\BaseController;
use App\Exceptions\ValidationExceptionApi;
use App\Http\Resources\Api\ProfileResource;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

use App\Mail\ResetPasswordOtp;
use Illuminate\Support\Facades\Mail;


class AuthController extends BaseController
{

    public function email_check(Request $request)
    {
        try {
            $user = User::where('email', $request->email)->first();
            if ($user) {
                return $this->response(0, ["messages" => "Email is invalid or already taken", "errors" => array("")]);
            } else {
                return $this->response(1, ["messages" => "", "errors" => array()]);
            }
        } catch (\Throwable $th) {
            return $this->response(0, ["messages" => array(), 'errors' => [$th->getMessage()]]);
        }
    }
    /**
     * Create User
     * @param Request $request
     * @return User 
     */
    public function register(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email',
                    // 'role' => 'required|integer',
                    'password' => 'required',
                ]
            );
            if ($validator->fails()) {
                $x = new ValidationExceptionApi($validator);
                $msg = $x->getMessages();
                return $this->response(0, ["messages" => array("Error"), 'errors' => $msg]);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'age' => $request->age,
                'gender' => $request->gender,
                'country_id' => $request->country_id,
                'city' => $request->city,
                'religion' => $request->religion,
                'password' => Hash::make($request->password),
                'role_id' => $request->role ?? 2,
                // 'refer_id' => $refer_id,

            ]);

            return $this->response(1, ["messages" => ['User Created Successfully.'], 'errors' => [], "tocken" => [$user->createToken("API")->plainTextToken]]);
        } catch (\Throwable $th) {
            return $this->response(0, ["messages" => array(), 'errors' => [$th->getMessage()]]);
        }
    }



    public function login(Request $request)
    {
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required'
                ]
            );
            if ($validateUser->fails()) {
                $x = new ValidationExceptionApi($validateUser);
                $msg = $x->getMessages();
                return $this->response(0, ["messages" => array("Error"), 'errors' => $msg]);
            }


            if (!Auth::attempt($request->only(['email', 'password']))) {
                return $this->response(0, ["messages" => array(), 'errors' => ['Email & Password does not match with our record.']]);
            }

            $user = User::where('email', $request->email)->first();
            $tocken = $user->createToken("API")->plainTextToken;
            return $this->response(1, ["messages" => ['You Logged In Successfully'], 'errors' => [], "tocken" => $tocken]);
        } catch (\Throwable $th) {
            return $this->response(0, ["messages" => array(), 'errors' => [$th->getMessage()]]);
        }
    }

    public function logout()
    {
        try {
            auth()->user()->tokens()->delete();
            return $this->response(1, ["messages" => ['Logout Successfully'], 'errors' => []]);
        } catch (\Throwable $th) {
            return $this->response(0, ["messages" => array(), 'errors' => [$th->getMessage()]]);
        }
    }


    public function profile_update(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                // 'name' => 'required',
                'image' => 'mimes:jpg,jpeg,png',
                //'address' => 'required',
            ]
        );
        if ($validator->fails()) {
            $x = new ValidationExceptionApi($validator);
            $msg = $x->getMessages();
            return $this->response(0, ["messages" => array("Error"), 'errors' => $msg]);
        }

        if (Auth::check() == true) {
            $data = [];
            $user = Auth::user();
            $user_profile = User::find($user->id);
            $user_profile->name = $request->name;
            $user_profile->address = $request->address;
            // $user_profile->pincode = $request->pin_code;
            // $user_profile->phone = $request->phone;
            $img_path_name = null;
            if ($request->image != "") {
                $isExists = File::exists($user->image);
                if ($isExists) {
                    unlink($user->image);
                }
                $filename = time() . '-' . rand(1000, 9999) . '.' . $request->image->getClientOriginalExtension();
                $upload_location = 'uploads/avatar/';
                $img_path_name = $upload_location . $filename;
                $request->image->move(public_path($upload_location), $filename);
                $user_profile->image = $img_path_name;
            }
            if ($user_profile->save()) {
                return $this->response(1, ["messages" => array("Profile updated successfully"), 'result' =>  []]);
            }
        } else {
            return $this->response(0, ["messages" => array("Error"), "errors" => array("Something went wrong try again latter.")]);
        }
    }

    public function profile_get()
    {
        if (Auth::check() == true) {
            $user =  new ProfileResource(Auth::user());
            return $this->response(1, ["messages" => array(""), 'result' =>   $user]);
        } else {
            return $this->response(0, ["messages" => array("Error"), "errors" => array("Something went wrong try again latter.")]);
        }
    }

    public function sendResetOtpEmail(Request $request)
    {

        try {


            $validator = Validator::make(
                $request->all(),
                [

                    'email' => 'required|email|exists:users',

                ]
            );
            if ($validator->fails()) {
                $x = new ValidationExceptionApi($validator);
                $msg = $x->getMessages();
                return $this->response(0, ["messages" => array(""), 'errors' => $msg]);
            }


            // Delete all old code that user send before.
            ResetCodePassword::where('email', $request->email)->delete();

            // Generate random code
            // $data['email'] = $request->email;
            // $data['code'] = mt_rand(100000, 999999);
            $data['code'] = "111111";

            // Create a new code
            $ResetCodePassword =  new  ResetCodePassword;
            $ResetCodePassword->email = $request->email;
            $ResetCodePassword->code = $data['code'];
            $ResetCodePassword->save();


            // Send email to user
            // Mail::to($request->email)->send(new ResetPasswordOtp($codeData->code));

            return $this->response(1, ['messages' =>   ["Mail send successfulty"], "errors" => ""]);
        } catch (\Throwable $th) {
            return $this->response(0, ["messages" => array(), 'errors' => [$th->getMessage()]]);
        }
    }

    public function otp_check(Request $request)
    {


        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'otp' => 'required|string|exists:reset_code_passwords,code',

            ]
        );

        if ($validator->fails()) {
            $x = new ValidationExceptionApi($validator);
            $msg = $x->getMessages();
            return $this->response(0, ["messages" => array(""), 'errors' => $msg]);
        }
        // find the code
        $passwordReset = ResetCodePassword::where('code', $request->otp)->Where('email',  $request->email)->first();
        if ($passwordReset) {
            return $this->response(1, ['messages' =>   ["Otp is valid"], "errors" => ""]);
        } else {
            return $this->response(0, ["messages" => array(), 'errors' => ["Otp is not match"]]);
        }
    }
    public function ResetPassword(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'otp' => 'required|string|exists:reset_code_passwords,code',
                "password" => 'required',
            ]
        );

        if ($validator->fails()) {
            $x = new ValidationExceptionApi($validator);
            $msg = $x->getMessages();
            return $this->response(0, ["messages" => array(""), 'errors' => $msg]);
        }

        $passwordReset = ResetCodePassword::where('code', $request->otp)->Where('email',  $request->email)->first();
        if ($passwordReset) {

            $user = User::where('email', $request->email)->first();
            $user->password = Hash::make($request->password);
            $user->save();
            if ($user->save()) {
                $passwordReset->delete();
                return $this->response(1, ['messages' =>   ["password reset successfully"], "errors" => ""]);
            } else {
                return $this->response(0, ["messages" => array(), 'errors' => ["someting went wrong"]]);
            }

            // return $this->response(1, ['messages' =>   ["Otp is valid"], "errors" => ""]);
        } else {
            return $this->response(0, ["messages" => array(), 'errors' => ["Otp is not match"]]);
        }



        // check if it does not expired: the time is one hour
        // if ($passwordReset->created_at > now()->addHour()) {
        //     $passwordReset->delete();
        //     return $this->response(0, ["messages" => array(), 'errors' => ["Otp is expire"]]);
        // }

        return $this->response(1, ['messages' =>   ["Otp is valid"], "errors" => ""]);
    }
    // // Credit the referrer's account with a referral bonus
    // public function creditReferrer(User $referrer, $amount)
    // {
    //     WalletTransaction::create([
    //         'user_id' => $referrer->id,
    //         'amount' => $amount,
    //         'type' => 'credit',
    //         'description' => 'Referral bonus',
    //     ]);
    // }

    // // Debit a user's account when using a referral bonus
    // public function debitUser(User $user, $amount)
    // {
    //     WalletTransaction::create([
    //         'user_id' => $user->id,
    //         'amount' => $amount,
    //         'type' => 'debit',
    //         'description' => 'Referral bonus used',
    //     ]);
    // }
}
