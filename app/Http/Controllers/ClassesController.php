<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClassesController extends Controller
{
    public function __construct()
    {
        $this->middleware("login");
    }
    
    public function index()
    {
        $class = ClassModel::all();

        return response()->json([
            'success' => true,
            'message' => 'List Semua Kelas',
            'data'    => $class
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'class_name' => 'required',
            'class_code' => 'required',
            'id_program_study' => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success'   => false,
                'message'   => 'Semua Kolom Wajib Diisi!',
                'data'  => $validator->errors()
            ], 401);
        } else {

            $class = ClassModel::create([
                'class_name'    => $request->input('class_name'),
                'class_code'    => $request->input('class_code'),
                'id_program_study' => $request->input('id_program_study'),
            ]);

            if ($class) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kelas Berhasil Disimpan!',
                    'data' => $class
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Kelas Gagal Disimpan!',
                ], 400);
            }
        }
    }

    public function show($id)
    {
        $class = ClassModel::find($id);

        if ($class) {
            return response()->json([
                'success'   => true,
                'message'   => 'Detail Kelas!',
                'data'      => $class
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Kelas Tidak Ditemukan!',
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'class_name' => 'required',
            'class_code' => 'required',
            'id_program_study' => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Semua Kolom Wajib Diisi!',
                'data'   => $validator->errors()
            ], 401);
        } else {

            $class = ClassModel::whereId($id)->update([
                'class_name'     => $request->input('class_name'),
                'class_code' => $request->input('class_code'),
                'id_program_study' => $request->input('id_program_study'),
            ]);

            if ($class) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kelas Berhasil Diupdate!',
                    'data' => $class
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Kelas Gagal Diupdate!',
                ], 400);
            }
        }
    }

    public function destroy($id)
    {
        $class = ClassModel::whereId($id)->first();
        $class->delete();

        if ($class) {
            return response()->json([
                'success' => true,
                'message' => 'Kelas Berhasil Dihapus!',
            ], 200);
        }
    }
}
