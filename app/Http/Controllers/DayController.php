<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class DayController extends Controller
{
    public function __construct()
    {
        $this->middleware("login");
    }

    public function listDay() {
        $room = DB::table('day')
        ->select('id','name')
        ->get();

        return $room;
    }
    
    public function index()
    {
        $room = $this->listDay();

        return response()->json([
            'success' => true,
            'message' => 'List Semua Hari',
            'data'    => $room
        ], 200);
    }
}
