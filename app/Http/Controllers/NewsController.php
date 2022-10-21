<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $news = News::orderBy('id', 'DESC')->paginate(10);
        if($news->isEmpty()){return 'no news';}
        return $news;
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
        
        // Let's Get the Image Uploaded if Available
        if ( $request->hasFile('image') ) {
            
            //  Let's Run Some Validation
            if ( $request->file('image')->isValid() ) {
                //
                $validated = $request->validate([
                    'title' => 'required',
                    'body' => 'required',
                    'image' => 'mimes:jpeg,png,jpg|max:2048',
                ]);
                $extensionImage = $request->image->extension();

                $trans = array(" " => "-", ":" => "-");
                
                $imageName = strtr(now(), $trans).'image';
                
                $request->image->storeAs('/blog/media', $imageName.'.'.$extensionImage, 'public');
                
                // Assign a Storage URL
                $urlImage = '/blog/media/'.$imageName.'.'.$extensionImage;
                // retur
                
                // Create the Entry to KYC table
                $newsEntry = News::create([
                   'title' => $request->title,
                   'body' => $request->body,
                   'meta_title' => $request->meta_title,
                   'meta_desc' => $request->meta_desc,
                   'slug' => $request->slug,
                   'image' => $urlImage,
                ]);

                return 'News Successfully created';
            }
        }else{
            // Create the Entry to KYC table
            $newsEntry = News::create([
                'title' => $request->title,
                'body' => $request->body,
                'meta_title' => $request->meta_title,
                'meta_desc' => $request->meta_desc,
                'slug' => $request->slug,
                'image' => null,
             ]);

             return 'News Successfully created';

        }
        return "An Error Occured";

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $news = News::find($id);
        if($news == NULL){ die('News ID Does Not Exist');}

        return $news;    
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function edit(News $news)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $news = News::find($id);
        
        if($news == NULL){ die('News ID Does Not Exist');}
        
        $validatedData = $request->all();
        
        // return "something here";

        // Let's Get the Image Uploaded if Available
        if ( $request->hasFile('image') ) {
            return "Image Updated";
            
            //  Let's Run Some Validation
            if ( $request->file('image')->isValid() ) {
                $extensionImage = $request->image->extension();

                $trans = array(" " => "-", ":" => "-");
                
                $imageName = strtr(now(), $trans).'image';
                
                $request->image->storeAs('/blog/media', $imageName.'.'.$extensionImage, 'public');
                
                // Assign a Storage URL
                $urlImage = '/blog/media/'.$imageName.'.'.$extensionImage;
                $validatedData['image'] = $urlImage;
            }
        }
        $result = $news->fill($validatedData)->save();
        // return $validatedData;

        if($result){
            return "Updated Successfully";
        } else{
            return "An Error Occured While Updating News";
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $news = News::find($id);

        // Check if the ID still exists in DB
        if($news == NULL) {die("News ID No Longer Exists");}

        // DELETE function for the ID
        $result = $news->delete();

        // Check if the Result executed

        if($result){
            return ["result" => "Selected News has been Successfully Deleted!"];
        } else{
            return ["result" => "An Error Occured While Deleting News"];
        }
    }
}
