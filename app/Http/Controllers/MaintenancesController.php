<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MaintenancesController extends Controller
{
    public function index()
    {
        $maintenance = MaintenanceModel::all();

        return response()->json([
            'success' => true,
            'message' => 'List Semua Pemeliharaan',
            'data'    => $maintenance
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'no_computer'   => 'required',
            'complaint'   => 'required',
            'date_reported'   => 'required',
            'date_required'   => 'required',
            'status'   => 'required',
            'id_lecturer'   => 'required',
            'id_technician' => 'required',
            'id_room'   => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Semua Kolom Wajib Diisi!',
                'data'   => $validator->errors()
            ], 401);
        } else {

            $maintenance = MaintenanceModel::create([
                'no_computer'     => $request->input('no_computer'),
                'complaint'     => $request->input('complaint'),
                'date_reported'     => $request->input('date_reported'),
                'date_required'     => $request->input('date_required'),
                'date_repaired'     => $request->input('date_repaired'),
                'repair_result'     => $request->input('repair_result'),
                'status'     => $request->input('status'),
                'id_lecturer'     => $request->input('id_lecturer'),
                'id_technician'     => $request->input('id_technician'),
                'id_room'     => $request->input('id_room'),
            ]);

            if ($maintenance) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pemeliharaan Berhasil Disimpan!',
                    'data' => $maintenance
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Pemeliharaan Gagal Disimpan!',
                ], 400);
            }
        }
    }

    public function show($id)
    {
        $maintenance = MaintenanceModel::find($id);

        if ($maintenance) {
            return response()->json([
                'success'   => true,
                'message'   => 'Detail Pemeliharaan!',
                'data'      => $maintenance
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Pemeliharaan Tidak Ditemukan!',
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'no_computer'   => 'required',
            'complaint'   => 'required',
            'date_reported'   => 'required',
            'date_required'   => 'required',
            'date_repaired'   => 'required',
            'repair_result'   => 'required',
            'status'   => 'required',
            'id_lecturer'   => 'required',
            'id_technician' => 'required',
            'id_room'   => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Semua Kolom Wajib Diisi!',
                'data'   => $validator->errors()
            ], 401);
        } else {

            $maintenance = MaintenanceModel::whereId($id)->update([
                'no_computer'     => $request->input('no_computer'),
                'complaint'     => $request->input('complaint'),
                'date_reported'     => $request->input('date_reported'),
                'date_required'     => $request->input('date_required'),
                'date_repaired'     => $request->input('date_repaired'),
                'repair_result'     => $request->input('repair_result'),
                'status'     => $request->input('status'),
                'id_lecturer'     => $request->input('id_lecturer'),
                'id_technician'     => $request->input('id_technician'),
                'id_room'     => $request->input('id_room'),
            ]);

            if ($maintenance) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pemeliharaan Berhasil Diupdate!',
                    'data' => $maintenance
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Pemeliharaan Gagal Diupdate!',
                ], 400);
            }
        }
    }

    public function destroy($id)
    {
        $maintenance = MaintenanceModel::whereId($id)->first();
        $maintenance->delete();

        if ($maintenance) {
            return response()->json([
                'success' => true,
                'message' => 'Pemeliharaan Berhasil Dihapus!',
            ], 200);
        }
    }
}
