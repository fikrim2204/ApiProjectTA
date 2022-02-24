<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use File;
use Carbon\Carbon;


class MaintenancesController extends Controller
{
    public function __construct()
    {
        $this->middleware("login");
    }

    protected function maintenanceAll() {
        return DB::table('maintenance')
        ->leftJoin('users', 'maintenance.id_lecturer', '=', 'users.id')
        ->select('maintenance.id', 'maintenance.no_computer as computer', 'maintenance.title', 
        'maintenance.status', 'users.name as lecturer')
        ->where('maintenance.status', '!=', 'Selesai')
        ->whereOr('maintenance.status', '!=', 'Dibatalkan')
        ->orderBy('maintenance.date_required')
        ->get();
    }

    // protected function maintenanceDetail($id) {
    //     return DB::table('maintenance')
    //     ->join('users', 'maintenance.id_lecturer', '=', 'users.id')
    //     ->select('maintenance.id', 'maintenance.title', 'users.name as lecturer', 
    //     'maintenance.id_room', 'maintenance.no_computer as computer', 'maintenance.date_reported', 
    //     'maintenance.date_required', 'maintenance.status', 'maintenance.img_1', 'maintenance.img_2', 'maintenance.img_3')
    //     ->where('maintenance.id', $id)->first();
    // }

    protected function maintenanceMonth($month, $year) {
        return DB::table('maintenance')
        ->leftJoin('users', 'maintenance.id_lecturer', '=', 'users.id')
        ->select('maintenance.id', 'maintenance.title', 'users.name as lecturer', 'maintenance.status')
        ->whereMonth('maintenance.date_reported', $month)
        ->whereYear('maintenance.date_reported', $year)
        ->get();
    }

    protected function maintenanceDetail($id) {
        return DB::table('maintenance')
        ->leftJoin('room', 'maintenance.id_room', '=', 'room.id')
        ->leftJoin('users as a1', 'maintenance.id_lecturer', '=', 'a1.id')
        ->leftJoin('users as a2', 'maintenance.id_technician', '=', 'a2.id')
        ->select('maintenance.id', 'maintenance.title', 'maintenance.no_computer', 'maintenance.id_room', 'room.name as room', 'a1.name as lecturer', 'maintenance.date_reported', 'maintenance.date_required as date_required', 
        'a2.name as technician', 'maintenance.date_repaired', 'maintenance.repair_result', 'maintenance.status as status', 'maintenance.img_1', 'maintenance.img_2', 'maintenance.img_3')
        ->where('maintenance.id', $id)
        ->first();
    }

    protected function selectImage($id) {
        return DB::table('maintenance')
        ->select('maintenance.img_1', 'maintenance.img_2', 'maintenance.img_3')
        ->where('maintenance.id', $id)
        ->first();
    }

    protected function saveImage(Request $request, string $img) {
        $fileStore = "";
        if ($files = $request->file($img)) {
            $extension = $request->file($img)->getClientOriginalExtension();
            $name = Str::random(29);
            $fileStore = $name . '.' . $extension;
            $files->move('img', $fileStore);
        } else {
            $fileStore = null;
        }
        return $fileStore;
    }

