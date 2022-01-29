<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class HourController extends Controller
{
    public function __construct()
    {
        $this->middleware("login");
    }

    public function listHour() {
        $room = DB::table('hour')
        ->select('id','name')
        ->get();

        return $room;
    }
    
    public function index()
    {
        $room = $this->listHour();

        return response()->json([
            'success' => true,
            'message' => 'List Semua Jam',
            'data'    => $room
        ], 200);
    }
}
