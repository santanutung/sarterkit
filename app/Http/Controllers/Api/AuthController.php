<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Role;
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
                return response()->json([
                    'status' => 0,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if (!Auth::attempt($request->only(['email', 'password']))) {
                return $this->response(0, ["messages" => array(), 'errors' => ['Email & Password does not match with our record.']]);
            }

            $user = User::where('email', $request->email)->first();
            return $this->response(1, ["messages" => ['User Logged In Successfully'], 'errors' => [], "tocken" => [$user->createToken("API")->plainTextToken]]);
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
            $user_profile->pincode = $request->pin_code;
            $user_profile->phone = $request->phone;
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


    // Credit the referrer's account with a referral bonus
    public function creditReferrer(User $referrer, $amount)
    {
        WalletTransaction::create([
            'user_id' => $referrer->id,
            'amount' => $amount,
            'type' => 'credit',
            'description' => 'Referral bonus',
        ]);
    }

    // Debit a user's account when using a referral bonus
    public function debitUser(User $user, $amount)
    {
        WalletTransaction::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'type' => 'debit',
            'description' => 'Referral bonus used',
        ]);
    }
}
