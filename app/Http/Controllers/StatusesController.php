 <?php

namespace App\Http\Controllers;

use App\Models\StatusModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StatusesController extends Controller
{
    public function __construct()
    {
        $this->middleware("login");
    }
    
    public function index()
    {
        $status = StatusModel::all();

        return response()->json([
            'success' => true,
            'message' => 'List Semua Status',
            'data'    => $status
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status_name'   => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Semua Kolom Wajib Diisi!',
                'data'   => $validator->errors()
            ], 401);
        } else {

            $status = StatusModel::create([
                'status_name'     => $request->input('status_name'),
            ]);

            if ($status) {
                return response()->json([
                    'success' => true,
                    'message' => 'Status Berhasil Disimpan!',
                    'data' => $status
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Status Gagal Disimpan!',
                ], 400);
            }
        }
    }

    public function show($id)
    {
        $status = StatusModel::find($id);

        if ($status) {
            return response()->json([
                'success'   => true,
                'message'   => 'Detail Status!',
                'data'      => $status
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Status Tidak Ditemukan!',
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status_name'   => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Semua Kolom Wajib Diisi!',
                'data'   => $validator->errors()
            ], 401);
        } else {

            $status = StatusModel::whereId($id)->update([
                'status_name'     => $request->input('status_name'),
            ]);

            if ($status) {
                return response()->json([
                    'success' => true,
                    'message' => 'Status Berhasil Diupdate!',
                    'data' => $status
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Status Gagal Diupdate!',
                ], 400);
            }
        }
    }

    public function destroy($id)
    {
        $status = StatusModel::whereId($id)->first();
        $status->delete();

        if ($status) {
            return response()->json([
                'success' => true,
                'message' => 'Status Berhasil Dihapus!',
            ], 200);
        }
    }
}
