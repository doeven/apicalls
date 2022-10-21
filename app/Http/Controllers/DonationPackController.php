<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//Models
use App\Models\DonationPack;

class DonationPackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $donation_packs = DonationPack::all();
        return $donation_packs;
    }

    // Show Active Donation Packs to Users
    public function donation_packs()
    {
        $donation_packs = DonationPack::whereStatus(1)->get();
        return $donation_packs;
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
        $result = DonationPack::create($input);

        if($result){
            return ["result" => "Donation Pack Created Successfully"];
        } else{
            return ["result" => "An Error Occured While Creating Donation Pack"];
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
        $donation_pack = DonationPack::find($id);
        if($donation_pack == NULL){ die('Donation Pack ID Does Not Exist');}

        return $donation_pack;  
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
        $donation_pack = DonationPack::find($id);

        if($donation_pack == NULL){ die('Donation Pack ID Does Not Exist');}

        $validatedData = $request->all();

        $result = $donation_pack->fill($validatedData)->save();

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
        $donation_pack = DonationPack::find($id);

        // Check if the ID still exists in DB
        if($donation_pack == NULL) {die("Donation Pack ID No Longer Exists");}

        // DELETE function for the ID
        $result = $donation_pack->delete();

        // Check if the Result executed

        if($result){
            return ["result" => "Selected Donation Pack has been Successfully Deleted!"];
        } else{
            return ["result" => "An Error Occured While Deleting Donation Pack"];
        }
    }
}
