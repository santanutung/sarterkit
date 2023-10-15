<?php

namespace App\Http\Controllers\Backend;


use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

use Illuminate\Support\Str;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Gate::authorize('app.users.index');
        $countries = Country::all();
        return view('backend.countries.index', ['countries' => $countries]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Gate::authorize('app.users.create');

        return view('backend.countries.from');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        // Gate::authorize('app.users.create');
        $this->validate($request, [
            'name' => 'required |max:190|string',

            'image' => 'required|image'

        ]);

        $country =  new Country;
        $img_path_name = null;
        $avatar_image = $request->file('image');
        if ($avatar_image) {
            $isExists = File::exists($country->image);
            if ($isExists) {
                // unlink($country->image);
            }
            // $original_name = $avatar_image->getClientOriginalName();
            $name_generated =   time();
            $extension = strtolower($avatar_image->getClientOriginalExtension());
            // $image_name =   Str::slug($request->name)."-". $name_generated . "." . $extension;
            $image_name =   Str::slug($request->name) . "-" . $name_generated . "." . $extension;
            $upload_location = 'uploads/countries/';
            $img_path_name = $upload_location . $image_name;
            $avatar_image->move(public_path($upload_location), $image_name);
            $country->image = $img_path_name;
        }


        $country->name = $request->name;

        // $country->status = $request->filled('status');
        $country->save();



        notify()->success('User Successfully Added.', 'Added');
        return redirect()->route('app.countries.index');

        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(Country $country)
    {
        // Gate::authorize('app.users.index');

        return view('backend.countries.show', ['country' => $country]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(Country $country)
    {
        // Gate::authorize('app.users.edit');

        return view('backend.countries.from', ['country' => $country]);




        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Country $country)
    {

        // Gate::authorize('app.users.index');

        $this->validate($request, [
            'name' => 'required |max:190|string',

            'image' => 'image',


        ]);



        $img_path_name = null;
        $avatar_image = $request->file('image');
        if ($avatar_image) {
            $isExists = File::exists($country->image);
            if ($isExists) {
                // unlink($country->image);
            }
            // $original_name = $avatar_image->getClientOriginalName();
            $name_generated =   time();
            $extension = strtolower($avatar_image->getClientOriginalExtension());
            // $image_name =   Str::slug($request->name)."-". $name_generated . "." . $extension;
            $image_name =   Str::slug($request->name) . "-" . $name_generated . "." . $extension;
            $upload_location = 'uploads/countries/';
            $img_path_name = $upload_location . $image_name;
            $avatar_image->move(public_path($upload_location), $image_name);
            $country->image = $img_path_name;
        }


        $country->name = $request->name;

        // $country->status = $request->filled('status');
        $country->save();


        notify()->success('Country Updated Added.', 'Added');
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
    public function destroy(Country $country)
    {
        // Gate::authorize('app.users.destroy');

        $country->delete();
        notify()->success("country Successfully Deleted", "Deleted");

        return back();

        //
    }
}
