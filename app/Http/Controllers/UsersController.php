<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware("login");
    }

    public function userlogin()
    {
        return "Anda Berhasil masuk";
    }

    public function index()
    {
        $user = UserModel::all();

        return response()->json([
            'success' => true,
            'message' => 'List Semua Pengguna',
            'data'    => $user
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'   => 'required',
            'address'   => 'required',
            'no_hp'   => 'required',
            'password'   => 'required',
            'id_user_type'   => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Semua Kolom Wajib Diisi!',
                'data'   => $validator->errors()
            ], 401);
        } else {

            $user = UserModel::create([
                'name'     => $request->input('name'),
                'address'     => $request->input('address'),
                'no_hp'     => $request->input('no_hp'),
                'password'     => $request->input('password'),
                'id_user_type'     => $request->input('id_user_type'),
            ]);

            if ($user) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pengguna Berhasil Disimpan!',
                    'data' => $user
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengguna Gagal Disimpan!',
                ], 400);
            }
        }
    }

    public function show($id)
    {
        $user = UserModel::find($id);

        if ($user) {
            return response()->json([
                'success'   => true,
                'message'   => 'Detail Pengguna!',
                'data'      => $user
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna Tidak Ditemukan!',
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'   => 'required',
            'address'   => 'required',
            'no_hp'   => 'required',
            'password'   => 'required',
            'id_user_type'   => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Semua Kolom Wajib Diisi!',
                'data'   => $validator->errors()
            ], 401);
        } else {

            $user = UserModel::whereId($id)->update([
                'name'     => $request->input('name'),
                'address'     => $request->input('address'),
                'no_hp'     => $request->input('no_hp'),
                'password'     => $request->input('password'),
                'id_user_type'     => $request->input('id_user_type'),
            ]);

            if ($user) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pengguna Berhasil Diupdate!',
                    'data' => $user
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengguna Gagal Diupdate!',
                ], 400);
            }
        }
    }

    public function destroy($id)
    {
        $user = UserModel::whereId($id)->first();
        $user->delete();

        if ($user) {
            return response()->json([
                'success' => true,
                'message' => 'Pengguna Berhasil Dihapus!',
            ], 200);
        }
    }
}
