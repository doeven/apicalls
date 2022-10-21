<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//Models
use App\Models\Level;

class LevelController extends Controller
{
  
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $levels = Level::all();
        return $levels;
        
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
        $result = Level::create($input);

        if($result){
            return ["result" => "Level Created Successfully"];
        } else{
            return ["result" => "An Error Occured While Creating Level"];
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
        $level = Level::find($id);
        if($level == NULL){ die('Level ID Does Not Exist');}

        return $level;        

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
        $level = Level::find($id);

        if($level == NULL){ die('Level ID Does Not Exist');}

        $validatedData = $request->all();

        $result = $level->fill($validatedData)->save();

        if($result){
            return ["result" => "Updated Successfully"];
        } else{
            return ["result" => "An Error Occured While Updating Level"];
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

        // Cannot Delete the ID 1 (Standard/Basic) Level
        if($id == 1){
            return ["result" => "Cannot Delete the Basic Level"];
        }

        $level = Level::find($id);

        // Check if the ID still exists in DB
        if($level == NULL) {die("Level ID No Longer Exists");}

        // DELETE function for the ID
        $result = $level->delete();

        // Check if the Result executed

        if($result){
            return ["result" => "Selected Level has been Successfully Deleted!"];
        } else{
            return ["result" => "An Error Occured While Deleting Level"];
        }
    }
}
