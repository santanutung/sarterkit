<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Gate::authorize('app.page.index');
        $page = Page::latest('id')->get();

        return view('backend.page.index', ['pages' => $page]);
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Gate::authorize('app.page.create');
        return view('backend.page.from');
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        Gate::authorize('app.page.create');
        $this->validate($request, [
            'title' => 'required |string|unique:pages',
            'body' => 'required|string',
            'image' => 'image'
        ]);

        $page = Page::create([
            'title' => $request->title,
            'slug' => Str::slug($request->slug),
            'excerpt' => $request->excerpt,
            'body' => $request->body,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
            'status' => $request->filled('status'),
        ]);
        // upload images
        if ($request->hasFile('image')) {
            $page->addMedia($request->image)->toMediaCollection('image');
        }
        notify()->success('Page Successfully Added.', 'Added');
        return redirect()->route('app.pages.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function show(Page $page)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page)
    {
        Gate::authorize('app.page.edit');

        return view('backend.page.from', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page)
    {

        Gate::authorize('app.page.edit');
        $this->validate($request, [
            'title' => 'required |string|unique:pages,title,' . $page->id,
            'body' => 'required|string',
            'image' => 'image'
        ]);

        $page->update([
            'title' => $request->title,
            'slug' => Str::slug($request->slug),
            'excerpt' => $request->excerpt,
            'body' => $request->body,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,
            'status' => $request->filled('status'),
        ]);
        // upload images
        if ($request->hasFile('image')) {
            $page->addMedia($request->image)->toMediaCollection('image');
        }
        notify()->success('Page Successfully Updated.', 'Added');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $page)
    {
        Gate::authorize('app.page.destroy');
        $page->delete();
        notify()->success('Page  Successfully  deleted.', 'Success');
        return back();
    }
}
