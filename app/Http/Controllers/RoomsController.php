<?php

namespace App\Http\Controllers;

use App\Models\RoomModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class RoomsController extends Controller
{
    public function __construct()
    {
        $this->middleware("login");
    }

    public function listLabRoom() {
        $room = DB::table('room')
        ->select('id', 'code', 'name')
        ->where('id', '<=', '8')
        ->get();

        return $room;
    }
    
    public function index()
    {
        $room = $this->listLabRoom();

        return response()->json([
            'success' => true,
            'message' => 'List Semua Ruangan',
            'data'    => $room
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lab_name'   => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Semua Kolom Wajib Diisi!',
                'data'   => $validator->errors()
            ], 401);
        } else {

            $room = RoomModel::create([
                'lab_name'     => $request->input('lab_name'),
            ]);

            if ($room) {
                return response()->json([
                    'success' => true,
                    'message' => 'Ruangan Berhasil Disimpan!',
                    'data' => $room
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Ruangan Gagal Disimpan!',
                ], 400);
            }
        }
    }

    public function show($id)
    {
        $room = RoomModel::find($id);

        if ($room) {
            return response()->json([
                'success'   => true,
                'message'   => 'Detail Ruangan!',
                'data'      => $room
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Ruangan Tidak Ditemukan!',
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'lab_name'   => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Semua Kolom Wajib Diisi!',
                'data'   => $validator->errors()
            ], 401);
        } else {

            $room = RoomModel::whereId($id)->update([
                'lab_name'     => $request->input('lab_name'),
            ]);

            if ($room) {
                return response()->json([
                    'success' => true,
                    'message' => 'Ruangan Berhasil Diupdate!',
                    'data' => $room
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Ruangan Gagal Diupdate!',
                ], 400);
            }
        }
    }

    public function destroy($id)
    {
        $room = RoomModel::whereId($id)->first();
        $room->delete();

        if ($room) {
            return response()->json([
                'success' => true,
                'message' => 'Ruangan Berhasil Dihapus!',
            ], 200);
        }
    }
}
