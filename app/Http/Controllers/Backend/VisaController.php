<?php

namespace App\Http\Controllers\Backend;


use App\Http\Controllers\Controller;
use App\Models\Visa;
use App\Models\Country;
use App\Models\AddonPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

use Illuminate\Support\Str;

class VisaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $addon_packages = Visa::all();
        return view('backend.visas.index', ['addon_packages' => $addon_packages]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        // Gate::authorize('app.users.create');
        $countries = Country::all();
        return view('backend.visas.from', ['countries' => $countries]);
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
            'country' => 'required',
            'price' => 'required',
            'image' => 'required|image'
        ]);

        $visa = new Visa;
        $visa->name =  $request->name;
        $visa->country_id =  $request->country;
        $visa->price =  $request->price;

        $img_path_name = null;
        $avatar_image = $request->file('image');
        if ($avatar_image) {
            $isExists = File::exists($visa->image);
            if ($isExists) {
                // unlink($country->image);
            }
            // $original_name = $avatar_image->getClientOriginalName();
            $name_generated =   time();
            $extension = strtolower($avatar_image->getClientOriginalExtension());
            // $image_name =   Str::slug($request->name)."-". $name_generated . "." . $extension;
            $image_name =   Str::slug($request->name) . "-" . $name_generated . "." . $extension;
            $upload_location = 'uploads/visas/';
            $img_path_name = $upload_location . $image_name;
            $avatar_image->move(public_path($upload_location), $image_name);
            $visa->image = $img_path_name;
        }

        $visa->save();
        // upload images


        notify()->success('Visa Successfully Added.', 'Added');
        return redirect()->route('app.visas.index');

        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AddonPackage  $user
     * @return \Illuminate\Http\Response
     */
    public function show(AddonPackage $addon_package)
    {
        // Gate::authorize('app.users.index');

        return view('backend.visas.show', ['AddonPackage' => $addon_package]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AddonPackage  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(Visa $visa)
    {
        $countries = Country::all();
        return view('backend.visas.from', ['visa' => $visa,'countries'=>$countries]);
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AddonPackage  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Visa $visa)
    {

        // Gate::authorize('app.users.index');

        $this->validate($request, [
            'name' => 'required |max:190|string',
            'country' => 'required',
            'price' => 'required',
            'image' => 'image'
        ]);



    
      
        $visa->name =  $request->name;
        $visa->country_id =  $request->country;
        $visa->price =  $request->price;

        $img_path_name = null;
        $avatar_image = $request->file('image');
        if ($avatar_image) {
            $isExists = File::exists($visa->image);
            if ($isExists) {
                // unlink($country->image);
            }
            // $original_name = $avatar_image->getClientOriginalName();
            $name_generated =   time();
            $extension = strtolower($avatar_image->getClientOriginalExtension());
            // $image_name =   Str::slug($request->name)."-". $name_generated . "." . $extension;
            $image_name =   Str::slug($request->name) . "-" . $name_generated . "." . $extension;
            $upload_location = 'uploads/visas/';
            $img_path_name = $upload_location . $image_name;
            $avatar_image->move(public_path($upload_location), $image_name);
            $visa->image = $img_path_name;
        }

        $visa->save();



        notify()->success('visa Updated.', '');
        // return redirect()->route('app.users.index');
        return back();
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AddonPackage  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(AddonPackage $addon_package)
    {
        // Gate::authorize('app.users.destroy');

        $addon_package->delete();
        notify()->success("AddonPackage Successfully Deleted", "Deleted");
        return back();

        //
    }
}
