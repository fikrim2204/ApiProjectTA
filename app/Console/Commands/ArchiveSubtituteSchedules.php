<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\SubtituteScheduleModel;

class ArchiveSubtituteSchedules extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:archive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Move data from table schedule to table subtitute_schedule when it done';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $now = Carbon::now();
        $schedule = DB::table('schedule')
        ->select('day', 'hour', 'id_class', 'id_user', 'id_user2', 'subject', 'id_room', 'date', 'id_regular')
        ->where('date', '<', $now)
        ->get();

        if ($schedule) {
            foreach ($schedule as $row) {
                $subtitute_schedule = SubtituteScheduleModel::create([
                'day' => $row->day,
                'hour' => $row->hour,
                'id_class' => $row->id_class,
                'id_user' => $row->id_user,
                'id_user2' => $row->id_user2,
                'subject' => $row->subject,
                'id_room' => $row->id_room,
                'date' => $row->date,
                'id_schedule' => $row->id_regular
            ]);
            }

            $delete_old = DB::table('schedule')
                ->where('date', '<', $now)->delete();   
        }
    }
}
