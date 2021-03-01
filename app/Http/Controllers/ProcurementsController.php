<?php

namespace App\Http\Controllers;

use App\Models\ProcurementModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProcurementsController extends Controller
{
    public function __construct()
    {
        $this->middleware("login");
    }
    
    public function index()
    {
        $procurement = ProcurementModel::all();

        return response()->json([
            'success' => true,
            'message' => 'List Semua Pengadaan',
            'data'    => $procurement
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'procurement'   => 'required',
            'date_requested'   => 'required',
            'reason'   => 'required',
            'id_user'   => 'required',
            'status'   => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Semua Kolom Wajib Diisi!',
                'data'   => $validator->errors()
            ], 401);
        } else {

            $procurement = ProcurementModel::create([
                'procurement'     => $request->input('procurement'),
                'date_requested'     => $request->input('date_requested'),
                'reason'     => $request->input('reason'),
                'id_user'     => $request->input('id_user'),
                'status'     => $request->input('status'),
                'note' => $request->input('note'),
            ]);

            if ($procurement) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pengadaan Berhasil Disimpan!',
                    'data' => $procurement
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengadaan Gagal Disimpan!',
                ], 400);
            }
        }
    }

    public function show($id)
    {
        $procurement = ProcurementModel::find($id);

        if ($procurement) {
            return response()->json([
                'success'   => true,
                'message'   => 'Detail Pengadaan!',
                'data'      => $procurement
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Pengadaan Tidak Ditemukan!',
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'procurement'   => 'required',
            'date_requested'   => 'required',
            'reason'   => 'required',
            'id_user'   => 'required',
            'status'   => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Semua Kolom Wajib Diisi!',
                'data'   => $validator->errors()
            ], 401);
        } else {

            $procurement = ProcurementModel::whereId($id)->update([
                'procurement'     => $request->input('procurement'),
                'date_requested'     => $request->input('date_requested'),
                'reason'     => $request->input('reason'),
                'id_user'     => $request->input('id_user'),
                'status'     => $request->input('status'),
                'note' => $request->input('note'),
            ]);

            if ($procurement) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pengadaan Berhasil Diupdate!',
                    'data' => $procurement
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengadaan Gagal Diupdate!',
                ], 400);
            }
        }
    }

    public function destroy($id)
    {
        $procurement = ProcurementModel::whereId($id)->first();
        $procurement->delete();

        if ($procurement) {
            return response()->json([
                'success' => true,
                'message' => 'Pengadaan Berhasil Dihapus!',
            ], 200);
        }
    }
}
