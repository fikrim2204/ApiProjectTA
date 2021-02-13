<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchedulleModel extends Model
{
    /**
     * @var string
     */
    protected $table = 'schedulle';

    /**
     * @var array
     */
    protected $fillable = [
        'day',
        'hour',
        'id_class',
        'id_user',
        'id_room',
    ];
}
