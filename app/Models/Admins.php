<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Admins extends Model
{
    use HasFactory,HasApiTokens;
    protected $primaryKey="admin_id";
    protected $fillable=["username",
                        "password",
                        "status",
                        "postId",
                        "admin_token"
];
}
