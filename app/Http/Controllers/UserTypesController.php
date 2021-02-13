<?php

namespace App\Http\Controllers;

use App\Models\UserTypeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserTypesController extends Controller
{
    public function index()
    {
        $user_type = UserTypeModel::all();

        return response()->json([
            'success' => true,
            'message' => 'List Semua Tipe User',
            'data'    => $user_type
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_type'   => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Semua Kolom Wajib Diisi!',
                'data'   => $validator->errors()
            ], 401);
        } else {

            $user_type = UserTypeModel::create([
                'user_type'     => $request->input('user_type'),
            ]);

            if ($user_type) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tipe User Berhasil Disimpan!',
                    'data' => $user_type
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Tipe User Gagal Disimpan!',
                ], 400);
            }
        }
    }

    public function show($id)
    {
        $user_type = UserTypeModel::find($id);

        if ($user_type) {
            return response()->json([
                'success'   => true,
                'message'   => 'Detail Tipe User!',
                'data'      => $user_type
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Tipe User Tidak Ditemukan!',
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_type'   => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Semua Kolom Wajib Diisi!',
                'data'   => $validator->errors()
            ], 401);
        } else {

            $user_type = UserTypeModel::whereId($id)->update([
                'user_type'     => $request->input('user_type'),
            ]);

            if ($user_type) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tipe User Berhasil Diupdate!',
                    'data' => $user_type
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Tipe User Gagal Diupdate!',
                ], 400);
            }
        }
    }

    public function destroy($id)
    {
        $user_type = UserTypeModel::whereId($id)->first();
        $user_type->delete();

        if ($user_type) {
            return response()->json([
                'success' => true,
                'message' => 'Tipe User Berhasil Dihapus!',
            ], 200);
        }
    }
}
