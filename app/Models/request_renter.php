<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class request_renter extends Model
{
    use HasFactory;
    protected $primaryKey = 'request_id';

    protected $fillable = [
        'renterId ',
        'postId ',
    ];
}
