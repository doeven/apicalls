<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//Models
use App\Models\Package;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $packages = Package::all();
        return $packages;
    }

    // Show Active Packages to Users
    public function packages()
    {
        $packages = Package::whereStatus(1)->get();
        return $packages;
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
        $result = Package::create($input);

        if($result){
            return ["result" => "Package Created Successfully"];
        } else{
            return ["result" => "An Error Occured While Creating Package"];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $package = Package::find($id);
        if($package == NULL){ die('Package ID Does Not Exist');}

        return $package;  
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $package = Package::find($id);

        if($package == NULL){ die('Package ID Does Not Exist');}

        $validatedData = $request->all();

        $result = $package->fill($validatedData)->save();

        if($result){
            return ["result" => "Updated Successfully"];
        } else{
            return ["result" => "An Error Occured While Updating Pakcage"];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $package = Package::find($id);

        // Check if the ID still exists in DB
        if($package == NULL) {die("Package ID No Longer Exists");}

        // DELETE function for the ID
        $result = $package->delete();

        // Check if the Result executed

        if($result){
            return ["result" => "Selected Package has been Successfully Deleted!"];
        } else{
            return ["result" => "An Error Occured While Deleting Package"];
        }
    }
}
