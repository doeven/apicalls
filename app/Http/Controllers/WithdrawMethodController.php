<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//Models
use App\Models\WithdrawMethod;

class WithdrawMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $withdraw_methods = WithdrawMethod::all();
        return $withdraw_methods;
        
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
        $result = WithdrawMethod::create($input);

        if($result){
            return ["result" => "Withdraw Method Created Successfully"];
        } else{
            return ["result" => "An Error Occured While Creating Withdraw Method"];
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
        $withdraw_method = WithdrawMethod::find($id);
        if($withdraw_method == NULL){ die('Withdraw Method ID Does Not Exist');}

        return $withdraw_method;        

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
        $withdraw_method = WithdrawMethod::find($id);

        if($withdraw_method == NULL){ die('Withdraw Method ID Does Not Exist');}

        $validatedData = $request->all();

        $result = $withdraw_method->fill($validatedData)->save();

        if($result){
            return ["result" => "Updated Successfully"];
        } else{
            return ["result" => "An Error Occured While Updating Withdraw Method"];
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
        $withdraw_method = WithdrawMethod::find($id);

        // Check if the ID still exists in DB
        if($withdraw_method == NULL) {die("Withdraw Method ID No Longer Exists");}

        // DELETE function for the ID
        $result = $withdraw_method->delete();

        // Check if the Result executed

        if($result){
            return ["result" => "Selected Withdraw Method has been Successfully Deleted!"];
        } else{
            return ["result" => "An Error Occured While Deleting Withdraw Method"];
        }
    }
}