    protected function deleteImage(String $img) {
            $image_path = 'img/'.$img;
            if (file_exists($image_path)) {
                unlink($image_path);
        }
    
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
            'title'   => 'required',
            'date_required'   => 'required',
            'status'   => 'required',
            'img_1' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'img_2' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'img_3' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'id_lecturer'   => 'required',
            'id_room'   => 'required'
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Semua Kolom Wajib Diisi!',
                'data'   => $validator->errors()
            ], 401);
        } else {

            $img_1 = $this->saveImage($request, "img_1");
            $img_2 = $this->saveImage($request, "img_2");
            $img_3 = $this->saveImage($request, "img_3");

            $maintenance = MaintenanceModel::create([
                'no_computer'     => $request->input('no_computer'),
                'title'     => $request->input('title'),
                'date_reported'     => date('Y-m-d'),
                'date_required'     => $request->input('date_required'),
                'status'     => $request->input('status'),
                'img_1' => $img_1,
                'img_2' => $img_2,
                'img_3' => $img_3,
                'id_lecturer'     => $request->input('id_lecturer'),
                'id_room'     => $request->input('id_room')
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
                ], 401);
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
                'data'      => $maintenance
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {       
            $maintenance = MaintenanceModel::where('id', $id)->first();
            if ($maintenance) {
                $maintenance -> no_computer = $request->input('no_computer');
                $maintenance -> title = $request->input('title');
                $maintenance -> date_required = $request->input('date_required');
                $maintenance -> status = $request->input('status');
                $maintenance -> id_lecturer = $request->input('id_lecturer');
                $maintenance -> id_technician = $request->input('id_technician');
                $maintenance -> date_repaired = $request->input('date_repaired');
                $maintenance -> repair_result = $request->input('repair_result');

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

    public function updateTechnician(Request $request, $id)
    {       
            $maintenance = MaintenanceModel::where('id', $id)->first();
            if ($maintenance) {
                $maintenance -> status = $request->input('status');
                $maintenance -> id_technician = $this->convertUsertoIdFromDatabase($request->input('id_technician'));
                $maintenance -> date_repaired = date('Y-m-d');
                $maintenance -> repair_result = $request->input('repair_result');

                $maintenance -> save();
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Id tidak ditemukan'
                ], 400);
            }

            if ($maintenance) {
                $maintenanceDetail = $this->maintenanceDetail($id);
                return response()->json([
                    'success' => true,
                    'message' => 'Pemeliharaan Berhasil Diupdate!',
                    'data' => $maintenanceDetail
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Pemeliharaan Gagal Diupdate!',
                ], 400);
            }
    }

    public function showCurrentMonth() {
        $now = Carbon::now();
        $month = date('m');
        $year = date('Y');
        $maintenance = $this->maintenanceMonth($month, $year);

        if ($maintenance) {
            return response()->json([
                'success' => true,
                'message' => 'Pemeliharaan ditemukan',
                'data' => $maintenance
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Pemeliharaan tidak ditemukan',
            ], 400);
        }
    }

    public function showPreviousMonth() {
        $now = Carbon::now();
        $month = date('m', strtotime(date('Y-m')." -1 month"));
        $year = date('Y', strtotime(date('Y-m')." -1 month"));
        $maintenance = $this->maintenanceMonth($month, $year);

        if ($maintenance) {
            return response()->json([
                'success' => true,
                'message' => 'Pemeliharaan ditemukan',
                'data' => $maintenance
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Pemeliharaan tidak ditemukan',
            ], 400);
        }
    }

    public function showTwoMonthAgo() {
        $now = Carbon::now();
        $month = date('m', strtotime(date('Y-m')." -2 month"));
        $year = date('Y', strtotime(date('Y-m')." -2 month"));
        $maintenance = $this->maintenanceMonth($month, $year);

        if ($maintenance) {
            return response()->json([
                'success' => true,
                'message' => 'Pemeliharaan ditemukan',
                'data' => $maintenance
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Pemeliharaan tidak ditemukan',
            ], 400);
        }
    }

    public function showMonthByUser($month, $year) {
        $maintenance = $this->maintenanceMonth($month, $year);

        if ($maintenance) {
            return response()->json([
                'success' => true,
                'message' => 'Pemeliharaan ditemukan',
                'data' => $maintenance
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Pemeliharaan tidak ditemukan',
            ], 400);
        }
    }

    protected function convertRoomtoIdFromDatabase($room) {
        $query = DB::table('room')
        ->select('room.id')
        ->where('room.name', $room)->first();
        if (!empty($query)) {
            return $query->id;
        } else {
            return null;
        }
    }

    protected function convertUsertoIdFromDatabase($user) {
        $query = DB::table('users')
        ->select('users.id')
        ->where('users.name', $user)->first();
        if (!empty($query)) {
            return $query->id;
        } else {
            return null;
        }
    }

    public function reportCurrentMonth() {
        $now = Carbon::now();
        $month = date('m');
        $year = date('Y');
        $maintenance = $this->maintenanceMonth($month, $year);

        return view('maintenance_monthly_report', ['maintenance' => $maintenance]);
    }

    public function reportPreviousMonth() {
        $now = Carbon::now();
        $month = date('m', strtotime(date('Y-m')." -1 month"));
        $year = date('Y', strtotime(date('Y-m')." -1 month"));
        $maintenance = $this->maintenanceMonth($month, $year);

        return view('maintenance_monthly_report', ['maintenance' => $maintenance]);
    }

    public function reportTwoMonthAgo() {
        $now = Carbon::now();
        $month = date('m', strtotime(date('Y-m')." -2 month"));
        $year = date('Y', strtotime(date('Y-m')." -2 month"));
        echo $month.$year;
        $maintenance = $this->maintenanceMonth($month, $year);

        return view('maintenance_monthly_report', ['maintenance' => $maintenance]);
    }

    public function reportMonthByUser($month, $year) {
        $maintenance = $this->maintenanceMonth($month, $year);

        return view('maintenance_monthly_report', ['maintenance' => $maintenance]);
    }
    
    public function reportDetail($id) {
        $maintenance = $this->maintenanceDetail($id);

        return view('maintenance_detail_report', ['maintenance' => $maintenance]);
    }

    public function destroy($id)
    {
        $maintenance = MaintenanceModel::whereId($id)->first();
        $image = $this->selectImage($id);
        foreach ($image as $img) {
            if ($img != null) {
            $this->deleteImage($img);
            }
        }
        $maintenance->delete();

        if ($maintenance) {
            return response()->json([
                'success' => true,
                'message' => 'Pemeliharaan Berhasil Dihapus!',
            ], 200);
        }
    }
}
