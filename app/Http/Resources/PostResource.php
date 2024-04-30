<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $imagesPost = explode(',', $this->images );


        for($i=0;$i<count($imagesPost); $i++ ){
          $imageList[]=  asset('imagesPosts/'.$imagesPost[$i]);
        }
        return [
            'id'=>$this->id,
            'images'=>$imageList,
            'description'=>$this->description,
            'price'=>$this->price,
            'size'=>$this->size,
            'bedroom'=>$this->bedroom,
            'bathroom'=>$this->bathroom,
            'region'=>$this->region,
            'city'=>$this->city,
            'floor'=>$this->floor,
            'condition'=>$this->condition,
            'status'=>$this->status,
            'owner'=>[
                'owner_id'=>$this->owner->id,
                'username'=>$this->owner->username,
            ],




        ];
    }
}
