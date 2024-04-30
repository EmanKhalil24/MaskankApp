<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Favourite extends Model
{
    use HasFactory;
    protected $fillable = [
        'renter_id' ,
        'post_id'   ,
    ];


    public function post():BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
    public function renter():BelongsTo
    {
        return $this->belongsTo(Renter::class);
    }
}
