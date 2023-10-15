<?php

namespace App\Http\Controllers\Backend;


use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Gate::authorize('app.users.index');
        $users = User::all();
        return view('backend.user.index', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Gate::authorize('app.users.create');
        $role = Role::all();
        return view('backend.user.from', ['roles' => $role]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        Gate::authorize('app.users.create');
        $this->validate($request, [
            'name' => 'required |max:190|string',
            'email' => 'required|email|max:190|unique:users',
            'phone' => 'required',
            'sub_district' => 'required',
            'district' => 'required',
            'province' => 'required',
            'line_id' => 'required',
            'pin_code' => 'required',
            'address' => 'required',
            'role' => 'required',
            'password' => 'required|confirmed|string|min:8',
            'avatar' => 'required|image'
            

        ]);
        $img_path_name = null;
        $avatar_image = $request->file('avatar');
        if ($avatar_image) {
            $original_name = $avatar_image->getClientOriginalName();
            $name_generated =  $original_name . time();
            $extension = strtolower($avatar_image->getClientOriginalExtension());
            $image_name = $name_generated . "." . $extension;
            $upload_location = 'uploads/avatar/';
            $img_path_name = $upload_location . $image_name;
            $avatar_image->move(public_path($upload_location), $image_name);
        }

        $user = User::create([
            'role_id' => $request->role,
            'name' => $request->name,
            'email' => $request->email,
            'director_name' => $request->director_name,
            'phone' => $request->phone,
            'sub_district' => $request->sub_district,
            'district' => $request->district,
            'province' => $request->province,
            'line_id' => $request->line_id,
            'pin_code' => $request->pin_code,
            'address' => $request->address,
            'avatar' => $img_path_name,
            'password' => Hash::make($request->password),
            'status' => $request->filled('status'),
        ]);
        // upload images


        notify()->success('User Successfully Added.', 'Added');
        return redirect()->route('app.users.index');

        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        Gate::authorize('app.users.index');

        return view('backend.user.show', ['user' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        Gate::authorize('app.users.edit');
        $roles = Role::all();
        return view('backend.user.from', ['roles' =>  $roles, 'user' => $user]);




        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {

        Gate::authorize('app.users.index');

        $this->validate($request, [
            'name' => 'required |max:190|string',
            'email' => 'required|email|max:190|unique:users,email,' . $user->id,
            'role' => 'required',
            'phone' => 'required',
            'sub_district' => 'required',
            'district' => 'required',
            'province' => 'required',
            'line_id' => 'required',
            'pin_code' => 'required',
            'address' => 'required',
            'password' => 'nullable|confirmed|string|min:8',
            'avatar' => 'nullable|image',

        ]);

        // upload images
        // if ($request->hasFile('avatar')) {
        //     $user->addMedia($request->avatar)->toMediaCollection('avatar');
        // }
        $img_path_name = null;
        $avatar_image = $request->file('avatar');
        if ($avatar_image) {
            $isExists = File::exists($user->image);
            if ($isExists) {
                unlink($user->image);
            }
            // $original_name = $avatar_image->getClientOriginalName();
            $name_generated =   time();
            $extension = strtolower($avatar_image->getClientOriginalExtension());
            $image_name = $name_generated . "." . $extension;
            $upload_location = 'uploads/avatar/';
            $img_path_name = $upload_location . $image_name;
            $avatar_image->move(public_path($upload_location), $image_name);
            $user->image = $img_path_name;
        }

        $user->role_id = $request->role;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->director_name = $request->director_name;
        $user->sub_district = $request->sub_district;
        $user->district = $request->district;
        $user->province = $request->province;
        $user->line_id = $request->line_id;
        $user->pin_code = $request->pin_code;
        $user->address = $request->address;
        $user->password = isset($request->password) ? Hash::make($request->password) : $user->password;
        $user->status = $request->filled('status');
        $user->save();


        notify()->success('User Updated Added.', 'Added');
        // return redirect()->route('app.users.index');
        return back();
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        Gate::authorize('app.users.destroy');
        if ($user->deletable == true) {
            $user->delete();
            notify()->success("User Successfully Deleted", "Deleted");
        } else {
            notify()->error("You can\'t delete system User.", "Error");
            return back();
        }
        return back();

        //
    }
}
