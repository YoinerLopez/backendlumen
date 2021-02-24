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
       $input['user_id']= auth()->user()->id;
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
       $post = Post::find($id);
       $validation = $this->uservalidation($post);
       if ($validation) {
           
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
            'message'=> 'Update correct'
            ]);
        
        }
        else{
            return response()->json([
                'res'    => false,
                'message'=> 'Update incorrect, the user is incorrect'
                ]);
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
        $validation =  $this->uservalidation(Post::find($id));
        if($validation){
            Post::destroy($id);
            return response()->json([
                'res'    => true,
                'message'=> 'Delete correct'
            ]);  
        }else{
            return response()->json([
                'res'    => false,
                'message'=> 'Delete incorrect, user incorrect'
            ]); 
        }
   }
   private function validation($request,$id =null){
        $ruleUpdate = is_null($id) ? '' : ',' . $id;            
        $this->validate($request,[
            'title'     =>'required|min:3',   
            'image'      =>'unique:posts,image'.$ruleUpdate
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
    private function uservalidation($post){
       return $post->id === auth()->user()->id ? true : false;
    }
}
