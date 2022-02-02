<?php

namespace App\Http\Controllers;

use App\Models\ScheduleModel;
use App\Models\SubtituteScheduleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SchedulesController extends Controller
{
    public function __construct()
    {
        $this->middleware("login");
    }
    
    protected function scheduleAllWithDetail() {
        return DB::table('schedule')
        ->join('day', 'schedule.day', '=', 'day.id')
        ->join('hour', 'schedule.hour', '=', 'hour.id')
        ->join('class', 'schedule.id_class', '=', 'class.id')
        ->join('users as a1', 'schedule.id_user', '=', 'a1.id')
        ->join('users as a2', 'schedule.id_user2', '=', 'a2.id')
        ->join('subject', 'schedule.subject', '=', 'subject.id')
        ->join('room', 'schedule.id_room', '=', 'room.id')
        ->select('schedule.id', 'day.name as day', 'hour.name as hour',
        'class.name as class', 'a1.name as lecture', 'a2.name as lecture2',
        'subject.name as subject', 'room.name as room')
        ->get();
    }

    protected function scheduleWithIdRoom($room_id) {
        return $table1 =  DB::table('schedule')
        ->leftJoin('day', 'schedule.day', '=', 'day.id')
        ->leftJoin('hour', 'schedule.hour', '=', 'hour.id')
        ->leftJoin('class', 'schedule.id_class', '=', 'class.id')
        ->leftJoin('subject', 'schedule.subject', '=', 'subject.id')
        ->leftJoin('users as a1', 'schedule.id_user', '=', 'a1.id')
        ->leftJoin('users as a2', 'schedule.id_user2', '=', 'a2.id')
        ->leftJoin('room', 'schedule.id_room', '=', 'room.id')
        ->selectRaw('any_value(group_concat(schedule.id order by schedule.id)) as id, class.name as class, subject.name as subject, day.name as day, group_concat(hour.name order by hour.id) as hour, schedule.date as date')
        ->where('room.id', $room_id)
        ->whereNotNull('schedule.id_class')
        ->groupBy('class.name', 'subject.name', 'day.name', 'schedule.date')
        ->orderBy('schedule.id')->get();

        // $table2 = DB::table('subtitute_schedule')
        // ->leftJoin('day', 'subtitute_schedule.day', '=', 'day.id')
        // ->leftJoin('hour', 'subtitute_schedule.hour', '=', 'hour.id')
        // ->leftJoin('class', 'subtitute_schedule.id_class', '=', 'class.id')
        // ->leftJoin('subject', 'subtitute_schedule.subject', '=', 'subject.id')
        // ->leftJoin('users as a1', 'subtitute_schedule.id_user', '=', 'a1.id')
        // ->leftJoin('users as a2', 'subtitute_schedule.id_user2', '=', 'a2.id')
        // ->leftJoin('room', 'subtitute_schedule.id_room', '=', 'room.id')
        // ->selectRaw('group_concat(subtitute_schedule.id order by subtitute_schedule.id) as id, class.name as class, subject.name as subject, day.name as day, group_concat(hour.name order by hour.id) as hour, subtitute_schedule.date as date')
        // ->where('room.id', $room_id)
        // ->where('subtitute_schedule.date', '>=', Carbon::now()->format('Y-m-d'))
        // ->groupBy('class.name', 'subject.name', 'day.name', 'subtitute_schedule.date');

        // $mergeTable = $table1->union($table2);
        // return DB::table(DB::raw("({$mergeTable->toSql()}) as mt order by mt.day desc, mt.hour asc"))->mergeBindings($mergeTable)->get();
    }

    public function scheduleWithIdRoomEmpty($room_id) {
        return DB::table('schedule')
        ->leftJoin('day', 'schedule.day', '=', 'day.id')
        ->leftJoin('hour', 'schedule.hour', '=', 'hour.id')
        ->leftJoin('class', 'schedule.id_class', '=', 'class.id')
        ->leftJoin('subject', 'schedule.subject', '=', 'subject.id')
        ->leftJoin('users as a1', 'schedule.id_user', '=', 'a1.id')
        ->leftJoin('users as a2', 'schedule.id_user2', '=', 'a2.id')
        ->leftJoin('room', 'schedule.id_room', '=', 'room.id')
        ->select('schedule.id as id', 'class.name as class', 'subject.name as subject', 'day.name as day', 'hour.name as hour', 'schedule.date as date')
        ->where('room.id', $room_id)
        ->whereNull('schedule.id_class')
        ->orderBy('schedule.id')
        ->get();
    }

    public function scheduleAll() {
        return DB::table('schedule')
        ->join('class', 'schedule.id_class', '=', 'class.id')
        ->join('subject', 'schedule.subject', '=', 'subject.id')
        ->join('room', 'schedule.id_room', '=', 'room.id')
        ->select('schedule.id', 'class.name as class', 'subject.name as subject', 'room.name as room')
        ->get();
    }

    public function scheduleSubtituteMonthly($month, $year) {
        return DB::table('subtitute_schedule')
        ->join('class', 'subtitute_schedule.id_class', '=', 'class.id')
        ->join('day', 'subtitute_schedule.day', '=', 'day.id')
        ->join('subject', 'subtitute_schedule.subject', '=', 'subject.id')
        ->join('room', 'subtitute_schedule.id_room', '=', 'room.id')
        ->select('subtitute_schedule.id', 'class.name as class', 'subject.name as subject', 'room.name as room', 'day.name as day', 'subtitute_schedule.hour as hour', 'subtitute_schedule.date')
        ->whereMonth('subtitute_schedule.date', $month)
        ->whereYear('subtitute_schedule.date', $year)
        ->orderBy('subtitute_schedule.date', 'asc')
        ->get();
    }

    public function scheduleRoomEmpty($room_id) {
        $day = DB::table('subtitute_schedule')
        ->select('day')
        ->where('id_room', '=', $room_id)
        ->where('date', '>=', Carbon::now()->format('Y-m-d'))
        ->get();
            
        $id_room = DB::table('subtitute_schedule')
        ->select('id_room')
        ->where('id_room', '=', $room_id)
        ->where('date', '>=', Carbon::now()->format('Y-m-d'))
        ->get();
            
        $hour = DB::table('subtitute_schedule')
        ->select('hour')
        ->where('id_room', '=', $room_id)
        ->where('date', '>=', Carbon::now()->format('Y-m-d'))
        ->get();

        $table1 =  DB::table('schedule')
        ->leftJoin('day', 'schedule.day', '=', 'day.id')
        ->leftJoin('hour', 'schedule.hour', '=', 'hour.id')
        ->leftJoin('class', 'schedule.id_class', '=', 'class.id')
        ->leftJoin('subject', 'schedule.subject', '=', 'subject.id')
        ->selectRaw('group_concat(schedule.id order by schedule.id) as id, class.name as class, subject.name as subject, day.name as day, group_concat(hour.name order by hour.id) as hour')
        ->where('id_room', $room_id)
        ->whereNotIn('id_room', $id_room[])
        ->whereNotIn('day', $day[])
        ->whereNotIn('hour', $hour[])
        ->whereNull('schedule.id_class')
        
        ->groupBy('class.name', 'subject.name', 'day.name')
        ->get();
        return $table2;
    }

    protected function scheduleDetail($id) {
        return DB::table('schedule')
        ->leftJoin('day', 'schedule.day', '=', 'day.id')
        ->leftJoin('hour', 'schedule.hour', '=', 'hour.id')
        ->leftJoin('class', 'schedule.id_class', '=', 'class.id')
        ->leftJoin('users as a1', 'schedule.id_user', '=', 'a1.id')
        ->leftJoin('users as a2', 'schedule.id_user2', '=', 'a2.id')
        ->leftJoin('subject', 'schedule.subject', '=', 'subject.id')
        ->leftJoin('room', 'schedule.id_room', '=', 'room.id')
        ->select('schedule.id', 'day.name as day', 'hour.name as hour',
        'class.name as class', 'a1.name as lecture', 'a2.name as lecture2',
        'subject.name as subject', 'schedule.id_room', 'room.name as room', 'schedule.day as id_day',
        'schedule.id_class', 'schedule.id_user', 'schedule.id_user2', 'subject.code as subjectCode',
        'schedule.id_room', 'schedule.date')
        ->where('schedule.id', '=', $id)
        ->first();
    }

    public function scheduleSubstituteDetail($id) {
        return DB::table('subtitute_schedule')
        ->leftJoin('day', 'subtitute_schedule.day', '=', 'day.id')
        ->leftJoin('class', 'subtitute_schedule.id_class', '=', 'class.id')
        ->leftJoin('users as a1', 'subtitute_schedule.id_user', '=', 'a1.id')
        ->leftJoin('users as a2', 'subtitute_schedule.id_user2', '=', 'a2.id')
        ->leftJoin('subject', 'subtitute_schedule.subject', '=', 'subject.id')
        ->leftJoin('room', 'subtitute_schedule.id_room', '=', 'room.id')
        ->select('subtitute_schedule.id', 'day.name as day', 'subtitute_schedule.hour as hour',
        'class.name as class', 'a1.name as lecture', 'a2.name as lecture2',
        'subject.name as subject', 'subtitute_schedule.id_room', 'room.name as room', 'subtitute_schedule.day as id_day',
        'subtitute_schedule.id_class', 'subtitute_schedule.id_user', 'subtitute_schedule.id_user2', 'subject.code as subjectCode',
        'subtitute_schedule.id_room', 'subtitute_schedule.date')
        ->where('subtitute_schedule.id', '=', $id)
        ->first();
    }

    public function scheduleIdGroup($day, $class, $user, $user2, $subject, $room) {
        return DB::table('schedule')
        ->select('schedule.id')
        ->where('schedule.day', '=', $day)
        ->where('schedule.id_class', '=', $class)
        ->where('schedule.id_user', '=', $user)
        ->where('schedule.id_user2', '=', $user2)
        ->where('schedule.subject', '=', $subject)
        ->where('schedule.id_room', '=', $room)
        ->get();
    }

    public function searchIdSchedule($day, $hour, $room){
        return DB::table('schedule')
        ->select('schedule.id')
        ->where('schedule.day', '=', $day)
        ->where('schedule.hour', '=', $hour)
        ->where('schedule.id_room', '=', $room)
        ->first();
    }

    public function checkScheduleEmpty($room, $day, $hour) {
        $idRoom = $this->convertRoomtoIdFromDatabase($room);
        $idDay = $this->convertDaytoIdFromDatabase($day);
        // $idHour = $this->convertHourtoIdFromDatabase($hour);
        $schedule =  DB::table('schedule')
        ->select('id')
        ->where('id_room', '=', $idRoom)
        ->where('day', '=', $idDay)
        ->where('hour', '=', $hour)
        ->whereNull('id_class')
        ->first();

        if ($schedule) {
            return 1;
        } else {
            return 0;
        }
    }

    public function checkLectureEmpty($day, $hour, $id_user) {
        $idDay = $this->convertDaytoIdFromDatabase($day);
        $schedule =  DB::table('schedule')
        ->select('id')
        ->where('day', '=', $idDay)
        ->where('hour', '=', $hour)
        ->where('id_user', '=', $id_user)
        ->first();

        if (!$schedule) {
            return 1;
        } else {
            return 0;
        }
    }

    public function checkClassEmpty($day, $hour, $id_class) {
        $idDay = $this->convertDaytoIdFromDatabase($day);
        $schedule =  DB::table('schedule')
        ->select('id')
        ->where('day', '=', $idDay)
        ->where('hour', '=', $hour)
        ->where('id_class', '=', $id_class)
        ->first();

        if (!$schedule) {
            return 1;
        } else {
            return 0;
        }
    }

    public function checkSubtituteScheduleEmpty($room, $day, $hour) {
        $idRoom = $this->convertRoomtoIdFromDatabase($room);
        $idDay = $this->convertDaytoIdFromDatabase($day);
        // $idHour = $this->convertHourtoIdFromDatabase($hour);
        $schedule = DB::table('subtitute_schedule')
        ->select('id')
        ->where('id_room', '=', $idRoom)
        ->where('day', '=', $idDay)
        ->where('hour', '=', $hour)
        ->where('date', '>', Carbon::now())
        ->first();

        
        if (!$schedule) {
            return 1;
        } else {
            return 0;
        }
    }

    public function translateDate($day)
    {
        if ($day == "Monday") {
            return "Senin";
        } else if ($day == "Tuesday") {           
            return "Selasa";
        } else if ($day == "Wednesday") {
            return "Rabu";
        } else if ($day == "Thursday") {
            return "Kamis";
        } else if ($day == "Friday") {
            return "Jumat";
        } else if ($day == "Saturday") {
            return "Sabtu";
        }
    }

    public function listScheduleEmpty($room_id){
        $schedule = $this->scheduleRoomEmpty($room_id);
        
        return response()->json([
            'success' => true,
            'message' => 'List Semua Jadwal kosong',
            'data'    => $schedule
        ], 200);
    }

    // public function test() {
    //     $now = Carbon::now();
    //     $schedule = DB::table('schedule')
    //     ->select('day', 'hour', 'id_class', 'id_user', 'id_user2', 'subject', 'id_room', 'date', 'id_regular')
    //     ->where('date', '<', $now)
    //     ->get();

    //     if ($schedule) {
    //         foreach ($schedule as $row) {
    //             $subtitute_schedule = SubtituteScheduleModel::create([
    //             'day' => $row->day,
    //             'hour' => $row->hour,
    //             'id_class' => $row->id_class,
    //             'id_user' => $row->id_user,
    //             'id_user2' => $row->id_user2,
    //             'subject' => $row->subject,
    //             'id_room' => $row->id_room,
    //             'date' => $row->date,
    //             'id_schedule' => $row->id_regular
    //         ]);
    //         }

    //         $delete_old = DB::table('schedule')
    //             ->where('date', '<', $now)->delete();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'List Pengganti aktif',
    //             'data'    => $schedule
    //         ], 200);
    //     } else {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'List Pengganti aktif tidak ditemukan'
    //         ], 401);
    //     }
    // }

    public function index()
    {
        $schedule = $this->scheduleAll();

        return response()->json([
            'success' => true,
            'message' => 'List Semua Jadwal',
            'data'    => $schedule
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'schedule' => 'mimes:xml|max:10240'
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'File tidak sesuai',
                'data'   => $validator->errors()
            ], 401);
        } else {

            $schedule = $this->saveFile($request, "schedule");

            if ($schedule) {
                $this->parse($schedule);
                return response()->json([
                    'success' => true,
                    'message' => 'Jadwal Berhasil Disimpan!',
                    'data' => $schedule
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal Gagal Disimpan!',
                ], 400);
            }
        }
    }

    public function isLectureEmpty($day, $hour, $id_user) {
        $schedule = $this->checkLectureEmpty($day, $hour, $id_user);
        
        if ($schedule == 1) {
            return 1;
        } else {
            return 0;
        }
    }

    public function isClassEmpty($day, $hour, $id_class) {
        $schedule = $this->checkClassEmpty($day, $hour, $id_class);
        
        if ($schedule == 1) {
            return 1;
        } else {
            return 0;
        }
    }

    public function isScheduleEmpty($room, $day, $hour) {
        $schedule = $this->checkScheduleEmpty($room, $day, $hour);
        // $subtituteSchedule = $this->checkSubtituteScheduleEmpty($room, $day, $hour);
        
        if ($schedule == 1) {
            return 1;
        } else {
            return 0;
        }

            // if ($schedule == 1 and $subtituteSchedule == 1) {
            //     return 1;
            // } else {
            //     return 0;
            // }

        // if ($schedule == 1 or $subtituteSchedule == 1 ) {
        //     if ($schedule == 1 and $subtituteSchedule == 0) {
        //         return 1;
        //     } else if ($schedule == 0 and $subtituteSchedule == 1) {
        //         return 1;
        //     } else {
        //         return 0;
        //     }
        // } else {
        //     return 0;
        // }
    }

    public function rescheduleStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'day'   => 'required',
            'hour'   => 'required',
            'id_class'   => 'required',
            'id_user'   => 'required',
            'subject'   => 'required',
            'id_room'   => 'required',
            'old_id' => 'required'
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Semua Kolom Wajib Diisi!',
                'data'   => $validator->errors()
            ], 401);
        } else {
            $day = $this->convertDaytoIdFromDatabase($request->input('day'));
            $class = $this->convertClassToIdFromDatabase($request->input('id_class'));
            $subject = $this->convertSubjecttoIdFromDatabase($request->input('subject'));
            $splitRoom = explode(" ", $request->input('id_room'));
            $room = $this->convertRoomtoIdFromDatabase($splitRoom[0]);
            $lecture1 = $this->convertUsertoIdFromDatabase($request->input('id_user'));
            $lecture2 = $this->convertUsertoIdFromDatabase($request->input('id_user2'));
            $class = $this->convertClassToIdFromDatabase($request->input('id_class'));
            $hours = explode(",", $request->input('hour'));
            $firstHour = $this->convertHourtoIdFromDatabase($hours[0]);
            $endHour = $firstHour+$hours[1];
            $scheduleEmpty = [];
            $lectureEmpty = [];
            $lecture2Empty = [];
            $classEmpty = [];
            for ($i=$firstHour; $i < $endHour ; $i++) { 
                echo $request->input('day').$request->input('day').$i;
                $scheduleEmpty[] = $this->isScheduleEmpty($splitRoom[0], $request->input('day'), $i);
                $lectureEmpty[] = $this->isLectureEmpty($request->input('day'), $i, $lecture1);
                $lecture2Empty[] = $this->isLectureEmpty($request->input('day'), $i, $lecture1);
                $classEmpty[] = $this->isClassEmpty($request->input('day'), $i, $class);
            }
            
            if (sizeof($scheduleEmpty) == 3) {
                echo $scheduleEmpty[0].$scheduleEmpty[1].$scheduleEmpty[2];
                if ($scheduleEmpty[0] == 1 and $scheduleEmpty[1] == 1 and $scheduleEmpty[2] == 1) {
                    if ($classEmpty[0] == 1 and $classEmpty[1] == 1 and $classEmpty[2] == 1) {
                        if ($lectureEmpty[0] == 1 and $lectureEmpty[1] == 1 and $lectureEmpty[2] == 1) {
                            if ($lecture2Empty[0] == 1 and $lecture2Empty[1] == 1 and $lecture2Empty[2] == 1) {
                                for ($i=$firstHour; $i < $endHour ; $i++) { 
                                    $idSchedule = $this->searchIdSchedule($day, $i, $room);
                                    $schedule = ScheduleModel::where('id', $idSchedule->id)->first();
                                    if ($schedule) {
                                        $schedule -> id_class = $class;
                                        $schedule -> id_user = $lecture1;
                                        $schedule -> id_user2 = $lecture2;
                                        $schedule -> subject = $subject;

                                        $schedule -> save();
                                    }
                                }
                                if ($schedule) {
                                    $this->updateGroup($request->input('old_id'));
                                    return response()->json([
                                        'success' => true,
                                        'message' => 'Penjadwalan Ulang Berhasil Disimpan!'
                                    ], 201);
                                } else {
                                    return response()->json([
                                            'success' => false,
                                        'message' => 'Penjadwalan ulang Gagal Disimpan!',
                                    ], 400);
                                }
                            } else {
                                return response()->json([
                                    'success' => false,
                                'message' => 'Dosen 2 memiliki jadwal lain!',
                            ], 400);
                            }
                        } else {
                            return response()->json([
                                'success' => false,
                            'message' => 'Dosen 1 memiliki jadwal lain!',
                        ], 400);
                        }
                    } else {
                        return response()->json([
                            'success' => false,
                        'message' => 'Kelas memiliki jadwal lain!',
                    ], 400);
                    }
                } else {
                    return response()->json([
                        'success' => false,
                    'message' => 'Ruangan tidak kosong!',
                ], 400);
                }
            } else if (sizeof($scheduleEmpty) == 4) {
                echo $scheduleEmpty[0].$scheduleEmpty[1].$scheduleEmpty[2].$scheduleEmpty[3];
                if ($scheduleEmpty [0] == 1 and $scheduleEmpty [1] == 1 and $scheduleEmpty [2] == 1 and $scheduleEmpty [3] == 1) {
                    if ($classEmpty[0] == 1 and $classEmpty[1] == 1 and $classEmpty[2] == 1 and $classEmpty[3] == 1) {
                        if ($lectureEmpty[0] == 1 and $lectureEmpty[1] == 1 and $lectureEmpty[2] == 1 and $lectureEmpty[3] == 1) {
                            if ($lecture2Empty[0] == 1 and $lecture2Empty[1] == 1 and $lecture2Empty[2] == 1 and $lecture2Empty[3] == 1) {
                                for ($i=$firstHour; $i < $endHour ; $i++) { 
                                    $idSchedule = $this->searchIdSchedule($day, $i, $room);
                                    $schedule = ScheduleModel::where('id', $idSchedule->id)->first();
                                    if ($schedule) {
                                        $schedule -> id_class = $class;
                                        $schedule -> id_user = $lecture1;
                                        $schedule -> id_user2 = $lecture2;
                                        $schedule -> subject = $subject;

                                        $schedule -> save();
                                    }
                                }
                                if ($reschedule) {
                                    $this->updateGroup($request->input('old_id'));
                                    return response()->json([
                                        'success' => true,
                                        'message' => 'Penjadwalan Ulang Berhasil Disimpan!'
                                    ], 201);
                                } else {
                                    return response()->json([
                                            'success' => false,
                                        'message' => 'Penjadwalan ulang Gagal Disimpan!',
                                    ], 400);
                                }
                            } else {
                                return response()->json([
                                    'success' => false,
                                'message' => 'Dosen 2 memiliki jadwal lain!',
                            ], 400);
                            }
                        } else {
                            return response()->json([
                                'success' => false,
                            'message' => 'Dosen 1 memiliki jadwal lain!',
                        ], 400);
                        }
                    } else {
                        return response()->json([
                            'success' => false,
                        'message' => 'Kelas memiliki jadwal lain!',
                    ], 400);
                    }
                } else {
                    return response()->json([
                        'success' => false,
                    'message' => 'Ruangan tidak kosong!',
                ], 400);
                }
            }


            // if (sizeof($hours) == 3) {

                // $scheduleEmpty = $this->isScheduleEmpty($request->input('id_room'), $request->input('day'), $hours[0]);
            //     $scheduleEmpty2 = $this->isScheduleEmpty($request->input('id_room'), $request->input('day'), $hours[1]);
            //     $scheduleEmpty3 = $this->isScheduleEmpty($request->input('id_room'), $request->input('day'), $hours[2]);
                
            //     if ($scheduleEmpty == 1 and $scheduleEmpty2 == 1 and $scheduleEmpty3 == 1)  {
            //         foreach ($hours as $row) {
            //             $reschedule = ScheduleModel::create([
            //                 'day'     => $this->convertDaytoIdFromDatabase($request->input('day')),
            //                 'hour'     => $this->convertHourtoIdFromDatabase($row),
            //                 'id_class'     => $this->convertClassToIdFromDatabase($request->input('id_class')),
            //                 'id_user'     => $this->convertUsertoIdFromDatabase($request->input('id_user')),
            //                 'id_user2'     => $this->convertUsertoIdFromDatabase($request->input('id_user2')),
            //                 'subject'     => $this->convertSubjecttoIdFromDatabase($request->input('subject')),
            //                 'id_room'     => $this->convertRoomtoIdFromDatabase($request->input('id_room'))
            //             ]);
            //         }
                    // if ($reschedule) {
                    //     $this->deleteGroup($request->input('old_id'));
                    //     return response()->json([
                    //         'success' => true,
                    //         'message' => 'Penjadwalan Ulang Berhasil Disimpan!'
                    //     ], 201);
                    // } else {
                    //     return response()->json([
                    //             'success' => false,
                    //         'message' => 'Penjadwalan ulang Gagal Disimpan!',
                    //     ], 401);
                    // }
            //     } else {
            //         return response()->json([
            //             'success' => false,
            //         'message' => 'Penjadwalan ulang Gagal Disimpan!',
            //     ], 401);
            //     }
            // } else {

            //     $scheduleEmpty = $this->isScheduleEmpty($request->input('id_room'), $request->input('day'), $hours[0]);
            //     $scheduleEmpty2 = $this->isScheduleEmpty($request->input('id_room'), $request->input('day'), $hours[1]);
            //     $scheduleEmpty3 = $this->isScheduleEmpty($request->input('id_room'), $request->input('day'), $hours[2]);
            //     $scheduleEmpty4 = $this->isScheduleEmpty($request->input('id_room'), $request->input('day'), $hours[3]);

            //     if ($scheduleEmpty == 1 and $scheduleEmpty2 == 1 and $scheduleEmpty3 == 1 and $scheduleEmpty4 == 1)  {
            //         foreach ($hours as $row) {
            //             $reschedule = ScheduleModel::create([
            //                 'day'     => $this->convertDaytoIdFromDatabase($request->input('day')),
            //                 'hour'     => $this->convertHourtoIdFromDatabase($row),
            //                 'id_class'     => $this->convertClassToIdFromDatabase($request->input('id_class')),
            //                 'id_user'     => $this->convertUsertoIdFromDatabase($request->input('id_user')),
            //                 'id_user2'     => $this->convertUsertoIdFromDatabase($request->input('id_user2')),
            //                 'subject'     => $this->convertSubjecttoIdFromDatabase($request->input('subject')),
            //                 'id_room'     => $this->convertRoomtoIdFromDatabase($request->input('id_room'))
            //             ]);
            //         }
            //         if ($reschedule) {
            //                 $this->deleteGroup($request->input('old_id'));
            //                 return response()->json([
            //                     'success' => true,
            //                     'message' => 'Penjadwalan Ulang Berhasil Disimpan!'
            //              ], 201);
            //         } else {
            //                 return response()->json([
            //                     'success' => false,
            //                     'message' => 'Penjadwalan ulang Gagal Disimpan!',
            //              ], 401);
            //         }
            //     }else {
            //         return response()->json([
            //             'success' => false,
            //         'message' => 'Penjadwalan ulang Gagal Disimpan!',
            //     ], 401); 
            //     }
            // }
        }
    }
    
    public function subtituteSchedule(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'hour'   => 'required',
            'id_class'   => 'required',
            'id_user'   => 'required',
            'subject'   => 'required',
            'id_room'   => 'required',
            'date' => 'required',
            // 'id_regular' => 'required'
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Semua Kolom Wajib Diisi!',
                'data'   => $validator->errors()
            ], 401);
        } else {

            $hours = explode(",", $request->input('hour'));
            $date = $request->input('date');
            $day = Carbon::createFromFormat('d-m-Y', $date)->format('l');
            $hari = $this->translateDate($day);
            
            $day = $this->convertDaytoIdFromDatabase($hari);
            $class = $this->convertClassToIdFromDatabase($request->input('id_class'));
            $subject = $this->convertSubjecttoIdFromDatabase($request->input('subject'));
            $splitRoom = explode(" ", $request->input('id_room'));
            $room = $this->convertRoomtoIdFromDatabase($splitRoom[0]);
            $lecture1 = $this->convertUsertoIdFromDatabase($request->input('id_user'));
            $lecture2 = $this->convertUsertoIdFromDatabase($request->input('id_user2'));
            $class = $this->convertClassToIdFromDatabase($request->input('id_class'));
            $hours = explode(",", $request->input('hour'));
            $firstHour = $this->convertHourtoIdFromDatabase($hours[0]);
            $endHour = $firstHour+$hours[1]-1;
            $scheduleEmpty = [];
            $lectureEmpty = [];
            $lecture2Empty = [];
            $classEmpty = [];
            for ($i=$firstHour; $i <= $endHour ; $i++) { 
                $scheduleEmpty[] = $this->isScheduleEmpty($splitRoom[0], $hari, $i);
                $lectureEmpty[] = $this->isLectureEmpty($hari, $i, $lecture1);
                $lecture2Empty[] = $this->isLectureEmpty($hari, $i, $lecture1);
                $classEmpty[] = $this->isClassEmpty($hari, $i, $class);
            }

            if (sizeof($scheduleEmpty) == 3) {
                if ($scheduleEmpty [0] == 1 and $scheduleEmpty [1] == 1 and $scheduleEmpty [2] == 1) {
                    if ($classEmpty[0] == 1 and $classEmpty[1] == 1 and $classEmpty[2] == 1) {
                        if ($lectureEmpty[0] == 1 and $lectureEmpty[1] == 1 and $lectureEmpty[2] == 1) {
                            if ($lecture2Empty[0] == 1 and $lecture2Empty[1] == 1 and $lecture2Empty[2] == 1) {
                                $id_regular = [];
                                for ($i=$firstHour; $i <= $endHour ; $i++) { 
                                    $idSchedule = $this->searchIdSchedule($day, $i, $room);
                                    $id_regular[] = $idSchedule->id;
                                    $schedule = ScheduleModel::where('id', $idSchedule->id)->first();
                                    if ($schedule) {
                                        $schedule -> id_class = $class;
                                        $schedule -> id_user = $lecture1;
                                        $schedule -> id_user2 = $lecture2;
                                        $schedule -> subject = $subject;
                                        $schedule -> date = $date;
                                        $schedule -> save();
                                    }
                                }
                                $endHourText = $this->convertHourtoNameFromDatabase($endHour);
                                $firstHourSplit = substr($hours[0], 0, strpos($hours[0], '-'));
                                $endHourSplit = substr($endHourText, 6);
                                $hourGroup = $firstHourSplit.'-'.$endHourSplit;
                                $substitute = SubtituteScheduleModel::create([
                                    'day' => $day,
                                    'hour' => $hourGroup,
                                    'id_class' => $class,
                                    'id_user' => $lecture1,
                                    'id_user2'=> $lecture2,
                                    'subject' => $subject,
                                    'id_room' => $room,
                                    'date' => $date,
                                    'id_regular' => implode(",", $id_regular)
                                ]);

                                if ($schedule) {
                                    if ($substitute) {
                                        return response()->json([
                                            'success' => true,
                                            'message' => 'Jadwal Pengganti Berhasil Disimpan!'
                                        ], 201);
                                    } else {
                                        return response()->json([
                                            'success' => false,
                                        'message' => 'Jadwal Pengganti Gagal Dimasukkan ke tabel substitute!',
                                    ], 400);
                                    }
                                } else {
                                    return response()->json([
                                            'success' => false,
                                        'message' => 'Jadwal Pengganti Gagal Disimpan!',
                                    ], 400);
                                }
                            } else {
                                return response()->json([
                                    'success' => false,
                                'message' => 'Dosen 2 memiliki jadwal lain!',
                            ], 400);
                            }
                        } else {
                            return response()->json([
                                'success' => false,
                            'message' => 'Dosen 1 memiliki jadwal lain!',
                        ], 400);
                        }
                    } else {
                        return response()->json([
                            'success' => false,
                        'message' => 'Kelas memiliki jadwal lain!',
                    ], 400);
                    }
                } else {
                    return response()->json([
                        'success' => false,
                    'message' => 'Ruangan tidak kosong!'
                ], 400);
                }
            } else if (sizeof($scheduleEmpty) == 4) {
                if ($scheduleEmpty [0] == 1 and $scheduleEmpty [1] == 1 and $scheduleEmpty [2] == 1 and $scheduleEmpty [3] == 1) {
                    if ($classEmpty[0] == 1 and $classEmpty[1] == 1 and $classEmpty[2] == 1 and $classEmpty[3] == 1) {
                        if ($lectureEmpty[0] == 1 and $lectureEmpty[1] == 1 and $lectureEmpty[2] == 1 and $lectureEmpty[3] == 1) {
                            if ($lecture2Empty[0] == 1 and $lecture2Empty[1] == 1 and $lecture2Empty[2] == 1 and $lecture2Empty[3] == 1) {
                                for ($i=$firstHour; $i <= $endHour ; $i++) { 
                                    $idSchedule = $this->searchIdSchedule($day, $i, $room);
                                    $id_regular[] = $idSchedule->id;
                                    $schedule = ScheduleModel::where('id', $idSchedule->id)->first();
                                    if ($schedule) {
                                        $schedule -> id_class = $class;
                                        $schedule -> id_user = $lecture1;
                                        $schedule -> id_user2 = $lecture2;
                                        $schedule -> subject = $subject;
                                        $schedule -> date = $date;

                                        $schedule -> save();
                                    }
                                }

                                $endHourText = $this->convertHourtoNameFromDatabase($endHour);
                                $firstHourSplit = substr($hours[0], 0, strpos($hours[0], '-'));
                                $endHourSplit = substr($endHourText, 6);
                                $hourGroup = $firstHourSplit.'-'.$endHourSplit;
                                $substitute = SubtituteScheduleModel::create([
                                    'day' => $day,
                                    'hour' => $hourGroup,
                                    'id_class' => $class,
                                    'id_user' => $lecture1,
                                    'id_user2'=> $lecture2,
                                    'subject' => $subject,
                                    'id_room' => $room,
                                    'date' => $date,
                                    'id_regular' => implode(",", $id_regular)
                                ]);
                                if ($schedule) {
                                    return response()->json([
                                        'success' => true,
                                        'message' => 'Jadwal Pengganti Berhasil Disimpan!'
                                    ], 201);
                                } else {
                                    return response()->json([
                                            'success' => false,
                                        'message' => 'Jadwal Pengganti Gagal Disimpan!',
                                    ], 400);
                                }
                            } else {
                                return response()->json([
                                    'success' => false,
                                'message' => 'Dosen 2 memiliki jadwal lain!',
                            ], 400);
                            }
                        } else {
                            return response()->json([
                                'success' => false,
                            'message' => 'Dosen 1 memiliki jadwal lain!',
                        ], 400);
                        }
                    } else {
                        return response()->json([
                            'success' => false,
                        'message' => 'Kelas memiliki jadwal lain!',
                    ], 400);
                    }
                } else {
                    return response()->json([
                        'success' => false,
                    'message' => 'Ruangan tidak kosong!',
                ], 400);
                }
            }
        }
    }
    
    public function substituteDone($id) {
        $substitute = $this->updateGroup($id);

        if ($substitute) {
            return response()->json([
                'success'   => true,
                'message'   => 'Jadwal Pengganti dihapus'
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal Tidak Ditemukan!',
            ], 401);
        }
    }

    public function substituteCancel($id, $date) {
        $schedule = $this->updateGroup($id);
        $substitute = $this->deleteSubstitute($id, $date);

        if ($schedule) {
            return response()->json([
                'success'   => true,
                'message'   => 'Jadwal Pengganti dihapus'
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal Tidak Ditemukan!',
            ], 401);
        }
    }

    public function showroom($room_id) {
        $schedule = $this->scheduleWithIdRoom($room_id);

        if ($schedule) {
            return response()->json([
                'success'   => true,
                'message'   => 'Jadwal ruangan',
                'data'      => $schedule
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal Tidak Ditemukan!',
            ], 404);
        }
    }

    public function showEmptyRoom($room_id) {
        $schedule = $this->scheduleWithIdRoomEmpty($room_id);

        if ($schedule) {
            return response()->json([
                'success'   => true,
                'message'   => 'Jadwal ruangan',
                'data'      => $schedule
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal Tidak Ditemukan!',
            ], 404);
        }
    }

    public function parse($file)
    {
        $loadFile = simplexml_load_file('schedule/'.$file, "SimpleXMLElement", LIBXML_NOERROR |  LIBXML_ERR_NONE);
        
        if (!$loadFile) {
            return response()->json([
                'success' => false,
                'message' => 'Load File xml gagal!',
                'data' => $loadFile
            ], 401);
        } else {
            $this->createSchedule();
            foreach ($loadFile as $items) {
                $temp = $items['name'];
                $class = trim(str_replace('Grup Otomat', '', $temp));
                foreach ($items->children() as $days) {
                    $day = $days['name'];
                    foreach ($days->children() as $hours) {
                        $hour = $hours['name'];
                        $classId = $this->convertClassToIdFromDatabase($class);
                        $dayId = $this->convertDaytoIdFromDatabase($day);
                        $hourId = $this->convertHourtoIdFromDatabase($hour);
                        $item = [];
                        foreach ($hours->children() as $key => $items) {
                            $item[] = (string) $items['name'];
                        }
                        //input database
                        if (!empty($item)) {
                            if (count($item)>3) {
                                $lecture1 = $this->splitString($item[0]);
                                $lecture2 = $this->splitString($item[1]);
                                $subject = $this->splitString($item[2]);
                                $room = $this->splitString($item[3]);
                                $user1Id = $this->convertUsertoIdFromDatabase($lecture1);
                                $user2Id = $this->convertUsertoIdFromDatabase($lecture2);
                                $subjectId = $this->convertSubjecttoIdFromDatabase($subject);
                                $roomId = $this->convertRoomtoIdFromDatabase($room);

                                if (!empty($roomId)) {
                                $schedule = ScheduleModel::where('day', $dayId)
                                ->where('hour', $hourId)
                                ->where('id_room', $roomId)
                                ->first();
                            
                                $schedule -> id_class = $classId;
                                $schedule -> id_user = $user1Id;
                                if (!empty($user2Id)) {
                                    $schedule -> id_user2 = $user2Id;
                                }
                                $schedule -> subject = $subjectId;

                                $schedule -> save();
                                

                                    // $schedule = ScheduleModel::create([
                                    //     'id_class' => $classId,
                                    //     'day' => $dayId,
                                    //     'hour'=> $hourId,
                                    //     'id_user' => $user1Id,
                                    //     'id_user2' => $user2Id,
                                    //     'subject' => $subjectId,
                                    //     'id_room' => $roomId
                                    // ]);
                                }
                            } else {
                                $lecture1 = $this->splitString($item[0]);
                                $subject = $this->splitString($item[1]);
                                $room = $this->splitString($item[2]);
                                $user1Id = $this->convertUsertoIdFromDatabase($lecture1);
                                $subjectId = $this->convertSubjecttoIdFromDatabase($subject);
                                $roomId = $this->convertRoomtoIdFromDatabase($room);

                                if (!empty($roomId)) {
                                $schedule = ScheduleModel::where('day', $dayId)
                                ->where('hour', $hourId)
                                ->where('id_room', $roomId)
                                ->first();
                            
                                $schedule -> id_class = $classId;
                                $schedule -> id_user = $user1Id;
                                $schedule -> subject = $subjectId;

                                $schedule -> save();
                                

                                    // $schedule = ScheduleModel::create([
                                    //     'id_class' => $classId,
                                    //     'day' => $dayId,
                                    //     'hour'=> $hourId,
                                    //     'id_user' => $user1Id,
                                    //     'id_user2' => $user2Id,
                                    //     'subject' => $subjectId,
                                    //     'id_room' => $roomId
                                    // ]);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    // public function parse($file)
    // {
    //     $loadFile = simplexml_load_file('schedule/'.$file, "SimpleXMLElement", LIBXML_NOERROR |  LIBXML_ERR_NONE);
        
    //     if (!$loadFile) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Load File xml gagal!',
    //             'data' => $loadFile
    //         ], 401);
    //     } else {
    //         foreach ($loadFile as $items) {
    //             $temp = $items['name'];
    //             $class = trim(str_replace('Grup Otomat', '', $temp));
    //             foreach ($items->children() as $days) {
    //                 $day = $days['name'];
    //                 foreach ($days->children() as $hours) {
    //                     $hour = $hours['name'];
    //                     $classId = $this->convertClassToIdFromDatabase($class);
    //                     $dayId = $this->convertDaytoIdFromDatabase($day);
    //                     $hourId = $this->convertHourtoIdFromDatabase($hour);
    //                     $item = [];
    //                     foreach ($hours->children() as $key => $items) {
    //                         $item[] = (string) $items['name'];
    //                     }
    //                     //input database
    //                     if (!empty($item)) {
    //                         if (count($item)>3) {
    //                             $lecture1 = $this->splitString($item[0]);
    //                             $lecture2 = $this->splitString($item[1]);
    //                             $subject = $this->splitString($item[2]);
    //                             $room = $this->splitString($item[3]);
    //                             $user1Id = $this->convertUsertoIdFromDatabase($lecture1);
    //                             $user2Id = $this->convertUsertoIdFromDatabase($lecture2);
    //                             $subjectId = $this->convertSubjecttoIdFromDatabase($subject);
    //                             $roomId = $this->convertRoomtoIdFromDatabase($room);
    //                             if (!empty($roomId)) {
    //                                 $schedule = ScheduleModel::create([
    //                                     'id_class' => $classId,
    //                                     'day' => $dayId,
    //                                     'hour'=> $hourId,
    //                                     'id_user' => $user1Id,
    //                                     'id_user2' => $user2Id,
    //                                     'subject' => $subjectId,
    //                                     'id_room' => $roomId
    //                                 ]);
    //                             }
    //                         } 
    //                     }
    //                 }
    //             }
    //         }
    //     }
    // }

    public function showSubtituteSchedule($id) {
        $substituteSchedule = $this->scheduleSubstituteDetail($id);

        if ($substituteSchedule) {
            return response()->json([
                'success'   => true,
                'message'   => 'Detail Jadwal!',
                'data'      => $substituteSchedule
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal Tidak Ditemukan!',
            ], 404);
        }
    }

    public function showSubtituteCurrentMonth()
    {
        $now = Carbon::now();
        $month = date('m');
        $year = date('Y');
        $schedule = $this->scheduleSubtituteMonthly($month, $year);
        
        if (sizeof($schedule) != 0) {
            return response()->json([
                'success'   => true,
                'message'   => 'Pengganti Jadwal Bulanan!',
                'data'      => $schedule
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal Pengganti Tidak Ditemukan!',
            ], 404);
        }
    }

    public function showSubtitutePreviousMonth()
    {
        $now = Carbon::now();
        $month = date('m', strtotime(date('Y-m')." -1 month"));
        $year = date('Y', strtotime(date('Y-m')." -1 month"));
        $schedule = $this->scheduleSubtituteMonthly($month, $year);
        
        if (sizeof($schedule) != 0) {
            return response()->json([
                'success'   => true,
                'message'   => 'Pengganti Jadwal Bulanan!',
                'data'      => $schedule
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal Pengganti Tidak Ditemukan!',
            ], 404);
        }
    }

    public function showSubtituteTwoMonthAgo()
    {
        $now = Carbon::now();
        $month = date('m', strtotime(date('Y-m')." -2 month"));
        $year = date('Y', strtotime(date('Y-m')." -2 month"));
        $schedule = $this->scheduleSubtituteMonthly($month, $year);
        
        if (sizeof($schedule) != 0) {
            return response()->json([
                'success'   => true,
                'message'   => 'Pengganti Jadwal Bulanan!',
                'data'      => $schedule
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal Pengganti Tidak Ditemukan!',
            ], 404);
        }
    }

    public function showSubtituteMonthly($month, $year)
    {
        $schedule = $this->scheduleSubtituteMonthly($month, $year);
        
        if (sizeof($schedule) != 0) {
            return response()->json([
                'success'   => true,
                'message'   => 'Pengganti Jadwal Bulanan!',
                'data'      => $schedule
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal Pengganti Tidak Ditemukan!',
            ], 404);
        }
    }

    public function show($id)
    {
        $schedule = $this->scheduleDetail($id);

        if ($schedule) {
            return response()->json([
                'success'   => true,
                'message'   => 'Detail Jadwal!',
                'data'      => $schedule
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal Tidak Ditemukan!',
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'day'   => 'required',
            'hour' => 'required',
            'id_class' => 'required',
            'id_user' => 'required',
            'id_room' => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Semua Kolom Wajib Diisi!',
                'data'   => $validator->errors()
            ], 401);
        } else {

            $schedule = ScheduleModel::whereId($id)->update([
                'day'     => $request->input('day'),
                'hour'     => $request->input('hour'),
                'id_class'     => $request->input('id_class'),
                'id_user'     => $request->input('id_user'),
                'id_room'     => $request->input('id_room'),
            ]);

            if ($schedule) {
                return response()->json([
                    'success' => true,
                    'message' => 'Jadwal Berhasil Diupdate!',
                    'data' => $schedule
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Jadwal Gagal Diupdate!',
                ], 400);
            }
        }
    }

    public function destroy($id)
    {
        $schedule = ScheduleModel::whereId($id)->first();
        $schedule->delete();

        if ($schedule) {
            return response()->json([
                'success' => true,
                'message' => 'Jadwal Berhasil Dihapus!',
            ], 200);
        }
    }

    public function destroySubtitute($id)
    {
        try {
            $ids = explode(",", $id);
            $schedule = SubtituteScheduleModel::whereIn('id', $ids)->delete();
            if ($schedule) {
                return response()->json([
                    'success' => true,
                    'message' => 'Jadwal Berhasil Dihapus!',
                ], 200);
            }
        }catch (Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th
            ], 401);
        }
    }
    
    public function deleteSubstitute($id, $date)
    {
            $schedule = SubtituteScheduleModel::where('id_regular', $id)->where('date', $date)->delete();
            if ($schedule) {
                return response()->json([
                    'success' => true,
                    'message' => 'Jadwal Berhasil Dihapus!',
                ], 200);
            }
            return response()->json([
                'success' => false,
                'message' => $th
            ], 401);
    }

    public function updateGroup($id) {
        try {
            $ids = explode(",", $id);
            $schedule = ScheduleModel::whereIn('id', $ids)->update([
                'id_class' => null,
                'id_user'  => null,
                'id_user2' => null,
                'subject'  => null,
                'date'     => null
            ]);
            if ($schedule) {
                return response()->json([
                    'success' => true,
                    'message' => 'Jadwal Berhasil Dikosongkan!',
                ], 200);
            }
        } catch (Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th
            ], 401);
        }
    }

    protected function convertClassToIdFromDatabase($class) {
        $query = DB::table('class')
        ->select('class.id')
        ->where('class.name', $class)->first();
        if (!empty($query)) {
            return $query->id;
        } else {
            return null;
        }
    }

    protected function convertDaytoIdFromDatabase($day) {
        $query = DB::table('day')
        ->select('day.id')
        ->where('day.name', $day)->first();
        if (!empty($query)) {
            return $query->id;
        } else {
            return null;
        }
    }

    protected function convertHourtoIdFromDatabase($hour) {
        $query = DB::table('hour')
        ->select('hour.id')
        ->where('hour.name', $hour)->first();
        if (!empty($query)) {
            return $query->id;
        } else {
            return null;
        }
    }

    protected function convertHourtoNameFromDatabase($hour) {
        $query = DB::table('hour')
        ->select('hour.name')
        ->where('hour.id', $hour)->first();
        if (!empty($query)) {
            return $query->name;
        } else {
            return null;
        }
    }

    protected function convertRoomtoIdFromDatabase($room) {
        $query = DB::table('room')
        ->select('room.id')
        ->where('room.code', $room)->first();
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

    protected function convertSubjecttoIdFromDatabase($subject) {
        $query = DB::table('subject')
        ->select('subject.id')
        ->where('subject.code', $subject)->first();
        if (!empty($query)) {
            return $query->id;
        } else {
            return null;
        }
    }

    protected function saveFile(Request $request, string $file) {
        $fileStore = "";
        if ($files = $request->file($file)) {
            $extension = $request->file($file)->getClientOriginalExtension();
            $name = Str::random(10);
            $fileStore = $name . '.' . $extension;
            $files->move('schedule', $fileStore);
        } else {
            $fileStore = null;
        }
        return $fileStore;
    }

    protected function splitString(string $input) {
        $result = "";
        if (strpos($input, '-') != false) {
            $result = substr($input, 0, strpos($input, '-'));
        } else {
            $result = $input;
        }
        return $result;
    }

    public function reportCurrentMonth() {
        $now = Carbon::now();
        $month = date('m');
        $year = date('Y');
        $schedule = $this->scheduleSubtituteMonthly($month, $year);

        if (sizeof($schedule) != 0) {
            return view('schedule_monthly_report', ['schedule' => $schedule]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Laporan tidak tersedia!',
            ], 404);
        }
    }

    public function reportPreviousMonth() {
        $now = Carbon::now();
        $month = date('m', strtotime(date('Y-m')." -1 month"));
        $year = date('Y', strtotime(date('Y-m')." -1 month"));
        $schedule = $this->scheduleSubtituteMonthly($month, $year);

        if (sizeof($schedule) != 0) {
            return view('schedule_monthly_report', ['schedule' => $schedule]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Laporan tidak tersedia!',
            ], 404);
        }
    }

    public function reportTwoMonthAgo() {
        $now = Carbon::now();
        $month = date('m', strtotime(date('Y-m')." -2 month"));
        $year = date('Y', strtotime(date('Y-m')." -2 month"));
        $schedule = $this->scheduleSubtituteMonthly($month, $year);
        
        if (sizeof($schedule) != 0) {
            return view('schedule_monthly_report', ['schedule' => $schedule]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Laporan tidak tersedia!',
            ], 404);
        }
    }

    public function reportMonthByUser($month, $year) {
        $schedule = $this->scheduleSubtituteMonthly($month, $year);

        if (sizeof($schedule) != 0) {
            return view('schedule_monthly_report', ['schedule' => $schedule]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Laporan tidak tersedia!',
            ], 404);
        }
    }

    public function reportdetail($id) {
        // $ids = explode(",", $id);
        // $schedule = [];
        // foreach ($ids as $row) {    
        //     $schedule[] = $this->scheduleSubstituteDetail($row);
        // }
        $schedule = $this->scheduleSubstituteDetail($id);
        if ($schedule) {
            return view('schedule_detail_report', ['schedule' => $schedule]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Laporan tidak tersedia!',
            ], 404);
        }
    }

    public function createSchedule(){
        ScheduleModel::truncate();
        for ($day = 1; $day <= 6; $day++) {
            for ($room=1; $room <=20 ; $room++) { 
                for ($hour=1; $hour <=10 ; $hour++) { 
                    ScheduleModel::create([
                        'day' => $day,
                        'hour'=> $hour,
                        'id_room' => $room
                    ]);
                }
            }
        }
    }

    public function getEmptyRoom($roomId) {
        $schedule =  DB::table('schedule')
        ->join('day', 'schedule.day', '=', 'day.id')
        ->join('hour', 'schedule.hour', '=', 'hour.id')
        ->join('room', 'schedule.id_room', '=', 'room.id')
        ->selectRaw('group_concat(schedule.id order by schedule.id) as id, day.name as day, group_concat(hour.name order by hour.id) as hour')
        ->where('schedule.id_room', $roomId)
        ->WhereNull('schedule.id_class')
        ->orderBy('day.id')
        ->groupBy('day.name')
        ->get();

        if (sizeof($schedule) != 0) {
            return response()->json([
                'success'   => true,
                'message'   => 'Ruangan kosong tersedia!',
                'data'      => $schedule
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Ruangan kosong tidak tersedia!',
            ], 404);
        }
    }

    public function checkScheduleBoyerMoore($room, $hour, $long, $class, $user1, $user2) {
        $startTime = $this->convertHourtoIdFromDatabase($hour);
        $endTime = $startTime + $long;
        // echo $startTime.' and '.$endTime;

        $schedule =  DB::table('schedule')
        ->leftJoin('day', 'schedule.day', '=', 'day.id')
        ->leftJoin('hour', 'schedule.hour', '=', 'hour.id')
        ->leftJoin('room', 'schedule.id_room', '=', 'room.id')
        ->select('schedule.id', 'day.name as day', 'hour.name as hour', 'room.code as room')
        ->where('schedule.id_class', '=', '')
        ->orWhereNull('schedule.id_class')
        ->get();

        // if ($schedule) {
        //     return response()->json([
        //         'success' => true,
        //         'message' => 'Jadwal Berhasil Diupdate!',
        //         'data' => $schedule
        //     ], 201);
        // } else {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Jadwal Gagal Diupdate!',
        //     ], 400);
        // }
            foreach ($schedule as $row) {
                for ($i=$startTime; $i <$endTime ; $i++) { 
                $time = $this->convertHourtoNameFromDatabase($i);

                $resultRoom = $this->boyerMoore($row->room, $room);
                $resultHour = $this->boyerMoore($row->hour, $time);
                
                    // echo 'Room: '.$room.' with '.$row->room.' is '.$resultRoom.', Hour: '.$time.' with '.$row->hour.' is '.$resultHour.'</br>';
                    if ($resultRoom >= 0 and $resultHour >= 0) {
                    
                        $scheduleLecture = DB::table('schedule')
                        ->leftJoin('users', 'schedule.id_user', 'users.id')
                        ->select('schedule.id')
                        ->where('users.id', $user1)
                        ->where('schedule.day', $row->day)
                        ->where('schedule.hour', $row->hour)
                        ->first();
                        
                        $scheduleLecture2 = DB::table('schedule')
                        ->leftJoin('users', 'schedule.id_user', 'users.id')
                        ->select('schedule.id')
                        ->where('users.id', $user2)
                        ->where('schedule.day', $row->day)
                        ->where('schedule.hour', $row->hour)
                        ->first();

                        $scheduleClass = DB::table('schedule')
                        ->leftJoin('class', 'schedule.id_class', 'class.id')
                        ->select('schedule.id')
                        ->where('class.name', $class)
                        ->where('schedule.day', $row->day)
                        ->where('schedule.hour', $row->hour)
                        ->first();

                        // echo $scheduleLecture.', '.$scheduleLecture2.', '.$scheduleClass;
                        if (empty($scheduleClass)) {
                            echo 'day: '.$row->day.', '.'hour: '.$row->hour.', '.$row->room.'</br>';
                        } else {
                            echo 'kok';
                        }
                    } 
                }
            }
    }

     
    /**
    * Returns the index of the first occurrence of the
    * specified substring. If it's not found return -1.
    *
    * @param text The string to be scanned
    * @param pattern The target string to search
    * @return The start index of the substring
    */

    public function boyerMoore($text, $pattern) {
        $patlen = strlen($pattern);
        $textlen = strlen($text);
        $table = $this->makeCharTable($pattern);
    
        for ($i=$patlen-1; $i < $textlen;) {
            $t = $i;
            for ($j=$patlen-1; $pattern[$j]==$text[$i]; $j--,$i--) {
                if($j == 0) return $i;
            }
            $i = $t;
            if(array_key_exists($text[$i], $table))
                $i = $i + max($table[$text[$i]], 1);
            else
                $i += $patlen;
        }
        return -1;
    }

    function makeCharTable($string) {
        $len = strlen($string);
        $table = array();
        for ($i=0; $i < $len; $i++) {
            $table[$string[$i]] = $len - $i - 1;
        }
        return $table;
    } 
 
}
