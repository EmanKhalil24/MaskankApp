<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admins;
use App\Models\Post;
use App\Http\Requests\AdminPostApprovedRequest;
class Adminscontroller extends Controller
{
    public function login(Request $request)
    {
        $admin=Admins::where("username",$request->input("username"))->first();
          if(!$admin)
          {
            return response()->json(["message"=>"admin not found"],401);
          }
            if($request->input("password")!=$admin->password)
            {
                return response()->json(["message"=>"wrong password"],404);
            }
            $token = $admin->createToken("Maskank");
            return response()->json(["token" => $token->plainTextToken ,
            "message" => "Admin successfully logged in!"
        ] , 200);
    }

    public function logout(Request $request)
    {
    $request->user()->tokens()->delete();

    return response()->json(['message' => 'Logout successful'], 200);
    }

    public function approvedPost(AdminPostApprovedRequest $request , string $post_id){
      $post = post::find($post_id);
      if($post->update($request->all())){

          return response()->json(['data'=>[$post],'status'=>true,'message'=>'post is approved successfully'],200);

      }else{
          return response()->json(['data'=>[],'status'=>false,'message'=>'post is not approved'],405);
      }

  }
}