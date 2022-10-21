<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//Models
use App\Models\Gateway;

class GatewayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $gateways = Gateway::all();
        return $gateways;
        
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
        $result = Gateway::create($input);

        if($result){
            return ["result" => "Gateway Created Successfully"];
        } else{
            return ["result" => "An Error Occured While Creating Gateway"];
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
        $gateway = Gateway::find($id);
        if($gateway == NULL){ die('Gateway ID Does Not Exist');}

        return $gateway;        

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
        //
        $gateway = Gateway::find($id);

        if($gateway == NULL){ die('Gateway ID Does Not Exist');}

        $validatedData = $request->all();

        $result = $gateway->fill($validatedData)->save();

        if($result){
            return ["result" => "Updated Successfully"];
        } else{
            return ["result" => "An Error Occured While Updating Gateway"];
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
        $gateway = Gateway::find($id);

        // Check if the ID still exists in DB
        if($gateway == NULL) {die("Gateway ID No Longer Exists");}

        // DELETE function for the ID
        $result = $gateway->delete();

        // Check if the Result executed

        if($result){
            return ["result" => "Selected Gateway has been Successfully Deleted!"];
        } else{
            return ["result" => "An Error Occured While Deleting Gateway"];
        }
    }
}
