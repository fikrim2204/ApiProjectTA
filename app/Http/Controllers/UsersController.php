<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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

    protected function userInfo($id) {
        return DB::table('users')
        ->select('name', 'email',
        'address', 'no_hp')
        ->where('id', $id)
        ->first();
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
            'role'   => 'required',
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
                'role'     => $request->input('role'),
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
        $user = $this->userInfo($id);

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
            if (empty($request->input('password'))) {
                $user = UserModel::whereId($id)->update([
                    'email'     => $request->input('email'),
                    'address'     => $request->input('address'),
                    'no_hp'     => $request->input('no_hp')
                ]);
            } else {
                $hashPwd = Hash::make($request->input('password'));
                $user = UserModel::whereId($id)->update([
                    'password'     => $hashPwd
                ]);
            }

            if ($user) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pengguna Berhasil Diupdate!'
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengguna Gagal Diupdate!',
                ], 400);
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
