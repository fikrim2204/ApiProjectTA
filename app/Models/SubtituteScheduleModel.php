<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubtituteScheduleModel extends Model
{
    /**
     * @var string
     */
    protected $table = 'subtitute_schedule';

    /**
     * @var array
     */
    protected $fillable = [
        'day',
        'hour',
        'id_class',
        'id_user',
        'id_user2',
        'subject',
        'id_room',
        'date',
        'id_regular'
    ];
}
