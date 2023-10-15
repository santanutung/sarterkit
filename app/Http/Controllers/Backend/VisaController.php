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

        $addon_packages = AddonPackage::all();
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
        // countries
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
            'price' => 'required',
        ]);


        AddonPackage::create([
            'name' => $request->name,
            'price' => $request->price,
        ]);
        // upload images


        notify()->success('AddonPackage Successfully Added.', 'Added');
        return redirect()->route('app.addon-packages.index');

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
    public function edit(AddonPackage $addon_package)
    {

        return view('backend.visas.from', ['AddonPackage' => $addon_package]);




        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AddonPackage  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AddonPackage $addon_package)
    {

        // Gate::authorize('app.users.index');

        $this->validate($request, [
            'name' => 'required |max:190|string',
            'price' => 'required',
        ]);



        $addon_package->name = $request->name;
        $addon_package->price = $request->price;


        // $country->status = $request->filled('status');
        $addon_package->save();


        notify()->success('Addon package Updated Added.', 'Added');
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
