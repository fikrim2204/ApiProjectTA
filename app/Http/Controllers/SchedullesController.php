<?php

namespace App\Http\Controllers;

use App\Models\SchedulleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SchedullesController extends Controller
{
    public function __construct()
    {
        $this->middleware("login");
    }
    
    public function index()
    {
        $schedulle = SchedulleModel::all();

        return response()->json([
            'success' => true,
            'message' => 'List Semua Jadwal',
            'data'    => $schedulle
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'day'   => 'required',
            'hour' => 'required',
            'id_class' => 'required',
            'id_user' => 'required',
            'id_room' => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Semua Kolom Wajib Diisi!',
                'data'   => $validator->errors()
            ], 401);
        } else {

            $schedulle = SchedulleModel::create([
                'day'     => $request->input('day'),
                'hour'     => $request->input('hour'),
                'id_class'     => $request->input('id_class'),
                'id_user'     => $request->input('id_user'),
                'id_room'     => $request->input('id_room'),
            ]);

            if ($schedulle) {
                return response()->json([
                    'success' => true,
                    'message' => 'Jadwal Berhasil Disimpan!',
                    'data' => $schedulle
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal Gagal Disimpan!',
                ], 400);
            }
        }
    }

    public function show($id)
    {
        $schedulle = SchedulleModel::find($id);

        if ($schedulle) {
            return response()->json([
                'success'   => true,
                'message'   => 'Detail Jadwal!',
                'data'      => $schedulle
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal Tidak Ditemukan!',
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'day'   => 'required',
            'hour' => 'required',
            'id_class' => 'required',
            'id_user' => 'required',
            'id_room' => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Semua Kolom Wajib Diisi!',
                'data'   => $validator->errors()
            ], 401);
        } else {

            $schedulle = SchedulleModel::whereId($id)->update([
                'day'     => $request->input('day'),
                'hour'     => $request->input('hour'),
                'id_class'     => $request->input('id_class'),
                'id_user'     => $request->input('id_user'),
                'id_room'     => $request->input('id_room'),
            ]);

            if ($schedulle) {
                return response()->json([
                    'success' => true,
                    'message' => 'Jadwal Berhasil Diupdate!',
                    'data' => $schedulle
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal Gagal Diupdate!',
                ], 400);
            }
        }
    }

    public function destroy($id)
    {
        $schedulle = SchedulleModel::whereId($id)->first();
        $schedulle->delete();

        if ($schedulle) {
            return response()->json([
                'success' => true,
                'message' => 'Jadwal Berhasil Dihapus!',
            ], 200);
        }
    }
}
