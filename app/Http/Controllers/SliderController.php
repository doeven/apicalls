<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sliders = Slider::all();
        return $sliders;
    }

    // Show all Active Sliders
    public function active()
    {
        $sliders = Slider::whereStatus(1)->get();
        return $sliders;
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
        $result = Slider::create($input);

        if($result){
            return ["result" => "Slider Created Successfully"];
        } else{
            return ["result" => "An Error Occured While Creating Slider"];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $slider = Slider::find($id);
        if($slider == NULL){ die('Slider ID Does Not Exist');}

        return $slider;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function edit(Slider $slider)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $slider = Slider::find($id);

        if($slider == NULL){ die('Slider ID Does Not Exist');}

        $validatedData = $request->all();

        $result = $slider->fill($validatedData)->save();

        if($result){
            return ["result" => "Updated Successfully"];
        } else{
            return ["result" => "An Error Occured While Updating Slider"];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $slider = Slider::find($id);

        // Check if the ID still exists in DB
        if($slider == NULL) {die("Slider ID No Longer Exists");}

        // DELETE function for the ID
        $result = $slider->delete();

        // Check if the Result executed

        if($result){
            return ["result" => "Selected Slider has been Successfully Deleted!"];
        } else{
            return ["result" => "An Error Occured While Deleting Slider"];
        }
    }
}
