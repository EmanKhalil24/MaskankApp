<?php

namespace App\Http\Controllers;
use App\Models\request_renter;
use Illuminate\Http\Request;

class Requestscontroller extends Controller
{
    public function requestsnumber()
    {
        $requests=request_renter::all('request_id');
        return response()->json('count : '.count($requests));
    }
}
