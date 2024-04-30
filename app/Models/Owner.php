<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Owner extends Model
{
    use HasFactory,HasApiTokens;
    protected $primaryKey = "owner_id";
    protected $fillable = [
        "username",
        "owner_name",
        "phone",
        "password",
       "national_id",
       "status",
        "api_token",
        "image"
    ];
}
