<?php

namespace App\Http\Controllers;

use App\Models\ProcurementModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ProcurementsController extends Controller
{
    public function __construct()
    {
        $this->middleware("login");
    }

    public function procurementAll() {
        return DB::table('procurement')
        ->select('procurement.id', 'procurement.title', 'procurement.total', 'procurement.date_requested', 'procurement.status')
        ->where('status', '=', 'Menunggu persetujuan')
        ->get();
    }

    public function procurementDetail($id) {
        return DB::table('procurement')
        ->join('users', 'procurement.id_user', '=', 'users.id')
        ->select('procurement.id', 'procurement.title as title', 'procurement.total', 'procurement.unit', 'procurement.date_requested', 'procurement.description', 'users.name as user', 'procurement.status', 'procurement.note', 'procurement.img_1', 'procurement.img_2', 'procurement.img_3')
        ->where('procurement.id', $id)
        ->first();
    }

    public function procurementMonth($month, $year) {
        return DB::table('procurement')
        ->select('id', 'title', 'date_requested', 'status')
        ->whereMonth('procurement.date_requested', $month)
        ->whereYear('procurement.date_requested', $year)
        ->where('status', '!=', 'Menunggu persetujuan')
        ->get();
    }
    
    public function index()
    {
        $procurement = $this->procurementAll();

        return response()->json([
            'success' => true,
            'message' => 'List Semua Pengadaan',
            'data'    => $procurement
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'   => 'required',
            'total' => 'required',
            'description'   => 'required',
            'id_user'   => 'required',
            'status'   => 'required'
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

            $procurement = ProcurementModel::create([
                'title'     => $request->input('title'),
                'total' => $request->input('total'),
                'unit' => $request->input('unit'),
                'date_requested'     => date('Y-m-d'),
                'description'     => $request->input('description'),
                'id_user'     => $request->input('id_user'),
                'status'     => $request->input('status'),
                'img_1' => $img_1,
                'img_2' => $img_2,
                'img_3' => $img_3
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
        $procurement = $this->procurementDetail($id);

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

    public function showCurrentMonth() {
        $now = Carbon::now();
        $month = date('m');
        $year = date('Y');
        $procurement = $this->procurementMonth($month, $year);

        if ($procurement) {
            return response()->json([
                'success' => true,
                'message' => 'Pengadaan ditemukan',
                'data' => $procurement
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Pengadaan tidak ditemukan',
            ], 400);
        }
    }

    public function showPreviousMonth() {
        $now = Carbon::now();
        $month = date('m', strtotime(date('Y-m')." -1 month"));
        $year = date('Y', strtotime(date('Y-m')." -1 month"));
        $procurement = $this->procurementMonth($month, $year);

        if ($procurement) {
            return response()->json([
                'success' => true,
                'message' => 'Pengadaan ditemukan',
                'data' => $procurement
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Pengadaan tidak ditemukan',
            ], 400);
        }
    }

    public function showTwoMonthAgo() {
        $now = Carbon::now();
        $month = date('m', strtotime(date('Y-m')." -2 month"));
        $year = date('Y', strtotime(date('Y-m')." -2 month"));
        $procurement = $this->procurementMonth($month, $year);

        if ($procurement) {
            return response()->json([
                'success' => true,
                'message' => 'Pengadaan ditemukan',
                'data' => $procurement
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Pengadaan tidak ditemukan',
            ], 400);
        }
    }

    public function showMonthByUser($month, $year) {
        $procurement = $this->procurementMonth($month, $year);

        if ($procurement) {
            return response()->json([
                'success' => true,
                'message' => 'Pengadaan ditemukan',
                'data' => $procurement
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Pengadaan tidak ditemukan',
            ], 400);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title'   => 'required',
            'description'   => 'required',
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

            $procurement = ProcurementModel::where('id', $id)->first();
            if ($procurement) {
                $procurement -> title = $request->input('title');
                $procurement -> total = $request->input('total');
                $procurement -> unit = $request->input('unit');
                $procurement -> description = $request->input('description');
                $procurement -> id_user = $request->input('id_user');
                $procurement -> status = $request->input('status');
                $procurement -> note = $request->input('note');

                $procurement -> save();
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Id tidak ditemukan'
                ], 400);
            }

            // $procurement = ProcurementModel::whereId($id)->update([
            //     'procurement'     => $request->input('procurement'),
            //     'date_requested'     => $request->input('date_requested'),
            //     'reason'     => $request->input('reason'),
            //     'id_user'     => $request->input('id_user'),
            //     'status'     => $request->input('status'),
            //     'note' => $request->input('note'),
            // ]);

            // if ($procurement) {
            //     return response()->json([
            //         'success' => true,
            //         'message' => 'Pengadaan Berhasil Diupdate!',
            //         'data' => $procurement
            //     ], 201);
            // } else {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Pengadaan Gagal Diupdate!',
            //     ], 400);
            // }

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

    public function updateConfirm(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status'   => 'required'
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Semua Kolom Wajib Diisi!',
                'data'   => $validator->errors()
            ], 401);
        } else {

            $procurement = ProcurementModel::where('id', $id)->first();
            if ($procurement) {
                $procurement -> status = $request->input('status');
                $procurement -> note = $request->input('note');

                $procurement -> save();
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Id tidak ditemukan'
                ], 400);
            }

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

    public function reportCurrentMonth() {
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;
        $procurement = $this->procurementMonth($month, $year);

    // var_dump($procurement);
        return view('procurement_monthly_report', ['procurement' => $procurement]);
    }

    public function reportPreviousMonth() {
        $month = Carbon::now()->month-1;
        $year = Carbon::now()->year;
        $procurement = $this->procurementMonth($month, $year);

        return view('procurement_monthly_report', ['procurement' => $procurement]);
    }

    public function reportTwoMonthAgo() {
        $month = Carbon::now()->month-2;
        $year = Carbon::now()->year;
        $procurement = $this->procurementMonth($month, $year);

        return view('procurement_monthly_report', ['procurement' => $procurement]);
    }

    public function reportMonthByUser($month, $year) {
        $procurement = $this->procurementMonth($month, $year);

        return view('procurement_monthly_report', ['procurement' => $procurement]);
    }

    public function reportDetail($id) {
        $procurement = $this->procurementDetail($id);

        return view('procurement_detail_report', ['procurement' => $procurement]);
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

    public function destroy($id)
    {
        $procurement = ProcurementModel::whereId($id)->first();
        $procurement->delete();

        if ($procurement) {
            return response()->json([
                'success' => true,
                'message' => 'Pengadaan Berhasil Dihapus!',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Pengadaan Gagal Diupdate!',
            ], 400);
        }
    }
}
