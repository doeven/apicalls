<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = Page::orderBy('id', 'DESC')->paginate(10);
        if($pages->isEmpty()){return 'no pages';}
        return $pages;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
        $input = $request->all();
        $result = Page::create($input);

        if($result){
            return ["result" => "Page Created Successfully"];
        } else{
            return ["result" => "An Error Occured While Creating Page"];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pages  $pages
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $page = Page::find($id);
        if($page == NULL){ die('Page ID Does Not Exist');}

        return $page;    
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pages  $pages
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $pages)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pages  $pages
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $page = Page::find($id);

        if($page == NULL){ die('Page ID Does Not Exist');}

        $validatedData = $request->all();

        $result = $page->fill($validatedData)->save();

        if($result){
            return ["result" => "Updated Successfully"];
        } else{
            return ["result" => "An Error Occured While Updating Page"];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pages  $pages
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $page = Page::find($id);

        // Check if the ID still exists in DB
        if($page == NULL) {die("Page ID No Longer Exists");}

        // DELETE function for the ID
        $result = $page->delete();

        // Check if the Result executed

        if($result){
            return ["result" => "Selected Page has been Successfully Deleted!"];
        } else{
            return ["result" => "An Error Occured While Deleting Page"];
        }
    }
}
