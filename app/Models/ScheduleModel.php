<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleModel extends Model
{
    /**
     * @var string
     */
    protected $table = 'schedule';

    /**
     * @var array
     */
    protected $fillable = [
        'id_class',
        'day',
        'hour',
        'id_user',
        'id_user2',
        'subject',
        'id_room',
        'date',
        'id_regular'
    ];
}
