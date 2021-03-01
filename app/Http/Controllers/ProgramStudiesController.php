<?php

namespace App\Http\Controllers;

use App\Models\ProgramStudyModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProgramStudiesController extends Controller
{
    public function __construct()
    {
        $this->middleware("login");
    }
    
    public function index()
    {
        $program_study = ProgramStudyModel::all();

        return response()->json([
            'success' => true,
            'message' => 'List Semua Program Studi',
            'data'    => $program_study
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'program_study_name'   => 'required',
            'program_study_code' => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Semua Kolom Wajib Diisi!',
                'data'   => $validator->errors()
            ], 401);
        } else {

            $program_study = ProgramStudyModel::create([
                'program_study_name'     => $request->input('program_study_name'),
                'program_study_code' => $request->input('program_study_code'),
            ]);

            if ($program_study) {
                return response()->json([
                    'success' => true,
                    'message' => 'Program Studi Berhasil Disimpan!',
                    'data' => $program_study
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Program Studi Gagal Disimpan!',
                ], 400);
            }
        }
    }

    public function show($id)
    {
        $program_study = ProgramStudyModel::find($id);

        if ($program_study) {
            return response()->json([
                'success'   => true,
                'message'   => 'Detail Program Studi!',
                'data'      => $program_study
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Program Studi Tidak Ditemukan!',
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'program_study_name'   => 'required',
            'program_study_code' => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Semua Kolom Wajib Diisi!',
                'data'   => $validator->errors()
            ], 401);
        } else {

            $program_study = ProgramStudyModel::whereId($id)->update([
                'program_study_name'     => $request->input('program_study_name'),
                'program_study_code' => $request->input('program_study_code'),
            ]);

            if ($program_study) {
                return response()->json([
                    'success' => true,
                    'message' => 'Program Studi Berhasil Diupdate!',
                    'data' => $program_study
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Program Studi Gagal Diupdate!',
                ], 400);
            }
        }
    }

    public function destroy($id)
    {
        $program_study = ProgramStudyModel::whereId($id)->first();
        $program_study->delete();

        if ($program_study) {
            return response()->json([
                'success' => true,
                'message' => 'Program Studi Berhasil Dihapus!',
            ], 200);
        }
    }
}
