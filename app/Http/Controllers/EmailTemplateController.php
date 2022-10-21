<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use Egulias\EmailValidator\Warning\EmailTooLong;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $emailTemplates = EmailTemplate::orderBy('id', 'DESC')->paginate(10);
        return $emailTemplates;
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
        $result = EmailTemplate::create($input);

        if($result){
            return ["result" => "Email Template Created Successfully"];
        } else{
            return ["result" => "An Error Occured While Creating Email Template"];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EmailTemplate  $emailTemplate
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $emailTemplate = EmailTemplate::find($id);
        if($emailTemplate == NULL){ die('Email Template ID Does Not Exist');}

        return $emailTemplate;   
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EmailTemplate  $emailTemplate
     * @return \Illuminate\Http\Response
     */
    public function edit(EmailTemplate $emailTemplate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EmailTemplate  $emailTemplate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $emailTemplate = EmailTemplate::find($id);

        if($emailTemplate == NULL){ die('Email Template ID Does Not Exist');}

        $validatedData = $request->all();

        $result = $emailTemplate->fill($validatedData)->save();

        if($result){
            return ["result" => "Updated Successfully"];
        } else{
            return ["result" => "An Error Occured While Updating Email Template"];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmailTemplate  $emailTemplate
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $emailTemplate = EmailTemplate::find($id);

        // Check if the ID still exists in DB
        if($emailTemplate == NULL) {die("Email Template ID No Longer Exists");}

        // DELETE function for the ID
        $result = $emailTemplate->delete();

        // Check if the Result executed

        if($result){
            return ["result" => "Selected Email Template has been Successfully Deleted!"];
        } else{
            return ["result" => "An Error Occured While Deleting Email Template"];
        }
    }
}
