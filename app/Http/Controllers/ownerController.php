<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Owner;
use App\Http\Requests\ownerUpdateRequest;
use Illuminate\Support\Facades\Storage;

class ownerController extends Controller
{
    public function register(Request $request){
        $validatedData= $request->validate([
            "username" => "required|string",
            "owner_name" => "required|string",
            "phone" => "required|string",
            "password" => "required|string|min:6",
            "national_id" => "required|string",
            "image" => "required|string",
        ]);
        $owner =Owner::where("username" , $request->input("username"))->first();
        if($owner){
            return response()->json(["message"=> "owner already exist"] , 401);
        }

        $owner = Owner::create([
            "username" => $request->input("username"),
            "owner_name" => $request->input("owner_name"),
            "phone" => $request->input("phone"),
            "password" => bcrypt($request->input("password")),
            "national_id" => $request->input("national_id"),
            "image" => $request->input("image")
        ]);
        return response()->json(["message" => "registration successfully"]);
    }

    public function login(Request $request){
        $owner =Owner::where("username" , $request->input("username"))->first();
        if(!$owner){
            return response()->json(["message"=> "owner not found"] , 401);
        }
        if(!Hash::check($request->input("password") , $owner->password)){
            return response()->json(["message" => "wrong password"] , 401);
        }
       $token = $owner->createToken("Maskank");
        return response()->json(["token" => $token->plainTextToken ,
        "message" => "owner successfully logged in"
        ] , 200);
    }
    
    public function logout(Request $request){
            $request->user()->currentAccessToken()->delete();
            return response()->json(["message" => "owner successfully logged out"] , 200);
        }

    public function destroy($owner_id)
    {
        $owner = Owner::find($owner_id);
        if (!$owner) {
            return response()->json(['message' => 'Owner not found'], 404);
        }
        $national_id = explode(',',$owner['national_id']);
        for($i=0;$i<count($national_id)-1; $i++ ){
            $imagePath = public_path('nationalId/'.$national_id[$i]);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
        }
        if (!empty($owner->photo)){
        $imagePath = public_path('ownerPhoto/'.$owner->photo);
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }
        $deleted = $owner->delete();
        $ownerData = Owner::all();
        if ($deleted) {
            $message = 'Owner deleted successfully.';
            return response()->json(['data' => $ownerData , 'status' => true, 'message' => $message], 200);
        } else {
            return response()->json(['message' => 'Failed to delete owner'], 500);
        }
    }

    public function show($owner_id)
    {
        $owner = owner::find($owner_id);
        $national_id = explode(',',$owner['national_id']);
        for($i=0;$i<count($national_id)-1; $i++ ){
            $national_id=  asset('nationalId/'.$national_id[$i]);
            }
        return response()->json([
            'status'=>true,
            'message'=>'data owner',
            'data'=>[
                'data_owner'=>$owner,
                'path'=>asset('ownerPhoto/'.$owner->photo),
                'national_id'=>$national_id
        ],
        ]);
    }

    public function update(ownerUpdateRequest $request,$id)
    {
        $owner = owner::find($id);
        $data = $request->validated();
            if($request->hasFile('photo')){
                if (!empty($owner->photo)){
                    $imagePath = public_path('ownerPhoto/'.$owner->photo);
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                    $ext = $data['photo']->getClientOriginalExtension();
                    $imageName = time().'_'.rand().'.'.$ext;
                    $photoName =  $data['photo']->move(public_path().'/ownerPhoto', $imageName);
                    $newName = explode('\\',$photoName);
                    $data['photo'] = end($newName);
                }
            }

            if( $owner->update($data)){
                return response()->json([
                'status' =>  true,
                'massage'=>  'Owner update successfully',
                'data'   =>  [$owner],
                'path'=> Storage::path($owner->photo),
            ],200);
        }else{
            return response()->json([
                'status' => false,
                'massage'=> 'owner not update successfully',
                'data'=>  [],
            ],405);
        }
    }
}