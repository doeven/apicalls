<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Alert;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $alert = Alert::orderBy('id', 'DESC')->paginate(20);
        if($alert->isEmpty()){return 'no alert';}
        return $alert;
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
        
            //  Let's Run Some Validation
                $validated = $request->validate([
                    'title' => 'required',
                    'body' => 'required',
                ]);

                if($validated){
                // Create the Entry to KYC table
                $alertEntry = Alert::create([
                   'title' => $request->title,
                   'body' => $request->body
                ]);

                //Update All Users Alert Notifications
                User::whereVerStatus(1)->update(['alert' => 1]);

                return "Alert Successfully created";
            }
            else{
                return "An error occured while creating this Alert.";
            }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Alert  $alert
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $alert = Alert::find($id);
        if($alert == NULL){ die('Alert ID Does Not Exist');}

        return $alert;    
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Alert  $alert
     * @return \Illuminate\Http\Response
     */
    public function edit(Alert $alert)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Alert  $alert
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $alert = Alert::find($id);
        
        if($alert == NULL){ die('Alert ID Does Not Exist');}
        
        $validatedData = $request->all();
        
        // return "something here";
        $result = $alert->fill($validatedData)->save();
        return $validatedData;

        if($result){
            return "Updated Successfully";
        } else{
            return "An Error Occured While Updating Alert";
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Alert  $alert
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $alert = Alert::find($id);

        // Check if the ID still exists in DB
        if($alert == NULL) {die("Alert ID No Longer Exists");}

        // DELETE function for the ID
        $result = $alert->delete();

        // Check if the Result executed

        if($result){
            return ["result" => "Selected Alert has been Successfully Deleted!"];
        } else{
            return ["result" => "An Error Occured While Deleting Alert"];
        }
    }
}
