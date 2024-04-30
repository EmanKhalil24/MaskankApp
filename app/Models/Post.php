<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasFactory;

    protected $primaryKey = 'post_id';

    protected $fillable = [
        'images',
        'description',
        'price',
        'size',
        'purpose',
        'bedrooms',
        'bathrooms',
        'region',
        'city',
        'floor',
        'condition',
        'status',
        'ownerId',
    ];

    protected $searchable = [
        'images',
        'description',
        'price',
        'size',
        'bedrooms',
        'bathrooms',
        'region',
        'city',
        'floor',
        'condition',
    ];
    public function owner():BelongsTo
    {
        return $this->belongsTo(owner::class);
    }

    public function ScopeSearch(Builder $builder ,string $term=''){
        foreach($this->searchable as $searchable){
            $builder->orWhere($searchable,'like',"%$term%");
        }
        return $builder ;
    }
}
