<?php

namespace App\Http\Controllers;
use App\Models\Renter;
use Illuminate\Http\Request;
use App\Http\Requests\RenterUpdateRequest;

class Renterscontroller extends Controller
{
    public function renters()
    {
        $renters=Renter::all();
        return $renters;
    }

    public function update(RenterUpdateRequest $request,$renter_id)
    {
        $renter = Renter::find($renter_id);

        if(!$renter){
            return response()->json([
                'status' =>  false,
                'massage'=>  'not found',
                'data'   =>  null,
            ],404);
        }

        $data = $request->validated();
        if($request->hasFile('photo')){
            if (!empty($renter->photo)){
                $imagePath = public_path('renterPhoto/'.$renter->photo);
                if (file_exists($imagePath)) {
                unlink($imagePath);
            }
                    $ext = $data['photo']->getClientOriginalExtension();
                    $imageName = time().'_'.rand().'.'.$ext;
                    $photoName =  $data['photo']->move(public_path().'/renterPhoto', $imageName);
                    $newName = explode('\\',$photoName);
                    $data['photo'] = end($newName);


            }
        }

        if( $renter->update($data)){
            return response()->json([
            'status' =>  true,
            'massage'=>  'Renter update successfully',
            'data'   =>  [$renter],
        ],200);
        }else{
        return response()->json([
            'status' => false,
            'massage'=> 'Renter not update successfully',
            'data'   =>  [],
        ],405);
        }
    }

    public function show(string $renter_id)
{
    $renter = Renter::find($renter_id);
    if($renter){
        return response()->json([$renter]);
    }else{
        return response()->json([
            'status' =>  false,
            'massage'=>  'Not Found',
            'data'   =>  [ ],

      ],404);
    }


}

public function destroy($renter_id)
{
    $renter = Renter::find($renter_id);
    if (!$renter) {
        return response()->json(['message' => 'Owner not found'], 404);
    }
    if (!empty($renter->photo)){
        $imagePath = public_path('renterPhoto/'.$renter->photo);
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }
    if($renter->delete()){
        return response()->json(['data'=>[],'status'=>true,'message'=>'owner is deleted successfully'],200);
    }
}
}
