<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Renter extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $primaryKey = 'renter_id';
    protected $fillable = [
        "username",
        "renter_name",
        "phone",
        "password",
        "api_token",
        "image"
    ];
}
