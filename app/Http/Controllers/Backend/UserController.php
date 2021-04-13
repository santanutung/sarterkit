<?php

namespace App\Http\Controllers\Backend;


use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;


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
        $users=User::all();
        return view('backend.user.index', ['users'=> $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Gate::authorize('app.users.create');
       $role= Role::all();
       return view('backend.user.from',['roles'=>$role]);


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
            'role' => 'required',
            'password'=> 'required|confirmed|string|min:8',
            'avatar'=>'required|image'

        ]);

        $user = User::create([
            'role_id' => $request->role,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => $request->filled('status'),
        ]);
        // upload images
        if ($request->hasFile('avatar')) {
            $user->addMedia($request->avatar)->toMediaCollection('avatar');
        }
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

        return view('backend.user.show', ['user'=> $user]);

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
        $roles=Role::all();
        return view('backend.user.from', ['roles'=>  $roles, 'user'=>$user]);




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
            'email' => 'required|email|max:190|unique:users,email,'. $user->id,
            'role' => 'required',
            'password' => 'nullable|confirmed|string|min:8',
            'avatar' => 'nullable|image',

        ]);



         $user->update([
            'role_id' => $request->role,
            'name' => $request->name,
            'email' => $request->email,
            'password' => isset($request->password) ? Hash::make($request->password) : $user->password,
            'status' => $request->filled('status'),
        ]);

        // upload images
        if ($request->hasFile('avatar')) {
            $user->addMedia($request->avatar)->toMediaCollection('avatar');
        }

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
