<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Models\Renter;
use App\Models\Favourite;
use App\Models\Post;

class renterController extends Controller
{
    public function register(Request $request){
        try {
            $validatedData= $request->validate([
                "username" => "required|string",
                "renter_name" => "required|string",
                "phone" => "required|string",
                "password" => "required|string|min:6",
                "image" => "required|string",
            ]);
            
            $renter = Renter::where("username" , $request->input("username"))->first();
            if($renter){
                return response()->json(["message"=> "User already exists"], 401);
            }
    
            $renter = Renter::create([
                "username" => $request->input("username"),
                "renter_name" => $request->input("renter_name"),
                "phone" => $request->input("phone"),
                "password" => bcrypt($request->input("password")),
                "image" => $request->input("image")
            ]);
    
            return response()->json(["message"=> "Registration successful"]);
        } catch (ValidationException $e) {
            return response()->json(["error" => $e->errors()], 422);
        }
    }
     function login(Request $request){
        $renter =Renter::where("username" , $request->input("username"))->first();
        if(!$renter){
            return response()->json(["message"=> "user not found"] , 401);
        }
        if(!Hash::check($request->input("password") , $renter->password)){
            return response()->json(["message" => "wrong password"] , 401);
        }
       $token = $renter->createToken("maskankApp");
        return response()->json(["token" => $token->plainTextToken ,
        "message" => "renter successfully logged in"
    ] , 200);
    }
    function logout(Request $request){
        try{
            $request->user()->currentAccessToken()->delete();
            return response()->json(["message" => "renter successfully logged out"] , 200);
        }
        catch (ValidationException $e) {
            return response()->json(["error" => $e->errors()], 422);
        }
    }

    public function showRenterFavouritePost($post_id){

    $post = Post::find($post_id);
     $favs = Favourite::where('post_id', $post_id)->get();
     $renters=[];
     foreach($favs as $fav){
         $renters[] = $fav->renter;
     }
     return response()->json($renters);
     }

     public function showAllRenters()
 {
     $renters = Favourite::distinct()->pluck('renter_id');
 
     $renterDetails = Renter::whereIn('renter_id', $renters)->get();
 
     return response()->json([
         'message' => 'All renters who have favorited any post.',
         'data' => $renterDetails,
     ]);
 }
}

