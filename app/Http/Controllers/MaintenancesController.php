<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class MaintenancesController extends Controller
{
    public function __construct()
    {
        $this->middleware("login");
    }

    protected function maintenanceAll(){
        return DB::table('maintenance')
        ->select('maintenance.id', 'maintenance.complaint', 
        'maintenance.status', 'maintenance.id_lecturer')
        ->get();
    }

    protected function maintenanceDetail($id){
        return DB::table('maintenance')
        ->join('users', 'maintenance.id_lecturer', '=', 'users.id')
        ->select('maintenance.id', 'maintenance.complaint', 'users.name as lecturer', 
        'maintenance.id_room', 'maintenance.no_computer', 'maintenance.date_reported', 
        'maintenance.date_required', 'maintenance.status')
        ->where('maintenance.id', $id)->first();
    }

    public function index()
    {
        $maintenance = $this->maintenanceAll();

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
                'date_reported'     => date('Y-m-d'),
                'date_required'     => $request->input('date_required'),
                'status'     => $request->input('status'),
                'id_lecturer'     => $request->input('id_lecturer'),
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
        // $maintenance['maintenance'] = MaintenanceModel::find($id);
        $maintenance = $this->maintenanceDetail($id);
    //     $maintenance =[
    //         'maintenance' =>MaintenanceModel::maintenanceDetail($id),
    //         'user' =>MaintenanceModel::maintenanceDetail($id)
    // ];

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
            'status'   => 'required',
            'id_lecturer'   => 'required',
            'id_room'   => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Semua Kolom Wajib Diisi!',
                'data'   => $validator->errors()
            ], 401);
        } else {
            
            $maintenance = MaintenanceModel::where('id', $id)->first();
            if ($maintenance) {
                $maintenance -> no_computer = $request->input('no_computer');
                $maintenance -> complaint = $request->input('complaint');
                $maintenance -> date_reported = $request->input('date_reported');
                $maintenance -> date_required = $request->input('date_required');
                $maintenance -> date_repaired = $request->input('date_repaired');
                $maintenance -> repair_result = $request->input('repair_result');
                $maintenance -> status = $request->input('status');
                $maintenance -> id_lecturer = $request->input('id_lecturer');
                $maintenance -> id_technician = $request->input('id_technician');
                $maintenance -> id_room = $request->input('id_room');

                $maintenance -> save();
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Id tidak ditemukan'
                ], 400);
            }

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
