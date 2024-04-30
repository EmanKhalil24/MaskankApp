<?php

namespace App\Http\Controllers;
use App\Models\Owner;
use Illuminate\Http\Request;

class Ownerscontroller extends Controller
{
     public function allowners()
    {
        $owners=owner::all();
        return ($owners);
    }
}
