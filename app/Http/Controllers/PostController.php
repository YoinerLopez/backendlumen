<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Requests\StorePostPost;
use Illuminate\Support\Facades\File;
use App\Http\Requests\StoreShowPostPost;

class PostController extends Controller
{
    /**
     * txtsearch: search for title
     */
    public function index(Request $request)
   {
       
        if($request->has('txtsearch')){
            
            return Post::where('title','like','%'.$request->txtsearch.'%')->get();
        }
        else
            return Post::orderBy('created_at', 'desc')->get();
   }
   public function show($id)
   {
        return $post = Post::findOrFail($id);
   }

   /**
    * add new post
    *
    */
   public function store(Request $request)
   {
       
       //validation
        $this->validation($request);
       //unique:tabla,columna
        $input = $request->all();
        if($request->has('image')){
            $input['image']= $this->loadImage($request->image);
        }
       
       Post::create($input);
       
        return response()->json([
           'res'    => true,
           'message'=> 'Register complete'
        ]);
  }  
   /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function update($id,Request $request)
   {   
       //validation
       $this->validation($request,$id);
       //unique:tabla,columna
       
        $input = $request->all();
        if($request->has('image')){
            $input['image']= $this->loadImage($request->image);
        }
       $post = Post::find($id);
       $postimg= $post->image;
       $resp = $this->deleteImage(base_path('public/img/').$post->image);
       $post->update($input);
       
        return response()->json([
           'res'    => true,
           'message'=> 'Update correct'.$postimg.' la respuesta '.$resp
        ]);
  }

   /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function destroy($id)
   {
        Post::destroy($id);
        return response()->json([
            'res'    => true,
            'message'=> 'Delete correct'
         ]);  
   }
   private function validation($request,$id =null){
        $ruleUpdate = is_null($id) ? '' : ',' . $id;    
        
        $this->validate($request,[
            'title'     =>'required|min:3',   
            'image'      =>'unique:posts,image'.$ruleUpdate,
            'user_id'   =>'required'
        ]);
   }
   private function loadImage($image)
    {
        $nameFile = time() . "." . $image->getClientOriginalExtension();
        $image->move(base_path('/public/img'), $nameFile);
        return $nameFile;
    }
    private function deleteImage($filename)
    {
        File::delete($filename);
        return $filename;
                
    }
}
