<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class Postscontroller extends Controller
{
    public function postsnumber()
    {
        $posts=Post::all('post_id');
        return response()->json('count : '.count($posts));
    }

    public function waiting()
    {
       $waiting=[]; 
       $allposts=Post::all();
       foreach($allposts as $column)
       {
        if($column->status==0)
        {   
            array_push($waiting,$column);
            
        }
        
       }
       return response()->json(['data'=>$waiting]);
      
       
    }

    
    public function acceptable()
    {
       $accept=[]; 
       $allposts=Post::all();
       foreach($allposts as $column)
       {
        if($column->status==1)
        {   
            array_push($accept,$column);
            
        }
        
    }
    return response()->json(['data'=>$accept]);
    }

}
